<?php
/*header("Access-Control-Allow-Origin: *");*/
require_once("init/initialize_admin.php");
require_once("init/initialize_general.php");

$signal_object = new Signal_Management();
/*
 * Pips correction script please kindly run once
 */
$query = "SELECT symbol_id, price, exit_price FROM signal_daily WHERE trigger_date = '2018-08-09' AND trigger_status = '2'";
$result = $db_handle->fetchAssoc($db_handle->runQuery($query));
foreach($result AS $row){
    $pips = $signal_object->get_pips($row['symbol_id'],$row['price'],$row['exit_price']);
    $query = "UPDATE signal_daily SET pips = 'pips'";
    $result = $db_handle->runQuery($query);
    if($result){
        echo "Thank You";
    }else{
        echo "Not Successful";
    }
}
?>

