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

    </head>
    <body>
        <?php require_once 'layouts/header.php'; ?>
        <!-- Main Body: The is the main content area of the web site, contains a side bar  -->
        <div id="main-body" class="container-fluid">
            <div class="row no-gutter">
                <?php require_once 'layouts/topnav.php'; ?>

                <!-- Main Body - Content Area: This is the main content area, unique for each page  -->
                <div id="main-body-content-area" class="col-md-8 col-md-push-4 col-lg-9 col-lg-push-3">
                    <!-- Unique Page Content Starts Here
                    ================================================== -->
                    <div  class="item super-shadow page-top-section">
                        <?php include "views/general_pages/advert_div.html"; ?>
                    </div>
                    <div id="welcome-note" class="section-tint super-shadow">
                        <h2 class="text-center">Welcome to Instaforex Nigeria</h2>
                        <p><i class="fa fa-quote-left fa-2x fa-pull-left fa-border"></i> InstaForex, the award winning and international forex broker seeks to serve her Nigerian clients better by the introduction of an InstaForex Nigeria Representative office. InstaFxNg.com is operated by Instant Web-Net Technologies Limited as a Nigerian InstaForex Representative / Introducing Broker Partner.</p>
                        <p>InstaForex has over the years introduced many client oriented services for clients to fully maximize the many income opportunities in the forex market.
                            Today, InstaForex services are of a great interest for more than 2 million Forex traders all over the world, among them are beginners as well as professionals of Forex currency trading. <a href="live_account.php" title="Open a live account" >Open an account</a> to get access to Forex trading operations, CFD for NYSE shares and also to futures deals of Forex and commodity markets.
                        </p>
                    </div>
                    
                    <div class="text-center section-tint super-shadow">
                        <!--<p>Enrol for any course and get up to $100 Education Bonus</p>-->
                        <br>
                        <div class="row">
                            <div class="col-sm-6">
                                <h2>Forex Profit Academy</h2>
                                <b class="text-justify">All successful traders once started as newbies and studied trading from
                                    scratch gradually discovering its basics and gaining experience.</b>
                                <p>This course is designed to take a beginner by the hand, step by step to become a professional and profitable trader.</p>
                                <div class="text-center" style="margin-bottom: 10px;"><a href="forex_profit_academy.php" title="Free Profit Academy" class="btn btn-default">Learn More</a></div>
                            </div>
                            <div class="col-sm-6">
                                <img src="images/train.png" alt="" class="img-responsive img-thumbnail" />
                            </div>
                        </div>
                        <br>
                    </div>

                    <div class="text-center section-tint super-shadow">
                        <div class="row"><div class="col-sm-12"><h2>Forex Informer</h2><br /></div></div>
                        <div class="row">
                            <div class="col-sm-6">
                                <div class="row">
                                    <div class="col-sm-12">
                                        <div class="embed-responsive embed-responsive-4by3">
                                            <iframe src="https://informers.instaforex.com/position_traders/run/ticker=EURUSD~GBPUSD~USDJPY~USDCAD~EURJPY~EURGBP~GBPJPY&w=320&t=30&ch=undefined&l=1&r=0&c1=968686&c2=FFFFFF&c2f=h&c2s=12&c3=302e30&c4=FF0000&c5=000000&c5f=h&c5s=12&c6=000000&c6f=h&c6s=11&x=BBLR&i=1&style=1_0_1&type=0" frameborder="0" width="320" height="319" scrolling="no" title="InstaForex is a universal Forex portal for traders"></iframe>
                                        </div>
                                        <br />
                                    </div>
                                </div>
                            </div>
                            <div class="col-sm-6">
                                <div class="row">
                                    <div class="col-sm-12">
                                        <div class="embed-responsive embed-responsive-4by3">
                                            <iframe src="https://informers.instaforex.com/calendar/run/w=320&count=7&tz=Europe~London&bg=fefefe_e2e2e2_666666_0_0_0_1_c2bebe_FFFFFF_&br=8_8_8_8&bgt=&stars=FFD700&font=11_000000_h_1_1_3_FFFFFF&full=1&i=1&high=0&part_code=x-BBLR&type=0" frameborder="0" width="100%" height="316" scrolling="no" title="InstaForex is an universal Forex portal for traders"></iframe>
                                        </div>
                                        <br />
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>


                    <!-- Unique Page Content Ends Here
                    ================================================== -->

                </div>
                <!-- Main Body - Side Bar  -->
                <div id="main-body-side-bar" class="col-md-4 col-md-pull-8 col-lg-3 col-lg-pull-9 left-nav">
                <?php require_once 'layouts/sidebar.php'; ?>
                </div>
                
            </div>
        </div>
        <?php require_once 'layouts/footer.php'; ?>
    </body>
</html>