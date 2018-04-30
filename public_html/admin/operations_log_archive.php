<?php
/**
 * Created by PhpStorm.
 * User: user
 * Date: 03/04/2018
 * Time: 3:02 PM
 */
require_once("../init/initialize_admin.php");
if (!$session_admin->is_logged_in()) {
    redirect_to("login.php");
}
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
//Gets Administrator code
$admin_code = $_SESSION['admin_unique_code'];
$filter = "WHERE status = '1'";
//select logs for withdrawals
if(isset($_POST['SEARCH'])){
    $filter = $db_handle->sanitizePost(trim($_POST['filter']));
    $search = $db_handle->sanitizePost(trim($_POST['search']));
    if(!empty($search)){$searcher = "WHERE $filter LIKE '%$search%'";}
    if(!empty($filter)){$filter = "ORDER BY $filter DESC";}
}

$query2 = "SELECT * FROM operations_log $searcher $filter ";

$numrows = $db_handle->numRows($query2);
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
                    <h4>OPERATIONS LOG REPORT</h4>
                </div>
            </div>

                <div class="section-tint super-shadow">
                    <div class="row">
                        <div class="col-sm-6">
                            <form id="requisition_form" data-toggle="validator" class="form-horizontal" role="form" method="post" action="">

                                <div class="form-group">
                                    <div class="col-sm-12">
                                        <div class="row">
                                            <div class="col-md-3">
                                                <select name="filter" class="form-control" id="filter" placeholder="Filter by">
                                                    <option value="" >Filter by:</option>
                                                    <option value="date">Date</option>
                                                    <option value="transaction_id">Transaction ID</option>
                                                </select>
                                            </div>
                                            <div class="col-md-3">
                                                <div class="input-group">
                                                    <input type="text" class="form-control" name="search" placeholder="Search...">
                                                </div>
                                            </div>
                                            <div class="col-md-2">
                                                <div class="input-group">
                                                    <button name="SEARCH" class="btn btn-success" type="submit"><span class="glyphicon glyphicon-search"></span></button>
                                                </div>
                                            </div>


                                        </div>
                                    </div>
                                </div>
                            </form>
                        </div>
                        <div class="col-md-6">
                            <div>
                                <a href="operations_log.php"><button class="btn btn-default pull-right">View Pending issues <span class="glyphicon glyphicon-list"></button></a>
                            </div>
                        </div>

                    </div>
                    <hr>
                </div>
                <div class="col-md-12 section-tint super-shadow">
                    <table class="table table-responsive table-striped table-bordered table-hover">
                        <thead>
                        <tr>
                            <th>Transaction ID</th>
                            <th>Clients Name</th>
                            <th>Account No.</th>
                            <th>Details</th>
                            <th>Status</th>
                            <th>Created By</th>
                            <th>Date Created</th>
                        </tr>
                        </thead>
                        <tbody>
                        <?php if((isset($withdrawal_logs) && !empty($withdrawal_logs)) || (isset($search_logs) && !empty($search_logs)) ) { foreach ($withdrawal_logs as $row) { ?>
                            <tr>
                                <td><?php echo $row['transaction_id']; ?></td>
                                <?php
                                $transaction_details = $admin_object->get_transaction_details($row['transaction_id']);
                                foreach($transaction_details as $row2) {?>
                                <td><?php echo $row2['full_name']; ?></td>
                                <td><?php echo $row2['ifx_acct_no']; ?></td>
                                <td><button type="button" data-target="#details<?php echo $row['transaction_id']; ?>" data-toggle="modal" class="btn btn-default">View Details</button></td>
                                <!--Modal - Operations log details-->
                                <div id="details<?php echo $row['transaction_id']; ?>" tabindex="-1" role="dialog" aria-hidden="true" class="modal fade">
                                    <div class="modal-dialog modal-lg">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <button type="button" data-dismiss="modal" aria-hidden="true"
                                                        class="close">&times;</button>
                                                <h4 class="modal-title">Operations Issue Description</h4></div>
                                            <div class="modal-body">
                                                <div class="row">
                                                    <div class="col-sm-6" >
                                                            <div class="row" style="margin: 15px;">
                                                                <div class="col-sm-4"><strong>Client Name</strong></div>
                                                                <div class="col-sm-8"><?php echo $row2['full_name']; ?></div>
                                                            </div>
                                                            <div class="row" style="margin: 15px;">
                                                                <div class="col-sm-4"><strong>Account Number</strong></div>
                                                                <div class="col-sm-8"><?php echo $row2['ifx_acct_no']; ?></div>
                                                            </div>
                                                            <div class="row" style="margin: 15px;">
                                                                <div class="col-sm-4"><strong>Amount Ordered</strong></div>
                                                                <div class="col-sm-8">&dollar; <?php echo $row2['dollar_ordered']; ?></div>
                                                            </div>
                                                            <div class="row" style="margin: 15px;">
                                                                <div class="col-sm-4"><strong>Transaction Status</strong></div>
                                                                <div class="col-sm-8"><?php echo status_user_deposit($row2['status']); ?></div>
                                                            </div>  <?php } ?>

                                                        <div class="row" style="margin: 15px;">
                                                            <div class="col-sm-4"><strong>Issue Status</strong></div>
                                                            <div class="col-sm-8"> <?php echo status_operations($row['status']); ?><?php if($row['status'] == 1){ echo " on ".date_to_text($row['date_closed']);}?></div>
                                                        </div>

                                                        <div class="row" style="margin: 15px;">
                                                            <div class="col-sm-4"><strong>Issue Discription</strong></div>
                                                            <div class="col-sm-8"> <?php echo $row['details'];?></div>
                                                        </div>
                                                    </div>
                                                    <div class="col-sm-6">
                                                        <center><p class="btn btn-default"><strong>COMMENTS</strong></p></center>
                                                        <div style="height: 300px; overflow: auto;">
                                                            <?php
                                                            $comments_details = $admin_object->get_comment_details( $row['transaction_id'] );
                                                            if(!empty($comments_details)){
                                                                foreach($comments_details as $row3) { ?>
                                                                    <div class="transaction-remarks">
                                                                                        <span id="trans_remark_author"><?php
                                                                                            $admin_code = $row3['admin_code'];
                                                                                            $destination_details = $obj_facility->get_admin_detail_by_code($admin_code);
                                                                                            $admin_name = $destination_details['first_name'];
                                                                                            $admin_lname = $destination_details['last_name'];
                                                                                            echo $admin_name . " " . $admin_lname;?></span>
                                                                        <span id="trans_remark"><?php echo $row3['comment']; ?></span>
                                                                        <span id="trans_remark_date"><?php echo datetime_to_text($row3['created']); ?></span>
                                                                    </div>
                                                                <?php }} else{ ?> <img class="img-responsive" src="../images/No-Comments.png" /> <?php } ?>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="modal-footer">
                                                <button type="submit" name="close" onClick="window.close();" data-dismiss="modal" class="btn btn-danger">Close!</button>
                                            </div>

                                        </div>
                                    </div>
                                </div>
                                <td><?php echo status_operations($row['status']); ?><?php if($row['status'] == 1){ echo " on ".date_to_text($row['date_closed']);}?></td>
                               <td><?php
                                    $admin_code = $row['admin'];
                                    $destination_details = $obj_facility->get_admin_detail_by_code($admin_code);
                                    $admin_name = $destination_details['first_name'];
                                    $admin_lname = $destination_details['last_name'];
                                    echo $admin_name . " " . $admin_lname;?></td>
                                <td><?php echo datetime_to_text2($row['date']); ?></td>
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
</body>
</html>
