<?php
require_once '../init/initialize_client.php';
$thisPage = "";

if (!$session_client->is_logged_in()) {
    redirect_to("login.php");
}

$page_requested = "";

$get_params = allowed_get_params(['id']);
$course_id_encrypted = $get_params['id'];
$course_id = decrypt(str_replace(" ", "+", $course_id_encrypted));
$course_id = preg_replace("/[^A-Za-z0-9 ]/", '', $course_id);

$selected_course = $education_object->get_active_course_by_id($course_id);

if(empty($selected_course)) {
    redirect_to("./"); // cannot find course or URL tampered
} else {
    $origin_of_deposit = '1'; // Originates online
    $stamp_duty = 0; // It has been added to course cost
    $course_cost = $selected_course['course_cost'];
    $total_payable = $stamp_duty + $course_cost;
    $card_processing = 0.015 * $total_payable;
    $total_payable_card = $card_processing + $total_payable;
    $course_name = $selected_course['course_code'] . " - " . $selected_course['title'];
    $trans_id_encrypted = encrypt($trans_id);
    $client_name = $_SESSION['client_last_name'] . " " . $_SESSION['client_first_name'];
    $client_email = $_SESSION['client_email'];
}

if(isset($_POST['course_payment_summary'])) {
    foreach($_POST as $key => $value) {
        $_POST[$key] = $db_handle->sanitizePost(trim($value));
    }

    $pay_type = $db_handle->sanitizePost($_POST['pay_type']);

    $origin_of_deposit = '1'; // Originates online
    $stamp_duty = 0; // It has been added to course cost
    $course_cost = $selected_course['course_cost'];
    $total_payable = $stamp_duty + $course_cost;
    $card_processing = 0.015 * $total_payable;
    $total_payable_card = $card_processing + $total_payable;
    $course_name = $selected_course['course_code'] . " - " . $selected_course['title'];
    $trans_id = "FPA" . time();
    $trans_id_encrypted = encrypt($trans_id);
    $client_name = $_SESSION['client_last_name'] . " " . $_SESSION['client_first_name'];
    $client_email = $_SESSION['client_email'];

    switch ($pay_type) {
        case '1':
            $education_object->log_course_deposit($_SESSION['client_unique_code'], $trans_id, $course_id, $course_cost, $stamp_duty, $card_processing, $pay_type, $origin_of_deposit, $client_name, $client_email);
            $page_requested = "course_payment_pay_type_card_php";
            break;
        default :
            $card_processing = 0;
            $education_object->log_course_deposit($_SESSION['client_unique_code'], $trans_id, $course_id, $course_cost, $stamp_duty, $card_processing, $pay_type, $origin_of_deposit, $client_name, $client_email);
            $page_requested = "course_payment_pay_type_bank_php";
    }
}


switch($page_requested) {
    case '': $course_payment_summary_php = true; break;
    case 'course_payment_pay_type_card_php': $course_payment_pay_type_card_php = true; break;
    case 'course_payment_pay_type_bank_php': $course_payment_pay_type_bank_php = true; break;
    default: $course_payment_summary_php = true;
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
                                <h5 class="text-center text-danger">Course Payment</h5>
                                <hr />
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-12">

                                <?php require_once 'layouts/feedback_message.php'; ?>

                                <?php
                                    if($course_payment_summary_php) { include_once 'views/course_payment_summary.php'; }
                                    if($course_payment_pay_type_card_php) { include_once 'views/course_payment_pay_type_card.php'; }
                                    if($course_payment_pay_type_bank_php) { include_once 'views/course_payment_pay_type_bank.php'; }
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