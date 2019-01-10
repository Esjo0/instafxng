<?php
set_include_path('/home/tboy9/public_html/init/');
require_once 'initialize_general.php';
$signal_object = new Signal_Management();

$quotes = $signal_object->get_quotes_from_file();
$insert_query = "INSERT INTO signal_quotes (symbol_id, bid, ask, created) VALUES ";
foreach ($quotes as $row) {
    $symbol_id = $signal_object->get_symbol_id($row->symbol);
    $bid = (float)$db_handle->sanitizePost(trim($row->bid));
    $ask = (float)$db_handle->sanitizePost(trim($row->ask));
    $created = date('Y-m-d h:i:s', (int)$db_handle->sanitizePost(trim($row->timestamp)));
    $insert_query .= "($symbol_id, $bid, $ask, '$created'), ";
}
$pos = strrpos($insert_query, ',');
if ($pos !== false) {
    $insert_query = substr_replace($insert_query, '', $pos, strlen(','));
}
if ($db_handle->runQuery($insert_query)) {
    file_put_contents('/home/tboy9/models/daily_quotes.json', '');
}
file_put_contents('/home/tboy9/models/signal_daily.json', '');

if($db_handle) { $db_handle->closeDB(); mysqli_close($db_handle); }