<?php
require_once("../init/initialize_admin.php");
if (!$session_admin->is_logged_in()) {
    redirect_to("login.php");
}

$page_requested = "";

$client_operation = new clientOperation();

$partner = new Partner();
$get_params = allowed_get_params(['id']);

$partner_code = trim($get_params['id']);

$partn_details = $partner->get_partner_by_partner_code($partner_code);
$user_code = $partnstatus['user_code'];
$user_detail = $client_operation->get_user_by_user_code($user_code);
extract($user_detail);
$full_name = $last_name . " " . $middle_name . " " . $first_name;

if (isset($_POST['update_partn_details']) ) {
    $ifxaccount = $db_handle->sanitizePost($_POST['ifxaccount']);
    $user_code = $db_handle->sanitizePost($_POST['user_code']);
    $partner_code = $db_handle->sanitizePost($_POST['partner_code']);
    $amount2pay = $db_handle->sanitizePost($_POST['amount2pay']);
    $comment = $db_handle->sanitizePost($_POST['comment']);

    $updpartn = $partner->pay_partner($user_code,$partner_code, $ifxaccount, $amount2pay, $comment);

    if($updpartn) {
        $message_success = "You have successfully paid the partner";
    } else {
        $message_error = "Something went wrong. Please try again.";
    }
}

?>
<!DOCTYPE html>
<html lang="en">
    <head>
        <base target="_self">
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>Instaforex Nigeria | Admin - Partner Payout History Details</title>
        <meta name="title" content="Instaforex Nigeria | Admin - Partner Payout History Details" />
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
                            <h4><strong>PARTNER PAYOUT HISTORY DETAILS</strong></h4>
                        </div>
                    </div>
                    
                    <div class="section-tint super-shadow">
                        <div class="row">
                            <div class="col-sm-12">
                                <?php require_once 'layouts/feedback_message.php'; ?>
                                                                
                                <?php
                                    include_once 'views/partner_manage/pay_partners.php';
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