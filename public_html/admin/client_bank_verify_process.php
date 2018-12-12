<?php
require_once("../init/initialize_admin.php");
if (!$session_admin->is_logged_in()) {
    redirect_to("login.php");
}

$get_params = allowed_get_params(['x', 'id']);
$user_bank_id_encrypted = $get_params['id'];
$user_bank_id = dec_enc('decrypt',  $user_bank_id_encrypted);

$client_operation = new clientOperation();

if (isset($_POST['process'])) {
    foreach($_POST as $key => $value) {
        $_POST[$key] = $db_handle->sanitizePost(trim($value));
    }
    
    extract($_POST);
    $user_bank_id = dec_enc('decrypt',  $user_bank_id);

    if(empty($bank_account_status)) {
        $message_error = "All fields must be filled, please try again";
    } else {
        $update_verification = $client_operation->update_bank_account_status($user_bank_id, $bank_account_status);
        
        if($update_verification) {
            $message_success = "You have successfully updated the bank account details for this client.";
        } else {
            $message_error = "Looks like something went wrong or you didn't make any change.";
        }
    }
}

if($get_params['x'] == 'edit') {
    if(!empty($user_bank_id)) {
        $selected_bank_account = $client_operation->get_user_bank_by_id($user_bank_id);
    }
}

?>
<!DOCTYPE html>
<html lang="en">
    <head>
        <base target="_self">
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>Instaforex Nigeria | Admin - Bank Account Verification</title>
        <meta name="title" content="Instaforex Nigeria | Admin - Bank Account Verification" />
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
                            <h4><strong>BANK ACCOUNT VERIFICATION</strong></h4>
                        </div>
                    </div>
                    
                    <div class="section-tint super-shadow">
                        <div class="row">
                            <div class="col-sm-12">
                                <?php require_once 'layouts/feedback_message.php'; ?>
                                <p><a href="client_bank_verify.php" class="btn btn-default" title="Verify Bank Account"><i class="fa fa-arrow-circle-left"></i> Verify Bank Account</a></p>

                                <p>You can approve or disapprove a bank account entered by the client, confirm that the bank account name on your
                                internet banking platform corresponds with names displayed. Disapproved accounts cannot be used for withdrawal.</p>
                                <form data-toggle="validator" class="form-horizontal" enctype="multipart/form-data" role="form" method="post" action="<?php echo $_SERVER['REQUEST_URI']; ?>">
                                    <input type="hidden" name="user_bank_id" value="<?php echo dec_enc('encrypt', $user_bank_id); ?>" />

                                    <div class="form-group">
                                        <label class="control-label col-sm-2" for=""></label>
                                        <div class="col-sm-10 col-lg-6">
                                            <p>
                                                <a target="_blank" title="View Profile" class="btn btn-info" href="client_detail.php?id=<?php echo dec_enc('encrypt', $selected_bank_account['user_code']); ?>"><i class="glyphicon glyphicon-eye-open icon-white"></i> </a>

                                                <?php if($client_operation->account_flagged($selected_bank_account['user_code'])) { ?>
                                                    <img class="center-block" src="../images/red-flag.png" alt="" title="This client has an account flagged.">
                                                <?php } ?>
                                            </p>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="control-label col-sm-2" for="client_name">Client Name:</label>
                                        <div class="col-sm-10 col-lg-6">
                                            <input type="text" name="client_name" class="form-control" id="client_name" value="<?php if(isset($selected_bank_account['full_name'])) { echo $selected_bank_account['full_name']; } ?>" disabled/>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="control-label col-sm-2" for="client_phone">Phone Number:</label>
                                        <div class="col-sm-10 col-lg-6">
                                            <input type="text" name="client_phone" class="form-control" id="client_phone" value="<?php if(isset($selected_bank_account['phone'])) { echo $selected_bank_account['phone']; } ?>" disabled/>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="control-label col-sm-2" for="ifx_account">IFX Account:</label>
                                        <div class="col-sm-10 col-lg-6">
                                            <?php if(isset($selected_bank_account['ifx_accounts'])) { echo $selected_bank_account['ifx_accounts']; } ?>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="control-label col-sm-2" for="bank_name">Bank Name:</label>
                                        <div class="col-sm-10 col-lg-6">
                                            <input type="text" name="bank_name" class="form-control" id="bank_name" value="<?php if(isset($selected_bank_account['bank_name'])) { echo $selected_bank_account['bank_name']; } ?>" disabled/>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="control-label col-sm-2" for="account_name">Account Name:</label>
                                        <div class="col-sm-10 col-lg-6">
                                            <input type="text" name="account_name" class="form-control" id="account_name" value="<?php if(isset($selected_bank_account['bank_acct_name'])) { echo $selected_bank_account['bank_acct_name']; } ?>" disabled/>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="control-label col-sm-2" for="account_number">Account Number:</label>
                                        <div class="col-sm-10 col-lg-6">
                                            <input type="text" name="account_number" class="form-control" id="account_number" value="<?php if(isset($selected_bank_account['bank_acct_no'])) { echo $selected_bank_account['bank_acct_no']; } ?>" disabled/>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="control-label col-sm-2" for="bank_account_status">Status:</label>
                                        <div class="col-sm-10 col-lg-8">
                                            <div class="radio">
                                                <label><input type="radio" name="bank_account_status" value="2" <?php if($selected_bank_account['status'] == '2') { echo "checked"; } ?> required>Approve</label>
                                            </div>
                                            <div class="radio">
                                                <label><input type="radio" name="bank_account_status" value="3" <?php if($selected_bank_account['status'] == '3') { echo "checked"; } ?> required>Disapprove</label>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <div class="col-sm-offset-2 col-sm-10">
                                            <button type="button" data-target="#bank-verification-confirm" data-toggle="modal" class="btn btn-success"><i class="fa fa-save fa-fw"></i> Save</button>
                                        </div>
                                    </div>

                                    <!-- Modal - confirmation boxes -->
                                    <div id="bank-verification-confirm" tabindex="-1" role="dialog" aria-hidden="true" class="modal fade">
                                        <div class="modal-dialog">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <button type="button" data-dismiss="modal" aria-hidden="true"
                                                        class="close">&times;</button>
                                                    <h4 class="modal-title">Bank Account Verification Confirmation</h4>
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