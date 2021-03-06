<?php
ini_set("xdebug.var_display_max_children", -1);
ini_set("xdebug.var_display_max_data", -1);
ini_set("xdebug.var_display_max_depth", -1);

require_once("../init/initialize_admin.php");
if (!$session_admin->is_logged_in()){    redirect_to("login.php");}

if(isset($_POST['post_comment'])) {
    $comment = $db_handle->sanitizePost(trim($_POST['comment']));
    $r_id = $db_handle->sanitizePost(trim($_POST['report_id']));
    $new_comment = $obj_rms->set_report_comment($r_id, $comment, $_SESSION['admin_unique_code']);
    $new_comment ? $message_success = "New comment added" : $message_error = "Operation failed";
}
$targets = $obj_rms->get_reportees_targets($_SESSION['admin_unique_code']);

if(isset($_POST['selection'])) {$_SESSION['selection'] = $_POST['selection'];}
if(empty($_SESSION['selection'])){$_SESSION['selection'] = 'p_r_php';}
if(isset($_POST['extra']) && !empty($_POST['extra'])){redirect_to('rms.php'.$_POST['extra']);}
$selection = $_SESSION['selection'];

if(isset($_POST['process_report'])) {
    $window_period = $db_handle->sanitizePost(trim($_POST['from_date']))."*".$db_handle->sanitizePost(trim($_POST['to_date']));
    $report = $db_handle->sanitizePost(str_replace('â€™', "'", $_POST['report']));

    $target_id = $db_handle->sanitizePost(trim($_POST['target_id']));
    $status = $db_handle->sanitizePost(trim($_POST['status']));
    $new_report = $obj_rms->set_report($window_period, $_SESSION['admin_unique_code'], $report, $target_id, $status);
    if(count($_FILES['attachments']['name'])) {
        foreach ($_FILES['attachments']['name'] as $key => $value) {
            $tmp_name = $_FILES['attachments']["tmp_name"][$key];
            $name = strtolower($_FILES['attachments']["name"][$key]);
            $dirname = "report_attachments".DIRECTORY_SEPARATOR.$new_report['report_id'];
            if(!is_file("report_attachments")){mkdir("report_attachments");}
            if(!is_file($dirname)){mkdir($dirname);}
            move_uploaded_file($tmp_name, $dirname.DIRECTORY_SEPARATOR.$name);
        }
    }
    $new_report['status'] ? $message_success = "Operation Successful" : $message_error = "Operation Failed";
}

if(isset($_POST['process_update'])) {
    $window_period = $db_handle->sanitizePost(trim($_POST['from_date']))."*".$db_handle->sanitizePost(trim($_POST['to_date']));
    $report = $db_handle->sanitizePost(str_replace('â€™', "'", $_POST['report']));
    $report_id = $db_handle->sanitizePost(trim($_POST['report_id']));
    $target_id = $db_handle->sanitizePost(trim($_POST['target_id']));
    $status = $db_handle->sanitizePost(trim($_POST['status']));
    $new_report = $obj_rms->update_report($window_period, $_SESSION['admin_unique_code'], $report, $target_id, $report_id, $status);
    if(count($_FILES['attachments']['name'])) {
        foreach ($_FILES['attachments']['name'] as $key => $value) {
            $tmp_name = $_FILES['attachments']["tmp_name"][$key];
            $name = strtolower($_FILES['attachments']["name"][$key]);
            $dirname = "report_attachments".DIRECTORY_SEPARATOR.$new_report['report_id'];
            if(!is_file("report_attachments")){mkdir("report_attachments");}
            if(!is_file($dirname)){mkdir($dirname);}
            move_uploaded_file($tmp_name, $dirname.DIRECTORY_SEPARATOR.$name);
        }
    }
    $new_report['status'] ? $message_success = "Operation Successful" : $message_error = "Operation Failed";
}

if(isset($_POST["process_settings"])) {
    $result = $obj_rms->set_reviewers($_POST['admin_code'], implode(',', $_POST['reviewers']));
    if($result){$message_success = "Operation Successful.";}
    else{$message_error = "Sorry the operation failed. Please try again.";}
}

if(isset($_POST['process_target'])) {
    $title = $db_handle->sanitizePost(trim($_POST['title']));
    $description = $db_handle->sanitizePost(trim($_POST['description']));
    $window_period = $db_handle->sanitizePost(trim($_POST['from_date']))." * ".$db_handle->sanitizePost(trim($_POST['to_date']));
    $reportees = implode(',', $_POST['reportees']);
    $new_target = $obj_rms->set_target($title, $description, $window_period, $_SESSION['admin_unique_code'], $reportees);
    $new_target ? $message_success = "New target created." : $message_error = "Opertion Failed. Please try again.";
}

if(isset($_POST['edit_target'])) {
    $target_id = $db_handle->sanitizePost(trim($_POST['target_id']));
    $title = $db_handle->sanitizePost(trim($_POST['title']));
    $description = $db_handle->sanitizePost(trim($_POST['description']));
    $window_period = $db_handle->sanitizePost(trim($_POST['from_date']))." * ".$db_handle->sanitizePost(trim($_POST['to_date']));
    $reportees = implode(',', $_POST['reportees']);
    $new_target = $obj_rms->update_target($target_id, $title, $description, $window_period, $_SESSION['admin_unique_code'], $reportees);
    $new_target ? $message_success = "Target updated." : $message_error = "Opertion Failed. Please try again.";
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
        <script type="text/javascript">
            tinyMCE.init({
                selector: "textarea#report",
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
        <script>function resizeIframe(obj){obj.style.height = obj.contentWindow.document.body.scrollHeight+'px';}</script>
        <script src="tinymce/tinymce.min.js"></script>
        <script type="text/javascript">
            tinyMCE.init({
                selector: "textarea#t-description",
                height: 300,
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
            tinyMCE.init({
                selector: "textarea#report",
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
            function formatFileSize(bytes)
            {
                if(bytes == 0) return '0 Bytes';
                var k = 1000,
                    dm = 1,
                    sizes = ['Bytes', 'KB', 'MB', 'GB', 'TB', 'PB', 'EB', 'ZB', 'YB'],
                    i = Math.floor(Math.log(bytes) / Math.log(k));
                return parseFloat((bytes / Math.pow(k, i)).toFixed(dm)) + ' ' + sizes[i];
            }
            function add_file_to_table(file_input, file_table)
            {
                var file_selector = document.getElementById(file_input);
                var file_list = document.getElementById(file_table);
                var i, j;
                for (i = 0; i < file_selector.files.length; ++i)
                {
                    j = file_selector.files[i].name;
                    var row = file_list.insertRow(0);
                    var cell2 = row.insertCell(0);
                    var cell3 = row.insertCell(1);
                    cell2.innerHTML = file_selector.files[i].name;
                    cell3.innerHTML = formatFileSize(file_selector.files[i].size);
                }
            }
            function show_form(div)
            {
                var x = document.getElementById(div);
                if (x.style.display === 'none')
                {
                    x.style.display = 'block';
                    document.getElementById('trigger').innerHTML = '<i class="glyphicon glyphicon-arrow-up"></i>';
                }
                else
                {
                    x.style.display = 'none';
                    document.getElementById('trigger').innerHTML = '<i class="glyphicon glyphicon-arrow-down"></i>';
                }
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
                                        <a onclick="document.getElementById('p_r_php').click();" href="javascript:void(0);">
                                            <div class="panel-footer">
                                                <span class="pull-left">Manage Personal Reports</span>
                                                <span class="pull-right"><i class="fa fa-arrow-circle-right"></i></span>
                                                <div class="clearfix"></div>
                                            </div>
                                        </a>
                                        <div style="display: none;">
                                            <form role="form" method="post" action="<?php echo $_SERVER['REQUEST_URI']; ?>">
                                                <button name="selection" id="p_r_php" value="p_r_php" type="submit"></button>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                                <?php if(in_array(254, $_SESSION['user_privilege'])): ?>
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
                                        <a onclick="document.getElementById('s_r_php').click();" href="javascript:void(0);">
                                            <div class="panel-footer">
                                                <span class="pull-left">Manage Staff Reports</span>
                                                <span class="pull-right"><i class="fa fa-arrow-circle-right"></i></span>
                                                <div class="clearfix"></div>
                                            </div>
                                        </a>
                                        <div style="display: none;">
                                            <form role="form" method="post" action="<?php echo $_SERVER['REQUEST_URI']; ?>">
                                                <button name="selection" id="s_r_php" value="s_r_php" type="submit"></button>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                                <?php endif; ?>
                                <?php if(in_array(252, $_SESSION['user_privilege'])): ?>
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
                                        <a onclick="document.getElementById('s_t_php').click();" href="javascript:void(0);">
                                            <div class="panel-footer">
                                                <span class="pull-left">Manage Staff Targets</span>
                                                <span class="pull-right"><i class="fa fa-arrow-circle-right"></i></span>
                                                <div class="clearfix"></div>
                                            </div>
                                        </a>
                                        <div style="display: none;">
                                            <form role="form" method="post" action="<?php echo $_SERVER['REQUEST_URI']; ?>">
                                                <button name="selection" id="s_t_php" value="s_t_php" type="submit"></button>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                                <?php endif; ?>
                                <?php if(in_array(251, $_SESSION['user_privilege'])): ?>
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
                                        <a onclick="document.getElementById('s_php').click();" href="javascript:void(0);">
                                            <div class="panel-footer">
                                                <span class="pull-left">Manage Report Settings</span>
                                                <span class="pull-right"><i class="fa fa-arrow-circle-right"></i></span>
                                                <div class="clearfix"></div>
                                            </div>
                                        </a>
                                        <div style="display: none;">
                                            <form role="form" method="post" action="<?php echo $_SERVER['REQUEST_URI']; ?>">
                                                <button name="selection" id="s_php" value="s_php" type="submit"></button>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                                <?php endif; ?>
                        </div></div>
                    </div>
                    <div class="section-tint super-shadow">
                        <div class="row">
                            <div class="col-sm-12">
                                <?php require_once 'layouts/feedback_message.php'; ?>
                                <?php
                                if($selection == 'p_r_php') {include_once 'views/rms/personal_reports.php';}
                                if($selection == 's_r_php') {include_once 'views/rms/staff_reports.php';}
                                if($selection == 's_t_php') {include_once 'views/rms/staff_targets.php';}
                                if($selection == 's_php') {include_once 'views/rms/settings.php';}
                                if($selection == 'n_r_php') {include_once 'views/rms/new_report.php';}
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