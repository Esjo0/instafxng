<?php
/*header("Access-Control-Allow-Origin: *");*/
require_once("init/initialize_general.php");
$signal_object = new Signal_Management();

$date = date('Y-m-d');
$pair = $signal_object->get_scheduled_pairs($date);
$key = $signal_object->quotes_api_key();
//set api
$quotes = array();

$signals = (array) json_decode(file_get_contents('../models/signal_daily.json'));
    //if(!empty($json)) {echo $json;}
foreach ($signals as $row1) {
    $row1 = (array)$row1;
    if (!empty($row1)) {
        $pips = $signal_object->get_pips_display($row1['order_type'], $row1['pips']);
        $pips_time = datetime_to_text($row1['pips_time']);
        $pips = $pips." on ".$pips_time;
        $id = (string)$row1['signal_id'];
        $quotes[count($quotes)] = array( symbol=>$id, pips=>$pips);
    }
}

$result = json_encode($quotes);
echo $result;
?>
