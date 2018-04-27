<?php
require_once("../init/initialize_admin.php");
if (!$session_admin->is_logged_in())
{
    redirect_to("login.php");
}

if(isset($_POST['process']))
{
    $window_period = $db_handle->sanitizePost(trim($_POST['from_date']))."*".$db_handle->sanitizePost(trim($_POST['to_date']));
    $report = $db_handle->sanitizePost(trim($_POST['content']));
    $target_id = $db_handle->sanitizePost(trim($_POST['target_id']));
    $new_report = $obj_rms->set_report($window_period, $_SESSION['admin_unique_code'], $report, $target_id);
    $new_report ? $message_success = "Operation Successful" : $message_error = "Operation Failed";
}
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
                            <h4><strong>CREATE REPORT</strong></h4>
                        </div>
                    </div>
                    <div class="section-tint super-shadow">
                        <div class="row">
                            <div class="col-sm-12">
                                <?php require_once 'layouts/feedback_message.php'; ?>
                                <a href="rms_reports.php" class="btn btn-default" title="Previous Reports"><i class="fa fa-arrow-circle-left"></i> Previous Reports</a>
                                <p>Select a date range below and create a report. </p>
                                <form data-toggle="validator" class="form-horizontal" enctype="multipart/form-data" role="form" method="post" action="<?php echo $_SERVER['REQUEST_URI']; ?>">
                                    <?php if(isset($_GET['t_id'])){ ?>
                                        <input name="target_id" type="hidden" value="<?php echo $_GET['t_id']?>">
                                    <?php } ?>
                                    <?php if(isset($_GET['t_id']) && !empty(isset($_GET['t_id']))):
                                        $target_details = $obj_rms->get_target_by_id($_GET['t_id']);
                                        $target_details = explode('*', $target_details['window_period']);
                                        ?>
                                        <input name="from_date" type="hidden" value="<?php echo $target_details[0]?>">
                                        <input name="to_date" type="hidden" value="<?php echo date('Y-m-d')?>">
                                        <div class="form-group">
                                            <div class="col-sm-12">
                                                <div class="col-sm-2"></div>
                                                <div class="col-sm-4">
                                                    <div class="input-group date">
                                                        <input disabled placeholder="Date Range(From)" value="<?php echo $target_details[0] ?>"  type="text" class="form-control" id="datetimepicker" required>
                                                        <span class="input-group-addon"><span class="glyphicon glyphicon-calendar"></span></span>
                                                    </div>
                                                </div>
                                                <div class="col-sm-4">
                                                    <div class="input-group date">
                                                        <input disabled value="<?php echo date('Y-m-d'); ?>" type="text" class="form-control" id="datetimepicker2" required>
                                                        <span class="input-group-addon"><span class="glyphicon glyphicon-calendar"></span></span>
                                                    </div>
                                                </div>
                                                <div class="col-sm-2"></div>
                                            </div>
                                        </div>
                                    <?php endif;?>
                                    <?php if(!isset($_GET['t_id'])):?>
                                    <div class="form-group">
                                        <div class="col-sm-12">
                                            <div class="col-sm-2"></div>
                                            <div class="col-sm-4">
                                                <div class="input-group date">
                                                    <input placeholder="Date Range(From)"  name="from_date" type="text" class="form-control" id="datetimepicker" required>
                                                    <script type="text/javascript">
                                                        $(function () {
                                                            $('#datetimepicker').datetimepicker({
                                                                format: 'YYYY-MM-DD'
                                                            });
                                                        });
                                                    </script>
                                                    <span class="input-group-addon"><span class="glyphicon glyphicon-calendar"></span></span>
                                                </div>
                                            </div>
                                            <div class="col-sm-4">
                                                <div class="input-group date">
                                                    <input placeholder="Date Range(To)" name="to_date" type="text" class="form-control" id="datetimepicker2" required>
                                                    <script type="text/javascript">
                                                        $(function () {
                                                            $('#datetimepicker2').datetimepicker({
                                                                format: 'YYYY-MM-DD'
                                                            });
                                                        });
                                                    </script>
                                                    <span class="input-group-addon"><span class="glyphicon glyphicon-calendar"></span></span>
                                                </div>
                                            </div>
                                            <div class="col-sm-2"></div>
                                        </div>
                                    </div>
                                    <?php endif; ?>
                                    <div class="form-group">
                                        <div class="col-sm-12">
                                            <textarea placeholder="Enter your report here..." name="content" id="content" rows="5" class="form-control" required></textarea>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <div class="col-sm-12">
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
        <script src="//cdnjs.cloudflare.com/ajax/libs/moment.js/2.9.0/moment-with-locales.js"></script>
        <script src="//cdn.rawgit.com/Eonasdan/bootstrap-datetimepicker/e8bddc60e73c1ec2475f827be36e1957af72e2ea/src/js/bootstrap-datetimepicker.js"></script>
    </body>
</html>