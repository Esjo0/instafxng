<?php
require_once("../init/initialize_admin.php");
if (!$session_admin->is_logged_in()) {
    redirect_to("login.php");
}

$get_params = allowed_get_params(['id']);
$campaign_category_id_encrypted = $get_params['id'];
$campaign_id = decrypt(str_replace(" ", "+", $campaign_category_id_encrypted));
$campaign_id = preg_replace("/[^A-Za-z0-9 ]/", '', $campaign_id);

if (isset($_POST['process'])) {
    
    foreach($_POST as $key => $value) {
        $_POST[$key] = $db_handle->sanitizePost(trim($value));
    }
    
    extract($_POST);
    
    if(empty($title) || empty($description)) {
        $message_error = "All fields are compulsory, please try again.";
    } else {
        $new_category = $system_object->add_new_campaign_category($title, $description, $campaign_category_status, $campaign_category_no, $client_group);

        if($new_category) {
            $message_success = "You have successfully saved the campaign category.";
        } else {
            $message_error = "Looks like something went wrong or you didn't make any change.";
        }
    }
}


if(!empty($campaign_id)) {
    $selected_campaign = $system_object->get_campaign_category_by_id($campaign_id);
    $selected_campaign = $selected_campaign[0];
}

?>
<!DOCTYPE html>
<html lang="en">
    <head>
        <base target="_self">
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>Instaforex Nigeria | Admin - Create Campaign Category</title>
        <meta name="title" content="Instaforex Nigeria | Admin - Create Campaign Category" />
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
                            <h4><strong>CREATE CAMPAIGN CATEGORY</strong></h4>
                        </div>
                    </div>
                    
                    <div class="section-tint super-shadow">
                        <div class="row">
                            <div class="col-sm-12">
                                <?php require_once 'layouts/feedback_message.php'; ?>
                                <p><a href="campaign_all_category.php" class="btn btn-default" title="All Campaign Category"><i class="fa fa-arrow-circle-left"></i> Campaign Category</a></p>
                                
                                <p>Create a campaign category. Every Email or SMS campaign must belong to a category, e.g. a category can
                                    be named "Special Updates". Please enter the title and the description.</p>
                                
                                <form data-toggle="validator" class="form-horizontal" role="form" method="post" action="">
                                    <input type="hidden" name="campaign_category_no" value="<?php if(isset($selected_campaign['campaign_category_id'])) { echo $selected_campaign['campaign_category_id']; } ?>" />
                                    <div class="form-group">
                                        <label class="control-label col-sm-2" for="title">Title:</label>
                                        <div class="col-sm-10 col-lg-8">
                                            <input type="text" name="title" class="form-control" id="title" value="<?php if(isset($selected_campaign['title'])) { echo $selected_campaign['title']; } ?>" placeholder="Category Title" required/>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="control-label col-sm-2" for="description">Description:</label>
                                        <div class="col-sm-10 col-lg-8"><textarea name="description" id="description" rows="3" class="form-control" required="required"><?php if(isset($selected_campaign['description'])) { echo $selected_campaign['description']; } ?></textarea></div>
                                    </div>
                                    <div class="form-group">
                                        <label class="control-label col-sm-2" for="client_group">Client Group:</label>
                                        <div class="col-sm-10 col-lg-5">
                                            
                                            <?php foreach($client_group_DEFAULT as $key => $value) { ?>
                                            <div class="radio">
                                                <label><input type="radio" name="client_group" value="<?php echo $key; ?>" <?php if($key == '1') { echo "checked"; } ?> required><?php echo $value; ?></label>
                                            </div>
                                            <?php } ?>
                                            
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="control-label col-sm-2" for="campaign_category_status">Status:</label>
                                        <div class="col-sm-10 col-lg-5">
                                            <div class="radio">
                                                <label><input type="radio" name="campaign_category_status" value="1" <?php if($selected_campaign['status'] == '1') { echo "checked"; } ?> required>Active</label>
                                            </div>
                                            <div class="radio">
                                                <label><input type="radio" name="campaign_category_status" value="2" <?php if($selected_campaign['status'] == '2') { echo "checked"; } ?> required>Inactive</label>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <div class="col-sm-offset-2 col-sm-10">
                                            <button type="button" data-target="#save-category-confirm" data-toggle="modal" class="btn btn-success"><i class="fa fa-save fa-fw"></i> Save Category</button>
                                        </div>
                                    </div>

                                    <!-- Modal - confirmation boxes -->
                                    <div id="save-category-confirm" tabindex="-1" role="dialog" aria-hidden="true" class="modal fade">
                                        <div class="modal-dialog">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <button type="button" data-dismiss="modal" aria-hidden="true"
                                                        class="close">&times;</button>
                                                    <h4 class="modal-title">Save Campaign Category</h4></div>
                                                <div class="modal-body">Do you want to save this campaign category?</div>
                                                <div class="modal-footer">
                                                    <input name="process" type="submit" class="btn btn-success" value="Save Category">
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