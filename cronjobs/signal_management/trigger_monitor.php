<?php
set_include_path('../../public_html/init/');
require_once 'initialize_general.php';
$signal_object = new Signal_Management();

$scheduled_signals = (array) json_decode(file_get_contents('../../models/signal_daily.json'));
if(!empty($scheduled_signals)) {
    for ($i = 0; $i < count($scheduled_signals); $i++){
        $row = (array) $scheduled_signals[$i];
        $signal_id = $row['signal_id'];
        $symbol = str_replace('/', '', $row['symbol']);
        $url = Signal_Management::QUOTES_API."?pairs=$symbol&api_key=".$signal_object->quotes_api_key();
        $get_data = $signal_object->curl_call($url, 'GET');
        $response = (array) json_decode($get_data, true);
        if($response[0]['price'] == $row['price']){
            $signal_object->update_signal_schedule($row['symbol_id'], $row['symbol'], $row['price'], $row['take_profit'], $row['stop_loss'], $row['trigger_time'], $row['trigger_date'], $row['note'], $row['trend'], $row['order_type']);
            if($row['order_type']==1){trigger_buy_order($row);}
            if($row['order_type']==2){trigger_sell_order($row);}
        }
    }
}

function trigger_buy_order($row){
    global $signal_object;
    $symbol = str_replace('/', '', $row['symbol']);
    $url = Signal_Management::QUOTES_API."?pairs=$symbol&api_key=".$signal_object->quotes_api_key();
    $get_data = $signal_object->curl_call($url, 'GET');
    $response = (array) json_decode($get_data, true);
    if($response[0]['price'] == $row['price']){
        $entry_price = $response[0]['price'];
        $entry_time = date('Y-m-d h:i:s', $response[0]['timestamp']);
        $signal_object->trigger_signal_schedule($row['signal_id'], $row['trigger_status'], $entry_price, $entry_time, '', '');
    }
    if($response[0]['bid'] == $row['stop_loss']){
        $exit_time = date('Y-m-d h:i:s', $response[0]['timestamp']);
        $pips = (float) $row['price'] - $response[0]['bid'];
        $signal_object->trigger_signal_schedule($row['signal_id'], $row['trigger_status'], '', '', $exit_time, $pips);
    }
    if($response[0]['ask'] == $row['take_profit']){
        $exit_time = date('Y-m-d h:i:s', $response[0]['timestamp']);
        $pips = (float) $row['price'] - $response[0]['ask'];
        $signal_object->trigger_signal_schedule($row['signal_id'], $row['trigger_status'], '', '', $exit_time, $pips);
    }
}

function trigger_sell_order($row){
    global $signal_object;
    $symbol = str_replace('/', '', $row['symbol']);
    $url = Signal_Management::QUOTES_API."?pairs=$symbol&api_key=".$signal_object->quotes_api_key();
    $get_data = $signal_object->curl_call($url, 'GET');
    $response = (array) json_decode($get_data, true);
    if($response[0]['price'] == $row['price']){
        $entry_price = $response[0]['price'];
        $entry_time = date('Y-m-d h:i:s', $response[0]['timestamp']);
        $signal_object->trigger_signal_schedule($row['signal_id'], $row['trigger_status'], $entry_price, $entry_time, '', '');
    }
    if($response[0]['ask'] == $row['stop_loss']){
        $exit_time = date('Y-m-d h:i:s', $response[0]['timestamp']);
        $pips = (float) $row['price'] - $response[0]['ask'];
        $signal_object->trigger_signal_schedule($row['signal_id'], $row['trigger_status'], '', '', $exit_time, $pips);
    }
    if($response[0]['bid'] == $row['take_profit']){
        $exit_time = date('Y-m-d h:i:s', $response[0]['timestamp']);
        $pips = (float) $row['price'] - $response[0]['bid'];
        $signal_object->trigger_signal_schedule($row['signal_id'], $row['trigger_status'], '', '', $exit_time, $pips);
    }
}