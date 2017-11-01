<?php
require_once("../init/initialize_admin.php");
if (!$session_admin->is_logged_in()) {
    redirect_to("login.php");
}

$get_params = allowed_get_params(['x']);
$report_code_encrypted = $get_params['x'];
$report_code = decrypt(str_replace(" ", "+", $report_code_encrypted));
$report_code = preg_replace("/[^A-Za-z0-9 ]/", '', $report_code);

$query = "SELECT
          project_management_projects.title AS project_title,
          CONCAT(admin.first_name, SPACE(1), admin.last_name) AS author_name,
          project_management_projects.deadline AS project_deadline,
          project_management_reports.created AS report_submission_date,
          project_management_reports.report AS report,
          project_management_reports.report_code AS report_code,
          project_management_reports.status AS report_status,
          project_management_projects.project_code AS project_code
          FROM project_management_projects, admin, project_management_reports
          WHERE 
          project_management_reports.report_code = '$report_code'
           AND 
           admin.admin_code = project_management_reports.author_code  LIMIT 1
            ";
$result = $db_handle->runQuery($query);
$result = $db_handle->fetchAssoc($result);

if(isset($_POST['accept']))
{
    var_dump($_POST);
    extract($_POST);
    $query = "UPDATE project_management_reports SET status = 'APPROVED' WHERE report_code = '$report_code' ";
    $accept1 = $db_handle->runQuery($query);

    $query = "UPDATE project_management_projects SET status = 'COMPLETED', completion_stamp = now() WHERE project_code = '$project_code' LIMIT 1;";
    $accept2 = $db_handle->runQuery($query);
    if($accept1 && $accept2)
    {
        $message_success = "This report has been accepted, the task has been flagged as a completed task.";
    }
    else
    {
        $message_error = "The operation was not successful, please try again.";
    }
}

if(isset($_POST['decline']))
{
    extract($_POST);
    $query = "UPDATE project_management_reports SET status = 'DECLINED', comment = '$reasons' WHERE report_code = '$report_code' LIMIT 1;";
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
        <script>
            function print_report(divName)
            {
                var printContents = document.getElementById(divName).innerHTML;
                var originalContents = document.body.innerHTML;

                document.body.innerHTML = printContents;

                window.print();

                document.body.innerHTML = originalContents;
            }
        </script>
    </head>
    <body>
        <?php require_once 'layouts/header.php'; ?>
        <!-- Main Body: The is the main content area of the web site, contains a side bar  -->
        <div id="main-body" class="container-fluid">
            <div class="row no-gutter">
                <!-- Main Body - Content Area: This is the main content area, unique for each page  -->
                <div id="main-body-content-area" class="col-lg-1">
                </div>
                <div id="main-body-content-area" class="col-lg-10">
                    
                    <!-- Unique Page Content Starts Here
                    ================================================== -->
                                        
                    <div class="row">
                        <div class="col-sm-12 text-danger">
                            <h4 class="text-center"><strong>VIEW PROJECT REPORT</strong></h4>
                        </div>
                    </div>
                    
                    <div class="section-tint super-shadow">
                        <div class="row">
                            <div class="col-sm-12">
                                <?php require_once 'layouts/feedback_message.php'; ?>
                                <p><a onclick="window.history.back()" class="btn btn-default" title="Back"><i class="fa fa-arrow-circle-left"></i> Back</a></p>
                                <?php foreach ($result as $row) { ?>
                                    <p><b>PROJECT TITLE:</b> <?php echo $row['project_title']; ?></p>
                                    <p><b>AUTHOR:</b> <?php echo $row['author_name']; ?></p>
                                    <p>
                                        <b>PROJECT DEADLINE:</b>
                                        <?php echo datetime_to_text($row['project_deadline']); ?>           |
                                        <b>REPORT SUBMISSION DATE:</b>
                                        <?php echo datetime_to_text($row['report_submission_date']); ?>
                                    </p>
                                    <br/>
                                    <br/>
                                    <p class="text-justify"> <?php echo $row['report']; ?> </p>
                                    <br/>
                                    <br/>
                                    <?php
                                    if ($row['report_status'] == "APPROVED")
                                    {
                                        echo '<p class="text-center"><b>This report has been APPROVED.</b></p>';
                                    }
                                    if ($row['report_status'] == "DECLINED")
                                    {
                                        echo '<p class="text-center"><b>This report has been DECLINED.</b></p>';
                                    }
                                    if ($row['report_status'] == "PENDING")
                                    {
                                        echo '<form data-toggle="validator" class="form-horizontal" role="form" method="post" action="">';
                                        echo '<div class="row">';
                                        echo '<div class="col-sm-12">';
                                        echo '<input class="hidden" type="hidden" name="report_code" value="'.$row['report_code'].'"/>';
                                        echo '<input class="hidden" type="hidden" name="project_code" value="'.$row['project_code'].'"/>';
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
                                }?>
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
                                                                <input name="project_code" type="hidden" value="<?php echo $row['project_code'] ?>"/>

                                                                <p><strong>Comments:</strong></p>
                                                                <textarea name="reasons" placeholder="Reasons for decline..." class="form-control" rows="5" required></textarea>
                                                                <hr/>

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
                <div id="main-body-content-area" class="col-lg-1">
                </div>
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

                                <?php foreach ($result as $row) { ?>
                                    <p><b>PROJECT TITLE:</b> <?php echo $row['project_title']; ?></p>
                                    <p><b>AUTHOR:</b> <?php echo $row['author_name']; ?></p>
                                    <p><b>PROJECT DEADLINE:</b> <?php echo $row['project_deadline']; ?>           |           <b>SUBMISSION
                                            DATE:</b> <?php echo $row['report_submission_date']; ?></p>
                                    <br/>
                                    <br/>
                                    <p class="text-justify"> <?php echo $row['report'] ?> </p>
                                    <br/>
                                    <br/>
                                    <?php
                                    if ($row['report_status'] == "APPROVED") {
                                        echo '<p class="text-center"><b>This report has been APPROVED.</b></p>';
                                    }
                                    if ($row['report_status'] == "DECLINED") {
                                        echo '<p class="text-center"><b>This report has been DECLINED.</b></p>';
                                    }

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