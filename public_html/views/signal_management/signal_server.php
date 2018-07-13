<?php
require_once '../../init/initialize_general.php';
$signal_object = new Signal_Management();
$method_name = $db_handle->sanitizePost(trim($_GET['method_name']));
$method_args = $db_handle->sanitizePost(trim($_GET['method_args']));

$response = $signal_object->{$method_name}($method_args);
if(!empty($response)){echo $response;}