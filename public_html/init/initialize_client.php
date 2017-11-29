<?php
// Development settings
defined('DS') ? NULL : define('DS', DIRECTORY_SEPARATOR);
defined('SITE_ROOT') ? NULL : define('SITE_ROOT', 'C:'.DS.'wamp64'.DS.'www'.DS.'instafxngwebsite');
defined('LIB_PATH') ? NULL : define('LIB_PATH', SITE_ROOT.DS.'app_assets');

//// Production settings
//defined('DS') ? NULL : define('DS', DIRECTORY_SEPARATOR);
//defined('SITE_ROOT') ? NULL : define('SITE_ROOT', DS.'home'.DS.'tboy9');
//defined('LIB_PATH') ? NULL : define('LIB_PATH', SITE_ROOT.DS.'app_assets');

// Load config files first
require_once(LIB_PATH.DS."config.php");

// Load basic functions so that everything after can use them
require_once(LIB_PATH.DS."malek_func_library_1.0.0.php");
require_once(LIB_PATH.DS."functions.php");

// Load core objects
require_once(LIB_PATH.DS."session_client.php");
require_once(LIB_PATH.DS."class_database.php");

// Load other assets
require_once(LIB_PATH.DS.'PHPMailer'.DS."PHPMailerAutoload.php");
require_once(LIB_PATH.DS."class_system.php");
require_once(LIB_PATH.DS."class_client_operation.php");
require_once(LIB_PATH.DS."class_education.php");
require_once(LIB_PATH.DS."class_questions.php");
require_once(LIB_PATH.DS."config_sys.php");
require_once(LIB_PATH.DS."class_reminders.php");
require_once(LIB_PATH.DS."class_customer_care_log.php");