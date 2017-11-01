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

$message_main = '<p style="font-size: small">Project Title: '.$project_details['project_title'] ."</p>";
$message_main .= '<span style="font-size: small" id="trans_remark_author">'.$admin_object->get_admin_name_by_code($author_code) ."</span>";
$message_main .= '<span style="font-size: small" id="trans_remark">'.$message."</span>";
$recipients = $project_details['recipients'];
$type = '1';

$obj_push_notification->add_new_notification($message_main, $recipients, $type);
//var_dump($new_notification);

$created = date('y-m-d h-i-sa');
$obj_project_management->project_messages_email_notification($project_details['project_title'], $message, $created, $recipients, $author_code);




