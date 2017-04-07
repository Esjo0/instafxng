<?php
require_once("../init/initialize_admin.php");
if (!$session_admin->is_logged_in()) {
    redirect_to("login.php");
}

if (isset($_POST['system_settings'])) {
    foreach($_POST as $key => $value) {
        $_POST[$key] = $db_handle->sanitizePost(trim($value));
    }

    extract($_POST);

    if(empty($set_value)) {
        $message_error = "All fields must be filled, please try again";
    } else {
        if($system_object->update_settings_by_id($set_id, $set_value)) {
            $message_success = "You have successfully updated this setting";
        } else {
            $message_error = "An error occurred, your changes could not be saved.";
        }
    }
}

$get_params = allowed_get_params(['id']);
$settings_id_encrypted = $get_params['id'];

$settings_id = decrypt(str_replace(" ", "+", $settings_id_encrypted));
$settings_id = preg_replace("/[^A-Za-z0-9 ]/", '', $settings_id);
$settings_detail = $system_object->get_settings_by_id($settings_id);
?>
<!DOCTYPE html>
<html lang="en">
    <head>
        <base target="_self">
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>Instaforex Nigeria | Edit System Settings</title>
        <meta name="title" content="Instaforex Nigeria | Edit System Settings" />
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
                            <h4><strong>PROFILE SETTING</strong></h4>
                        </div>
                    </div>
                    
                    <div class="section-tint super-shadow">
                        <div class="row">
                            <div class="col-sm-12">
                                <?php require_once 'layouts/feedback_message.php'; ?>

                                <p>Make change and click the save button.</p>
                                
                                <form data-toggle="validator" class="form-horizontal" role="form" method="post" action="<?php echo $REQUEST_URI; ?>">
                                    <input name="set_id" type="hidden" value="<?php echo $settings_id; ?>">
                                    <div class="form-group">
                                        <label class="control-label col-sm-3" for="describe">Description</label>
                                        <div class="col-sm-9 col-lg-5">
                                            <p class="form-control-static"><?php if(isset($settings_detail['description'])) { echo $settings_detail['description']; } ?></p>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="control-label col-sm-3" for="set_value">Value:</label>
                                        <div class="col-sm-9 col-lg-5">
                                            <input name="set_value" type="text" class="form-control" id="set_value" value="<?php if(isset($settings_detail['value'])) { echo $settings_detail['value']; } ?>" required>
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <div class="col-sm-offset-3 col-sm-9">
                                            <input name="system_settings" type="submit" class="btn btn-success" value="Save" />
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
        <script src="//cdnjs.cloudflare.com/ajax/libs/moment.js/2.9.0/moment-with-locales.js"></script>
        <script src="//cdn.rawgit.com/Eonasdan/bootstrap-datetimepicker/e8bddc60e73c1ec2475f827be36e1957af72e2ea/src/js/bootstrap-datetimepicker.js"></script>
    </body>
</html>