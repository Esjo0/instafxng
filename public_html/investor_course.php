<?php
require_once 'init/initialize_general.php';
$thisPage = "Education";
?>
<?php $thisPage = ""; ?>
<!DOCTYPE html>
<html lang="en">
    <head>
        <base target="_self">
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>Instaforex Nigeria | Investor Course</title>
        <meta name="title" content="Instaforex Nigeria | Investor Course" />
        <meta name="keywords" content="instaforex, forex investment, forex investing, forex investments, forex course, instaforex nigeria." />
        <meta name="description" content="Learn how to invest in the forex market, enroll for the InstaForex Nigeria forex investor's course" />
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
                                <h1>The Forex Investor Course is designed for busy people who wants to share the forex market profits</h1>
                                <p>Position yourself to make profits by learning how to invest in the Instaforex PAMM system and ForexCopy system.</p>
                            </div>
                            <div class="col-sm-6">
                                <img src="images/investor-course-banner.jpg" alt="" class="img-responsive" />
                            </div>
                        </div>
                    </div>
                    
                    <div class="text-center" style="margin-bottom: 10px;"><a href="education_enrol.php?c=investor" title="Click here to enrol" class="btn btn-success btn-lg">Enrol for Investor Course</a></div>
                    
                    <div class="section-tint super-shadow">
                        <div class="row">
                            <div class="col-sm-12 text-danger">
                                <h4><strong>Investor Course Summary</strong></h4>
                            </div>
                        </div>
                        
                        <div class="row">
                            <div class="col-sm-12">
                                <p>Are you a busy executive, a busy business owner, a civil servant that doesn't have the time to trade but wants a part of forex profit?</p>
                                <p>We invite you to position yourself to take your share of the profit taken daily from the $4 trillion a day Forex Market by attending our 
                                Forex Investor's Course.</p>
                                <p>This course is designed for Investors who do not wish to trade but 
                                wish to make profit by investing in the trading activity of expert traders.</p>
                                <p><strong>For Who:</strong> People who for whatever reasons are unable to trade but still want to be part of the forex market. 
                                (bankers, workers, investors and everyone else).</p>
                                <p>90 spaces are available each class (one class per month)</p>
                                <p><strong>Venue:</strong> Block 1A Plot 8 Diamond Estate, Estate Bus Stop, Lasu / Isheri Road, Isheri Olofin Lagos.</p>
                                <p><strong>Cost:</strong> &#8358;27, 000 (Twenty Seven Thousand Naira Only)</p>
                                
                            </div>
                        </div>
                        <div class="text-center" style="margin-bottom: 10px;"><a href="education_enrol.php?c=investor" title="Click here to enrol" class="btn btn-success btn-lg">Enrol for Investor Course</a></div>
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