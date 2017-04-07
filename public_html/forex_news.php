<?php
require_once 'init/initialize_general.php';
$thisPage = "Forex News";
?>
<!DOCTYPE html>
<html lang="en">
    <head>
        <base target="_self">
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>Instaforex Nigeria | Forex News - Forex Traders</title>
        <meta name="title" content="Instaforex Nigeria | Forex News - Forex Traders" />
        <meta name="keywords" content="forex news, forex news online, live forex news, instaforex news" />
        <meta name="description" content="Forex News, Live Forex News from International Award Winning Forex Broker - InstaForex" />
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
                        <h1>Forex News</h1>
                        <!-- InstaForex--><script language="javascript" type="text/javascript">if (navigator.appName!="Microsoft Internet Explorer"){function receiveSize(e){document.getElementById("mt5iframe").style.height = e.data + "px";}window.addEventListener("message", receiveSize, false);}</script><iframe id="mt5iframe" src="https://informers.instaforex.com/index.php/forex_news/informer/sz=900_800&cn=15&r=0&hc=0&s=InstaForex_news&sf=v&ss=10&sw=0&sd=0&to_main=0&si=0&sht=1&shp=1&tf=v&ts=11&tw=0&td=0&ti=1&c1=ffffff&c2=ff0000&c3=000000&c4=fa0000&i=1&c=0&pl=ABC" frameborder="0" width="900" height="800" scrolling="no" title="InstaForex is an universal Forex portal for traders"></iframe><noframes><a href="https://instaforex.com/">InstaForex broker</a></noframes><!-- InstaForex-->
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