<?php
require_once 'init/initialize_general.php';
$thisPage = "timmer";

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <base target="_self">
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Instaforex Nigeria | timmer</title>
    <meta name="title" content="Instaforex Nigeria | InstaFxNg Black Friday Splurge" />
    <meta name="keywords" content="instaforex, promotions of instaforex, gifts for forex traders, contest and promotions" />
    <meta name="description" content="InstaFxNg Black Friday Splurge" />
    <link href="https://fonts.googleapis.com/css?family=Oleo+Script|Pontano+Sans" rel="stylesheet">
    <?php require_once 'layouts/head_meta.php'; ?>
</head>
<body>
<!-- Main Body: The is the main content area of the web site, contains a side bar  -->
<div id="main-body" class="container-fluid">
    <div class="row no-gutter">
        <!-- Main Body - Content Area: This is the main content area, unique for each page  -->
        <div id="main-body-content-area" class="col-md-12 col-lg-12">

            <!-- Unique Page Content Starts Here
            ================================================== -->

            <div class="section-tint super-shadow" style="background-color: black;">
                <div class="row">
                    <div class="col-sm-12 text-lg-center text-center" id="time-counter" style="color: white; font-size: 28px;">
                        <span  id="day"></span>
                        <span  id="hour"></span>
                        <span  id="min"></span>
                        <span  id="sec"></span>
                    </div>
            </div>

            <!-- Unique Page Content Ends Here
            ================================================== -->

        </div>
    </div>
</div>

<!-- Time Counter -->
<script type="text/javascript">
    // Set the date we're counting down to
    var countDownDate = new Date('2018-11-30');
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