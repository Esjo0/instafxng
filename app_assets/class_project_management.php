<?php

class Project_Management
{
    public function get_project_comments($project_code)
    {
        global $db_handle;
        $query = "SELECT 
                  project_management_project_comments.comment AS comment,
                  project_management_project_comments.created AS created, 
                  CONCAT(admin.first_name, SPACE(1), admin.last_name) AS author_name
                  FROM project_management_project_comments, admin 
                  WHERE 
                  project_management_project_comments.project_code = '$project_code'
                  AND project_management_project_comments.author_code = admin.admin_code";

        $result = $db_handle->runQuery($query);
        $result = $db_handle->fetchAssoc($result);
        return $result;
    }

    public function get_project_messages($project_code)
    {
        global $db_handle;
        $query = "SELECT * FROM project_management_messages WHERE project_code = '$project_code' ORDER BY created ASC ";
        //var_dump($query);
        $result = $db_handle->runQuery($query);
        $result = $db_handle->fetchAssoc($result);
        return $result;
    }

    public function add_new_reminder($admin_code, $description, $effect_date)
    {
        global $db_handle;
        $query = "INSERT INTO project_management_reminders (admin_code, description, effect_date) VALUES ('$admin_code','$description','$effect_date')";
        $db_handle->runQuery($query);
        return $db_handle->affectedRows() > 0 ? true : false;
    }

    public function submit_report($author_code, $report, $project_code, $supervisor_code)
    {
        global $db_handle;
        $report_code = $this->generate_code();
        $query = "INSERT INTO project_management_reports (report_code, project_code,  author_code, supervisor_code, report) 
                  VALUES('$report_code', '$project_code',  '$author_code', '$supervisor_code', '$report')";
        $db_handle->runQuery($query);
        return $db_handle->affectedRows() > 0 ? true : false;
    }

    public function create_new_project($title, $description, $deadline, $all_allowed_admin, $admin_code)
    {
        global $db_handle;
        $project_code = $this->generate_code();
        $query = "INSERT INTO project_management_projects (title, description, deadline, executors, supervisor_code, project_code) VALUES ('$title','$description','$deadline','$all_allowed_admin','$admin_code','$project_code')";
        //var_dump($query);
        $db_handle->runQuery($query);
        return $db_handle->affectedRows() > 0 ? true : false;
    }

    public function update_project($title, $description, $deadline, $all_allowed_admin, $author_code, $project_code)
    {
        global $db_handle;
        $query = "UPDATE project_management_projects SET title = '$title', description = '$description', deadline = '$deadline',  supervisor_code = '$author_code', executors = '$all_allowed_admin' WHERE project_code = '$project_code' LIMIT 1";
        $db_handle->runQuery($query);
        return $db_handle->affectedRows() > 0 ? true : false;
    }

    public function create_new_task($project_code, $title, $description, $time_span, $status, $author_code, $excecutors)
    {
        global $db_handle;
        $task_code = $this->generate_code();
        $query = "INSERT INTO project_management_tasks (title, description, time_span, status, author_code, project_code, task_code, excecutors) VALUES ('$title','$description','$time_span','$status','$author_code','$project_code','$task_code','$excecutors')";
        $db_handle->runQuery($query);
        return $db_handle->affectedRows() > 0 ? true : false;
    }

    public function generate_code($length = 6)
    {
        $str = "";
        $characters = array_merge(range('a','z'), range('0','9'));
        $max = count($characters) - 1;
        for ($i = 0; $i < $length; $i++)
        {
            $rand = mt_rand(0, $max);
            $str .= $characters[$rand];
        }
        return $str;
    }

    public function get_pending_reminders() {
        global $db_handle;
        $query = "SELECT * FROM project_management_reminders WHERE status = 'ON'";
        $result = $db_handle->runQuery($query);
        $all_open_reminders = $db_handle->fetchAssoc($result);
        return $all_open_reminders;
    }

    public function get_expired_reminders() {
        global $db_handle;
        $query = "SELECT * FROM project_management_reminders WHERE status = 'OFF'";
        $result = $db_handle->runQuery($query);
        $all_closed_reminders = $db_handle->fetchAssoc($result);
        return $all_closed_reminders;
    }

    public function get_all_reminders() {
        global $db_handle;
        $query = "SELECT * FROM project_management_reminders";
        $result = $db_handle->runQuery($query);
        $all_reminders = $db_handle->fetchAssoc($result);
        return $all_reminders;
    }

    public function delete_reminder($reminder_id)
    {
        global $db_handle;
        $query = "DELETE FROM project_management_reminders WHERE project_management_reminders.reminder_id = '$reminder_id'";
        $db_handle->runQuery($query);
        return $db_handle->affectedRows() > 0 ? true : false;
    }

    public function update_reminders($reminder_id, $description, $effect_date) {
        global $db_handle;
        $query = "UPDATE project_management_reminders SET  description = '$description', effect_date = '$effect_date' WHERE reminder_id = '".$reminder_id."' LIMIT 1";
        //var_dump($query);
        $db_handle->runQuery($query);
        return $db_handle->affectedRows() > 0 ? true : false;
    }

    public function treated_reminders($reminder_id)
    {
        global $db_handle;
        $db_handle->runQuery("UPDATE project_management_reminders SET status = 'OFF' WHERE reminder_id = '".$reminder_id."';");
        return $db_handle->affectedRows() > 0 ? true : false;
    }

}

$obj_project_management = new Project_Management();