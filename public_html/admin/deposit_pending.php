<?php
require_once("../init/initialize_admin.php");
if (!$session_admin->is_logged_in()) {
    redirect_to("login.php");
}

$client_operation = new clientOperation();

$query = "SELECT ud.trans_id, ud.dollar_ordered, ud.created, ud.naira_total_payable,
        ui.ifx_acct_no, CONCAT(u.last_name, SPACE(1), u.first_name) AS full_name, u.phone,
        uc.passport, ui.ifxaccount_id
        FROM user_deposit AS ud
        INNER JOIN user_ifxaccount AS ui ON ud.ifxaccount_id = ui.ifxaccount_id
        INNER JOIN user AS u ON ui.user_code = u.user_code
        LEFT JOIN user_credential AS uc ON ui.user_code = uc.user_code
        WHERE ud.status = '1' ORDER BY ud.user_deposit_id DESC ";
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
$pending_deposit_requests = $db_handle->fetchAssoc($result);

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <base target="_self">
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Instaforex Nigeria | Admin - Pending Deposit</title>
    <meta name="title" content="Instaforex Nigeria | Admin - Pending Deposit" />
    <meta name="keywords" content="" />
    <meta name="description" content="" />
    <?php require_once 'layouts/head_meta.php'; ?>
    <script src="operations_comment.js"></script>
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
                    <h4><strong>PENDING DEPOSIT</strong></h4>
                </div>
            </div>

            <div class="section-tint super-shadow">
                <div class="row">
                    <div class="col-sm-12">
                        <?php require_once 'layouts/feedback_message.php'; ?>

                        <p>Deposit Transactions Initiated by Clients. These transactions have not been notified.</p>

                        <table class="table table-responsive table-striped table-bordered table-hover">
                            <thead>
                            <tr>
                                <th>Transaction ID</th>
                                <th>Client Name</th>
                                <th>IFX Account</th>
                                <th>Phone Number</th>
                                <th>Amount Ordered</th>
                                <th>Total Payable</th>
                                <th>Date Created</th>
                                <th>Action</th>
                            </tr>
                            </thead>
                            <tbody>
                            <?php
                            if(isset($pending_deposit_requests) && !empty($pending_deposit_requests)) {
                                foreach ($pending_deposit_requests as $row) {
                                    ?>
                                    <tr>
                                        <td><?php echo $row['trans_id']; ?></td>
                                        <td><?php echo $row['full_name']; ?></td>
                                        <td><?php echo $row['ifx_acct_no']; ?></td>
                                        <td><?php echo $row['phone']; ?></td>
                                        <td class="nowrap">&dollar; <?php echo number_format($row['dollar_ordered'], 2, ".", ","); ?></td>
                                        <td class="nowrap">&#8358; <?php echo number_format($row['naira_total_payable'], 2, ".", ","); ?></td>
                                        <td><?php echo datetime_to_text($row['created']); ?></td>
                                        <td class="nowrap">
                                            <a class="btn btn-info" href="deposit_pay_notify.php?id=<?php echo encrypt($row['trans_id']); ?>" title="Payment Notification"><i class="fa fa-bell-o" aria-hidden="true"></i></a>
                                            <a class="btn btn-info" href="deposit_process.php?x=pending&id=<?php echo encrypt($row['trans_id']); ?>" title="Comment"><i class="fa fa-comments-o" aria-hidden="true"></i></a>
                                            <?php $transaction_issue = $admin_object->get_transaction_issue($row['trans_id']);
                                            foreach($transaction_issue as $row2) {
                                                if($row2['status'] == '0'){?>
                                                    <i type="button" data-target="#details<?php echo $row['trans_id']; ?>" data-toggle="modal" class="fa fa-exclamation-triangle" style="color:red;" aria-hidden="true"></i>
                                                    <!--Modal - Operations log details-->
                                                    <div id="details<?php echo $row['trans_id']; ?>" tabindex="-1" role="dialog" aria-hidden="true" class="modal fade">
                                                        <div class="modal-dialog modal-lg" style="color: #000;font-weight: normal;">
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
                                                                                <div class="col-sm-8"><?php echo $row['full_name']; ?></div>
                                                                            </div>
                                                                            <div class="row" style="margin: 15px;">
                                                                                <div class="col-sm-4"><strong>Account Number</strong></div>
                                                                                <div class="col-sm-8"><?php echo $row['ifx_acct_no']; ?></div>
                                                                            </div>
                                                                            <div class="row" style="margin: 15px;">
                                                                                <div class="col-sm-4"><strong>Amount Ordered</strong></div>
                                                                                <div class="col-sm-8">&dollar; <?php echo $row['dollar_ordered']; if(!empty( $row['dollar_withdraw'])){echo  $row['dollar_withdraw'];} ?></div>
                                                                            </div>
                                                                            <div class="row" style="margin: 15px;">
                                                                                <div class="col-sm-4"><strong>Transaction Status</strong></div>
                                                                                <div class="col-sm-8"><?php echo status_user_deposit($row['status']); ?></div>
                                                                            </div>

                                                                            <div class="row" style="margin: 15px;">
                                                                                <div class="col-sm-4"><strong>Issue Status</strong></div>
                                                                                <div class="col-sm-8"> <?php echo status_operations($row2['status']); ?><?php if($row2['status'] == 1){ echo " on ".date_to_text($row2['date_closed']);}?></div>
                                                                            </div>

                                                                            <div class="row" style="margin: 15px;">
                                                                                <div class="col-sm-4"><strong>Issue Discription</strong></div>
                                                                                <div class="col-sm-8"> <?php echo $row2['details'];?></div>
                                                                            </div>
                                                                        </div>
                                                                        <div class="col-sm-6">
                                                                            <center><p class="btn btn-default"><strong>COMMENTS</strong></p></center>
                                                                            <div id="results" style="height: 300px; overflow: auto;">
                                                                                <?php
                                                                                $comments_details = $admin_object->get_comment_details( $row['trans_id'] );
                                                                                if(!empty($comments_details)){
                                                                                    foreach($comments_details as $row3) { ?>
                                                                                        <div style="background-color: lightgrey; margin: 15px; border: 1px solid #5f5f5f;">

                                                                                            <div  style="color: #0e90d2"><i><?php
                                                                                                    $admin_code = $row3['admin_code'];
                                                                                                    $destination_details = $obj_facility->get_admin_detail_by_code($admin_code);
                                                                                                    $admin_name = $destination_details['first_name'];
                                                                                                    $admin_lname = $destination_details['last_name'];
                                                                                                    echo $admin_name . " " . $admin_lname;?></i></div><br/>
                                                                                            <div class="row">
                                                                                                <div class="col-sm-2"></div>
                                                                                                <div class="col-sm-8"><?php echo $row3['comment']; ?></div>
                                                                                                <div class="col-sm-2"></div>
                                                                                            </div>
                                                                                            <span class="time-right" style="color: #ff0f1d"><strong>TIME : </strong><?php echo datetime_to_text($row3['created']); ?></span>
                                                                                        </div>
                                                                                    <?php }} else{ ?> <img class="img-responsive" src="../images/No-Comments.png" /> <?php } ?>
                                                                            </div>
                                                                            <form id="myForm" method="post" data-toggle="validator" class="form-vertical" role="form" enctype="multipart/form-data">
                                                                                <input id="admin" name="admin" type="hidden" value="<?php echo $admin_code;?>" required>
                                                                                <input id="trans_id" name="trans_id" type="hidden" value="<?php echo $row['trans_id'];?>" required>
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
                                                                    <input id="submitt" name="addcomment"  class="btn btn-warning" onclick="SubmitFormData();" value="Add New Comment">
                                                                    </form>
                                                                    <button type="submit" name="close" onClick="window.close();" data-dismiss="modal" class="btn btn-danger">Close!</button>
                                                                </div>

                                                            </div>
                                                        </div>
                                                    </div>
                                                <?php }
                                            }?>
                                        </td>
                                    </tr>
                                <?php } } else { echo "<tr><td colspan='7' class='text-danger'><em>No results to display</em></td></tr>"; } ?>
                            </tbody>
                        </table>

                        <?php if(isset($pending_deposit_requests) && !empty($pending_deposit_requests)) { ?>
                            <div class="tool-footer text-right">
                                <p class="pull-left">Showing <?php echo $prespagelow . " to " . $prespagehigh . " of " . $numrows; ?> entries</p>
                            </div>
                        <?php } ?>

                    </div>
                </div>

                <?php if(isset($pending_deposit_requests) && !empty($pending_deposit_requests)) { require_once 'layouts/pagination_links.php'; } ?>
            </div>

            <!-- Unique Page Content Ends Here
            ================================================== -->

        </div>

    </div>
</div>
<?php require_once 'layouts/footer.php'; ?>
</body>
</html>