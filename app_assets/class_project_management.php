<?php

class Project_Management
{
    public function get_project_details_for_push_notification($project_code)
    {
        global $db_handle;
        $query = "SELECT 
                  project_management_projects.title AS project_title,
                  CONCAT(project_management_projects.supervisor_code,',', project_management_projects.executors) AS recipients
                  FROM project_management_projects
                  WHERE 
                  project_management_projects.project_code = '$project_code' ";

        $result = $db_handle->runQuery($query);
        $result = $db_handle->fetchAssoc($result);
        return $result[0];
    }

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
        $db_handle->runQuery($query);
        $this->notify_supervisor($title, $description, $deadline, $all_allowed_admin, $admin_code);
        $this->notify_executors($title, $description, $deadline, $all_allowed_admin, $admin_code);
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
        $query = "UPDATE project_management_reminders SET  description = '$description', effect_date = '$effect_date' WHERE reminder_id = '$reminder_id' LIMIT 1";
        $db_handle->runQuery($query);
        return $db_handle->affectedRows() > 0 ? true : false;
    }

    public function treated_reminders($reminder_id)
    {
        global $db_handle;
        $db_handle->runQuery("UPDATE project_management_reminders SET status = 'OFF' WHERE reminder_id = '".$reminder_id."';");
        return $db_handle->affectedRows() > 0 ? true : false;
    }

    public function notify_supervisor($title, $description, $deadline, $all_allowed_admin, $admin_code)
    {
        $admin_object = new AdminUser();
        $destination_details = $admin_object->get_admin_detail_by_code($admin_code);
        $admin_name = $destination_details['first_name'];
        $admin_email = $destination_details['email'];
        $subject = 'NEW PROJECT ASSIGNMENT';
        $message = <<<MAIL
                    <div style="background-color: #F3F1F2">
                        <div style="max-width: 80%; margin: 0 auto; padding: 10px; font-size: 14px; font-family: Verdana;">
                            <img src="https://instafxng.com/images/ifxlogo.png" />
                            <hr />
                            <div style="background-color: #FFFFFF; padding: 15px; margin: 5px 0 5px 0;">
                                <p>Dear $admin_name,</p>
                                <p>You have created a new project.</p>
                                <p><b>PROJECT TITLE: </b>$title</p>
                                <p><b>PROJECT DESCRIPTION: </b><br/>$description</p>
                                <p><b>PROJECT DEADLINE: </b>$deadline<br/></p>
                                <p>You will be undertaking these project along with<br/>
MAIL;
        $all_allowed_admin = explode("," ,$all_allowed_admin);
        for ($i = 0; $i < count($all_allowed_admin); $i++)
        {
            $message .= $admin_object->get_admin_name_by_code($all_allowed_admin[$i])."<br/>";
        }
        $message .= <<<MAIL
                                <p><a href="https://instafxng.com/admin/">Login to your Admin Cabinet for for more information.</a></p>
                                <br /><br />
                                <p>Best Regards,</p>
                                <p>Instafxng Support,<br />
                                   www.instafxng.com</p>
                                <br /><br />
                            </div>
                            <hr />
                            <div style="background-color: #EBDEE9;">
                                <div style="font-size: 11px !important; padding: 15px;">
                                    <p style="text-align: center"><span style="font-size: 12px"><strong>We"re Social</strong></span><br /><br />
                                        <a href="https://facebook.com/InstaForexNigeria"><img src="https://instafxng.com/images/Facebook.png"></a>
                                        <a href="https://twitter.com/instafxng"><img src="https://instafxng.com/images/Twitter.png"></a>
                                        <a href="https://www.instagram.com/instafxng/"><img src="https://instafxng.com/images/instagram.png"></a>
                                        <a href="https://www.youtube.com/channel/UC0Z9AISy_aMMa3OJjgX6SXw"><img src="https://instafxng.com/images/Youtube.png"></a>
                                        <a href="https://linkedin.com/company/instaforex-ng"><img src="https://instafxng.com/images/LinkedIn.png"></a>
                                    </p>
                                    <p><strong>Head Office Address:</strong> TBS Place, Block 1A, Plot 8, Diamond Estate, Estate Bus-Stop, LASU/Isheri road, Isheri Olofin, Lagos.</p>
                                    <p><strong>Lekki Office Address:</strong> Road 5, Suite K137, Ikota Shopping Complex, Lekki/Ajah Express Road, Lagos State</p>
                                    <p><strong>Office Number:</strong> 08028281192</p>
                                    <br />
                                </div>
                                <div style="font-size: 10px !important; padding: 15px; text-align: center;">
                                    <p>This email was sent to you by Instant Web-Net Technologies Limited, the
                                        official Nigerian Representative of Instaforex, operator and administrator
                                        of the website www.instafxng.com</p>
                                    <p>To ensure you continue to receive special offers and updates from us,
                                        please add support@instafxng.com to your address book.</p>
                                </div>
                            </div>
                        </div>
                    </div>
MAIL;
        //echo $message;
        $system_object = new InstafxngSystem();
        $system_object->send_email($subject, $message, $admin_email, $admin_name);
        //echo $message;
    }

    public function notify_executors($title, $description, $deadline, $all_allowed_admin, $admin_code)
    {
        $admin_object = new AdminUser();
        $system_object = new InstafxngSystem();
        $supervisor = $admin_object->get_admin_name_by_code($admin_code);
        $all_allowed_admin = explode("," ,$all_allowed_admin);
        for ($i = 0; $i < count($all_allowed_admin); $i++)
        {
            $destination_details = $admin_object->get_admin_detail_by_code($all_allowed_admin[$i]);
            $admin_name = $destination_details['first_name'];
            $admin_email = $destination_details['email'];
            $subject = 'NEW PROJECT ASSIGNMENT';
            $message = <<<MAIL
                    <div style="background-color: #F3F1F2">
                        <div style="max-width: 80%; margin: 0 auto; padding: 10px; font-size: 14px; font-family: Verdana;">
                            <img src="https://instafxng.com/images/ifxlogo.png" />
                            <hr />
                            <div style="background-color: #FFFFFF; padding: 15px; margin: 5px 0 5px 0;">
                                <p>Dear $admin_name,</p>
                                <p>You have been assigned a new project.</p>
                                <p><b>PROJECT TITLE: </b>$title</p>
                                <p><b>PROJECT DESCRIPTION: </b><br/>$description</p>
                                <p><b>PROJECT DEADLINE: </b>$deadline<br/></p>
                                <p>You will be undertaking these project along with<br/>
MAIL;
            $all_allowed_admin = explode("," ,$all_allowed_admin);
            for ($i = 0; $i < count($all_allowed_admin); $i++)
            {
                $message .= $admin_object->get_admin_name_by_code($all_allowed_admin[$i])."<br/>";
            }
            $message .= <<<MAIL
                                <p>On this project, you will be reporting to $supervisor.<p>
                                <p><a href="https://instafxng.com/admin/">Login to your Admin Cabinet for for more information.</a></p>
                                <br /><br />
                                <p>Best Regards,</p>
                                <p>Instafxng Support,<br />
                                   www.instafxng.com</p>
                                <br /><br />
                            </div>
                            <hr />
                            <div style="background-color: #EBDEE9;">
                                <div style="font-size: 11px !important; padding: 15px;">
                                    <p style="text-align: center"><span style="font-size: 12px"><strong>We"re Social</strong></span><br /><br />
                                        <a href="https://facebook.com/InstaForexNigeria"><img src="https://instafxng.com/images/Facebook.png"></a>
                                        <a href="https://twitter.com/instafxng"><img src="https://instafxng.com/images/Twitter.png"></a>
                                        <a href="https://www.instagram.com/instafxng/"><img src="https://instafxng.com/images/instagram.png"></a>
                                        <a href="https://www.youtube.com/channel/UC0Z9AISy_aMMa3OJjgX6SXw"><img src="https://instafxng.com/images/Youtube.png"></a>
                                        <a href="https://linkedin.com/company/instaforex-ng"><img src="https://instafxng.com/images/LinkedIn.png"></a>
                                    </p>
                                    <p><strong>Head Office Address:</strong> TBS Place, Block 1A, Plot 8, Diamond Estate, Estate Bus-Stop, LASU/Isheri road, Isheri Olofin, Lagos.</p>
                                    <p><strong>Lekki Office Address:</strong> Road 5, Suite K137, Ikota Shopping Complex, Lekki/Ajah Express Road, Lagos State</p>
                                    <p><strong>Office Number:</strong> 08028281192</p>
                                    <br />
                                </div>
                                <div style="font-size: 10px !important; padding: 15px; text-align: center;">
                                    <p>This email was sent to you by Instant Web-Net Technologies Limited, the
                                        official Nigerian Representative of Instaforex, operator and administrator
                                        of the website www.instafxng.com</p>
                                    <p>To ensure you continue to receive special offers and updates from us,
                                        please add support@instafxng.com to your address book.</p>
                                </div>
                            </div>
                        </div>
                    </div>
MAIL;
            //echo $message;
            $system_object->send_email($subject, $message, $admin_email, $admin_name);
            //echo $message;
        }
    }

    public function project_messages_email_notification($title, $message, $created, $recipients, $admin_code)
    {
        $admin_object = new AdminUser();
        $system_object = new InstafxngSystem();

        $author = $admin_object->get_admin_name_by_code($admin_code);
        $created = datetime_to_text($created);

        $recipients = explode("," ,$recipients);
        for ($i = 0; $i < count($recipients); $i++)
        {
            $destination_details = $admin_object->get_admin_detail_by_code($recipients[$i]);
            $admin_name = $destination_details['first_name'];
            $admin_email = $destination_details['email'];
            $subject = 'NEW PROJECT MESSAGE';
            $message = <<<MAIL
                    <div style="background-color: #F3F1F2">
                        <div style="max-width: 80%; margin: 0 auto; padding: 10px; font-size: 14px; font-family: Verdana;">
                            <img src="https://instafxng.com/images/ifxlogo.png" />
                            <hr />
                            <div style="background-color: #FFFFFF; padding: 15px; margin: 5px 0 5px 0;">
                                <p>Dear $admin_name,</p>
                                <p>$author left a new project message.</p>
                                <p><b>PROJECT TITLE: </b>$title</p>
                                <p><b>PROJECT MESSAGE: </b><br/>$message</p>
                                <p><b>DATE AND TIME: </b>$created</p>                                
                                <p><a href="https://instafxng.com/admin/">Login to your Admin Cabinet for for more information.</a></p>
                                <br /><br />
                                <p>Best Regards,</p>
                                <p>Instafxng Support,<br />
                                   www.instafxng.com</p>
                                <br /><br />
                            </div>
                            <hr />
                            <div style="background-color: #EBDEE9;">
                                <div style="font-size: 11px !important; padding: 15px;">
                                    <p style="text-align: center"><span style="font-size: 12px"><strong>We"re Social</strong></span><br /><br />
                                        <a href="https://facebook.com/InstaForexNigeria"><img src="https://instafxng.com/images/Facebook.png"></a>
                                        <a href="https://twitter.com/instafxng"><img src="https://instafxng.com/images/Twitter.png"></a>
                                        <a href="https://www.instagram.com/instafxng/"><img src="https://instafxng.com/images/instagram.png"></a>
                                        <a href="https://www.youtube.com/channel/UC0Z9AISy_aMMa3OJjgX6SXw"><img src="https://instafxng.com/images/Youtube.png"></a>
                                        <a href="https://linkedin.com/company/instaforex-ng"><img src="https://instafxng.com/images/LinkedIn.png"></a>
                                    </p>
                                    <p><strong>Head Office Address:</strong> TBS Place, Block 1A, Plot 8, Diamond Estate, Estate Bus-Stop, LASU/Isheri road, Isheri Olofin, Lagos.</p>
                                    <p><strong>Lekki Office Address:</strong> Road 5, Suite K137, Ikota Shopping Complex, Lekki/Ajah Express Road, Lagos State</p>
                                    <p><strong>Office Number:</strong> 08028281192</p>
                                    <br />
                                </div>
                                <div style="font-size: 10px !important; padding: 15px; text-align: center;">
                                    <p>This email was sent to you by Instant Web-Net Technologies Limited, the
                                        official Nigerian Representative of Instaforex, operator and administrator
                                        of the website www.instafxng.com</p>
                                    <p>To ensure you continue to receive special offers and updates from us,
                                        please add support@instafxng.com to your address book.</p>
                                </div>
                            </div>
                        </div>
                    </div>
MAIL;
            //echo $message;
            $system_object->send_email($subject, $message, $admin_email, $admin_name);
            //echo $message;
        }
    }

}

$obj_project_management = new Project_Management();