<?php
require_once("../init/initialize_admin.php");
if (!$session_admin->is_logged_in()) {
    redirect_to("login.php");
}

/*
 * Period: One year ago
 *
 * Tier 0: No funding
 * Tier 1: $1 - $150
 * Tier 2: $151 - $500
 * Tier 3: $501 - $2000
 * Tier 4: $2001 and above
 */

//$date_start = "2017-07-07";
//$date_end = "2018-07-06";
//
//$query1 = "SELECT ud.trans_id
//    FROM user_deposit AS ud
//    INNER JOIN user_ifxaccount AS ui ON ud.ifxaccount_id = ui.ifxaccount_id
//    WHERE MAX(ud.dollar_ordered) BETWEEN 1 AND 150 AND ud.status = '8' AND (STR_TO_DATE(ud.created, '%Y-%m-%d') BETWEEN '$date_start' AND '$date_end') GROUP BY ui.user_code";
//
//$tier1 = $db_handle->numRows($query1);
//
//$query2 = "SELECT ud.trans_id
//    FROM user_deposit AS ud
//    INNER JOIN user_ifxaccount AS ui ON ud.ifxaccount_id = ui.ifxaccount_id
//    WHERE MAX(ud.dollar_ordered) BETWEEN 151 AND 500 AND ud.status = '8' AND (STR_TO_DATE(ud.created, '%Y-%m-%d') BETWEEN '$date_start' AND '$date_end') GROUP BY ui.user_code";
//
//$tier2 = $db_handle->numRows($query2);
//
//$query3 = "SELECT ud.trans_id
//    FROM user_deposit AS ud
//    INNER JOIN user_ifxaccount AS ui ON ud.ifxaccount_id = ui.ifxaccount_id
//    WHERE MAX(ud.dollar_ordered)BETWEEN 501 AND 2000 AND ud.status = '8' AND (STR_TO_DATE(ud.created, '%Y-%m-%d') BETWEEN '$date_start' AND '$date_end') GROUP BY ui.user_code";
//
//$tier3 = $db_handle->numRows($query3);
//
//$query4 = "SELECT ud.trans_id
//    FROM user_deposit AS ud
//    INNER JOIN user_ifxaccount AS ui ON ud.ifxaccount_id = ui.ifxaccount_id
//    WHERE MAX(ud.dollar_ordered) >= 2001 AND ud.status = '8' AND (STR_TO_DATE(ud.created, '%Y-%m-%d') BETWEEN '$date_start' AND '$date_end') GROUP BY ui.user_code";
//
//$tier4 = $db_handle->numRows($query4);
//
//echo "Tier 1: " . $tier1 . "<br />";
//echo "Tier 2: " . $tier2 . "<br />";
//echo "Tier 3: " . $tier3 . "<br />";
//echo "Tier 4: " . $tier4 . "<br />";


//$user_code = "";
//$client_operation = new clientOperation();
//$user_detail = $client_operation->get_user_by_user_code($user_code);
//$client_credential = $client_operation->get_user_credential($user_code);
//$client_address = $client_operation->get_user_address_by_code($user_code);
//
//$passport = $client_credential['passport'];
//$signature = $client_credential['signature'];
//$date = datetime_to_text($client_credential['updated']);
//$client_name = $user_detail['last_name'] . ' ' . $user_detail['middle_name'] . ' ' . $user_detail['first_name'];
//$full_address = $client_address['address'] . ' ' . $client_address['address2'] . ' ' . $client_address['city'] . ' ' . $client_address['state'];
//
//$message_final = <<<MAIL
//<div>
//    <div style="max-width: 80%; margin: 0 auto; padding: 10px; font-size: 14px; font-family: Verdana;">
//        <p><strong>Client Declaration</strong></p>
//        <hr />
//        <div style="background-color: #FFFFFF; padding: 15px; margin: 5px 0 5px 0;">
//            <p><img src="https://instafxng.com/userfiles/$passport" width="186" height="191"></p>
//            <p>
//            I, <strong>$client_name</strong> of <strong>$full_address</strong> declare that all the information I have submitted to Instant Web-Net Technologies Limited are genuine and true.
//            <p> I declare that I shall only use any information including bank account belonging to Instant Web-Net Technologies Limited which may come into my possession through the use of this website (www.instafxng.com) for legitimate business activities including making deposits and withdrawal to and from my Instaforex account.</p>
//            <p> I further declare that I shall not use the Website or any other information belonging to Instant Web-Net Technologies Limited for any fraudulent or malicious purposes.
//                I willingly make this declaration on <strong>$date</strong>.
//            </p>
//            <p><img src="https://instafxng.com/userfiles/$signature" width="146" height="91"></p>
//            <p>Signature</p>
//        </div>
//        <hr />
//        <div style="background-color: #EBDEE9;">
//            <div style="font-size: 11px !important; padding: 15px; text-align: center">
//                <p>Instant Web-Net Technologies Ltd</p>
//            </div>
//        </div>
//    </div>
//</div>
//MAIL;
//
//$mpdf = new \Mpdf\Mpdf();
//$mpdf->WriteHTML($message_final);
//$mpdf->Output();

// LOYALTY POINT ARCHIVING
//$start_date = "2019-01-01";
//$end_date = "2019-01-31";
//
//$query = "SELECT pr.month_rank, u.user_code, u.first_name AS full_name
//      FROM point_ranking AS pr
//      INNER JOIN user AS u ON pr.user_code = u.user_code
//      ORDER BY pr.month_rank DESC, full_name ASC LIMIT 20";
//
//$result = $db_handle->runQuery($query);
//$selected_loyalty = $db_handle->fetchAssoc($result);
//
//$count = 1;
//if(isset($selected_loyalty) && !empty($selected_loyalty)) {
//    foreach ($selected_loyalty as $row) {
//
//        $position = $count;
//        $points = $row['month_rank'];
//        $user_code = $row['user_code'];
//
//        $query = "INSERT INTO point_ranking_log (user_code, position, point_earned, type, start_date, end_date)
//            VALUES ('$user_code', $position, $points, '1', '$start_date', '$end_date')";
//
//        $db_handle->runQuery($query);
//
//        $count++;
//    }
//}
////END LOYALTY POINT ARCHIVING////

//$query = "SELECT user_code FROM user_loyalty_log";
//$result = $db_handle->runQuery($query);
//$selected = $db_handle->fetchAssoc($result);
//
//foreach ($selected AS $row) {
//    extract($row);
//
//    // Make an insert
//    $total_point_earned = $obj_loyalty_point->user_total_point_earned($user_code);
//    $total_point_claimed = $obj_loyalty_point->user_total_point_claimed($user_code);
//
//    $query = "UPDATE user_loyalty_log SET total_point_earned = $total_point_earned,
//        total_point_claimed = $total_point_claimed WHERE user_code = '$user_code' LIMIT 1";
//    $result = $db_handle->runQuery($query);
//}

/**
 * // This process runs at 12:01am
 * 1. Calculate point lagged
 *
 * // This process runs at 11:59pm, runs when total points claimed change, total point earned
 * 1. Take expired points, total points lagged, total points claimed,
 * total points earned from user_loyalty_log table
 * 2. Calculate spent points
 * 3. Calculate expired points
 * 4. Calculate point balance
 * 5. Store point balance
 * 6. Store expired points
 *
 */

////////////////////////////////////////////////////////////

//$query = "SELECT * FROM forum_registrations";
//$result = $db_handle->runQuery($query);
//$selected = $db_handle->fetchAssoc($result);
//
//foreach ($selected AS $row) {
//    extract($row);
//
//    $query = "SELECT email FROM forum_participant WHERE email = '$email' LIMIT 1";
//    $result = $db_handle->runQuery($query);
//
//    if($db_handle->numOfRows($result) > 0) {
//        continue;
//    } else {
//
//        $full_name = ucwords(strtolower(trim($full_name)));
//        $full_name = explode(" ", $full_name);
//
//        if(count($full_name) == 3) {
//            $last_name = $full_name[0];
//            if(strlen($full_name[2]) < 3) {
//                $middle_name = $full_name[2];
//                $first_name = $full_name[1];
//            } else {
//                $middle_name = $full_name[1];
//                $first_name = $full_name[2];
//            }
//        } else {
//            $last_name = $full_name[0];
//            $middle_name = "";
//            $first_name = $full_name[1];
//        }
//
//
//        if($venue == 'Diamond Estate') { $cvenue = '1'; } else { $cvenue = '2'; }
//
//        $query = "INSERT INTO forum_participant (first_name, middle_name, last_name, email, phone, venue, forum_activate)
//          VALUES ('$first_name', '$middle_name', '$last_name', '$email', '$phone', '$cvenue', '2')";
//
//        $db_handle->runQuery($query);
//
//    }
//
//}
//
//$date_start = '2017-10-01';
//$date_end = '2017-10-24';
//
//$query = "SELECT COUNT(ifx_acct_no) AS total_account
//        FROM user_ifxaccount AS ui
//        INNER JOIN user AS u ON ui.user_code = u.user_code
//        INNER JOIN free_training_campaign AS ftc ON ftc.email = u.email
//        WHERE ftc.attendant = '1' AND (STR_TO_DATE(ui.created, '%Y-%m-%d') BETWEEN '$date_start' AND '$date_end')";
//
//$fetched_data = $db_handle->fetchAssoc($db_handle->runQuery($query));
//$ifx_account_count = $fetched_data[0]['total_account'];
//echo $ifx_account_count . "<br /><br />";

//$date_start = '2017-10-01';
//$date_end = '2017-10-24';
//
//$query = "SELECT SUM(ud.real_dollar_equivalent) AS total
//    FROM user_deposit AS ud
//    INNER JOIN user_ifxaccount AS ui ON ud.ifxaccount_id = ui.ifxaccount_id
//    INNER JOIN user AS u ON ui.user_code = u.user_code
//    INNER JOIN free_training_campaign AS ftc ON ftc.email = u.email
//    WHERE ud.status = '8' AND ftc.attendant = '1' AND (STR_TO_DATE(ud.created, '%Y-%m-%d') BETWEEN '$date_start' AND '$date_end')
//    ";
//$result = $db_handle->runQuery($query);
//$total_result = $db_handle->fetchAssoc($result);
//
//var_dump($total_result);
//echo "<br /><br />";
//
//$query = "SELECT COUNT(ui.user_code) AS count
//    FROM user_deposit AS ud
//    INNER JOIN user_ifxaccount AS ui ON ud.ifxaccount_id = ui.ifxaccount_id
//    INNER JOIN user AS u ON ui.user_code = u.user_code
//    INNER JOIN free_training_campaign AS ftc ON ftc.email = u.email
//    WHERE ud.status = '8' AND ftc.attendant = '1' AND (STR_TO_DATE(ud.created, '%Y-%m-%d') BETWEEN '$date_start' AND '$date_end')
//    GROUP BY ui.user_code";
//$result = $db_handle->runQuery($query);
//$total_count = $db_handle->fetchAssoc($result);
//
//var_dump($total_count);

/////////////////////////////////////////////////////////////////

//$result = $db_handle->runQuery("SELECT u.phone FROM user AS u INNER JOIN user_meta AS um ON u.user_code = um.user_code LEFT JOIN state AS s ON um.state_id = s.state_id WHERE u.campaign_subscribe = '1' AND um.address LIKE '%Lagos%'");
//$result = $db_handle->runQuery("SELECT u.phone FROM user AS u WHERE u.campaign_subscribe = '1'");
//$numbers = $db_handle->fetchAssoc($result);
//
//foreach($numbers as $row) {
//    echo $row['phone'] . '<br/>';
//}

//$date_start = '2017-03-01';
//$date_end = '2017-03-31';
//
//$query = "SELECT phone FROM free_training_campaign
//      WHERE training_centre = '2' AND created BETWEEN '$date_start' AND '$date_end'";
//$result = $db_handle->runQuery($query);
//$total_result = $db_handle->fetchAssoc($result);
//foreach ($total_result AS $row) {
//    echo $row['phone'] . "<br />";
//}


// Fill the database with dates
///////////////////////////////////////////////////////////
//$date = strtotime("2009-01-01");
//$date_end = strtotime("2025-12-31");
//
//while( $date < $date_end) {
//
//    $log_it = date("Y-m-d", $date);
//    $query = "INSERT INTO log_of_dates (date_of_day) VALUES ('$log_it')";
//    $db_handle->runQuery($query);
//
//    $date = strtotime("+1 day", $date);
//
//}



//$date_start = '2015-08-01';
//$date_end = '2016-07-31';
//
//$query = "SELECT STR_TO_DATE(ud.created, '%Y-%m-%d') AS trans_date, CONCAT(u.last_name, SPACE(1), u.first_name) AS full_name,
//      ud.real_naira_confirmed, ud.naira_service_charge, ud.naira_vat_charge, ud.naira_stamp_duty
//      FROM user_deposit AS ud
//      INNER JOIN user_ifxaccount AS ui ON ud.ifxaccount_id = ui.ifxaccount_id
//      INNER JOIN user AS u ON ui.user_code = u.user_code
//      WHERE ud.status = '8' AND STR_TO_DATE(ud.created, '%Y-%m-%d') BETWEEN '2015-08-01' AND '2016-07-31'
//      ORDER BY ud.created DESC";
//$result = $db_handle->runQuery($query);
//$total_result = $db_handle->fetchAssoc($result);

//////////////////////////////////////////////////////////////////
////////////////////// POINT RANKING ARCHIVE TOOL ///////////////
//$date_start = '2017-02-01';
//$date_end = '2017-02-28';
//
//$query = "SELECT pr.month_rank, u.user_code, u.first_name AS full_name
//      FROM point_ranking AS pr
//      INNER JOIN user AS u ON pr.user_code = u.user_code
//      ORDER BY pr.month_rank DESC, full_name ASC LIMIT 20";
//
//$result = $db_handle->runQuery($query);
//$selected_loyalty = $db_handle->fetchAssoc($result);
//var_dump($query);
//
//$count = 1;
//if(isset($selected_loyalty) && !empty($selected_loyalty)) {
//    foreach ($selected_loyalty as $row) {
//        $rank = $row['month_rank'];
//        $user = $row['user_code'];
//
//        $query = "INSERT INTO point_ranking_log (user_code, position, point_earned, type, start_date, end_date)
//            VALUES ('$user', $count, $rank, '1', '$date_start', '$date_end')";
//
//        $db_handle->runQuery($query);
//        $count++;
//    }
//}

//////////////////////////////////////////////////////////////////////////////

//$query = "SELECT ud.trans_id, ud.exchange_rate, ud.created FROM user_deposit AS ud
//      INNER JOIN user_ifxaccount AS ui ON ud.ifxaccount_id = ui.ifxaccount_id
//      WHERE ui.type = '1' AND ud.created BETWEEN '2016-09-30' AND '2017-02-28' GROUP BY STR_TO_DATE(ud.created, '%Y-%m-%d')
//      ORDER BY ud.created DESC";
//$result = $db_handle->runQuery($query);
//$total_result = $db_handle->fetchAssoc($result);


///////////////////////////////////////////////////////////////////////////
//var_dump($total_result);

//$date = "2017-01-31";
//$query = "SELECT STR_TO_DATE(ud.created, '%Y-%m-%d') AS date_created, ud.trans_id, ud.naira_total_payable FROM user_deposit AS ud WHERE STR_TO_DATE(ud.created, '%Y-%m-%d') > '2017-01-31' AND ud.status = '8' AND ud.client_pay_method = '7'";
//$result = $db_handle->runQuery($query);
//$numbers = $db_handle->fetchAssoc($result);

////////////////////////////////////////////////////////////////////////////////

//$date_start = '2017-01-01';
//$date_end = '2017-01-31';
//
//$query = "SELECT COUNT(ftc.email) FROM free_training_campaign AS ftc
//      WHERE ftc.training_centre = '3' AND ftc.created BETWEEN '$date_start' AND '$date_end'";
//$result = $db_handle->runQuery($query);
//$total_result = $db_handle->fetchAssoc($result);
//var_dump($total_result);

//$query = "SELECT updated, trans_id FROM user_deposit WHERE status = '8'";
//$result = $db_handle->runQuery($query);
//$all_deposits = $db_handle->fetchAssoc($result);
//
//set_time_limit(280000);
//
//foreach ($all_deposits as $row) {
//    extract($row);
//
//    $query = "UPDATE user_deposit SET order_complete_time = updated WHERE status = '8'";
//    $db_handle->runQuery($query);
//}



//// Select all clients
//$month_active_start = "2017-01-01";
//$month_active_end = "2017-01-31";
//$user_code = "BTmantlx9fU";
//
//// Get points in active month - month_current_points
//$query = "SELECT SUM(pbr.point_earned) AS sum_point_earned
//        FROM point_based_reward AS pbr
//        WHERE pbr.user_code = '$user_code' AND pbr.date_earned BETWEEN '$month_active_start' AND '$month_active_end'";
//$result = $db_handle->runQuery($query);
//$month_current_points = $db_handle->fetchAssoc($result);
//$month_current_points = $month_current_points[0]['sum_point_earned'];
//var_dump($month_current_points);
//
//