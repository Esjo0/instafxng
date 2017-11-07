<?php
set_include_path('/home/tboy9/public_html/init/');
require_once 'initialize_general.php';

$days_left_this_month = date('t') - date('j');
$limit = 200; // number of emails to send per round

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

// First select just one campaign that is in active mode
$query = "SELECT campaign_track_id, campaign_id, recipient_query, total_recipient, current_offset FROM campaign_email_track WHERE status = '1' LIMIT 1";
$result = $db_handle->runQuery($query);

if($db_handle->numOfRows($result) > 0) {
    $fetched_data = $db_handle->fetchAssoc($result);
    $fetched_data = $fetched_data[0];

    extract($fetched_data);

    $recipient_query = stripslashes($recipient_query);

    $selected_campaign_email = $system_object->get_campaign_email_by_id($campaign_id);

    $my_subject = trim($selected_campaign_email['subject']);
    $my_message = stripslashes($selected_campaign_email['content']);

    $query = $recipient_query . " ORDER BY created ASC LIMIT $limit OFFSET $current_offset";
    $result = $db_handle->runQuery($query);
    $all_selected_members = $db_handle->fetchAssoc($result);

    // update offset before sending email to the selected emails
    // this means no possibility of someone receiving two emails
    $query = "UPDATE campaign_email_track SET current_offset = current_offset + $limit WHERE campaign_track_id = $campaign_track_id";
    $result = $db_handle->runQuery($query);

    if($result) {
        foreach ($all_selected_members as $row) {
            $client_name = ucwords(strtolower(trim($row['first_name'])));
            $client_email = strtolower(trim($row['email']));

            // Replace [NAME] with clients full name
            $my_message_new = str_replace('[NAME]', $client_name, $my_message);
            $my_subject_new = str_replace('[NAME]', $client_name, $my_subject);

            if(array_key_exists('user_code', $row)) {
                $user_code = $row['user_code'];

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
                $my_message_new = str_replace('[UC]', $user_code, $my_message_new);

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

            $system_object->send_email($my_subject_new, $my_message_new, $client_email, $client_name);
        }

        // if the current offset plus limit is equal or greater than total recipient
        // update campaign_email_track to completed
        $total_sent = $current_offset + $limit;

        if($total_sent >= $total_recipient) {
            $query = "UPDATE campaign_email_track SET status = '2' WHERE campaign_track_id = $campaign_track_id";
            $result = $db_handle->runQuery($query);

            // Update this campaign to completed
            $query = "UPDATE campaign_email SET status = '6' WHERE campaign_email_id = $campaign_id";
            $result = $db_handle->runQuery($query);
        }
    }
}