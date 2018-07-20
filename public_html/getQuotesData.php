<?php
/*header("Access-Control-Allow-Origin: *");*/
require_once 'init/initialize_general.php';
$signal_object = new Signal_Management();
echo $signal_object->UI_get_signals_for_page();
?>
