<?php
require_once 'init/initialize_general.php';
$thisPage = "Careers";

$get_params = allowed_get_params(['c']);
$job_code_encrypted = $get_params['c'];

$job_code = decrypt(str_replace(" ", "+", $job_code_encrypted));
$job_code = preg_replace("/[^A-Za-z0-9 ]/", '', $job_code);

if(!$obj_careers->is_valid_job_code($job_code)) {
    header('Location: careers.php');
    exit;
}

$job_detail = $obj_careers->get_job_by_code($job_code);

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
                <div class="row"><div class="col-sm-12"><p><a href="careers.php" class="btn btn-default" title="Job List"><i class="fa fa-arrow-circle-left"></i> Go Back - Job List</a></p></div></div>

                <div class="row">
                    <div class="col-sm-12">
                        <p class="text-center">You're applying for</p>
                        <h3 class="text-center"><?php echo $job_detail['title']; ?></h3>
                        <hr />
                    </div>
                </div>

                <div class="row">
                    <div class="col-sm-6 text-right" style="margin-bottom: 7px !important;">
                        <a title="Apply Now" class="btn btn-success btn-block" href="careers_register.php?c=<?php echo encrypt($job_detail['job_code']); ?>">New? Register Account</a>
                    </div>
                    <div class="col-sm-6 text-left" style="margin-bottom: 7px !important;">
                        <a title="Login" class="btn btn-success btn-block" href="careers_login.php">Existing? Login Account</a>
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