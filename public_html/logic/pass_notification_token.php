<?php
/**
 * Created by PhpStorm.
 * User: user
 * Date: 22/01/2019
 * Time: 12:32 PM
 */
require_once("../init/initialize_general.php");
$token = $_GET["token"];
$type = $_GET["type"];


$query = "SELECT * FROM push_notification_token WHERE token = '$token' AND type = '$type' LIMIT 1";
$numrow = $db_handle->numRows($query);

if($numrow == 0){
    $query = "INSERT IGNORE INTO push_notification_token (token, category) VALUES('$token', '$type')";
    $result = $db_handle->runQuery($query);
}
?>
