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
                            <h4><strong>CREATE NEW TARGET</strong></h4>
                        </div>
                    </div>
                    <div class="section-tint super-shadow">
                        <div class="row">
                            <?php require_once 'layouts/feedback_message.php'; ?>
                            <form data-toggle="validator" class="form-horizontal" enctype="multipart/form-data" role="form" method="post" action="<?php echo $_SERVER['REQUEST_URI']; ?>">
                                <div style="display: none" class="form-group">
                                    <div class="col-sm-12">
                                        <textarea placeholder="Enter your report here..." name="content" id="content" rows="5" class="form-control" required></textarea>
                                    </div>
                                </div>
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
                                <div class="form-group">
                                    <?php foreach ($admin_members as $row2){ ?>
                                        <!--Reviewer-->
                                        <div class="col-sm-4"><div class="checkbox"><label for=""><input type="checkbox" name="reviewers[]" value="<?php echo $row2['admin_code']?>" <?php if (in_array(1, $my_pages)) { echo 'checked="checked"'; } ?>/><?php echo $row2['full_name']?></label></div></div>
                                        <!--Reviewer-->
                                    <?php } ?>
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
                    <!-- Unique Page Content Ends Here
                    ================================================== -->
                </div>
            </div>
        </div>
        <?php require_once 'layouts/footer.php'; ?>
    </body>
</html>