<?php
session_start();
require_once("../init/initialize_general.php");
require_once("../init/initialize_admin.php");
$interest_yes = "i_have_traded_forex_before.";
$interest_no = "i_have_not_traded_forex_before";
if(isset($_POST['sign_up']))
{
    $name = $db_handle->sanitizePost(trim($_POST['name']));
    $email = $db_handle->sanitizePost(trim($_POST['email']));
    $phone = $db_handle->sanitizePost(trim($_POST['phone']));
    $interest = $db_handle->sanitizePost(trim($_POST['interest']));
    if(!filter_var($email, FILTER_VALIDATE_EMAIL))
    {
        $message_error = "You entered an invalid email address, please try again.";
    }
    extract(split_name($name));
    if($interest == $interest_no)
    {
        if($obj_loyalty_training->is_duplicate_training($email, $phone))
        {
            $message_error = "Sorry, you have previously registered for the FxAcademy Online Training.";
        }
        else
        {
            $training = $obj_loyalty_training->add_training($first_name, $last_name, $email, $phone);
            if($training)
            {
                $_SESSION['f_name'] = $first_name;
                $_SESSION['l_name'] = $last_name;
                $_SESSION['m_name'] = $middle_name;
                $_SESSION['email'] = $email;
                $_SESSION['phone'] = $phone;
                redirect_to("https://instafxng.com/forex-income/");
            }
        }
    }
    if($interest == $interest_yes)
    {
        if($obj_loyalty_training->is_duplicate_loyalty($email, $phone))
        {
            $message_error = "Sorry, you have previously enrolled into the InstaFxNg Loyalty Promotions And Rewards";
        }
        else
        {
            $loyalty = $obj_loyalty_training->add_loyalty($first_name, $last_name, $email, $phone);
            if($loyalty)
            {
                $_SESSION['f_name'] = $first_name;
                $_SESSION['l_name'] = $last_name;
                $_SESSION['m_name'] = $middle_name;
                $_SESSION['email'] = $email;
                $_SESSION['phone'] = $phone;
                $_SESSION['source'] = 'lp';
                redirect_to("https://instafxng.com/loyalty/");
            }
        }
    }
}

?>

<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>Welcome | InstaFxNg</title>
        <link rel="shortcut icon" type="image/icon" href="../images/favicon.png"/>
        <link href="https://maxcdn.bootstrapcdn.com/font-awesome/4.6.3/css/font-awesome.min.css" rel="stylesheet">
        <link href="assets/css/bootstrap.min.css" rel="stylesheet">
        <link href="assets/css/slick.css" rel="stylesheet">
        <link id="switcher" href="assets/css/theme-color/default-theme.css" rel="stylesheet">
        <link href="assets/style.css" rel="stylesheet">
        <link href="//fonts.googleapis.com/css?family=Open+Sans:300,400,400i,600,700,800" rel="stylesheet">
        <link href="//fonts.googleapis.com/css?family=Montserrat" rel="stylesheet">
        <link href=' http://fonts.googleapis.com/css?family=Amazone+BT' rel='stylesheet' type='text/css'>
        <script src="//oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
        <script src="//oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
        <script src="../js/bootstrap-datetimepicker.js"></script>
        <script src="../js/jquery_2.1.1.min.js"></script>
        <link href="//cdn.rawgit.com/Eonasdan/bootstrap-datetimepicker/e8bddc60e73c1ec2475f827be36e1957af72e2ea/build/css/bootstrap-datetimepicker.css" rel="stylesheet">
        <link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
    </head>
    <body>
        <header  id="mu-hero" class="" role="banner">
            <div style="width: 100%; height:100vh;" class="mu-hero-overlay">
                <div class="container">
                    <div class="mu-hero-area">
                        <div class="mu-hero-featured-area">
                            <div class="col-sm-12">
                            </div>
                        </div>
                    </div>
                </div>
                <footer style="position:fixed;bottom:0;"  id="mu-footer" role="contentinfo">
                    <div class="container">
                        <div class="mu-footer-area">
                            <div class="mu-footer-top">
                                <div class="mu-social-media">
                                    <a href="//www.facebook.com/InstaForexNigeria"><i class="fa fa-facebook"></i></a>
                                    <a href="//www.twitter.com/instafxng"><i class="fa fa-twitter"></i></a>
                                    <a href="//www.instagram.com/instafxng/"><i class="fa fa-instagram"></i></a>
                                    <a href="//www.linkedin.com/company/instaforex-ng"><i class="fa fa-linkedin"></i></a>
                                    <a href="//www.youtube.com/channel/UC0Z9AISy_aMMa3OJjgX6SXw"><i class="fa fa-youtube"></i></a>
                                </div>
                            </div>
                            <div class="mu-footer-bottom">
                                <p class="mu-copy-right">&copy; Copyright <a rel="nofollow" href="#">Instant Web Net Technologies Ltd</a>. All rights reserved.</p>
                            </div>
                        </div>
                    </div>
                </footer>
            </div>
        </header>
        <div data-keyboard="false" data-backdrop="static" id="confirm-add-admin" tabindex="-1" role="dialog" aria-hidden="true" class="modal fade">
            <form class="form-horizontal" role="form" method="post" action="">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-body">
                        <center>
                            <img class="img-responsive" src="../images/ifxlogo.png">
                            <br/>
                        </center>
                        <?php require_once '../layouts/feedback_message.php'; ?>
                        <h6 class="text-center text-danger">Sign Up To Begin</h6>
                        <p class="text-justify">Fill the form below to begin your journey to making consistent income.</p>
                        <div class="row">
                            <div class="col-sm-12">
                                <div class="col-sm-2"></div>
                                <div class="col-sm-8">
                                    <div class="form-group">
                                        <input name="name" placeholder="Full Name" type="text" class="form-control" id="">
                                    </div>
                                    <div class="form-group">
                                        <input name="email" placeholder="Email Address" type="text" class="form-control" id="">
                                    </div>
                                    <div class="form-group">
                                        <input name="phone" placeholder="Phone Number" type="text" class="form-control" id="">
                                    </div>
                                    <div class="form-group">
                                        <label><input name="interest" type="radio" class="radio" id="no">I have not traded Forex</label>
                                    </div>
                                    <div class="form-group">
                                        <label><input name="interest" type="radio" class="radio" id="yes">I have traded Forex</label>
                                    </div>
                                </div>
                                <div class="col-sm-2"></div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer"><button type="submit" name="sign_up" class="btn btn-success">Proceed!</button></div>
                </div>
            </div>
        </div>
        <script>
            $(document).ready(function()
            {
                $('#confirm-add-admin').modal("show");
            });
        </script>
        <script src="//ajax.googleapis.com/ajax/libs/jquery/1.11.3/jquery.min.js"></script>
        <!-- Include all compiled plugins (below), or include individual files as needed -->
        <!-- Bootstrap -->
        <script src="assets/js/bootstrap.min.js"></script>
        <!-- Slick slider -->
        <script type="text/javascript" src="assets/js/slick.min.js"></script>
        <!-- Event Counter -->
        <script type="text/javascript" src="assets/js/jquery.countdown.min.js"></script>
        <!-- Ajax contact form  -->
        <script type="text/javascript" src="assets/js/app.js"></script>
        <!-- Custom js -->
        <script type="text/javascript" src="assets/js/custom.js"></script>
        <script src="//cdnjs.cloudflare.com/ajax/libs/moment.js/2.9.0/moment-with-locales.js"></script>
        <script src="//cdn.rawgit.com/Eonasdan/bootstrap-datetimepicker/e8bddc60e73c1ec2475f827be36e1957af72e2ea/src/js/bootstrap-datetimepicker.js"></script>
        <script src="//code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
    </body>
</html>