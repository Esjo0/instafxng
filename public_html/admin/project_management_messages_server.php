<?php
require_once("../init/initialize_admin.php");
if (!$session_admin->is_logged_in())
{
    redirect_to("login.php");
}
$author_code = $_SESSION['admin_unique_code'];
$message = htmlspecialchars_decode(stripslashes(trim($_POST['message'])));
$project_code = $db_handle->sanitizePost($_POST['project_code']);
$query = "INSERT INTO project_management_messages (author_code, project_code, message) VALUES ('$author_code', '$project_code', '$message')";
$db_handle->runQuery($query);

$project_details = $obj_project_management->get_project_details_for_push_notification($project_code);

$title = "New Project Message";
$message1 = "Project Title - ".$project_details['project_title']."<br/>Message: $message";
$recipients = $project_details['recipients'];
$author = $admin_object->get_admin_name_by_code($author_code);
$source_url = "https://instafxng.com/admin/project_management_project_view.php?x=".encrypt($project_code);
$notify_support = $obj_push_notification->add_new_notification($title, $message1, $recipients, $author, $source_url);

$created = date('y-m-d');
$obj_project_management->project_messages_email_notification($project_details['project_title'], $message, $created, $recipients, $author_code);




