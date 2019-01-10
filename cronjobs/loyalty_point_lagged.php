<?php
set_include_path('/home/tboy9/public_html/init/');
require_once 'initialize_general.php';

$query = "SELECT user_code FROM user_loyalty_log";
$result = $db_handle->runQuery($query);
$selected = $db_handle->fetchAssoc($result);

foreach ($selected AS $row) {
    extract($row);

    $total_point_earned_lagged = $obj_loyalty_point->user_total_point_earned_lagged($user_code);

    // Update total_point_earned_lagged
    $query = "UPDATE user_loyalty_log SET total_point_earned_lagged = $total_point_earned_lagged WHERE user_code = '$user_code' LIMIT 1";
    $result = $db_handle->runQuery($query);
}

if($db_handle) { $db_handle->closeDB(); mysqli_close($db_handle); }