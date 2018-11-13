<?php
require_once '../init/initialize_general.php';

$get_params = allowed_get_params(['z','u']);
$choice = $get_params['z'];




?>
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <meta http-equiv="x-ua-compatible" content="ie=edge">
  <title>InstaFxNG Dinner 2018</title>
  <link rel="shortcut icon" type="image/x-icon" href="../images/favicon.png">
  <!-- Font Awesome -->
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">
  <!-- Bootstrap core CSS -->
  <link href="css/bootstrap.min.css" rel="stylesheet">
  <!-- Material Design Bootstrap -->
  <link href="css/mdb.min.css" rel="stylesheet">
  <!-- Your custom styles (optional) -->
  <link href="css/style.min.css" rel="stylesheet">
  <style type="text/css">
    html,
    body,
    header,
    .view {
      height: 100%;
    }

    @media (max-width: 740px) {
      html,
      body,
      header,
      .view {
        height: 1000px;
      }
    }

    @media (min-width: 800px) and (max-width: 850px) {
      html,
      body,
      header,
      .view {
        height: 650px;
      }
    }
    @media (min-width: 800px) and (max-width: 850px) {
              .navbar:not(.top-nav-collapse) {
                  background: #1C2331!important;
              }
          }
  </style>
</head>

<body>

<!-- Full Page Intro -->
<div class="view full-page-intro" style="background-image: url('img/royal.jpg'); background-repeat: no-repeat; background-size: cover;">

    <!-- Mask & flexbox options-->
    <div class="mask rgba-black-light d-flex justify-content-center align-items-center">

        <!-- Content -->
        <div class="container">

            <!--Grid row-->
            <div class="row wow fadeIn">

                <!--Grid column-->
                <div class="col-md-12 mb-4 white-text text-center">
                    <center><img style="background-color: white; border-radius: 10px;" src="../images/ifxlogo.png" class="img-fluid z-depth-1-half img-responsive" alt=""/><center>

                    <hr class="hr-light">

                    <h1><STRONG>INSTAFXNG ROYAL BALL</STRONG></h1>

                            <?php if ($choice == 1){?>
                            <!--Card-->
                            <div class="card">

                                <!--Card content-->
                                <div class="card-body">
                                    <p class="text-center" style="border-radius:5px; background-color: rgba(255, 194, 36, 0.29); color: black;"><b>YOU HAVE SUCCESSFULLY RESERVED A SEAT FOR THE INSTAFXNG ROYAL BALL</b></p>
                                </div>
                            </div>
                            <img style="opacity: 0.7" src="img/success_logo.png" class="img-fluid z-depth-1-half" alt="">
                            <?php }?>
                            <?php if ($choice == 2){?>
                                <!--Card-->
                                <div class="card">

                                    <!--Card content-->
                                    <div class="card-body">
                                        <p class="text-center" style="border-radius:5px; background-color: rgba(255, 194, 36, 0.29); color: black;"><b>YOUR SEAT HAS BEEN RESERVED TEMPORARILY CONFIRM YOUR RESRVATION WITHIN THE NEXT 5 DAYS.</b></p>
                                    </div>
                                </div>
                                <img style="opacity: 0.9" height="450" width="500" src="img/royal_ball.jpeg" class="img-fluid z-depth-1-half" alt="">
                            <?php }?>
                            <?php if ($choice == 3){?>
                                <!--Card-->
                                <div class="card">

                                    <!--Card content-->
                                    <div class="card-body">
                                        <p class="text-center" style="border-radius:5px; background-color: rgba(255, 194, 36, 0.29); color: black;"><b>THANKS YOU WE WILL SURELY INVITE YOU FOR SUBSEQUENT EVENTS.</b></p>
                                    </div>
                                </div>
                                <img style="opacity: 0.9" height="450" width="500" src="img/royal_ball.jpeg" class="img-fluid z-depth-1-half" alt="">
                            <?php }?>

                </div>
                <!--Grid column-->

            </div>
            <!--Grid row-->

        </div>
        <!-- Content -->

    </div>
    <!-- Mask & flexbox options-->

</div>
<!-- Full Page Intro -->

<!--Footer-->
<footer class="page-footer text-center font-small mt-4 wow fadeIn">

    <!--Copyright-->
    <div class="footer-copyright py-3">
        © 2018 Copyright:
        <a href="https://instafxng.com" target="_blank"> InsatFxNg.com</a>
    </div>
    <!--/.Copyright-->

</footer>
<!--/.Footer-->

<!-- JQuery -->
<script type="text/javascript" src="js/jquery-3.3.1.min.js"></script>
<!-- Bootstrap tooltips -->
<script type="text/javascript" src="js/popper.min.js"></script>
<!-- Bootstrap core JavaScript -->
<script type="text/javascript" src="js/bootstrap.min.js"></script>
<!-- MDB core JavaScript -->
<script type="text/javascript" src="js/mdb.min.js"></script>
<!-- Initializations -->
<script type="text/javascript">
    // Animations initialization
    new WOW().init();
</script>
</body>

</html>