<?php
require_once("../init/initialize_admin.php");
if (!$session_admin->is_logged_in()) {
    redirect_to("login.php");
}

$page_requested = "";

// This section processes - views/deposit_add/deposit_add_ifx_acct.php
if (isset($_POST['deposit_add_ifx_acct'])) {
    $account_no = $db_handle->sanitizePost($_POST['ifx_acct_no']);

    $client_operation = new clientOperation($account_no);
    $user_ifx_details = $client_operation->get_client_data();

    if($user_ifx_details) {
        extract($user_ifx_details); // turn table columns selected into variables
        $total_point_earned = $client_operation->get_loyalty_point($client_user_code);

        $page_requested = 'deposit_add_qty_php';
    } else {
        $message_error = "The Instaforex account does not exist, You can add the Instaforex account <a href=\"client_add.php\">here</a>.";
    }
}

// This section processes - views/deposit_add/deposit_add_qty.php
if (isset($_POST['deposit_add_qty'])) {
    
    $page_requested = 'deposit_add_qty_php';

    $account_no = $db_handle->sanitizePost($_POST['ifx_acct_no']);
    $ifx_naira_amount = $db_handle->sanitizePost($_POST['naira_amount']);
    $origin_of_deposit = $db_handle->sanitizePost($_POST['deposit_origin']);
    $point_claimed = $db_handle->sanitizePost($_POST['point_claimed']);
    $remarks = $db_handle->sanitizePost($_POST['remarks']);

    $client_operation = new clientOperation($account_no);
    $user_ifx_details = $client_operation->get_client_data();
    extract($user_ifx_details);

    $total_point_earned = $client_operation->get_loyalty_point($client_user_code);

    switch($ifx_acc_type) {
        case '1': // ILPR Account
            $ifx_dollar_amount = $ifx_naira_amount / IPLRFUNDRATE;
            $exchange = IPLRFUNDRATE;
            break;
        case '2': // Non-ILPR Account
            $ifx_dollar_amount = $ifx_naira_amount / NFUNDRATE;
            $exchange = NFUNDRATE;
            break;
        default:
            $ifx_naira_amount = NFUNDRATE * $ifx_dollar_amount;
            $exchange = NFUNDRATE;
    }

    $client_full_name = $client_last_name . " " . $client_first_name;
    
    if($point_claimed > $total_point_earned) {
        $message_error = "You can not redeem more than your total earned point.";
    } else {
        if(isset($point_claimed) && !empty($point_claimed)) {
            $point_claimed_id = $client_operation->set_point_claimed($point_claimed, $client_user_code);
        }
        $stamp_duty = 0.00;
        $trans_id = "IFX" . time();
        $trans_id_encrypted = encrypt($trans_id);
        $service_charge = 0.00;
        $vat = 0.00;
        $total_payable = $vat + $service_charge + $ifx_naira_amount;
        $total_payable = number_format($total_payable, 2, ".", "");

        if(!empty($point_claimed_id)) {
            $log_deposit = $client_operation->log_deposit($trans_id, $ifx_acc_id, $exchange, $ifx_dollar_amount, $ifx_naira_amount, $service_charge, $vat, $stamp_duty, $total_payable, $origin_of_deposit, $point_claimed_id);
        } else {
            $log_deposit = $client_operation->log_deposit($trans_id, $ifx_acc_id, $exchange, $ifx_dollar_amount, $ifx_naira_amount, $service_charge, $vat, $stamp_duty, $total_payable, $origin_of_deposit);
        }

        if($log_deposit) {
            $pay_date = date('Y-m-d', time());

            $client_operation->deposit_comment($trans_id, $_SESSION['admin_unique_code'], $remarks);
            $client_operation->user_payment_notification($trans_id, '7', $pay_date, "", $total_payable);
            $page_requested = 'deposit_add_finalize_php';
        } else {
            $message_error = "Something went wrong, please try again.";
            $page_requested = "";
        }



    }
}

switch($page_requested) {
    case '': $deposit_add_ifx_acct_php = true; break;
    case 'deposit_add_qty_php': $deposit_add_qty_php = true; break;
    case 'deposit_add_finalize_php': $deposit_add_finalize_php = true; break;
    default: $deposit_add_ifx_acct_php = true;
}

?>
<!DOCTYPE html>
<html lang="en">
    <head>
        <base target="_self">
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>Instaforex Nigeria | Admin - Add Deposit</title>
        <meta name="title" content="Instaforex Nigeria | Admin - Add Deposit" />
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
                            <h4><strong>ADD DEPOSIT</strong></h4>
                        </div>
                    </div>

                    <div class="section-tint super-shadow">
                        <div class="row">
                            <div class="col-sm-12">
                                <?php require_once 'layouts/feedback_message.php'; ?>
                                
                                <?php 
                                    if($deposit_add_ifx_acct_php) { include_once 'views/deposit_add/deposit_add_ifx_acct.php'; }
                                    if($deposit_add_qty_php) { include_once 'views/deposit_add/deposit_add_qty.php'; }
                                    if($deposit_add_finalize_php) { include_once 'views/deposit_add/deposit_add_finalize.php'; }
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