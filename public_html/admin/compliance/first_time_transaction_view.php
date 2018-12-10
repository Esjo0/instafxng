<?php
require_once("../../init/initialize_admin.php");
if (!$session_admin->is_logged_in()) {
    redirect_to("login.php");
}

$client_operation = new clientOperation();

$get_params = allowed_get_params(['id']);
$trans_id_encrypted = $get_params['id'];

$trans_id = dec_enc('decrypt', $trans_id_encrypted);
$trans_id_detail = $client_operation->get_deposit_transaction($trans_id);

if(!$trans_id_detail) {
    redirect_to("first_time_transaction_initiated.php");
}

if (isset($_POST['process'])) {

    foreach($_POST as $key => $value) {
        $_POST[$key] = $db_handle->sanitizePost(trim($value));
    }

    extract($_POST);

    $trans_id = decrypt_ssl(str_replace(" ", "+", $trans_id));
    $trans_id = preg_replace("/[^A-Za-z0-9 ]/", '', $trans_id);
    $admin_full_name = $_SESSION['admin_last_name'] . " " . $_SESSION['admin_first_name'] ;
    $remarks = $admin_full_name . ": " . $remarks;

    $query = "UPDATE user_first_transaction SET status = '$status', comment = '$remarks' WHERE trans_id = '$trans_id' LIMIT 1";
    $db_handle->runQuery($query);

    if($db_handle->affectedRows() == 1) {
        $message_success = "You have successfully updated the Transaction ID: $trans_id";
    } else {
        $message_error = "An error occurred.";
    }

    redirect_to("first_time_transaction_initiated.php");
}

?>
<!DOCTYPE html>
<html lang="en">
    <head>
        <base target="_self">
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>Instaforex Nigeria | Admin - Client First Transaction</title>
        <meta name="title" content="Instaforex Nigeria | Admin - Client First Transaction" />
        <meta name="keywords" content="" />
        <meta name="description" content="" />
        <?php require_once '../layouts/head_meta.php'; ?>
    </head>
    <body>
        <?php require_once '../layouts/header.php'; ?>
        <!-- Main Body: The is the main content area of the web site, contains a side bar  -->
        <div id="main-body" class="container-fluid">
            <div class="row no-gutter">
                <!-- Main Body - Side Bar  -->
                <div id="main-body-side-bar" class="col-md-4 col-lg-3 left-nav">
                <?php require_once '../layouts/sidebar.php'; ?>
                </div>
                
                <!-- Main Body - Content Area: This is the main content area, unique for each page  -->
                <div id="main-body-content-area" class="col-md-8 col-lg-9">
                    
                    <!-- Unique Page Content Starts Here
                    ================================================== -->
                    <div class="row">
                        <div class="col-sm-12 text-danger">
                            <h4><strong>MODIFY FIRST TIME TRANSACTION</strong></h4>
                        </div>
                    </div>

                    <div class="section-tint super-shadow">
                        <div class="row">
                            <div class="col-sm-12">
                                <p><a href="compliance/first_time_transaction_initiated.php" class="btn btn-default" title="Go back to First Time Transactions"><i class="fa fa-arrow-circle-left"></i> Go Back - First Time Transactions</a></p>

                                <?php require_once '../layouts/feedback_message.php'; ?>
                                <p>Modify the selected transaction, email already sent to client.</p>

                                <form data-toggle="validator" class="form-horizontal" role="form" method="post" action="">
                                    <input name="trans_id" type="hidden" value="<?php echo encrypt_ssl($trans_id); ?>">
                                    <div class="form-group">
                                        <label class="control-label col-sm-3" for="full_name">Full Name:</label>
                                        <div class="col-sm-9 col-lg-5">
                                            <input type="text" class="form-control" id="full_name" name="full_name" value="<?php echo $trans_id_detail['full_name']; ?>" disabled>
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <label class="control-label col-sm-3" for="email_address">Email Address:</label>
                                        <div class="col-sm-9 col-lg-5">
                                            <input type="text" class="form-control" id="email_address" name="email_address" value="<?php echo $trans_id_detail['email']; ?>" readonly>
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <label class="control-label col-sm-3" for="phone_number">Phone Number:</label>
                                        <div class="col-sm-9 col-lg-5">
                                            <input type="text" class="form-control" id="phone_number" name="phone_number" value="<?php echo $trans_id_detail['phone']; ?>" readonly>
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <label class="control-label col-sm-3" for="amount_ordered">Amount Ordered:</label>
                                        <div class="col-sm-9 col-lg-5">
                                            <input type="text" class="form-control" id="amount_ordered" name="amount_ordered" value="&dollar; <?php echo $trans_id_detail['dollar_ordered']; ?>" readonly>
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <label class="control-label col-sm-3" for="status">Status:</label>
                                        <div class="col-sm-9 col-lg-5">
                                            <div class="radio">
                                                <label><input type="radio" name="status" value="2" required>Approve</label>
                                            </div>
                                            <div class="radio">
                                                <label><input type="radio" name="status" value="3" required>Decline</label>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <label class="control-label col-sm-3" for="remarks">Your Remark:</label>
                                        <div class="col-sm-9 col-lg-5"><textarea name="remarks" id="message" rows="3" class="form-control" placeholder="Enter your remark" required></textarea></div>
                                    </div>

                                    <div class="form-group">
                                        <div class="col-sm-offset-3 col-sm-9">
                                            <button type="button" data-target="#confirm-modify-first-transaction" data-toggle="modal" class="btn btn-success">Save</button>
                                        </div>
                                    </div>

                                    <!--Modal - confirmation boxes-->
                                    <div id="confirm-modify-first-transaction" tabindex="-1" role="dialog" aria-hidden="true" class="modal fade">
                                        <div class="modal-dialog">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <button type="button" data-dismiss="modal" aria-hidden="true"
                                                            class="close">&times;</button>
                                                    <h4 class="modal-title">Modify First Time Transaction</h4></div>
                                                <div class="modal-body">Are you sure you want to modify the first time transaction?</div>
                                                <div class="modal-footer">
                                                    <input name="process" type="submit" class="btn btn-success" value="Proceed">
                                                    <button type="submit" name="close" data-dismiss="modal" class="btn btn-danger">Close!</button>
                                                </div>
                                            </div>
                                        </div>
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
        <?php require_once '../layouts/footer.php'; ?>
    </body>
</html>