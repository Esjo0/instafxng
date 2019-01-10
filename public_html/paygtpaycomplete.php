<?php
require_once 'init/initialize_general.php';
$thisPage = "Traders";

$client_operation = new clientOperation();

$page_requested = "";

$get_params = allowed_get_params(['trans_id']);
$trans_id = $get_params['trans_id'];

$payment_date = date("d-M-Y");

if(isset($get_params['trans_id']) && !empty($_REQUEST)) {
    
    // Select the name and email associated with the transaction
    $query = "SELECT ud.user_deposit_id, ud.trans_id, ud.naira_total_payable, ud.status, u.email, u.first_name
            FROM user_deposit AS ud
            INNER JOIN user_ifxaccount AS ui ON ud.ifxaccount_id = ui.ifxaccount_id
            INNER JOIN user AS u ON ui.user_code = u.user_code
            WHERE ud.trans_id = '$trans_id' LIMIT 1";
    $result = $db_handle->runQuery($query);
    $selected_deposit = $db_handle->fetchAssoc($result);
    extract($selected_deposit[0]);
    
    // Extract GTPay gateway response
    extract($_REQUEST);
    
    // Log Deposit Meta Data Returned from payment gateway
    $log_deposit_meta = $client_operation->log_deposit_meta($user_deposit_id, $gtpay_tranx_status_code, $gtpay_tranx_status_msg, $gtpay_tranx_amt, $gtpay_tranx_curr, $gtpay_gway_name, $gtpay_full_verification_hash);

    if($gtpay_tranx_status_code == '00') {

        if($db_handle->numOfRows($result) > 0) {
            // Update the transaction
            $client_naira_notified = $gtpay_tranx_amt;

            if($status == '1') {
                $query = "UPDATE user_deposit SET client_naira_notified = '$client_naira_notified', client_pay_date = NOW(), client_pay_method = '1', client_notified_date = NOW(), status = '2' WHERE trans_id = '$trans_id' LIMIT 1";

                $db_handle->runQuery($query);

                $formated_client_naira_notified = number_format($client_naira_notified, 2);

                // Send a success email to the client
                $subject = "Your Payment Was Approved";
                $body =
<<<MAIL
<div style="background-color: #F3F1F2">
    <div style="max-width: 80%; margin: 0 auto; padding: 10px; font-size: 14px; font-family: Verdana;">
        <img src="https://instafxng.com/images/ifxlogo.png" />
        <hr />
        <div style="background-color: #FFFFFF; padding: 15px; margin: 5px 0 5px 0;">
            <p>Dear $first_name,</p>

            <p>Your payment has been approved and your payment notification has been submitted
            successfully, Your InstaForex account will be funded shortly.<br /></p>

            <p>Amount: N $formated_client_naira_notified</p>
            <p>Transaction ID: $trans_id</p>

            <p>Funding will be completed within 5 minutes to 2 hours on work days. Fundings
            normally are done same day. If there is any delay we will inform you.</p>

            <p>If you have any questions, please contact our support desk at https://instafxng.com/contact_info.php
            And please mention your transaction ID: $trans_id when you call.</p>

            <p>Thank you for using our services.</p>

            <br /><br />
            <p>Best Regards,</p>
            <p>Instafxng Support,<br />
                www.instafxng.com</p>
            <br /><br />
        </div>
        <hr />
        <div style="background-color: #EBDEE9;">
            <div style="font-size: 11px !important; padding: 15px;">
                <p style="text-align: center"><span style="font-size: 12px"><strong>We're Social</strong></span><br /><br />
                    <a href="https://facebook.com/InstaFxNg"><img src="https://instafxng.com/images/Facebook.png"></a>
                    <a href="https://twitter.com/instafxng"><img src="https://instafxng.com/images/Twitter.png"></a>
                    <a href="https://www.instagram.com/instafxng/"><img src="https://instafxng.com/images/instagram.png"></a>
                    <a href="https://www.youtube.com/channel/UC0Z9AISy_aMMa3OJjgX6SXw"><img src="https://instafxng.com/images/Youtube.png"></a>
                    <a href="https://linkedin.com/company/instaforex-ng"><img src="https://instafxng.com/images/LinkedIn.png"></a>
                </p>
                <p><strong>Head Office Address:</strong> TBS Place, Block 1A, Plot 8, Diamond Estate, Estate Bus-Stop, LASU/Isheri road, Isheri Olofin, Lagos.</p>
                <p><strong>Lekki Office Address:</strong> Block A3, Suite 508/509 Eastline Shopping Complex, Opposite Abraham Adesanya Roundabout, along Lekki - Epe expressway, Lagos.</p>
                <p><strong>Office Number:</strong> 08028281192</p>
                <br />
            </div>
            <div style="font-size: 10px !important; padding: 15px; text-align: center;">
                <p>This email was sent to you by Instant Web-Net Technologies Limited, the
                    official Nigerian Representative of Instaforex, operator and administrator
                    of the website www.instafxng.com</p>
                <p>To ensure you continue to receive special offers and updates from us,
                    please add support@instafxng.com to your address book.</p>
            </div>
        </div>
    </div>
</div>
MAIL;
                $system_object->send_email($subject, $body, $email, $first_name);
            }

        } else {
            header("Location: deposit.php");
        }
    } else {
        // This is a failed transaction
        $page_requested = "deposit_funds_failed_php";
        
        // Update the transaction
        $query = "UPDATE user_deposit SET client_naira_notified = '$client_naira_notified', client_pay_date = NOW(), client_pay_method = '1', client_notified_date = NOW(), status = '9', client_comment = '$gtpay_tranx_status_msg' WHERE trans_id = '$trans_id' LIMIT 1";
        $db_handle->runQuery($query);

        $client_naira_notified_formated = number_format($client_naira_notified, 2);

        // Send a failure email to the client
        $subject = "Your Payment Was Not Successful";
        $body =
<<<MAIL
<div style="background-color: #F3F1F2">
    <div style="max-width: 80%; margin: 0 auto; padding: 10px; font-size: 14px; font-family: Verdana;">
        <img src="https://instafxng.com/images/ifxlogo.png" />
        <hr />
        <div style="background-color: #FFFFFF; padding: 15px; margin: 5px 0 5px 0;">
            <p>Dear $first_name,</p>

            <p>Your payment was not successful, see details below.</p>

            <p>Amount: N $client_naira_notified_formated</p>
            <p>Transaction ID: $trans_id</p>
            <p>Reason: $gtpay_tranx_status_msg</p>

            <p>If you have questions about your order, please contact our support desk at https://instafxng.com/contact_info.php
            And please mention your transaction ID: $trans_id when you call.</p>

            <p>Thank you for using our services.</p>

            <br /><br />
            <p>Best Regards,</p>
            <p>Instafxng Support,<br />
                www.instafxng.com</p>
            <br /><br />
        </div>
        <hr />
        <div style="background-color: #EBDEE9;">
            <div style="font-size: 11px !important; padding: 15px;">
                <p style="text-align: center"><span style="font-size: 12px"><strong>We're Social</strong></span><br /><br />
                    <a href="https://facebook.com/InstaFxNg"><img src="https://instafxng.com/images/Facebook.png"></a>
                    <a href="https://twitter.com/instafxng"><img src="https://instafxng.com/images/Twitter.png"></a>
                    <a href="https://www.instagram.com/instafxng/"><img src="https://instafxng.com/images/instagram.png"></a>
                    <a href="https://www.youtube.com/channel/UC0Z9AISy_aMMa3OJjgX6SXw"><img src="https://instafxng.com/images/Youtube.png"></a>
                    <a href="https://linkedin.com/company/instaforex-ng"><img src="https://instafxng.com/images/LinkedIn.png"></a>
                </p>
                <p><strong>Head Office Address:</strong> TBS Place, Block 1A, Plot 8, Diamond Estate, Estate Bus-Stop, LASU/Isheri road, Isheri Olofin, Lagos.</p>
                <p><strong>Lekki Office Address:</strong> Block A3, Suite 508/509 Eastline Shopping Complex, Opposite Abraham Adesanya Roundabout, along Lekki - Epe expressway, Lagos.</p>
                <p><strong>Office Number:</strong> 08028281192</p>
                <br />
            </div>
            <div style="font-size: 10px !important; padding: 15px; text-align: center;">
                <p>This email was sent to you by Instant Web-Net Technologies Limited, the
                    official Nigerian Representative of Instaforex, operator and administrator
                    of the website www.instafxng.com</p>
                <p>To ensure you continue to receive special offers and updates from us,
                    please add support@instafxng.com to your address book.</p>
            </div>
        </div>
    </div>
</div>
MAIL;
        $system_object->send_email($subject, $body, $email, $first_name);
    }
} else {
    header("Location: deposit.php");
}

switch($page_requested) {
    case '': $deposit_funds_completed_php = true; break;
    case 'deposit_funds_failed_php': $deposit_funds_failed_php = true; break;
    default: $deposit_funds_completed_php = true;
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
                                <h4><strong>Fund Your Instaforex Account</strong></h4>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-sm-12">
                                                                
                                <?php 
                                    if($deposit_funds_completed_php) { include_once 'views/deposit_funds/deposit_funds_completed.php'; }
                                    if($deposit_funds_failed_php) { include_once 'views/deposit_funds/deposit_funds_failed.php'; }
                                ?>
                                <!--<pre><?php // var_dump($_REQUEST); ?></pre>-->
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