<?php
require_once("../init/initialize_admin.php");
if (!$session_admin->is_logged_in()) {
    redirect_to("login.php");
}

$get_params = allowed_get_params(['x', 'id']);
$user_ilpr_enrolment_id_encrypted = $get_params['id'];
$user_ilpr_enrolment_id = decrypt(str_replace(" ", "+", $user_ilpr_enrolment_id_encrypted));
$user_ilpr_enrolment_id = preg_replace("/[^A-Za-z0-9 ]/", '', $user_ilpr_enrolment_id);

$query = "SELECT ui.ifx_acct_no
        FROM user_ilpr_enrolment AS uie
        INNER JOIN user_ifxaccount AS ui ON uie.ifxaccount_id = ui.ifxaccount_id
        WHERE uie.user_ilpr_enrolment_id = $user_ilpr_enrolment_id LIMIT 1";
$result = $db_handle->runQuery($query);
$fetched_data = $db_handle->fetchAssoc($result);

$account_no = $fetched_data[0]['ifx_acct_no'];
$client_operation = new clientOperation($account_no);

if (isset($_POST['process'])) {
    foreach($_POST as $key => $value) {
        $_POST[$key] = $db_handle->sanitizePost(trim($value));
    }
    
    extract($_POST);

    if(empty($account_type)) {
        $message_error = "All fields must be filled, please try again";
    } else {
        $db_handle->runQuery("UPDATE user_ilpr_enrolment SET status = '2' WHERE user_ilpr_enrolment_id = $user_ilpr_enrolment_id LIMIT 1");
        $update_account = $client_operation->update_account_type($account_id, $account_type);

        $message_success = "You have successfully updated the account";
        redirect_to('client_moderate_account.php');

    }
}

$client_operation2 = new clientOperation($account_no);
$user_ifx_details = $client_operation2->get_client_data();
extract($user_ifx_details);

if($get_params['x'] == 'edit') {
    if(!empty($user_code)) {
        $user_detail = $client_operation->get_user_by_code($user_code);
        if($user_detail) {
           extract($user_detail);
        } else { // user_code invalid, no client
            header("Location: ./");
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
        <title>Instaforex Nigeria | Admin - Moderate Client IFX Account</title>
        <meta name="title" content="Instaforex Nigeria | Admin - Moderate Client IFX Account" />
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
                            <h4><strong>MODERATE CLIENT ACCOUNT</strong></h4>
                        </div>
                    </div>
                    
                    <div class="section-tint super-shadow">
                        <div class="row">
                            <div class="col-sm-12">
                                <?php require_once 'layouts/feedback_message.php'; ?>
                                <p><a href="client_moderate_account.php" class="btn btn-default" title="Moderate IFX Account"><i class="fa fa-arrow-circle-left"></i> Moderate IFX Accounts</a></p>
                                
                                <p>Moderate the client account displayed below. Choose the Account Type.</p>
                                <form data-toggle="validator" class="form-horizontal" enctype="multipart/form-data" role="form" method="post" action="<?php echo $_SERVER['REQUEST_URI']; ?>">
                                    <input type="hidden" name="account_id" value="<?php if(isset($ifx_acc_id)) { echo $ifx_acc_id; } ?>" />

                                    <div class="form-group">
                                        <label class="control-label col-sm-2" for=""></label>
                                        <div class="col-sm-10 col-lg-6">
                                            <p>
                                                <a target="_blank" title="View Profile" class="btn btn-info" href="client_detail.php?id=<?php echo encrypt($client_user_code); ?>"><i class="glyphicon glyphicon-eye-open icon-white"></i> </a>

                                                <?php if($client_operation->account_flagged($client_user_code)) { ?>
                                                    <img class="center-block" src="../images/red-flag.png" alt="" title="This client has an account flagged.">
                                                <?php } ?>
                                            </p>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="control-label col-sm-2" for="client_name">Client Name:</label>
                                        <div class="col-sm-10 col-lg-6">
                                            <input type="text" name="client_name" class="form-control" id="client_name" value="<?php if(isset($client_full_name)) { echo $client_full_name; } ?>" readonly/>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="control-label col-sm-2" for="client_email">Email Address:</label>
                                        <div class="col-sm-10 col-lg-6">
                                            <input type="text" name="client_email" class="form-control" id="client_email" value="<?php if(isset($client_email)) { echo $client_email; } ?>" readonly/>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="control-label col-sm-2" for="client_phone">Phone Number:</label>
                                        <div class="col-sm-10 col-lg-6">
                                            <input type="text" name="client_phone" class="form-control" id="client_phone" value="<?php if(isset($client_phone_number)) { echo $client_phone_number; } ?>" readonly/>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="control-label col-sm-2" for="client_account">Account Number:</label>
                                        <div class="col-sm-10 col-lg-6">
                                            <input type="text" name="client_account" class="form-control" id="client_account" value="<?php if(isset($ifx_acc_no)) { echo $ifx_acc_no; } ?>" readonly/>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="control-label col-sm-2" for="account_type">Account Type:</label>
                                        <div class="col-sm-10 col-lg-5">
                                            <div class="radio">
                                                <label><input type="radio" name="account_type" value="1" <?php if($ifx_acc_type == '1') { echo "checked"; } ?> required>ILPR</label>
                                            </div>
                                            <div class="radio">
                                                <label><input type="radio" name="account_type" value="2" <?php if($ifx_acc_type == '2') { echo "checked"; } ?> required>Non-ILPR</label>
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
                                                    <h4 class="modal-title">Moderate Client Profile Confirmation</h4>
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