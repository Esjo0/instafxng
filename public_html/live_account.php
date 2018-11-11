<?php
require_once 'init/initialize_general.php';
$thisPage = "Account";
$source = $_GET['id'];
$page_requested = "";

// This section processes - views/live_account_info.php
if(isset($_POST['live_account_info'])) {
    $page_requested = "live_account_open_php";
}

// This section processes - views/live_account_open.php
if(isset($_POST['live_account_open'])) {
    $page_requested = "live_account_ilpr_reg_php";
}

// This section processes - views/live_account_ilpr_reg.php
if(isset($_POST['live_account_ilpr_reg'])) {
    $page_requested = "live_account_ilpr_reg_php";
    
    $secret = '6LcKDhATAAAAALn9hfB0-Mut5qacyOxxMNOH6tov';
    $verifyResponse = file_get_contents('https://www.google.com/recaptcha/api/siteverify?secret='.$secret.'&response='.$_POST['g-recaptcha-response']);
    $responseData = json_decode($verifyResponse);
    
    $account_no = $db_handle->sanitizePost(trim($_POST['ifx_acct_no']));
    $full_name = $db_handle->sanitizePost(trim($_POST['full_name']));
    $email_address = $db_handle->sanitizePost(trim($_POST['email']));
    $phone_number = $db_handle->sanitizePost(trim($_POST['phone']));

    if(!$responseData->success) {
        $message_error = "You did not pass the robot verification, please try again.";
    } else
        if(empty($full_name) || empty($email_address) || empty($phone_number) || empty($account_no)) {
        $message_error = "All fields must be filled.";
    } elseif (!check_email($email_address)) {
        $message_error = "You have provided an invalid email address. Please try again.";
    } else {

        $client_operation = new clientOperation();
        $log_new_ifxaccount = $client_operation->new_user($account_no, $full_name, $email_address, $phone_number, $type = 2, $my_refferer);

        if($log_new_ifxaccount) {
            if(isset($_COOKIE['ifxng_tweeter_lead'])){
                $name = split_name($full_name);
                extract($name);
                $query = "INSERT INTO campaign_leads (f_name, l_name, email, phone, source, interest, created) VALUE ('$first_name', '$last_name', '$email_address', '$phone_number', '4', '2', now())";
                $result = $db_handle->runQuery($query);
                $user = $client_operation->get_user_by_email($email);
                $user = encrypt($user);
                header('Location: https://instafxng.com/tweeter_campaign.php?x='.$user);
            }
            $page_requested = "live_account_completed_php";
        } else {
            $message_error = "This account could not be enrolled here, please contact support to enrol for ILPR.";
        }
    }
}

switch($page_requested) {
    case '': $live_account_info_php = true; break;
    case 'live_account_open_php': $live_account_open_php = true; break;
    case 'live_account_ilpr_reg_php': $live_account_ilpr_reg_php = true; break;
    case 'live_account_completed_php': $live_account_completed_php = true; break;
    default: $live_account_info_php = true;
}

?>
<!DOCTYPE html>
<html lang="en">
    <head>
        <base target="_self">
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>Instaforex Nigeria | Open a Live Account</title>
        <meta name="title" content="Instaforex Nigeria | Open a Live Account" />
        <meta name="keywords" content="instaforex nigeria, open a live account,">
        <meta name="description" content="Learn how to trade forex, get free information about the forex market in our forex trading seminars.">
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
                                <h4><strong>Open Live Trading Account</strong></h4>
                            </div>
                        </div>
                        
                        <div class="row">
                            <div class="col-sm-12">
                                <?php require_once 'layouts/feedback_message.php'; ?>
                                                                
                                <?php 
                                    if($live_account_info_php) { include_once 'views/live_account/live_account_info.php'; }
                                    if($live_account_open_php) { include_once 'views/live_account/live_account_open.php'; }
                                    if($live_account_ilpr_reg_php) { include_once 'views/live_account/live_account_ilpr_reg.php'; }
                                    if($live_account_completed_php) { include_once 'views/live_account/live_account_completed.php'; }
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
        <script src='https://www.google.com/recaptcha/api.js'></script>
    </body>
</html>