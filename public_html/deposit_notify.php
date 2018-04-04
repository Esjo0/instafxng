<?php
require_once 'init/initialize_general.php';
$thisPage = "Traders";

$page_requested = "";

// This section processes - views/deposit_notify_trans_id.php
if (isset($_POST['deposit_notify_trans_id'])) {
    $trans_id = $db_handle->sanitizePost($_POST['transaction_ID']);

    $client_operation = new clientOperation();
    $transaction_detail = $client_operation->get_deposit_transaction($trans_id);

    if($transaction_detail) {
        if($transaction_detail['status'] == 1) {
            extract($transaction_detail);
            $expired = $client_operation->is_deposit_order_expired($created);
            if($expired) {
                $message_error = "The transaction ID provided has expired, we do not take payment notification of orders "
                        . "submitted over 24 hours. Please contact the <a href=\"contact_info.php\">support department</a> for assistance.";
            } else {
                $full_name = $last_name . " " . $first_name;
                $page_requested = "deposit_notify_detail_form_php";
            }
        } else {
            $message_error = "The payment notification for this transaction already exist. Please confirm the status with our <a href=\"contact_info.php\">support department</a>. Thank you.";
        }
    } else {
        $message_error = "Transaction ID does not exist in our record. Please confirm you have the correct transaction ID or contact our <a href=\"contact_info.php\">support department</a>.";
    }
}

// This section processes - views/deposit_notify_detail_form.php
if (isset($_POST['deposit_notify_detail_form'])) {
    foreach ($_POST as $key => $value) {
        $_POST[$key] = $db_handle->sanitizePost(trim($value));
    }
    extract($_POST);
    $naira_amount = str_replace(",", "", $naira_amount);

    $client_operation = new clientOperation();
    $notification = $client_operation->user_payment_notification($trans_id, $pay_method, $pay_date, $teller_no, $naira_amount, $comment);
    if($notification) {
        $_transaction_details = $client_operation->get_deposit_transaction($trans_id);
        $title = "New Deposit Notification";
        $message = "Transaction ID: $trans_id <br/>Client Name: ".$_transaction_details['full_name']." <br/>Pay Date: $pay_date <br/>Amount : N $naira_amount <br/>Teller No : $teller_no <br/>Client's Comment: $comment <br/>";
        $recipients = $obj_push_notification->get_recipients_by_access(31);
        $author = $_transaction_details['full_name'];
        $source_url = "https://instafxng.com/admin/deposit_notified.php";
        $notify_support = $obj_push_notification->add_new_notification($title, $message, $recipients, $author, $source_url);
        $page_requested = "deposit_notify_completed_php";
    } else {
        $message_error = "Something went wrong, the operation could not be completed.
            Payment notification for this transaction may already exist. Please contact support.";
    }
}

switch($page_requested) {
    case '': $deposit_notify_trans_id_php = true; break;
    case 'deposit_notify_detail_form_php': $deposit_notify_detail_form_php = true; break;
    case 'deposit_notify_completed_php': $deposit_notify_completed_php = true; break;
    default: $deposit_notify_trans_id_php = true;
}

?>
<!DOCTYPE html>
<html lang="en">
    <head>
        <base target="_self">
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>Instaforex Nigeria | Payment Notification</title>
        <meta name="title" content="Instaforex Nigeria | Payment Notification" />
        <meta name="keywords" content="instaforex, how to trade forex, trade forex, instaforex nigeria.">
        <meta name="description" content="Instaforex Nigeria Payment Notification">
        <?php require_once 'layouts/head_meta.php'; ?>        
    </head>
    <body>
        <?php require_once 'layouts/header.php'; ?>
        <!-- Main Body: The is the main content area of the web site, contains a side bar  -->
        <div id="main-body" class="container-fluid">
            <div class="row no-gutter">
                <?php require_once 'layouts/topnav.php'; ?>
                <!-- Main Body - Content Area: This is the main content area, unique for each page  -->
                <div id="main-body-content-area" class="col-md-8 col-md-push-4 col-lg-9 col-lg-push-3">
                    
                    <!-- Unique Page Content Starts Here
                    ================================================== -->
                    
                    <div class="section-tint super-shadow">
                        <div class="row">
                            <div class="col-sm-12 text-danger">
                                <h4><strong>Account Funding Payment Notification</strong></h4>
                            </div>
                        </div>
                        
                        <div class="row">
                            <div class="col-sm-12">
                                <?php require_once 'layouts/feedback_message.php'; ?>
                                                                
                                <?php 
                                    if($deposit_notify_trans_id_php) { include_once 'views/deposit_notify/deposit_notify_trans_id.php'; }
                                    if($deposit_notify_detail_form_php) { include_once 'views/deposit_notify/deposit_notify_detail_form.php'; }
                                    if($deposit_notify_completed_php) { include_once 'views/deposit_notify/deposit_notify_completed.php'; }
                                ?>
                            </div>
                        </div>
                    </div>

                    <!-- Unique Page Content Ends Here
                    ================================================== -->
                    
                </div>
                <!-- Main Body - Side Bar  -->
                <div id="main-body-side-bar" class="col-md-4 col-md-pull-8 col-lg-3 col-lg-pull-9 left-nav">
                <?php require_once 'layouts/sidebar.php'; ?>
                </div>
            </div>
        </div>
        <?php require_once 'layouts/footer.php'; ?>
        <script src="//cdnjs.cloudflare.com/ajax/libs/moment.js/2.9.0/moment-with-locales.js"></script>
        <script src="//cdn.rawgit.com/Eonasdan/bootstrap-datetimepicker/e8bddc60e73c1ec2475f827be36e1957af72e2ea/src/js/bootstrap-datetimepicker.js"></script>
    </body>
</html>