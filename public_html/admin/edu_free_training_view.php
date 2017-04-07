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

    if(!empty($state)) {
        $update_registration = $system_object->update_free_training_registration($selected_id, $train_interest, $train_centre, $comment, $_SESSION['admin_unique_code'], $state);
    } else {
        $update_registration = $system_object->update_free_training_registration($selected_id, $train_interest, $train_centre, $comment, $_SESSION['admin_unique_code']);
    }



    if($update_registration) {
        $message_success = "You have successfully updated the registration";
    } else {
        $message_error = "Looks like something went wrong or you didn't make any change.";
    }
}

$get_params = allowed_get_params(['x']);
$selected_id_encrypted = $get_params['x'];
$selected_id = decrypt(str_replace(" ", "+", $selected_id_encrypted));
$selected_id = preg_replace("/[^A-Za-z0-9 ]/", '', $selected_id);
$selected_detail = $system_object->get_free_training_reg_by_id($selected_id);
$selected_detail = $selected_detail[0];

$selected_comment = $system_object->get_free_training_registration_comment($selected_id);
$all_states = $system_object->get_all_states();
?>
<!DOCTYPE html>
<html lang="en">
    <head>
        <base target="_self">
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>Instaforex Nigeria | View Free Training Registration Details</title>
        <meta name="title" content="Instaforex Nigeria | View Free Training Registration Details" />
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
                            <h4><strong>VIEW REGISTRATION DETAILS</strong></h4>
                        </div>
                    </div>
                    
                    <div class="section-tint super-shadow">
                        <div class="row">
                            <div class="col-sm-12">
                                <?php require_once 'layouts/feedback_message.php'; ?>
                                <p><a href='<?php echo "edu_free_training.php?pg={$currentpage}"; ?>'  class="btn btn-default" title="Education - Free Training"><i class="fa fa-arrow-circle-left"></i> Education - Free Training</a></p>
                                
                                <p>View Free Training Registration Details</p>
                                
                                <div class="row">
                                    <div class="col-lg-7">
                                        <form data-toggle="validator" class="form-horizontal" role="form" method="post" action="<?php echo $_SERVER['REQUEST_URI']; ?>">
                                            <input type="hidden" name="selected_id" value="<?php if(isset($selected_detail['free_training_campaign_id'])) { echo $selected_detail['free_training_campaign_id']; } ?>" />

                                            <div class="form-group">
                                                <label class="control-label col-sm-3" for="full_name">Full Name:</label>
                                                <div class="col-sm-9"><input type="text" name="full_name" class="form-control" id="full_name" value="<?php if(isset($selected_detail['full_name'])) { echo $selected_detail['full_name']; } ?>" required disabled/></div>
                                            </div>
                                            <div class="form-group">
                                                <label class="control-label col-sm-3" for="email_address">Email Address:</label>
                                                <div class="col-sm-9"><input type="text" name="email_address" class="form-control" id="email_address" value="<?php if(isset($selected_detail['email'])) { echo $selected_detail['email']; } ?>" required disabled/></div>
                                            </div>
                                            <div class="form-group">
                                                <label class="control-label col-sm-3" for="phone_number">Phone Number:</label>
                                                <div class="col-sm-9"><input type="text" name="phone_number" class="form-control" id="email_address" value="<?php if(isset($selected_detail['phone'])) { echo $selected_detail['phone']; } ?>" required disabled/></div>
                                            </div>
                                            <div class="form-group">
                                                <label class="control-label col-sm-3" for="state">State:</label>
                                                <div class="col-sm-9">
                                                    <input type="text" name="state" class="form-control" id="state" value="<?php if(isset($selected_detail['main_state'])) { echo $selected_detail['main_state']; } ?>" required disabled/>
                                                </div>
                                            </div>

                                            <div class="form-group">
                                                <label class="control-label col-sm-3" for="state">State of Residence:</label>
                                                <div class="col-sm-9 col-lg-7">
                                                    <select name="state" class="form-control" id="state" >
                                                        <option value="" selected>Select State</option>
                                                        <?php foreach($all_states as $key => $value) { ?>
                                                            <option value="<?php echo $value['state_id']; ?>"><?php echo $value['state']; ?></option>
                                                        <?php } ?>
                                                    </select>
                                                </div>
                                            </div>

                                            <div class="form-group">
                                                <label class="control-label col-sm-3" for="training_interest">Training Interest:</label>
                                                <div class="col-sm-9">
                                                    <div class="radio">
                                                        <label><input id="venue" type="radio" name="train_interest" value="1" <?php if($selected_detail['training_interest'] == '1') { echo "checked"; } ?> required>Not Yet</label>
                                                    </div>
                                                    <div class="radio">
                                                        <label><input id="venue" type="radio" name="train_interest" value="2" <?php if($selected_detail['training_interest'] == '2') { echo "checked"; } ?> required>Yes</label>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <label class="control-label col-sm-3" for="train_centre">Training Centre:</label>
                                                <div class="col-sm-9">
                                                    <div class="radio">
                                                        <label><input id="venue" type="radio" name="train_centre" value="1" <?php if($selected_detail['training_centre'] == '1') { echo "checked"; } ?> >Diamond Estate</label>
                                                    </div>
                                                    <div class="radio">
                                                        <label><input id="venue" type="radio" name="train_centre" value="2" <?php if($selected_detail['training_centre'] == '2') { echo "checked"; } ?> >Ikota Office</label>
                                                    </div>
                                                    <div class="radio">
                                                        <label><input id="venue" type="radio" name="train_centre" value="3" <?php if($selected_detail['training_centre'] == '3') { echo "checked"; } ?> >Online</label>
                                                    </div>
                                                </div>
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
                                                            <h4 class="modal-title">Modify Registration Confirmation</h4>
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