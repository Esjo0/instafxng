<?php
require_once("../init/initialize_admin.php");
if (!$session_admin->is_logged_in()){    redirect_to("login.php");}
if(isset($_POST['process_target']))
{
    $title = $db_handle->sanitizePost(trim($_POST['title']));
    $description = $db_handle->sanitizePost(trim($_POST['description']));
    $window_period = $db_handle->sanitizePost(trim($_POST['from_date']))." - ".$db_handle->sanitizePost(trim($_POST['to_date']));
    $reportees = implode(',', $_POST['reportees']);
    $new_target = $obj_rms->set_target($title, $description, $window_period, $_SESSION['admin_unique_code'], $reportees);
    $new_target ? $message_success = "New target created." : $message_error = "Opertion Failed. Please try again.";
}
$admin_members = $admin_object->get_all_admin_member();
$created_targets = $obj_rms->get_created_targets($_SESSION['admin_unique_code']);
$_reportees = $obj_rms->get_reportees($_SESSION['admin_unique_code']);



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
                        <div class="row">
                            <div class="col-sm-12">
                            <?php require_once 'layouts/feedback_message.php'; ?>
                            <p class="pull-right"><button data-target="#confirm-add-admin" data-toggle="modal" class="btn btn-sm btn-default"><i class="glyphicon glyphicon-plus"></i> New Target</button></p>
                            <div id="confirm-add-admin" tabindex="-1" role="dialog" aria-hidden="true" class="modal fade">
                                <div class="modal-dialog modal-lg">
                                    <div class="modal-content">
                                        <form data-toggle="validator" role="form" method="post" action="">
                                            <div class="modal-header">
                                                <button type="button" data-dismiss="modal" aria-hidden="true"  class="close">&times;</button>
                                                <h4 class="modal-title">New Target</h4></div>
                                            <div class="modal-body">
                                                <div class="row">
                                                    <div class="col-sm-12">
                                                        <div class="form-group">
                                                            <input type="text" name="title" placeholder="Target Title" class="form-control" />
                                                        </div>
                                                        <div class="form-group">
                                                            <textarea name="description" placeholder="Target Description" class="form-control" rows="4"></textarea>
                                                        </div>
                                                        <div class="form-group">
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
                                                        <div class="form-group">
                                                            <?php foreach ($_reportees as $row2){;?>
                                                                <!--Reviewer-->
                                                                <div class="col-sm-6"><div class="checkbox"><label for=""><input type="checkbox" name="reportees[]" value="<?php echo $row2?>" <?php if (in_array(1, $my_pages)) { echo 'checked="checked"'; } ?>/><?php echo $admin_object->get_admin_name_by_code($row2);?></label></div></div>
                                                                <!--Reviewer-->
                                                            <?php } ?>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="modal-footer">
                                                <input name="process_target" type="submit" class="btn btn-sm btn-success" value="Proceed">
                                                <button type="button" name="close" onClick="window.close();" data-dismiss="modal" class="btn btn-sm btn-danger">Close!</button>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
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
                                        <td>
                                            <a href="javascript:void(0);"><b><?php echo $row['title']?></b></a>
                                            <div id="details_<?php echo $row['target_id']?>" tabindex="-1" role="dialog" aria-hidden="true" class="modal fade">
                                                <div class="modal-dialog modal-lg">
                                                    <div class="modal-content">
                                                        <form data-toggle="validator" role="form" method="post" action="">
                                                            <div class="modal-header">
                                                                <button type="button" data-dismiss="modal" aria-hidden="true"  class="close">&times;</button>
                                                                <h4 class="modal-title"><?php echo $row['title']?></h4></div>
                                                            <div class="modal-body">
                                                                <div class="row">
                                                                    <div class="col-sm-12">
                                                                        <p><b>Target Title:</b> <?php echo $row['title']?></p>
                                                                        <p><b>Target Description:</b> <?php echo $row['description']?></p>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <div class="modal-footer">
                                                                <input name="process_target" type="submit" class="btn btn-sm btn-success" value="Proceed">
                                                                <button type="button" name="close" onClick="window.close();" data-dismiss="modal" class="btn btn-sm btn-danger">Close!</button>
                                                            </div>
                                                        </form>
                                                    </div>
                                                </div>
                                            </div>
                                        </td>
                                        <td>
                                            <?php $reportees = explode(',', $row['reportees']);
                                                foreach($reportees as $key) {echo "<a href=''>".$admin_object->get_admin_name_by_code($key)."</a><br/>";} ?>
                                        </td>
                                        <td>
                                            <?php $window_period = explode('-', $row['window_period']);
                                            echo date_to_text($window_period[0])."  --->  ".date_to_text($window_period[1]); ?>
                                        </td>
                                        <td><?php echo datetime_to_text($row['created'])?></td>
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