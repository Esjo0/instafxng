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

        $dec = strlen(substr(strrchr($response[0]['price'], "."), 1));
        $diff1 = substr(strrchr($response[0]['price'], "."),1,$dec);

        $diff2 = substr(strrchr($row1['price'], "."),1,$dec);

       $diff = (integer)$diff1 - (integer)$diff2;

       $diff = str_replace("-", '', $diff);

       $quotes[count($quotes)] = array( symbol=>$row1['signal_id'], price=>$diff);

    }
}

$result = json_encode($quotes);
echo $result;
?>
