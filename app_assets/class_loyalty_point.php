<?php

class loyaltyPoint {


    function user_total_point_earned($user_code) {
        global $db_handle;

        $query = "SELECT SUM(pbr.point_earned) AS total_point_earned
                FROM point_based_reward AS pbr
                WHERE pbr.user_code = '$user_code'";

        $result = $db_handle->runQuery($query);
        $selected_data = $db_handle->fetchAssoc($result);
        $total_point_earned = $selected_data[0]['total_point_earned'];

        return $total_point_earned ? $total_point_earned : 0;
    }

    function user_total_point_earned_lagged($user_code) {
        global $db_handle;

        $date_start = "2016-10-10";
        $time = strtotime("-1 year", time());
        $date_end = date("Y-m-d", $time);

        $query = "SELECT SUM(pbr.point_earned) AS total_point_earned_lagged
                FROM point_based_reward AS pbr
                WHERE pbr.user_code = '$user_code' AND pbr.date_earned BETWEEN '$date_start' AND '$date_end'";

        $result = $db_handle->runQuery($query);
        $selected_data = $db_handle->fetchAssoc($result);
        $total_point_earned_lagged = $selected_data[0]['total_point_earned_lagged'];

        return $total_point_earned_lagged ? $total_point_earned_lagged : 0;

    }

    function user_total_point_claimed($user_code) {
        global $db_handle;

        $query = "SELECT SUM(pbc.point_claimed) AS total_point_claimed
                FROM point_based_claimed AS pbc
                WHERE pbc.user_code = '$user_code' AND status IN ('1', '2')";

        $result = $db_handle->runQuery($query);
        $selected_data = $db_handle->fetchAssoc($result);
        $total_point_claimed = $selected_data[0]['total_point_claimed'];

        return $total_point_claimed ? $total_point_claimed : 0;
    }

    function user_total_point_balance($user_code) {
        global $db_handle;

        $query = "SELECT user_code, total_point_earned_lagged, expired_point
            FROM user_loyalty_log WHERE user_code = '$user_code' LIMIT 1";
        $result = $db_handle->runQuery($query);
        $selected = $db_handle->fetchAssoc($result);

        $total_point_earned_lagged = $selected[0]['total_point_earned_lagged'];
        $expired_point = $selected[0]['expired_point'];

        $total_point_earned = $this->user_total_point_earned($user_code);
        $total_point_claimed = $this->user_total_point_claimed($user_code);

        $spent_point = $total_point_claimed + $expired_point;
        $additional_expired_point = $total_point_earned_lagged - $spent_point;

        if($additional_expired_point < 0) {
            $additional_expired_point = 0; // we do not allow negative
        }

        $point_balance = ($total_point_earned - $spent_point) - $additional_expired_point;

        // Make update to the point balance, total point claimed, total point earned, expired point
        $query = "UPDATE user_loyalty_log AS ull
            INNER JOIN user AS u ON ull.user_code = u.user_code
            SET ull.total_point_earned = $total_point_earned,
            ull.total_point_claimed = $total_point_claimed,
            ull.expired_point = expired_point + $additional_expired_point,
            ull.point_balance = $point_balance,
            u.point_balance = $point_balance
            WHERE ull.user_code = '$user_code'";
        $db_handle->runQuery($query);

        return $db_handle->affectedRows() > 0 ? true : false;
    }

    function point_balance_sum() {
        global $db_handle;

        $query = "SELECT SUM(point_balance) AS total_points FROM user_loyalty_log";
        $result = $db_handle->runQuery($query);
        $selected = $db_handle->fetchAssoc($result);
        $point_balance = $selected[0]['total_points'];

        return $point_balance ? $point_balance : false;
    }

    function total_expired_point() {
        global $db_handle;

        $query = "SELECT SUM(expired_point) AS total_expired_point FROM user_loyalty_log";
        $result = $db_handle->runQuery($query);
        $selected = $db_handle->fetchAssoc($result);
        $total_expired_point = $selected[0]['total_expired_point'];

        return $total_expired_point ? $total_expired_point : false;
    }

    function get_user_point_details($user_code) {
        global $db_handle;

        $query = "SELECT point_balance, expired_point, total_point_earned, total_point_claimed FROM user_loyalty_log WHERE user_code = '$user_code' LIMIT 1";
        $result = $db_handle->runQuery($query);
        $selected = $db_handle->fetchAssoc($result);
        $point_details = $selected[0];

        return $point_details ? $point_details : false;
    }

    function get_general_point_analysis() {
        global $db_handle;

        $query = "SELECT SUM(point_balance) AS total_point_balance, SUM(expired_point) AS total_expired_point,
            SUM(total_point_earned) AS total_point_earned, SUM(total_point_claimed) AS total_point_claimed FROM user_loyalty_log";
        $result = $db_handle->runQuery($query);
        $selected = $db_handle->fetchAssoc($result);
        $point_details = $selected[0];

        return $point_details ? $point_details : false;
    }
}

$obj_loyalty_point = new loyaltyPoint();