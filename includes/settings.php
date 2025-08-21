<?php
date_default_timezone_set("Europe/Amsterdam");
setlocale(LC_ALL, 'nl_NL', 'Dutch', 'nl_utf8');

define("DB_HOST", "127.0.0.1");
define("DB_USER", "root");
define("DB_PASS", "");
define("DB_NAME", "db343767_huushinne");

//define("MOLLIE_API_KEY", "live_adu6dnUDhBhNvjfPncB737vukp9u3B");
define("MOLLIE_API_KEY", "test_mQqRK7RbQpRarGSKWDE76PMk4apjRy");

define("MAILCHIMP_API_KEY", "2bc958991514698be9625573a18ff536-us3");

define("INSTAGRAM_API_KEY", "IGQVJXSWxvMnZArc1pPcnpNVms3QUI3NVdRMWxlV3VEY0d0WTl4aW1GYVMzNWJQTTF2SGNPQU40LWFBZAUNnclh3NkFfSmVUaXZAzYmRJbEh6RUVKUGdOVXNsVkxFMTFvMmY1eWpMSlV1MUpkc1ZAMOURrWAZDZD");

define("MAIL_JOB_TOKEN", "hh78239rh23f@hb3hdn70h2378dhnj2370hd782hb8fv8n23hf@sxlk3m3k902nmfds");

define("URL", "http://huushinne.local");
define("URL_MAIL", "http://huushinne.local");
define("URL_NAME", "huushinne.local");

define("ERROR_LOG_PATH", "logs/");

define("MAIL_DEBUG", "0");
define("MAIL_IS_SMTP", "true");
define("MAIL_HOST", "vps.transip.email");
define("MAIL_SMTP_AUTH", "1");
define("MAIL_USERNAME", "huushinne@vps.transip.email");
define("MAIL_PASSWORD", "wUCdqruijkqxsNAF");
define("MAIL_SMTPSECURE", "STARTTLS");
define("MAIL_PORT", "587");
define("MAIL_SENDER", "info@huushinne.nl");
define("MAIL_SENDER_NAME", "Huushinne.nl");

define("MAIL_IMAP_SERVER", "mail.mandenmand.nl");
define("MAIL_IMAP_USER", "info@huushinne.nl");
define("MAIL_IMAP_PASS", "pvCvAwmjtjdArlAFVBum");

ini_set('display_errors', 'off');
ini_set('display_startup_errors', 'off');
ini_set('log_errors', 'on');
error_reporting(E_ALL & ~E_DEPRECATED & ~E_STRICT);
