<?php
require_once 'init/initialize_general.php';
$thisPage = "Signals";
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

    </head>
    <body>
        <?php require_once 'layouts/header.php'; ?>
        <!-- Main Body: The is the main content area of the web site, contains a side bar  -->
        <div id="main-body" class="container-fluid">
            <div class="row no-gutter">
                <?php require_once 'layouts/topnav.php'; ?>

                <!-- Main Body - Content Area: This is the main content area, unique for each page  -->
                <div id="main-body-content-area" class="col-md-8 col-md-push-4 col-lg-9 col-lg-push-3">
                    <!-- Unique Page Content Starts Here
                    ================================================== -->
                    <div  class="item super-shadow page-top-section">
                        <?php  echo htmlspecialchars_decode(stripslashes(trim(file_get_contents("views/general_pages/advert_div.html"))));  ?>
                    </div>
                    <div class="section-tint super-shadow">
                        <h2 class="text-center">Intra-Day Signals For <?php echo date("F j, Y, g:i a")?></h2>
                        <div class=" section-tint super-shadow">

                            <br>
                            <div class="row">
                                <div class="col-sm-12">

                                    <span id="trans_remark_author">  </span>
                                    <span id="trans_remark">
<!--                                        <table class="table table-hover">-->
<!--  <thead>-->
<!--    <tr>-->
<!--      <th scope="col">#</th>-->
<!--      <th scope="col">First</th>-->
<!--      <th scope="col">Last</th>-->
<!--      <th scope="col">Handle</th>-->
<!--    </tr>-->
<!--  </thead>-->
<!--  <tbody>-->
<!--    <tr>-->
<!--      <th scope="row">BUY PRICE</th>-->
<!--      <td>Mark</td>-->
<!--      <td>Otto</td>-->
<!--      <td>@mdo</td>-->
<!--    </tr>-->
<!--  <tr>-->
<!--      <th scope="row">SELL PRICE</th>-->
<!--      <td>Mark</td>-->
<!--      <td>Otto</td>-->
<!--      <td>@mdo</td>-->
<!--    </tr>-->
<!--  </tbody>-->
<!--</table>-->
                                    </span>
                                    <span id="trans_remark_date"> <?php echo date("F j, Y, g:i a")?> </span>
                                    <button type="button" class="btn btn-outline-primary">Primary</button>
                                    <marquee behavior="scroll" direction="left" scrollamount="5" class="col-lg-12">
                                        Lorem Ipsum is simply dummy text of the printing and typesetting industry.
                                    </marquee>
                                </div>
                            </div>
                            <br>
                        </div>
                        <div class="text-center section-tint super-shadow">

                            <br>
                            <div class="row">
                                <div class="col-sm-6">
                                </div>
                                <div class="col-sm-6">
                                </div>
                            </div>
                            <br>
                        </div>
                    </div>
                    

                    <!-- Unique Page Content Ends Here
                    ================================================== -->

                </div>
                <!-- Main Body - Side Bar  -->
                <div id="main-body-side-bar" class="col-md-4 col-md-pull-8 col-lg-3 col-lg-pull-9 left-nav">
                <?php require_once 'layouts/sidebar.php'; ?>
                </div>
                
            </div>
        </div>
        <?php require_once 'layouts/footer.php'; ?>
    </body>
</html>