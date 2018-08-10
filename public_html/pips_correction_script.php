<?php
/*header("Access-Control-Allow-Origin: *");*/
require_once("init/initialize_admin.php");
require_once("init/initialize_general.php");

$signal_object = new Signal_Management();
/*
 * Pips correction script please kindly run once
 */
$query = "SELECT signal_id, symbol_id, price, exit_price FROM signal_daily";
$result = $db_handle->fetchAssoc($db_handle->runQuery($query));
foreach($result AS $row){
    if(!empty($row['exit_price'])){
    $pips = $signal_object->get_pips($row['symbol_id'],$row['exit_price'],$row['price']);
    }else{$pips = 0;}
    $id = $row['signal_id'];
    $query = "UPDATE signal_daily SET pips = '$pips' WHERE signal_id = '$id'";
    $result = $db_handle->runQuery($query);
    if($result){
        echo "Thank You";
    }else{
        echo "Not Successful";
    }
}
?>

