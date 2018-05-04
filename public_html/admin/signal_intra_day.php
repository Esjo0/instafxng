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
                            <h4><strong>UPLOAD INTRA-DAY SIGNALS</strong></h4>
                        </div>
                    </div>
<div class="row">
    <div class="col-md-8">
                    <div class="section-tint super-shadow">
                        <div class="row">
                            <div class="col-sm-12">
                                <?php require_once 'layouts/feedback_message.php'; ?>
                                <p>Modify the form below to update the daily signal. Choose the date you are posting the signal for.</p>
                                <br />
                                <div class="col-md-12 order-md-1">
                                    <form class="needs-validation" novalidate>
                                        <div class="form-group">
                                            <label class="control-label col-sm-3" for="location">Currency Pair </label>
                                            <div class="col-sm-9 col-lg-5">
                                                <div class="input-group date">
                                                    <select name="location" class="form-control" id="location">
                                                        <option value="" selected>All Offices</option>
                                                        <?php
                                                        $query = "SELECT * FROM accounting_system_office_locations ";
                                                        $result = $db_handle->runQuery($query);
                                                        $result = $db_handle->fetchAssoc($result);
                                                        foreach ($result as $row)
                                                        {
                                                            extract($row)
                                                            ?>
                                                            <option value="<?php echo $location_id;?>"><?php echo $location;?></option>
                                                        <?php } ?>
                                                    </select>
                                                    <span class="input-group-addon"><span class="fa fa-gg"></span></span>
                                                </div>
                                            </div>
                                        </div>
                                        <br>
                                        <br/>
                                        <hr/>
                                        <div class="form-group">
                                            <div class="col-sm-12">
                                                <div class="row">
                                                    <label class="control-label col-sm-3" for="">BUY PRICE </label>
                                                    <div class="col-sm-3"><input name="eur_usd_buy_price" type="text" value="<?php if(isset($eur_usd_buy_price)) { echo $eur_usd_buy_price; } ?>" class="form-control" placeholder="Price"  required/></div>
                                                    <div class="col-sm-3"><input name="eur_usd_buy_tp" type="text" value="<?php if(isset($eur_usd_buy_tp)) { echo $eur_usd_buy_tp; } ?>" class="form-control" placeholder="TP" required/></div>
                                                    <div class="col-sm-3"><input name="eur_usd_buy_sl" type="text" value="<?php if(isset($eur_usd_buy_sl)) { echo $eur_usd_buy_sl; } ?>" class="form-control" placeholder="SL" required/></div>
                                                </div>
                                            </div>
                                        </div>
<br/>
                                        <div class="form-group">

                                            <div class="col-sm-12">
                                                <div class="row">
                                                    <label class="control-label col-sm-3" for="">SELL PRICE </label>
                                                    <div class="col-sm-3"><input name="eur_usd_sell_price" type="text" value="<?php if(isset($eur_usd_sell_price)) { echo $eur_usd_sell_price; } ?>" class="form-control" placeholder="Price"  required/></div>
                                                    <div class="col-sm-3"><input name="eur_usd_sell_tp" type="text" value="<?php if(isset($eur_usd_sell_tp)) { echo $eur_usd_sell_tp; } ?>" class="form-control" placeholder="TP" required/></div>
                                                    <div class="col-sm-3"><input name="eur_usd_sell_sl" type="text" value="<?php if(isset($eur_usd_sell_sl)) { echo $eur_usd_sell_sl; } ?>" class="form-control" placeholder="SL" required/></div>
                                                </div>
                                                <hr/><br/>
                                            </div>
                                        </div>

                                        <div class="form-group">
                                            <label class="control-label col-sm-3" for="pay_date">Signal Date/Hour:</label>
                                            <div class="col-sm-9 col-lg-5">
                                                <div class='input-group date' id='datetimepicker'>
                                                    <input name="signal_date" type="text" class="form-control" id='datetimepicker2' required>
                                                    <span class="input-group-addon"><span class="glyphicon glyphicon-calendar"></span></span>
                                                </div>
                                            </div>
                                            <script type="text/javascript">
                                                $(function () {
                                                    $('#datetimepicker2').datetimepicker({
                                                        format: 'YYYY-MM-DD / h:m:s'
                                                    });
                                                });
                                            </script>
                                        </div>

                                        <div class="form-group">
                                            <center><label class="col-sm-12" for="exampleFormControlTextarea1">Comment</label></center>
                                            <textarea class="form-control" id="exampleFormControlTextarea1" rows="3"></textarea>
                                        </div>

                                        <br>
                                        <hr class="mb-4">
                                        <center><button class="btn btn-success btn-sm bottom" type="submit"> Upload Signals</button><center>
                                    </form>
                                </div>
                            </div>
                            </div>
                        </div>
</div>
                    <div class="col-md-4">
                        <div class="col-md-12 order-md-2 mb-4 section-tint super-shadow">
                            <h4 class="d-flex justify-content-between align-items-center mb-3">
                                <span class="text-muted">All signals</span>
                                <span class="badge badge-secondary badge-pill">3</span>
                            </h4>
                            <ul class="list-group mb-3">
                                <li class="list-group-item d-flex justify-content-between lh-condensed">
                                    <div>
                                        <h6 class="my-0">Product name</h6>
                                        <small class="text-muted">Brief description</small>
                                    </div>
                                    <span class="text-muted">$12</span>
                                </li>
                                <li class="list-group-item d-flex justify-content-between">
                                    <span>Total (USD)</span>
                                    <strong>$20</strong>
                                </li>
                            </ul>

                            <form>
                                <center>
                                <p>Create Currency Pair</p>
                                <div class="input-group">
                                    <input type="text" class="form-control" placeholder="Enter Currency Pair Symbol">
                                </div>
                                    <button type="submit" class="btn btn-secondary"><strong>Redeem</strong></button>
                                </center>
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