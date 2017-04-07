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
        <title>Instaforex Nigeria | Private Forex Trading Course</title>
        <meta name="title" content="Instaforex Nigeria | Private Forex Trading Course" />
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
                                <h1>The Forex Private Course is uniquely tailored for people with busy schedules</h1>
                                <p>Learn Forex right in your home or office and at your own time. Learn and master forex trading at your own pace.</p>
                            </div>
                            <div class="col-sm-6">
                                <img src="images/private-course-banner.jpg" alt="" class="img-responsive" />
                            </div>
                        </div>
                    </div>
                    
                    <div class="section-tint super-shadow">
                        <div class="row">
                            <div class="col-sm-12 text-danger">
                                <h4><strong>Private Course Summary</strong></h4>
                            </div>
                        </div>
                        
                        <div class="row">
                            <div class="col-sm-12">
                                <p>Do you think your schedules does not fit into our forex trading courses 
                                (<a href="beginner_traders_course.php" target="_blank" title="Beginner Traders Course">Beginners</a>, 
                                <a href="advanced_traders_course.php" target="_blank" title="Advanced Traders Course">Advanced</a>, 
                                <a href="course.php" target="_blank" title="Forex Freedom Course">Freedom</a>, 
                                <a href="investor_course.php" target="_blank" title="Forex Investor Course">Investor</a>)? 
                                No need to worry anymore.</p>
                                <p>You can now register for private training and be trained right in your house or selected places of your choice.</p>
                                <p>After due registration, an instructor would be assigned to you, and an agreement would be reached on a schedule that
                                best fit you.</p>
                            </div>
                        </div>
                        
                        <div class="text-center" style="margin-bottom: 10px;"><a href="education_enrol.php?c=private" title="Click here to enrol" class="btn btn-success btn-lg">Enrol for Private Course</a></div>
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