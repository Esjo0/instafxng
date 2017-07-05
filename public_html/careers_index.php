<?php
require_once 'init/initialize_general.php';
if (!$session_careers->is_logged_in()) {
    redirect_to("careers_login.php");
}

$get_params = allowed_get_params(['p']);
$page_requested = $get_params['p'];;

switch($page_requested) {
    case '': $careers_index_php = true; break;
    case 'bda': $careers_biodata_php = true; break;
    case 'ach': $careers_achievement_php = true; break;
    case 'edu': $careers_education_php = true; break;
    case 'ski': $careers_skill_php = true; break;
    case 'wex': $careers_work_experience_php = true; break;
    default: $careers_index_php = true;
}


?>
<!DOCTYPE html>
<html lang="en">
    <head>
        <base target="_self">
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>Instaforex Nigeria | Careers and Job Opportunity</title>
        <meta name="title" content="Instaforex Nigeria | Careers and Job Opportunity" />
        <meta name="keywords" content="" />
        <meta name="description" content="" />
        <?php require_once 'layouts/head_meta.php'; ?>
        <style>
            .container-fluid { max-width: 1020px !important; }
            hr { max-width: 1020px !important; }
            #footer { max-width: 1020px !important; margin: 0 auto; background: #ddd url(../../images/footerbg.png); padding: 5px 10px 10px 10px; }
        </style>
    </head>

    <body>
        <!-- Header Section: Logo and Live Chat  -->
        <header id="header">
            <div class="container-fluid no-gutter masthead">
                <div class="row">
                    <div id="main-logo" class="col-sm-12 col-md-5">
                        <a href="./" target="_blank"><img src="images/ifxlogo.png" alt="Instaforex Nigeria Logo" /></a>
                    </div>
                    <div id="top-nav" class="col-sm-12 col-md-7 text-right">
                    </div>
                </div>
            </div>
            <hr />
        </header>

        <!-- Main Body: The is the main content area of the web site, contains a side bar  -->
        <div id="main-body" class="container-fluid">
            <div class="row no-gutter">

                <!-- Main Body - Content Area: This is the main content area, unique for each page  -->
                <div id="main-body-content-area" class="col-md-12">

                    <!-- Unique Page Content Starts Here
                    ================================================== -->

                    <div class="super-shadow page-top-section">
                        <div class="row ">
                            <div class="col-sm-7">
                                <h2>Careers and Job Opportunity</h2>
                                <p>If you like scaling new heights and you have the drive for excellence, you
                                    will fit into our team.</p>
                                <p>For 7 years, we have pushed the boundaries of service delivery in the Forex
                                    Trading industry in Nigeria. Do you have what it takes to be on our team?</p>
                            </div>

                            <div class="col-sm-5">
                                <img src="images/instafxng-careers.jpg" alt="" class="img-responsive" />
                            </div>
                        </div>
                    </div>

                    <div class="section-tint super-shadow">
                        <div class="row">
                            <div class="col-sm-12">
                                <p class="text-center">
                                    <a href="careers_index.php">Home</a> &nbsp; | &nbsp;
                                    <a href="careers_index.php?p=bda">Bio-Data</a> &nbsp; | &nbsp;
                                    <a href="careers_index.php?p=edu">Education</a> &nbsp; | &nbsp;
                                    <a href="careers_index.php?p=wex">Work Experience</a> &nbsp; | &nbsp;
                                    <a href="careers_index.php?p=ski">Skills</a> &nbsp; | &nbsp;
                                    <a href="careers_index.php?p=ach">Achievement</a> &nbsp; | &nbsp;
                                    <a title="Logout of Careers" href="careers_logout.php">Logout</a>
                                </p>
                                <hr />


                                <?php
                                    if($careers_index_php) { include_once 'views/careers/careers_index.php'; }
                                    if($careers_biodata_php) { include_once 'views/careers/careers_biodata.php'; }
                                    if($careers_achievement_php) { include_once 'views/careers/careers_achievement.php'; }
                                    if($careers_education_php) { include_once 'views/careers/careers_education.php'; }
                                    if($careers_skill_php) { include_once 'views/careers/careers_skill.php'; }
                                    if($careers_work_experience_php) { include_once 'views/careers/careers_work_experience.php'; }
                                ?>

                            </div>
                        </div>
                    </div>

                    <!-- Unique Page Content Ends Here
                    ================================================== -->
                </div>
            </div>

        </div>
        <footer id="footer" class="super-shadow">
            <div class="container-fluid no-gutter">
                <div class="col-sm-12">
                    <div class="row">
                        <p class="text-center" style="font-size: 16px !important;">&copy; <?php echo date('Y'); ?>, All rights reserved. Instant Web-Net Technologies Limited (www.instafxng.com)</p>
                    </div>
                </div>
            </div>
        </footer>
    </body>
</html>