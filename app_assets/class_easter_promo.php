<?php

class Easter_Promo
{
    const entry_cost = '50'; //$50  per entry
    const deadline = '2018-04-01 00:00:00'; //Promo closes
    public function new_entry($transaction_id, $acc_no, $points)
    {
        global $db_handle;
        if($this->confirm_deadline(date('Y-m-d h:i:s'), self::deadline))
        {
            $query = "INSERT INTO easter_promo_2018 (transaction_id, ifx_acc_no, points) VALUES('$transaction_id', '$acc_no', '$points') ";
            $db_handle->runQuery($query);
        }
    }

    public function confirm_deadline($today, $deadline)
    {
        if($today >= $deadline){ return false; }
        else { return true; }
    }

    public function get_all_entries($from_date, $to_date)
    {
        global $db_handle;
        $query = "INSERT INTO easter_promo_2018 (entry_id, ifx_acc_no, points) VALUES('$transaction_id', '$acc_no', '$points') ";
        $result = $db_handle->runQuery($query);
    }

    public function get_top20_entries($from_date, $to_date)
    {
        global $db_handle;
        if($this->confirm_deadline(date('Y-m-d h:i:s'), self::deadline))
        {
            $query = "INSERT INTO easter_promo_2018 (entry_id, ifx_acc_no, points) VALUES('$transaction_id', '$acc_no', '$points') ";
            $result = $db_handle->runQuery($query);
        }
    }

    public function get_winner($from_date, $to_date)
    {
        global $db_handle;
        if($this->confirm_deadline(date('Y-m-d h:i:s'), self::deadline))
        {
            $query = "INSERT INTO easter_promo_2018 (entry_id, ifx_acc_no, points) VALUES('$transaction_id', '$acc_no', '$points') ";
            $result = $db_handle->runQuery($query);
        }
    }
}

$obj_easter_promo = new Easter_Promo();