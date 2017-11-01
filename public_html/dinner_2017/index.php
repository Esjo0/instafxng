<?php
require_once '../init/initialize_general.php';
require_once("../init/initialize_admin.php");
$page_requested = "";

// This section processes - views/dinner_2017_login.php
if(isset($_POST['login']))
{
    $email = $db_handle->sanitizePost($_POST['email']);
    if(check_email($email))
    {
        $attendee_detail = $db_handle->fetchAssoc($db_handle->runQuery("SELECT * FROM dinner_2017 WHERE email = '$email'"));
        $attendee_detail = $attendee_detail[0];
        if(empty($attendee_detail))
        {
            $message_error = "You do not have a reservation yet.";
            $page_requested = "";
        }
        else
        {
            $page_requested = "details";
        }
    }
    else
    {
        $page_requested = 'login';
        $message_error = "You entered an invalid email address, please try again.";
    }
}

// Process comment
if ($_SERVER['REQUEST_METHOD'] == 'POST' && $_POST['process'] == true)
{
    foreach($_POST as $key => $value)
    {
        $_POST[$key] = $db_handle->sanitizePost(trim($value));
    }
    extract($_POST);

    $update = $admin_object->update_dinner_guest_2017($reservation_code, $ticket_type, $confirmation_status);
    if($update)
    {
        $message_success = "You have successfully updated your reservation.";
        $attendee_detail = $db_handle->fetchAssoc($db_handle->runQuery("SELECT * FROM dinner_2017 WHERE email = '$email'"));
        $attendee_detail = $attendee_detail[0];
        if(empty($attendee_detail))
        {
            $message_error = "You do not have a reservation yet.";
            $page_requested = "";
        }
        else
        {
            $page_requested = "details";
        }
    }
}

switch($page_requested) {
    case '': $login = true; break;
    case 'details': $details = true; break;
    case 'login': $login = true; break;
    default: $login = true;
}

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <base target="_self">
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Instaforex Nigeria | Afro Nite 2017</title>
    <meta name="title" content="Instaforex Nigeria | Afro Nite 2017" />
    <meta name="keywords" content="instaforex, withdraw funds from your trading account, how to trade forex, trade forex, instaforex nigeria.">
    <meta name="description" content="Witthdraw Funds from your instaforex trading account">
    <meta http-equiv="Content-Language" content="en" />
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <meta http-equiv="Pragma" content="no-cache" />
    <meta name="robots" content="index, follow" />
    <meta name="author" content="Instant Web-Net Technologies Limited" />
    <meta name="publisher" content="Instant Web-Net Technologies Limited" />
    <meta name="copyright" content="Instant Web-Net Technologies Limited" />
    <meta name="rating" content="General" />
    <meta name="doc-rights" content="Private" />
    <meta name="doc-class" content="Living Document" />
    <link rel="stylesheet" href="../css/instafx.css?v=1.0">
    <link rel="shortcut icon" type="image/x-icon" href="../images/favicon.png">

    <link rel="stylesheet" href="../css/bootstrap_3.3.5.min.css">
    <link rel="stylesheet" href="../css/font-awesome_4.6.3.min.css">
    <script src="../js/jquery_2.1.1.min.js"></script>
    <script src="../js/bootstrap_3.3.5.min.js"></script>

    <!--<link rel="stylesheet" href="//maxcdn.bootstrapcdn.com/bootstrap/3.3.5/css/bootstrap.min.css">-->
    <!--<link rel="stylesheet" href="//maxcdn.bootstrapcdn.com/font-awesome/4.6.3/css/font-awesome.min.css">-->
    <link href="//cdn.rawgit.com/Eonasdan/bootstrap-datetimepicker/e8bddc60e73c1ec2475f827be36e1957af72e2ea/build/css/bootstrap-datetimepicker.css" rel="stylesheet">
    <!--<script src="//ajax.googleapis.com/ajax/libs/jquery/2.1.1/jquery.min.js"></script>-->
    <!--<script src="//maxcdn.bootstrapcdn.com/bootstrap/3.3.5/js/bootstrap.min.js"></script>-->

    <script src="../js/ie10-viewport-bug-workaround.js"></script>
    <script src="../js/validator.min.js"></script>
    <script src="../js/npm.js"></script>
    <script>
        function print_report(divName)
        {
            var printContents = document.getElementById(divName).innerHTML;
            var originalContents = document.body.innerHTML;

            document.body.innerHTML = printContents;

            window.print();

            document.body.innerHTML = originalContents;
        }
    </script>
</head>
<body>
<!-- Header Section: Logo and Live Chat  -->
<header id="header">
    <div class="container-fluid no-gutter masthead">
        <div class="row">
            <div id="main-logo" class="col-sm-12 col-md-5">
                <a href="./" title="Home Page"><img src="../images/ifxlogo.png" alt="Instaforex Nigeria Logo" /></a>
            </div>
            <div id="top-nav" class="col-sm-12 col-md-7 text-right">
                <blockquote id="header-blockquote" class="blockquote-reverse">
                    <span style="font-size: 0.9em">If you are not willing to risk the unusual, you will have to settle for the ordinary.</span>
                    <footer>Jim Rohn</footer>
                </blockquote>
            </div>
        </div>
    </div>
    <hr />
</header>
<!-- Main Body: The is the main content area of the web site, contains a side bar  -->
<div id="main-body" class="container-fluid">
    <div class="row no-gutter">

        <!-- Top Navigation: The main navigation of the Web site  -->
        <nav id="top-nav" class="navbar navbar-inverse">
            <div class="navbar-header">
                <span class="visible-xs navbar-brand">Menu <i class="fa fa-fw fa-long-arrow-right"></i></span>
                <button type="button" class="navbar-toggle" data-toggle="collapse" data-target="#myNavbar">
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                </button>
            </div>
            <div  class="collapse navbar-collapse" id="myNavbar">
                <ul class="nav navbar-nav">
                    <li <?php if ($thisPage == "Home") { echo "class=\"active\""; } ?>><a href="./" title="Home Page"><i title="Home Page" class="fa fa-home"></i> </a></li>
                    <li class="dropdown <?php if ($thisPage == "About") { echo " active"; } ?>">
                        <a class="dropdown-toggle" data-toggle="dropdown" href="#"><i class="fa fa-history fa-fw"></i> About
                            <span class="caret"></span></a>
                        <ul class="dropdown-menu">
                            <li><a href="https://instaforex.com/about_us.php?x=BBLR" title="Instaforex" target="_blank">Instaforex</a></li>
                            <li><a href="../view_news.php" title="Instaforex Nigeria News Centre">Company News</a></li>
                            <li><a href="../photo_gallery.php" title="Photo Gallery">Photo Gallery</a></li>
                            <li><a href="../video_gallery.php" title="Video Gallery">Video Gallery</a></li>
                        </ul>
                    </li>
                    <li class="dropdown <?php if ($thisPage == "Traders") { echo " active"; } ?>">
                        <a class="dropdown-toggle" data-toggle="dropdown" href="#"><i class="fa fa-line-chart fa-fw"></i> Traders
                            <span class="caret"></span></a>
                        <ul class="dropdown-menu">
                            <li><a href="../deposit_funds.php" title="Deposit Funds">Deposit Funds</a></li>
                            <li><a href="../withdraw_funds.php" title="Withdraw Funds">Withdraw Funds</a></li>
                            <li><a href="../deposit_notify.php" title="Payment Notification">Payment Notification</a></li>
                            <li><a href="../forex_bonus.php" title="30% Bonus On Deposit">30% Bonus On Deposit</a></li>
                            <li><a href="../view_signal.php" title="Free Forex Signals">Forex Signals</a></li>
                            <li><a href="../instaforex_tv.php" title="Instaforex TV">Instaforex TV</a></li>
                            <li><a href="https://www.instaforex.com/55bonus.php?x=BBLR" title="55% Deposit Bonus" target="_blank">55% Deposit Bonus</a></li>
                            <li><a href="https://www.instaforex.com/nodeposit_bonus.php?x=BBLR" title="No Deposit Bonus" target="_blank">No Deposit Bonus</a></li>
                            <li><a href="https://instaforex.com/trading_conditions.php?x=BBLR" title="Instaforex Trading Conditions" target="_blank">Trading Conditions</a></li>
                            <li><a href="https://instaforex.com/pamm_system.php?x=BBLR" title="PAMM System" target="_blank">PAMM System</a></li>
                            <li><a href="https://instaforex.com/forex_options.php?x=BBLR" title="Forex Options" target="_blank">Forex Options</a></li>
                            <li><a href="https://instaforex.com/forexcopy_system.php?x=BBLR" title="ForexCopy System" target="_blank">ForexCopy System</a></li>
                        </ul>
                    </li>
                    <li class="dropdown <?php if ($thisPage == "Education") { echo " active"; } ?>">
                        <a class="dropdown-toggle" data-toggle="dropdown" href="#"><i class="fa fa-book fa-fw"></i> Education
                            <span class="caret"></span></a>
                        <ul class="dropdown-menu">
                            <li><a href="../free_seminar.php" title="Free Forex Seminars">Free Seminar</a></li>
                            <li><a href="../traders_forum.php" title="Forex Traders Forum">Traders Forum</a></li>
                            <li><a href="../beginner_traders_course.php" title="Beginner Trader Course">Beginner Trader Course</a></li>
                            <li><a href="../advanced_traders_course.php" title="Advance Trader Course">Advance Trader Course</a></li>
                            <li><a href="../course.php" title="Forex Freedom Course">Forex Freedom Course</a></li>
                            <li><a href="../private_course.php" title="Forex Private Course">Forex Private Course</a></li>
                            <li><a href="../investor_course.php" title="Investor Course">Investor Course</a></li>
                        </ul>
                    </li>
                    <li class="<?php if ($thisPage == "Promotion") { echo " active"; } ?>"><a href="promo.php" title="Instaforex Promotions"><i class="fa fa-bookmark fa-fw"></i> Promo</a></li>
                    <li class="dropdown <?php if ($thisPage == "Account") { echo " active"; } ?>">
                        <a class="dropdown-toggle" data-toggle="dropdown" href="#"><i class="fa fa-dollar fa-fw"></i> Open Account
                            <span class="caret"></span></a>
                        <ul class="dropdown-menu">
                            <li><a href="../live_account.php" title="Open live Account">Live Account</a></li>
                            <li><a href="../bonus_account.php" title="$20 Bonus Account">$20 Bonus Account</a></li>
                            <li><a href="https://instaforex.com/open_demo_account.php?x=BBLR" title="Demo Account" target="_blank">Demo Account</a></li>
                            <li><a href="https://instaforex.com/downloads.php?x=BBLR" title="Download Trading Terminal" target="_blank">Download Trading Terminal</a></li>
                        </ul>
                    </li>
                    <li class="dropdown <?php if ($thisPage == "Support") { echo " active"; } ?>">
                        <a class="dropdown-toggle" data-toggle="dropdown" href="#"><i class="fa fa-phone fa-fw"></i> Support
                            <span class="caret"></span></a>
                        <ul class="dropdown-menu">
                            <li><a href="../contact_info.php" title="Contact">Contact Info</a></li>
                            <li><a href="../faq.php" title="Frequently Asked Questions">FAQ</a></li>
                            <li><a href="../careers.php" title="Careers" target="_blank">Careers</a></li>
                        </ul>
                    </li>
                    <!--                    <li class="dropdown <?php if ($thisPage == "Partner") { echo " active"; } ?>">
                        <a class="dropdown-toggle" data-toggle="dropdown" href="#"><i class="fa fa-money fa-fw"></i> Partner
                        <span class="caret"></span></a>
                        <ul class="dropdown-menu">
                            <li><a href="partner/" title="Instafxng Partnership Program">How it Works</a></li>
                            <li><a href="partner/signup.php" title="">Registration</a></li>
                            <li><a href="partner/login.php" title="">Partner Login</a></li>
                            <li><a href="partner/banners.php" title="">Banners</a></li>
                        </ul>
                    </li>-->
                </ul>
                <ul class="nav navbar-nav navbar-right">
                    <li><a href="https://cabinet.instaforex.com/client/login?x=BBLR" title="Login into Client Cabinet" target="_blank"><i class="fa fa-lock fa-fw"></i> Client Login</a></li>
                </ul>
            </div>
        </nav>

        <!-- Main Body - Content Area: This is the main content area, unique for each page  -->
        <div id="main-body-content-area" class="col-md-8 col-md-push-4 col-lg-9 col-lg-push-3">

            <!-- Unique Page Content Starts Here
            ================================================== -->

            <div class="section-tint super-shadow">
                <div class="row">
                    <div class="col-sm-12 text-danger">
                        <h4><strong>Instaforex Afro Nite 2017</strong></h4>
                    </div>
                </div>
                <div class="row">
                    <div class="col-sm-12">
                        <?php require_once '../layouts/feedback_message.php'; ?>
                        <?php
                        if($login) { include_once 'views/dinner_2017_login.php'; }
                        if($details) { include_once 'views/dinner_2017_details.php'; }
                        ?>

                    </div>
                </div>
            </div>

            <!-- Unique Page Content Ends Here
            ================================================== -->

        </div>
        <!-- Main Body - Side Bar  -->
        <div id="main-body-side-bar" class="col-md-4 col-md-pull-8 col-lg-3 col-lg-pull-9 left-nav">
            <?php require_once '../layouts/sidebar.php'; ?>
        </div>
    </div>
</div>
<?php require_once '../layouts/footer.php'; ?>
</body>
</html>