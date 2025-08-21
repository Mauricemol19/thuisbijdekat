<?php

require("includes/settings.php");
require("classes/database.php");
require("classes/session.php");
require("classes/core.php");
require("classes/url.php");

$core = new Core;
$db = new Database;
$session = new Session;
$url = new Url;

date_default_timezone_set("Europe/Amsterdam");

//Meta tags
$m_title = "";
$m_description = "";
$m_keywords = "";

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
<body>
<?php

