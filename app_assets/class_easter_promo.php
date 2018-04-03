<?php

class Easter_Promo
{
    const entry_cost = 50; //$50  per entry
    const deadline = '2018-04-01 00:00:00'; //Promo closes
    public function new_entry($transaction_id, $acc_no, $points)
    {
        global $db_handle;
        if($this->confirm_deadline(date('Y-m-d h:i:s'), self::deadline)) { return false; }

        $query = "UPDATE easter_promo_2018 (transaction_id, ifx_acc_no, points) VALUES('$transaction_id', '$acc_no', '$points') ";
        $db_handle->runQuery($query);
        return $db_handle->affectedRows() > 0 ? true : false;

    }

    public function confirm_deadline($today, $deadline)
    {
        if($today >= $deadline){  return true;}
        else { return false; }
    }

    public function get_all_entries($from_date, $to_date)
    {
        global $db_handle;
        $query = "SELECT * FROM easter_promo_2018 
                  WHERE (STR_TO_DATE(created, '%Y-%m-%d') 
                  BETWEEN '$from_date' AND '$to_date')
                  AND completed IS NOT NULL   ";
        $result = $db_handle->runQuery($query);
        return $db_handle->fetchAssoc($result);
    }

    public function get_top_entries($from_date, $to_date, $number_value)
    {
        global $db_handle;

        ////Get unique participants
        $participants = array();
        $participants_count = 0;
        $p_query = "SELECT ifx_acc_no FROM easter_promo_2018 WHERE ((STR_TO_DATE(created, '%Y-%m-%d') BETWEEN '$from_date' AND '$to_date') AND completed IS NOT NULL ";
        $participant_query = $db_handle->fetchAssoc($db_handle->runQuery($p_query));
        foreach ($participant_query as $row)
        {
            if(!array_search($row['ifx_acc_no'], $participants))
            {
                $participants[$participants_count] = $row['ifx_acc_no'];
                $participants_count++;
            }
        }


        $query = "SELECT * FROM easter_promo_2018 
                  WHERE ((STR_TO_DATE(created, '%Y-%m-%d') BETWEEN '$from_date' AND '$to_date') AND completed IS NOT NULL )
                  ORDER BY points DESC LIMIT $number_value 
                  GROUP BY ifx_acc_no ";
        $result = $db_handle->runQuery($query);
        return $db_handle->fetchAssoc($result);
    }

    public function get_winner($from_date, $to_date)
    {
        global $db_handle;
        $query = "SELECT * FROM easter_promo_2018 
                  WHERE ((STR_TO_DATE(created, '%Y-%m-%d') BETWEEN '$from_date' AND '$to_date') AND completed IS NOT NULL )
                  ORDER BY points DESC LIMIT 1  ";
        $result = $db_handle->runQuery($query);
        return $db_handle->fetchAssoc($result);
    }
}

$obj_easter_promo = new Easter_Promo();