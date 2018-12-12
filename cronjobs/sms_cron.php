<?php
set_include_path('/home/tboy9/public_html/init/');
require_once 'initialize_general.php';

$limit = 100; // number of SMS to send per round

// First select just one campaign that is in active mode
$query = "SELECT campaign_track_id, campaign_id, recipient_query, total_recipient, current_offset FROM campaign_sms_track WHERE status = '1' LIMIT 1";
$result = $db_handle->runQuery($query);

if($db_handle->numOfRows($result) > 0) {
    $fetched_data = $db_handle->fetchAssoc($result);
    $fetched_data = $fetched_data[0];

    extract($fetched_data);

    $recipient_query = stripslashes($recipient_query);

    $selected_campaign_sms = $system_object->get_campaign_sms_by_id($campaign_id);

    $my_message = stripslashes($selected_campaign_sms['content']);

    $query = $recipient_query . " LIMIT $limit OFFSET $current_offset";
    $result = $db_handle->runQuery($query);
    $all_selected_members = $db_handle->fetchAssoc($result);

    // update offset before sending email to the selected emails
    // this means no possibility of someone receiving two emails
    $query = "UPDATE campaign_sms_track SET current_offset = current_offset + $limit WHERE campaign_track_id = $campaign_track_id";
    $result = $db_handle->runQuery($query);

    if($result) {
        foreach ($all_selected_members as $row)
        {
            $client_phone = strtolower(trim($row['phone']));
            $user_code = strtolower(trim($row['user_code']));
            $my_message = str_replace('[UC]', dec_enc('encrypt', $user_code), $my_message);
            $my_message_new = str_replace('[UC]', '', $my_message);
            $system_object->send_sms($client_phone, $my_message_new);
        }

        // if the current offset plus limit is equal or greater than total recipient
        // update campaign_email_track to completed
        $total_sent = $current_offset + $limit;

        if($total_sent >= $total_recipient) {
            $query = "UPDATE campaign_sms_track SET status = '2' WHERE campaign_track_id = $campaign_track_id";
            $result = $db_handle->runQuery($query);

            // Update this campaign to completed
            $query = "UPDATE campaign_sms SET status = '6' WHERE campaign_sms_id = $campaign_id";
            $result = $db_handle->runQuery($query);
        }
    }
}

if($db_handle) { $db_handle->closeDB(); mysqli_close($db_handle); }