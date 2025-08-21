<?php
date_default_timezone_set("Europe/Amsterdam");
setlocale(LC_ALL, 'nl_NL', 'Dutch', 'nl_utf8');

define("DB_HOST", "127.0.0.1");
define("DB_USER", "root");
define("DB_PASS", "");
define("DB_NAME", "db3749273_thuisbijdekat");

define("URL", "http://kattenoppasgroningen.local");

define("ERROR_LOG_PATH", "logs/");

ini_set('display_errors', 'off');
ini_set('display_startup_errors', 'off');
ini_set('log_errors', 'on');
error_reporting(E_ALL & ~E_DEPRECATED & ~E_STRICT);
