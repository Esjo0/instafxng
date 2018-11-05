<?php
require_once("../init/initialize_admin.php");
if (!$session_admin->is_logged_in()) {
    redirect_to("login.php");
}
//Gets Administrator code
$admin_code = $_SESSION['admin_unique_code'];

//delete notification schedule
if(isset($_POST['delete'])) {
    $advert_id = $db_handle->sanitizePost($_POST['advert_id']);
    $query = "DELETE FROM advert_div WHERE advert_id = $advert_id";
    $result = $db_handle->runQuery($query);
    if($result) {
        $message_success = "You have successfully deleted this notification";
    } else {
        $message_error = "Something went wrong. Please try again.";
    }
}

//Add new notification schedule
if(isset($_POST['create'])){
    $name = $db_handle->sanitizePost($_POST['name']);
    $details = $db_handle->sanitizePost($_POST['details']);
    $period = $db_handle->sanitizePost($_POST['period']);
    $value = $db_handle->sanitizePost($_POST['value']);
    $year = $db_handle->sanitizePost($_POST['year']);
    $type = $db_handle->sanitizePost($_POST['type']);

    if($period > 12){

    }

    $query = "INSERT into admin_targets(name, details, period, value, year, status, admin) VALUES('$name','$details', '$period','$value', '$year', '1', '$admin_code')";
    var_dump($query);
    $result = $db_handle->runQuery($query);
    if($result) {
        $message_success = "You have successfully created a new Target";
    } else {
        $message_error = "Something went wrong. Please try again.";
    }
}

//Update notification
if(isset($_POST['update'])){
    $advert_id = $db_handle->sanitizePost($_POST['advert_id']);
    $content = $db_handle->sanitizePost($_POST['content']);

    $query = "UPDATE advert_div SET content = '$content' WHERE advert_id = '$advert_id'";
    $result = $db_handle->runQuery($query);
    if($result) {
        $message_success = "You have successfully updated this notification";
    } else {
        $message_error = "Something went wrong. Please try again.";
    }
}

//Update notification status to display
if(isset($_POST['status_display'])){
    $advert_id = $db_handle->sanitizePost($_POST['advert_id']);
    $query = "UPDATE advert_div SET status = 1 WHERE advert_id = '$advert_id'";
    $result = $db_handle->runQuery($query);
    if($result) {
        $message_success = "You have successfully updated this notification view status";
    } else {
        $message_error = "Something went wrong. Please try again.";
    }
}

//Update notification status to hidden
if(isset($_POST['status_hide'])){
    $advert_id = $db_handle->sanitizePost($_POST['advert_id']);
    $query = "UPDATE advert_div SET status = 2 WHERE advert_id = '$advert_id'";
    $result = $db_handle->runQuery($query);
    if($result) {
        $message_success = "You have successfully updated this notification view status";
    } else {
        $message_error = "Something went wrong. Please try again.";
    }
}

//select previous notification schedules
$query = "SELECT * FROM admin_targets";
$numrows = $db_handle->numRows($query);
$rowsperpage = 10;

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
$query .= ' LIMIT ' . $offset . ',' . $rowsperpage;
$result = $db_handle->runQuery($query);
$targets = $db_handle->fetchAssoc($result);

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
    <script src="tinymce/tinymce.min.js"></script>
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
        <div id="main-body-content-area" class="col-md-9 col-lg-9">

            <!-- Unique Page Content Starts Here
            ================================================== -->
            <div class="row">
                <div class="col-sm-12 text-danger">
                    <h4><strong>All Targets</strong></h4>
                </div>
            </div>
            <div class="row">
                <div class="col-lg-12">
                    <div class="section-tint super-shadow">
                        <?php require_once 'layouts/feedback_message.php'; ?>

                        <div class="row">
                            <div class="col-md-12">
                                <div class="row">
                                    <div class="col-sm-12">
                                <button class="btn btn-sm btn-success pull-right"  data-target="#add_target" data-toggle="modal" title="Click Here to Update or Delete this notification."><strong>ADD TARGET</strong><i class="fa fa-plus"></i></button>
                                    </div>
                                    </div>
                                        <!--Modal - confirmation boxes-->
                                <div id="add_target" tabindex="-1" role="dialog" aria-hidden="true" class="modal fade">
                                    <div class="modal-dialog">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <button type="button" data-dismiss="modal" aria-hidden="true"
                                                        class="close">&times;</button>
                                                <h4 class="modal-title">Create New Target</h4></div>
                                            <div class="modal-body">

                                                <form class="form-vertical" role="form" method="post" action="">
                                                    <div class="form-group row">
                                                        <div class="col-sm-12">
                                                            <label for="inputHeading3" class="col-form-label">Target Title/Name:</label>
                                                            <input name="name" type="text" class="form-control" id="forum_title" placeholder="Enter Target Name or title">
                                                        </div>
                                                    </div>
                                                    <div class="form-group row">
                                                        <div class="col-sm-12">
                                                            <label for="inputHeading3" class="col-form-label">Description</label>
                                                            <textarea rows="3" name="details" type="text" class="form-control" id="forum_title" placeholder="Enter Detailed Description of the target"></textarea>
                                                        </div>
                                                    </div>
                                                    <div class="form-group row">
                                                        <div class="col-sm-6">
                                                            <label>Target Type</label>
                                                            <select  type="text" name="type" class="form-control " >
                                                                <option value="1">On Boarding</option>
                                                                <option value="2">Retention</option>
                                                            </select>
                                                        </div>
                                                    </div>
                                                    <div class="form-group row">
                                                        <label class="col-sm-12">Select Duration</label>
                                                        <div class="col-sm-6">
                                                            <div class="input-group date">
                                                                <input placeholder="Select Year" name="year" type="text" class="form-control" id="datetimepicker" required>
                                                                <span class="input-group-addon"><span class="glyphicon glyphicon-calendar"></span></span>
                                                            </div>
                                                        </div>
                                                        <div class="col-sm-6">
                                                            <div class="input-group date">
                                                            <select  type="text" name="period" class="form-control" >
                                                                <option value="1">January</option>
                                                                <option value="2">February</option>
                                                                <option value="3">March</option>
                                                                <option value="4">April</option>
                                                                <option value="5">May</option>
                                                                <option value="6">June</option>
                                                                <option value="7">July</option>
                                                                <option value="8">August</option>
                                                                <option value="9">September</option>
                                                                <option value="10">October</option>
                                                                <option value="11">November</option>
                                                                <option value="12">December</option>
                                                                <option value="1-12">Annual</option>
                                                                <option value="1-6">First Half</option>
                                                                <option value="6-12">Second Half</option>
                                                                <option value="1-3">First Quarter</option>
                                                                <option value="3-6">Second Quarter</option>
                                                                <option value="6-9">Third Quarter</option>
                                                                <option value="9-12">Fourth Quarter</option>
                                                            </select>
                                                                </div>
                                                        </div>
                                                    </div>
                                                    <script type="text/javascript">
                                                        $(function () {$('#datetimepicker').datetimepicker({format: 'YYYY'});});
                                                    </script>
                                                    <div class="form-group row">
                                                        <div class="col-sm-5">
                                                            <label for="inputHeading3" class="col-form-label">Value</label>
                                                            <input name="value" type="number" class="form-control" placeholder="Enter Target Value">
                                                        </div>
                                                    </div>
                                            </div>
                                            <div class="modal-footer">
                                                <button name="create" type="submit" class="btn btn-success"> CREATE</button></form>
                                                <button type="submit" name="close" onClick="window.close();" data-dismiss="modal" class="btn btn-danger">Close!</button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-12">
                                        <h5><strong>List of All Targets</strong></h5>
                                        <?php if(isset($targets) && !empty($targets)):?>
                                            <table  class="table table-responsive table-striped table-bordered table-hover">
                                                <thead>
                                                <tr>
                                                    <th>Title</th>
                                                    <th>Description</th>
                                                    <th>Period</th>
                                                    <th>Year</th>
                                                    <th>Value</th>
                                                    <th>Status</th>
                                                </tr>
                                                </thead>
                                                <tbody>
                                                <p><i class="fa fa-info-circle"></i> Click Each Target row to Update or Delete Target</p>
                                                <?php
                                                foreach ($targets as $row) {extract($row);
                                                ?>
                                                <tr data-target="#update<?php echo $row['advert_id']; ?>" data-toggle="modal" title="Click Here to Update or Delete this notification.">
                                                    <td ><?php echo $name; ?></td>
                                                    <td ><?php echo $details; ?></td>
                                                    <td ><?php echo target_period($period); ?></td>
                                                    <td ><?php echo $year; ?></td>
                                                    <td ><?php echo $value; ?></td>
                                                    <td ><?php echo status_snappy_help($status); ?></td>

                                                    <!--Modal - confirmation boxes-->
                                                    <div id="update<?php echo $row['advert_id']; ?>" tabindex="-1" role="dialog"
                                                         aria-hidden="true" class="modal fade ">
                                                        <div class="modal-dialog modal-md">
                                                            <div class="modal-content">
                                                                <div class="modal-header">
                                                                    <button type="button" data-dismiss="modal"
                                                                            aria-hidden="true"
                                                                            class="close">&times;
                                                                    </button>
                                                                    <h4 class="modal-title">Update Target <?php echo ($row['title']); ?> </h4>
                                                                    <div class="pull-right">
                                                                        <span><b>STATUS</b></span>
                                                                    <form data-toggle="validator"
                                                                          class="form-vertical" role="form"
                                                                          method="post" action="">
                                                                        <input name="advert_id"
                                                                               class="form-control" type="hidden"
                                                                               value="<?php echo $row['advert_id']; ?>" >
                                                                        <?php if($row['status'] == 2){?>
                                                                            <button title="Click Here to Make this notification visible to Users" type="submit" name="status_display" class="btn btn-success" >
                                                                                <span class="glyphicon glyphicon-eye-open"></span></button>
                                                                        <?php }elseif($row['status'] == 1){?>
                                                                            <button title="Click Here to Hide this notification from Users" type="submit" name="status_hide" class="btn btn-success" >
                                                                                <span class="glyphicon glyphicon-eye-close"></span></button>
                                                                        <?php }?>
                                                                    </form>
                                                                        </div>
                                                                </div>
                                                                <div class="modal-body">

                                                                    <form class="form-vertical" role="form" method="post" action="">
                                                                        <div class="form-group row">
                                                                            <div class="col-sm-12">
                                                                                <label for="inputHeading3" class="col-form-label">Target Title/Name:</label>
                                                                                <input name="name" type="text" class="form-control" id="forum_title" placeholder="Enter Target Name or title">
                                                                            </div>
                                                                        </div>
                                                                        <div class="form-group row">
                                                                            <div class="col-sm-12">
                                                                                <label for="inputHeading3" class="col-form-label">Description</label>
                                                                                <textarea rows="3" name="details" type="text" class="form-control" id="forum_title" placeholder="Enter Detailed Description of the target"></textarea>
                                                                            </div>
                                                                        </div>
                                                                        <div class="form-group row">
                                                                            <div class="col-sm-6">
                                                                                <label>Target Type</label>
                                                                                <select  type="text" name="type" class="form-control " >
                                                                                    <option value="1">On Boarding</option>
                                                                                    <option value="2">Retention</option>
                                                                                </select>
                                                                            </div>
                                                                        </div>
                                                                        <div class="form-group row">
                                                                            <label class="col-sm-12">Select Duration</label>
                                                                            <div class="col-sm-6">
                                                                                <div class="input-group date">
                                                                                    <input placeholder="Select Year" name="year" type="text" class="form-control" id="datetimepicker" required>
                                                                                    <span class="input-group-addon"><span class="glyphicon glyphicon-calendar"></span></span>
                                                                                </div>
                                                                            </div>
                                                                            <div class="col-sm-6">
                                                                                <div class="input-group date">
                                                                                    <select  type="text" name="period" class="form-control" >
                                                                                        <option value="1">January</option>
                                                                                        <option value="2">February</option>
                                                                                        <option value="3">March</option>
                                                                                        <option value="4">April</option>
                                                                                        <option value="5">May</option>
                                                                                        <option value="6">June</option>
                                                                                        <option value="7">July</option>
                                                                                        <option value="8">August</option>
                                                                                        <option value="9">September</option>
                                                                                        <option value="10">October</option>
                                                                                        <option value="11">November</option>
                                                                                        <option value="12">December</option>
                                                                                        <option value="1-12">Annual</option>
                                                                                        <option value="1-6">First Half</option>
                                                                                        <option value="6-12">Second Half</option>
                                                                                        <option value="1-3">First Quarter</option>
                                                                                        <option value="3-6">Second Quarter</option>
                                                                                        <option value="6-9">Third Quarter</option>
                                                                                        <option value="9-12">Fourth Quarter</option>
                                                                                    </select>
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                        <script type="text/javascript">
                                                                            $(function () {$('#datetimepicker').datetimepicker({format: 'YYYY'});});
                                                                        </script>
                                                                        <div class="form-group row">
                                                                            <div class="col-sm-5">
                                                                                <label for="inputHeading3" class="col-form-label">Value</label>
                                                                                <input name="value" type="number" class="form-control" placeholder="Enter Target Value">
                                                                            </div>
                                                                        </div>
                                                                </div>
                                                                <div class="modal-footer">
                                                                    <button name="create" type="submit" class="btn btn-success"> UPDATE</button></form>
                                                                    </form>
                                                                    <button type="submit" name="close" onClick="window.close();" data-dismiss="modal" class="btn btn-danger">Close!</button>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <?php
                                                    }
                                                    ?></tr>
                                                </tbody>
                                            </table>
                                        <?php endif; ?>
                                        <?php if(empty($targets)){echo "No Previous Notification";}else{ require 'layouts/pagination_links.php'; }?>
                                    </div>


                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Unique Page Content Ends Here
                    ================================================== -->

                </div>
            </div>
        </div>
    </div>
    <?php require_once 'layouts/footer.php'; ?>
    <script src="//cdnjs.cloudflare.com/ajax/libs/moment.js/2.9.0/moment-with-locales.js"></script>
    <script src="//cdn.rawgit.com/Eonasdan/bootstrap-datetimepicker/e8bddc60e73c1ec2475f827be36e1957af72e2ea/src/js/bootstrap-datetimepicker.js"></script>
</body>
</html>