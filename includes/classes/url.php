<?php
/**
 * Class URL
 *
 * Retrieves user input url and stores it in different global vars.
 * Also accepts get parameters, accessible via $url->$get["name"]["param"]
 *
 * Maurice Mol
 * mauricemol@hotmail.nl
 *
 */

Class Url {
    private $page;
    private $category = array();
    private $subCat = array();
    private $product = array();
    private $get = array();
    private $vars_array = array();
    private $old_pages = array(
        //array("molenmessen-aanbiedingen-c-41.html", "Schilmessen/Set%20aanbiedingen%20carbonstaal"),
    );

    public function __construct() {
        $elements = $this->getURL();

        $count = count($elements);

        /*
        echo "<pre>";
        var_dump($elements);
        echo "</pre>";
        */
		
		$path = ltrim($_SERVER['REQUEST_URI'], '/');
         			
		if (strpos($path, '?') !== false) {
			$elements[$count] = $this->stripGet($path, $count);
		}

        if (empty($elements) || $count == 0) {
            return;
        }

        if (!$this->checkList($count, $elements) || $count > 3) {
            $path = ltrim($_SERVER['REQUEST_URI'], '/');
            $path = htmlspecialchars($path);

            /*
            for ($h = 0;$h < count($this->old_pages);$h++) {
                if ($path == $this->old_pages[$h][0]) {
                    header("Location: /" . $this->old_pages[$h][1], true, 301);
                    die();
                }
            }
            */

            include ("404.php");
            die();
        }
    }

    /**
     * checklist()
     * Checks if product, sub-cat, category and pages exist and are in the db
     *
     * @param int $count
     * @param array[string] $elements
     *
     * @return bool
     */
    private function checklist(int $count, $elements) : bool
    {
        global $db;

        $mainPages = [
            "index.php"            
        ];

        $db->query("SELECT name FROM pages");

        if (!empty($row = $db->resultset())) {
            for ($i = 0;$i < count($row);$i++) {
                $name = str_replace('%20', ' ', $row[$i]["name"]);
                //$name = $row[$i]["name"];

                array_push($mainPages, $name);
            }
        }

        if (in_array($elements[0], $mainPages) || in_array(str_replace('%20', ' ', $elements[0]), $mainPages)) {
            if ($count == 0 || $elements[0] == "") {
                $this->page = "index.php";
            } else {
                $page = str_replace('%20', ' ', $elements[0]);
                $this->page = $page;
            }
            return true;
        }

        if ($count >= 2) {
            if (!$this->catCheck($elements)) {
                return false;
            }

            $category = $this->getCat();

            if (!$this->subCheck($elements, $category["id"])) {
                return false;
            }

            $subCat = $this->getsubCat();

            if ($count == 3) {
                if ($this->productCheck($elements, $subCat["id"])) {
                    return true;
                }

                return false;
            } elseif ($count > 3) {
                return false;
            }

            return true;
        }
        /* else {
            if ($this->catCheck($elements)) {
                if ($count >= 2) {
                    $category = $this->getCat();

                    if ($this->subCheck($elements, $category["id"])) {
                        return true;
                    } else {
                        return false;
                    }
                }
                //return true;
            }
        }*/

        return false;
    }

    /**
     * getURL()
     * Retrieves server url
     *
     * @return array[string]
     */
    private function getURL() : array
    {
        if (isset($_SERVER['UNENCODED_URL']))
        {
            $path = ltrim($_SERVER['UNENCODED_URL'], '/');
        }
        else
        {
            $path = ltrim($_SERVER['REQUEST_URI'], '/');
        }

        $path = explode("?", $path)[0];

        $path = htmlspecialchars($path);
        $elements = explode('/', $path);

        for ($i = 0; $i < count($elements); $i++)
        {
            $elements[$i] = rawurldecode($elements[$i]);
        }

        return array_filter($elements);
    }

    /**
     * catCheck()
     * Checks if category exists in db
     *
     * @param array[string] $elements
     *
     * @return boolean
     */
    private function catCheck(array $elements) : bool
    {
        global $db;

        $db->query("SELECT id, name FROM category");
        $row = $db->resultset();

        foreach ($row as $index => $value) {
            if (in_array($value['name'], $elements)) {
                $this->category['name'] = $value['name'];
                $this->category['id'] = $value['id'];
                return true;
            }
        }
        return false;
    }

    /**
     * subCheck()
     * Checks if sub-category exists in db
     *
     * @param array[string] $elements
     * @param int $id
     *
     * @return bool
     */
    private function subCheck(array $elements, int $id) : bool
    {
        global $db;

        $db->query("SELECT s.id, s.name AS s_name, c.name AS c_name FROM subcategory AS s, category AS c WHERE parent_id = :id AND s.parent_id = c.id");
        $db->bind(':id', $id);
        $row = $db->resultset();

        /*
        echo "<pre>";
        var_dump($row);
        echo "</pre>";
        */

        for ($i = 0;$i < count($row);$i++) {
            //Check if cat and sub-cat are the same name
            if ($elements[0] === $elements[1]) {
                if ($elements[1] === $row[$i]['s_name']) {
                    $this->subCat['name'] = $row[$i]['s_name'];
                    $this->subCat['id'] = $row[$i]['id'];

                    return true;
                }
            } else {
                //Check if subcat actually belongs to the correct cat
                if ($row[$i]['c_name'] == $elements[0]) {
                    if (array_search($row[$i]['s_name'], $elements) != false) {
                        $this->subCat['name'] = $row[$i]['s_name'];
                        $this->subCat['id'] = $row[$i]['id'];

                        return true;
                    }
                }
            }
        }

        return false;
    }

    /**
     * productCheck()
     * Checks if product exists in db
     *
     * @param array[string] $elements
     * @param int $id
     *
     * @return bool
     */
    private function productCheck(array $elements, int $id) : bool
    {
        global $db;

        $db->query("SELECT id, name FROM product WHERE parent_id = :id || second_parent_id = :s_id");
        $db->bind(':id', $id);
        $db->bind(':s_id', $id);

        $row = $db->resultset();

        foreach ($row as $index => $value) {
            if (in_array($value['name'], $elements)) {
                $this->product['name'] = $value['name'];
                $this->product['id'] = $value['id'];
                return true;
            }
        }

        return false;
    }

    /**
     * stripGet()
     * Strips and stores all GET vars in $url->$get
     *
     * @param string $last
     * @param int $count
     *
     * @return string
     */
    private function stripGet(string $last, int $count) : string
    {
        $vars = explode("?", $last);
        $first = $vars[0];

        if ($count > 0) {
            $last = array_pop($vars);
        }

        if (strpos($last, '&') !== false) {
            //Multiple get vars
            $Gvars = explode("&", $last);

            foreach ($Gvars as $v) {
                $temp = explode("=", $v);
                $temp[0] = str_replace("&", " ", $temp[0]);
                $temp[0] = str_replace("amp;", " ", $temp[0]);

                if (empty($temp[0]) || empty($temp[1])) {
                    include ("404.php");
                    die();
                }

                $tempArray = array("name" => $temp[0], "param" => $temp[1]);
                array_push($this->vars_array, $tempArray);
            }

            $this->get = $this->vars_array;
        } else {
            //One get var
            $vars = explode("=", $last);
            $vars = array_filter($vars);

            if (count($vars) == 1 || count($vars) == 0) {
                include ("404.php");
                die();
            }

            $vars = array("0" => array("name" => $vars[0], "param" => $vars[1]));
            $this->get = $vars;
        }

        return $first;
    }

    /**
     * getCat()
     *
     * @return array[string]
     */
    public function getCat() : array
    {
        return $this->category;
    }

    /**
     * getsubCat()
     *
     * @return array[string]
     */
    public function getsubCat() : array
    {
        return $this->subCat;
    }

    /**
     * getProduct()
     *
     * @return array[string]
     */
    public function getProduct() : array
    {
        return $this->product;
    }

    /**
     * getPage()
     *
     * @return string
     */
    public function getPage() : string
    {
        return htmlspecialchars($this->page);
    }

    /**
    s     * getGET()
     *
     * @return array[string]
     */
    public function getGET() : array
    {
        return $this->get;
    }

}