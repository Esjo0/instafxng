<?php
require_once("../init/initialize_admin.php");
$admin_code = $_SESSION['admin_unique_code'];
$type = $_POST['type'];
if ($type === '00')
{
    $dismiss_notification = $obj_push_notification->dismiss_all_notifications($admin_code);
}
if ($type === '0')
{
    $notification_id = $_POST['id'];
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
            <div class="alert <?php if($row['status'] == "0"){echo 'alert-success';}else{echo 'alert-info';}?>">
                <button type="button" onclick="push_notifications.show_alert('<?php echo $row['title'] ?>', '<?php echo datetime_to_text($row['created']) ?>', '<?php echo $row['author'] ?>', '<?php echo $row['message'] ?>', '<?php echo $row['notification_id']; ?>')" class="btn btn-xs"><i class="glyphicon glyphicon-expand"></i></button>
                <a href="javascript:void(0);" onclick="push_notifications.dismiss_notification('<?php echo $row['notification_id']; ?>')" class="close"  aria-label="close">&times;</a>
                <hr/>
                <strong><?php echo $row['title'] ?></strong>
                <p style="font-size: x-small !important;" class="text-sm-left text-muted"><?php echo datetime_to_text($row['created']) ?></p>
            </div>
            <?php if($row['status'] == "0"){ $update = $obj_push_notification->update_notification_as_old($row['notification_id']); } ?>
            <?php
        }
    }
}

if ($type == '2')
{
    $notifications = $obj_push_notification->get_notifications();
    foreach ($notifications as $row)
    {
        $recipients = explode("," ,$row['recipients']);
        if (in_array($admin_code, $recipients, true))
        {
            ?>
            <!--Modal - confirmation boxes-->
            <div id="view_<?php echo $row['notification_id'] ?>" tabindex="-1" role="dialog" aria-hidden="true" class="modal fade">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <div class="row">
                                <div class="col-sm-12">
                                    <div class="col-sm-8">
                                        <h4 class="modal-title"><?php echo $row['title'] ?></h4>
                                    <p style="font-size: small !important;" class="text-sm-left text-muted"><?php echo datetime_to_text2($row['created']) ?></p></div>
                                    <div class="col-sm-4">
                                        <a href="<?php echo $row['source_url'] ?>" title="View Source"  class="btn btn-sm btn-default"><i class="glyphicon glyphicon-home"></i></a>
                                        <button data-dismiss="modal" aria-hidden="true" title="Dismiss Notification" onclick="push_notifications.dismiss_notification('<?php echo $row['notification_id'] ?>');" type="button"  class="btn btn-sm btn-default"><i class="glyphicon glyphicon-ban-circle"></i></button>
                                        <button title="Close" onClick="window.close();" type="button" data-dismiss="modal" aria-hidden="true" class="btn btn-sm btn-default"><i class="glyphicon glyphicon-remove"></i></button>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="modal-body">
                            <p><b>Message Title: </b> <?php echo $row['title'] ?></p>
                            <p><b>Author: </b> <?php echo $row['author'] ?></p>
                            <p class="text-justify"><?php echo $row['message'] ?></p>
                        </div>
                    </div>
                </div>
            </div>
            <?php
        }
    }
}
?>
