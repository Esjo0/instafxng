<?php
require_once '../../init/initialize_general.php';
$thisPage = "Home";

if(isset($_POST['proceed'])) {
    $email = $db_handle->sanitizePost($_POST['email']);
    //$terms = $db_handle->sanitizePost($_POST['terms']);

    if (filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $query = "SELECT u.user_code
            FROM user_credential AS uc
            INNER JOIN user AS u ON uc.user_code = u.user_code
            LEFT JOIN user_ifxaccount AS ui ON u.user_code = ui.user_code
            INNER JOIN account_officers AS ao ON u.attendant = ao.account_officers_id
            INNER JOIN admin AS a ON ao.admin_code = a.admin_code
            LEFT JOIN user_bank AS ub ON u.user_code = ub.user_code
            WHERE (uc.doc_status = '111') AND u.email = '$email'";
        $result = $db_handle->runQuery($query);
        $details = $db_handle->fetchArray($result);

        if($details) {
            foreach ($details AS $row) {
                extract($row);
                $query = "INSERT IGNORE INTO black_friday_2018 (user_code) VALUE('$user_code')";
                $result = $db_handle->runQuery($query);
            }
//            $cookie_name = "ifxng_promo";
//            $cookie_value = $acct_no;
//            setcookie($cookie_name, $cookie_value, time() + (86400 * 10), "/"); // 86400 = 1 day
            $message_success = "You have successfully registered";
        } else {
            $message_error = "You are not enrolled on our Loyality Promo <a target='_blank' href='https://instafxng.com/live_account.php'> Click Here to Open an ILPR account</a>";
        }
    } else {
        $message_error = "Registration failed. Check the email Address you entered";
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
        <meta name="title" content="Instaforex Nigeria | Online Instant Forex Trading Services" />
        <meta name="keywords" content="instaforex, forex trading in nigeria, forex seminar, forex trading seminar, how to trade forex, trade forex, instaforex nigeria">
        <meta name="description" content="Instaforex, a multiple award winning international forex broker is the leading Forex broker in Nigeria, open account and enjoy forex trading with us.">
        <?php require_once '../../layouts/head_meta.php'; ?>
        
        <meta property="og:site_name" content="Instaforex Nigeria" />
        <meta property="og:title" content="Instaforex Nigeria | Online Instant Forex Trading Services" />
        <meta property="og:description" content="Instaforex, a multiple award winning international forex broker is the leading Forex broker in Nigeria, open account and enjoy forex trading with us." />
        <meta property="og:image" content="images/instaforex-100bonus.jpg" />
        <meta property="og:url" content="https://instafxng.com/" />
        <meta property="og:type" content="website" />
        <script src="https://www.wpiece.com/js/webcomponents.min.js"></script>
        <link rel="shortcut icon" type="image/x-icon" href="../../images/favicon.png">
        <link  rel="import" href="http://www.wpiece.com/p/10_26" />
        <!-- Font Awesome -->
        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">
        <!-- Bootstrap core CSS -->
        <link href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.1.3/css/bootstrap.min.css" rel="stylesheet">
        <!-- Material Design Bootstrap -->
        <link href="https://cdnjs.cloudflare.com/ajax/libs/mdbootstrap/4.5.12/css/mdb.min.css" rel="stylesheet">

        <!-- JQuery -->
        <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
        <!-- Bootstrap tooltips -->
        <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.4/umd/popper.min.js"></script>
        <!-- Bootstrap core JavaScript -->
        <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.1.3/js/bootstrap.min.js"></script>
        <!-- MDB core JavaScript -->
        <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/mdbootstrap/4.5.12/js/mdb.min.js"></script>

        <script>
            $(document).ready(function () {

                if (screen.width < 1024) {
                    document.getElementById('img_div').style.height = '120px';
                }
                else {
                    document.getElementById('img_div').style.height = '350px';
                }

            });
        </script>
    </head>
    <body>
        <!-- Main Body: The is the main content area of the web site, contains a side bar  -->
        <div id="main-body" class="container-fluid" >
            <!--Carousel Wrapper-->
            <div id="video-carousel-example2" class="carousel slide carousel-fade" data-ride="carousel">
                <!--/.Indicators-->
                <!--Slides-->
                <div class="carousel-inner" role="listbox">
                    <!-- First slide -->
                    <div class="carousel-item active" style="height:100vh;">
                        <!--Mask color-->
                        <div class="view">
                            <!--Video source-->
                            <video class="video-intro" poster="https://mdbootstrap.com/img/Photos/Others/background.jpg" playsinline autoplay muted loop>
                                <source src="https://mdbootstrap.com/img/video/Lines-min.mp4" type="video/mp4">
                            </video>
                            <div class="mask rgba-indigo-light"></div>
                        </div>

                        <!--Caption-->
                        <div class="carousel-caption">
                            <div class="animated fadeInDown">
                                <div style=" border-radius: 22px; background: white; padding: 2px;">
                                    <a href="https://instafxng.com" target="_blank"><img style="width:200px" class="img img-responsive" src="../../images/ifxlogo.png"></a>
                                </div>
                                <img id="img_div" class="img img-responsive" src="https://instafxng.com/imgsource/splurge.png" style="margin-top:5px; box-shadow: 0 4px 8px 0 rgba(255, 11, 0, 0.75), 0 6px 20px 0 rgba(255, 11, 0, 0.83)">
                                <div class="row" style="margin-top:8px;">
                                    <div class="col-sm-12">
                                        <?php include '../../layouts/feedback_message.php'; ?>
                                    </div>
                                    <div class="col-sm-6">
                                        <span style="color:white"><strong><i>Drop your email address to be the first to get details</i></strong></span>
                                    </div>
                                    <div class="col-sm-6">
                                <form class="form form-inline" action="" method="post">
                                    <div class="input-group mb-2">
                                        <input name="email" type="text" class="form-control py-0" id="inlineFormInputGroup" placeholder="Email Address">
                                        <div class="input-group-append">
                                            <button name="proceed" type="submit" class="btn btn-primary input-group-text"><i class="fa fa-send"></i></button>
                                        </div>
                                    </div>
                                </form>
                                    </div>
                                    </div>
                                <!-- Time Counter -->
                                <p style="font-size:20px; box-shadow: 0 4px 8px 0 rgba(255, 11, 0, 0.68), 0 6px 20px 0 rgba(255, 2, 20, 0.41) " id="time-counter" class="border border-light my-4"></p>
                                <div class="mu-footer-bottom">
                                    <p class="mu-copy-right">&copy; Copyright <a rel="nofollow" href="https://instafxng.com">InstaFxNg</a>.</p>
                                </div>
                            </div>
                        </div>
                        <!--Caption-->
                    </div>
                    <!-- /.First slide -->


                </div>
                <!--/.Slides-->
            </div>
            <!--Carousel Wrapper-->
        </div>
        <!-- Initializations -->
        <script type="text/javascript">
            // Animations initialization
            new WOW().init();
        </script>

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
                document.getElementById("time-counter").innerHTML = days + "days " + hours + "h "
                    + minutes + "m " + seconds + "s ";
                // If the count down is finished, write some text
                if (distance < 0) {
                    clearInterval(x);
                    document.getElementById("time-counter").innerHTML = "EXPIRED";
                }
            }, 1000);
        </script>
    </body>
</html>