<?php
require_once 'init/initialize_general.php';
$thisPage = "Events Calendar";
?>
<!DOCTYPE html>
<html lang="en">
    <head>
        <base target="_self">
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>Instaforex Nigeria | Forex Events Calendar - Instaforex Traders</title>
        <meta name="title" content="Instaforex Nigeria | Forex Events Calendar - Instaforex Traders" />
        <meta name="keywords" content="forex events, forex news calendar, forex trading, instaforex" />
        <meta name="description" content="Get updated about events that influences the forex market, stay tuned to the instaforex events calendar." />
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
                                <h4><strong>Events Calendar</strong></h4>
                            </div>
                        </div>
                        <!-- Instaforex.com --><script language="javascript" type="text/javascript">if (navigator.appName!="Microsoft Internet Explorer"){function receiveSize_1(e){el_str_data=e.data.split("_");document.getElementById(el_str_data[0]).style.height = el_str_data[1] + "px";}window.addEventListener("message", receiveSize_1, false);}</script><iframe class="autoHeight" id="calendar-iframe-7" name="calendar-iframe-7" src="https://informers.instaforex.com/calendar/informer_iframe/sz=100p&ss=10&c1=000000&c2=A30013&c3=C7C7C7&to_main=1&links_view=get&link=calendar&sf=v&pl=BBLR&ss=10&inst=1&i=7" frameborder="0" ALLOWTRANSPARENCY="true" width="100%" height="2800" scrolling="auto"  title="InstaForex is an universal Forex portal for traders"></iframe><noframes><a href="https://instaforex.com/">InstaForex broker</a></noframes><!-- Instaforex.com -->
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