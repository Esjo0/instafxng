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
$pending_reports = $obj_rms->get_past_reports($_SESSION['admin_unique_code']);
$targets = $obj_rms->get_reportees_targets($_SESSION['admin_unique_code']);
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
        <script src="//code.jquery.com/jquery-1.12.4.min.js"></script>
        <script type="text/javascript" src="../js/jquery-popup.js"></script>
        <link href="//jqueryscript.net/css/jquerysctipttop.css" rel="stylesheet" type="text/css">
        <link rel="stylesheet" type="text/css" href="../css/hunterPopup.css">
        <link rel="stylesheet" href="//netdna.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
        <script>
            function resizeIframe(obj) {
                obj.style.height = obj.contentWindow.document.body.scrollHeight + 'px';
            }
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
                            <h4><strong>REPORTS MANAGEMENT</strong></h4>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-sm-12">
                            <div class="row">
                                <div class="col-sm-3">
                                    <div class="panel panel-primary">
                                        <div class="panel-heading">
                                            <div class="row">
                                                <div class="col-xs-3">
                                                    <i class="fa fa-user fa-5x"></i>
                                                </div>
                                                <div class="col-xs-9 text-right">
                                                    <b>Personal Reports</b>
                                                </div>
                                            </div>
                                        </div>
                                        <a href="rms_settings.php">
                                            <div class="panel-footer">
                                                <span class="pull-left">Manage Personal Reports</span>
                                                <span class="pull-right"><i class="fa fa-arrow-circle-right"></i></span>
                                                <div class="clearfix"></div>
                                            </div>
                                        </a>
                                    </div>
                                </div>
                                <div class="col-sm-3">
                                    <div class="panel panel-primary">
                                        <div class="panel-heading">
                                            <div class="row">
                                                <div class="col-xs-3">
                                                    <i class="fa fa-users fa-5x"></i>
                                                </div>
                                                <div class="col-xs-9 text-right">
                                                    <b>Staff Reports</b>
                                                </div>
                                            </div>
                                        </div>
                                        <a href="rms_settings.php">
                                            <div class="panel-footer">
                                                <span class="pull-left">Manage Staff Reports</span>
                                                <span class="pull-right"><i class="fa fa-arrow-circle-right"></i></span>
                                                <div class="clearfix"></div>
                                            </div>
                                        </a>
                                    </div>
                                </div>
                                <div class="col-sm-3">
                                    <div class="panel panel-primary">
                                        <div class="panel-heading">
                                            <div class="row">
                                                <div class="col-xs-3">
                                                    <i class="fa fa-bullseye fa-5x"></i>
                                                </div>
                                                <div class="col-xs-9 text-right">
                                                    <b>Staff Targets</b>
                                                </div>
                                            </div>
                                        </div>
                                        <a href="rms_settings.php">
                                            <div class="panel-footer">
                                                <span class="pull-left">Manage Staff Targets</span>
                                                <span class="pull-right"><i class="fa fa-arrow-circle-right"></i></span>
                                                <div class="clearfix"></div>
                                            </div>
                                        </a>
                                    </div>
                                </div>
                                <div class="col-sm-3">
                                    <div class="panel panel-primary">
                                        <div class="panel-heading">
                                            <div class="row">
                                                <div class="col-xs-3">
                                                    <i class="fa fa-cogs fa-5x"></i>
                                                </div>
                                                <div class="col-xs-9 text-right">
                                                    <b>Settings</b>
                                                </div>
                                            </div>
                                        </div>
                                        <a href="rms_settings.php">
                                            <div class="panel-footer">
                                                <span class="pull-left">Manage Report Settings</span>
                                                <span class="pull-right"><i class="fa fa-arrow-circle-right"></i></span>
                                                <div class="clearfix"></div>
                                            </div>
                                        </a>
                                    </div>
                                </div>
                        </div></div>
                    </div>
                    <div class="section-tint super-shadow">
                        <div class="row">
                            <div class="col-sm-12">
                                <?php require_once 'layouts/feedback_message.php'; ?>
                                <?php
                                $p_r_php = true;
                                if($p_r_php) { include_once 'views/rms/personal_reports.php'; }
                                if($s_r_php) { include_once 'views/rms/staff_reports.php'; }
                                if($s_t_php) { include_once 'views/rms/staff_targets.php'; }
                                if($r_s_php) { include_once ''; }
                                ?>
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