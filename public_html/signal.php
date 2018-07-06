<?php
require_once 'init/initialize_general.php';
$thisPage = "Home";
?>
<!DOCTYPE html>
<html lang="en">
    <head>
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
        <script>
            $(document).ready(function() {setInterval(function(){signal.get_date('date');}, 1000);});
        </script>
        <script>
            $(document).ready(function() {setInterval(function(){signal.get_time('time');}, 1000);});
        </script>
        <script>
            signal.getQuotes('live');
            //$(document).ready(function() {setInterval(function(){signal.getQuotes('live');}, 60000);});
        </script>
        <script>
            function get () {
                $.post("signals_main_display.php",
                    function(data) {
                        $('#sign').html(data);
                    });
            }
            $(document).ready(function() {setInterval(function(){get();}, 60000);});
        </script>
    </head>
    <body>
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
                    <div class="text-center section-tint super-shadow">
                        <div class="row"><div class="col-sm-12"><h2>Forex Signals</h2><br /></div></div>
                        <div class="row">
                            <p class="col-sm-12 item super-shadow page-top-section" style="padding-top:5px;"><strong><marquee id="live" behavior="scroll" direction="left" scrollamount="2"></marquee></strong></p>
                            <div class="panel panel-default col-sm-4">
                                <div class="panel-body">View Previous Signals</div>
                            </div>
                            <div class="panel panel-default col-sm-4">
                                <div class="panel-body" id="date">Date</div>
                            </div>
                            <div class="panel panel-default col-sm-4">
                                <div class="panel-body" id="time">Time</div>
                            </div>
                            <div class="col-sm-12">
                                <div class="panel-group" id="sign">
                                    <div class="panel panel-success">
                                        <div class="panel-heading">
                                            <div class="row">
                                                <div class="col-sm-4"><strong>EURUSD</strong></div>
                                                <div class="col-sm-4">PRICE 1.23456</div>
                                                <div class="col-sm-4"><strong>BUY</strong></div>
                                            </div>
                                            <div class="pull-left"><i class="fa fa-spinner fa-spin"></i> Active</div>
                                        </div>
                                        <div class="row">
                                            <div class="col-sm-4">Take Profit 1.23456</div>
                                            <div class="col-sm-4">Stop Loss 1.23456</div>
                                        <span class="col-sm-2 pull-right panel-title"><a data-toggle="collapse" data-parent="#accordion" href="#collapse1">Read More...</a></h5></span>
                                        </div>

                                        <div id="collapse1" class="panel-collapse collapse">

                                            <div class="panel-body">
                                                <div class="col-sm-6">
                                                    <h5>SIGNAL ANALYSIS</h5>
                                                    <p style="border-radius: 50%;">
                                                        <span><strong><u>KEYNOTE</u></strong></span>
                                                    </p>
                                                </div>
                                                <div class="col-sm-6">
                                                    <section style="min-height: 300px;">
                                                        <script type="text/javascript" src="https://d33t3vvu2t2yu5.cloudfront.net/tv.js"></script>
                                                        <script type="text/javascript">
                                                            new TradingView.widget({
                                                                "width": "100%",
                                                                "height": 300,
                                                                "symbol": "FX:EURUSD",
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
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="panel panel-warning">
                                        <div class="panel-heading">
                                            <div class="row">
                                                <div class="col-sm-4"><strong>EURUSD</strong></div>
                                                <div class="col-sm-4">PRICE 1.23456</div>
                                                <div class="col-sm-4"><strong>BUY</strong></div>
                                            </div>
                                            <div class="pull-left"><i class="fa fa-circle-o-notch fa-spin"></i> Pending</div>
                                        </div>
                                        <div class="row">
                                            <div class="col-sm-4">Take Profit 1.23456</div>
                                            <div class="col-sm-4">Stop Loss 1.23456</div>
                                            <span class="col-sm-2 pull-right panel-title"><a data-toggle="collapse" data-parent="#accordion" href="#collapse2">Read More...</a></h5></span>
                                        </div>

                                        <div id="collapse2" class="panel-collapse collapse">

                                            <div class="panel-body">
                                                <div class="col-sm-6">
                                                    <h5>SIGNAL ANALYSIS</h5>
                                                    <p style="border-radius: 50%;">
                                                        <span><strong><u>KEYNOTE</u></strong></span>
                                                    </p>
                                                </div>
                                                <div class="col-sm-6">
                                                    <section style="min-height: 300px;">
                                                        <script type="text/javascript" src="https://d33t3vvu2t2yu5.cloudfront.net/tv.js"></script>
                                                        <script type="text/javascript">
                                                            new TradingView.widget({
                                                                "width": "100%",
                                                                "height": 300,
                                                                "symbol": "FX:EURUSD",
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
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="panel panel-danger">
                                        <div class="panel-heading">
                                            <div class="row">
                                                <div class="col-sm-4"><strong>EURUSD</strong></div>
                                                <div class="col-sm-4">PRICE 1.23456</div>
                                                <div class="col-sm-4"><strong>BUY</strong></div>
                                            </div>
                                            <div class="pull-left"><i class="fa fa-circle"></i> Closed</div>
                                        </div>
                                        <div class="row">
                                            <div class="col-sm-4">Take Profit 1.23456</div>
                                            <div class="col-sm-4">Stop Loss 1.23456</div>
                                            <span class="col-sm-2 pull-right panel-title"><a data-toggle="collapse" data-parent="#accordion" href="#collapse3">Read More...</a></h5></span>
                                        </div>

                                        <div id="collapse3" class="panel-collapse collapse">

                                            <div class="panel-body">
                                                <div class="col-sm-6">
                                                    <h5>SIGNAL ANALYSIS</h5>
                                                    <p style="border-radius: 50%;">
                                                        <span><strong><u>KEYNOTE</u></strong></span>
                                                    </p>
                                                </div>
                                                <div class="col-sm-6">
                                                    <section style="min-height: 300px;">
                                                        <script type="text/javascript" src="https://d33t3vvu2t2yu5.cloudfront.net/tv.js"></script>
                                                        <script type="text/javascript">
                                                            new TradingView.widget({
                                                                "width": "100%",
                                                                "height": 300,
                                                                "symbol": "FX:EURUSD",
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
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>


                    <!-- Unique Page Content Ends Here
                    ================================================== -->

                </div>
                <!-- Main Body - Side Bar  -->
                
            </div>
        </div>
        <?php require_once 'layouts/footer.php'; ?>
    </body>
</html>