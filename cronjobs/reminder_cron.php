<?php
set_include_path('init/');
require_once 'initialize_general.php';
require_once 'initialize_admin.php';

$current_date = date("Y-m-d");

//Get pending reminders
$pending_reminders = $obj_reminders->get_pending_reminders();

if($pending_reminders)
{
    foreach ($pending_reminders as $row)
    {
        if($row['effect_date'] == $current_date && $row['status'] == 'ON')
        {
            $destination_details = $admin_object->get_admin_detail_by_code($row['admin_code']);
            var_dump($pending_reminders);
            $admin_name = $destination_details['first_name']." ".$destination_details['last_name'];
            $admin_email = $destination_details['email'];
            $subject = "Prospect Reminder";

            $message = "Dear $admin_name,<br/>
            You added a reminder on ".datetime_to_text($row['created'])." . Below is the description of this reminder.<br/>
            <strong>Customer Details:</strong><br/>
            Customer/Client's Full Name: ".$row['full_name']."<br/>
            Customer/Client's Email Address: ".$row['email_address']."<br/>
            Customer/Client's Phone Contact: ".$row['phone']."<br/>
            Customer/Client's Account Number: ".$row['ifx_acc_no']."<br/><br/>
            <strong>Description:</strong><br/>
            ".$row['description']."<br/><br/>";

            $system_object->send_email($subject, $message, $admin_email, $admin_name);
            $obj_reminders->treated_reminder($row['reminder_id']);
        }

    }
}