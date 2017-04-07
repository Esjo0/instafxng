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
        <title>Instaforex Nigeria | Advance Traders Course</title>
        <meta name="title" content="Instaforex Nigeria | Advance Traders Course" />
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
                                <h1>The Forex Advance Course is designed for Amateur Forex Traders</h1>
                                <p>Learn different winning trading systems and device your own profitable trading system.</p>
                                <p>Master numerous trading systems and earn constantly from Forex</p>
                            </div>
                            <div class="col-sm-6">
                                <img src="images/advance-course-banner.jpg" alt="" class="img-responsive" />
                            </div>
                        </div>
                    </div>
                    
                    <div class="text-center" style="margin-bottom: 10px;"><a href="education_enrol.php?c=advance" title="Click here to enrol" class="btn btn-success btn-lg">Enrol for Advance Course</a></div>
                    
                    <div class="section-tint super-shadow">
                        <div class="row">
                            <div class="col-sm-12 text-danger">
                                <h4><strong>Advance Course Summary</strong></h4>
                            </div>
                        </div>
                        
                        <div class="row">
                            <div class="col-sm-12">
                                <p>Register for Advance Traders Course and get FREE gift of $100 education bonus to your trading account.</p>
                                <p>The Forex advance course is designed to take amateur Traders through different Trading Systems to creating their own
                                system and finally up to the point of profitable trading.</p>
                                <p>The cost of the Advanced Traders course is $100 (&#8358;<?php if(defined('IPLRFUNDRATE')) { echo number_format(100 * IPLRFUNDRATE); } ?> equivalent).</p>
                                <p>To qualify for the $100 Education Bonus*: You would have to fund your live trading account with $100 (&#8358;<?php if(defined('IPLRFUNDRATE')) { echo number_format(100 * IPLRFUNDRATE); } ?>), then 
                                the $100 education bonus would be added to the funding making $200. Hence the $100 Education Bonus* is a refund of
                                your $100 training fee.</p>
                                <p>This means that you pay &#8358;<?php if(defined('IPLRFUNDRATE')) { echo number_format(100 * IPLRFUNDRATE); } ?> as training fee + &#8358;<?php if(defined('IPLRFUNDRATE')) { echo number_format(100 * IPLRFUNDRATE); } ?> to fund your live trading account with $100 (&#8358;<?php if(defined('IPLRFUNDRATE')) { echo number_format(200 * IPLRFUNDRATE); } ?> total), 
                                Then we credit your live trading account with $100 + another $100 (education bonus) = $200 total.</p>
                                <p>In essence, you're paying nothing for the training.</p>
                                <p>*The $100 Education Bonus itself can only be used for trading and all profits made while trading can be withdrawn. The 
                                $100 Education Bonus itself cannot be withdrawn.</p>
                                <h5>Below is the course outline for Advance Course</h5>
                                <ul class="fa-ul">
                                    <li><i class="fa-li fa fa-check-square-o icon-tune"></i>Fibonacci Retracements</li>
                                    <li><i class="fa-li fa fa-check-square-o icon-tune"></i>Key Levels Concept</li>
                                    <li><i class="fa-li fa fa-check-square-o icon-tune"></i>US Dollar Index (USDX)</li>
                                    <li><i class="fa-li fa fa-check-square-o icon-tune"></i>Econometrics</li>
                                    <li><i class="fa-li fa fa-check-square-o icon-tune"></i>COT Reports (Futures Peek)</li>
                                    <li><i class="fa-li fa fa-check-square-o icon-tune"></i>Technical Indicators</li>
                                    <li><i class="fa-li fa fa-check-square-o icon-tune"></i>News Event Trading</li>
                                </ul>
                                <p>The FREE gift of education bonus is also applicable
                                for <a href="beginner_traders_course.php" target="_blank" title="Beginner Traders Course">
                                Beginner Course</a>.</p>
                                
                            </div>
                        </div>
                        
                        <div class="text-center" style="margin-bottom: 10px;"><a href="education_enrol.php?c=advance" title="Click here to enrol" class="btn btn-success btn-lg">Enrol for Advance Course</a></div>
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