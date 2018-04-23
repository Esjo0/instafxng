<?php

class Campaign_Management
{
    public function add_campaign($title, $desc, $admin_code)
    {
        global $db_handle;
        $query = "INSERT INTO campaign_management_campaigns (title, description, admin_code) VALUE ('$title', '$desc', '$admin_code')";
        $result = $db_handle->runQuery($query);
        if($result){return true;}
        else{return false;}
    }

    public function add_channel($title, $desc, $admin_code, $campaign_id)
    {
        global $db_handle;
        $query = "INSERT INTO campaign_management_campaign_channels (title, description, admin_code, campaign_id) VALUE ('$title', '$desc', '$admin_code', '$campaign_id')";
        $result = $db_handle->runQuery($query);
        if($result){return true;}
        else{return false;}
    }

    public function get_channels($campaign_id)
    {
        global $db_handle;
        $query = "SELECT * FROM campaign_management_campaign_channels WHERE campaign_id = $campaign_id ORDER BY created DESC ";
        $result = $db_handle->runQuery($query);
        $result = $db_handle->fetchAssoc($result);
        if($result){return $result;}
        else{return false;}
    }

    public function channel_status($status)
    {
        switch ($status)
        {
            case '0': $message = "Inactive"; break;
            case '1': $message = "Active"; break;
            default: $message = "Inactive"; break;
        }
        return $message;
    }

}
$obj_campaign_management = new Campaign_Management();