<?php
require_once("../init/initialize_admin.php");
if (!$session_admin->is_logged_in()) {
    redirect_to("login.php");
}

$get_params = allowed_get_params(['x', 'id']);
$snappy_id_encrypted = $get_params['id'];
$snappy_id = dec_enc('decrypt',  $snappy_id_encrypted);

if (isset($_POST['process'])) {
    foreach($_POST as $key => $value) {
        $_POST[$key] = $db_handle->sanitizePost(trim($value));
    }
    
    $content = $_POST['content'];
    $snappy_no = $_POST['snappy_help_no'];
    $snappy_status = $_POST['snappy_status'];
    
    if(empty($content)) {
        $message_error = "All fields must be filled, please try again";
    } else {
        $new_snappy_help = $system_object->add_new_snappy_help($snappy_no, $content, $snappy_status, $_SESSION['admin_unique_code']);
        
        if($new_snappy_help) {
            $message_success = "You have successfully saved the snappy help";
        } else {
            $message_error = "Looks like something went wrong or you didn't make any change.";
        }
    }
}

if($get_params['x'] == 'edit') {
    if(!empty($snappy_id)) {
        $selected_snappy_help = $system_object->get_snappy_help_by_id($snappy_id);
        $selected_snappy_help = $selected_snappy_help[0];
    }
}

?>
<!DOCTYPE html>
<html lang="en">
    <head>
        <base target="_self">
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>Instaforex Nigeria | Admin - Add Snappy Help</title>
        <meta name="title" content="Instaforex Nigeria | Admin - Add Snappy Help" />
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
                            <h4><strong>ADD SNAPPY HELP</strong></h4>
                        </div>
                    </div>
                    
                    <div class="section-tint super-shadow">
                        <div class="row">
                            <div class="col-sm-12">
                                <?php require_once 'layouts/feedback_message.php'; ?>
                                <p><a href="snappy_help_manage.php" class="btn btn-default" title="Manage Snappy Help"><i class="fa fa-arrow-circle-left"></i> Manage Snappy Help</a></p>
                                <p>Add a new Snappy Help. This is a short help tip displayed across the website.</p>
                                
                                <form data-toggle="validator" class="form-horizontal" enctype="multipart/form-data" role="form" method="post" action="<?php echo $_SERVER['REQUEST_URI']; ?>">
                                    <input type="hidden" name="snappy_help_no" value="<?php if(isset($selected_snappy_help['snappy_help_id'])) { echo $selected_snappy_help['snappy_help_id']; } ?>" />
                                    <div class="form-group">
                                        <label class="control-label col-sm-2" for="message">Content:</label>
                                        <div class="col-sm-10">
                                            <textarea onChange="javascript:check_count();document.form1.count_display.value=document.form1.message.value.length;check_count();" onkeypress="document.form1.count_display.value=document.form1.message.value.length;check_count();"  onBlur="document.form1.count_display.value=document.form1.message.value.length;check_count();" name="content" id="message" rows="3" class="form-control" placeholder="Enter Content"  onKeyDown="limitText(this.form.message,this.form.countdown,300);" onKeyUp="limitText(this.form.message,this.form.countdown,300);" ><?php if(isset($selected_snappy_help['content'])) { echo $selected_snappy_help['content']; } ?></textarea>
                                            <small>(Maximum characters: 300)<br>You have <input readonly type="text" name="countdown" size="3" value="300"> characters left.</small>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="control-label col-sm-2" for="status">Status:</label>
                                        <div class="col-sm-10 col-lg-5">
                                            <div class="radio">
                                                <label><input id="venue" type="radio" name="snappy_status" value="1" <?php if($selected_snappy_help['status'] == '1') { echo "checked"; } ?> required>Active</label>
                                            </div>
                                            <div class="radio">
                                                <label><input id="venue" type="radio" name="snappy_status" value="2" <?php if($selected_snappy_help['status'] == '2') { echo "checked"; } ?> required>Inactive</label>
                                            </div>
                                            <div class="radio">
                                                <label><input id="venue" type="radio" name="snappy_status" value="3" <?php if($selected_snappy_help['status'] == '3') { echo "checked"; } ?> required>Draft</label>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <div class="col-sm-offset-2 col-sm-10">
                                            <button type="button" data-target="#save-snappy-confirm" data-toggle="modal" class="btn btn-success"><i class="fa fa-save fa-fw"></i> Save</button>
                                        </div>
                                    </div>

                                    <!-- Modal - confirmation boxes -->
                                    <div id="save-snappy-confirm" tabindex="-1" role="dialog" aria-hidden="true" class="modal fade">
                                        <div class="modal-dialog">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <button type="button" data-dismiss="modal" aria-hidden="true"
                                                        class="close">&times;</button>
                                                    <h4 class="modal-title">Save Snappy Help Confirmation</h4>
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