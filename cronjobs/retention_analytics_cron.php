<?php
set_include_path('/home/tboy9/public_html/init/');
require_once 'initialize_admin.php';

$current_year = date('Y');
$main_current_month = date('m');
$current_month = ltrim($main_current_month, '0');
$yesterday = date('Y-m-d', strtotime("-1 days"));
$today = date('Y-m-d');

/// Determine the quarter that the current month belongs to
if ($main_current_month >= 1 && $main_current_month <= 3) {
    $current_quarter = '1-3';
} else if ($main_current_month >= 4 && $main_current_month <= 6) {
    $current_quarter = '4-6';
} else if ($main_current_month >= 7 && $main_current_month <= 9) {
    $current_quarter = '7-9';
} else if ($main_current_month >= 10 && $main_current_month <= 12) {
    $current_quarter = '10-12';
}

/**
 * Generate Page Top Analytics for Month
 */
$my_dates = $obj_analytics->get_from_to_dates($current_year, $current_month);

$my_prev_from_date = $my_dates['prev_from_date'];
$my_prev_to_date = $my_dates['prev_to_date'];
$my_from_date = $my_dates['from_date'];
$my_to_date = $my_dates['to_date'];

$clients_to_retain_query = "SELECT u.email
    FROM trading_commission AS td
    INNER JOIN user_ifxaccount AS ui ON td.ifx_acct_no = ui.ifx_acct_no
    INNER JOIN user AS u ON ui.user_code = u.user_code
    WHERE date_earned BETWEEN '$my_prev_from_date' AND '$my_prev_to_date' GROUP BY u.email";
$month_clients_to_retain = $db_handle->numRows($clients_to_retain_query);

$base_query = "SELECT SUM(td.volume) AS sum_volume, SUM(td.commission) AS sum_commission, u.user_code, CONCAT(u.last_name, SPACE(1), u.first_name) AS full_name,
    u.email, u.phone, u.created, MIN(td.date_earned) AS first_trade, MAX(td.date_earned) AS last_trade
    FROM trading_commission AS td
    INNER JOIN user_ifxaccount AS ui ON td.ifx_acct_no = ui.ifx_acct_no
    INNER JOIN user AS u ON ui.user_code = u.user_code
    WHERE date_earned BETWEEN '$my_prev_from_date' AND '$my_prev_to_date' GROUP BY u.email ORDER BY sum_commission DESC ";

$db_handle->runQuery("CREATE TEMPORARY TABLE my_reference_clients AS " . $base_query);

$base_query2 = "SELECT SUM(td.volume) AS sum_volume, SUM(td.commission) AS sum_commission, u.user_code, CONCAT(u.last_name, SPACE(1), u.first_name) AS full_name,
    u.email, u.phone, u.created, MIN(td.date_earned) AS first_trade, MAX(td.date_earned) AS last_trade
    FROM trading_commission AS td
    INNER JOIN user_ifxaccount AS ui ON td.ifx_acct_no = ui.ifx_acct_no
    INNER JOIN user AS u ON ui.user_code = u.user_code
    WHERE date_earned BETWEEN '$my_from_date' AND '$my_to_date' GROUP BY u.email ORDER BY sum_commission DESC ";

$db_handle->runQuery("CREATE TEMPORARY TABLE my_reference_clients_2 AS " . $base_query2);

$query = "SELECT rc2.sum_volume, rc2.sum_commission, rc2.user_code, rc2.full_name, rc2.email, rc2.phone, rc2.created, rc2.first_trade, rc2.last_trade
    FROM my_reference_clients AS rc1
    LEFT JOIN my_reference_clients_2 AS rc2 ON rc1.user_code = rc2.user_code
    WHERE rc2.user_code IS NOT NULL ";

$monthly_clients_retained = $db_handle->numRows($query);

$the_query = "SELECT first_trade FROM my_reference_clients_2 WHERE first_trade = '$yesterday'";
$monthly_clients_retained_yesterday = $db_handle->numRows($the_query);

////////////////////////////////////////////////

/**
 * Generate Page Top Analytics for current quarter
 */
$my_dates = $obj_analytics->get_from_to_dates($current_year, $current_quarter);

$my_prev_from_date = $my_dates['prev_from_date'];
$my_prev_to_date = $my_dates['prev_to_date'];
$my_from_date = $my_dates['from_date'];
$my_to_date = $my_dates['to_date'];

$clients_to_retain_query = "SELECT u.email
    FROM trading_commission AS td
    INNER JOIN user_ifxaccount AS ui ON td.ifx_acct_no = ui.ifx_acct_no
    INNER JOIN user AS u ON ui.user_code = u.user_code
    WHERE date_earned BETWEEN '$my_prev_from_date' AND '$my_prev_to_date' GROUP BY u.email";
$quarter_clients_to_retain = $db_handle->numRows($clients_to_retain_query);

$base_query = "SELECT SUM(td.volume) AS sum_volume, SUM(td.commission) AS sum_commission, u.user_code, CONCAT(u.last_name, SPACE(1), u.first_name) AS full_name,
    u.email, u.phone, u.created, MIN(td.date_earned) AS first_trade, MAX(td.date_earned) AS last_trade
    FROM trading_commission AS td
    INNER JOIN user_ifxaccount AS ui ON td.ifx_acct_no = ui.ifx_acct_no
    INNER JOIN user AS u ON ui.user_code = u.user_code
    WHERE date_earned BETWEEN '$my_prev_from_date' AND '$my_prev_to_date' GROUP BY u.email ORDER BY sum_commission DESC ";

$db_handle->runQuery("CREATE TEMPORARY TABLE my_reference_clients AS " . $base_query);

$base_query2 = "SELECT SUM(td.volume) AS sum_volume, SUM(td.commission) AS sum_commission, u.user_code, CONCAT(u.last_name, SPACE(1), u.first_name) AS full_name,
    u.email, u.phone, u.created, MIN(td.date_earned) AS first_trade, MAX(td.date_earned) AS last_trade
    FROM trading_commission AS td
    INNER JOIN user_ifxaccount AS ui ON td.ifx_acct_no = ui.ifx_acct_no
    INNER JOIN user AS u ON ui.user_code = u.user_code
    WHERE date_earned BETWEEN '$my_from_date' AND '$my_to_date' GROUP BY u.email ORDER BY sum_commission DESC ";

$db_handle->runQuery("CREATE TEMPORARY TABLE my_reference_clients_2 AS " . $base_query2);

$query = "SELECT rc2.sum_volume, rc2.sum_commission, rc2.user_code, rc2.full_name, rc2.email, rc2.phone, rc2.created, rc2.first_trade, rc2.last_trade
    FROM my_reference_clients AS rc1
    LEFT JOIN my_reference_clients_2 AS rc2 ON rc1.user_code = rc2.user_code
    WHERE rc2.user_code IS NOT NULL ";

$quarter_clients_retained = $db_handle->numRows($query);

$the_query = "SELECT first_trade FROM my_reference_clients_2 WHERE first_trade = '$yesterday'";
$quarter_clients_retained_yesterday = $db_handle->numRows($the_query);

////////////////////////////////////////////////

$query = "SELECT * FROM retention_analytics WHERE date_today = '$today' LIMIT 1";
$result = $db_handle->runQuery($query);

if($db_handle->numOfRows($result) > 0) {
    // Run update query
    $query = "UPDATE retention_analytics 
        SET m_client_to_retain = $month_clients_to_retain, 
        m_client_retained = $monthly_clients_retained, 
        m_retained_yesterday = $monthly_clients_retained_yesterday, 
        q_client_to_retain = $quarter_clients_to_retain, 
        q_client_retained = $quarter_clients_retained, 
        q_retained_yesterday = $quarter_clients_retained_yesterday
        WHERE date_today = '$today' LIMIT 1";
    $db_handle->runQuery($query);
} else {
    // Run insert query
    $query = "INSERT INTO retention_analytics (date_today, m_client_to_retain, m_client_retained, m_retained_yesterday, q_client_to_retain, q_client_retained, q_retained_yesterday) 
        VALUES ('$today', $month_clients_to_retain, $monthly_clients_retained, $monthly_clients_retained_yesterday, $quarter_clients_to_retain, $quarter_clients_retained, $quarter_clients_retained_yesterday)";
    $db_handle->runQuery($query);
}