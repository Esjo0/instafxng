<?php
require_once("../init/initialize_admin.php");
if (!$session_admin->is_logged_in()) {
    redirect_to("login.php");
}

$query = "SELECT * FROM forum_registrations";
$result = $db_handle->runQuery($query);
$selected = $db_handle->fetchAssoc($result);

foreach ($selected AS $row) {
    extract($row);

    $query = "SELECT email FROM forum_participant WHERE email = '$email' LIMIT 1";
    $result = $db_handle->runQuery($query);

    if($db_handle->numOfRows($result) > 0) {
        continue;
    } else {

        $full_name = ucwords(strtolower(trim($full_name)));
        $full_name = explode(" ", $full_name);

        if(count($full_name) == 3) {
            $last_name = $full_name[0];
            if(strlen($full_name[2]) < 3) {
                $middle_name = $full_name[2];
                $first_name = $full_name[1];
            } else {
                $middle_name = $full_name[1];
                $first_name = $full_name[2];
            }
        } else {
            $last_name = $full_name[0];
            $middle_name = "";
            $first_name = $full_name[1];
        }


        if($venue == 'Diamond Estate') { $cvenue = '1'; } else { $cvenue = '2'; }

        $query = "INSERT INTO forum_participant (first_name, middle_name, last_name, email, phone, venue, forum_activate)
          VALUES ('$first_name', '$middle_name', '$last_name', '$email', '$phone', '$cvenue', '2')";

        $db_handle->runQuery($query);

    }

}

//$date_start = '2017-05-01';
//$date_end = '2017-05-31';

//$query = "SELECT COUNT(ifx_acct_no) AS total_account
//        FROM user_ifxaccount AS ui
//        INNER JOIN user AS u ON ui.user_code = u.user_code
//        INNER JOIN free_training_campaign AS ftc ON ftc.email = u.email
//        WHERE ftc.attendant = '2' AND (STR_TO_DATE(ui.created, '%Y-%m-%d') BETWEEN '$date_start' AND '$date_end')";
//
//$fetched_data = $db_handle->fetchAssoc($db_handle->runQuery($query));
//$ifx_account_count = $fetched_data[0]['total_account'];
//echo $ifx_account_count;

//$date_start = '2017-05-01';
//$date_end = '2017-05-31';
//
//$query = "SELECT SUM(ud.real_dollar_equivalent) AS total
//    FROM user_deposit AS ud
//    INNER JOIN user_ifxaccount AS ui ON ud.ifxaccount_id = ui.ifxaccount_id
//    INNER JOIN user AS u ON ui.user_code = u.user_code
//    INNER JOIN free_training_campaign AS ftc ON ftc.email = u.email
//    WHERE ud.status = '8' AND ftc.attendant = '2' AND (STR_TO_DATE(ud.created, '%Y-%m-%d') BETWEEN '$date_start' AND '$date_end')
//    ";
//$result = $db_handle->runQuery($query);
//$total_result = $db_handle->fetchAssoc($result);

//var_dump($total_result);

//$query = "SELECT ui.user_code
//    FROM user_deposit AS ud
//    INNER JOIN user_ifxaccount AS ui ON ud.ifxaccount_id = ui.ifxaccount_id
//    INNER JOIN user AS u ON ui.user_code = u.user_code
//    INNER JOIN free_training_campaign AS ftc ON ftc.email = u.email
//    WHERE ud.status = '8' AND ftc.attendant = '2' AND (STR_TO_DATE(ud.created, '%Y-%m-%d') BETWEEN '$date_start' AND '$date_end')
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