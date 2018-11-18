<?php
require_once '../init/initialize_client.php';
$thisPage = "";

if (!$session_client->is_logged_in()) {
    redirect_to("login.php");
}
$get_learning_position = $education_object->get_clients_max_lesson_completed($_SESSION['client_unique_code']);
$highest_lesson_published = $education_object->get_highest_lesson_published();

if($get_learning_position != $highest_lesson_published){
    redirect_to("./");
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
                                <p ><a class="btn btn-success btn-lg" href="https://instafxng.com/fxacademy/certificate.php">Download Your Certificate</a></p>
                                <p><a class="btn btn-success btn-lg" target="_blank" href="https://instafxng.com/fxacademy/schedule_training.php">Schedule A free one-on-one training with our analyst</a></p>
                                <hr />
                            </div>
                        </div>
                    </div>
                </div>
        </div>
        </div>
        <?php require_once 'layouts/footer.php'; ?>
    </body>
</html>