<?php
require_once("../init/initialize_admin.php");
if (!$session_admin->is_logged_in())
{
    redirect_to("login.php");
}
$admin_members = $admin_object->get_all_admin_member();
if(isset($_POST["process"]))
{
    $result = $obj_reporting_system->review_settings($_POST['admin_code'], $_POST['reviewers']);
    if($result){$message_success = "Operation Successful.";}
    else{$message_error = "Sorry the operation failed. Please try again.";}
}

$created_targets = $obj_rms->get_created_targets($_SESSION['admin_unique_code']);
?>
<!DOCTYPE html>
<html lang="en">
    <head>
        <base target="_self">
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>Instaforex Nigeria | Admin Reporting System</title>
        <meta name="title" content="Instaforex Nigeria | Admin Reporting System" />
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
                    "template paste textcolor colorpicker textpattern "
                ],
                toolbar1: "insertfile undo redo | styleselect | bold italic | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | link image",
                toolbar2: "| print preview media | forecolor backcolor emoticons",
                image_advtab: true,
                external_filemanager_path: "../filemanager/",
                filemanager_title: "Instafxng Filemanager",
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
                            <h4><strong>MANAGE TARGETS</strong></h4>
                        </div>
                    </div>
                    <div class="section-tint super-shadow">
                        <p class="pull-right"><button data-target="#confirm-add-admin" data-toggle="modal" class="btn btn-sm btn-default"><i class="glyphicon glyphicon-plus"></i> New Target</button></p>
                        <div id="confirm-add-admin" tabindex="-1" role="dialog" aria-hidden="true" class="modal fade">
                            <div class="modal-dialog modal-lg">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <button type="button" data-dismiss="modal" aria-hidden="true"  class="close">&times;</button>
                                        <h4 class="modal-title">New Target</h4></div>
                                    <div class="modal-body">
                                        <form>
                                        </form>
                                    </div>
                                    <div class="modal-footer">
                                        <input name="process" type="submit" class="btn btn-sm btn-success" value="Proceed">
                                        <button type="button" name="close" onClick="window.close();" data-dismiss="modal" class="btn btn-sm btn-danger">Close!</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <?php require_once 'layouts/feedback_message.php'; ?>
                            <table class="table table-responsive table-striped table-bordered table-hover">
                                <thead>
                                    <tr>
                                        <th>Target</th>
                                        <th>Reportees</th>
                                        <th>Window Period</th>
                                        <th>Created</th>
                                    </tr>
                                </thead>
                                <tbody>
                                <?php if(isset($created_targets) && !empty($created_targets)) { foreach ($created_targets as $row) { ?>
                                    <tr>
                                        <td><?php echo $row['title']?></td>
                                        <td>
                                            <?php $reportees = explode(',', $row['reportees']);
                                                foreach($reportees as $key) {echo $admin_object->get_admin_name_by_code($key)."<br/>";} ?>
                                        </td>
                                        <td><?php echo $row['window_period']?></td>
                                        <td><?php echo datetime_to_text($row['created'])?></td>
                                    </tr>
                                <?php } } else { echo "<tr><td colspan='4' class='text-danger'><em>No results to display</em></td></tr>"; } ?>
                                </tbody>
                            </table>
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