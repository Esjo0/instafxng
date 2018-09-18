<?php
require_once("../init/initialize_admin.php");
if (!$session_admin->is_logged_in()) {
    redirect_to("login.php");
}

$admin_code = $_SESSION['admin_unique_code'];

$get_params = allowed_get_params(['x', 'id']);
$user_credential_id_encrypted = $get_params['id'];
$user_credential_id = decrypt(str_replace(" ", "+", $user_credential_id_encrypted));
$user_credential_id = preg_replace("/[^A-Za-z0-9 ]/", '', $user_credential_id);

$client_operation = new clientOperation();


if($get_params['x'] == 'edit') {
    if(!empty($user_credential_id)) {
        $selected_user_docs = $client_operation->get_user_verification_docs($user_credential_id);
        $user_code = $selected_user_docs['user_code'];
        $verification_remark = $client_operation->get_verification_remark($user_code);
    }
}

if (isset($_POST['pending'])) {
    foreach ($_POST as $key => $value) {
        $_POST[$key] = $db_handle->sanitizePost(trim($value));
    }
    extract($_POST);

    $credential_id = decrypt(str_replace(" ", "+", $credential_id));
    $credential_id = preg_replace("/[^A-Za-z0-9 ]/", '', $credential_id);

    $meta_id = decrypt(str_replace(" ", "+", $meta_id));
    $meta_id = preg_replace("/[^A-Za-z0-9 ]/", '', $meta_id);

    if(is_null($passport_status) || is_null($id_card_status) || is_null($signature_status) || is_null($address_status) || is_null($remarks)) {
        $message_error = "All fields must be filled, please try again";
    } else {
        $doc_status = $id_card_status . $passport_status . $signature_status;
        $update_verification = $client_operation->update_document_verification_status($credential_id, $meta_id, $doc_status, $address_status, $remarks);
        $update_remark = $client_operation->update_verification_remark($user_code, $admin_code, $remarks);

        // Set the status to pending
        $query = "UPDATE user_credential SET status = '3' WHERE user_credential_id = $credential_id LIMIT 1";
        $result = $db_handle->runQuery($query);

        if($update_verification && $result) {
            $message_success = "You have successfully pend this verification and updated comments";
            redirect_to("client_doc_verify.php");
        } else {
            $message_error = "Looks like something went wrong or you didn't make any change.";
        }
    }
}

if (isset($_POST['process'])) {
    foreach($_POST as $key => $value) {
        $_POST[$key] = $db_handle->sanitizePost(trim($value));
    }
    
    extract($_POST);

    $credential_id = decrypt(str_replace(" ", "+", $credential_id));
    $credential_id = preg_replace("/[^A-Za-z0-9 ]/", '', $credential_id);

    $meta_id = decrypt(str_replace(" ", "+", $meta_id));
    $meta_id = preg_replace("/[^A-Za-z0-9 ]/", '', $meta_id);

    if(is_null($passport_status) || is_null($id_card_status) || is_null($signature_status) || is_null($address_status) || is_null($remarks)) {
        $message_error = "All fields must be filled, please try again";
    } else {
        $doc_status = $id_card_status . $passport_status . $signature_status;
        $update_verification = $client_operation->update_document_verification_status($credential_id, $meta_id, $doc_status, $address_status, $remarks);
        $update_remark = $client_operation->update_verification_remark($user_code, $admin_code, $remarks);
        if($update_verification) {
            $message_success = "You have successfully updated the account";
            redirect_to("client_doc_verify.php");
        } else {
            $message_error = "Looks like something went wrong or you didn't make any change.";
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
        <title>Instaforex Nigeria | Admin - Document Verification</title>
        <meta name="title" content="Instaforex Nigeria | Admin - Document Verification" />
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
                            <h4><strong>DOCUMENT VERIFICATION</strong></h4>
                        </div>
                    </div>
                    
                    <div class="section-tint super-shadow">
                        <div class="row">
                            <div class="col-sm-8">
                                <?php require_once 'layouts/feedback_message.php'; ?>
                                <p><a href="client_doc_verify.php" class="btn btn-default" title="Verify Documents"><i class="fa fa-arrow-circle-left"></i> Verify Documents</a></p>

                                <p>Verify the client document and address, you can approve individual document. Please note that the remark you entered will
                                be emailed to the client.</p>
                                <form data-toggle="validator" class="form-horizontal" enctype="multipart/form-data" role="form" method="post" action="<?php echo $_SERVER['REQUEST_URI']; ?>">
                                    <input type="hidden" name="credential_id" value="<?php echo encrypt($selected_user_docs['user_credential_id']); ?>" />
                                    <input type="hidden" name="meta_id" value="<?php echo encrypt($selected_user_docs['user_meta_id']); ?>" />
                                    <div class="form-group">
                                        <label class="control-label col-sm-2" for=""></label>
                                        <div class="col-sm-10 col-lg-6">
                                            <p>
                                                <a target="_blank" title="View Profile" class="btn btn-info" href="client_detail.php?id=<?php echo encrypt($selected_user_docs['user_code']); ?>"><i class="glyphicon glyphicon-eye-open icon-white"></i> </a>

                                            <?php if($client_operation->account_flagged($selected_user_docs['user_code'])) { ?>
                                                <img class="center-block" src="../images/red-flag.png" alt="" title="This client has an account flagged.">
                                            <?php } ?>
                                            </p>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="control-label col-sm-2" for="client_name">Client Name:</label>
                                        <div class="col-sm-10 col-lg-6">
                                            <input type="text" name="client_name" class="form-control" id="client_name" value="<?php if(isset($selected_user_docs['full_name'])) { echo $selected_user_docs['full_name']; } ?>" disabled/>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="control-label col-sm-2" for="client_phone">Phone Number:</label>
                                        <div class="col-sm-10 col-lg-6">
                                            <input type="text" name="client_phone" class="form-control" id="client_phone" value="<?php if(isset($selected_user_docs['phone'])) { echo $selected_user_docs['phone']; } ?>" disabled/>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="control-label col-sm-2" for="ifx_account">IFX Account:</label>
                                        <div class="col-sm-10 col-lg-6">
                                            <?php if(isset($selected_user_docs['ifx_accounts'])) { echo $selected_user_docs['ifx_accounts']; } ?>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="control-label col-sm-2" for="documents">Documents:</label>
                                        <div class="col-sm-10 col-lg-8">
                                            <div class="row">
                                                <div class="col-sm-4">
                                                    <span class="text-danger text-center">Passport</span>
                                                    <a href="../userfiles/<?php echo $selected_user_docs['passport']; ?>" title="click to enlarge" target="_blank"><img src="../userfiles/<?php echo $selected_user_docs['passport']; ?>" width="120px" height="120px"/></a>
                                                    <div class="radio">
                                                        <label><input type="radio" name="passport_status" value="1" required>Approve</label>
                                                    </div>
                                                    <div class="radio">
                                                        <label><input type="radio" name="passport_status" value="0" required>Disapprove</label>
                                                    </div>
                                                </div>
                                                <div class="col-sm-4">
                                                    <span class="text-danger text-center">ID Card</span>
                                                    <a href="../userfiles/<?php echo $selected_user_docs['idcard']; ?>" title="click to enlarge" target="_blank"><img src="../userfiles/<?php echo $selected_user_docs['idcard']; ?>" width="120px" height="120px"/></a>
                                                    <div class="radio">
                                                        <label><input type="radio" name="id_card_status" value="1" required>Approve</label>
                                                    </div>
                                                    <div class="radio">
                                                        <label><input type="radio" name="id_card_status" value="0" required>Disapprove</label>
                                                    </div>
                                                </div>
                                                <div class="col-sm-4">
                                                    <span class="text-danger text-center">Signature</span>
                                                    <a href="../userfiles/<?php echo $selected_user_docs['signature']; ?>" title="click to enlarge" target="_blank"><img src="../userfiles/<?php echo $selected_user_docs['signature']; ?>" width="120px" height="120px"/></a>
                                                    <div class="radio">
                                                        <label><input type="radio" name="signature_status" value="1" required>Approve</label>
                                                    </div>
                                                    <div class="radio">
                                                        <label><input type="radio" name="signature_status" value="0" required>Disapprove</label>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="control-label col-sm-2" for="address">Address:</label>
                                        <div class="col-sm-10 col-lg-8">
                                            <textarea name="description" id="description" rows="3" class="form-control" disabled required><?php if(isset($selected_user_docs['full_address'])) { echo $selected_user_docs['full_address']; } ?></textarea>
                                            <div class="radio">
                                                <label><input type="radio" name="address_status" value="2" required>Approve</label>
                                            </div>
                                            <div class="radio">
                                                <label><input type="radio" name="address_status" value="3" required>Disapprove</label>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="control-label col-sm-2" for="remarks">Your Remark:</label>
                                        <div class="col-sm-10 col-lg-8">
                                            <textarea name="remarks" id="remarks" rows="3" class="form-control"><?php if(isset($selected_user_docs['remark'])) { echo $selected_user_docs['remark']; } ?></textarea>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <div class="col-sm-offset-2 col-sm-10">
                                            <button type="button" data-target="#document-verification-confirm" data-toggle="modal" class="btn btn-success"><i class="fa fa-save fa-fw"></i> Save</button>
                                            <input name="pending" type="submit" class="btn btn-primary" value="Pending">
                                        </div>
                                    </div>

                                    <!-- Modal - confirmation boxes -->
                                    <div id="document-verification-confirm" tabindex="-1" role="dialog" aria-hidden="true" class="modal fade">
                                        <div class="modal-dialog">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <button type="button" data-dismiss="modal" aria-hidden="true"
                                                        class="close">&times;</button>
                                                    <h4 class="modal-title">Document Verification Confirmation</h4>
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
                            <div class="col-sm-4">
                                <h5>Admin Remark</h5>
                                <div style="max-height: 550px; overflow: scroll;">
                                    <?php
                                    if(isset($verification_remark) && !empty($verification_remark)) {
                                        foreach ($verification_remark as $row) {
                                            ?>
                                            <div class="row">
                                                <div class="col-sm-12">
                                                    <div class="transaction-remarks">
                                                        <span id="trans_remark_author"><?php echo $row['admin_full_name']; ?></span>
                                                        <span id="trans_remark"><?php echo $row['comment']; ?></span>
                                                        <span id="trans_remark_date"><?php echo datetime_to_text($row['created']); ?></span>
                                                    </div>
                                                </div>
                                            </div>
                                        <?php } } else { ?>
                                        <div class="row">
                                            <div class="col-sm-12">
                                                <div class="transaction-remarks">
                                                    <span class="text-danger"><em>There is no remark to display.</em></span>
                                                </div>
                                            </div>
                                        </div>
                                    <?php } ?>
                                </div>
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