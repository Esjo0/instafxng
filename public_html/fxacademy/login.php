<?php
require_once '../init/initialize_client.php';
if ($session_client->is_logged_in()) {
    redirect_to("index.php");
}

$client_operation = new clientOperation();

if (isset($_POST['submit']) && !empty($_POST['submit'])) {

    if(isset($_POST['g-recaptcha-response']) && !empty($_POST['g-recaptcha-response'])) {
        $secret = '6LcKDhATAAAAALn9hfB0-Mut5qacyOxxMNOH6tov';
        $verifyResponse = file_get_contents('https://www.google.com/recaptcha/api/siteverify?secret=' . $secret . '&response=' . $_POST['g-recaptcha-response']);
        $responseData = json_decode($verifyResponse);

        if ($responseData->success) {
            $client_email = $db_handle->sanitizePost($_POST['client_email']);
            $user_code = $client_operation->get_user_by_email($client_email);
            $user_ifx_details = $client_operation->get_user_by_code($user_code['user_code']);

            if($user_ifx_details) {

                // confirm that the user is active
                if($client_operation->user_is_active($user_ifx_details['client_email'])) {
                    $found_user = array(
                        'user_code' => $user_ifx_details['client_user_code'],
                        'status' => $user_ifx_details['client_status'],
                        'first_name' => $user_ifx_details['client_first_name'],
                        'last_name' => $user_ifx_details['client_last_name'],
                        'email' => $user_ifx_details['client_email']
                    );
                    $session_client->login($found_user);
                    redirect_to("index.php");
                } else {
                    $message_error = "Your profile has certain issues, please contact support.";
                }

            } else {
                $message_error = "Your profile details could not be found, please confirm you
                entered the correct email address used on our website or contact support.";
            }
        } else {
            $message_error = "Please try the robot test again.";
        }
    } else {
        $message_error = "Please confirm that you are not a robot. :)";
    }
} else { // Form has not been submitted.
    $username = "";
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
        <base target="_self">
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>Instaforex Nigeria</title>
        <meta name="title" content="" />
        <meta name="keywords" content="">
        <meta name="description" content="">
        <?php require_once 'layouts/head_meta.php'; ?>
    </head>
    <body>
        <?php require_once 'layouts/header.php'; ?>

        <!-- Main Body: The is the main content area of the web site, contains a side bar  -->
        <div id="main-body" class="container-fluid">
            <div class="row no-gutter">

                <!-- Main Body - Content Area: This is the main content area, unique for each page  -->
                <div id="main-body-content-area" class="col-md-12">

                    <!-- Unique Page Content Starts Here
                    ================================================== -->
                    <div class="section-tint-red super-shadow">
                        <div class="row">
                            <div class="col-sm-9 text-center">
                                <h4 class="white-text">Forex Profit Academy</h4>
                            </div>
                            <div class="col-sm-3 text-center">
                                <i id="special-i" class="fa fa-line-chart fa-fw white-text"></i>
                            </div>
                        </div>
                    </div>

                    <div id="main-container" class="section-tint super-shadow">

                        <div class="row">
                            <div class="col-sm-12">
                                <p><img src="images/training_course_banner.jpg" class="img-responsive center-block" alt="" /></p>

                                <blockquote>
                                    <span style="font-size: 0.9em">Money is plentiful for those who understand the simple laws which govern its acquisition.</span>
                                    <footer>George S. Clason</footer>
                                </blockquote>

                                <p>Forex Trading is easy to learn, enter your Email Address below and Start Learning.</p>
                            </div>
                        </div>


                        <div class="row" style="max-width: 500px !important; margin: 0 auto">
                            <div class="col-sm-12">
                                <?php require_once 'layouts/feedback_message.php'; ?>

                                <!-- Login Form -->
                                <form role="form" method="post" action="<?php echo $_SERVER['PHP_SELF']; ?>">
                                    <div class="form-group">
                                        <div class="input-group">
                                            <span class="input-group-addon"><i class="fa fa-user fa-fw"></i></span>
                                            <input type="text" placeholder="Email Address" name="client_email" class="form-control">
                                        </div>
                                    </div>
                                    <div class="form-group"><div class="g-recaptcha" data-sitekey="6LcKDhATAAAAAF3bt-hC_fWA2F0YKKpNCPFoz2Jm"></div></div>
                                    <div class="form-group">
                                        <input type="submit" name="submit" class="btn btn-lg btn-success" value="Start Learning">
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

        <?php require_once 'layouts/footer.php'; ?>
        <script src='https://www.google.com/recaptcha/api.js'></script>
    </body>
</html>