<?php
require_once '../init/initialize_client.php';
$thisPage = "";

if (!$session_client->is_logged_in()) {
    redirect_to("login.php");
}

?>
<!DOCTYPE html>
<html lang="en">
    <head>
        <base target="_self">
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>Instaforex Nigeria</title>
        <meta name="title" content="" />
        <meta name="keywords" content="">
        <meta name="description" content="">
        <?php require_once 'layouts/head_meta.php'; ?>
        <script>
            function print_report(divName) {
                var printContents = document.getElementById(divName).innerHTML;
                var originalContents = document.body.innerHTML;
                document.body.innerHTML = printContents;
                window.print();
                document.body.innerHTML = originalContents;
            }
        </script>

    </head>
    <body>
        <?php require_once 'layouts/header.php'; ?>

        <!-- Main Body: The is the main content area of the web site, contains a side bar  -->
        <div id="main-body" class="container-fluid">
            <div class="row no-gutter">

                <!-- Main Body - Content Area: This is the main content area, unique for each page  -->
                <div id="main-body-content-area" class="col-md-12">

                    <!-- Unique Page Content Starts Here
                    ================================================== -->
                    <?php require_once 'layouts/navigation.php'; ?>

                    <div id="main-container" class="section-tint super-shadow">
                        <div class="row">
                            <div class="col-md-12">
                                <h1 class="text-center">Forex Trading Academy Milestone</h1>
                                <p>Congratulations, <strong><?php echo $_SESSION['client_last_name'] . ' ' . $_SESSION['client_first_name']; ?></strong>, you have completed all the available courses in the
                                    <strong>Forex Profit Academy</strong>. You should be proud of yourself.</p>
                                <p>You can now comfortably take live trades in the Forex Market and make as much profit as you desire.
                                    At this point, you qualify for our 100% education bonus* for funding your account.</p>
                                <p><a class="btn btn-success btn-lg" target="_blank" href="https://instafxng.com/deposit.php">Fund Account - Get Bonus Now</a></p>
                                <p><button onclick="print_report('print_out');" type="button" class="btn btn-lg btn-success">Download Your Certificate</button></p>
                                <p><a class="btn btn-success btn-lg" target="_blank" href="schedule_training.php">Schedule A free one-on-one training with our analyst</a></p>
                                <hr />
                            </div>
                        </div>
                    </div>
                    <div style="display: none;" id="print_out">
                        <div class="row">
                            <div class="col-md-2"></div>
                        <div class="col-md-8" style="height:100%; width:100%; padding:20px; text-align:center; border: 5px solid #787878">
                            <img src="images/ifxlogo.png" alt="Instaforex Nigeria Logo" /></br>
                            <span style="font-size:50px; font-weight:bold">Certificate of Completion</span>
                            <br><br>
                            <span style="font-size:25px"><i>This is to certify that</i></span>
                            <br><br>
                            <span style="font-size:30px"><b><?php echo $_SESSION['client_last_name'] . ' ' . $_SESSION['client_first_name']; ?></b></span><br/><br/>
                            <span style="font-size:25px"><i>
                                    has satisfactorily completed the requirements for completion of the course and is hereby awarded this Certificate Of Completion. In withness, we acknowledge your learing and studies at the Forex Academy.
                                    </i></span> <br/>
                            <span style="font-size:30px">----------</span> <br/><br/>
                            <img class="pull-left" src="https://instafxng.com/imgsource/demolas_signature.png" width="100" height="65"><br/><br/><br/><br>
                            <span style="font-size:20px; text-align:left;" class="pull-left">Ademola Oyebode</span> <br/><br/>
                            <span style="font-size:15px; text-align:left;" class="pull-left">Director Of Programs.</span><br/><br/>
                            <span style="font-size:10px"><i>www.instafxng.com/fxacademy</i></span>
                        </div>
                            <div class="col-md-2"></div>
                            </div>
                    </div>
                    </div>
        </div>

        <?php require_once 'layouts/footer.php'; ?>
    </body>
</html>