<?php
require_once("../init/initialize_admin.php");
if (!$session_admin->is_logged_in()) {
    redirect_to("login.php");
}
$admin_code = $_SESSION['admin_unique_code'];
$all_admin_member = $admin_object->get_all_admin_member();
if(isset($_POST['ok'])){
    $inventid = $db_handle->sanitizePost($_POST['id']);
    $repo = "Equipment is In Good condition";
    $status = 0;
    $query = "INSERT into facility_report(invent_id,report,admin,status) VALUES('$inventid','$repo','$admin_code','$status')";
    $result = $db_handle->runQuery($query);
    if($result) {
        $message_success = "You have successfully Submitted your report";
    } else {
        $message_error = "Something went wrong. Please try again.";
    }
}

if(isset($_POST['process'])){
    $inventid = $db_handle->sanitizePost($_POST['id']);
    $repo = $db_handle->sanitizePost($_POST['report']);
    $status = 0;
    $query = "INSERT into facility_report(invent_id,report,admin,status) VALUES('$inventid','$repo','$admin_code','$status')";
    $result = $db_handle->runQuery($query);
    if($result) {
        $message_success = "You have successfully Submitted your report";
    } else {
        $message_error = "Something went wrong. Please try again.";
    }
}

if(isset($_POST['work'])){
    $admin = $db_handle->sanitizePost($_POST['admin']);
    $details = $db_handle->sanitizePost($_POST['details']);
    $title = $db_handle->sanitizePost($_POST['subject']);
    $location = $db_handle->sanitizePost($_POST['location']);
    $prior = $db_handle->sanitizePost($_POST['priority']);
    $status = "pending" ;
    $query = "INSERT into facility_work(created_by,title,location,details,priority,status,pro) VALUES('$admin','$title','$location','$details','$prior','$status','10')";
    var_dump($query);
    $result = $db_handle->runQuery($query);
    if($result) {
        $message_success = "You have successfully Submitted Your work Request";
    } else {
        $message_error = "Something went wrong. Please try again.";
    }
}

$query2 = "SELECT
facility_work.id AS workid,
facility_work.title AS title,
facility_work.pro AS pro,
facility_work.status AS status
          FROM facility_work
          WHERE facility_work.created_by = '$admin_code' 
          ORDER BY facility_work.created DESC ";
$numrows = $db_handle->numRows($query2);
$rowsperpage = 20;

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
                    <h4><strong>FACILITY USER</strong></h4>
                </div>
            </div>


            <?php require_once 'layouts/feedback_message.php'; ?>

            <div class="row">
                <div class="col-lg-8">

                    <div class="section-tint super-shadow">
                        <div class="row">
                            <div class="col-sm-12">


                                <h5>List of Assigned Equipment </h5>
                                <table class="table table-responsive table-striped table-bordered table-hover">

                                    <thead>
                                    <th></th>
                                    <th>Inventory ID</th>
                                    <th>Name</th>
                                    <th>Report</th>
                                    </thead>
                                    <tbody>
                                    <?php include'facility_report_view.php';?>
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

                <div class="col-lg-4">

                    <div class="section-tint super-shadow">
                        <h5>Current Work Progess</h5>
                        <hr/>


<!--                       // -->
                        <table class="table table-responsive table-striped table-bordered table-hover">
                            <thead>
                            <th>Title</th>
                            <th></th>
                            </thead>
                            <tbody>
                            <?php if(isset($rep) && !empty($rep))
                            {
                                foreach ($rep as $row)
                                { ?>
                                    <tr type="button" data-target="#comment<?php echo $row['workid']; ?>" data-toggle="modal">
                                        <td><?php echo $row['title']; ?></td>
                                        <td><div class='progress' >
                                                <div class='progress-bar progress-bar-striped progress-bar-success active' role='progressbar' aria-valuenow='<?php echo $row['pro']; ?>' aria-valuemin='0' aria-valuemax='<?php echo $row['pro']; ?>' style='width: <?php echo $row['pro']; ?>%;color: #0e0e0e'>
                                                    <?php echo $row['pro']; ?>%<?php echo $row['status']; ?>
                                                </div>
                                            </div>
                                        </td>
                                    </tr>
                                    <!--Modal - confirmation boxes-->
                                    <div id="comment<?php echo $row['workid']; ?>" tabindex="-1" role="dialog" aria-hidden="true" class="modal fade">
                                        <div class="modal-dialog">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <button type="button" data-dismiss="modal" aria-hidden="true"
                                                            class="close">&times;</button>
                                                    <h4 class="modal-title"><?php echo $row['title']; ?></h4></div>
                                                <div class="modal-body"><p>Work Progress : <?php echo $row['status']; ?></p></div>
                                                <div class="modal-footer">
                                                    <input name="process" type="submit" class="btn btn-success" value="Proceed">
                                                    <button type="submit" name="close" onClick="window.close();" data-dismiss="modal" class="btn btn-danger">Close!</button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <?php
                                }
                            }
                            else
                            { echo "<em>No results to display...</em>"; } ?>
                            </tbody>
                            <?php if(isset($rep) && !empty($rep)) { require_once 'layouts/pagination_links.php'; } ?>
                        </table>


                    </div>

                    <div class="section-tint super-shadow">
                        <h5>Work Request</h5>
                        <hr/>
                        <form data-toggle="validator" class="form-vertical" role="form" method="post" action="">
                            <input name="admin" type="hidden" id="id" value="<?php echo $admin_code?>" class="form-control">
                            <div class="form-group">
                                <label class="control-label col-sm-3" for="title">Subject:</label>
                                <div class="col-sm-12 col-lg-8">
                                    <div class="input-group">
                                        <span class="input-group-addon"><i class="fa fa-pencil fa-fw"></i></span>
                                        <input name="subject" type="text" id="" value="" class="form-control" required/>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="comment">Details:</label>
                                <textarea name="details" class="form-control" rows="5" id="comment"></textarea>
                            </div>
                            <div class="form-group">
                                <label class="control-label col-sm-4" for="location">Locations:</label>
                                <div class="col-sm-8 col-lg-8">
                                    <div class="input-group date">
                                        <select name="location" class="form-control" id="location">
                                            <option value="" selected>All Offices</option>
                                            <?php
                                            $query = "SELECT * FROM accounting_system_office_locations ";
                                            $result = $db_handle->runQuery($query);
                                            $result = $db_handle->fetchAssoc($result);
                                            foreach ($result as $row)
                                            {
                                                extract($row)
                                                ?>
                                                <option value="<?php echo $location_id;?>"><?php echo $location;?></option>
                                            <?php } ?>
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="control-label col-sm-4" for="priority">Priority:</label>
                                <div class="col-sm-8 col-lg-8">
                                    <div class="input-group date">
                                        <select name="priority" class="form-control" id="location">
                                            <option value="HIGH" selected>HIGH</option>
                                            <option value="MEDium" selected>MEDIUM</option>
                                            <option value="low" selected>LOW</option>

                                        </select>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group">
                                <button  name="work" type="submit" class="btn btn-success"><i class="fa fa-send fa-fw"></i>Send</button>
                            </div>
                        </form>
                    </div>

                    <div class="section-tint super-shadow">
                        <h5>Incidence Form</h5>
                        <hr/>
                        <form data-toggle="validator" class="form-vertical" role="form" method="post" action="">
                            <div class="form-group">
                                <label class="control-label col-sm-3" for="title">Title:</label>
                                <div class="col-sm-12 col-lg-8">
                                    <div class="input-group">
                                        <span class="input-group-addon"><i class="fa fa-pencil fa-fw"></i></span>
                                        <input name="inventoryid" type="text" id="" value="" class="form-control" required/>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="comment">Details:</label>
                                <textarea class="form-control" rows="5" id="comment"></textarea>
                            </div>
                            <div class="form-group">
                                <label class="control-label col-sm-4" for="location">Locations:</label>
                                <div class="col-sm-8 col-lg-8">
                                    <div class="input-group date">
                                        <select name="location" class="form-control" id="location">
                                            <option value="" selected>All Offices</option>
                                            <?php
                                            $query = "SELECT * FROM accounting_system_office_locations ";
                                            $result = $db_handle->runQuery($query);
                                            $result = $db_handle->fetchAssoc($result);
                                            foreach ($result as $row)
                                            {
                                                extract($row)
                                                ?>
                                                <option value="<?php echo $location_id;?>"><?php echo $location;?></option>
                                            <?php } ?>
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group">
                                    <button   class="btn btn-success"><i class="fa fa-send fa-fw"></i>Send</button>
                            </div>
                        </form>
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