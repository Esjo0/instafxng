<?php
require_once("../init/initialize_partner.php");

if ($session_partner->is_logged_in()) {
    redirect_to("index.php");
}

if (isset($_POST['submit']) && !empty($_POST['submit'])) {
    $email = strip_tags(trim($_POST['email']));
    $password = strip_tags(trim($_POST['password']));
    
    // Check database to see if username/password exist.
    $found_partner = $partner_object->authenticate($email, $password);

    if($found_partner) {
        $partner_code = $found_partner['partner_code'];
        $status = $found_partner['status'];

        if($status == '2') {

            $session_partner->login($found_partner);
            redirect_to("cabinet/index.php");
        } else {
            $message_error = "Your profile is currently inactive or suspended, please contact support for assistance.";
        }
    } else {
        // username/password combo was not found in the database
        $message_error = "Username and password combination do not match.";
    }
} else { // Form has not been submitted.
    $email = "";
    $password = "";
}

if(isset($_GET['logout'])) {
    $logout_code = $_GET['logout'];
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
<!--        <base target="_self">-->
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>Instaforex Nigeria | Instafxng Partner Login</title>
        <meta name="title" content="Instaforex Nigeria | Instafxng Partner Login" />
        <meta name="keywords" content="instaforex, forex trading in nigeria, forex seminar, forex trading seminar, how to trade forex, trade forex, instaforex nigeria">
        <meta name="description" content="Instaforex, a multiple award winning international forex broker is the leading Forex broker in Nigeria, open account and enjoy forex trading with us.">
        <?php require_once 'layouts/head_meta.php'; ?>
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
                        <h3>Instafxng Partner Login</h3>
                        <p>Login below to access the partner area</p>

                        <div class="container-fluid" style="margin-top: 50px; max-width: 500px !important;">
                            <div class="row">
                            <div class="col-sm-12">
                                <?php require_once 'layouts/feedback_message.php'; ?>

                                <!-- Login Form -->
                                <form role="form" method="post" action="<?php echo $_SERVER['PHP_SELF']; ?>">
                                    <div class="form-group">
                                        <div class="input-group">
                                            <span class="input-group-addon"><i class="fa fa-user fa-fw"></i></span>
                                            <input type="text" placeholder="Email" name="email" class="form-control">
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <div class="input-group">
                                            <span class="input-group-addon"><i class="fa fa-key fa-fw"></i></span>
                                            <input type="password" placeholder="Password" name="password" class="form-control">
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <input type="submit" name="submit" class="btn btn-success" value="Login">
                                        <span>Forgot your password? <a href="#" title="Click here to recover your password">Recover Password</a></span>
                                    </div>
                                </form>
                                <p>Don't have an IPP Account? <a href="partner/signup.php" title="Register for Instafxng Partner Program">Register here</a></p>
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