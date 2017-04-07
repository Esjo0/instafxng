<?php
require_once 'init/initialize_general.php';
$thisPage = "Forex Market Analysis";
?>
<!DOCTYPE html>
<html lang="en">
    <head>
        <base target="_self">
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>Instaforex Nigeria | Forex Market Analysis</title>
        <meta name="title" content="Instaforex Nigeria | Forex Market Analysis" />
        <meta name="keywords" content="instaforex, forex market analysis, forex analysis, forex technical analysis, instaforex nigeria." />
        <meta name="description" content="InstaForex company forex market analysis. Expert forex analysis." />
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
                                <h4><strong>Forex Market Analysis</strong></h4>
                            </div>
                        </div>
                        <!-- InstaForex --><iframe src="https://informers.instaforex.com/forex_analysis/informer/sz=900_1200&cn=10&r=0&hc=1&s=Market_Analysis&sf=v&ss=12&sw=0&sd=0&si=0&sht=1&shp=1&tf=v&ts=12&tw=0&ti=0&td=1&tgi=1&f=1&sdc=1&c1=ffffff&c2=ff0000&c3=000000&c4=ff0000&i=1&to_main=1&pl=BBLR" frameborder="0" width="900" height="1200" scrolling="auto" title="InstaForex is an universal Forex portal for traders"></iframe><noframes><a href="https://instaforex.com/">InstaForex broker</a></noframes><!-- InstaForex -->
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