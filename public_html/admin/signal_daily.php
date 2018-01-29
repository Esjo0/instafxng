<?php
require_once("../init/initialize_admin.php");
if (!$session_admin->is_logged_in()) {
    redirect_to("login.php");
}


// Update signal
if (isset($_POST['process'])) {
    
    foreach($_POST as $key => $value) {
        $_POST[$key] = $db_handle->sanitizePost(trim($value));
    }

    extract($_POST);

    if($db_handle->numRows("SELECT * FROM signal_daily WHERE signal_date LIKE '$signal_date'") > 0)
    {
        $message_error = "You have already uploaded forex signal for the selected date, please choose another date.";
    } else {
        $query = "INSERT INTO signal_daily (symbol_id, order_type, price, take_profit, stop_loss, signal_date) VALUES
            (1, 'BUY', '$eur_usd_buy_price', '$eur_usd_buy_tp', '$eur_usd_buy_sl', '$signal_date'),
            (1, 'SELL', '$eur_usd_sell_price', '$eur_usd_sell_tp', '$eur_usd_sell_sl', '$signal_date'),
            (2, 'BUY', '$gbp_usd_buy_price', '$gbp_usd_buy_tp', '$gbp_usd_buy_sl', '$signal_date'),
            (2, 'SELL', '$gbp_usd_sell_price', '$gbp_usd_sell_tp', '$gbp_usd_sell_sl', '$signal_date'),
            (3, 'BUY', '$usd_cad_buy_price', '$usd_cad_buy_tp', '$usd_cad_buy_sl', '$signal_date'),
            (3, 'SELL', '$usd_cad_sell_price', '$usd_cad_sell_tp', '$usd_cad_sell_sl', '$signal_date')";
        $result = $db_handle->runQuery($query);
        $result ? $message_success = "Your changes have been saved." : $message_error = "No Changes Made";
    }
}



// Get signal details
$signals = $db_handle->fetchAssoc($db_handle->runQuery("SELECT * FROM signal_daily INNER JOIN signal_symbol ON signal_symbol.signal_symbol_id = signal_daily.symbol_id"));

//TODO: refactor this foreach later, it should not be hard coded like this
foreach($signals as $row) {
    if(in_array("EURUSD", $row) && in_array("BUY", $row)) {
        $eur_usd_buy_price = $row['price']; $eur_usd_buy_tp = $row['take_profit']; $eur_usd_buy_sl = $row['stop_loss'];
    }
    
    if(in_array("EURUSD", $row) && in_array("SELL", $row)) {
        $eur_usd_sell_price = $row['price']; $eur_usd_sell_tp = $row['take_profit']; $eur_usd_sell_sl = $row['stop_loss'];
    }
    
    if(in_array("GBPUSD", $row) && in_array("BUY", $row)) {
        $gbp_usd_buy_price = $row['price']; $gbp_usd_buy_tp = $row['take_profit']; $gbp_usd_buy_sl = $row['stop_loss'];
    }
    
    if(in_array("GBPUSD", $row) && in_array("SELL", $row)) {
        $gbp_usd_sell_price = $row['price']; $gbp_usd_sell_tp = $row['take_profit']; $gbp_usd_sell_sl = $row['stop_loss'];
    }
    
    if(in_array("USDCAD", $row) && in_array("BUY", $row)) {
        $usd_cad_buy_price = $row['price']; $usd_cad_buy_tp = $row['take_profit']; $usd_cad_buy_sl = $row['stop_loss'];
    }
    
    if(in_array("USDCAD", $row) && in_array("SELL", $row)) {
        $usd_cad_sell_price = $row['price']; $usd_cad_sell_tp = $row['take_profit']; $usd_cad_sell_sl = $row['stop_loss'];
    }
}
?>
<!DOCTYPE html>
<html lang="en">
    <head>
        <base target="_self">
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>Instaforex Nigeria | Signal Daily</title>
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
                            <h4><strong>UPDATE DAILY SIGNAL</strong></h4>
                        </div>
                    </div>
                    
                    <div class="section-tint super-shadow">
                        <div class="row">
                            <div class="col-sm-12">
                                <?php require_once 'layouts/feedback_message.php'; ?>
                                <p>Modify the form below to update the daily signal. Choose the date you are posting the signal for.</p>

                                <br />
                                <form data-toggle="validator" class="form-horizontal" role="form" method="post" action="<?php echo $REQUEST_URI; ?>">

                                    <div class="form-group">
                                        <label class="control-label col-sm-3" for="pay_date">Signal Date:</label>
                                        <div class="col-sm-9 col-lg-5">
                                            <div class='input-group date' id='datetimepicker'>
                                                <input name="signal_date" type="text" class="form-control" id='datetimepicker2' required>
                                                <span class="input-group-addon"><span class="glyphicon glyphicon-calendar"></span></span>
                                            </div>
                                            <span class="help-block">Format: (YYYY-MM-DD) e.g. 2016-12-25</span>
                                        </div>
                                        <script type="text/javascript">
                                            $(function () {
                                                $('#datetimepicker').datetimepicker({
                                                    format: 'YYYY-MM-DD'
                                                });
                                                $('#datetimepicker2').datetimepicker({
                                                    format: 'YYYY-MM-DD'
                                                });
                                            });
                                        </script>
                                    </div>

                                    <div class="form-group">
                                        <label class="control-label col-sm-3" for="">EUR/USD:</label>
                                        <div class="col-sm-9 col-lg-8">
                                            <div class="row">
                                                <div class="col-sm-3"><input name="eur_usd_buy_order" type="text" value="BUY" class="form-control" placeholder="BUY" disabled/></div>
                                                <div class="col-sm-3"><input name="eur_usd_buy_price" type="text" value="<?php if(isset($eur_usd_buy_price)) { echo $eur_usd_buy_price; } ?>" class="form-control" placeholder="Price"  required/></div>
                                                <div class="col-sm-3"><input name="eur_usd_buy_tp" type="text" value="<?php if(isset($eur_usd_buy_tp)) { echo $eur_usd_buy_tp; } ?>" class="form-control" placeholder="TP" required/></div>
                                                <div class="col-sm-3"><input name="eur_usd_buy_sl" type="text" value="<?php if(isset($eur_usd_buy_sl)) { echo $eur_usd_buy_sl; } ?>" class="form-control" placeholder="SL" required/></div>
                                            </div>
                                        </div>
                                    </div>
                                    
                                    <div class="form-group">
                                        <label class="control-label col-sm-3" for="">EUR/USD:</label>
                                        <div class="col-sm-9 col-lg-8">
                                            <div class="row">
                                                <div class="col-sm-3"><input name="eur_usd_sell_order" type="text" value="SELL" class="form-control" placeholder="SELL" disabled/></div>
                                                <div class="col-sm-3"><input name="eur_usd_sell_price" type="text" value="<?php if(isset($eur_usd_sell_price)) { echo $eur_usd_sell_price; } ?>" class="form-control" placeholder="Price"  required/></div>
                                                <div class="col-sm-3"><input name="eur_usd_sell_tp" type="text" value="<?php if(isset($eur_usd_sell_tp)) { echo $eur_usd_sell_tp; } ?>" class="form-control" placeholder="TP" required/></div>
                                                <div class="col-sm-3"><input name="eur_usd_sell_sl" type="text" value="<?php if(isset($eur_usd_sell_sl)) { echo $eur_usd_sell_sl; } ?>" class="form-control" placeholder="SL" required/></div>
                                            </div>
                                            <br/><hr/>
                                        </div>
                                    </div>
                                    
                                    <div class="form-group">
                                        <label class="control-label col-sm-3" for="">GBP/USD:</label>
                                        <div class="col-sm-9 col-lg-8">
                                            <div class="row">
                                                <div class="col-sm-3"><input name="gbp_usd_buy_order" type="text" value="BUY" class="form-control" placeholder="BUY" disabled/></div>
                                                <div class="col-sm-3"><input name="gbp_usd_buy_price" type="text" value="<?php if(isset($gbp_usd_buy_price)) { echo $gbp_usd_buy_price; } ?>" class="form-control" placeholder="Price"  required/></div>
                                                <div class="col-sm-3"><input name="gbp_usd_buy_tp" type="text" value="<?php if(isset($gbp_usd_buy_tp)) { echo $gbp_usd_buy_tp; } ?>" class="form-control" placeholder="TP" required/></div>
                                                <div class="col-sm-3"><input name="gbp_usd_buy_sl" type="text" value="<?php if(isset($gbp_usd_buy_sl)) { echo $gbp_usd_buy_sl; } ?>" class="form-control" placeholder="SL" required/></div>
                                            </div>
                                        </div>
                                    </div>
                                    
                                    <div class="form-group">
                                        <label class="control-label col-sm-3" for="">GBP/USD:</label>
                                        <div class="col-sm-9 col-lg-8">
                                            <div class="row">
                                                <div class="col-sm-3"><input name="gbp_usd_sell_order" type="text" value="SELL" class="form-control" placeholder="SELL" disabled/></div>
                                                <div class="col-sm-3"><input name="gbp_usd_sell_price" type="text" value="<?php if(isset($gbp_usd_sell_price)) { echo $gbp_usd_sell_price; } ?>" class="form-control" placeholder="Price"  required/></div>
                                                <div class="col-sm-3"><input name="gbp_usd_sell_tp" type="text" value="<?php if(isset($gbp_usd_sell_tp)) { echo $gbp_usd_sell_tp; } ?>" class="form-control" placeholder="TP" required/></div>
                                                <div class="col-sm-3"><input name="gbp_usd_sell_sl" type="text" value="<?php if(isset($gbp_usd_sell_sl)) { echo $gbp_usd_sell_sl; } ?>" class="form-control" placeholder="SL" required/></div>
                                            </div>
                                            <br/><hr/>
                                        </div>
                                    </div>
                                    
                                    <div class="form-group">
                                        <label class="control-label col-sm-3" for="">USD/CAD:</label>
                                        <div class="col-sm-9 col-lg-8">
                                            <div class="row">
                                                <div class="col-sm-3"><input name="usd_cad_buy_order" type="text" value="BUY" class="form-control" placeholder="BUY" disabled/></div>
                                                <div class="col-sm-3"><input name="usd_cad_buy_price" type="text" value="<?php if(isset($usd_cad_buy_price)) { echo $usd_cad_buy_price; } ?>" class="form-control" placeholder="Price"  required/></div>
                                                <div class="col-sm-3"><input name="usd_cad_buy_tp" type="text" value="<?php if(isset($usd_cad_buy_tp)) { echo $usd_cad_buy_tp; } ?>" class="form-control" placeholder="TP" required/></div>
                                                <div class="col-sm-3"><input name="usd_cad_buy_sl" type="text" value="<?php if(isset($usd_cad_buy_sl)) { echo $usd_cad_buy_sl; } ?>" class="form-control" placeholder="SL" required/></div>
                                            </div>
                                        </div>
                                    </div>
                                    
                                    <div class="form-group">
                                        <label class="control-label col-sm-3" for="">USD/CAD:</label>
                                        <div class="col-sm-9 col-lg-8">
                                            <div class="row">
                                                <div class="col-sm-3"><input name="usd_cad_sell_order" type="text" value="SELL" class="form-control" placeholder="SELL" disabled/></div>
                                                <div class="col-sm-3"><input name="usd_cad_sell_price" type="text" value="<?php if(isset($usd_cad_sell_price)) { echo $usd_cad_sell_price; } ?>" class="form-control" placeholder="Price"  required/></div>
                                                <div class="col-sm-3"><input name="usd_cad_sell_tp" type="text" value="<?php if(isset($usd_cad_sell_tp)) { echo $usd_cad_sell_tp; } ?>" class="form-control" placeholder="TP" required/></div>
                                                <div class="col-sm-3"><input name="usd_cad_sell_sl" type="text" value="<?php if(isset($usd_cad_sell_sl)) { echo $usd_cad_sell_sl; } ?>" class="form-control" placeholder="SL" required/></div>
                                            </div>
                                        </div>
                                    </div>
                                    
                                    <div class="form-group">
                                        <div class="col-sm-offset-3 col-sm-9">
                                            <button type="button" data-target="#confirm-update-signal" data-toggle="modal" class="btn btn-success">Update Signal</button>
                                        </div>
                                    </div>
                                    
                                    <!--Modal - confirmation boxes--> 
                                    <div id="confirm-update-signal" tabindex="-1" role="dialog" aria-hidden="true" class="modal fade">
                                        <div class="modal-dialog">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <button type="button" data-dismiss="modal" aria-hidden="true"
                                                        class="close">&times;</button>
                                                    <h4 class="modal-title">Update Signal Confirmation</h4></div>
                                                <div class="modal-body">Are you sure you want to update the signal? This action cannot be reversed.</div>
                                                <div class="modal-footer">
                                                    <input name="process" type="submit" class="btn btn-success" value="Proceed">
                                                    <button type="submit" name="close" onClick="window.close();" data-dismiss="modal" class="btn btn-danger">Close!</button>
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