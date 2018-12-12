<?php
require_once("../init/initialize_admin.php");
if (!$session_admin->is_logged_in()) {
    redirect_to("login.php");
}

$admin_code = $_SESSION['admin_unique_code'];

$query = "SELECT 
          project_management_projects.title AS project_title, 
          project_management_projects.deadline AS project_deadline, 
          project_management_reports.created AS report_submission_date, 
          CONCAT(admin.first_name, SPACE(1), admin.last_name) AS author_name,
          project_management_reports.report_code AS report_code
          FROM admin, project_management_projects, project_management_reports 
          WHERE project_management_reports.supervisor_code = '$admin_code'
          AND project_management_reports.status = 'PENDING'
          AND project_management_reports.author_code = admin.admin_code 
          AND project_management_reports.project_code = project_management_projects.project_code 
          ORDER BY project_management_reports.created DESC ";

$numrows = $db_handle->numRows($query);

$rowsperpage = 20;

$totalpages = ceil($numrows / $rowsperpage);
// get the current page or set a default
if (isset($_GET['pg']) && is_numeric($_GET['pg'])) {
    $currentpage = (int) $_GET['pg'];
} else {
    $currentpage = 1;
}
if ($currentpage > $totalpages) { $currentpage = $totalpages; }
if ($currentpage < 1) { $currentpage = 1; }

$prespagelow = $currentpage * $rowsperpage - $rowsperpage + 1;
$prespagehigh = $currentpage * $rowsperpage;
if($prespagehigh > $numrows) { $prespagehigh = $numrows; }

$offset = ($currentpage - 1) * $rowsperpage;
$query .= 'LIMIT ' . $offset . ',' . $rowsperpage;

$result = $db_handle->runQuery($query);
$projects = $db_handle->fetchAssoc($result);

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <base target="_self">
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Instaforex Nigeria | Admin - Project Management</title>
    <meta name="title" content="Instaforex Nigeria | Admin - Project Management" />
    <meta name="keywords" content="" />
    <meta name="description" content="" />
    <?php require_once 'layouts/head_meta.php'; ?>
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
                    <h4><strong>CONFIRMATION REQUESTS</strong></h4>
                </div>
            </div>
            <div class="section-tint super-shadow">
                <div class="row">
                    <div class="col-sm-12">
                        <?php require_once 'layouts/feedback_message.php'; ?>
                    </div>
                    <div class="col-sm-12">
                    </div>
                    <div class="col-sm-12">
                        <table class="table table-striped table-bordered table-hover">
                            <thead>
                            <tr>
                                <th>Project Title</th>
                                <th>Project Deadline</th>
                                <th>Report Submission Date</th>
                                <th>Author</th>
                                <th></th>
                            </tr>
                            </thead>
                            <tbody>

                            <?php if(isset($projects) && !empty($projects))
                            {
                                foreach ($projects as $row)
                                { ?>
                                    <tr>
                                        <td><?php echo $row['project_title']; ?></td>
                                        <td><?php echo $row['project_deadline']; ?></td>
                                        <td><?php echo $row['report_submission_date']; ?></td>
                                        <td><?php echo $row['author_name']; ?></td>
                                        <td>
                                            <a href="project_management_report_view.php?x=<?php echo dec_enc('encrypt', $row['report_code']); ?>"><button class="btn btn-success"><i class="glyphicon glyphicon-eye-open"></i></button></a>
                                        </td>
                                    </tr>
                                    <?php
                                }
                            }
                            else
                            { echo "<em>No results to display...</em>"; } ?>

                            </tbody>
                        </table>
                        <?php if(isset($projects) && !empty($projects)) { ?>
                            <div class="tool-footer text-right">
                                <p class="pull-left">Showing <?php echo $prespagelow . " to " . $prespagehigh . " of " . $numrows; ?> entries</p>
                            </div>
                        <?php } ?>
                    </div>
                </div>
                <?php if(isset($projects) && !empty($projects)) { require_once 'layouts/pagination_links.php'; } ?>
            </div>
            <!-- Unique Page Content Ends Here
            ================================================== -->
        </div>
    </div>
</div>
<?php require_once 'layouts/footer.php'; ?>
</body>
</html>
<script src="https://code.jquery.com/jquery-1.12.3.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/0.9.0rc1/jspdf.min.js"></script>