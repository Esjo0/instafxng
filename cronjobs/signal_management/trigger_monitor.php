<?php
set_include_path('/home/tboy9/public_html/init/');
//set_include_path('../../public_html/init/');
require_once 'initialize_general.php';
$signal_object = new Signal_Management();

//$scheduled_signals = (array)json_decode(file_get_contents('/home/tboy9/models/signal_daily.json'));
$scheduled_signals = (array)json_decode(file_get_contents('../../models/signal_daily.json'));

if (!empty($scheduled_signals)) {
    for ($i = 0; $i < count($scheduled_signals); $i++) {
        $row = (array)$scheduled_signals[$i];
        $signal_id = $row['signal_id'];
        $symbol = str_replace('/', '', $row['symbol']);
        $url = Signal_Management::QUOTES_API . "?pairs=$symbol&api_key=" . $signal_object->quotes_api_key();
        $get_data = file_get_contents($url);
        $response = (array)json_decode($get_data, true);
		$entry_price = $response[0]['price'];
        //Trigger Sell Order
        if (($response[0]['price'] <= $row['price']) && ($row['trigger_status'] != 2) && ($row['order_type'] == 2) && !empty($response[0][price]) && ($response[0][price] != 0)) {
            if ($row['trigger_status'] != 1 && ($response[0]['price'] >= ($row['price'] + 0.0020))) {
                $entry_price = $response[0]['price'];
                $entry_time = date('Y-m-d h:i:s');
                $signal_object->trigger_signal_schedule($signal_id, 1, $entry_price, $entry_time, '', '', '', '');
                $pips = $signal_object->get_pips($response[0]['price'], $row['price']);
                if($pips > $row['pips']){
                        $signal_object->trigger_signal_schedule($row['signal_id'], '', '', '', '', $pips, '', '');
                    }
                if ($row['order_type'] == 2) {
                    trigger_sell_order($row);
                }
            } else {
                $pips = $signal_object->get_pips($response[0]['price'], $row['price']);
                if($pips > $row['pips']){
                    $signal_object->trigger_signal_schedule($row['signal_id'], '', '', '', '', $pips, '', '');
                }
                if ($row['order_type'] == 2) {
                    trigger_sell_order($row);
                }
            }
        }

        //Trigger Buy Order
        if (($response[0]['price'] >= $row['price']) && ($row['trigger_status'] != 2) && ($row['order_type'] == 1) && !empty($response[0][price])) {
            if ($row['trigger_status'] != 1 && ($response[0]['price'] <= ($row['price'] + 0.0020))) {
                $entry_price = $response[0]['price'];
                $entry_time = date('Y-m-d h:i:s');
                $signal_object->trigger_signal_schedule($signal_id, 1, $entry_price, $entry_time, '', '', '', '');

                $pips = $signal_object->get_pips($response[0]['price'], $row['price']);
                if($pips > $row['pips']){
                    $signal_object->trigger_signal_schedule($row['signal_id'], '', '', '', '', $pips, '', '');
                }

                if ($row['order_type'] == 1) {
                    trigger_buy_order($row);
                }
            } else {
                $pips = $signal_object->get_pips($response[0]['price'], $row['price']);
                if($pips > $row['pips']){
                    $signal_object->trigger_signal_schedule($row['signal_id'], '', '', '', '', $pips, '', '');
                }
                if ($row['order_type'] == 1) {
                    trigger_buy_order($row);
                }
            }
        }
    }
}

// Close Sell Order
function trigger_sell_order($row)
{
    global $signal_object;
    $symbol = str_replace('/', '', $row['symbol']);
    $url = Signal_Management::QUOTES_API . "?pairs=$symbol&api_key=" . $signal_object->quotes_api_key();
    $get_data = file_get_contents($url);
    $response = (array)json_decode($get_data, true);
    if (($response[0]['price'] <= $row['take_profit']) && !empty($response[0][price])) {
        $exit_time = date('Y-m-d h:i:s');
		$exit_type = "Take Profit";
        $pips = $signal_object->get_pips($response[0]['price'], $row['price']);
		$exit_price = $response[0]['price'];
        $signal_object->trigger_signal_schedule($row['signal_id'], 2, '', '', $exit_time, $pips, $exit_type, $exit_price);
    }
    if (($response[0]['price'] >= $row['stop_loss']) && !empty($response[0][price])) {
        $exit_time = date('Y-m-d h:i:s');
		$exit_type = "Stop Loss";
		$exit_price = $response[0]['price'];
        $pips = $signal_object->get_pips($response[0]['price'], $row['price']);
        $signal_object->trigger_signal_schedule($row['signal_id'], 2, '', '', $exit_time, $pips, $exit_type, $exit_price);
    }
}

// Close Buy Order
function trigger_buy_order($row)
{
    global $signal_object;
    $symbol = str_replace('/', '', $row['symbol']);
    $url = Signal_Management::QUOTES_API . "?pairs=$symbol&api_key=" . $signal_object->quotes_api_key();
    $get_data = file_get_contents($url);
    $response = (array)json_decode($get_data, true);

    if (($response[0]['price'] >= $row['take_profit']) && !empty($response[0][price])) {
        $exit_time = date('Y-m-d h:i:s');
		$exit_type = "Take Profit";
		$exit_price = $response[0]['price'];
        $pips = $signal_object->get_pips($response[0]['price'], $row['price']);
        $signal_object->trigger_signal_schedule($row['signal_id'], 2, '', '', $exit_time, $pips, $exit_type, $exit_price);
    }
    if (($response[0]['price'] <= $row['stop_loss']) && !empty($response[0][price])) {
        $exit_time = date('Y-m-d h:i:s');
		$exit_type = "Stop Loss";
		$exit_price = $response[0]['price'];
        $pips = $signal_object->get_pips($response[0]['price'], $row['price']);
        $signal_object->trigger_signal_schedule($row['signal_id'], 2, '', '', $exit_time, $pips, $exit_type, $exit_price);
    }
}