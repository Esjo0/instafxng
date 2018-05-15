<?php
if(isset($_POST['post_comment']))
{
    $comment = $db_handle->sanitizePost(trim($_POST['comment']));
    $report_id = $db_handle->sanitizePost(trim($_POST['report_id']));
    $new_comment = $obj_rms->set_report_comment($report_id, $comment, $_SESSION['admin_unique_code']);
    $new_comment ? $message_success = "New comment added" : $message_error = "Operation failed";
}
$pending_reports = $obj_rms->get_pending_reports($_SESSION['admin_unique_code'])
?>

<table class="table table-responsive table-striped table-bordered table-hover">
    <thead>
    <tr>
        <th>Author</th>
        <th>Report</th>
        <th>Type</th>
        <th>Created</th>
        <th></th>
    </tr>
    </thead>
    <tbody>
    <?php if(isset($pending_reports) && !empty($pending_reports)) { foreach ($pending_reports as $row) {?>
        <tr>
            <td><a href="javascript:void(0);"><b><?php echo $admin_object->get_admin_name_by_code($row['admin_code'])?></b></a></td>
            <td>
                <?php $window_period = explode('*', $row['window_period']); ?>
                <b><?php echo $window_period[0]; ?>  <i class='glyphicon glyphicon-arrow-right'></i>  <?php echo $window_period[1]; ?></b>
            </td>
            <td><?php echo $obj_rms->get_report_type($row['target_id']);?></td>
            <td><?php echo datetime_to_text($row['created'])?></td>
            <td><center><a class="btn btn_sm btn-info" href="rms_read_report.php?r_id=<?php echo $row['report_id']?>">Read</a></center></td>
        </tr>
    <?php } } else { echo "<tr><td colspan='4' class='text-danger'><em>No results to display</em></td></tr>"; } ?>
    </tbody>
</table>
