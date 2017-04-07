<?php
require_once("../init/initialize_admin.php");
if (!$session_admin->is_logged_in()) {
    redirect_to("login.php");
}

$get_params = allowed_get_params(['id']);

$trans_id_encrypted = $get_params['id'];
$trans_id = decrypt(str_replace(" ", "+", $trans_id_encrypted));
$trans_id = preg_replace("/[^A-Za-z0-9 ]/", '', $trans_id);

$client_operation = new clientOperation();
$transaction_detail = $client_operation->get_deposit_transaction($trans_id);
    
if($transaction_detail) {

    if($transaction_detail['status'] == 1) {
        extract($transaction_detail);
        $expired = $client_operation->is_deposit_order_expired($created);
        if($expired) {
            $message_error = "The transaction ID provided has expired, you cannot submit payment notification of orders "
                    . "submitted over " . DEPOSIT_EXPIRE_HOUR . " hours.";
        }
        $allow_payment_notification = true;
    } else {
        $message_error = "The payment notification for this transaction already exist. Please confirm the status with our <a href=\"contact_info.php\">support department</a>. Thank you.";
    }
} else {
    $message_error = "Transaction ID does not exist in our record. Please confirm you have the correct transaction ID or contact our <a href=\"contact_info.php\">support department</a>.";
}

if (isset($_POST['deposit_pay_notify'])) {
    foreach ($_POST as $key => $value) {
        $_POST[$key] = $db_handle->sanitizePost(trim($value));
    }
    extract($_POST);
    $naira_amount = str_replace(",", "", $naira_amount);

    $client_operation = new clientOperation();
    $notification = $client_operation->user_payment_notification($trans_id, $pay_method, $pay_date, $teller_no, $naira_amount, $comment);
    if($notification) {
        $payment_notification_completed = true;
    } else {
        $message_error = "Your notification did not go through. Please try again.";
    }
}

?>
<!DOCTYPE html>
<html lang="en">
    <head>
        <base target="_self">
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>Instaforex Nigeria | Deposit - Payment Notification</title>
        <meta name="title" content="Instaforex Nigeria | Admin - Pending Deposit" />
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
                            <h4><strong>PAYMENT NOTIFICATION</strong></h4>
                        </div>
                    </div>
                    
                    <div class="section-tint super-shadow">
                        <div class="row">
                            <div class="col-sm-12">
                                <?php require_once 'layouts/feedback_message.php'; ?>
                                <p><a href="deposit_pending.php" class="btn btn-default" title="Pending Deposits"><i class="fa fa-arrow-circle-left"></i> Pending Deposits</a></p>
                                
                                
                                <?php if($allow_payment_notification && !$payment_notification_completed) { ?>
                                <p>You can submit payment notification of the order detail displayed below.</p>
                                <form data-toggle="validator" class="form-horizontal" role="form" method="post" action="<?php echo $_SERVER['REQUEST_URI']; ?>">
                                    <input name="trans_id" type="hidden" value="<?php if(isset($trans_id)) { echo $trans_id; } ?>">
                                    <div class="form-group">
                                        <label class="control-label col-sm-3" for="full_name">Full Name:</label>
                                        <div class="col-sm-9 col-lg-5"><input name="full_name" type="text" value="<?php if(isset($full_name)) { echo $full_name; } ?>" class="form-control" id="full_name" disabled></div>
                                    </div>
                                    <div class="form-group">
                                        <label class="control-label col-sm-3" for="phone_number">Phone Number:</label>
                                        <div class="col-sm-9 col-lg-5"><input name="phone_number" type="text" value="<?php if(isset($phone)) { echo $phone; } ?>" class="form-control" id="phone_number" disabled></div>
                                    </div>
                                    <div class="form-group">
                                        <label class="control-label col-sm-3" for="email">Email Address:</label>
                                        <div class="col-sm-9 col-lg-5"><input name="email" type="text" value="<?php if(isset($email)) { echo $email; } ?>" class="form-control" id="email" disabled></div>
                                    </div>
                                    <div class="form-group">
                                        <label class="control-label col-sm-3" for="ifx_account">IFX Account Number:</label>
                                        <div class="col-sm-9 col-lg-5"><input name="ifx_account" type="text" value="<?php if(isset($ifx_acct_no)) { echo $ifx_acct_no; } ?>" class="form-control" id="ifx_account" disabled></div>
                                    </div>
                                    <div class="form-group">
                                        <label class="control-label col-sm-3" for="dollar_ordered">Amount Ordered (&dollar;):</label>
                                        <div class="col-sm-9 col-lg-5"><input name="dollar_ordered" type="text" value="<?php if(isset($dollar_ordered)) { echo $dollar_ordered; } ?>" class="form-control" id="dollar_ordered" disabled></div>
                                    </div>
                                    <div class="form-group">
                                        <label class="control-label col-sm-3" for="total_payable">Total Payable (&#8358;):</label>
                                        <div class="col-sm-9 col-lg-5"><input name="total_payable" type="text" value="<?php if(isset($naira_total_payable)) { echo number_format($naira_total_payable, 2, ".", ","); } ?>" class="form-control" id="dollar_ordered" disabled></div>
                                    </div>
                                    <div class="form-group">
                                        <label class="control-label col-sm-3" for="order_date">Order Date:</label>
                                        <div class="col-sm-9 col-lg-5"><input name="order_date" type="text" value="<?php if(isset($created)) { echo datetime_to_text($created) . " (" . time_since($created) . ")"; } ?>" class="form-control" id="dollar_ordered" disabled></div>
                                    </div>

                                    <?php if(!$expired) { // allow to notify if transaction has not expired ?>
                                    <div class="form-group">
                                        <label class="control-label col-sm-3" for="pay_method">Payment Method:</label>
                                        <div class="col-sm-9 col-lg-5">
                                            <select name="pay_method" class="form-control" id="pay_method" required>
                                                <option value="">---Select Type---</option>
                                                <option value="2">Internet Transfer</option>
                                                <option value="3">ATM Transfer</option>
                                                <option value="4">Bank Transfer</option>
                                                <option value="5">Mobile Money Transfer</option>
                                                <option value="6">Cash Deposit</option>
                                                <option value="7">Office Funding</option>
                                                <option value="8">- Not Listed -</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="control-label col-sm-3" for="pay_date">Payment Date:</label>
                                        <div class="col-sm-9 col-lg-5">
                                            <div class='input-group date' id='datetimepicker'>
                                                <input name="pay_date" type="text" class="form-control" id='datetimepicker2' required>
                                                <span class="input-group-addon"><span class="glyphicon glyphicon-calendar"></span></span>
                                            </div>
                                            <span class="help-block">Format: (YYYY-MM-DD) e.g. 2015-12-25</span>
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
                                        <label class="control-label col-sm-3" for="teller_no">Teller / Reference Number:</label>
                                        <div class="col-sm-9 col-lg-5"><input name="teller_no" type="text" class="form-control" id="teller_no"></div>
                                    </div>
                                    <div class="form-group">
                                        <label class="control-label col-sm-3" for="naira_amount">Amount Paid in Naira (&#8358;):</label>
                                        <div class="col-sm-9 col-lg-5"><input name="naira_amount" type="text" class="form-control" id="naira_amount" required></div>
                                    </div>
                                    <div class="form-group">
                                        <div class="col-sm-offset-3 col-sm-9"><input name="deposit_pay_notify" type="submit" class="btn btn-success" value="Submit" /></div>
                                    </div>
                                    <?php } // END: if(!$expired) { ?>
                                </form>
                                <?php } // END: if($allow_payment_notification) { ?>
                                
                                <?php if($payment_notification_completed) { ?>

                                <div class="alert alert-success">
                                    <strong>Success!</strong> Payment Notification submitted. Go to <a href="deposit_notified.php" title="Notified Deposits">Notified Deposits</a> for payment confirmation.
                                </div>
                                
                                <br />
                                <br />
                                
                                <?php } ?>
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