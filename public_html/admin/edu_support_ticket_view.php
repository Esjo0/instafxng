<?php
require_once("../init/initialize_admin.php");

if (!$session_admin->is_logged_in()) {
    redirect_to("login.php");
}

if (isset($_POST['submit_reply'])) {
    foreach ($_POST as $key => $value) {
        $_POST[$key] = $db_handle->sanitizePost(trim($value));
    }
    extract($_POST);

    $support_id = decrypt(str_replace(" ", "+", $support_id));
    $support_id = preg_replace("/[^A-Za-z0-9 ]/", '', $support_id);

    $question_reply = $education_object->set_lesson_support_reply('2', $support_id, $comment_reply, $_SESSION['admin_unique_code'], '2', $client_email, $client_name);

    if($question_reply) {
        $message_success = "You have successfully submitted a reply to this support thread.";
    } else {
        $message_error = "There seems to be a problem somewhere, your reply could not be saved. Please try again.";
    }
}

$get_params = allowed_get_params(['id']);
$support_request_encrypted = $get_params['id'];
$support_request_code = decrypt(str_replace(" ", "+", $support_request_encrypted));
$support_request_code = preg_replace("/[^A-Za-z0-9 ]/", '', $support_request_code);

if(!empty($support_request_code)) {
    $selected_support = $education_object->get_support_request_by_code($support_request_code);

    if(!empty($selected_support)) {
        $selected_responses = $education_object->get_support_answers_by_id($selected_support['user_edu_support_request_id']);
    }
}

?>
<!DOCTYPE html>
<html lang="en">
    <head>
        <base target="_self">
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>Instaforex Nigeria | Admin</title>
        <meta name="title" content="Instaforex Nigeria | Admin" />
        <meta name="keywords" content="" />
        <meta name="description" content="" />
        <?php require_once 'layouts/head_meta.php'; ?>
        <script src="tinymce/tinymce.min.js"></script>
        <script type="text/javascript">
            tinyMCE.init({
                selector: "textarea#comment_reply",
                height: 350,
                theme: "modern",
                relative_urls: false,
                remove_script_host: false,
                convert_urls: true,
                plugins: [],
                toolbar1: "insertfile undo redo | styleselect | bold italic | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | link image",
                toolbar2: "| responsivefilemanager print preview media | forecolor backcolor emoticons",
                image_advtab: true,
                external_filemanager_path: "../filemanager/",
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
                            <h4><strong>VIEW SELECTED COURSE MESSAGE</strong></h4>
                        </div>
                    </div>
                    
                    <div class="section-tint super-shadow">

                        <div class="row">
                            <div class="col-sm-12">
                                <?php require_once 'layouts/feedback_message.php'; ?>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-sm-7">

                                <div class="row">
                                    <div class="col-sm-12">
                                        <table class="table table-responsive table-striped table-bordered table-hover">
                                            <thead>
                                            <tr>
                                                <th></th>
                                                <th></th>
                                            </tr>
                                            </thead>
                                            <tbody>
                                                <tr><td>Client Name</td><td><?php echo $selected_support['full_name']; ?></td></tr>
                                                <tr><td>Email</td><td><?php echo $selected_support['email']; ?></td></tr>
                                                <tr><td>Phone</td><td><?php echo $selected_support['phone']; ?></td></tr>
                                                <tr><td>Course Title</td><td><?php echo $selected_support['course_title']; ?></td></tr>
                                                <tr><td>Lesson Title</td><td><?php echo $selected_support['lesson_title']; ?></td></tr>
                                                <tr><td>Created</td><td><?php echo datetime_to_text($selected_support['created']); ?></td></tr>
                                                <tr><td class="text-danger"><strong>Question</strong></td><td class="text-danger"><strong><?php echo $selected_support['request']; ?></td></strong></tr>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-sm-12">
                                        <form role="form" method="post" action="">
                                            <input type="hidden" name="client_email" value="<?php echo $selected_support['email']; ?>" />
                                            <input type="hidden" name="client_name" value="<?php echo $selected_support['full_name']; ?>" />
                                            <input type="hidden" name="support_id" value="<?php echo encrypt($selected_support['user_edu_support_request_id']); ?>" />
                                            <div class="form-group">
                                                <label for="comment_reply">Post a reply:</label>
                                                <textarea name="comment_reply" class="form-control" rows="5" id="comment_reply"></textarea>
                                            </div>
                                            <div class="form-group">
                                                <input name="submit_reply" type="submit" class="btn btn-success" value="Submit Reply" />
                                            </div>
                                        </form>
                                    </div>
                                </div>

                            </div>
                            <div class="col-sm-5">

                                <div style="max-height: 550px; overflow: auto;">
                                    <?php if(!empty($selected_responses)) {
                                        foreach($selected_responses as $row) { ?>

                                            <div class="row">
                                                <div class="col-sm-12">
                                                    <div class="transaction-remarks">

                                                        <?php if($row['category'] == '1') { $full_name = $education_object->get_client_detail_by_code($row['author']); ?>
                                                            <span id="trans_remark_author" style="font-weight: bold !important; color: green !important;"><?php echo $full_name['full_name']; ?></span>
                                                        <?php } ?>

                                                        <?php if($row['category'] == '2') { $full_name = $education_object->get_admin_detail_by_code($row['author']); ?>
                                                            <span id="trans_remark_author" style="font-weight: bold !important;"><?php echo $full_name['full_name'] . ' - Admin'; ?></span>
                                                        <?php } ?>

                                                        <span id="trans_remark"><?php echo $row['response']; ?></span>
                                                        <span id="trans_remark_date"><?php echo datetime_to_text($row['created']); ?></span>
                                                    </div>
                                                </div>
                                            </div>

                                        <?php } } else { ?>
                                        <p class="text-danger"><em>There are no responses to this support request yet.</em></p>
                                    <?php } ?>
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