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
        $pair = $row1['symbol'];
        $url = "https://forex.1forge.com/1.0.3/quotes?pairs=$pair&api_key=$key";

//call api
        $json = file_get_contents($url);
        $response = (array) json_decode($json, true);

       $diff = $row1['price'] - $response[0]['price'];
       $diff = (string)$diff;
       $quotes[count($quotes)] = array( symbol=>$row1['signal_id'], price=>$diff);
       //var_dump($quotes);

       //echo $row1['signal_id'];
        //$result = json_decode($result);
//        $result = json_encode([$result]);
//       echo $result;

    }
    //$result = array_merge($quotes,$quotes);
}

$result = json_encode($quotes);
echo $result;
?>
