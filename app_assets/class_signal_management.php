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
        6 => 'itAOIeH3HY9JNn5K0Bc86Vbc7LJ1GIw9',
        7 => 'MVirptGI9kaHg78rzH2d2Ol8AvG5wJ9V',
        8 => 'CI2becFW2E4R2f6OAgBQow3VoNJuDWjm',
        9 => 'I7eLZvjx9cZZLMWJr4NdzDG20mMAC3uf',
        10 => '7UDKNPEu1AR1DVXKoAcPDCXiTPGeEBfU',
        12 => 'd4YPchqHgfO4htWlA7kUcJLkJomBTud5',
        13 => 'liskO3jffqHmxil0DUi4svo5LahgNucj',
        14 => 'OGhqcwRaoR9eROL4nkbeYCuTVly3YB7H',
        15 => 'Kuow3F5BMpHwNC06UhlcncFKjdjP6eDQ',
        16 => 'CZEcLcfQGpWDBMdoqqzUlXofH1YScsRY',
        17 => 'jwJS0j8pJdGo8EqxvT5e3lkQBMhoZ5rP',
        18 => 'ztccz3kIyAYQkc9jJoJcwUEZnfVtDhzH',
        19 => 'VXLJbX58kBx9P9W8e12btpni9rph503d',
        20 => 'ebStjJtD241H7RSD6KxhGayabVGITPuu',
        21 => '4oC0cBBlKLeAY5Tp1OOtbs7aAUdT1hsN',
        22 => 'FvLzdaOVM4AAdEAlFDZ37rYxVs4h4Wqi',
        23 => 'eIyPTw8YhMNdeFZk22RHSJzUpnA13v38',
        24 => 'INGrYU5jRTRiT2cYiSUc7cqznZjRKGiL',
        25 => 'QHIlTNDwNcd3S12TadYisGvxNB5bckqj',
        26 => 'n2HgTqLpTNSfWszd0FeBVim31xcSCwLF',
        27 => 'pdXalhZVQbekguYqAWO4PKz9crA5V8yV',
        28 => '5dmrvBzakweeiq9qRsgDmDzlsnnxOBqu',
        29 => 'zyWyE4JKKzrMpJ4yw2IcBvXfJ89WdoZ0',
        30 => 'TgHTt313hAk5LpHO3BgcNbBFoKEIpcLP',
        31 => 'ubrVu0e2al42XK1mXKULgdEjCWlIGVZK',
        32 => 'XfMunc3YC4NNTMbXKcCEJAkiMb15md92',
        33 => 'Tsqs23KWuXnszaMWMiUZOAz3tVznjBB1',
        34 => '3hk4A2ewQ8DDmVpruDeXOj4iJztJHKB6',
        35 => 'pSpqowJntDfuDjbCkog6evtbP6Tvpvh',
        36 => 'AryJXGUQIW6cl6mpgQ3nczovspaftrFs',
        37 => 'qtZeOnKU9WhaWXl0y5AiWp0kkfGZwMam',
        38 => 'LxuPQisDtf392Dk3JjIGW8FHsUKbVK7R',
        39 => 'v5DUQaPgYJv49iKktlMQNKON4zDSoHXY',
        40 => '6FTtxpD5ONkki3GnA6M3ogUaOebFn8Qq'
    );
/*
 * get the ID of a signal_symbol_pair
 */
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

    public function get_live_pips($trigger_status, $symbol_id, $symbol, $price, $order_type, $pips, $exit_time, $entry_time, $exit_type, $signal_id, $highest_pips, $lowest_pips, $highest_pips_time, $lowest_pips_time)
    {
        //$pairs = $this->get_scheduled_pairs(date('Y-m-d'));
        if ($trigger_status == 1) {
            $symbol = str_replace('/', '', $symbol);
            get_key:
            $key = $this->quotes_api_key();

            $url = Signal_Management::QUOTES_API . "?pairs=$symbol&api_key=$key";
            $get_data = file_get_contents($url);
            $response = json_decode($get_data, true);
            $market_price = $response[0]['price'];
            if (empty($market_price)) {
                goto get_key;
            };

            if (!empty($market_price) && !empty($price)) {
                $diff = $this->get_pips($symbol_id, $market_price, $price);
                $display = $this->get_pips_display($order_type, $diff);
            } else {
                $display = 0;
            }
            $open_date = datetime_to_text($entry_time);
            if(!empty($row1['highest_pips_time']) && ($row1['highest_pips_time'] != 0)){
            $highest_pips_time = datetime_to_text($highest_pips_time);}
            $display2 = $this->get_pips_display($order_type, $highest_pips);
            $display = $display . " as at " . date('H:i:s a');
            $display = <<<MAIL
            <li class="list-group-item d-flex justify-content-between lh-condensed" style="display:block" >
                                            <div>
                                            <h6 style="font-size: 15px" class="my-0">
                                            <strong>This Trade is {$this->UI_pips_msg($trigger_status)} and has a high of {$display2} on {$highest_pips_time}</strong></h6>
                                            <h6 class="my-0"></h6>

                                            <small class="text-muted">
                                            </small>

                                        </div>
                                        </li>
            <li class="list-group-item d-flex justify-content-between lh-condensed" style="display:block" >
                                            <div>
                                            <h6 style="font-size: 15px" class="my-0">
                                            <strong>This Trade is at {$display} it Opened on {$open_date} </strong></h6>
                                            <h6 class="my-0"></h6>

                                            <small class="text-muted">
                                            </small>

                                        </div>
                                        </li>
MAIL;
        } elseif ($trigger_status == 2) {
            $closed_date = datetime_to_text($exit_time);
            if(!empty($row1['highest_pips_time']) && ($row1['highest_pips_time'] != 0)){
                $highest_pips_time = datetime_to_text($highest_pips_time);}
            if(!empty($row1['lowest_pips_time']) && ($row1['lowest_pips_time'] != 0)){
                $lowest_pips_time = "as at".datetime_to_text($lowest_pips_time);}
            $display = $this->get_pips_display($order_type, $pips);
            //$display2 = $this->get_pips_display($order_type, $highest_pips);
            //$display3 = $this->get_pips_display($order_type, $lowest_pips);
            if(($highest_pips > 5) && ($exit_type == "Stop Loss")){
              $display_break_even = "Kindly Note that if You Used Your trailing Stop This Trade is a Break-Even-Trade!!!";
            }else{$display_break_even = "";}
            $display = <<<MAIL
            <li class="list-group-item d-flex justify-content-between lh-condensed" style="display:block" >
                                            <div>
                                            <h6 style="font-size: 15px" class="my-0">
                                            <strong>This Trade Closed on {$closed_date} and made {$display}</strong></h6>
                                            <h6 class="my-0">{$display_break_even}</h6>

                                            <small class="text-muted">
                                            Trade Closure Type : {$exit_type}
                                            </small>

                                        </div>
                                        </li>
                                        <li class="list-group-item d-flex justify-content-between lh-condensed" style="display:block" >
                                            <div>
                                            <h6 style="font-size: 15px" class="my-0">
                                            <strong>This Trade had a Drawdown of <span style="color:red !important;"> {$lowest_pips} pips Loss</span> {$lowest_pips_time} and a High of <span style="color:red !important;"> {$highest_pips} pips Profit</span> {$highest_pips_time}</strong></h6>
                                            <h6 class="my-0"></h6>

                                        </div>
                                        </li>
MAIL;

        } elseif ($trigger_status == 0) {
            $display = <<<MAIL
            <li class="list-group-item d-flex justify-content-between lh-condensed" style="display:block" >
                                            <div>
                                            <h6 style="font-size: 15px" class="my-0">
                                            <strong>This Trade is {$this->UI_pips_msg($trigger_status)} Kindly Place a pending Order, Ensure to use all signal parameters.</strong></h6>
                                            <h6 class="my-0"></h6>

                                            <small class="text-muted">
                                            </small>

                                        </div>
                                        </li>
MAIL;

        }
        return $display;
    }

    public function get_scheduled_pairs($date)
    {
        global $db_handle;
        $query = "SELECT DISTINCT SS.symbol 
                  FROM signal_daily AS SD 
                  INNER JOIN signal_symbol AS SS ON SD.symbol_id
                  WHERE SD.trigger_date = '$date' AND SD.symbol_id = SS.symbol_id ";
        $result = $db_handle->fetchAssoc($db_handle->runQuery($query));
        $pairs = array();
        foreach ($result as $row) {
            $pairs[count($pairs)] = $row['symbol'];
        }
        $pairs = implode(',', $pairs);
        return $pairs;
    }

    public function quotes_api_key()
    {
        return Signal_Management::QUOTES_API_KEY[mt_rand(1, 40)];
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

    public function round_price_to_dp($price, $decimal_place)
    {
//        $price = round($price,4);
//        $dec = strpos($price, ".");
        if($decimal_place == 4){
           $price = substr($price, 0, -1);
        }elseif($decimal_place == 2){
            $price = substr($price, 0, -3);
        }
        return $price;
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

    public function UI_get_signals_for_page()
    {
        $signals = (array)json_decode(file_get_contents('../models/signal_daily.json'));
        if (!empty($signals)) {
            for ($i = 0; $i < count($signals); $i++) {
                $row = (array)$signals[$i];
                $this->viewCount($row['signal_id']);
                $symbol = str_replace('/', '', $row['symbol']);
                $posted_date = datetime_to_text($row['created']);
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
                                                <b class="thumbnail-label pull-left"><span class="currency_pair" id="signal_{$row['signal_id']}_currency_pair">{$row['symbol']}</b>
                                                    <br/>
                                                <span>{$this->UI_get_signal_trigger_status_msg($row['trigger_status'])}</span>
                                            </div>
                                            <div class="col-sm-3">
                                                 <b class="pull-right">{$this->UI_order_type_status_msg($row['order_type'])}</b>
                                            </div>
                                       </div>
                                                        <hr>
                                        <div class="well text-center"><b>ENTRY PRICE: {$this->round_price_to_dp($row['price'], $row['decimal_place'])}</b></div>
                                        <div class="row">
                                            <div class="col-sm-6"><div class="well text-center"><span>{$this->round_price_to_dp($row['stop_loss'], $row['decimal_place'])}<br/>Stop Loss</span></div></div>
                                            <div class="col-sm-6"><div class="well text-center"><span>{$this->round_price_to_dp($row['take_profit'], $row['decimal_place'])}<br/>Take Profit</span></div></div>
                                        </div>
                                        <div class="row">
                                             <div class="col-sm-12"><a target="_blank" href="https://webtrader.instaforex.com/login" class="btn btn-sm btn-success btn-group-justified">{$this->UI_signal_call_to_action_msg($row['trigger_status'])}</a><br/></div>
                                        </div>
                                        <div class="col-sm-12">
                                        <h6 style="font-size: 10px" class="my-0 pull-right"><strong><span class="text-muted"><span>Posted on </span>$posted_date</span></strong></h6>

                                        <button class="pull-right" type="button" id="signal_{$row['signal_id']}_trigger" onclick="signal.show_extra_analysis('{$row['signal_id']}')"><b>VIEW EXTRA ANALYSIS <i class="glyphicon glyphicon-arrow-right"></i></b></button>
                                        </div>
                                       </div>
                                                    <!--............................................-->
                                                    <!--............................................-->
                                       <div id="signal_{$row['signal_id']}_extra" style="display: none" class="col-xs-12 col-sm-6 col-md-6 col-lg-8 col-xl-8">
                                            <div class="row">
                                                 <div  class="col-sm-5 col-xs-12">

{$this->get_live_pips($row['trigger_status'], $row['symbol_id'], $row['symbol'], $row['price'], $row['order_type'], $row['pips'], $row['exit_time'], $row['entry_time'], $row['exit_type'], $row['signal_id'], $row['highest_pips'], $row['lowest_pips'], $row['highest_pips_time'], $row['lowest_pips_time'])}



                                       <li class="list-group-item d-flex justify-content-between lh-condensed"  style="display:none;">
                                        <div>
                                        <h6 style="font-size: 12px" class="my-0 pull-right"><strong>Know how much you can gain from taking this trade.</strong></h6>
                                        <h6 class="my-0"></h6>

                                       <center>
                                        <form class="form form-validate" role="form" method="post" action="">
                                            <div class="input-group">
                                                        <span class="input-group-addon"><i class="fa fa-dollar fa-fw"></i></span>
                                                        <input name="name" type="text" id="signal_equity_{$row['signal_id']}" value="" class="form-control" placeholder="Enter Your Equity" required/>
                                            </div>
                                            <div class="input-group">
                                                        <span class="input-group-addon"><i class="fa fa-circle fa-fw"></i></span>
                                                        <input name="name" type="text" id="signal_lots_{$row['signal_id']}" value="" class="form-control" placeholder="Enter Desired Lots" required/>
                                            </div>
                                            <div class="input-group" >
                                                        <input style="display:none" name="name" type="text" id="signal_gain_{$row['signal_id']}" value="" class="form-control" placeholder="Enter Desired Lots"/>
                                            </div>
                                            <div>
                                                    <button onclick="cal_gain({$row['signal_id']})" type="button" class="btn btn-success">Calculate</button>                                              </div>
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
                                            <div class="tradingview-widget-container" style="pointer-events: none;">
                                            <div class="tradingview-widget-container__widget"></div>
                                            <div class="tradingview-widget-copyright"><a href="https://www.tradingview.com/markets/currencies/" rel="noopener" target="_blank"><span class="blue-text">Forex</span></a> by TradingView</div>
                                                    <script type="text/javascript" src="https://s3.tradingview.com/external-embedding/embed-widget-market-overview.js" async>
                                                                                      {
                                                                                     "width": 335,
                                                                                     "height": 290,
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
                                                                                       "symbols": [
                                                                                      {
                                                                                      "s": "FX:{$symbol}"
                                                                                      }
                                                                                                   ]
                                                                                      }
                                                                                              ]
                                                                                      }
                                                    </script>
                                            </div>

                                            <div>
                                            <small style="font-size: x-small">Your use of the signals means you have read and accepted our
                                                 <a href="signal_terms_of_use.php" title="Forex Signal Terms of Use">terms of use</a>.
                                                 Download the <a href="downloads/Signals_Guide.txt" target="_blank" title="Download signal guide">
                                                 signal guide</a> to learn how to use the signals.
                                            </small>
                                            <li class="list-group-item d-flex justify-content-between lh-condensed" >
                                                  <div>
                                                     <small class="text-muted">
                                                        KeyNote: {$row['note']}
                                                     </small>
                                                  </div>
                                            </li>
                                       </div>
                                   </div>
                               </div>
                          </div>
                     </div>
                </div>
            </div>
        </div>
MAIL;
                echo $output;
            }
        } else {
            $output = <<<MAIL
                <center><p>No signals at the moment...</p></center>
MAIL;
            echo $output;
        }

    }

    public function viewCount($id)
    {
        global $db_handle;
        $query = "UPDATE signal_daily view SET views = views + 1 WHERE signal_id = '$id'";
        $result = $db_handle->runQuery($query);
        return $result;
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

    public function UI_get_symbol_current_price($symbol)
    {
        $symbol = str_replace('/', '', $symbol);
        $url = Signal_Management::QUOTES_API . "?pairs=$symbol&api_key=" . $this->quotes_api_key();
        $get_data = file_get_contents($url);
        $response = (array)json_decode($get_data, true);
        return $response[0]['price'];
    }

    public function UI_get_signal_trigger_status_msg($trigger_stat)
    {
        $trigger_stat = (int)$trigger_stat;
        switch ($trigger_stat) {
            case 0:
                $msg = '<i class="fa fa-circle-o-notch fa-spin"></i> Pending';
                break;
            case 1:
                $msg = '<i class="fa fa-spinner fa-spin"></i> Active';
                break;
            case 2:
                $msg = '<i class="fa fa-circle"></i> Closed';
                break;
        }
        return $msg;
    }

    public function UI_pips_msg($trigger_stat)
    {
        $trigger_stat = (int)$trigger_stat;
        switch ($trigger_stat) {
            case 0:
                $msg = 'Pending';
                break;
            case 1:
                $msg = 'Active';
                break;
            case 2:
                $msg = 'Closed';
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

    public function UI_signal_call_to_action_msg($trigger_stat)
    {
        $trigger_stat = (int)$trigger_stat;
        switch ($trigger_stat) {
            case 0:
                $msg = 'PLACE PENDING ORDER';
                break;
            case 1:
                $msg = 'TAKE TRADE NOW';
                break;
            case 2:
                $msg = 'CHECK MARKET HISTORY';
                break;
        }
        return $msg;
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

    public function get_scheduled_signals($date)
    {
        global $db_handle;
        $query = "SELECT SD.symbol_id, SD.signal_id, SD.order_type, SD.price, SD.take_profit,
SD.stop_loss, SD.created, SD.trigger_date, SD.trigger_time, SD.note, SD.trigger_status, SS.symbol,
 SD.pips, SD.entry_time, SD.exit_time, SD.exit_type, SD.highest_pips_time, SD.lowest_pips_time, SD.highest_pips, SD.lowest_pips, SS.decimal_place
FROM signal_daily AS SD 
INNER JOIN signal_symbol AS SS ON SD.symbol_id = SS.symbol_id 
WHERE SD.trigger_date = '$date'";
        return $db_handle->fetchAssoc($db_handle->runQuery($query));
    }

    public function update_signal_daily_FILE($signal_array)
    {
        file_put_contents('/home/tboy9/models/signal_daily.json', json_encode($signal_array));
        // file_put_contents('../../models/signal_daily.json', json_encode($signal_array));

    }

    /**
     * @param $price - Send as a string
     * @param $decimal - length of figure to return, same as standard decimal for currency pairs
     * @return int
     */
    public function quote_splitter($price, $decimal)
    {
        intval($price);
        $value = explode('.', $price)[1];
        $return = substr($value, 0, $decimal);
        $return = (int)$return;

        return $return;
    }

    public function get_pips($symbol_id, $market_price, $price)
    {
        global $db_handle;
        $query = "SELECT decimal_place FROM signal_symbol WHERE symbol_id = '$symbol_id' LIMIT 1";
        $result = $db_handle->fetchAssoc($db_handle->runQuery($query));
        foreach ($result as $row) {
            $decimal = $row['decimal_place'];
        }

        $market_price = $this->quote_splitter("$market_price", $decimal);
        $price = $this->quote_splitter("$price", $decimal);
        $diff = $market_price - $price;

        return $diff;
    }

    public function get_pips_display($order_type, $pips)
    {
        switch ($order_type) {
            case 1:
                if ($pips > 0) {
                    $pips_msg = <<<MSG
                    <span style="color:green !important;">{$pips} pips Profit</span>
MSG;
                } elseif ($pips < 0) {
                    $pips_msg = <<<MSG
                    <span style="color:red !important;">{$pips} pips Loss</span>
MSG;
                } else {
                    $pips_msg = <<<MSG
                    <span>0 pip</span>
MSG;
                }
                break;
            case 2:
                if ($pips > 0) {
                    $pips = $pips * -1;
                    $pips_msg = <<<MSG
                    <span style="color:red !important;">{$pips} pips Loss</span>
MSG;
                } elseif ($pips < 0) {
                    $pips = $pips * -1;
                    $pips_msg = <<<MSG
                    <span style="color:green !important;">{$pips} pips Profit</span>
MSG;
                } else {
                    $pips_msg = <<<MSG
                    <span>0 pip</span>
MSG;
                }
                break;
        }
        return "$pips_msg";
    }

    public function trigger_signal_schedule($signal_id, $trigger_status, $entry_price, $entry_time, $exit_time, $pips, $exit_type, $exit_price, $highest_pips_time, $lowest_pips_time, $highest_pips, $lowest_pips)
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
        if (!empty($exit_type)) {
            $query .= ", exit_type = '$exit_type'";
        }
        if (!empty($exit_price)) {
            $query .= ", exit_price = '$exit_price'";
        }
        if (!empty($highest_pips_time) && ($highest_pips_time == 1)) {
            $query .= ", highest_pips_time = now()";
        }
        if (!empty($lowest_pips_time) && ($lowest_pips_time == 1)) {
            $query .= ", lowest_pips_time = now()";
        }
        if (!empty($highest_pips)) {
            $query .= ", highest_pips = $highest_pips";
        }
        if (!empty($lowest_pips)) {
            $query .= ", lowest_pips = $lowest_pips";
        }
        $query .= " WHERE signal_id = $signal_id ";
        $result = $db_handle->runQuery($query);
        if ($result) {
            $signal_array = $this->get_scheduled_signals(date('Y-m-d'));
            $this->update_signal_daily_FILE($signal_array);
        }
        return $result;
    }

    public function new_signal_schedule($symbol_id, $order_type, $price, $take_profit, $stop_loss, $trigger_date, $trigger_time, $trend, $note = '', $admin_code, $market_price)
    {
        global $db_handle;
        $query = "INSERT INTO signal_daily (symbol_id, order_type, price, take_profit, stop_loss, trigger_date, trigger_time, note, views, created_by, market_price, pips)
                  VALUES ('$symbol_id','$order_type','$price', '$take_profit', '$stop_loss', '$trigger_date', '$trigger_time', '$note', 0, '$admin_code', '$market_price', 0)";
        $result = $db_handle->runQuery($query);
        if ($result) {
            $signal_array = $this->get_scheduled_signals(date('Y-m-d'));
            $this->update_signal_daily_FILE($signal_array);
        }
        return $result;
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
        $date = date('Y-m-d');
        global $db_handle;
        $query = "SELECT DISTINCT SS.symbol 
                  FROM signal_daily AS SD 
                  INNER JOIN signal_symbol AS SS ON SD.symbol_id
                  WHERE SD.trigger_date = '$date' AND SD.symbol_id = SS.symbol_id ";
        $symbols = $db_handle->fetchAssoc($db_handle->runQuery($query));
        $symbol_array = array();
        foreach ($symbols as $key => $value) {
            $symbol_array['symbols'][count($symbol_array['symbols'])] = array('title' => $value['symbol'], 'proName' => str_replace('/', '', $value['symbol']));
        }
        if (empty($symbol_array)) {
            $query = "SELECT symbol FROM signal_symbol";
            $symbols = $db_handle->fetchAssoc($db_handle->runQuery($query));
            $symbol_array = array();
            foreach ($symbols as $key => $value) {
                $symbol_array['symbols'][count($symbol_array['symbols'])] = array('title' => $value['symbol'], 'proName' => str_replace('/', '', $value['symbol']));
            }
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
            for ($i = 0; $i < count($signals_side); $i++) {
                $row_side = (array)$signals_side[$i];
                $output_side = <<<MAIL
                <tr><td><a href="signal_schedules.php#signal_{$row_side['signal_id']}"><strong Style="color: black">{$row_side['symbol']}</strong></a></td>
                <td></td>
                <td>{$this->UI_order_type_status_msg($row_side['order_type'])}</td>
                <td>{$this->UI_signal_trend_msg($row_side['order_type'])}</td>
                <td style="color:black;"><a href="signal_schedules.php#signal_{$row_side['signal_id']}">{$this->UI_get_signal_trigger_status_msg($row_side['trigger_status'])}<i class="glyphicon glyphicon-arrow-right"></i></a></td>
                </tr>
MAIL;
                echo $output_side;
            }
        } else {
            $output_side = <<<MAIL
                <center><p>No signals at the moment...</p></center>
MAIL;
            echo $output_side;
        }

    }

    public function add_lead($name, $email_address, $phone_number, $source, $interest, $created, $state_id = '')
    {
        global $db_handle;
        global $system_object;
        $name = split_name($name);
        $first_name = $name['first_name'];
        $last_name = $name['last_name'];
        $query = "INSERT INTO campaign_leads (f_name, l_name, email, phone, source, interest, created) VALUE ('$first_name', '$last_name', '$email_address', '$phone_number', $source, $interest, '$created')";
        $result = $db_handle->runQuery($query);

                $text_message = "Welcome on board! The key to trading Forex profitably is Knowledge, We are so excited you have chosen us to guide you through the path of making money from the Forex market, Click bit.ly/2iExTpN to begin your free training.";
                $subject = "Welcome to InstaFxNg $first_name";
                $message = <<<MAIL
    <div style="background-color: #F3F1F2">
    <div style="max-width: 80%; margin: 0 auto; padding: 10px; font-size: 14px; font-family: Verdana;">
        <img src="https://instafxng.com/images/ifxlogo.png" />
        <hr />
        <div style="background-color: #FFFFFF; padding: 15px; margin: 5px 0 5px 0;">
            <p>Hello $first_name,</p>
            <p>Welcome on board!</p>
            <p>I would like to take this opportunity to let you know how pleased and excited I am that you have chosen to trade with InstaForex Nigeria (www.InstaFxNg.com).</p>
            <p>You have joined over 14,000 Nigerians who make consistent income from the Forex market using the InstaForex platform and also earn more money just for trading.</p>
            <p>To start your journey to earning more money from Forex trading, you need to gain adequate knowledge of the Forex market.</p>

            <p>We have a Free online training that you can take advantage of to learn more about how to trade Forex profitably.</p>
            <p>The training is simple and easy to follow and you can take it from your house, office or even on your bed.</p>

            <p>Within the next hour, you will be placing informed trades and increase your chances of taking your slice of the 5.3 Billion Dollars from the Forex market.</p>
            <p>Guess what! It is free (at least for now).</p>
            <p><a href="https://instafxng.com/fxacademy/">Click Here to Start the Training Now.</a></p>
            <p>$first_name, we are taking in just 50 people at this time, for the brand new Forex Money Maker course.</p>
            <p>35 spots have been taken already and we have 15 left.
            The slots are filling up very fast.
            <a href="https://instafxng.com/fxacademy/">Go here now to start the free training.</a></p>
            <p>Please don’t miss this. Go ahead and login to the training immediately to secure your spot.</p>
            <p>This will be your best shot at generating a healthy side income from forex trading. Go ahead and make the move now.</p>
            <p>Start the Forex Money Maker Course now so you can launch your Forex trading entry with a big bang.</p>
            <p>See you on the other side. Secure your spot now. Only 15 spots are up for grabs. Don’t wait till it will be too late.</p>
            <p><a href="https://instafxng.com/fxacademy/">Here is the link to the online training again.</a></p>
            <br/><br/>
            <p>Best Regards,</p>
            <p>Mercy,</p>
            <p>Client Relations Manager,</p>
            <p>InstaForex Nigeria</p>
            <p>www.instafxng.com</p>
            <br /><br />
        </div>
        <hr />
        <div style="background-color: #EBDEE9;">
            <div style="font-size: 11px !important; padding: 15px;">
                <p style="text-align: center"><span style="font-size: 12px"><strong>We"re Social</strong></span><br /><br />
                    <a href="https://facebook.com/InstaForexNigeria"><img src="https://instafxng.com/images/Facebook.png"></a>
                    <a href="https://twitter.com/instafxng"><img src="https://instafxng.com/images/Twitter.png"></a>
                    <a href="https://www.instagram.com/instafxng/"><img src="https://instafxng.com/images/instagram.png"></a>
                    <a href="https://www.youtube.com/channel/UC0Z9AISy_aMMa3OJjgX6SXw"><img src="https://instafxng.com/images/Youtube.png"></a>
                    <a href="https://linkedin.com/company/instaforex-ng"><img src="https://instafxng.com/images/LinkedIn.png"></a>
                </p>
                <p><strong>Head Office Address:</strong> TBS Place, Block 1A, Plot 8, Diamond Estate, Estate Bus-Stop, LASU/Isheri road, Isheri Olofin, Lagos.</p>
                <p><strong>Lekki Office Address:</strong> Block A3, Suite 508/509 Eastline Shopping Complex, Opposite Abraham Adesanya Roundabout, along Lekki - Epe expressway, Lagos. </p>
                <p><strong>Office Number:</strong> 08028281192</p>
                <br />
            </div>
            <div style="font-size: 10px !important; padding: 15px; text-align: center;">
                <p>This email was sent to you by Instant Web-Net Technologies Limited, the
                    official Nigerian Representative of Instaforex, operator and administrator
                    of the website www.instafxng.com</p>
                <p>To ensure you continue to receive special offers and updates from us,
                    please add support@instafxng.com to your address book.</p>
            </div>
        </div>
    </div>
</div>
MAIL;

        if($result) {
            $system_object->send_sms($phone_number, $text_message);
            $system_object->send_email($subject, $message, $email_address, $first_name);
            $assigned_account_officer = $system_object->next_account_officer();
            $client_operation = new clientOperation();
            $client_operation->new_user_ordinary($first_name." ".$last_name, $email_address, $phone_number, $assigned_account_officer);
            return true;}
        else{return false;}
    }

}