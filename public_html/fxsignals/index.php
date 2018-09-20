<?php
require_once '../init/initialize_general.php';
$thisPage = "Home";
$date = date('Y-m-d H:i:s');
$cook = $_COOKIE[$ifxngsignals];
$signal_object = new Signal_Management();
$get_phone_and_name = true;
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
                header('Location: https://instafxng.com/signal_schedules.php');
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
                            header('Location: https://instafxng.com/signal_schedules.php');
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
                header('Location: https://instafxng.com/signal_schedules.php');
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
    <meta property="og:image" content="../images/instaforex-100bonus.jpg"/>
    <meta property="og:url" content="https://instafxng.com/"/>
    <meta property="og:type" content="website"/>

    <link rel="shortcut icon" type="image/x-icon" href="../images/favicon.png">
    <link rel="stylesheet" href="../css/font-awesome_4.6.3.min.css">
    <link href="vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet">

<!--    <!-- Custom fonts for this template -->
    <link href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:200,200i,300,300i,400,400i,600,600i,700,700i,900,900i" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css?family=Merriweather:300,300i,400,400i,700,700i,900,900i" rel="stylesheet">
<!---->
<!--    <!-- Custom styles for this template -->
    <link href="../css/fxsignals.min.css" rel="stylesheet">
    <script>
        $(document).ready(function () {

            if (screen.width < 1024) {
                document.getElementById('img_div').style.display = 'none';
            }
            else {
                document.getElementById('img_div').style.display = 'block';
            }

        });
        </script>
    <style>
    @media (min-width: 1300px){
        #img_div_2 {
            display: none;
        }
    }

    @media (max-width: 1280px){
        #img_div {
            display: none;
        }
    }
    </style>
<!--    This section helps facbook to track those that finally got to this page from facebook advert and it also
heelps to build facebook adds retargetting

Please sir kindly review this sir-->
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
        fbq('init', '177357696206919');
        fbq('track', 'CompleteRegistration');
    </script>
    <noscript>
        <img height="1" width="1"
             src="https://www.facebook.com/tr?id=177357696206919&ev=PageView
&noscript=1"/>
    </noscript>
    <!-- End Facebook Pixel Code -->
</head>

<body style="background: white" >
                    <div  id="img_div" class="col-sm-5 pull-right" style="margin-right:120px; margin-top:150px;" >
                        <img height="100%" width="100%" class="img img-responsive img-thumbnail" src="../images/signal_img.jpg" style="box-shadow: 0 4px 8px 0 rgba(0, 0, 0, 0.2), 0 6px 20px 0 rgba(0, 0, 0, 0.19)">
                    </div>
<div class="masthead">
<div class="masthead-bg" style="background-color: #e74c3c"></div>
     <div class="container h-100">
        <div class="row h-100">
            <div class="col-12 my-auto">
                <div class="masthead-content text-white py-5 py-md-0">
                    <div style="margin-bottom: 20px; border-radius: 22px; background: white; padding: 8px;">
                        <a href="https://instafxng.com" target="_blank"><img class="img img-responsive" src="../images/ifxlogo.png"></a>
                    </div>
                    <h1 class="mb-3">WELCOME!</h1>

                    <p class="mb-5"><b>Trade the markets by following the best free trading signals! From InstaFxNg</b><br/>
                       </p>
                    <form class="form-horizontal" role="form" method="post" action="">
                        <?php include '../layouts/feedback_message.php'; ?>
                    <div class="input-group input-group-newsletter">
                        <input value="<?php echo $email ?>" type="email" maxlength="50" name="email" class="form-control" placeholder="Enter email..." aria-label="Enter email..." aria-describedby="basic-addon">

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
                            <label><input type="radio" name="interest" value="1"><b>I am new to forex.</b></label>
                            </div>
                        <div class="input-group-append">
                            <button class="btn btn-success" type="submit" name="login">Proceed!</button>
                        </div>
                    <?php } ?>
                    </form>
                    <img id="img_div_2" height="100%" width="100%" class="img img-responsive img-thumbnail" src="../images/signal_img.jpg" style="margin-top: 50px; box-shadow: 0 4px 8px 0 rgba(0, 0, 0, 0.2), 0 6px 20px 0 rgba(0, 0, 0, 0.19)">

                </div>

            </div>
            </div>
    </div>
</div>

<div class="social-icons">
    <ul class="list-unstyled text-center mb-0">
        <li class="list-unstyled-item" >
            <a href="https://twitter.com/instafxng" target="_blank">
                <i class="fa fa-twitter"></i>
            </a>
        </li>
        <li class="list-unstyled-item" >
            <a href="https://facebook.com/InstaForexNigeria" target="_blank">
                <i class="fa fa-facebook"></i>
            </a>
        </li>
        <li class="list-unstyled-item" >
            <a href="https://www.instagram.com/instafxng/" target="_blank">
                <i class="fa fa-instagram"></i>
            </a>
        </li>
    </ul>
</div>

<script src="vendor/jquery/jquery.min.js"></script>
<script src="vendor/bootstrap/js/bootstrap.bundle.min.js"></script>

<!-- Custom scripts for this template -->
<script src="../js/fxsignals.min.js"></script>

</body>

</html>
