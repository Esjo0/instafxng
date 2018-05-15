<?php
if(isset($_POST['process']))
{
    $window_period = $db_handle->sanitizePost(trim($_POST['from_date']))."*".$db_handle->sanitizePost(trim($_POST['to_date']));
    $report = $db_handle->sanitizePost(trim($_POST['content']));
    $target_id = $db_handle->sanitizePost(trim($_POST['target_id']));
    $new_report = $obj_rms->set_report($window_period, $_SESSION['admin_unique_code'], $report, $target_id);
    if(count($_FILES['attachments']['name']))
    {
        foreach ($_FILES['attachments']['name'] as $key => $value)
        {
            $tmp_name = $_FILES['attachments']["tmp_name"][$key];
            $name = strtolower($_FILES['attachments']["name"][$key]);
            $dirname = "report_attachments".DIRECTORY_SEPARATOR.$new_report['report_id'];
            if(!is_file("report_attachments")){mkdir("report_attachments");}
            if(!is_file($dirname)){mkdir($dirname);}
            move_uploaded_file($tmp_name, $dirname.DIRECTORY_SEPARATOR.$name);
        }
    }
    $new_report['status'] ? $message_success = "Operation Successful" : $message_error = "Operation Failed";
}
?>