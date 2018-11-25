<?php
set_include_path('/home/tboy9/public_html/init/');
require_once 'initialize_general.php';

$total_active_clients = $system_object->get_total_active_clients();
$total_active_accounts = $system_object->get_total_active_accounts();
$today = date('Y-m-d');

$query = "INSERT INTO active_client (clients, accounts, date) VALUES ($total_active_clients, $total_active_accounts, '$today')";
$db_handle->runQuery($query);

if($db_handle) { $db_handle->closeDB(); mysqli_close($db_handle); }