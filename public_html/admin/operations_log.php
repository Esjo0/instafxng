<?php
require_once("../init/initialize_admin.php");
if (!$session_admin->is_logged_in()) {
    redirect_to("login.php");
}

//Gets Administrator code
$admin_code = $_SESSION['admin_unique_code'];

// resolved issues
if(isset($_POST['resolved'])){
    $transaction_id = $db_handle->sanitizePost(trim($_POST['transaction_id']));
    $comment = $db_handle->sanitizePost(trim($_POST['comment']));
    $date_closed = date("Y-m-d h:i:sa");
    $add_comment = $admin_object->add_operations_comment($transaction_id, $comment ,$admin_code);
    $query = "UPDATE operations_log SET status = '1' , date_closed = '$date_closed' WHERE transaction_id = '$transaction_id' ";
    $result2 =$db_handle->runQuery($query);
    if($result2 && ($add_comment == true)) {
        $message_success = "Transaction issues resolved";
    } else {
        $message_error = "Something went wrong. Please try again.";
    }
}

//Add comment to issues
if(isset($_POST['addcomment'])){

    $transaction_id = $db_handle->sanitizePost(trim($_POST['transaction_id']));
    $comment = $db_handle->sanitizePost(trim($_POST['comment']));
    if(empty($comment)){
        $message_error = "The comment section was empty. Kindly type a comment.";
    }else{
    $add_comment = $admin_object->add_operations_comment($transaction_id, $comment ,$admin_code);
    if($add_comment = true) {
        $message_success = "You have successfully added a new comment";
    } else {
        $message_error = "Something went wrong. Please try again.";
    }
    }
}

// add new operations record
if (isset($_POST['add'])){



    $transaction_id = $db_handle->sanitizePost(trim($_POST['transaction_id']));

    $query = "SELECT transaction_id FROM operations_log WHERE transaction_id = '$transaction_id'";
    $numrows = $db_handle->numRows($query);
    if($numrows == 0){
        $details = $db_handle->sanitizePost(trim($_POST['details']));
        $add = $admin_object->add_issues($transaction_id,$details,$admin_code);
        if($add = true) {
            $message_success = "You have successfully added a new record";
        } else {
            $message_error = "Something went wrong. Please try again.";
        }

    }elseif($numrows == 1){
        $details = $db_handle->sanitizePost(trim($_POST['details']));
        $query = "SELECT details FROM operations_log WHERE transaction_id = '$transaction_id' LIMIT 1";
        $result = $db_handle->runQuery($query);
        $old_details = $db_handle->fetchAssoc($result);
        foreach ($old_details AS $rowd){$old_details = $rowd['details'];}
        $date = date("D M d, Y G:i");
        $new_details = $old_details."</br><hr/></br>Re-Opened On".$date."<br/><strong> >> </strong>".$details;
        $query = "UPDATE operations_log SET status = '0', details = '$new_details' WHERE transaction_id = '$transaction_id'";
        $result = $db_handle->runQuery($query);
        if($result == true){
            $message_success = "You have reopened this issue";
        } else {
            $message_error = "Something went wrong. Please try again.";
        }
    }
}

//select ooperations deposit log
$query = "SELECT * FROM operations_log WHERE status = '0' ORDER BY date DESC ";
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
            </div>
                <?php require_once 'layouts/feedback_message.php'; ?>
            <div class="row">
                <div class="col-lg-12">

                    <div class="section-tint super-shadow">
                        <div class="row">
                            <div class="col-sm-12 container">
                                <a href="operations_log_archive.php"  class="btn btn-default" title="View Closed Logs HERE"><i class="fa fa-arrow-circle-left"></i>View Closed Records</a>
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
                                <div class="clearfix"><p><hr/></p></div>
                                </div>
                            <div class="clearfix"></div>
                                        <table class="table table-responsive table-striped table-bordered table-hover">
                                            <thead>
                                            <tr>
                                                <th>Transaction ID</th>
                                                <th>Clients Name</th>
                                                <th>Account No</th>
                                                <th>Created by</th>
                                                <th>Status</th>
                                                <th>Details/Updates</th>
                                                <th>Date Created</th>
                                            </tr>
                                            </thead>
                                            <tbody>
                                            <?php if(isset($logs) && !empty($logs)) {
                                                foreach ($logs as $row) { ?>
                                                <tr>
                                                    <td><?php echo $row['transaction_id']; ?></td>
                                                    <?php
                                                    $transaction_details = $admin_object->get_transaction_details($row['transaction_id']);
                                                    foreach($transaction_details as $row2) {?>
                                                    <td><?php echo $row2['full_name']; ?></td>
                                                    <td><?php echo $row2['ifx_acct_no']; ?></td>
                                                    <td><?php
                                                        $admin_code = $row['admin'];
                                                        $destination_details = $obj_facility->get_admin_detail_by_code($admin_code);
                                                        $admin_name = $destination_details['first_name'];
                                                        $admin_lname = $destination_details['last_name'];
                                                        echo $admin_name . " " . $admin_lname;?></td>
                                                    <td><?php echo status_operations($row['status']); ?></td>
                                                    <td><button type="button" data-target="#details<?php echo $row['transaction_id']; ?>" data-toggle="modal" class="btn btn-default">View Details</button>
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
                                                                                    <div class="col-sm-8">&dollar; <?php echo $row2['dollar_ordered']; if(!empty( $row2['dollar_withdraw'])){echo  $row2['dollar_withdraw'];} ?></div>
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
                                                                            <form id="comment form" data-toggle="validator" class="form-vertical" role="form" method="post" action="" enctype="multipart/form-data">
                                                                                <input name="transaction_id" type="hidden" value="<?php echo $row['transaction_id'];?>" required>
                                                                            <div class="form-group row">
                                                                                <div class="col-sm-12">
                                                                                    <textarea id="comment" name="comment" class="form-control" rows="2" placeholder="Enter Detailed Description of Clients issue" required></textarea>
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                                <div class="modal-footer">
                                                                    <input name="resolved" type="submit" class="btn btn-success" value="Close Issue">
                                                                    <input name="addcomment" type="submit" class="btn btn-warning" value="Add New Comment">
                                                                    </form>
                                                                    <button type="submit" name="close" onClick="window.close();" data-dismiss="modal" class="btn btn-danger">Close!</button>
                                                                </div>

                                                            </div>
                                                        </div>
                                                    </div>
                                                    </td>
                                                    <td><?php echo datetime_to_text2($row['date']); ?></td>
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