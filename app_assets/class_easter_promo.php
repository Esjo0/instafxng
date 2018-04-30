<?php

class Easter_Promo
{
    const entry_cost = 50; //$50  per entry
    const deadline = '2018-04-06 11:59:00'; //Promo closes
    public function new_entry($transaction_id, $acc_no, $points)
    {
        global $db_handle;
        if($this->confirm_deadline(date('Y-m-d h:i:s'), self::deadline)) { return false; }

        $query = "INSERT IGNORE INTO easter_promo_participants (acc_no) VALUES ('$acc_no')";
        $db_handle->runQuery($query);

        $query = "INSERT INTO easter_promo_entries (transaction_id, points, acc_no) VALUES ('$transaction_id', '$points', '$acc_no')";
        $db_handle->runQuery($query);

        return $db_handle->affectedRows() > 0 ? true : false;

    }

    public function update_entry($transaction_id)
    {
        global $db_handle;
        $query = "UPDATE easter_promo_entries SET completed = now() WHERE transaction_id = '$transaction_id' ";
        $db_handle->runQuery($query);
        return $db_handle->affectedRows() > 0 ? true : false;

    }

    public function confirm_deadline($today, $deadline)
    {
        if($today >= $deadline){  return true;}
        else { return false; }
    }

    //TODO Revisit this logic
    public function get_all_entries($from_date, $to_date)
    {
        global $db_handle;
        $participants_entries = array();
        $count = 0;
        $query = "SELECT * FROM easter_promo_participants  ";
        $result = $db_handle->runQuery($query);
        $participants = $db_handle->fetchAssoc($result);
        foreach ($participants as $row)
        {
            $query = "SELECT SUM(points) AS points FROM easter_promo_entries WHERE ((STR_TO_DATE(created, '%Y-%m-%d') BETWEEN '$from_date' AND '$to_date') AND completed IS NOT NULL) AND acc_no = '".$row['acc_no']."' ";
            $result = $db_handle->runQuery($query);
            $points = $db_handle->fetchAssoc($result)[0]['points'];
            $participants_entries[$count] = array('participant'=>$row['acc_no'], 'points'=>$points);
            $count++;
        }
        return $participants_entries;
    }

    public function get_top_entries($from_date, $to_date, $number_value)
    {
        global $db_handle;
        $counter = 0;
        $participants_entries = array();
        $top_entries = array();
        $count = 0;
        $query = "SELECT * FROM easter_promo_participants  ";
        $result = $db_handle->runQuery($query);
        $participants = $db_handle->fetchAssoc($result);
        foreach ($participants as $row)
        {
            $query = "SELECT SUM(points) AS points FROM easter_promo_entries WHERE ((STR_TO_DATE(created, '%Y-%m-%d') BETWEEN '$from_date' AND '$to_date') AND completed IS NOT NULL) AND acc_no = '".$row['acc_no']."' ";
            $result = $db_handle->runQuery($query);
            $points = $db_handle->fetchAssoc($result)[0]['points'];
            $participants_entries[$count] = array('participant'=>$row['acc_no'], 'points'=>(int)$points);
            $count++;
        }

        $entries = $this->sort_by_points($participants_entries);

        while($counter < $number_value)
        {
            $top_entries[$counter]['participant'] = $entries[$counter]['participant'];
            $top_entries[$counter]['points'] = $entries[$counter]['points'];
            $counter++;
        }
        return $top_entries;
    }

    public function get_winner($from_date, $to_date)
    {
        global $db_handle;
        $participants_entries = array();
        $count = 0;
        $query = "SELECT * FROM easter_promo_participants ";
        $result = $db_handle->runQuery($query);
        $participants = $db_handle->fetchAssoc($result);
        foreach ($participants as $row)
        {
            $query = "SELECT SUM(points) AS points FROM easter_promo_entries WHERE ((STR_TO_DATE(created, '%Y-%m-%d') BETWEEN '$from_date' AND '$to_date') AND completed IS NOT NULL) AND acc_no = '".$row['acc_no']."' ";
            $result = $db_handle->runQuery($query);
            $points = $db_handle->fetchAssoc($result)[0]['points'];
            $participants_entries[$count] = array('participant'=>$row['acc_no'], 'points'=>$points);
            $count++;
        }
        return $this->sort_by_points($participants_entries)[0];
    }

    public function get_points_per_acc($acc_no)
    {
        $from_date = date('Y-m-d');
        $to_date = date('Y-m-d');
        global $db_handle;
        $query = "SELECT *  FROM easter_promo_entries WHERE ((STR_TO_DATE(created, '%Y-%m-%d') BETWEEN '$from_date' AND '$to_date') AND completed IS NOT NULL) AND acc_no = '$acc_no' ";
        $result = $db_handle->runQuery($query);
        $entries = $db_handle->numOfRows($result);

        $query = "SELECT SUM(points) AS points FROM easter_promo_entries WHERE ((STR_TO_DATE(created, '%Y-%m-%d') BETWEEN '$from_date' AND '$to_date') AND completed IS NOT NULL) AND acc_no = '$acc_no' ";
        $result = $db_handle->runQuery($query);
        $points = $db_handle->fetchAssoc($result)[0]['points'];
        $participants_entries = array('entries'=>$entries, 'points'=>$points);
        return $participants_entries;
    }



    public function sort_by_points($users)
    {
        $names = array();

        foreach ($users as $user)
        {
            $names[] = $user['points'];
        }

        array_multisort($names, SORT_DESC, $users);
        return $users;
    }

    public function get_client_by_name($ifx_acct)
    {
        global $db_handle;
        $query = "SELECT first_name, middle_name, last_name, email, phone FROM user, user_ifxaccount WHERE user_ifxaccount.ifx_acct_no = '$ifx_acct' AND user.user_code = user_ifxaccount.user_code ";
        return $db_handle->fetchAssoc($db_handle->runQuery($query))[0];
    }
}

$obj_easter_promo = new Easter_Promo();