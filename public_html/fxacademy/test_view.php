<?php
require_once '../init/initialize_client.php';
$thisPage = "";

if (!$session_client->is_logged_in()) {
    redirect_to("login.php");
}

$get_params = allowed_get_params(['lid', 'cid']);
$course_id_encrypted = $get_params['cid'];
$course_id = decrypt(str_replace(" ", "+", $course_id_encrypted));
$course_id = preg_replace("/[^A-Za-z0-9 ]/", '', $course_id);

$course_lesson_id_encrypted = $get_params['lid'];
$course_lesson_id = decrypt(str_replace(" ", "+", $course_lesson_id_encrypted));
$course_lesson_id = preg_replace("/[^A-Za-z0-9 ]/", '', $course_lesson_id);

$confirm_second_time_assessment = $education_object->confirm_second_time_assessment($course_lesson_id, $_SESSION['client_unique_code']);

if($confirm_second_time_assessment) {
    redirect_to("test_result.php?lid=$course_lesson_id_encrypted");
    exit;
}

$selected_course = $education_object->get_active_course_by_id($course_id);
$selected_lesson = $education_object->get_single_course_lesson_id($course_lesson_id);
$selected_exercise = $education_object->get_all_exercise_by_lesson_id($course_lesson_id);

if(empty($selected_course)) {
    redirect_to("./"); // cannot find course or URL tampered
} else {
    if(empty($selected_lesson)) {
        $back_url = "course_view.php?id=" . encrypt($course_id);
        redirect_to($back_url); // cannot find lesson or URL tampered
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
                                <p><a href="fxacademy/course_view.php?id=<?php echo encrypt($course_id); ?>" class="btn btn-default" title="Course Outline"><i class="fa fa-arrow-circle-left"></i> Course Outline</a></p>
                                <p>Course Title: <span class="text-danger"><?php echo $selected_course['title']; ?></span></p>
                                <p class="text-center"><span class="text-danger"><?php echo $selected_lesson['title']; ?></span> (Lesson Assessment)</p>
                                <hr />

                                <form data-toggle="validator" class="form-horizontal" role="form" method="post" action="fxacademy/test_result.php">
                                    <input type="hidden" name="course_no" value="<?php echo $course_id_encrypted; ?>" />
                                    <input type="hidden" name="lesson_no" value="<?php echo $course_lesson_id_encrypted; ?>" />

                                    <?php if(isset($selected_exercise) && !empty($selected_exercise)) { $count = 1; foreach ($selected_exercise as $row) { ?>
                                    <p><?php echo $count . ". " .$row['question']; ?></p>
                                    <div class="btn-group btn-group-vertical" data-toggle="buttons">
                                        <label class="btn">
                                            <input type="radio" name="<?php echo $row['edu_lesson_exercise_id']; ?>" value="A" required><i class="fa fa-circle-o fa-2x"></i><i class="fa fa-dot-circle-o fa-2x"></i><span> <?php echo $row['option_a']; ?></span>
                                        </label>
                                        <label class="btn">
                                            <input type="radio" name="<?php echo $row['edu_lesson_exercise_id']; ?>" value="B" required><i class="fa fa-circle-o fa-2x"></i><i class="fa fa-dot-circle-o fa-2x"></i><span> <?php echo $row['option_b']; ?></span>
                                        </label>

                                        <?php if(!empty($row['option_c'])) { ?>
                                            <label class="btn">
                                                <input type="radio" name="<?php echo $row['edu_lesson_exercise_id']; ?>" value="C" required><i class="fa fa-circle-o fa-2x"></i><i class="fa fa-dot-circle-o fa-2x"></i><span> <?php echo $row['option_c']; ?></span>
                                            </label>
                                        <?php } ?>

                                        <?php if(!empty($row['option_d'])) { ?>
                                            <label class="btn">
                                                <input type="radio" name="<?php echo $row['edu_lesson_exercise_id']; ?>" value="D" required><i class="fa fa-circle-o fa-2x"></i><i class="fa fa-dot-circle-o fa-2x"></i><span> <?php echo $row['option_d']; ?></span>
                                            </label>
                                        <?php } ?>

                                    </div>
                                    <p>&nbsp;</p>
                                    <?php $count++; } } ?>
                                    <div class="form-group">
                                        <div class="col-sm-12">
                                            <button type="button" data-target="#lesson-assessment" data-toggle="modal" class="btn btn-success btn-lg">Submit Assessment</button>
                                        </div>
                                    </div>

                                    <!--Modal - confirmation boxes-->
                                    <div id="lesson-assessment" tabindex="-1" role="dialog" aria-hidden="true" class="modal fade">
                                        <div class="modal-dialog">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <button type="button" data-dismiss="modal" aria-hidden="true"
                                                            class="close">&times;</button>
                                                    <h4 class="modal-title">Lesson Assessment</h4></div>
                                                <div class="modal-body">Are you sure you want to submit the assessment for this lesson?</div>
                                                <div class="modal-footer">
                                                    <input name="process_test" type="submit" class="btn btn-success" value="Submit">
                                                    <button type="submit" name="close" data-dismiss="modal" class="btn btn-danger">Close!</button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </form>
                                <hr />
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