<?php
session_start();
require_once '../init/initialize_client.php';
extract($_SESSION);
$open_account = true;
// TODO: What next after account opening... Fix that.
// This section processes - views/live_account_ilpr_reg.php
if(isset($_POST['live_account_ilpr_reg'])) {
	//$page_requested = "live_account_ilpr_reg_php";
	$secret = '6LcKDhATAAAAALn9hfB0-Mut5qacyOxxMNOH6tov';
	$verifyResponse = file_get_contents('https://www.google.com/recaptcha/api/siteverify?secret='.$secret.'&response='.$_POST['g-recaptcha-response']);
	$responseData = json_decode($verifyResponse);

	$account_no = $db_handle->sanitizePost($_POST['ifx_acct_no']);
	$full_name = $db_handle->sanitizePost($_POST['full_name']);
	$email_address = $db_handle->sanitizePost($_POST['email']);
	$phone_number = $db_handle->sanitizePost($_POST['phone']);

	if(!$responseData->success) {
		$message_error = "You did not pass the robot verification, please try again.";
	} elseif(empty($full_name) || empty($email_address) || empty($phone_number) || empty($account_no)) {
		$message_error = "All fields must be filled.";
	} elseif (!check_email($email_address)) {
		$message_error = "You have provided an invalid email addresss. Please try again.";
	} else {

		$client_operation = new clientOperation();
		$log_new_ifxaccount = $client_operation->new_user($account_no, $full_name, $email_address, $phone_number, $type = 2, $my_refferer, $attendant, $source);
		$log_new_ifxaccount = true;
		if($log_new_ifxaccount)
		{
			$open_account = false;
			$open_complete = true;
		}
		else
		{
			$open_account = true;
			$open_complete = false;
			$message_error = "Something went wrong, the operation could not be completed. Please try again.";
		}
	}
}
?>
<!DOCTYPE html>
<html>
	<head>
		<title>Instafxng Loyalty Promotions And Rewards</title>
		<meta name="viewport" content="width=device-width, initial-scale=1">
		<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
		<meta name="keywords" content=""/>
		<script type="application/x-javascript">
			addEventListener("load", function () { setTimeout(hideURLbar, 0); }, false);
			function hideURLbar() {	window.scrollTo(0, 1); }
		</script>
		<link rel="shortcut icon" type="image/x-icon" href="../images/favicon.png">
		<link href="css/bootstrap.css" rel="stylesheet" type="text/css" media="all" />
		<link href="css/style.css" rel="stylesheet" type="text/css" media="all" />
		<link href="css/prettyPhoto.css" rel="stylesheet" type="text/css" />
		<link href="css/font-awesome.css" rel="stylesheet">
		<link href="//fonts.googleapis.com/css?family=Raleway:100,100i,200,300,300i,400,400i,500,500i,600,600i,700,800" rel="stylesheet">
		<link href="//fonts.googleapis.com/css?family=Source+Sans+Pro:300,300i,400,400i,600,600i,700" rel="stylesheet">
	</head>
	<body>
		<div class="top_header" id="home">
			<!-- Fixed navbar    navbar-fixed-top-->
			<nav class="navbar navbar-default ">
				<div class="nav_top_fx_w3ls_agileinfo">
					<div class="navbar-header">
						<button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#navbar" aria-expanded="false" aria-controls="navbar">
							<span class="sr-only">Toggle navigation</span>
							<span class="icon-bar"></span>
							<span class="icon-bar"></span>
							<span class="icon-bar"></span>
						</button>
						<div class="logo-w3layouts-agileits">
							<h1>
								<a class="navbar-brand" href="index.php">
									<img src="../images/ifxlogo.png" class="img-responsive" />
								</a>
							</h1>
						</div>
					</div>
					<div id="navbar" class="navbar-collapse collapse">
						<div class="nav_right_top">
							<ul class="nav navbar-nav navbar-right">
								<!--<li><a class="request" href="#" data-toggle="modal" data-target="#myModal">Get Started</a></li>-->

								<li class="dropdown">
									<a class="request dropdown-toggle" data-toggle="dropdown" href="#"><i class="fa fa-history fa-fw"></i> About
										<span class="caret"></span></a>
									<ul class="dropdown-menu">
										<li><a href="https://instaforex.com/about_us.php?x=BBLR" title="Instaforex" target="_blank">Instaforex</a></li>
										<li><a href="https://instafxng.com/view_news.php" title="Instaforex Nigeria News Centre">Company News</a></li>
										<li><a href="https://instafxng.com/photo_gallery.php" title="Photo Gallery">Photo Gallery</a></li>
										<li><a href="https://instafxng.com/video_gallery.php" title="Video Gallery">Video Gallery</a></li>
									</ul>
								</li>
								<li class="dropdown <?php if ($thisPage == "Traders") { echo " active"; } ?>">
									<a class="request dropdown-toggle" data-toggle="dropdown" href="#"><i class="fa fa-line-chart fa-fw"></i> Traders
										<span class="caret"></span></a>
									<ul class="dropdown-menu">
										<li><a href="https://instafxng.com/deposit_funds.php" title="Deposit Funds">Deposit Funds</a></li>
										<li><a href="https://instafxng.com/withdraw_funds.php" title="Withdraw Funds">Withdraw Funds</a></li>
										<li><a href="https://instafxng.com/deposit_notify.php" title="Payment Notification">Payment Notification</a></li>
										<li><a href="https://instafxng.com/forex_bonus.php" title="30% Bonus On Deposit">30% Bonus On Deposit</a></li>
										<li><a href="https://instafxng.com/view_signal.php" title="Free Forex Signals">Forex Signals</a></li>
										<li><a href="https://instafxng.com/instaforex_tv.php" title="Instaforex TV">Instaforex TV</a></li>
										<li><a href="https://www.instaforex.com/55bonus.php?x=BBLR" title="55% Deposit Bonus" target="_blank">55% Deposit Bonus</a></li>
										<li><a href="https://www.instaforex.com/nodeposit_bonus.php?x=BBLR" title="No Deposit Bonus" target="_blank">No Deposit Bonus</a></li>
										<li><a href="https://instaforex.com/trading_conditions.php?x=BBLR" title="Instaforex Trading Conditions" target="_blank">Trading Conditions</a></li>
										<li><a href="https://instaforex.com/pamm_system.php?x=BBLR" title="PAMM System" target="_blank">PAMM System</a></li>
										<li><a href="https://instaforex.com/forex_options.php?x=BBLR" title="Forex Options" target="_blank">Forex Options</a></li>
										<li><a href="https://instaforex.com/forexcopy_system.php?x=BBLR" title="ForexCopy System" target="_blank">ForexCopy System</a></li>
									</ul>
								</li>
								<li class="dropdown <?php if ($thisPage == "Education") { echo " active"; } ?>">
									<a class="request dropdown-toggle" data-toggle="dropdown" href="#"><i class="fa fa-book fa-fw"></i> Education
										<span class="caret"></span></a>
									<ul class="dropdown-menu">
										<li><a href="https://instafxng.com/forex_profit_academy.php" title="Forex Profit Academy">Forex Profit Academy</a></li>
										<li><a href="https://instafxng.com/traders_forum.php" title="Forex Traders Forum">Traders Forum</a></li>
									</ul>
								</li>
								<li class="<?php if ($thisPage == "Promotion") { echo " active"; } ?>"><a class="request" href="promo.php" title="Instaforex Promotions"><i class="fa fa-bookmark fa-fw"></i> Promo</a></li>
								<li class="dropdown <?php if ($thisPage == "Account") { echo " active"; } ?>">
									<a class="request dropdown-toggle" data-toggle="dropdown" href="#"><i class="fa fa-dollar fa-fw"></i> Open Account
										<span class="caret"></span></a>
									<ul class="dropdown-menu">
										<li><a href="https://instaforex.com/downloads.php?x=BBLR" title="Download Trading Terminal" target="_blank">Download Trading Terminal</a></li>
									</ul>
								</li>
								<li class="dropdown <?php if ($thisPage == "Support") { echo " active"; } ?>">
									<a class="request dropdown-toggle" data-toggle="dropdown" href="#"><i class="fa fa-phone fa-fw"></i> Support
										<span class="caret"></span></a>
									<ul class="dropdown-menu">
										<li><a href="https://instafxng.com/contact_info.php" title="Contact">Contact Info</a></li>
										<li><a href="https://instafxng.com/faq.php" title="Frequently Asked Questions">FAQ</a></li>
										<li><a href="https://instafxng.com/careers.php" title="Careers" target="_blank">Careers</a></li>
									</ul>
								</li>
							</ul>
						</div>
					</div>
				</div>
			</nav>
		</div>
		<div class="modal video-modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModal">
			<div class="modal-dialog" role="document">
				<div class="modal-content">
					<div class="modal-header">
						<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
						<br/>
					</div>
					<div class="modal-body">
						<div style="padding: 10px">
							<h4 class="text-justify">Enroll your new instaforex account into the <b>INSTAFXNG LOYALTY PROGRAM AND REWARDS</b></h4>
							<br/>
							<form data-toggle="validator" class="form-horizontal" role="form" method="post" action="">
								<?php if(isset($message_success)): ?>
									<div class="alert alert-success">
										<a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
										<strong>Success!</strong> <?php echo $message_success; ?>
									</div>
								<?php endif ?>
								<?php if(isset($message_error)): ?>
									<div class="alert alert-danger">
										<a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
										<strong>Oops!</strong> <?php echo $message_error; ?>
									</div>

								<?php endif;?>
								<div class="form-group">
									<div class="col-sm-12">
										<input value="<?php echo $f_name." ".$m_name." ".$l_name;?>" placeholder="First name and surname" name="full_name" type="text" class="form-control" id="full_name" required>
									</div>
								</div>
								<div class="form-group">
									<div class="col-sm-12"><input value="<?php echo $email;?>" name="email" placeholder="Email Address" type="email"  class="form-control" maxlength="50" required></div>
								</div>
								<div class="form-group">
									<div class="col-sm-12"><input value="<?php echo $phone;?>" name="phone" placeholder="080********" type="text" class="form-control" maxlength="11" required></div>
								</div>
								<div class="form-group">
									<div class="col-sm-12">
										<input placeholder="New Instaforex Account Number" name="ifx_acct_no" type="text" class="form-control" id="ifx_acct_no" required>
									</div>
								</div>
                                <p class="text-muted" ><strong> Help <i class="fa fa-exclamation"></i></strong> Us Fight Spam.</p>
								<div class="form-group">
									<div class="col-sm-12">
										<div class="g-recaptcha" data-sitekey="6LcKDhATAAAAAF3bt-hC_fWA2F0YKKpNCPFoz2Jm"></div>
									</div>
								</div>
								<div class="col-sm-12">
									<span style="color: #ffffff">*All fields are required</span>
								</div>
								<div class="form-group">
									<div class="col-sm-12">
										<input name="live_account_ilpr_reg" type="submit" class="btn btn-success btn-lg btn-group-justified"  value="Get Me Started Now!"/>
									</div>

								</div>
							</form>
						</div>
					</div>
				</div>
			</div>
		</div>

		<div class="banner_bottom">
			<div class="clearfix"></div>
			<div class="col-md-4 ">
				<div class="col-md-12 ">
					<div class="col-md-12 ">
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
									<a class="list-group-item" href="https://instafxng.com/deposit_funds.php" title="Deposit money into your Instaforex account"><i class="fa fa-check-square fa-fw icon-tune"></i>&nbsp;<strong>Fund Account - &#8358;<?php if(defined('IPLRFUNDRATE')) { echo IPLRFUNDRATE; } ?>  / &dollar;1</strong></a>
									<a class="list-group-item" href="https://instafxng.com/withdraw_funds.php" title="Withdraw from your Instaforex account"><i class="fa fa-check-square fa-fw icon-tune"></i>&nbsp;<strong>Withdraw - &#8358;<?php if(defined('WITHDRATE')) { echo WITHDRATE; } ?> / &dollar;1</strong></a>
									<a class="list-group-item" href="https://instafxng.com/deposit_notify.php" title="Payment Notification"><i class="fa fa-check-square fa-fw"></i>&nbsp;<strong>Payment Notification</strong></a>
									<a class="list-group-item" href="https://instafxng.com/verify_account.php" title="Verification"><i class="fa fa-check-square fa-fw"></i>&nbsp;<strong>Verification</strong></a>
								</div>
							</div>
						</div>
						<br/>
						<br/>
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
										<a class="btn btn-default" href="https://instafxng.com/contact_info.php" title="Our full contact details">Contact Details</a>
									</article>
								</div>
							</div>
						</div>
						<br/>
						<br/>
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
														<p><i class="fa fa-eye fa-fw"></i> <?php echo $row['view_count']; ?></p> &nbsp;&nbsp;
													</div>
												</div>
											</div>
										</article>
									<?php } } else { echo "<em>No news to display</em>"; } ?>
									<article>
										<div class="text-center">
											<a class="btn btn-default" href="https://instafxng.com/blog.php" title="Click to visit our blog">More Blog Post</a>
										</div>
									</article>
								</div>
							</div>
						</div>
						<br/>
						<br/>
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
										<small>Your use of the signals means you have read and accepted our <a href="https://instafxng.com/signal_terms_of_use.php" title="Forex Signal Terms of Use">terms of use</a>. Download the <a href="https://instafxng.com/downloads/signalguide.pdf" target="_blank" title="Download signal guide">signal guide</a> to learn how to use the signals.</small>
									</article>
								</div>
							</div>
						</div>
						<br/>
						<br/>
						<div class="row ">
							<div class="col-sm-6 col-md-12">
								<div class="nav-display super-shadow">
									<header><i class="fa fa-bars fa-fw"></i> Live Forex Quotes</header>
									<article class="text-center"><object classid="clsid:d27cdb6e-ae6d-11cf-96b8-444553540000" codebase="https://download.macromedia.com/pub/shockwave/cabs/flash/swflash.cab#version=10,0,0,0" width="224" height="250" id="quotes" align="middle"><param name="allowScriptAccess" value="always" /><param name="allowFullScreen" value="false" /><param name="movie" value="https://informers.instaforex.com/i/img/quotes04.swf?time=10&url=https://quotes.instaforex.com/&target=_blank&url_name=https://informers.instaforex.com/?x=BBLR?x=BBLR" /><param name="quality" value="high" /><param name="wmode" value="transparent" /><embed src="https://informers.instaforex.com/i/img/quotes04.swf?time=10&url=https://quotes.instaforex.com/&target=_blank&url_name=https://informers.instaforex.com/?x=BBLR?x=BBLR" quality="high" wmode="transparent" width="224" height="250" name="tween" align="middle" allowScriptAccess="always" allowFullScreen="false" type="application/x-shockwave-flash" pluginspage="https://www.adobe.com/go/getflashplayer" /></object></article>
								</div>
							</div>
						</div>
						<br/>
						<br/>
						<div class="row ">
							<div class="col-sm-6 col-md-12">
								<div class="nav-display super-shadow">
									<header><i class="fa fa-bars fa-fw"></i> Economic Event Timer</header>
									<article class="text-center"><!-- InstaForex--><iframe src="https://informers.instaforex.com/event_countdown_timer/run/w=280&count=5&mode=horizontal_500_3000&bg=ffffff_e5e5e5_8_666_0_0_3_1_000000&bgl=ff0000_910000_ffffff_h&bgv=ff0000_910000_ffffff_h_666_3_8&tt=000000_h&ch=undefined&pg=0&cht=ff0000_910000&high=0&lh=&i=1&x=BBLR&type=0" frameborder="0" width="288" height="269" scrolling="no" id="iframesp" title="MT5 - Universal Forex (Forex) portal for traders"></iframe><noframes><a href="https://www.instaforex.com/">Forex Portal</a></noframes><!-- InstaForex--></article>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
			<div class="col-md-8 banner_bottom_left">
				<?php if($open_account){include 'views/account_open.php';} ?>
				<?php if($open_complete){include 'views/open_complete.php';} ?>
			</div>
			<div class="clearfix"></div>
		</div>
		<div class="banner_bottom">
			<div class="col-md-12 banner_bottom_grid help">
				<div class="col-md-12 banner_bottom_grid help">
					<div class="col-md-12 banner_bottom_grid help">

						<!-- Footer Section: Copyright, Site Map  -->
						<footer id="footer" class="super-shadow">
							<div class="container-fluid no-gutter">
								<div class="col-sm-12">
									<div class="row">
										<div class="col-md-3">
											<header><i class="fa fa-tty fa-fw icon-tune"></i> <strong>Contact Details</strong></header>
											<article>
												<p><strong><span class="text-danger">Head Office Address</span></strong><br />Block 1A, Plot 8, Diamond Estate, Estate Bus-Stop, LASU/Isheri road, Isheri-Olofin, Lagos.
													<br /><strong>Support Email:</strong> support@instafxng.com<br /><strong>Phone:</strong> 08028281192, 08182045184, 08084603182</p>

												<p><strong><span class="text-danger">Lekki Office Address</span></strong><br />Block A3, Suite 508/509 Eastline Shopping Complex, Opposite Abraham Adesanya Roundabout, along Lekki - Epe expressway, Lagos.
													<br /><strong>Phone:</strong> 08139250268, 08083956750</p>
											</article>
										</div>

										<div class="col-md-3">
											<header><i class="fa fa-sitemap fa-fw icon-tune"></i> <strong>We're Social</strong></header>
											<article>
												<a href="https://facebook.com/InstaForexNigeria" target="_blank" title="Facebook"><img src="../images/Facebook.png"></a>
												<a href="https://twitter.com/instafxng" target="_blank" title="Twitter"><img src="../images/Twitter.png"></a>
												<a href="https://www.instagram.com/instafxng/" target="_blank" title="Instagram"><img src="../images/instagram.png"></a>
												<a href="https://www.youtube.com/channel/UC0Z9AISy_aMMa3OJjgX6SXw" target="_blank" title="Youtube Channel"><img src="../images/Youtube.png"></a>
												<a href="https://linkedin.com/company/instaforex-ng" target="_blank" title="LinkedIn"><img src="../images/LinkedIn.png"></a>
												<br />
											</article>

											<header><i class="fa fa-sitemap fa-fw icon-tune"></i> <strong>Instaforex Traders</strong></header>
											<article>
												<ul>
													<li><a href="https://instafxng.com/events_calendar.php" title="Events Calendar">Events Calendar</a></li>
													<li><a href="https://instafxng.com/forex_news.php" title="Forex News">Forex News</a></li>
													<li><a href="https://instafxng.com/market_analysis.php" title="Market Analysis">Market Analysis</a></li>
													<li><a href="https://instafxng.com/instaforex_tv.php" title="InstaForex TV">InstaForex TV</a></li>
													<li><a href="https://instaforex.com/pamm_system.php?x=BBLR" target="_blank" title="PAMM System">PAMM System</a></li>
													<li><a href="https://instaforex.com/forex_options.php?x=BBLR" target="_blank" title="Forex Options">Forex Options</a></li>
													<li><a href="https://instaforex.com/forexcopy_system.php?x=BBLR" target="_blank" title="ForexCopy System">ForexCopy System</a></li>
													<li><a href="https://instaforex.com/downloads.php?x=BBLR" target="_blank" title="Download Trading Terminal">Download Trading Terminal</a></li>
													<li><a href="https://instafxng.com/eaindicator_install.php" title="EA - Indicator Installation">EA - Indicator Installation</a></li>
												</ul>
											</article>
										</div>

										<div class="col-md-3">
											<header><i class="fa fa-sitemap fa-fw icon-tune"></i> <strong>Forex Education</strong></header>
											<article>
												<ul>
													<li><a href="https://instafxng.com/forex_profit_academy.php" title="Forex Profit Academy">Forex Profit Academy</a></li>
												</ul>
											</article>
											<header><i class="fa fa-sitemap fa-fw icon-tune"></i> <strong>Sponsored</strong></header>
											<article>
												<ul>
													<li><a href="tourism/" title="Tourism in Lagos">Miss Tourism Contest</a></li>
												</ul>
											</article>
										</div>

										<div class="col-md-3">
											<header><i class="fa fa-sitemap fa-fw icon-tune"></i> <strong>Promotions & Contest</strong></header>
											<article>
												<ul>
													<!--<li><a href="monthly_cash_back.php" title="Monthly Cash Back">$50 Monthly Cash Back</a></li>-->
													<li><a href="https://instafxng.com/loyalty.php" title="Point Based Loyalty Reward">Point Based Loyalty Reward</a></li>
													<li><a href="https://instafxng.com/egg_hunt.php" title="Easter Egg Hunt">Easter Egg Hunt</a></li>
													<li><a href="https://instafxng.com/forex_bonanza.php" title="Instafxng December Bonanza">Instafxng December Bonanza</a></li>
													<li><a href="https://instafxng.com/forex_hyundai_sonata.php" title="InstaForex Hyundai Sonata Promotion">Hyundai Sonata Promotion</a></li>
													<li><a href="https://instafxng.com/forex_bmw_contest.php" title="BMW X6 from Instaforex">BMW X6 Promotion</a></li>
													<li><a href="https://instafxng.com/forex_100bonus.php" title="100% LFC Partnership Bonus">100% LFC Bonus</a></li>
													<li><a href="https://instafxng.com/spring_in_liverpool.php" title="Spring in Liverpool by Instaforex">Spring in Liverpool</a></li>
													<li><a href="https://instafxng.com/hyundai_accent.php" title="InstaForex Hyundai Accent Promotion">Hyundai Accent Promotion</a></li>
													<li><a href="https://instafxng.com/kia_picanto.php" title="InstaForex Kia Picanto">KIA Picanto Promotion</a></li>
												</ul>
												<br />
												<span style="font-size: 12px; font-weight: bold">Visit <a href="https://instaforex.com" target="_blank" title="Instaforex">InstaForex.com</a> for more about Instaforex</span>
											</article>
										</div>
									</div>
								</div>
							</div>

							<div class="container-fluid no-gutter copyright">
								<div class="col-sm-12">
									<small class="text-left">
                        <span class="text-danger">
                            <strong>WARNING:</strong>
                        </span>
										<strong>Foreign Exchange Trading and Investment </strong>
										in derivatives can be very speculative and may result in
										losses as well as profits. Foreign Exchange and Derivatives Trading
										is not suitable for many members of the public and only risk capital
										should be applied. The website does not take into account special
										investment goals, the financial institution or specific requirements
										of individual users. We will not accept liability for any loss or
										damage, including without limitation to, any loss of profit, which
										may arise directly or indirectly from use of or reliance on information
										contained on this site or in your trading. You should carefully
										consider your financial situation and consult your financial advisors
										as to the suitability to your situation prior making any investment
										or entering into any transactions.
									</small>
									<hr>
									<p class="text-center">&copy; <?php echo date('Y'); ?>, All rights reserved. Instant Web-Net Technologies Limited (www.instafxng.com)</p>
								</div>
							</div>
						</footer>

						<!--LiveZilla Tracking Code (ALWAYS PLACE IN BODY ELEMENT)-->
						<div id="livezilla_tracking" style="display:none"></div><script type="text/javascript">
							var script = document.createElement("script");script.async=true;script.type="text/javascript";var src = "https://instafxng.com/livechat/server.php?a=3a1a8&rqst=track&output=jcrpt&nse="+Math.random();setTimeout("script.src=src;document.getElementById('livezilla_tracking').appendChild(script)",1);</script><noscript><img src="https://instafxng.com/livechat/server.php?a=3a1a8&amp;rqst=track&amp;output=nojcrpt" width="0" height="0" style="visibility:hidden;" alt=""></noscript>
						<!--http://www.LiveZilla.net Tracking Code-->

						<script>
							(function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
									(i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
								m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
							})(window,document,'script','//www.google-analytics.com/analytics.js','ga');

							ga('create', 'UA-69536508-1', 'auto');
							ga('send', 'pageview');
						</script>

						<?php if($db_handle) { mysqli_close($db_handle); } ?>
					</div>
				</div>
			</div>
			<div class="clearfix"></div>
		</div>

		<script type="text/javascript" src="js/jquery-2.2.3.min.js"></script>
		<script type="text/javascript" src="js/bootstrap.js"></script>
		<script>
			$('ul.dropdown-menu li').hover(function () {
				$(this).find('.dropdown-menu').stop(true, true).delay(200).fadeIn(500);
			}, function () {
				$(this).find('.dropdown-menu').stop(true, true).delay(200).fadeOut(500);
			});
		</script>
		<script type="text/javascript" src="js/easing.js"></script>
		<script type="text/javascript" src="js/move-top.js"></script>
		<script type="text/javascript">
			jQuery(document).ready(function ($) {
				$(".scroll, .navbar li a, .footer li a").click(function (event) {
					$('html,body').animate({
						scrollTop: $(this.hash).offset().top
					}, 1000);
				});
			});
		</script>
		<script type="text/javascript">
			$(document).ready(function () {
				/*
										var defaults = {
											containerID: 'toTop', // fading element id
											containerHoverID: 'toTopHover', // fading element hover id
											scrollSpeed: 1200,
											easingType: 'linear'
										};
										*/

				$().UItoTop({
					easingType: 'easeOutQuart'
				});

			});
		</script>
		<a href="#home" class="scroll" id="toTop" style="display: block;"> <span id="toTopHover" style="opacity: 1;"> </span></a>
		<script type="text/javascript" src="js/jquery-1.7.2.js"></script>
		<script src="js/jquery.quicksand.js" type="text/javascript"></script>
		<script src="js/script.js" type="text/javascript"></script>
		<script src="js/jquery.prettyPhoto.js" type="text/javascript"></script>
		<script src='https://www.google.com/recaptcha/api.js'></script>
		<?php if(isset($message_success) || isset($message_error)): ?>
			<script>
				document.getElementById('gt').click();
			</script>
		<?php endif; ?>
	</body>
</html>