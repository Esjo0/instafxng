<?php
require_once 'init/initialize_general.php';
if ($session_careers->is_logged_in()) {
    redirect_to("careers_index.php");
}

$thisPage = "Careers";

$get_params = allowed_get_params(['m', 'logout']);
$message = $get_params['m'];

if($message == true) {
    $message_success = "Your data has been submitted, check your email for the system generated password.";
}

if (isset($_POST['submit']) && !empty($_POST['submit'])) {
    $username = strip_tags(trim($_POST['username']));
    $password = strip_tags(trim($_POST['password']));

    // Check database to see if username/password exist.
    $found_user = $obj_careers->authenticate($username, $password);

    if($found_user) {
        if($obj_careers->applicant_is_active($username)) {
            $found_user = $found_user[0];
            $session_careers->login($found_user);
            redirect_to("careers_index.php");
        } else {
            $message_error = "Your profile has certain issues, please contact support.";
        }
    } else {
        // username/password combo was not found in the database
        $message_error = "Username and password combination do not match.";
    }
} else { // Form has not been submitted.
    $username = "";
    $password = "";
}

if($get_params['logout']) {
    $logout_code = $get_params['logout'];
    switch ($logout_code) {
        case 1:
            $message_success = "You have logged out";
            break;
        case 2:
            $message_success = "You have been auto-logged out due to inactivity";
            break;
    }
}

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <base target="_self">
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Instaforex Nigeria | Careers and Job Opportunity</title>
    <meta name="title" content="Instaforex Nigeria | Careers and Job Opportunity" />
    <meta name="keywords" content="" />
    <meta name="description" content="" />
    <?php require_once 'layouts/head_meta.php'; ?>
    <style>
        .container-fluid { max-width: 1020px !important; }
        hr { max-width: 1020px !important; }
        #footer { max-width: 1020px !important; margin: 0 auto; background: #ddd url(../../images/footerbg.png); padding: 5px 10px 10px 10px; }
    </style>
</head>

<body>
<!-- Header Section: Logo and Live Chat  -->
<header id="header">
    <div class="container-fluid no-gutter masthead">
        <div class="row">
            <div id="main-logo" class="col-sm-12 col-md-5">
                <a href="./" target="_blank"><img src="images/ifxlogo.png" alt="Instaforex Nigeria Logo" /></a>
            </div>
            <div id="top-nav" class="col-sm-12 col-md-7 text-right">
            </div>
        </div>
    </div>
    <hr />
</header>

<!-- Main Body: The is the main content area of the web site, contains a side bar  -->
<div id="main-body" class="container-fluid">
    <div class="row no-gutter">

        <!-- Main Body - Content Area: This is the main content area, unique for each page  -->
        <div id="main-body-content-area" class="col-md-12">

            <!-- Unique Page Content Starts Here
            ================================================== -->

            <div class="super-shadow page-top-section">
                <div class="row ">
                    <div class="col-sm-7">
                        <h2>Careers and Job Opportunity</h2>
                        <p>If you like scaling new heights and you have the drive for excellence, you
                            will fit into our team.</p>
                        <p>For 7 years, we have pushed the boundaries of service delivery in the Forex
                            Trading industry in Nigeria. Do you have what it takes to be on our team?</p>
                    </div>

                    <div class="col-sm-5">
                        <img src="images/instafxng-careers.jpg" alt="" class="img-responsive" />
                    </div>
                </div>
            </div>

            <div class="section-tint super-shadow">
                <div class="row">
                    <div class="col-sm-12">
                        <div class="row"><div class="col-sm-12"><p><a href="careers.php" class="btn btn-default" title="Job List"><i class="fa fa-arrow-circle-left"></i> Go Back - Job List</a></p></div></div>

                        <?php require_once 'layouts/feedback_message.php'; ?>

                        <p>Login to complete your application.</p>

                        <!-- Login Form -->
                        <form data-toggle="validator" class="form-horizontal" role="form" method="post" action="careers_login.php">

                            <div class="form-group">
                                <label class="control-label col-sm-3" for="username">Email Address:</label>
                                <div class="col-sm-9 col-lg-5">
                                    <div class="input-group">
                                        <span class="input-group-addon"><i class="fa fa-user fa-fw"></i></span>
                                        <input type="text" placeholder="Username" name="username" class="form-control">
                                    </div>
                                </div>
                            </div>

                            <div class="form-group">
                                <label class="control-label col-sm-3" for="password">Password:</label>
                                <div class="col-sm-9 col-lg-5">
                                    <div class="input-group">
                                        <span class="input-group-addon"><i class="fa fa-key fa-fw"></i></span>
                                        <input type="password" placeholder="Password" name="password" class="form-control">
                                    </div>
                                </div>
                            </div>

                            <div class="form-group">
                                <div class="col-sm-offset-3 col-sm-9"><input type="submit" name="submit" class="btn btn-success" value="Login"></div>
                            </div>
                        </form>
                    </div>


                </div>
            </div>

            <!-- Unique Page Content Ends Here
            ================================================== -->
        </div>
    </div>

</div>
<footer id="footer" class="super-shadow">
    <div class="container-fluid no-gutter">
        <div class="col-sm-12">
            <div class="row">
                <p class="text-center" style="font-size: 16px !important;">&copy; <?php echo date('Y'); ?>, All rights reserved. Instant Web-Net Technologies Limited (www.instafxng.com)</p>
            </div>
        </div>
    </div>
</footer>
</body>
</html>