<?php
require_once("../init/initialize_admin.php");
if (!$session_admin->is_logged_in()) {
    redirect_to("login.php");
}

$obj_training = new training();
$admin_code = $_SESSION['admin_unique_code'];
$admin_email = $_SESSION['admin_email'];

if (isset($_POST['schedule_public'])) {
    $no_of_students = $db_handle->sanitizePost($_POST['no_of_students']);
    $date = $db_handle->sanitizePost($_POST['schedule_time']);
    $mode = $db_handle->sanitizePost($_POST['mode']);
    $location = $db_handle->sanitizePost($_POST['location']);
    $query = "SELECT * FROM training_schedule_dates WHERE schedule_date = '$date' AND schedule_type = '1'";
    $numrows = $db_handle->numRows($query);
    if ($numrows == 0) {
        $schedule_public = $obj_training->schedule_public_time($date, $mode, $no_of_students, $admin_code, $location);
        if ($schedule_public == true) {
            $message_success = "Successfully submitted";
        } else {
            $message_error = "Schedule not successful. Kindly Try again.";
        }
    } else {
        $message_error = "Schedule not successful. This time has already been scheduled.";
    }
}

if (isset($_POST['schedule_private'])) {
    $email = $db_handle->sanitizePost($_POST['email']);
    $email = filter_var($email, FILTER_VALIDATE_EMAIL);
    $date = $db_handle->sanitizePost($_POST['schedule_time_private']);
    $mode = $db_handle->sanitizePost($_POST['mode']);
    $location = $db_handle->sanitizePost($_POST['location']);
    $schedule_type = 2;

    $schedule_public = $obj_training->schedule_private_time($date, $mode, $email, $admin_code, $location, $schedule_type);
    if ($schedule_public == true) {
        $message_success = "Successfully submitted";
    } else {
        $message_error = "Schedule not successful. Kindly Try again.";
    }
}

if (isset($_POST['schedule_private_paid'])) {
    $amount = $db_handle->sanitizePost($_POST['amount']);
    $email = $db_handle->sanitizePost($_POST['email']);
    $email = filter_var($email, FILTER_VALIDATE_EMAIL);
    $date = $db_handle->sanitizePost($_POST['schedule_time_private']);
    $mode = $db_handle->sanitizePost($_POST['mode']);
    $location = $db_handle->sanitizePost($_POST['location']);

    $pay_type = $db_handle->sanitizePost($_POST['pay_type']);

    $query = "SELECT user_code, CONCAT(last_name, SPACE(1), first_name) AS full_name FROM user WHERE email = '$email'";
    $result = $db_handle->runQuery($query);
    $get_details = $db_handle->fetchAssoc($result);
    foreach ($get_details as $row) {
        extract($row);
    }
    $course_id = 0;
    $origin_of_deposit = '1'; // Originates online
    $stamp_duty = 50; // It has been added to course cost
    $course_cost = $amount;
    $total_payable = $stamp_duty + $course_cost;
    $card_processing = 0;
    $total_payable_card = $card_processing + $total_payable;
    $course_name = "Intermediate Mentorship Program";
    $trans_id = "FPA" . time();
    $trans_id_encrypted = dec_enc('encrypt', $trans_id);
    $client_name = $full_name;
    $client_email = $email;
    $schedule_type = 3;

    $payment = $obj_training->log_course_deposit($mode, $location, $date, $user_code, $trans_id, $course_id, $course_cost, $stamp_duty, $card_processing, $pay_type, $origin_of_deposit, $client_name, $client_email);

    $schedule_public = $obj_training->schedule_private_time($date, $mode, $client_email, $admin_code, $location, $schedule_type);

    $date = datetime_to_text($date);
    $content = <<<MAIL
<p>You Created a paid Training for $client_name</p>
<p>Transaction ID is $trans_id</p>
<p>Training is scheduled for $date.</p>
<p>TEAM INSAFXNG</p>
MAIL;
    $send_mail = $obj_training->send_mail($content, $admin_email, $first_name);
    if (($schedule_public == true)) {
        $message_success = "Successfully submitted";
    } else {
        $message_error = "Schedule not successful. Kindly Try again.";
    }
}

if (isset($_POST['reschedule'])) {
    $id = $db_handle->sanitizePost($_POST['training_id']);
    $date = $db_handle->sanitizePost($_POST['reschedule_date']);
    $mode = $db_handle->sanitizePost($_POST['reschedule_mode']);
    $type = $db_handle->sanitizePost($_POST['reschedule_type']);
    $location = $db_handle->sanitizePost($_POST['location']);
    $schedule_public = $obj_training->reschedule($date, $mode, $id, $admin_code, $type, $location);
    if ($schedule_public == true) {
        $message_success = "Successfully submitted";
    } else {
        $message_error = "Schedule not successful. Kindly Try again. mE";
    }
}

if (isset($_POST['completed'])) {
    $id = $db_handle->sanitizePost($_POST['training_id']);
    $query = "UPDATE training_schedule_students SET status = '1' WHERE id = $id ";
    $result = $db_handle->runQuery($query);
    if ($result == true) {
        $message_success = "Successfully Completed";
    } else {
        $message_error = "Schedule not successfully submitted. Kindly Try again.";
    }
}

if (isset($_POST['follow_completed'])) {
    $id = $db_handle->sanitizePost($_POST['training_id']);
    $query = "UPDATE training_schedule_students SET status = '4' WHERE id = $id ";
    $result = $db_handle->runQuery($query);
    if ($result == true) {
        $message_success = "Successfully Completed";
    } else {
        $message_error = "Schedule not successfully submitted. Kindly Try again.";
    }
}

if (isset($_POST['final_completed'])) {
    $id = $db_handle->sanitizePost($_POST['training_id']);
    $query = "UPDATE training_schedule_students SET status = '5' WHERE id = $id ";
    $result = $db_handle->runQuery($query);
    if ($result == true) {
        $message_success = "Successfully Completed";
    } else {
        $message_error = "Schedule not successfully submitted. Kindly Try again.";
    }
}

//0-scheduled, 1-completed, 2-rescheduled, 3-follow-up, 4-Followup completed, 5-final_completed

if (isset($_POST['delete_schedule'])) {
    $del_id = $db_handle->sanitizePost($_POST['del_id']);
    $query = "DELETE FROM training_schedule_dates WHERE schedule_id = '$del_id' ";
    $result = $db_handle->runQuery($query);
    if ($result == true) {
        $message_success = "Successfully Deleted";
    } else {
        $message_error = "Schedule not successfully submitted. Kindly Try again.";
    }
}

$available_dates = $obj_training->get_available_dates();

$from_date = "";
$to_date = "";

if (isset($_POST['view'])) {
    unset($_SESSION['filter']);
}

if (isset($_POST['filter'])) {
    foreach ($_POST as $key => $value) {
        $_POST[$key] = $db_handle->sanitizePost(trim($value));
    }
    $from_date = $_POST['from_date'];
    $to_date = $_POST['to_date'];
    $cat = $_POST['cat'];
    if ($cat == 11) {
        $cat = "";
    } else {
        $cat = "AND tss.status = '$cat'";
    }
    $filter = "(STR_TO_DATE(tsd.schedule_date, '%Y-%m-%d') BETWEEN '$from_date' AND '$to_date') $cat";

}
if (empty($_SESSION['filter'])) {
    $from_date = date('Y-m-d');
    $to_date = date('Y-m') . "-31";
    $filter = "(STR_TO_DATE(tsd.schedule_date, '%Y-%m-%d') BETWEEN '$from_date' AND '$to_date')";
}

if (!empty($filter) || ($filter != null)) {
    $_SESSION['filter'] = $filter;
}
$filter_val = $_SESSION['filter'];


$query = "SELECT tss.follow_up_class, tss.final_class, tsd.location, tsd.schedule_type, tss.id, tss.status,
CONCAT(u.last_name, SPACE(1), u.first_name) AS full_name, u.phone, u.email, tsd.schedule_date, u.user_code, tsd.schedule_mode
    FROM training_schedule_students AS tss
    INNER JOIN user AS u ON u.user_code = tss.user_code
    INNER JOIN training_schedule_dates AS tsd ON tsd.schedule_id = tss.schedule_id
    WHERE  $filter_val";
$numrows = $db_handle->numRows($query);

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
$query .= ' ORDER BY tsd.created DESC LIMIT ' . $offset . ',' . $rowsperpage;
$result = $db_handle->runQuery($query);
$training_schedules = $db_handle->fetchAssoc($result);

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <base target="_self">
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Instaforex Nigeria | Admin - Independence Contest 2018</title>
    <meta name="title" content="Instaforex Nigeria | Admin - Independence Contest 2018"/>
    <meta name="keywords" content=""/>
    <meta name="description" content=""/>
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
                    <h4><strong>Training Schedule Desk.</strong></h4>
                    <?php require_once 'layouts/feedback_message.php'; ?>
                </div>
            </div>

            <div class="section-tint super-shadow">
                <div class="row">
                    <div class="col-sm-12">
                        <div class="panel panel-default">
                            <div class="panel-heading"><strong>Schedule FPA Training</strong></div>
                            <div class="panel-body">
                                <form class="form-inline text-center" role="form" method="post" action="">
                                    <div class="form-group mx-sm-3 mb-2">
                                        <label for="input" class="sr-only">Training Time</label>
                                        <input name="schedule_time" type="text" id="pickdate" class="form-control"
                                               placeholder="Enter Schedule Date" required>
                                    </div>
                                    <div class="form-group mx-sm-2 mb-2">
                                        <label for="input" class="sr-only">Allowed Number of Students</label>
                                        <input name="no_of_students" type="number" class="form-control"
                                               placeholder="No. of students" required>
                                    </div>
                                    <div class="form-group mx-sm-4 mb-2">
                                        <select name="mode" id="mode" class="form-control" required>
                                            <option value="">Select Training Type</option>
                                            <option value="1">Online</option>
                                            <option value="2">Offline</option>
                                        </select>
                                    </div>
                                    <div class="form-group mx-sm-4 mb-2">
                                        <select type="text" name="location" class="form-control " id="location">
                                            <?php
                                            $query = "SELECT * FROM facility_location";
                                            $result = $db_handle->runQuery($query);
                                            $result = $db_handle->fetchAssoc($result);
                                            foreach ($result as $row_loc) {
                                                extract($row_loc)
                                                ?>
                                                <option
                                                    value="<?php echo $location_id; ?>"><?php echo $location; ?></option>
                                            <?php } ?>
                                        </select>
                                    </div>
                                    <script type="text/javascript">
                                        $(function () {
                                            $('#pickdate').datetimepicker({
                                                format: 'YYYY-MM-DD HH:mm'
                                            });
                                        });
                                    </script>
                                    <button name="schedule_public" type="submit" class="btn btn-default mb-2">SUBMIT
                                    </button>
                                </form>
                            </div>
                            <div class="panel-footer">
                                <span>Total Training Scheduled for today :</span>
                                <div class="pull-right">
                                    <button type="button" data-target="#schedule_private" data-toggle="modal"
                                            class="btn btn-sm btn-success"> Schedule Private Training Date
                                    </button>
                                </div>

                                <!--Modal - confirmation boxes-->
                                <div id="schedule_private" tabindex="-1" role="dialog" aria-hidden="true"
                                     class="modal fade">
                                    <form class="form-inline text-center" role="form" method="post" action="">
                                        <div class="modal-dialog modal-lg">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <button type="button" data-dismiss="modal" aria-hidden="true"
                                                            class="close">&times;</button>
                                                    <h4 class="modal-title">Schedule Personal Training Time</h4>
                                                </div>
                                                <div class="modal-body">
                                                    <div class="form-group mx-sm-4 mb-2">
                                                        <label for="input" class="sr-only">Email Address</label>
                                                        <input name="email" type="text" class="form-control"
                                                               placeholder="Enter Client Email " required>
                                                    </div>
                                                    <div class="form-group mx-sm-4 mb-2">
                                                        <label for="input" class="sr-only">Training Time</label>
                                                        <input name="schedule_time_private" type="text"
                                                               id="pickdateprivate" class="form-control"
                                                               placeholder="Enter Training Date" required>
                                                    </div>
                                                    <script type="text/javascript">
                                                        $(function () {
                                                            $('#pickdateprivate').datetimepicker({
                                                                format: 'YYYY-MM-DD HH:mm'
                                                            });
                                                        });
                                                    </script>

                                                    <div class="form-group mx-sm-4 mb-2">
                                                        <select type="text" name="location" class="form-control "
                                                                id="location">
                                                            <?php
                                                            $query = "SELECT * FROM facility_location";
                                                            $result = $db_handle->runQuery($query);
                                                            $result = $db_handle->fetchAssoc($result);
                                                            foreach ($result as $row_loc) {
                                                                extract($row_loc)
                                                                ?>
                                                                <option
                                                                    value="<?php echo $location_id; ?>"><?php echo $location; ?></option>
                                                            <?php } ?>
                                                        </select>
                                                    </div>

                                                    <div class="form-group mx-sm-4 mb-2">
                                                        <select name="mode" id="mode" class="form-control" required>
                                                            <option value="">Select Training Type</option>
                                                            <option value="1">Online</option>
                                                            <option value="2">Offline</option>
                                                        </select>
                                                    </div>
                                                </div>
                                                <div class="modal-footer">
                                                    <input name="schedule_private" type="submit"
                                                           class="btn btn-sm btn-default" value="SUBMIT">
                                                    <button type="button" name="close" onClick="window.close();"
                                                            data-dismiss="modal" class="btn btn-sm btn-danger">Close!
                                                    </button>
                                                </div>
                                            </div>
                                        </div>
                                    </form>
                                </div>
                                <div class="pull-right">
                                    <button type="button" data-target="#schedule_private_paid" data-toggle="modal"
                                            class="btn btn-sm btn-info"> Schedule Private Paid Training Date
                                    </button>
                                </div>

                                <!--Modal - confirmation boxes-->
                                <div id="schedule_private_paid" tabindex="-1" role="dialog" aria-hidden="true"
                                     class="modal fade">
                                    <form class="form-horizontal text-center" role="form" method="post" action="">
                                        <div class="modal-dialog modal-sm">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <button type="button" data-dismiss="modal" aria-hidden="true"
                                                            class="close">&times;</button>
                                                    <h4 class="modal-title">Schedule Paid Personal Training Time</h4>
                                                </div>
                                                <div class="modal-body">
                                                    <div class="form-group mx-sm-4 mb-2">
                                                        <label for="input" class="sr-only">Email Address</label>
                                                        <input name="email" type="text" class="form-control"
                                                               placeholder="Enter Client Email " required>
                                                    </div>
                                                    <div class="form-group mx-sm-4 mb-2">
                                                        <label for="input" class="sr-only">Enter Amount</label>
                                                        <input name="amount" type="number" class="form-control"
                                                               placeholder="Enter Required Amount" required>
                                                    </div>


                                                    <div class="form-group mx-sm-4 mb-2">
                                                        <label for="input" class="sr-only">Training Time</label>
                                                        <input name="schedule_time_private" type="text"
                                                               id="pickdateprivatepaid" class="form-control"
                                                               placeholder="Enter Training Date" required>
                                                    </div>
                                                    <script type="text/javascript">
                                                        $(function () {
                                                            $('#pickdateprivatepaid').datetimepicker({
                                                                format: 'YYYY-MM-DD HH:mm'
                                                            });
                                                        });
                                                    </script>

                                                    <div class="form-group mx-sm-4 mb-2">
                                                        <select type="text" name="location" class="form-control "
                                                                id="location">
                                                            <?php
                                                            $query = "SELECT * FROM facility_location";
                                                            $result = $db_handle->runQuery($query);
                                                            $result = $db_handle->fetchAssoc($result);
                                                            foreach ($result as $row_loc) {
                                                                extract($row_loc)
                                                                ?>
                                                                <option
                                                                    value="<?php echo $location_id; ?>"><?php echo $location; ?></option>
                                                            <?php } ?>
                                                        </select>
                                                    </div>

                                                    <div class="form-group mx-sm-4 mb-1">
                                                        <select name="mode" id="mode" class="form-control" required>
                                                            <option value="">Select Training Type</option>
                                                            <option value="1">Online</option>
                                                            <option value="2">Offline</option>
                                                        </select>
                                                    </div>
                                                    <div class="form-group mx-sm-4 mb-1">
                                                        <select name="pay_type" id="mode" class="form-control" required>
                                                            <option value="">Select Payment Method</option>
                                                            <option value="2">Internet Transfer</option>
                                                            <option value="3">ATM Transfer</option>
                                                            <option value="4">Bank Transfer</option>
                                                            <option value="5">Mobile Money Tranfer</option>
                                                            <option value="6">Cash Deposit</option>
                                                            <option value="7">Office Payment</option>
                                                            <option value="8">Not Listed</option>
                                                        </select>
                                                    </div>
                                                </div>

                                                <div class="modal-footer">
                                                    <input name="schedule_private_paid" type="submit"
                                                           class="btn btn-sm btn-default" value="SUBMIT">
                                                    <button type="button" name="close" onClick="window.close();"
                                                            data-dismiss="modal" class="btn btn-sm btn-danger">Close!
                                                    </button>
                                                </div>
                                            </div>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-sm-12"><strong>Available Dates :</strong>
                                <div class="row">

                                    <?php if (!empty($available_dates)){
                                    foreach ($available_dates AS $row){
                                    extract($row);
                                    if ($location == 1) {
                                        $location = "Diamond Estate office";
                                    } elseif ($location == 2) {
                                        $location = "EASTLINE Complex office";
                                    }
                                    echo "<div class='col-sm-3'>[ " . datetime_to_text($schedule_date) . " <br> No of Students $no_of_students <br>" . training_mode($schedule_mode) . " <br> @ : " . $location . " ] "; ?>
                                    <form method="post" action="">
                                        <input name="del_id" type="hidden" value="<?php echo $schedule_id; ?>">
                                        <button name="delete_schedule" type="submit" class="btn btn-danger btn-sm"><i
                                                class="glyphicon glyphicon-remove-circle"></i></button>
                                    </form>
                                </div>

                                <?php }
                                } ?>
                            </div>

                            </br>
                            <div class="pull-left">
                                <b>All Students scheduled <?php if (!empty($from_date)) {
                                        echo "Between " . date_to_text($from_date); ?> to <?php echo date_to_text($to_date);
                                    } ?>.</b></p>

                                <?php if (isset($numrows)) { ?>
                                    <p><strong>Result Found: </strong><?php echo number_format($numrows); ?></p>
                                <?php } ?>
                                <form method="post" action="">
                                    <button name="view" type="submit" class="btn btn-info btn-sm"><i
                                            class="glyphicon glyphicon-eye-circle"></i>View Upcoming Training
                                    </button>
                                </form>
                            </div>

                            <div class="pull-right inline">

                                <button type="button" data-target="#confirm-add-admin" data-toggle="modal"
                                        class="btn btn-sm btn-default"><i class="glyphicon glyphicon-search"></i> Apply
                                    Filter
                                </button>
                            </div>

                            <!--Modal - confirmation boxes-->
                            <div id="confirm-add-admin" tabindex="-1" role="dialog" aria-hidden="true"
                                 class="modal fade">
                                <form data-toggle="validator" class="form-horizontal" role="form" method="post"
                                      action="<?php echo $_SERVER['REQUEST_URI']; ?>">
                                    <div class="modal-dialog modal-md">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <button type="button" data-dismiss="modal" aria-hidden="true"
                                                        class="close">&times;</button>
                                                <h4 class="modal-title">Apply Search Filter</h4></div>
                                            <div class="modal-body">
                                                <p>Select Students within a date range using the form below.</p>

                                                <div class="input-group date">
                                                    <input placeholder="Select start date" name="from_date" type="text"
                                                           class="form-control" id="datetimepicker" required>
                                                    <span class="input-group-addon"><span
                                                            class="glyphicon glyphicon-calendar"></span></span>
                                                </div>

                                                <br>

                                                <div class="input-group date">
                                                    <input placeholder="Select end date" name="to_date" type="text"
                                                           class="form-control" id="datetimepicker2" required>
                                                    <span class="input-group-addon"><span
                                                            class="glyphicon glyphicon-calendar"></span></span>
                                                </div>
                                                <script type="text/javascript">
                                                    $(function () {
                                                        $('#datetimepicker, #datetimepicker2').datetimepicker({format: 'YYYY-MM-DD'});
                                                    });
                                                </script>
                                                <br/>

                                                <div class="input-group">
                                                    <label>Select Category</label>
                                                    <select placeholder="Select start date" name="cat"
                                                            id="reschedule_type" class="form-control" required>
                                                        <option value="11">All Trainings</option>
                                                        <option value="0">Scheduled Trainings</option>
                                                        <option value="1">Completed Trainings</option>
                                                        <option value="2">Re-Scheduled Trainings</option>
                                                        <option value="3">Follow-up Trainings</option>
                                                        <option value="4">Follow-up Trainings Completed</option>
                                                        <option value="5">Final Trainings Completed</option>
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="modal-footer">
                                                <input name="filter" type="submit" class="btn btn-sm btn-success"
                                                       value="Proceed">
                                                <button type="button" name="close" onClick="window.close();"
                                                        data-dismiss="modal" class="btn btn-sm btn-danger">Close!
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                    <table class="table table-responsive table-striped table-bordered table-hover">
                        <thead>
                        <tr>
                            <th>Name</th>
                            <th>Schedule Date</th>
                            <th>Email Address</th>
                            <th>Phone Number</th>
                            <th>Location</th>
                            <th></th>
                            <th></th>
                        </tr>
                        </thead>
                        <tbody>
                        <?php
                        if (isset($training_schedules) && !empty($training_schedules)) {
                            foreach ($training_schedules as $row) {
                                ?>
                                <tr>
                                    <td><?php echo $row['full_name']; ?>
                                        <?php echo "<span class=\"badge badge-light\">".training_mode($row['schedule_mode'])."</span>"; ?>
                                    </td>
                                    <td><?php echo datetime_to_text($row['schedule_date']); ?></td>
                                    <td><?php if ($row['schedule_type'] == '2') {
                                            echo " <span class=\"badge badge-light\">Private</span>";
                                        } ?>
                                        <?php if ($row['schedule_type'] == '3') {
                                            echo " <span class=\"badge badge-light\">Private Paid</span>";
                                        } ?>
                                        <?php echo $row['email']; ?></td>
                                    <td><?php echo $row['phone']; ?></td>
                                    <td><?php if ($row['location'] == 1) {
                                            echo "Diamond Estate office";
                                        } elseif ($row['location'] == 3) {
                                            echo "EASTLINE Complex office";
                                        }
                                        ?></td>
                                    <td nowrap="nowrap">
                                        <a class="btn btn-primary btn-sm" title="Send Email"
                                           href="campaign_email_single.php?name=<?php $name = $row['full_name'];
                                           echo dec_enc('encrypt', $name) . '&email=' . dec_enc('encrypt', $row['email']); ?>"><i
                                                class="glyphicon glyphicon-envelope"></i></a>
                                        <a class="btn btn-success btn-sm" title="Send SMS"
                                           href="campaign_sms_single.php?lead_phone=<?php echo dec_enc('encrypt', $row['phone']) ?>"><i
                                                class="glyphicon glyphicon-phone-alt"></i></a>
                                        <a target="_blank" title="View" class="btn btn-info btn-sm"
                                           href="client_detail.php?id=<?php echo dec_enc('encrypt', $row['user_code']); ?>"><i
                                                class="glyphicon glyphicon-eye-open icon-white"></i> </a>
                                        <a target="_blank" title="Comment" class="btn btn-success btn-sm"
                                           href="sales_contact_view.php?x=<?php echo dec_enc('encrypt', $row['user_code']); ?>&r=<?php echo 'training_schedule'; ?>&c=<?php echo dec_enc('encrypt', 'TRAINING SCHEDULE'); ?>&pg=<?php echo $currentpage; ?>"><i
                                                class="glyphicon glyphicon-comment icon-white"></i> </a>
                                        <a class="btn btn-default btn-sm" data-toggle="modal" title="Re-schedule"
                                           data-target="#re<?php echo($row['id']) ?>"><i
                                                class="glyphicon glyphicon-repeat"></i></a>
                                        <!--Modal - confirmation boxes-->
                                        <div id="re<?php echo($row['id']) ?>" tabindex="-1" role="dialog"
                                             aria-hidden="true" class="modal fade">
                                            <div class="modal-dialog modal-lg">
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <button type="button" data-dismiss="modal" aria-hidden="true"
                                                                class="close">&times;</button>
                                                        <form class="form-inline text-center" method="post" action=""><input
                                                                name="training_id" type="hidden"
                                                                value="<?php echo $row['id']; ?>">
                                                            <button title="Click Here if trainiing is completed"
                                                                    name="completed" type="submit"
                                                                    class="btn btn-success btn-sm"><strong>FIRST TRAINING
                                                                    COMPLETED</strong><i
                                                                    class="glyphicon glyphicon-ok-circle"></i></button>
                                                            <button title="Click Here if trainiing is completed"
                                                                    name="follow_completed" type="submit"
                                                                    class="btn btn-success btn-sm"><strong>FOLLOW-UP CLASS
                                                                    COMPLETED</strong><i
                                                                    class="glyphicon glyphicon-ok-circle"></i></button>
                                                            <button title="Click Here if trainiing is completed"
                                                                    name="final_completed" type="submit"
                                                                    class="btn btn-success btn-sm"><strong>FINAL CLASS
                                                                    COMPLETED</strong><i
                                                                    class="glyphicon glyphicon-ok-circle"></i></button>
                                                        </form>
                                                        <h4 class="modal-title">Re-Schedule Personal Training Time</h4>
                                                    </div>
                                                    <div class="modal-body">

                                                        <form class="form-inline text-center" role="form" method="post"
                                                              action="">

                                                            <input name="training_id" type="hidden"
                                                                   value="<?php echo $row['id'] ?>"
                                                                   class="form-control">
                                                            <div class="form-group mx-sm-4 mb-2">
                                                                <label for="input" class="sr-only">Training Time</label>
                                                                <input name="reschedule_date" type="text"
                                                                       id="reschedule<?php echo $row['id']; ?>"
                                                                       class="form-control"
                                                                       placeholder="Enter Training Date" required>
                                                            </div>
                                                            <script type="text/javascript">
                                                                $(function () {
                                                                    $('#reschedule<?php echo $row['id'] ?>').datetimepicker({
                                                                        format: 'YYYY-MM-DD HH:mm'
                                                                    });
                                                                });
                                                            </script>
                                                            <div class="form-group mx-sm-4 mb-2">
                                                                <select name="reschedule_mode" id="reschedule_mode"
                                                                        class="form-control" required>
                                                                    <option>Select Training Type</option>
                                                                    <option value="1">Online</option>
                                                                    <option value="2">Offline</option>
                                                                </select>
                                                            </div>
                                                            <div class="form-group mx-sm-4 mb-2">
                                                                <select type="text" name="location"
                                                                        class="form-control " id="location">
                                                                    <?php
                                                                    $query = "SELECT * FROM facility_location";
                                                                    $result = $db_handle->runQuery($query);
                                                                    $result = $db_handle->fetchAssoc($result);
                                                                    foreach ($result as $row_loc) {
                                                                        extract($row_loc)
                                                                        ?>
                                                                        <option
                                                                            value="<?php echo $location_id; ?>"><?php echo $location; ?></option>
                                                                    <?php } ?>
                                                                </select>
                                                                <div class="form-group mx-sm-4 mb-2">
                                                                    <select name="reschedule_type" id="reschedule_type"
                                                                            class="form-control" required>
                                                                        <option>Select Re-schedule Type</option>
                                                                        <option value="2">Re-schedule Date</option>
                                                                        <option value="3">Follow-up Class</option>
                                                                    </select>
                                                                </div>
                                                            </div>
                                                            <?php if($row['follow_up_class'] != NULL && !empty($row['follow_up_class'])){?>
                                                                <table class="table table-responsive hover">
                                                                    <tr>
                                                                        <td>Follow Up Class</td>
                                                                        <td><?php echo datetime_to_textday($row['follow_up_class']) . " " . datetime_to_text($row['follow_up_class']) ?></td>
                                                                    </tr>
                                                                    <tr>
                                                                        <td>Final Class</td>
                                                                        <td><?php echo datetime_to_textday($row['final_class']) . " " . datetime_to_text($row['final_class']) ?></td>
                                                                    </tr>
                                                                </table>
                                                            <?php }?>

                                                            <div class="modal-footer">
                                                                <input name="reschedule" type="submit"
                                                                       class="btn btn-sm btn-default" value="SUBMIT">
                                                        </form>

                                                        <button type="button" name="close" onClick="window.close();"
                                                                data-dismiss="modal" class="btn btn-sm btn-danger">
                                                            Close!
                                                        </button>
                                                    </div>
                                                </div>
                                            </div>

                                        </div>

                                    </td>
                                    <td>
                                        <?php if ($row['status'] == '1') {
                                            echo "<i>Completed</i>";
                                        } ?>
                                        <?php if ($row['status'] == '2') {
                                            echo "<i>Re-scheduled</i>";
                                        } ?>
                                        <?php if ($row['status'] == '3') {
                                            echo "<i>Follow-up</i>";
                                        } ?>
                                        <?php if ($row['status'] == '4') {
                                            echo "<i>Follow-up Completed</i>";
                                        } ?>
                                        <?php if ($row['status'] == '5') {
                                            echo "<i>Final Completed</i>";
                                        } ?>
                                    </td>
                                </tr>
                            <?php }
                        } else {
                            echo "<tr><td colspan='5' class='text-danger'><em>No results to display</em></td></tr>";
                        } ?>
                        </tbody>
                    </table>

                    <?php if (isset($training_schedules) && !empty($training_schedules)) { ?>
                        <div class="tool-footer text-right">
                            <p class="pull-left">
                                Showing <?php echo $prespagelow . " to " . $prespagehigh . " of " . $numrows; ?>
                                entries</p>
                        </div>
                    <?php } ?>
                </div>
            </div>

            <?php if (isset($training_schedules) && !empty($training_schedules)) {
                require_once 'layouts/pagination_links.php';
            } ?>
        </div>

        <!-- Unique Page Content Ends Here
        ================================================== -->

    </div>

</div>
</div>
<script src="//cdnjs.cloudflare.com/ajax/libs/moment.js/2.9.0/moment-with-locales.js"></script>
<script
    src="//cdn.rawgit.com/Eonasdan/bootstrap-datetimepicker/e8bddc60e73c1ec2475f827be36e1957af72e2ea/src/js/bootstrap-datetimepicker.js"></script>
<?php require_once 'layouts/footer.php'; ?>
</body>
</html>