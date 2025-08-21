<?php
/**
 * Class Core
 *
 * Maurice Mol
 * mauricemol@hotmail.nl
 *
 */

class Core 
{	
	private string $registerError;

    /**
     * get_barcode()
     *
     * @return int
     *
     * @throws Exception
     */
    function get_barcode() : int
    {
        global $db;

        while (true)
        {
            $barcode = random_int(1000, 9999);

            $db->query("SELECT barcode FROM winkel_barcode WHERE barcode = :barcode");
            $db->bind(":barcode", $barcode);

            if (empty($db->single())) {
                return $barcode;
            }
        }
    }

    /**
     * input_validation()
     * Validates input from register() function via regular expression
     *
     * @param string $fname
     * @param string $lname
     * @param string $email
     * @param string $street
     * @param string $postal
     * @param string $city
     * @param string $country
     *
     * @return boolean
     */
    function input_validation($fname, $lname, $email, $street, $postal, $city, $country) {
        /*
        if (preg_match("/^[a-z0-9._%+-]+@[a-z0-9.-]+\.[a-z]{2,}$/", $email) != true) {
            return false;
        }

        if (preg_match("/^(.+)\s(\S+)$/", $street) != true) {
            return false;
        }

        if (preg_match("/^[a-zA-Z]+*\s$/", $fname) != true) {
            return false;
        }
        */

        /*
        if (preg_match("/^[a-zA-Z]+$/", $lname) != true) {
            return false;
        }
        */

        if (preg_match("/^[a-zA-Z- ]+$/", $city) != true) {
            return false;
        }

        return true;
    }

    /**
     * checkEmail()
     * Check if email doesn't already exist
     *
     * @param string $email
     *
     * @return boolean
     */
    function checkEmail($email) {
        global $db;

        $db->query("SELECT email FROM customer");
        $row = $db->resultset();

        if (!empty(array_filter($row))) {
            for ($i = 0; $i < count($row); $i++) {
                if ($row[$i]["email"] === $email) {
                    return false;
                }
            }
        }
        return true;
    }

    /**
     * login()
     * Process login
     *
     * @param string $email
     * @param string $pass
     *
     * @return boolean
     */
    function login($email, $pass) {
        global $db;

        if (empty($email) && empty($pass)) {
            return false;
        }

        if (!preg_match("/^[a-z0-9._%+-]+@[a-z0-9.-]+\.[a-z]{2,}$/", $email) && !preg_match('^(?=.*\d)(?=.*[a-z])(?=.*[A-Z])(?!.*\s).{6,13}$', $pass)) {
            return false;
        }

        $db->query("SELECT id, password_hash, attempts FROM customer WHERE email = :email AND password_hash != ''");
        $db->bind(":email", $email);
        $row = $db->single();

        if (empty($row)) {
            return false;
        }

        $id = $row["id"];
        $pass_hash = $row["password_hash"];
        $attempts = $row["attempts"];

        if (empty($id)) {
            return false;
        }

        if ($attempts >= 80) {
            return false;
        }

        if (!password_verify($pass, $pass_hash)) {
            return false;
        }

        $_SESSION["id"] = $row["id"];

        return true;
    }

    /**
     * register()
     * Process register and input validation progress
     *
     * @return boolean
     */
	function register() {
	    global $db;

        if (isset($_POST["fname"]) && isset($_POST["lname"]) && isset($_POST["email"]) && isset($_POST["street"])
            && isset($_POST["postal"]) && isset($_POST["city"]) && isset($_POST["country"])
            && isset($_POST["pass1"]) && isset($_POST["pass2"])) {
            $fname = htmlspecialchars($_POST["fname"]);
            $lname = htmlspecialchars($_POST["lname"]);
            $email = htmlspecialchars($_POST["email"]);
            $street = htmlspecialchars($_POST["street"]);
            $postal = htmlspecialchars($_POST["postal"]);
            $city = htmlspecialchars($_POST["city"]);
            $country = htmlspecialchars($_POST["country"]);
            $pass1 = htmlspecialchars($_POST["pass1"]);
            $pass2 = htmlspecialchars($_POST["pass2"]);

            if (!empty($fname) && !empty($lname) && !empty($email) && !empty($street) && !empty($postal)
                && !empty($city) && !empty($country) && !empty($pass1) && !empty($pass2)) {

                //Check all input with regular expression
                if (!$this->input_validation($fname, $lname, $email, $street, $postal, $city, $country)) {
					$this->registerError = "Een of meer velden zijn niet goed ingevuld, probeer het alstublieft opnieuw.";
					$this->er_log("Input validation failed");
                    return false;
                }

                $db->query("SELECT id FROM customer WHERE email = :email AND password_hash IS NOT NULL");
                $db->bind(":email", $email);

                if (!empty($row = $db->single())) {
					$this->registerError = "Dit email adres is al in gebruik, probeer het alstublieft opnieuw.";
					$this->er_log("Email already in use");
                    return false;
                }

                if ($pass1 == $pass2) {
                    if (preg_match('/^(?=.*\d)(?=.*[a-z])(?=.*[A-Z])(?!.*\s).{6,13}$/', $pass1) == false) {
						$this->registerError = "Uw wachtwoord voldoet niet aan de vereiste indeling, probeer het alstublieft opnieuw.";
						$this->er_log("Password does not meet format");
                        return false;
                    }
                } else {
					$this->registerError = "De wachtwoorden komen niet overeen, probeer het alstublieft opnieuw.";
					$this->er_log("Password does not meet format");
                    return false;
                }

                if (strlen($fname) <= 34 && strlen($lname) <= 34 && strlen($email) <= 50 && strlen($street) <= 50
                    && strlen($postal) <= 7 && strlen($city) <= 24 && strlen($country) <= 9 && strlen($pass1) <= 24
                ) {
                    $db->query('INSERT INTO adres (street, postal_code, city, country) VALUES (:street, :postal, :city, :country)');
                    $db->bind(':street', $street);
                    $db->bind(':postal', $postal);
                    $db->bind(':city', $city);
                    $db->bind(':country', $country);
                    $db->execute();
                    $id = $db->lastInsertId();

                    if (!empty($id)) {
                        $hash = password_hash($pass1, PASSWORD_DEFAULT);
                        $db->query('INSERT INTO customer (address_id, fname, lname, email, password_hash, verified, verify_code) VALUES (:ad_id, :fname, :lname, :email, :pass, 0, NULL)');
                        $db->bind(':ad_id', $id);
                        $db->bind(':fname', $fname);
                        $db->bind(':lname', $lname);
                        $db->bind(':email', $email);
                        $db->bind(':pass', $hash);
                        $db->execute();

                        if (!empty($id = $db->lastInsertId())) {
                            $_SESSION["id"] = $id;
                            return true;
                        } else {
							$this->registerError = "Er is iets foutgegaan, probeer het alstublieft opnieuw.";
							$this->er_log("Unable to retrieve customer lastInsertId");
						}
                    } else {
						$this->registerError = "Er is iets foutgegaan, probeer het alstublieft opnieuw.";
						$this->er_log("Unable to retrieve adres lastInsertId");
					}
                } else {			
					$this->registerError = "Een of meer velden zijn niet of niet goed ingevuld, probeer het alstublieft opnieuw.";
					$this->er_log("One or more vars failed length check");
				}
            } else {
				$this->registerError = "Een of meer velden zijn niet of niet goed ingevuld, probeer het alstublieft opnieuw.";
				$this->er_log("One or more vars empty");
			}
        } else {
			$this->registerError = "Een of meer velden zijn niet of niet goed ingevuld, probeer het alstublieft opnieuw.";
			$this->er_log("One or more vars not set");
		}
        return false;
    }

    /**
     *
     * request()
     *
     * @param string url
     *
     * @return string
     */
    function request($url) {
        $ch = curl_init();

        curl_setopt($ch, CURLOPT_URL, $url);

        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);

        $output = curl_exec($ch);

        if (curl_errno($ch)) {
            $this->er_log("Unable to send request: " . curl_error($ch));
        } else {
            $resultStatus = curl_getinfo($ch, CURLINFO_HTTP_CODE);
            if ($resultStatus == 200) {
                curl_close($ch);

                return $output;
            } else {
                $this->er_log("Curl request failed: HTTP status code: " . $resultStatus . " Url: " . $url);
            }
        }

        curl_close($ch);

        return null;
    }
	
	/**
	 *
	 * clear_er_register()
	 *
	 */
	 function clear_er_register() {
		 $this->registerError = "";
	 }
	 
	/**
	 *
	 * get_er_register()
	 *
	 * @return string
	 */
	 function get_er_register() {
		 return $this->registerError;
	 }

    /**
     * idExists()
     * Check if the id that was sent via post/get actually exists in the current cookie/session
     *
     * @param int $id
     * @param array[int][string] $quantity
     *
     * @return array[int][string]
     */
    function idExists($id, $quantity)
    {
		for ($i = 0;$i < count($quantity);$i++) {
			if ($quantity[$i]["id"] == $id) {
				$quantity[$i]["amount"] = $quantity[$i]["amount"] + 1;
				return $quantity;
			}
		}
		$quantity[$i]["id"] = $id;
		$quantity[$i]["amount"] = 1;

		return $quantity;
	}

    /**
     * pretty_print_r
     *
     * @param array $element
     *
     */
    function pretty_print_r($element) {
        echo "<pre>" . print_r($element) . "</pre>";
    }

    /**
     * translate_status
     *
     * @param string $status
     *
     * @return string
     */
    function translate_status($status) {
        switch ($status) {
            case "paid" :
                $result = "Betaald";
                break;
            case "failed" :
                $result = "Mislukt";
                break;
            case "expired" :
                $result = "Verlopen";
               break;
            case "pauze" :
                $result = "In behandeling";
               break;
            case "canceled" :
                $result = "Geannuleerd";
                break;
            case "send" :
                $result = "Verzonden";
                break;
            case "send-tt" :
                $result = "Verzonden met Track & Trace";
                break;
            case "open" :
                $result = "Open";
                break;
            case "created" :
                $result = "Wachten op credit check";
                break;
            case "authorized" :
                $result = "Klaar om te verzenden";
                break;
            case "completed" :
                $result = "Afgerond";
                break;
            default :
                $result = "";
                break;
        }

        return $result;
    }

    /**
     * method_friendly
     *
     * @param string $method
     *
     * @return string
     */
    function method_friendly(string $method): string
    {
        switch ($method) {
            case "ideal" :
                $result = "Ideal";
                break;
            case "creditcard" :
                $result = "Credit card";
                break;
            case "bancontact" :
                $result = "Bancontact";
                break;
            case "kbc" :
                $result = "KBC/CBC";
                break;
            case "klarnapaylater" :
                $result = "Klarna After Pay";
                break;
            default :
                $result = "";
                break;
        }

        return $result;
    }

    /**
     * breadcrumbs()
     *
     * Determine location based on cat, subcat and product and then echo the contents as a clickable link.
     *
     */
    function breadcrumbs() {
	    global $url;

        $cat = $url->getCat();
        $subCat = $url->getsubCat();
        $product = $url->getProduct();

        echo "<div id='breadcrumbs'>";

        if (!empty($product) && !empty($subCat) && !empty($cat)) {
            echo  ucfirst($cat["name"]) . " / <a href='/" . $cat["name"] . "/" . $subCat["name"] . "' style='color: #877364;'>" . ucfirst($subCat["name"]) . "</a> / <a href='/" . $cat["name"] . "/" . $subCat["name"] . "/" . $product["name"] . "' style='color: #877364;'>" . ucfirst($product["name"]) . "</a>";
        } elseif (!empty($cat) && !empty($subCat) && empty($product)) {
            echo ucfirst($cat["name"]) . " / <a href='/" . $cat["name"] . "/" . $subCat["name"] . "' style='color: #877364;'>" . ucfirst($subCat["name"]) . "</a>";
        } elseif(!empty($cat) && empty($subCat) && empty($product)) {
            echo ucfirst($cat["name"]);
        }

        echo "</div>";
    }

    /**
     * writeMenu()
     *
     * Retrieve from db and write menu
     *
     */
    function writeMenu() {
	    global $db;

        $db->query("SELECT id, name FROM category ORDER BY sort_order");

        $cat_row = $db->resultset();

        $x = 0;

        foreach ($cat_row as $cat) {
            $db->query("SELECT id, name FROM subcategory WHERE parent_id = :id ORDER BY sort_order");
            $db->bind(':id', $cat["id"]);
            $row = $db->resultset();

            if (!empty($row)) {
                echo "<li class='dropdown noflex'><a class='menuJS' style='padding-left: 0;'>"
                    . strtoupper($cat["name"]) . "<b class='caret nocaret'></b></a><ul class='dropdown-menu'>";
                foreach ($row as $sub) {
                    $db->query("SELECT id FROM product WHERE parent_id = :p_id OR second_parent_id = :s_p_id GROUP BY id");
                    $db->bind(":p_id", $sub["id"]);
                    $db->bind(":s_p_id", $sub["id"]);

                    if (!empty($p_row = $db->resultset())) {
                        echo "<li><a href='/" . str_replace(' ', '%20', $cat["name"]) . "/" . str_replace(' ', '%20', $sub["name"]) . "'>" . ucfirst($sub['name']) . "</a></li>";
                    }
                }
                echo "</ul></li>";
            } else {
                echo "<li class='main_menu_li'>" . strtoupper($cat['name']) . "</li>";
            }

            $x++;
        }
    }
	
	/**
	 *er_log()
	 *
	 */
	function er_log($message) {
		global $url;
		
		$path = ERROR_LOG_PATH;
		$message = $url->getPage() . "| [" . date("Y-m-d H:i:s") . "] " . URL . " : " . $message . "\n";
		
		error_log($message, 3, $path . "error_log-" . URL_NAME . ".log");
	}

    /**
     * errorLogin()
     *
     * @param string $email
     *
     * @return string
     */
    function errorLogin($email) {
        global $db;

        $db->query("SELECT attempts FROM customer WHERE email = :email AND password_hash IS NOT NULL");
        $db->bind(":email", $email);

        if (!empty($row = $db->single())) {
            if ($row["attempts"] >= 80) {
                return "<b>Dit account is geblokkeerd, neem alstublieft contact op met onze klantenservice.</b>";
            } else {
                 return "<b>De gebruikersnaam/wachtwoord combinatie wordt niet herkend.</b>";
            }
        }

        return "<b>De gebruikersnaam/wachtwoord combinatie wordt niet herkend.</b>";
    }

    /**
     * error404()
     *
     */
	function error404() {
		include ("404.php");
		exit;
	}
}

?>