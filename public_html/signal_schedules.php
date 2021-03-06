<?php
session_start();
require_once 'init/initialize_general.php';
$thisPage = "Home";

$signal_object = new Signal_Management();

if (isset($_POST['login'])) {

    $email = $db_handle->sanitizePost($_POST['email']);
    $name = $db_handle->sanitizePost($_POST['name']);
    $phone = $db_handle->sanitizePost($_POST['phone']);

    if (filter_var($email, FILTER_VALIDATE_EMAIL)) {

        if (empty($name) || empty($phone)) {
            $query = "SELECT name, phone, email FROM signal_users WHERE email = '$email' ";

            if ($db_handle->numRows($query) > 0) {

                $ifxngsignals = "ifxng_signals";
                $cookie_value = $email;
                setcookie($ifxngsignals, $cookie_value, time() + (86400 * 30), "/"); // 86400 = 1 day
                redirect_to("signal_schedules.php");

            } else {

                $user_details = $db_handle->fetchAssoc($db_handle->runQuery("SELECT phone, CONCAT(first_name, SPACE(1), last_name) AS name FROM user WHERE email = '$email'"));

                if (!empty($user_details)) {

                    extract($user_details[0]);

                    $query = "INSERT IGNORE INTO signal_users (name, phone, email) VALUES ('$name', '$phone', '$email') ";

                    if ($db_handle->runQuery($query)) {
                        $ifxngsignals = "ifxng_signals";
                        $cookie_value = $email;
                        setcookie($ifxngsignals, $cookie_value, time() + (86400 * 30), "/"); // 86400 = 1 day
                        redirect_to("signal_schedules.php");
                    } else {
                        $message_error = "Something went wrong, please try again.";
                        $get_phone_and_name = true;
                    }

                } else {
                    $message_error = "You are a first time user, please fill the subscription form to continue.";
                    $get_phone_and_name = true;
                }
            }
        } else {
            $query = "INSERT IGNORE INTO signal_users (name, phone, email) VALUES ('$name', '$phone', '$email') ";

            if ($db_handle->runQuery($query)) {
                $ifxngsignals = "ifxng_signals";
                $cookie_value = $email;
                setcookie($ifxngsignals, $cookie_value, time() + (86400 * 30), "/"); // 86400 = 1 day
                redirect_to("signal_schedules.php");

            } else {
                $message_error = "Something went wrong, please try again.";
            }
        }
    } else {
        $message_error = "You entered an invalid email address, please try again.";
    }
}

$scheduled_signals = $signal_object->get_scheduled_signals(date('Y-m-d'));

?>
<!DOCTYPE html>
<html lang="en">
    <head>
        <!--........................................-->
        <style>
            .se-pre-con {
                position: fixed;
                left: 0px;
                top: 0px;
                width: 100%;
                height: 100%;
                z-index: 9999;
                background: url(images/Spinner.gif) center no-repeat #fff;
            }
        </style>
        <script src="http://ajax.googleapis.com/ajax/libs/jquery/1.5.2/jquery.min.js"></script>
        <script>$(window).load(function () {
                $(".se-pre-con").fadeOut("slow");
            });</script>
        <!--........................................-->
        <base target="_self">
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>Instaforex Nigeria | Online Instant Forex Trading Services</title>
        <meta name="title" content="Instaforex Nigeria | Online Instant Forex Trading Services"/>
        <meta name="keywords" content="instaforex, forex trading in nigeria, forex seminar, forex trading seminar, how to trade forex, trade forex, instaforex nigeria">
        <meta name="description" content="Instaforex, a multiple award winning international forex broker is the leading Forex broker in Nigeria, open account and enjoy forex trading with us.">
        <?php require_once 'layouts/head_meta.php'; ?>
        <meta property="og:site_name" content="Instaforex Nigeria"/>
        <meta property="og:title" content="Instaforex Nigeria | Online Instant Forex Trading Services"/>
        <meta property="og:description" content="Instaforex, a multiple award winning international forex broker is the leading Forex broker in Nigeria, open account and enjoy forex trading with us."/>
        <meta property="og:image" content="images/instaforex-100bonus.jpg"/>
        <meta property="og:url" content="https://instafxng.com/"/>
        <meta property="og:type" content="website"/>
        <link rel="stylesheet" href="font-awesome-animation.min.css">
        <!--............................-->
        <link rel="stylesheet" href="https://unpkg.com/simplebar@latest/dist/simplebar.css"/>
        <script src="https://unpkg.com/simplebar@latest/dist/simplebar.js"></script>
    </head>
    <body>
        <?php require_once 'layouts/header.php'; ?>
        <!-- Main Body: The is the main content area of the web site, contains a side bar  -->
        <div id="main-body" class="container-fluid">
            <div class="row no-gutter">
                <?php require_once 'layouts/topnav.php'; ?>
                <!-- Main Body - Content Area: This is the main content area, unique for each page  -->

                <div id="main-body-content-area" class="col-md-8 col-md-push-4 col-lg-9 col-lg-push-3">

                    <section id="signals_div">
                        <!-- Unique Page Content Starts Here
                        ================================================== -->
                        <div class="super-shadow page-top-section">
                            <div class="row ">
                                <div class="col-sm-12">
                                    <h3 class="text-center"><strong>FOREX TRADING SIGNALS</strong></h3>
                                    <p class="text-center">
                                        <b>Trade the markets by following the best free trading signals!</b><br/>
                                        InstaFxNg's trading analysts spot market opportunities and provide
                                        you with profitable, easy to follow trading signals.
                                    </p>
                                </div>
                            </div>
                        </div>

                        <div class="section-tint super-shadow">

                            <?php include 'layouts/feedback_message.php'; ?>

                            <div id="page_reloader" style="z-index: 5; max-width:500px; display: none; position: fixed; top: 20px; right: 10px;" class="alert alert-success">
                                <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
                                <strong>New Updates Available!</strong> <a href="signal_schedules.php">Click here to view these updates.</a>
                            </div>

                            <div class="row">
                                <div class="col-sm-12" style="pointer-events: none">
                                    <!-- TradingView Widget BEGIN -->
                                    <?php $signal_object->UI_show_live_quotes(); ?>
                                    <!-- TradingView Widget END -->
                                </div>

                                <div class="col-sm-12">
                                    <div id="signals-grid" class="row grid">
                                        <?php echo $signal_object->UI_get_signals_for_page(); ?>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!--///////////////////////////////
                        Login Form Scripting-->
                        <?php if (!isset($_COOKIE['ifxng_signals'])) { ?>
                            <!--Modal - confirmation boxes-->
                            <!--                <div  data-backdrop="static" id="confirm-add-admin" tabindex="-1" role="dialog" aria-hidden="true" class="modal fade">-->
                            <div data-backdrop="static" data-keyboard="false" id="confirm-add-admin" tabindex="-1" role="dialog"
                                 aria-hidden="true" class="modal fade">
                                <form class="form-horizontal" role="form" method="post" action="">
                                    <div class="modal-dialog">
                                        <div class="modal-content">
                                            <div class="modal-body">
                                                <?php include 'layouts/feedback_message.php'; ?>

                                                <h3 class="text-center"><strong>FOREX TRADING SIGNALS</strong></h3>
                                                <p class="text-center">
                                                    <b>Trade the markets by following the best free trading signals!</b><br/>
                                                    InstaFxNg's trading analysts spot market opportunities and provide
                                                    you with profitable, easy to follow trading signals.
                                                </p>

                                                <p class="text-justify">To use this service, please enter your email below.</p>
                                                <div class="form-group">
                                                    <div class="col-sm-12 col-lg-12">
                                                        <input maxlength="100" value="<?php echo $email; ?>" placeholder="Email Address" name="email" type="text" class="form-control" id="email" required>
                                                    </div>
                                                </div>

                                                <?php if ($get_phone_and_name) { ?>
                                                    <div class="form-group">
                                                        <div class="col-sm-12 col-lg-12">
                                                            <input maxlength="100" value="<?php echo $name ?>" placeholder="Full Name" name="name" type="text" class="form-control" id="name" required>
                                                        </div>
                                                    </div>
                                                    <div class="form-group">
                                                        <div class="col-sm-12 col-lg-12">
                                                            <input maxlength="20" value="<?php echo $phone ?>" placeholder="Phone Number" name="phone" type="text" class="form-control" id="phone" required>
                                                        </div>
                                                    </div>
                                                <?php } ?>

                                            </div>

                                            <div class="modal-footer">
                                                <button type="submit" name="login" class="btn btn-success">Proceed!</button>
                                            </div>
                                        </div>
                                    </div>
                                </form>
                            </div>
                            <script>$(document).ready(function () {
                                    $('#confirm-add-admin').modal("show");
                                });</script>
                        <?php } ?>
                        <!--//////////////////////////////-->


                        <!-- Unique Page Content Ends Here
                        ================================================== -->

                        <!-- Main Body - Side Bar  -->
                    </section>
                </div>

                <div id="main-body-side-bar" class="col-md-4 col-md-pull-8 col-lg-3 col-lg-pull-9 left-nav">
                    <?php require_once 'layouts/sidebar.php'; ?>
                </div>

            </div>
        </div>
        <?php require_once 'layouts/footer.php'; ?>

        <!--    <script src="https://www.gstatic.com/firebasejs/5.3.0/firebase.js"></script>-->
        <!--    <!-- Firebase App is always required and must be first -->
        <!--    <script src="https://www.gstatic.com/firebasejs/5.3.0/firebase-app.js"></script>-->
        <!--    <!-- Add additional services that you want to use -->
        <!--    <script src="https://www.gstatic.com/firebasejs/5.3.0/firebase-messaging.js"></script>-->
        <!--    <script src="https://www.gstatic.com/firebasejs/5.3.0/firebase-functions.js"></script>-->
        <!--    <script>-->
        <!--        // Initialize Firebase-->
        <!--        var config = {-->
        <!--            apiKey: "AIzaSyCdT2R-aTP1V-MtQ3K8QTIGauSiijRr_6k",-->
        <!--            authDomain: "instafxng-signals-e6755.firebaseapp.com",-->
        <!--            databaseURL: "https://instafxng-signals-e6755.firebaseio.com",-->
        <!--            projectId: "instafxng-signals-e6755",-->
        <!--            storageBucket: "instafxng-signals-e6755.appspot.com",-->
        <!--            messagingSenderId: "179558919499"-->
        <!--        };-->
        <!--        firebase.initializeApp(config);-->
        <!--    </script>-->
        <script>
            signal.new_signal_listener();
            //signal.getQuotes();
            setInterval(function () {
                signal.new_signal_listener();
            }, 30000);//TODO: Fix this back to 5000
            //setInterval(function(){signal.getQuotes();},60000);//TODO: Fix this back to 5000
        </script>
        <script>
            function cal_gain(id) {
                var equity = parseInt(document.getElementById('signal_equity_' + id).value);
                //var lots = parseFloat(document.getElementById('signal_lots_' + id).value);
                //var pips = parseInt(document.getElementById('signal_currency_diff_' + id).innerHTML);
                if (equity != "" && equity != null && equity > 0) {

                    var per = equity * 0.01;
                    var lots = per / 15;
                    lots = lots.toFixed(2);
                    if(lots == 0.00){lots = 0.01;}
                    document.getElementById('signal_gain_' + id).value =  lots +" Advised Volume" ;
                    document.getElementById('signal_gain_' + id).style.display = 'block';

                }
            }
        </script>

        <div id="advert_signals" style="z-index: 5; display: none; position: fixed; bottom: 10px; right: 5px;" class="alert alert-info pull-right">
            <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
            <i class="fa fa-paw"></i> <a href="http://bit.ly/2wBKzob" target="_blank" class="alert-link">Win big in the ILPR Promo, Click Here!</a><br>
            <i class="fa fa-paw"></i> <a href="http://bit.ly/2N4RTD5" target="_blank" class="alert-link">Learn Forex Trading for FREE, Click Here.</a><br>
        </div>

        <script>
            //poopup after 3mins
            setTimeout(advert_signals, 180000);
            function advert_signals() { document.getElementById('advert_signals').style.display = 'block'; }
            //closes after 2mins
            setTimeout(advert_signals_hide, 120000);
            function advert_signals_hide() { document.getElementById('advert_signals').style.display = 'none'; }
        </script>

    </body>
</html>