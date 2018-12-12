<?php
require_once("../init/initialize_admin.php");
if (!$session_admin->is_logged_in()) {
    redirect_to("login.php");
}

// get the current page or set a default
if (isset($_GET['pg']) && is_numeric($_GET['pg'])) {
    $currentpage = (int) $_GET['pg'];
} else {
    $currentpage = 1;
}

if (isset($_POST['process'])) {
    foreach($_POST as $key => $value) {
        $_POST[$key] = $db_handle->sanitizePost(trim($value));
    }

    extract($_POST);
    $comment = "FAILED SMS CODE: " . $comment;
    $admin_code = $_SESSION['admin_unique_code'];
    $query = "INSERT INTO sales_contact_comment (user_code, admin_code, comment) VALUES ('$selected_id', '$admin_code', '$comment')";
    $result = $db_handle->runQuery($query);

    if($result) {
        $message_success = "You have successfully saved your comment";
    } else {
        $message_error = "Looks like something went wrong or you didn't make any change.";
    }
}

$get_params = allowed_get_params(['x']);
$user_code_encrypted = $get_params['x'];
$user_code = dec_enc('decrypt',  $user_code_encrypted));


$query = "SELECT first_name, last_name, email, phone FROM user WHERE user_code = '$user_code' LIMIT 1";
$result = $db_handle->runQuery($query);
$user_detail = $db_handle->fetchAssoc($result);
$user_detail = $user_detail[0];

$query = "SELECT scc.comment, scc.created, CONCAT(a.last_name, SPACE(1), a.first_name) AS admin_name
          FROM sales_contact_comment AS scc
          INNER JOIN admin AS a ON scc.admin_code = a.admin_code
          WHERE scc.user_code = '$user_code' ORDER BY scc.created DESC";
$result = $db_handle->runQuery($query);
$selected_comment = $db_handle->fetchAssoc($result);
?>
<!DOCTYPE html>
<html lang="en">
    <head>
        <base target="_self">
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>Instaforex Nigeria | Failed SMS Code</title>
        <meta name="title" content="Instaforex Nigeria | Failed SMS Code" />
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
                            <h4><strong>FAILED SMS CODE DETAILS</strong></h4>
                        </div>
                    </div>
                    
                    <div class="section-tint super-shadow">
                        <div class="row">
                            <div class="col-sm-12">
                                <?php require_once 'layouts/feedback_message.php'; ?>
                                <p><a href='<?php echo "client_failed_sms_code.php?pg={$currentpage}"; ?>'  class="btn btn-default" title="Failed SMS Code"><i class="fa fa-arrow-circle-left"></i> Failed SMS Code</a></p>
                                
                                <p>View Details</p>
                                
                                <div class="row">
                                    <div class="col-lg-7">
                                        <form data-toggle="validator" class="form-horizontal" role="form" method="post" action="<?php echo $_SERVER['REQUEST_URI']; ?>">
                                            <input type="hidden" name="selected_id" value="<?php if(isset($user_code)) { echo $user_code; } ?>" />
                                            <div class="form-group">
                                                <label class="control-label col-sm-3" for="first_name">First Name:</label>
                                                <div class="col-sm-9">
                                                    <input type="text" name="first_name" class="form-control" id="first_name" value="<?php if(isset($user_detail['first_name'])) { echo $user_detail['first_name']; } ?>" required disabled/>
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <label class="control-label col-sm-3" for="last_name">Last Name:</label>
                                                <div class="col-sm-9"><input type="text" name="last_name" class="form-control" id="last_name" value="<?php if(isset($user_detail['last_name'])) { echo $user_detail['last_name']; } ?>" required disabled/></div>
                                            </div>
                                            <div class="form-group">
                                                <label class="control-label col-sm-3" for="email_address">Email Address:</label>
                                                <div class="col-sm-9"><input type="text" name="email_address" class="form-control" id="email_address" value="<?php if(isset($user_detail['email'])) { echo $user_detail['email']; } ?>" required disabled/></div>
                                            </div>
                                            <div class="form-group">
                                                <label class="control-label col-sm-3" for="phone_number">Phone Number:</label>
                                                <div class="col-sm-9"><input type="text" name="phone_number" class="form-control" id="email_address" value="<?php if(isset($user_detail['phone'])) { echo $user_detail['phone']; } ?>" required disabled/></div>
                                            </div>

                                            <div class="form-group">
                                                <label class="control-label col-sm-3" for="comment">Comment:</label>
                                                <div class="col-sm-9"><textarea name="comment" id="comment" rows="3" class="form-control" required></textarea></div>
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
                                                            <h4 class="modal-title">Failed SMS Code Client Comment</h4>
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
                                                <span id="trans_remark_author"><?php echo $row['admin_name']; ?></span>
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