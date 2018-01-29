<?php
header("Access-Control-Allow-Origin: *");
require_once("init/initialize_admin.php");
require_once("init/initialize_general.php");
$query = "SELECT symbol, order_type, price, take_profit, stop_loss, signal_date, signal_daily.created 
          FROM signal_daily, signal_symbol
          WHERE signal_daily.symbol_id = signal_symbol.symbol_id";
$result = $db_handle->runQuery($query);
$result = $db_handle->fetchAssoc($result);
echo json_encode($result);
?>
<!--<div style="margin-top: 70px" id="two" class="ui-body-d ui-content">
    <div class="w3-container">
        <?php /*foreach ($result as $row)
        {*/?>
            <div class="w3-card-4" style="width:100%">
                <div class="w3-container">
                    <img src="img/test_image.png" alt="Avatar" class="w3-left w3-round w3-margin-right" style="padding-top: 10px; height:70px;  width:70px">
                    <p><b><?php /*echo $row['symbol']*/?></b></p>
                    <small><?php /*echo datetime_to_text2($row['created'])*/?></small>
                    <hr>
                    <p><?php /*echo strtoupper($row['order_type']);*/?> AT: <?php /*echo $row['price'] */?></p>
                    <p>TAKE PROFIT AT: <?php /*echo $row['take_profit'] */?></p>
                    <p>STOP LOSS AT: <?php /*echo $row['stop_loss'] */?></p>
                </div>
            </div>
            <br/>
        <?php /*}*/?>

    </div>
</div>-->
