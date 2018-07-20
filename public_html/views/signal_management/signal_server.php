<?php
//ini_set('max_execution_time', 120); //300 seconds = 5 minutes
require_once '../../init/initialize_general.php';
$signal_object = new Signal_Management();
$method_name = $db_handle->sanitizePost(trim($_GET['method_name']));
$method_args = $db_handle->sanitizePost(trim($_GET['method_args']));

$response = $signal_object->{$method_name}($method_args);
var_dump($response);
if(!empty($response)){echo $response;}