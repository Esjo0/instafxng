<?php
set_include_path('../../public_html/init/');
require_once 'initialize_general.php';
$signal_object = new Signal_Management();
/*function lreplace($search, $replace, $subject){
    $pos = strrpos($subject, $search);
    if($pos !== false){
        $subject = substr_replace($subject, $replace, $pos, strlen($search));
    }
    return $subject;
}*/
$quotes = $signal_object->get_quotes_from_file();
$insert_query = "INSERT INTO signal_quotes (symbol_id, bid, ask, created) VALUES ";
foreach($quotes as $row){
    $symbol_id = $signal_object->get_symbol_id($row->symbol);
    $bid = (float)$db_handle->sanitizePost(trim($row->bid));
    $ask = (float)$db_handle->sanitizePost(trim($row->ask));
    $created = date('Y-m-d h:i:s', (int)$db_handle->sanitizePost(trim($row->timestamp)));
    $insert_query .= "($symbol_id, $bid, $ask, '$created'), ";
}
$pos = strrpos($insert_query, ',');
if($pos !== false){$insert_query = substr_replace($insert_query, '', $pos, strlen(','));}
if($db_handle->runQuery($insert_query)){
    file_put_contents('../../models/daily_quotes.json', '');
    file_put_contents('../../models/signal_daily.json', '');
}

