<?php
require_once("../init/initialize_admin.php");
if (!$session_admin->is_logged_in()) {
    redirect_to("login.php");
}
//Gets Administrator code
$admin_code = $_SESSION['admin_unique_code'];

$client_operation = new clientOperation();

// SUM OF TRANSACTIONS:
$sum_query = "SELECT SUM(uw.dollar_withdraw) AS sum_dollar_withdraw, SUM(uw.naira_total_withdrawable) AS sum_naira_total_withdrawable
        FROM user_withdrawal AS uw
        INNER JOIN user_ifxaccount AS ui ON uw.ifxaccount_id = ui.ifxaccount_id
        INNER JOIN user AS u ON ui.user_code = u.user_code
        LEFT JOIN user_credential AS uc ON ui.user_code = uc.user_code
        WHERE uw.status = '1' OR uw.status = '2' ORDER BY uw.created DESC ";
$sum_result = $db_handle->runQuery($sum_query);
$sum_data = $db_handle->fetchAssoc($sum_result);
$sum_dollar_withdraw = $sum_data[0]['sum_dollar_withdraw'];
$sum_naira_total_withdrawable = $sum_data[0]['sum_naira_total_withdrawable'];
// END: SUM OF TRANSACTIONS //

$query = "SELECT uw.trans_id, uw.dollar_withdraw, uw.created, uw.naira_total_withdrawable,
        uw.client_phone_password, uw.status AS withdrawal_status,
        CONCAT(u.last_name, SPACE(1), u.first_name) AS full_name, u.phone, u.user_code,
        uc.passport, ui.ifxaccount_id, ui.ifx_acct_no
        FROM user_withdrawal AS uw
        INNER JOIN user_ifxaccount AS ui ON uw.ifxaccount_id = ui.ifxaccount_id
        INNER JOIN user AS u ON ui.user_code = u.user_code
        LEFT JOIN user_credential AS uc ON ui.user_code = uc.user_code
        WHERE uw.status = '1' OR uw.status = '2' ORDER BY uw.created DESC ";
$numrows = $db_handle->numRows($query);

$rowsperpage = 10;

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
$initiated_withdrawal_requests = $db_handle->fetchAssoc($result);

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <base target="_self">
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Instaforex Nigeria | Admin - Initiated Withdrawal</title>
    <meta name="title" content="Instaforex Nigeria | Admin - Initiated Withdrawal" />
    <meta name="keywords" content="" />
    <meta name="description" content="" />
    <?php require_once 'layouts/head_meta.php'; ?>

    <script>
        $(function () {
            $('[data-toggle="popover"]').popover()
        })
    </script>
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
                    <h4><strong>INITIATED WITHDRAWAL</strong></h4>
                </div>
            </div>

            <div class="section-tint super-shadow">
                <div class="row">
                    <div class="col-sm-12">
                        <?php require_once 'layouts/feedback_message.php'; ?>

                        <p>Below is the list of all initiated withdrawal requests.</p>

                        <p class="text-success"><strong>Total Value of Transactions: &dollar; <?php echo number_format($sum_dollar_withdraw, 2, ".", ","); ?> - &#8358; <?php echo number_format($sum_naira_total_withdrawable, 2, ".", ","); ?></strong></p>
                        <hr /><br />

                        <div class="row">

                            <?php if(isset($initiated_withdrawal_requests) && !empty($initiated_withdrawal_requests)){
                                foreach ($initiated_withdrawal_requests as $row) {
                                    ?>
                                    <!-- Order -->
                                    <div class="col-sm-6">
                                        <div class="trans_item">
                                            <div class="trans_item_content">
                                                <div class="row">
                                                    <div class="col-xs-8 trans_item_bio">
                                                        <span id="bio_name"><?php echo $row['full_name']; ?></span>
                                                        <span><?php echo $row['phone']; ?></span>
                                                    </div>
                                                    <div class="col-xs-4 trans_item_bio">
                                                        <?php if($row['withdrawal_status'] == '2') { ?>
                                                            <img src="../images/in-progress.png" alt="" class="img-responsive" title="This transaction is in progress">
                                                        <?php } ?>
                                                        <?php $flag = $client_operation->account_flagged($row['user_code']);
                                                        foreach ($flag as $rowf){?>
                                                            <a tabindex="0" role="button" data-toggle="popover" data-trigger="focus"
                                                               data-content="<?php echo $rowf['comment']; ?>">
                                                                <img src="../images/red-flag.png" alt="" title="The account number associated with this transaction is flagged."></a>
                                                        <?php }?>
                                                    </div>
                                                </div>
                                                <div class="row">
                                                    <div class="col-sm-4 trans_item-thumb">
                                                        <p class="text-center"><a target="_blank" title="View Client Profile" class="btn btn-info" href="client_detail.php?id=<?php echo encrypt($row['user_code']); ?>"><i class="glyphicon glyphicon-eye-open icon-white"></i> </a>

                                                        </p>
                                                        <?php
                                                        if(!empty($row['passport'])) { $file_location = "../userfiles/" . $row['passport']; }

                                                        if(file_exists($file_location)) {
                                                            ?>
                                                            <img src="<?php echo $file_location; ?>" alt="" height="120px" width="120px" />
                                                        <?php } else { ?>
                                                            <img src="../images/placeholder.jpg" alt="" class="img-responsive">
                                                        <?php } unset($file_location); // so that it will not remember for someone without passport ?>
                                                    </div>
                                                    <div class="col-sm-8 ">
                                                        <span id="transaction_identity"><?php echo $row['trans_id']; ?>
                                                            <?php $transaction_issue = $admin_object->get_transaction_issue($row['trans_id']);
                                                            foreach($transaction_issue as $row2) {
                                                                if($row2['status'] == '0'){?>
                                                                    <i type="button" data-target="#details<?php echo $row['trans_id']; ?>" data-toggle="modal" class="fa fa-exclamation-triangle btn btn-default" style="color:red;" aria-hidden="true"></i>
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
                                                                                <div class="col-sm-4"><strong>Created By</strong></div>
                                                                                <div class="col-sm-8"> <?php
                                                                                    $admin_code = $row2['admin'];
                                                                                    $destination_details = $obj_facility->get_admin_detail_by_code($admin_code);
                                                                                    $admin_name = $destination_details['first_name'];
                                                                                    $admin_lname = $destination_details['last_name'];
                                                                                    echo $admin_name . " " . $admin_lname;?></div>
                                                                            </div>

                                                                            <div class="row" style="margin: 15px;">
                                                                                <div class="col-sm-4"><strong>Date Created</strong></div>
                                                                                <div class="col-sm-8"><?php echo datetime_to_text($row2['date']); ?></div>
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
                                                                    <input type="button" name="addcomment"  class="btn btn-warning" onclick="SubmitFormData();" value="Add New Comment"></input>
                                                                    </form>
                                                                    <button type="submit" name="close" onClick="window.close();" data-dismiss="modal" class="btn btn-danger">Close!</button>
                                                                </div>

                                                                </div>
                                                                </div>
                                                                </div>
                                                                <?php }
                                                            }?>
                                                        </span>

                                                        <span><strong>Withdraw:</strong> &dollar; <?php echo $row['dollar_withdraw']; ?> - &#8358; <?php echo number_format($row['naira_total_withdrawable'], 2, ".", ","); ?></span>
                                                        <span><strong>Date: </strong><?php echo datetime_to_text($row['created']); ?></span>
                                                        <span><strong>Account:</strong> <?php echo $row['ifx_acct_no']; ?></span>
                                                        <span><strong>Phone Password:</strong>
                                                            <?php
                                                            $phone_password_encrypted = $row['client_phone_password'];
                                                            $client_phone_password = decrypt($phone_password_encrypted);
                                                            echo trim($client_phone_password);
                                                            ?>
                                                        </span>
                                                        <hr/>
                                                    </div>
                                                </div>
                                                <div class="row" style="margin-top: 5px;">
                                                    <div class="col-xs-8">
                                                        <span class="text-danger" style="text-align: left;">
                                                            <?php if(!is_null($row['created'])) {
                                                                echo datetime_to_text($row['created']) . "<br/>";
                                                                echo time_since($row['created']);
                                                            } ?>
                                                        </span>
                                                    </div>
                                                    <div class="col-xs-4"><span style="text-align: right"><a class="btn btn-info" href="withdraw_process.php?x=initiated&id=<?php echo encrypt($row['trans_id']); ?>"><i class="glyphicon glyphicon-edit icon-white"></i> Process</a></span></div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <!-- close -->
                                <?php } } else { ?>
                                <div class="col-sm-12">
                                    <div class="trans_item">
                                        <div class="trans_item_content">
                                            <div class="row">
                                                <div class="col-sm-12 text-danger"><p><em>There is no result to display</em></p></div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            <?php } ?>

                        </div>
                    </div>
                </div>

                <?php if(isset($initiated_withdrawal_requests) && !empty($initiated_withdrawal_requests)) { ?>
                    <div class="tool-footer text-right">
                        <p class="pull-left">Showing <?php echo $prespagelow . " to " . $prespagehigh . " of " . $numrows; ?> entries</p>
                    </div>
                <?php } ?>
                <?php if(isset($initiated_withdrawal_requests) && !empty($initiated_withdrawal_requests)) { require_once 'layouts/pagination_links.php'; } ?>
            </div>

            <!-- Unique Page Content Ends Here
            ================================================== -->

        </div>

    </div>
</div>
<?php require_once 'layouts/footer.php'; ?>
</body>
</html>