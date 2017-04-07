<?php
require_once("../init/initialize_admin.php");

if (!$session_admin->is_logged_in()) {
    redirect_to("login.php");
}

if (isset($_POST['edu_deposit_process'])) {
    foreach ($_POST as $key => $value) {
        $_POST[$key] = $db_handle->sanitizePost(trim($value));
    }
    extract($_POST);

    $transaction_no = decrypt(str_replace(" ", "+", $transaction_no));
    $transaction_no = preg_replace("/[^A-Za-z0-9 ]/", '', $transaction_no);

    $course_no = decrypt(str_replace(" ", "+", $course_no));
    $course_no = preg_replace("/[^A-Za-z0-9 ]/", '', $course_no);

    $user_no = decrypt(str_replace(" ", "+", $user_no));
    $user_no = preg_replace("/[^A-Za-z0-9 ]/", '', $user_no);

    $update_deposit = $education_object->modify_edu_deposit_order($transaction_no, $course_no, $user_no, $deposit_status, $admin_comment, $_SESSION['admin_unique_code']);

    if($update_deposit) {
        $message_success = "You have successfully updated this deposit.";
    } else {
        $message_error = "An error occurred, looks like there was a problem";
    }
}

$get_params = allowed_get_params(['id']);
$trans_id_encrypted = $get_params['id'];
$trans_id = decrypt(str_replace(" ", "+", $trans_id_encrypted));
$trans_id = preg_replace("/[^A-Za-z0-9 ]/", '', $trans_id);

$trans_detail = $education_object->get_edu_deposit_by_id($trans_id);

if(empty($trans_detail)) {
    redirect_to("edu_deposit.php");
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
        <title>Instaforex Nigeria | Admin - Process Education Deposits</title>
        <meta name="title" content="Instaforex Nigeria | Admin - Process Education Deposits" />
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
                            <h4><strong>PROCESS DEPOSITS - EDUCATION</strong></h4>
                        </div>
                    </div>
                    
                    <div class="section-tint super-shadow">
                        <div class="row">
                            <div class="col-sm-12">
                                <p><a href="edu_deposit.php" class="btn btn-default" title="Go back to Education Deposits"><i class="fa fa-arrow-circle-left"></i> Go Back - Education Deposits</a></p>

                                <?php require_once 'layouts/feedback_message.php'; ?>

                                <p>See details of deposit transactions for Education below, once confirmed, client will be able to
                                access the course paid for.</p>

                                <div class="row">

                                    <div class="col-sm-7">
                                        <form data-toggle="validator" class="form-horizontal" role="form" method="post" action="">

                                            <input name="transaction_no" type="hidden" value="<?php if(isset($trans_id)) { echo encrypt($trans_id); } ?>">
                                            <input name="user_no" type="hidden" value="<?php if(isset($trans_detail['user_code'])) { echo encrypt($trans_detail['user_code']); } ?>">
                                            <input name="course_no" type="hidden" value="<?php if(isset($trans_detail['edu_course_id'])) { echo encrypt($trans_detail['edu_course_id']); } ?>">

                                            <div class="form-group">
                                                <label class="control-label col-sm-4" for="course_name">Course:</label>
                                                <div class="col-sm-8">
                                                    <input name="course_name" type="text" class="form-control" id="course_name" value="<?php if(isset($trans_detail['title'])) { echo $trans_detail['title']; } ?>" readonly="readonly">
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <label class="control-label col-sm-4" for="client_name">Client Name:</label>
                                                <div class="col-sm-8">
                                                    <input name="client_name" type="text" class="form-control" id="client_name" value="<?php if(isset($trans_detail['client_full_name'])) { echo $trans_detail['client_full_name']; } ?>" readonly="readonly">
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <label class="control-label col-sm-4" for="client_email">Email Address:</label>
                                                <div class="col-sm-8">
                                                    <input name="client_email" type="text" class="form-control" id="client_email" value="<?php if(isset($trans_detail['email'])) { echo $trans_detail['email']; } ?>" readonly="readonly">
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <label class="control-label col-sm-4" for="camount">Course Cost (&#8358;):</label>
                                                <div class="col-sm-8">
                                                    <input name="camount" type="text" class="form-control" id="camount" value="<?php if(isset($trans_detail['course_cost'])) { echo number_format($trans_detail['course_cost'], 2, ".", ","); } ?>" readonly="readonly">
                                                </div>
                                            </div>
                                            <hr />
                                            <div class="form-group">
                                                <label class="control-label col-sm-4" for="total_payable">Total Payable:</label>
                                                <div class="col-sm-8">
                                                    <input name="total_payable" type="hidden" class="form-control" id="total_payable" value="<?php if(isset($total_payable)) { echo number_format($total_payable, 2, ".", ","); } ?>" readonly="readonly">
                                                    <p style="font-size: 1.6em; padding: 0; color: green;"><strong>&#8358; <?php if(isset($total_payable)) { echo number_format($total_payable, 2, ".", ","); } ?></strong></p>
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <label class="control-label col-sm-4" for="deposit_status">Choose Status:</label>
                                                <div class="col-sm-8">
                                                    <div class="radio"><label><input name="deposit_status" type="radio" value="3"> Confirmed</label></div>
                                                    <div class="radio"><label><input name="deposit_status" type="radio" value="4"> Declined</label></div>
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <label class="control-label col-sm-4" for="admin_comment">Comment:</label>
                                                <div class="col-sm-8"><textarea name="admin_comment" class="form-control" rows="7" id="admin_comment"></textarea></div>
                                            </div>

                                            <div class="form-group">
                                                <div class="col-sm-offset-4 col-sm-8"><input name="edu_deposit_process" type="submit" class="btn btn-success" value="Process Deposit" /></div>
                                            </div>
                                        </form>
                                    </div>

                                    <div class="col-sm-5">
                                        <h5>Admin Remarks</h5>
                                        <div style="max-height: 550px; overflow: scroll;">
                                            <?php
                                            if(isset($trans_remark) && !empty($trans_remark)) {
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
                                                <?php } } else { ?>
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
    </body>
</html>