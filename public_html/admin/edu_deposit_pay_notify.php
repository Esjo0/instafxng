<?php
require_once("../init/initialize_admin.php");
if (!$session_admin->is_logged_in()) {
    redirect_to("login.php");
}

$get_params = allowed_get_params(['id', 'x']);
$trans_id_encrypted = $get_params['id'];
$trans_id = decrypt(str_replace(" ", "+", $trans_id_encrypted));
$trans_id = preg_replace("/[^A-Za-z0-9 ]/", '', $trans_id);

switch ($get_params['x']) {
    case 'initiated':
        $deposit_process_initiated = true;
        $page_title = '- INITIATED';
        $return_page = 'edu_deposit_initiated.php';
        break;
    case 'notified':
        $deposit_process_notified = true;
        $page_title = '- NOTIFIED';
        $return_page = 'edu_deposit_notified.php';
        break;
    default:
        $no_valid_page = true;
        break;
}

if (isset($_POST['edu_deposit_notify'])) {
    foreach ($_POST as $key => $value) {
        $_POST[$key] = $db_handle->sanitizePost(trim($value));
    }
    extract($_POST);

    $transaction_no = decrypt(str_replace(" ", "+", $transaction_no));
    $transaction_no = preg_replace("/[^A-Za-z0-9 ]/", '', $transaction_no);

    $payment_notified = $education_object->submit_payment_notification($transaction_no, $pay_date, $naira_amount, $admin_comment, $_SESSION['admin_unique_code']);

    if ($payment_notified) {
        $message_success = "You have successfully submitted deposit notification.";
    } else {
        $message_error = "An error occurred, looks like there was a problem";
    }

}

$trans_detail = $education_object->get_edu_deposit_by_id($trans_id);

if (empty($trans_detail)) {
    redirect_to("./");
    exit;
} else {
    $total_payable = $trans_detail['amount'] + $trans_detail['stamp_duty'] + $trans_detail['gateway_charge'];
    $trans_remark = $education_object->get_edu_deposit_remark($trans_id);
}


?>
<!DOCTYPE html>
<html lang="en">
    <head>
        <base target="_self">
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>Instaforex Nigeria | Deposit - Education Payment Notification</title>
        <meta name="title" content="Instaforex Nigeria | Admin - Education Payment Notification" />
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
                            <h4><strong>EDUCATION - PAYMENT NOTIFICATION</strong></h4>
                        </div>
                    </div>
                    
                    <div class="section-tint super-shadow">
                        <div class="row">
                            <div class="col-sm-12">
                                <?php require_once 'layouts/feedback_message.php'; ?>

                                <p><a href="<?php if (isset($return_page)) { echo $return_page; } ?>" class="btn btn-default" title="Go back to Education Deposits"><i class="fa fa-arrow-circle-left"></i> Go Back - Education Deposits</a></p>

                                <p>Fill payment notification for the selected transaction.</p>

                                <div class="row">

                                    <div class="col-sm-7">
                                        <form data-toggle="validator" class="form-horizontal" role="form" method="post" action="">

                                            <input name="transaction_no" type="hidden" value="<?php if (isset($trans_id)) { echo encrypt($trans_id); } ?>">

                                            <div class="form-group">
                                                <label class="control-label col-sm-4" for="transaction_id">Transaction ID:</label>
                                                <div class="col-sm-8">
                                                    <input name="transaction_id" type="text" class="form-control" id="transaction_id" value="<?php if (isset($trans_id)) { echo $trans_id; } ?>" readonly="readonly">
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <label class="control-label col-sm-4" for="course_name">Course:</label>
                                                <div class="col-sm-8">
                                                    <input name="course_name" type="text" class="form-control" id="course_name" value="<?php if (isset($trans_detail['title'])) { echo $trans_detail['title']; } ?>" readonly="readonly">
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <label class="control-label col-sm-4" for="client_name">Client Name:</label>
                                                <div class="col-sm-8">
                                                    <input name="client_name" type="text" class="form-control" id="client_name" value="<?php if (isset($trans_detail['client_full_name'])) { echo $trans_detail['client_full_name']; } ?>" readonly="readonly">
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <label class="control-label col-sm-4" for="client_email">Email Address:</label>
                                                <div class="col-sm-8">
                                                    <input name="client_email" type="text" class="form-control" id="client_email" value="<?php if (isset($trans_detail['email'])) { echo $trans_detail['email']; } ?>" readonly="readonly">
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <label class="control-label col-sm-4" for="camount">Course Cost
                                                    (&#8358;):</label>
                                                <div class="col-sm-8">
                                                    <input name="camount" type="text" class="form-control" id="camount" value="<?php if (isset($trans_detail['course_cost'])) { echo number_format($trans_detail['course_cost'], 2, ".", ","); } ?>" readonly="readonly">
                                                </div>
                                            </div>
                                            <hr/>
                                            <div class="form-group">
                                                <label class="control-label col-sm-4" for="total_payable">Total Payable:</label>
                                                <div class="col-sm-8">
                                                    <input name="total_payable" type="hidden" class="form-control" id="total_payable" value="<?php if (isset($total_payable)) { echo number_format($total_payable, 2, ".", ","); } ?>" readonly="readonly">
                                                    <p style="font-size: 1.6em; padding: 0; color: green;"><strong> &#8358; <?php if (isset($total_payable)) { echo number_format($total_payable, 2, ".", ","); } ?></strong></p>
                                                </div>
                                            </div>

                                            <div class="form-group">
                                                <label class="control-label col-sm-4" for="pay_date">Payment Date:</label>
                                                <div class="col-sm-8 col-lg-5">
                                                    <div class='input-group date' id='datetimepicker'>
                                                        <input name="pay_date" type="text" class="form-control" id='datetimepicker2' required>
                                                        <span class="input-group-addon"><span class="glyphicon glyphicon-calendar"></span></span>
                                                    </div>
                                                    <span class="help-block">Format: (YYYY-MM-DD) e.g. 2017-12-25</span>
                                                </div>
                                                <script type="text/javascript">
                                                    $(function () {
                                                        $('#datetimepicker').datetimepicker({
                                                            format: 'YYYY-MM-DD'
                                                        });
                                                        $('#datetimepicker2').datetimepicker({
                                                            format: 'YYYY-MM-DD'
                                                        });
                                                    });
                                                </script>
                                            </div>
                                            <div class="form-group">
                                                <label class="control-label col-sm-4" for="naira_amount">Amount Paid (&#8358;):</label>
                                                <div class="col-sm-8 col-lg-5"><input name="naira_amount" type="text" class="form-control" id="naira_amount" required></div>
                                            </div>

                                            <div class="form-group">
                                                <label class="control-label col-sm-4" for="admin_comment">Comment:</label>
                                                <div class="col-sm-8"><textarea name="admin_comment" class="form-control" rows="7" id="admin_comment"></textarea></div>
                                            </div>

                                            <div class="form-group">
                                                <div class="col-sm-offset-4 col-sm-8"><input name="edu_deposit_notify" type="submit" class="btn btn-success" value="Submit Notification"/></div>
                                            </div>
                                        </form>
                                    </div>

                                    <div class="col-sm-5">
                                        <h5>Admin Remarks</h5>
                                        <div style="max-height: 550px; overflow: scroll;">
                                            <?php
                                            if (isset($trans_remark) && !empty($trans_remark)) {
                                                foreach ($trans_remark as $row) {
                                                    ?>
                                                    <div class="row">
                                                        <div class="col-sm-12">
                                                            <div class="transaction-remarks">
                                                                <span id="trans_remark_author"><?php echo $row['admin_full_name']; ?></span>
                                                                <span id="trans_remark"><?php echo $row['comment']; ?></span>
                                                                <span id="trans_remark_date"><?php echo datetime_to_text($row['created']); ?></span>
                                                            </div>
                                                        </div>
                                                    </div>
                                                <?php }
                                            } else { ?>
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
                    </div>

                    <!-- Unique Page Content Ends Here
                    ================================================== -->
                    
                </div>
                
            </div>
        </div>
        <?php require_once 'layouts/footer.php'; ?>
        <script src="//cdnjs.cloudflare.com/ajax/libs/moment.js/2.9.0/moment-with-locales.js"></script>
        <script src="//cdn.rawgit.com/Eonasdan/bootstrap-datetimepicker/e8bddc60e73c1ec2475f827be36e1957af72e2ea/src/js/bootstrap-datetimepicker.js"></script>
    </body>
</html>