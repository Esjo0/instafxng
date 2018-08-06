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
        $pair = str_replace('/', '', $rowl['symbol']);
        $url = "https://forex.1forge.com/1.0.3/quotes?pairs=$pair&api_key=$key";
        $json = file_get_contents($url);
        $response = (array) json_decode($json, true);
        $dpips = $signal_object->get_pips($response[0]['price'], $row1['price']);
        $id = $row1['signal_id'];
        $query = "SELECT pips FROM signal_daily WHERE signal_id = '$id'";
        $result = $db_handle->runQuery($query);
        $result = $db_handle->fetchAssoc($result);
        foreach ($result as $row) {
            extract($row);
            $ppips = $pips;
        }
        if($dpips > $ppips){
            $signal_object->trigger_signal_schedule($row['signal_id'], '', '', '', '', $dpips);
        }else{
            $dpips = $ppips;
        }
////call api
//
//
//        $dec = strlen(substr(strrchr($response[0]['price'], "."), 1));
//        $diff1 = substr(strrchr($response[0]['price'], "."),1,$dec);
//
//        $diff2 = substr(strrchr($row1['price'], "."),1,$dec);
//
//       $diff = (integer)$diff1 - (integer)$diff2;
//
//        if($row1['order_type'] == 1){
//            //buy
//            if($diff < 0){$gain = "   LOSS";}else{$gain = "  PROFIT";}
//
//        }elseif($row1['order_type'] == 2){
//            //sell
//            if($diff < 0){$gain = "   PROFIT";}else{$gain = "  LOSS";}
//        }
//
//        $diff = str_replace("-", '', $diff);
//        $diff = substr($diff,$dec-2,2);
//
//       $quotes[count($quotes)] = array( symbol=>$row1['signal_id'], price=>$diff, pl=>$gain);

        $price = $response[0]['price'];
        $price = (string)$price;
        $dpips = (string)$dpips;
        $quotes[count($quotes)] = array( symbol=>$row1['signal_id'], price=>$price, pips=>$dppips);
    }
}

$result = json_encode($quotes);
echo $result;
?>
