<?php
require_once("../init/initialize_admin.php");
if (!$session_admin->is_logged_in()){    redirect_to("login.php");}
if(!isset($_GET['r_id']) || empty($_GET['r_id'])) {redirect_to($_SERVER['HTTP_REFERER']);}
$report_details = $obj_rms->get_report_by_id($_GET['r_id']);
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
                            <h4><strong>VIEW REPORT</strong></h4>
                        </div>
                    </div>
                    <div class="section-tint super-shadow">
                        <div class="row">
                            <div class="col-sm-12">
                                <?php require_once 'layouts/feedback_message.php'; ?>
                                <a href="<?php echo $_SERVER['HTTP_REFERER']; ?>" class="btn btn-default" title="Back"><i class="fa fa-arrow-circle-left"></i> Back</a>

                                <p><b>Author: </b><?php echo $admin_object->get_admin_name_by_code($report_details['admin_code']); ?></p>

                                <p><b>Created: </b><?php echo datetime_to_text($report_details['created']); ?></p>

                                <p><b>Period: </b><?php $window_period = explode('*', $report_details['window_period']); ?>
                                    <?php echo $window_period[0]; ?>  <i class='glyphicon glyphicon-arrow-right'></i>  <?php echo $window_period[1]; ?>
                                </p>

                                <p><b>Reviewed: </b>
                                    <?php
                                    $reviewed = explode(',', $report_details['created']);
                                    foreach ($reviewed as $key)
                                    {
                                        echo $admin_object->get_admin_name_by_code($key)."<br/>";
                                    }
                                    ?>
                                </p>

                                <p><b>Attached Files: </b><em>none</em></p>

                                <p><b>Report: </b><?php echo $report_details['report'];?></p>
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