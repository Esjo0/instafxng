<?php
set_include_path('/home/tboy9/public_html/init/');
require_once 'initialize_general.php';
//require_once '../app_assets/initialize_general.php';

// Those that did not show interest for the training
$query = "SELECT * FROM free_training_campaign WHERE training_interest = '1'";
$result = $db_handle->runQuery($query);
$fetched_data = $db_handle->fetchAssoc($result);

$today = time();

$day_difference = $today - $created;

foreach ($fetched_data as $row) {
    $client_name = ucwords(strtolower(trim($row['first_name'])));
    $client_email = strtolower(trim($row['email']));
    $client_email_encrypted = encrypt($client_email);
    $created = strtotime($row['created']);
    
    $day_diff = $today - $created;
    $day_due = floor($day_diff / (60 * 60 * 24));
    
    $query = "SELECT subject, content FROM campaign_email_solo WHERE solo_group = '1' AND day_to_send = $day_due AND status = '2' LIMIT 1";
    $result = $db_handle->runQuery($query);
    $selected_campaign_email = $db_handle->fetchAssoc($result);
    $selected_campaign_email = $selected_campaign_email[0];
    
    if($selected_campaign_email) {
        $my_subject = trim($selected_campaign_email['subject']);
        $my_message = stripslashes($selected_campaign_email['content']);
        
        // Replace [NAME] with clients full name
        $my_message_new = str_replace('[NAME]', $client_name, $my_message);
        $my_message_new = str_replace('[EMAIL]', $client_email_encrypted, $my_message_new);
        $my_subject_new = str_replace('[NAME]', $client_name, $my_subject);

        $system_object->send_email($my_subject_new, $my_message_new, $client_email, $client_name, "Bunmi - InstaFxNg");
        
    } else {
        continue;
    }
}



// Those that shown interest for the training
$query = "SELECT * FROM free_training_campaign WHERE training_interest = '2'";
$result = $db_handle->runQuery($query);
$fetched_data = $db_handle->fetchAssoc($result);

$today = time();

$day_difference = $today - $created;

foreach ($fetched_data as $row) {
    $client_name = ucwords(strtolower(trim($row['first_name'])));
    $client_email = strtolower(trim($row['email']));
    $client_email_encrypted = encrypt($client_email);
    $created = strtotime($row['created']);

    $day_diff = $today - $created;
    $day_due = floor($day_diff / (60 * 60 * 24));

    $query = "SELECT subject, content FROM campaign_email_solo WHERE solo_group = '2' AND day_to_send = $day_due AND status = '2' LIMIT 1";
    $result = $db_handle->runQuery($query);
    $selected_campaign_email = $db_handle->fetchAssoc($result);
    $selected_campaign_email = $selected_campaign_email[0];

    if($selected_campaign_email) {
        $my_subject = trim($selected_campaign_email['subject']);
        $my_message = stripslashes($selected_campaign_email['content']);

        // Replace [NAME] with clients full name
        $my_message_new = str_replace('[NAME]', $client_name, $my_message);
        $my_message_new = str_replace('[EMAIL]', $client_email_encrypted, $my_message_new);
        $my_subject_new = str_replace('[NAME]', $client_name, $my_subject);

        $system_object->send_email($my_subject_new, $my_message_new, $client_email, $client_name, "Bunmi - InstaFxNg");

    } else {
        continue;
    }
}