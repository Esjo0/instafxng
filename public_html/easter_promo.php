<?php
require_once 'init/initialize_general.php';
$thisPage = "Promotion";
?>
<!DOCTYPE html>
<html lang="en">
    <head>
        <base target="_self">
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>Instaforex Nigeria | Special Easter Promo</title>
        <meta name="title" content=" " />
        <meta name="keywords" content=" ">
        <meta name="description" content=" ">
        <?php require_once 'layouts/head_meta.php'; ?>
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
                    <div class="row">
                        <div class="col-sm-12">
                            <div class="section-tint super-shadow">
                                <center>
                                    <img src="images/Easter/Easter_Campaign-Banner2.jpg" alt="Happy Easter Holidays" class="img-responsive" />
                                </center>

                                <h3 class="text-center">INSTAFOREX DELUXE EASTER BUFFET</h3>
                                <p class="text-justify">It’s More than a Buffet, It’s an Experience!</p>
                                <p class="text-justify">Enjoy an Exclusive All-Expense-Paid Buffet Treat at Four Points by Sheraton Hotel in the InstaForex Easter Promo.</p>
                                <p class="text-justify">The promo runs for 3 days only. It starts on Thursday, 29th of March and ends on Saturday 31st of March, 2018.</p>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-sm-12">
                            <div class="section-tint super-shadow">
                                <?php require_once 'layouts/feedback_message.php'; ?>
                                <div class="row">
                                    <div class="col-sm-7">
                                        <table class="table table-responsive table-striped table-bordered table-hover">
                                            <thead>
                                            <tr>
                                                <th colspan="3" class="text-center">Today's Race as at <?php echo date('h:i:s A')?>, <?php echo date('d M Y')?></th>
                                            </tr>
                                            <tr>
                                                <th>Position</th>
                                                <th>Participants Name</th>
                                                <th>Promo Points</th>
                                            </tr>
                                            </thead>
                                            <tbody>
                                            <tr>
                                                <th>1</th>
                                                <td>Nkiru Blessing</td>
                                                <th>68</th>
                                            </tr>
                                            <tr>
                                                <th>2</th>
                                                <td>Nkiru Blessing</td>
                                                <th>68</th>
                                            </tr>
                                            <tr>
                                                <th>3</th>
                                                <td>Nkiru Blessing</td>
                                                <th>68</th>
                                            </tr>
                                            </tbody>
                                        </table>
                                    </div>
                                    <div class="col-sm-5">
                                        <table class="table table-responsive table-striped table-bordered table-hover">
                                            <thead>
                                            <tr>
                                                <th colspan="2" class="text-center">Guaranteed Winners</th>
                                            </tr>
                                            <tr>
                                                <th>Date</th>
                                                <th>Participants Name</th>
                                            </tr>
                                            </thead>
                                            <tbody>
                                            <tr>
                                                <td>Day 1 -> <?php echo date_to_text('29-03-2018')?></td>
                                                <td>Nkiru Blessing</td>
                                            </tr>
                                            <tr>
                                                <td>Day 2 -> <?php echo date_to_text('30-03-2018')?></td>
                                                <td>Nkiru Blessing</td>
                                            </tr>
                                            <tr>
                                                <td>Day 3 -> <?php echo date_to_text('31-03-2018')?></td>
                                                <td>Nkiru Blessing</td>
                                            </tr>
                                            </tbody>
                                        </table>
                                    </div>
                                    <div class="col-sm-12">
                                        <a class="btn-group-justified btn btn-success btn-lg" href="deposit.php"><b>Join The Race Now!!!</b></a>
                                    </div>
                                    <div class="col-sm-12">
                                        <p><b>TERMS & CONDITIONS</b></p>
                                        <p class="text-justify"><i class="glyphicon glyphicon-check"></i>   Only ILPR clients are eligible to participate.</p>
                                        <p class="text-justify"><i class="glyphicon glyphicon-check"></i>   Participants are required to fund multiples units of $50 and above.
                                            <br/>$50 = 1 Unit as regards this promo.</p>
                                        <p class="text-justify"><i class="glyphicon glyphicon-check"></i>   Any deposit transaction above $50 will be split into $50 portions
                                            to generate equivalent unit amount. <br/> E.g. If you fund $1000 once in a day, it will be divide by $50 and that means you have funded
                                            50 dollars 20 times that day.</p>
                                        <p class="text-justify"><i class="glyphicon glyphicon-check"></i>   Multiple transactions of $50 at different intervals within the same
                                            day still counts every participant.</p>
                                        <p class="text-justify"><i class="glyphicon glyphicon-check"></i>   The trader with the highest number of multiple $50 transactions in
                                            a day is the winner for that particular day.</p>
                                        <p class="text-justify"><i class="glyphicon glyphicon-check"></i>   All daily winners automatically qualifies for the prize.</p>
                                        <p class="text-justify"><i class="glyphicon glyphicon-check"></i>   Winners will be contacted to redeem their prizes within – and -. (Please state date for redemption)</p>
                                        <p class="text-justify"><i class="glyphicon glyphicon-check"></i>   The prize is an Exclusive All-Expense-Paid Buffet Treat at Four Points by Sheraton Hotel.<br/>
                                            No cash equivalent will be issued out to winner in place of the stated prize.</p>
                                    </div>
                                </div>
                            </div>
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