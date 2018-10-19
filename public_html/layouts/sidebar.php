<?php

$date_today = date('Y-m-d');
$featured_news = $db_handle->fetchAssoc($db_handle->runQuery("SELECT * FROM article WHERE status = '1' ORDER BY created DESC LIMIT 3"));
$featured_signal = $db_handle->fetchAssoc($db_handle->runQuery("SELECT * FROM signal_daily INNER JOIN signal_symbol ON signal_symbol.signal_symbol_id = signal_daily.symbol_id WHERE signal_date LIKE '$date_today'"));
$signal_last_updated = $db_handle->fetchAssoc($db_handle->runQuery("SELECT created FROM signal_daily ORDER BY created DESC LIMIT 1"));
?>
<div class="row ">
    <div class="col-sm-6 col-md-12">
        <div class="list-group" style="margin-bottom: 5px !important;">
            <a class="list-group-item" href="live_account.php" title="Open a live Instaforex trading account"><i class="fa fa-check-square fa-fw"></i>&nbsp;<strong>Open Live Account</strong></a>
            <a class="list-group-item" href="deposit_funds.php" title="Deposit money into your Instaforex account"><i class="fa fa-check-square fa-fw icon-tune"></i>&nbsp;<strong>Fund Account - &#8358;<?php if(defined('IPLRFUNDRATE')) { echo IPLRFUNDRATE; } ?> </strong></a>
            <a class="list-group-item" href="withdraw_funds.php" title="Withdraw from your Instaforex account"><i class="fa fa-check-square fa-fw icon-tune"></i>&nbsp;<strong>Withdraw - &#8358;<?php if(defined('WITHDRATE')) { echo WITHDRATE; } ?> </strong></a>
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
            <div class="">
            <div id="myCarousel" class="carousel slide" data-ride="carousel">
                <!-- Indicators -->
                <ol class="carousel-indicators">
                    <li data-target="#myCarousel" data-slide-to="0" class="active"></li>
                    <li data-target="#myCarousel" data-slide-to="1"></li>
                    <li data-target="#myCarousel" data-slide-to="2"></li>
                    <li data-target="#myCarousel" data-slide-to="3"></li>
                </ol>

                <!-- Wrapper for slides -->
                <div class="carousel-inner" style="height: 350px;">

                    <div class="item active">
                        <img src="images/blog_img.png" class="img img-responsive"  alt="" style="width: 100%; height: 350px;">
                    </div>

                <?php if(isset($featured_news)) { foreach($featured_news as $row) { ?>
                    <div class="item blog-featured">
                        <article>
                        <div class="col-sm-12 text-center">
                            <p><a href="news1/id/<?php echo $row['article_id'] . '/u/' . $row['url'] . '/'; ?>" title="Click to read"><?php echo substr($row['title'], 0, 60); ?>...</a></p>
                        </div>
                        <?php if(file_exists("images/blog/{$row['display_image']}")) { ?>
                            <img style="max-height: 130px" class="img-responsive center-block" alt="" src="https://instafxng.com/images/blog/<?php echo $row['display_image']; ?>" />
                        <?php } else { ?>
                            <img  class="img-responsive center-block" alt="" src="https://instafxng.com/images/placeholder2.jpg" />
                        <?php } ?>
                        <div class="carousel-caption">
                            <div class="row">

                            </div>
                        </div>
                        <div class=" blog-featured-foot">
                            <hr>
                            <small class="pull-left"><?php echo time_since($row['created']); ?></small>

                            <div class="pull-right">
                                <i class="fa fa-eye fa-fw"></i> <?php echo $row['view_count']; ?>
                            </div>
                            <hr>
                        </div>
                        </article>
                    </div>
                <?php } } else { echo "<em>No news to display</em>"; } ?>
                </div>

                <!-- Left and right controls -->
                <a class="left carousel-control" href="#myCarousel" data-slide="prev">
                    <span class="glyphicon glyphicon-chevron-left"></span>
                    <span class="sr-only">Previous</span>
                </a>
                <a class="right carousel-control" href="#myCarousel" data-slide="next">
                    <span class="glyphicon glyphicon-chevron-right"></span>
                    <span class="sr-only">Next</span>
                </a>
            </div>
            <article>
                <div class="text-center">
                    <a class="btn btn-default" href="blog.php" title="Click to visit our blog">More Blog Post</a>
                </div>
            </article>
        </div>
    </div>
</div>
    </div>

<section id="signals">
<div class="row ">
    <div class="col-sm-6 col-md-12">
        <div class="nav-display super-shadow">
           <?php include "signals_sidebar.php"; ?>
        </div>
    </div>
</div>
</section>

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