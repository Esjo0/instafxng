<?php
require_once 'init/initialize_general.php';
$thisPage = "Careers";

$all_jobs = $obj_careers->get_open_jobs();

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
                            <p>Check for open positions below and apply accordingly. Please note that you can only apply for one
                            position, multiple applications for multiple positions is not allowed. Kindly read all instructions, it will
                                take just a few minutes to complete the process.</p>
                            <p>If you started an application process, <a title="Login" class="btn btn-success" href="careers_login.php">Login Here</a> to continue.</p>
                            <p>If you encounter any challenge, please send an email to careers@instafxng.com, we will be glad to assist you.</p>
                            <hr />
                        </div>

                        <div class="col-sm-12">
                            <div class="panel-group" id="accordion">

                                <?php $count = 1; if(isset($all_jobs) && !empty($all_jobs)) { foreach ($all_jobs as $row) { ?>

                                    <div class="panel panel-default">
                                        <div class="panel-heading">
                                            <h5 class="panel-title"><a data-toggle="collapse" data-parent="#accordion" href="#collapse<?php echo $count; ?>"><?php echo $row['title']; ?> - Code: <?php echo $row['job_code']; ?></a></h5>
                                        </div>
                                        <div id="collapse<?php echo $count; ?>" class="panel-collapse collapse">
                                            <div class="panel-body">
                                                <p class="text-right"><a title="Apply Now" class="btn btn-success btn-bg" href="careers_apply.php?c=<?php echo encrypt($row['job_code']); ?>"><i class="fa fa-paper-plane-o icon-white"></i> Apply Now</a></p>
                                                <?php echo $row['detail']; ?>
                                                <p class="text-right"><a title="Apply Now" class="btn btn-success btn-bg" href="careers_apply.php?c=<?php echo encrypt($row['job_code']); ?>"><i class="fa fa-paper-plane-o icon-white"></i> Apply Now</a></p>
                                            </div>
                                        </div>
                                    </div>

                                    <?php $count++; } } else { echo "<span class='text-danger'><em><hr />There is currently no job listed.</em></span>"; } ?>

                            </div>

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