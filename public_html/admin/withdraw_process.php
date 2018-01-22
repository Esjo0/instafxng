<?php
require_once("../init/initialize_admin.php");
if (!$session_admin->is_logged_in()) {
    redirect_to("login.php");
}

$client_operation = new clientOperation();

$get_params = allowed_get_params(['x', 'id']);

$trans_id_encrypted = $get_params['id'];
$trans_id = decrypt(str_replace(" ", "+", $trans_id_encrypted));
$trans_id = preg_replace("/[^A-Za-z0-9 ]/", '', $trans_id);

switch($get_params['x']) {
    case 'initiated': $withdraw_process_initiated = true; $page_title = '- INITIATED'; break;
    case 'confirmed': $withdraw_process_confirmed = true; $page_title = '- CONFIRMED'; break;
    case 'debited': $withdraw_process_ifx_debited = true; $page_title = '- IFX DEBITED'; break;
    default: $no_valid_page = true; break;
}

if($no_valid_page) {
    header("Location: ./");
}

// Process Initiated Withdrawal
if ($withdraw_process_initiated && ($_SERVER['REQUEST_METHOD'] == 'POST' && $_POST['process'] == true)) {
    
    foreach($_POST as $key => $value) {
        $_POST[$key] = $db_handle->sanitizePost(trim($value));
    }
    
    $transaction_id = $_POST['transaction_id'];
    $remarks = $_POST['remarks'];
    
    switch ($_POST['process']) {
        case 'Confirm Account Check':
            $status = '4'; // Account Check Successful
            break;
        case 'Decline Account Check':
            $status = '3'; // Account Check Failed
            break;
        case 'Pend Account Check':
            $status = '2'; // Account Check In Progress
            break;
    }

    $client_operation->withdrawal_transaction_account_check($transaction_id, $status, $remarks, $_SESSION['admin_unique_code']);
    header("Location: withdrawal_initiated.php");
}

// Process Confirmed Withdrawal
if ($withdraw_process_confirmed && ($_SERVER['REQUEST_METHOD'] == 'POST' && $_POST['process'] == true)) {
    foreach($_POST as $key => $value) {
        $_POST[$key] = $db_handle->sanitizePost(trim($value));
    }
    
    $transaction_id = $_POST['transaction_id'];
    $remarks = $_POST['remarks'];
    $transaction_reference = $_POST['trans_ref'];

    switch ($_POST['process']) {
        // Withdrawal Successful
        case 'Confirm IFX Debit': $status = '7'; break;
        
        // Withdrawal Declined
        case 'Decline IFX Debit': $status = '6'; break;
        
        // Withdrawal In Progress
        case 'Pend IFX Debit': $status = '5'; break;
    }

    $client_operation->withdrawal_transaction_ifx_debited($transaction_id, $transaction_reference, $status, $remarks, $_SESSION['admin_unique_code']);
    header("Location: withdrawal_confirmed.php");
}

$trans_detail = $client_operation->get_withdrawal_by_id_full($trans_id);
$user_bank_account = $client_operation->get_user_bank_account($trans_detail['user_code']);
// Process IFX Debited Withdrawal
if ($withdraw_process_ifx_debited && ($_SERVER['REQUEST_METHOD'] == 'POST' && $_POST['process'] == true)) {
    foreach($_POST as $key => $value) {
        $_POST[$key] = $db_handle->sanitizePost(trim($value));
    }
    
    $transaction_id = $_POST['transaction_id'];
    $remarks = $_POST['remarks'];

    $_author_name = $admin_object->get_admin_name_by_code($_SESSION['admin_unique_']);
    $_full_name = $trans_detail['full_name'];
    $_phone = $trans_detail['phone'];
    $_email = $trans_detail['email'];
    $_dollar_withdraw = $trans_detail['dollar_withdraw'];
    $_naira_total_withdrawable = number_format($trans_detail['naira_total_withdrawable'], 2, ".", ",");

    $_withdrawal_created = datetime_to_text($trans_detail['withdrawal_created']);
    $_ifx_acct_no = $trans_detail['ifx_acct_no'];
    $_client_bank_name = $user_bank_account['client_bank_name'];
    $_client_acct_name = $user_bank_account['client_acct_name'];
    $_client_acct_no = $user_bank_account['client_acct_no'];
    $_naira_total_withdrawable = number_format($trans_detail['naira_total_withdrawable'], 2, ".", ",");


    switch ($_POST['process']) {
        // Withdrawal Successful
        case 'Complete Payment': $status = '10'; break;
        
        // Withdrawal Declined
        case 'Decline Payment': $status = '9'; break;
        
        // Withdrawal In Progress
        case 'Pend Payment':
            $status = '8';
            $subject = "Withdrwal IFX Debited";
            $message_final = <<<MAIL
                    <div style="background-color: #F3F1F2">
                        <div style="max-width: 80%; margin: 0 auto; padding: 10px; font-size: 14px; font-family: Verdana;">
                            <img src="https://instafxng.com/images/ifxlogo.png" />
                            <hr />
                            <div style="background-color: #FFFFFF; padding: 15px; margin: 5px 0 5px 0;">
                                <p>Author Name: $_author_name</p>
                                <p>Author Remark: $ip_address</p>
                                <br/>
                                <p>Client Name: $_full_name</p>
                                <p>Client Phone Number: $_phone</p>
                                <p>Client Email: $_email</p>
                                <br/>
                                <p>Transaction ID: $transaction_id</p>
                                <p>Withdraw: $$_dollar_withdraw - &#8358;$_naira_total_withdrawable</p>
                                <p>Date: $_withdrawal_created</p>
                                <p>Account: $_ifx_acct_no</p>
                                <br/>
                                <p>Bank Name: $_client_bank_name</p>
                                <p>Account Name: $_client_acct_name</p>
                                <p>Account Number: $_client_acct_no</p>
                                <p>Amount To Pay: &#8358;$_naira_total_withdrawable</p>
                            </div>      
                        </div>
                    </div>
MAIL;
            $system_object->send_email($subject, $message_final, "Demola@instafxng.com", $admin_name);

            $status = '8';
            break;
    }

    $client_operation->withdrawal_transaction_completed($transaction_id, $status, $remarks, $_SESSION['admin_unique_code']);
    header("Location: withdrawal_ifx_debited.php");
}

$trans_detail = $client_operation->get_withdrawal_by_id_full($trans_id);

if(empty($trans_detail)) {
    redirect_to("./");
    exit;
} else {
    $trans_remark = $client_operation->get_withdrawal_remark($trans_id);
}

?>
<!DOCTYPE html>
<html lang="en">
    <head>
        <base target="_self">
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>Instaforex Nigeria | Admin - Process Withdrawal</title>
        <meta name="title" content="Instaforex Nigeria | Admin - Process Withdrawal" />
        <meta name="keywords" content="" />
        <meta name="description" content="" />
        <?php require_once 'layouts/head_meta.php'; ?>
    </head>
    <body>
        <?php require_once 'layouts/header.php'; ?>
        <!-- Main Body: The is the main content area of the web site, contains a side bar  -->
        <div id="main-body" class="container-fluid">
            <div class="row no-gutter">
                <!-- Main Body - Side Bar  -->
                <div id="main-body-side-bar" class="col-md-4 col-lg-3 left-nav">
                <?php require_once 'layouts/sidebar.php'; ?>
                </div>
                
                <!-- Main Body - Content Area: This is the main content area, unique for each page  -->
                <div id="main-body-content-area" class="col-md-8 col-lg-9">
                    
                    <!-- Unique Page Content Starts Here
                    ================================================== -->
                    <div class="row">
                        <div class="col-sm-12 text-danger">
                            <h4><strong>PROCESS WITHDRAWAL <?php if($page_title) { echo $page_title; } ?></strong></h4>
                        </div>
                    </div>
                    
                    
                    <div class="section-tint super-shadow">
                        <div class="row">
                            <div class="col-sm-12">
                                <?php require_once 'layouts/feedback_message.php'; ?>
                                
                                <?php 
                                    if($withdraw_process_initiated) { include_once 'views/withdraw_process/withdraw_process_initiated.php'; }
                                    if($withdraw_process_confirmed) { include_once 'views/withdraw_process/withdraw_process_confirmed.php'; }
                                    if($withdraw_process_ifx_debited) { include_once 'views/withdraw_process/withdraw_process_ifx_debited.php'; }
                                ?>
                                
                            </div>
                        </div>
                        
                    </div>

                    <!-- Unique Page Content Ends Here
                    ================================================== -->
                    
                </div>
                
            </div>
        </div>
        <?php require_once 'layouts/footer.php'; ?>
    </body>
</html>