<?php
session_start();
require_once 'init/initialize_general.php';
$thisPage = "Home";
$signal_object = new Signal_Management();
if(isset($_POST['login'])) {
    $email = $db_handle->sanitizePost($_POST['email']);
    $name = $db_handle->sanitizePost($_POST['name']);
    $phone = $db_handle->sanitizePost($_POST['phone']);
    if(filter_var($email, FILTER_VALIDATE_EMAIL)){
        if(empty($name) || empty($phone)){
            $query = "SELECT name, phone, email FROM signal_users WHERE email = '$email' ";
            if($db_handle->numRows($query) > 0){
                //$_SESSION['signal_schedule_user'] = $email;
                $ifxngsignals = "ifxng_signals";
                $cookie_value = $email;
                setcookie($ifxngsignals, $cookie_value, time() + (86400 * 30), "/"); // 86400 = 1 day
                $message_success = "Welcome Thanks for Subscribing to InstaFxNg Signals <a href='signal_schedules.php'>Click Here To Continue</a>";
            }else{
                $user_details = $db_handle->fetchAssoc($db_handle->runQuery("SELECT phone, CONCAT(first_name, SPACE(1), last_name) AS name FROM user WHERE email = '$email'"));//[0];
                if(!empty($user_details)){
                    foreach($user_details AS $row) {
                        extract($row);
                        $query = "INSERT IGNORE INTO signal_users (name, phone, email) VALUES ('$name', '$phone', '$email') ";
                        if ($db_handle->runQuery($query)) {
                            //$_SESSION['signal_schedule_user'] = $email;
                            $ifxngsignals = "ifxng_signals";
                            $cookie_value = $email;
                            setcookie($ifxngsignals, $cookie_value, time() + (86400 * 30), "/"); // 86400 = 1 day
                            $message_success = "Welcome Thanks for Subscribing to InstaFxNg Signals <a href='signal_schedules.php'>Click Here To Continue</a>";
                        } else {
                            $message_error = "Sorry the operation failed, please try again.";
                            $get_phone_and_name = true;
                        }
                    }
                    }else{
                    $message_error = "Please update your profile details.";
                    $get_phone_and_name = true;
                }
            }
        }else{
            $query = "INSERT IGNORE INTO signal_users (name, phone, email) VALUES ('$name', '$phone', '$email') ";
            if($db_handle->runQuery($query)){
                //$_SESSION['signal_schedule_user'] = $email;
                $ifxngsignals = "ifxng_signals";
                $cookie_value = $email;
                setcookie($ifxngsignals, $cookie_value, time() + (86400 * 30), "/"); // 86400 = 1 day
                $message_success = "Welcome Thanks for Subscribing to InstaFxNg Signals <a href='signal_schedules.php'>Click Here To Continue</a>";
            }else{
                $message_error = "Sorry the operation failed, please try again.";
            }
        }
    }
    else
    {
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
            .se-pre-con {  position: fixed;  left: 0px;  top: 0px;  width: 100%;  height: 100%;  z-index: 9999;  background: url(images/Spinner.gif) center no-repeat #fff;  }
        </style>
        <script src="http://ajax.googleapis.com/ajax/libs/jquery/1.5.2/jquery.min.js"></script>
        <script>$(window).load(function() {$(".se-pre-con").fadeOut("slow");});</script>
        <!--........................................-->
        <base target="_self">
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>Instaforex Nigeria | Online Instant Forex Trading Services</title>
        <meta name="title" content="Instaforex Nigeria | Online Instant Forex Trading Services" />
        <meta name="keywords" content="instaforex, forex trading in nigeria, forex seminar, forex trading seminar, how to trade forex, trade forex, instaforex nigeria">
        <meta name="description" content="Instaforex, a multiple award winning international forex broker is the leading Forex broker in Nigeria, open account and enjoy forex trading with us.">
        <?php require_once 'layouts/head_meta.php'; ?>
        <meta property="og:site_name" content="Instaforex Nigeria" />
        <meta property="og:title" content="Instaforex Nigeria | Online Instant Forex Trading Services" />
        <meta property="og:description" content="Instaforex, a multiple award winning international forex broker is the leading Forex broker in Nigeria, open account and enjoy forex trading with us." />
        <meta property="og:image" content="images/instaforex-100bonus.jpg" />
        <meta property="og:url" content="https://instafxng.com/" />
        <meta property="og:type" content="website" />
        <link rel="stylesheet" href="font-awesome-animation.min.css">
        <!--............................-->
        <link rel="stylesheet" href="https://unpkg.com/simplebar@latest/dist/simplebar.css" />
        <script src="https://unpkg.com/simplebar@latest/dist/simplebar.js"></script>

    </head>
    <body>
    <!--.............................-->
<!--        <div id="page_preloader" class="se-pre-con"></div>-->
    <!--......................-->
        <?php require_once 'layouts/header.php'; ?>
        <!-- Main Body: The is the main content area of the web site, contains a side bar  -->
        <div id="main-body" class="container-fluid">
            <div class="row no-gutter">
                <?php require_once 'layouts/topnav.php'; ?>

                <div id="main-body-side-bar" class="col-md-4 col-lg-3 ">
                    <?php require_once 'layouts/sidebar.php'; ?>
                </div>

                <!-- Main Body - Content Area: This is the main content area, unique for each page  -->
                <div id="main-body-content-area" class="col-md-8 col-lg-9 right-nav">
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
                        <p><div id="page_reloader" style="display: none;" class="alert alert-success">
                                    <!--<a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>-->
                                    <strong>New Updates Availabe!</strong> <a href="signal_schedules.php">Click here to view these updates.</a>
                                </div></p>
                            <div class="row">
                            <div id="sig" class="col-sm-12" style="pointer-events: none">
                                <!-- TradingView Widget BEGIN -->
                                <?php $signal_object->UI_show_live_quotes();?>
                                <!-- TradingView Widget END -->
                            </div>

                            <div class="col-sm-12">
                                <div id="signals-grid" class="row grid">
                                    <?php echo $signal_object->UI_get_signals_for_page();?>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!--///////////////////////////////
                    Login Form Scripting-->
                    <?php if(!isset($_COOKIE['ifxng_signals'])){ ?>
                        <!--Modal - confirmation boxes-->
<!--                        <div data-keyboard="false" data-backdrop="static" id="confirm-add-admin" tabindex="-1" role="dialog" aria-hidden="true" class="modal fade">
-->                            <div   id="confirm-add-admin" tabindex="-1" role="dialog" aria-hidden="true" class="modal fade">
                    <form class="form-horizontal" role="form" method="post" action="">
                                <div class="modal-dialog">
                                    <div class="modal-content">
                                        <div class="modal-body">
                                            <?php include 'layouts/feedback_message.php'; ?>
                                            <center>
                                                <h3 class="text-center"><strong>FOREX TRADING SIGNALS</strong></h3>
                                                <p class="text-center">
                                                    <b>Trade the markets by following the best free trading signals!</b><br/>
                                                    InstaFxNg's trading analysts spot market opportunities and provide
                                                    you with profitable, easy to follow trading signals.
                                                </p>
                                            </center>
                                            <p class="text-justify">To use this service, please enter your email below.</p>
                                            <div class="form-group">
                                                <div class="col-sm-12 col-lg-12">
                                                    <input maxlength="100" value="<?php echo $email ?>" placeholder="Email Address" name="email" type="text" class="form-control" id="email" required>
                                                </div>
                                            </div>
                                            <?php if($get_phone_and_name){ ?>
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
                        <script>$(document).ready(function() {$('#confirm-add-admin').modal("show");});</script>
                    <?php } ?>
                    <!--//////////////////////////////-->


                    <!-- Unique Page Content Ends Here
                    ================================================== -->
                </div>
                <!-- Main Body - Side Bar  -->
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
        setInterval(function(){signal.new_signal_listener();}, 120000);//TODO: Fix this back to 5000
        //setInterval(function(){signal.getQuotes();}, 60000);//TODO: Fix this back to 5000
    </script>
<!--<script>-->
<!--    function cal_gain(id) {-->
<!--        var equity = parseInt(document.getElementById('signal_equity_'+id).value);-->
<!--        var lots = parseFloat(document.getElementById('signal_lots_'+id).value);-->
<!--        var pips = parseInt(document.getElementById('signal_currency_diff_'+id).innerHTML);-->
<!--        console.log('signal_currency_diff_'+id);-->
<!--        if(equity!="" && lots!="" && equity!=null && lots!=null && equity > 0 && lots > 0){-->
<!---->
<!--        var gain = equity + (lots * pips);-->
<!---->
<!--        document.getElementById('signal_gain_'+id).value = "New Equity $"+gain;-->
<!--        document.getElementById('signal_gain_'+id).style.display = 'block';-->
<!---->
<!--        }-->
<!--    }-->
<!--</script>-->
    </body>
</html>