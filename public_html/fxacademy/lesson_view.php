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

$selected_course = $education_object->get_active_course_by_id($course_id);
$selected_lesson = $education_object->get_single_course_lesson_id($course_lesson_id);
$selected_exercise = $education_object->get_all_exercise_by_lesson_id($course_lesson_id);

if(empty($selected_course)) {
    redirect_to("fxacademy/"); // cannot find course or URL tampered
} else {
    if(empty($selected_lesson)) {
        $back_url = "course_view.php?id=" . encrypt($course_id);
        redirect_to($back_url); // cannot find lesson or URL tampered
    }
}

if (isset($_POST['submit_question'])) {
    foreach ($_POST as $key => $value) {
        $_POST[$key] = $db_handle->sanitizePost(trim($value));
    }
    extract($_POST);

    $question_submitted = $education_object->set_lesson_support_request($course_id, $course_lesson_id, $comment, $_SESSION['client_unique_code']);

    if($question_submitted) {
        $message_success = "Your support request has been submitted successfully, your Instructor will respond soon.
                    Go to <a href='fxacademy/support_message.php'>Support Message</a> to follow responses on all your support request.";
    } else {
        $message_error = "There seems to be a problem somewhere, your support request could not be saved. Please try again.";
    }
}

// Go to next lesson
$next_lesson = $education_object->get_next_lesson($course_id, $course_lesson_id);

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
                            <div class="col-md-12 lesson_reader">
                                <p><a href="fxacademy/course_view.php?id=<?php echo encrypt($course_id); ?>" class="btn btn-default" title="Course Outline"><i class="fa fa-arrow-circle-left"></i> Course Outline</a></p>
                                <p><span class="text-danger"><?php echo $selected_course['title']; ?></span></p>
                                <h3 class="text-center"><strong><?php echo $selected_lesson['title']; ?></strong></h3>

                                <?php require_once 'layouts/feedback_message.php'; ?>

                                <hr />

                                <?php echo $selected_lesson['content']; ?>
                                <hr />

                                <ul class="pager">
                                    <?php if(empty($selected_exercise)) { ?>
                                        <li class="next"><a href="<?php echo $next_lesson['next_lesson_url']; ?>"><?php echo $next_lesson['next_lesson_name']; ?> &rarr;</a></li>
                                    <?php } else { ?>
                                        <li class="next"><a href="fxacademy/test_view.php?cid=<?php echo encrypt($course_id); ?>&lid=<?php echo encrypt($course_lesson_id); ?>">Proceed: Take Lesson Assessment &rarr;</a></li>
                                    <?php } ?>
                                </ul>
                                <hr />

                                <p class="text-danger">Do you have any question about this lesson? Send a message to your Instructor.</p>

                                <form class="form-horizontal" role="form" method="post" action="">

                                    <div class="form-group">
                                        <label class="control-label col-sm-2" for="question">Question:</label>
                                        <div class="col-sm-10 col-lg-7"><textarea name="comment" class="form-control" rows="7" id="question"></textarea></div>
                                    </div>
                                    <div class="form-group">
                                        <div class="col-sm-offset-2 col-sm-10"><input name="submit_question" type="submit" class="btn btn-success" value="Submit" /></div>
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