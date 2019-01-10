<?php
require_once("../init/initialize_admin.php");
if (!$session_admin->is_logged_in()) {
    redirect_to("login.php");
}

$client_operation = new clientOperation();

// get the current page or set a default
if (isset($_GET['pg']) && is_numeric($_GET['pg'])) {
    $currentpage = (int) $_GET['pg'];
} else {
    $currentpage = 1;
}

if (isset($_POST['process'])) {
    foreach($_POST as $key => $value) {
        $_POST[$key] = $db_handle->sanitizePost(trim($value));
    }

    extract($_POST);
    $comment = "DEPOSIT FAILED COMMENT: " . $comment;
    $admin_code = $_SESSION['admin_unique_code'];

    $result = $client_operation->deposit_comment($transaction_id, $admin_code, $comment);

    if($result) {
        $message_success = "You have successfully saved your comment";
    } else {
        $message_error = "Looks like something went wrong or you didn't make any change.";
    }
}

$get_params = allowed_get_params(['x']);
$trans_id_encrypted = $get_params['x'];
$trans_id = dec_enc('decrypt',  $trans_id_encrypted);

$query = "SELECT ud.trans_id, ud.dollar_ordered, ud.created, ud.naira_total_payable, ud.real_dollar_equivalent, ud.real_naira_confirmed,
        ud.client_naira_notified, ud.client_pay_date, ud.client_reference, ud.client_pay_method, ud.naira_equivalent_dollar_ordered,
        ud.client_notified_date, ud.points_claimed_id, ud.transfer_reference, ud.status AS deposit_status, u.user_code, ud.updated,
        ui.ifx_acct_no, CONCAT(u.last_name, SPACE(1), u.first_name) AS full_name, u.phone, u.email
        FROM user_deposit AS ud
        INNER JOIN user_ifxaccount AS ui ON ud.ifxaccount_id = ui.ifxaccount_id
        INNER JOIN user AS u ON ui.user_code = u.user_code
        WHERE ud.trans_id = '$trans_id'";
$result = $db_handle->runQuery($query);
$user_detail = $db_handle->fetchAssoc($result);
$user_detail = $user_detail[0];

$user_code = $user_detail['user_code'];

$selected_comment = $client_operation->get_deposit_remark($trans_id);
?>
<!DOCTYPE html>
<html lang="en">
    <head>
        <base target="_self">
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>Instaforex Nigeria | Deposit Failed</title>
        <meta name="title" content="Instaforex Nigeria | Deposit Failed" />
        <meta name="keywords" content="" />
        <meta name="description" content="" />
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
                            <h4><strong>DEPOSIT FAILED DETAILS</strong></h4>
                        </div>
                    </div>
                    
                    <div class="section-tint super-shadow">
                        <div class="row">
                            <div class="col-sm-12">
                                <?php require_once 'layouts/feedback_message.php'; ?>
                                <p><a href='<?php echo "deposit_failed.php?pg={$currentpage}"; ?>'  class="btn btn-default" title="Deposit Failed"><i class="fa fa-arrow-circle-left"></i> Deposit Failed</a></p>
                                
                                <p>View Details</p>
                                
                                <div class="row">
                                    <div class="col-lg-7">
                                        <form data-toggle="validator" class="form-horizontal" role="form" method="post" action="<?php echo $_SERVER['REQUEST_URI']; ?>">
                                            <input type="hidden" name="transaction_id" value="<?php if(isset($trans_id)) { echo $trans_id; } ?>" />
                                            <div class="form-group">
                                                <label class="control-label col-sm-3" for="full_name">Full Name:</label>
                                                <div class="col-sm-9">
                                                    <input type="text" name="full_name" class="form-control" id="full_name" value="<?php if(isset($user_detail['full_name'])) { echo $user_detail['full_name']; } ?>" required disabled/>
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <label class="control-label col-sm-3" for="email_address">Email Address:</label>
                                                <div class="col-sm-9"><input type="text" name="email_address" class="form-control" id="email_address" value="<?php if(isset($user_detail['email'])) { echo $user_detail['email']; } ?>" required disabled/></div>
                                            </div>
                                            <div class="form-group">
                                                <label class="control-label col-sm-3" for="amount_ordered">Amount Ordered:</label>
                                                <div class="col-sm-9"><input type="text" name="amount_ordered" class="form-control" id="amount_ordered" value="&dollar; <?php if(isset($user_detail['dollar_ordered'])) { echo $user_detail['dollar_ordered']; } ?>" required disabled/></div>
                                            </div>

                                            <div class="form-group">
                                                <label class="control-label col-sm-3" for="total_payable">Total Payable:</label>
                                                <div class="col-sm-9"><input type="text" name="total_payable" class="form-control" id="total_payable" value="&#8358; <?php if(isset($user_detail['naira_total_payable'])) { echo $user_detail['naira_total_payable']; } ?>" required disabled/></div>
                                            </div>

                                            <div class="form-group">
                                                <label class="control-label col-sm-3" for="comment">Comment:</label>
                                                <div class="col-sm-9"><textarea name="comment" id="comment" rows="3" class="form-control" required></textarea></div>
                                            </div>
                                            <div class="form-group">
                                                <div class="col-sm-offset-3 col-sm-9">
                                                    <button type="button" data-target="#add-comment-confirm" data-toggle="modal" class="btn btn-success"><i class="fa fa-save fa-fw"></i> Save</button>
                                                </div>
                                            </div>

                                            <!-- Modal - confirmation boxes -->
                                            <div id="add-comment-confirm" tabindex="-1" role="dialog" aria-hidden="true" class="modal fade">
                                                <div class="modal-dialog">
                                                    <div class="modal-content">
                                                        <div class="modal-header">
                                                            <button type="button" data-dismiss="modal" aria-hidden="true"
                                                                class="close">&times;</button>
                                                            <h4 class="modal-title">Deposit Failed Comment</h4>
                                                        </div>
                                                        <div class="modal-body">Are you sure you want to save this information?</div>
                                                        <div class="modal-footer">
                                                            <input name="process" type="submit" class="btn btn-success" value="Save">
                                                            <button type="submit" name="decline" onClick="window.close();" data-dismiss="modal" class="btn btn-danger">Close !</button>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </form>
                                    </div>
                                    
                                    <div class="col-lg-5">
                                        <!-- comment history goes here -->
                                        <h5>Admin Remarks</h5>
                                        <div class="row" style="max-height: 500px !important; overflow: scroll;">
                                            <?php 
                                                if(isset($selected_comment) && !empty($selected_comment)) {
                                                    foreach ($selected_comment as $row) {
                                            ?>
                                            <div class="col-sm-12">
                                                <div class="transaction-remarks">
                                                <span id="trans_remark_author"><?php echo $row['admin_full_name']; ?></span>
                                                <span id="trans_remark_date"><?php echo datetime_to_text($row['created']); ?></span>
                                                <span id="trans_remark"><?php echo $row['comment']; ?></span>
                                                </div>
                                            </div>
                                            <?php } } else { ?>
                                        </div>
                                        <div class="row">
                                            <div class="col-sm-12">
                                                <div class="transaction-remarks">
                                                    <span class="text-danger"><em>There is no remark to display.</em></span>
                                                </div>
                                            </div>
                                        </div>
                                        <?php } ?>
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