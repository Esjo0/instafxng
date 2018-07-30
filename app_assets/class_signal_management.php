<?php
class Signal_Management
{
    const QUOTES_API = "https://forex.1forge.com/1.0.3/quotes";
    //TODO: Update this...
    const QUOTES_API_KEY = array(
        1 => 'wmhaGdIcdztlSAXy76QZxeHAsWDpCtru',
        2 => 'nHpHP0Y6FgJ9NVbHO6cRf5yg2kJ5f5ky',
        3 => 'OADrX7UGJesDhvH5lDJ5NK93HZ3uSmxe',
        4 => 'Q0byrL4ELAk5jS8k11gyBq4i7dIL1PE6',
        5 => 'Oa9r9zco2Twqdw6vS0P9wDQXHj8qzbup',
        6 => 'uoGGIjYh0JADs5GsdfuuJT3LFEPiFw8S',
        7 => 'MVirptGI9kaHg78rzH2d2Ol8AvG5wJ9V'

    );

    public function quotes_api_key()
    {
        return Signal_Management::QUOTES_API_KEY[mt_rand(1, 7)];
    }

    public function get_symbol_id($pair_str)
    {
        global $db_handle;
        $currrency_pair = implode('/', str_split($pair_str, 3));
        $query = "SELECT symbol_id FROM signal_symbol WHERE symbol = '$currrency_pair' LIMIT 1";
        return $db_handle->fetchAssoc($db_handle->runQuery($query))[0]['symbol_id'];
    }

    public function get_quotes_from_file()
    {
        $old_quotes = file_get_contents('daily_quotes.json');
        if (json_decode($old_quotes)) {
            return json_decode($old_quotes);
        }
    }

    public function UI_get_symbol_current_price($symbol)
    {
        $symbol = str_replace('/', '', $symbol);
        $url = Signal_Management::QUOTES_API . "?pairs=$symbol&api_key=" . $this->quotes_api_key();
        $get_data = file_get_contents($url);
        $response = (array)json_decode($get_data, true);
        return $response[0]['price'];
    }

    public function get_live_quotes()
    {
        $pairs = $this->get_scheduled_pairs(date('Y-m-d'));
        if (!empty($pairs)) {
            $url = Signal_Management::QUOTES_API . "?pairs=$pairs&api_key=" . $this->quotes_api_key();
            $get_data = file_get_contents($url);
            $response = json_decode($get_data, true);
            return $response;
        }
    }

    public function get_scheduled_signals($date)
    {
        global $db_handle;
        $query = "SELECT SD.symbol_id, SD.signal_id, SD.order_type, SD.price, SD.price, SD.take_profit,
SD.stop_loss, SD.created, SD.trigger_date, SD.trigger_time, SD.note, SD.trigger_status, SS.symbol 
FROM signal_daily AS SD 
INNER JOIN signal_symbol AS SS ON SD.symbol_id = SS.symbol_id 
WHERE SD.trigger_date = '$date'";
        return $db_handle->fetchAssoc($db_handle->runQuery($query));
    }

    public function get_scheduled_pairs($date)
    {
        global $db_handle;
        $query = "SELECT DISTINCT SS.symbol 
                  FROM signal_daily AS SD 
                  INNER JOIN signal_symbol AS SS ON SD.symbol_id
                  WHERE SD.trigger_date = '$date' ";
        $result = $db_handle->fetchAssoc($db_handle->runQuery($query));
        $pairs = array();
        foreach ($result as $row) {
            $pairs[count($pairs)] = $row['symbol'];
        }
        $pairs = implode(',', $pairs);
        $pairs = str_replace('/', '', $pairs);
        return $pairs;
    }

    public function new_signal_listener()
    {
        $file_current_property = date('Y-m-d h:i:s', stat('../../../models/signal_daily.json')['mtime']);
        $file_old_property = file_get_contents('../../../models/signal_daily_bookmark.json');
        if ($file_current_property != $file_old_property) {
            echo 'new-signals-found';
            file_put_contents('../../../models/signal_daily_bookmark.json', $file_current_property);
        }
    }

    public function UI_get_news_for_page($currency_pair)
    {
        $date = date('Y-m-d');
        $news_array = (array)json_decode(file_get_contents("https://newsapi.org/v2/everything?q=$currency_pair&from=$date&sortBy=popularity&apiKey=f954016b06bd412288ac281bc509a719"));
        $output = '';
        if (!empty($news_array)) {
            foreach ($news_array as $row) {
                $output .= <<<MAIL
<div class="row col-sm-12 col-xs-12">
<div class="col-sm-4 col-xs-4">
<img class="img-responsive" alt="" src="{$row['urlToImage']}" />
</div>
<div class="col-sm-8 col-xs-8">
<b class="text-justify" style="font-size: small !important;"><a target="_blank" href="{$row['url']}">{$row['title']}</a></b><br/>
<span class="text-justify" style="font-size: small !important;">Posted:{$row['publishedAt']}</span>
</div>
<div class="col-sm-12"><hr/></div>
</div>
MAIL;
            }
            return $output;
        }
    }

    public function UI_get_signal_trigger_status_msg($trigger_stat)
    {
        $trigger_stat = (int)$trigger_stat;
        switch ($trigger_stat) {
            case 0:
                $msg = 'Pending...';
                break;
            case 1:
                $msg = 'Active...';
                break;
            case 2:
                $msg = 'Closed';
                break;
        }
        return $msg;
    }

    public function UI_signal_trend_msg($order_type)
    {
        $trigger_stat = (int)$order_type;
        switch ($trigger_stat) {
            case 1:
                $msg = '<b style="font-size: large" class="text-success"><i class="glyphicon glyphicon-arrow-up"></i></b>';
                break;
            case 2:
                $msg = '<b style="font-size: large" class="text-danger"><i class="glyphicon glyphicon-arrow-down"></i></b>';
                break;
        }
        return $msg;
    }

    public function UI_signal_call_to_action_msg($trigger_stat)
    {
        $trigger_stat = (int)$trigger_stat;
        switch ($trigger_stat) {
            case 0:
                $msg = 'PLACE PENDING ORDER';
                break;
            case 1:
                $msg = 'TRADE NOW';
                break;
            case 2:
                $msg = 'CHECK MARKET HISTORY';
                break;
        }
        return $msg;
    }

    public function UI_order_type_status_msg($order_type)
    {
        $order_type = (int)$order_type;
        switch ($order_type) {
            case 1:
                $msg = "<b class='text-success'>BUY</b>";
                break;
            case 2:
                $msg = "<b class='text-danger'>SELL</b>";
                break;
        }
        return $msg;
    }

    public function viewCount($id)
    {
        global $db_handle;
        $query = "UPDATE signal_daily SET views = views + 1 WHERE signal_id = '$id'";
        $result = $db_handle->runQuery($query);
        return $result;
    }

    public function UI_get_signals_for_page()
    {
        $signals = (array)json_decode(file_get_contents('../models/signal_daily.json'));
        if (!empty($signals)) {
            foreach ($signals as $row) {
                $row = (array)$row;
                $this->viewCount($row['signal_id']);
                $output = <<<MAIL
<div id="signal_{$row['signal_id']}" class="col-xs-12 col-sm-6 col-md-6 col-lg-4 col-xl-4 card grid-item main">
                                        <div class="thumbnail">
                                            <div class="caption">
                                                <div class="row">
                                                    <!--.....................................-->
                                                    <div id="signal_{$row['signal_id']}_main" class="col-xs-12 col-sm-12 col-md-12 col-lg-12 col-xl-12">
                                                        <div class="row">
                                                            <div class="col-sm-2"><p style="font-size: xxx-large">{$this->UI_signal_trend_msg($row['order_type'])}</p></div>
                                                            <div class="col-sm-7">
                                                                <b class="thumbnail-label pull-left"><span class="currency_pair" id="signal_{$row['signal_id']}_currency_pair">{$row['symbol']} (<span class="current_price" id="signal_{$row['signal_id']}_currency_price">{$this->UI_get_symbol_current_price($row['symbol'])}</span>)</b>
                                                                <br/>
                                                                <span>{$this->UI_get_signal_trigger_status_msg($row['trigger_status'])}</span>
                                                            </div>
                                                            <div class="col-sm-3">
                                                                <b class="pull-right">{$this->UI_order_type_status_msg($row['order_type'])}</b>
                                                            </div>
                                                        </div>
                                                        <hr>
                                                        <div class="well text-center"><b>ENTRY PRICE: {$row['price']}</b></div>
                                                        <div class="row">
                                                            <div class="col-sm-6"><div class="well text-center"><span>{$row['stop_loss']}<br/>Stop Loss</span></div></div>
                                                            <div class="col-sm-6"><div class="well text-center"><span>{$row['take_profit']}<br/>Take Profit</span></div></div>
                                                        </div>
                                                        <div class="row">
                                                            <div class="col-sm-12"><a target="_blank" href="https://webtrader.instaforex.com/login" class="btn btn-sm btn-success btn-group-justified">{$this->UI_signal_call_to_action_msg($row['trigger_status'])}</a><br/></div>
                                                        </div>
                                                        <div class="row">
                                                            <div class="col-sm-2"><a class="pull-left" href="javascript:void(0);"><i class="glyphicon glyphicon-star-empty"></i></a></div>
                                                            <div class="col-sm-10"><a id="signal_{$row['signal_id']}_trigger" onclick="signal.show_extra_analysis('signal_{$row['signal_id']}')" class="pull-right" href="javascript:void(0);"><b>SHOW EXTRA ANALYSIS <i class="glyphicon glyphicon-arrow-right"></i></b></a></div>
                                                        </div>
                                                    </div>
                                                    <!--............................................-->
                                                    <!--............................................-->
                                                    <div id="signal_{$row['signal_id']}_extra" style="display: none" class="col-xs-12 col-sm-6 col-md-6 col-lg-8 col-xl-8">
                                                        <div class="row">
                                                            <div  class="col-sm-5 col-xs-12">
                                    <li class="list-group-item d-flex justify-content-between lh-condensed" >
                                        <div>
                                            <h6 style="font-size: 20px" class="my-0 pull-right"><strong>Number of Pips to trigger</strong></h6>
                                            <h6 class="my-0"></h6>

                                            <small class="text-muted">
                                                
                                            </small>

                                        </div>
                                        <span class="text-muted"><span id="signal_currency_diff_{$row['signal_id']}">0</span> Pips</span>
                                    </li>
                                    <li class="list-group-item d-flex justify-content-between lh-condensed" >
                                        <div>
                                            <h6 style="font-size: 20px" class="my-0 pull-right"><strong>Calculate Amount Gain</strong></h6>
                                            <h6 class="my-0"></h6>

                                              <center> 
                                              <form role="form" method="post" action="">
                                               <div class="input-group">
                                                        <span class="input-group-addon"><i class="fa fa-dollar fa-fw"></i></span>
                                                        <input name="name" type="text" id="" value="" class="form-control" placeholder="Enter Your Equity" required/>
                                                </div>
                                                <div>
                                                    <input name="add" type="submit" class="btn btn-success" value="Calculate">                                                </div>
                                                </div>
                                            </form>
                                            </center>
                                        </div>
                                        <span class="text-muted"></span>
                                    </li>
                                                            <div style="" class="col-sm-7 col-xs-12">
                                                                <!-- TradingView Widget BEGIN -->
                                                                <!-- TradingView Widget BEGIN -->

<!-- TradingView Widget BEGIN -->
<div class="tradingview-widget-container">
  <div class="tradingview-widget-container__widget"></div>
  <div class="tradingview-widget-copyright"><a href="https://www.tradingview.com/markets/currencies/" rel="noopener" target="_blank"><span class="blue-text">Forex</span></a> by TradingView</div>
  <script type="text/javascript" src="https://s3.tradingview.com/external-embedding/embed-widget-market-overview.js" async>
  {
  "showChart": true,
  "locale": "en",
  "largeChartUrl": "",
  "plotLineColorGrowing": "rgba(60, 188, 152, 1)",
  "plotLineColorFalling": "rgba(255, 74, 104, 1)",
  "gridLineColor": "rgba(233, 233, 234, 1)",
  "scaleFontColor": "rgba(214, 216, 224, 1)",
  "belowLineFillColorGrowing": "rgba(60, 188, 152, 0.05)",
  "belowLineFillColorFalling": "rgba(255, 74, 104, 0.05)",
  "symbolActiveColor": "rgba(242, 250, 254, 1)",
  "tabs": [
    {
      "title": "Forex",
      "symbols": [
        {
          "s": "FX:{$row['symbol']}"
        }
      ],
      "originalTitle": "Forex"
    }
  ]
}
  </script>
</div>
<!-- TradingView Widget END -->
                            <!-- TradingView Widget END--->
                            <div>
                            <small style="font-size: x-small">Your use of the signals means you have read and accepted our
        <a href="signal_terms_of_use.php" title="Forex Signal Terms of Use">terms of use</a>.
        Download the <a href="downloads/signalguide.pdf" target="_blank" title="Download signal guide">
            signal guide</a> to learn how to use the signals.
    </small>
</div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <!--............................................-->
                                                </div>
                                            </div>
                                        </div>
                                    </div>
MAIL;

            }
        } else {
            $output = <<<MAIL
                <center><p>No signals at the moment...</p></center>
MAIL;
        }
        echo $output;
    }

    public function update_signal_schedule($id, $symbol, $price, $take_profit, $stop_loss, $signal_time, $signal_date, $comment, $trend, $type)
    {
        global $db_handle;
        $query = "UPDATE signal_daily SET symbol_id = '$symbol', order_type = '$type', price = '$price', take_profit = '$take_profit', stop_loss = '$stop_loss', trigger_date = '$signal_date', trigger_time = '$signal_time', note = '$comment' WHERE signal_id = '$id'";
        $result = $db_handle->runQuery($query);
        if ($result) {
            $signal_array = $this->get_scheduled_signals(date('Y-m-d'));
            $this->update_signal_daily_FILE($signal_array);
        }
        return $result;
    }

    public function trigger_signal_schedule($signal_id, $trigger_status, $entry_price, $entry_time, $exit_time, $pips)
    {
        global $db_handle;
        $query = "UPDATE signal_daily SET trigger_status = '$trigger_status' ";
        if (!empty($entry_price)) {
            $query .= ", entry_price = $entry_price";
        }
        if (!empty($entry_time)) {
            $query .= ", entry_time = '$entry_time'";
        }
        if (!empty($exit_time)) {
            $query .= ", exit_time = '$exit_time'";
        }
        if (!empty($pips)) {
            $query .= ", pips = $pips";
        }
        $query .= " WHERE signal_id = '$signal_id'";
        $result = $db_handle->runQuery($query);
        if ($result) {
            $signal_array = $this->get_scheduled_signals(date('Y-m-d'));
            $this->update_signal_daily_FILE($signal_array);
        }
        return $result;
    }

    public function new_signal_schedule($symbol_id, $order_type, $price, $take_profit, $stop_loss, $trigger_date, $trigger_time, $trend, $note = '')
    {
        global $db_handle;
        $query = "INSERT INTO signal_daily (symbol_id, order_type, price, take_profit, stop_loss, trigger_date, trigger_time, note) 
                  VALUES ('$symbol_id','$order_type','$price', '$take_profit', '$stop_loss', '$trigger_date', '$trigger_time', '$note')";
        $result = $db_handle->runQuery($query);
        if ($result) {
            $signal_array = $this->get_scheduled_signals(date('Y-m-d'));
            $this->update_signal_daily_FILE($signal_array);
        }
        return $result;
    }

    public function update_signal_daily_FILE($signal_array)
    {
        file_put_contents('../../models/signal_daily.json', json_encode($signal_array));
    }

    public function UI_select_currency_pair()
    {
        global $db_handle;
        $query = "SELECT * FROM signal_symbol ";
        $result = $db_handle->runQuery($query);
        $result = $db_handle->fetchAssoc($result);
        if (!empty($result) && is_array($result)) {
            echo '<select name="symbol" class="form-control" id="location">';
            foreach ($result as $row) {
                extract($row);
                echo '<option value="' . $symbol_id . '">' . $symbol . '</option>';
            }
            echo '</select>';
            echo '<span class="input-group-addon"><span class="fa fa-gg"></span></span>';
        }
    }

    public function UI_show_live_quotes()
    {
        global $db_handle;
        $query = "SELECT symbol FROM signal_symbol";
        $symbols = $db_handle->fetchAssoc($db_handle->runQuery($query));
        $symbol_array = array();
        foreach ($symbols as $key => $value) {
            $symbol_array['symbols'][count($symbol_array['symbols'])] = array('title' => $value['symbol'], 'proName' => str_replace('/', '', $value['symbol']));
        }
        $symbol_array['locale'] = 'en';
        echo "<div class='tradingview-widget-container'>";
        echo "<div class='tradingview-widget-container__widget'></div>";
        echo "<script type='text/javascript' src='https://s3.tradingview.com/external-embedding/embed-widget-tickers.js' async>";
        echo json_encode($symbol_array);
        echo "</script></div><br/>";
    }

    public function UI_get_signals_for_sidebar()
    {
        $signals_side = (array)json_decode(file_get_contents('../../../models/signal_daily.json'));
        if (!empty($signals_side)) {
            foreach ($signals_side as $row_side) {
                $row_side = (array)$row_side;
                $output_side = <<<MAIL
                <tr><td><a href="signal_schedules.php#signal_{$row_side['signal_id']}"><strong Style="color: black">{$row_side['symbol']}</strong></a></td>
                <td></td>
                <td>{$this->UI_order_type_status_msg($row_side['order_type'])}</td>
                <td>{$this->UI_signal_trend_msg($row_side['order_type'])}</td>
                <td style="color:black;"><a href="signal_schedules.php#signal_{$row_side['signal_id']}">{$this->UI_get_signal_trigger_status_msg($row_side['trigger_status'])}<i class="glyphicon glyphicon-arrow-right"></i></a></td>
                </tr>
MAIL;
            }
        } else {
            $output_side = <<<MAIL
                <center><p>No signals at the moment...</p></center>
MAIL;
        }
        echo $output_side;
    }

}