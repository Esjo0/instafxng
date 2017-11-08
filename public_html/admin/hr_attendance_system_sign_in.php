<?php
require_once("../init/initialize_admin.php");
if (!$session_admin->is_logged_in())
{
    redirect_to("login.php");
}
$admin_code = $_SESSION['admin_unique_code'];

$date = $db_handle->sanitizePost($_POST['date']);
$time = $db_handle->sanitizePost($_POST['time']);
$location = $db_handle->sanitizePost($_POST['location']);

$query = "SELECT * FROM hr_attendance_log WHERE hr_attendance_log.admin_code = '$admin_code' AND hr_attendance_log.date = '$date' ";

$result = $db_handle->numRows($query);
if($result == "0")
{
    $query = "INSERT INTO hr_attendance_log (admin_code, date, time, location) VALUES ('$admin_code', '$date', '$time', '$location')";
    $result = $db_handle->runQuery($query);
    if($result)
    {
        $output = "You have signed in successfully.\nOffice Location: ".office_location($location)." \nDate: ".date_to_text($date)." \nTime: ".date('h:i A', mktime($time));
        echo $output;
    }
}
else
{
}

?>
