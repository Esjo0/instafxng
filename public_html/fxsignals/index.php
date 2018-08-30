<?php
require_once '../init/initialize_general.php';
$thisPage = "Home";
$date = date('Y-m-d H:i:s');
$cook = $_COOKIE[$ifxngsignals];
echo $_COOKIE[$ifxngsignals];
$signal_object = new Signal_Management();
if (isset($_POST['login'])) {
    $email = $db_handle->sanitizePost($_POST['email']);
    $name = $db_handle->sanitizePost($_POST['name']);
    $phone = $db_handle->sanitizePost($_POST['phone']);
    $interest = $db_handle->sanitizePost($_POST['interest']);
    if (filter_var($email, FILTER_VALIDATE_EMAIL)) {
        if (empty($name) || empty($phone)) {
            $query = "SELECT name, phone, email FROM signal_users WHERE email = '$email' ";
            if ($db_handle->numRows($query) > 0) {
                //$_SESSION['signal_schedule_user'] = $email;
                $ifxngsignals = "ifxng_signals";
                $cookie_value = $email;
                setcookie($ifxngsignals, $cookie_value, time() + (86400 * 30), "/"); // 86400 = 1 day
                header('Location: https:instafxng.com/signal_schedules.php');
                } else {
                $user_details = $db_handle->fetchAssoc($db_handle->runQuery("SELECT phone, CONCAT(first_name, SPACE(1), last_name) AS name FROM user WHERE email = '$email'"));//[0];
                if (!empty($user_details)) {
                    foreach ($user_details AS $row) {
                        extract($row);
                        $signal_object->add_lead($name, $email, $phone, 3, $interest, $date);
                        $query = "INSERT IGNORE INTO signal_users (name, phone, email) VALUES ('$name', '$phone', '$email') ";
                        if ($db_handle->runQuery($query)) {
                            //$_SESSION['signal_schedule_user'] = $email;
                            $ifxngsignals = "ifxng_signals";
                            $cookie_value = $email;
                            setcookie($ifxngsignals, $cookie_value, time() + (86400 * 30), "/"); // 86400 = 1 day
                            header('Location: https:instafxng.com/signal_schedules.php');
                        } else {
                            $message_error = "Sorry the operation failed, please try again.";
                            $get_phone_and_name = true;
                        }
                    }
                } else {
                    $message_error = "Please update your profile details.";
                    $get_phone_and_name = true;
                }
            }
        } else {
            $signal_object->add_lead($name, $email, $phone, 3, $interest, $date);
            $query = "INSERT IGNORE INTO signal_users (name, phone, email) VALUES ('$name', '$phone', '$email') ";
            if ($db_handle->runQuery($query)) {
                //$_SESSION['signal_schedule_user'] = $email;
                $ifxngsignals = "ifxng_signals";
                $cookie_value = $email;
                setcookie($ifxngsignals, $cookie_value, time() + (86400 * 30), "/"); // 86400 = 1 day
                header('Location: https:instafxng.com/signal_schedules.php');
            } else {
                $message_error = "Sorry the operation failed, please try again.";
            }
        }
    } else {
        $message_error = "You entered an invalid email address, please try again.";
    }
}
?>
<!DOCTYPE html>
<html lang="en">

<head>

    <base target="_self">
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Instaforex Nigeria | Online Instant Forex Trading Services</title>
    <meta name="title" content="Instaforex Nigeria | Online Instant Forex Trading Services"/>
    <meta name="keywords"
          content="forex signal, instaforex, forex trading in nigeria, forex seminar, forex trading seminar, how to trade forex, trade forex, instaforex nigeria">
    <meta name="description"
          content="Instaforex, a multiple award winning international forex broker is the leading Forex broker in Nigeria, open account and enjoy forex trading with us.">
    <?php require_once '../layouts/head_meta.php'; ?>
    <meta property="og:site_name" content="Instaforex Nigeria"/>
    <meta property="og:title" content="Instaforex Nigeria | Online Instant Forex Trading Services"/>
    <meta property="og:description"
          content="Instaforex, a multiple award winning international forex broker is the leading Forex broker in Nigeria, open account and enjoy forex trading with us."/>
    <meta property="og:image" content="images/instaforex-100bonus.jpg"/>
    <meta property="og:url" content="https://instafxng.com/"/>
    <meta property="og:type" content="website"/>

    <link rel="shortcut icon" type="image/x-icon" href="../images/favicon.png">
    <link rel="stylesheet" href="font-awesome-animation.min.css">

    <!-- Bootstrap core CSS -->
    <link href="vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet">

<!--    <!-- Custom fonts for this template -->
    <link href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:200,200i,300,300i,400,400i,600,600i,700,700i,900,900i" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css?family=Merriweather:300,300i,400,400i,700,700i,900,900i" rel="stylesheet">
    <link href="vendor/font-awesome/css/font-awesome.min.css" rel="stylesheet">
<!---->
<!--    <!-- Custom styles for this template -->
    <link href="css/coming-soon.min.css" rel="stylesheet">

</head>

<body>

<div class="overlay"><img height="100%" width="100%" class="img img-responsive" src="../images/signal_bg.png"></div>
<!--<video playsinline="playsinline" autoplay="autoplay" muted="muted" loop="loop">-->
<!--    <source src="mp4/bg.mp4" type="video/mp4">-->
<!--</video>-->
<div class="masthead">
<div class="masthead-bg" style="background-color: rgba(202, 45, 37, 0.73)"></div>
    <div class="container h-100">
        <div class="row h-100">
            <div class="col-12 my-auto">
                <div class="masthead-content text-white py-5 py-md-0">
                    <img src="../images/ifxlogo.png">
                    <h1 class="mb-3">WELCOME!</h1>

                    <p class="mb-5"><b>Trade the markets by following the best free trading signals! From InstaFxNg</b><br/>
                       </p>
                    <form class="form-horizontal" role="form" method="post" action="">
                        <?php include '../layouts/feedback_message.php'; ?>
                    <div class="input-group input-group-newsletter">
                        <input value="<?php echo $email ?>" type="email" maxlength="50" name="email" class="form-control" placeholder="Enter email..." aria-label="Enter email..." aria-describedby="basic-addon">
                        <div class="input-group-append">
                            <button class="btn btn-secondary" type="submit" name="login">Proceed!</button>
                        </div>
                    </div>
                    <?php if ($get_phone_and_name) { ?>
                    <div class="input-group">
                        <input value="<?php echo $name ?>" type="text" maxlength="100" name="name" class="form-control" placeholder="Enter Your Full Name" aria-label="Enter email..." aria-describedby="basic-addon">
                    </div>
                    <div class="input-group">
                        <input value="<?php echo $phone ?>" type="text" maxlength="15" name="phone" class="form-control" placeholder="Enter Your Phone Number" aria-label="Enter email..." aria-describedby="basic-addon">
                    </div>
                        <div class="input-group">
                        <label><input type="radio" name="interest" value="3"><b>I trade with Instaforex.</b></label>
                            <label><input type="radio" name="interest" value="2"><b>I trade with Other brokers.</b></label><br/>
                            <label><input type="radio" name="interest" value="1"><b>I trade Not Traded Forex Before.</b></label>
                            </div>
                    <?php } ?>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="social-icons">
    <ul class="list-unstyled text-center mb-0">
        <li class="list-unstyled-item" >
            <a href="https://twitter.com/instafxng">
                <i class="fa fa-twitter"></i>
            </a>
        </li>
        <li class="list-unstyled-item" >
            <a href="https://facebook.com/InstaForexNigeria">
                <i class="fa fa-facebook"></i>
            </a>
        </li>
        <li class="list-unstyled-item" >
            <a href="https://www.instagram.com/instafxng/">
                <i class="fa fa-instagram"></i>
            </a>
        </li>
    </ul>
</div>

<!-- Bootstrap core JavaScript -->
<script src="vendor/jquery/jquery.min.js"></script>
<script src="vendor/bootstrap/js/bootstrap.bundle.min.js"></script>

<!-- Custom scripts for this template -->
<script src="js/coming-soon.min.js"></script>

</body>

</html>
