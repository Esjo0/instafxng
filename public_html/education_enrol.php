<?php
require_once 'init/initialize_general.php';
$thisPage = "Education";

$chosen_course = $_GET['c'];
switch ($chosen_course) {
    case 'beginner':
        $course = "Beginner Trader Course";
        break;
    case 'advance':
        $course = "Advance Trader Course";
        break;
    case 'private':
        $course = "Forex Private Course";
        break;
    case 'freedom':
        $course = "Forex Freedom Course";
        break;
    case 'investor':
        $course = "Investor Course";
        break;
    case 'free_training':
        $course = "Free Forex Training";
        break;
} // if not set, allow user select course from a checkbox, also link to course comparison education.php

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
        <title>Instaforex Nigeria | Enrol for Forex Education</title>
        <meta name="title" content=" " />
        <meta name="keywords" content="instaforex, forex seminar, forex trading seminar, how to trade forex, trade forex, instaforex nigeria.">
        <meta name="description" content="Learn how to trade forex, get free information about the forex market in our forex trading seminars.">
        <link rel="stylesheet" href="css/free_seminar.css">
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
                                <h4><strong>Enrol For Forex Education</strong></h4>
                            </div>
                        </div>
                        
                        <div class="row">
                            <div class="col-sm-12">
                                <?php if(isset($message_success)): ?>
                                <div class="alert alert-success">
                                    <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
                                    <strong>Success!</strong> <?php echo $message_success; ?>
                                </div>
                                <?php endif ?>
                                
                                <p>You are about enrol for the <strong><?php if(isset($course)) { echo $course; } ?></strong>. Please fill the form below.</p>
                                <form class="form-horizontal" role="form" method="post" action="logic/education_enrol_logic.php">
                                    <input name="course_type" type="hidden" value="<?php if(isset($course)) { echo $course; } ?>">
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
                                        <label class="control-label col-sm-2" for="location">Location:</label>
                                        <div class="col-sm-10 col-lg-5"><input name="location" type="text" class="form-control" id="location"></div>
                                    </div>
                                    <div class="form-group">
                                        <label class="control-label col-sm-2" for="comment">Your Comment:</label>
                                        <div class="col-sm-10 col-lg-7"><textarea name="comment" class="form-control" rows="5" id="comment"></textarea></div>
                                    </div>
                                    <center style="color: #0d95e8"><strong> Help <i class="fa fa-exclamation"></i></strong> Us Fight Spam.
                                    <div class="form-group"><div class="col-sm-offset-2 col-sm-10 g-recaptcha" data-sitekey="6LcKDhATAAAAAF3bt-hC_fWA2F0YKKpNCPFoz2Jm"></div></div>
                                    <div class="form-group">
                                        <div class="col-sm-offset-2 col-sm-10"><input name="submit" type="submit" class="btn btn-success" value="Enrol Now" /></div>
                                    </div></center>
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