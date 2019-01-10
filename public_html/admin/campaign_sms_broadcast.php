<?php
require_once("../init/initialize_admin.php");
if (!$session_admin->is_logged_in()) {
    redirect_to("login.php");
}

$get_params = allowed_get_params(['x', 'id']);
$campaign_sms_id_encrypted = $get_params['id'];
$campaign_sms_id = dec_enc('decrypt',  $campaign_sms_id_encrypted);

if(!empty($campaign_sms_id)) {
    $selected_campaign_sms = $system_object->get_campaign_sms_by_id($campaign_sms_id);
    if(!$selected_campaign_sms) { redirect_to("campaign_sms_view.php"); } // Campaign cannot be found
} else {
    redirect_to("campaign_sms_view.php");
}

// Send campaign to the associated client group
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['send_campaign'])) {
    
    $campaign_sms_id = $_POST['campaign_sms_no'];
        
    $send_campaign = $system_object->schedule_campaign($campaign_sms_id, "sms");
    
    if($send_campaign) {
        $message_success = "Your campaign has been queued and will start sending shortly.";
    } else {
        $message_error = "Something went wrong, your campaign could not be queued, try again.";
    }
}

// Send test
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['send_test'])) {
    
    $campaign_sms_id = $_POST['campaign_sms_no'];
    $selected_campaign_sms = $system_object->get_campaign_sms_by_id($campaign_sms_id);

    $my_message = stripslashes($selected_campaign_sms['content']);

    $email = $_POST['test_email'];  //only specified
    $email = trim(str_replace(" ", "", $email));
    $arr1 = explode(',', $email);

    foreach($arr1 as $sendto) {
        $query = "SELECT phone, user_code FROM user WHERE email = '$sendto' LIMIT 1";
        $result = $db_handle->runQuery($query);
        $selected_member = $db_handle->fetchAssoc($result);

        if(!empty($selected_member)) {
            $client_phone = ucwords(strtolower(trim($selected_member[0]['phone'])));
            $user_code = strtolower(trim($selected_member[0]['user_code']));
            $my_message = str_replace('[UC]', dec_enc('encrypt', $user_code), $my_message);
            $my_message_new = str_replace('[UC]', '', $my_message);
            $system_object->send_sms($client_phone, $my_message_new);
        }
        continue;
    }
    $message_success = "You have successfully sent the SMS test.";
}

if($get_params['x'] == 'test') {    
    $campaign_test = true;
} elseif($get_params['x'] == 'send' && $selected_campaign_sms['status'] == '2' && $selected_campaign_sms['send_status'] == '2') {
    $campaign_send = true;
} else {
    redirect_to("campaign_sms_view.php"); // URL tampered
}

?>
<!DOCTYPE html>
<html lang="en">
    <head>
        <base target="_self">
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>Instaforex Nigeria | Admin - SMS Campaign Broadcast</title>
        <meta name="title" content="Instaforex Nigeria | Admin - SMS Campaign Broadcast" />
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
                            <h4><strong>SMS CAMPAIGN BROADCAST</strong></h4>
                        </div>
                    </div>
                    
                    <div class="section-tint super-shadow">
                        <div class="row">
                            <div class="col-sm-12">
                                <?php require_once 'layouts/feedback_message.php'; ?>
                                <p><a href="campaign_sms_view.php" class="btn btn-default" title="Manage SMS Campaigns"><i class="fa fa-arrow-circle-left"></i> Manage SMS Campaigns</a></p>
                                <p><strong>Campaign Content:</strong></p>
                                <div class="alert alert-info"><?php if(isset($selected_campaign_sms['content'])) { echo $selected_campaign_sms['content']; } ?></div>

                                <form data-toggle="validator" class="form-horizontal" enctype="multipart/form-data" role="form" method="post" action="<?php echo $_SERVER['REQUEST_URI']; ?>">
                                    <input type="hidden" name="campaign_sms_no" value="<?php if(isset($selected_campaign_sms['campaign_sms_id'])) { echo $selected_campaign_sms['campaign_sms_id']; } ?>" />
                                    <hr /><br />
                                    <?php if($campaign_test) { ?>
                                    <p>Please enter the email addresses for the test sms, the system will automatically get the phone numbers associated with the emails.</p>
                                    <div class="form-group">
                                        <div class="col-sm-12"><label class="control-label" for="test_sms">Test SMS:</label></div>
                                        <div class="col-sm-12">
                                            <textarea name="test_email" id="test_email" rows="3" class="form-control" required></textarea>
                                            <span class="help-block">Separate multiple email with comma</span>
                                        </div>
                                    </div>
                                    <?php } ?>
                                    
                                    <div class="form-group">
                                        <div class="col-sm-12">
                                            <?php if($campaign_test) { ?>
                                            <button type="button" data-target="#send-test-confirm" data-toggle="modal" class="btn btn-success"><i class="fa fa-paper-plane fa-fw"></i> Send Test SMS</button>
                                            <?php } ?>
                                            
                                            <?php if($campaign_send) { ?>
                                            <button type="button" data-target="#send-campaign-confirm" data-toggle="modal" class="btn btn-success"><i class="fa fa-paper-plane fa-fw"></i> Send Campaign</button>
                                            <?php } ?>
                                        </div>
                                    </div>

                                    <!-- Modal - confirmation boxes -->
                                    <div id="send-test-confirm" tabindex="-1" role="dialog" aria-hidden="true" class="modal fade">
                                        <div class="modal-dialog">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <button type="button" data-dismiss="modal" aria-hidden="true"
                                                        class="close">&times;</button>
                                                    <h4 class="modal-title">Send Test SMS Confirmation</h4></div>
                                                <div class="modal-body">Do you want to send test sms now?</div>
                                                <div class="modal-footer">
                                                    <input name="send_test" type="submit" class="btn btn-success" value="Send Test">
                                                    <button type="submit" name="decline" onClick="window.close();" data-dismiss="modal" class="btn btn-danger">Close !</button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    
                                    <div id="send-campaign-confirm" tabindex="-1" role="dialog" aria-hidden="true" class="modal fade">
                                        <div class="modal-dialog">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <button type="button" data-dismiss="modal" aria-hidden="true"
                                                        class="close">&times;</button>
                                                    <h4 class="modal-title">Send SMS Campaign Confirmation</h4></div>
                                                <div class="modal-body">Do you want to send sms campaign now? This cannot be reversed, sms will be sent to associated client group.</div>
                                                <div class="modal-footer">
                                                    <input name="send_campaign" type="submit" class="btn btn-success" value="Send Campaign">
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