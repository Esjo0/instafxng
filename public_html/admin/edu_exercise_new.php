<?php
require_once("../init/initialize_admin.php");
if (!$session_admin->is_logged_in()) {
    redirect_to("login.php");
}

$get_params = allowed_get_params(['x', 'cid', 'lid', 'eid']);
$course_id_encrypted = $get_params['cid'];
$course_id = decrypt(str_replace(" ", "+", $course_id_encrypted));
$course_id = preg_replace("/[^A-Za-z0-9 ]/", '', $course_id);

$course_lesson_id_encrypted = $get_params['lid'];
$course_lesson_id = decrypt(str_replace(" ", "+", $course_lesson_id_encrypted));
$course_lesson_id = preg_replace("/[^A-Za-z0-9 ]/", '', $course_lesson_id);

$exercise_id_encrypted = $get_params['eid'];
$exercise_id = decrypt(str_replace(" ", "+", $exercise_id_encrypted));
$exercise_id = preg_replace("/[^A-Za-z0-9 ]/", '', $exercise_id);

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Save this campaign email
    foreach($_POST as $key => $value) {
        $_POST[$key] = $db_handle->sanitizePost(trim($value));
    }
    
    extract($_POST);
    
    if(empty($l_question) || empty($l_option_a) || empty($l_option_b) || empty($right_ans)) {
        $message_error = "All fields must be filled, please try again";
    } else {
        
        $new_exercise = $education_object->create_new_exercise($l_question_no, $course_lesson_id, $l_question, $l_option_a, $l_option_b, $l_option_c, $l_option_d, $right_ans, $_SESSION['admin_unique_code']);

        if($new_exercise) {
            $message_success = "You have successfully saved exercise";
        } else {
            $message_error = "Looks like something went wrong or you didn't make any change.";
        }
    }
}

$selected_course = $education_object->get_course_by_id($course_id);
$selected_lesson = $education_object->get_single_course_lesson_id($course_lesson_id);

if(empty($selected_lesson)) {
    $back_url = "edu_course_view.php?id=" . encrypt($course_id);
    redirect_to($back_url); // cannot find lesson or URL tampered
}


if($get_params['x'] == 'edit' && isset($exercise_id)) {
    $selected_exercise = $education_object->get_single_exercise_by_id($exercise_id);

    if(empty($selected_exercise)) {
        $back_url = "edu_course_view.php?id=" . encrypt($course_id);
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
        <title>Instaforex Nigeria | Admin - Create New Exercise</title>
        <meta name="title" content="Instaforex Nigeria | Admin - Create New Exercise" />
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
                            <h4><strong>CREATE NEW EXERCISE</strong></h4>
                        </div>
                    </div>
                    
                    <div class="section-tint super-shadow">
                        <div class="row">
                            <div class="col-sm-12">
                                <?php require_once 'layouts/feedback_message.php'; ?>

                                <?php if($selected_exercise) { ?>
                                <p><a href="edu_lesson_view.php?cid=<?php echo encrypt($course_id); ?>&lid=<?php echo encrypt($course_lesson_id); ?>" class="btn btn-default" title="Go Back To Selected Lesson"><i class="fa fa-arrow-circle-left"></i> Go Back To Selected Lesson</a></p>
                                <?php } else { ?>
                                <p><a href="edu_course_view.php?id=<?php echo encrypt($course_id); ?>" class="btn btn-default" title="Go Back To Selected Course"><i class="fa fa-arrow-circle-left"></i> Go Back To Selected Course</a></p>
                                <?php } ?>

                                <p><strong><span class="text-danger">Course Title:</span> <?php echo $selected_course['title']; ?></strong></p>
                                <p><strong><span class="text-danger">Lesson:</span> <?php echo $selected_lesson['title']; ?></strong></p>

                                <p>Create a new exercise for this lesson. Please note that each exercise must have at least two options</p>

                                <form data-toggle="validator" class="form-horizontal" role="form" method="post" action="">
                                    <?php if(isset($selected_exercise)) { ?>
                                        <input type="hidden" name="l_question_no" value="<?php if(isset($selected_exercise['edu_lesson_exercise_id'])) { echo $selected_exercise['edu_lesson_exercise_id']; } ?>" />
                                    <?php } ?>

                                    <div class="form-group">
                                        <label class="control-label col-sm-3" for="l_question">Question:</label>
                                        <div class="col-sm-9 col-lg-7"><textarea name="l_question" id="l_question" rows="3" class="form-control" required><?php if(isset($selected_exercise['question'])) { echo $selected_exercise['question']; } ?> </textarea></div>
                                    </div>
                                    <div class="form-group">
                                        <label class="control-label col-sm-3" for="l_option_a">Option A:</label>
                                        <div class="col-sm-9 col-lg-7"><textarea name="l_option_a" id="l_option_a" rows="3" class="form-control" required><?php if(isset($selected_exercise['option_a'])) { echo $selected_exercise['option_a']; } ?> </textarea></div>
                                    </div>
                                    <div class="form-group">
                                        <label class="control-label col-sm-3" for="l_option_b">Option B:</label>
                                        <div class="col-sm-9 col-lg-7"><textarea name="l_option_b" id="l_option_b" rows="3" class="form-control" required><?php if(isset($selected_exercise['option_b'])) { echo $selected_exercise['option_b']; } ?> </textarea></div>
                                    </div>
                                    <div class="form-group">
                                        <label class="control-label col-sm-3" for="l_option_c">Option C:</label>
                                        <div class="col-sm-9 col-lg-7"><textarea name="l_option_c" id="l_option_c" rows="3" class="form-control"><?php if(isset($selected_exercise['option_c'])) { echo $selected_exercise['option_c']; } ?> </textarea></div>
                                    </div>
                                    <div class="form-group">
                                        <label class="control-label col-sm-3" for="l_option_b">Option D:</label>
                                        <div class="col-sm-9 col-lg-7"><textarea name="l_option_d" id="l_option_d" rows="3" class="form-control"><?php if(isset($selected_exercise['option_d'])) { echo $selected_exercise['option_d']; } ?> </textarea></div>
                                    </div>
                                    <div class="form-group">
                                        <label class="control-label col-sm-3" for="status">Right Option:</label>
                                        <div class="col-sm-9 col-lg-7">
                                            <div class="radio">
                                                <label><input type="radio" name="right_ans" value="A" <?php if($selected_exercise['right_option'] == 'A') { echo "checked"; } ?> required>Option A</label>
                                            </div>
                                            <div class="radio">
                                                <label><input type="radio" name="right_ans" value="B" <?php if($selected_exercise['right_option'] == 'B') { echo "checked"; } ?> required>Option B</label>
                                            </div>
                                            <div class="radio">
                                                <label><input type="radio" name="right_ans" value="C" <?php if($selected_exercise['right_option'] == 'C') { echo "checked"; } ?> required>Option C</label>
                                            </div>
                                            <div class="radio">
                                                <label><input type="radio" name="right_ans" value="D" <?php if($selected_exercise['right_option'] == 'D') { echo "checked"; } ?> required>Option D</label>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <div class="col-sm-offset-3 col-sm-9">
                                            <button type="button" data-target="#save-exercise-confirm" data-toggle="modal" class="btn btn-success"><i class="fa fa-save fa-fw"></i> Save Question</button>
                                        </div>
                                    </div>

                                    <!-- Modal - confirmation boxes -->
                                    <div id="save-exercise-confirm" tabindex="-1" role="dialog" aria-hidden="true" class="modal fade">
                                        <div class="modal-dialog">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <button type="button" data-dismiss="modal" aria-hidden="true"
                                                        class="close">&times;</button>
                                                    <h4 class="modal-title">Save Question Confirmation</h4></div>
                                                <div class="modal-body">Do you want to save this information?</div>
                                                <div class="modal-footer">
                                                    <input name="process" type="submit" class="btn btn-success" value="Save">
                                                    <button type="submit" name="decline" onClick="window.close();" data-dismiss="modal" class="btn btn-danger">Close !</button>
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
        <?php require_once 'layouts/footer.php'; ?>
    </body>
</html>