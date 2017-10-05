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

