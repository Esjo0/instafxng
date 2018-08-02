<?php
require_once("init/initialize_admin.php");

$signals_details = $obj_customer_care_log->get_signals();
if(!empty($signals_details)){
    foreach($signals_details as $row3) {
        $currency_pair = $row3['currency_pair'];
        $order_type = $row3['order_type'];
        $take_profit = $row3['take_profit'];
        $stop_loss = $row3['stop_loss'];;
        $status = $row3['status'];
        $note = $row3['note'];
        $price = $row3['price'];
        if($order_type == 0){
            $order_name = "BUY";
        }elseif ($order_type == 1){
            $order_name = "SELL";
        }

        if($status == 0){
            $signal_background = "panel_success";
            $status_icon = "fa-circle-o-notch fa-spin";
            $status_name = "Pending";
        }elseif($status == 1 ){
            $signal_background = "panel_warning";
            $status_icon = "fa-spinner fa-spin";
            $status_name = "Active";
        }elseif($status == 2 ){
            $signal_background = "panel_danger";
            $status_icon = "fa-circle";
            $status_name = "Closed";
        }


        echo "<div class=\"panel .$signal_background.\">
                                        <div class=\"panel-heading\">
                                            <div class=\"row\">
                                                <div class=\"col-sm-4\"><strong>.$currency_pair.</strong></div>
                                                <div class=\"col-sm-4\">PRICE.$price.</div>
                                                <div class=\"col-sm-4\"><strong>.$order_name.</strong></div>
                                            </div>
                                            <div class=\"pull-left\"><i class=\"fa .$status_icon.\"></i> .$status_name. </div>
                                        </div>
                                        <div class=\"row\">
                                            <div class=\"col-sm-4\">Take Profit .$take_profit.</div>
                                            <div class=\"col-sm-4\">Stop Loss .$stop_loss.</div>
                                            <span class=\"col-sm-2 pull-right panel-title\"><a data-toggle=\"collapse\" data-parent=\"#accordion\" href=\"#collapse3\">Read More...</a></h5></span>
                                        </div>

                                        <div id=\"collapse3\" class=\"panel-collapse collapse\">

                                            <div class=\"panel-body\">
                                                <div class=\"col-sm-6\">
                                                    <h5>SIGNAL ANALYSIS</h5>
                                                    <p style=\"border-radius: 50%;\">
                                                        <span><strong><u>KEYNOTE</u></strong></span>
                                                        .$note.
                                                    </p>
                                                </div>
                                                <div class=\"col-sm-6\">
                                                    <section style=\"min-height: 300px;\">
                                                        <script type=\"text/javascript\" src=\"https://d33t3vvu2t2yu5.cloudfront.net/tv.js\"></script>
                                                        <script type=\"text/javascript\">
                                                            new TradingView.widget({
                                                                \"width\": \"100%\",
                                                                \"height\": 300,
                                                                \"symbol\": \"FX:EURUSD\",
                                                                \"interval\": \"5\",
                                                                \"timezone\": \"UTC\",
                                                                \"theme\": \"White\",
                                                                \"style\": \"8\",
                                                                \"toolbar_bg\": \"#f1f3f6\",
                                                                \"hide_side_toolbar\": false,
                                                                \"allow_symbol_change\": true,
                                                                \"hideideas\": true,
                                                                \"show_popup_button\": true,
                                                                \"popup_width\": \"1000\",
                                                                \"popup_height\": \"650\"
                                                            });
                                                        </script>
                                                    </section>
                                                </div>
                                            </div>
                                        </div>
                                    </div>";
    }} else{ echo "<img class=\"img-responsive\" src=\"images/No-Comments.png\" /> "; }

                    ?>
