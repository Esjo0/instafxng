<?php
require_once 'init/initialize_general.php';
$thisPage = "Promotion";

if(isset($_POST['proceed'])) {
    $email = $db_handle->sanitizePost($_POST['email']);

    if (filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $query = "SELECT user_code, email 
            FROM user 
            WHERE user_code IN (SELECT user_code FROM user_ifxaccount AS UI WHERE UI.type = '1') AND email = '$email'";
        $result = $db_handle->runQuery($query);
        $details = $db_handle->fetchArray($result);

        if ($details) {
            foreach ($details AS $row) {
                extract($row);
                $query = "INSERT IGNORE INTO black_friday_2018 (user_code) VALUE ('$user_code')";
                $result = $db_handle->runQuery($query);
            }
            $message_success = "Yay! We would send you a reminder when Black Friday Splurge begins.";
        } else {
            $message_error = "You are not enrolled on our Loyalty Promo <a target='_blank' href='https://instafxng.com/live_account.php'> Click Here to Open an ILPR account</a>";
        }
    } else {
        $message_error = "Looks like you entered an invalid email, please try again.";
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <base target="_self">
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Instaforex Nigeria | InstaFxNg Black Friday Splurge</title>
    <meta name="title" content="Instaforex Nigeria | InstaFxNg Black Friday Splurge" />
    <meta name="keywords" content="instaforex, promotions of instaforex, gifts for forex traders, contest and promotions" />
    <meta name="description" content="InstaFxNg Black Friday Splurge" />
    <link href="https://fonts.googleapis.com/css?family=Oleo+Script|Pontano+Sans" rel="stylesheet">
    <?php require_once 'layouts/head_meta.php'; ?>
</head>
<body>
<?php require_once 'layouts/header.php'; ?>
<!-- Main Body: The is the main content area of the web site, contains a side bar  -->
<div id="main-body" class="container-fluid">
    <div class="row no-gutter">
        <?php require_once 'layouts/topnav.php'; ?>
        <!-- Main Body - Content Area: This is the main content area, unique for each page  -->
        <div id="main-body-content-area" class="col-md-12 col-lg-12">

            <!-- Unique Page Content Starts Here
            ================================================== -->

            <div class="section-tint super-shadow" style="background-color: black;">
                <div class="row">
                    <div class="col-sm-6">
                        <?php include 'layouts/feedback_message.php'; ?>

                        <div class="row">
                            <div class="col-sm-12">
                                <h1 style="font-family: 'Oleo Script', cursive !important; color: white !important">The Blackest Friday Splurge is Coming This November, Up to 150% Extra</h1>
                                <p style="font-family: 'Pontano Sans', sans-serif !important; color: white !important; font-size: 1.2em !important;">Don't Miss out on extra cash! Enter your email address below to get a reminder when the promo begins.</p>
                                <hr />
                                <form data-toggle="validator" class="form-horizontal" role="form" method="post" action="">
                                    <div class="form-group">
                                        <label class="control-label col-sm-3" style="color: white !important;" for="email">Email Address:</label>
                                        <div class="col-sm-9">
                                            <div class="input-group">
                                                <span class="input-group-addon"><i class="fa fa-envelope fa-fw"></i></span>
                                                <input name="email" type="text" id="" value="" class="form-control" required />
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <div class="col-sm-offset-3 col-sm-9">
                                            <input name="proceed" type="submit" class="btn btn-danger" value="Get A Reminder" />
                                        </div>
                                    </div>
                                </form>
                                <hr />
                            </div>
                        </div>

                        <div class="row text-lg-center text-center" id="time-counter" style="color: white; font-size: 28px;">
                            <div class="col-sm-3" id="day"></div>
                            <div class="col-sm-3" id="hour"></div>
                            <div class="col-sm-3" id="min"></div>
                            <div class="col-sm-3" id="sec"></div>
                        </div>

                    </div>

                    <div class="col-sm-6">
                        <img id="img_div" class="img img-responsive" src="https://instafxng.com/imgsource/instaforex-black-friday-splurge.jpg" style="margin-top:5px; box-shadow: 0 4px 8px 0 rgba(255, 11, 0, 0.75), 0 6px 20px 0 rgba(255, 11, 0, 0.83)">
                    </div>

                </div>

            </div>

            <!-- Unique Page Content Ends Here
            ================================================== -->

        </div>
    </div>
</div>
<?php require_once 'layouts/footer.php'; ?>

<!-- Time Counter -->
<script type="text/javascript">
    // Set the date we're counting down to
    var countDownDate = new Date('2018-11-12');
    countDownDate.setDate(countDownDate.getDate());

    // Update the count down every 1 second
    var x = setInterval(function () {

        // Get todays date and time
        var now = new Date().getTime();

        // Find the distance between now an the count down date
        var distance = countDownDate - now;

        // Time calculations for days, hours, minutes and seconds
        var days = Math.floor(distance / (1000 * 60 * 60 * 24));
        var hours = Math.floor((distance % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
        var minutes = Math.floor((distance % (1000 * 60 * 60)) / (1000 * 60));
        var seconds = Math.floor((distance % (1000 * 60)) / 1000);

        document.getElementById("day").innerHTML = days + "Days  ";
        document.getElementById("hour").innerHTML = hours + "Hours  ";
        document.getElementById("min").innerHTML = minutes + "Mins  ";
        document.getElementById("sec").innerHTML = seconds + "Secs  ";

        // If the count down is finished, write some text
        if (distance < 0) {
            clearInterval(x);
            document.getElementById("time-counter").innerHTML = "EXPIRED";
        }
    }, 1000);
</script>

</body>
</html>