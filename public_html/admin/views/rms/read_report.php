<?php
$report_details = $obj_rms->get_report_by_id($id['report_id']);
if(isset($_POST['flag']))
{
    $flag = $obj_rms->flag($_POST['report_id'], $_SESSION['admin_unique_code']);
    $flag ? $message_success = "Operation successful." : $message_error = "Operation failed.";
}
$flag_status = $obj_rms->flag($_POST['report_id'], $_SESSION['admin_unique_code'], 1);
?>
<!--<form data-toggle="validator" class="form-horizontal" role="form" method="post" action="">
<a href="<?php /*echo $_SERVER['HTTP_REFERER']; */?>" class="btn btn-sm btn-default pull-left" title="Back"><i class="fa fa-arrow-circle-left"></i> Back</a>
    <input value="<?php /*echo $report_details['report_id']; */?>" name="report_id" type="hidden"/>
    <button name="flag" type="submit" class="btn btn-sm btn-success pull-right" title="<?php /*echo $flag_status['title'] */?>"><?php /*echo $flag_status['text'] */?></button>
</form>
<br/><br/>-->
<p><b>Author: </b><?php echo $admin_object->get_admin_name_by_code($report_details['admin_code']); ?></p>
<p><b>Created: </b><?php echo datetime_to_text($report_details['created']); ?></p>
<p><b>Period: </b><?php $window_period = explode('*', $report_details['window_period']); ?>
    <?php echo $window_period[0]; ?>  <i class='glyphicon glyphicon-arrow-right'></i>  <?php echo $window_period[1]; ?>
</p>
<p><b>Reviewed: </b>
    <?php
    $reviewed = explode(',', $report_details['created']);
    foreach ($reviewed as $key) {
        echo $admin_object->get_admin_name_by_code($key)."<br/>";
    }
    ?>
</p>
<p><b>Attached Files: </b>
    <span>
        <?php
        $attachments = $obj_rms->get_report_attachments($report_details['report_id']);
        if(isset($attachments) && !empty($attachments)):
            foreach ($attachments as $row3) {?>
                <a href="<?php echo $row3['url']; ?>" download="<?php echo $row3['name']; ?>"><?php echo $row3['name']; ?></a><br/>
            <?php } endif; ?>
    </span>
</p>
<p><b>Report: </b><?php echo $report_details['report'];?></p>