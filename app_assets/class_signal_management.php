<?php
class Signal_Management
{
    const QUOTES_API = "https://forex.1forge.com/1.0.3/quotes";
    const QUOTES_API_KEY = "VvffCmdMk0g1RKjPBPqYHqAeWwIORY1r";
    private function curl_call($url, $method, $headers = '', $post_data = ''){
        $ch = curl_init();
        switch ($method){
            case "POST":
                curl_setopt($ch, CURLOPT_POST, true);
                if ($post_data)
                    curl_setopt($ch, CURLOPT_POSTFIELDS, $post_data);
                break;
            case "PUT":
                curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "PUT");
                if ($post_data)
                    curl_setopt($ch, CURLOPT_POSTFIELDS, $post_data);
                break;
            default:
                if ($post_data)
                    $url = sprintf("%s?%s", $url, http_build_query($post_data));
                break;
        }
        curl_setopt($ch, CURLOPT_URL, $url);
        if(!empty($headers)){ curl_setopt($ch, CURLOPT_HTTPHEADER, $headers); }
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_IPRESOLVE, CURL_IPRESOLVE_V4 );
        $result = curl_exec($ch);
        curl_close($ch);
        return $result;
    }

    public function get_symbol_id($pair_str){
        global $db_handle;
        $currrency_pair = implode('/',str_split($pair_str, 3));
        $query = "SELECT symbol_id FROM signal_symbol WHERE symbol = '$currrency_pair' LIMIT 1";
        return $db_handle->fetchAssoc($db_handle->runQuery($query))[0]['symbol_id'];
    }

    public function get_quotes_from_file(){
        $old_quotes = file_get_contents('daily_quotes.json');
        if(json_decode($old_quotes)) {
            return json_decode($old_quotes);
        }
    }

    public function get_live_quotes(){
        $pairs = $this->get_scheduled_pairs(date('Y-m-d'));
        if(!empty($pairs)){
            $url = Signal_Management::QUOTES_API."?pairs=$pairs&api_key=".Signal_Management::QUOTES_API_KEY;
            $get_data = $this->curl_call($url, 'GET');
            $response = json_decode($get_data, true);
            return $response;
        }
    }

    public function get_scheduled_signals($date){
        global $db_handle;
        $query = "SELECT SD.symbol_id, SD.signal_id, SD.order_type, SD.price, SD.price, SD.take_profit,
SD.stop_loss, SD.created, SD.trigger_date, SD.trigger_time, SD.trend, SD.note, SD.trigger_status, SS.symbol 
FROM signal_daily AS SD 
INNER JOIN signal_symbol AS SS ON SD.symbol_id = SS.symbol_id 
WHERE SD.trigger_date = '$date'";
        return $db_handle->fetchAssoc($db_handle->runQuery($query));
    }

    public function get_scheduled_pairs($date){
        global $db_handle;
        $query = "SELECT DISTINCT SS.symbol 
                  FROM signal_daily AS SD 
                  INNER JOIN signal_symbol AS SS ON SD.symbol_id
                  WHERE SD.trigger_date = '$date' ";
        $result = $db_handle->fetchAssoc($db_handle->runQuery($query));
        $pairs = array();
        foreach ($result as $row){ $pairs[count($pairs)] = $row['symbol'];}
        $pairs = implode(',', $pairs);
        $pairs = str_replace('/', '', $pairs);
        return $pairs;
    }

    /*public function send_push($token, $message, $title){
        $path_to_firebase_cm = 'https://fcm.googleapis.com/fcm/send';
        $API_SERVER_KEY = 'AAAAC0QYmqE:APA91bGSDtRp6HucthhIimDbmH3rzVakSLUIQRIIFqgBV-jXmYCfzE7sWvEdGVghRTSXL-fdLnnjdiXwTKibzrn4KrTaOTSrbyPGKkQylOt5mkRkvmup6MmUN9zZh-8QzYutQPazAvZu';
        $fields = array(
            'registration_ids' => $token,
            'priority' => 'high',
            'notification' => array('title' => $title, 'body' =>  $message ,'sound'=>'Enabled','image'=>'Notification Image' ),
            'delay_while_idle' => false,
            'content_available' => true,
            'time_to_live' => 2419200
        );
        $headers = array('Authorization:key='.$API_SERVER_KEY, 'Content-Type:application/json');

        // Open connection
        $ch = curl_init();
        // Set the url, number of POST vars, POST data
        curl_setopt($ch, CURLOPT_URL, $path_to_firebase_cm);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_IPRESOLVE, CURL_IPRESOLVE_V4 );
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($fields));
        // Execute post
        $result = curl_exec($ch);
        // Close connection
        curl_close($ch);
        return $result;
    }*/
    public function update_signal_schedule($id, $symbol, $price, $take_profit, $stop_loss, $signal_time, $signal_date, $comment, $trend, $type){
        global $db_handle;
        $query = "UPDATE signal_daily SET symbol_id = '$symbol', order_type = '$type', price = '$price', take_profit = '$take_profit', stop_loss = '$stop_loss', trigger_date = '$signal_date', trigger_time = '$signal_time', note = '$comment', trend = '$trend' WHERE signal_id = '$id'";
        $result = $db_handle->runQuery($query);
        if($result){
            $signal_array = $this->get_scheduled_signals(date('Y-m-d'));
            $this->update_signal_daily_FILE($signal_array);
        }
        return $result;
    }

    public function new_signal_schedule($symbol_id, $order_type, $price, $take_profit, $stop_loss, $trigger_date, $trigger_time, $trend, $note = ''){
        global $db_handle;
        $query = "INSERT INTO signal_daily (symbol_id, order_type, price, take_profit, stop_loss, trigger_date, trigger_time, note, trend) 
                  VALUES ('$symbol_id','$order_type','$price', '$take_profit', '$stop_loss', '$trigger_date', '$trigger_time', '$note', '$trend')";
        $result = $db_handle->runQuery($query);
        if($result){
            $signal_array = $this->get_scheduled_signals(date('Y-m-d'));
            $this->update_signal_daily_FILE($signal_array);
        }
        return $result;
    }

    public function update_signal_daily_FILE($signal_array){
        file_put_contents('../../models/signal_daily.json', json_encode($signal_array));
    }

    public function UI_select_currency_pair(){
        global $db_handle;
        $query = "SELECT * FROM signal_symbol ";
        $result = $db_handle->runQuery($query);
        $result = $db_handle->fetchAssoc($result);
        if(!empty($result) && is_array($result)){
            echo '<select name="symbol" class="form-control" id="location">';
            foreach ($result as $row) {
                extract($row);
                echo '<option value="'.$symbol_id.'">'.$symbol.'</option>';
            }
            echo '</select>';
            echo '<span class="input-group-addon"><span class="fa fa-gg"></span></span>';
        }
    }
}