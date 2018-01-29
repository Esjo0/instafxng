<?php
set_include_path('/home/tboy9/public_html/init/');
require_once 'initialize_general.php';

// Get All Webpay Deposit Older than 15 minutes that are in deposit initiated OR failed
$query = "SELECT trans_id FROM user_deposit WHERE status IN ('1', '9') AND order_complete_time IS NULL AND client_pay_method = '1' AND (STR_TO_DATE(created, '%Y-%m-%d') BETWEEN DATE_SUB(NOW(), INTERVAL 1 WEEK) AND DATE_SUB(NOW(), INTERVAL 10 MINUTE))";
$result = $db_handle->runQuery($query);
$selected_transactions = $db_handle->fetchAssoc($result);

foreach($selected_transactions AS $trans_detail) {
    $trans_id = $trans_detail['trans_id'];

    $client_operation = new clientOperation();
    $requery_feedback = $client_operation->requery_webpay_deposit($trans_id);
}