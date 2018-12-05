<?php
require_once("../init/initialize_admin.php");
if (!$session_admin->is_logged_in()) {
    redirect_to("login.php");
}

$get_params = allowed_get_params(['x', 'lid', 'cid']);
$course_id_encrypted = $get_params['cid'];
$course_id = decrypt_ssl(str_replace(" ", "+", $course_id_encrypted));
$course_id = preg_replace("/[^A-Za-z0-9 ]/", '', $course_id);

$course_lesson_id_encrypted = $get_params['lid'];
$course_lesson_id = decrypt_ssl(str_replace(" ", "+", $course_lesson_id_encrypted));
$course_lesson_id = preg_replace("/[^A-Za-z0-9 ]/", '', $course_lesson_id);

if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    foreach($_POST as $key => $value) {
        $_POST[$key] = $db_handle->sanitizePost(trim($value));
    }

    extract($_POST);

    if(empty($l_title) || empty($l_content) || empty($l_order)) {
        $message_error = "All fields must be filled, please try again";
    } else {

        $new_courses = $education_object->create_new_lesson($l_lesson_no, $course_id, $l_order, $l_title, $l_content, $l_status, $_SESSION['admin_unique_code']);

        if($new_courses) {
            $message_success = "You have successfully saved this lesson";
        } else {
            $message_error = "Looks like something went wrong or you didn't make any change.";
        }
    }
}


$selected_course = $education_object->get_course_by_id($course_id);

if(empty($selected_course)) {
    redirect_to("edu_course.php"); // cannot find course or URL tampered
}


if($get_params['x'] == 'edit' && isset($course_lesson_id)) {
    $selected_lesson = $education_object->get_single_course_lesson_id($course_lesson_id);

    if(empty($selected_lesson)) {
        $back_url = "edu_course_view.php?id=" . encrypt_ssl($course_id);
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
        <title>Instaforex Nigeria | Admin - Create New Lesson</title>
        <meta name="title" content="Instaforex Nigeria | Admin - Create New Lesson" />
        <meta name="keywords" content="" />
        <meta name="description" content="" />
        <?php require_once 'layouts/head_meta.php'; ?>
        <script src="tinymce/tinymce.min.js"></script>
        <script type="text/javascript">
            tinyMCE.init({
                selector: "textarea#l_content",
                height: 500,
                theme: "modern",
                relative_urls: false,
                remove_script_host: false,
                convert_urls: true,
                plugins: [
                    "advlist autolink lists link image charmap print preview hr anchor pagebreak",
                    "searchreplace wordcount visualblocks visualchars code fullscreen",
                    "insertdatetime media nonbreaking save table contextmenu directionality",
                    "emoticons template paste textcolor colorpicker textpattern responsivefilemanager"
                ],
                toolbar1: "insertfile undo redo | styleselect | bold italic | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | link image",
                toolbar2: "| responsivefilemanager print preview media | forecolor backcolor emoticons",
                image_advtab: true,
                external_filemanager_path: "filemanager/",
                filemanager_title: "Instafxng Filemanager",
                browser_spellcheck: true,
//                external_plugins: { "filemanager" : "../filemanager/plugin.min.js"}

            });
        </script>
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
                            <h4><strong>CREATE NEW LESSON</strong></h4>
                        </div>
                    </div>
                    
                    <div class="section-tint super-shadow">
                        <div class="row">
                            <div class="col-sm-12">
                                <?php require_once 'layouts/feedback_message.php'; ?>
                                <p><a href="edu_course_view.php?id=<?php echo encrypt_ssl($course_id); ?>" class="btn btn-default" title="Go Back To Selected Course"><i class="fa fa-arrow-circle-left"></i> Go Back To Selected Course</a></p>

                                <p><strong>Course Title: <?php if(isset($selected_course['title'])) { echo $selected_course['title']; } ?></strong></p>

                                <p>Create a new lesson for this course</p>

                                <form class="form-horizontal" role="form" method="post" action="">
                                    <?php if(isset($selected_lesson)) { ?>
                                        <input type="hidden" name="l_lesson_no" value="<?php if(isset($selected_lesson['edu_lesson_id'])) { echo $selected_lesson['edu_lesson_id']; } ?>" />
                                    <?php } ?>

                                    <div class="form-group">
                                        <label class="control-label col-sm-2" for="l_order">Order:</label>
                                        <div class="col-sm-10">
                                            <input type="text" name="l_order" class="form-control" id="l_order" value="<?php if(isset($selected_lesson['lesson_order'])) { echo $selected_lesson['lesson_order']; } ?>" placeholder="Order" required/>
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <label class="control-label col-sm-2" for="l_title">Title:</label>
                                        <div class="col-sm-10">
                                            <input type="text" name="l_title" class="form-control" id="l_title" value="<?php if(isset($selected_lesson['title'])) { echo $selected_lesson['title']; } ?>" placeholder="Title" required/>
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <label class="control-label col-sm-2" for="l_content">Description:</label>
                                        <div class="col-sm-10"><textarea name="l_content" id="l_content" rows="3" class="form-control"><?php if(isset($selected_lesson['content'])) { echo $selected_lesson['content']; } ?></textarea></div>
                                    </div>

                                    <div class="form-group">
                                        <label class="control-label col-sm-2" for="status">Status:</label>
                                        <div class="col-sm-10 col-lg-5">
                                            <div class="radio">
                                                <label><input type="radio" name="l_status" value="1" <?php if($selected_lesson['status'] == '1') { echo "checked"; } ?> required>Draft</label>
                                            </div>
                                            <div class="radio">
                                                <label><input type="radio" name="l_status" value="2" <?php if($selected_lesson['status'] == '2') { echo "checked"; } ?> required>Published</label>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <div class="col-sm-offset-2 col-sm-10">
                                            <button type="button" data-target="#save-lesson-confirm" data-toggle="modal" class="btn btn-success"><i class="fa fa-save fa-fw"></i> Save Lesson</button>
                                        </div>
                                    </div>

                                    <!-- Modal - confirmation boxes -->
                                    <div id="save-lesson-confirm" tabindex="-1" role="dialog" aria-hidden="true" class="modal fade">
                                        <div class="modal-dialog">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <button type="button" data-dismiss="modal" aria-hidden="true"
                                                        class="close">&times;</button>
                                                    <h4 class="modal-title">Save Lesson Confirmation</h4></div>
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