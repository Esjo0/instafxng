<?php
require_once("../init/initialize_admin.php");
if (!$session_admin->is_logged_in()) {
    redirect_to("login.php");
}

$get_params = allowed_get_params(['x']);
$project_code_encrypted = $get_params['x'];
$project_code = decrypt(str_replace(" ", "+", $project_code_encrypted));
$project_code = preg_replace("/[^A-Za-z0-9 ]/", '', $project_code);

$query = "SELECT
          project_management_projects.title AS project_title,
          project_management_projects.supervisor_code AS supervisor_code,
          project_management_projects.project_code AS project_code
          FROM project_management_projects
          WHERE project_management_projects.project_code = '$project_code'  LIMIT 1";
$result = $db_handle->runQuery($query);
$result = $db_handle->fetchAssoc($result);


if (isset($_POST['process']))
{
    foreach($_POST as $key => $value)
    {
        $_POST[$key] = $db_handle->sanitizePost(trim($value));
    }
    
    $project_code = $_POST['project_code'];
    $supervisor_code = $_POST['supervisor_code'];
    $content = str_replace('â€™', "'", $_POST['content']);

    if($_FILES["display_picture"]["error"] == UPLOAD_ERR_OK) {
        if(isset($_FILES["display_picture"]["name"])) {
            $tmp_name = $_FILES["display_picture"]["tmp_name"];
            $name = strtolower($_FILES["display_picture"]["name"]);

            // Get file extension of original uploaded file and create a new file name
            $extension = explode(".", $name);

            new_name:
            $name_string = rand_string(25);
            $newfilename = $name_string . '.' . end($extension);
            $display_picture = strtolower($newfilename);

            if(file_exists("../images/blog/$display_picture")) {
                goto new_name;
            }

            move_uploaded_file($tmp_name, "../images/blog/$display_picture");
        }
    }


    $new_report = $obj_project_management->submit_report($_SESSION['admin_unique_code'], $content, $project_code, $supervisor_code);

        if($new_report) {
            $message_success = "You have successfully submitted the report.";
        } else {
            $message_error = "Looks like something went wrong or you didn't make any change.";
        }

}


?>
<!DOCTYPE html>
<html lang="en">
    <head>
        <base target="_self">
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>Instaforex Nigeria | Admin - Add New Project Report</title>
        <meta name="title" content="Instaforex Nigeria | Admin - Add New Project Report" />
        <meta name="keywords" content="" />
        <meta name="description" content="" />
        <?php require_once 'layouts/head_meta.php'; ?>
        <script src="tinymce/tinymce.min.js"></script>
        <script type="text/javascript">
            tinyMCE.init({
                selector: "textarea#content",
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
                external_filemanager_path: "../filemanager/",
                filemanager_title: "Instafxng Filemanager",
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
                <div id="main-body-content-area" class="col-md-8 col-lg-9">
                    
                    <!-- Unique Page Content Starts Here
                    ================================================== -->
                                        
                    <div class="row">
                        <div class="col-sm-12 text-danger">
                            <h4 class="text-center"><strong>PROJECT REPORT</strong></h4>
                        </div>
                    </div>
                    
                    <div class="section-tint super-shadow">
                        <div class="row">
                            <div class="col-sm-12">
                                <?php require_once 'layouts/feedback_message.php'; ?>
                                <p><a onclick="window.history.back()" class="btn btn-default" title="Back"><i class="fa fa-arrow-circle-left"></i> Back</a></p>
                                
                                <p>Create a project report. </p>
                                <p></p>
                                <form data-toggle="validator" class="form-horizontal" enctype="multipart/form-data" role="form" method="post" action="<?php echo $_SERVER['REQUEST_URI']; ?>">
                                    <input type="hidden" name="MAX_FILE_SIZE" value="500000" />
                                    <input type="hidden" name="POST_MAX_SIZE" value="500000" />
                                    <div class="form-group">
                                        <label class="control-label col-sm-2" for="content">Project Title:</label>
                                        <div class="col-sm-10">
                                            <?php foreach ($result as $row) { ?>
                                                <p class="text-center text-justify"> <?php echo $row['project_title']; ?>
                                                    <input name="project_code" type="hidden" value="<?php echo $row['project_code']; ?>" />
                                                </p>
                                                <input name="supervisor_code" type="hidden" value="<?php echo $row['supervisor_code']; ?>"/>
                                            <?php } ?>
                                            <textarea placeholder="Enter your report here..." name="content" id="content" rows="3" class="form-control" required></textarea>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <div class="col-sm-offset-2 col-sm-10">
                                            <button type="button" data-target="#add-report-confirm" data-toggle="modal" class="btn btn-success"><i class="fa fa-send fa-fw"></i> Submit</button>
                                        </div>
                                    </div>

                                    <!-- Modal - confirmation boxes -->
                                    <div id="add-report-confirm" tabindex="-1" role="dialog" aria-hidden="true" class="modal fade">
                                        <div class="modal-dialog">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <button type="button" data-dismiss="modal" aria-hidden="true"
                                                        class="close">&times;</button>
                                                    <h4 class="modal-title">Report Confirmation</h4>
                                                </div>
                                                <div class="modal-body">Are you sure you want to send this report?</div>
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