<?php

//please lets stop this for now sir
set_include_path('../../public_html/init/');
require_once 'initialize_general.php';
$signal_object = new Signal_Management();
$live_quotes = $signal_object->get_live_quotes();
$old_quotes = $signal_object->get_quotes_from_file();
if(!empty($old_quotes) && is_array($old_quotes)){
    $quotes = json_encode(array_merge($old_quotes, $live_quotes));
}else{
    $quotes = json_encode($live_quotes);
}
file_put_contents('../../models/daily_quotes.json', $quotes);