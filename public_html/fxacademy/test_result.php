<?php
require_once '../init/initialize_client.php';
$thisPage = "";

if (!$session_client->is_logged_in())
{
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

$selected_course = $education_object->get_active_course_by_id($course_id);
$selected_lesson = $education_object->get_single_course_lesson_id($course_lesson_id);

if (isset($_POST['process_test']))
{
    foreach($_POST as $key => $value) {
        $_POST[$key] = $db_handle->sanitizePost(trim($value));
    }

    extract($_POST);
    unset($_POST['course_no']);
    unset($_POST['lesson_no']);
    unset($_POST['process_test']);
    $question_answered = $_POST;

    $course_id = decrypt(str_replace(" ", "+", $course_no));
    $course_id = preg_replace("/[^A-Za-z0-9 ]/", '', $course_id);
    $course_lesson_id = decrypt(str_replace(" ", "+", $lesson_no));
    $course_lesson_id = preg_replace("/[^A-Za-z0-9 ]/", '', $course_lesson_id);

    $education_object->set_lesson_rating($_SESSION['client_unique_code'],$course_lesson_id,$course_id,$_POST['rating'],$_POST['comments']);

    $assessment_result = $education_object->set_assessment_result($question_answered, $course_lesson_id, $_SESSION['client_unique_code']);

    $total_question = count($assessment_result['result']);
    $total_score = $assessment_result['correct'];
    $result = $assessment_result['result'];


} elseif($confirm_second_time_assessment) {

    $assessment_result = $education_object->get_assessment_result($course_lesson_id, $_SESSION['client_unique_code']);

    $total_question = count($assessment_result['result']);
    $total_score = $assessment_result['correct'];
    $result = $assessment_result['result'];

    $message_success = "You have taken this assessment before, see result below and proceed to the next lesson.";

} else {
    redirect_to('./');
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
                            <div class="col-md-12">
                                <p><a href="fxacademy/course_view.php?id=<?php echo encrypt($course_id); ?>" class="btn btn-default" title="Course Outline"><i class="fa fa-arrow-circle-left"></i> Course Outline</a></p>

                                <h3 class="text-center">Lesson Assessment</h3>
                                <p><span class="text-danger"><?php echo $selected_course['title']; ?></span></p>
                                <p><span class="text-danger"><?php echo $selected_lesson['title']; ?></span></p>
                                <hr />

                                <ul class="pager">
                                    <li class="next"><a href="<?php echo $next_lesson['next_lesson_url']; ?>"><?php echo $next_lesson['next_lesson_name']; ?> &rarr;</a></li>
                                </ul>
                                <hr />

                                <?php require_once 'layouts/feedback_message.php'; ?>

                                <p><strong>Your Score:</strong> <?php echo $total_score; ?> out of <?php echo $total_question; ?></p>

                                <?php if($total_score == 0) { ?>
                                    <p>What happened? You missed all the questions. Hmm, will you give the next assessment a better try?
                                    We think you have what it takes to do much better.</p>
                                <?php } elseif($total_score == $total_question) { ?>
                                    <p>Bravo! It looks like you are a born champion. Hey! Keep the groove on, proceed to the next lesson.</p>
                                <?php } else { ?>
                                    <p>Good job there, aim for a better score in the next assessment, you've got what it takes.</p>
                                <?php } ?>

                                <?php if($total_score <> $total_question) { ?>
                                <p><strong>Assessment Correction</strong></p>
                                        <?php $count = 1; foreach ($result as $row) {
                                        if($row['user_answer'] <> $row['right_option']) {
                                        $selected_exercise = $education_object->get_single_exercise_by_id($row['exercise_id']);
                                        ?>
                                        <p><?php echo $selected_exercise['question']; ?></p>
                                        <div class="btn-group btn-group-vertical" data-toggle="buttons">
                                            <label class="btn">
                                                <input type="radio" name="<?php echo $selected_exercise['edu_lesson_exercise_id']; ?>" value="A" <?php if($row['right_option'] == 'A') { echo 'checked'; } ?> disabled><i class="fa fa-circle-o fa-2x"></i><i class="fa fa-dot-circle-o fa-2x"></i><span> <?php echo $selected_exercise['option_a']; ?></span>
                                            </label>
                                            <label class="btn">
                                                <input type="radio" name="<?php echo $selected_exercise['edu_lesson_exercise_id']; ?>" value="B" <?php if($row['right_option'] == 'B') { echo 'checked'; } ?> disabled><i class="fa fa-circle-o fa-2x"></i><i class="fa fa-dot-circle-o fa-2x"></i><span> <?php echo $selected_exercise['option_b']; ?></span>
                                            </label>

                                            <?php if(!empty($selected_exercise['option_c'])) { ?>
                                                <label class="btn">
                                                    <input type="radio" name="<?php echo $selected_exercise['edu_lesson_exercise_id']; ?>" value="C" <?php if($row['right_option'] == 'C') { echo 'checked'; } ?> disabled><i class="fa fa-circle-o fa-2x"></i><i class="fa fa-dot-circle-o fa-2x"></i><span> <?php echo $selected_exercise['option_c']; ?></span>
                                                </label>
                                            <?php } ?>

                                            <?php if(!empty($selected_exercise['option_d'])) { ?>
                                                <label class="btn">
                                                    <input type="radio" name="<?php echo $selected_exercise['edu_lesson_exercise_id']; ?>" value="D" <?php if($row['right_option'] == 'D') { echo 'checked'; } ?> disabled><i class="fa fa-circle-o fa-2x"></i><i class="fa fa-dot-circle-o fa-2x"></i><span> <?php echo $selected_exercise['option_d']; ?></span>
                                                </label>
                                            <?php } ?>

                                        </div>
                                        <p>&nbsp;</p>
                                        <?php $count++; } } ?>
                                <?php } ?>
                                <hr />

                                <ul class="pager">
                                    <li class="next"><a href="<?php echo $next_lesson['next_lesson_url']; ?>"><?php echo $next_lesson['next_lesson_name']; ?> &rarr;</a></li>
                                </ul>
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