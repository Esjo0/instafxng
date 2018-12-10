<?php
require_once 'init/initialize_general.php';

$client_operation = new clientOperation();

$query = "SELECT * FROM dinner_2018";
$result = $db_handle->runQuery($query);
$result = $db_handle->fetchArray($result);
foreach($result AS $row){
    extract($row);
if($choice == '1') {
if($type == 1){$ticket = 'SINGLE';}
else if($type == 2){$ticket = 'DOUBLE';}
else if($type == 3){$ticket = 'VIP';}
else if($type == 4){$ticket = 'VVIP';}
else if($type == 5){$ticket = 'TEAM';}
else if($type == 6){$ticket = 'VENDOR';}
    $invite_code = $id.$title[0].$gender.$town[0].$state[0]."~".$ticket;
    $query = "UPDATE dinner_2018 SET invite_code = '$invite_code'
                          WHERE email = '$email'";
    $result = $db_handle->runQuery($query);
}
}?>
