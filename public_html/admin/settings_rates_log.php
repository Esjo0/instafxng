<?php
require_once("../init/initialize_admin.php");
if (!$session_admin->is_logged_in()) {
    redirect_to("login.php");
}

$client_operation = new clientOperation();

if (isset($_POST["rates_log"])) {

    foreach($_POST as $key => $value) {
        $_POST[$key] = $db_handle->sanitizePost(trim($value));
    }

    extract($_POST);

    if(empty($effect_date) || empty($deposit_ilpr) || empty($deposit_nonilpr) || empty($withdraw_rate)) {
        $message_error = "All fields are compulsory, please try again.";
    } else {
        $rates_log = $system_object->log_new_rates($effect_date, $deposit_ilpr, $deposit_nonilpr, $withdraw_rate);

        if($rates_log) {
            $message_success = "You have successfully log new exchange rates.";
        } else {
            $message_error = "Looks like something went wrong or you didn't make any change.";
        }
    }
}

?>
<!DOCTYPE html>
<html lang="en">
    <head>
        <base target="_self">
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>Instaforex Nigeria | Admin - Settings Rates Log</title>
        <meta name="title" content="Instaforex Nigeria | Admin - Settings Rates Log" />
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
                            <h4><strong>EXCHANGE RATES LOG</strong></h4>
                        </div>
                    </div>
                    
                    <div class="section-tint super-shadow">
                        <div class="row">
                            <div class="col-sm-12">
                                <?php require_once 'layouts/feedback_message.php'; ?>
                                
                                <p>Log exchange rate below, select the date that this rate is taking effect.</p>

                                <form data-toggle="validator" class="form-horizontal" role="form" method="post" action="">

                                    <div class="form-group">
                                        <label class="control-label col-sm-3" for="effect_date">Effect Date:</label>
                                        <div class="col-sm-9 col-lg-5">
                                            <div class="input-group date" id="datetimepicker">
                                                <input name="effect_date" type="text" class="form-control" id="datetimepicker2" required>
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
                                        <label class="control-label col-sm-3" for="deposit_ilpr">Deposit ILPR:</label>
                                        <div class="col-sm-9 col-lg-5">
                                            <input name="deposit_ilpr" type="text" id="" value="" class="form-control" required/>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="control-label col-sm-3" for="deposit_nonilpr">Deposit Non-ILPR:</label>
                                        <div class="col-sm-9 col-lg-5">
                                            <input name="deposit_nonilpr" type="text" id="" value="" class="form-control" required/>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="control-label col-sm-3" for="withdraw_rate">Withdraw:</label>
                                        <div class="col-sm-9 col-lg-5">
                                            <input name="withdraw_rate" type="text" id="" value="" class="form-control" required/>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <div class="col-sm-offset-3 col-sm-9">
                                            <button type="button" data-target="#rates-log-confirm" data-toggle="modal" class="btn btn-success"> Save</button>
                                        </div>
                                    </div>
                                    
                                    <!-- Modal - confirmation boxes -->
                                    <div id="rates-log-confirm" tabindex="-1" role="dialog" aria-hidden="true" class="modal fade">
                                        <div class="modal-dialog">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <button type="button" data-dismiss="modal" aria-hidden="true"
                                                        class="close">&times;</button>
                                                    <h4 class="modal-title">Log Rates Confirmation</h4>
                                                </div>
                                                <div class="modal-body">Are you sure you want to save this information?</div>
                                                <div class="modal-footer">
                                                    <input name="rates_log" type="submit" class="btn btn-success" value="Save" />
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