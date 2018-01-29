<?php
require_once("../init/initialize_admin.php");
if (!$session_admin->is_logged_in())
{
    redirect_to("login.php");
}
$author_code = $_SESSION['admin_unique_code'];
$message = $db_handle->sanitizePost($_POST['message']);
$project_code = $db_handle->sanitizePost($_POST['project_code']);
$query = "INSERT INTO project_management_messages (author_code, project_code, message) VALUES ('$author_code', '$project_code', '$message')";
$db_handle->runQuery($query);

$project_details = $obj_project_management->get_project_details_for_push_notification($project_code);
$message_main = '<p style="font-size: small">Project Title: '.$project_details['project_title'] ."<br/>";
$message_main .= 'Author: '.$admin_object->get_admin_name_by_code($author_code) ."<br/>";
$query ="SELECT location FROM accounting_system_office_locations WHERE location_id = '$location' LIMIT 1";
$message_main .= 'Message: '.$message."</p>";
$recipients = $project_details['recipients'];
$type = '1';

$obj_push_notification->add_new_notification($message_main, $recipients, $type);
//var_dump($new_notification);
$created = date('y-m-d');
$obj_project_management->project_messages_email_notification($project_details['project_title'], $message, $created, $recipients, $author_code);




