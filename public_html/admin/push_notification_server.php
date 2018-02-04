<?php
require_once("../init/initialize_admin.php");
if (!$session_admin->is_logged_in())
{
    redirect_to("login.php");
}
$admin_code = $_SESSION['admin_unique_code'];
$type = $_POST['type'];
if ($type == '0')
{
    $notification_id = $_POST['notification_id'];
    $dismiss_notification = $obj_push_notification->dismiss_notification($admin_code, $notification_id);
}
if ($type == '1')
{
$notifications = $obj_push_notification->get_notifications();
    foreach ($notifications as $row)
    {
        $recipients = explode("," ,$row['recipients']);
        if (in_array($admin_code, $recipients, true))
        {
            ?>
            <li onclick="$('#view_<?php echo $row['notification_id'] ?>').modal('show');" class="alert <?php if($row['status'] == "0"){echo 'alert-success';}else{echo 'alert-info';}?>">
                <a href="#" onclick="dismiss_notification('<?php echo $row['notification_id']; ?>')" class="close" data-dismiss="alert" aria-label="close">&times;</a>
                <strong><?php echo $row['title'] ?></strong>
                <p style="font-size: x-small !important;" class="text-sm-left text-muted"><?php echo datetime_to_text2($row['created']) ?></p>
            </li>
            <?php
            $update = $obj_push_notification->update_notification_as_old($row['notification_id']);
        }
    }
}
?>

