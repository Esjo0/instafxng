<?php
require_once("../init/initialize_admin.php");
if (!$session_admin->is_logged_in()) {
    redirect_to("login.php");
}

$obj_training = new training();
$admin_code = $_SESSION['admin_unique_code'];
$admin_email = $_SESSION['admin_email'];


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
                    <h4><strong>Training Schedule View Page.</strong></h4>
                    <?php require_once 'layouts/feedback_message.php'; ?>
                </div>
            </div>

            <div class="section-tint super-shadow">
                <div class="row">
                    <div class="col-sm-12">
                        <div class="row">
                            <div class="col-sm-12"><strong>Available Schedule Dates :</strong>
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
                                        } elseif ($row['location'] == 2) {
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