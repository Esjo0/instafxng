<?php
require_once 'init/initialize_general.php';
$thisPage = "Traders";

$get_params = allowed_get_params(['x']);
$additional_msg = $get_params['x'];

if($additional_msg == 'msg')
{
    $special_msg = <<<msg
        <p>Our double combo <a href="http://bit.ly/2JysCvT" target="_blank">Free Daily 85% Accurate High Precision trading signals</a> +
        <a href="http://bit.ly/2CSUjAz" target="_blank">High - Impact News Release</a> will help you hit your
        profit target this month!</p>

        <p>This duo has consistently booked traders between 100-200 pips daily.</p>

        <p>Here's what some of our clients had to say about the signals.</p>
        <br />

        <div class="row">
            <div class="col-sm-6"><img class="img-responsive" src="images/testimony-1.png" /></div>
            <div class="col-sm-6"><img class="img-responsive" src="images/testimony-2.png" /></div>
        </div>

        <br />

        <p>Are you maximizing the use of the daily signals and News release? Not yet?
        You're missing out a lot!</p>

        <p>This is a jaw-dropping opportunity to make consistent profit from your trades,
        earn more loyalty points and get a share of $500 this month!</p>

        <p><strong>STEP 1: Fund your InstaForex Account</strong></p>
        <p>Funding your Instaforex account and trading actively increases your loyalty points and qualifies you for the monthly reward of $500.</p>
        <ul><li><a href="#acct_num">Click here</a> to fund account now.</li></ul>

        <p><strong>STEP 2: Get Signals and News Release Alert Daily</strong></p>

        <p>The trading signals are posted every day on our website, just so you do not miss any of the entry prices as they drop, we have created a channel via Facebook Messenger where you can get direct notification as soon as the signals are posted.</p>
        <p>Don't miss any signal, <a href="http://bit.ly/2KEWxWQ" target="_blank">click here</a> to get daily notifications as soon as the signals are posted.</p>
        <hr />
        <br />
msg;
}

if($additional_msg == 'msg_new')
{
    $special_msg = "page";
    $special_msg_url = "views/deposit_funds/ilpr_deposit_landing.php";
}



$page_requested = "";

// This section processes - views/val_offer_info.php
if(isset($_POST['deposit_funds_ifx_acct'])) {
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
                $page_requested = 'deposit_funds_pcode_new_php'; // No associated pass code
            } else {
                $page_requested = 'deposit_funds_pcode_php'; // Continue, has pass code
            }
        }
    } else {
        $page_requested = 'deposit_funds_kyc_php'; // For new accounts
    }
}

// This section processes - views/deposit_funds_pcode.php
if(isset($_POST['deposit_funds_pcode'])) {
    $account_no = $db_handle->sanitizePost($_POST['ifx_acct_no']);
    $password_submitted = $db_handle->sanitizePost($_POST['pass_code']);
    
    $client_operation = new clientOperation($account_no);
    $user_ifx_details = $client_operation->get_client_data();
    
    if($user_ifx_details) {
        extract($user_ifx_details);
    
        if($client_operation->authenticate_user_password($password_submitted, $client_pass_salt, $client_password)) {
//            switch($ifx_acc_type) {
//                case '1': $page_requested = 'deposit_funds_qty_ilpr_php'; break; // ILPR Account
//                case '2': $page_requested = 'deposit_funds_qty_nonilpr_php'; break; // Non-ILPR Account
//            }

            $page_requested = 'deposit_funds_notice_php';
        } else {
            $page_requested = 'deposit_funds_pcode_php';
            $message_error = "You have entered an incorrect pass code";
            // Limit number of pass code trial to a specified number to avoid abuse
        }
    } else {
        $message_error = "Something went wrong, please try again.";
    }    
}

// This section processes - views/deposit_funds_notice.php
if(isset($_POST['deposit_funds_notice'])) {
    $account_no = $db_handle->sanitizePost($_POST['ifx_acct_no']);

    $client_operation = new clientOperation($account_no);
    $user_ifx_details = $client_operation->get_client_data();

    if($user_ifx_details) {
        extract($user_ifx_details);
        switch($ifx_acc_type) {
            case '1': $page_requested = 'deposit_funds_qty_ilpr_php'; break; // ILPR Account
            case '2': $page_requested = 'deposit_funds_qty_nonilpr_php'; break; // Non-ILPR Account
        }
    } else {
        $message_error = "Something went wrong, please try again.";
    }
}

// This section processes - views/deposit_funds_kyc.php
if(isset($_POST['deposit_funds_kyc'])) {
    $page_requested = 'deposit_funds_kyc_php';
    
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
                    $page_requested = 'deposit_funds_pcode_new_php'; // No associated pass code
                } else {
                    $page_requested = 'deposit_funds_pcode_php'; // Continue, has pass code
                }
            }

        }
    }
}

// This section processes - views/deposit_funds_qty_ilpr.php and views/deposit_funds_qty_nonilpr.php
if(isset($_POST['deposit_funds_qty_ilpr']) || isset($_POST['deposit_funds_qty_nonilpr'])) {
    
    if(isset($_POST['deposit_funds_qty_ilpr'])) { $page_requested = 'deposit_funds_qty_ilpr_php'; }
    if(isset($_POST['deposit_funds_qty_nonilpr'])) { $page_requested = 'deposit_funds_qty_nonilpr_php'; }
    
    $account_no = $db_handle->sanitizePost($_POST['ifx_acct_no']);
    $ifx_dollar_amount = $db_handle->sanitizePost($_POST['ifx_dollar_amount']);

    if(isset($_POST['point_claimed'])) {
        $point_claimed = $db_handle->sanitizePost($_POST['point_claimed']);

        $point_claimed = (float)$point_claimed;

        $point_rate_used = DOLLAR_PER_POINT;
        $point_dollar_amount = $point_claimed * $point_rate_used;
    }

    $client_operation = new clientOperation($account_no);
    $user_ifx_details = $client_operation->get_client_data();
    extract($user_ifx_details);

    $client_point_details = $obj_loyalty_point->get_user_point_details($client_user_code);
    $total_point_balance = $client_point_details['point_balance'];
    
    $client_verification = $client_operation->get_client_verification_status($client_user_code);
    $client_full_name = $client_last_name . " " . $client_first_name;

    switch($ifx_acc_type) {
        case '1': // ILPR Account
            $ifx_naira_amount = IPLRFUNDRATE * $ifx_dollar_amount;
            $exchange = IPLRFUNDRATE;
            break;
        case '2': // Non-ILPR Account
            $ifx_naira_amount = NFUNDRATE * $ifx_dollar_amount;
            $exchange = NFUNDRATE;
            break;
        default: 
            $ifx_naira_amount = NFUNDRATE * $ifx_dollar_amount;
            $exchange = NFUNDRATE;
    }

    if($client_verification == '1') {
        // confirm total funding orders today, if > 2000, disallow this order
        if($client_operation->deposit_limit_exceeded($client_user_code, $ifx_dollar_amount)) {
            $message_error = "You are unable to complete this process now because you have not verified your account. Please <a href='verify_account.php'>click here to verify your account.</a> ";
            $message_error .= "<br />Your address, valid ID, Passport Photograph and your Signature is required for verification.";
            //$message_error .= "The requested amount will make your total funding order today exceed the allowed daily limit of $" . LEVEL_ONE_MAX_PER_DEPOSIT;
            //$message_error .= "<br />To fund without limits please verify your account by <a href='verify_account.php'> clicking here</a> or reduce your funding order.<br />";
        } else {
            $max_per_deposit = LEVEL_ONE_MAX_PER_DEPOSIT;
            if($ifx_dollar_amount < FUNDING_MIN_VALUE || $ifx_dollar_amount > $max_per_deposit) {
                $message_error = "Please re-enter amount. Minimum order is $" . FUNDING_MIN_VALUE . " and maximum order is $" . number_format($max_per_deposit) . " per transaction.";
            } elseif((isset($point_claimed) && !is_null($point_claimed)) && ($point_claimed > $total_point_balance)) {
                $message_error = "You can not redeem more than your point balance.";
            } elseif((isset($point_claimed) && !is_null($point_claimed) && !empty($point_claimed)) && ($point_claimed < 100)) {
                $message_error = "You can not redeem less than 100 points.";
            } else {
                $allow_funding = 1;
            }
        }
    } elseif($client_verification == '2' || $client_verification == '3') {
        $max_per_deposit = LEVEL_TWO_MAX_PER_DEPOSIT;
        if($ifx_dollar_amount < FUNDING_MIN_VALUE || $ifx_dollar_amount > $max_per_deposit) {
            $message_error = "Please re-enter amount. Minimum order is $" . FUNDING_MIN_VALUE . " and maximum order is $" . number_format($max_per_deposit) . " per transaction. Please split your transaction if you wish to fund more.";
        } elseif((isset($point_claimed) && !is_null($point_claimed)) && ($point_claimed > $total_point_balance)) {
            $message_error = "You can not redeem more than your point balance.";
        } elseif((isset($point_claimed) && !is_null($point_claimed) && !empty($point_claimed)) && ($point_claimed < 100)) {
            $message_error = "You can not redeem less than 100 points.";
        } else {
            $allow_funding = 1;
        }
    } else {
        $message_error = "Something went wrong, we could not determine the details of this order, please contact the support department.";
    }

    // If point is claimed, client must fund equivalent of point claimed or higher
    $funding_amount = floatval($ifx_dollar_amount);
    if(isset($point_claimed) && ($funding_amount < $point_dollar_amount)) {
        $allow_funding = 0;
        $message_error = "You are about to claim points worth $ $point_dollar_amount, you must fund minimum of $ $point_dollar_amount. Please try again to proceed.";
    }

    if($allow_funding == 1) {
        if(isset($point_claimed) && !empty($point_claimed)) {
            $point_claimed_id = $client_operation->set_point_claimed($point_claimed, $client_user_code);
        }

        $origin_of_deposit = '1'; // Originates online
        $stamp_duty = CBN_STAMP_DUTY;
        $trans_id = "D" . time();
        $trans_id_encrypted = encrypt($trans_id);
        $service_charge = $ifx_naira_amount * DSERVCHARGE;
        $vat = $service_charge * DVAT;
        $total_payable = CBN_STAMP_DUTY + $vat + $service_charge + $ifx_naira_amount;
        $total_payable = number_format($total_payable, 2, ".", "");

        if(!empty($point_claimed_id)) {
            $log_deposit = $client_operation->log_deposit($trans_id, $ifx_acc_id, $exchange, $ifx_dollar_amount, $ifx_naira_amount, $service_charge, $vat, $stamp_duty, $total_payable, $origin_of_deposit, $point_claimed_id);
        } else {
            $log_deposit = $client_operation->log_deposit($trans_id, $ifx_acc_id, $exchange, $ifx_dollar_amount, $ifx_naira_amount, $service_charge, $vat, $stamp_duty, $total_payable, $origin_of_deposit);
        }

        if($log_deposit) {
            $obj_loyalty_point->user_total_point_balance($client_user_code);
            $page_requested = 'deposit_funds_finalize_php';
        } else {
            $message_error = "Something went wrong, please try again.";
            $page_requested = "";
        }
    }
}

// This section processes - views/deposit_funds_finalize.php
if(isset($_POST['deposit_funds_finalize'])) {
    $trans_id_encrypted = $db_handle->sanitizePost($_POST['transaction_no']);
    $trans_id = decrypt(str_replace(" ", "+", $trans_id_encrypted));
    $trans_id = preg_replace("/[^A-Za-z0-9 ]/", '', $trans_id);
    
    $client_operation = new clientOperation();
    $transaction = $client_operation->get_deposit_by_id_mini($trans_id);

    if($transaction)
    {
        extract($transaction);
        $page_requested = 'deposit_funds_pay_type_php';
        $trans_id_encrypted = encrypt($client_trans_id);

    } else {
        $message_error = "Something went wrong, please try again.";
        $page_requested = "";
    }
}

// This section processes - views/deposit_funds_pay_type.php
if(isset($_POST['deposit_funds_pay_type'])) {
    $client_operation = new clientOperation();

    $trans_id_encrypted = $db_handle->sanitizePost($_POST['transaction_no']);
    $trans_id = decrypt(str_replace(" ", "+", $trans_id_encrypted));
    $trans_id = preg_replace("/[^A-Za-z0-9 ]/", '', $trans_id);
    
    $pay_type = $db_handle->sanitizePost($_POST['pay_type']);
    $client_operation->log_deposit_pay_method($trans_id, $pay_type); // Update payment method selected

    $transaction = $client_operation->get_deposit_by_id_mini($trans_id);
    extract($transaction);
    
    $client_full_name = $client_first_name . " " . $client_last_name;
    $formated_client_naira_total = number_format($client_naira_total, 2);

    $client_operation->notify_admin(0, $trans_id, 30, $client_full_name); //Notify an admin
    
    switch ($pay_type) {
        case '1':
            $page_requested = "deposit_funds_pay_type_card_php";
            break;
        default :
            $invoice_msg = $client_operation->send_order_invoice($client_full_name, $client_email, $client_trans_id, $client_account, $client_dollar, $formated_client_naira_total, $pay_type);
            $page_requested = "deposit_funds_pay_type_bank_php";
    }
}

switch($page_requested) {
    case '': $deposit_funds_ifx_acct_php = true; break;
    case 'deposit_funds_pcode_php': $deposit_funds_pcode_php = true; break;
    case 'deposit_funds_notice_php': $deposit_funds_notice_php = true; break;
    case 'deposit_funds_pcode_new_php': $deposit_funds_pcode_new_php = true; break;
    case 'deposit_funds_kyc_php': $deposit_funds_kyc_php = true; break;
    case 'deposit_funds_qty_ilpr_php': $deposit_funds_qty_ilpr_php = true; break;
    case 'deposit_funds_qty_nonilpr_php': $deposit_funds_qty_nonilpr_php = true; break;
    case 'deposit_funds_finalize_php': $deposit_funds_finalize_php = true; break;
    case 'deposit_funds_pay_type_php': $deposit_funds_pay_type_php = true; break;
    case 'deposit_funds_pay_type_bank_php': $deposit_funds_pay_type_bank_php = true; break;
    case 'deposit_funds_pay_type_card_php': $deposit_funds_pay_type_card_php = true; break;
    default: $deposit_funds_ifx_acct_php = true;
}

?>
<!DOCTYPE html>
<html lang="en">
    <head>
        <base target="_self">
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>Instaforex Nigeria | Deposit Funds in Naira</title>
        <meta name="title" content="Instaforex Nigeria | Deposit Funds in Naira" />
        <meta name="keywords" content="instaforex, deposit funds into trading account, how to trade forex, trade forex, instaforex nigeria.">
        <meta name="description" content="Deposit Funds into your instaforex trading account">
        <?php require_once 'layouts/head_meta.php'; ?>
        <script>
            function checkInp() {
                var x=document.forms["enter_amount"]["ifx_dollar_amount"].value;
                if (isNaN(x)) {
                    alert("Must input numbers");
                    return false;
                } else if (x < <?php echo FUNDING_MIN_VALUE; ?>) {
                    alert("Minimum Funding is $<?php echo FUNDING_MIN_VALUE; ?>");
                    return false;
                }
            }
        </script>
    </head>
    <body onload="document.submit2gtpay_form.submit()">
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

<!--                            --><?php //if(!$additional_msg) { ?>
<!--                                <div class="col-sm-12">-->
<!--                                    <h4>ATTENTION!!!</h4>-->
<!--                                    <p>Instant Card Payment is Back!</p>-->
<!--                                    <p>Enjoy a more convenient and swift experience funding your account!  With Instant card payment, your transactions are now fast, easy, and more secured!</p>-->
<!--                                </div>-->
<!--                            --><?php //} ?>

                            <?php if($additional_msg != 'msg_new'): ?>
                                <div class="col-sm-12 text-danger">

                                    <?php if(!$special_msg) { ?>
                                        <h4><strong>Fund Your Instaforex Account</strong></h4>
                                    <?php } else { ?>
                                        <h4><strong>Make Over 1000 pips more before September runs out!!!</strong></h4>
                                    <?php } ?>

                                </div>
                            <?php endif; ?>

                        </div>
                        <div class="row">
                            <div class="col-sm-12">
                                <?php require_once 'layouts/feedback_message.php'; ?>
                                                                
                                <?php
                                    if($deposit_funds_ifx_acct_php) { include_once 'views/deposit_funds/deposit_funds_ifx_acct.php'; }
                                    if($deposit_funds_pcode_php) { include_once 'views/deposit_funds/deposit_funds_pcode.php'; }
                                    if($deposit_funds_notice_php) { include_once 'views/deposit_funds/deposit_funds_notice.php'; }
                                    if($deposit_funds_pcode_new_php) { include_once 'views/deposit_funds/deposit_funds_pcode_new.php'; }
                                    if($deposit_funds_kyc_php) { include_once 'views/deposit_funds/deposit_funds_kyc.php'; }
                                    if($deposit_funds_qty_ilpr_php) { include_once 'views/deposit_funds/deposit_funds_qty_ilpr.php'; }
                                    if($deposit_funds_qty_nonilpr_php) { include_once 'views/deposit_funds/deposit_funds_qty_nonilpr.php'; }
                                    if($deposit_funds_finalize_php) { include_once 'views/deposit_funds/deposit_funds_finalize.php'; }
                                    if($deposit_funds_pay_type_php) { include_once 'views/deposit_funds/deposit_funds_pay_type.php'; }
                                    if($deposit_funds_pay_type_bank_php) { include_once 'views/deposit_funds/deposit_funds_pay_type_bank.php'; }
                                    if($deposit_funds_pay_type_card_php) { include_once 'views/deposit_funds/deposit_funds_pay_type_card.php'; }
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