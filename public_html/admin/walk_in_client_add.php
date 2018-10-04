<?php
require_once("../init/initialize_admin.php");
if (!$session_admin->is_logged_in()) {
    redirect_to("login.php");
}

$office_locations = $system_object->get_office_locations();

if (isset($_POST['process'])) {
    foreach($_POST as $key => $value) {
        $_POST[$key] = $db_handle->sanitizePost(trim($value));
    }

    extract($_POST);

    // Log walk in client record
    // First collate the interest

    $client_interest = array();

    if($int_fund == "funding") { array_push($client_interest, $int_fund); }
    if($int_withdrawal == "withdrawal") { array_push($client_interest, $int_withdrawal); }
    if($int_verify == "verification") { array_push($client_interest, $int_verify); }
    if($int_acct_open == "account_opening") { array_push($client_interest, $int_acct_open); }
    if($int_inquiry == "inquiry") { array_push($client_interest, $int_inquiry); }
    if($int_other == "other") { array_push($client_interest, $int_other); }

    $client_interest = implode(", ", $client_interest);
    $admin_code = $_SESSION['admin_unique_code'];

    $query = "INSERT INTO walk_in_client (admin_code, full_name, phone, email_address, trans_type, client_feedback, admin_comment, time_in, time_out, issues_record) VALUE ('$admin_code', '$full_name', '$phone_number', '$email_address', '$client_interest', '$client_feedback', '$admin_comment', '$time_in', '$time_out', '$issues_record')";
    $result = $db_handle->runQuery($query);

    if($result) {
        $message_success = "You have successfully saved the information.";
    } else {
        $message_error = "Looks like something went wrong or you didn't make any change.";
    }
}

?>
<!DOCTYPE html>
<html lang="en">
    <head>
        <base target="_self">
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>Instaforex Nigeria | Log Walk In Client</title>
        <meta name="title" content="Instaforex Nigeria | Log Walk In Client" />
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
                            <h4><strong>LOG WALK-IN CLIENT</strong></h4>
                        </div>
                    </div>
                    
                    <div class="section-tint super-shadow">
                        <div class="row">
                            <div class="col-sm-12">
                                <?php require_once 'layouts/feedback_message.php'; ?>

                                <p>Fill the form below to log walk-in client, please fill all necessary fields.</p>

                                <div class="row">
                                    <div class="col-lg-7">
                                        <form data-toggle="validator" class="form-horizontal" role="form" method="post" action="">

                                            <div class="form-group">
                                                <label class="control-label col-sm-3" for="full_name">Full Name:</label>
                                                <div class="col-sm-9"><input type="text" name="full_name" class="form-control" id="full_name" value="" required /></div>
                                            </div>

                                            <div class="form-group">
                                                <label class="control-label col-sm-3" for="email_address">Email Address:</label>
                                                <div class="col-sm-9"><input type="text" name="email_address" class="form-control" id="email_address" value="" required /></div>
                                            </div>

                                            <div class="form-group">
                                                <label class="control-label col-sm-3" for="phone_number">Phone Number:</label>
                                                <div class="col-sm-9"><input type="text" name="phone_number" class="form-control" id="phone_number" maxlength="11" value="" required /></div>
                                            </div>

                                            <div class="form-group">
                                                <label class="control-label col-sm-3" for="trans_type">Transaction Type:</label>
                                                <div class="col-sm-9">
                                                    <div class="checkbox"><label><input type="checkbox" name="int_fund" value="funding" /> Funding</label></div>
                                                    <div class="checkbox"><label><input type="checkbox" name="int_withdrawal" value="withdrawal" /> Withdrawal<br></label></div>
                                                    <div class="checkbox"><label><input type="checkbox" name="int_verify" value="verification" /> Verification<br></label></div>
                                                    <div class="checkbox"><label><input type="checkbox" name="int_acct_open" value="account_opening" /> Account Opening<br></label></div>
                                                    <div class="checkbox"><label><input type="checkbox" name="int_inquiry" value="inquiry" /> Inquiry<br></label></div>
                                                    <div class="checkbox"><label><input type="checkbox" name="int_other" value="other" /> Other</label></div>
                                                </div>
                                            </div>

                                            <div class="form-group">
                                                <label class="control-label col-sm-3" for="client_feedback">Client Feedback</label>
                                                <div class="col-sm-9"><textarea name="client_feedback" id="client_feedback" rows="3" class="form-control" required></textarea></div>
                                            </div>

                                            <div class="form-group">
                                                <label class="control-label col-sm-3" for="issues_record">Issues Record</label>
                                                <div class="col-sm-9"><textarea name="issues_record" id="issues_record" rows="3" class="form-control" required></textarea></div>
                                            </div>

                                            <div class="form-group">
                                                <label class="control-label col-sm-3" for="admin_comment">Admin Comment</label>
                                                <div class="col-sm-9"><textarea name="admin_comment" id="admin_comment" rows="3" class="form-control" required></textarea></div>
                                            </div>

                                            <div class="form-group row">
                                                <label class="control-label col-sm-3" for="time_in">Time In:</label>
                                                <div class="col-sm-9 col-lg-5">
                                                    <div class='input-group date' id='datetimepicker'>
                                                        <input name="time_in" type="text" class="form-control" value="" id='datetimepickert_in' required>
                                                        <span class="input-group-addon"><span class="fa fa-clock-o"></span></span>
                                                    </div>
                                                </div>
                                                <script type="text/javascript">
                                                    $(function () {
                                                        $('#datetimepickert_in').datetimepicker({
                                                            format: 'HH:mm:ss'
                                                        });
                                                    });
                                                </script>
                                            </div>

                                            <div class="form-group row">
                                                <label class="control-label col-sm-3" for="time_out">Time Out:</label>
                                                <div class="col-sm-9 col-lg-5">
                                                    <div class='input-group date' id='datetimepicker'>
                                                        <input name="time_out" type="text" class="form-control" value="" id='datetimepickert_out' required>
                                                        <span class="input-group-addon"><span class="fa fa-clock-o"></span></span>
                                                    </div>
                                                </div>
                                                <script type="text/javascript">
                                                    $(function () {
                                                        $('#datetimepickert_out').datetimepicker({
                                                            format: 'HH:mm:ss'
                                                        });
                                                    });
                                                </script>
                                            </div>

                                            <div class="form-group">
                                                <label class="control-label col-sm-3" for="walkin_origin">Status:</label>
                                                <div class="col-sm-9 col-lg-5">
                                                    <div class="radio">

                                                        <label><input type="radio" name="walkin_origin" value="1" required> </label>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="form-group">
                                                <div class="col-sm-offset-3 col-sm-9">
                                                    <button type="button" data-target="#add-walk-in-client" data-toggle="modal" class="btn btn-success"><i class="fa fa-save fa-fw"></i> Save</button>
                                                </div>
                                            </div>

                                            <!-- Modal - confirmation boxes -->
                                            <div id="add-walk-in-client" tabindex="-1" role="dialog" aria-hidden="true" class="modal fade">
                                                <div class="modal-dialog">
                                                    <div class="modal-content">
                                                        <div class="modal-header">
                                                            <button type="button" data-dismiss="modal" aria-hidden="true"
                                                                class="close">&times;</button>
                                                            <h4 class="modal-title">Save Walk-in Client</h4>
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
                        </div>
                    </div>

                    <!-- Unique Page Content Ends Here
                    ================================================== -->
                    
                </div>
                
            </div>
        </div>
        <?php require_once 'layouts/footer.php'; ?>
        <script src="//cdnjs.cloudflare.com/ajax/libs/moment.js/2.9.0/moment-with-locales.js"></script>
        <script src="//cdn.rawgit.com/Eonasdan/bootstrap-datetimepicker/e8bddc60e73c1ec2475f827be36e1957af72e2ea/src/js/bootstrap-datetimepicker.js"></script>
    </body>
</html>