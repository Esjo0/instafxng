<?php
require_once("init/initialize_admin.php");
require_once("init/initialize_general.php");
$query = "SELECT symbol, order_type, price, take_profit, stop_loss, signal_date, signal_daily.created 
          FROM signal_daily, signal_symbol
          WHERE signal_daily.symbol_id = signal_symbol.signal_symbol_id";
$result = $db_handle->runQuery($query);
$result = $db_handle->fetchAssoc($result);
$result = json_encode($result);
echo $result;