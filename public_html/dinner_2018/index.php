<?php
require_once '../init/initialize_general.php';
$get_params = allowed_get_params(['r']);
$user_code_encrypted = $get_params['r'];
if (empty($user_code_encrypted)) {
    redirect_to("https://instafxng.com");
    exit;
}
$user_code = decrypt(str_replace(" ", "+", $user_code_encrypted));
$user_code = preg_replace("/[^A-Za-z0-9 ]/", '', $user_code);
$client_operation = new clientOperation();
$details = $client_operation->get_user_by_code($user_code);
extract($details);
if (empty($details) || $details = NULL) {
    redirect_to("https://instafxng.com");
    exit;
}
$query = "SELECT * FROM dinner_2018 WHERE user_code = '$user_code' AND choice = '2'";
$numrows = $db_handle->numRows($query);
if($numrows == 1){
    $maybe = true;
}

if (isset($_POST['submit1']) || isset($_POST['submit2'])) {
    $avatar = $db_handle->sanitizePost($_POST['avatar']);
    $choice = $db_handle->sanitizePost($_POST['choice']);
    $title = $db_handle->sanitizePost($_POST['title']);
    $town = $db_handle->sanitizePost($_POST['town']);
    $state = $db_handle->sanitizePost($_POST['state']);
    $query = "SELECT * FROM dinner_2018 WHERE user_code = '$user_code' AND (choice = '1' OR choice = '3')";
    $numrows = $db_handle->numRows($query);
    if($numrows == 0) {
        if ($choice == 1 && !empty($avatar) && $avatar != NULL && !empty($town) && $town != NULL && !empty($state) && $state != NULL && !empty($title) && $title != NULL) {
            if ($maybe == true) {
                $query = "UPDATE dinner_2018 SET choice = '$choice' WHERE user_code = '$user_code'";
            } else {
                $query = "INSERT IGNORE INTO dinner_2018 (user_code, choice, title, town, gender, state, type) VALUE('$client_user_code', '$choice', '$title', '$town', '$avatar', '$state', '1')";

            }
            $db_handle->runQuery($query);
            if ($avatar == 1) {
                $gender = "his";
            } elseif ($avatar == 2) {
                $gender = "her";
            }
            $subject = 'Your seat has been reserved, ' . $client_first_name . '!';
            $message_final = <<<MAIL
                    <div style="background-color: #F3F1F2">
                        <div style="max-width: 80%; margin: 0 auto; padding: 10px; font-size: 14px; font-family: Verdana;">
                            <img src="https://instafxng.com/images/ifxlogo.png" />
                            <hr />
                            <div style="background-color: #FFFFFF; padding: 15px; margin: 5px 0 5px 0;">
                                <p>All hail $title $client_full_name, the first of $gender name from the house of $town</p>
                                <p>It is our pleasure to receive your consent to attend the Royal Ball</p>
                                <p>Your seat has been reserved and your dynasty is being prepared to receive your presence.</p>
                                <p>The royal invite will be sent when all is set, brace up it’s going to be a ball to remember.</p>
                                <p>Complement of the seasons</p>
                                <br /><br />
                                <p>Best Regards,</p>
                                <p>Mercy,</p>
                                <p>Client Relationship Manager</p>
                                <p>InstaFxNg Team,<br />
                                   www.instafxng.com</p>
                                <br /><br />
                            </div>
                            <hr />
                            <div style="background-color: #EBDEE9;">
                                <div style="font-size: 11px !important; padding: 15px;">
                                    <p style="text-align: center"><span style="font-size: 12px"><strong>We"re Social</strong></span><br /><br />
                                        <a href="https://facebook.com/InstaFxNg"><img src="https://instafxng.com/images/Facebook.png"></a>
                                        <a href="https://twitter.com/instafxng"><img src="https://instafxng.com/images/Twitter.png"></a>
                                        <a href="https://www.instagram.com/instafxng/"><img src="https://instafxng.com/images/instagram.png"></a>
                                        <a href="https://www.youtube.com/channel/UC0Z9AISy_aMMa3OJjgX6SXw"><img src="https://instafxng.com/images/Youtube.png"></a>
                                        <a href="https://linkedin.com/company/instaforex-ng"><img src="https://instafxng.com/images/LinkedIn.png"></a>
                                    </p>
                                    <p><strong>Head Office Address:</strong> TBS Place, Block 1A, Plot 8, Diamond Estate, Estate Bus-Stop, LASU/Isheri road, Isheri Olofin, Lagos.</p>
                                    <p><strong>Lekki Office Address:</strong> Block A3, Suite 508/509 Eastline Shopping Complex, Opposite Abraham Adesanya Roundabout, along Lekki - Epe expressway, Lagos.</p>
                                    <p><strong>Office Number:</strong> 08139250268, 08083956750</p>
                                    <br />
                                </div>
                                <div style="font-size: 10px !important; padding: 15px; text-align: center;">
                                    <p>This email was sent to you by Instant Web-Net Technologies Limited, the
                                        official Nigerian Representative of Instaforex, operator and administrator
                                        of the website www.instafxng.com</p>
                                    <p>To ensure you continue to receive special offers and updates from us,
                                        please add support@instafxng.com to your address book.</p>
                                </div>
                            </div>
                        </div>
                    </div>
MAIL;
            $system_object->send_email($subject, $message_final, $client_email, $client_first_name);
            header('Location: completed.php?z=' . $choice);
        } else {
        $message_error = "Reservation Not Successful Kindly Try Again";
    }
        if ($choice == 2) {
        $query = "INSERT IGNORE INTO dinner_2018 (user_code, choice) VALUE('$client_user_code', '$choice')";
        $subject = 'The Ball Will Be Brighter With Your Presence';
        $message_final = <<<MAIL
                    <div style="background-color: #F3F1F2">
                        <div style="max-width: 80%; margin: 0 auto; padding: 10px; font-size: 14px; font-family: Verdana;">
                            <img src="https://instafxng.com/images/ifxlogo.png" />
                            <hr />
                            <div style="background-color: #FFFFFF; padding: 15px; margin: 5px 0 5px 0;">
                            <p>Your royal highness,</p>
                            <p>It will be a pleasure to receive you at this year’s Royal ball.</p>
                            <p>However, we understand that the season is very eventful and you are quite uncertain of your attendance.</p>
                            <p>To this end, our dynasty has decided to reserve your spot for the next 5 nights!</p>
                            <p>The royal raven will be back to get your final decision by the fifth night.</p>
                            <p>This ball will be one to remember forever… We look forward to hosting your royalty.</p>
                                <br /><br />
                                <p>Best Regards,</p>
                                <p>Mercy,</p>
                                <p>Client Relationship Manager</p>
                                <p>InstaFxNg Team,<br />
                                   www.instafxng.com</p>
                                <br /><br />
                            </div>
                            <hr />
                            <div style="background-color: #EBDEE9;">
                                <div style="font-size: 11px !important; padding: 15px;">
                                    <p style="text-align: center"><span style="font-size: 12px"><strong>We"re Social</strong></span><br /><br />
                                        <a href="https://facebook.com/InstaFxNg"><img src="https://instafxng.com/images/Facebook.png"></a>
                                        <a href="https://twitter.com/instafxng"><img src="https://instafxng.com/images/Twitter.png"></a>
                                        <a href="https://www.instagram.com/instafxng/"><img src="https://instafxng.com/images/instagram.png"></a>
                                        <a href="https://www.youtube.com/channel/UC0Z9AISy_aMMa3OJjgX6SXw"><img src="https://instafxng.com/images/Youtube.png"></a>
                                        <a href="https://linkedin.com/company/instaforex-ng"><img src="https://instafxng.com/images/LinkedIn.png"></a>
                                    </p>
                                    <p><strong>Head Office Address:</strong> TBS Place, Block 1A, Plot 8, Diamond Estate, Estate Bus-Stop, LASU/Isheri road, Isheri Olofin, Lagos.</p>
                                    <p><strong>Lekki Office Address:</strong> Block A3, Suite 508/509 Eastline Shopping Complex, Opposite Abraham Adesanya Roundabout, along Lekki - Epe expressway, Lagos.</p>
                                    <p><strong>Office Number:</strong> 08139250268, 08083956750</p>
                                    <br />
                                </div>
                                <div style="font-size: 10px !important; padding: 15px; text-align: center;">
                                    <p>This email was sent to you by Instant Web-Net Technologies Limited, the
                                        official Nigerian Representative of Instaforex, operator and administrator
                                        of the website www.instafxng.com</p>
                                    <p>To ensure you continue to receive special offers and updates from us,
                                        please add support@instafxng.com to your address book.</p>
                                </div>
                            </div>
                        </div>
                    </div>
MAIL;
        $system_object->send_email($subject, $message_final, $client_email, $last_name);
        $db_handle->runQuery($query);
        header('Location: completed.php?z=' . $choice);
    } else {
            $message_error = "Reservation Not Successful Kindly Try Again";
        }
        if ($choice == 3) {
        $query = "INSERT IGNORE INTO dinner_2018 (user_code, choice) VALUE('$client_user_code', '$choice')";
        $db_handle->runQuery($query);
        $subject = 'The Ball Would have been more fun with you ' . $client_first_name . '!';
        $message_final = <<<MAIL
                    <div style="background-color: #F3F1F2">
                        <div style="max-width: 80%; margin: 0 auto; padding: 10px; font-size: 14px; font-family: Verdana;">
                            <img src="https://instafxng.com/images/ifxlogo.png" />
                            <hr />
                            <div style="background-color: #FFFFFF; padding: 15px; margin: 5px 0 5px 0;">
                                <p>Dear $client_first_name,</p>
                                <p>The ball would be incomplete without your presence!</p>
                                <p>Even though our desire is to have you grace this year’s grand dinner, we understand that there are other pertinent tasks that will require your time this season, hence your inability to attend this event.</p>
                                <p>We look forward to celebrating a greater feat with you next year.</p>
                                <p>Your invite for this year’s ball has been canceled.</p>
                                <p>Compliment of the season.</p>
                                <br /><br />
                                <p>Best Regards,</p>
                                <p>Mercy,</p>
                                <p>Client Relationship Manager</p>
                                <p>InstaFxNg Team,<br />
                                   www.instafxng.com</p>
                                <br /><br />
                            </div>
                            <hr />
                            <div style="background-color: #EBDEE9;">
                                <div style="font-size: 11px !important; padding: 15px;">
                                    <p style="text-align: center"><span style="font-size: 12px"><strong>We"re Social</strong></span><br /><br />
                                        <a href="https://facebook.com/InstaFxNg"><img src="https://instafxng.com/images/Facebook.png"></a>
                                        <a href="https://twitter.com/instafxng"><img src="https://instafxng.com/images/Twitter.png"></a>
                                        <a href="https://www.instagram.com/instafxng/"><img src="https://instafxng.com/images/instagram.png"></a>
                                        <a href="https://www.youtube.com/channel/UC0Z9AISy_aMMa3OJjgX6SXw"><img src="https://instafxng.com/images/Youtube.png"></a>
                                        <a href="https://linkedin.com/company/instaforex-ng"><img src="https://instafxng.com/images/LinkedIn.png"></a>
                                    </p>
                                    <p><strong>Head Office Address:</strong> TBS Place, Block 1A, Plot 8, Diamond Estate, Estate Bus-Stop, LASU/Isheri road, Isheri Olofin, Lagos.</p>
                                    <p><strong>Lekki Office Address:</strong> Block A3, Suite 508/509 Eastline Shopping Complex, Opposite Abraham Adesanya Roundabout, along Lekki - Epe expressway, Lagos.</p>
                                    <p><strong>Office Number:</strong> 08139250268, 08083956750</p>
                                    <br />
                                </div>
                                <div style="font-size: 10px !important; padding: 15px; text-align: center;">
                                    <p>This email was sent to you by Instant Web-Net Technologies Limited, the
                                        official Nigerian Representative of Instaforex, operator and administrator
                                        of the website www.instafxng.com</p>
                                    <p>To ensure you continue to receive special offers and updates from us,
                                        please add support@instafxng.com to your address book.</p>
                                </div>
                            </div>
                        </div>
                    </div>
MAIL;
        $system_object->send_email($subject, $message_final, $client_email, $last_name);
        header('Location: completed.php?z=' . $choice);
    } else {
        $message_error = "Reservation Not Successful Kindly Try Again";
    }
    }else{
        $message_error = "You have completed the reservation process Earlier!!!.";

    }
}

?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta http-equiv="x-ua-compatible" content="ie=edge">
    <title>InstaFxNg Dinner 2018</title>
    <link rel="shortcut icon" type="image/x-icon" href="../images/favicon.png">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">
    <!-- Bootstrap core CSS -->
    <link href="css/bootstrap.min.css" rel="stylesheet">
    <!-- Material Design Bootstrap -->
    <link href="css/mdb.min.css" rel="stylesheet">
    <!-- Your custom styles (optional) -->
    <link href="css/style.min.css" rel="stylesheet">
    <style type="text/css">
        html,
        body,
        header,
        .view {
            height: 100%;
        }

        @media (max-width: 740px) {
            html,
            body,
            header,
            .view {
                height: 1000px;
            }
        }

        @media (min-width: 800px) and (max-width: 850px) {
            html,
            body,
            header,
            .view {
                height: 650px;
            }
        }

        @media (min-width: 800px) and (max-width: 850px) {
            .navbar:not(.top-nav-collapse) {
                background: #1C2331 !important;
            }
        }
    </style>
</head>

<body style="background-color: #ff8fe7; background-image: url('img/doodles.png');">

<!-- Full Page Intro -->
<div class="view full-page-intro"
     style=" background-color: transparent; background-repeat: no-repeat; background-size: cover;">
<a href="https//:instafxng.com">
    <div id="page_reloader"
         style="border-radius: 10px; z-index: 5; max-width:500px; position: fixed; top: 20px; left: 30px; background-image: url('img/gilt.jpg');">
        <img
            src="img/ifxlogo.png"
            class="img-fluid img img-responsive" alt="">
    </div>
    </a>
    <!-- Mask & flexbox options-->
    <div class="mask rgba-black-light d-flex justify-content-center align-items-center">

        <!-- Content -->
        <div class="container">

            <!--Grid row-->
            <div class="row wow fadeIn">

                <!--Grid column-->
                <div class="col-md-12 mb-3 white-text text-center" style=" background-color: transparent; ">

                    <hr class="hr-light">
                    <center><img
                            src="img/roll.png"
                            class="img-fluid img img-responsive" alt=""></center>
                    <strong>
                        <hr class="hr-light">
                    </strong>

                    <div class="row">
                        <div class="col-md-4"></div>
                        <!--Card-->
                        <div class="card col-md-4"
                             style=" background-color: transparent;background-image: url('img/form_bg.png');">
                            <!--Card content-->
                            <div class="card-body">
                                <p class="text-center"
                                   style="font-family: cursive !important; font-size:15px; border-radius:5px; background-color: rgba(205, 151, 37, 0.89); color: black;">
                                    <b>Complete the form below to reserve your seat.</b>
                                </p>

                                <!-- Heading -->
                                <?php include '../layouts/feedback_message.php'; ?>
                                <hr>
                                <form style="color: black;" class="form-horizontal" role="form" method="post" action="">
                                    <strong>
                                        <div class="md-form text-left">
                                            <i class="fa fa-user prefix grey-text"></i>
                                            <input style="padding-left:10px; background-color:white; color:black; "
                                                   value="<?php echo $client_full_name; ?>" type="text" id="form3"
                                                   class="form-control" disabled>
                                            <label for="form3">Your name</label>
                                        </div>
                                        <div class="md-form text-left">
                                            <i class="fa fa-envelope prefix grey-text"></i>
                                            <input style="padding-left:10px; background-color:white; color:black; "
                                                   value="<?php echo $client_email; ?>" type="text" id="form2"
                                                   class="form-control"
                                                   disabled>
                                            <label for="form2">Your email</label>
                                        </div>
                                    </strong>
                                    <p class="text-center"><b>Would You Be in attendance?</b></p>
                                    <b>
                                        <div class="row">
                                            <div class="col-md-2"></div>
                                            <!-- Group of default radios - option 1 -->
                                            <div class="col-md-3 custom-control custom-radio">
                                                <input onchange="you('1')" value="1" type="radio"
                                                       class="custom-control-input"
                                                       id="defaultGroupExample1" name="choice">
                                                <label class="custom-control-label"
                                                       for="defaultGroupExample1">YES</label>
                                            </div>

                                            <!-- Group of default radios - option 2 -->
                                            <div class="col-md-3 custom-control custom-radio">
                                                <input onchange="you('2')" value="2" type="radio"
                                                       class="custom-control-input"
                                                       id="defaultGroupExample2" name="choice">
                                                <label class="custom-control-label"
                                                       for="defaultGroupExample2">MAYBE</label>
                                            </div>

                                            <!-- Group of default radios - option 3 -->
                                            <div class="col-md-3 custom-control custom-radio">
                                                <input onchange="you('3')" value="3" type="radio"
                                                       class="custom-control-input"
                                                       id="defaultGroupExample3" name="choice">
                                                <label class="custom-control-label"
                                                       for="defaultGroupExample3">NO</label>
                                            </div>
                                            <div class="col-md-1"></div>

                                        </div>



                                        <div class="text-center" id="proceed" style="display: none;">
                                            <a href="#continue" class="btn btn-indigo">PROCEED <i
                                                    class="fa fa-arrow-down"></i></a>
                                            <hr>
                                        </div>
                                        <div class="text-center" style="display: none;" id="submit">
                                            <button name="submit1" type="submit" class="btn btn-indigo">SUBMIT</button>
                                            <hr>
                                        </div>
                                    </b>
                            </div>
                        </div>
                        <!--/.Card-->
                        <div class="col-md-4"></div>

                    </div>

                </div>
                <!--Grid column-->
            </div>
            <!--Grid row-->

        </div>
        <!-- Content -->

    </div>
    <!-- Mask & flexbox options-->

</div>
<!-- Full Page Intro -->

<!--Main layout-->
<main>
    <div class="container-fluid">
        <div id="continue"></div>

        <!--Section: Main info-->
        <section class="card container mt-5 wow fadeIn" style="border-radius:25px; background-color: #ff8fe7;">
            <!--Grid row-->
            <div class="row wow fadeIn text-center">

                <!--Grid column-->
                <div class="col-lg-6 col-md-6 mb-4">

                    <img
                        src="img/ball_logo.png"
                        class="img-fluid img img-responsive" alt="">

                </div>
                <!--Grid column-->

                <!--Grid column-->
                <div class=" col-lg-6 col-md-6 mb-4 text-lg-center">
                    <h3 style="background-color: rgba(128, 128, 128, 0.15); font-family: Sofia"
                        class="h3 text-center mb-5">About the Event</h3>
                    <strong style="font-size:18px;">

                        <p>The InstaFxNg 2018 dinner event is themed ‘Royal Ball’ in celebration of your loyalty and
                            support to our brand.</p>
                        <p>This year’s dinner is set to celebrate your royalty and loyalty in crown, wine and great
                            splendor!</p>
                        <p>We’re set to bring you a wonderful and an unforgettable evening at the ball as we host you to
                            an amazing experience of royalty that you are.</p>
                        <p>Your throne is set and the dinner is royal!</p>
                        <p>Come and be a part of our extravagant experience.</p>
                    </strong>

                </div>
                <!--Grid column-->
            </div>
            <!--Grid row-->
        </section>
        <!--Section: Main info-->

        <!--Section: Main info-->
        <section id="select_avatar" class="mt-5 container-fluid wow fadeIn" title="Click on an image to select your style"
                 style="margin:0px; padding:0px; display:none; background-color:white;">
            <h3 style="background-color: rgba(128, 128, 128, 0.15); font-family: Sofia" class="h3 text-center mb-5">
                Select Your Style</h3>
            <div class="row">
                <div class="col-md-3"></div>
                <p class="col-md-6 card text-center" style="font-size:20px;"><i>"People will stare. Make it worth their while." Harry
                        Winston</i></p>
                <div class="col-md-3"></div>
            </div>
            <!--Grid row-->
            <div class="row wow fadeIn text-center">
                <!--Grid column-->
                <div class="col-md-6 text-center">

                    <div class="row text-center" style="padding:50px">
                        <!--Card-->
                        <div class="btn">

                            <!--Card image-->
                            <div data-toggle="modal" data-target="#sideModalTR3">
                                <img src="img/male3.png"
                                     class="card-img-top" alt="" style="height:200px; width:150px;">
                            </div>
                            <!--Card image-->
                            <!-- Side Modal Top Right -->

                            <!-- To change the direction of the modal animation change .right class -->
                            <div class="modal fade right" id="sideModalTR3" tabindex="-1" role="dialog"
                                 aria-labelledby="myModalLabel" aria-hidden="true">

                                <!-- Add class .modal-side and then add class .modal-top-right (or other classes from list above) to set a position to the modal -->
                                <div class="modal-dialog modal-side modal-top-right" role="document">


                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                <span aria-hidden="true">&times;</span>
                                            </button>
                                        </div>
                                        <div class="modal-body">
                                            <img src="img/male3.png"
                                                 class="card-img-top" alt="">
                                            <span data-dismiss="modal" onclick="select_avatar('1')"
                                                  class="btn btn-indigo">SELECT</span>

                                        </div>
                                    </div>
                                </div>
                            </div>
                            <!-- Side Modal Top Right -->

                        </div>
                        <!--Card-->
                        <!--Card-->
                        <div class="btn">

                            <!--Card image-->
                            <div data-toggle="modal" data-target="#sideModalTR4">
                                <img src="img/male4.png"
                                     class="card-img-top" alt="" style="height:200px; width:150px;">
                            </div>
                            <!--Card image-->
                            <!-- Side Modal Top Right -->

                            <!-- To change the direction of the modal animation change .right class -->
                            <div class="modal fade right" id="sideModalTR4" tabindex="-1" role="dialog"
                                 aria-labelledby="myModalLabel" aria-hidden="true">

                                <!-- Add class .modal-side and then add class .modal-top-right (or other classes from list above) to set a position to the modal -->
                                <div class="modal-dialog modal-side modal-top-right" role="document">


                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                <span aria-hidden="true">&times;</span>
                                            </button>
                                        </div>
                                        <div class="modal-body">
                                            <img src="img/male4.png"
                                                 class="card-img-top" alt="">
                                            <span data-dismiss="modal" onclick="select_avatar('1')"
                                                  class="btn btn-indigo">SELECT</span>

                                        </div>
                                    </div>
                                </div>
                            </div>
                            <!-- Side Modal Top Right -->

                        </div>
                        <!--Card-->
                        <!--Card-->
                        <div class=" btn">

                            <!--Card image-->
                            <div data-toggle="modal" data-target="#sideModalTR9">
                                <img src="img/male9.png"
                                     class="card-img-top" alt="" style="height:200px; width:150px;">
                            </div>
                            <!--Card image-->
                            <!-- Side Modal Top Right -->

                            <!-- To change the direction of the modal animation change .right class -->
                            <div class="modal fade right" id="sideModalTR9" tabindex="-1" role="dialog"
                                 aria-labelledby="myModalLabel" aria-hidden="true">

                                <!-- Add class .modal-side and then add class .modal-top-right (or other classes from list above) to set a position to the modal -->
                                <div class="modal-dialog modal-side modal-top-right" role="document">


                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                <span aria-hidden="true">&times;</span>
                                            </button>
                                        </div>
                                        <div class="modal-body">
                                            <img src="img/male9.png"
                                                 class="card-img-top" alt="">
                                            <span data-dismiss="modal" onclick="select_avatar('1')"
                                                  class="btn btn-indigo">SELECT</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <!-- Side Modal Top Right -->

                        </div>
                        <!--Card-->
                        <!--Card-->
                        <div class=" btn">

                            <!--Card image-->
                            <div data-toggle="modal" data-target="#sideModalTR10">
                                <img src="img/male10.jpg"
                                     class="card-img-top" alt="" style="height:200px; width:150px;">
                            </div>
                            <!--Card image-->
                            <!-- Side Modal Top Right -->

                            <!-- To change the direction of the modal animation change .right class -->
                            <div class="modal fade right" id="sideModalTR10" tabindex="-1" role="dialog"
                                 aria-labelledby="myModalLabel" aria-hidden="true">

                                <!-- Add class .modal-side and then add class .modal-top-right (or other classes from list above) to set a position to the modal -->
                                <div class="modal-dialog modal-side modal-top-right" role="document">


                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                <span aria-hidden="true">&times;</span>
                                            </button>
                                        </div>
                                        <div class="modal-body">
                                            <img src="img/male10.jpg"
                                                 class="card-img-top" alt="">
                                            <span data-dismiss="modal" onclick="select_avatar('1')"
                                                  class="btn btn-indigo">SELECT</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <!-- Side Modal Top Right -->

                        </div>
                        <!--Card-->
                    </div>
                    <h5>
                        <strong>
                            <a href="" class="text-center dark-grey-text">
                                <b id="duke"
                                   style="display:none; background-color: #d7d7d7;
                                               border-radius:8px; color:green !important;
                                               box-shadow: 0 4px 8px 0 rgb(0, 128, 0), 0 6px 20px 0 rgba(255, 11, 0, 0.83)"">
                                <span class="fa fa-check"></span></b>
                            </a>
                        </strong>
                    </h5>
                </div>
                <!--Grid column-->

                <!--Grid column-->
                <div class="col-md-6 text-center">

                    <div class="row text-center" style="padding:50px">
                        <!--Card-->
                        <div class="btn">

                            <!--Card image-->
                            <div data-toggle="modal" data-target="#sideModalTf1">
                                <img src="img/ball1.jpg"
                                     class="card-img-top" style="height:200px; width:150px;" alt="">
                            </div>
                            <!--Card image-->
                            <!-- Side Modal Top Right -->

                            <!-- To change the direction of the modal animation change .right class -->
                            <div class="modal fade right" id="sideModalTf1" tabindex="-1" role="dialog"
                                 aria-labelledby="myModalLabel" aria-hidden="true">

                                <!-- Add class .modal-side and then add class .modal-top-right (or other classes from list above) to set a position to the modal -->
                                <div class="modal-dialog modal-side modal-top-right" role="document">


                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                <span aria-hidden="true">&times;</span>
                                            </button>
                                        </div>
                                        <div class="modal-body">
                                            <img src="img/ball1.jpg"
                                                 class="card-img-top" alt="">
                                            <span data-dismiss="modal" onclick="select_avatar('2')"
                                                  class="btn btn-indigo">SELECT</span>

                                        </div>
                                    </div>
                                </div>
                            </div>
                            <!-- Side Modal Top Right -->

                        </div>
                        <!--Card-->
                        <!--Card-->
                        <div class="btn">

                            <!--Card image-->
                            <div data-toggle="modal" data-target="#sideModalTf3">
                                <img src="img/ball3.jpg"
                                     class="card-img-top" alt="" style="height:200px; width:150px;">
                            </div>
                            <!--Card image-->
                            <!-- Side Modal Top Right -->

                            <!-- To change the direction of the modal animation change .right class -->
                            <div class="modal fade right" id="sideModalTf3" tabindex="-1" role="dialog"
                                 aria-labelledby="myModalLabel" aria-hidden="true">

                                <!-- Add class .modal-side and then add class .modal-top-right (or other classes from list above) to set a position to the modal -->
                                <div class="modal-dialog modal-side modal-top-right" role="document">


                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                <span aria-hidden="true">&times;</span>
                                            </button>
                                        </div>
                                        <div class="modal-body">
                                            <img src="img/ball3.jpg"
                                                 class="card-img-top" alt="">
                                            <span data-dismiss="modal" onclick="select_avatar('2')"
                                                  class="btn btn-indigo">SELECT</span>

                                        </div>
                                    </div>
                                </div>
                            </div>
                            <!-- Side Modal Top Right -->

                        </div>
                        <!--Card-->
                        <!--Card-->
                        <div class=" btn">

                            <!--Card image-->
                            <div data-toggle="modal" data-target="#sideModalTf6">
                                <img src="img/ball6.jpg"
                                     class="card-img-top" alt="" style="height:200px; width:150px;">
                            </div>
                            <!--Card image-->
                            <!-- Side Modal Top Right -->

                            <!-- To change the direction of the modal animation change .right class -->
                            <div class="modal fade right" id="sideModalTf6" tabindex="-1" role="dialog"
                                 aria-labelledby="myModalLabel" aria-hidden="true">

                                <!-- Add class .modal-side and then add class .modal-top-right (or other classes from list above) to set a position to the modal -->
                                <div class="modal-dialog modal-side modal-top-right" role="document">


                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                <span aria-hidden="true">&times;</span>
                                            </button>
                                        </div>
                                        <div class="modal-body">
                                            <img src="img/ball6.jpg"
                                                 class="card-img-top" alt="">
                                            <span data-dismiss="modal" onclick="select_avatar('2')"
                                                  class="btn btn-indigo">SELECT</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <!-- Side Modal Top Right -->


                        </div>
                        <!--Card-->
                        <!--Card-->
                        <div class=" btn">

                            <!--Card image-->
                            <div data-toggle="modal" data-target="#sideModalTf9">
                                <img src="img/ball9.jpg"
                                     class="card-img-top" alt="" style="height:200px; width:150px;">
                            </div>
                            <!--Card image-->
                            <!-- Side Modal Top Right -->

                            <!-- To change the direction of the modal animation change .right class -->
                            <div class="modal fade right" id="sideModalTf9" tabindex="-1" role="dialog"
                                 aria-labelledby="myModalLabel" aria-hidden="true">

                                <!-- Add class .modal-side and then add class .modal-top-right (or other classes from list above) to set a position to the modal -->
                                <div class="modal-dialog modal-side modal-top-right" role="document">


                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                <span aria-hidden="true">&times;</span>
                                            </button>
                                        </div>
                                        <div class="modal-body">
                                            <img src="img/ball9.jpg"
                                                 class="card-img-top" alt="">
                                            <span data-dismiss="modal" onclick="select_avatar('2')"
                                                  class="btn btn-indigo">SELECT</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <!-- Side Modal Top Right -->

                        </div>
                        <!--Card-->
                    </div>
                    <h5>
                        <strong>
                            <a href="" class="text-center dark-grey-text">
                                <b id="duch"
                                   style="display:none; background-color: #d7d7d7;
                                               border-radius:8px; color:green !important;
                                               box-shadow: 0 4px 8px 0 rgb(0, 128, 0), 0 6px 20px 0 rgba(255, 11, 0, 0.83)"">
                                <span class="fa fa-check"></span></b>
                            </a>
                        </strong>
                    </h5>
                </div>
                <!--Grid column-->

                <input id="avatar" type="hidden" name="avatar">
            </div>
            <!--Grid row-->
            <hr class="my-5">
        </section>
        <!--Section: Main info-->


        <!--Section: Main features & Quick Start-->
        <section class="container card" style="display: none; background:white; border-radius:25px; " id="select_title">
            <br>
            <h3 class="h3 text-center mb-5">SELECT YOUR ROYAL TITLE</h3>
            <p class="help-block text-center"><i class="fa fa-info-circle"></i> Example. Emperor of Ile-Ife</p>

            <!--Grid row-->
            <div class="row wow fadeIn">

                <!--Grid column-->
                <div class="col-lg-4 col-md-12 px-4">

                    <div class="modal-body mx-3">

                        <i class="fa fa-title prefix grey-text"> Select Your Title</i>
                        <div class="md-form mb-5">
                            <select id="title" name="title" class="form-control">
                                <option value="Emperor">Emperor</option>
                                <option value="King">King</option>
                                <option value="Duke">Duke</option>
                                <option value="Prince">Prince</option>
                                <option value="Knight">Knight</option>
                                <option value="Sir">Sir</option>
                                <option value="Viscount">Viscount</option>
                                <option value="Lord">Lord</option>
                                <option value="Empress">Empress</option>
                                <option value="Queen">Queen</option>
                                <option value="Princess">Princess</option>
                                <option value="Duchess">Duchess</option>
                                <option value="Lady">Lady</option>
                                <option value="Baronet">Baronet</option>
                                <option value="Earldom">Earldom</option>
                                <option value="Viscountess">Viscountess</option>
                            </select>
                        </div>

                    </div>

                </div>
                <!--/Grid column-->

                <!--Grid column-->
                <div class="col-lg-4 col-md-12">

                    <div class="modal-body mx-3">

                        <div class="md-form">
                            <i class="fa fa-pencil prefix grey-text"></i>
                            <textarea name="town" type="text" id="town" class="md-textarea"></textarea>
                            <label for="form8">Enter Your Home Town</label>
                        </div>

                    </div>
                </div>
                <!--/Grid column-->

                <!--Grid column-->
                <div class="col-lg-4 col-md-12 px-4">

                    <div class="modal-body mx-3">

                        <i class="fa fa-title prefix grey-text"> Select Your Current state of Residence</i>
                        <div class="md-form mb-5">
                            <select id="state" name="state" class="form-control">
                                <option value="Abia State">Abia State</option>
                                <option value="Adamawa State">Adamawa State</option>
                                <option value="Akwa Ibom State">Akwa Ibom State</option>
                                <option value="Anambra State">Anambra State</option>
                                <option value="Bauchi State">Bauchi State</option>
                                <option value="Bayelsa State">Bayelsa State</option>
                                <option value="Benue State">Benue State</option>
                                <option value="Borno State">Borno State</option>
                                <option value="Cross River State">Cross River State</option>
                                <option value="Delta State">Delta State</option>
                                <option value="Ebonyi State">Ebonyi State</option>
                                <option value="Edo State">Edo State</option>
                                <option value="Ekiti State">Ekiti State</option>
                                <option value="Enugu State">Enugu State</option>
                                <option value="FCT Abuja">FCT Abuja</option>
                                <option value="Gombe State">Gombe State</option>
                                <option value="Imo State">Imo State</option>
                                <option value="Jigawa State">Jigawa State</option>
                                <option value="Kaduna State">Kaduna State</option>
                                <option value="Kano State">Kano State</option>
                                <option value="Katsina State">Katsina State</option>
                                <option value="Kebbi State">Kebbi State</option>
                                <option value="Kogi State">Kogi State</option>
                                <option value="Kwara State">Kwara State</option>
                                <option value="Lagos State">Lagos State</option>
                                <option value="Nasarawa State">Nasarawa State</option>
                                <option value="Niger State">Niger State</option>
                                <option value="Ogun State">Ogun State</option>
                                <option value="Ondo State">Ondo State</option>
                                <option value="Osun State">Osun State</option>
                                <option value="Oyo State">Oyo State</option>
                                <option value="Plateau State">Plateau State</option>
                                <option value="Rivers State">Rivers State</option>
                                <option value="Sokoto State">Sokoto State</option>
                                <option value="Taraba State">Taraba State</option>
                                <option value="Yobe State">Yobe State</option>
                                <option value="Zamfara State">Zamfara State</option>
                            </select>
                        </div>

                    </div>

                </div>
                <!--/Grid column-->

            </div>
            <!--/Grid row-->
            <hr class="my-5">
        </section>
        <!--Section: Main features & Quick Start-->


        <!--Section: Not enough-->
        <section id="complete" style="display: none;">

            <h2 class="my-5 h3 text-center">
                <div class="text-center">
                    <button name="submit2" type="submit" class="btn btn-indigo">SUBMIT</button>
                    <hr>
                </div>
            </h2>
        </section>
        <!--Section: Not enough-->

        <!-- Form -->


    </div>
</main>
<!--Main layout-->
</form>
<!--Footer-->
<footer class="page-footer text-center font-small mt-4 wow fadeIn">

    <!--Copyright-->
    <div class="footer-copyright py-3">
        © 2018 Copyright:
        <a href="https://instafxng.com" target="_blank"> InstaFxNg.com</a>
    </div>
    <!--/.Copyright-->

</footer>
<!--/.Footer-->
<!-- SCRIPTS -->
<link href='https://fonts.googleapis.com/css?family=Sofia' rel='stylesheet'>
<script>
    function you(type) {
        console.log(type);
        if (type == '1') {
            document.getElementById("avatar").required = true;
            document.getElementById("title").required = true;
            document.getElementById("town").required = true;
            document.getElementById("state").required = true;
            document.getElementById("submit").style.display = "none";
            document.getElementById("proceed").style.display = "block";
            document.getElementById("select_avatar").style.display = "block";
            document.getElementById("select_title").style.display = "block";
            document.getElementById("complete").style.display = "block";


        }
        else if (type == '2' || type == '3') {
            document.getElementById("submit").style.display = "block";
            document.getElementById("proceed").style.display = "none";
            document.getElementById("select_avatar").style.display = "none";
            document.getElementById("select_title").style.display = "none";
            document.getElementById("complete").style.display = "none";
            document.getElementById("avatar").required = false;
            document.getElementById("title").required = false;
            document.getElementById("town").required = false;
            document.getElementById("state").required = false;
        }
    }
</script>

<script>
    function select_avatar(type) {
        if (type >= 1 && type <= 2) {
            document.getElementById("avatar").value = type;
        }
        if (type == 1) {
            document.getElementById("duke").style.display = "block";
            document.getElementById("duch").style.display = "none";
        }
        else if (type == 2) {
            document.getElementById("duke").style.display = "none";
            document.getElementById("duch").style.display = "block";
        }
    }
</script>
<!-- JQuery -->
<script type="text/javascript" src="js/jquery-3.3.1.min.js"></script>
<!-- Bootstrap tooltips -->
<script type="text/javascript" src="js/popper.min.js"></script>
<!-- Bootstrap core JavaScript -->
<script type="text/javascript" src="js/bootstrap.min.js"></script>
<!-- MDB core JavaScript -->
<script type="text/javascript" src="js/mdb.min.js"></script>
<!-- Initializations -->
<script type="text/javascript">
    // Animations initialization
    new WOW().init();
</script>
</body>

</html>