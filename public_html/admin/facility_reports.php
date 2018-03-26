<?php
require_once("../init/initialize_admin.php");
if (!$session_admin->is_logged_in()) {
    redirect_to("login.php");
}

$all_admin_member = $admin_object->get_all_admin_member();
if (isset($_POST['cross'])) {
    $com = $_POST['report'];
    $id = $_POST['rep_id'];
    $query = "UPDATE facility_report SET status = '1' , a_comment = '$com' WHERE id = '$id' ";
    $result2 =$db_handle->runQuery($query);
    if($result2) {
        $message_success = "You have successfully added a new client to the system";
    } else {
        $message_error = "Something went wrong. Please try again.";
    }
}

if (isset($_POST['report'])) {

    foreach ($_POST as $key => $value) {
        $_POST[$key] = $db_handle->sanitizePost(trim($value));
    }
    extract($_POST);

        $query = "SELECT 
          facility_report.invent_id AS inventoryid,
          facility_report.report AS report, 
          facility_report.created AS date, 
          facility_report.a_comment AS adcom, 
          CONCAT(admin.first_name, SPACE(1), admin.last_name) AS name
          FROM admin, facility_report
          WHERE facility_report.admin = admin.admin_code
          AND STR_TO_DATE(facility_report.created, '%Y-%m-%d') BETWEEN '$from_date' AND '$to_date' 
          ORDER BY facility_report.created DESC ";

    $numrows = $db_handle->numRows($query);
    $rowsperpage = 100;

    $totalpages = ceil($numrows / $rowsperpage);
// get the current page or set a default
    if (isset($_GET['pg']) && is_numeric($_GET['pg'])) {
        $currentpage = (int)$_GET['pg'];
    } else {
        $currentpage = 1;
    }
    if ($currentpage > $totalpages) {
        $currentpage = $totalpages;
    }
    if ($currentpage < 1) {
        $currentpage = 1;
    }

    $prespagelow = $currentpage * $rowsperpage - $rowsperpage + 1;
    $prespagehigh = $currentpage * $rowsperpage;
    if ($prespagehigh > $numrows) {
        $prespagehigh = $numrows;
    }

    $offset = ($currentpage - 1) * $rowsperpage;
    $query .= 'LIMIT ' . $offset . ',' . $rowsperpage;
    $result = $db_handle->runQuery($query);
    $reports = $db_handle->fetchAssoc($result);
}

$query2 = "SELECT 
          facility_report.invent_id AS inventoryid,
          facility_report.id AS id,
          facility_report.report AS report, 
          facility_report.created AS date, 
          CONCAT(admin.first_name, SPACE(1), admin.last_name) AS name
          FROM admin, facility_report
          WHERE facility_report.admin = admin.admin_code AND facility_report.status = '0'
          ORDER BY facility_report.created DESC ";
$numrows = $db_handle->numRows($query2);
$rowsperpage = 20;
$totalpages = ceil($numrows / $rowsperpage);
if (isset($_GET['pg']) && is_numeric($_GET['pg'])) {    $currentpage = (int) $_GET['pg'];} else {    $currentpage = 1;}
if ($currentpage > $totalpages) { $currentpage = $totalpages; }
if ($currentpage < 1) { $currentpage = 1; }
$prespagelow = $currentpage * $rowsperpage - $rowsperpage + 1;
$prespagehigh = $currentpage * $rowsperpage;
if($prespagehigh > $numrows) { $prespagehigh = $numrows; }
$offset = ($currentpage - 1) * $rowsperpage;
$query2 .= 'LIMIT ' . $offset . ',' . $rowsperpage;
$result = $db_handle->runQuery($query2);
$rep = $db_handle->fetchAssoc($result);

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
    <script>
        function goBack() {
            window.history.back();
        }
    </script>
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
                    <h4><button onclick="goBack()"><i class="fa fa-mail-reply fa-fw"></i>back</button><strong>FACILITY EQUIPMENT REPORTS</strong></h4>
                </div>
            </div>



            <div class="row">
                <div class="col-lg-12">

                    <div class="section-tint super-shadow">
                        <div class="row">
                            <div class="col-sm-12">
                                <?php require_once 'layouts/feedback_message.php'; ?>

                                <p>Fetch requisition reports within a date range using the form below.</p>
                                <form data-toggle="validator" class="form-horizontal" role="form" method="post" action="<?php echo $_SERVER['REQUEST_URI']; ?>">
                                    <div class="form-group">
                                        <label class="control-label col-sm-3" for="from_date">From:</label>
                                        <div class="col-sm-9 col-lg-5">
                                            <div class="input-group date">
                                                <input name="from_date" type="text" class="form-control" id="datetimepicker" required>
                                                <span class="input-group-addon"><span class="glyphicon glyphicon-calendar"></span></span>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="control-label col-sm-3" for="to_date">To:</label>
                                        <div class="col-sm-9 col-lg-5">
                                            <div class="input-group date">
                                                <input name="to_date" type="text" class="form-control" id="datetimepicker2" required>
                                                <span class="input-group-addon"><span class="glyphicon glyphicon-calendar"></span></span>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <div class="col-sm-offset-3 col-sm-9">
                                            <input name="report" type="submit" class="btn btn-success" value="Search" />
                                        </div>
                                    </div>
                                    <script type="text/javascript">
                                        $(function () {
                                            $('#datetimepicker, #datetimepicker2').datetimepicker({
                                                format: 'YYYY-MM-DD'
                                            });
                                        });
                                    </script>
                                </form>

                                <hr>
                                <?php if((isset($from_date) && isset($to_date)) && isset($reports)):?>
                                    <div id="dvTable">
                                        <h5>Facility Reports between <strong><?php echo $from_date." and ".$to_date; ?> </strong></h5>
                                        <table  class="table table-responsive table-striped table-bordered table-hover">
                                            <thead>
                                            <tr>
                                                <th>Date</th>
                                                <th>Inventory ID</th>
                                                <th>User</th>
                                                <th>Report</th>
                                                <th>Admin Comment</th>
                                            </tr>
                                            </thead>
                                            <tbody>
                                            <?php
                                            foreach ($reports as $row)
                                            {
                                                ?>
                                                    <tr>
                                                        <td><?php echo datetime_to_text2($row['date']); ?></td>
                                                        <td><?php echo $row['inventoryid']; ?></td>
                                                        <td><?php echo $row['name']; ?></td>
                                                        <td><?php echo $row['report']?></td>
                                                        <td><?php echo $row['adcom']?></td>
                                                    </tr>
                                                    <?php
                                                }?>
                                            </tbody>
                                        </table>
                                    </div>
                                    <center>
                                        <button type="button" class="btn btn-sm btn-info" onclick="window.exportExcel()">Export table to Excel</button>
                                    </center>
                                <?php endif; ?>


                            </div>
                        </div>

                    </div>

                                <div class="section-tint super-shadow">
                                    <div class="row">
                                        <div class="col-sm-12">
                                            <h5>List of All Pending Reports </h5>
                                            <?php if(isset($rep)):?>
                                            <table  class="table table-responsive table-striped table-bordered table-hover">
                                                <thead>
                                                <tr>
                                                    <th>Date</th>
                                                    <th>Inventory ID</th>
                                                    <th>User</th>
                                                    <th>Report</th>
                                                    <th></th>
                                                </tr>
                                                </thead>
                                                <tbody>
                                                <?php
                                                foreach ($rep as $row)
                                                {
                                                    ?>
                                                    <tr>
                                                        <td><?php echo datetime_to_text2($row['date']); ?></td>
                                                        <td><?php echo $row['inventoryid']; ?></td>
                                                        <td><?php echo $row['report']?></td>
                                                        <td> <button type="button" data-target="#check<?php echo $row['inventoryid']; ?>" data-toggle="modal"
                                                            class="btn btn-success">Cross Check
                                                            </button>
                                                            <div id="check<?php echo $row['inventoryid']; ?>" tabindex="-1" role="dialog" aria-hidden="true" class="modal fade">
                                                            <div class="modal-dialog">
                                                                <div class="modal-content">
                                                                    <div class="modal-header">
                                                                        <button type="button" data-dismiss="modal" aria-hidden="true"
                                                                                class="close">&times;</button>
                                                                        <h4 class="modal-title">Cross Check Equipment</h4></div>
                                                                    <div class="modal-body"><p>Kindly type in your report</p>
                                                                        <form id="requisition_form" data-toggle="validator" class="form-vertical" role="form" method="post" action="">

                                                                            <div class="form-group">
                                                                                <label for="comment">Report Details:</label>
                                                                                <textarea name="report" class="form-control" rows="3" id="comment" required></textarea>
                                                                            </div></div>
                                                                    <div class="modal-footer">
                                                                        <input name="rep_id" type="text"  value="<?php echo $row['id']; ?>" hidden>
                                                                        <input name="cross" type="submit" class="btn btn-success" value="Submit">
                                                                        <button type="submit" name="close" onClick="window.close();" data-dismiss="modal" class="btn btn-danger">Close!</button>
                                                                    </div>
                                                                    </form>
                                                                </div>
                                                            </div>
                                        </div>
                                        </td>
                                                    </tr>
                                                    <?php
                                                }?>
                                                </tbody>
                                            </table>
                                            <?php if(isset($rep) && !empty($rep)) { ?>
                                            <div class="tool-footer text-right">
                                                <p class="pull-left">Showing <?php echo $prespagelow . " to " . $prespagehigh . " of " . $numrows; ?> entries</p>
                                            </div>
                                            <?php } ?>
                                            <?php endif; ?>
                                        </div>
                                    </div>
                                </div>


                                <?php if(isset($rep) && !empty($rep)) { require_once 'layouts/pagination_links.php'; } ?>

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