<?php
require_once '../init/initialize_client.php';
session_start();
if (isset($_POST['submit2']) || isset($_POST['submit']))
{
	foreach ($_POST as $key => $value) {$_POST[$key] = $db_handle->sanitizePost(trim($value));}
	extract($_POST);
	if (empty($full_name) || empty($email_address) || empty($phone_number))
	{
		$message_error = "All fields must be filled, please try again";
	}
	elseif (!check_email($email_address))
	{
		$message_error = "You have supplied an invalid email address, please try again.";
	}
	elseif (check_number($phone_number) != 5)
	{
		$message_error = "You have supplied an invalid phone number, please try again.";
	}
	elseif (check_duplicate($email_address, $phone_number))
	{
		$message_error = "Duplicate Registration. <br/> Please use another phone number or email address.";
	}
	else
	{
		$client_full_name = $full_name;
		$full_name = str_replace(".", "", $full_name);
		$full_name = ucwords(strtolower(trim($full_name)));
		$full_name = explode(" ", $full_name);
		if (count($full_name) == 3)
		{
			$last_name = trim($full_name[0]);
			if (strlen($full_name[2]) < 3) { $middle_name = trim($full_name[2]); $first_name = trim($full_name[1]);}
			else { $middle_name = trim($full_name[1]); $first_name = trim($full_name[2]); }
		}
		else
		{
			$last_name = trim($full_name[0]);
			$middle_name = "";
			$first_name = trim($full_name[1]);
		}
		if (empty($first_name))
		{
			$first_name = $last_name;
			$last_name = "";
		}
		$query = "INSERT INTO prospect_ilpr_biodata (f_name, l_name, m_name, email, phone) VALUE ('$first_name', '$last_name', '$middle_name', '$email_address', '$phone_number')";
		$result = $db_handle->runQuery($query);
		$inserted_id = $db_handle->insertedId();
		if($result)
		{
			$subject = "Welcome to InstaFxNg $first_name";
			$message = <<<MAIL
    <div style="background-color: #F3F1F2">
    <div style="max-width: 80%; margin: 0 auto; padding: 10px; font-size: 14px; font-family: Verdana;">
        <img src="https://instafxng.com/images/ifxlogo.png" />
        <hr />
        <div style="background-color: #FFFFFF; padding: 15px; margin: 5px 0 5px 0;">
            <p>Dear $first_name,</p>
            <p>Welcome on board!</p>
            <p>I would like to take this opportunity to let you know how pleased and excited I am that you have chosen to trade with InstaForex Nigeria (www.InstaFxNg.com).</p>
            <p>You have joined over 14,000 Nigerians who make consistent income from the Forex market using the InstaForex platform and also earn more money just for trading.</p>
            <p>At InstaFxNg, we consider it a privilege to serve and provide you with excellent and unparalleled service at all times.</p>
            <p>Our job is to take care of everything else while you focus on trading and making money. </p>
            <p>From making deposit into your account (locally and easily) to instant stress free withdrawals (to your bank account) to unmatched customer support, we have you covered. </p>
            <p>We are dedicated to working effectively to ensure swift service delivery to you consistently.</p>
            <p>To start your journey to earning more money in our Loyalty Rewards Program, kindly click <a href="https://instafxng.com/live_account.php?id=lp">here</a> to open an InstaForex account immediately.</p>
            <p>Very shortly you're going to receive a call welcoming you on board from our ever cheerful customer service representatives who will assist you in opening an InstaForex account so you can get started immediately.</p>
            <p>Subsequently you will receive Emails.</p>
            <p>These emails will continue to educate you on how to trade, grow your Profits, take advantage of bonuses and earn more loyalty points. (I promise not to disturb you with my mails).</p>
            <p>Don’t forget to click <a href="https://instafxng.com/live_account.php?id=lp">here</a> to open your InstaForex account. You can also click <a href="https://instafxng.com/deposit_funds.php">here</a> to fund your account so you can get started immediately.</p>
            <p>I’m so glad you are here and I can’t wait for you to start earning from your trades and in the InstaFxNg Loyalty Points and Reward Program.</p>
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
			$system_object->send_email($subject, $message, $email, $full_name);
			$message_success = "Welcome to Instaforex Nigeria.";


			$_SESSION['f_name'] = $first_name;
			$_SESSION['l_name'] = $last_name;
			$_SESSION['m_name'] = $middle_name;
			$_SESSION['email'] = $email_address;
			$_SESSION['phone'] = $phone_number;
			$_SESSION['source'] = 'lp';

			redirect_to("live_account.php");

		}
		if(!$result)
		{
			$message_error = "Looks like something went wrong or you didn't make any change.";
		}
	}
}
function check_duplicate($email_address, $phone_number)
{
	global $db_handle;
	$query = "SELECT * FROM prospect_ilpr_biodata WHERE phone = '$phone_number' OR email = '$email_address' ";
	return $db_handle->numRows($query) ? true : false;
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
			<!-- Fixed navbar -->
			<nav class="navbar navbar-default navbar-fixed-top">
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
								<li><a id="gt" class="request" href="#" data-toggle="modal" data-target="#myModal">Get Started</a></li>
							</ul>
						</div>
					</div>
				</div>
			</nav>
		</div>
		<br/>
		<br/>
		<br/>
		<div id="myCarousel" class="carousel slide" data-ride="carousel">
			<!-- Indicators -->
			<ol class="carousel-indicators">
				<li data-target="#myCarousel" data-slide-to="0" class="active"></li>
				<li data-target="#myCarousel" data-slide-to="1" class=""></li>
				<!--<li data-target="#myCarousel" data-slide-to="2" class=""></li>
				<li data-target="#myCarousel" data-slide-to="3" class=""></li>-->
			</ol>
			<div class="carousel-inner" role="listbox">
				<div class="item active">
					<div class="container">
						<div class="carousel-caption">
						</div>
					</div>
				</div>
				<div class="item item2">
					<div class="container">
						<div class="carousel-caption">
						</div>
					</div>
				</div>
			</div>
			<a class="left carousel-control" href="#myCarousel" role="button" data-slide="prev">
				<span class="fa fa-chevron-left" aria-hidden="true"></span>
				<span class="sr-only">Previous</span>
			</a>
			<a class="right carousel-control" href="#myCarousel" role="button" data-slide="next">
				<span class="fa fa-chevron-right" aria-hidden="true"></span>
				<span class="sr-only">Next</span>
			</a>
			<!-- The Modal -->
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
							<h2>Get Started Now</h2>
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
										<input name="full_name" placeholder="Full Name" type="text" class="form-control" maxlength="120" required>
									</div>
								</div>
								<div class="form-group">
									<div class="col-sm-12"><input name="email_address" placeholder="Email Address" type="email"  class="form-control" maxlength="50" required></div>
								</div>
								<div class="form-group">
									<div class="col-sm-12"><input name="phone_number" placeholder="080********" type="text" class="form-control" maxlength="11" required></div>
								</div>
								<div class="col-sm-12">
									<span style="color: #ffffff">*All fields are required</span>
								</div>
								<div class="form-group">
									<div class="col-sm-12">
										<input name="submit2" type="submit" class="btn btn-success btn-lg btn-group-justified"  value="Get Me Started Now!"/>
									</div>

								</div>
							</form>
						</div>
					</div>
				</div>
			</div>
		</div>
		<div class="banner_bottom">
			<div class="container">
				<!--<h3 class="tittle-w3ls">About Us</h3>-->
				<div class="inner_sec_info_wthree_agile">
					<div class="help_full">
						<div class="col-md-6 banner_bottom_grid help">
							<img src="images/Th11-Paper%20money.jpg" alt=" " class="img-responsive">
						</div>
						<div class="col-md-6 banner_bottom_left">
							<h4 class="text-justify">Here is one thing that has been <b class="text-uppercase">Delivering Extra Profits</b> to the accounts of over 14,000 traders</h4>
							<p class="text-justify">What is thrilling about the Loyalty rewards is how much you can get without doing anything extra
								other than trading.</p>
							<p class="text-justify">You do not enter for a draw or trade a particular lot size to qualify.</p>
							<p class="text-justify"><b>Just Trade!!!</b></p>
							<p class="text-justify"><b>No hidden terms and conditions!</b></p>
							<p class="text-justify"><b>No funny Policies!</b></p>
							<p class="text-justify">The more you trade, the more points you earn, the more points you earn, the more money you make!</p>
							<div class="ab_button">
								<a class="btn btn-primary btn-lg hvr-underline-from-left" href="#" data-toggle="modal" data-target="#myModal" role="button">Start Now </a>
							</div>
						</div>
						<div class="clearfix"></div>
					</div>
				</div>
				<div class="inner_sec_info_wthree_agile">
					<div class="help_full">
						<div class="col-md-6 banner_bottom_left">
							<h4>Interested in earning more money from trading Forex? </h4>
							<p class="text-justify">Then you have finally reached the right place to start your journey to making extra cash NOW...
								Not tomorrow, Not in 12 months time!</p>
							<p class="text-justify">You get a piece of the pie as long as you open an account with us,
								deposit funds into your account and trade.</p>
							<p class="text-justify">This is the biggest reward system for Forex traders in Nigeria and I
								can’t wait for you to come on-board to enjoy this amazing offer!</p>
							<div class="ab_button">
								<a class="btn btn-primary btn-lg hvr-underline-from-left" href="#" data-toggle="modal" data-target="#myModal" role="button">Start Now </a>
							</div>
						</div>
						<div class="col-md-6 banner_bottom_grid help">
							<img src="images/Forex-Money.jpg" alt=" " class="img-responsive">
						</div>
						<div class="clearfix"></div>
					</div>
				</div>
				<div class="news-main">
					<div class="col-md-4 banner_bottom_left">
						<div class="banner_bottom_pos">
							<div class="banner_bottom_pos_grid">
								<div class="col-xs-3 banner_bottom_grid_left">
									<div class="banner_bottom_grid_left_grid">
										<span class="fa fa-check" aria-hidden="true"></span>
									</div>
								</div>
								<div class="col-xs-9 banner_bottom_grid_right">
									<h4>Open An Account</h4>
									<p class="text-justify">Open an Instaforex Trading Account</p>
								</div>
								<div class="clearfix"> </div>
							</div>
						</div>
					</div>
					<div class="col-md-4 banner_bottom_left">
						<div class="banner_bottom_pos">
							<div class="banner_bottom_pos_grid">
								<div class="col-xs-3 banner_bottom_grid_left">
									<div class="banner_bottom_grid_left_grid">
										<span class="fa fa-check" aria-hidden="true"></span>
									</div>
								</div>
								<div class="col-xs-9 banner_bottom_grid_right">
									<h4>Deposit Funds</h4>
									<p class="text-justify">Deposit funds into your Instaforex Account</p>
								</div>
								<div class="clearfix"> </div>
							</div>
						</div>
					</div>
					<div class="col-md-4 banner_bottom_left">
						<div class="banner_bottom_pos">
							<div class="banner_bottom_pos_grid">
								<div class="col-xs-3 banner_bottom_grid_left">
									<div class="banner_bottom_grid_left_grid">
										<span class="fa fa-check" aria-hidden="true"></span>
									</div>
								</div>
								<div class="col-xs-9 banner_bottom_grid_right">
									<h4>Trade</h4>
									<p class="text-justify">Carry out trades on your Instaforex Account</p>
								</div>
								<div class="clearfix"> </div>
							</div>
						</div>
					</div>
				</div>
				<div class="inner_sec_info_wthree_agile">
					<div class="help_full">
						<div class="col-md-12 banner_bottom_grid help">
							<img src="images/Collage.jpg" alt=" " class="img-responsive img-thumbnail">
						</div>
						<div class="col-md-12 banner_bottom_left">
							<!--<h4>Get you share of over 2.2 Million Naira</h4>-->
							<p class="text-justify">The first round of the InstaFxNg Point Based Loyalty Program and Reward ended on the 30th of November, 2017  and 10 amazing winners got cash prizes ranging from <b>N50,000</b> up to <b>N1,000,000</b></p>
							<p class="text-justify">Can you dig that?</p>
							<p class="text-justify">A total of <b>N4,390,000</b> was paid out to clients in rewards during the one year duration of the last round of the promo.</p>
							<p class="text-justify">This doesn't even include <b>$18,000</b> withdrawn as loyalty points!</p>
							<p class="text-justify">In the current round, you can be one of the clients that will be paid tens of thousands dollars during the one year duration of this round of the promo.</p>
							<div class="ab_button">
								<a class="btn btn-primary btn-lg hvr-underline-from-left" href="#" data-toggle="modal" data-target="#myModal" role="button">Get Your Share Now </a>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
		<div class="banner_bottom">
			<div class="banner_bottom_in">
				<h3 class="tittle-w3ls we text-justify">This man won a whopping sum of One Million Naira...</h3>
				<p>He started trading with InstaForex Nigeria in July 2017, just 5 months to the end of the promo!
					And kept trading consistently till he worked his way up to the top of the rank scale and emerged the
					winner of a whopping One Million naira.</p>
				<p>It’s neither too late nor too early for you to emerge the star winner by the end of the 2018 round.</p>
				<img src="images/FXDINNER-232.jpg" class="img-responsive" alt="">
			</div>
		</div>
		<div class="banner_bottom">
			<div class="container">
				<h3 class="tittle-w3ls">Here is how it works...</h3>
				<div class="inner_sec_info_wthree_agile">
					<div class="help_full">
						<div class="col-md-12 banner_bottom_left">
							<p>Each time you make a deposit to your account or trade as usual you earn some point's
								equivalent to your deposit or trading actions.</p>
							<p>Our proprietary rewards technology automatically calculates the
								points you have earned and your reward account is updated accordingly.</p>
						</div>
						<div class="clearfix"></div>
					</div>
				</div>
				<div class="inner_sec_info_wthree_agile">
					<div class="help_full">
						<div class="col-md-6 banner_bottom_left">
							<h4>When can I redeem points?</h4>
							<p class="text-justify">1. You can only redeem your points to credit your InstaForex
									account from 100 points and above.</p>
							<p class="text-justify">2. If you have a minimum of 100 points you will be presented
									with the option of redeeming some of your points for InstaForex credit to your
									account. For example if you are initiating a deposit transaction of $100 to
									your InstaForex account and you have 1000 reward points, you will be able
									to redeem up to 1000 points for another $100 hence your InstaForex account
									will be credited with a total of $200. Every client is eligible for this
									as long as you have earned the minimum 100 reward points.</p>
							<p class="text-justify">3. Points can only be redeemed when depositing funds.</p>
							<div class="ab_button">
								<a class="btn btn-group-justified btn-primary btn-lg hvr-underline-from-left" href="#" role="button"> Get Your Points Now </a>
							</div>
						</div>

						<div class="col-md-6 banner_bottom_grid help">
							<img src="images/money22.png" alt=" " class="img-responsive">
						</div>
						<div class="clearfix"></div>
					</div>
				</div>
			</div>
		</div>
		<div class="banner_bottom">
			<div class="container">
				<h3 class="tittle-w3ls">There's Still More...</h3>
				<div class="inner_sec_info_wthree_agile">
					<div class="help_full">
						<div class="col-md-12 banner_bottom_left">
							<p>Each time you make a deposit to your account or trade as usual, you earn some point's
								equivalent to your deposit or trading actions.</p>
							<p>Our proprietary rewards technology automatically calculates the
								points you have earned and your reward account is updated accordingly.</p>
						</div>
						<div class="clearfix"></div>
					</div>
				</div>
				<div class="inner_sec_info_wthree_agile">
					<div class="help_full">
						<div class="col-md-6 banner_bottom_left">
							<h4>Monthly Prizes</h4>
							<p class="text-justify">Five traders with the highest number of loyalty points will be rewarded with $500
								every month.</p>
							<p class="text-justify">The prizes are as follows</p>
							<p class="text-center">1st Prize     <b><i class="fa fa-arrow-right"></i></b>    $150</p>
							<p class="text-center">2nd Prize     <b><i class="fa fa-arrow-right"></i></b>    $120</p>
							<p class="text-center">3rd Prize     <b><i class="fa fa-arrow-right"></i></b>    $100</p>
							<p class="text-center">4th Prize     <b><i class="fa fa-arrow-right"></i></b>    $80</p>
							<p class="text-center">5th Prize     <b><i class="fa fa-arrow-right"></i></b>    $50</p>
							<div class="ab_button">
								<a class="btn btn-group-justified btn-primary btn-lg hvr-underline-from-left" href="#" data-toggle="modal" data-target="#myModal" role="button"> Grab The 1st Prize Now</a>
							</div>
						</div>

						<div class="col-md-6 banner_bottom_grid help">
							<img src="images/cash-clipart-cash-prize-14.jpg" alt=" " class="img-responsive">
						</div>
						<div class="clearfix"></div>
					</div>
				</div>
				<div class="inner_sec_info_wthree_agile">
					<div class="help_full">
						<div class="col-md-6 banner_bottom_grid help">
							<img src="images/Compensation-pay.jpg" alt=" " class="img-responsive">
						</div>
						<div class="col-md-6 banner_bottom_left">
							<h4>Annual Prizes</h4>
							<p class="text-justify">We are not done yet...</p>
							<p class="text-justify">You still have <b>N2, 250,000</b> up for grabs!</p>
							<p class="text-justify">At the end of every annual cycle, the ten (10) traders
								with the highest number of reward points will be awarded prizes.</p>
							<p class="text-justify"> The current annual cycle runs from December 1, 2017 to November 30th, 2018.
								Check out the Annual Prizes</p>
								<p class="text-center">1st Prize     <b><i class="fa fa-arrow-right"></i></b>    N1,000,000</p>
								<p class="text-center">2nd Prize     <b><i class="fa fa-arrow-right"></i></b>    N500,000</p>
								<p class="text-center">3rd Prize     <b><i class="fa fa-arrow-right"></i></b>    N250,000</p>
								<p class="text-center">4th Prize     <b><i class="fa fa-arrow-right"></i></b>    N150,000</p>
								<p class="text-center">5th Prize     <b><i class="fa fa-arrow-right"></i></b>    N100,000</p>
								<p class="text-center">6th - 10th Prize    <b><i class="fa fa-arrow-right"></i></b> N50,000 each</p>
							<p class="text-justify">Prizes will be presented to winners during our end of the year dinner in December.</p>
							<div class="ab_button">
								<a class="btn btn-primary btn-lg hvr-underline-from-left" href="#" data-toggle="modal" data-target="#myModal" role="button"> Grab The 1st Prize Now</a>
							</div>
						</div>
						<div class="clearfix"></div>
					</div>
				</div>
			</div>
		</div>
		<div class="banner_bottom">
			<div class="container">
				<h3 class="tittle-w3ls">How much can you earn from this rewards Program...?</h3>
				<div class="inner_sec_info_wthree_agile">
					<div class="help_full">
						<div class="col-md-12 banner_bottom_left">
							<p>You can earn up to $4,200 and N1, 000,000 every year. Below is a breakdown of how easy it is.</p>
							<p>If your deposit and your trading activities earn you an average of 2,000
								points every month making you the highest point earner every single month of the year,
								then you will earn the following.</p>
								<p class="text-justify"><i class="fa fa-thumbs-up"></i>   As first prize monthly winner you will get $150
									every month making a total of $1, 800 for one year.</p>
								<p class="text-justify"><i class="fa fa-thumbs-up"></i>   With 2,000 points monthly for 12 months you will have 24, 000
									points and you can redeem that to get $2, 400.<br/>
									$2,400 + $1,800 = $4,200
								</p>
								<p class="text-justify"><i class="fa fa-thumbs-up"></i>   As the first prize annual cumulative winner,
									you will get one million naira (N1, 000,000).</p>
						</div>
						<div class="clearfix"></div>
						<div class="ab_button">
							<a class="btn btn-group-justified btn-primary btn-lg hvr-underline-from-left" href="#" data-toggle="modal" data-target="#myModal" role="button"> Get Started Now </a>
						</div>
					</div>
				</div>
			</div>
		</div>
		<div class="footer">
			<p class="text-center">&copy 2018 Instafxng.com. All rights reserved.</p>
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
		<?php if(isset($message_success) || isset($message_error)): ?>
			<script>
				document.getElementById('gt').click();
			</script>
		<?php endif; ?>
	</body>
</html>