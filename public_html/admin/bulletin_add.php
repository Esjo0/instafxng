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
    //$content = $db_handle->sanitizePost(trim(str_replace('â€™', "'", $_POST['content'])));
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

        if($new_bulletin)
        {
            if($bulletin_status == '1')
            {
                $title1 = "New Bulletin Added";
                $message = "Bulletin Title: $title <br/>  $content ";
                $recipients = $all_allowed_admin;
                $author = $admin_object->get_admin_name_by_code($_SESSION['admin_unique_code']);
                if(isset($bulletin_no) && !empty($bulletin_no))
                {
                    $source_url = "https://instafxng.com/admin/bulletin_read.php?id=".encrypt($bulletin_no);
                }
                else
                {
                    $query = "SELECT admin_bulletin_id FROM admin_bulletin WHERE title = '$title', content = '$content' ";
                    $bulletin_no = $db_handle->fetchAssoc($db_handle->runQuery($query))[0];
                    $source_url = "https://instafxng.com/admin/bulletin_read.php?id=".encrypt($bulletin_no);
                }
                $notify_support = $obj_push_notification->add_new_notification($title1, $message, $recipients, $author, $source_url);

                foreach ($allowed_admin as $row)
                {
                    $author = $_SESSION['admin_first_name']." ".$_SESSION['admin_last_name'];
                    $destination_details = $admin_object->get_admin_detail_by_code($row);
                    $admin_name = $destination_details['first_name'];
                    $admin_email = $destination_details['email'];
                    $admin_email = $destination_details['email'];
                    $message = htmlentities($content);
                    $message = stripslashes($message);
                    $message = str_replace('â€™', "'", $message);
                    $message = htmlspecialchars_decode(stripslashes(trim($message)));
                    $subject = 'New Bulletin - '.$title;
                    $created = date('d-m-y h:i:s a');
                    $message_final = <<<MAIL
                    <div style="background-color: #F3F1F2">
                        <div style="max-width: 80%; margin: 0 auto; padding: 10px; font-size: 14px; font-family: Verdana;">
                            <img src="https://instafxng.com/images/ifxlogo.png" />
                            <hr />
                            <div style="background-color: #FFFFFF; padding: 15px; margin: 5px 0 5px 0;">
                                <p>Dear $admin_name,</p>
                                <p>$author created a new bulletin.</p>
                                <p><b>BULLETIN TITLE: </b>$title</p>
                                <p><b>MESSAGE: </b><br/>$message</p>
                                <p><b>DATE AND TIME: </b>$created</p>                                
                                <p><a href="https://instafxng.com/admin/">Login to your Admin Cabinet for for more information.</a></p>
                                <br /><br />
                                <p>Best Regards,</p>
                                <p>Instafxng Support,<br />
                                   www.instafxng.com</p>
                                <br /><br />
                            </div>
                            <hr />
                            <div style="background-color: #EBDEE9;">
                                <div style="font-size: 11px !important; padding: 15px;">
                                    <p style="text-align: center"><span style="font-size: 12px"><strong>We"re Social</strong></span><br /><br />
                                        <a href="https://facebook.com/InstaForexNigeria"><img src="https://instafxng.com/images/Facebook.png"></a>
                                        <a href="https://twitter.com/instafxng"><img src="https://instafxng.com/images/Twitter.png"></a>
                                        <a href="https://www.instagram.com/instafxng/"><img src="https://instafxng.com/images/instagram.png"></a>
                                        <a href="https://www.youtube.com/channel/UC0Z9AISy_aMMa3OJjgX6SXw"><img src="https://instafxng.com/images/Youtube.png"></a>
                                        <a href="https://linkedin.com/company/instaforex-ng"><img src="https://instafxng.com/images/LinkedIn.png"></a>
                                    </p>
                                    <p><strong>Head Office Address:</strong> TBS Place, Block 1A, Plot 8, Diamond Estate, Estate Bus-Stop, LASU/Isheri road, Isheri Olofin, Lagos.</p>
                                    <p><strong>Lekki Office Address:</strong> Block A3, Suite 508/509 Eastline Shopping Complex, Opposite Abraham Adesanya Roundabout, along Lekki - Epe expressway, Lagos.</p>
                                    <p><strong>Office Number:</strong> 08139250268, 08083956750</p>
                                    <br />
                                </div>
                                <div style="font-size: 10px !important; padding: 15px; text-align: center;">
                                    <p>This email was sent to you by Instant Web-Net Technologies Limited, the
                                        official Nigerian Representative of Instaforex, operator and administrator
                                        of the website www.instafxng.com</p>
                                    <p>To ensure you continue to receive special offers and updates from us,
                                        please add support@instafxng.com to your address book.</p>
                                </div>
                            </div>
                        </div>
                    </div>
MAIL;
                    $system_object->send_email($subject, $message_final, $admin_email, $admin_name);
                }
            }
            $message_success = "You have successfully saved the bulletin";
        }
        else
            {
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
                                                <div class="col-sm-4">
                                                    <div class="checkbox">
                                                        <label for="">
                                                            <input type="checkbox" name="allowed_admin[]" value="<?php echo $key['admin_code']; ?>" <?php if (in_array($key['admin_code'], $allowed_admin)) { echo 'checked="checked"'; } if($key['full_name'] == 'Toye Oyeleke'){ echo'checked="checked" disabled';} ?>/> <?php echo $key['full_name']; ?>
                                                        </label>
                                                    </div>
                                                </div>
                                            <?php if($key['full_name'] == 'Toye Oyeleke'){?>
                                                <input type="hidden" name="allowed_admin[]" value="<?php echo $key['admin_code']; ?>"/>
                                                <?php } ?>
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