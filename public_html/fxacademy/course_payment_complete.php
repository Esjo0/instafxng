<?php
require_once '../init/initialize_client.php';
$thisPage = "";

if (!$session_client->is_logged_in()) {
    redirect_to("login.php");
}

$page_requested = "";

$get_params = allowed_get_params(['trans_id']);
$trans_id = $get_params['trans_id'];

if(isset($get_params['trans_id']) && !empty($_REQUEST)) {
    
    // Select the name and email associated with the transaction
    $query = "SELECT * FROM user_edu_deposits WHERE trans_id = '$trans_id' LIMIT 1";
    $result = $db_handle->runQuery($query);
    $selected_deposit = $db_handle->fetchAssoc($result);
    extract($selected_deposit[0]);

    // Get the details of this client
    $client_details = $education_object->get_client_detail_by_code($user_code);
    $client_first_name = $client_details['first_name'];
    $client_email = $client_details['email'];
    
    // Extract GTPay gateway response
    extract($_REQUEST);

    if($gtpay_tranx_status_code == '00') {
        $page_requested = "course_payment_completed_php";

        if($db_handle->numOfRows($result) > 0) {
            // Update the transaction
            $client_naira_notified = $gtpay_tranx_amt;

            if($status == '1') {
                $query = "UPDATE user_edu_deposits SET status = '2', pay_date = NOW(), amount_paid = $client_naira_notified WHERE trans_id = '$trans_id' LIMIT 1";

                $db_handle->runQuery($query);

                $formated_client_naira_notified = number_format($client_naira_notified, 2);

                // Send a success email to the client
                $subject = "Your Forex Profit Optimizer Payment Was Approved";
                $body =
<<<MAIL
<div style="background-color: #F3F1F2">
    <div style="max-width: 80%; margin: 0 auto; padding: 10px; font-size: 14px; font-family: Verdana;">
        <img src="https://instafxng.com/images/ifxlogo.png" />
        <hr />
        <div style="background-color: #FFFFFF; padding: 15px; margin: 5px 0 5px 0;">
            <p>Dear $client_first_name,</p>

            <p>Your payment has been approved and your payment notification has been submitted
            successfully, Your Forex Profit Optimizer Course will be activated shortly.<br /></p>

            <p>Amount: N $formated_client_naira_notified</p>
            <p>Transaction ID: $trans_id</p>

            <p>If you have any questions, please contact our support desk at <a href='https://instafxng.com/contact_info.php'>https://instafxng.com/contact_info.php</a>
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
                    <a href="https://facebook.com/InstaForexNigeria"><img src="https://instafxng.com/images/Facebook.png"></a>
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
                $system_object->send_email($subject, $body, $client_email, $client_first_name);
            }

        } else {
            redirect_to("https://instafxng.com/fxacademy");
        }
    } else {
        $page_requested = "course_payment_failed_php";

        // This is a failed transaction
        // Update the transaction
        $query = "UPDATE user_edu_deposits SET status = '5' WHERE trans_id = '$trans_id' LIMIT 1";
        $db_handle->runQuery($query);

        $client_naira_notified_formated = number_format($client_naira_notified, 2);

        // Send a failure email to the client
        $subject = "Your Forex Optimizer Course Payment Was Not Successful";
        $body =
<<<MAIL
<div style="background-color: #F3F1F2">
    <div style="max-width: 80%; margin: 0 auto; padding: 10px; font-size: 14px; font-family: Verdana;">
        <img src="https://instafxng.com/images/ifxlogo.png" />
        <hr />
        <div style="background-color: #FFFFFF; padding: 15px; margin: 5px 0 5px 0;">
            <p>Dear $client_first_name,</p>

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
                    <a href="https://facebook.com/InstaForexNigeria"><img src="https://instafxng.com/images/Facebook.png"></a>
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
        $system_object->send_email($subject, $body, $client_email, $client_first_name);
    }
} else {
    redirect_to("https://instafxng.com/fxacademy");
}

switch($page_requested) {
    case '': $course_payment_completed_php = true; break;
    case 'course_payment_failed_php': $course_payment_failed_php = true; break;
    default: $course_payment_completed_php = true;
}

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <base target="_self">
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Instaforex Nigeria</title>
    <meta name="title" content="" />
    <meta name="keywords" content="">
    <meta name="description" content="">
    <?php require_once 'layouts/head_meta.php'; ?>
</head>
<body>
<?php require_once 'layouts/header.php'; ?>

<!-- Main Body: The is the main content area of the web site, contains a side bar  -->
<div id="main-body" class="container-fluid">
    <div class="row no-gutter">

        <!-- Main Body - Content Area: This is the main content area, unique for each page  -->
        <div id="main-body-content-area" class="col-md-12">

            <!-- Unique Page Content Starts Here
            ================================================== -->
            <?php require_once 'layouts/navigation.php'; ?>

            <div id="main-container" class="section-tint super-shadow">
                <div class="row">
                    <div class="col-md-12">
                        <h5 class="text-center text-danger">Course Payment</h5>
                        <hr />
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-12">

                        <?php require_once 'layouts/feedback_message.php'; ?>

                        <?php
                        if($course_payment_completed_php) { include_once 'views/course_payment_completed.php'; }
                        if($course_payment_failed_php) { include_once 'views/course_payment_failed.php'; }
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