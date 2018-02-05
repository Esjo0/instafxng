<?php
require_once '../init/initialize_client.php';
$thisPage = "";

if (!$session_client->is_logged_in()) {
    redirect_to("login.php");
}

if (isset($_POST['notify'])) {
    foreach ($_POST as $key => $value) {
        $_POST[$key] = $db_handle->sanitizePost(trim($value));
    }
    extract($_POST);

    $transaction_no = decrypt(str_replace(" ", "+", $transaction_no));
    $transaction_no = preg_replace("/[^A-Za-z0-9 ]/", '', $transaction_no);

    $payment_notified = $education_object->submit_payment_notification($transaction_no, $pay_date, $naira_amount);

    if ($payment_notified) {
        $message_success = <<<MSG
<p>Payment Notification submitted.</p>
<p>Your payment will be confirmed and your order will be treated shortly.</p>
<p>Please note that your order will be completed within 5-30 minutes.</p>
<p>Your order will be completed as soon your payment is received.</p>
MSG;
    } else {
        $message_error = "An error occurred, looks like you have already notified or the transaction does not exist.";
    }

}

?>
<!DOCTYPE html>
<html lang="en">
    <head>
        <base target="_self">
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>Instaforex Nigeria</title>
        <meta name="title" content="" />
        <meta name="keywords" content="">
        <meta name="description" content="">
        <link href="//netdna.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.css" rel="stylesheet">
        <script src="//ajax.googleapis.com/ajax/libs/jquery/2.1.0/jquery.js"></script>
        <link href="layouts/ratings_styles/css/star-rating.css" media="all" rel="stylesheet" type="text/css" />
        <script src="layouts/ratings_styles/js/star-rating.js" type="text/javascript"></script>
        <?php require_once 'layouts/head_meta.php'; ?>
    </head>
    <body>
        <?php require_once 'layouts/header.php'; ?>

        <!-- Main Body: The is the main content area of the web site, contains a side bar  -->
        <div id="main-body" class="container-fluid">
            <div class="row no-gutter">

                <!-- Main Body - Content Area: This is the main content area, unique for each page  -->
                <div id="main-body-content-area" class="col-md-12">

                    <!-- Unique Page Content Starts Here
                    ================================================== -->
                    <?php require_once 'layouts/navigation.php'; ?>

                    <div id="main-container" class="section-tint super-shadow">
                        <div class="row">
                            <div class="col-md-12">

                                <?php require_once 'layouts/feedback_message.php'; ?>

                                <p>Enter your Transaction ID for payment notification.</p>

                                <form data-toggle="validator" class="form-horizontal" role="form" method="post" action="">
                                    <div class="form-group">
                                        <label class="control-label col-sm-3" for="transaction_no">Transaction ID:</label>
                                        <div class="col-sm-9 col-lg-5">
                                            <input name="transaction_no" type="text" class="form-control" id="transaction_no" required>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="control-label col-sm-3" for="pay_date">Payment Date:</label>
                                        <div class="col-sm-9 col-lg-5">
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
                                        <label class="control-label col-sm-3" for="naira_amount">Amount Paid (&#8358;):</label>
                                        <div class="col-sm-9 col-lg-5"><input name="naira_amount" type="text" class="form-control" id="naira_amount" required></div>
                                    </div>
                                    <div class="form-group">
                                        <div class="col-sm-offset-3 col-sm-9"><input name="notify" type="submit" class="btn btn-success" value="Submit" /></div>
                                    </div>
                                </form>
                                <br />


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