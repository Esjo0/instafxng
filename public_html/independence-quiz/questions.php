<?php
set_include_path('../init/');
require_once 'initialize_general.php';
if (!$session_participant->is_logged_in())
{
    redirect_to("index.php");
}
$participant_email = $_SESSION['participant_email'];
if($_SERVER['REQUEST_METHOD'] == 'POST' || isset($_POST['next']))
{
    extract($_POST);

    $time = $minute.".".$second;
    $time = $time_per_question - $time;

    $question = $obj_quiz->get_question($participant_email);

    $total_no_of_questions_taken = $obj_quiz->get_total_no_of_questions_taken($participant_email);

    if($total_no_of_questions_taken > $no_of_allowed_questions)
    {
        $obj_quiz->grade_question($question_id, $option, $participant_email);
        $obj_quiz->update_time($time, $participant_email);
        redirect_to("thankyou.php");
    }

    $obj_quiz->grade_question($question_id, $option, $participant_email);

    $obj_quiz->update_time($time, $participant_email);
}
else
{
    $question = $obj_quiz->get_question($participant_email);
    $total_no_of_questions_taken = $obj_quiz->get_total_no_of_questions_taken($participant_email);
    $total_no_of_questions_taken = $total_no_of_questions_taken['total_questions'];
}
extract($question);
$options = explode("*", $options);
?>

<!DOCTYPE html>
<html lang="en" xmlns="http://www.w3.org/1999/html">
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
    <script src="//ajax.googleapis.com/ajax/libs/jquery/1.5.2/jquery.min.js"></script>
    <script src="//cdnjs.cloudflare.com/ajax/libs/modernizr/2.8.2/modernizr.js"></script>
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
    <script type='text/javascript' src='loadImg.js'></script>
    <script type='text/javascript'>
        $(function()
        {
            $('img').imgPreload()
        })
    </script>
    <script type="text/javascript">
        // set minutes
        var mins = 0;

        // calculate the seconds (don't change this! unless time progresses at a different speed for you...)
        var secs = 5;
        function countdown()
        {
            setTimeout('Decrement()',1000);
        }
        function Decrement()
        {
            if (document.getElementById)
            {
                if(document.getElementById("seconds").innerHTML == "0")
                {
                    document.getElementById("next").disabled = true;
                    document.getElementById("questions_form").submit();
                }
                minutes = document.getElementById("minutes");
                seconds = document.getElementById("seconds");
                if (seconds < 59)
                {
                    seconds.innerHTML = secs;
                }
                else
                    {
                    minutes.innerHTML = getminutes();
                    seconds.innerHTML = getseconds();
                }
                secs--;
                /**/
                setTimeout('Decrement()',1000);

            }
        }
        function getminutes()
        {
            // minutes is seconds divided by 60, rounded down
            mins = Math.floor(secs / 60);
            return mins;
        }
        function getseconds()
        {
            // take mins remaining (as seconds) away from total seconds remaining
            return secs-Math.round(mins *60);
        }
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
            <div  onmousedown="return false;" onselectstart="return false;" id="question_view" class="section-tint super-shadow" >
                <div class="row">
                    <div class="col-md-4">
                        <p class="text-center"><img src="images/independence-banner-1.jpg" alt="" class="center-block img-responsive"></p>
                    </div>
                    <div class="well well-sm col-sm-8" style="padding: 5%">
                        <div style="max-width: 850px; margin: 0 auto; ">
                            <form id="questions_form"  data-toggle="validator" class="form-horizontal" role="form" method="post" action="<?php echo $_SERVER['PHP_SELF']; ?>">
                                <input type="hidden" name="question_id" value="<?php echo $question_id; ?>"/>
                                <div class="pull-right">
                                    <h1  id="#run-the-timer">
                                        <span id="minutes" class="minute"></span>:<span id="seconds" class="second"></span>
                                    </h1>
                                    <input  id="minute" type="hidden" name="minute"/><input id="second" type="hidden" name="second"/>


                                    <script type="text/javascript">countdown();</script>

                                </div>
                                <div class="form-group">
                                    <h2><strong>Question <?php echo $total_no_of_questions_taken ?> of <?php echo $no_of_allowed_questions; ?>:</strong></h2>
                                    <br/>
                                    <h4><?php  echo $question; ?></h4>
                                    <div class="form-group row">
                                        <?php
                                        foreach($options as $key=>$value)
                                        {
                                            echo '<div class="col-sm-12">';
                                            echo '<div class="radio">';
                                            echo '<label for="option">';
                                            echo '<input id="option" type="radio" name="option" value="'.$value.'">'.$value;
                                            echo '</label>';
                                            echo '</div>';
                                            echo '</div>';
                                            echo '<br/>';
                                        }?>
                                    </div>
                                    <input id="next" type="submit" name="next" class="btn btn-success pull-right" value="Next" />
                                </div>
                            </form>
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

</html>