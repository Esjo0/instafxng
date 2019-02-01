<?php
require_once 'init/initialize_general.php';
$thisPage = "Traders";

$page_requested = "";

// This section processes - views/withdraw_funds_ifx_acct.php
if(isset($_POST['withdraw_funds_ifx_acct'])) {
    $account_no = $db_handle->sanitizePost($_POST['ifx_acct_no']);

    $client_operation = new clientOperation($account_no);
    $user_ifx_details = $client_operation->get_client_data();

    if($user_ifx_details) {
        extract($user_ifx_details); // turn table columns selected into variables

        if($client_status != '1') {
            $page_requested = "";
            $message_error = "Your profile is yet to be activated on our system or is inactive, on probation or suspended, please contact support.";
        } elseif($ifx_acc_status != '2') {
            $page_requested = "";
            $message_error = "The Instaforex Account $account_no provided is yet to be activated on our system or is inactive, please contact support.";
        } elseif($ifx_acc_bonus_status == '2') {
            $page_requested = "";
            $message_error = "The Instaforex Account $account_no provided is a Bonus account on our system, please contact support.";
        } else {
            if(is_null($client_password) || empty($client_password)) {
                // User has not chosen a password, phone not verified, email not verified.
                $client_operation->send_verification_message($client_user_code, $client_email, $client_phone_number, $client_first_name, $client_verification_id);
                $page_requested = 'withdraw_funds_pcode_new_php'; // No associated pass code
            } else {
                $page_requested = 'withdraw_funds_pcode_php'; // Continue, has pass code
            }
        }
    } else {
        $page_requested = 'withdraw_funds_kyc_php'; // For new accounts
    }
}

// This section processes - views/withdraw_funds_pcode.php
if(isset($_POST['withdraw_funds_pcode'])) {
    $account_no = $db_handle->sanitizePost($_POST['ifx_acct_no']);
    $password_submitted = $db_handle->sanitizePost($_POST['pass_code']);

    $client_operation = new clientOperation($account_no);
    $user_ifx_details = $client_operation->get_client_data();

    if($user_ifx_details) {
        extract($user_ifx_details);

        if($client_operation->authenticate_user_password($password_submitted, $client_pass_salt, $client_password)) {
            if(!$client_operation->confirm_credential($client_user_code)) {
                $message_error = "You have not verified your account by uploading identification documents. You must verify your account ";
                $message_error .= "before proceeding with your withdrawal. Please verify your account by <a href='verify_account.php'> clicking here.</a></p>";
            } elseif (!$client_operation->confirm_client_address($client_user_code)) {
                $message_error = "You have not verified your contact address or your verification is still been processed. You must verify your address to proceed. ";
                $message_error .= "Please verify your account by <a href='verify_account.php'> clicking here.</a> or <a href='contact_info.php'>contact us</a> for help</p>";
            } elseif (!$client_operation->confirm_bank_account($client_user_code)) {
                $all_banks = $system_object->get_all_banks();
                $page_requested = 'withdraw_funds_add_bank_php';
            } else {
                $page_requested = 'withdraw_funds_qty_php';
            }
        } else {
            $page_requested = 'withdraw_funds_pcode_php';
            $message_error = "You have entered an incorrect pass code";
            // Limit number of pass code trial to a specified number to avoid abuse
        }
    } else {
        $message_error = "Something went wrong, please try again.";
    }
}

// This section processes - views/deposit_funds_kyc.php
if(isset($_POST['withdraw_funds_kyc'])) {
    $page_requested = 'withdraw_funds_kyc_php';

    $account_no = $db_handle->sanitizePost(trim($_POST['ifx_acct_no']));
    $full_name = $db_handle->sanitizePost(trim($_POST['full_name']));
    $email_address = $db_handle->sanitizePost(trim($_POST['email']));
    $phone_number = $db_handle->sanitizePost(trim($_POST['phone']));

    $client_operation = new clientOperation();

    if(empty($full_name) || empty($email_address) || empty($phone_number)) {
        $message_error = "All fields must be filled.";
    } elseif (!check_email($email_address)) {
        $message_error = "You have provided an invalid email address. Please try again.";
    } elseif (check_number($phone_number) != 5) {
        $message_error = "The supplied phone number is invalid.";
    } else {
        $user_code = $client_operation->new_user($account_no, $full_name, $email_address, $phone_number, $type = 1);

        if($user_code) {
            $user_ifx_details = $client_operation->set_client_data($account_no);
            extract($user_ifx_details);

            if($client_status != '1') {
                $page_requested = "";
                $message_error = "Your profile is yet to be activated on our system or is inactive, on probation or suspended, please contact support.";
            } else {
                if(is_null($client_password) || empty($client_password)) {
                    // User has not chosen a password, phone not verified, email not verified.
                    $client_operation->send_verification_message($client_user_code, $client_email, $client_phone_number, $client_first_name, $client_verification_id);
                    $page_requested = 'withdraw_funds_pcode_new_php'; // No associated pass code
                } else {
                    $page_requested = 'withdraw_funds_pcode_php'; // Continue, has pass code
                }
            }

        }
    }
}

// This section processes - views/withdraw_funds_add_bank.php
if(isset($_POST['withdraw_funds_add_bank'])) {
    $page_requested = 'withdraw_funds_add_bank_php';
    
    $account_no = $db_handle->sanitizePost($_POST['ifx_acct_no']);
    $bank_name = $db_handle->sanitizePost($_POST['bank_name']);
    $bank_acct_name = $db_handle->sanitizePost($_POST['bank_acct_name']);
    $bank_acct_number = $db_handle->sanitizePost($_POST['bank_acct_number']);

    if(empty($bank_name) || empty($bank_acct_name) || empty($bank_acct_number)) {
        $message_error = "All fields must be filled.";
    } else {

        $client_operation = new clientOperation($account_no);
        $user_ifx_details = $client_operation->get_client_data();

        if($user_ifx_details) {
            extract($user_ifx_details);

            $client_operation->set_bank_account($client_user_code, $bank_acct_name, $bank_acct_number, $bank_name);
            $message_success = "Your bank information have been submitted. Please hold on for approval. You can <a href='contact_info.php'>contact us</a> if you need any help.";
        } else {
            $message_error = "Something went wrong, the operation could not be completed.";
        }
    }
}

// This section processes - views/withdraw_funds_qty.php
if(isset($_POST['withdraw_funds_qty'])) {
    $page_requested = 'withdraw_funds_qty_php';

    $account_no = $db_handle->sanitizePost($_POST['ifx_acct_no']);
    $ifx_dollar_amount = $db_handle->sanitizePost($_POST['ifx_dollar_amount']);
    $phone_password = $db_handle->sanitizePost($_POST['phone_password']);

    $client_operation = new clientOperation($account_no);
    $user_ifx_details = $client_operation->get_client_data();
    if($user_ifx_details) {
        extract($user_ifx_details);
    }

    $today_client_withdrawal_today = $client_operation->get_total_client_withdrawal_today($client_user_code);

    if($ifx_dollar_amount < WITHDRAWAL_MIN_VALUE || $ifx_dollar_amount > WITHDRAWAL_MAX_VALUE) {
        $message_error = "Please re-enter amount. The minimum you can withdraw per transaction is $" . WITHDRAWAL_MIN_VALUE . " and the maximum is $" . number_format(WITHDRAWAL_MAX_VALUE);
    } elseif (($today_client_withdrawal_today + $ifx_dollar_amount) > WITHDRAWAL_DAILY_MAX_VALUE) {
        $message_error = "Please re-enter amount. This transaction will make your total requested withdrawal today to exceed the maximum allowed withdrawal per day, the maximum per day is $"  . number_format(WITHDRAWAL_DAILY_MAX_VALUE);
    } else {

        if($client_user_code) {

            $user_bank_details = $client_operation->get_user_bank_account($client_user_code);
            extract($user_bank_details);

            $full_name = $client_first_name . " " . $client_last_name;

            $trans_id = "WIT" . time();

            $exchange = WITHDRATE;
            $ifx_naira_amount = $ifx_dollar_amount * WITHDRATE;
            $service_charge = $ifx_naira_amount * WSERVCHARGE;
            $vat = $service_charge * WVAT;
            $total_withdrawal_payable = $ifx_naira_amount - ($service_charge + $vat);

            $log_withdrawal = $client_operation->log_withdrawal($trans_id, $ifx_acc_id, $exchange, $ifx_dollar_amount, $ifx_naira_amount, $service_charge, $vat, $total_withdrawal_payable, $phone_password);

            if($log_withdrawal) {
                $client_operation->send_withdrawal_invoice($client_full_name, $client_email, $trans_id, $account_no, $ifx_dollar_amount, $ifx_naira_amount, $service_charge, $vat, $total_withdrawal_payable, $client_bank_name, $client_acct_name, $client_acct_no);
                $title = "New Withdrawal Notification";
                $message = "Transaction ID: $trans_id <br/>Client Name: $client_full_name <br/>Client Email: $client_email <br/>Account No: $account_no <br/>Amount : N $total_withdrawal_payable <br/>Client Bank Name: $client_bank_name <br/>Client Account Name: $client_acct_name <br/>Client Account No: $client_acct_no <br/>";
                $recipients = $obj_push_notification->get_recipients_by_access(41);
                $author = $client_full_name;
                $source_url = "https://instafxng.com/admin/withdrawal_initiated.php";
                $notify_support = $obj_push_notification->add_new_notification($title, $message, $recipients, $author, $source_url);

                $message_success = "Withdrawal Request Submitted.";
                $page_requested = 'withdraw_funds_finalize_php';
            } else {
                $message_error = "An error occurred, please enter your amount again or contact support for assistance.";
            }
        } else {
            $message_error = "Something went wrong, the operation could not be completed.";
        }

    }
}

switch($page_requested) {
    case '': $withdraw_funds_ifx_acct_php = true; break;
    case 'withdraw_funds_pcode_php': $withdraw_funds_pcode_php = true; break;
    case 'withdraw_funds_pcode_new_php': $withdraw_funds_pcode_new_php = true; break;
    case 'withdraw_funds_kyc_php': $withdraw_funds_kyc_php = true; break;
    case 'withdraw_funds_add_bank_php': {
        $query = "SELECT status FROM user_bank WHERE user_code = '$client_user_code' AND is_active = '1' LIMIT 1";
        $result = $db_handle->runQuery($query);
        $fetched_data = $db_handle->fetchAssoc($result);
        $bank_status = $fetched_data[0]['status'];

        if($bank_status == '1') {
            $message_error = "Your bank account information is not yet approved, please <a href='contact_info.php'>contact us</a> for help.";
            $submit_btn = false;
        } else {
            $submit_btn = true;
        }

        $withdraw_funds_add_bank_php = true;
    }; break;
    case 'withdraw_funds_qty_php': $withdraw_funds_qty_php = true; break;
    case 'withdraw_funds_finalize_php': $withdraw_funds_finalize_php = true; break;
    default: $withdraw_funds_ifx_acct_php = true;
}

?>
<!DOCTYPE html>
<html lang="en">
    <head>
        <base target="_self">
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>Instaforex Nigeria | Withdraw Funds in Naira</title>
        <meta name="title" content="Instaforex Nigeria | Withdraw Funds in Naira" />
        <meta name="keywords" content="instaforex, withdraw funds from your trading account, how to trade forex, trade forex, instaforex nigeria.">
        <meta name="description" content="Witthdraw Funds from your instaforex trading account">
        <?php require_once 'layouts/head_meta.php'; ?>
        <script>
            function checkInp() {
                var x=document.forms["enter_amount"]["ifx_dollar_amount"].value;
                if (isNaN(x)) {
                    alert("Must input numbers");
                    return false;
                } else if (x < <?php echo WITHDRAWAL_MIN_VALUE; ?>) {
                    alert("Minimum Withdrawal is $<?php echo WITHDRAWAL_MIN_VALUE; ?>");
                    return false;
                } else if (x > <?php echo WITHDRAWAL_MAX_VALUE; ?>) {
                    alert("Maximum Withdrawal is $<?php echo number_format(WITHDRAWAL_MAX_VALUE); ?>.");
                    return false;
                }
            }
        </script>
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
                                <h4><strong>Withdraw from your Instaforex Account</strong></h4>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-sm-12">
                                <?php require_once 'layouts/feedback_message.php'; ?>
                                                                
                                <?php 
                                    if($withdraw_funds_ifx_acct_php) { include_once 'views/withdraw_funds/withdraw_funds_ifx_acct.php'; }
                                    if($withdraw_funds_pcode_php) { include_once 'views/withdraw_funds/withdraw_funds_pcode.php'; }
                                    if($withdraw_funds_pcode_new_php) { include_once 'views/withdraw_funds/withdraw_funds_pcode_new.php'; }
                                    if($withdraw_funds_kyc_php) { include_once 'views/withdraw_funds/withdraw_funds_kyc.php'; }
                                    if($withdraw_funds_add_bank_php) { include_once 'views/withdraw_funds/withdraw_funds_add_bank.php'; }
                                    if($withdraw_funds_qty_php) { include_once 'views/withdraw_funds/withdraw_funds_qty.php'; }
                                    if($withdraw_funds_finalize_php) { include_once 'views/withdraw_funds/withdraw_funds_finalize.php'; }
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
    </body>
</html>