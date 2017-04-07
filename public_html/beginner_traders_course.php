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
        <title>Instaforex Nigeria | Beginner Traders Course</title>
        <meta name="title" content="Instaforex Nigeria | Beginner Traders Course" />
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
                                <h1>The Forex Beginner Course is designed for newcomers and Forex beginners</h1>
                                <p>You will learn the basics through demo-trading up to the point of profitable live trading.</p>
                            </div>
                            <div class="col-sm-6">
                                <img src="images/beginner-course-banner.jpg" alt="" class="img-responsive" />
                            </div>
                        </div>
                    </div>
                    
                    <div class="text-center" style="margin-bottom: 10px;"><a href="education_enrol.php?c=beginner" title="Click here to enrol" class="btn btn-success btn-lg">Enrol for Beginner Course</a></div>
                    
                    <div class="section-tint super-shadow">
                        <div class="row">
                            <div class="col-sm-12 text-danger">
                                <h4><strong>Beginner Course Summary</strong></h4>
                            </div>
                        </div>
                        
                        <div class="row">
                            <div class="col-sm-12">
                                <p>Register for Beginners Traders Course and get FREE gift of $50 education bonus to your trading account.</p>

                                <p>The Forex beginner's course is designed to take newcomers and Forex beginners from the basics through demo-trading up to the point of
                                profitable live trading.</p>

                                <p>The cost of the Beginners Traders course is $50 (&#8358;<?php if(defined('IPLRFUNDRATE')) { echo number_format(50 * IPLRFUNDRATE); } ?> equivalent).</p>

                                <p>To qualify for the $50 Education Bonus*: You would have to fund your live trading account with $50 (&#8358;<?php if(defined('IPLRFUNDRATE')) { echo number_format(50 * IPLRFUNDRATE); }?>), then the $50 education
                                bonus would be added to the funding making $100. Hence the $50 Education Bonus* is a refund of your $50 training fee.</p>

                                <p>This means that you pay &#8358;<?php if(defined('IPLRFUNDRATE')) { echo number_format(50 * IPLRFUNDRATE); } ?> as training fee + &#8358;<?php if(defined('IPLRFUNDRATE')) { echo number_format(50 * IPLRFUNDRATE); } ?> to fund your live trading account with $50 (&#8358;<?php if(defined('IPLRFUNDRATE')) { echo number_format(100 * IPLRFUNDRATE); } ?> total), Then we credit your 
                                live trading account with $50 + another $50 (education bonus) = $100 total.</p>

                                <p>In essence, you're paying nothing for the training.</p>

                                <p>*The $50 Education Bonus itself can only be used for trading and all profits made while trading can be withdrawn. The $50 Education Bonus
                                itself cannot be withdrawn.</p>
                                
                                <h5>Below is the course outline for Beginner Course</h5>
                                <ul class="fa-ul">
                                    <li><i class="fa-li fa fa-check-square-o icon-tune"></i>Introduction to online trading</li>
                                    <li><i class="fa-li fa fa-check-square-o icon-tune"></i>Legal issues of online Trading in Nigeria</li>
                                    <li><i class="fa-li fa fa-check-square-o icon-tune"></i>The trading Platform: Metatrader 4</li>
                                    <li><i class="fa-li fa fa-check-square-o icon-tune"></i>The Forex Market (What, Why, Where, & When)</li>
                                    <li><i class="fa-li fa fa-check-square-o icon-tune"></i>What is Fundamental Analysis?</li>
                                    <li><i class="fa-li fa fa-check-square-o icon-tune"></i>What is Technical Analysis?</li>
                                    <li><i class="fa-li fa fa-check-square-o icon-tune"></i>The 3 basic trading style / trader type</li>
                                    <li><i class="fa-li fa fa-check-square-o icon-tune"></i>Pips, Leverage, Margins, Lotsize, TP and SL</li>
                                    <li><i class="fa-li fa fa-check-square-o icon-tune"></i>Placement & exiting an order</li>
                                    <li><i class="fa-li fa fa-check-square-o icon-tune"></i>Types of order & indicators</li>
                                    <li><i class="fa-li fa fa-check-square-o icon-tune"></i>Support, Resistance & pivots</li>
                                    <li><i class="fa-li fa fa-check-square-o icon-tune"></i>Basic Money Management</li>
                                    <li><i class="fa-li fa fa-check-square-o icon-tune"></i>Traders psychology - Beginners</li>
                                    <li><i class="fa-li fa fa-check-square-o icon-tune"></i>Demo trading with MT4</li>
                                </ul>
                                <p>The FREE gift of education bonus is also applicable for <a href="advanced_traders_course.php" target="_blank" title="Advance Traders Course">Advance Course</a>.</p>
                                
                            </div>
                        </div>
                        <div class="text-center" style="margin-bottom: 10px;"><a href="education_enrol.php?c=beginner" title="Click here to enrol" class="btn btn-success btn-lg">Enrol for Beginner Course</a></div>
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