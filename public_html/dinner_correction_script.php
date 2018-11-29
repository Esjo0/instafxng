<?php
require_once 'init/initialize_general.php';

$client_operation = new clientOperation();

$query = "SELECT * FROM dinner_2018";
$result = $db_handle->runQuery($query);
$result = $db_handle->fetchArray($result);
foreach($result AS $row){
    extract($row);
if($user_code != NULL) {
    $details = $client_operation->get_user_by_code($user_code);
    extract($details);
    $query = "UPDATE dinner_2018 SET name = '$client_full_name', email = '$client_email', phone = '$client_phone_number'
                          WHERE user_code = '$user_code'";
    $result = $db_handle->runQuery($query);
}
}
?>