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
require_once(LIB_PATH.DS."session_careers.php");
require_once(LIB_PATH.DS."session_client.php");
require_once(LIB_PATH.DS."class_database.php");
require_once(LIB_PATH.DS."class_system.php");

// Load other assets
require_once(LIB_PATH.DS."vendor".DS."autoload.php");
require_once(LIB_PATH.DS."class_paystack.php");
require_once(LIB_PATH.DS."class_client_operation.php");
require_once(LIB_PATH.DS."config_sys.php");
require_once(LIB_PATH.DS."class_careers.php");
require_once(LIB_PATH.DS."class_project_management.php");
require_once(LIB_PATH.DS."class_quiz.php");
require_once(LIB_PATH.DS."session_quiz_participant.php");
require_once(LIB_PATH.DS."class_loyalty_point.php");
require_once(LIB_PATH.DS."class_push_notification.php");
//Loyalty/Training Campaign Controller
require_once(LIB_PATH.DS."class_loyalty_training_campaign.php");
require_once(LIB_PATH.DS."class_bonus_operations.php");
require_once(LIB_PATH.DS."class_bonus_conditions.php");
require_once(LIB_PATH.DS."class_signal_management.php");

/*
 * TRACK CLIENTS THAT PARTNERS REFER
 */
$this_my_page = $_SERVER['REQUEST_URI'];
$my_new_page = strtok($this_my_page, '?');

$get_params = allowed_get_params(['q']);
if(isset($get_params) && !IS_NULL($get_params)) {
    $partner_refer_code = $get_params['q'];
}

if(isset($partner_refer_code)) {
    $query = "SELECT partner_code FROM partner WHERE partner_code = '$partner_refer_code' LIMIT 1";
    if($db_handle->numRows($query) > 0) {

        $cookie_name = "instafxng_partner_ref";
        $cookie_value = $partner_refer_code;
        $cookie_expire = time() + (86400 * PARTNER_COOKIE_EXPIRE);

        $instafxng_partner = $_COOKIE["instafxng_partner_ref"];

        if(isset($_COOKIE["instafxng_partner_ref"])) {
            setcookie ($instafxng_partner, "", time() - 3600);
        }

        setcookie($cookie_name, $cookie_value, $cookie_expire, "/");
        redirect_to($my_new_page);
    } else {
        redirect_to($my_new_page);
    }
}

if(isset($_COOKIE["instafxng_partner_ref"])) {
    $instafxng_partner = $_COOKIE["instafxng_partner_ref"];
    $query = "SELECT partner_code FROM partner WHERE partner_code = '$instafxng_partner' LIMIT 1";
    if($db_handle->numRows($query) > 0) {
        $my_refferer = $instafxng_partner;
    }
}