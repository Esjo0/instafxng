<?php
require_once("../init/initialize_admin.php");
if (!$session_admin->is_logged_in()){    redirect_to("login.php");}
if(isset($_POST['post_comment']))
{
    $comment = $db_handle->sanitizePost(trim($_POST['comment']));
    $report_id = $db_handle->sanitizePost(trim($_POST['report_id']));
    $new_comment = $obj_rms->set_report_comment($report_id, $comment, $_SESSION['admin_unique_code']);
    $new_comment ? $message_success = "New comment added" : $message_error = "Operation failed";
}
$pending_reports = $obj_rms->get_pending_reports($_SESSION['admin_unique_code'])
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
                            <h4><strong>MANAGE REPORTS</strong></h4>
                        </div>
                    </div>
                    <div class="section-tint super-shadow">
                        <div class="row">
                            <div class="col-sm-12">
                            <?php require_once 'layouts/feedback_message.php'; ?>
                            <table class="table table-responsive table-striped table-bordered table-hover">
                                <thead>
                                    <tr>
                                        <th>Author</th>
                                        <th>Report</th>
                                        <th>Type</th>
                                        <th>Created</th>
                                        <th></th>
                                    </tr>
                                </thead>
                                <tbody>
                                <?php if(isset($pending_reports) && !empty($pending_reports)) { foreach ($pending_reports as $row) {?>
                                    <tr>
                                        <td><a href="javascript:void(0);"><b><?php echo $admin_object->get_admin_name_by_code($row['admin_code'])?></b></a></td>
                                        <td>
                                            <?php $window_period = explode('*', $row['window_period']); ?>
                                            <b><?php echo $window_period[0]; ?>  <i class='glyphicon glyphicon-arrow-right'></i>  <?php echo $window_period[1]; ?></b>
                                        </td>
                                        <td><?php echo $obj_rms->get_report_type($row['report_id']);?></td>
                                        <td><?php echo datetime_to_text($row['created'])?></td>
                                        <td><center><a class="btn btn_sm btn-info" href="rms_read_report.php?r_id=<?php echo $row['report_id']?>">Read</a></center></td>
                                    </tr>
                                <?php } } else { echo "<tr><td colspan='4' class='text-danger'><em>No results to display</em></td></tr>"; } ?>
                                </tbody>
                            </table>
                            </div>
                        </div>
                    </div>
                    <!-- Unique Page Content Ends Here
                    ================================================== -->
                </div>
            </div>
        </div>
        <?php require_once 'layouts/footer.php'; ?>
        <script src="//cdnjs.cloudflare.com/ajax/libs/moment.js/2.9.0/moment-with-locales.js"></script>
        <script src="//cdn.rawgit.com/Eonasdan/bootstrap-datetimepicker/e8bddc60e73c1ec2475f827be36e1957af72e2ea/src/js/bootstrap-datetimepicker.js"></script>
    </body>
</html>