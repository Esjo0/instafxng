<?php
require_once 'init/initialize_general.php';
$thisPage = "Campaigns";
?>
<!DOCTYPE html>
<html lang="en">
    <head>
        <base target="_self">
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>InstaFxNg | InstaFxNg Campaigns</title>
        <meta name="title" content="" />
        <meta name="keywords" content="instaforex, promotions of instaforex, gifts for forex traders, contest and promotions" />
        <meta name="description" content="" />
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
                            <div class="col-sm-12">
                                <h3 class="text-center"><strong>Campaigns from InstaFxNg</strong></h3>
                                <p>InstaFxNg offers great opportunities and rewards for Forex Traders in addition to the best trading conditions.
                                    We invite you to delight yourself by participating in any of our promotions and get a chance to be a winner.</p>
                            </div>
                        </div>
                    </div>

                    <div class="section-tint super-shadow">
                        <div class="row">
                            <div class="col-sm-12"><h5>InstaFxNg Point Based Loyalty Program and Rewards</h5></div>
                        </div>
                        <div class="row">
                            <div class="col-sm-4">
                                <a href="loyalty.php" title="click for full details" target="_blank"><img src="images\Loyalty_Points_Images\ilpr_landing_image.jpg" alt="" class="img-responsive" /></a>
                            </div>
                            <div class="col-sm-8">
                                <p>Make Up To $4, 200 and N1, 000, 000 Extra While You Take Your Normal Trades</p>
                                <p>Each funding transaction you make earn you points in our loyalty program, also
                                    every trade you execute earn you points in our loyalty program.</p>
                                <a class="btn btn-success" href="loyalty.php" title="click for full details" target="_blank">More Details</a>
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