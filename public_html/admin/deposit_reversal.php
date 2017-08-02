<?php
require_once("../init/initialize_admin.php");
if (!$session_admin->is_logged_in()) {
    redirect_to("login.php");
}

if (isset($_POST['deposit_reversal'])) {
    $trans_id = $db_handle->sanitizePost($_POST['transaction_no']);

    $query = "SELECT trans_id, status FROM user_deposit WHERE trans_id = '$trans_id' LIMIT 1";
    $result = $db_handle->runQuery($query);

    if($db_handle->numOfRows($result) == 1) {
        $trans_detail = $db_handle->fetchAssoc($result);
        $trans_detail = $trans_detail[0];

        if($trans_detail['status'] == '5' || $trans_detail['status'] == '6') {
            $query = "UPDATE user_deposit SET status = '2' WHERE trans_id = '$trans_id' LIMIT 1";
            $result = $db_handle->runQuery($query);

            if($db_handle->affectedRows() == 1) {
                $message_success = "The transaction has been successfully reversed.";
            } else {
                $message_error = "An error occurred, the transaction was not reversed.";
            }
        } else {
            $message_error = "Deposit reversal is only possible with transactions with status 'Deposit Confirmed', please try again.";
        }

    } else {
        $message_error = "The Transaction ID you supplied does not exist, kindly confirm the transaction and try again.";
    }
}

?>
<!DOCTYPE html>
<html lang="en">
    <head>
        <base target="_self">
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>Instaforex Nigeria | Admin - Deposit Reversal</title>
        <meta name="title" content="Instaforex Nigeria | Admin - Deposit Reversal" />
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
                            <h4><strong>DEPOSIT REVERSAL</strong></h4>
                        </div>
                    </div>

                    <div class="section-tint super-shadow">
                        <div class="row">
                            <div class="col-sm-12">
                                <?php require_once 'layouts/feedback_message.php'; ?>

                                <p>You can reverse a deposit transaction that has the status "Deposit Confirmed" back to "Deposit Notified".
                                Please note that this reversal only works when a transaction has "Deposit Confirmed" status and only
                                reverses to "Deposit Notified"</p>

                                <ul class="fa-ul">
                                    <li><i class="fa-li fa fa-check-square-o icon-tune"></i>Enter a Transaction ID</li>
                                </ul>

                                <form class="form-horizontal" role="form" method="post" action="<?php echo $_SERVER['REQUEST_URI']; ?>">
                                    <div class="form-group">
                                        <label class="control-label col-sm-3" for="transaction_no">Transaction ID:</label>
                                        <div class="col-sm-9 col-lg-5">
                                            <input name="transaction_no" type="text" class="form-control" id="transaction_no">
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <div class="col-sm-offset-3 col-sm-9"><input name="deposit_reversal" type="submit" class="btn btn-success" value="Reverse Transaction" /></div>
                                    </div>
                                </form>
                                
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