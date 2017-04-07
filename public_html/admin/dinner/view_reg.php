<?php
require_once("../../init/initialize_admin.php");
if (!$session_admin->is_logged_in()) {
    redirect_to("login.php");
}

function get_dinner_reg_remark($reg_id) {
    global $db_handle;

    $query = "SELECT CONCAT(a.last_name, SPACE(1), a.first_name) AS admin_full_name, dc.comment, dc.created
                FROM dinner2016_comment AS dc
                INNER JOIN admin AS a ON dc.admin_code = a.admin_code
                WHERE dc.dinner_id = $reg_id ORDER BY dc.created DESC";
    $result = $db_handle->runQuery($query);
    $fetched_data = $db_handle->fetchAssoc($result);

    return $fetched_data ? $fetched_data : false;
}

$get_params = allowed_get_params(['x', 'id']);

$reg_id_encrypted = $get_params['id'];
$reg_id = decrypt(str_replace(" ", "+", $reg_id_encrypted));
$reg_id = preg_replace("/[^A-Za-z0-9 ]/", '', $reg_id);

// Process comment
if ($_SERVER['REQUEST_METHOD'] == 'POST' && $_POST['process'] == true) {
    foreach($_POST as $key => $value) {
        $_POST[$key] = $db_handle->sanitizePost(trim($value));
    }

    $registration_id = $_POST['registration_id'];
    $attended = $_POST['attended'];

    if(isset($attended) && !empty($attended)) {
        $db_handle->runQuery("UPDATE dinner_2016 SET attended = '$attended' WHERE id_dinner_2016 = $registration_id LIMIT 1");
    }
    $message_success = "You have successfully saved attendance details.";
}


$attendee_detail = $db_handle->fetchAssoc($db_handle->runQuery("SELECT * FROM dinner_2016 WHERE id_dinner_2016 = $reg_id"));
$attendee_detail = $attendee_detail[0];

if(empty($attendee_detail)) {
    redirect_to("./");
    exit;
} else {
    $admin_remark = get_dinner_reg_remark($reg_id);
}

?>
<!DOCTYPE html>
<html lang="en">
    <head>
        <base target="_self">
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>Instaforex Nigeria | View Dinner Registration</title>
        <meta name="title" content="Instaforex Nigeria | View Dinner Registration" />
        <meta name="keywords" content="" />
        <meta name="description" content="" />
        <?php require_once '../layouts/head_meta.php'; ?>
    </head>
    <body>
        <?php require_once '../layouts/header.php'; ?>
        <!-- Main Body: The is the main content area of the web site, contains a side bar  -->
        <div id="main-body" class="container-fluid">
            <div class="row no-gutter">
                <!-- Main Body - Side Bar  -->
                <div id="main-body-side-bar" class="col-md-4 col-lg-3 left-nav">
                <?php require_once '../layouts/sidebar.php'; ?>
                </div>
                
                <!-- Main Body - Content Area: This is the main content area, unique for each page  -->
                <div id="main-body-content-area" class="col-md-8 col-lg-9">
                    
                    <!-- Unique Page Content Starts Here
                    ================================================== -->
                    <div class="row">
                        <div class="col-sm-12 text-danger">
                            <h4><strong>VIEW DINNER REGISTRATION</strong></h4>
                        </div>
                    </div>
                    
                    <div class="section-tint super-shadow">
                        <div class="row">
                            <div class="col-sm-12">
                                <p><a href="dinner/all_reg.php" class="btn btn-default" title="Go back to All Registrations"><i class="fa fa-arrow-circle-left"></i> Go Back - All Registrations</a></p>
                                
                                <?php require_once '../layouts/feedback_message.php'; ?>
                                <p>Contact the selected dinner attendee and record your comment.</p>

                                <form data-toggle="validator" class="form-horizontal" role="form" method="post" action="<?php echo $_SERVER['REQUEST_URI']; ?>">
                                    <input name="registration_id" type="hidden" value="<?php echo $attendee_detail['id_dinner_2016']; ?>">
                                    <div class="form-group">
                                        <label class="control-label col-sm-3" for="name">Full Name:</label>
                                        <div class="col-sm-9 col-lg-5">
                                            <input type="text" class="form-control" id="name" name="name" value="<?php echo $attendee_detail['full_name']; ?>" readonly>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="control-label col-sm-3" for="email_address">Email Address:</label>
                                        <div class="col-sm-9 col-lg-5">
                                            <input type="text" class="form-control" id="email_address" name="email_address" value="<?php echo $attendee_detail['email']; ?>" readonly>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="control-label col-sm-3" for="phone_number">Phone Number:</label>
                                        <div class="col-sm-9 col-lg-5">
                                            <input type="text" class="form-control" id="phone_number" name="phone_number" value="<?php echo $attendee_detail['phone']; ?>" readonly>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="control-label col-sm-3" for="interest">Interest:</label>
                                        <div class="col-sm-9 col-lg-5">
                                            <input type="text" class="form-control" id="interest" name="interest" value="<?php echo dinner_interest_status($attendee_detail['interest']); ?>" readonly>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="control-label col-sm-3" for="invite">Invite:</label>
                                        <div class="col-sm-9 col-lg-5">
                                            <input type="text" class="form-control" id="invite" name="invite" value="<?php echo dinner_invite_status($attendee_detail['invite']); ?>" readonly>
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <label class="control-label col-sm-3" for="attended">Dinner Attendance:</label>
                                        <div class="col-sm-9 col-lg-5">
                                            <div class="radio">
                                                <label><input type="radio" name="attended" value="1" <?php if($attendee_detail['attended'] == '1') { echo "checked"; }; ?>>Did Not Attend</label>
                                            </div>
                                            <div class="radio">
                                                <label><input type="radio" name="attended" value="2" <?php if($attendee_detail['attended'] == '2') { echo "checked"; }; ?>>Attended</label>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <div class="col-sm-offset-3 col-sm-9">
                                            <button type="button" data-target="#confirm-save-attendance" data-toggle="modal" class="btn btn-success">Save Attendance</button>
                                        </div>
                                    </div>
                                    
                                    <!--Modal - confirmation boxes--> 
                                    <div id="confirm-save-attendance" tabindex="-1" role="dialog" aria-hidden="true" class="modal fade">
                                        <div class="modal-dialog">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <button type="button" data-dismiss="modal" aria-hidden="true"
                                                        class="close">&times;</button>
                                                    <h4 class="modal-title">Save Attendance</h4></div>
                                                <div class="modal-body">Are you sure you want to save attendance? This action cannot be reversed.</div>
                                                <div class="modal-footer">
                                                    <input name="process" type="submit" class="btn btn-success" value="Proceed">
                                                    <button type="submit" name="close" onClick="window.close();" data-dismiss="modal" class="btn btn-danger">Close!</button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </form>
                                
                                
                                <hr/>
                                
                            </div>

                            <div class="col-sm-12">
                                <h5>Admin Remarks</h5>

                                <?php
                                if(isset($admin_remark) && !empty($admin_remark)) {
                                    foreach ($admin_remark as $row) {
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

                    <!-- Unique Page Content Ends Here
                    ================================================== -->
                    
                </div>
                
            </div>
        </div>
        <?php require_once '../layouts/footer.php'; ?>
    </body>
</html>