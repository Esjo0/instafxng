<?php
//Past Reports////////////////////////////////////////////////////////////
$past_reports = $obj_rms->get_past_reports($_SESSION['admin_unique_code']);
$numrows = count($past_reports);
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
$_past_reports = paginate_array($offset,$past_reports, $rowsperpage);
ksort($_past_reports);
//////////////////////////////////////////////////////////////////////
?>
<div class="row ">
    <div class="col-sm-12">
        <p class="pull-right"><a onclick="document.getElementById('n_r_php').click();" href="javascript:void(0);" class="btn btn-sm btn-default"><i class="glyphicon glyphicon-plus"></i> New Report</a></p>
        <div style="display: none;">
            <form role="form" method="post" action="<?php echo $_SERVER['REQUEST_URI']; ?>">
                <input type="text" name="extra" value=" "/>
                <button name="selection" id="n_r_php" value="n_r_php" type="submit"></button>
            </form>
        </div>
        <p class="pull-left"><a href="javascript:void(0);" data-target="#target_report" data-toggle="modal" class="btn btn-sm btn-default"><i class="glyphicon glyphicon-plus"></i> New Target Report</a></p>
    </div>
</div>
<div id="target_report" tabindex="-1" role="dialog" aria-hidden="true" class="modal fade">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" data-dismiss="modal" aria-hidden="true"  class="close">&times;</button>
                <h4 class="modal-title">New Target Report</h4>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-sm-12">
                        <p>Targets</p>
                        <table class="table table-responsive table-striped table-bordered table-hover">
                            <thead>
                                <tr>
                                    <th>Title</th>
                                    <th>Window Period</th>
                                    <th>Created</th>
                                    <th></th>
                                </tr>
                            </thead>
                            <tbody>
                            <?php if(isset($targets) && !empty($targets)) { foreach ($targets as $row) { $target_details = $obj_rms->get_target_by_id($row); ?>
                                <tr>
                                    <td>
                                        <a id="a_<?php echo $row; ?>" href="javascript:void(0);"><?php echo $target_details['title'];?></a>
                                        <script>
                                            $().ready(function(e) {
                                                $('#a_<?php echo $row; ?>').hunterPopup({
                                                    width: '720px',
                                                    height: '100%',
                                                    title: "Target Details",
                                                    content: $('#content_<?php echo $row; ?>')
                                                });

                                            });
                                        </script>
                                        <div style="display: none" id="content_<?php echo $row; ?>">
                                            <p><?php echo $target_details['description'];?></p>
                                        </div>
                                    </td>
                                    <td><?php $window_period = explode('*', $target_details['window_period']); echo date_to_text($window_period[0])."   <i class='glyphicon glyphicon-arrow-right'></i>   ".date_to_text($window_period[1]) ?></td>
                                    <td><?php echo datetime_to_text($target_details['created'])?></td>
                                    <td>
                                        <a onclick="document.getElementById('n_r_php1').click();" href="javascript:void(0);" title="Create Report for this target." class="btn btn-sm btn-default"><i class="glyphicon glyphicon-pencil"></i></a>
                                        <div style="display: none;">
                                            <form action="" method="post">
                                                <input type="text" name="extra" value="<?php echo '?t_id='.$row; ?>"/>
                                                <button name="selection" id="n_r_php1" value="n_r_php" type="submit"></button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            <?php } } else { echo "<tr><td colspan='3' class='text-danger'><em>No results to display</em></td></tr>"; } ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" name="close" onClick="window.close();" data-dismiss="modal" class="btn btn-sm btn-danger">Close!</button>
            </div>
        </div>
    </div>
</div>


<h4><strong>PERSONAL REPORTS</strong></h4>
<table class="table table-responsive table-striped table-bordered table-hover">
    <thead><tr><th>Report</th><th>Created</th></tr></thead>
    <tbody>
    <?php if(isset($_past_reports) && !empty($_past_reports)) { foreach ($_past_reports as $row) { ?>
        <tr>
            <td>
                <a href="javascript:void(0);" data-target="#details_<?php echo $row['report_id']?>" data-toggle="modal"><b><?php $window_period = explode('*', $row['window_period']); echo date_to_text($window_period[0])."     <i class='glyphicon glyphicon-arrow-right'></i>     ".date_to_text($window_period[1]) ?></b></a>
                <div id="details_<?php echo $row['report_id']?>" tabindex="-1" role="dialog" aria-hidden="true" class="modal fade">
                    <div style="width: 90vw !important;" class="modal-dialog modal-lg">
                        <div class="modal-content">
                            <form data-toggle="validator" role="form" method="post" action="">
                                <div class="modal-header">
                                    <button type="button" data-dismiss="modal" aria-hidden="true"  class="close">&times;</button>
                                    <h4 class="modal-title"><?php echo $row['title']?></h4>
                                </div>
                                <div class="modal-body">
                                    <div class="row">
                                        <div class="col-sm-7">
                                            <?php include 'views/rms/read_report.php'?>
                                        </div>
                                        <div class="col-sm-5">
                                            <p class="text-center"><b>COMMENTS</b></p>
                                            <div style="word-break:break-all; max-height: 550px; overflow-y: scroll; overflow-x: hidden">
                                                <?php
                                                $latest_comments = $obj_rms->get_report_comment($row['report_id']);
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
                                            </div>
                                            <form  data-toggle="validator" role="form" method="post" action="">
                                                <input type="hidden" class="form-control" id="report_id" name="report_id" value="<?php echo $row['report_id']; ?>">
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
            <td><?php echo datetime_to_text($row['created'])?></td>
        </tr>
    <?php } } else { echo "<tr><td colspan='4' class='text-danger'><em>No results to display</em></td></tr>"; } ?>
    </tbody>
</table>
<?php if(isset($_past_reports) && !empty($_past_reports)) { ?>
    <div class="tool-footer text-right">
        <p class="pull-left">Showing <?php echo $prespagelow . " to " . $prespagehigh . " of " . $numrows; ?> entries</p>
    </div>
<?php } ?>
<?php if(isset($_past_reports) && !empty($_past_reports)) { require_once 'layouts/pagination_links.php'; } ?>
