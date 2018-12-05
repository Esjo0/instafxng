<?php
set_include_path('/home/tboy9/public_html/init/');
require_once 'initialize_general.php';

$client_operation = new clientOperation();

$query = "SELECT * FROM point_based_claimed WHERE status = '1'";
$result = $db_handle->runQuery($query);
$all_new_points = $db_handle->fetchAssoc($result);

foreach ($all_new_points as $row) {
    $point_id = $row['point_based_claimed_id'];
    $point_claimed = $row['point_claimed'];
    $user_code = $row['user_code'];

    $query = "SELECT trans_id, created FROM user_deposit WHERE points_claimed_id = $point_id AND status IN ('1', '4', '7', '9', '10') LIMIT 1";
    $result = $db_handle->runQuery($query);
    $selection_transaction = $db_handle->fetchAssoc($result);

    $created = $selection_transaction[0]['created'];
    $trans_id = $selection_transaction[0]['trans_id'];

    $expired = $client_operation->is_deposit_order_expired($created);

    if($expired) {
        $query = "UPDATE point_based_claimed SET status = '3' WHERE point_based_claimed_id = $point_id LIMIT 1";
        $db_handle->runQuery($query);

        // REVERSAL: When transaction failed, return deducted point back to the point balance
        // Update client point balance
        $query = "UPDATE user SET point_balance = point_balance + $point_claimed WHERE user_code = '$user_code' LIMIT 1";
        $db_handle->runQuery($query);
    }
}

if($db_handle) { $db_handle->closeDB(); mysqli_close($db_handle); }