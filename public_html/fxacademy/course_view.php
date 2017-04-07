<?php
require_once '../init/initialize_client.php';
$thisPage = "";

if (!$session_client->is_logged_in()) {
    redirect_to("login.php");
}

$get_params = allowed_get_params(['id']);
$course_id_encrypted = $get_params['id'];
$course_id = decrypt(str_replace(" ", "+", $course_id_encrypted));
$course_id = preg_replace("/[^A-Za-z0-9 ]/", '', $course_id);

$selected_course = $education_object->get_active_course_by_id($course_id);

if(empty($selected_course)) {
    redirect_to("./"); // cannot find course or URL tampered
} else {
    $course_lessons = $education_object->get_active_lessons_by_id($course_id);

    if($selected_course['course_fee'] == '2' || $selected_course['course_cost'] > 0) {
        $paid_course = true;
        $confirm_client_paid = $education_object->confirm_course_payment($_SESSION['client_unique_code'], $course_id);
    } else {
        $paid_course = false;
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
                                <h5 class="text-center text-danger"><?php echo $selected_course['title']; ?></h5>
                                <hr />
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <p><?php echo $selected_course['description']; ?></p>
                            </div>

                            <?php if($paid_course == false || ($paid_course == true && $confirm_client_paid == true)) { ?>
                            <div class="col-md-6">
                                <p><strong>Course Outline:</strong></p>

                                <?php if(isset($course_lessons) && !empty($course_lessons)) { ?>
                                <ul class="list-group">
                                    <?php
                                        foreach ($course_lessons as $row) {
                                            // if client has viewed this lesson before, make it clickable
                                            $check_assessment = $education_object->taken_lesson_assessment($row['edu_lesson_id'], $_SESSION['client_unique_code']);
                                            if($check_assessment) {
                                    ?>
                                        <li class="list-group-item list-group-item-warning"><a title="Click to view lesson again" href="fxacademy/lesson_view.php?cid=<?php echo encrypt($course_id); ?>&lid=<?php echo encrypt($row['edu_lesson_id']); ?>"><?php echo $row['title']; ?></a></li>
                                <?php } else { ?>
                                        <li class="list-group-item list-group-item-warning"><?php echo $row['title']; ?></li>
                                    <?php } } ?>
                                </ul>
                                <?php } else { ?>
                                <span class="text-danger"><strong>There are no published lessons for this course yet, please check back later.</strong></span>
                                <?php } ?>
                                <hr />

                                <?php if($course_lessons) { ?>
                                <ul class="pager">
                                    <li class="next"><a href="fxacademy/lesson_view.php?cid=<?php echo encrypt($course_id); ?>&lid=<?php echo encrypt($course_lessons[0]['edu_lesson_id']); ?>"><?php echo $course_lessons[0]['title']; ?> &rarr;</a></li>
                                </ul>
                                <?php } ?>

                            </div>
                            <?php } else { ?>
                            <div class="col-md-6">
                                <p><strong>Course Cost:</strong> &#8358; <?php echo number_format(($selected_course['course_cost']), 2, ".", ","); ?></p>
                                <p>This is a paid course, to access this course, please follow the button below to quickly make payment.</p>
                                <a href="fxacademy/course_payment.php?id=<?php echo encrypt($course_id); ?>" title="Click to make payment" class="btn btn-success btn-lg">Make Payment</a>

                                <hr />
                                <p><strong>Course Outline:</strong></p>
                                <?php if(isset($course_lessons) && !empty($course_lessons)) { ?>
                                    <ul class="list-group">
                                        <?php foreach ($course_lessons as $row) { ?>
                                            <li class="list-group-item list-group-item-warning"><?php echo $row['title']; ?></li>
                                        <?php } ?>
                                    </ul>
                                <?php } else { ?>
                                    <span class="text-danger"><strong>There are no published lessons for this course yet, please check back later.</strong></span>
                                <?php } ?>

                                <hr />
                            </div>
                            <?php } ?>


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