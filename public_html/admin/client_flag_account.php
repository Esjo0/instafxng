<?php
require_once("../init/initialize_admin.php");
if (!$session_admin->is_logged_in()) {
    redirect_to("login.php");
}

$get_params = allowed_get_params(['x', 'id']);
$account_flag_id_encrypted = $get_params['id'];
$account_flag_id = dec_enc('decrypt',  $account_flag_id_encrypted);

$client_operation = new clientOperation();

// Add New Flag - Process submitted form
if (isset($_POST['process'])) {
    
    foreach($_POST as $key => $value) {
        $_POST[$key] = $db_handle->sanitizePost(trim($value));
    }

    extract($_POST);

    $ifx_account_id = $client_operation->get_account_id_by_account_no($account_no);
    $client_user_code = $client_operation->get_user_code_by_account_no($account_no);
    
    if(empty($account_no) || empty($flag_account_comment) || empty($flag_account_status)) {
        $message_error = "All fields are compulsory, please try again.";
    } elseif (!$ifx_account_id) {
        $message_error = "You have provided an invalid account number. Please try again.";
    } else {
        $new_flag = $client_operation->add_new_account_flag($account_flag_no, $client_user_code, $ifx_account_id, $flag_account_comment, $flag_account_status, $_SESSION['admin_unique_code']);
        if($new_flag) {
            $message_success = "You have successfully saved the account flag.";
        } else {
            $message_error = "Looks like something went wrong or you didn't make any change.";
        }
    }
}

if($get_params['x'] == 'edit') {
    if(!empty($account_flag_id)) {
        $selected_account_flag = $system_object->get_account_flag_by_id($account_flag_id);
        $selected_account_flag = $selected_account_flag[0];
    }
}

?>
<!DOCTYPE html>
<html lang="en">
    <head>
        <base target="_self">
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>Instaforex Nigeria | Admin - Flag Account</title>
        <meta name="title" content="Instaforex Nigeria | Admin - Flag Account" />
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
                            <h4><strong>FLAG CLIENT ACCOUNT</strong></h4>
                        </div>
                    </div>
                    
                    <div class="section-tint super-shadow">
                        <div class="row">
                            <div class="col-sm-12">
                                <?php require_once 'layouts/feedback_message.php'; ?>
                                <p><a href="client_flag_view.php" class="btn btn-default" title="Manage Account Flags"><i class="fa fa-arrow-circle-left"></i> Manage Account Flags</a></p>
                                
                                <p>Fill the form below to flag an account.</p>
                                <form data-toggle="validator" class="form-horizontal" role="form" method="post" action="<?php echo $REQUEST_URI; ?>">
                                    <input type="hidden" name="account_flag_no" value="<?php if(isset($selected_account_flag['user_account_flag_id'])) { echo $selected_account_flag['user_account_flag_id']; } ?>" />
                                    <div class="form-group">
                                        <label class="control-label col-sm-2" for="account_no">IFX Account:</label>
                                        <div class="col-sm-10 col-lg-5">
                                            <input name="account_no" type="text" id="" value="<?php if(isset($selected_account_flag['ifx_acct_no'])) { echo $selected_account_flag['ifx_acct_no']; } ?>" class="form-control" required/>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="control-label col-sm-2" for="flag_account_comment">Comment:</label>
                                        <div class="col-sm-10 col-lg-8"><textarea name="flag_account_comment" id="content" rows="3" class="form-control" required><?php if(isset($selected_account_flag['comment'])) { echo $selected_account_flag['comment']; } ?></textarea></div>
                                    </div>
                                    <div class="form-group">
                                        <label class="control-label col-sm-2" for="flag_account_status">Status:</label>
                                        <div class="col-sm-10 col-lg-5">
                                            <div class="radio">
                                                <label><input id="venue" type="radio" name="flag_account_status" value="1" <?php if($selected_account_flag['status'] == '1') { echo "checked"; } ?> required>Active</label>
                                            </div>
                                            <div class="radio">
                                                <label><input id="venue" type="radio" name="flag_account_status" value="2" <?php if($selected_account_flag['status'] == '2') { echo "checked"; } ?> required>Inactive</label>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <div class="col-sm-offset-2 col-sm-10">
                                            <button type="button" data-target="#confirm-account-flag" data-toggle="modal" class="btn btn-success"><i class="fa fa-save fa-fw"></i> Save Flag</button>
                                        </div>
                                    </div>
                                    
                                    <!--Modal - confirmation boxes--> 
                                    <div id="confirm-account-flag" tabindex="-1" role="dialog" aria-hidden="true" class="modal fade">
                                        <div class="modal-dialog">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <button type="button" data-dismiss="modal" aria-hidden="true"
                                                        class="close">&times;</button>
                                                    <h4 class="modal-title">Account Flag</h4></div>
                                                <div class="modal-body">Are you sure you want to save this flag?</div>
                                                <div class="modal-footer">
                                                    <input name="process" type="submit" class="btn btn-success" value="Save Flag">
                                                    <button type="submit" name="close" onClick="window.close();" data-dismiss="modal" class="btn btn-danger">Close!</button>
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