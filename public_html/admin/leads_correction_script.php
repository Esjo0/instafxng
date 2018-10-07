<?php
/**
 * Created by PhpStorm.
 * User: user
 * Date: 07/10/2018
 * Time: 12:46 AM
 */

require_once("../init/initialize_admin.php");

$query = "SELECT phone FROM user WHERE phone LIKE '%p+%' ";
$result = $db_handle->runQuery($query);
$phone_nos = $db_handle->fetchArray($result);
foreach($phone_nos AS $row){
    extract($row);
    $phone_new = strtolower(trim(str_replace('p+', '+', $phone))) ;
    $query = "UPDATE user SET phone = $phone_new WHERE phone = '$phone'";
    $result = $db_handle->runQuery($query);
}


$query = "SELECT phone FROM campaign_leads WHERE phone LIKE '%p+%' ";
$result = $db_handle->runQuery($query);
$phone_nos = $db_handle->fetchArray($result);
foreach($phone_nos AS $row){
    extract($row);
    $phone_new = strtolower(trim(str_replace('p+', '+', $phone))) ;
    $query = "UPDATE user SET phone = $phone_new WHERE phone = '$phone'";
    $result = $db_handle->runQuery($query);
}

?>