<?php
set_include_path('../init/');
require_once 'initialize_general.php';
if (!$session_participant->is_logged_in())
{
    redirect_to("index.php");
}
$participant_email = $_SESSION['participant_email'];

$query = "SELECT questions_answered_correctly FROM quiz_participant WHERE participant_email = '$participant_email' LIMIT 1";
$result = $db_handle->runQuery($query);
$result = $db_handle->fetchAssoc($result);
$result = $result[0];
$questions_answered_correctly = $result['questions_answered_correctly'];
$total_score = $mark_per_question * $questions_answered_correctly;
$query = "UPDATE quiz_participant SET total_score = '$total_score' WHERE participant_email = '$participant_email' LIMIT 1";
$db_handle->runQuery($query);


$query = "SELECT total_time FROM quiz_participant WHERE participant_email = '$participant_email' LIMIT 1";
$result = $db_handle->runQuery($query);
$result = $db_handle->fetchAssoc($result);
$result = $result[0];
$total_time = $result['total_time'];
$average_time = $total_time / $no_of_allowed_questions;
$average_time = round($average_time,2);
$query = "UPDATE quiz_participant SET average_time = '$average_time' WHERE participant_email = '$participant_email' LIMIT 1";
$db_handle->runQuery($query);

$query = "SELECT total_time FROM quiz_participant WHERE participant_email = '$participant_email' LIMIT 1";
$result = $db_handle->runQuery($query);
$result = $db_handle->fetchAssoc($result);
$result = $result[0];
$total_time = $result['total_time'];
$total_time = round($total_time,2);

$total_correct_answers = $questions_answered_correctly;

$query = "SELECT CONCAT(first_name, SPACE(1), last_name) AS full_name FROM user WHERE email = '$participant_email' LIMIT 1";
$result = $db_handle->runQuery($query);
$result = $db_handle->fetchAssoc($result);
$result = $result[0];
$full_name = $result['full_name'];

?>

<!DOCTYPE html>
<html lang="en">
    <head>
        <style>
            .no-js #loader
            {
                display: none;
            }
            .js #loader
            {
                display: block; position: absolute; left: 100px; top: 0;
            }
            .se-pre-con
            {
                position: fixed;
                left: 0px;
                top: 0px;
                width: 100%;
                height: 100%;
                z-index: 9999;
                background: url(images/Spinner.gif) center no-repeat #fff;
            }
        </style>
        <script src="http://ajax.googleapis.com/ajax/libs/jquery/1.5.2/jquery.min.js"></script>
        <script src="http://cdnjs.cloudflare.com/ajax/libs/modernizr/2.8.2/modernizr.js"></script>
        <script>
            $(window).load(function()
            {
                // Animate loader off screen
                $(".se-pre-con").fadeOut("slow");
            });
        </script>
        <base target="_self">
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>57th Independence Anniversary | Instaforex Nigeria</title>
        <meta name="title" content="" />
        <meta name="keywords" content="" />
        <meta name="description" content="" />
        <meta http-equiv="Content-Language" content="en" />
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
        <meta http-equiv="Pragma" content="no-cache" />
        <meta name="robots" content="index, follow" />
        <meta name="author" content="Instant Web-Net Technologies Limited" />
        <meta name="publisher" content="Instant Web-Net Technologies Limited" />
        <meta name="copyright" content="Instant Web-Net Technologies Limited" />
        <meta name="rating" content="General" />
        <meta name="doc-rights" content="Private" />
        <meta name="doc-class" content="Living Document" />
        <link rel="stylesheet" href="css/instafx_fi.css">
        <link rel="shortcut icon" type="image/x-icon" href="images/favicon.png">
        <link rel="stylesheet" href="//maxcdn.bootstrapcdn.com/bootstrap/3.3.5/css/bootstrap.min.css">
        <link rel="stylesheet" href="//maxcdn.bootstrapcdn.com/font-awesome/4.3.0/css/font-awesome.min.css">
        <script src="//ajax.googleapis.com/ajax/libs/jquery/2.1.1/jquery.min.js"></script>
        <script src="//maxcdn.bootstrapcdn.com/bootstrap/3.3.5/js/bootstrap.min.js"></script>
        <script src="js/ie10-viewport-bug-workaround.js"></script>
        <script src="js/validator.min.js"></script>
        <script src="js/npm.js"></script>
        <script type='text/javascript' src='http://ajax.googleapis.com/ajax/libs/jquery/1.6.1/jquery.min.js'></script>
        <script type='text/javascript' src='loadImg.js'></script>
        <script type='text/javascript'>
                $(function()
                {
                    $('img').imgPreload()
                })
        </script>

    </head>
    <body>
        <div class="se-pre-con"></div>
        <!-- Header Section: Logo and Live Chat  -->
        <header id="header">
            <div class="container-fluid no-gutter masthead">
                <div class="row">
                    <div id="main-logo" class="col-sm-12 col-md-5">
                        <img src="images/ifxlogo.png" alt="Instaforex Nigeria Logo" />
                    </div>
                    <div id="top-nav" class="col-sm-12 col-md-7 text-right">
                    </div>
                </div>
            </div>
            <hr />
        </header>

        <!-- Main Body: The is the main content area of the web site, contains a side bar  -->
        <div id="main-body" class="container-fluid">
            <div class="row no-gutter">
                <!-- Main Body - Content Area: This is the main content area, unique for each page  -->
                <div id="main-body-content-area" class="col-md-12">
                    <!-- Unique Page Content Starts Here
                    ================================================== -->
                    <div id="thanks_view" class="section-tint super-shadow" >
                        <div class="row">
                            <h2 style="color: #289d07 !important;" class="text-center color-green">
                                Thank You For Participating!
                            </h2>
                            <hr />
                            <p class="text-center">
                                <img width="50%" height="50%" src="images/thanks.jpg" alt="Thank you picture" class="center-block img-responsive" />
                            </p>
                            <div class="row">
                                <div class="col-sm-12">
                                    <div class="col-sm-3">
                                    </div>
                                    <div class="col-sm-6">
                                        <div class="table-responsive">
                                            <table class="table table-bordered table-hover table-striped">
                                                <thead>
                                                <tr>
                                                    <th class="text-center" colspan="2">Quiz Results</th>
                                                </tr>
                                                </thead>
                                                <tbody>
                                                <tr>
                                                    <td><b>Total Correct Answers</b></td>
                                                    <td><?php echo $total_correct_answers ?></td>
                                                </tr>
                                                <tr>
                                                    <td><b>Total Time</b></td>
                                                    <td><?php echo $total_time ?> second(s)</td>
                                                </tr>
                                                <tr>
                                                    <td><b>Average Time</b></td>
                                                    <td><?php echo $average_time ?> second(s)</td>
                                                </tr>
                                                <tr>
                                                    <td><b>Total Score</b></td>
                                                    <td><?php echo $total_score ?>%</td>
                                                </tr>
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                    <div class="col-sm-3">
                                    </div>
                                </div>
                                <div class="col-sm-12 ">
                                    <div class="col-sm-2">
                                    </div>
                                    <div class="col-sm-8">
                                        <p class="text-justify">
                                            Thank you <?php echo $full_name; ?>,
                                            for participating in the Independence Day Contest.<br/>
                                            The top five participants will be announced on the 3rd of October 2017.
                                        </p>
                                    </div>
                                    <div class="col-sm-2">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- Unique Page Content Ends Here
                    ================================================== -->
                </div>
            </div>
        </div>
        <footer id="footer" class="super-shadow">
            <div class="container-fluid no-gutter">
                <div class="col-sm-12">
                    <div class="row">
                        <p class="text-center" style="font-size: 16px !important;">&copy; <?php echo date('Y'); ?>, All rights reserved. Instant Web-Net Technologies Limited (www.instafxng.com)</p>
                    </div>
                </div>
            </div>
        </footer>
    </body>
<?php
$session_participant->logout();
?>
</html>

