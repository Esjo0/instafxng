<?php
require_once 'init/initialize_general.php';
$thisPage = "Support";

if(isset($_GET['s']) && $_GET['s'] == 1) {
    $message_success = "Form submitted succesfully";
}
?>
<!DOCTYPE html>
<html lang="en">
    <head>
        <base target="_self">
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>Instaforex Nigeria | Contact Us</title>
        <meta name="title" content="Instaforex Nigeria | Contact Us" />
        <meta name="keywords" content="instaforex, instaforex nigeria, contact address of instaforex nigeria, forex trading in nigeria">
        <meta name="description" content="Instaforex Forex Trading Service for Nigerians. Contact us today!">
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
                                <h4><strong>Contact Us</strong></h4>
                            </div>
                        </div>
                        
                        <div class="row">
                            <div class="col-sm-12">
                                <?php require_once 'layouts/feedback_message.php'; ?>
                                
                                <p>InstaFxNg.com is operated by Instant Web-Net Technologies Limited as a Nigerian InstaForex Representative / Introducing Broker Partner.
                                You can use any of the methods below to contact us, you can check our <a href="faq.php" >frequently asked questions</a>,
                                you may also fill the form below.</p>
                                <ul class="fa-ul">
                                    <li><i class="fa-li fa fa-check-square-o icon-tune"></i>
                                        <strong>Head Office Address</strong><br>
                                        TBS Place, Block 1A, Plot 8, Diamond Estate, Estate Busstop, LASU/Isheri road, Isheri Olofin, Lagos, Nigeria.<br>
                                        <strong>Support Phones:</strong> 08028281192, 08182045184,08084603182<br>
                                        <strong>Email:</strong> support@instafxng.com
                                    </li>
                                    <li><i class="fa-li fa fa-check-square-o icon-tune"></i>
                                        <strong>Lekki Office Address</strong><br>
                                        Block A3, Suite 508/509 Eastline Shopping Complex, Opposite Abraham Adesanya Roundabout, along Lekki - Epe expressway, Lagos, Nigeria.<br>
                                        <strong>Support Phones:</strong> 08139250268, 08083956750
                                    </li>
                                </ul>
                                <h5>Kindly fill the form below to contact us</h5>
                                <form class="form-horizontal" role="form" method="post" action="logic/contact_info_logic.php">
                                    <div class="form-group">
                                        <label class="control-label col-sm-2" for="full_name">Full Name:</label>
                                        <div class="col-sm-10 col-lg-5"><input name="full_name" type="text" class="form-control" id="full_name"></div>
                                    </div>
                                    <div class="form-group">
                                        <label class="control-label col-sm-2" for="email">Email Address:</label>
                                        <div class="col-sm-10 col-lg-5"><input name="email_address" type="email" class="form-control" id="email"></div>
                                    </div>
                                    <div class="form-group">
                                        <label class="control-label col-sm-2" for="phone">Phone Number:</label>
                                        <div class="col-sm-10 col-lg-5"><input name="phone_number" type="text" class="form-control" id="phone"></div>
                                    </div>
                                    <div class="form-group">
                                        <label class="control-label col-sm-2" for="comment">Your Inquiry:</label>
                                        <div class="col-sm-10 col-lg-7"><textarea name="comment" class="form-control" rows="5" id="comment"></textarea></div>
                                    </div>
                                    <div class="form-group"><div class="col-sm-offset-2 col-sm-10 g-recaptcha" data-sitekey="6LcKDhATAAAAAF3bt-hC_fWA2F0YKKpNCPFoz2Jm"></div></div>
                                    <div class="form-group">
                                        <div class="col-sm-offset-2 col-sm-10"><input name="submit" type="submit" class="btn btn-success" value="Submit" /></div>
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