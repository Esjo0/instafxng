<?php

class Reporting_System
{

    public function set_reviewers($admin_code, $reviewers)
    {
        global $db_handle;
        $query = "SELECT * FROM rms_review_settings WHERE admin_code = '$admin_code' ";
        if($db_handle->numRows($query) < 1)
        {
            $query = "INSERT INTO rms_review_settings (admin_code, reviewers) VALUES ('$admin_code', '$reviewers')";
        }
        else
        {
            $query = "UPDATE rms_review_settings SET reviewers = '$reviewers' WHERE admin_code = '$admin_code' ";
        }
        $db_handle->runQuery($query);
        return $db_handle->affectedRows() > 0 ? true : false;
    }

    public function get_reviewers($admin_code)
    {
        global $db_handle;
        $query = "SELECT reviewers FROM rms_review_settings WHERE admin_code = '$admin_code' ";
        $result = $db_handle->runQuery($query);
        $reviewers = explode(',', $db_handle->fetchAssoc($result)[0]['reviewers']);
        return $reviewers;
    }

    public function get_reportees($session_admin_code)
    {
        global $db_handle;
        $query = "SELECT * FROM rms_review_settings ";
        $result = $db_handle->runQuery($query);
        $_x = $db_handle->fetchAssoc($result);
        $reportees = array();
        foreach ($_x as $row)
        {
            $_reviwers = explode(',', $row['reviewers']);
            if(in_array($session_admin_code, $_reviwers)){ $reportees[] = $row['admin_code']; }
        }
        return $reportees;
    }

    public function get_created_targets($admin_code)
    {
        global $db_handle;
        $query = "SELECT * FROM rms_targets WHERE admin_code = '$admin_code' ";
        $result = $db_handle->runQuery($query);
        return $db_handle->fetchAssoc($result);
    }

    public function set_target($title, $description, $window_period, $admin_code, $reportees)
    {
        global $db_handle;
        $query = "INSERT INTO rms_targets (title, description, window_period, admin_code, reportees) VALUES ('$title', '$description', '$window_period', '$admin_code', '$reportees')";
        $db_handle->runQuery($query);
        return $db_handle->affectedRows() > 0 ? true : false;
    }

    public function get_past_reports($admin_code)
    {
        global $db_handle;
        $query = "SELECT * FROM rms_reports WHERE admin_code = '$admin_code' ";
        $result = $db_handle->runQuery($query);
        return $db_handle->fetchAssoc($result);
    }

    public function set_report($window_period, $admin_code, $report, $target_id)
    {
        global $db_handle;
        if(isset($target_id) && !empty($target_id)) {
            $query = "INSERT INTO rms_reports (window_period, admin_code, report, target_id) VALUES ('$window_period', '$admin_code', '$report', '$target_id')";
        }
        else {
            $query = "INSERT INTO rms_reports (window_period, admin_code, report) VALUES ('$window_period', '$admin_code', '$report')";
        }
        $db_handle->runQuery($query);
        return $db_handle->affectedRows() > 0 ? true : false;
    }

    public function get_report_comment($report_id)
    {
        global $db_handle;
        $query = "SELECT comment, created, admin_code FROM rms_report_comments WHERE report_id = $report_id ";
        $result = $db_handle->runQuery($query);
        return $db_handle->fetchAssoc($result);
    }

    public function get_report_by_id($report_id)
    {
        global $db_handle;
        $query = "SELECT * FROM rms_reports WHERE report_id = $report_id ";
        $result = $db_handle->runQuery($query);
        return $db_handle->fetchAssoc($result)[0];
    }

    public function set_report_comment($report_id, $comment, $admin_code)
    {
        global $db_handle;
        $query = "INSERT INTO rms_report_comments (report_id, comment, admin_code) VALUES ($report_id, '$comment', '$admin_code')";
        $db_handle->runQuery($query);
        return $db_handle->affectedRows() > 0 ? true : false;
    }

    public function get_reportees_targets($admin_code)
    {
        global $db_handle;
        $query = "SELECT * FROM rms_targets ";
        $result = $db_handle->runQuery($query);
        $targets = $db_handle->fetchAssoc($result);
        $reportees_targets = array();
        foreach ($targets as $row)
        {
            $target_reportees = explode(',', $row['reportees']);
            if(in_array($admin_code, $target_reportees))
            {
                $reportees_targets[] = $row['target_id'];
            }
        }
        if(isset($reportees_targets) && !empty($reportees_targets)) { return $reportees_targets; }
        else{ return false;}
    }

    public function get_target_by_id($target_id)
    {
        global $db_handle;
        $query = "SELECT * FROM rms_targets WHERE target_id = $target_id ";
        $result = $db_handle->runQuery($query);
        $result = $db_handle->fetchAssoc($result);
        return $result[0];
    }

    public function get_pending_reports($admin_code)
    {
        global $db_handle;
        $pending_reports_id = array();
        $reportees = $this->get_reportees($admin_code);
        foreach ($reportees as $row)
        {
            $query = "SELECT * FROM rms_reports WHERE admin_code = '$row' ";
            $result = $db_handle->runQuery($query);
            $result = $db_handle->fetchAssoc($result);
            foreach ($result as $key)
            {
                $reviewed = explode(',', $key['reviewed']);
                if(!in_array($admin_code, $reviewed))
                {
                    $pending_reports_id[] = $key['report_id'];
                }
            }
        }
        $pending_reports = array();
        foreach ($pending_reports_id as $row)
        {
            $report = $this->get_report_by_id($row);
            $pending_reports[] = $report;
        }
        if(isset($pending_reports)&& !empty($pending_reports)) {return $pending_reports;}
        else{ return false;}

    }

    public function get_report_type($report_id)
    {
        empty($report_id) ? $x = "Routine Report" : $x = "Target Report";
        return $x;
    }
}

$obj_rms = new Reporting_System();