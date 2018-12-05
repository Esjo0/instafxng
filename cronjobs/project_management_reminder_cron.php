<?php
set_include_path('/home/tboy9/public_html/init/');
require_once 'initialize_general.php';

$current_date = date("Y-m-d");

//Get pending reminders
$pending_reminders = $obj_project_management->get_pending_reminders();

if($pending_reminders)
{
    foreach ($pending_reminders as $row)
    {
        if($row['effect_date'] == $current_date && $row['status'] == 'ON')
        {
            $destination_details = $admin_object->get_admin_detail_by_code($row['admin_code']);
            //var_dump($pending_reminders);
            $admin_name = $destination_details['first_name']." ".$destination_details['last_name'];
            $admin_email = $destination_details['email'];
            $subject = "Reminder";

            $message = "Dear $admin_name,<br/>
            You added a reminder on ".datetime_to_text($row['created'])." . Below is the description of this reminder.<br/>
           
            <strong>Description:</strong><br/>
            ".$row['description']."<br/><br/>";

            $system_object->send_email($subject, $message, $admin_email, $admin_name);
            $obj_project_management->treated_reminders($row['reminder_id']);
        }

    }
}

if($db_handle) { $db_handle->closeDB(); mysqli_close($db_handle); }