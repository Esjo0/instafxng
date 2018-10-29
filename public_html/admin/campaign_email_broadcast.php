<?php
require_once("../init/initialize_admin.php");
if (!$session_admin->is_logged_in()) {
    redirect_to("login.php");
}
$client_operation = new clientOperation();
$from_date = date('Y-m-d', strtotime('first day of last month'));
$to_date = date('Y-m-d', strtotime('last day of last month'));
$days_left_this_month = date('t') - date('j');

// Get Month Loyalty Ranking
$query = "SELECT pr.user_code, pr.month_rank AS rank, u.first_name AS full_name
      FROM point_ranking AS pr
      INNER JOIN user AS u ON pr.user_code = u.user_code
      ORDER BY pr.month_rank DESC, full_name ASC";
$result = $db_handle->runQuery($query);
$found_loyalty_month = $db_handle->fetchAssoc($result);

// Get Year Loyalty Ranking
$query = "SELECT pr.user_code, pr.year_rank AS rank, u.first_name AS full_name
      FROM point_ranking AS pr
      INNER JOIN user AS u ON pr.user_code = u.user_code
      ORDER BY pr.year_rank DESC, full_name ASC";
$result = $db_handle->runQuery($query);
$found_loyalty_year = $db_handle->fetchAssoc($result);

$get_params = allowed_get_params(['x', 'id']);
$campaign_email_id_encrypted = $get_params['id'];
$campaign_email_id = decrypt(str_replace(" ", "+", $campaign_email_id_encrypted));
$campaign_email_id = preg_replace("/[^A-Za-z0-9 ]/", '', $campaign_email_id);

if(!empty($campaign_email_id)) {
    $selected_campaign_email = $system_object->get_campaign_email_by_id($campaign_email_id);
    if(!$selected_campaign_email) { redirect_to("campaign_email_view.php"); } // Campaign cannot be found
} else {
    redirect_to("campaign_email_view.php");
}

// Send campaign to the associated client group
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['send_campaign'])) {
    
    $campaign_email_id = $_POST['campaign_email_no'];
        
    $send_campaign = $system_object->schedule_campaign($campaign_email_id, "email");
    
    if($send_campaign) {
        $message_success = "Your campaign has been queued and will start sending shortly.";
    } else {
        $message_error = "Something went wrong, your campaign could not be queued, try again.";
    }
}

// Send test
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['send_test'])) {
    
    $campaign_email_id = $_POST['campaign_email_no'];
    $selected_campaign_email = $system_object->get_campaign_email_by_id($campaign_email_id);

    $my_subject = trim($selected_campaign_email['subject']);
    $my_message = stripslashes($selected_campaign_email['content']);
    $mail_sender = trim($selected_campaign_email['sender']);

    $email = $_POST['test_email'];  //only specified
    $email = trim(str_replace(" ", "", $email));
    $arr1 = explode(',', $email);

    foreach($arr1 as $sendto) {
        $query = "SELECT user_code, first_name FROM user WHERE email = '$sendto' LIMIT 1";
        $result = $db_handle->runQuery($query);
        $fetched_data = $db_handle->fetchAssoc($result);
        $selected_member = $fetched_data[0];

        $client_name = ucwords(strtolower(trim($selected_member['first_name'])));

        // Replace [NAME] with clients full name
        $my_message_new = str_replace('[NAME]', $client_name, $my_message);
        $my_subject_new = str_replace('[NAME]', $client_name, $my_subject);

        if(array_key_exists('user_code', $selected_member)) {
            $user_code = $selected_member['user_code'];

            $encrypted_user_code = encrypt($user_code);
            $black_friday_link = "<a title='Click Here to enjoy the splurge' style='background-color: black; color:white;' href='https://instafxng.com/black_friday_splurge.php?x=$encrypted_user_code'><strong>Black Friday Link - The Splurge</strong></a>";
            $found_position_month = in_array_r($user_code, $found_loyalty_month);
            $month_position = $found_position_month['position'];
            $month_rank = number_format(($found_position_month['rank']), 2, ".", ",");
            $month_rank_highest = number_format(($found_loyalty_month[0]['rank']), 2, ".", ",");
            $month_rank_difference = number_format(($month_rank_highest - $month_rank), 2, ".", ",");
            $month_rank_goal = number_format(($month_rank_difference / $days_left_this_month), 2, ".", ",");

            $found_position_year = in_array_r($user_code, $found_loyalty_year);
            $year_position = $found_position_year['position'];
            $year_rank = number_format(($found_position_year['rank']), 2, ".", ",");
            $year_rank_highest = number_format(($found_loyalty_year[0]['rank']), 2, ".", ",");
            $year_rank_difference = number_format(($year_rank_highest - $year_rank), 2, ".", ",");
            $year_rank_goal = number_format(($year_rank_difference / $days_left_this_month), 2, ".", ",");

            $funded = $client_operation->get_total_funding($user_code, $from_date, $to_date);
            $withdrawn = $client_operation->get_total_withdrawal($user_code, $from_date, $to_date);

            $my_message_new = str_replace('[BFL]', $black_friday_link, $my_message_new);
            $my_message_new = str_replace('[LPMP]', $month_position, $my_message_new);
            $my_message_new = str_replace('[LPMR]', $month_rank, $my_message_new);
            $my_message_new = str_replace('[LPMHR]', $month_rank_highest, $my_message_new);
            $my_message_new = str_replace('[LPMD]', $month_rank_difference, $my_message_new);
            $my_message_new = str_replace('[LPMG]', $month_rank_goal, $my_message_new);
            $my_message_new = str_replace('[LPYP]', $year_position, $my_message_new);
            $my_message_new = str_replace('[LPYR]', $year_rank, $my_message_new);
            $my_message_new = str_replace('[LPYHR]', $year_rank_highest, $my_message_new);
            $my_message_new = str_replace('[LPYG]', $year_rank_difference, $my_message_new);
            $my_message_new = str_replace('[LPYD]', $year_rank_goal, $my_message_new);
            $my_message_new = str_replace('[UC]', encrypt($user_code), $my_message_new);

            $my_message_new = str_replace('[FUNDED]', $funded, $my_message_new);
            $my_message_new = str_replace('[WITHDRAWN]', $withdrawn, $my_message_new);
            $my_subject_new = str_replace('[FUNDED]', $funded, $my_subject_new);
            $my_subject_new = str_replace('[WITHDRAWN]', $withdrawn, $my_subject_new);

            $my_message_new = str_replace('[LPMP]', '', $my_message_new);
            $my_message_new = str_replace('[LPMR]', '', $my_message_new);
            $my_message_new = str_replace('[LPMHR]', '', $my_message_new);
            $my_message_new = str_replace('[LPMD]', '', $my_message_new);
            $my_message_new = str_replace('[LPMG]', '', $my_message_new);
            $my_message_new = str_replace('[LPYP]', '', $my_message_new);
            $my_message_new = str_replace('[LPYR]', '', $my_message_new);
            $my_message_new = str_replace('[LPYHR]', '', $my_message_new);
            $my_message_new = str_replace('[LPYG]', '', $my_message_new);
            $my_message_new = str_replace('[LPYD]', '', $my_message_new);
            $my_message_new = str_replace('[UC]', '', $my_message_new);
        }
        $system_object->send_email($my_subject_new, $my_message_new, $sendto, $client_name, $mail_sender);
    }
    
    $message_success = "You have successfully sent the email test.";
}

if($get_params['x'] == 'test') {    
    $campaign_test = true;
} elseif($get_params['x'] == 'send' && $selected_campaign_email['status'] == '2' && $selected_campaign_email['send_status'] == '2') {
    $campaign_send = true;
} else {
    redirect_to("campaign_email_view.php"); // URL tampered
}

?>
<!DOCTYPE html>
<html lang="en">
    <head>
        <base target="_self">
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>Instaforex Nigeria | Admin - Campaign Broadcast</title>
        <meta name="title" content="Instaforex Nigeria | Admin - Compose Email" />
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
                            <h4><strong>CAMPAIGN BROADCAST</strong></h4>
                        </div>
                    </div>
                    
                    <div class="section-tint super-shadow">
                        <div class="row">
                            <div class="col-sm-12">
                                <?php require_once 'layouts/feedback_message.php'; ?>
                                <p><a href="campaign_email_view.php" class="btn btn-default" title="Manage Email Campaigns"><i class="fa fa-arrow-circle-left"></i> Manage Email Campaigns</a></p>
                                <p><strong>Campaign Subject:</strong></p>
                                <p><?php if(isset($selected_campaign_email['subject'])) { echo $selected_campaign_email['subject']; } ?></p>
                                <p><strong>Campaign Content:</strong></p>
                                <?php if(isset($selected_campaign_email['content'])) { echo $selected_campaign_email['content']; } ?>
                                
                                <form data-toggle="validator" class="form-horizontal" enctype="multipart/form-data" role="form" method="post" action="<?php echo $_SERVER['REQUEST_URI']; ?>">
                                    <input type="hidden" name="campaign_email_no" value="<?php if(isset($selected_campaign_email['campaign_email_id'])) { echo $selected_campaign_email['campaign_email_id']; } ?>" />
                                    <hr /><br />
                                    <?php if($campaign_test) { ?>
                                    <div class="form-group">
                                        <div class="col-sm-12"><label class="control-label" for="test_email">Test Email:</label></div>
                                        <div class="col-sm-12">
                                            <textarea name="test_email" id="test_email" rows="3" class="form-control" required></textarea>
                                            <span class="help-block">Separate multiple email with comma</span>
                                        </div>
                                    </div>
                                    <?php } ?>
                                    
                                    <div class="form-group">
                                        <div class="col-sm-12">
                                            <?php if($campaign_test) { ?>
                                            <button type="button" data-target="#send-test-confirm" data-toggle="modal" class="btn btn-success"><i class="fa fa-paper-plane fa-fw"></i> Send Test</button>
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
                                                    <h4 class="modal-title">Send Test Email Confirmation</h4></div>
                                                <div class="modal-body">Do you want to send test email now?</div>
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
                                                    <h4 class="modal-title">Send Email Campaign Confirmation</h4></div>
                                                <div class="modal-body">Do you want to send email campaign now? This cannot be reversed, email will be sent to associated client group.</div>
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