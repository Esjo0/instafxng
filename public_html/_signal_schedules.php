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
                $_SESSION['signal_schedule_user'] = $email;
            }else{
                $user_details = $db_handle->fetchAssoc($db_handle->runQuery("SELECT phone, CONCAT(first_name, SPACE(1), last_name) AS name FROM users WHERE email = '$email'"));//[0];
                var_dump($user_details);
                if(empty($user_details['phone']) || empty($user_details['name'])){
                    $message_error = "Please update your profile details.";
                    $get_phone_and_name = true;
                }
            }
        }else{
            $query = "INSERT IGNORE INTO signal_users (name, phone, email) VALUES ('$name', '$phone', '$email') ";
            if($db_handle->runQuery($query)){
                $_SESSION['signal_schedule_user'] = $email;
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

        <!--<script>signal.get_signals_for_page();</script>-->
        <!--................................-->
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
                        <div class="row">
                            <div class="col-sm-12">
                                <?php include 'layouts/feedback_message.php'; ?>
                                <div id="page_reloader" style="display: none" class="alert alert-success">
                                    <!--<a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>-->
                                    <strong>New Updates Availabe!</strong> <a href="signal_schedules.php">Click here to view these updates.</a>
                                </div>
                            </div>

                            <div id="sig" class="col-sm-12" style="pointer-events: none">
                                <!-- TradingView Widget BEGIN -->
                                <?php $signal_object->UI_show_live_quotes();?>
                                <!-- TradingView Widget END -->
                            </div>

                            <div class="col-sm-12">
                                <div id="signals-grid" class="row">
                                    <!--////////////////////////////////////////////////-->
                                    <div id="signal_1" class="col-xs-12 col-sm-12 col-md-12 col-lg-12 col-xl-12 card main">
                                        <div class="thumbnail">
                                            <div class="caption">
                                                <div class="row">
                                                    <!--.....................................-->
                                                    <div id="signal_1_main" class="col-xs-12 col-sm-12 col-md-12 col-lg-12 col-xl-12">
                                                        <div class="row">
                                                            <div class="col-sm-4">
                                                                <div class="row">
                                                                    <div class="col-sm-4"><p style="font-size: xxx-large"><b class="text-danger"><i class="glyphicon glyphicon-arrow-down"></i></b>   <b class='text-danger'>SELL</b></p></div>
                                                                    <div class="col-sm-8">
                                                                        <p><b class="thumbnail-label pull-left"><span class="currency_pair" id="signal_1_currency_pair">USD/JPY (<span class="current_price" id="1">12.999 </span>)</b></p>
                                                                        <p>Active...</p>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <div class="col-sm-2"><div class="well text-center"><b>1.223</b><br/><b class="nowrap">ENTRY PRICE</b></div></div>
                                                            <div class="col-sm-2"><div class="well text-center"><span>1.990<br/><b class="nowrap">STOP LOSS</b></span></div></div>
                                                            <div class="col-sm-2"><div class="well text-center"><span>1.990<br/><b class="nowrap">TAKE PROFIT</b></span></div></div>
                                                            <div class="col-sm-2">
                                                                <a target="_blank" href="https://webtrader.instaforex.com/login" class="btn btn-sm btn-success btn-group-justified">Trade Now</a>
                                                                <br/>
                                                                <div class="row">
                                                                    <div class="col-sm-3"><a class="pull-left" href="javascript:void(0);"><i class="glyphicon glyphicon-star-empty"></i></a></div>
                                                                    <div class="col-sm-9"><a id="signal_1_trigger" onclick="signal.show_extra_analysis('signal_1')" class="pull-right" href="javascript:void(0);"><b>ANALYSIS <i class="glyphicon glyphicon-arrow-down"></i></b></a></div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <!--............................................-->
                                                    <!--............................................-->
                                                    <div id="signal_1_extra" style="display: none" class="col-xs-12 col-sm-12 col-md-12 col-lg-12 col-xl-12">
                                                        <div class="row">
                                                            <div  class="col-sm-5 col-xs-12">
                                                                <script> new SimpleBar(document.getElementById('signal_1_news')); </script>
                                                                <div id="signal_1_news" style="height: 300px; overflow-y: scroll;" data-simplebar data-simplebar-auto-hide="true" class="row">
                                                                    {$this->UI_get_news_for_page($row['symbol'])}
                                                                </div>
                                                            </div>
                                                            <div style="" class="col-sm-7 col-xs-12">
                                                                <section>
                                                                    <script type="text/javascript" src="https://s3.tradingview.com/tv.js"></script>
                                                                    <script type="text/javascript">
                                                                        new TradingView.widget({
                                                                            "width": "100%",
                                                                            "height": 300,
                                                                            "symbol": "FX:{$row['symbol']}",
                                                                            "interval": "5",
                                                                            "timezone": "UTC",
                                                                            "theme": "White",
                                                                            "style": "8",
                                                                            "toolbar_bg": "#f1f3f6",
                                                                            "hide_side_toolbar": false,
                                                                            "allow_symbol_change": true,
                                                                            "hideideas": true,
                                                                            "show_popup_button": true,
                                                                            "popup_width": "1000",
                                                                            "popup_height": "650"
                                                                        });
                                                                    </script>
                                                                </section>
                                                                <div>
                                                                    <small style="font-size: x-small">Your use of the signals means you have read and accepted our
                                                                        <a href="signal_terms_of_use.php" title="Forex Signal Terms of Use">terms of use</a>.
                                                                        Download the <a href="downloads/signalguide.pdf" target="_blank" title="Download signal guide">
                                                                            signal guide</a> to learn how to use the signals.
                                                                    </small>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <!--............................................-->
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <!--///////////////////////////////////////////////-->
                                </div>
                            </div>
                        </div>
                    </div>

                    <!--///////////////////////////////
                    Login Form Scripting-->
                    <?php if(!isset($_SESSION['signal_schedule_user']) || empty($_SESSION['signal_schedule_user'])){ ?>
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

    <script src="https://www.gstatic.com/firebasejs/5.3.0/firebase.js"></script>
    <!-- Firebase App is always required and must be first -->
    <script src="https://www.gstatic.com/firebasejs/5.3.0/firebase-app.js"></script>
    <!-- Add additional services that you want to use -->
    <script src="https://www.gstatic.com/firebasejs/5.3.0/firebase-messaging.js"></script>
    <script src="https://www.gstatic.com/firebasejs/5.3.0/firebase-functions.js"></script>
    <script>
        // Initialize Firebase
        var config = {
            apiKey: "AIzaSyCdT2R-aTP1V-MtQ3K8QTIGauSiijRr_6k",
            authDomain: "instafxng-signals-e6755.firebaseapp.com",
            databaseURL: "https://instafxng-signals-e6755.firebaseio.com",
            projectId: "instafxng-signals-e6755",
            storageBucket: "instafxng-signals-e6755.appspot.com",
            messagingSenderId: "179558919499"
        };
        firebase.initializeApp(config);
    </script>
    <script> signal.new_signal_listener(); </script>
    </body>
</html>