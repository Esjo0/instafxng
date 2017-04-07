<?php
require_once("../init/initialize_admin.php");
if (!$session_admin->is_logged_in()) {
    redirect_to("login.php");
}

$page_requested = "";
$client_operation = new clientOperation();

// This section processes - views/client_add/client_add_email.php
if (isset($_POST['client_add_email'])) {
    
    $client_email = $db_handle->sanitizePost($_POST['client_email']);

    if(check_email($client_email)) {
        $check_details = $client_operation->get_user_by_email($client_email);

        if($check_details) {
            $user_code = $check_details['user_code'];
            $user_detail = $client_operation->get_user_by_user_code($user_code);
            extract($user_detail);

            $full_name = $last_name . " " . $middle_name . " " . $first_name;
            $page_requested = "client_add_ifx_acct_php";
        } else {
            $page_requested = "client_add_details_php";
        }
    } else {
        $message_error = "You have entered an invalid email address, please try again.";
    }
    

}

// This section processes - views/client_add/client_add_ifx_acct.php and views/client_add/client_add_details.php
if (isset($_POST['client_add_ifx_acct']) || isset($_POST['client_add_details'])) {
    $ifx_acct_no = $db_handle->sanitizePost($_POST['ifx_acct_no']);
    $email_address = $db_handle->sanitizePost($_POST['email_address']);
    $client_name = $db_handle->sanitizePost($_POST['client_name']);
    $phone_number = $db_handle->sanitizePost($_POST['phone_number']);
    
    if($client_operation->ifx_account_is_duplicate($ifx_acct_no)) {
        $message_error = "You have entered an Instaforex Account that is already existing.";
    } else {
        $new_user = $client_operation->new_user($ifx_acct_no, $client_name, $email_address, $phone_number, $type = 2);
        if($new_user) {
            $message_success = "You have successfully added a new client to the system";
        } else {
            $message_error = "Something went wrong. Please try again.";
        }
    }
}

switch($page_requested) {
    case '': $client_add_email_php = true; break;
    case 'client_add_ifx_acct_php': $client_add_ifx_acct_php = true; break;
    case 'client_add_details_php': $client_add_details_php = true; break;
    default: $client_add_email_php = true;
}

?>
<!DOCTYPE html>
<html lang="en">
    <head>
        <base target="_self">
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>Instaforex Nigeria | Admin - Add Client</title>
        <meta name="title" content="Instaforex Nigeria | Admin - Add Client" />
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
                            <h4><strong>ADD CLIENT</strong></h4>
                        </div>
                    </div>
                    
                    <div class="section-tint super-shadow">
                        <div class="row">
                            <div class="col-sm-12">
                                <?php require_once 'layouts/feedback_message.php'; ?>
                                                                
                                <?php 
                                    if($client_add_email_php) { include_once 'views/client_add/client_add_email.php'; }
                                    if($client_add_ifx_acct_php) { include_once 'views/client_add/client_add_ifx_acct.php'; }
                                    if($client_add_details_php) { include_once 'views/client_add/client_add_details.php'; }
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