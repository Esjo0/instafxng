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
    $expired_point = $total_point_earned_lagged - $total_point_claimed;

    $point_balance = $total_point_earned - $total_point_claimed - $expired_point;




}