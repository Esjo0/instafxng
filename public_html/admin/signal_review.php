<?php
require_once("../init/initialize_admin.php");
if (!$session_admin->is_logged_in()) {redirect_to("login.php");}

$signal_object = new Signal_Management();

if (isset($_POST['signal_report'])){
    foreach($_POST as $key => $value) {$_POST[$key] = $db_handle->sanitizePost(trim($value));}
    $from_date = $_POST['from_date'];
    $to_date = $_POST['to_date'];
}else{
    $from_date = date('Y-m')."-1";
    $to_date = date('Y-m-d');
}


$query = "SELECT SS.symbol AS pair, SD.price, SD.take_profit, SD.stop_loss, SD.created, SD.views, SD.entry_price,
SD.entry_time, SD.exit_time, SD.exit_type,SD.highest_pips, SD.lowest_pips, SD.pips, SD.trigger_status, SD.order_type, SD.exit_price, SD.note, SD.created_by, SD.market_price, SS.decimal_place
FROM signal_daily AS SD 
INNER JOIN signal_symbol AS SS ON SD.symbol_id = SS.symbol_id 
WHERE (STR_TO_DATE(trigger_date, '%Y-%m-%d') BETWEEN '$from_date' AND '$to_date')  ";

$total_Signals_Posted = $db_handle->numRows($query);
$total_Signals_triggered = $db_handle->numRows($query."AND SD.entry_price IS NOT NULL OR SD.entry_time IS NOT NULL ");
$total_Signals_triggered_tp = $db_handle->numRows($query."AND SD.exit_type = 'Take Profit' ");
$total_Signals_triggered_sl = $db_handle->numRows($query."AND SD.exit_type = 'Stop Loss' ");
$total_Signals_pending = $db_handle->numRows($query."AND SD.trigger_status = '0' ");
$total_Signals_users = $db_handle->numRows("SELECT email FROM signal_users");


$query_profit = "SELECT pips AS Total_profit FROM signal_daily WHERE trigger_date BETWEEN '$from_date' AND '$to_date' AND (exit_type = 'Take Profit')";
$result_profit = $db_handle->runQuery($query_profit);
$result_profit = $db_handle->fetchAssoc($result_profit);

$sum_of_profit = 0;
foreach($result_profit AS $row){
    extract($row);
    $Total_profit = abs($Total_profit);
    $sum_of_profit += $Total_profit;
}

$query_loss = "SELECT pips AS Total_loss FROM signal_daily WHERE trigger_date BETWEEN '$from_date' AND '$to_date' AND (exit_type = 'Stop Loss')";
$result_loss= $db_handle->runQuery($query_loss);
$result_loss = $db_handle->fetchAssoc($result_loss);
$sum_of_loss = 0;
foreach($result_loss AS $row){
    extract($row);
    $Total_loss = abs($Total_loss);
    $sum_of_loss += $Total_loss;
}

//$query = "SELECT trigger_status FROM signal_daily WHERE trigger_date BETWEEN '$from_date' AND '$to_date' AND trigger_status = '1'";
//$total_triggered = $db_handle->numRows($query);

//$query = "SELECT * FROM signal_daily WHERE trigger_date BETWEEN '$from_date' AND '$to_date' ORDER BY created DESC";
$numrows = $db_handle->numRows($query);
//$total_untriggered = $numrows - $total_triggered;
$rowsperpage = 10;
$totalpages = ceil($numrows / $rowsperpage);
if (isset($_GET['pg']) && is_numeric($_GET['pg'])) {    $currentpage = (int) $_GET['pg'];} else {    $currentpage = 1;}
if ($currentpage > $totalpages) { $currentpage = $totalpages; }
if ($currentpage < 1) { $currentpage = 1; }
$prespagelow = $currentpage * $rowsperpage - $rowsperpage + 1;
$prespagehigh = $currentpage * $rowsperpage;
if($prespagehigh > $numrows) { $prespagehigh = $numrows; }
$offset = ($currentpage - 1) * $rowsperpage;
$query .= ' ORDER BY created DESC LIMIT ' . $offset . ',' . $rowsperpage;
$result = $db_handle->runQuery($query);
$all_signals = $db_handle->fetchAssoc($result);

function table_context($trigger_status){
    switch ((int) $trigger_status){
        case 0: $msg = 'table-warning'; break;
        case 1: $msg = 'table-success'; break;
        case 2: $msg = 'table-danger'; break;
    }
    echo $msg;
}

?>
<!DOCTYPE html>
<html lang="en">
    <head>
        <base target="_self">
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>Instaforex Nigeria | Admin - Scheduled Signals</title>
        <meta name="title" content="Instaforex Nigeria | Admin - Scheduled Signals" />
        <meta name="keywords" content="" />
        <meta name="description" content="" />
        <?php require_once 'layouts/head_meta.php'; ?>
        <script type="text/javascript" src="//cdnjs.cloudflare.com/ajax/libs/mdbootstrap/4.5.0/js/mdb.min.js"></script>
        <script>
            function show_form(div) {
                var x = document.getElementById(div);
                if (x.style.display === 'none') {
                    x.style.display = 'block';
                    document.getElementById('trigger').innerHTML = '<i class="glyphicon glyphicon-arrow-up"></i>';
                } else {
                    x.style.display = 'none';
                    document.getElementById('trigger').innerHTML = '<i class="glyphicon glyphicon-arrow-down"></i>';
                }
            }
        </script>
        <link rel="stylesheet" href="../css/signal_table_context.css">
        <script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
        <script type="text/javascript">
            google.charts.load("current", {packages:["corechart"]});
            google.charts.setOnLoadCallback(drawChart);
            function drawChart() {
                var profit = <?php echo $Total_profit;?>;
                var loss =  <?php echo $Total_loss;?>;
                var data = google.visualization.arrayToDataTable([
                    ['Type', 'Pips'],
                    ['Profit', profit],
                    ['Loss',    loss]
                ]);

                var options = {
                    title: 'Pips Analysis',
                    pieHole: 0.4,
                };

                var chart = new google.visualization.PieChart(document.getElementById('donutchart'));
                chart.draw(data, options);
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
                            <h4><strong>SIGNAL'S REPORTS</strong></h4>
                        </div>
                    </div>

                    <div class="section-tint super-shadow">


                        <div class="row">
                            <div class="col-sm-12">
                                <div class="pull-right"><button type="button" data-target="#confirm-add-admin" data-toggle="modal" class="btn btn-sm btn-default"><i class="glyphicon glyphicon-search"></i> Apply Filter</button></div>

                                <!--Modal - confirmation boxes-->
                                <div id="confirm-add-admin" tabindex="-1" role="dialog" aria-hidden="true" class="modal fade">
                                    <form data-toggle="validator" class="form-horizontal" role="form" method="post" action="<?php echo $_SERVER['REQUEST_URI']; ?>">
                                        <div class="modal-dialog modal-md">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <button type="button" data-dismiss="modal" aria-hidden="true"  class="close">&times;</button>
                                                    <h4 class="modal-title">Apply Search Filter</h4></div>
                                                <div class="modal-body">
                                                    <p>Select signals posted within a date range using the form below.</p>

                                                    <div class="input-group date">
                                                        <input placeholder="Select start date" name="from_date" type="text" class="form-control" id="datetimepicker" required>
                                                        <span class="input-group-addon"><span class="glyphicon glyphicon-calendar"></span></span>
                                                    </div>

                                                    <br/>

                                                    <div class="input-group date">
                                                        <input placeholder="Select end date" name="to_date" type="text" class="form-control" id="datetimepicker2" required>
                                                        <span class="input-group-addon"><span class="glyphicon glyphicon-calendar"></span></span>
                                                    </div>
                                                    <script type="text/javascript">
                                                        $(function () {$('#datetimepicker, #datetimepicker2').datetimepicker({format: 'YYYY-MM-DD'});});
                                                    </script>
                                                </div>
                                                <div class="modal-footer">
                                                    <input name="signal_report" type="submit" class="btn btn-sm btn-success" value="Proceed">
                                                    <button type="button" name="close" onClick="window.close();" data-dismiss="modal" class="btn btn-sm btn-danger">Close!</button>
                                                </div>
                                            </div>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-sm-6">
                                <table class=" table table-responsive table-striped table-bordered table-hover">
                                        <thead>
                                            <tr><th>Total Signals Posted</th> <th><?php echo $total_Signals_Posted;?></th></tr>
                                            <tr><th>Total Triggered Signals (All)</th> <th><?php echo $total_Signals_triggered;?></th></tr>
                                            <tr><th>Total Triggered Signals (Take Profit)</th> <th><?php echo $total_Signals_triggered_tp;?></th></tr>
                                            <tr><th>Total Triggered Signals (Stop Loss)</th> <th><?php echo $total_Signals_triggered_sl;?></th></tr>
                                            <tr><th>Total Pending Signals</th> <th><?php echo $total_Signals_pending;?></th></tr>
                                            <tr><th>Total Signal Users</th> <th><?php echo $total_Signals_users;?></th></tr>
                                        </thead>
                                    </table>
                            </div>
                            <div class="col-sm-6">
                                <div id="donutchart" ></div>
                                <p class="text-center">Total Profit : <span style="color:green !important;"><?php echo $sum_of_profit;?></span>pips || Total Loss : <span style="color:red !important;">-<?php echo $sum_of_loss;?></span>pips</p>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-sm-12">
                                <p><b>Signals history from <?php echo date_to_text($to_date)?> to <?php echo date_to_text($from_date)?>.</b></p>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-sm-12">
                                <?php if(isset($all_signals) && !empty($all_signals)){?>
                                <?php foreach ($all_signals as $row) {?>
                                <table  class="table table-responsive table-striped table-bordered table-hover">
                                    <thead>
                                    <tr>
                                        <td rowspan="2" class="<?php table_context($row['trigger_status']) ?>"> <p style="font-size: xx-large">
                                                <?php if($row['order_type'] == 1){echo "<b style='font-size: large' class='text-success'><i class='glyphicon glyphicon-arrow-up'></i></b>";}
                                                if($row['order_type'] == 2){echo "<b style='font-size: large' class='text-danger'><i class='glyphicon glyphicon-arrow-down'></i></b>";}
                                                ?></p></td>
                                        <td>
                                            <span><b>Currency Pair:</b> <?php echo $row['pair']; ?></span><br/>
                                            <span><b>Price:</b> <?php echo $signal_object->round_price_to_dp($row['price'], $row['decimal_place']); ?></span><br/>
                                            <span><b>Take Profit:</b> <?php echo $signal_object->round_price_to_dp($row['take_profit'], $row['decimal_place']); ?></span><br/>
                                            <span><b>Stop Loss:</b> <?php echo $signal_object->round_price_to_dp($row['stop_loss'], $row['decimal_place']); ?></span><br/>
                                            <span><b>Date Created:</b> <?php echo datetime_to_text($row['created']); ?></span><br/>
											<span><b>Keynote:</b> <?php echo $row['note']; ?><br/>
                                            <span><b>Created By:</b> <?php echo $admin_object->get_admin_name_by_code($row['created_by']);; ?>
                                        </td>
                                        <td>
                                            <span><b>Market Price when order was place:</b> <?php echo $row['market_price']; ?></span></br>
                                            <span><b>Entry Price:</b> <?php if(!empty($row['entry_price'])){echo $signal_object->round_price_to_dp($row['entry_price'], $row['decimal_place']);}else{echo $row['market_price']; } ?></span><br/>
                                            <span><b>Entry Time:</b> <?php if(!empty($row['entry_time'])){echo datetime_to_text($row['entry_time']);}else{echo datetime_to_text($row['created']);} ?></span><br/>
                                            <span><b>Exit Time:</b> <?php if(!empty($row['exit_time'])){echo datetime_to_text($row['exit_time']);} ?></span><br/>
                                            <span><b>Highest Pips:</b><span style="color:green !important;"> <?php echo $row['highest_pips']; ?></span>pips</span><br/>
                                            <span><b>Pips:</b> <?php echo $signal_object->get_pips_display($row['order_type'],$row['pips']); ?></span><br/>
                                            <span><b>Lowest Pips:</b> <span style="color:red !important;"><?php echo $row['lowest_pips']; ?></span>pips</span><br/>
                                            <span><b>Exit Type:</b> <?php echo $row['exit_type']; ?></span><br/>
											<span><b>Exit Price:</b> <?php echo $signal_object->round_price_to_dp($row['exit_price'], $row['decimal_place']); ?></span>
                                        </td>
                                    </tr>
                                    <tr class="table-warning">
                                        <td><b>Views:</b> <?php echo number_format($row['views']); ?></td>
                                        <td><b>Trigger Status:</b> <?php echo $signal_object->UI_get_signal_trigger_status_msg($row['trigger_status']); ?></td>
                                    </tr>
                                    </thead>
                                </table>
                                    <?php }} else { echo "<table><tr><td ><em>No results to display...</em></td></tr></table>"; } ?>
                                <?php if(isset($all_signals) && !empty($all_signals)) { ?>
                                    <div class="tool-footer text-right">
                                        <p class="pull-left">Showing <?php echo $prespagelow . " to " . $prespagehigh . " of " . $numrows; ?> entries</p>
                                    </div>
                                <?php } ?>
                            </div>
                        </div>
                        <?php require 'layouts/pagination_links.php'; ?>
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