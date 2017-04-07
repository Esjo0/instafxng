<?php
set_time_limit(0);
set_include_path('/home/tboy9/public_html/init/');
require_once 'initialize_general.php';

// Select all clients
$query = "SELECT user_code FROM user";
$result = $db_handle->runQuery($query);
$all_clients = $db_handle->fetchAssoc($result);

// Select active year loyalty period
$query = "SELECT start_date, end_date FROM point_season WHERE type = '2' AND is_active = '1' LIMIT 1";
$result = $db_handle->runQuery($query);
$year_active = $db_handle->fetchAssoc($result);
$year_active_start = $year_active[0]['start_date'];
$year_active_end = $year_active[0]['end_date'];

// Select active month loyalty period
$query = "SELECT start_date, end_date FROM point_season WHERE type = '1' AND is_active = '1' LIMIT 1";
$result = $db_handle->runQuery($query);
$month_active = $db_handle->fetchAssoc($result);
$month_active_start = $month_active[0]['start_date'];
$month_active_end = $month_active[0]['end_date'];

foreach ($all_clients as $row) {
    $user_code = $row['user_code'];

    // Get points in active year - year_current_points
    $query = "SELECT SUM(pbr.point_earned) AS sum_point_earned
        FROM point_based_reward AS pbr
        WHERE pbr.user_code = '$user_code' AND pbr.date_earned BETWEEN '$year_active_start' AND '$year_active_end'";
    $result = $db_handle->runQuery($query);
    $year_current_points = $db_handle->fetchAssoc($result);
    $year_current_points = $year_current_points[0]['sum_point_earned'];
    if(is_null($year_current_points)) { $year_current_points = 0.00; }

    // Get points before active year - year_archive_points
    $query = "SELECT SUM(pbr.point_earned) AS sum_point_earned
        FROM point_based_reward AS pbr
        WHERE pbr.date_earned < '$year_active_start' AND pbr.user_code = '$user_code'";
    $result = $db_handle->runQuery($query);
    $year_archive_points = $db_handle->fetchAssoc($result);
    $year_archive_points = $year_archive_points[0]['sum_point_earned'];
    if(is_null($year_archive_points)) { $year_archive_points = 0.00; }

    // Get points in active month - month_current_points
    $query = "SELECT SUM(pbr.point_earned) AS sum_point_earned
        FROM point_based_reward AS pbr
        WHERE pbr.user_code = '$user_code' AND pbr.date_earned BETWEEN '$month_active_start' AND '$month_active_end'";
    $result = $db_handle->runQuery($query);
    $month_current_points = $db_handle->fetchAssoc($result);
    $month_current_points = $month_current_points[0]['sum_point_earned'];
    if(is_null($month_current_points)) { $month_current_points = 0.00; }

    // Get points before active month - month_archive_points
    $query = "SELECT SUM(pbr.point_earned) AS sum_point_earned
        FROM point_based_reward AS pbr
        WHERE pbr.date_earned < '$month_active_start' AND pbr.user_code = '$user_code'";
    $result = $db_handle->runQuery($query);
    $month_archive_points = $db_handle->fetchAssoc($result);
    $month_archive_points = $month_archive_points[0]['sum_point_earned'];
    if(is_null($month_archive_points)) { $month_archive_points = 0.00; }

    // Get total claimed points
    $query = "SELECT SUM(pbc.point_claimed) AS total_point_claimed
        FROM point_based_claimed AS pbc
        WHERE pbc.user_code = '$user_code' AND status IN ('1', '2')";
    $result = $db_handle->runQuery($query);
    $selected_data = $db_handle->fetchAssoc($result);
    $total_point_claimed = $selected_data[0]['total_point_claimed'];
    if(is_null($total_point_claimed)) { $total_point_claimed = 0.00; }

    // Calculate Year Rank
    if($total_point_claimed > $year_archive_points) {
        $diff = $total_point_claimed - $year_archive_points;
        $year_rank = $year_current_points - $diff;
    } else {
        $year_rank = $year_current_points;
    }

    // Calculate Month Rank
    if($total_point_claimed > $month_archive_points) {
        $diff = $total_point_claimed - $month_archive_points;
        $month_rank = $month_current_points - $diff;
    } else {
        $month_rank = $month_current_points;
    }

    // Populate Point Ranking Table

    // Check whether this client already exists
    $query = "SELECT user_code FROM point_ranking WHERE user_code = '$user_code' LIMIT 1";

    if($db_handle->numRows($query) == 1) {
        $query = "UPDATE point_ranking SET year_earned = $year_current_points, year_earned_archive = $year_archive_points,
            year_rank = $year_rank, month_earned = $month_current_points, month_earned_archive = $month_archive_points,
            month_rank = $month_rank, point_claimed = $total_point_claimed WHERE user_code = '$user_code' LIMIT 1";
        $db_handle->runQuery($query);
    } else {
        $query = "INSERT INTO point_ranking (user_code, year_earned, year_earned_archive, year_rank, month_earned, month_earned_archive, month_rank, point_claimed)
            VALUES ('$user_code', $year_current_points, $year_archive_points, $year_rank, $month_current_points, $month_archive_points, $month_rank, $total_point_claimed)";
        $db_handle->runQuery($query);
    }

}