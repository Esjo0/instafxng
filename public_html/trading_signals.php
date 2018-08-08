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
        <script src="https://www.wpiece.com/js/webcomponents.min.js"></script>
        <link  rel="import" href="http://www.wpiece.com/p/10_26" />
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
                        <br>
                        <div class="row">
                            <div class="col-sm-12">
                                <!-- TradingView Widget BEGIN -->
                                <script type="text/javascript" src="https://d33t3vvu2t2yu5.cloudfront.net/tv.js"></script>
                                <script type="text/javascript">
                                    new TradingView.widget( {
                                        'width'               : 600,
                                        'height'              : 305,
                                        'symbol'              : 'FX:EURUSD',
                                        'interval'            : 'D',
                                        'timezone'            : 'Etc/UTC',
                                        'theme'               : 'White',
                                        'style'               : '1',
                                        'locale'              : 'en',
                                        'toolbar_bg'          : '#f1f3f6',
                                        'enable_publishing'   : false,
                                        'allow_symbol_change' : true,
                                        'hideideas'           : true,
                                        'show_popup_button'   : true,
                                        'popup_width'         : '1000',
                                        'popup_height'        : '650'
                                    } );
                                </script>
                            </div>
                        </div>
                        <br>
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