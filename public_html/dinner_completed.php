<?php
require_once 'init/initialize_general.php';

$get_params = allowed_get_params(['z']);
$choice = $get_params['z'];

if (empty($choice) || $choice == NULL) {
    redirect_to("https://instafxng.com");
    exit;
}


?>
<!DOCTYPE html>
<html lang="en">
<head>
    <base target="_self">
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>InstaFxNg Royal Ball</title>
    <meta name="title" content="InstaFxNg Royal Ball" />
    <meta name="keywords" content=" ">
    <meta name="description" content=" ">
    <link rel="stylesheet" href="css/free_seminar.css">
    <link href="https://fonts.googleapis.com/css?family=Josefin+Sans|Pacifico|Patua+One" rel="stylesheet">
    <?php require_once 'layouts/head_meta.php'; ?>
    <link rel="stylesheet" href="css/prettyPhoto.css">
    <style>
        .photo_g > ul, .photo_g > li {
            display: inline;
        }
    </style>
    <style>
        body {
            background: url("images/full-bloom.png") repeat;
        }
    </style>
</head>
<body>
<!-- Header Section: Logo and Live Chat  -->
<header id="header">
    <div class="container-fluid no-gutter masthead">
        <div class="row">
            <div id="main-logo" class="col-sm-12 col-md-5">
                <a href="./" title="Home Page"><img src="images/ifxlogo.png" alt="Instaforex Nigeria Logo" /></a>
            </div>

        </div>
    </div>
    <hr />
</header>
<section id="" class=" view wow fadeIn"
         style=" margin:0px; padding:0px;">
    <?php if ($choice == 1) { ?>
        <!--Card-->
        <div class="card">

            <!--Card content-->
            <div class="card-body">
                <p class="text-center"
                   style="border-radius:5px; background-color: rgba(255, 194, 36, 0.29); color: black; font-family: cursive !important;">
                    <b>YOU HAVE SUCCESSFULLY RESERVED A SEAT FOR THE INSTAFXNG ROYAL BALL</b></p>
            </div>
        </div>
        <img style="opacity: 0.5" src="img/success_logo.png" class="img-fluid z-depth-1-half" alt="">
    <?php } ?>
    <?php if ($choice == 2) { ?>
        <!--Card-->
        <div class="card">

            <!--Card content-->
            <div class="card-body">
                <p class="text-center"
                   style="font-family: cursive !important; border-radius:5px; background-color: rgba(255, 194, 36, 0.29); color: black;">
                    <b>YOUR SEAT HAS BEEN TEMPORARILY RESERVED. KINDLY CONFIRM YOUR RESERVATION WITHIN THE NEXT
                        5 DAYS.</b></p>
            </div>
        </div>
    <?php } ?>
    <?php if ($choice == 3) { ?>
        <!--Card-->
        <div class="card">

            <!--Card content-->
            <div class="card-body">
                <p class="text-center"
                   style="font-family: cursive !important; border-radius:5px; background-color: rgba(255, 194, 36, 0.29); color: black;">
                    <b>THANK YOU WE WILL SURELY INVITE YOU FOR SUBSEQUENT EVENTS.</b></p>
            </div>
        </div>
    <?php } ?>
</section>
<!-- Full Page Intro -->
<!-- Full Page Intro -->

<!-- Footer Section: Copyright, Site Map  -->
<footer id="footer" class="super-shadow">
    <div class="container-fluid no-gutter copyright">
        <div class="col-sm-12">
            <p class="text-center">&copy; <?php echo date('Y'); ?>, All rights reserved. Instant Web-Net Technologies Limited (www.instafxng.com)</p>
        </div>
    </div>
</footer>

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