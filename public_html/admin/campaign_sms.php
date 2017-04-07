<?php
require_once("../init/initialize_admin.php");
if (!$session_admin->is_logged_in()) {
    redirect_to("login.php");
}

$get_params = allowed_get_params(['x', 'id']);
$campaign_sms_id_encrypted = $get_params['id'];
$campaign_sms_id = decrypt(str_replace(" ", "+", $campaign_sms_id_encrypted));
$campaign_sms_id = preg_replace("/[^A-Za-z0-9 ]/", '', $campaign_sms_id);

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    foreach($_POST as $key => $value) {
        $_POST[$key] = $db_handle->sanitizePost(trim($value));
    }

    extract($_POST);

    if(empty($content) || empty($campaign_sms_status)) {
        $message_error = "All fields must be filled, please try again";
    } else {

        if(isset($_POST['process_save']) && !empty($_POST['process_save'])) {
            $new_campaign_sms = $system_object->add_new_campaign_sms($campaign_sms_no, $category, $content, $_SESSION['admin_unique_code'], $campaign_sms_status);
        }

        if(isset($_POST['process_savenew']) && !empty($_POST['process_savenew'])) {
            unset($campaign_sms_no);
            $new_campaign_sms = $system_object->add_new_campaign_sms($campaign_sms_no, $category, $content, $_SESSION['admin_unique_code'], $campaign_sms_status);
        }

        if($new_campaign_sms) {
            $message_success = "You have successfully saved the email campaign";
        } else {
            $message_error = "Looks like something went wrong or you didn't make any change.";
        }

    }

}

// Confirm that campaign category exist before a new sms campaign is saved
$all_campaign_category = $system_object->get_all_campaign_category();

if(!$all_campaign_category) {
    $message_error = "No campaign category created, you must create a category before any campaign. <a href=\"campaign_new_category.php\" title=\"Create new category\">Click here</a> to create one.";
}

if($get_params['x'] == 'edit') {

    $selected_campaign_sms = $system_object->get_campaign_sms_by_id($campaign_sms_id);

    if(!empty($campaign_sms_id)) {
        if($selected_campaign_sms['send_status'] != '2') {
            redirect_to("campaign_sms_view.php"); // campaign sent and cannot be edited or URL tampered
        }
    } else {
        redirect_to("campaign_sms_view.php"); // cannot find campaign or URL tampered
    }
}

?>
<!DOCTYPE html>
<html lang="en">
    <head>
        <base target="_self">
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>Instaforex Nigeria | Admin - Compose SMS</title>
        <meta name="title" content="Instaforex Nigeria | Admin - Compose SMS" />
        <meta name="keywords" content="" />
        <meta name="description" content="" />
        <?php require_once 'layouts/head_meta.php'; ?>
        
        <script language="javascript" type="text/javascript">
            function limitText(limitField, limitCount, limitNum) {
                if (limitField.value.length > limitNum) {
                        limitField.value = limitField.value.substring(0, limitNum);
                } else {
                        limitCount.value = limitNum - limitField.value.length;
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
                            <h4><strong>COMPOSE SMS</strong></h4>
                        </div>
                    </div>
                    
                    <div class="section-tint super-shadow">
                        <div class="row">
                            <div class="col-sm-12">
                                <?php require_once 'layouts/feedback_message.php'; ?>
                                <p><a href="campaign_sms_view.php" class="btn btn-default" title="Manage SMS Campaigns"><i class="fa fa-arrow-circle-left"></i> Manage SMS Campaigns</a></p>
                                <p>Compose an SMS campaign below. Every SMS campaign must belong to a campaign, select the appropriate category for this campaign.</p>
                                
                                <form data-toggle="validator" class="form-horizontal" enctype="multipart/form-data" role="form" method="post" action="">
                                    <input type="hidden" name="campaign_sms_no" value="<?php if(isset($selected_campaign_sms['campaign_sms_id'])) { echo $selected_campaign_sms['campaign_sms_id']; } ?>" />
                                    <div class="form-group">
                                        <label class="control-label col-sm-2" for="category">Category:</label>
                                        <div class="col-sm-10 col-lg-6">
                                            <select name="category" class="form-control" id="category">
                                                <?php if(isset($all_campaign_category) && !empty($all_campaign_category)) { foreach ($all_campaign_category as $row) { ?>
                                                <option value="<?php echo $row['campaign_category_id']; ?>" <?php if(isset($selected_campaign_sms['campaign_category_id']) && $row['campaign_category_id'] == $selected_campaign_sms['campaign_category_id']) { echo "selected='selected'"; } ?>><?php echo $row['title']; ?></option>
                                                <?php } } ?>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="control-label col-sm-2" for="message">Message:</label>
                                        <div class="col-sm-10">
                                            <textarea onChange="javascript:check_count();document.form1.count_display.value=document.form1.message.value.length;check_count();" onkeypress="document.form1.count_display.value=document.form1.message.value.length;check_count();"  onBlur="document.form1.count_display.value=document.form1.message.value.length;check_count();" name="content" id="message" rows="3" class="form-control" placeholder="Enter Message"  onKeyDown="limitText(this.form.message,this.form.countdown,320);" onKeyUp="limitText(this.form.message,this.form.countdown,320);" required><?php if(isset($selected_campaign_sms['content'])) { echo $selected_campaign_sms['content']; } ?></textarea>
                                            <small>(Maximum characters: 320)<br>You have <input readonly type="text" name="countdown" size="3" value="320"> characters left.</small>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="control-label col-sm-2" for="campaign_sms_status">Status:</label>
                                        <div class="col-sm-10 col-lg-5">
                                            <div class="radio">
                                                <label><input type="radio" name="campaign_sms_status" value="1" <?php if($selected_campaign_sms['status'] == '1') { echo "checked"; } ?> required>Draft</label>
                                            </div>
                                            <div class="radio">
                                                <label><input type="radio" name="campaign_sms_status" value="2" <?php if($selected_campaign_sms['status'] == '2') { echo "checked"; } ?> required>Publish</label>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <div class="col-sm-offset-2 col-sm-10">
                                            <button type="button" data-target="#save-sms-confirm" data-toggle="modal" class="btn btn-success"><i class="fa fa-save fa-fw"></i> Save</button>
                                            <button type="button" data-target="#savenew-sms-confirm" data-toggle="modal" class="btn btn-info"><i class="fa fa-save fa-fw"></i> Save As New</button>
                                        </div>
                                    </div>

                                    <!-- Modal - confirmation boxes -->
                                    <div id="save-sms-confirm" tabindex="-1" role="dialog" aria-hidden="true" class="modal fade">
                                        <div class="modal-dialog">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <button type="button" data-dismiss="modal" aria-hidden="true"
                                                            class="close">&times;</button>
                                                    <h4 class="modal-title">Save SMS Confirmation</h4></div>
                                                <div class="modal-body">Do you want to save this SMS now?</div>
                                                <div class="modal-footer">
                                                    <input name="process_save" type="submit" class="btn btn-success" value="Save">
                                                    <button type="submit" name="decline" onClick="window.close();" data-dismiss="modal" class="btn btn-danger">Close !</button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div id="savenew-sms-confirm" tabindex="-1" role="dialog" aria-hidden="true" class="modal fade">
                                        <div class="modal-dialog">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <button type="button" data-dismiss="modal" aria-hidden="true"
                                                            class="close">&times;</button>
                                                    <h4 class="modal-title">Save SMS Confirmation</h4></div>
                                                <div class="modal-body">Do you want to save SMS as new?</div>
                                                <div class="modal-footer">
                                                    <input name="process_savenew" type="submit" class="btn btn-success" value="Save As New">
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