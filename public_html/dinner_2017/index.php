<?php
require_once '../init/initialize_general.php';
require_once("../init/initialize_admin.php");

$get_params = allowed_get_params(['x', 'id']);
$user_code_encrypted = $get_params['id'];
if(empty($user_code_encrypted))
{
    redirect_to("signin.php");
    exit;
}
$user_code = decrypt(str_replace(" ", "+", $user_code_encrypted));
$user_code = preg_replace("/[^A-Za-z0-9 ]/", '', $user_code);
$attendee_detail = $db_handle->fetchAssoc($db_handle->runQuery("SELECT * FROM dinner_2017 WHERE reservation_code = '$user_code'"));
$attendee_detail = $attendee_detail[0];
if(empty($attendee_detail))
{
    redirect_to("signin.php");
    exit;
}
if (isset($_POST['process']))
{
    foreach($_POST as $key => $value)
    {
        $_POST[$key] = $db_handle->sanitizePost(trim($value));
    }
    extract($_POST);

    $query = "UPDATE dinner_2017 SET confirmation = '$confirmation_status' WHERE reservation_code = '$user_code'";
    $update = $db_handle->runQuery($query);
    if($update)
    {
        $message_success = "You have successfully updated your reservation.";
    }
    else
    {
        $message_error = "Error";
    }
}




?>
<!DOCTYPE html>
<html lang="en">
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
    <link href='//fonts.googleapis.com/css?family=Amazone+BT' rel='stylesheet' type='text/css'>
    <script src="//oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
    <script src="//oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <script src="../js/bootstrap-datetimepicker.js"></script>
    <script src="../js/jquery_2.1.1.min.js"></script>
    <link href="//cdn.rawgit.com/Eonasdan/bootstrap-datetimepicker/e8bddc60e73c1ec2475f827be36e1957af72e2ea/build/css/bootstrap-datetimepicker.css" rel="stylesheet">
    <link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
</head>
<body>
<!-- Start Header -->
<header id="mu-hero" class="" role="banner">
    <!-- Start menu  -->
    <nav class="navbar navbar-fixed-top navbar-default mu-navbar">
        <div class="container">
            <!-- Brand and toggle get grouped for better mobile display -->
            <div class="navbar-header">
                <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1" aria-expanded="false">
                    <span class="sr-only">Toggle navigation</span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                </button>

                <!-- Logo -->
                <a class="navbar-brand" href="index.php"><img height="50%" width="50%" class="img-responsive" src="../images/ifxlogo-xmas.png"></a>

            </div>

            <!-- Collect the nav links, forms, and other content for toggling -->
            <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
                <ul class="nav navbar-nav mu-menu navbar-right">
                    <li><a href="#mu-hero">Reservation</a></li>
                    <li><a href="#mu-about">About The Event</a></li>
                </ul>
            </div><!-- /.navbar-collapse -->
        </div><!-- /.container-fluid -->
    </nav>
    <form data-toggle="validator" class="form-horizontal" role="form" method="post" action="">
    <!-- End menu -->
    <div class="mu-hero-overlay">
        <div class="container">
            <div class="mu-hero-area">
                <!-- Start hero featured area -->
                <div class="mu-hero-featured-area">
                    <div class="col-sm-12">
                        <div class="row">
                            <div class="col-sm-7">
                                <!-- Start center Logo -->
                                <div class="mu-logo-area">
                                    <!-- text based logo -->
                                    <!--<a class="mu-logo" href="#"><center><img height="50%" width="50%" class="img-responsive" src="../images/ifxlogo-xmas.png"></center></a>-->
                                    <!-- image based logo -->
                                    <a class="mu-logo" href="#"><img src="../images/ifxlogo-xmas.png" alt="logo img"></a>
                                </div>
                                <!-- End center Logo -->

                                <div class="mu-hero-featured-content">
                                    <h2>Presents</h2>
                                    <h1 style="font-family: 'Times New Roman'">THE ETHNIC IMPRESSION 2017</h1>
                                    <!--<h1>WELCOME TO INSTAFXNG DINNER 2017</h1>
                                    <h2>The Ethnic Impression</h2>-->
                                    <p class="mu-event-date-line">17 December, 2017. Lagos State, Nigeria</p>

                                    <div class="mu-event-counter-area">
                                        <div id="mu-event-counter">
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-sm-5">
                                <div style="display: -webkit-flex;  -webkit-flex-wrap: wrap;  display: flex;  flex-wrap: wrap;" class="modal-content">
                                    <div class="panel-heading">
                                        <div class="row">
                                            <div class="col-sm-12 text-center text-danger">
                                                <center><h5><strong>2017 DINNER RESERVATION</strong></h5></center>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="panel-body">
                                            <fieldset>
                                                <?php require_once '../layouts/feedback_message.php'; ?>
                                                <p>Please fill the form below to update your reservation.</p>
                                                <div class="form-group">
                                                    <div class="col-sm-12">
                                                        <div class="input-group">
                                                            <span class="input-group-addon"><i class="fa fa-user fa-fw"></i></span>
                                                            <input value="<?php echo $attendee_detail['full_name']; ?>" placeholder="Full Name" name="full_name" type="text" id="full_name" class="form-control" required disabled/>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="form-group">
                                                    <div class="col-sm-12">
                                                        <div class="input-group">
                                                            <span class="input-group-addon"><i class="fa fa-envelope fa-fw"></i></span>
                                                            <input placeholder="Email Address" name="email" type="text" id="email" value="<?php echo $attendee_detail['email']; ?>" class="form-control" required disabled/>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="form-group">
                                                    <div class="col-sm-12">
                                                        <div class="input-group">
                                                            <span class="input-group-addon"><i class="fa fa-phone fa-fw"></i></span>
                                                            <input type="text" class="form-control" id="phone_number" name="phone_number" value="<?php echo $attendee_detail['phone']; ?>">
                                                        </div>
                                                    </div>
                                                </div>
                                                <input id="ticket_type" name="ticket_type" type="hidden" value="0">
                                                <div class="form-group">
                                                    <div class="col-sm-12">
                                                        <div class="input-group">
                                                            <span class="input-group-addon"><i class="fa fa-location-arrow fa-fw"></i></span>
                                                            <input type="text" class="form-control" id="state_of_residence" name="state_of_residence" value="<?php echo $attendee_detail['state_of_residence']; ?>" readonly>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="form-group">
                                                    <div class="col-sm-12">
                                                        <div class="text-justify input-group">
                                                            <input id="confirmation_status" type="radio" name="confirmation_status" value="2" required/> <label for="confirmation_status">Yes, I would be in attendance.</label>
                                                            <br/>
                                                            <input id="confirmation_status" type="radio" name="confirmation_status" value="3" required/> <label for="confirmation_status">No, I would not be in attendance.</label>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="text-center form-group">
                                                    <div class=" col-sm-12">
                                                        <input data-target="#confirm-save-attendance" data-toggle="modal" name="process" type="button" class="btn btn-success" value="Update My Reservation!">
                                                    </div>
                                                </div>
                                            </fieldset>

                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- End hero featured area -->

            </div>
        </div>
    </div>
</header>
<!-- End Header -->
<!--Modal - confirmation boxes-->
<div id="confirm-save-attendance" tabindex="-1" role="dialog" aria-hidden="true" class="modal fade">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" data-dismiss="modal" aria-hidden="true"
                        class="close">&times;</button>
                <h4 class="modal-title">Update Reservation</h4></div>
            <div class="modal-body">Are you sure you want to save the changes? </div>
            <div class="modal-footer">
                <input name="process" type="submit" class="btn btn-success" value="Proceed">
                <button type="submit" name="close" onClick="window.close();" data-dismiss="modal" class="btn btn-danger">Close!</button>
            </div>
        </div>
    </div>
</div>
</form>
<!-- Start main content -->
<main role="main">
    <!-- Start About -->
    <section id="mu-about">
        <div class="container">
            <div class="row">
                <div class="col-md-12">
                    <div class="mu-about-area">
                        <!-- Start Feature Content -->
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mu-about-left">
                                    <img class="" src="assets/images/dinner_revert 3_Copy.jpg" alt="Men Speaker">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mu-about-right" style='font-family: "Arial Black" font-weight: bold'>
                                    <h2>About The Event</h2>
                                    <p class="text-justify" >This year’s dinner promises to be filled with great fun as we look deep into the riches of our culture and heritage.</p>
                                    <p class="text-justify">The expression of our cultural beauty, the ambience of our arts, the melody of our music, the rhythm of our folk story,
                                        the beauty of our clothing and the uniqueness of our languages makes up our native identities.</p>
                                    <p class="text-justify">In this Edition, we would be celebrating the Native heritage of the indigenous groups in Nigeria by creating an aura that embraces our roots as a people.</p>
                                    <p class="text-justify">The theme,the dressing, the food and the activities lined up for the dinner are harmonized to celebrate you, the uniqueness of your origin, the source that birthed you and the ideology that groomed you.</p>
                                    <p class="text-justify">Come dressed in your tribal attire, have a taste of your food and the groove of your music.</p>
                                </div>
                            </div>
                        </div>
                        <!-- End Feature Content -->

                    </div>
                </div>
            </div>
        </div>
    </section>
    <!-- End About -->

    <!-- Start Video -->
    <section id="mu-video" >
        <div class="mu-video-overlay">
            <div class="container">
                <div class="row">
                    <div   class="col-md-12">
                        <div   class="mu-video-area" >
                            <h2>Watch Previous Event Video</h2>
                            <!--<a class="mu-video-play-btn" href="#">
                                <i class="glyphicon glyphicon-play-circle" aria-hidden="true"></i>
                            </a>-->
                            <div style="opacity: 1!important;" class="embed-responsive embed-responsive-16by9">
                                <iframe class="embed-responsive-item" src="//www.youtube.com/embed/tdHp4WGw7YE" frameborder="0" allowfullscreen></iframe>
                            </div>
                        </div>

                    </div>
                </div>
            </div>



            <!--<div class="mu-video-content">
                <div class="mu-video-iframe-area">
                    <a class="mu-video-close-btn" href="#"><i class="fa fa-times" aria-hidden="true"></i></a>
                    <iframe width="854" height="480" src="//www.youtube.com/embed/tdHp4WGw7YE" frameborder="0" allowfullscreen></iframe>
                </div>
            </div>-->

        </div>

        <!-- End Video content -->

    </section>
    <!-- End Video -->


    <!-- Start Venue -->
    <section id="mu-video" >
        <div class="mu-video-overlay">
            <div class="row">
                <div class="col-md-6">
                    <div class="mu-venue-map">
                        <iframe src="//www.google.com/maps/embed/v1/place?key=AIzaSyBDmmOQa-UXFfKtrcyowlYvq4FpbUfAjxw&q=Four+Points+by+Sheraton,Lagos" width="100%" height="450" frameborder="0" style="border:0" allowfullscreen></iframe>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="mu-venue-address">
                        <h2>VENUE <i class="fa fa-chevron-right" aria-hidden="true"></i></h2>
                        <h3>Four Points by Sheraton Lagos</h3>
                        <h4>Plot 9/10, Block 2, Oniru Chieftaincy Estate,</h4>
                        <h4>Lagos State, Nigeria</h4>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!-- End Venue -->
</main>
<!-- End main content -->


<!-- Start footer -->
<footer id="mu-footer" role="contentinfo">
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