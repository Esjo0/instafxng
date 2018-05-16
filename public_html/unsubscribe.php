<?php
require_once 'init/initialize_general.php';
$thisPage = "Unsubscribe";

if(isset($_GET['s']) && $_GET['s'] == 1) {
    $message_success = "Your unsubscription has been processed succesfully";
}

if (isset($_POST['submit']) && !empty($_POST['submit'])) {
    if(isset($_POST['g-recaptcha-response']) && !empty($_POST['g-recaptcha-response'])) {
        $secret = '6LcKDhATAAAAALn9hfB0-Mut5qacyOxxMNOH6tov';
        $verifyResponse = file_get_contents('https://www.google.com/recaptcha/api/siteverify?secret='.$secret.'&response='.$_POST['g-recaptcha-response']);
        $responseData = json_decode($verifyResponse);
        if($responseData->success) {
            $email = $db_handle->sanitizePost(trim($_POST['email_address']));
            $comment = $db_handle->sanitizePost($_POST['comment']);

            if($db_handle->numRows("SELECT email FROM user WHERE email = '$email' LIMIT 1") > 0) {
                //unsubscribe client here
                $db_handle->runQuery("UPDATE user SET campaign_subscribe = '2' WHERE email = '$email' LIMIT 1");

                //send notice email to instafxng support department
                $email_subject = "Newsletter Unsubscription Notice";
                $email_message = "
Hello,

The client below unsubscribed from our Newsletter. Please note the
comment below for record purpose.

Details:

Email: " . $email . "
Comment: " . $comment . "


Regards.
Instafxng Webmaster";

                $admin_email = "Instafxng Support <support@instafxng.com>";
                $headers = "From: Instafxng Webmaster <noreply@instafxng.com>";
                mail($admin_email, $email_subject, $email_message, $headers);
                header("Location: unsubscribe.php?s=1");
            } else {
                $message_error = "This email address does not exist in our record.";
            }
            
        }
    } else {
        $message_error = "Please confirm that you are not a robot";
    }
}
?>
<!DOCTYPE html>
<html lang="en">
    <head>
        <base target="_self">
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>Instaforex Nigeria | Email Alerts</title>
        <meta name="title" content="Instaforex Nigeria | Email Alerts" />
        <meta name="keywords" content="instaforex, how to trade forex, trade forex, instaforex nigeria.">
        <meta name="description" content="Instaforex Nigeria | Email Alerts">
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
                                <h4><strong>Unsubscribe from Instafxng Newsletter</strong></h4>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-sm-12">
                                <?php require_once 'layouts/feedback_message.php'; ?>

                                <p>We are sorry to see you want to unsubscribe from our newsletter.
                                If you unsubscribe, you will no longer receive emails containing News, Promotions and Special Offers from us.</p>
                                <p>To unsubcribe, kindly fill the form below, please leave a comment to let us know why you are unsubscribing. Thank you.</p>

                                <form data-toggle="validator" class="form-horizontal" role="form" method="post" action="">
                                    <div class="form-group">
                                        <label class="control-label col-sm-2" for="email_address">Email:</label>
                                        <div class="col-sm-10 col-lg-5"><input name="email_address" type="email" class="form-control" required></div>
                                    </div>
                                    <div class="form-group">
                                        <label class="control-label col-sm-2" for="comment">Comment:</label>
                                        <div class="col-sm-10 col-lg-7"><textarea name="comment" class="form-control" rows="5" id="comment" required></textarea></div>
                                    </div>
                                    <p class="text-muted"><strong> Help <i class="fa fa-exclamation"></i></strong> Us Fight Spam.</p>
                                    <div class="form-group"><div class="col-sm-offset-2 col-sm-10 g-recaptcha" data-sitekey="6LcKDhATAAAAAF3bt-hC_fWA2F0YKKpNCPFoz2Jm"></div></div>
                                    <div class="form-group">
                                        <div class="col-sm-offset-2 col-sm-10"><input name="submit" type="submit" class="btn btn-success" value="Unsubscribe" /></div>
                                    </div>
                                </form>

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