<?php

$date_today = date('Y-m-d');
$featured_news = $db_handle->fetchAssoc($db_handle->runQuery("SELECT * FROM article WHERE status = '1' ORDER BY created DESC LIMIT 1"));
$featured_signal = $db_handle->fetchAssoc($db_handle->runQuery("SELECT * FROM signal_daily INNER JOIN signal_symbol ON signal_symbol.signal_symbol_id = signal_daily.symbol_id WHERE signal_date LIKE '$date_today'"));
$signal_last_updated = $db_handle->fetchAssoc($db_handle->runQuery("SELECT created FROM signal_daily ORDER BY created DESC LIMIT 1"));
?>
<div class="row ">
    <div class="col-sm-6 col-md-12">
        <div class="list-group" style="margin-bottom: 5px !important;">
            <a class="list-group-item" href="live_account.php" title="Open a live Instaforex trading account"><i class="fa fa-check-square fa-fw"></i>&nbsp;<strong>Open Live Account</strong></a>
            <a class="list-group-item" href="deposit_funds.php" title="Deposit money into your Instaforex account"><i class="fa fa-check-square fa-fw icon-tune"></i>&nbsp;<strong>Fund Account - &#8358;<?php if(defined('IPLRFUNDRATE')) { echo IPLRFUNDRATE; } ?>  / &dollar;1</strong></a>
            <a class="list-group-item" href="withdraw_funds.php" title="Withdraw from your Instaforex account"><i class="fa fa-check-square fa-fw icon-tune"></i>&nbsp;<strong>Withdraw - &#8358;<?php if(defined('WITHDRATE')) { echo WITHDRATE; } ?> / &dollar;1</strong></a>
            <a class="list-group-item" href="deposit_notify.php" title="Payment Notification"><i class="fa fa-check-square fa-fw"></i>&nbsp;<strong>Payment Notification</strong></a>
            <a class="list-group-item" href="verify_account.php" title="Verification"><i class="fa fa-check-square fa-fw"></i>&nbsp;<strong>Verification</strong></a>
        </div>
    </div>
</div>

<div class="row ">
    <div class="col-sm-6 col-md-12">
        <div class="nav-display super-shadow">
            <header><i class="fa fa-bars fa-fw"></i> Need Help?</header>
            <article class="text-center">

                <!-- livezilla.net PLACE WHERE YOU WANT TO SHOW GRAPHIC BUTTON -->
                <a href="javascript:void(window.open('https://instafxng.com/livechat/chat.php?v=2','','width=590,height=760,left=0,top=0,resizable=yes,menubar=no,location=no,status=yes,scrollbars=yes'))" class="lz_cbl"><img src="https://instafxng.com/livechat/image.php?id=4&type=inlay" width="210" height="66" style="border:0;" alt="LiveZilla Live Chat Software"></a>
                <!-- livezilla.net PLACE WHERE YOU WANT TO SHOW GRAPHIC BUTTON -->

                <!-- livezilla.net PLACE SOMEWHERE IN BODY -->
                <div id="lvztr_b5e" style="display:none"></div><script id="lz_r_scr_3adcd252cbea832dd6e9443fc0789dd5" type="text/javascript">lz_code_id="3adcd252cbea832dd6e9443fc0789dd5";var script = document.createElement("script");script.async=true;script.type="text/javascript";var src = "https://instafxng.com/livechat/server.php?rqst=track&output=jcrpt&nse="+Math.random();script.src=src;document.getElementById('lvztr_b5e').appendChild(script);</script>
                <!-- livezilla.net PLACE SOMEWHERE IN BODY -->

                <p><i class="fa fa-phone-square fa-fw"></i> +234 802 828 1192</p>
                <a class="btn btn-default" href="contact_info.php" title="Our full contact details">Contact Details</a>
            </article>
        </div>
    </div>
</div>

<div class="row ">
    <div class="col-sm-6 col-md-12">
        <div class="nav-display super-shadow">
            <header><i class="fa fa-bars fa-fw"></i> Latest Blog</header>

            <?php if(isset($featured_news)) { foreach($featured_news as $row) { ?>
            <article>
                <div class="blog-featured">
                    <div class="row">
                        <div class="col-sm-12 text-center">
                            <p><a href="news1/id/<?php echo $row['article_id'] . '/u/' . $row['url'] . '/'; ?>" title="Click to read"><?php echo $row['title']; ?></a></p>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-sm-12">
                            <?php if(file_exists("images/blog/{$row['display_image']}")) { ?>
                                <img style="max-height: 130px" class="img-responsive center-block" alt="" src="https://instafxng.com/images/blog/<?php echo $row['display_image']; ?>" />
                            <?php } else { ?>
                                <img  class="img-responsive center-block" alt="" src="https://instafxng.com/images/placeholder2.jpg" />
                            <?php } ?>
                        </div>
                    </div>

                    <br/>
                    <small><?php echo time_since($row['created']); ?></small>
                    <hr/>
                    <div class="row blog-featured-foot">
                        <div class="col-sm-12">
                            <i class="fa fa-eye fa-fw"></i> <?php echo $row['view_count']; ?> &nbsp;&nbsp;
                        </div>
                    </div>
                </div>
            </article>
            <?php } } else { echo "<em>No news to display</em>"; } ?>
            <article>
                <div class="text-center">
                    <a class="btn btn-default" href="blog.php" title="Click to visit our blog">More Blog Post</a>
                </div>
            </article>
        </div>
    </div>
</div>

<div class="row ">
    <div class="col-sm-6 col-md-12">
        <div class="nav-display super-shadow">
            <header><i class="fa fa-bars fa-fw"></i> Daily Forex Signal</header>
            <article>
                <small><em><strong>Date:</strong> <?php echo datetime_to_text2($date_today); ?></em></small>
                <table style="font-size: 0.8em; font-family: sans-serif;" class="table table-responsive table-striped table-bordered table-hover">
                    <thead>
                        <tr>
                            <th>Symbol</th>
                            <th>Order</th>
                            <th>Price</th>
                            <th>TP</th>
                            <th>SL</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php

                        if(isset($featured_signal)) { foreach($featured_signal as $row) {
                        ?>
                        <tr><td><?php echo $row['symbol']; ?></td><td><?php echo $row['order_type']; ?></td><td><?php echo $row['price']; ?></td><td><?php echo $row['take_profit']; ?></td><td><?php echo $row['stop_loss']; ?></td></tr>
                        <?php } } else { echo "<tr><td colspan='5' class='text-danger'><em>No results to display</em></td></tr>"; } ?>
                    </tbody>
                </table>
                <hr>
                <small>Your use of the signals means you have read and accepted our <a href="signal_terms_of_use.php" title="Forex Signal Terms of Use">terms of use</a>. Download the <a href="downloads/signalguide.pdf" target="_blank" title="Download signal guide">signal guide</a> to learn how to use the signals.</small>
            </article>
        </div>
    </div>
</div>

<div class="row ">
    <div class="col-sm-6 col-md-12">
        <div class="nav-display super-shadow">
            <header><i class="fa fa-bars fa-fw"></i> Live Forex Quotes</header>
            <article class="text-center"><object classid="clsid:d27cdb6e-ae6d-11cf-96b8-444553540000" codebase="https://download.macromedia.com/pub/shockwave/cabs/flash/swflash.cab#version=10,0,0,0" width="224" height="250" id="quotes" align="middle"><param name="allowScriptAccess" value="always" /><param name="allowFullScreen" value="false" /><param name="movie" value="https://informers.instaforex.com/i/img/quotes04.swf?time=10&url=https://quotes.instaforex.com/&target=_blank&url_name=https://informers.instaforex.com/?x=BBLR?x=BBLR" /><param name="quality" value="high" /><param name="wmode" value="transparent" /><embed src="https://informers.instaforex.com/i/img/quotes04.swf?time=10&url=https://quotes.instaforex.com/&target=_blank&url_name=https://informers.instaforex.com/?x=BBLR?x=BBLR" quality="high" wmode="transparent" width="224" height="250" name="tween" align="middle" allowScriptAccess="always" allowFullScreen="false" type="application/x-shockwave-flash" pluginspage="https://www.adobe.com/go/getflashplayer" /></object></article>
        </div>
    </div>
</div>

<div class="row ">
    <div class="col-sm-6 col-md-12">
        <div class="nav-display super-shadow">
            <header><i class="fa fa-bars fa-fw"></i> Economic Event Timer</header>
            <article class="text-center"><!-- InstaForex--><iframe src="https://informers.instaforex.com/event_countdown_timer/run/w=280&count=5&mode=horizontal_500_3000&bg=ffffff_e5e5e5_8_666_0_0_3_1_000000&bgl=ff0000_910000_ffffff_h&bgv=ff0000_910000_ffffff_h_666_3_8&tt=000000_h&ch=undefined&pg=0&cht=ff0000_910000&high=0&lh=&i=1&x=BBLR&type=0" frameborder="0" width="288" height="269" scrolling="no" id="iframesp" title="MT5 - Universal Forex (Forex) portal for traders"></iframe><noframes><a href="https://www.instaforex.com/">Forex Portal</a></noframes><!-- InstaForex--></article>
        </div>
    </div>
</div>