<?php
set_include_path('/home/tboy9/public_html/init/');
//set_include_path('../../public_html/init/');
require_once 'initialize_general.php';
$signal_object = new Signal_Management();

$system_object = new InstafxngSystem();

$scheduled_signals = (array)json_decode(file_get_contents('/home/tboy9/models/signal_daily.json'));
//$scheduled_signals = (array)json_decode(file_get_contents('../../models/signal_daily.json'));

for($x=0; $x<=3; $x++){
if (!empty($scheduled_signals)) {
    for ($i = 0; $i < count($scheduled_signals); $i++) {
        $row = (array)$scheduled_signals[$i];
        $signal_id = $row['signal_id'];
        $symbol = str_replace('/', '', $row['symbol']);
        $url = Signal_Management::QUOTES_API . "?pairs=$symbol&api_key=" . $signal_object->quotes_api_key();
        $get_data = file_get_contents($url);
        $response = (array)json_decode($get_data, true);
        $entry_price = $response[0]['price'];
    $decimal_place = $row['decimal_place'];
        if($decimal_place == 4){$decimal_place = 0.0005;}
        if($decimal_place == 2){$decimal_place = 0.05;}

        //Get High and low for sell
        if(($row['trigger_status'] == 1) && ($row['order_type'] == 2) && !empty($response[0]['price']) && ($response[0]['price'] != 0)){
            $pips = $signal_object->get_pips($row['symbol_id'], $response[0]['price'], $row['price']);
            $pips = $pips * -1;
            if($pips > $row['highest_pips']) {
                    $signal_object->trigger_signal_schedule($row['signal_id'], 1, '', '', '', '', '', '', 1, '', $pips, '');
            }
            if($pips < $row['lowest_pips']){
                $signal_object->trigger_signal_schedule($row['signal_id'], 1, '', '', '', '', '', '', '', 1, '', $pips);
            }
        }
        //Get High and low for buy
        if(($row['trigger_status'] == 1) && ($row['order_type'] == 1) && !empty($response[0]['price']) && ($response[0]['price'] != 0)){
            $pips = $signal_object->get_pips($row['symbol_id'], $response[0]['price'], $row['price']);
            if($pips > $row['highest_pips']){
                $signal_object->trigger_signal_schedule($row['signal_id'], 1, '', '', '', '', '', '', 1, '', $pips, '');
            }
            if($pips < $row['lowest_pips']){
                $signal_object->trigger_signal_schedule($row['signal_id'], 1, '', '', '', '', '', '', '', 1, '', $pips);
            }
        }
        //Trigger Sell Order
        if (($response[0]['price'] >= ($row['price'] - $decimal_place)) && ($response[0]['price'] <= $row['price']) && ($row['trigger_status'] != 2) && ($row['order_type'] == 2) && !empty($response[0]['price']) && ($response[0]['price'] != 0)) {
            if ($row['trigger_status'] != 1) {
                $entry_price = $response[0]['price'];
                $entry_time = date('Y-m-d H:i:s');
                $signal_object->trigger_signal_schedule($signal_id, 1, $entry_price, $entry_time, '', '', '', '', '', '', '', '');

                if ($row['order_type'] == 2) {
                    trigger_sell_order($row);
                }
            } else {

                if ($row['order_type'] == 2) {
                    trigger_sell_order($row);
                }
            }
        }else if(($row['trigger_status'] == 1) && ($row['order_type'] == 2) && ($response[0]['price'] <= $row['price'])){
            trigger_sell_order($row);
        }else if(($row['trigger_status'] == 1) && ($row['order_type'] == 2)){
            trigger_sell_order($row);
        }

        //Trigger Buy Order
        if (($response[0]['price'] <= ($row['price'] + $decimal_place)) && ($response[0]['price'] >= $row['price']) && ($row['trigger_status'] != 2) && ($row['order_type'] == 1) && !empty($response[0]['price']) && ($response[0]['price'] != 0)) {
            if ($row['trigger_status'] != 1 ) {
                $entry_price = $response[0]['price'];
                $entry_time = date('Y-m-d H:i:s');
                $signal_object->trigger_signal_schedule($signal_id, 1, $entry_price, $entry_time, '', '', '', '', '', '', '', '');

                if ($row['order_type'] == 1) {
                    trigger_buy_order($row);
                }
            } else {

                if ($row['order_type'] == 1) {
                    trigger_buy_order($row);
                }
            }
        }else if(($row['trigger_status'] == 1) && ($row['order_type'] == 1) && ($response[0]['price'] >= $row['price'])){

            trigger_buy_order($row);
        }else if(($row['trigger_status'] == 1) && ($row['order_type'] == 1)){
            trigger_buy_order($row);
        }

    }
}
    sleep(10);
}

// Close Sell Order
function trigger_sell_order($row)
{
    global $signal_object;
    global $system_object;
    $symbol = str_replace('/', '', $row['symbol']);
    $url = Signal_Management::QUOTES_API . "?pairs=$symbol&api_key=" . $signal_object->quotes_api_key();
    $get_data = file_get_contents($url);
    $response = (array)json_decode($get_data, true);

    if (($response[0]['price'] <= $row['take_profit']) && !empty($response[0]['price'])) {
        $exit_time = date('Y-m-d H:i:s');
        $exit_type = "Take Profit";
        $pips = $signal_object->get_pips($row['symbol_id'], $row['take_profit'], $row['price']);
        $exit_price = $response[0]['price'];
        $signal_object->trigger_signal_schedule($row['signal_id'], 2, '', '', $exit_time, $pips, $exit_type, $exit_price, '', '', '', '');
    }
    if (($response[0]['price'] >= $row['stop_loss']) && !empty($response[0]['price'])) {
        $exit_time = date('Y-m-d H:i:s');
        $exit_type = "Stop Loss";
        $exit_price = $response[0]['price'];
        $pips = $signal_object->get_pips($row['symbol_id'], $row['stop_loss'], $row['price']);
        $signal_object->trigger_signal_schedule($row['signal_id'], 2, '', '', $exit_time, $pips, $exit_type, $exit_price, '', '', '', '');
    }
    $tp = $signal_object->get_pips($row['symbol_id'], $row['take_profit'], $row['price']);
    $tp = $tp * -1;
    //    send sms
    // $signal_object->send_notification($row['highest_pips'], $tp, $row['signal_id'], $row['symbol']);
//send sms end
    if ( $row['highest_pips'] > $tp ) {
        $exit_time = date('Y-m-d H:i:s');
        $exit_type = "Take Profit";
        $exit_price = $response[0]['price'];
        $pips = $signal_object->get_pips($row['symbol_id'], $row['take_profit'], $row['price']);
        $signal_object->trigger_signal_schedule($row['signal_id'], 2, '', '', $exit_time, $pips, $exit_type, $exit_price, '', '', '', '');
    }
    $sl = $signal_object->get_pips($row['symbol_id'], $row['stop_loss'], $row['price']);
    $sl = $sl * -1;
    if ($row['lowest_pips'] < $sl ) {
        $exit_time = date('Y-m-d H:i:s');
        $exit_type = "Stop Loss";
        $exit_price = $response[0]['price'];
        $pips = $signal_object->get_pips($row['symbol_id'], $row['stop_loss'], $row['price']);
        $signal_object->trigger_signal_schedule($row['signal_id'], 2, '', '', $exit_time, $pips, $exit_type, $exit_price, '', '', '','');
    }
}

// Close Buy Order
function trigger_buy_order($row)
{
    global $signal_object;
    global $system_object;
    $symbol = str_replace('/', '', $row['symbol']);
    $url = Signal_Management::QUOTES_API . "?pairs=$symbol&api_key=" . $signal_object->quotes_api_key();
    $get_data = file_get_contents($url);
    $response = (array)json_decode($get_data, true);

    if (($response[0]['price'] >= $row['take_profit']) && !empty($response[0]['price'])) {
        $exit_time = date('Y-m-d H:i:s');
        $exit_type = "Take Profit";
        $exit_price = $response[0]['price'];
        $pips = $signal_object->get_pips($row['symbol_id'], $row['take_profit'], $row['price']);
        $signal_object->trigger_signal_schedule($row['signal_id'], 2, '', '', $exit_time, $pips, $exit_type, $exit_price, '', '', '', '');
    }
    if (($response[0]['price'] <= $row['stop_loss']) && !empty($response[0]['price'])) {
        $exit_time = date('Y-m-d H:i:s');
        $exit_type = "Stop Loss";
        $exit_price = $response[0]['price'];
        $pips = $signal_object->get_pips($row['symbol_id'], $row['stop_loss'], $row['price']);
        $signal_object->trigger_signal_schedule($row['signal_id'], 2, '', '', $exit_time, $pips, $exit_type, $exit_price, '', '', '','');
    }
    $tp = $signal_object->get_pips($row['symbol_id'], $row['take_profit'], $row['price']);
//    send sms
    // $signal_object->send_notification($row['highest_pips'], $tp, $row['signal_id'], $row['symbol']);
//send sms end
    if ( $row['highest_pips'] > $tp ) {
        $exit_time = date('Y-m-d H:i:s');
        $exit_type = "Take Profit";
        $exit_price = $response[0]['price'];
        $pips = $signal_object->get_pips($row['symbol_id'], $row['take_profit'], $row['price']);
        $signal_object->trigger_signal_schedule($row['signal_id'], 2, '', '', $exit_time, $pips, $exit_type, $exit_price, '', '', '', '');
    }
    $sl = $signal_object->get_pips($row['symbol_id'], $row['stop_loss'], $row['price']);
    if ($row['lowest_pips'] < $sl ) {
        $exit_time = date('Y-m-d H:i:s');
        $exit_type = "Stop Loss";
        $exit_price = $response[0]['price'];
        $pips = $signal_object->get_pips($row['symbol_id'], $row['stop_loss'], $row['price']);
        $signal_object->trigger_signal_schedule($row['signal_id'], 2, '', '', $exit_time, $pips, $exit_type, $exit_price, '', '', '','');
    }
}



