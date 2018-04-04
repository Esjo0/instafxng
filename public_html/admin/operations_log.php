<?php
require_once("../init/initialize_admin.php");
if (!$session_admin->is_logged_in()) {
    redirect_to("login.php");
}

//Gets Administrator code
$admin_code = $_SESSION['admin_unique_code'];

// Unresolved issues
if(isset($_POST['unresolved'])){
    $prev_details = $db_handle->sanitizePost(trim($_POST['prev_details']));
    $log_id = $db_handle->sanitizePost(trim($_POST['log_id']));
    $new_details = $db_handle->sanitizePost(trim($_POST['new_details']));
    $comment_time = date("Y-m-d h:i:sa");
    $update_details = $prev_details."<strong> Update Time : ".$comment_time."</strong><p>".$new_details."</p>";
    $query = "UPDATE operations_log SET status = '0' , details = '$update_details' WHERE log_id = '$log_id' ";
    $result2 =$db_handle->runQuery($query);
    if($result2) {
        $message_success = "Transaction issues still opened";
    } else {
        $message_error = "Something went wrong. Please try again.";
    }
}

//resolved issues
if(isset($_POST['resolved'])){
    $prev_details = $db_handle->sanitizePost(trim($_POST['prev_details']));
    $log_id = $db_handle->sanitizePost(trim($_POST['log_id']));
    $new_details = $db_handle->sanitizePost(trim($_POST['new_details']));
    $comment_time = date("Y-m-d h:i:sa");
    $update_details = $prev_details."<strong> Update Time : ".$comment_time."</strong><p>".$new_details."</p>";
    $query = "UPDATE operations_log SET status = '1' , details = '$update_details' WHERE log_id = '$log_id' ";
    $result2 =$db_handle->runQuery($query);
    if($result2) {
        $message_success = "You have successfully closed clients transaction issue";
    } else {
        $message_error = "Something went wrong. Please try again.";
    }
}

// add new operations record
if (isset($_POST['add'])){
    $client_name = $db_handle->sanitizePost(trim($_POST['client_name']));
    $phone_no = $db_handle->sanitizePost(trim($_POST['phone_no']));
    $transaction_id = $db_handle->sanitizePost(trim($_POST['transaction_id']));
    $transaction_type = $db_handle->sanitizePost(trim($_POST['transaction_type']));
    $details = $db_handle->sanitizePost(trim($_POST['details']));
    $query = "INSERT INTO operations_log (client_name, phone_no, transaction_id, details, transaction_type, admin, status) VALUES ('$client_name','$phone_no','$transaction_id','$details','$transaction_type','$admin_code','0')";

    $result =$db_handle->runQuery($query);
    if($result) {
        $message_success = "You have successfully added a new record";
    } else {
        $message_error = "Something went wrong. Please try again.";
    }
}

//select ooperations deposit log
$query = "SELECT * FROM operations_log WHERE transaction_type = 'deposit' ORDER BY date DESC ";
$numrows = $db_handle->numRows($query);
$rowsperpage = 25;

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
$logs = $db_handle->fetchAssoc($result);

//select logs for withdrawals
$query2 = "SELECT * FROM operations_log WHERE transaction_type = 'withdrawal' ORDER BY date DESC ";
$numrows = $db_handle->numRows($query);
$rowsperpage = 25;

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
$query2 .= ' LIMIT ' . $offset . ',' . $rowsperpage;

$result = $db_handle->runQuery($query2);
$withdrawal_logs = $db_handle->fetchAssoc($result);

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
    <?php  require_once 'layouts/head_meta.php'; ?>
    <?php require_once 'hr_attendance_system.php'; ?>

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
                    <h4>OPERATIONS LOG</h4>
                </div>
                <?php require_once 'layouts/feedback_message.php'; ?>
                <div class="panel-footer">
                    <button type="button" data-target="#add-log" data-toggle="modal" class="btn btn-primary pull-right"><span ><i class="fa fa-plus"></i></span></button>

                    <!--Modal-- to add new operations log-->
                    <div id="add-log" tabindex="-1" role="dialog" aria-hidden="true" class="modal fade">
                        <div class="modal-dialog">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <button type="button" data-dismiss="modal" aria-hidden="true"
                                            class="close">&times;</button>
                                    <h4 class="modal-title">Add New Record</h4></div>
                                <div class="modal-body">
                                    <form data-toggle="validator" class="form-vertical" role="form" method="post" action="" enctype="multipart/form-data">
                                        <div class="form-group row">
                                            <label for="inputHeading3" class="col-sm-2 col-form-label">Client Name</label>
                                            <div class="col-sm-10">
                                                <input name="client_name" type="text" class="form-control" id="forum_title" placeholder="Enter Client Name" required>
                                            </div>
                                        </div>
                                        <div class="form-group row">
                                            <label for="inputHeading3" class="col-sm-2 col-form-label">Phone Number</label>
                                            <div class="col-sm-10">
                                                <input name="phone_no" type="text" class="form-control" id="forum_title" placeholder="Enter Phone-No" required>
                                            </div>
                                        </div>
                                        <div class="form-group row">
                                            <label for="inputHeading3" class="col-sm-2 col-form-label">Transaction type</label>
                                            <div class="col-sm-10">
                                        <select name="transaction_type" class="form-control" id="location">
                                            <option value="deposit" selected>Deposit</option>
                                            <option value="withdrawal" >Withdrawal</option>
                                        </select>
                                            </div>
                                        </div>
                                        <div class="form-group row">
                                            <label for="inputHeading3" class="col-sm-2 col-form-label">Transaction ID</label>
                                            <div class="col-sm-10">
                                                <input name="transaction_id" type="text" class="form-control" id="forum_title" placeholder="Enter Transaction ID" required>
                                            </div>
                                        </div>

                                        <div class="form-group row">
                                            <label for="inputSubtile3" class="col-sm-2 col-form-label">Description</label>
                                            <div class="col-sm-10">
                                                <textarea name="details" class="form-control" rows="3" placeholder="Enter Detailed Description of Clients issue" required></textarea>
                                            </div>
                                        </div>

                                </div>
                                <div class="modal-footer">
                                    <input name="add" type="submit" class="btn btn-success" value="Add To Records">
                                    <button type="submit" name="close" onClick="window.close();" data-dismiss="modal" class="btn btn-danger">Close!</button>
                                </div>
                                </form>
                            </div>
                        </div>
                    </div>

                    <span class="pull-right btn"><strong>Click the button to add New Log</strong></span>
                    <div class="clearfix"></div>
                </div>
            </div>
            <div class="row">
                <div class="col-lg-12">

                    <div class="section-tint super-shadow">
                        <div class="row">
                            <div class="col-sm-12">
                                <h5>Records</h5>
                                <ul class="nav nav-tabs">
                                    <li class="active"><a data-toggle="tab" href="#deposit_log">Deposit</a></li>
                                    <li><a data-toggle="tab" href="#withdrawal_log">Withdrawal</a></li>
                                </ul>
                                <div class="tab-content">
                                    <div id="deposit_log" class="tab-pane fade in active">
                                        <table class="table table-responsive table-striped table-bordered table-hover">
                                            <thead>
                                            <tr>
                                                <th>Transaction ID</th>
                                                <th>Client Name</th>
                                                <th>Phone No</th>
                                                <th>Details</th>
                                                <th>Status</th>
                                                <th>Update</th>
                                            </tr>
                                            </thead>
                                            <tbody>
                                            <?php if(isset($logs) && !empty($logs)) { foreach ($logs as $row) { ?>
                                                <tr>
                                                    <td><?php echo $row['transaction_id']; ?></td>
                                                    <td><?php echo $row['client_name']; ?></td>
                                                    <td><?php echo $row['phone_no']; ?></td>
                                                    <td><button type="button" data-target="#details<?php echo $row['log_id']; ?>" data-toggle="modal" class="btn btn-default">View Details</button></td>
                                                    <!--Modal - Operations log details-->
                                                    <div id="details<?php echo $row['log_id']; ?>" tabindex="-1" role="dialog" aria-hidden="true" class="modal fade">
                                                        <div class="modal-dialog">
                                                            <div class="modal-content">
                                                                <div class="modal-header">
                                                                    <button type="button" data-dismiss="modal" aria-hidden="true"
                                                                            class="close">&times;</button>
                                                                    <h4 class="modal-title">Operations Issue Description</h4></div>
                                                                <div class="modal-body">
                                                                    <?php echo $row['details']; ?>
                                                                </div>
                                                                <div class="modal-footer">
                                                                    <button type="submit" name="close" onClick="window.close();" data-dismiss="modal" class="btn btn-danger">Close!</button>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <td><?php echo status_operations($row['status']); ?></td>
                                                    <td><button type="button" data-target="#Update<?php echo $row['log_id']; ?>" data-toggle="modal" class="btn btn-primary">Update Status</button></td>
                                                    <!--Modal - Operations log details update-->
                                                    <div id="Update<?php echo $row['log_id']; ?>" tabindex="-1" role="dialog" aria-hidden="true" class="modal fade">
                                                        <div class="modal-dialog">
                                                            <div class="modal-content">
                                                                <div class="modal-header">
                                                                    <button type="button" data-dismiss="modal" aria-hidden="true"
                                                                            class="close">&times;</button>
                                                                    <h4 class="modal-title">Operations Record Update</h4></div>
                                                                <div class="modal-body">
                                                                    <div class="form-group row">
                                                                        <label for="inputSubtile3" class="col-sm-2 col-form-label">Previous Description</label>
                                                                        <div class="col-sm-10">
                                                                            <?php echo $row['details']; ?>                                                                        </div>
                                                                    </div>
                                                                    <form data-toggle="validator" class="form-vertical" role="form" method="post" action="" enctype="multipart/form-data">
                                                                        <input name="log_id" type="hidden" value="<?php echo $row['log_id']; ?>">
                                                                        <input name="prev_details" type="hidden" value="<?php echo $row['details']; ?>">
                                                                    <div class="form-group row">
                                                                        <label for="inputSubtile3" class="col-sm-2 col-form-label">Update Description</label>
                                                                        <div class="col-sm-10">
                                                                            <textarea name="new_details" class="form-control" rows="3" placeholder="Enter Detailed Description of Clients issue" required></textarea>
                                                                        </div>
                                                                    </div>

                                                                </div>
                                                                <div class="modal-footer">
                                                                    <input name="resolved" type="submit" class="btn btn-success" value="Closed">
                                                                    <input name="unresolved" type="submit" class="btn btn-warning" value="Still Opened">
                                                                    <button type="submit" name="close" onClick="window.close();" data-dismiss="modal" class="btn btn-danger">Close!</button>
                                                                </div>
                                                            </form>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </tr>
                                            <?php } } else { echo "<tr><td colspan='5' class='text-danger'><em>No results to display</em></td></tr>"; } ?>
                                            </tbody>
                                        </table>
                                        <?php if(isset($logs) && !empty($logs)) { ?>
                                            <div class="tool-footer text-right">
                                                <p class="pull-left">Showing <?php echo $prespagelow . " to " . $prespagehigh . " of " . $numrows; ?> entries</p>
                                            </div>
                                        <?php } ?>
                                        <?php if(isset($logs) && !empty($logs)) { require_once 'layouts/pagination_links.php'; } ?>
                                    </div>
                                    <div id="withdrawal_log" class="tab-pane fade">
                                        <table class="table table-responsive table-striped table-bordered table-hover">
                                            <thead>
                                            <tr>
                                                <th>Transaction ID</th>
                                                <th>Client Name</th>
                                                <th>Phone No</th>
                                                <th>Details</th>
                                                <th>Status</th>
                                                <th>Update</th>
                                            </tr>
                                            </thead>
                                            <tbody>
                                            <?php if(isset($withdrawal_logs) && !empty($withdrawal_logs)) { foreach ($withdrawal_logs as $row) { ?>
                                                <tr>
                                                    <td><?php echo $row['transaction_id']; ?></td>
                                                    <td><?php echo $row['client_name']; ?></td>
                                                    <td><?php echo $row['phone_no']; ?></td>
                                                    <td><button type="button" data-target="#details<?php echo $row['log_id']; ?>" data-toggle="modal" class="btn btn-default">View Details</button></td>
                                                    <!--Modal - Operations log details-->
                                                    <div id="details<?php echo $row['log_id']; ?>" tabindex="-1" role="dialog" aria-hidden="true" class="modal fade">
                                                        <div class="modal-dialog">
                                                            <div class="modal-content">
                                                                <div class="modal-header">
                                                                    <button type="button" data-dismiss="modal" aria-hidden="true"
                                                                            class="close">&times;</button>
                                                                    <h4 class="modal-title">Operations Issue Description</h4></div>
                                                                <div class="modal-body"><?php echo $row['details']; ?></div>
                                                                <div class="modal-footer">
                                                                    <button type="submit" name="close" onClick="window.close();" data-dismiss="modal" class="btn btn-danger">Close!</button>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <td><?php echo status_operations($row['status']); ?></td>
                                                    <td><button type="button" data-target="#Update<?php echo $row['log_id']; ?>" data-toggle="modal" class="btn btn-primary">Update Status</button></td>
                                                    <!--Modal - Operations log details update-->
                                                    <div id="Update<?php echo $row['log_id']; ?>" tabindex="-1" role="dialog" aria-hidden="true" class="modal fade">
                                                        <div class="modal-dialog">
                                                            <div class="modal-content">
                                                                <div class="modal-header">
                                                                    <button type="button" data-dismiss="modal" aria-hidden="true"
                                                                            class="close">&times;</button>
                                                                    <h4 class="modal-title">Operations Record Update</h4></div>
                                                                <div class="modal-body">
                                                                    <div class="form-group row">
                                                                        <label for="inputSubtile3" class="col-sm-2 col-form-label">Previous Description</label>
                                                                        <div class="col-sm-10">
                                                                            <?php echo $row['details']; ?>                                                                        </div>
                                                                    </div>
                                                                    <form data-toggle="validator" class="form-vertical" role="form" method="post" action="" enctype="multipart/form-data">
                                                                        <input name="log_id" type="hidden" value="<?php echo $row['log_id']; ?>">
                                                                        <input name="prev_details" type="hidden" value="<?php echo $row['details']; ?>">
                                                                        <div class="form-group row">
                                                                            <label for="inputSubtile3" class="col-sm-2 col-form-label">Update Description</label>
                                                                            <div class="col-sm-10">
                                                                                <textarea name="new_details" class="form-control" rows="3" placeholder="Enter Detailed Description of Clients issue" required></textarea>
                                                                            </div>
                                                                        </div>

                                                                </div>
                                                                <div class="modal-footer">
                                                                    <input name="resolved" type="submit" class="btn btn-success" value="Closed">
                                                                    <input name="unresolved" type="submit" class="btn btn-warning" value="Still Opened">
                                                                    <button type="submit" name="close" onClick="window.close();" data-dismiss="modal" class="btn btn-danger">Close!</button>
                                                                </div>
                                                                </form>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </tr>
                                            <?php } } else { echo "<tr><td colspan='5' class='text-danger'><em>No results to display</em></td></tr>"; } ?>
                                            </tbody>
                                        </table>
                                        <?php if(isset($withdrawal_logs) && !empty($withdrawal_logs)) { ?>
                                            <div class="tool-footer text-right">
                                                <p class="pull-left">Showing <?php echo $prespagelow . " to " . $prespagehigh . " of " . $numrows; ?> entries</p>
                                            </div>
                                        <?php } ?>
                                        <?php if(isset($withdrawal_logs) && !empty($withdrawal_logs)) { require_once 'layouts/pagination_links.php'; } ?>
                                    </div>
                                </div>

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
<?php require_once 'layouts/footer.php'; ?>
</body>
</html>