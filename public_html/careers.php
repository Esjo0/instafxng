<?php
require_once 'init/initialize_general.php';
$thisPage = "Careers";

$query = "SELECT * FROM career_jobs WHERE status = '2'";
$result = $db_handle->runQuery($query);
$all_jobs = $db_handle->fetchAssoc($result);

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
                                <p>Check for open positions below and apply accordingly.</p>
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
                                                <?php echo $row['detail']; ?>
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
                <!-- Main Body - Side Bar  -->
                <div id="main-body-side-bar" class="col-md-4 col-md-pull-8 col-lg-3 col-lg-pull-9 left-nav">
                    <?php require_once 'layouts/sidebar.php'; ?>
                </div>
            </div>
        </div>
        <?php require_once 'layouts/footer.php'; ?>
    </body>
</html>