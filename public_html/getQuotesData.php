<?php
/*header("Access-Control-Allow-Origin: *");*/
require_once("init/initialize_admin.php");
require_once("init/initialize_general.php");
// set location

//set map api url
$url = "https://forex.1forge.com/1.0.3/quotes?pairs=EURUSD,GBPJPY,AUDUSD&api_key=VvffCmdMk0g1RKjPBPqYHqAeWwIORY1r";
$myFile = "quotes.json";

//call api
$json = file_get_contents($url);
$json = json_decode($json);

//Get data from existing json file
$jsondata = file_get_contents($myFile);

// converts json data into array
$arr_data = json_decode($jsondata, true);

// Push user data to array
array_push($arr_data,$json);

//Convert updated array to JSON
$jsondata = json_encode($arr_data, JSON_PRETTY_PRINT);

//write json data into data.json file
if(file_put_contents($myFile, $jsondata)) {
    if(!empty($json)) {echo json_encode($json);}
}
?>
