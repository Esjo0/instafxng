<?php
require_once 'init/initialize_general.php';
$thisPage = "Home";
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <base target="_self">
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Instaforex Nigeria | Online Instant Forex Trading Services</title>
    <meta name="title" content="Instaforex Nigeria | Online Instant Forex Trading Services" />
    <meta name="keywords" content="instaforex, forex trading in nigeria, forex seminar, forex trading seminar, how to trade forex, trade forex, instaforex nigeria">
    <meta name="description" content="Instaforex, a multiple award winning international forex broker is the leading Forex broker in Nigeria, open account and enjoy forex trading with us.">
    <?php require_once 'layouts/head_meta.php'; ?>

    <meta property="og:site_name" content="Instaforex Nigeria" />
    <meta property="og:title" content="Instaforex Nigeria | Online Instant Forex Trading Services" />
    <meta property="og:description" content="Instaforex, a multiple award winning international forex broker is the leading Forex broker in Nigeria, open account and enjoy forex trading with us." />
    <meta property="og:image" content="images/instaforex-100bonus.jpg" />
    <meta property="og:url" content="https://instafxng.com/" />
    <meta property="og:type" content="website" />
    <script src="https://www.wpiece.com/js/webcomponents.min.js"></script>
    <link  rel="import" href="http://www.wpiece.com/p/10_26" />
</head>
<body>
<?php require_once 'layouts/header.php'; ?>
<!-- Main Body: The is the main content area of the web site, contains a side bar  -->
<?php require_once 'layouts/topnav.php'; ?>
<div id="main-body" style="background-color: black; margin: 0px; padding:0px">
    <div class="row no-gutter">


        <!-- Main Body - Content Area: This is the main content area, unique for each page  -->
        <div id="main-body-content-area" class="col-sm-12">
            <!-- Unique Page Content Starts Here
            ================================================== -->
            <div>
                <div class="row" style="margin-top:8px;">
                    <div class="text-center col-sm-12">
                        <strong>
                            <div style="color: white; font-size: 50px;" id="time-counter" class="col-sm-12 text-lg-center">
                               <span style="background-color: rgba(255, 255, 255, 0.09); padding:10px;" id="day"></span>
                                <span style="background-color: rgba(255, 255, 255, 0.09); padding:10px;" id="hour"></span>
                                <span style="background-color: rgba(255, 255, 255, 0.09); padding:10px;" id="min"></span>
                                <span style="background-color: rgba(255, 255, 255, 0.09); padding:10px;" id="sec"></span>
                            </div>
                        </strong>
                    </div>
                    </div>
                    <div class="col-sm-2"></div>
                <p class="col-sm-4 text-center">
                <img id="img_div" class="img img-responsive" src="https://instafxng.com/imgsource/splurge.jpg" style="margin-top:5px; box-shadow: 0 4px 8px 0 rgba(255, 11, 0, 0.75), 0 6px 20px 0 rgba(255, 11, 0, 0.83)">
                </p>
                    <div class="col-sm-6 ">

                        <form class="form form-horizontal text-center" action="" method="post">
                            <div class="col-sm-12">
                                <?php include 'layouts/feedback_message.php'; ?>
                            </div>

                            <div class="input-group col-sm-12 text-center">
                                <div class="row">
                                    <div class="col-sm-3"></div>
                                    <div class="col-xs-6 text-center" style="margin-top:20px;">
                                        <span class="pull-left"><p></p></span>
                                        <p style="border-radius:15px; color: white;"><strong><i>Drop your email to opt in!</i></strong></p>
                                <input name="email" type="text" class="form-control col-sm-6" placeholder="Email Address">
                                <button name="proceed" type="submit" class="btn btn-primary"><strong>SUBMIT</strong><i class="fa fa-send"></i></button>
                                </div>
                                    <div class="col-sm-3"></div>
                            </div>
                        </form>
                            </div>
                    </div>
</div>

                </div>




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

                        // Display the result in the element with id="demo"
//                        document.getElementById("time-counter").innerHTML = days + " " + hours + " "
//                            + minutes + " " + seconds + " ";
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
            <!-- Unique Page Content Ends Here
            ================================================== -->

        </div>
        <!-- Main Body - Side Bar  -->
    </div>

</body>
<?php require_once 'layouts/footer.php'; ?>
</html>