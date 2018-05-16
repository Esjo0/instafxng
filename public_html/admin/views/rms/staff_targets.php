<?php

$admin_members = $admin_object->get_all_admin_member();
$created_targets = $obj_rms->get_staff_targets($_SESSION['admin_unique_code']);
array_sort_by_column_desc($created_targets, 'created');
//$created_targets = groupArray($created_targets, "target_id");
$_reportees = $obj_rms->get_reportees($_SESSION['admin_unique_code']);
?>

<p class="pull-right"><button data-target="#confirm-add-admin" data-toggle="modal" class="btn btn-sm btn-default"><i class="glyphicon glyphicon-plus"></i> New Target</button></p>
<div id="confirm-add-admin" tabindex="-1" role="dialog" aria-hidden="true" class="modal fade">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <form data-toggle="validator" role="form" method="post" action="">
                <div class="modal-header">
                    <button type="button" data-dismiss="modal" aria-hidden="true"  class="close">&times;</button>
                    <h4 class="modal-title">New Target</h4></div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-sm-12">
                            <div class="form-group">
                                <input type="text" name="title" placeholder="Target Title" class="form-control" />
                            </div>
                            <div class="form-group">
                                <textarea name="description" placeholder="Target Description" class="form-control" rows="4"></textarea>
                            </div>
                            <div class="form-group">
                                <div class="col-sm-2"></div>
                                <div class="col-sm-4">
                                    <div class="input-group date">
                                        <input placeholder="Date Range(From)"  name="from_date" type="text" class="form-control" id="datetimepicker" required>
                                        <script type="text/javascript">
                                            $(function () {
                                                $('#datetimepicker').datetimepicker({
                                                    format: 'YYYY-MM-DD'
                                                });
                                            });
                                        </script>
                                        <span class="input-group-addon"><span class="glyphicon glyphicon-calendar"></span></span>
                                    </div>
                                </div>
                                <div class="col-sm-4">
                                    <div class="input-group date">
                                        <input placeholder="Date Range(To)" name="to_date" type="text" class="form-control" id="datetimepicker2" required>
                                        <script type="text/javascript">
                                            $(function () {
                                                $('#datetimepicker2').datetimepicker({
                                                    format: 'YYYY-MM-DD'
                                                });
                                            });
                                        </script>
                                        <span class="input-group-addon"><span class="glyphicon glyphicon-calendar"></span></span>
                                    </div>
                                </div>
                                <div class="col-sm-2"></div>
                            </div>
                            <div class="form-group">
                                <?php foreach ($_reportees as $row2){;?>
                                    <!--Reviewer-->
                                    <div class="col-sm-6"><div class="checkbox"><label for=""><input type="checkbox" name="reportees[]" value="<?php echo $row2?>" <?php if (in_array(1, $my_pages)) { echo 'checked="checked"'; } ?>/><?php echo $admin_object->get_admin_name_by_code($row2);?></label></div></div>
                                    <!--Reviewer-->
                                <?php } ?>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <input name="process_target" type="submit" class="btn btn-sm btn-success" value="Proceed">
                    <button type="button" name="close" onClick="window.close();" data-dismiss="modal" class="btn btn-sm btn-danger">Close!</button>
                </div>
            </form>
        </div>
    </div>
</div>
<h4><strong>STAFF TARGETS</strong></h4>
<table class="table table-responsive table-striped table-bordered table-hover">
    <thead>
    <tr>
        <th>Target</th>
        <th>Author</th>
        <th>Reportees</th>
        <th>Window Period</th>
        <th>Created</th>
    </tr>
    </thead>
    <tbody>
    <?php if(isset($created_targets) && !empty($created_targets)) { foreach ($created_targets as $row) { ?>
        <tr>
            <td>
                <a data-target="#details_<?php echo $row['target_id']?>" data-toggle="modal" href="javascript:void(0);"><b><?php echo $row['title']?></b></a>
                <div id="details_<?php echo $row['target_id']?>" tabindex="-1" role="dialog" aria-hidden="true" class="modal fade">
                    <div class="modal-dialog modal-lg">
                        <div class="modal-content">
                            <form data-toggle="validator" role="form" method="post" action="">
                                <div class="modal-header">
                                    <button type="button" data-dismiss="modal" aria-hidden="true"  class="close">&times;</button>
                                    <h4 class="modal-title"><?php echo $row['title']?></h4></div>
                                <div class="modal-body">
                                    <div class="row">
                                        <div class="col-sm-12">
                                            <p><b>Target Title:</b> <?php echo $row['title']?></p>
                                            <p><b>Target Description:</b> <?php echo $row['description']?></p>
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
            <td>
                <?php echo $admin_object->get_admin_name_by_code($row['author']); ?>
            </td>
            <td>
                <?php $reportees = explode(',', $row['reportees']);
                foreach($reportees as $key)
                {
                    $x = $obj_rms->get_target_report($key, $row['target_id']);
                    if(isset($x) && !empty($x)): ?>
                        <a onclick="show_form('tr_<?php echo $row['target_id'] ?>')" id="a_<?php echo $row['target_id'] ?>" href="javascript:void(0);"><?php echo $admin_object->get_admin_name_by_code($key);?></a><br/>

                        <div style="display: none" id="tr_<?php echo $row['target_id'] ?>" >
                            <table style="width: 300px!important;" class="table-responsive table">
                            <tbody>
                            <?php $rc = 1;
                            foreach ($x as $pin) {$window_period = explode('*', $pin['window_period']);    ?>
                                <tr>
                                    <td><?php echo $rc; $rc++?></td>
                                    <td>
                                        <a href="javascript:void(0);" data-target="#t_r_<?php echo $pin['report_id']?>" data-toggle="modal"><?php echo date_to_text($window_period[0])."  <i class='glyphicon glyphicon-arrow-right'></i>  ".date_to_text($window_period[1]); ?></a>
                                        <div id="t_r_<?php echo $pin['report_id']?>" tabindex="-1" role="dialog" aria-hidden="true" class="modal fade">
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
                                                                    <?php
                                                                    $row['report_id'] = $pin['report_id'];
                                                                    include 'views/rms/read_report.php'
                                                                    ?>
                                                                </div>
                                                                <div class="col-sm-5">
                                                                    <p class="text-center"><b>COMMENTS</b></p>
                                                                    <div style="word-break:break-all; max-height: 550px; overflow-y: scroll; overflow-x: hidden">
                                                                        <?php
                                                                        $latest_comments = $obj_rms->get_report_comment($pin['report_id']);
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

                                </tr>
                            <?php } ?>
                            </tbody>
                        </table>
                            <br/><br/>
                        </div>
                    <?php endif;





                    if(!isset($x) || empty($x)):
                        echo $admin_object->get_admin_name_by_code($key)."<br/>";
                    endif;
                } ?>
            </td>
            <td>
                <?php $window_period = explode('*', $row['window_period']);
                echo date_to_text($window_period[0])."  <i class='glyphicon glyphicon-arrow-right'></i>  ".date_to_text($window_period[1]); ?>
            </td>
            <td><?php echo datetime_to_text($row['created'])?></td>
        </tr>
    <?php } } else { echo "<tr><td colspan='4' class='text-danger'><em>No results to display</em></td></tr>"; } ?>
    </tbody>
</table>

<?php if(isset($created_targets) && !empty($created_targets)) { ?>
    <div class="tool-footer text-right">
        <p class="pull-left">Showing <?php echo $prespagelow . " to " . $prespagehigh . " of " . $numrows; ?> entries</p>
    </div>
<?php } ?>
<?php if(isset($created_targets) && !empty($created_targets)) { require_once 'layouts/pagination_links.php'; } ?>
