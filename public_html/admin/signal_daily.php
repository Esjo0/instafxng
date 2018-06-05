<?php
require_once("../init/initialize_admin.php");
if (!$session_admin->is_logged_in()) {
    redirect_to("login.php");
}
$today = date('Y-m-d');
$query = "SELECT * FROM signal_daily WHERE trigger_date = '$today'";
$todays_signals = $db_handle->numRows($query);

$query = "SELECT SUM(views) AS Total FROM signal_daily WHERE trigger_date = '$today'";
$result_view = $db_handle->runQuery($query);

if(isset($_POST['trigger'])){
    $id = $db_handle->sanitizePost($_POST['id']);
    $query = "UPDATE signal_intraday SET status = '1' WHERE id = '$id'";
    $result =$db_handle->runQuery($query);
    if($result) {
        $message_success = "Signal Triggered Successfully created for ".datetime_to_text($signal_time);
    } else {
        $message_error = "Something went wrong. Please try again.";
    }
}

if(isset($_POST['create_symbol'])){
   $pair = $db_handle->sanitizePost($_POST['pair']);
   $query = "SELECT * FROM signal_symbol WHERE symbol = '$pair'";
   $result = $db_handle->numRows($query);
   if($result == 0){
   $query = "INSERT INTO signal_symbol (symbol) VALUE('$pair')";
   $result2 =$db_handle->runQuery($query);
    if($result2) {
        $message_success = "You have successfully created a new Currency Symbol";
    } else {
        $message_error = "Something went wrong. Please try again.";
    }
    }else{
       $message_error = "Something went wrong. Currency Pair (".$pair.") Already exist.";
   }
}

if(isset($_POST['new_signal'])){
	$options = $db_handle->sanitizePost($_POST['options']);
    $symbol = $db_handle->sanitizePost($_POST['symbol']);
    $buy_price = $db_handle->sanitizePost($_POST['buy_price']);
    $buy_price_tp = $db_handle->sanitizePost($_POST['buy_price_tp']);
    $buy_price_sl = $db_handle->sanitizePost($_POST['buy_price_sl']);
    $sell_price = $db_handle->sanitizePost($_POST['sell_price']);
    $sell_price_tp = $db_handle->sanitizePost($_POST['sell_price_tp']);
    $sell_price_sl = $db_handle->sanitizePost($_POST['sell_price_sl']);
    $signal_time = $db_handle->sanitizePost($_POST['signal_time']);
	$signal_date = $db_handle->sanitizePost($_POST['signal_date']);
    $comment = $db_handle->sanitizePost($_POST['comment']);
	$trend = $db_handle->sanitizePost($_POST['trend']);
	
	if(($options == 1) || ($options == 3)){
    $query ="INSERT INTO signal_daily(symbol_id, order_type, price, take_profit, stop_loss, trigger_date, trigger_time, note, trend, views)
              VALUE('$symbol','1','$buy_price', '$buy_price_tp', '$buy_price_sl', '$signal_date', '$signal_time', '$comment', '$trend', '0')";

	}
	if(($options == 2) || ($options == 3)){
		$query2 ="INSERT INTO signal_daily(symbol_id, order_type, price, take_profit, stop_loss, trigger_date, trigger_time, note, trend, views)
              VALUE('$symbol','2' , '$sell_price', '$sell_price_tp', '$sell_price_sl', '$signal_date', '$signal_time', '$comment', '$trend', '0')";
	}	  
    $result =$db_handle->runQuery($query);
	$result2 =$db_handle->runQuery($query2);
    if($result || $result2) {
        $message_success = "Signal Successfully created for ".datetime_to_text($signal_time);
    } else {
        $message_error = "Something went wrong. Please try again.";
    }

}

if(isset($_POST['update_signal'])){
    $id = $db_handle->sanitizePost($_POST['id']);
    $symbol = $db_handle->sanitizePost($_POST['symbol']);
    $price = $db_handle->sanitizePost($_POST['price']);
    $take_profit = $db_handle->sanitizePost($_POST['take_profit']);
    $stop_loss = $db_handle->sanitizePost($_POST['stop_loss']);
    $signal_time = $db_handle->sanitizePost($_POST['signal_time']);
    $signal_date = $db_handle->sanitizePost($_POST['signal_date']);
    $comment = $db_handle->sanitizePost($_POST['comment']);
    $trend = $db_handle->sanitizePost($_POST['trend']);
    $type = $db_handle->sanitizePost($_POST['type']);

    $query = "UPDATE signal_daily SET symbol_id = '$symbol', order_type = '$type', price = '$price', take_profit = '$take_profit', stop_loss = '$stop_loss', trigger_date = '$signal_date', trigger_time = '$signal_time', note = '$comment', trend = '$trend' WHERE signal_id = '$id'";
    $result =$db_handle->runQuery($query);
    if($result) {
        $message_success = "Signal Updated Successfully created for ".datetime_to_text($signal_time);
    } else {
        $message_error = "Something went wrong. Please try again.";
    }

}

$query = "SELECT * FROM signal_daily WHERE trigger_date = '$today' ORDER BY trigger_time DESC";
$numrows = $db_handle->numRows($query);
$rowsperpage = 5;
$totalpages = ceil($numrows / $rowsperpage);
if (isset($_GET['pg']) && is_numeric($_GET['pg'])) {    $currentpage = (int) $_GET['pg'];} else {    $currentpage = 1;}
if ($currentpage > $totalpages) { $currentpage = $totalpages; }
if ($currentpage < 1) { $currentpage = 1; }
$prespagelow = $currentpage * $rowsperpage - $rowsperpage + 1;
$prespagehigh = $currentpage * $rowsperpage;
if($prespagehigh > $numrows) { $prespagehigh = $numrows; }
$offset = ($currentpage - 1) * $rowsperpage;
$query .= ' '.'LIMIT ' . $offset . ',' . $rowsperpage;
$result = $db_handle->runQuery($query);
$all_signals = $db_handle->fetchAssoc($result);
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
        <script>
            function displayOptions()
            {
                var options = $("#options").val();
                if(options == 1)
                {
                    document.getElementById("buy").style.display = "block";
                    document.getElementById("sell").style.display = "none";
                    document.getElementById("sell_price").required = false;
                    document.getElementById("sell_price_tp").required = false;
                    document.getElementById("sell_price_sl").required = false;
                }
                else if(options == 2)
                {
                    document.getElementById("sell").style.display = "block";
                    document.getElementById("buy").style.display = "none";
                    document.getElementById("buy_price").required = false;
                    document.getElementById("buy_price_tp").required = false;
                    document.getElementById("buy_price_sl").required = false;
                }
                else if(options == 3)
                {
                    document.getElementById("buy").style.display = "block";
                    document.getElementById("sell").style.display = "block";
                }
                else
                {
                    document.getElementById("buy").style.display = "none";
                    document.getElementById("sell").style.display = "none";
                }
            }
        </script>
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
                            <h4><strong>UPLOAD SIGNALS</strong></h4>
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
                                                <form class="needs-validation" role="form" method="post" action="">
                                                    <div class="form-group row">
                                                        <label class="control-label col-sm-3" for="location">Currency Pair </label>
                                                        <div class="col-sm-9 col-lg-5">
                                                            <div class="input-group date">
                                                                <select name="symbol" class="form-control" id="location">
                                                                    <?php
                                                                    $query = "SELECT * FROM signal_symbol ";
                                                                    $result = $db_handle->runQuery($query);
                                                                    $result = $db_handle->fetchAssoc($result);
                                                                    foreach ($result as $row)
                                                                    {
                                                                        extract($row)
                                                                        ?>
                                                                        <option value="<?php echo $symbol_id;?>"><?php echo $symbol;?></option>
                                                                    <?php } ?>
                                                                </select>
                                                                <span class="input-group-addon"><span class="fa fa-gg"></span></span>
                                                            </div>
                                                        </div>
                                                    </div>

                                                    <div class="form-group row">
                                                        <label class="control-label col-sm-3" for="location">Options</label>
                                                        <div class="col-sm-9 col-lg-5">
                                                            <div class="input-group date">
                                                                <select name="options" id="options" class="form-control" onchange="displayOptions()">
                                                                    <option value=""></option>
                                                                    <option value="1">Buy</option>
                                                                    <option value="2">Sell</option>
                                                                    <option value="3">Buy - Sell</option>
                                                                </select>
                                                                <span class="input-group-addon"><span class="fa fa-signal"></span></span>
                                                            </div>
                                                        </div>
                                                    </div>

                                                    <hr/>
                                                    <div class="form-group row" style="display:none;" id="buy">
                                                        <div class="col-sm-12">
                                                            <div class="row">
                                                                <label class="control-label col-sm-3" for="">BUY PRICE </label>
                                                                <div class="col-sm-3"><input id="buy_price" name="buy_price" type="text" class="form-control" placeholder="Price"  required/></div>
                                                                <div class="col-sm-3"><input id="buy_price_tp" name="buy_price_tp" type="text" class="form-control" placeholder="TP" required/></div>
                                                                <div class="col-sm-3"><input id="buy_price_sl" name="buy_price_sl" type="text" class="form-control" placeholder="SL" required/></div>
                                                            </div>
                                                        </div>
                                                    </div>
            <br/>
                                                    <div class="form-group row" style="display:none;" id="sell">

                                                        <div class="col-sm-12" >
                                                            <div class="row">
                                                                <label class="control-label col-sm-3" for="">SELL PRICE </label>
                                                                <div class="col-sm-3"><input id="sell_price" name="sell_price" type="text" value="<?php if(isset($eur_usd_sell_price)) { echo $eur_usd_sell_price; } ?>" class="form-control" placeholder="Price"  required/></div>
                                                                <div class="col-sm-3"><input id="sell_price_tp" name="sell_price_tp" type="text" value="<?php if(isset($eur_usd_sell_tp)) { echo $eur_usd_sell_tp; } ?>" class="form-control" placeholder="TP" required/></div>
                                                                <div class="col-sm-3"><input id="sell_price_sl" name="sell_price_sl" type="text" value="<?php if(isset($eur_usd_sell_sl)) { echo $eur_usd_sell_sl; } ?>" class="form-control" placeholder="SL" required/></div>
                                                            </div>
                                                            <hr/><br/>
                                                        </div>
                                                    </div>

                                                    <div class="form-group row">
                                                        <label class="control-label col-sm-3" for="pay_date">Signal Date:</label>
                                                        <div class="col-sm-9 col-lg-5">
                                                            <div class='input-group date' id='datetimepicker'>
                                                                <input name="signal_date" type="text" class="form-control" id='datetimepicker2' required>
                                                                <span class="input-group-addon"><span class="glyphicon glyphicon-calendar"></span></span>
                                                            </div>
                                                        </div>
                                                        <script type="text/javascript">
                                                            $(function () {
                                                                $('#datetimepicker2').datetimepicker({
                                                                    format: 'YYYY-MM-DD'
                                                                });
                                                            });
                                                        </script>
                                                    </div>

                                                    <div class="form-group row">
                                                        <label class="control-label col-sm-3" for="pay_date">Signal Time:</label>
                                                        <div class="col-sm-9 col-lg-5">
                                                            <div class='input-group date' id='datetimepicker'>
                                                                <input name="signal_time" type="text" class="form-control" id='datetimepicker3' required>
                                                                <span class="input-group-addon"><span class="glyphicon glyphicon-calendar"></span></span>
                                                            </div>
                                                        </div>
                                                        <script type="text/javascript">
                                                            $(function () {
                                                                $('#datetimepicker3').datetimepicker({
                                                                    format: 'HH:mm:ss'
                                                                });
                                                            });
                                                        </script>
                                                    </div>

                                                    <div class="form-group row">
                                                        <label class="control-label col-sm-3" for="location">Trend</label>
                                                        <div class="col-sm-9 col-lg-5">
                                                            <div class="input-group date">
                                                                <select name="trend" id="trend" class="form-control" >
                                                                    <option value=""></option>
                                                                    <option value="0">Buying(Bullish)</option>
                                                                    <option value="1">Selling(Bearish)</option>
                                                                </select>
                                                                <span class="input-group-addon"><span class="fa fa-arrows-v"></span></span>
                                                            </div>
                                                        </div>
                                                    </div>

                                                    <div class="form-group row">
                                                        <textarea name="comment" class="form-control" id="exampleFormControlTextarea1" rows="3" placeholder="Enter Comments"></textarea>
                                                    </div>

                                                    <br>
                                                    <hr class="mb-4">
                                                    <center><button name="new_signal" class="btn btn-success btn-sm bottom" type="submit"> Upload Signals</button><center>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="col-md-12 order-md-2 mb-4 section-tint super-shadow">
                                    <h4 class="d-flex justify-content-between align-items-center mb-3">
                                        <span class="text-muted">Todays signals</span>
                                        <span class="badge badge-secondary badge-pill"><?php echo $todays_signals?></span>
                                    </h4>
                                    <ul class="list-group mb-3">
                                        <?php foreach ($all_signals as $row) {?>
                                        <li class="list-group-item d-flex justify-content-between lh-condensed" data-toggle="modal" data-target="#editsignal<?php echo $row['signal_id']?>">
                                            <div>
                                                <h6 class="my-0 pull-right"><i class="fa fa-eye fa-fw"></i> <?php echo $row['views']?></h6>
                                                <h6 class="my-0"><?php echo datetime_to_text($row['trigger_date']." ".$row['trigger_time']); ?></h6>

                                                <small class="text-muted">
                                                    <?php
                                                    $check_id = $row['symbol_id'];
                                                    $query = "SELECT symbol AS Currency_Pair FROM signal_symbol WHERE symbol_id = $check_id";
                                                    $result = $db_handle->runQuery($query);
                                                    $result = $db_handle->fetchAssoc($result);
                                                    foreach ($result as $row4)
                                                    {
                                                        extract($row4);
                                                        echo $Currency_Pair;
                                                    } ?>
                                                </small>

                                            </div>
                                            <span class="text-muted">PRICE: <?php echo $row['price'];?>
                                                <?php
                                                if($row['order_type'] == 2)
                                                {
                                                    echo" <b class='text-danger'><i class='glyphicon glyphicon-arrow-down'></i></b>";
                                                }
										        elseif($row['order_type'] == 1)
                                                {
                                                    echo"<b class='text-success'><i class='glyphicon glyphicon-arrow-up'></i></b>";
                                                }
                                                ?>
                                            </span>
                                        </li>
                                        <div class="modal fade" id="editsignal<?php echo $row['signal_id']?>" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                            <div class="modal-dialog" role="document">
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <h5 class="modal-title" id="exampleModalLabel">Signal Details</h5>
                                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                            <span aria-hidden="true">&times;</span>
                                                        </button>
                                                    </div>
                                                    <div class="modal-body">
                                                        <form  role="form" method="post" action="">
                                                            <div class="form-group row">
                                                                <label class="control-label col-sm-3" for="location">Currency Pair </label>
                                                                <div class="col-sm-9 col-lg-5">
                                                                    <div class="input-group date">
                                                                        <select name="symbol" class="form-control" id="location">
                                                                            <?php
                                                                            $query = "SELECT * FROM signal_symbol ";
                                                                            $result = $db_handle->runQuery($query);
                                                                            $result = $db_handle->fetchAssoc($result);
                                                                            foreach ($result as $row3)
                                                                            {
                                                                                extract($row3)
                                                                                ?>
                                                                                <option value="<?php echo $symbol_id;?>"<?php if($symbol_id == $row['symbol_id']){echo "selected";}?>><?php echo $symbol;?></option>
                                                                            <?php } ?>
                                                                        </select>
                                                                        <span class="input-group-addon"><span class="fa fa-gg"></span></span>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        <div class="form-group row">
                                                            <label class="control-label col-sm-3" for="location">Option</label>
                                                            <div class="col-sm-9 col-lg-5">
                                                                <div class="input-group date">
                                                                    <select name="type" id="trend" class="form-control" >
                                                                        <option value="1" <?php if($row['order_type'] == 1){echo "selected";}?>>Buying</option>
                                                                        <option value="2" <?php if($row['order_type'] == 2){echo "selected";}?>>Selling</option>
                                                                    </select>
                                                                    <span class="input-group-addon"><span class="fa fa-signal"></span></span>
                                                                </div>                                                              </div>
                                                        </div>
                                                            <br>
                                                            <hr/>
                                                        <div class="form-group row">

                                                                <div class="col-sm-12">
                                                                    <div class="row">

                                                                        <div class="col-sm-4">
                                                                            <label class="control-label" for="">PRICE</label>
                                                                            <input name="price" type="text" value="<?php echo $row['price'];?>" class="form-control"   required/></div>
                                                                        <div class="col-sm-4">
                                                                            <label class="control-label" for="">Take Profit</label>
                                                                            <input name="take_profit" type="text" value="<?php echo $row['take_profit'];?>" class="form-control"  required/></div>
                                                                        <div class="col-sm-4">
                                                                            <label class="control-label" for="">Stop Loss</label>
                                                                            <input name="stop_loss" type="text" value="<?php echo $row['stop_loss'];?>" class="form-control"  required/></div>

                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <hr/>
                                                            <br>
                                                            <div class="form-group row">
                                            <label class="control-label col-sm-3" for="pay_date">Signal Date:</label>
                                            <div class="col-sm-9 col-lg-5">
                                                <div class='input-group date' id='datetimepicker'>
                                                    <input value="<?php echo $row['trigger_date'];?>" name="signal_date" type="text" class="form-control" id='datetimepickerd<?php echo $row['signal_id']?>' required>
                                                    <span class="input-group-addon"><span class="glyphicon glyphicon-calendar"></span></span>
                                                </div>
                                            </div>
                                            <script type="text/javascript">
                                                $(function () {
                                                    $('#datetimepickerd<?php echo $row['signal_id']?>').datetimepicker({
                                                        format: 'YYYY-MM-DD'
                                                    });
                                                });
                                            </script>
                                        </div>
										
										<div class="form-group row">
                                            <label class="control-label col-sm-3" for="pay_date">Signal Time:</label>
                                            <div class="col-sm-9 col-lg-5">
                                                <div class='input-group date' id='datetimepicker'>
                                                    <input value="<?php echo $row['trigger_time'];?>" name="signal_time" type="text" class="form-control" id='datetimepickert<?php echo $row['signal_id']?>' required>
                                                    <span class="input-group-addon"><span class="glyphicon glyphicon-clock"></span></span>
                                                </div>
                                            </div>
                                            <script type="text/javascript">
                                                $(function () {
                                                    $('#datetimepickert<?php echo $row['signal_id']?>').datetimepicker({
                                                        format: 'HH:mm:ss'
                                                    });
                                                });
                                            </script>
                                        </div>
										
															<div class="form-group row">
                                                                <label class="control-label col-sm-3" for="location">Trend</label>
                                                                <div class="col-sm-9 col-lg-5">
																		<div class="input-group date">
                                                    <select name="trend" id="trend" class="form-control" >
                                                        <option value="0" <?php if($row['trend'] == 0){echo "selected";}?>>Buying(Bullish)</option>
                                                        <option value="1" <?php if($row['trend'] == 1){echo "selected";}?>>Selling(Bearish)</option>
                                                    </select>
                                                    <span class="input-group-addon"><span class="fa fa-arrows-v"></span></span>
                                                </div>                                                              </div>
                                                            </div>

                                                        <div class="form-group">
                                                                <center><label class="col-sm-12" for="exampleFormControlTextarea1">Comment</label></center>
                                                                <textarea name="comment" class="form-control" id="exampleFormControlTextarea1" rows="3">
                                                                    <?php echo $row['note'];?>
                                                                </textarea>
                                                            </div>
                                                        <input name="id" type="hidden" value="<?php echo $row['signal_id'];?>"/>

                                                        <br>
                                                            <hr class="mb-4">
                                                            <center><button name="update_signal" class="btn btn-success btn-sm bottom" type="submit">Submit Changes</button><center>
                                                        </form>
                                                </div>
                                                <div class="modal-footer">
                                                    <center><?php if($row['status'] == 0){?><form method="post" action=""><input name="id" type="hidden" value="<?php echo $row['id'];?>"><button name="trigger" type="submit" class="btn btn-success">Trigger</button></form><?php }?></center>

                                                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>

                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                <?php } ?>
                                <?php require 'layouts/pagination_links.php'; ?>
                                <!--<li class="list-group-item d-flex justify-content-between">
                                    <span>Total Signals</span>
                                    <strong><?php /*echo $numrows;*/?></strong>
                                </li>
                              
                                <li class="list-group-item d-flex justify-content-between">
                                    <span>Total Overall Signals Views</span>
                                    <strong><i class="fa fa-eye fa-fw"></i>
									<?php /*if(isset($result_view)){ foreach($result_view AS $view){
									echo $view['Total'];
									}}*/?></strong>
                                </li>-->
                            </ul>

                            <form role="form" method="post" action="">
                                <center>
                                <p>Create Currency Pair</p>
                                <div class="input-group">
                                    <input name="pair" type="text" class="form-control" placeholder="Enter Currency Pair Symbol" required>
                                </div>
                                    <button name="create_symbol" type="submit" class="btn btn-secondary"><strong>Create</strong></button>
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

