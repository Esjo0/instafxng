<?php
require_once("../init/initialize_admin.php");
if (!$session_admin->is_logged_in()) {
    redirect_to("login.php");
}

$get_params = allowed_get_params(['x', 'id']);
$ifxaccount_id_encrypted = $get_params['id'];
$ifxaccount_id = dec_enc('decrypt',  $ifxaccount_id_encrypted);

if (isset($_POST['process'])) {
    foreach($_POST as $key => $value) {
        $_POST[$key] = $db_handle->sanitizePost(trim($value));
    }
    
    extract($_POST);

    if(empty($account_status)) {
        $message_error = "All fields must be filled, please try again";
    } else {
        $update_account = $client_object->update_account_status($ifxaccount_no, $account_status);
        
        if($update_account) {
            $message_success = "You have successfully updated the account";
        } else {
            $message_error = "Looks like something went wrong or you didn't make any change.";
        }
    }
}

if($get_params['x'] == 'edit') {
    if(!empty($ifxaccount_id)) {
        $selected_account = $client_object->get_user_name_ifxaccount($ifxaccount_id);
    }
}

?>
<!DOCTYPE html>
<html lang="en">
    <head>
        <base target="_self">
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>Instaforex Nigeria | Admin - Moderate IFX Account</title>
        <meta name="title" content="Instaforex Nigeria | Admin - Moderate IFX Account" />
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
                            <h4><strong>MODERATE IFX ACCOUNT</strong></h4>
                        </div>
                    </div>
                    
                    <div class="section-tint super-shadow">
                        <div class="row">
                            <div class="col-sm-12">
                                <?php require_once 'layouts/feedback_message.php'; ?>
                                <p><a href="client_moderate_profile.php" class="btn btn-default" title="Moderate Clients"><i class="fa fa-arrow-circle-left"></i> Moderate Clients</a></p>
                                
                                <p>Check the account status below and update accordingly.</p>
                                <form data-toggle="validator" class="form-horizontal" enctype="multipart/form-data" role="form" method="post" action="<?php echo $_SERVER['REQUEST_URI']; ?>">
                                    <input type="hidden" name="ifxaccount_no" value="<?php if(isset($ifxaccount_id)) { echo $ifxaccount_id; } ?>" />
                                    <div class="form-group">
                                        <label class="control-label col-sm-2" for="client_name">Client Name:</label>
                                        <div class="col-sm-10 col-lg-6">
                                            <input type="text" name="client_name" class="form-control" id="client_name" value="<?php if(isset($selected_account['full_name'])) { echo $selected_account['full_name']; } ?>" disabled/>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="control-label col-sm-2" for="ifx_account">IFX Account:</label>
                                        <div class="col-sm-10 col-lg-6"><input type="text" name="ifx_account" class="form-control" id="ifx_account" value="<?php if(isset($selected_account['account_no'])) { echo $selected_account['account_no']; } ?>" disabled required/></div>
                                    </div>
                                    <div class="form-group">
                                        <label class="control-label col-sm-2" for="account_status">Status:</label>
                                        <div class="col-sm-10 col-lg-5">
                                            <div class="radio">
                                                <label><input id="venue" type="radio" name="account_status" value="2" <?php if($selected_account['account_status'] == '2') { echo "checked"; } ?> required>ILPR</label>
                                            </div>
                                            <div class="radio">
                                                <label><input id="venue" type="radio" name="account_status" value="3" <?php if($selected_account['account_status'] == '3') { echo "checked"; } ?> required>Non-ILPR</label>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <div class="col-sm-offset-2 col-sm-10">
                                            <button type="button" data-target="#moderate-account-confirm" data-toggle="modal" class="btn btn-success"><i class="fa fa-save fa-fw"></i> Save</button>
                                        </div>
                                    </div>

                                    <!-- Modal - confirmation boxes -->
                                    <div id="moderate-account-confirm" tabindex="-1" role="dialog" aria-hidden="true" class="modal fade">
                                        <div class="modal-dialog">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <button type="button" data-dismiss="modal" aria-hidden="true"
                                                        class="close">&times;</button>
                                                    <h4 class="modal-title">Moderate Account Confirmation</h4>
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