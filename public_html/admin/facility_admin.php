<?php
require_once("../init/initialize_admin.php");
if (!$session_admin->is_logged_in()) {
    redirect_to("login.php");
}

$query9 = "SELECT * FROM facility_report WHERE status = '0'";
$preport = $db_handle->numRows($query9);

$all_admin_member = $admin_object->get_all_admin_member();
if(isset($_POST['ok'])){
    $id = $db_handle->sanitizePost($_POST['id']);
    $status = "Work Completed";
    $query = "UPDATE facility_work SET status = 'COMPLETED', pro = '100' WHERE id = '$id'";
    $result = $db_handle->runQuery($query);
    if($result) {
        $message_success = "You have successfully Completed this work";
    } else {
        $message_error = "Something went wrong. Please try again.";
    }
}

if(isset($_POST['decline'])){
    $id = $db_handle->sanitizePost($_POST['id']);
    $repo = $db_handle->sanitizePost($_POST['report']);
    $status = "DECLINED : ".$repo;
    $query = "UPDATE facility_work SET status = '$status', pro = '75' WHERE id = '$id'";
    $result = $db_handle->runQuery($query);
    if($result) {
        $message_success = "You have successfully Declined this work request";
    } else {
        $message_error = "Something went wrong. Please try again.";
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <base target="_self">
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Instaforex Nigeria | Admin</title>
    <meta name="title" content="Instaforex Nigeria | Admin" />
    <meta name="keywords" content="" />
    <meta name="description" content="" />
    <?php require_once 'layouts/head_meta.php'; ?>
    <?php require_once 'hr_attendance_system.php'; ?>
    <script src="//cdn.jsdelivr.net/alasql/0.3/alasql.min.js"></script>
    <script src="//cdnjs.cloudflare.com/ajax/libs/xlsx/0.7.12/xlsx.core.min.js"></script>
</head>
<body>
<?php require_once 'layouts/header.php'; ?>
<!-- Main Body: The is the main content area of the web site, contains a side bar  -->
<div id="main-body" class="container-fluid">
    <div class="row no-gutter">
        <!-- Main Body - Side Bar  -->
        <div id="main-body-side-bar" class="col-md-4 col-lg-3 left-nav">
            <?php require_once 'layouts/sidebar.php'; ?>
        </div>

        <!-- Main Body - Content Area: This is the main content area, unique for each page  -->
        <div id="main-body-content-area" class="col-md-8 col-lg-9">

            <!-- Unique Page Content Starts Here
            ================================================== -->
            <div class="row">
                <div class="col-sm-12 text-danger">
                    <h4><strong>FACILITY ADMIN</strong></h4>
                </div>
            </div>



            <div class="row">
                <div class="col-lg-12">
                    <?php require_once 'layouts/feedback_message.php'; ?>
                    <div class="section-tint super-shadow">
                        <div class="row">
                            <div class="col-sm-12">
                                <h5> <a href="facility_reports.php">
                                        <div class="panel-footer">
                                            <span class="pull-right">Cross check <?php echo $preport;?> pending Reports</span>
                                            <span class="pull-right"><i class="fa fa-arrow-circle-right"></i></span>
                                            <a href="facility_servicing.php"><button style="align:centre;" type="button"  class="btn btn-info pull-left"><strong>SERVICING/MAINTENANCE</strong></button></a>

                                            <div class="clearfix"></div>
                                        </div>
                                    </a></br>List of Assigned Work</h5>
                                            <table class="table table-responsive table-striped table-bordered table-hover">

                                                <thead>
                                                <th>Work ID</th>
                                                <th>Date</th>
                                                <th>Work Title</th>
                                                <th>Details</th>
                                                <th>Requested By</th>
                                                <th>Location</th>
                                                </thead>
                                                <tbody>
                                                <?php include'facility_work_view.php';?>
                                                </tbody>

                                            </table>



                                            <?php if(isset($equipments) && !empty($equipments)) { ?>
                                                <div class="tool-footer text-right">
                                                    <p class="pull-left">Showing <?php echo $prespagelow . " to " . $prespagehigh . " of " . $numrows; ?> entries</p>
                                                </div>
                                            <?php } ?>

                                        </div>
                                    </div>
                                </div>
                                <?php if(isset($equipments) && !empty($equipments)) { require_once 'layouts/pagination_links.php'; } ?>

                            </div>


                </div>
                </div>
            </div>

            <!-- Unique Page Content Ends Here
            ================================================== -->

        </div>

    </div>
<?php require_once 'layouts/footer.php'; ?>
<script src="//cdnjs.cloudflare.com/ajax/libs/moment.js/2.9.0/moment-with-locales.js"></script>
<script src="//cdn.rawgit.com/Eonasdan/bootstrap-datetimepicker/e8bddc60e73c1ec2475f827be36e1957af72e2ea/src/js/bootstrap-datetimepicker.js"></script>
</body>
</html>