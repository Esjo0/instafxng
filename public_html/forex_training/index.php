<?php
require_once '../init/initialize_general.php';

$get_params = allowed_get_params(['b']);
$campaign_id = $get_params['b'];
$client_operation = new clientOperation();

$obj_loyalty_training = new Loyalty_Training();

if (isset($_POST['submit'])) {
	$email = $db_handle->sanitizePost($_POST['email']);
	$name = $db_handle->sanitizePost($_POST['name']);
	$phone = $db_handle->sanitizePost($_POST['phone']);
	$today = date('Y-m-d');
	extract(split_name($name));
	if (filter_var($email, FILTER_VALIDATE_EMAIL) && filter_var($phone, FILTER_SANITIZE_NUMBER_INT)) {
		$obj_loyalty_training->add_lead($first_name, $last_name, $email, $phone, 1, 1, $today, $state_id = '');
		$query = "INSERT IGNORE INTO onboarding_campaign_leads (email, campaign_id) VALUE('$email', '$campaign_id')";
		$result = $db_handle->runQuery($query);
		if ($result == true) {
			$message_success = "Successfully submitted";
			$user_code = $client_operation->get_user_by_email($email);
			$user_ifx_details = $client_operation->get_user_by_code($user_code['user_code']);

			if($user_ifx_details) {
				$found_user = array(
					'user_code' => $user_ifx_details['client_user_code'],
					'status' => $user_ifx_details['client_status'],
					'first_name' => $user_ifx_details['client_first_name'],
					'last_name' => $user_ifx_details['client_last_name'],
					'email' => $user_ifx_details['client_email']
				);
				$session_client->login($found_user);
			}
			// Check if this is a first time login, then log the date
			if(empty($user_ifx_details['client_academy_first_login']) || is_null($user_ifx_details['client_academy_first_login'])) {
				$client_operation->log_academy_first_login($user_ifx_details['client_first_name'], $user_ifx_details['client_email'], $user_ifx_details['client_user_code']);
			}
			redirect_to('completed.php');
		} else {
			$message_error = "Not successful. Kindly Try again.";
		}
	}else{
		$message_error = "You entered an invalid email. Kindly Try again.";
	}
}


?>
<!DOCTYPE HTML>
<!--
	Paradigm Shift by HTML5 UP
	html5up.net | @ajlkn
	Free for personal and commercial use under the CCA 3.0 license (html5up.net/license)
-->
<html>
	<head>
		<base target="_self">
		<meta charset="UTF-8">
		<meta name="viewport" content="width=device-width, initial-scale=1">
		<title>Instaforex Nigeria | Online Instant Forex Trading Services</title>
		<meta name="title" content="Instaforex Nigeria | Online Instant Forex Trading Services" />
		<meta name="keywords" content="instaforex, forex trading in nigeria, forex seminar, forex trading seminar, how to trade forex, trade forex, instaforex nigeria">
		<meta name="description" content="Instaforex, a multiple award winning international forex broker is the leading Forex broker in Nigeria, open account and enjoy forex trading with us.">
		<?php require_once '../layouts/head_meta.php'; ?>
		<meta property="og:site_name" content="Instaforex Nigeria" />
		<meta property="og:title" content="Instaforex Nigeria | Online Instant Forex Trading Services" />
		<meta property="og:description" content="Instaforex, a multiple award winning international forex broker is the leading Forex broker in Nigeria, open account and enjoy forex trading with us." />
		<meta property="og:image" content="images/instaforex-100bonus.jpg" />
		<meta property="og:url" content="https://instafxng.com/" />
		<meta property="og:type" content="website" />
		<script src="https://www.wpiece.com/js/webcomponents.min.js"></script>

		<link rel="shortcut icon" type="image/x-icon" href="../images/favicon.png">
		<link rel="stylesheet" href="../css/font-awesome_4.6.3.min.css">
		<link  rel="import" href="http://www.wpiece.com/p/10_26" />
		<link rel="stylesheet" href="assets/css/main.css" />
	</head>
	<body class="is-preload" style="height:100vh">

		<!-- Wrapper -->
			<div id="wrapper">


				<!-- Section -->
					<section class="intro">
						<header>
							<h1>Fx Academy</h1>
							<p>Your first step to making steady income trading Forex... </p>
							<img src="https://instafxng.com/images/train.png"/>
						</header>
						<div class="content">
							<div style="margin-top:20px; border-radius: 22px; background: white; padding: 20px;">
								<a href="https://instafxng.com" target="_blank"><img class="img img-responsive" src="../images/ifxlogo.png"></a>
							</div>
							<?php include '../layouts/feedback_message.php'; ?>
							<form role="form" method="post" action="">

								<div class="fields">
									<div class="field">
										<input type="text" name="name" id="name" placeholder="Name" required/>
									</div>
									<div class="field">
										<input type="email" name="email" id="email" placeholder="Email" required/>
									</div>
									<div class="field">
										<input type="text" name="phone" id="email" placeholder="Phone" required/>
									</div>
								</div>
								<div>
									<button type="submit" name="submit">SUBMIT</button>
									</div>
							</form>
						</div>
						<footer>
							<ul class="items">
								<li>
									<ul class="icons">
										<li><a href="#" class="icon fa-twitter"><span class="label">Twitter</span></a></li>
										<li><a href="#" class="icon fa-facebook"><span class="label">Facebook</span></a></li>
										<li><a href="#" class="icon fa-instagram"><span class="label">Instagram</span></a></li>
										<li><a href="#" class="icon fa-linkedin"><span class="label">LinkedIn</span></a></li>
										<li><a href="#" class="icon fa-github"><span class="label">GitHub</span></a></li>
										<li><a href="#" class="icon fa-codepen"><span class="label">Codepen</span></a></li>
									</ul>
								</li>
							</ul>
						</footer>
					</section>

				<!-- Copyright -->
					<div class="copyright"><a href="https://instafxng.com">&copy; 2019 Instafxng.com</a>.</div>

			</div>

		<!-- Scripts -->
			<script src="assets/js/jquery.min.js"></script>
			<script src="assets/js/jquery.scrolly.min.js"></script>
			<script src="assets/js/browser.min.js"></script>
			<script src="assets/js/breakpoints.min.js"></script>
			<script src="assets/js/util.js"></script>
			<script src="assets/js/main.js"></script>

	</body>
</html>