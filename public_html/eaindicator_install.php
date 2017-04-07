<?php
require_once 'init/initialize_general.php';
$thisPage = "EA Installation";
?>
<!DOCTYPE html>
<html lang="en">
    <head>
        <base target="_self">
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>Instaforex Nigeria | EA Indicators MT4 Installation</title>
        <meta name="title" content="Instaforex Nigeria | EA Indicators MT4 Installation" />
        <meta name="keywords" content="instaforex, how to trade forex, trade forex, instaforex nigeria, mt4 installation, new meta trader">
        <meta name="description" content="Instaforex Nigeria | EA Indicators MT4 Installation - step by step instruction on installation of EA in the new Meta Trader.">
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
                                <h4><strong>How To Install EA (Expert Advisors) and Custom Indicators on The 600 Build Version MT4</strong></h4>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-sm-12">
                                <div class="embed-responsive embed-responsive-16by9">
                                    <iframe class="embed-responsive-item" src="//www.youtube.com/embed/3t5bz065KvM?rel=0" frameborder="0" allowfullscreen></iframe>
                                </div>
                                <p>Following the upgrade of Metatrader 4 (MT4), some clients have encountered problems installing 
                                    their custom indicators and Expert Advisors (EA). The following steps would aid in successful 
                                    installation of your Indicators and EA.</p>
                                <p>Every custom indicator/EA has an MQ4 file; you need the MQ4 file for the installation to be successful.</p>
                                <ol>
                                    <li>Open your Platform.</li>
                                    <li>If you have an &quot;ex4&quot; file of the custom indicator skip to step 12, but if not proceed to step 3. Note: the &quot;ex4&quot; file is the compiled format of the custom indicator or Expert Advisor.</li>
                                    <li>On the top of the platform, Click on Meta Editor.</li>
                                    <li>When the file opens you double click on Indicators/ Experts.</li>
                                    <li>Among the different indicators/ EA that will be displayed, click on the custom indicator/EA you want to install.</li>
                                    <li>Go to file and save it in documents.</li>
                                    <li>Close the file .</li>
                                    <li>Go to your documents and click on the indicator/EA file (E.g zig zag mq4)</li>
                                    <li>When it opens, click on compile.</li>
                                    <li>Look down below to ensure that you have (&quot;0&quot; errors and &quot;0&quot;warnings) before you can move to the next step.</li>
                                    <li>Close the file and go to your documents to find the &quot;ex4&quot; file (compiled format).</li>
                                    <li>Copy &quot;ex4&quot; file from documents.</li>
                                    <li>Open your platforms, click on file, then open data folder, click on MQL4, click on indicators/ experts and paste your copied &quot;ex4&quot; file.</li>
                                    <li>Close the platform, and reopen it.</li>
                                    <li>Then enter custom indicators/ Experts and double click on the indicator/ EA.</li>
                                </ol>
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