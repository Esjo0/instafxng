<?php
set_include_path('/home/tboy9/public_html/init/');
require_once 'initialize_general.php';

$query = "SELECT user_code,expired_point AS selected_expired_point FROM user_loyalty_log";
$result = $db_handle->runQuery($query);
$selected = $db_handle->fetchAssoc($result);

foreach ($selected AS $row) {
    extract($row);

    $total_point_earned_lagged = $obj_loyalty_point->user_total_point_earned_lagged($user_code);
    $total_point_claimed = $obj_loyalty_point->user_total_point_claimed($user_code);
    $expired_point = $total_point_earned_lagged - $total_point_claimed;

    // Update expired point in db if it is a new high
    if($expired_point > $selected_expired_point) {
        $query = "UPDATE user_loyalty_log SET total_point_earned_lagged = $total_point_earned_lagged, expired_point = $expired_point WHERE user_code = '$user_code' LIMIT 1";
        $result = $db_handle->runQuery($query);
    } else {
        $query = "UPDATE user_loyalty_log SET total_point_earned_lagged = $total_point_earned_lagged WHERE user_code = '$user_code' LIMIT 1";
        $result = $db_handle->runQuery($query);
    }
}