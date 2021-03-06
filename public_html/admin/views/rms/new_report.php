
<h4><strong>NEW REPORT</strong></h4>
<span>Select a date range below and create a report. </span>
<form data-toggle="validator" class="form-horizontal" enctype="multipart/form-data" role="form" method="post" action="<?php echo $_SERVER['REQUEST_URI']; ?>">
    <?php if(isset($_GET['t_id'])){ ?>
        <input name="target_id" type="hidden" value="<?php echo $_GET['t_id']?>">
    <?php } ?>
    <?php if(isset($_GET['r_id'])){ ?>
        <input name="report_id" type="hidden" value="<?php echo $_GET['r_id']?>">
    <?php } ?>
    <?php if(isset($_GET['t_id']) && !empty(isset($_GET['t_id']))):
        $target_details = $obj_rms->get_target_by_id($_GET['t_id']);
        $target_details = explode('*', $target_details['window_period']);
        ?>
        <input name="from_date" type="hidden" value="<?php echo $target_details[0]?>">
        <input name="to_date" type="hidden" value="<?php echo date('Y-m-d')?>">
        <div class="form-group">
            <div class="col-sm-12">
                <div class="col-sm-2"></div>
                <div class="col-sm-4">
                    <div class="input-group date">
                        <input disabled placeholder="Date Range(From)" value="<?php echo $target_details[0] ?>"  type="text" class="form-control" id="datetimepicker" required>
                        <span class="input-group-addon"><span class="glyphicon glyphicon-calendar"></span></span>
                    </div>
                </div>
                <div class="col-sm-4">
                    <div class="input-group date">
                        <input disabled value="<?php echo date('Y-m-d'); ?>" type="text" class="form-control" id="datetimepicker2" required>
                        <span class="input-group-addon"><span class="glyphicon glyphicon-calendar"></span></span>
                    </div>
                </div>
                <div class="col-sm-2"></div>
            </div>
        </div>
    <?php endif;?>
    <?php if(isset($_GET['r_id']) && !empty(isset($_GET['r_id']))):
        $report_details = $obj_rms->get_report_by_id($_GET['r_id']);
        $report_details['window_period'] = explode('*', $report_details['window_period']);
        ?>
        <input name="target_id" type="hidden" value="<?php echo $report_details['target_id']?>">
        <input name="from_date" type="hidden" value="<?php echo $report_details['window_period'][0]?>">
        <input name="to_date" type="hidden" value="<?php echo $report_details['window_period'][1]?>">
        <div class="form-group">
            <div class="col-sm-12">
                <div class="col-sm-2"></div>
                <div class="col-sm-4">
                    <div class="input-group date">
                        <input placeholder="Date Range(From)" value="<?php echo $report_details['window_period'][0] ?>"  type="text" class="form-control" id="datetimepicker" required>
                        <span class="input-group-addon"><span class="glyphicon glyphicon-calendar"></span></span>
                    </div>
                </div>
                <div class="col-sm-4">
                    <div class="input-group date">
                        <input value="<?php echo $report_details['window_period'][1]; ?>" type="text" class="form-control" id="datetimepicker2" required>
                        <span class="input-group-addon"><span class="glyphicon glyphicon-calendar"></span></span>
                    </div>
                </div>
                <div class="col-sm-2"></div>
            </div>
        </div>
    <?php endif;?>
    <?php if(!isset($_GET['t_id']) && !isset($_GET['r_id'])):?>
        <div class="form-group">
            <div class="col-sm-12">
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
        </div>
    <?php endif; ?>
    <div class="form-group">
        <div class="col-sm-12">
            <textarea placeholder="Enter your report here..." name="report" id="report"  rows="5" required><?php if(isset($report_details['report']) && !empty($report_details['report'])){echo $report_details['report'];} ?></textarea>
        </div>
    </div>
    <div class="form-group">
        <div class="col-sm-12">
            <table class="table table-responsive table-bordered table-sm">
                <thead>
                    <tr>
                        <th colspan="3">
                            <input name="attachments[]" onchange="add_file_to_table(this.id, 'file_table')" style="display:none" id="file_input" type="file" multiple>
                            <label for="file_input" class="btn btn-sm btn-default"><i class="glyphicon glyphicon-file"></i> Add Attachment</label>
                        </th>
                    </tr>
                </thead>
                <tbody id="file_table"></tbody>
            </table>
        </div>
    </div>
    <div class="form-group">
        <div class="col-sm-10 col-lg-5">
            <div class="radio">
                <label><input id="venue" type="radio" name="status" value="1" <?php if($report_details['status'] == '1') { echo "checked"; } ?> required>Save As Draft</label>
            </div>
            <div class="radio">
                <label><input id="venue" type="radio" name="status" value="2" <?php if($report_details['status'] == '2') { echo "checked"; } ?> required>Send For Review</label>
            </div>
        </div>
    </div>
    <div class="form-group">
        <div class="col-sm-12">
            <button type="button" data-target="#add-report-confirm" data-toggle="modal" class="btn btn-success"><i class="fa fa-send fa-fw"></i> Submit</button>
        </div>
    </div>

    <!-- Modal - confirmation boxes -->
    <div id="add-report-confirm" tabindex="-1" role="dialog" aria-hidden="true" class="modal fade">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" data-dismiss="modal" aria-hidden="true"
                            class="close">&times;</button>
                    <h4 class="modal-title">Report Confirmation</h4>
                </div>
                <div class="modal-body">Are you sure you want to send this report?</div>
                <div class="modal-footer">
                    <input name="<?php if(isset($report_details['report_id']) && !empty($report_details['report_id'])){echo 'process_update';}else{echo 'process_report';} ?>" type="submit" class="btn btn-success" value="Save">
                    <button type="submit" name="decline" onClick="window.close();" data-dismiss="modal" class="btn btn-danger">Close !</button>
                </div>
            </div>
        </div>
    </div>
</form>
