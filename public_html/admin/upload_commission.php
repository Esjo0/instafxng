<?php
require_once("../init/initialize_admin.php");
if (!$session_admin->is_logged_in()) {
    redirect_to("login.php");
}

$client_operation = new clientOperation();

if (isset($_POST["upload_commission"])) {
    $csv_file = $_FILES["file"]["tmp_name"];
    $commission_date = $_POST["commission_date"];
    
    if($system_object->commission_upload_duplicate($commission_date)) {
        $message_error = "Looks like you have uploaded commission for the selected date";
    } else {
        $getfile = fopen($csv_file, "r+");
        if($getfile) {
            while (($data = fgets($getfile)) !== FALSE) {
                
                $slice = explode(",", $data);

                $date_opened = trim($slice[0]);
                $account_no = trim($slice[1]);
                $volume = trim($slice[2]);
                $commission = trim($slice[3]);

                // Do date conversion here
                $new_date_opened = date("Y-m-d", strtotime($date_opened));

                if($new_date_opened == $commission_date) {
                    $query = "INSERT INTO trading_commission(ifx_acct_no, volume, commission, currency_id, date_earned) VALUES ('$account_no', $volume, $commission, '2', '$commission_date')";
                    $db_handle->runQuery($query);
                    $reference = $db_handle->insertedId();

                    $client_operation->set_trading_loyalty_point($account_no, $volume, $reference, $commission_date);
                } else {
                    continue;
                }
            }

            $message_success = "Commission details has been uploaded into the system";
        }
    }
}

$query = "SELECT date_earned FROM trading_commission ORDER BY date_earned DESC LIMIT 1";
$result = $db_handle->runQuery($query);
$last_updated = $db_handle->fetchAssoc($result);
$last_updated = date_to_text($last_updated[0]['date_earned']);

?>
<!DOCTYPE html>
<html lang="en">
    <head>
        <base target="_self">
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>Instaforex Nigeria | Admin</title>
        <meta name="title" content="Instaforex Nigeria | Admin" />
        <meta name="keywords" content="" />
        <meta name="description" content="" />
        <?php require_once 'layouts/head_meta.php'; ?>
    </head>
    <body>
        <?php require_once 'layouts/header.php'; ?>
        <!-- Main Body: The is the main content area of the web site, contains a side bar  -->
        <div id="main-body" class="container-fluid">
            <div class="row no-gutter">
                <!-- Main Body - Side Bar  -->
                <div id="main-body-side-bar" class="col-md-4 col-lg-3 left-nav">
                <?php require_once 'layouts/sidebar.php'; ?>
                </div>
                
                <!-- Main Body - Content Area: This is the main content area, unique for each page  -->
                <div id="main-body-content-area" class="col-md-8 col-lg-9">
                    
                    <!-- Unique Page Content Starts Here
                    ================================================== -->
                    <div class="row">
                        <div class="col-sm-12 text-danger">
                            <h4><strong>UPLOAD TRADING COMMISSIONS</strong></h4>
                        </div>
                    </div>
                    
                    <div class="section-tint super-shadow">
                        <div class="row">
                            <div class="col-sm-12">
                                <?php require_once 'layouts/feedback_message.php'; ?>
                                
                                <p>On this page, you can upload an excel file containing trading commissions.</p>
                                <p><span class="text-danger"><strong>Note:</strong></span> Before upload, convert the excel file to a CSV format, remove unnecessary information
                                from the file, then browse to upload the file. Choose the date that the commission was earned.</p>

                                <p class="text-danger"><small>Commission Last Updated: <?php echo $last_updated; ?></small></p>

                                <form data-toggle="validator" class="form-horizontal" enctype="multipart/form-data" role="form" method="post" action="<?php echo $REQUEST_URI; ?>">
                                    <div class="form-group">
                                        <label class="control-label col-sm-3" for="file_upload">File Upload:</label>
                                        <div class="col-sm-9 col-lg-5">
                                            <p><input type="file" name="file" id="file" size="150" /></p>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="control-label col-sm-3" for="commission_date">Commission Date:</label>
                                        <div class="col-sm-9 col-lg-5">
                                            <div class="input-group date" id="datetimepicker">
                                                <input name="commission_date" type="text" class="form-control" id="datetimepicker2" required>
                                                <span class="input-group-addon"><span class="glyphicon glyphicon-calendar"></span></span>
                                            </div>
                                        </div>
                                        <script type="text/javascript">
                                            $(function () {
                                                $('#datetimepicker, #datetimepicker2').datetimepicker({
                                                    format: 'YYYY-MM-DD'
                                                });
                                            });
                                        </script>
                                    </div>
                                    <div class="form-group">
                                        <div class="col-sm-offset-3 col-sm-9">
                                            <button type="button" data-target="#upload-commission-confirm" data-toggle="modal" class="btn btn-success"><i class="fa fa-upload fa-fw"></i> Upload</button>
                                        </div>
                                    </div>
                                    
                                    <!-- Modal - confirmation boxes -->
                                    <div id="upload-commission-confirm" tabindex="-1" role="dialog" aria-hidden="true" class="modal fade">
                                        <div class="modal-dialog">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <button type="button" data-dismiss="modal" aria-hidden="true"
                                                        class="close">&times;</button>
                                                    <h4 class="modal-title">Upload Commission Confirmation</h4>
                                                </div>
                                                <div class="modal-body">Are you sure you want to upload this information?</div>
                                                <div class="modal-footer">
                                                    <input name="upload_commission" type="submit" class="btn btn-success" value="Upload" />
                                                    <button type="submit" name="decline" onClick="window.close();" data-dismiss="modal" class="btn btn-danger">Close !</button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>

                    </div>

                    <!-- Unique Page Content Ends Here
                    ================================================== -->
                    
                </div>
                
            </div>
        </div>
        <?php require_once 'layouts/footer.php'; ?>
        <script src="//cdnjs.cloudflare.com/ajax/libs/moment.js/2.9.0/moment-with-locales.js"></script>
        <script src="//cdn.rawgit.com/Eonasdan/bootstrap-datetimepicker/e8bddc60e73c1ec2475f827be36e1957af72e2ea/src/js/bootstrap-datetimepicker.js"></script>
    </body>
</html>