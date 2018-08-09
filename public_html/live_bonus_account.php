<?php
require_once 'init/initialize_general.php';
$thisPage = "";
$bonus_operations = new Bonus_Operations();
$package_code = decrypt_ssl(str_replace(" ", "+", $_GET['pc']));
$package_details = $bonus_operations->get_package_by_code($package_code);

$page_requested = '';

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
    $bonus_code = $db_handle->sanitizePost(trim($_POST['bonus_code']));

    //ToDo: Reverse this...
    if(!$responseData->success) {
        $message_error = "You did not pass the robot verification, please try again.";
    } elseif(empty($full_name) || empty($email_address) || empty($phone_number) || empty($account_no) || empty($bonus_code)) {
        $message_error = "All fields must be filled.";
    } elseif (!check_email($email_address)) {
        $message_error = "You have provided an invalid email address. Please try again.";
    } else {
        $log_new_ifxaccount = $bonus_operations->new_bonus_application($account_no, $full_name, $email_address, $phone_number, $bonus_code);
        if($log_new_ifxaccount) {
            $page_requested = "live_account_completed_php";
        } else {
            $message_error = "This account could not be enrolled here, please contact support for more details.";
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
        <title>Instaforex Nigeria | <?php echo $package_details['bonus_title'] ?></title>
        <meta name="title" content="Instaforex Nigeria | <?php echo $package_details['bonus_title'] ?>" />
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
                                <h4><strong>Open A Bonus Trading Account</strong></h4>
                            </div>
                        </div>
                        
                        <div class="row">
                            <div class="col-sm-12">
                                <?php require_once 'layouts/feedback_message.php'; ?>
                                <?php 
                                    if($live_account_info_php) { include_once 'views/live_bonus_account/live_bonus_account_info.php'; }
                                    if($live_account_open_php) { include_once 'views/live_bonus_account/live_bonus_account_open.php'; }
                                    if($live_account_ilpr_reg_php) { include_once 'views/live_bonus_account/live_bonus_account_reg.php'; }
                                    if($live_account_completed_php) { include_once 'views/live_bonus_account/live_bonus_account_completed.php'; }
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