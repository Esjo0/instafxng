
<!DOCTYPE html>
<html lang="en">
<head>
    <base target="_self">
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Instaforex Nigeria | Admin Reporting System</title>
    <meta name="title" content="Instaforex Nigeria | Admin Reporting System" />
    <meta name="keywords" content="" />
    <meta name="description" content="" />
    <?php require_once 'layouts/head_meta.php'; ?>
    <script src="tinymce/tinymce.min.js"></script>
    <script type="text/javascript">
        tinyMCE.init({
            selector: "textarea#content",
            height: 500,
            theme: "modern",
            relative_urls: false,
            remove_script_host: false,
            convert_urls: true,
            plugins: [
                "advlist autolink lists link image charmap print preview hr anchor pagebreak",
                "searchreplace wordcount visualblocks visualchars code fullscreen",
                "insertdatetime media nonbreaking save table contextmenu directionality",
                "template paste textcolor colorpicker textpattern "
            ],
            toolbar1: "insertfile undo redo | styleselect | bold italic | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | link image",
            toolbar2: "| print preview media | forecolor backcolor emoticons",
            image_advtab: true,
            external_filemanager_path: "../filemanager/",
            filemanager_title: "Instafxng Filemanager",
        });
    </script>
    <script src="//code.jquery.com/jquery-1.12.4.min.js"></script>
    <script type="text/javascript" src="../js/jquery-popup.js"></script>
    <link href="//jqueryscript.net/css/jquerysctipttop.css" rel="stylesheet" type="text/css">
    <link rel="stylesheet" type="text/css" href="../css/hunterPopup.css">
    <link rel="stylesheet" href="//netdna.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
</head>
<body class="">
<div class="section-tint super-shadow">
<div class="row ">
    <div class="col-sm-12">
        <?php require_once 'layouts/feedback_message.php'; ?>
        <p class="pull-right"><a href="rms_create_report.php" class="btn btn-sm btn-default"><i class="glyphicon glyphicon-plus"></i> New Report</a></p>
        <p class="pull-left"><a href="javascript:void(0);" data-target="#target_report" data-toggle="modal" class="btn btn-sm btn-default"><i class="glyphicon glyphicon-plus"></i> New Target Report</a></p>
    </div></div>
<div id="target_report" tabindex="-1" role="dialog" aria-hidden="true" class="modal fade">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <form data-toggle="validator" role="form" method="post" action="">
                <div class="modal-header">
                    <button type="button" data-dismiss="modal" aria-hidden="true"  class="close">&times;</button>
                    <h4 class="modal-title">New Target Report</h4></div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-sm-12">
                            <p></p>
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
                                            <a id="a_<?php echo $row; ?>" href="#"><?php echo $target_details['title'];?></a>
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
                                        <td><?php echo datetime_to_text($row['created'])?></td>
                                        <td><a href="rms_create_report.php?t_id=<?php echo $row ?>" title="Create Report for this target." class="btn btn-sm btn-default"><i class="glyphicon glyphicon-pencil"></i></a></td>
                                    </tr>
                                <?php } } else { echo "<tr><td colspan='3' class='text-danger'><em>No results to display</em></td></tr>"; } ?>
                                </tbody>
                            </table>
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
<h4><strong>PERSONAL REPORTS</strong></h4>
<table class="table table-responsive table-striped table-bordered table-hover">
    <thead>
    <tr>
        <th>Report</th>
        <th>Created</th>
    </tr>
    </thead>
    <tbody>
    <?php if(isset($past_reports) && !empty($past_reports)) { foreach ($past_reports as $row) { ?>
        <tr>
            <td>
                <a href="javascript:void(0);" data-target="#details_<?php echo $row['report_id']?>" data-toggle="modal"><b><?php $window_period = explode('*', $row['window_period']); echo date_to_text($window_period[0])."<i class='glyphicon glyphicon-arrow-right'></i>".date_to_text($window_period[1]) ?></b></a>
                <div id="details_<?php echo $row['report_id']?>" tabindex="-1" role="dialog" aria-hidden="true" class="modal fade">
                    <div style="width: 90vw !important;" class="modal-dialog modal-lg">
                        <div class="modal-content">
                            <form data-toggle="validator" role="form" method="post" action="">
                                <div class="modal-header">
                                    <button type="button" data-dismiss="modal" aria-hidden="true"  class="close">&times;</button>
                                    <h4 class="modal-title"><?php echo $row['title']?></h4></div>
                                <div class="modal-body">
                                    <div class="row">
                                        <div class="col-sm-7">
                                            <p class="text-center"><b>REPORT</b></p>
                                            <?php echo $row['report']?>
                                            <p><b>Date Created:</b> <?php echo datetime_to_text($row['created'])?></p>
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
    </div>
<script src="//cdnjs.cloudflare.com/ajax/libs/moment.js/2.9.0/moment-with-locales.js"></script>
<script src="//cdn.rawgit.com/Eonasdan/bootstrap-datetimepicker/e8bddc60e73c1ec2475f827be36e1957af72e2ea/src/js/bootstrap-datetimepicker.js"></script>
</body>
</html>