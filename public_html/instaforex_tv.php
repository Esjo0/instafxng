<?php
require_once 'init/initialize_general.php';
$thisPage = "Instaforex TV";
?>
<!DOCTYPE html>
<html lang="en">
    <head>
        <base target="_self">
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>Instaforex Nigeria | Forex TV - Forex Traders</title>
        <meta name="title" content="Instaforex Nigeria | Forex TV - Forex Traders" />
        <meta name="keywords" content="instaforex, forex tv, online forex tv" />
        <meta name="description" content="Forex TV - Get updated with happenings at Instaforex." />
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
                    
                    <div class="section-tint super-shadow">
                        <div class="row">
                            <div class="col-sm-12 text-danger">
                                <h4><strong>Instaforex TV</strong></h4>
                            </div>
                        </div>
                        <!-- InstaForex--><iframe src="https://informers.instaforex.com/instaforex_tv/run_new/100/&r=8&bg=990000&bg1=990000&mc=000000&mf=v&mca=FFFFFF&fc=FFFFFF&fs=12&ff=v&fp=center&i=1&x=BBLR" frameborder="0" width="900" height="467" scrolling="no" title="InstaForex is an universal Forex portal for traders"></iframe><noframes><a href="https://instaforex.com/">Forex broker</a></noframes><!-- InstaForex-->

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