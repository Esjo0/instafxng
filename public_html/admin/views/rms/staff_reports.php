<?php
$pending_reports = $obj_rms->get_pending_reports($_SESSION['admin_unique_code']);
$numrows = count($pending_reports);
$rowsperpage = 10;
$totalpages = ceil($numrows / $rowsperpage);
// get the current page or set a default
if (isset($_GET['pg']) && is_numeric($_GET['pg'])) {$currentpage = (int) $_GET['pg'];} else {$currentpage = 1;}
if ($currentpage > $totalpages) { $currentpage = $totalpages; }
if ($currentpage < 1) { $currentpage = 1; }
$prespagelow = $currentpage * $rowsperpage - $rowsperpage + 1;
$prespagehigh = $currentpage * $rowsperpage;
if($prespagehigh > $numrows) { $prespagehigh = $numrows; }
$offset = ($currentpage - 1) * $rowsperpage;
array_sort_by_column_desc($pending_reports, 'created');
if(!empty($pending_reports)) {$pending_reports = paginate_array($offset,$pending_reports, $rowsperpage);}
?>
<h4><strong>STAFF REPORTS</strong></h4>
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
    <?php if(isset($pending_reports) && !empty($pending_reports)) { foreach ($pending_reports as $row) { ?>
        <tr>
            <td><b><?php echo $admin_object->get_admin_name_by_code($row['admin_code'])?></b></td>
            <td>
                <?php $window_period = explode('*', $row['window_period']); ?>
                <b><?php echo $window_period[0]; ?>  <i class='glyphicon glyphicon-arrow-right'></i>  <?php echo $window_period[1]; ?></b>
            </td>
            <td>
                <?php
                echo $obj_rms->get_report_type($row['target_id'], $row['report_id']);
                $target_details = $obj_rms->get_target_by_id($row['target_id']);
                ?>
                <div id="tdetails_<?php echo $row['report_id']?>" tabindex="-1" role="dialog" aria-hidden="true" class="modal fade">
                    <div style="width: 90vw !important;" class="modal-dialog modal-lg">
                        <div class="modal-content">
                            <form data-toggle="validator" role="form" method="post" action="">
                                <div class="modal-header">
                                    <button type="button" data-dismiss="modal" aria-hidden="true"  class="close">&times;</button>
                                    <h4 class="modal-title">Target Report</h4>
                                </div>
                                <div class="modal-body">
                                    <div class="row">
                                        <div class="col-sm-12">
                                            <p><b>Target Author:</b> <?php echo $admin_object->get_admin_name_by_code($target_details['admin_code']) ;?></p>
                                            <p><b>Created:</b> <?php echo datetime_to_text($target_details['created']) ;?></p>
                                            <p><b>Target Title:</b> <?php echo $target_details['title'];?></p>
                                            <p><b>Target Description:</b> <?php echo $target_details['description'];?></p>
                                        </div>
                                    </div>
                                </div>
                                <div class="modal-footer">
                                    <button type="button" name="close" onClick="window.close();" data-dismiss="modal" class="btn btn-sm btn-danger">Close!</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </td>
            <td><?php echo datetime_to_text($row['created'])?></td>
            <td>
                <center><button data-target="#rdetails_<?php echo $row['report_id']?>" data-toggle="modal" class="btn btn_sm btn-info">Read</button></center>
                <div id="rdetails_<?php echo $row['report_id']?>" tabindex="-1" role="dialog" aria-hidden="true" class="modal fade">
                    <div style="width: 90vw !important;" class="modal-dialog modal-lg">
                        <div class="modal-content">
                            <form data-toggle="validator" role="form" method="post" action="">
                                <div class="modal-header">
                                    <button type="button" data-dismiss="modal" aria-hidden="true"  class="close">&times;</button>
                                    <h4 class="modal-title">Target Report</h4>
                                </div>
                                <div class="modal-body">
                                    <div class="row">
                                        <div class="col-sm-7">
                                            <?php $rpt_id = $row['report_id']; ?>
                                            <?php $id['report_id'] = $row['report_id']; ?>
                                            <?php include 'views/rms/read_report.php'?>
                                        </div>
                                        <div class="col-sm-5">
                                            <p class="text-center"><b>COMMENTS</b></p>
                                            <div style="word-break:break-all; max-height: 550px; overflow-y: scroll; overflow-x: hidden">
                                                <?php
                                                $latest_comments = $obj_rms->get_report_comment($rpt_id);
                                                if(isset($latest_comments) && !empty($latest_comments)) {
                                                    foreach ($latest_comments as $row1) {
                                                        ?>
                                                        <div class="row">
                                                            <div class="col-sm-12">
                                                                <div class="transaction-remarks">
                                                                    <span id="trans_remark_author"><?php echo $admin_object->get_admin_name_by_code($row1['admin_code']); ?></span>
                                                                    <span id="trans_remark"><?php echo $row1['comment'];?></span>
                                                                    <span id="trans_remark_date"><?php echo datetime_to_text($row1['created']); ?></span>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    <?php } } else { ?>
                                                    <div class="row">
                                                        <div class="col-sm-12">
                                                            <div class="transaction-remarks">
                                                                <span class="text-danger"><em>There is no remark to display.</em></span>
                                                            </div>
                                                        </div>
                                                    </div>
                                                <?php } ?>
                                            </div
                                            <form  data-toggle="validator" role="form" method="post" action="">
                                                <input type="hidden" class="form-control" id="report_id" name="report_id" value="<?php echo $rpt_id; ?>">
                                                <div class="form-group">
                                                    <div>
                                                        <textarea placeholder="Your Remark" rows="3" name="comment" type="text" id="comment" class="form-control" required></textarea>
                                                    </div>
                                                </div>
                                                <div class="form-group">
                                                    <button type="submit" name="post_comment" class="btn btn-sm btn-success">Post Remark</button>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                                <div class="modal-footer">
                                    <button type="button" name="close" onClick="window.close();" data-dismiss="modal" class="btn btn-sm btn-danger">Close!</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </td>
        </tr>
    <?php } } else { echo "<tr><td colspan='4' class='text-danger'><em>No results to display</em></td></tr>"; } ?>
    </tbody>
</table>
<?php if(isset($pending_reports) && !empty($pending_reports)) { ?>
    <div class="tool-footer text-right">
        <p class="pull-left">Showing <?php echo $prespagelow . " to " . $prespagehigh . " of " . $numrows; ?> entries</p>
    </div>
<?php } ?>
<?php if(isset($pending_reports) && !empty($pending_reports)) { require_once 'layouts/pagination_links.php'; } ?>
