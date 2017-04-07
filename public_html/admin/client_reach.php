<?php
require_once("../init/initialize_admin.php");
if (!$session_admin->is_logged_in()) {
    redirect_to("login.php");
}
$client_operation = new clientOperation();

if (isset($_POST['process'])) {
    foreach($_POST as $key => $value) {
        $_POST[$key] = $db_handle->sanitizePost(trim($value));
    }
    
    extract($_POST);

    $comment_logged = $system_object->set_sales_comment($selected_id, $comment, $_SESSION['admin_unique_code']);

    if($comment_logged) {
        $message_success = "You comment has been saved.";
    } else {
        $message_error = "Looks like something went wrong or you didn't make any change.";
    }
}

$get_params = allowed_get_params(['x']);
$selected_id_encrypted = $get_params['x'];
$selected_id = decrypt(str_replace(" ", "+", $selected_id_encrypted));
$selected_id = preg_replace("/[^A-Za-z0-9 ]/", '', $selected_id);
$selected_detail = $client_operation->get_user_by_code($selected_id);

$selected_comment = $system_object->get_sales_comment($selected_id);

?>
<!DOCTYPE html>
<html lang="en">
    <head>
        <base target="_self">
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>Instaforex Nigeria | Sales Client Details</title>
        <meta name="title" content="Instaforex Nigeria | Sales Client Details" />
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
                            <h4><strong>VIEW CLIENT DETAILS</strong></h4>
                        </div>
                    </div>
                    
                    <div class="section-tint super-shadow">
                        <div class="row">
                            <div class="col-sm-12">
                                <?php require_once 'layouts/feedback_message.php'; ?>

                                <p>View client details below, contact the client and log your comment</p>
                                
                                <div class="row">
                                    <div class="col-lg-7">
                                        <form data-toggle="validator" class="form-horizontal" role="form" method="post" action="<?php echo $_SERVER['REQUEST_URI']; ?>">
                                            <input type="hidden" name="selected_id" value="<?php if(isset($selected_detail['client_user_code'])) { echo $selected_detail['client_user_code']; } ?>" />
                                            <div class="form-group">
                                                <label class="control-label col-sm-3" for="full_name">First Name:</label>
                                                <div class="col-sm-9">
                                                    <input type="text" name="full_name" class="form-control" id="full_name" value="<?php if(isset($selected_detail['client_full_name'])) { echo $selected_detail['client_full_name']; } ?>" required disabled/>
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <label class="control-label col-sm-3" for="email_address">Email Address:</label>
                                                <div class="col-sm-9"><input type="text" name="email_address" class="form-control" id="email_address" value="<?php if(isset($selected_detail['client_email'])) { echo $selected_detail['client_email']; } ?>" required disabled/></div>
                                            </div>
                                            <div class="form-group">
                                                <label class="control-label col-sm-3" for="phone_number">Phone Number:</label>
                                                <div class="col-sm-9"><input type="text" name="phone_number" class="form-control" id="email_address" value="<?php if(isset($selected_detail['client_phone_number'])) { echo $selected_detail['client_phone_number']; } ?>" required disabled/></div>
                                            </div>
                                            <div class="form-group">
                                                <label class="control-label col-sm-3" for="comment">Comment:</label>
                                                <div class="col-sm-9"><textarea name="comment" id="comment" rows="3" class="form-control"></textarea></div>
                                            </div>
                                            <div class="form-group">
                                                <div class="col-sm-offset-3 col-sm-9">
                                                    <button type="button" data-target="#add-comment-confirm" data-toggle="modal" class="btn btn-success"><i class="fa fa-save fa-fw"></i> Save</button>
                                                </div>
                                            </div>

                                            <!-- Modal - confirmation boxes -->
                                            <div id="add-comment-confirm" tabindex="-1" role="dialog" aria-hidden="true" class="modal fade">
                                                <div class="modal-dialog">
                                                    <div class="modal-content">
                                                        <div class="modal-header">
                                                            <button type="button" data-dismiss="modal" aria-hidden="true"
                                                                class="close">&times;</button>
                                                            <h4 class="modal-title">Save Comment Confirmation</h4>
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
                                    
                                    <div class="col-lg-5">
                                        <!-- comment history goes here -->
                                        <h5>Admin Remarks</h5>
                                        <div class="row" style="max-height: 500px !important; overflow: scroll;">
                                            <?php 
                                                if(isset($selected_comment) && !empty($selected_comment)) { 
                                                    foreach ($selected_comment as $row) {
                                            ?>
                                            <div class="col-sm-12">
                                                <div class="transaction-remarks">
                                                <span id="trans_remark_author"><?php echo $admin_object->get_admin_name_by_code($row['admin_code']); ?></span>
                                                <span id="trans_remark_date"><?php echo datetime_to_text($row['created']); ?></span>
                                                <span id="trans_remark"><?php echo $row['comment']; ?></span>
                                                </div>
                                            </div>
                                            <?php } } else { ?>
                                        </div>
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
                    </div>

                    <!-- Unique Page Content Ends Here
                    ================================================== -->
                    
                </div>
                
            </div>
        </div>
        <?php require_once 'layouts/footer.php'; ?>
    </body>
</html>