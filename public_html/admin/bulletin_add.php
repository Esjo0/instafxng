<?php
require_once("../init/initialize_admin.php");
if (!$session_admin->is_logged_in()) {
    redirect_to("login.php");
}

$get_params = allowed_get_params(['x', 'id']);
$bulletin_id_encrypted = $get_params['id'];
$bulletin_id = decrypt(str_replace(" ", "+", $bulletin_id_encrypted));
$bulletin_id = preg_replace("/[^A-Za-z0-9 ]/", '', $bulletin_id);

$all_admin_member = $admin_object->get_all_admin_member();

if (isset($_POST['process'])) {
    $title = $db_handle->sanitizePost(trim($_POST['title']));
    $content = $db_handle->sanitizePost(trim(str_replace('â€™', "'", $_POST['content'])));
    $bulletin_no = $db_handle->sanitizePost(trim($_POST['bulletin_no']));
    $bulletin_status = $db_handle->sanitizePost(trim($_POST['bulletin_status']));

    $allowed_admin = $_POST["allowed_admin"];
    for ($i = 0; $i < count($allowed_admin); $i++) {
        $all_allowed_admin = $all_allowed_admin . "," . $allowed_admin[$i];
    }

    $all_allowed_admin = substr_replace($all_allowed_admin, "", 0, 1);

    if(empty($title) || empty($content)) {
        $message_error = "All fields must be filled, please try again";
    } else {
        $new_bulletin = $admin_object->add_new_bulletin($bulletin_no, $title, $content, $bulletin_status, $all_allowed_admin, $_SESSION['admin_unique_code']);

        if($new_bulletin) {
            $message_success = "You have successfully saved the bulletin";
        } else {
            $message_error = "Looks like something went wrong or you didn't make any change.";
        }

    }
}

if($get_params['x'] == 'edit') {
    if(!empty($bulletin_id)) {
        $selected_bulletin = $system_object->get_bulletin_by_id($bulletin_id);
        $selected_bulletin = $selected_bulletin[0];
        $allowed_admin = explode(",", $selected_bulletin['allowed_admin']);
    }
}


?>
<!DOCTYPE html>
<html lang="en">
    <head>
        <base target="_self">
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>Instaforex Nigeria | Admin - Add Bulletin</title>
        <meta name="title" content="Instaforex Nigeria | Admin - Add Bulletin" />
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
                
                <!-- Main Body - Content Area: This is the main content area, unique for each page  -->
                <div id="main-body-content-area" class="col-md-8 col-lg-9">
                    
                    <!-- Unique Page Content Starts Here
                    ================================================== -->
                    <div class="row">
                        <div class="col-sm-12 text-danger">
                            <h4><strong>ADD BULLETIN</strong></h4>
                        </div>
                    </div>
                    
                    <div class="section-tint super-shadow">
                        <div class="row">
                            <div class="col-sm-12">
                                <?php require_once 'layouts/feedback_message.php'; ?>
                                <p><a href="bulletin_view.php" class="btn btn-default" title="Manage Bulletin"><i class="fa fa-arrow-circle-left"></i> Manage Bulletin</a></p>
                                <p>Add a new Bulletin. This is a news content readable by Admin members only.</p>
                                
                                <form data-toggle="validator" class="form-horizontal" enctype="multipart/form-data" role="form" method="post" action="<?php echo $_SERVER['REQUEST_URI']; ?>">
                                    <input type="hidden" name="MAX_FILE_SIZE" value="500000" />
                                    <input type="hidden" name="POST_MAX_SIZE" value="500000" />
                                    <input type="hidden" name="bulletin_no" value="<?php if(isset($selected_bulletin['admin_bulletin_id'])) { echo $selected_bulletin['admin_bulletin_id']; } ?>" />
                                    <div class="form-group">
                                        <label class="control-label col-sm-2" for="title">Title:</label>
                                        <div class="col-sm-10">
                                            <input type="text" name="title" class="form-control" id="title" value="<?php if(isset($selected_bulletin['title'])) { echo $selected_bulletin['title']; } ?>" required/>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="control-label col-sm-2" for="content">Content:</label>
                                        <div class="col-sm-10"><textarea name="content" id="content" rows="3" class="form-control" required><?php if(isset($selected_bulletin['content'])) { echo $selected_bulletin['content']; } ?></textarea></div>
                                    </div>
                                    <div class="form-group">
                                        <label class="control-label col-sm-2" for="status">Status:</label>
                                        <div class="col-sm-10 col-lg-5">
                                            <div class="radio">
                                                <label><input id="venue" type="radio" name="bulletin_status" value="1" <?php if($selected_bulletin['status'] == '1') { echo "checked"; } ?> required>Publish</label>
                                            </div>
                                            <div class="radio">
                                                <label><input id="venue" type="radio" name="bulletin_status" value="2" <?php if($selected_bulletin['status'] == '2') { echo "checked"; } ?> required>Draft</label>
                                            </div>
                                            <div class="radio">
                                                <label><input id="venue" type="radio" name="bulletin_status" value="3" <?php if($selected_bulletin['status'] == '3') { echo "checked"; } ?> required>Inactive</label>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="control-label col-sm-2" for="allowed_admin">Allowed Admin:</label>
                                        <div class="col-sm-10">
                                            <?php foreach($all_admin_member AS $key) { ?>
                                                <div class="col-sm-4"><div class="checkbox"><label for=""><input type="checkbox" name="allowed_admin[]" value="<?php echo $key['admin_code']; ?>" <?php if (in_array($key['admin_code'], $allowed_admin)) { echo 'checked="checked"'; } ?>/> <?php echo $key['full_name']; ?></label></div></div>
                                            <?php } ?>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <div class="col-sm-offset-2 col-sm-10">
                                            <button type="button" data-target="#save-bulletin-confirm" data-toggle="modal" class="btn btn-success"><i class="fa fa-save fa-fw"></i> Save</button>
                                        </div>
                                    </div>

                                    <!-- Modal - confirmation boxes -->
                                    <div id="save-bulletin-confirm" tabindex="-1" role="dialog" aria-hidden="true" class="modal fade">
                                        <div class="modal-dialog">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <button type="button" data-dismiss="modal" aria-hidden="true"
                                                        class="close">&times;</button>
                                                    <h4 class="modal-title">Save Bulletin Confirmation</h4>
                                                </div>
                                                <div class="modal-body">Are you sure you want to save this information?</div>
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