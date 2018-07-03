<?php
/*header("Access-Control-Allow-Origin: *");*/
require_once("init/initialize_admin.php");
require_once("init/initialize_general.php");
// set location

//set map api url
$url = "https://forex.1forge.com/1.0.3/quotes?pairs=EURUSD,GBPJPY,AUDUSD&api_key=VvffCmdMk0g1RKjPBPqYHqAeWwIORY1r";

//call api
$json = file_get_contents($url);
$json = json_decode($json);
if(!empty($json)) {echo json_encode($json);}

?>
