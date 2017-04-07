<?php
/**
 * Created by PhpStorm.
 * User: WeaverBird
 * Date: 9/13/2016
 * Time: 12:07 PM
 */

require_once("../init/initialize_admin.php");
if (!$session_admin->is_logged_in()) {
    redirect_to("login.php");
}

$get_params = allowed_get_params(['c','l', 'edit', 'delete']);
$course_id = $get_params['c'];
$lesson_id = $get_params['l'];

$data_list = [];
$course_data = [];
$lesson_data = [];

// WHEN FORM HAS BEEN SUBMITTED
if( isset($_POST['process'])){

    foreach($_POST as $key => $value) {
        if($key != "option"){
            $_POST[$key] = $db_handle->sanitizePost(trim($value));
        }
    }

    extract($_POST);

    var_dump($_POST);

    if( empty($question) || empty($option) || empty($c) || empty($l) || $right ==  "" ) {
        $message_error = "All fields are compulsory, please try again.";
    } else {

        //Check for creation or Editing here
        if(! empty($flag) ){//edit if flag is correct

            $updated = $questions_object->update_question_and_options($c, $l, $q_id, $question, $option, $right);
            //var_dump($option);
            if($updated) {
                $message_success = "You have successfully Updated Course " . $title;
            } else {
                $message_error = "Looks like something went wrong or Unable to Edit and Update Course.";
            }


        }else{
            //Create a new Question
            $created = $questions_object->add_question($c, $l, $question, $option, $right); //return bool

            if($created) {
                $message_success = "You have successfully Created A new Course.";
                $_SESSION['display_message'] = $message_success;
                header('location:'.$_SERVER['PHP_SELF'].'?c='.$course_id.'&l='.$lesson_id);
                exit;
            } else {
                $message_error = "Looks like something went wrong or you didn't make any change.";
            }


        }

    }

}

// DELETING A QUESTION
$to_delete = $get_params['delete'];
if(!empty($to_delete) && !empty($course_id) && !empty($lesson_id)){
    if($questions_object->delete_question($to_delete, $course_id)){
        $message_success = "Question Deleted ";
        $_SESSION['display_message'] = $message_success;

    }else{
        $message_error = "Unable to Delete Question";
    }
    header('location:'.$_SERVER['PHP_SELF'].'?c='.$course_id.'&l='.$lesson_id);
    exit;
}

// GETTING THE QUESTIONS
if(!empty($course_id) && !empty($lesson_id)) {

    $data_list = $questions_object->get_question_and_options($course_id, $lesson_id);

    if( !empty($data_list)){

        $course_id = $data_list[0]['course_id'];

    }

    $course_data = $education_object->get_course($course_id);
    $lesson_data = $education_object->get_lesson($course_id, $lesson_id);
    $lesson_id = $lesson_data['edu_lesson_id'];

}


$to_edit = $get_params['edit'];

if(!empty($to_edit)){
    //perform an edit on the form
    $edit_data = $questions_object->get_question($course_id, $lesson_id, $to_edit);
    $edit_data_option = $questions_object->get_options($edit_data['question_id']);
    //var_dump($edit_data, $edit_data_option);
}

if(!empty($_SESSION['display_message'])){
    $message_success = $_SESSION['display_message'];
}
echo $message_success;
unset($_SESSION['display_message']);

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <base target="_self">
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Instaforex Nigeria | Admin - Courses</title>
    <meta name="title" content="Instaforex Nigeria | Admin" />
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
                    <h4><strong>QUESTIONS FOR COURSE : <?php echo $course_data['title']; ?></strong></h4>
                </div>
            </div>

            <div class="row">
                <?php require_once 'layouts/feedback_message.php'; ?>

                <!-- Column One : Questions List -->
                <div class="col-lg-7">

                    <div class="section-tint super-shadow">
                        <div class="row">
                            <div class="col-sm-12">
                                <h5>Questions for Lesson : <?php echo $lesson_data['title']; ?></h5>

                                <ul>
                                    <?php
                                    if($data_list){
                                        if(! empty($data_list)) {

                                            $last_question = null;
                                            foreach($data_list as $question) {

                                                $next_question = $question['question'];
                                                if( $question['question'] != $last_question) {
                                                    ?>
                                                    <li>

                                                        <p> <?php echo $question['question']; ?>

                                                            <span><!-- Edit the Question -->
                                                            <a href="questions.php?edit=<?php echo $question['question_id']; ?>&c=<?php echo $course_data['edu_course_id']; ?>&l=<?php echo $lesson_data['edu_lesson_id']; ?>">
                                                                <i class="fa fa-pencil"></i> Edit </a>

                                                            <a href="questions.php?delete=<?php echo $question['question_id']; ?>&c=<?php echo $course_data['edu_course_id']; ?>&l=<?php echo $lesson_data['edu_lesson_id']; ?>">
                                                                <i class="fa fa-pencil"></i>Delete</a>
                                                            </span>

                                                        </p>

                                                        <ul><!-- Options -->
                                                            <?php
                                                            // TODO : Optimize this option display later updates
                                                            foreach($data_list as $option) {
                                                                if ($option['question'] == $next_question) {
                                                                    ?>
                                                                    <li><?php echo $option['option']; ?></li>
                                                                    <?php
                                                                }
                                                            }
                                                            ?>
                                                        </ul>


                                                    </li><!-- #question -->
                                                    <?php
                                                }
                                                $last_question = $question['question'];
                                            }
                                        }else{
                                            echo "<li> You have not created any Question for this Lesson Yet </li>";
                                        }
                                    }else{

                                        echo "<li> You have not created any Question for this Lesson Yet </li>";

                                    }
                                    ?>
                                </ul>

                            </div>
                        </div>
                    </div>

                </div>

                <!-- Column Two : Question Form -->
                <div class="col-lg-5">
                    <div class="section-tint super-shadow">
                        <h5> <span> Create Questions For Lessons : <?php echo $lesson_data['title']; ?></span></h5>
                        <hr/><br>

                            <div class="row">
                                <div class="col-sm-12">

                                    <form data-toggle="validator" role="form" method="post" action="<?php echo $_SERVER['PHP_SELF'] . '?c='. $course_id.'&l='.$lesson_id; ?>">

                                        <input type="hidden" name="c" value="<?php echo $course_id; ?>" />
                                        <input type="hidden" name="l" value="<?php echo $lesson_id; ?>" />

                                        <?php
                                        if(! empty($to_edit)) {
                                            ?>
                                            <input type="hidden" name="q_id" value="<?php echo isset($edit_data) ? $edit_data['question_id'] : null; ?>" />
                                            <input type="hidden" name="flag" value="edit"/>
                                            <?php
                                        }
                                        ?>


                                        <div class="form-group"><!-- Question -->
                                            <label class="control-label row" for="description">Question:</label>
                                            <div class="col-sm-12">
                                                <textarea name="question" id="description" rows="3" class="form-control"
                                                          required="required"><?php if(isset($edit_data['question'])) { echo $edit_data['question']; } ?></textarea></div>
                                        </div>

                                        <?php
                                        $opt = []; // set the opt varaiable

                                        if(isset($edit_data_option, $edit_data)){

                                            if(count($edit_data_option) > 0){

                                                foreach($edit_data_option as $key => $value){

//                                                    $opt[$key] = [ 'option' => $value['option'], 'option_id' => $value['option_id']];
                                                    $opt['old'][$key] = [ 'option' => $value['option'], 'option_id' => $value['option_id']];

                                                    if($value['option_id'] == $edit_data['right_option']){
                                                        $right_option = $key;
                                                    }

                                                }

                                            }
                                        }
                                        ?>

                                        <div class="form-group"><!-- Options -->
                                            <div class="input-group">
                                                <span class="input-group-addon">
                                                    <input type="radio" name="right" value="0" <?php if(isset($right_option)) echo $right_option == 0 ? "checked" : null; ?> > Option 1 </span>
                                                <input type="text" placeholder="Option Choice 1"
                                                       name="<?php
                                                       if(!empty($opt['old'][0])){
                                                           echo 'option[old]['. $opt['old'][0]['option_id'] .']';
                                                       }else{
                                                           echo 'option[new][]';
                                                       }
                                                       ?>"
                                                       class="form-control"
                                                value="<?php echo !empty($opt['old'][0]) ? $opt['old'][0]['option'] : null; ?>">
                                            </div>
                                        </div>

                                        <div class="form-group"><!-- 1 -->
                                            <div class="input-group">
                                                <span class="input-group-addon">
                                                    <input type="radio" name="right" value="1" <?php if(isset($right_option)) echo $right_option == 1 ? "checked" : null; ?> > Option 2</span>
                                                <input type="text" placeholder="Option Choice 2"
                                                       name="<?php
                                                       if(!empty($opt['old'][1])){
                                                           echo 'option[old]['. $opt['old'][1]['option_id'] .']';
                                                       }else{
                                                           echo 'option[new][]';
                                                       }
                                                       ?>"
                                                       class="form-control"
                                                       value="<?php echo !empty($opt['old'][1]) ? $opt['old'][1]['option'] : null; ?>">

                                            </div>
                                        </div>

                                        <div class="form-group"><!-- 2 -->
                                            <div class="input-group">
                                                <span class="input-group-addon">
                                                    <input type="radio" name="right" value="2" <?php if(isset($right_option)) echo $right_option == 2 ? "checked" : null; ?> > Option 3</span>
                                                <input type="text" placeholder="Option Choice 3"
                                                       name="<?php
                                                       if(!empty($opt['old'][2])){
                                                           echo 'option[old]['. $opt['old'][2]['option_id'] .']';
                                                       }else{
                                                           echo 'option[new][]';
                                                       }
                                                       ?>"
                                                       class="form-control"
                                                       value="<?php echo !empty($opt['old'][2]) ? $opt['old'][2]['option'] : null; ?>">
                                            </div>
                                        </div>

                                        <div class="form-group"><!-- 3 -->
                                            <div class="input-group">
                                                <span class="input-group-addon"><input type="radio" name="right" value="3" <?php if(isset($right_option)) echo $right_option == 3 ? "checked" : null; ?> > Option 4</span>
                                                <input type="text" placeholder="Option Choice 4"
                                                       name="<?php
                                                       if(!empty($opt['old'][3])){
                                                           echo 'option[old]['. $opt['old'][3]['option_id'] .']';
                                                       }else{
                                                           echo 'option[new][]';
                                                       }
                                                       ?>"
                                                       class="form-control"
                                                       value="<?php echo !empty($opt['old'][3]) ? $opt['old'][3]['option'] : null; ?>">
                                            </div>
                                        </div>

                                        <!-- Submit button -->
                                        <div class="form-group">
                                            <div class="col-sm-offset-2 col-sm-10">
                                                <button type="button" data-target="#save-form-confirm" data-toggle="modal" class="btn btn-success">
                                                    <i class="fa fa-save fa-fw"></i>
                                                    <?php echo !empty($to_edit) ? 'Save Edits' : "Create Question "?>
                                                </button>
                                            </div>
                                        </div>

                                        <!-- Modal - confirmation boxes -->
                                        <div id="save-form-confirm" tabindex="-1" role="dialog" aria-hidden="true" class="modal fade">
                                            <div class="modal-dialog">
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <button type="button" data-dismiss="modal" aria-hidden="true"
                                                                class="close">&times;</button>
                                                        <h4 class="modal-title"><?php echo !empty($data_list) ? "Edit" : "Create"; ?> Question Confirmation</h4>
                                                    </div>
                                                    <div class="modal-body">Are you sure you want to save this Question?</div>
                                                    <div class="modal-footer">
                                                        <input name="process" type="submit" class="btn btn-success" value="Save Lesson">
                                                        <button type="submit" name="decline" onClick="window.close();" data-dismiss="modal" class="btn btn-danger">Close !</button>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                    </form>

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
