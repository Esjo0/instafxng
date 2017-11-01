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
$notifications = $obj_push_notification->get_notifications();
foreach ($notifications as $row)
{
    if($row['notification_type'] == '1'):
        $recipients = explode("," ,$row['recipients']);
        if (in_array($admin_code, $recipients, true)):
            ?>
            <li style="word-break:break-all;">
                <p style="font-size: 15px" class="text-center"><b>NEW PROJECT MESSAGE</b></p>
                <div class="row">
                    <div class="col-sm-12">
                        <div class="transaction-remarks">
                            <?php echo $row['message']; ?>
                            <span style="font-size: small" id="trans_remark_date"><?php echo datetime_to_text($row['created']); ?></span>
                            <center><button type="button" onclick="dismiss_notification('<?php echo $row['notification_id']; ?>')" class="btn btn-sm btn-default">Dismiss</button></center>
                        </div>
                    </div>
                </div>
            </li>
        <?php endif;
    endif;

    if($row['notification_type'] == '2'):
        $recipients = explode("," ,$row['recipients']);
        if (in_array($admin_code, $recipients, true)):
            ?>
            <li style="word-break:break-all;">
                <p style="font-size: 15px" class="text-center"><b>NEW BULLETIN MESSAGE</b></p>
                <div class="row">
                    <div class="col-sm-12">
                        <div class="transaction-remarks">
                            <?php echo $row['message']; ?>
                            <span style="font-size: small" id="trans_remark_date"><?php echo datetime_to_text($row['created']); ?></span>
                            <center><button type="button" onclick="dismiss_notification('<?php echo $row['notification_id']; ?>')" class="btn btn-sm btn-default">Dismiss</button></center>
                        </div>
                    </div>
                </div>
            </li>
        <?php endif;
    endif;
    ?>
<?php } ?>

