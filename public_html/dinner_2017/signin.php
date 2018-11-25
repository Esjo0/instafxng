<?php
require_once("../init/initialize_general.php");
require_once("../init/initialize_admin.php");
?>
<?php



if(isset($_POST['login']))
{
    $email = $db_handle->sanitizePost($_POST['email']);
    if(filter_var($email, FILTER_VALIDATE_EMAIL))
    {
        $attendee_detail = $db_handle->fetchAssoc($db_handle->runQuery("SELECT * FROM dinner_2017 WHERE email = '$email'"));
        $attendee_detail = $attendee_detail[0];
        if(empty($attendee_detail))
        {
            $message_error = "You do not have a reservation yet.";
        }
        else
        {
            $url = "index.php?id=".encrypt_ssl($attendee_detail['reservation_code']);
            redirect_to($url);
        }
    }
    else
    {
        $message_error = "You entered an invalid email address, please try again.";
    }
}

?>

<!DOCTYPE html>
<html lang="en">
<!--<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>Instaforex NG Dinner 2017</title>
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
</head>-->
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Instaforex NG Dinner 2017</title>
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

    <!-- Start Header -->
    <header  id="mu-hero" class="" role="banner">
        <!-- End menu -->
        <div style="width: 100%; height:100vh;" class="mu-hero-overlay">
            <div class="container">
                <div class="mu-hero-area">
                    <!-- Start hero featured area -->
                    <div class="mu-hero-featured-area">
                        <div class="col-sm-12">
                        </div>
                    </div>
                    <!-- End hero featured area -->

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

    <!-- End Header -->


<!--Modal - confirmation boxes-->
<div data-keyboard="false" data-backdrop="static" id="confirm-add-admin" tabindex="-1" role="dialog" aria-hidden="true" class="modal fade">
    <form class="form-horizontal" role="form" method="post" action="">
    <div class="modal-dialog">
        <div class="modal-content">
            <!--<div class="modal-header">
                <center>
                    <img width="40%" height="40%" class="img-responsive" style="border-radius: 50%" src="assets/images/dinner_2017.jpg">
                <!--<h3 class="text-center text-danger modal-title">The Ethnic Impression 2017</h3>
        </center>
    </div>-->
            <div class="modal-body">
                <?php require_once '../layouts/feedback_message.php'; ?>
                <center>
                    <img width="40%" height="40%" class="img-responsive" style="border-radius: 50%" src="assets/images/dinner_2017.jpg">
                    <!--<h3 class="text-center text-danger modal-title">The Ethnic Impression 2017</h3>-->
                </center>
                <h6 class="text-center text-danger"><b>Oops! </b>All available seats are taken.</h6>
                <p class="text-justify">If you have a reservation, enter your email address below to update it.</p>
                <div class="form-group">
                    <div class="col-sm-12 col-lg-12">
                        <input name="email" type="text" class="form-control" id="email">
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="submit" name="login" class="btn btn-success">Proceed!</button>
            </div>
        </div>
    </div>
</div>
<script>
    $(document).ready(function()
    {
        $('#confirm-add-admin').modal("show");
    });
</script>


<!-- Start footer -->

<!-- End footer -->

<!-- jQuery library -->
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