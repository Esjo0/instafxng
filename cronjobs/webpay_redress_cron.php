<?php
set_include_path('/home/tboy9/public_html/init/');
require_once 'initialize_general.php';

// Get All Webpay Deposit Older than 15 minutes that are in deposit initiated
$query = "SELECT trans_id FROM user_deposit WHERE status = '1' AND client_pay_method = '1' AND (STR_TO_DATE(created, '%Y-%m-%d') BETWEEN '2017-11-01' AND DATE_SUB(NOW(), INTERVAL 15 MINUTE))";
$result = $db_handle->runQuery($query);
$selected_transactions = $db_handle->fetchAssoc($result);

foreach($selected_transactions AS $trans_detail) {
    $trans_id = $trans_detail['trans_id'];

    $query = "UPDATE user_deposit SET status = '9' WHERE trans_id = '$trans_id' LIMIT 1";
    $db_handle->runQuery($query);
}