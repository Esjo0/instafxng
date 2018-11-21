<?php
require_once 'init/initialize_general.php';
$thisPage = "";
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

        <style>
            body {
                background: url("../images/full-bloom.png") repeat;
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

        <section class="container-fluid">
            <div class="row">
                <div class="col-sm-12 text-center">
                    <h1 style="color:#db281f; font-size: 45px !important;"><span style="font-family: 'Patua One', cursive;">InstaFxNg </span><span style="font-family: 'Pacifico', cursive;">Royal Ball</span></h1>
                    <p style="font-family: 'Josefin Sans', sans-serif !important; font-size: 22px !important;">A delightful evening to celebrate your loyalty and support to our brand</p>
                </div>
            </div>
        </section>

        <section class="container-fluid no-gutter" style="background-color: white; margin-top: 15px;">
            <div class="row" style="background-color: white;">
                <div class="col-sm-4" style="padding: 0 !important;">
                    <img src="images/royal_ball_image.jpg" alt="" class="img-responsive" />
                </div>

                <div class="col-sm-4">
                    <p>Please complete the form below to reserve your seat.</p>

                    <form data-toggle="validator" class="form-horizontal" role="form" method="post" action="">

                        <div class="form-group col-sm-12">
                            <div class="input-group">
                                <span class="input-group-addon"><i class="fa fa-user fa-fw"></i></span>
                                <input name="full_name" type="text" id="" value="" class="form-control" placeholder="Your Full Name" required/>
                            </div>
                        </div>

                        <div class="form-group col-sm-12">
                            <div class="input-group">
                                <span class="input-group-addon"><i class="fa fa-envelope fa-fw"></i></span>
                                <input name="email" type="text" id="" value="" class="form-control" placeholder="Your email address" required/>
                            </div>
                        </div>

                        <div class="form-group col-sm-12">
                            <small class="text-center"><strong>Will you be in attendance?</strong></small>
                        </div>

                        <div class="form-group col-sm-12">
                            <label><input type="radio" name="attendance" value="1" required> Yes</label> &nbsp;&nbsp;
                            <label><input type="radio" name="attendance" value="1" required> Maybe</label> &nbsp;&nbsp;
                            <label><input type="radio" name="attendance" value="1" required> No</label>
                        </div>

                        <div class="form-group col-sm-12">
                            <textarea name="address" class="form-control" rows="2" placeholder="Enter your home town" required></textarea>
                        </div>


                        <div class="form-group">
                            <div class="col-sm-12">
                                <input name="process" type="submit" class="btn btn-success" value="Proceed">
                            </div>
                        </div>

                    </form>

                </div>

                <div class="col-sm-4">
                    <p>Select your style your here</p>

                </div>
            </div>
        </section>

        <section class="container-fluid no-gutter" style="background-color: white; margin-top: 15px;">
            <div class="row">
                <div class="col-sm-6">
                    <h3>About the Royal Ball event</h3>
                    <p>The InstaFxNg 2018 dinner event is themed ‘Royal Ball’ in celebration of your loyalty and
                        support to our brand.</p>
                    <p>This year’s dinner is set to celebrate your royalty and loyalty in crown, wine and great
                        splendor!</p>
                    <p>We’re set to bring you a wonderful and an unforgettable evening at the ball as we host you to
                        an amazing experience of royalty that you are.</p>
                    <p>Your throne is set and the dinner is royal!</p>
                    <p>Come and be a part of our extravagant experience.</p>
                </div>

                <div class="col-sm-6" style="padding: 0 !important;">
                    <h3 style="padding: 15px !important;">Moments from Ethnic Impression 2017</h3>
                    <div class="embed-responsive embed-responsive-16by9">
                        <iframe class="embed-responsive-item" src="https://www.youtube.com/embed/6H2j_4cwnYE?rel=0"></iframe>
                    </div>
                </div>
            </div>
        </section>

        <!-- Footer Section: Copyright, Site Map  -->
        <footer id="footer" class="super-shadow">
            <div class="container-fluid no-gutter copyright">
                <div class="col-sm-12">
                    <p class="text-center">&copy; <?php echo date('Y'); ?>, All rights reserved. Instant Web-Net Technologies Limited (www.instafxng.com)</p>
                </div>
            </div>
        </footer>

    </body>
</html>