<?php
/*header("Access-Control-Allow-Origin: *");*/
require_once("init/initialize_general.php");
$signal_object = new Signal_Management();

$date = date('Y-m-d');
$pair = $signal_object->get_scheduled_pairs($date);
$key = $signal_object->get_key();
//set api
$url = "https://forex.1forge.com/1.0.3/quotes?pairs=$pair&api_key=$key";
$myFile = "quotes.json";

//call api
$json = file_get_contents($url);
$json = json_decode($json);

    if(!empty($json)) {echo json_encode($json);}


?>
