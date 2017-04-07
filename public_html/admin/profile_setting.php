<?php
require_once("../init/initialize_admin.php");
if (!$session_admin->is_logged_in()) {
    redirect_to("login.php");
}

if (isset($_POST['profile_settings'])) {
    foreach($_POST as $key => $value) {
        $_POST[$key] = $db_handle->sanitizePost(trim($value));
    }

    extract($_POST);

    if(empty($current_password) || empty($new_password) || empty($retype_password)) {
        $message_error = "All fields must be filled, please try again";
    } elseif($new_password !== $retype_password) {
        $message_error = "Your new password does not match the retype, please try again";
    } elseif(!$admin_object->get_admin_detail($email, $current_password)) {
        $message_error = "You have entered an invalid current password, please try again";
    } else {
        if($admin_object->update_admin_password($email, $new_password)) {
            $message_success = "You have successfully updated your profile, your new password will become active on your next login";
        } else {
            $message_error = "An error occurred, password update not successful";
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
        <title>Instaforex Nigeria | Admin - Profile Setting</title>
        <meta name="title" content="Instaforex Nigeria | Admin - Profile Setting" />
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
                                
                                <p>You can update your profile and change your password below.</p>
                                
                                <form data-toggle="validator" class="form-horizontal" role="form" method="post" action="<?php echo $REQUEST_URI; ?>">
                                    <input name="email" type="hidden" value="<?php if(isset($_SESSION['admin_email'])) { echo $_SESSION['admin_email']; } ?>">
                                    <input name="pass_dummy" type="password" class="form-control" style="display: none;">
                                    <div class="form-group">
                                        <label class="control-label col-sm-3" for="first_name">First Name:</label>
                                        <div class="col-sm-9 col-lg-5">
                                            <p class="form-control-static"><?php if(isset($_SESSION['admin_first_name'])) { echo $_SESSION['admin_first_name']; } ?></p>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="control-label col-sm-3" for="last_name">Last Name:</label>
                                        <div class="col-sm-9 col-lg-5">
                                            <p class="form-control-static"><?php if(isset($_SESSION['admin_last_name'])) { echo $_SESSION['admin_last_name']; } ?></p>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="control-label col-sm-3" for="email_address">Email Address:</label>
                                        <div class="col-sm-9 col-lg-5">
                                            <p class="form-control-static"><?php if(isset($_SESSION['admin_email'])) { echo $_SESSION['admin_email']; } ?></p>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="control-label col-sm-3" for="current_password">Current Password:</label>
                                        <div class="col-sm-9 col-lg-5">
                                            <input name="current_password" type="password" class="form-control" id="current_password" value="" required>
                                            <div class="help-block with-errors"></div>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="control-label col-sm-3" for="new_password">New Password:</label>
                                        <div class="col-sm-9 col-lg-5">
                                            <input name="new_password" type="password" data-minlength="6" class="form-control" id="new_password" value="" required>
                                            <span class="help-block">Minimum of 6 characters</span>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="control-label col-sm-3" for="retype_password">Retype Password:</label>
                                        <div class="col-sm-9 col-lg-5">
                                            <input name="retype_password" type="password" class="form-control" id="retype_password" value="" required>
                                            <div class="help-block with-errors"></div>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <div class="col-sm-offset-3 col-sm-9">
                                            <input name="profile_settings" type="submit" class="btn btn-success" value="Update Profile" /> 
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