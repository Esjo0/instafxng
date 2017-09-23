<?php
require_once("../init/initialize_admin.php");
//if (!$session_admin->is_logged_in()) {
    //redirect_to("login.php");
//}

// Add New Admin User - Process submitted form
if (isset($_POST['process'])) {
    
    foreach($_POST as $key => $value) {
        $_POST[$key] = $db_handle->sanitizePost(trim($value));
    }

    extract($_POST);
    
    $admin_id = decrypt(str_replace(" ", "+", $admin_id));
    $admin_code = preg_replace("/[^A-Za-z0-9 ]/", '', $admin_id);
    
    $profile_modified = $admin_object->modify_admin_profile($admin_code, $first_name, $last_name, $admin_status);
    
    if($profile_modified) {
        $message_success = "The admin profile has been updated.";
    } else {
        $message_error = "Something went wrong, the profile could not be updated.";
    }
}

if (isset($_POST['approve'])) {
    
    $admin_id = $_POST["admin_id"];
    $admin_id = decrypt(str_replace(" ", "+", $admin_id));
    $admin_code = preg_replace("/[^A-Za-z0-9 ]/", '', $admin_id);
    $pageid = $_POST["pageid"];
    
    for ($i = 0; $i < count($pageid); $i++) {
        $totpageid = $totpageid . "," . $pageid[$i];
    }
    
    $allowed_pages = substr_replace($totpageid, "", 0, 1);
    
    $privilege_modified = $admin_object->modify_admin_privilege($admin_code, $allowed_pages);
    
    if($privilege_modified) {
        $message_success = "You have successfully modified the privileges for this admin.";
    } else {
        $message_error = "Something went wrong, the modifications were not saved.";
    }
    
}

$get_params = allowed_get_params(['id']);
$admin_code_encrypted = $get_params['id'];

$admin_code = decrypt(str_replace(" ", "+", $admin_code_encrypted));
$admin_code = preg_replace("/[^A-Za-z0-9 ]/", '', $admin_code);
$admin_detail = $admin_object->get_admin_detail_by_code($admin_code);

if(!$admin_detail) {
    redirect_to("admin_view.php");
}


$my_pages = $admin_object->get_privileges($admin_code);
$my_pages = explode(",", $my_pages['allowed_pages']);

?>
<!DOCTYPE html>
<html lang="en">
    <head>
        <base target="_self">
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>Instaforex Nigeria | Admin - Modify Admin Member</title>
        <meta name="title" content="Instaforex Nigeria | Admin - Modify Admin Member" />
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
                            <h4><strong>MODIFY ADMIN MEMBER</strong></h4>
                        </div>
                    </div>
                    
                    <div class="section-tint super-shadow">
                        <div class="row">
                            <div class="col-sm-12">
                                <p><a href="admin_view.php" class="btn btn-default" title="Go back to View Admin"><i class="fa fa-arrow-circle-left"></i> Go Back - View Admin</a></p>
                                
                                <?php require_once 'layouts/feedback_message.php'; ?>
                                <p>Modify the selected Admin member. You can modify the Admin status and privileges.</p>
                                <h5>Admin Status</h5>
                                <p>Modify the Admin status, when completed, click 'Modify Profile'.</p>
                                <form data-toggle="validator" class="form-horizontal" role="form" method="post" action="<?php echo $_SERVER['REQUEST_URI']; ?>">
                                    <input name="admin_id" type="hidden" value="<?php echo $admin_code_encrypted; ?>">
                                    <div class="form-group">
                                        <label class="control-label col-sm-3" for="first_name">First Name:</label>
                                        <div class="col-sm-9 col-lg-5">
                                            <input type="text" class="form-control" id="first_name" name="first_name" value="<?php echo $admin_detail['first_name']; ?>">
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="control-label col-sm-3" for="last_name">Last Name:</label>
                                        <div class="col-sm-9 col-lg-5">
                                            <input type="text" class="form-control" id="last_name" name="last_name" value="<?php echo $admin_detail['last_name']; ?>">
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="control-label col-sm-3" for="email_address">Email Address:</label>
                                        <div class="col-sm-9 col-lg-5">
                                            <input type="text" class="form-control" id="email_address" name="email_address" value="<?php echo $admin_detail['email']; ?>" readonly>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="control-label col-sm-3" for="status">Status:</label>
                                        <div class="col-sm-9 col-lg-5">
                                            <div class="radio">
                                                <label><input type="radio" name="admin_status" value="1" <?php if($admin_detail['status'] == '1') { echo "checked"; } ?> required>Active</label>
                                            </div>
                                            <div class="radio">
                                                <label><input type="radio" name="admin_status" value="2" <?php if($admin_detail['status'] == '2') { echo "checked"; } ?> required>Inactive</label>
                                            </div>
                                            <div class="radio">
                                                <label><input type="radio" name="admin_status" value="3" <?php if($admin_detail['status'] == '3') { echo "checked"; } ?> required>Suspended</label>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <div class="col-sm-offset-3 col-sm-9">
                                            <button type="button" data-target="#confirm-modify-profile" data-toggle="modal" class="btn btn-success">Modify Profile</button>
                                        </div>
                                    </div>
                                    
                                    <!--Modal - confirmation boxes--> 
                                    <div id="confirm-modify-profile" tabindex="-1" role="dialog" aria-hidden="true" class="modal fade">
                                        <div class="modal-dialog">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <button type="button" data-dismiss="modal" aria-hidden="true"
                                                        class="close">&times;</button>
                                                    <h4 class="modal-title">Modify Admin</h4></div>
                                                <div class="modal-body">Are you sure you want to modify the admin profile? This action cannot be reversed.</div>
                                                <div class="modal-footer">
                                                    <input name="process" type="submit" class="btn btn-success" value="Proceed">
                                                    <button type="submit" name="close" data-dismiss="modal" class="btn btn-danger">Close!</button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </form>
                                
                                
                                <hr/>
                                
                                <h5>Admin Privileges</h5>
                                <p>Assign rights to the selected admin user. When completed, click the 'Save' button at the end of this page.</p>
                                
                                <form class="form-horizontal" role="form" method="post" action="<?php echo $REQUEST_URI; ?>">
                                    <input name="admin_id" type="hidden" value="<?php echo $admin_code_encrypted; ?>">
                                    <p><strong>Admin</strong></p>
                                    <div class="form-group row">
                                        <div class="col-sm-4"><div class="checkbox"><label for=""><input type="checkbox" name="pageid[]" value="1" id="" <?php if (in_array(1, $my_pages)) { echo 'checked="checked"'; } ?>/> Add Admin</label></div></div>
                                        <div class="col-sm-4"><div class="checkbox"><label for=""><input type="checkbox" name="pageid[]" value="2" id="" <?php if (in_array(2, $my_pages)) { echo 'checked="checked"'; } ?>/> View Admin</label></div></div>
                                        <div class="col-sm-4"><div class="checkbox"><label for=""><input type="checkbox" name="pageid[]" value="3" id="" <?php if (in_array(3, $my_pages)) { echo 'checked="checked"'; } ?>/> Add Bulletin</label></div></div>
                                        <div class="col-sm-4"><div class="checkbox"><label for=""><input type="checkbox" name="pageid[]" value="4" id="" <?php if (in_array(4, $my_pages)) { echo 'checked="checked"'; } ?>/> Manage Bulletin</label></div></div>
                                        <div class="col-sm-4"><div class="checkbox"><label for=""><input type="checkbox" name="pageid[]" value="5" id="" <?php if (in_array(5, $my_pages)) { echo 'checked="checked"'; } ?>/> Bulletin Centre</label></div></div>
                                        <div class="col-sm-4"><div class="checkbox"><label for=""><input type="checkbox" name="pageid[]" value="6" id="" <?php if (in_array(6, $my_pages)) { echo 'checked="checked"'; } ?>/> Add Snappy Help</label></div></div>
                                        <div class="col-sm-4"><div class="checkbox"><label for=""><input type="checkbox" name="pageid[]" value="7" id="" <?php if (in_array(7, $my_pages)) { echo 'checked="checked"'; } ?>/> Manage Snappy Help</label></div></div>
                                    </div>
                                    <hr/>
                                    
                                    <p><strong>Client Management</strong></p>
                                    <div class="form-group row">
                                        <div class="col-sm-4"><div class="checkbox"><label for=""><input type="checkbox" name="pageid[]" value="8" id="" <?php if (in_array(8, $my_pages)) { echo 'checked="checked"'; } ?>/> Add Client</label></div></div>
                                        <div class="col-sm-4"><div class="checkbox"><label for=""><input type="checkbox" name="pageid[]" value="9" id="" <?php if (in_array(9, $my_pages)) { echo 'checked="checked"'; } ?>/> Flag Account</label></div></div>
                                        <div class="col-sm-4"><div class="checkbox"><label for=""><input type="checkbox" name="pageid[]" value="10" id="" <?php if (in_array(10, $my_pages)) { echo 'checked="checked"'; } ?>/> View Flags</label></div></div>
                                        <div class="col-sm-4"><div class="checkbox"><label for=""><input type="checkbox" name="pageid[]" value="11" id="" <?php if (in_array(11, $my_pages)) { echo 'checked="checked"'; } ?>/> Search Clients</label></div></div>
                                        <div class="col-sm-4"><div class="checkbox"><label for=""><input type="checkbox" name="pageid[]" value="12" id="" <?php if (in_array(12, $my_pages)) { echo 'checked="checked"'; } ?>/> View Clients</label></div></div>
                                        <div class="col-sm-4"><div class="checkbox"><label for=""><input type="checkbox" name="pageid[]" value="201" id="" <?php if (in_array(201, $my_pages)) { echo 'checked="checked"'; } ?>/> Delete Client</label></div></div>
                                        <div class="col-sm-4"><div class="checkbox"><label for=""><input type="checkbox" name="pageid[]" value="13" id="" <?php if (in_array(13, $my_pages)) { echo 'checked="checked"'; } ?>/> Moderate IFX Account</label></div></div>
                                        <div class="col-sm-4"><div class="checkbox"><label for=""><input type="checkbox" name="pageid[]" value="14" id="" <?php if (in_array(14, $my_pages)) { echo 'checked="checked"'; } ?>/> Moderate Profile</label></div></div>
                                        <div class="col-sm-4"><div class="checkbox"><label for=""><input type="checkbox" name="pageid[]" value="15" id="" <?php if (in_array(15, $my_pages)) { echo 'checked="checked"'; } ?>/> Verify Documents</label></div></div>
                                        <div class="col-sm-4"><div class="checkbox"><label for=""><input type="checkbox" name="pageid[]" value="202" id="" <?php if (in_array(202, $my_pages)) { echo 'checked="checked"'; } ?>/> Failed SMS Code</label></div></div>
                                        <div class="col-sm-4"><div class="checkbox"><label for=""><input type="checkbox" name="pageid[]" value="16" id="" <?php if (in_array(16, $my_pages)) { echo 'checked="checked"'; } ?>/> Moderate Bank Account</label></div></div>
                                        <div class="col-sm-4"><div class="checkbox"><label for=""><input type="checkbox" name="pageid[]" value="17" id="" <?php if (in_array(17, $my_pages)) { echo 'checked="checked"'; } ?>/> Level 1 Clients</label></div></div>
                                        <div class="col-sm-4"><div class="checkbox"><label for=""><input type="checkbox" name="pageid[]" value="18" id="" <?php if (in_array(18, $my_pages)) { echo 'checked="checked"'; } ?>/> Level 2 Clients</label></div></div>
                                        <div class="col-sm-4"><div class="checkbox"><label for=""><input type="checkbox" name="pageid[]" value="19" id="" <?php if (in_array(19, $my_pages)) { echo 'checked="checked"'; } ?>/> Level 3 Clients</label></div></div>
                                        <div class="col-sm-4"><div class="checkbox"><label for=""><input type="checkbox" name="pageid[]" value="20" id="" <?php if (in_array(20, $my_pages)) { echo 'checked="checked"'; } ?>/> Unverified Clients</label></div></div>
                                        <div class="col-sm-4"><div class="checkbox"><label for=""><input type="checkbox" name="pageid[]" value="21" id="" <?php if (in_array(21, $my_pages)) { echo 'checked="checked"'; } ?>/> View ILPR Clients</label></div></div>
                                        <div class="col-sm-4"><div class="checkbox"><label for=""><input type="checkbox" name="pageid[]" value="22" id="" <?php if (in_array(22, $my_pages)) { echo 'checked="checked"'; } ?>/> View Non-ILPR Clients</label></div></div>
                                    </div>
                                    <hr/>

                                    <p><strong>System Insights</strong></p>
                                    <div class="form-group row">
                                        <div class="col-sm-4"><div class="checkbox"><label for=""><input type="checkbox" name="pageid[]" value="23" id="" <?php if (in_array(23, $my_pages)) { echo 'checked="checked"'; } ?>/> Last Month Funding</label></div></div>
                                        <div class="col-sm-4"><div class="checkbox"><label for=""><input type="checkbox" name="pageid[]" value="24" id="" <?php if (in_array(24, $my_pages)) { echo 'checked="checked"'; } ?>/> Last Month Withdrawal</label></div></div>
                                        <div class="col-sm-4"><div class="checkbox"><label for=""><input type="checkbox" name="pageid[]" value="25" id="" <?php if (in_array(25, $my_pages)) { echo 'checked="checked"'; } ?>/> Last Month New Client</label></div></div>
                                        <div class="col-sm-4"><div class="checkbox"><label for=""><input type="checkbox" name="pageid[]" value="26" id="" <?php if (in_array(26, $my_pages)) { echo 'checked="checked"'; } ?>/> Training Campaign Insights</label></div></div>
                                        <div class="col-sm-4"><div class="checkbox"><label for=""><input type="checkbox" name="pageid[]" value="27" id="" <?php if (in_array(27, $my_pages)) { echo 'checked="checked"'; } ?>/> Daily Funding</label></div></div>
                                    </div>
                                    <hr/>
                                    
                                    <p><strong>Deposit Transactions</strong></p>
                                    <div class="form-group row">
                                        <div class="col-sm-4"><div class="checkbox"><label for=""><input type="checkbox" name="pageid[]" value="28" id="" <?php if (in_array(28, $my_pages)) { echo 'checked="checked"'; } ?>/> Deposit - Add</label></div></div>
                                        <div class="col-sm-4"><div class="checkbox"><label for=""><input type="checkbox" name="pageid[]" value="29" id="" <?php if (in_array(29, $my_pages)) { echo 'checked="checked"'; } ?>/> Deposit - Search</label></div></div>
                                        <div class="col-sm-4"><div class="checkbox"><label for=""><input type="checkbox" name="pageid[]" value="30" id="" <?php if (in_array(30, $my_pages)) { echo 'checked="checked"'; } ?>/> Deposit - Pending</label></div></div>
                                        <div class="col-sm-4"><div class="checkbox"><label for=""><input type="checkbox" name="pageid[]" value="31" id="" <?php if (in_array(31, $my_pages)) { echo 'checked="checked"'; } ?>/> Deposit - Notified</label></div></div>
                                        <div class="col-sm-4"><div class="checkbox"><label for=""><input type="checkbox" name="pageid[]" value="32" id="" <?php if (in_array(32, $my_pages)) { echo 'checked="checked"'; } ?>/> Deposit - Confirmed</label></div></div>
                                        <div class="col-sm-4"><div class="checkbox"><label for=""><input type="checkbox" name="pageid[]" value="33" id="" <?php if (in_array(33, $my_pages)) { echo 'checked="checked"'; } ?>/> Deposit - Completed</label></div></div>
                                        <div class="col-sm-4"><div class="checkbox"><label for=""><input type="checkbox" name="pageid[]" value="34" id="" <?php if (in_array(34, $my_pages)) { echo 'checked="checked"'; } ?>/> Deposit - Declined</label></div></div>
                                        <div class="col-sm-4"><div class="checkbox"><label for=""><input type="checkbox" name="pageid[]" value="35" id="" <?php if (in_array(35, $my_pages)) { echo 'checked="checked"'; } ?>/> Deposit - Failed</label></div></div>
                                        <div class="col-sm-4"><div class="checkbox"><label for=""><input type="checkbox" name="pageid[]" value="36" id="" <?php if (in_array(36, $my_pages)) { echo 'checked="checked"'; } ?>/> Deposit - All</label></div></div>
                                        <div class="col-sm-4"><div class="checkbox"><label for=""><input type="checkbox" name="pageid[]" value="37" id="" <?php if (in_array(37, $my_pages)) { echo 'checked="checked"'; } ?>/> Transaction Calculator</label></div></div>
                                        <div class="col-sm-4"><div class="checkbox"><label for=""><input type="checkbox" name="pageid[]" value="38" id="" <?php if (in_array(38, $my_pages)) { echo 'checked="checked"'; } ?>/> Deposit - Reversal</label></div></div>
                                    </div>
                                    <hr/>
                                    
                                    <p><strong>Withdrawal Transactions</strong></p>
                                    <div class="form-group row">
                                        <div class="col-sm-4"><div class="checkbox"><label for=""><input type="checkbox" name="pageid[]" value="40" id="" <?php if (in_array(40, $my_pages)) { echo 'checked="checked"'; } ?>/> Withdrawal - Search</label></div></div>
                                        <div class="col-sm-4"><div class="checkbox"><label for=""><input type="checkbox" name="pageid[]" value="41" id="" <?php if (in_array(41, $my_pages)) { echo 'checked="checked"'; } ?>/> Withdrawal - Initiated</label></div></div>
                                        <div class="col-sm-4"><div class="checkbox"><label for=""><input type="checkbox" name="pageid[]" value="42" id="" <?php if (in_array(42, $my_pages)) { echo 'checked="checked"'; } ?>/> Withdrawal - Confirmed</label></div></div>
                                        <div class="col-sm-4"><div class="checkbox"><label for=""><input type="checkbox" name="pageid[]" value="43" id="" <?php if (in_array(43, $my_pages)) { echo 'checked="checked"'; } ?>/> Withdrawal - IFX Debited</label></div></div>
                                        <div class="col-sm-4"><div class="checkbox"><label for=""><input type="checkbox" name="pageid[]" value="44" id="" <?php if (in_array(44, $my_pages)) { echo 'checked="checked"'; } ?>/> Withdrawal - Completed</label></div></div>
                                        <div class="col-sm-4"><div class="checkbox"><label for=""><input type="checkbox" name="pageid[]" value="45" id="" <?php if (in_array(45, $my_pages)) { echo 'checked="checked"'; } ?>/> Withdrawal - Declined/Failed</label></div></div>
                                        <div class="col-sm-4"><div class="checkbox"><label for=""><input type="checkbox" name="pageid[]" value="46" id="" <?php if (in_array(46, $my_pages)) { echo 'checked="checked"'; } ?>/> Withdrawal - All</label></div></div>
                                        <div class="col-sm-4"><div class="checkbox"><label for=""><input type="checkbox" name="pageid[]" value="47" id="" <?php if (in_array(47, $my_pages)) { echo 'checked="checked"'; } ?>/> Transaction Calculator</label></div></div>
                                    </div>
                                    <hr/>

                                    <p><strong>Events</strong></p>
                                    <div class="form-group row">
                                        <div class="col-sm-4"><div class="checkbox"><label for=""><input type="checkbox" name="pageid[]" value="48" id="" <?php if (in_array(48, $my_pages)) { echo 'checked="checked"'; } ?>/> New Dinner 2016 Reg</label></div></div>
                                        <div class="col-sm-4"><div class="checkbox"><label for=""><input type="checkbox" name="pageid[]" value="49" id="" <?php if (in_array(49, $my_pages)) { echo 'checked="checked"'; } ?>/> All Dinner 2016 Reg</label></div></div>
                                        <div class="col-sm-4"><div class="checkbox"><label for=""><input type="checkbox" name="pageid[]" value="50" id="" <?php if (in_array(50, $my_pages)) { echo 'checked="checked"'; } ?>/> All Lekki Office Warming Reg</label></div></div>
                                    </div>
                                    <hr/>

                                    <p><strong>Partner Management</strong></p>
                                    <div class="form-group row">
                                        <div class="col-sm-4"><div class="checkbox"><label for=""><input type="checkbox" name="pageid[]" value="51" id="" <?php if (in_array(51, $my_pages)) { echo 'checked="checked"'; } ?>/> View Partners</label></div></div>
                                        <div class="col-sm-4"><div class="checkbox"><label for=""><input type="checkbox" name="pageid[]" value="52" id="" <?php if (in_array(52, $my_pages)) { echo 'checked="checked"'; } ?>/> Update Partners</label></div></div>
                                        <div class="col-sm-4"><div class="checkbox"><label for=""><input type="checkbox" name="pageid[]" value="53" id="" <?php if (in_array(53, $my_pages)) { echo 'checked="checked"'; } ?>/> Pay Partners</label></div></div>
                                        <div class="col-sm-4"><div class="checkbox"><label for=""><input type="checkbox" name="pageid[]" value="54" id="" <?php if (in_array(54, $my_pages)) { echo 'checked="checked"'; } ?>/> Set Commission Rate</label></div></div>
                                        <div class="col-sm-4"><div class="checkbox"><label for=""><input type="checkbox" name="pageid[]" value="55" id="" <?php if (in_array(55, $my_pages)) { echo 'checked="checked"'; } ?>/> Pending Payout</label></div></div>
                                        <div class="col-sm-4"><div class="checkbox"><label for=""><input type="checkbox" name="pageid[]" value="56" id="" <?php if (in_array(56, $my_pages)) { echo 'checked="checked"'; } ?>/> Payout History</label></div></div>
                                    </div>
                                    <hr/>
                                    
                                    <p><strong>Rewards</strong></p>
                                    <div class="form-group row">
                                        <div class="col-sm-4"><div class="checkbox"><label for=""><input type="checkbox" name="pageid[]" value="57" id="" <?php if (in_array(57, $my_pages)) { echo 'checked="checked"'; } ?>/> Upload Trading Commission</label></div></div>
                                        <div class="col-sm-4"><div class="checkbox"><label for=""><input type="checkbox" name="pageid[]" value="58" id="" <?php if (in_array(58, $my_pages)) { echo 'checked="checked"'; } ?>/> Commission Upload Log</label></div></div>
                                        <div class="col-sm-4"><div class="checkbox"><label for=""><input type="checkbox" name="pageid[]" value="59" id="" <?php if (in_array(59, $my_pages)) { echo 'checked="checked"'; } ?>/> View Commissions</label></div></div>
                                        <div class="col-sm-4"><div class="checkbox"><label for=""><input type="checkbox" name="pageid[]" value="60" id="" <?php if (in_array(60, $my_pages)) { echo 'checked="checked"'; } ?>/> Reports</label></div></div>
                                        <div class="col-sm-4"><div class="checkbox"><label for=""><input type="checkbox" name="pageid[]" value="61" id="" <?php if (in_array(61, $my_pages)) { echo 'checked="checked"'; } ?>/> Loyalty Rank Archive</label></div></div>
                                        <div class="col-sm-4"><div class="checkbox"><label for=""><input type="checkbox" name="pageid[]" value="62" id="" <?php if (in_array(62, $my_pages)) { echo 'checked="checked"'; } ?>/> Loyalty Point Claimed</label></div></div>
                                    </div>
                                    <hr/>
                                    
                                    <p><strong>Education Service</strong></p>
                                    <div class="form-group row">
                                        <div class="col-sm-4"><div class="checkbox"><label for=""><input type="checkbox" name="pageid[]" value="63" id="" <?php if (in_array(63, $my_pages)) { echo 'checked="checked"'; } ?>/> All Courses</label></div></div>
                                        <div class="col-sm-4"><div class="checkbox"><label for=""><input type="checkbox" name="pageid[]" value="64" id="" <?php if (in_array(64, $my_pages)) { echo 'checked="checked"'; } ?>/> Course Messages</label></div></div>
                                        <div class="col-sm-4"><div class="checkbox"><label for=""><input type="checkbox" name="pageid[]" value="65" id="" <?php if (in_array(65, $my_pages)) { echo 'checked="checked"'; } ?>/> Student Payments</label></div></div>
                                        <div class="col-sm-4"><div class="checkbox"><label for=""><input type="checkbox" name="pageid[]" value="66" id="" <?php if (in_array(66, $my_pages)) { echo 'checked="checked"'; } ?>/> Student Deposits</label></div></div>
                                        <div class="col-sm-4"><div class="checkbox"><label for=""><input type="checkbox" name="pageid[]" value="73" id="" <?php if (in_array(73, $my_pages)) { echo 'checked="checked"'; } ?>/> Free Training Campaign</label></div></div>
                                        <div class="col-sm-4"><div class="checkbox"><label for=""><input type="checkbox" name="pageid[]" value="74" id="" <?php if (in_array(74, $my_pages)) { echo 'checked="checked"'; } ?>/> Education Forum Registration</label></div></div>
                                    </div>
                                    <hr/>
                                    
                                    <p><strong>Article Management</strong></p>
                                    <div class="form-group row">
                                        <div class="col-sm-4"><div class="checkbox"><label for=""><input type="checkbox" name="pageid[]" value="75" id="" <?php if (in_array(75, $my_pages)) { echo 'checked="checked"'; } ?>/> Add Article</label></div></div>
                                        <div class="col-sm-4"><div class="checkbox"><label for=""><input type="checkbox" name="pageid[]" value="76" id="" <?php if (in_array(76, $my_pages)) { echo 'checked="checked"'; } ?>/> Manage Article</label></div></div>
                                    </div>
                                    <hr/>
                                    
                                    <p><strong>Signal Management</strong></p>
                                    <div class="form-group row">
                                        <div class="col-sm-4"><div class="checkbox"><label for=""><input type="checkbox" name="pageid[]" value="77" id="" <?php if (in_array(77, $my_pages)) { echo 'checked="checked"'; } ?>/> Update Daily Signal</label></div></div>
                                        <div class="col-sm-4"><div class="checkbox"><label for=""><input type="checkbox" name="pageid[]" value="78" id="" <?php if (in_array(78, $my_pages)) { echo 'checked="checked"'; } ?>/> Weekly Market Analysis</label></div></div>
                                    </div>
                                    <hr/>
                                    
                                    <p><strong>System Messages</strong></p>
                                    <div class="form-group row">
                                        <div class="col-sm-4"><div class="checkbox"><label for=""><input type="checkbox" name="pageid[]" value="79" id="" <?php if (in_array(79, $my_pages)) { echo 'checked="checked"'; } ?>/> System Messages</label></div></div>
                                    </div>
                                    <hr/>
                                    
                                    <p><strong>Campaign</strong></p>
                                    <div class="form-group row">
                                        <div class="col-sm-4"><div class="checkbox"><label for=""><input type="checkbox" name="pageid[]" value="80" id="" <?php if (in_array(80, $my_pages)) { echo 'checked="checked"'; } ?>/> New Campaign Solo Group</label></div></div>
                                        <div class="col-sm-4"><div class="checkbox"><label for=""><input type="checkbox" name="pageid[]" value="81" id="" <?php if (in_array(81, $my_pages)) { echo 'checked="checked"'; } ?>/> All Campaign Solo Group</label></div></div>
                                        <div class="col-sm-4"><div class="checkbox"><label for=""><input type="checkbox" name="pageid[]" value="82" id="" <?php if (in_array(82, $my_pages)) { echo 'checked="checked"'; } ?>/> New Solo Campaign</label></div></div>
                                        <div class="col-sm-4"><div class="checkbox"><label for=""><input type="checkbox" name="pageid[]" value="83" id="" <?php if (in_array(83, $my_pages)) { echo 'checked="checked"'; } ?>/> All Solo Campaign</label></div></div>
                                        <div class="col-sm-4"><div class="checkbox"><label for=""><input type="checkbox" name="pageid[]" value="85" id="" <?php if (in_array(85, $my_pages)) { echo 'checked="checked"'; } ?>/> New Category</label></div></div>
                                        <div class="col-sm-4"><div class="checkbox"><label for=""><input type="checkbox" name="pageid[]" value="86" id="" <?php if (in_array(86, $my_pages)) { echo 'checked="checked"'; } ?>/> All Categories</label></div></div>
                                        <div class="col-sm-4"><div class="checkbox"><label for=""><input type="checkbox" name="pageid[]" value="87" id="" <?php if (in_array(87, $my_pages)) { echo 'checked="checked"'; } ?>/> Compose SMS</label></div></div>
                                        <div class="col-sm-4"><div class="checkbox"><label for=""><input type="checkbox" name="pageid[]" value="88" id="" <?php if (in_array(88, $my_pages)) { echo 'checked="checked"'; } ?>/> Compose Email</label></div></div>
                                        <div class="col-sm-4"><div class="checkbox"><label for=""><input type="checkbox" name="pageid[]" value="89" id="" <?php if (in_array(89, $my_pages)) { echo 'checked="checked"'; } ?>/> Email Campaign</label></div></div>
                                        <div class="col-sm-4"><div class="checkbox"><label for=""><input type="checkbox" name="pageid[]" value="90" id="" <?php if (in_array(90, $my_pages)) { echo 'checked="checked"'; } ?>/> SMS Campaign</label></div></div>
                                        <div class="col-sm-4"><div class="checkbox"><label for=""><input type="checkbox" name="pageid[]" value="91" id="" <?php if (in_array(91, $my_pages)) { echo 'checked="checked"'; } ?>/> Sales Management</label></div></div>
                                    </div>
                                    <hr/>
                                    
                                    <p><strong>Careers</strong></p>
                                    <div class="form-group row">
                                        <div class="col-sm-4"><div class="checkbox"><label for=""><input type="checkbox" name="pageid[]" value="92" id="" <?php if (in_array(92, $my_pages)) { echo 'checked="checked"'; } ?>/> New Job</label></div></div>
                                        <div class="col-sm-4"><div class="checkbox"><label for=""><input type="checkbox" name="pageid[]" value="93" id="" <?php if (in_array(93, $my_pages)) { echo 'checked="checked"'; } ?>/> All Jobs</label></div></div>
                                        <div class="col-sm-4"><div class="checkbox"><label for=""><input type="checkbox" name="pageid[]" value="94" id="" <?php if (in_array(94, $my_pages)) { echo 'checked="checked"'; } ?>/> Application - All</label></div></div>
                                        <div class="col-sm-4"><div class="checkbox"><label for=""><input type="checkbox" name="pageid[]" value="95" id="" <?php if (in_array(95, $my_pages)) { echo 'checked="checked"'; } ?>/> Application - Submitted</label></div></div>
                                        <div class="col-sm-4"><div class="checkbox"><label for=""><input type="checkbox" name="pageid[]" value="96" id="" <?php if (in_array(96, $my_pages)) { echo 'checked="checked"'; } ?>/> Application - Review</label></div></div>
                                        <div class="col-sm-4"><div class="checkbox"><label for=""><input type="checkbox" name="pageid[]" value="97" id="" <?php if (in_array(97, $my_pages)) { echo 'checked="checked"'; } ?>/> Application - Interview</label></div></div>
                                        <div class="col-sm-4"><div class="checkbox"><label for=""><input type="checkbox" name="pageid[]" value="98" id="" <?php if (in_array(98, $my_pages)) { echo 'checked="checked"'; } ?>/> Application - Employed</label></div></div>
                                        <div class="col-sm-4"><div class="checkbox"><label for=""><input type="checkbox" name="pageid[]" value="98" id="" <?php if (in_array(98, $my_pages)) { echo 'checked="checked"'; } ?>/> Application - Rejected</label></div></div>
                                    </div>
                                    <hr/>
                                    
                                    <p><strong>System Settings</strong></p>
                                    <div class="form-group row">
                                        <div class="col-sm-4"><div class="checkbox"><label for=""><input type="checkbox" name="pageid[]" value="99" id="" <?php if (in_array(99, $my_pages)) { echo 'checked="checked"'; } ?>/> View Exchange Rates Log</label></div></div>
                                        <div class="col-sm-4"><div class="checkbox"><label for=""><input type="checkbox" name="pageid[]" value="100" id="" <?php if (in_array(100, $my_pages)) { echo 'checked="checked"'; } ?>/> Log Exchange Rates</label></div></div>
                                        <div class="col-sm-4"><div class="checkbox"><label for=""><input type="checkbox" name="pageid[]" value="101" id="" <?php if (in_array(101, $my_pages)) { echo 'checked="checked"'; } ?>/> Rates Settings</label></div></div>
                                        <div class="col-sm-4"><div class="checkbox"><label for=""><input type="checkbox" name="pageid[]" value="102" id="" <?php if (in_array(102, $my_pages)) { echo 'checked="checked"'; } ?>/> Schedules Settings</label></div></div>
                                    </div>
                                    <hr/>
                                    
                                    <p><strong>Performance Reports</strong></p>
                                    <div class="form-group row">
                                        <div class="col-sm-4"><div class="checkbox"><label for=""><input type="checkbox" name="pageid[]" value="103" id="" <?php if (in_array(103, $my_pages)) { echo 'checked="checked"'; } ?>/> Active Clients</label></div></div>
                                        <div class="col-sm-4"><div class="checkbox"><label for=""><input type="checkbox" name="pageid[]" value="104" id="" <?php if (in_array(104, $my_pages)) { echo 'checked="checked"'; } ?>/> Service Charges</label></div></div>
                                        <div class="col-sm-4"><div class="checkbox"><label for=""><input type="checkbox" name="pageid[]" value="105" id="" <?php if (in_array(105, $my_pages)) { echo 'checked="checked"'; } ?>/> VAT Charges</label></div></div>
                                        <div class="col-sm-4"><div class="checkbox"><label for=""><input type="checkbox" name="pageid[]" value="106" id="" <?php if (in_array(106, $my_pages)) { echo 'checked="checked"'; } ?>/> Saldo Reports</label></div></div>
                                        <div class="col-sm-4"><div class="checkbox"><label for=""><input type="checkbox" name="pageid[]" value="107" id="" <?php if (in_array(107, $my_pages)) { echo 'checked="checked"'; } ?>/> Commission Reports</label></div></div>
                                        <div class="col-sm-4"><div class="checkbox"><label for=""><input type="checkbox" name="pageid[]" value="108" id="" <?php if (in_array(108, $my_pages)) { echo 'checked="checked"'; } ?>/> Daily Funding Reports</label></div></div>
                                        <div class="col-sm-4"><div class="checkbox"><label for=""><input type="checkbox" name="pageid[]" value="109" id="" <?php if (in_array(109, $my_pages)) { echo 'checked="checked"'; } ?>/> Daily Withdrawal Reports</label></div></div>
                                    </div>
                                    <hr/>
                                    
                                    <p><strong>Prospect</strong></p>
                                    <div class="form-group row">
                                        <div class="col-sm-4"><div class="checkbox"><label for=""><input type="checkbox" name="pageid[]" value="110" id="" <?php if (in_array(110, $my_pages)) { echo 'checked="checked"'; } ?>/> Manage Prospect Source</label></div></div>
                                        <div class="col-sm-4"><div class="checkbox"><label for=""><input type="checkbox" name="pageid[]" value="111" id="" <?php if (in_array(111, $my_pages)) { echo 'checked="checked"'; } ?>/> Add New Prospect</label></div></div>
                                        <div class="col-sm-4"><div class="checkbox"><label for=""><input type="checkbox" name="pageid[]" value="112" id="" <?php if (in_array(112, $my_pages)) { echo 'checked="checked"'; } ?>/> Manage Prospects</label></div></div>
                                    </div>
                                    <hr/>
                                    
                                    <p><strong>Surveys &amp; Feedbacks</strong></p>
                                    <div class="form-group row">
                                        <div class="col-sm-4"><div class="checkbox"><label for=""><input type="checkbox" name="pageid[]" value="113" id="" <?php if (in_array(113, $my_pages)) { echo 'checked="checked"'; } ?>/> New Survey</label></div></div>
                                        <div class="col-sm-4"><div class="checkbox"><label for=""><input type="checkbox" name="pageid[]" value="114" id="" <?php if (in_array(114, $my_pages)) { echo 'checked="checked"'; } ?>/> Survey Feedbacks</label></div></div>
                                        <div class="col-sm-4"><div class="checkbox"><label for=""><input type="checkbox" name="pageid[]" value="115" id="" <?php if (in_array(115, $my_pages)) { echo 'checked="checked"'; } ?>/> Manage Surveys</label></div></div>
                                        <div class="col-sm-4"><div class="checkbox"><label for=""><input type="checkbox" name="pageid[]" value="116" id="" <?php if (in_array(116, $my_pages)) { echo 'checked="checked"'; } ?>/> Testimonials</label></div></div>
                                    </div>
                                    <hr/>

                                    <br/>
                                    <div class="form-group">
                                        <div class="col-sm-12">
                                            <button type="button" data-target="#edit-admin-rights" data-toggle="modal" class="btn btn-success"><i class="fa fa-save fa-fw"></i> Save</button>
                                        </div>
                                    </div>

                                    <!-- Modal - confirmation boxes -->
                                    <div id="edit-admin-rights" tabindex="-1" role="dialog" aria-hidden="true" class="modal fade">
                                        <div class="modal-dialog">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <button type="button" data-dismiss="modal" aria-hidden="true"
                                                        class="close">&times;</button>
                                                    <h4 class="modal-title">ASSIGN RIGHTS</h4></div>
                                                <div class="modal-body">Are you sure about the changes you have made?</div>
                                                <div class="modal-footer">
                                                    <input name="approve" type="submit" class="btn btn-success" value="Approve !">
                                                    <button type="button" class="btn btn-danger" data-dismiss="modal" title="Close">Close</button>
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