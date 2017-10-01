<?php
require_once '../init/initialize_general.php';

if (isset($_POST['start']) && !empty($_POST['email']))
{
    $email = $db_handle->sanitizePost($_POST['email']);
    $email = strip_tags(trim($email));
    if(check_email($email))
    {
        if ($obj_quiz->valid_ifx_email($email))
        {
            $obj_quiz->add_new_participant($email);
            if(!$obj_quiz->get_invalid_participant($email))
            {
                $session_participant->login($email);
                redirect_to("questions.php");
            }
            else
            {
                $message_error = "Sorry you have already participated in this quiz!";
            }
            //$obj_quiz->add_new_participant($email);
        }
        else
        {
            $message_error = "Sorry you must have an InstaForex account!";
        }
    }
    else
    {
        $message_error = "Sorry this email address is invalid!";
    }
}
else
{ // Form has not been submitted.
    $acc_no = "";
}
?>
<!DOCTYPE html>
<html lang="en">
    <head>
        <style>
            .no-js #loader { display: none;  }
            .js #loader { display: block; position: absolute; left: 100px; top: 0; }
            .se-pre-con {
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
        <script type='text/javascript' src='//ajax.googleapis.com/ajax/libs/jquery/1.6.1/jquery.min.js'></script>
        <script type='text/javascript' src='loadImg.js'></script>
        <script type='text/javascript'>
            $(function(){
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
                    <div id="login_view" class="section-tint super-shadow" >
                        <?php require_once 'feedback_message.php'; ?>
                        <div class="row">
                            <div class="col-md-4">
                                <p class="text-center"><img src="images/independence-banner-1.jpg" alt="" class="center-block img-responsive"></p>
                            </div>
                            <div class="col-md-8">
                                <div style="max-width: 850px; margin: 0 auto; ">
                                    <form data-toggle="validator" class="form-horizontal" role="form" method="post" action="">
                                        <div class="form-group">
                                            <label class="control-label col-sm-3" for="email">Email Address:</label>
                                            <div class="col-sm-9 col-lg-7">
                                                <input name="email" type="text" class="form-control" id="email" required>
                                            </div>
                                        </div>

                                        <div class="form-group">
                                            <div class="col-sm-offset-3 col-sm-9">
                                                <button type="button" data-target="#confirm-start" data-toggle="modal" class="btn btn-success btn-lg">Get Me Started Now!</button>
                                                <!--Modal - confirmation boxes-->
                                                <div id="confirm-start" tabindex="-1" role="dialog" aria-hidden="true" class="modal fade">
                                                    <div class="modal-dialog">
                                                        <div class="modal-content">
                                                            <div class="modal-header">
                                                                <button type="button" data-dismiss="modal" aria-hidden="true" class="close">&times;</button>
                                                                <p class="text-danger text-center"><strong>Here are the guidelines to Participating in the Independence Day Quiz Competition.</strong></p>
                                                            </div>
                                                            <div class="modal-body">
                                                                    <p class="text-justify">Follow the rules and regulations of the competition below diligently and
                                                                        attempt all questions to the best of your knowledge.</p>
                                                                <ul>
                                                                    <li>Only accounts enrolled on Instafxng Loyalty Program and Rewards (ILPR) can participate in this contest.</li>
                                                                    <li>You have 1 minute 15 secs to answer 15 multiple choice questions on the history of Nigeria.
                                                                    Each question has a time allotted it.</li>
                                                                    <li>If you couldn’t select an answer before time out, you have missed the question.</li>
                                                                    <li>You are allowed to participate in this contest just once even if you have more than one account.</li>
                                                                    <li>Five people with the highest scores will get $20 each.</li>
                                                                </ul>
                                                            </div>
                                                            <div class="modal-footer">
                                                                <input name="start" type="submit" class="btn btn-success" value="Start Now!">
                                                                <button type="submit" name="close" onClick="window.close();" data-dismiss="modal" class="btn btn-danger">Close!</button>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
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