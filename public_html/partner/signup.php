<?php
require_once '../init/initialize_general.php';
require_once '../init/initialize_partner.php';

$thisPage = "Home";

$page_requested = "";

if (isset($_POST['signup_request_email'])) {

    $account_no = $db_handle->sanitizePost($_POST['your_email']);

    if($register) {
        $message_success = $register;
    } else {
        $message_error = "Your registration was not successful, something went wrong or you provided an existing email address.";
    }
}

switch($page_requested) {
    case '': $signup_request_email_php = true; break;
    case 'signup_request_email_php': $signup_request_email_php = true; break;
    default: $signup_request_email_php = true;
}

?>
<!DOCTYPE html>
<html lang="en">
    <head>
        <base target="_self">
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>Instaforex Nigeria | Instafxng Partnership Program</title>
        <meta name="title" content="Instaforex Nigeria | Instafxng Partnership Program" />
        <meta name="keywords" content="instaforex, forex trading in nigeria, forex seminar, forex trading seminar, how to trade forex, trade forex, instaforex nigeria">
        <meta name="description" content="Instaforex, a multiple award winning international forex broker is the leading Forex broker in Nigeria, open account and enjoy forex trading with us.">
        <?php require_once 'layouts/head_meta.php'; ?>

        <style>

            .alert {
                margin: 10px 0 10px 0 !important;
                padding: 10px !important;
            }
        </style>

    </head>
    <body>
        <?php require_once 'layouts/header.php'; ?>
        <!-- Main Body: The is the main content area of the web site, contains a side bar  -->
        <div id="main-body" class="container-fluid">
            <div class="row no-gutter">
                <?php require_once 'layouts/topnav.php'; ?>
                <!-- Main Body - Content Area: This is the main content area, unique for each page  -->
                <div id="main-body-content-area" class="col-md-12">
                    
                    <!-- Unique Page Content Starts Here
                    ================================================== -->
                    <div class="row">
                        <div class="col-sm-12">
                            <img src="images/partner_pc.png" alt="" class="img-responsive center-block" width="668px" height="226px" />
                        </div>
                        
                    </div>
                    
                    <div class="section-tint super-shadow">
                        <h3>Instafxng Partnership Registration</h3>

                        <!-- FORM CONTAINER -->
                        <div class="container-fluid form-container-fluid">
                            <div class="row">
                                <div class="col-sm-12">
                                    <?php require_once 'layouts/feedback_message.php'; ?>

                                    <?php
                                        if($signup_request_email_php) { include_once 'views/signup/signup_request_email.php'; }
                                    ?>


                                </div>
                            </div>
                        </div>

                    </div>
                    <!-- Unique Page Content Ends Here
                    ================================================== -->
                    
                </div>
                
            </div>
            <div class="row no-gutter">
            <?php require_once 'layouts/footer.php'; ?>
            </div>
        </div>
        
    </body>
</html>