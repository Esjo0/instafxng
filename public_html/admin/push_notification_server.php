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
            <div onclick="$('#view_<?php echo $row['notification_id'] ?>').modal('show');" class="alert <?php if($row['status'] == "0"){echo 'alert-success';}else{echo 'alert-info';}?>">
                <!--<a href="#" onclick="dismiss_notification('<?php /*echo $row['notification_id']; */?>')" class="close" data-dismiss="alert" aria-label="close">&times;</a>-->
                <strong><?php echo $row['title'] ?></strong>
                <p style="font-size: x-small !important;" class="text-sm-left text-muted"><?php echo datetime_to_text2($row['created']) ?></p>
                <?php if($row['status'] == "0"){ $update = $obj_push_notification->update_notification_as_old($row['notification_id']); var_dump($update);} ?>
            </div>
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
                                        <button title="View Source" type="button" class="btn btn-sm btn-default"><i class="glyphicon glyphicon-home"></i></button>
                                        <button title="Dismis Notification" type="button"  class="btn btn-sm btn-default"><i class="glyphicon glyphicon-ban-circle"></i></button>
                                        <button title="Close" type="button" data-dismiss="modal" aria-hidden="true" class="btn btn-sm btn-default"><i class="glyphicon glyphicon-remove"></i></button>
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

