<?php
require_once("../init/initialize_admin.php");
if (!$session_admin->is_logged_in()) {
    redirect_to("login.php");
}

$get_params = allowed_get_params(['id']);
$campaign_solo_group_id_encrypted = $get_params['id'];
$campaign_solo_group_id = decrypt(str_replace(" ", "+", $campaign_solo_group_id_encrypted));
$campaign_solo_group_id = preg_replace("/[^A-Za-z0-9 ]/", '', $campaign_solo_group_id);

if (isset($_POST['process'])) {
    
    foreach($_POST as $key => $value) {
        $_POST[$key] = $db_handle->sanitizePost(trim($value));
    }
    
    extract($_POST);
    
    if(empty($name_of_group)) {
        $message_error = "All fields are compulsory, please try again.";
    } else {
        $new_solo_group = $system_object->add_new_campaign_solo_group($name_of_group, $campaign_solo_group_no);

        if($new_solo_group) {
            $message_success = "You have successfully saved the campaign solo group.";
        } else {
            $message_error = "Looks like something went wrong or you didn't make any change.";
        }
    }
}

if(!empty($campaign_solo_group_id)) {
    $selected_solo_group = $system_object->get_campaign_solo_group_by_id($campaign_solo_group_id);
    $selected_solo_group = $selected_solo_group[0];
}

?>
<!DOCTYPE html>
<html lang="en">
    <head>
        <base target="_self">
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>Instaforex Nigeria | Admin - Create Campaign Solo Group</title>
        <meta name="title" content="Instaforex Nigeria | Admin - Create Campaign Solo Group" />
        <meta name="keywords" content="" />
        <meta name="description" content="" />
        <?php require_once 'layouts/head_meta.php'; ?>
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
                            <h4><strong>CREATE CAMPAIGN SOLO GROUP</strong></h4>
                        </div>
                    </div>
                    
                    <div class="section-tint super-shadow">
                        <div class="row">
                            <div class="col-sm-12">
                                <?php require_once 'layouts/feedback_message.php'; ?>
                                <p><a href="campaign_solo_group_all.php" class="btn btn-default" title="All Campaign Solo Group"><i class="fa fa-arrow-circle-left"></i> Campaign Solo Group</a></p>
                                
                                <p>Create a campaign solo group.</p>
                                
                                <form data-toggle="validator" class="form-horizontal" role="form" method="post" action="">
                                    <input type="hidden" name="campaign_solo_group_no" value="<?php if(isset($selected_solo_group['campaign_email_solo_group_id'])) { echo $selected_solo_group['campaign_email_solo_group_id']; } ?>" />
                                    <div class="form-group">
                                        <label class="control-label col-sm-2" for="name_of_group">Group Name:</label>
                                        <div class="col-sm-10 col-lg-8">
                                            <input type="text" name="name_of_group" class="form-control" id="name_of_group" value="<?php if(isset($selected_solo_group['group_name'])) { echo $selected_solo_group['group_name']; } ?>" placeholder="Solo Group Name" required/>
                                        </div>
                                    </div>
                                    
                                    <div class="form-group">
                                        <div class="col-sm-offset-2 col-sm-10">
                                            <button type="button" data-target="#save-category-confirm" data-toggle="modal" class="btn btn-success"><i class="fa fa-save fa-fw"></i> Save Solo Group</button>
                                        </div>
                                    </div>

                                    <!-- Modal - confirmation boxes -->
                                    <div id="save-category-confirm" tabindex="-1" role="dialog" aria-hidden="true" class="modal fade">
                                        <div class="modal-dialog">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <button type="button" data-dismiss="modal" aria-hidden="true"
                                                        class="close">&times;</button>
                                                    <h4 class="modal-title">Save Campaign Solo Group</h4></div>
                                                <div class="modal-body">Do you want to save this campaign solo group?</div>
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