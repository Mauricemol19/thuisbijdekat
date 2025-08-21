<?php

require("includes/settings.php");

require("classes/database.php");
require("classes/session.php");
require("classes/core.php");
require("classes/url.php");
require("phpmailer/phpmailer/src/PHPMailer.php");
require("phpmailer/phpmailer/src/SMTP.php");
require("phpmailer/phpmailer/src/Exception.php");
require("classes/mail_handler.php");

$core = new Core;
$db = new Database;
$session = new Session;


$url = new Url;
$email_h = new mail_handler;

$get = $url->getGET();

date_default_timezone_set("Europe/Amsterdam");

//Meta create
if (!empty($url->getCat())) {
    if (!empty($url->getSubCat())) {
        if (!empty($url->getProduct())) {
            //Product page
            $productArray = $url->getProduct();
            $db->query("SELECT product.id, name, meta_d, meta_k, brand, material, blade FROM product, properties WHERE properties.id = product.properties_id AND product.id = :id");
            $db->bind(":id", $productArray["id"]);
            $m_row = $db->single();

            $m_id = $m_row["id"];
            $m_name = htmlspecialchars($m_row["name"]);
            $m_brand = htmlspecialchars($m_row["brand"]);
            $m_material = htmlspecialchars($m_row["material"]);
            $m_blade = htmlspecialchars($m_row["blade"]);

            $m_description = htmlspecialchars($m_row["meta_d"]);

            $m_title = $m_name . " - Huushinne.nl";
            $m_keywords = strtolower(htmlspecialchars($m_row["meta_k"]));
        } else {
            //Sub cat page
            $cat = $url->getCat();
            $subCat = $url->getsubCat();

            $db->query("SELECT name, description, m_k FROM subcategory WHERE id = :id");
            $db->bind(":id", $subCat["id"]);
            $row = $db->single();

            $m_title = htmlspecialchars($row["name"]) . " - Huushinne.nl";
            $m_description = htmlspecialchars($row["description"]);
            $m_keywords = htmlspecialchars($row["m_k"]);
        }
    } else {
        //Cat page
        //require("category.php");
		
		$cat = $url->getCat();
		
		$db->query("SELECT id, name, description, meta_k FROM category WHERE id = :id");
		$db->bind(":id", $cat["id"]);
		
		if (!empty($c_row_meta = $db->single())) {
			$m_title = htmlspecialchars($c_row_meta["name"]) . " - Huushinne.nl";
			$m_description = htmlspecialchars($c_row_meta["description"]);
			$m_keywords = htmlspecialchars($c_row_meta["meta_k"]);
		}
    }
} elseif (!empty($url->getPage()) && $url->getPage() == "Blog") {
    //Blog
    //require("includes/blog.php");
	
	if (isset($get[0]["param"])) {
		$m_title = str_replace("%20", " ", $get[0]["param"]);
		$m_description = $m_title;
		$m_keywords = "";
	} else {
		$m_title = "Blog";
		$m_description = "";
		$m_keywords = "";
	}
} elseif (!empty($url->getPage()) && $url->getPage() != "index.php") {
    //Pages
    //require("includes/pages.php");
    $m_page = $url->getPage();

    if ($url->getPage() == "winkelmand.php") {
        $m_title = "Winkelmand - Huushinne.nl";
        $m_description = "";
        $m_keywords = "";
	} elseif ($url->getPage() == "account.php") {
        $m_title = "Mijn Account - Huushinne.nl";
        $m_description = "";
        $m_keywords = "";
	} elseif ($url->getPage() == "register.php") {
        $m_title = "Registreren - Huushinne.nl";
        $m_description = "";
        $m_keywords = "";
    } else {
        $m_title = $url->getPage();
        $m_description = "";
        $m_keywords = "";
    }
} else {
    //Homepage
	//TODO: meta tags
    $m_title = "- Huushinne.nl";
    $m_description = "Scandinavische lifestylestore, wonen en fashion. Op dinsdag t/m zaterdag voor 17:00 besteld, nog dezelfde dag verzonden.";
    $m_keywords = "Scandinavisch, lifestyle, lifestylestore, emmen, HKLiving, wonen, fashion, kleding, meubels, lampen, Mijn Stijl, Herdermessen";
}

if ($url->getPage() != "account.php" && isset($get[0]["name"])) {
    if ($get[0]["name"] != "logout") {
        ?>
        <!DOCTYPE html>
        <html lang="nl">
        <head>
            <meta charset="UTF-8">
            <meta http-equiv="X-UA-Compatible" content="IE=edge">
            <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1, user-scalable=yes">

            <link rel="icon" href="/src/images/huushinne_logo.jpg" type="image/png"/>
            <link rel="shortcut icon" href="/src/images/huushinne_logo.jpg" type="image/png"/>

            <meta name="description" content='<?php echo $m_description; ?>'>
            <meta name="keywords" content='<?php echo $m_keywords; ?>'>
            <meta name="robots" content="index, follow">

            <title><?php echo $m_title; ?></title>

            <link href="/includes/css/bootstrap.min.css" rel="stylesheet">
            <link href="/includes/css/style.css" rel="stylesheet">

            <style>
                @import url('https://fonts.googleapis.com/css2?family=Montserrat:wght@300&display=swap');
            </style>
        </head>
		<!-- Google tag (gtag.js) -->
		<script async src="https://www.googletagmanager.com/gtag/js?id=G-Z55H4K8Y5M"></script>
		<script>
		  window.dataLayer = window.dataLayer || [];
		  function gtag(){dataLayer.push(arguments);}
		  gtag('js', new Date());

		  gtag('config', 'G-Z55H4K8Y5M');
		</script>
        <body>
        <?php
    }
} elseif (isset($_POST["delete_submit"]) == false) {
    if (isset($get[0]["name"])) {
        if ($get[0]["name"] != "logout") {
            ?>
            <!DOCTYPE html>
            <html lang="nl">
            <head>
                <meta charset="UTF-8">
                <meta http-equiv="X-UA-Compatible" content="IE=edge">
                <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1, user-scalable=yes">

                <meta name="description" content='<?php echo $m_description; ?>'>
                <meta name="keywords" content='<?php echo $m_keywords; ?>'>
                <meta name="robots" content="index, follow">

                <title><?php echo $m_title; ?></title>

                <link rel="icon" href="/src/images/huushinne_logo.jpg" type="image/png"/>
                <link rel="shortcut icon" href="/src/images/huushinne_logo.jpg" type="image/png"/>

                <link href="/includes/css/bootstrap.min.css" rel="stylesheet">
                <link href="/includes/css/style.css" rel="stylesheet">

                <style>
                    @import url('https://fonts.googleapis.com/css2?family=Montserrat:wght@300&display=swap');
                </style>
            </head>
			<!-- Google tag (gtag.js) -->
			<script async src="https://www.googletagmanager.com/gtag/js?id=G-Z55H4K8Y5M"></script>
			<script>
			  window.dataLayer = window.dataLayer || [];
			  function gtag(){dataLayer.push(arguments);}
			  gtag('js', new Date());

			  gtag('config', 'G-Z55H4K8Y5M');
			</script>
            <body>
            <?php
        }
    } else { ?>
        <!DOCTYPE html>
        <html lang="nl">
        <head>
            <meta charset="UTF-8">
            <meta http-equiv="X-UA-Compatible" content="IE=edge">
            <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1, user-scalable=yes">

            <meta name="description" content='<?php echo $m_description; ?>'>
            <meta name="keywords" content='<?php echo $m_keywords; ?>'>
            <meta name="robots" content="index, follow">

            <title><?php echo $m_title; ?></title>

            <link rel="icon" href="/src/images/huushinne_logo.jpg" type="image/png"/>
            <link rel="shortcut icon" href="/src/images/huushinne_logo.jpg" type="image/png"/>

            <link href="/includes/css/bootstrap.min.css" rel="stylesheet">
            <link href="/includes/css/style.css" rel="stylesheet">
            <link href="https://cdn.jsdelivr.net/gh/StephanWagner/jBox@v1.0.2/dist/jBox.all.min.css" rel="stylesheet">

            <style>
                @import url('https://fonts.googleapis.com/css2?family=Montserrat:wght@300&display=swap');
            </style>
        </head>
		<!-- Google tag (gtag.js) -->
		<script async src="https://www.googletagmanager.com/gtag/js?id=G-Z55H4K8Y5M"></script>
		<script>
		  window.dataLayer = window.dataLayer || [];
		  function gtag(){dataLayer.push(arguments);}
		  gtag('js', new Date());

		  gtag('config', 'G-Z55H4K8Y5M');
		</script>
        <body>
        <?php
    }
}
