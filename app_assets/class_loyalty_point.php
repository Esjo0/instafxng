<?php

class loyaltyPoint
{


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

        $date_start = "2016-10-16";
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

}

$obj_loyalty_point = new loyaltyPoint();