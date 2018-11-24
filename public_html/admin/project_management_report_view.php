<?php
require_once("../init/initialize_admin.php");
if (!$session_admin->is_logged_in()) {
    redirect_to("login.php");
}
if(isset($_POST['accept']))
{
    extract($_POST);
    $query = "UPDATE 
project_management_projects, 
project_management_reports 
SET 
project_management_projects.status = '2', 
project_management_projects.completion_stamp = now(), 
project_management_reports.status = 'APPROVED' 
WHERE project_management_projects.project_code = '$project_code' 
AND project_management_reports.report_code = '$report_code' ";
    $accept2 = $db_handle->runQuery($query);
    if($accept2){
        $message_success = "This report has been accepted, the task has been flagged as a completed task.";}
    else {
        $message_error = "The operation was not successful, please try again.";}
}
if(isset($_POST['decline']))
{
    extract($_POST);
    $query = "UPDATE project_management_reports SET status = 'DECLINED', comment = '$reasons' WHERE report_code = '$report_code' ";
    $decline = $db_handle->runQuery($query);
    if($decline)
    {
        $message_success = "This report has been declined, the reasons have been communicated to the report's author.";
    }
    else
    {
        $message_error = "The operation was not successful, please try again.";
    }
}
$get_params = allowed_get_params(['x']);
$report_code_encrypted = $get_params['x'];
$report_code = decrypt_ssl(str_replace(" ", "+", $report_code_encrypted));
$report_code = preg_replace("/[^A-Za-z0-9 ]/", '', $report_code);

$query = "SELECT PMP.title AS project_title, 
CONCAT(A.first_name, SPACE(1), A.last_name) AS author_name, 
PMP.deadline AS project_deadline, 
PMR.created AS report_submission_date, 
PMR.report AS report, 
PMR.report_code AS report_code, 
PMR.status AS report_status, 
PMP.project_code AS project_code
FROM project_management_projects AS PMP 
INNER JOIN project_management_reports AS PMR 
ON PMR.project_code = PMP.project_code 
INNER JOIN admin AS A 
ON A.admin_code = PMR.author_code
WHERE PMR.report_code = '$report_code'";
$result = $db_handle->runQuery($query);
$report_details = $db_handle->fetchAssoc($result)[0];




?>
<!DOCTYPE html>
<html lang="en">
    <head>
        <base target="_self">
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>Instaforex Nigeria | Admin - View Report</title>
        <meta name="title" content="Instaforex Nigeria | Admin - View Report" />
        <meta name="keywords" content="" />
        <meta name="description" content="" />
        <?php require_once 'layouts/head_meta.php'; ?>
        <script src="tinymce/tinymce.min.js"></script>
        <script>function print_report(divName) {
                var printContents = document.getElementById(divName).innerHTML;
                var originalContents = document.body.innerHTML;
                document.body.innerHTML = printContents;
                window.print();
                document.body.innerHTML = originalContents;
            }</script>
    </head>
    <body>
        <?php require_once 'layouts/header.php'; ?>
        <!-- Main Body: The is the main content area of the web site, contains a side bar  -->
        <div id="main-body" class="container-fluid">
            <div class="row no-gutter">
                <!-- Main Body - Content Area: This is the main content area, unique for each page  -->
                <!-- Main Body - Side Bar  -->
                <div id="main-body-side-bar" class="col-md-4 col-lg-3 left-nav">
                    <?php require_once 'layouts/sidebar.php'; ?>
                </div>
                <div id="main-body-content-area" class="col-md-8 col-lg-9">
                    <!-- Unique Page Content Starts Here
                    ================================================== -->
                    <div class="row">
                        <div class="col-sm-12 text-danger">
                            <h4><strong>VIEW PROJECT REPORT</strong></h4>
                        </div>
                    </div>
                    <div class="section-tint super-shadow">
                        <div class="row">
                            <div class="col-sm-12">
                                <?php require_once 'layouts/feedback_message.php'; ?>
                                <p><a href="project_management_project_view.php?x=<?php echo encrypt_ssl($report_details['project_code']) ?>" class="btn btn-default" title="Back"><i class="fa fa-arrow-circle-left"></i> Back</a></p>

                                    <p><b>PROJECT TITLE:</b> <?php echo $report_details['project_title']; ?></p>
                                    <p><b>AUTHOR:</b> <?php echo $report_details['author_name']; ?></p>
                                    <p><b>PROJECT DEADLINE: </b><?php echo datetime_to_text($report_details['project_deadline']); ?>           | <b>REPORT SUBMISSION DATE: </b><?php echo datetime_to_text($report_details['report_submission_date']); ?></p>
                                    <br/>
                                    <p class="text-justify"> <?php echo $report_details['report']; ?> </p>
                                    <br/>
                                    <?php
                                    if ($report_details['report_status'] == "APPROVED")
                                    {
                                        echo '<p class="text-center"><b>This report has been APPROVED.</b></p>';
                                    }
                                    if ($report_details['report_status'] == "DECLINED")
                                    {
                                        echo '<p class="text-center"><b>This report has been DECLINED.</b></p>';
                                    }
                                    if ($report_details['report_status'] == "PENDING")
                                    {
                                        echo '<form data-toggle="validator" class="form-horizontal" role="form" method="post" action="">';
                                        echo '<div class="row">';
                                        echo '<div class="col-sm-12">';
                                        echo '<input class="hidden" type="hidden" name="report_code" value="'.$report_details['report_code'].'"/>';
                                        echo '<input class="hidden" type="hidden" name="project_code" value="'.$report_details['project_code'].'"/>';
                                        echo '<button name="accept" type="submit" class="col-sm-3 btn btn-success" >Approve</button>';
                                        echo '<div class="col-sm-1">';
                                        echo '</div >';
                                        echo '<button type="button" data-toggle="modal" data-target="#decline_report" class="col-sm-3 btn btn-warning" >Decline</button>';
                                        echo '<div class="col-sm-1">';
                                        echo '</div >';
                                        echo '<button onClick="print_report('."'printout'".')" type="button" class="col-sm-3 btn btn-info" ><i class="glyphicon glyphicon-print"></i></button>';
                                        echo '</div >';
                                        echo '</div>';
                                        echo '</form>';
                                    }
                                    ?>
                                    <!-- Modal-- Edit Project -->
                                    <div id="decline_report" class="modal fade" role="dialog">
                                        <div class="modal-dialog">
                                            <!-- Modal content-->
                                            <form data-toggle="validator" class="form-horizontal" role="form" method="post" action="">
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                                                        <h4 class="modal-title">Decline Report</h4>
                                                    </div>
                                                    <div class="modal-body">
                                                        <div class="row">
                                                            <div class="col-sm-12">
                                                                <input name="project_code" type="hidden" value="<?php echo $report_details['project_code'] ?>"/>
                                                                <input name="report_code" type="hidden" value="<?php echo $report_code ?>"/>
                                                                <p><strong>Comments:</strong></p>
                                                                <textarea name="reasons" placeholder="Reasons for decline..." class="form-control" rows="5" required></textarea>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="modal-footer">
                                                        <button name="decline" type="submit" class="btn btn-success">Proceed</button>
                                                        <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
                                                    </div>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                            </div>
                        </div>
                    </div>
                    <!-- Unique Page Content Ends Here
                    ================================================== -->
                </div>
                <div id="main-body-content-area" class="col-lg-1">
                </div>
            </div>
        </div>

        <div id="printout" class="container-fluid" style="display: none">
            <div class="row no-gutter">
                <!-- Main Body - Content Area: This is the main content area, unique for each page  -->
                <div id="main-body-content-area" class="col-lg-1"></div>
                <div id="main-body-content-area" class="col-lg-10">
                    <!-- Unique Page Content Starts Here
                    ================================================== -->
                    <div class="section-tint super-shadow">
                        <div class="row">
                            <div class="col-sm-12">
                                <div id="main-logo" class=" col-sm-12 col-md-9">
                                    <a title="Home Page"><img src="../images/ifxlogo.png?v=1.1" alt="Instaforex Nigeria Logo" /></a>
                                </div>
                                <div class="row">
                                    <div class="col-sm-12 text-danger">
                                        <h4 class="text-center"><strong>PROJECT REPORT</strong></h4>
                                    </div>
                                </div>
                                    <p><b>PROJECT TITLE:</b> <?php echo $report_details['project_title']; ?></p>
                                    <p><b>AUTHOR:</b> <?php echo $report_details['author_name']; ?></p>
                                    <p><b>PROJECT DEADLINE:</b> <?php echo $report_details['project_deadline']; ?>  |  <b>SUBMISSION DATE:</b> <?php echo $report_details['report_submission_date']; ?></p>
                                    <br/>
                                    <br/>
                                    <p class="text-justify"> <?php echo $report_details['report'] ?> </p>
                                    <br/>
                                    <br/>
                                    <?php
                                    if ($report_details['report_status'] == "APPROVED") {
                                        echo '<p class="text-center"><b>This report has been APPROVED.</b></p>';
                                    }
                                    if ($report_details['report_status'] == "DECLINED") {
                                        echo '<p class="text-center"><b>This report has been DECLINED.</b></p>';
                                    }
                                ?>
                                <?php include 'layouts/footer.php'; ?>
                            </div>
                        </div>
                    </div>
                    <!-- Unique Page Content Ends Here
                    ================================================== -->
                </div>
                <div id="main-body-content-area" class="col-lg-1">
                </div>
            </div>
        </div>
        <?php include 'layouts/footer.php'; ?>
    </body>
</html>