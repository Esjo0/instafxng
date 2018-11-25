<?php
require_once("../init/initialize_admin.php");
if (!$session_admin->is_logged_in()) {
    redirect_to("login.php");
}

$get_params = allowed_get_params(['x', 'id']);
$job_id_encrypted = $get_params['id'];
$job_id = decrypt_ssl(str_replace(" ", "+", $job_id_encrypted));
$job_id = preg_replace("/[^A-Za-z0-9 ]/", '', $job_id);

if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    foreach($_POST as $key => $value) {
        $_POST[$key] = $db_handle->sanitizePost(trim($value));
    }

    extract($_POST);

    if(empty($job_title) || empty($job_content) || empty($job_status)) {
        $message_error = "All fields must be filled, please try again";
    } else {

        $new_job = $admin_object->create_new_job($job_no, $job_title, $job_content, $job_status, $_SESSION['admin_unique_code']);

        if($new_job) {
            $message_success = "You have successfully saved this job";
        } else {
            $message_error = "Looks like something went wrong or you didn't make any change.";
        }
    }
}

if($get_params['x'] == 'edit') {
    $selected_job = $admin_object->get_job_posting_by_id($job_id);
}

?>
<!DOCTYPE html>
<html lang="en">
    <head>
        <base target="_self">
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>Instaforex Nigeria | Admin - Careers New Job</title>
        <meta name="title" content="Instaforex Nigeria | Admin - Careers New Job" />
        <meta name="keywords" content="" />
        <meta name="description" content="" />
        <?php require_once 'layouts/head_meta.php'; ?>
        <script src="tinymce/tinymce.min.js"></script>
        <script type="text/javascript">
            tinyMCE.init({
                selector: "textarea#job_content",
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
                            <h4><strong>CAREERS - CREATE NEW JOB</strong></h4>
                        </div>
                    </div>
                    
                    <div class="section-tint super-shadow">
                        <div class="row">
                            <div class="col-sm-12">
                                <?php require_once 'layouts/feedback_message.php'; ?>
                                <p><a href="career_all_job.php" class="btn btn-default" title="All Job Listing"><i class="fa fa-arrow-circle-left"></i> All Job Listing</a></p>

                                <p>Post or modify job opening</p>

                                <form class="form-horizontal" role="form" method="post" action="">
                                    <?php if(isset($selected_job)) { ?>
                                        <input type="hidden" name="job_no" value="<?php if(isset($selected_job['career_jobs_id'])) { echo $selected_job['career_jobs_id']; } ?>" />
                                    <?php } ?>

                                    <div class="form-group">
                                        <label class="control-label col-sm-2" for="job_title">Title:</label>
                                        <div class="col-sm-10">
                                            <input type="text" name="job_title" class="form-control" value="<?php if(isset($selected_job['title'])) { echo $selected_job['title']; } ?>" placeholder="Title" required/>
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <label class="control-label col-sm-2" for="job_content">Description:</label>
                                        <div class="col-sm-10"><textarea id="job_content" name="job_content" rows="3" class="form-control"><?php if(isset($selected_job['detail'])) { echo $selected_job['detail']; } ?></textarea></div>
                                    </div>

                                    <div class="form-group">
                                        <label class="control-label col-sm-2" for="job_status">Status:</label>
                                        <div class="col-sm-10 col-lg-5">
                                            <div class="radio">
                                                <label><input type="radio" name="job_status" value="1" <?php if($selected_job['status'] == '1') { echo "checked"; } ?> required>Closed</label>
                                            </div>
                                            <div class="radio">
                                                <label><input type="radio" name="job_status" value="2" <?php if($selected_job['status'] == '2') { echo "checked"; } ?> required>Open</label>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <div class="col-sm-offset-2 col-sm-10">
                                            <button type="button" data-target="#save-new-job-confirm" data-toggle="modal" class="btn btn-success"><i class="fa fa-save fa-fw"></i> Save Job</button>
                                        </div>
                                    </div>

                                    <!-- Modal - confirmation boxes -->
                                    <div id="save-new-job-confirm" tabindex="-1" role="dialog" aria-hidden="true" class="modal fade">
                                        <div class="modal-dialog">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <button type="button" data-dismiss="modal" aria-hidden="true"
                                                        class="close">&times;</button>
                                                    <h4 class="modal-title">Save Job Confirmation</h4></div>
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