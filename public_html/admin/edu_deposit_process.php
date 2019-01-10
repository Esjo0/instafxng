<?php
require_once("../init/initialize_admin.php");

if (!$session_admin->is_logged_in()) {
    redirect_to("login.php");
}

$get_params = allowed_get_params(['id', 'x']);
$trans_id_encrypted = $get_params['id'];
$trans_id = dec_enc('decrypt',  $trans_id_encrypted);

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
    case 'completed':
        $deposit_process_completed = true;
        $page_title = '- COMPLETED';
        $return_page = 'edu_deposit_completed.php';
        break;
    default:
        $no_valid_page = true;
        break;
}

if ($no_valid_page) {
    header("Location: ./");
}

if (isset($_POST['edu_deposit_process_comment'])) {
    foreach ($_POST as $key => $value) {
        $_POST[$key] = $db_handle->sanitizePost(trim($value));
    }
    extract($_POST);

    $trans_id = dec_enc('decrypt',  $transaction_no);

    $update_deposit_comment = $education_object->log_edu_deposit_comment($_SESSION['admin_unique_code'], $trans_id, $admin_comment);

    if ($update_deposit_comment) {
        $message_success = "You have successfully logged your comment.";
    } else {
        $message_error = "An error occurred, looks like there was a problem";
    }


}

if (isset($_POST['edu_deposit_process_notified'])) {
    foreach ($_POST as $key => $value) {
        $_POST[$key] = $db_handle->sanitizePost(trim($value));
    }
    extract($_POST);

    $transaction_no = dec_enc('decrypt',  $transaction_no);
    $course_no = dec_enc('decrypt',  $course_no);
    $user_no = dec_enc('decrypt',  $user_no);

    $update_deposit = $education_object->modify_edu_deposit_order($transaction_no, $course_no, $user_no, $deposit_status, $admin_comment, $_SESSION['admin_unique_code']);

    if ($update_deposit) {
        $message_success = "You have successfully updated this deposit.";
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
    <title>Instaforex Nigeria | Admin - Process Education Deposits</title>
    <meta name="title" content="Instaforex Nigeria | Admin - Process Education Deposits"/>
    <meta name="keywords" content=""/>
    <meta name="description" content=""/>
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
                    <h4><strong>PROCESS EDUCATION DEPOSITS <?php if($page_title) { echo $page_title; } ?></strong></h4>
                </div>
            </div>

            <div class="section-tint super-shadow">
                <div class="row">
                    <div class="col-sm-12">
                        <?php require_once 'layouts/feedback_message.php'; ?>

                        <?php
                            if($deposit_process_initiated) { include_once 'views/edu_deposit_process/deposit_process_initiated.php'; }
                            if($deposit_process_notified) { include_once 'views/edu_deposit_process/deposit_process_notified.php'; }
                            if($deposit_process_completed) { include_once 'views/edu_deposit_process/deposit_process_completed.php'; }
                        ?>

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