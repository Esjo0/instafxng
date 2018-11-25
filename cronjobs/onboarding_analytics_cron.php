<?php
set_include_path('/home/tboy9/public_html/init/');
require_once 'initialize_admin.php';

$current_year = date('Y');
$main_current_month = date('m');
$current_month = ltrim($main_current_month, '0');
$yesterday = date('Y-m-d', strtotime("-1 days"));
$today = date('Y-m-d');

$current_quarter = $obj_analytics->get_quarter_code($main_current_month);
$current_half_year = $obj_analytics->get_half_year_code($main_current_month);
$current_year_code = "1-12";

/**
* Generate Page Top Analytics for Month
                                  */
$my_dates = $obj_analytics->get_from_to_dates($current_year, $current_month);

$my_from_date = $my_dates['from_date'];
$my_to_date = $my_dates['to_date'];

$query = "SELECT u.user_code, MIN(tc.date_earned) AS date_earned
        FROM trading_commission AS tc
        INNER JOIN user_ifxaccount AS ui ON tc.ifx_acct_no = ui.ifx_acct_no
        INNER JOIN user AS u ON ui.user_code = u.user_code
        GROUP BY u.user_code
        HAVING date_earned BETWEEN '$my_from_date' AND '$my_to_date' ";
$month_total_onboard = $db_handle->numRows($query);

////////////////////////////////////////////////

/**
 * Generate Page Top Analytics for Quarter
 */
$my_dates = $obj_analytics->get_from_to_dates($current_year, $current_quarter);

$my_from_date = $my_dates['from_date'];
$my_to_date = $my_dates['to_date'];

$query = "SELECT u.user_code, MIN(tc.date_earned) AS date_earned
        FROM trading_commission AS tc
        INNER JOIN user_ifxaccount AS ui ON tc.ifx_acct_no = ui.ifx_acct_no
        INNER JOIN user AS u ON ui.user_code = u.user_code
        GROUP BY u.user_code
        HAVING date_earned BETWEEN '$my_from_date' AND '$my_to_date' ";
$quarter_total_onboard = $db_handle->numRows($query);

////////////////////////////////////////////////

/**
 * Generate Page Top Analytics for Half Year
 */
$my_dates = $obj_analytics->get_from_to_dates($current_year, $current_half_year);

$my_from_date = $my_dates['from_date'];
$my_to_date = $my_dates['to_date'];

$query = "SELECT u.user_code, MIN(tc.date_earned) AS date_earned
        FROM trading_commission AS tc
        INNER JOIN user_ifxaccount AS ui ON tc.ifx_acct_no = ui.ifx_acct_no
        INNER JOIN user AS u ON ui.user_code = u.user_code
        GROUP BY u.user_code
        HAVING date_earned BETWEEN '$my_from_date' AND '$my_to_date' ";
$half_year_total_onboard = $db_handle->numRows($query);

////////////////////////////////////////////////

/**
 * Generate Page Top Analytics for Month
 */
$my_dates = $obj_analytics->get_from_to_dates($current_year, $current_year_code);

$my_from_date = $my_dates['from_date'];
$my_to_date = $my_dates['to_date'];

$query = "SELECT u.user_code, MIN(tc.date_earned) AS date_earned
        FROM trading_commission AS tc
        INNER JOIN user_ifxaccount AS ui ON tc.ifx_acct_no = ui.ifx_acct_no
        INNER JOIN user AS u ON ui.user_code = u.user_code
        GROUP BY u.user_code
        HAVING date_earned BETWEEN '$my_from_date' AND '$my_to_date' ";
$year_total_onboard = $db_handle->numRows($query);

////////////////////////////////////////////////

$query = "SELECT u.user_code, MIN(tc.date_earned) AS date_earned
        FROM trading_commission AS tc
        INNER JOIN user_ifxaccount AS ui ON tc.ifx_acct_no = ui.ifx_acct_no
        INNER JOIN user AS u ON ui.user_code = u.user_code
        GROUP BY u.user_code
        HAVING date_earned = '$yesterday' ";
$onboard_yesterday = $db_handle->numRows($query);

$query = "SELECT * FROM onboarding_analytics WHERE date_today = '$today' LIMIT 1";
$result = $db_handle->runQuery($query);

if($db_handle->numOfRows($result) > 0) {
    // Run update query
    $query = "UPDATE onboarding_analytics 
        SET onboard_yesterday = $onboard_yesterday, 
        m_total_onboard = $month_total_onboard, 
        q_total_onboard = $quarter_total_onboard, 
        h_total_onboard = $half_year_total_onboard, 
        y_total_onboard = $year_total_onboard
        WHERE date_today = '$today' LIMIT 1";
    $db_handle->runQuery($query);
} else {
    // Run insert query
    $query = "INSERT INTO onboarding_analytics (date_today, onboard_yesterday, m_total_onboard, q_total_onboard, h_total_onboard, y_total_onboard) 
        VALUES ('$today', onboard_yesterday, m_total_onboard, q_total_onboard, h_total_onboard, y_total_onboard)";
    $db_handle->runQuery($query);
}

if($db_handle) { $db_handle->closeDB(); mysqli_close($db_handle); }