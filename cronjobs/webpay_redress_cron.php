<?php
set_include_path('/home/tboy9/public_html/init/');
require_once 'initialize_general.php';

// Get All Webpay Deposit Older than 15 minutes that are in deposit initiated OR failed
$query = "SELECT trans_id, client_pay_method FROM user_deposit WHERE status IN ('1', '9') AND order_complete_time IS NULL AND client_pay_method IN ('1', '10') AND (STR_TO_DATE(created, '%Y-%m-%d') BETWEEN DATE_SUB(NOW(), INTERVAL 1 WEEK) AND DATE_SUB(NOW(), INTERVAL 10 MINUTE))";
$result = $db_handle->runQuery($query);
$selected_transactions = $db_handle->fetchAssoc($result);

foreach($selected_transactions AS $trans_detail) {
    $trans_id = $trans_detail['trans_id'];
    $client_pay_method = $trans_detail['client_pay_method'];

    $client_operation = new clientOperation();

    switch ($client_pay_method) {
        case '1':
            $return_msg = $client_operation->requery_webpay_deposit($trans_id);
            break;
        case '10':
            $return_msg = $client_operation->requery_paystack_deposit($trans_id);
            break;
    }
}