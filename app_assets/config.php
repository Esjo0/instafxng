<?php
// Database Constants
// defined('DB_SERVER') ? null : define("DB_SERVER", "localhost");
// defined('DB_USER')   ? null : define("DB_USER", "tboy9_acess");
// defined('DB_PASS')   ? null : define("DB_PASS", "hello_222A");

// defined('DB_NAME')   ? null : define("DB_NAME", "tboy9_maindb");

defined('DB_SERVER') ? null : define("DB_SERVER", "localhost");
defined('DB_USER')   ? null : define("DB_USER", "root");
defined('DB_PASS')   ? null : define("DB_PASS", "");

defined('DB_NAME')   ? null : define("DB_NAME", "tboy9_turbo");

defined('ADMIN_EMAIL')        ? null : define("ADMIN_EMAIL", "support@instafxng.com");
defined('ADMIN_NAME')         ? null : define("ADMIN_NAME", "Instafxng Support");
defined('ADMIN_NAME_EMAIL')   ? null : define("ADMIN_NAME_EMAIL", "Instafxng Support <support@instafxng.com>");

$timezone = "Africa/Lagos";
date_default_timezone_set($timezone);

// The below key was used at the front end for the pass code email confirmation
defined('KEY') ? null : define('KEY', '1234567890123456');