<?php
    require_once("../init/initialize_admin.php");
    $reminder_id = $_GET['data'];
    var_dump($reminder_id);
    $query = "UPDATE reminder 
                   SET reminder.status = 'OFF'
                      WHERE reminder.reminder_id = '".$reminder_id."';";
    $result = $db_handle->runQuery($query);
    redirect_to("reminder_manage.php");
?>