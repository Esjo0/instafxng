<?php
require_once("../init/initialize_admin.php");
if (!$session_admin->is_logged_in()) {
    redirect_to("login.php");
}

// Add New Admin User - Process submitted form
if (isset($_POST['process'])&& $_POST['process'] == 'Proceed') {
    
    foreach($_POST as $key => $value) {
        $_POST[$key] = $db_handle->sanitizePost(trim($value));
    }

    extract($_POST);
    
    $partner_id = decrypt_ssl($partner_id);
    $application_modified = $partner_object->modify_partner_application($partner_id, $partner_status);
    
    if($application_modified) {
        $message_success = "The admin profile has been updated.";
    } else {
        $message_error = "Something went wrong, the application could not be updated.";
    }
}

$get_params = allowed_get_params(['id']);
$partner_code_encrypted = $get_params['id'];

$partner_code = decrypt_ssl($partner_code_encrypted);
$partner_application = $partner_object->get_selected_pending_application($partner_code);

if(!$partner_application) {
    redirect_to("partner_new.php");
}

?>
<!DOCTYPE html>
<html lang="en">
    <head>
        <base target="_self">
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>Instaforex Nigeria | Admin - Manage Partner Application</title>
        <meta name="title" content="Instaforex Nigeria | Admin - Manage Partner Application" />
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
                            <h4><strong>MANAGE PARTNER APPLICATION</strong></h4>
                        </div>
                    </div>
                    
                    <div class="section-tint super-shadow">
                        <div class="row">
                            <div class="col-sm-12">
                                <p><a href="partner_new.php" class="btn btn-default" title="Go back to View Application"><i class="fa fa-arrow-circle-left"></i> Go Back - View Applications</a></p>
                                
                                <?php require_once 'layouts/feedback_message.php'; ?>
                                <p>Modify the selected payment application.</p>

                                <form data-toggle="validator" class="form-horizontal" role="form" method="post" action="<?php echo $_SERVER['REQUEST_URI']; ?>">
                                    <input name="partner_id" type="hidden" value="<?php echo $partner_code_encrypted; ?>">
                                    <div class="form-group">
                                        <label class="control-label col-sm-3" for="first_name">First Name:</label>
                                        <div class="col-sm-9 col-lg-5">
                                            <input type="text" class="form-control" id="first_name" name="first_name" value="<?php echo $partner_application['first_name']; ?>">
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="control-label col-sm-3" for="last_name">Last Name:</label>
                                        <div class="col-sm-9 col-lg-5">
                                            <input type="text" class="form-control" id="last_name" name="last_name" value="<?php echo $partner_application['last_name']; ?>">
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="control-label col-sm-3" for="email_address">Email Address:</label>
                                        <div class="col-sm-9 col-lg-5">
                                            <input type="text" class="form-control" id="email_address" name="email_address" value="<?php echo $partner_application['email_address']; ?>" readonly>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="control-label col-sm-3" for="status">Status:</label>
                                        <div class="col-sm-9 col-lg-5">
                                            <div class="radio">
                                                <label><input type="radio" name="partner_status" value="2" <?php if($partner_application['status'] == '2') { echo "checked"; } ?> required>Approve</label>
                                            </div>
                                            <div class="radio">
                                                <label><input type="radio" name="partner_status" value="3" <?php if($partner_application['status'] == '3') { echo "checked"; } ?> required>Disapprove</label>
                                            </div>
                                            <div class="radio">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <div class="col-sm-offset-3 col-sm-9">
                                            <button type="button" data-target="#confirm-modify-profile" data-toggle="modal" class="btn btn-success">Modify Application</button>
                                        </div>
                                    </div>
                                    
                                    <!--Modal - confirmation boxes--> 
                                    <div id="confirm-modify-profile" tabindex="-1" role="dialog" aria-hidden="true" class="modal fade">
                                        <div class="modal-dialog">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <button type="button" data-dismiss="modal" aria-hidden="true"
                                                        class="close">&times;</button>
                                                    <h4 class="modal-title">Modify Application</h4></div>
                                                <div class="modal-body">Are you sure you want to modify this partner application? This action cannot be reversed.</div>
                                                <div class="modal-footer">
                                                    <input name="process" type="submit" class="btn btn-success" value="Proceed">
                                                    <button type="submit" name="close" data-dismiss="modal" class="btn btn-danger">Close!</button>
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