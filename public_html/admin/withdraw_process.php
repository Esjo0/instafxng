<?php
require_once("../init/initialize_admin.php");
if (!$session_admin->is_logged_in()) {
    redirect_to("login.php");
}

$client_operation = new clientOperation();

$get_params = allowed_get_params(['x', 'id']);

$trans_id_encrypted = $get_params['id'];
$trans_id = decrypt_ssl(str_replace(" ", "+", $trans_id_encrypted));
$trans_id = preg_replace("/[^A-Za-z0-9 ]/", '', $trans_id);

switch($get_params['x']) {
    case 'initiated': $withdraw_process_initiated = true; $page_title = '- INITIATED'; break;
    case 'confirmed': $withdraw_process_confirmed = true; $page_title = '- CONFIRMED'; break;
    case 'debited': $withdraw_process_ifx_debited = true; $page_title = '- IFX DEBITED'; break;
    default: $no_valid_page = true; break;
}

if($no_valid_page) {
    header("Location: ./");
    exit();
}

#Process Transaction Release
if($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['release_transaction'])){
    release_transaction($trans_id, $_SESSION['admin_unique_code']);
    switch($get_params['x']) {
        case 'initiated': $url = 'withdrawal_initiated'; break;
        case 'confirmed': $url = 'withdrawal_confirmed'; break;
        case 'debited': $url = 'withdrawal_ifx_debited'; break;
    }
    header("Location: $url.php");
    exit();
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
    release_transaction($transaction_id, $_SESSION['admin_unique_code']);
    header("Location: withdrawal_initiated.php");
    exit();
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
    release_transaction($transaction_id, $_SESSION['admin_unique_code']);
    header("Location: withdrawal_confirmed.php");
    exit();
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
        case 'Pend Payment': $status = '8'; break;
    }

    $client_operation->withdrawal_transaction_completed($transaction_id, $status, $remarks, $_SESSION['admin_unique_code']);
    release_transaction($transaction_id, $_SESSION['admin_unique_code']);
    header("Location: withdrawal_ifx_debited.php");
    exit();
}

$trans_detail = $client_operation->get_withdrawal_by_id_full($trans_id);

if(empty($trans_detail)) {
    redirect_to("./");
    exit;
} else {
    $trans_remark = $client_operation->get_withdrawal_remark($trans_id);
}

$transaction_access = allow_transaction_review($trans_id, $_SESSION['admin_unique_code']);
if(!empty($transaction_access['holder'])){
    $message_error = "This transaction is currently being reviewed by {$transaction_access['holder']}";
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
        <script src="operations_comment.js"></script>
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
                                <?php if($transaction_access['status']): ?>
                                    <div class="alert alert-success">
                                        <form  data-toggle="validator" role="form" method="post" action="">
                                            <p><strong>Are you done reviewing this transaction? </strong></p>
                                            <div class="form-group">
                                                <input value="Click Here To Release This Transaction." name="release_transaction" type="submit" data-toggle="modal" class="btn btn-sm btn-success" />
                                            </div>
                                            <p>Clicking the button above allows other admin personnel work on this transaction.</p>
                                        </form>
                                    </div>
                                <?php endif; ?>

                                <?php require_once 'layouts/feedback_message.php'; ?>

                                <?php $bonus_obj = new Bonus_Operations(); $bonus_obj->UI_flag_as_bonus_transaction($trans_detail['ifx_acct_no']) ?>

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