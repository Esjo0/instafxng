<?php
set_include_path('/home/tboy9/public_html/init/');
require_once 'initialize_general.php';

$query = "SELECT user_code, total_point_earned_lagged, expired_point FROM user_loyalty_log";
$result = $db_handle->runQuery($query);
$selected = $db_handle->fetchAssoc($result);

foreach ($selected AS $row) {
    extract($row);

    $total_point_earned = $obj_loyalty_point->user_total_point_earned($user_code);
    $total_point_claimed = $obj_loyalty_point->user_total_point_claimed($user_code);

    $spent_point = $total_point_claimed + $expired_point;
    $additional_expired_point = $total_point_earned_lagged - $spent_point;

    if($additional_expired_point < 0) {
        $additional_expired_point = 0; // we do not allow negative
    }

    $point_balance = ($total_point_earned - $spent_point) - $additional_expired_point;

    // Make update to the point balance, total point claimed, total point earned, expired point
    $query = "UPDATE user_loyalty_log AS ull
            INNER JOIN user AS u ON ull.user_code = u.user_code
            SET ull.total_point_earned = $total_point_earned,
            ull.total_point_claimed = $total_point_claimed,
            ull.expired_point = expired_point + $additional_expired_point,
            ull.point_balance = $point_balance,
            u.point_balance = $point_balance
            WHERE ull.user_code = '$user_code'";
    $result = $db_handle->runQuery($query);
}

if($db_handle) { $db_handle->closeDB(); mysqli_close($db_handle); }