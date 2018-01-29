<?php
require_once '../init/initialize_general.php';
$thisPage = "";

if (isset($_POST['submit']))
{
	foreach($_POST as $key => $value) {
		$_POST[$key] = $db_handle->sanitizePost(trim($value));
	}

	extract($_POST);

	if(empty($full_name) || empty($email_address) || empty($phone_number)) {
		$message_error = "All fields must be filled, please try again";
	} elseif (!check_email($email_address)) {
		$message_error = "You have supplied an invalid email address, please try again.";
	} else {

		$client_full_name = $full_name;
		$full_name = str_replace(".", "", $full_name);
		$full_name = ucwords(strtolower(trim($full_name)));
		$full_name = explode(" ", $full_name);

		if(count($full_name) == 3) {
			$last_name = trim($full_name[0]);

			if(strlen($full_name[2]) < 3) {
				$middle_name = trim($full_name[2]);
				$first_name = trim($full_name[1]);
			} else {
				$middle_name = trim($full_name[1]);
				$first_name = trim($full_name[2]);
			}

		} else {
			$last_name = trim($full_name[0]);
			$middle_name = "";
			$first_name = trim($full_name[1]);
		}

		if(empty($first_name)) {
			$first_name = $last_name;
			$last_name = "";
		}

		$query = "INSERT INTO free_training_campaign (first_name, last_name, email, phone, campaign_period) VALUE ('$first_name', '$last_name', '$email_address', '$phone_number', '2')";
		$result = $db_handle->runQuery($query);
		$inserted_id = $db_handle->insertedId();

		if($result) {

			$assigned_account_officer = $system_object->next_account_officer();

			$query = "UPDATE free_training_campaign SET attendant = $assigned_account_officer WHERE free_training_campaign_id = $inserted_id LIMIT 1";
			$db_handle->runQuery($query);

			// create profile for this client
			$client_operation = new clientOperation();
			$log_new_client = $client_operation->new_user_ordinary($client_full_name, $email_address, $phone_number, $assigned_account_officer);
			//...//

			$user_code = $client_operation->get_user_by_email($email_address);
			$user_ifx_details = $client_operation->get_user_by_code($user_code['user_code']);
			$found_user = array(
				'user_code' => $user_ifx_details['client_user_code'],
				'status' => $user_ifx_details['client_status'],
				'first_name' => $user_ifx_details['client_first_name'],
				'last_name' => $user_ifx_details['client_last_name'],
				'email' => $user_ifx_details['client_email']
			);
			$session_client->login($found_user);

			// Check if this is a first time login, then log the date
			if(empty($user_ifx_details['client_academy_first_login']) || is_null($user_ifx_details['client_academy_first_login'])) {
				$client_operation->log_academy_first_login($user_ifx_details['client_first_name'], $user_ifx_details['client_email'], $user_ifx_details['client_user_code']);
			}

			redirect_to('../forex-income-2018/thank-you/thank-you.php');
		} else {
			$message_error = "An error occurred, please try again.";
		}
	}
}

$all_states = $system_object->get_all_states();

?>
<!DOCTYPE html>
<html lang="en">
	<head>
		<title>Create Multiple Streams of Income | Instaforex Nigeria</title>
		<meta name="viewport" content="width=device-width, initial-scale=1">
		<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
		<meta name="keywords" content="" />
		<script type="application/x-javascript">
			addEventListener("load", function() { setTimeout(hideURLbar, 0); }, false);
			function hideURLbar(){ window.scrollTo(0,1); }
		</script>
		<link rel="stylesheet" href="css/bootstrap.css">
		<link rel="stylesheet" href="css/style.css" type="text/css" media="all" />
		<link rel="stylesheet" href="css/font-awesome.css">
		<link href="//fonts.googleapis.com/css?family=Roboto+Condensed:300,300i,400,400i,700,700i" rel="stylesheet">
		<link href="//fonts.googleapis.com/css?family=Open+Sans:300,300i,400,400i,600,600i,700,700i,800,800i" rel="stylesheet">
		<!-- Facebook Pixel Code -->
		<script>
			!function(f,b,e,v,n,t,s)
			{if(f.fbq)return;n=f.fbq=function(){n.callMethod?
				n.callMethod.apply(n,arguments):n.queue.push(arguments)};
				if(!f._fbq)f._fbq=n;n.push=n;n.loaded=!0;n.version='2.0';
				n.queue=[];t=b.createElement(e);t.async=!0;
				t.src=v;s=b.getElementsByTagName(e)[0];
				s.parentNode.insertBefore(t,s)}(window,document,'script',
				'https://connect.facebook.net/en_US/fbevents.js');
			fbq('init', '859859360822977');
			fbq('track', 'CompleteRegistration');
		</script>
		<noscript>
			<img height="1" width="1"
				 src="https://www.facebook.com/tr?id=859859360822977&ev=CompleteRegistration
&noscript=1"/>
		</noscript>
		<!-- End Facebook Pixel Code -->
	</head>
	<body>
		<div class="header fixed-top">
				<nav class="navbar navbar-default link-effect-8" id="link-effect-8">
							<div class="navbar-header">
								<h1><a href="index.php"><img class="img-responsive" src="../images/ifxlogo.png"></a></h1>
							</div>
							<div class="top-nav-text">
								<div class="nav-contact-w3ls"><i class="glyphicon glyphicon-phone-alt" aria-hidden="true"></i>   <p style="color: #000000"><b>   +(234) 0802 828 1192</b></p></div>
							</div>
							<!-- navbar-header -->
							<div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
								<!--<ul class="nav navbar-nav navbar-right">
									<li><a href="#" class="scroll">Get Started</a>
								</ul>-->
							</div>
							<div class="clearfix"> </div>
				</nav>

		</div>
		<!-- Slider -->
		<div class="slider" id="home">
			<div class="callbacks_container">
				<ul class="rslides" id="slider">
					<li>
						<div class="w3layouts-banner-top w3layouts-banner-top1">
							<div class="banner-dott">
							<div class="container">
								<div class="slider-info">
									<div class="col-md-7  slider-info-txt">
										<h2>Sit at the comfort of your home or office and earn in dollars!</h2>
										<div class="w3ls-button">
											<a href="#reg_form" >Get Started</a>
										</div>
									</div>
									<div class="col-md-5">

									</div>
								</div>
							</div>
							</div>
						</div>
					</li>
					<!--<li>
						<div class="w3layouts-banner-top">
							<div class="banner-dott">
							<div class="container">
								<div class="slider-info">
									<div class="col-md-8 slider-info-txt">
										<h3>credit card processing services</h3>
										<div class="w3ls-button">
											<a href="#" data-toggle="modal" data-target="#myModal">More About Our Bank</a>
										</div>

									</div>
									<div class="col-md-4">

									</div>
								</div>
							</div>
							</div>
						</div>
					</li>
					<li>
						<div class="w3layouts-banner-top w3layouts-banner-top3">
							<div class="banner-dott">
							<div class="container">
								<div class="slider-info">
									<div class="col-md-8 slider-info-txt">
										<h3>Services for all your customer needs</h3>

										<div class="w3ls-button">
											<a href="#" data-toggle="modal" data-target="#myModal">More About Our Bank</a>
										</div>

									</div>
									<div class="col-md-4">

									</div>
								</div>
							</div>
							</div>
						</div>
					</li>-->
				</ul>
			</div>
			<div class="clearfix"></div>
			<div id="reg_form" class="main add">
					<h2>Get Started For Free</h2>
				<form  data-toggle="validator" class="form-horizontal" role="form" method="post" action="/training-2018/">
					<div class="form-group">
						<div class="col-sm-12"><input name="full_name" placeholder="Full Name" type="text" class="form-control" id="full_name" maxlength="120" required></div>
					</div>
					<div class="form-group">
						<div class="col-sm-12"><input name="email_address" placeholder="Email Address" type="email" class="form-control" id="email" maxlength="50" required></div>
					</div>
					<div class="form-group">
						<div class="col-sm-12"><input name="phone_number" placeholder="Phone Number" type="text" class="form-control" id="phone" maxlength="11" required></div>
					</div>
					<div class="col-sm-12">
						<span style="color: #ffffff">*All fields are required</span>
					</div>
					<div class="form-group">
						<div class="col-sm-12">
							<input name="submit" type="submit" class="btn btn-success btn-lg form-control" value="Get Me Started Now!" />
						</div>

					</div>
				</form>
				</div>
		</div>
		<!-- //Slider -->
		<!-- bootstrap-modal-pop-up -->
		<div class="modal video-modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModal">
			<div class="modal-dialog" role="document">
				<div class="modal-content">
					<div class="modal-header">
						<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
					</div>
						<div class="modal-body">
							<div style="padding: 10px">
							<h2>Get Started For Free</h2>
							<form data-toggle="validator" class="form-horizontal" role="form" method="post" action="/training-2018/">
									<div class="form-group">
										<div class="col-sm-12"><input name="full_name" placeholder="Full Name" type="text" class="form-control"  maxlength="120" required></div>
									</div>
									<div class="form-group">
										<div class="col-sm-12"><input name="email_address" placeholder="Email Address" type="email" class="form-control"  maxlength="50" required></div>
									</div>
									<div class="form-group">
										<div class="col-sm-12"><input name="phone_number" placeholder="Phone Number" type="text" class="form-control"  maxlength="11" required></div>
									</div>
									<div class="col-sm-12">
										<span style="color: #ffffff">*All fields are required</span>
									</div>
									<div class="form-group">
										<div class="col-sm-12">
											<input name="submit" type="submit" class="btn btn-success btn-lg form-control" value="Get Me Started Now!" />
										</div>

									</div>
								</form>
							</div>
						</div>
				</div>
			</div>
		</div>
		<!-- //bootstrap-modal-pop-up -->
		<!-- welcome -->
		<div class="welcome" id="about">
			<div class="container">

				<div class="welcome-grids">
					<div class="col-md-6 welcome-w3right">
						<img src="images/ab1.jpg" class="img-responsive" alt="" />
					</div>
					<div class="col-md-6 welcome-w3left">
						<h3>Need Some Extra Cash?</h3>
						<!--<h4>Sit at the comfort of your home or office and make over $20 day!</h4>-->
						<p class="text-justify">Could you use an extra $20 every day? $100 every week? Or even $400 every month?</p>
						<p class="text-justify">Are you used to spending hours, surfing the Internet, looking for a particular business
							which you can do alongside your business or 9-5 job?</p>
						<p class="text-justify">If so, you are one of the many people who would benefit from Forex Trading.</p>
						<p class="text-justify">A hassle-free, business that you can profit from every single day without working
							your fingers off for it! You read that right buddy!</p>
						<div class="readmore-w3-about">
							<a class="readmore" href="#" data-toggle="modal" data-target="#myModal">Get Started Now</a>
						</div>
					</div>
					<div class="clearfix"></div>
				</div>
			</div>
		</div>
		<!-- //welcome -->
		<!--/services-->
		<div class="services" id="services">
		<div class="container">
		<div class="w3-heading-all"><h3>Let's Get You Started</h3></div>
				<div class="w3-agile-services-grids1">
					<div class="col-md-6 services-left-grid">
						<h3>WHAT IS FOREX TRADING?</h3>
						<h5><span><i class="fa fa-check" aria-hidden="true"></i></span>Trading of currencies.</h5>
						<p class="text-justify">Online Forex trading is the exchange of one currency with another online in order to make profit.</p>
						<h5><span><i class="fa fa-check" aria-hidden="true"></i></span>World largest financial market.</h5>
						<p class="text-justify">The forex market is the biggest financial market in the world (bigger than the Stock market)
							and the profit you can make from trading forex is limitless.</p>
						<div class="readmore-w3-about">
							<a class="readmore" href="#" data-toggle="modal" data-target="#myModal">Get Started Now</a>
						</div>
					</div>
					<div class="col-md-6 services-right-grid">
					<img src="images/t1.jpg" class="img-responsive" alt="" />
					   </div>
					<div class="clearfix"></div>
				</div>
				<div class="w3-agile-services-grids2">
					<div class="col-md-6 services-right-grid">
					<img src="images/t21.jpg" class="img-responsive" alt="" />
					   </div>
					   <div class="col-md-6 services-left-grid">
						<h3>BECOMING A SUCCESSFUL TRADER</h3>
						<h5><span><i class="fa fa-check" aria-hidden="true"></i></span>Get trained.</h5>
						<p class="text-justify">Forex trading is a highly profitable business, no doubt!
							It is easy and profitable once you have learnt how to trade and gained adequate knowledge.</p>
						<h5><span><i class="fa fa-check" aria-hidden="true"></i></span>Daily mentoring.</h5>
						<p class="text-justify">Trading Forex gets better because you get mentored daily and you also get trading
							signals and analysis that increase your profits.</p>
						   <div class="readmore-w3-about">
							   <a class="readmore" href="#" data-toggle="modal" data-target="#myModal">Get Started Now</a>
						   </div>
					</div>
					<div class="clearfix"></div>
				</div>
				<div class="w3-agile-services-grids3">
					<div class="col-md-6 services-left-grid">
						<h3>FREE ONLINE FOREX TRADING TRAINING</h3>
						<p class="text-justify">The Free online Forex trading training completes your search for another stream of
							income through one of the best means of earning online as it is designed to take
							you from the basics up to the point of profitable live trading.</p>
						<p>In this FREE training, you will learn:</p>
						<h5><span><i class="fa fa-check" aria-hidden="true"></i></span>What Forex Trading is.</h5>
						<h5><span><i class="fa fa-check" aria-hidden="true"></i></span>How to make money trading Forex.</h5>
						<h5><span><i class="fa fa-check" aria-hidden="true"></i></span>What you need to trade Forex.</h5>
						<h5><span><i class="fa fa-check" aria-hidden="true"></i></span>How to trade Forex.</h5>
						<p class="text-justify">A practical trading session that teaches you how you can start trading to make money from Forex trading and lots more will be held regularly.</p>
						<div class="readmore-w3-about">
							<a class="readmore" href="#" data-toggle="modal" data-target="#myModal">Get Started Now</a>
						</div>
					</div>
					<div class="col-md-6 services-right-grid">
					<img src="images/t3.jpg" class="img-responsive" alt="" />
					   </div>
					<div class="clearfix"></div>
				</div>
		</div>
		</div>
		<!--//services-->
		<!-- agents -->
		<!--<div class="jarallax w3ls-section team1" id="agents">
			<div class="container">
				<div class="w3-heading-all">
					<h3><span>A</span>gents</h3>
				</div>
				<div id="team" class="team">
				<div class="team-info">
					<div class="col-md-4 team-grids">
						<img class="img-responsive" src="images/tm1.jpg" alt="">
						<div class="agileits-captn">
							<div class="social-icons">
								<ul>
									<li><a href="#" class="fa fa-facebook icon-border facebook"> </a></li>
									<li><a href="#" class="fa fa-twitter icon-border twitter"> </a></li>
									<li><a href="#" class="fa fa-google-plus icon-border googleplus"> </a></li>
									<li><a href="#" class="fa fa-rss icon-border rss"> </a></li>
								</ul>
							</div>
						</div>
						<h4>pulvinar</h4>
						<p>Aenean pulvinar ac enimet</p>
					</div>
					<div class="col-md-4 team-grids">
						<img class="img-responsive" src="images/tm2.jpg" alt="">
						<div class="agileits-captn">
							<div class="social-icons">
								<ul>
									<li><a href="#" class="fa fa-facebook icon-border facebook"> </a></li>
									<li><a href="#" class="fa fa-twitter icon-border twitter"> </a></li>
									<li><a href="#" class="fa fa-google-plus icon-border googleplus"> </a></li>
									<li><a href="#" class="fa fa-rss icon-border rss"> </a></li>
								</ul>
							</div>
						</div>
						<h4>Enimet</h4>
						<p>Aenean pulvinar ac enimet</p>
					</div>
					<div class="col-md-4 team-grids">
						<img class="img-responsive" src="images/tm3.jpg" alt="">
						<div class="agileits-captn">
							<div class="social-icons">
								<ul>
									<li><a href="#" class="fa fa-facebook icon-border facebook"> </a></li>
									<li><a href="#" class="fa fa-twitter icon-border twitter"> </a></li>
									<li><a href="#" class="fa fa-google-plus icon-border googleplus"> </a></li>
									<li><a href="#" class="fa fa-rss icon-border rss"> </a></li>
								</ul>
							</div>
						</div>
						<h4>Tincidun</h4>
						<p>Aenean pulvinar ac enimet</p>
					</div>
					<div class="col-md-4 team-grids">
						<img class="img-responsive" src="images/tm4.jpg" alt="">
						<div class="agileits-captn">
							<div class="social-icons">
								<ul>
									<li><a href="#" class="fa fa-facebook icon-border facebook"> </a></li>
									<li><a href="#" class="fa fa-twitter icon-border twitter"> </a></li>
									<li><a href="#" class="fa fa-google-plus icon-border googleplus"> </a></li>
									<li><a href="#" class="fa fa-rss icon-border rss"> </a></li>
								</ul>
							</div>
						</div>
						<h4>Inaren</h4>
						<p>Aenean pulvinar ac enimet</p>
					</div>
				<div class="clearfix"></div>
			</div>
		 </div>
		</div>
		</div>-->
		<!-- //agents -->
		<!-- Counter -->
		<!--<div class="stats">
			<div class="container">
				<div class="row">
					<div class="col-md-3 col-sm-3 stats-grid stats-grid-1 gridw3">
						<i class="fa fa-smile-o" aria-hidden="true"></i>
						<div class="numscroller" data-slno='1' data-min='0' data-max='158' data-delay='3' data-increment="1">85</div>
						<h4>Happy Customers</h4>
					</div>
					<div class="col-md-3 col-sm-3 stats-grid stats-grid-2 gridw3">
						<i class="fa fa-trophy" aria-hidden="true"></i>
						<div class="numscroller" data-slno='1' data-min='0' data-max='63' data-delay='3' data-increment="1">95</div>
						<h4>Awards </h4>
					</div>
					<div class="col-md-3 col-sm-3 stats-grid stats-grid-3 gridw3">
						<i class="fa fa-laptop" aria-hidden="true"></i>
						<div class="numscroller" data-slno='1' data-min='0' data-max='421' data-delay='3' data-increment="1">80</div>
						<h4>collabaration</h4>
					</div>
					<div class="col-md-3 col-sm-3 stats-grid stats-grid-4 gridw3">
						<i class="fa fa-users" aria-hidden="true"></i>
						<div class="numscroller" data-slno='1' data-min='0' data-max='562' data-delay='3' data-increment="1">90</div>
						<h4>Members</h4>
					</div>
					<div class="clearfix"></div>
				</div>
			</div>
		</div>-->
		<!-- //Counter -->
		<!-- testimonials -->
			<div class="testimonials" id="testimonials">
				<div class="container">
				<div class="w3-heading-all">
					<h3>Testimonials</h3>
					<div class="w3ls_testimonials_grids">
						 <section class="center slider">
								<div class="agileits_testimonial_grid">
									<div class="w3l_testimonial_grid">
										<p class="text-justify">Before now, I just started trading Forex without the necessary training and I made some losses since I didn’t
											know how to trade, I was practically gambling. After I took the online training, a lot of things became
											clear and now, my bank alert na kpakam!</p>
										<h4>Abegunde Emmanuel</h4>
										<h5>Hr/Business Strategist</h5>
										<div class="readmore-w3-about col-lg-12">
											<a class="readmore" href="#" data-toggle="modal" data-target="#myModal">Get Started Now</a>
										</div>
										<div class="w3l_testimonial_grid_pos">
											<!--<img src="images/c1.jpg" alt=" " class="img-responsive" />-->
										</div>
									</div>
								</div>
								<div class="agileits_testimonial_grid">
									<div class="w3l_testimonial_grid">
										<p>I completed the Free Online Training course last week and made $150 from my
											trade yesterday after finishing the online training. I’m awed!</p>
										<h4>Damilare Akanmu</h4>
										<h5>Self Employed</h5>
										<div class="readmore-w3-about col-lg-12">
											<a class="readmore" href="#" data-toggle="modal" data-target="#myModal">Get Started Now</a>
										</div>
										<div class="w3l_testimonial_grid_pos">
											<!--<img src="images/c2.jpg" alt=" " class="img-responsive" />-->
										</div>
									</div>
								</div>
								<div class="agileits_testimonial_grid">
									<div class="w3l_testimonial_grid">
										<p class="text-justify">Thank you for introducing me to Forex trading! Since I took your online training,
											I have been making daily profits from my trade. I have made a total of $50
											within three days of trading forex. I am so happy I found Forex.</p>
										<h4>Joshua Adegoke</h4>
										<h5>Economist</h5>
										<div class="readmore-w3-about col-lg-12">
											<a class="readmore" href="#" data-toggle="modal" data-target="#myModal">Get Started Now</a>
										</div>
										<div class="w3l_testimonial_grid_pos">
											<!--<img src="images/c3.jpg" alt=" " class="img-responsive" />-->
										</div>
									</div>
								</div>
							 <div class="agileits_testimonial_grid">
								 <div class="w3l_testimonial_grid">
									 <p class="text-justify">The online training is so informative, the tone is funny, simple and easy to follow.
										 I had a great time taking the course./p>
									 <h4>Emmanuel Audu</h4>
									 <h5>Software Engineer</h5>
									 <div class="readmore-w3-about col-lg-12">
										 <a class="readmore" href="#" data-toggle="modal" data-target="#myModal">Get Started Now</a>
									 </div>
									 <div class="w3l_testimonial_grid_pos">
										 <!--<img src="images/c3.jpg" alt=" " class="img-responsive" />-->
									 </div>
								 </div>
							 </div>
						</section>
					</div>
				</div>
			</div>
				</div>
		<!-- //testimonials -->




	<!-- footer -->
	<!--<div class="footer_agile_w3ls">
		<div class="container">
			<div class="agileits_w3layouts_footer_grids">
				<div class="col-md-3 footer-w3-agileits">
						<h3>Our Conditions</h3>
						<ul>
							<li>Etiam quis placerat</li>
							<li>the printing</li>
							<li>unknown printer</li>
							<li>Lorem Ipsum</li>
						</ul>
				</div>
				<div class="col-md-3 footer-agileits">
						<h3>Specialized</h3>
						<ul>
							<li>the printing</li>
							<li>Etiam quis placerat</li>
							<li>Lorem Ipsum</li>
							<li>unknown printer</li>
						</ul>
					</div>
					<div class="col-md-3 footer-wthree">
						<h3>Partners</h3>
						<ul>
							<li>unknown printer</li>
							<li>Lorem Ipsum</li>
							<li>the printing</li>
							<li>Etiam quis placerat</li>
						</ul>
					</div>

					<div class="col-md-3 footer-agileits-w3layouts">
						<h3>Our Links</h3>
						<ul>
							<li><a href="#home" class="scroll">Home</a></li>
							<li><a href="#about"  class="scroll">About</a></li>
							<li><a href="#services"  class="scroll">Services</a></li>
							<li><a href="#contact"  class="scroll">Contact Us</a></li>
						</ul>
					</div>
					<div class="clearfix"></div>

			</div>
		</div>
	</div>-->
	<div class="wthree_copy_right">
		<div class="container">
			<p>© 2018 Instant Web Net Technologies. All rights reserved <a href="http://Instafxng.com/">Instafxng.com</a></p>
		</div>
	</div>
	<!-- //footer -->
	<!-- js-scripts -->
	<!-- js -->
		<script type="text/javascript" src="js/jquery-2.1.4.min.js"></script>
		<script type="text/javascript" src="js/bootstrap.js"></script> <!-- Necessary-JavaScript-File-For-Bootstrap -->
	<!-- //js -->

	<!-- start-smoth-scrolling -->
	<script src="js/SmoothScroll.min.js"></script>
	<script type="text/javascript" src="js/move-top.js"></script>
	<script type="text/javascript" src="js/easing.js"></script>
	<script type="text/javascript">
		jQuery(document).ready(function($) {
			$(".scroll").click(function(event){
				event.preventDefault();
				$('html,body').animate({scrollTop:$(this.hash).offset().top},1000);
			});
		});
	</script>
	<!-- here stars scrolling icon -->
		<script type="text/javascript">
			$(document).ready(function() {
				/*
					var defaults = {
					containerID: 'toTop', // fading element id
					containerHoverID: 'toTopHover', // fading element hover id
					scrollSpeed: 1200,
					easingType: 'linear'
					};
				*/

				$().UItoTop({ easingType: 'easeOutQuart' });

				});
		</script>
		<!-- //here ends scrolling icon -->
	<!-- start-smoth-scrolling -->

	<!-- Baneer-js -->
		<script src="js/responsiveslides.min.js"></script>
		<script>
			$(function () {
				$("#slider").responsiveSlides({
					auto: true,
					pager:false,
					nav: true,
					speed: 1000,
					namespace: "callbacks",
					before: function () {
						$('.events').append("<li>before event fired.</li>");
					},
					after: function () {
						$('.events').append("<li>after event fired.</li>");
					}
				});
			});
		</script>
	<!-- //Baneer-js -->
	<!-- js for Counter -->
			<script type="text/javascript" src="js/numscroller-1.0.js"></script>
		<!-- /js for Counter -->
	<!-- carousal -->
		<script src="js/slick.js" type="text/javascript" charset="utf-8"></script>
		<script type="text/javascript">
			$(document).on('ready', function() {
			  $(".center").slick({
				dots: true,
				infinite: true,
				centerMode: true,
				slidesToShow: 2,
				slidesToScroll: 2,
				responsive: [
					{
					  breakpoint: 768,
					  settings: {
						arrows: true,
						centerMode: false,
						slidesToShow: 2
					  }
					},
					{
					  breakpoint: 480,
					  settings: {
						arrows: true,
						centerMode: false,
						centerPadding: '40px',
						slidesToShow: 1
					  }
					}
				 ]
			  });
			});
		</script>
	<!-- //carousal -->

	<!-- //js-scripts -->
	</body>
</html>
