<?php
require_once 'init/initialize_general.php';
$thisPage = "Home";
?>
<!DOCTYPE html>
<html lang="en">
    <head>
        <!--........................................-->
        <style>
            .se-pre-con {
                position: fixed;
                left: 0px;
                top: 0px;
                width: 100%;
                height: 100%;
                z-index: 9999;
                background: url(images/Spinner.gif) center no-repeat #fff;
            }
        </style>
        <script src="http://ajax.googleapis.com/ajax/libs/jquery/1.5.2/jquery.min.js"></script>
        <script>$(window).load(function() {$(".se-pre-con").fadeOut("slow");});</script>
        <!--........................................-->
        <base target="_self">
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>Instaforex Nigeria | Online Instant Forex Trading Services</title>
        <meta name="title" content="Instaforex Nigeria | Online Instant Forex Trading Services" />
        <meta name="keywords" content="instaforex, forex trading in nigeria, forex seminar, forex trading seminar, how to trade forex, trade forex, instaforex nigeria">
        <meta name="description" content="Instaforex, a multiple award winning international forex broker is the leading Forex broker in Nigeria, open account and enjoy forex trading with us.">
        <?php require_once 'layouts/head_meta.php'; ?>
        <meta property="og:site_name" content="Instaforex Nigeria" />
        <meta property="og:title" content="Instaforex Nigeria | Online Instant Forex Trading Services" />
        <meta property="og:description" content="Instaforex, a multiple award winning international forex broker is the leading Forex broker in Nigeria, open account and enjoy forex trading with us." />
        <meta property="og:image" content="images/instaforex-100bonus.jpg" />
        <meta property="og:url" content="https://instafxng.com/" />
        <meta property="og:type" content="website" />
        <link rel="stylesheet" href="font-awesome-animation.min.css">
        <!--............................-->
        <link rel="stylesheet" href="https://unpkg.com/simplebar@latest/dist/simplebar.css" />
        <script src="https://unpkg.com/simplebar@latest/dist/simplebar.js"></script>
        <script>signal.show_extra_analysis(document.getElementById("accordion"));</script>
        <!--................................-->
    </head>
    <body>
    <!--.............................-->
        <div id="page_preloader" class="se-pre-con"></div>
    <!--......................-->
        <?php require_once 'layouts/header.php'; ?>
        <!-- Main Body: The is the main content area of the web site, contains a side bar  -->
        <div id="main-body" class="container-fluid">
            <div class="row no-gutter">
                <?php require_once 'layouts/topnav.php'; ?>

                <div id="main-body-side-bar" class="col-md-4 col-lg-3 ">
                    <?php require_once 'layouts/sidebar.php'; ?>
                </div>

                <!-- Main Body - Content Area: This is the main content area, unique for each page  -->
                <div id="main-body-content-area" class="col-md-8 col-lg-9 right-nav">
                    <!-- Unique Page Content Starts Here
                    ================================================== -->
                    <div class="super-shadow page-top-section">
                        <div class="row ">
                            <div class="col-sm-12">
                                <h3 class="text-center"><strong>FOREX TRADING SIGNALS</strong></h3>
                                <p class="text-center">
                                    <b>Trade the markets by following the best free trading signals!</b><br/>
                                    InstaFxNg's trading analysts spot market opportunities and provide
                                    you with profitable, easy to follow trading signals.
                                </p>
                            </div>
                        </div>
                    </div>

                    <div class="section-tint super-shadow">
                        <div class="row">
                            <?php require_once 'layouts/feedback_message.php'; ?>
                            <div class="col-sm-12">
                                <!-- TradingView Widget BEGIN -->
                                <div class="tradingview-widget-container">
                                    <div class="tradingview-widget-container__widget"></div>
                                    <script type="text/javascript" src="https://s3.tradingview.com/external-embedding/embed-widget-tickers.js" async>
                                        {
                                            "symbols": [
                                                {"title": "S&P 500", "proName": "INDEX:SPX"},
                                            {"title": "Nasdaq 100", "proName": "INDEX:IUXX"},
                                            {"title": "EUR/USD", "proName": "FX_IDC:EURUSD"},
                                            {"title": "BTC/USD", "proName": "BITFINEX:BTCUSD"},
                                            {"title": "ETH/USD", "proName": "BITFINEX:ETHUSD"}
                                        ],
                                            "locale": "en"
                                        }
                                    </script>
                                </div><br/>
                                <!-- TradingView Widget END -->
<!--                          <table class="table table-responsive table-striped table-bordered table-hover"><thead><tr><th><p><marquee id="live_quotes" behavior="scroll" direction="left" scrollamount="2"></marquee></p></th></tr></thead></table>-->
                            </div>

                            <div class="col-sm-12">
                                <div id="accordion" class="row grid">
                                    <div class="col-xs-12 col-sm-6 col-md-6 col-lg-4 col-xl-4 card grid-item">
                                        <div class="thumbnail">
                                            <div class="caption">
                                                <div class="row">
                                                    <div class="col-sm-2"></div>
                                                    <div class="col-sm-7">
                                                        <b id="thumbnail-label pull-left">USD/CAD (1.3164)</b>
                                                        <br/>
                                                        <span>Active...</span>
                                                    </div>
                                                    <div class="col-sm-3">
                                                        <b class="text-info pull-right">BUY</b>
                                                    </div>
                                                </div>
                                                <hr>
                                                <div class="well text-center"><b>ENTRY PRICE: 0.0000</b></div>
                                                <div class="row">
                                                    <div class="col-sm-6"><div class="well text-center"><span>0.0000<br/>Stop Loss</span></div></div>
                                                    <div class="col-sm-6"><div class="well text-center"><span>0.0000<br/>Take Profit</span></div></div>
                                                </div>
                                                <div class="row">
                                                    <div class="col-sm-12"><a target="_blank" href="https://webtrader.instaforex.com/login" class="btn btn-sm btn-success btn-group-justified">TRADE NOW</a><br/></div>
                                                </div>
                                                <div class="row">
                                                    <div class="col-sm-9"><a class="accordion-toggle" href="#"><span>FURTHER ANALYSIS  </span><b class="caret"></b></a></div>
                                                    <div class="col-sm-3"><i class="glyphicon glyphicon-star-empty"></i></div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="body col-xs-12 col-sm-12 col-md-12 col-lg-12 col-xl-12 card">
                                        <div class="thumbnail">
                                            <div class="caption">
                                                <div class="row">
                                                    <div  class="col-sm-6 col-xs-12">
                                                        <script>
                                                            signal.get_news('USD/CAD');
                                                            new SimpleBar(document.getElementById('myElement'));
                                                        </script>
                                                        <div id="myElement" style="height: 230px; overflow-y: scroll;" data-simplebar data-simplebar-auto-hide="true" class="row">
                                                            <div class="row col-sm-12 col-xs-12">
                                                                <div class="col-sm-4 col-xs-4">
                                                                    <img class="img-responsive" alt="" src="https://editorial.azureedge.net/images/Macroeconomics/CentralBanks/BOC/Bank_of_Canada2_2016_Large.jpg" />
                                                                </div>
                                                                <div class="col-sm-8 col-xs-8">
                                                                    <b class="text-justify" style="font-size: small !important;"><a>Long USDCAD into the BoC meeting - TDS</a></b><br/>
    <!--                                                                <p class="text-justify" style="font-size: x-small !important;">The BoC meets this morning and despite Poloz's anti-guidance and rapidly shifting expectations of a rate hike, the market universally looks for a hike today, suggests the research team at TD Securities. Key Quotes “In the context of global trade tensions and …</p>
    -->                                                             <span class="text-justify" style="font-size: small !important;">Posted:2018-07-11 12:56:23 PM</span>
                                                                </div>
                                                                <div class="col-sm-12"><hr/></div>
                                                            </div>
                                                            <div class="row col-sm-12 col-xs-12">
                                                                <div class="col-sm-4 col-xs-4">
                                                                    <img class="img-responsive" alt="" src="https://editorial.azureedge.net/images/Macroeconomics/CentralBanks/BOC/Bank_of_Canada2_2016_Large.jpg" />
                                                                </div>
                                                                <div class="col-sm-8 col-xs-8">
                                                                    <b class="text-justify" style="font-size: small !important;"><a>Long USDCAD into the BoC meeting - TDS</a></b><br/>
                                                                    <!--                                                                <p class="text-justify" style="font-size: x-small !important;">The BoC meets this morning and despite Poloz's anti-guidance and rapidly shifting expectations of a rate hike, the market universally looks for a hike today, suggests the research team at TD Securities. Key Quotes “In the context of global trade tensions and …</p>
                                                                    -->                                                             <span class="text-justify" style="font-size: small !important;">Posted:2018-07-11 12:56:23 PM</span>
                                                                </div>
                                                                <div class="col-sm-12"><hr/></div>
                                                            </div>
                                                            <div class="row col-sm-12 col-xs-12">
                                                                <div class="col-sm-4 col-xs-4">
                                                                    <img class="img-responsive" alt="" src="https://editorial.azureedge.net/images/Macroeconomics/CentralBanks/BOC/Bank_of_Canada2_2016_Large.jpg" />
                                                                </div>
                                                                <div class="col-sm-8 col-xs-8">
                                                                    <b class="text-justify" style="font-size: small !important;"><a>Long USDCAD into the BoC meeting - TDS</a></b><br/>
                                                                    <!--                                                                <p class="text-justify" style="font-size: x-small !important;">The BoC meets this morning and despite Poloz's anti-guidance and rapidly shifting expectations of a rate hike, the market universally looks for a hike today, suggests the research team at TD Securities. Key Quotes “In the context of global trade tensions and …</p>
                                                                    -->                                                             <span class="text-justify" style="font-size: small !important;">Posted:2018-07-11 12:56:23 PM</span>
                                                                </div>
                                                                <div class="col-sm-12"><hr/></div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div style="height: 230px" class="col-sm-6 col-xs-12">
                                                        <!-- TradingView Widget BEGIN -->
                                                        <div class="tradingview-widget-container">
                                                            <div class="tradingview-widget-container__widget"></div>
                                                            <script type="text/javascript" src="https://s3.tradingview.com/external-embedding/embed-widget-market-overview.js" async>
                                                                {
                                                                    "showChart": true,
                                                                    "locale": "en",
                                                                    "width": "100%",
                                                                    "height": "100%",
                                                                    "largeChartUrl": "",
                                                                    "plotLineColorGrowing": "rgba(60, 188, 152, 1)",
                                                                    "plotLineColorFalling": "rgba(255, 74, 104, 1)",
                                                                    "gridLineColor": "rgba(233, 233, 234, 1)",
                                                                    "scaleFontColor": "rgba(233, 233, 234, 1)",
                                                                    "belowLineFillColorGrowing": "rgba(60, 188, 152, 0.05)",
                                                                    "belowLineFillColorFalling": "rgba(255, 74, 104, 0.05)",
                                                                    "symbolActiveColor": "rgba(242, 250, 254, 1)",
                                                                    "tabs": [
                                                                    {
                                                                        "title": "Forex",
                                                                        "symbols": [
                                                                            {
                                                                                "s": "FX:USDJPY"
                                                                            }
                                                                        ],
                                                                        "originalTitle": "Forex"
                                                                    }
                                                                ]
                                                                }
                                                            </script>
                                                        </div>
                                                        <!-- TradingView Widget END -->
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- Unique Page Content Ends Here
                    ================================================== -->
                </div>
                <!-- Main Body - Side Bar  -->
            </div>
        </div>
        <?php require_once 'layouts/footer.php'; ?>
    </body>
<!--    <script>document.getElementById('page_preloader').style.display = 'none'</script>
-->
</html>