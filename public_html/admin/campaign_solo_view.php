<?php
require_once("../init/initialize_admin.php");
if (!$session_admin->is_logged_in()) {
    redirect_to("login.php");
}

$get_params = allowed_get_params(['x', 'id']);
$campaign_email_id_encrypted = $get_params['id'];
$campaign_email_id = decrypt_ssl(str_replace(" ", "+", $campaign_email_id_encrypted));
$campaign_email_id = preg_replace("/[^A-Za-z0-9 ]/", '', $campaign_email_id);

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Save this campaign email
    foreach($_POST as $key => $value) {
        $_POST[$key] = $db_handle->sanitizePost(trim($value));
    }
    
    extract($_POST);
    
    if(empty($content) || empty($subject)) {
        $message_error = "All fields must be filled, please try again";
    } else {
        
        if($_POST['process'] == 'Save') {            
            $new_solo_campaign_email = $system_object->add_new_solo_campaign_email($campaign_email_no, $subject, $solo_group, $send_day, $content, $_SESSION['admin_unique_code'], $solo_campaign_email_status);
        } else {
            unset($campaign_email_no);
            $new_solo_campaign_email = $system_object->add_new_solo_campaign_email($campaign_email_no, $subject, $solo_group, $send_day, $content, $_SESSION['admin_unique_code'], $solo_campaign_email_status);
        }
        
        if($new_solo_campaign_email) {
            $message_success = "You have successfully saved the email campaign";
        } else {
            $message_error = "Looks like something went wrong or you didn't make any change.";
        }
            
    }
}

// Confirm that campaign solo group exist before a new email solo campaign is saved
$all_campaign_solo_group = $system_object->get_all_campaign_solo_group();

if(!$all_campaign_solo_group) {
    $message_error = "No solo campaign group created, you must create a solo campaign group before any solo campaign. <a href=\"campaign_solo_group_new.php\" title=\"Create new Solo Campaign Group\">Click here</a> to create one.";
}

if($get_params['x'] == 'edit') {
    if(empty($campaign_email_id)) {
        redirect_to("campaign_solo_all.php"); // cannot find campaign or URL tampered
    } else {
        $selected_solo_campaign_email = $system_object->get_solo_campaign_email_by_id($campaign_email_id);
        $selected_solo_campaign_email = $selected_solo_campaign_email[0];
        
        if(empty($selected_solo_campaign_email)) {
            redirect_to("campaign_solo_all.php"); // cannot find campaign or URL tampered
        }
    }
}

?>
<!DOCTYPE html>
<html lang="en">
    <head>
        <base target="_self">
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>Instaforex Nigeria | Admin - Compose Solo Campaign</title>
        <meta name="title" content="Instaforex Nigeria | Admin - Compose Solo Campaign" />
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
                            <h4><strong>COMPOSE SOLO CAMPAIGN</strong></h4>
                        </div>
                    </div>
                    
                    <div class="section-tint super-shadow">
                        <div class="row">
                            <div class="col-sm-12">
                                <?php require_once 'layouts/feedback_message.php'; ?>
                                <p><a href="campaign_solo_all.php" class="btn btn-default" title="Manage Solo Campaigns"><i class="fa fa-arrow-circle-left"></i> Manage Solo Campaigns</a></p>
                                <p>Create a solo campaign email below. Note: Use [NAME] wherever you want to display client name and [EMAIL] for client email.</p>
                                
                                <form class="form-horizontal" enctype="multipart/form-data" role="form" method="post" action="<?php echo $_SERVER['REQUEST_URI']; ?>">
                                    <input type="hidden" name="campaign_email_no" value="<?php if(isset($selected_solo_campaign_email['campaign_email_solo_id'])) { echo $selected_solo_campaign_email['campaign_email_solo_id']; } ?>" />
                                    <div class="form-group">
                                        <label class="control-label col-sm-2" for="subject">Subject:</label>
                                        <div class="col-sm-10">
                                            <input type="text" name="subject" class="form-control" id="subject" value="<?php if(isset($selected_solo_campaign_email['subject'])) { echo $selected_solo_campaign_email['subject']; } ?>" placeholder="Your Subject" required/>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="control-label col-sm-2" for="solo_group">Solo Group:</label>
                                        <div class="col-sm-10 col-lg-6">
                                            <select name="solo_group" class="form-control" id="category">
                                                <?php if(isset($all_campaign_solo_group) && !empty($all_campaign_solo_group)) { foreach ($all_campaign_solo_group as $row) { ?>
                                                <option value="<?php echo $row['campaign_email_solo_group_id']; ?>" <?php if($selected_solo_campaign_email['solo_group'] == $row['campaign_email_solo_group_id']) { echo "selected='selected'"; } ?>><?php echo $row['group_name']; ?></option>
                                                <?php } } ?>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="control-label col-sm-2" for="send_day">Day to Send:</label>
                                        <div class="col-sm-10">
                                            <input type="text" name="send_day" class="form-control" id="send_day" value="<?php if(isset($selected_solo_campaign_email['day_to_send'])) { echo $selected_solo_campaign_email['day_to_send']; } ?>" placeholder="Send Day" required/>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="control-label col-sm-2" for="content">Message:</label>
                                        <div class="col-sm-10"><textarea name="content" id="content" rows="3" class="form-control"><?php if(isset($selected_solo_campaign_email['content'])) { echo $selected_solo_campaign_email['content']; } ?></textarea></div>
                                    </div>
                                    <div class="form-group">
                                        <label class="control-label col-sm-2" for="status">Status:</label>
                                        <div class="col-sm-10 col-lg-5">
                                            <div class="radio">
                                                <label><input id="venue" type="radio" name="solo_campaign_email_status" value="1" <?php if($selected_solo_campaign_email['status'] == '1') { echo "checked"; } ?> required>Draft</label>
                                            </div>
                                            <div class="radio">
                                                <label><input id="venue" type="radio" name="solo_campaign_email_status" value="2" <?php if($selected_solo_campaign_email['status'] == '2') { echo "checked"; } ?> required>Publish</label>
                                            </div>
                                            <div class="radio">
                                                <label><input id="venue" type="radio" name="solo_campaign_email_status" value="3" <?php if($selected_solo_campaign_email['status'] == '3') { echo "checked"; } ?> required>Inactive</label>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <div class="col-sm-offset-2 col-sm-10">
                                            <button type="button" data-target="#save-email-confirm" data-toggle="modal" class="btn btn-success"><i class="fa fa-save fa-fw"></i> Save</button>
                                        </div>
                                    </div>

                                    <!-- Modal - confirmation boxes -->
                                    <div id="save-email-confirm" tabindex="-1" role="dialog" aria-hidden="true" class="modal fade">
                                        <div class="modal-dialog">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <button type="button" data-dismiss="modal" aria-hidden="true"
                                                        class="close">&times;</button>
                                                    <h4 class="modal-title">Save Email Confirmation</h4></div>
                                                <div class="modal-body">Do you want to save this email now?</div>
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