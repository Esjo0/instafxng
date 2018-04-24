<?php

class Reporting_System
{

    public function review_settings($admin_code, $reviewers)
    {
        global $db_handle;
        $reviewers = implode(",",$reviewers);
        $query = "SELECT * FROM reporting_system_review_settings WHERE admin_code = $admin_code ";
        if($db_handle->numRows($query) < 1)
        {
            $query = "INSERT INTO reporting_system_review_settings (admin_code, reviewers) VALUES ('$admin_code', $reviewers)";
        }
        else
        {
            $query = "UPDATE reporting_system_review_settings SET reviewers = $reviewers WHERE admin_code = $admin_code ";
        }
        $db_handle->runQuery($query);
        return $db_handle->affectedRows() > 0 ? true : false;
    }

    public function get_reviewers($admin_code)
    {
        global $db_handle;
        $query = "SELECT * FROM reporting_system_review_settings WHERE admin_code = $admin_code ";
        $result = $db_handle->runQuery($query);
        return $db_handle->fetchAssoc($result)[0];
    }

    public function get_created_targets($admin_code)
    {
        global $db_handle;
        $query = "SELECT * FROM rms_targets WHERE admin_code = $admin_code ";
        $result = $db_handle->runQuery($query);
        return $db_handle->fetchAssoc($result)[0];
    }

}

$obj_rms = new Reporting_System();