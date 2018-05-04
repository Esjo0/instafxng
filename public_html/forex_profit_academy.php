<?php
require_once 'init/initialize_general.php';
$thisPage = "Education";
?>
<!DOCTYPE html>
<html lang="en">
    <head>
        <base target="_self">
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>Instaforex Nigeria | Forex Profit Academy</title>
        <meta name="title" content="Instaforex Nigeria | Forex Profit Academy" />
        <meta name="keywords" content="instaforex, traders course, trading courses, how to day trade, day trading, trading classes, trading training, option trading" />
        <meta name="description" content="Boost your trading ability, register for a forex trading course." />
        <?php require_once 'layouts/head_meta.php'; ?>
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
                    <div class="super-shadow page-top-section">
                        <div class="row ">
                            <div class="col-sm-6">
                                <h1 class="text-center">Welcome to the  Forex Profit Academy</h1>
                                <p>Forex education is the first step to making steady income trading Forex... <!--<a href="/fxacademy/" title="Click here to enrol" class="btn btn-sm btn-success btn-rounded">Join the Forex Profit Academy</a>--></p>
                                <div class="text-center" style="margin-bottom: 10px;">
                                    <a href="/fxacademy/" target="_blank" title="Click here to enrol" class="btn btn-success btn-sm btn-rounded">Join the Forex Profit Academy</a>
                                </div>
                            </div>
                            <div class="col-sm-6">
                                <img src="images/train.png" alt="" class="img-responsive img-thumbnail" />
                            </div>
                        </div>
                    </div>
                    <div class="section-tint super-shadow">
                        <div class="row">
                            <div class="col-sm-12 text-danger">
                                <h4><strong>Forex Profit Academy</strong></h4>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-sm-12">
                                <p>Gaining adequate knowledge of the Forex market is key to making steady income from Forex trading.</p>
                                <p>It’s very important that you take the free training as you would gain the understanding of the basics of the Forex market.</p>
                                <p>The <b>Forex Profit Academy</b> is a beginner’s guide to Forex Trading which is made up of very well explained lessons including a practical trading class. </p>
                                <p>I almost forgot! The training is set using one of the best learning tools available at the moment.</p>
                                <p>You also get to interact with a personal instructor assigned to you in case you have any question or need more clarity on any of the lessons taken in the academy. </p>
                                <p>By the end of the course, you would have learnt how to trade Forex profitably and make money.</p>
                                <p>Be sure to give all the lessons in the academy your 100% attention and thoroughly go through each lesson without forgetting to attempt all the test exercises which you must complete to proceed to the next lesson. </p>
                            </div>
                        </div>
                        <div class="text-center" style="margin-bottom: 10px;"><a href="/fxacademy/" target="_blank" title="Click here to enrol" class="btn btn-success btn-lg">Join the Forex Profit Academy</a></div>
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