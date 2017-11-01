<?php
    require_once("../init/initialize_general.php");
    require_once("../init/initialize_admin.php");
    // Process submitted form
    if (isset($_POST['process']))
    {
        foreach($_POST as $key => $value)
        {
            $_POST[$key] = $db_handle->sanitizePost(trim($value));
        }
        extract($_POST);
        if(empty($email) || empty($phone) || empty($full_name) || empty($state_of_residence) || !isset($ticket_type))
        {

            $message_error = "All fields are compulsory, please try again.";
            echo '<script type="text/javascript">scroll_down();</script>';
        }
        elseif (!check_email($email))
        {

            $message_error = "You have provided an invalid email address. Please try again.";
            echo '<script type="text/javascript">scroll_down();</script>';
        }
        elseif ($admin_object->dinner_guest_2017_is_duplicate($email))
        {

            $message_error = "You placed a reservation request already";
            echo '<script type="text/javascript">scroll_down();</script>';
        }
        else
        {
            $new_reg = $admin_object->add_new_dinner_guest_2017($full_name, $email, $phone, $ticket_type, $state_of_residence, $comments);
            if($new_reg)
            {
                $subject = "AFRO NITE 2017";
                $message = <<<MAIL
    <div style="background-color: #F3F1F2">
    <div style="max-width: 80%; margin: 0 auto; padding: 10px; font-size: 14px; font-family: Verdana;">
    <img src="https://instafxng.com/images/ifxlogo.png" />
    <hr />
    <div style="background-color: #FFFFFF; padding: 15px; margin: 5px 0 5px 0;">
    <p>Dear $full_name,</p>
    
    <p>It’s been almost 365 days and a lot has happened during the year,
    but get ready for one more!</p>
    
    <p>I want to take this opportunity to appreciate you for showing
    consistent confidence in the services we render and allowing us to
    service you this year. You are truly one of the most loyal clients
    of our company and serving you remains our pleasure.</p>
    
    <p>To roundup the year together, I humbly request that you join us
    for an exciting and entertaining time at our annual Christmas Dinner
    Event specially designed so you could relax and have fun with the
    members of our team and other Forex traders like yourself.</p>
    
    <p>This year’s dinner is themed the <strong>Classic 80s Night</strong>. When you step
    inside the venue; the music, the theme and overall ambience will
    transport you to a simpler and more innocent time where you can have the
    feel of what it used to be in the 80s.</p>
    
    <p>If you love to have fun, you should be at this event as the outfit,
    the looks the music and the venue will represent what it used to be
    from way back 80s, and there would be lots of exciting activities lined
    up alongside the 3 course dinner to be served.</p>
    
    <p style="text-align: center"><strong>One last thing ...</strong></p>
    
    <p>We have one last surprise for you before the year ends, you will
    witness the launch of a new service created to make you more money
    daily even as you trade.</p>
    
    <!---<p style="text-align: center">You can
    <a href="https://instafxng.com/dinner.php?x=$id_encrypt">click here</a>
    to reserve your seat now.</p>-->
    
    <p><strong>NOTE: Admission is strictly by Invitation.</strong></p>
    
    <p>It would be an honor to have you at the event as I earnestly look
    forward to welcoming you and meeting you in person.</p>
    
    <br /><br />
    <p>Best Regards,</p>
    <p>Fujah Abideen,<br />
    Corporate Communications Manager<br />
    www.instafxng.com</p>
    <br /><br />
    </div>
    <hr />
    <div style="background-color: #EBDEE9;">
    <div style="font-size: 11px !important; padding: 15px;">
    <p style="text-align: center"><span style="font-size: 12px"><strong>We"re Social</strong></span><br /><br />
        <a href="https://facebook.com/InstaForexNigeria"><img src="https://instafxng.com/images/Facebook.png"></a>
        <a href="https://twitter.com/instafxng"><img src="https://instafxng.com/images/Twitter.png"></a>
        <a href="https://www.instagram.com/instafxng/"><img src="https://instafxng.com/images/instagram.png"></a>
        <a href="https://www.youtube.com/channel/UC0Z9AISy_aMMa3OJjgX6SXw"><img src="https://instafxng.com/images/Youtube.png"></a>
        <a href="https://linkedin.com/company/instaforex-ng"><img src="https://instafxng.com/images/LinkedIn.png"></a>
    </p>
    <p><strong>Head Office Address:</strong> TBS Place, Block 1A, Plot 8, Diamond Estate, Estate Bus-Stop, LASU/Isheri road, Isheri Olofin, Lagos.</p>
    <p><strong>Lekki Office Address:</strong> Road 5, Suite K137, Ikota Shopping Complex, Lekki/Ajah Express Road, Lagos State</p>
    <p><strong>Office Number:</strong> 08028281192</p>
    <br />
    </div>
    <div style="font-size: 10px !important; padding: 15px; text-align: center;">
    <p>This email was sent to you by Instant Web-Net Technologies Limited, the
        official Nigerian Representative of Instaforex, operator and administrator
        of the website www.instafxng.com</p>
    <p>To ensure you continue to receive special offers and updates from us,
        please add support@instafxng.com to your address book.</p>
    </div>
    </div>
    </div>
    </div>
MAIL;
                $system_object->send_email($subject, $message, $email, $full_name);
                $message_success = "You have successfully created a new dinner reservation.";
                echo '<script type="text/javascript">scroll_down();</script>';
            }
            else
            {
                $message_error = "Looks like something went wrong or you didn't make any change.";
                echo '<script type="text/javascript">scroll_down();</script>';
            }
        }

    }

?>
<?php
$get_params = allowed_get_params(['x', 'id']);
$user_code_encrypted = $get_params['id'];
$user_code = decrypt(str_replace(" ", "+", $user_code_encrypted));
$user_code = preg_replace("/[^A-Za-z0-9 ]/", '', $user_code);

// Process comment
if ($_SERVER['REQUEST_METHOD'] == 'POST' && $_POST['process'] == true)
{
    foreach($_POST as $key => $value)
    {
        $_POST[$key] = $db_handle->sanitizePost(trim($value));
    }
    extract($_POST);

    // Process submitted form
    if (isset($_POST['process']))
    {
        foreach($_POST as $key => $value)
        {
            $_POST[$key] = $db_handle->sanitizePost(trim($value));
        }
        extract($_POST);
        if(empty($phone) || empty($full_name) || empty($state_of_residence) || !isset($ticket_type))
        {
            $message_error = "All fields are compulsory, please try again.";
        }
        elseif (!check_email($email))
        {
            $message_error = "You have provided an invalid email address. Please try again.";
        }
        elseif ($admin_object->dinner_guest_2017_is_duplicate($email))
        {
            $message_error = "Duplicate";
        }
        else
        {
            $new_reg = $admin_object->add_new_dinner_guest_2017($confirmation_status, $full_name, $email, $phone, $ticket_type, $state_of_residence, $comments);
            if($new_reg)
            {
                $subject = "AFRO NITE 2017";
                $message = <<<MAIL
    <div style="background-color: #F3F1F2">
    <div style="max-width: 80%; margin: 0 auto; padding: 10px; font-size: 14px; font-family: Verdana;">
        <img src="https://instafxng.com/images/ifxlogo.png" />
        <hr />
        <div style="background-color: #FFFFFF; padding: 15px; margin: 5px 0 5px 0;">
            <p>Dear $full_name,</p>

            <p>It’s been almost 365 days and a lot has happened during the year,
            but get ready for one more!</p>

            <p>I want to take this opportunity to appreciate you for showing
            consistent confidence in the services we render and allowing us to
            service you this year. You are truly one of the most loyal clients
            of our company and serving you remains our pleasure.</p>

            <p>To roundup the year together, I humbly request that you join us
            for an exciting and entertaining time at our annual Christmas Dinner
            Event specially designed so you could relax and have fun with the
            members of our team and other Forex traders like yourself.</p>

            <p>This year’s dinner is themed the <strong>Classic 80s Night</strong>. When you step
            inside the venue; the music, the theme and overall ambience will
            transport you to a simpler and more innocent time where you can have the
            feel of what it used to be in the 80s.</p>

            <p>If you love to have fun, you should be at this event as the outfit,
            the looks the music and the venue will represent what it used to be
            from way back 80s, and there would be lots of exciting activities lined
            up alongside the 3 course dinner to be served.</p>

            <p style="text-align: center"><strong>One last thing ...</strong></p>

            <p>We have one last surprise for you before the year ends, you will
            witness the launch of a new service created to make you more money
            daily even as you trade.</p>

            <!---<p style="text-align: center">You can
            <a href="https://instafxng.com/dinner.php?x=$id_encrypt">click here</a>
            to reserve your seat now.</p>-->

            <p><strong>NOTE: Admission is strictly by Invitation.</strong></p>

            <p>It would be an honor to have you at the event as I earnestly look
            forward to welcoming you and meeting you in person.</p>

            <br /><br />
            <p>Best Regards,</p>
            <p>Fujah Abideen,<br />
            Corporate Communications Manager<br />
                www.instafxng.com</p>
            <br /><br />
        </div>
        <hr />
        <div style="background-color: #EBDEE9;">
            <div style="font-size: 11px !important; padding: 15px;">
                <p style="text-align: center"><span style="font-size: 12px"><strong>We"re Social</strong></span><br /><br />
                    <a href="https://facebook.com/InstaForexNigeria"><img src="https://instafxng.com/images/Facebook.png"></a>
                    <a href="https://twitter.com/instafxng"><img src="https://instafxng.com/images/Twitter.png"></a>
                    <a href="https://www.instagram.com/instafxng/"><img src="https://instafxng.com/images/instagram.png"></a>
                    <a href="https://www.youtube.com/channel/UC0Z9AISy_aMMa3OJjgX6SXw"><img src="https://instafxng.com/images/Youtube.png"></a>
                    <a href="https://linkedin.com/company/instaforex-ng"><img src="https://instafxng.com/images/LinkedIn.png"></a>
                </p>
                <p><strong>Head Office Address:</strong> TBS Place, Block 1A, Plot 8, Diamond Estate, Estate Bus-Stop, LASU/Isheri road, Isheri Olofin, Lagos.</p>
                <p><strong>Lekki Office Address:</strong> Road 5, Suite K137, Ikota Shopping Complex, Lekki/Ajah Express Road, Lagos State</p>
                <p><strong>Office Number:</strong> 08028281192</p>
                <br />
            </div>
            <div style="font-size: 10px !important; padding: 15px; text-align: center;">
                <p>This email was sent to you by Instant Web-Net Technologies Limited, the
                    official Nigerian Representative of Instaforex, operator and administrator
                    of the website www.instafxng.com</p>
                <p>To ensure you continue to receive special offers and updates from us,
                    please add support@instafxng.com to your address book.</p>
            </div>
        </div>
    </div>
</div>
MAIL;
                $system_object->send_email($subject, $message, $email, $full_name);
                $message_success = "You have successfully created a new dinner reservation.";
            }
            else
            {
                $message_error = "Looks like something went wrong or you didn't make any change.";
            }
        }

    }

}


$attendee_detail = $db_handle->fetchAssoc($db_handle->runQuery("SELECT CONCAT(first_name, SPACE(1), last_name) AS full_name, email FROM user WHERE user_code = '$user_code'"));
$attendee_detail = $attendee_detail[0];
//var_dump($attendee_detail);
if(empty($attendee_detail))
{
    //redirect_to("./");
    //exit;
} else
{
}

?>

<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>Instaforex NG | Afro Nite 2017</title>
        <link rel="shortcut icon" type="image/icon" href="../images/favicon.png"/>
        <link href="https://maxcdn.bootstrapcdn.com/font-awesome/4.6.3/css/font-awesome.min.css" rel="stylesheet">
        <link href="assets/css/bootstrap.min.css" rel="stylesheet">
        <link href="assets/css/slick.css" rel="stylesheet">
        <link id="switcher" href="assets/css/theme-color/default-theme.css" rel="stylesheet">
        <link href="assets/style.css" rel="stylesheet">
	    <link href="//fonts.googleapis.com/css?family=Open+Sans:300,400,400i,600,700,800" rel="stylesheet">
	    <link href="//fonts.googleapis.com/css?family=Montserrat" rel="stylesheet">
        <script src="//oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
        <script src="//oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
        <script src="../js/bootstrap-datetimepicker.js"></script>
        <script src="../js/jquery_2.1.1.min.js"></script>
        <link href="//cdn.rawgit.com/Eonasdan/bootstrap-datetimepicker/e8bddc60e73c1ec2475f827be36e1957af72e2ea/build/css/bootstrap-datetimepicker.css" rel="stylesheet">
        <script>
            function scroll_down()
            {
                $('html, body').animate({
                    scrollTop: $("#mu-register").offset().top
                }, 2000);
            }
        </script>
    </head>
    <body>
  	    <!-- Start Header -->
        <header id="mu-hero" class="" role="banner">
            <!-- Start menu  -->
            <nav class="navbar navbar-fixed-top navbar-default mu-navbar">
                <div class="container">
                    <!-- Brand and toggle get grouped for better mobile display -->
                    <div class="navbar-header">
                      <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1" aria-expanded="false">
                        <span class="sr-only">Toggle navigation</span>
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span>
                      </button>

                      <!-- Logo -->
                      <a class="navbar-brand" href="index.php"><img height="50%" width="50%" class="img-responsive" src="../images/ifxlogo-xmas.png"></a>

                    </div>

                    <!-- Collect the nav links, forms, and other content for toggling -->
                    <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
                        <ul class="nav navbar-nav mu-menu navbar-right">
                            <li><a href="#mu-hero">Home</a></li>
                            <li><a href="#mu-about">About The Event</a></li>
                            <li><a href="#mu-register">Reservation</a></li>
                        </ul>
                    </div><!-- /.navbar-collapse -->
                </div><!-- /.container-fluid -->
            </nav>
            <!-- End menu -->

            <div class="mu-hero-overlay">
                <div class="container">
                    <div class="mu-hero-area">

                        <!-- Start hero featured area -->
                        <div class="mu-hero-featured-area">
                            <!-- Start center Logo -->
                            <div class="mu-logo-area">
                                <!-- text based logo -->
                                <!--<a class="mu-logo" href="#"><center><img height="50%" width="50%" class="img-responsive" src="../images/ifxlogo-xmas.png"></center></a>-->
                                <!-- image based logo -->
                                <a class="mu-logo" href="#"><img src="../images/ifxlogo-xmas.png" alt="logo img"></a>
                            </div>
                            <!-- End center Logo -->

                            <div class="mu-hero-featured-content">

                                <h1>HELLO! WELCOME TO AFRO NITE 2017</h1>
                                <h2>The Biggest Rewards Ceremony In Africa</h2>
                                <p class="mu-event-date-line">17 December, 2017. Lagos State, Nigeria</p>

                                <div class="mu-event-counter-area">
                                    <div id="mu-event-counter">

                                    </div>
                                </div>

                            </div>
                        </div>
                        <!-- End hero featured area -->

                    </div>
                </div>
            </div>
        </header>
	    <!-- End Header -->
	
	    <!-- Start main content -->
        <main role="main">
            <!-- Start About -->
            <section id="mu-about">
                <div class="container">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="mu-about-area">
                                <!-- Start Feature Content -->
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="mu-about-left">
                                            <img class="" src="assets/images/about.jpg" alt="Men Speaker">
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="mu-about-right">
                                            <h2>About The Event</h2>
                                            <p>Explanation!Explanation!Explanation!Explanation!Explanation!Explanation!Explanation!Explanation!Explanation!Explanation!Explanation!</p>
                                            <p>Explanation!Explanation!Explanation!Explanation!Explanation!Explanation!Explanation!Explanation!Explanation!Explanation!Explanation!</p>
                                            <p>Explanation!Explanation!Explanation!Explanation!Explanation!Explanation!Explanation!Explanation!Explanation!Explanation!Explanation!</p>
                                        </div>
                                    </div>
                                </div>
                                <!-- End Feature Content -->

                            </div>
                        </div>
                    </div>
                </div>
            </section>
            <!-- End About -->

            <!-- Start Video -->
            <section id="mu-video">
                <div class="mu-video-overlay">
                    <div class="container">
                        <div class="row">
                            <div class="col-md-12">
                                <div class="mu-video-area">
                                    <h2>Watch Previous Event Video</h2>
                                    <a class="mu-video-play-btn" href="#">
                                        <i class="glyphicon glyphicon-play" aria-hidden="true"></i>
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Start Video content -->
                <div class="mu-video-content">
                    <div class="mu-video-iframe-area">
                        <a class="mu-video-close-btn" href="#"><i class="fa fa-times" aria-hidden="true"></i></a>
                        <iframe width="854" height="480" src="https://www.youtube.com/embed/n9AVEl9764s" frameborder="0" allowfullscreen></iframe>
                    </div>
                </div>
                <!-- End Video content -->

            </section>
            <!-- End Video -->

            <!-- Start Venue -->
            <section id="mu-venue">
                <div class="mu-venue-area">
                    <div class="row">

                        <div class="col-md-6">
                            <div class="mu-venue-map">
                                <iframe src="//www.google.com/maps/embed/v1/place?key=AIzaSyBDmmOQa-UXFfKtrcyowlYvq4FpbUfAjxw&q=Four+Points+by+Sheraton,Lagos" width="100%" height="450" frameborder="0" style="border:0" allowfullscreen></iframe>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="mu-venue-address">
                                <h2>VENUE <i class="fa fa-chevron-right" aria-hidden="true"></i></h2>
                                <h3>Four Points by Sheraton Lagos</h3>
                                <h4>Plot 9/10, Block 2, Oniru Chieftaincy Estate,</h4>
                                <h4>Lagos State, Nigeria</h4>
                            </div>
                        </div>

                    </div>
                </div>
            </section>
            <!-- End Venue -->

            <!-- Start Register  -->
            <section id="mu-register">
                <div class="container">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="mu-register-area">

                                <div class="mu-title-area">
                                    <h2 class="mu-title">Reservation Form</h2>
                                    <p>Please fill the form below to reserve a spot at the Instaforex Afro Nite.</p>
                                    <?php require_once '../admin/layouts/feedback_message.php'; ?>
                                </div>
                                <div class="mu-register-content">
                                    <form class="mu-register-form">
                                        <div class="row">
                                            <input name="full_name" type="hidden" id="name" value="<?php echo $attendee_detail['full_name']; ?>" class="form-control"/>
                                            <input name="email" type="hidden" id="email" value="<?php echo $attendee_detail['email']; ?>" class="form-control"/>
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <input type="text" class="form-control" placeholder="Full Name" id="full_name" name="full_name" value="<?php echo $attendee_detail['full_name']; ?>" disabled />
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <input type="email" class="form-control" placeholder="Email" id="email" name="email" value="<?php echo $attendee_detail['email']; ?>" disabled />
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <input type="text" class="form-control" placeholder="Phone Number" id="phone" name="phone" required="">
                                                </div>
                                            </div>
                                            <input id="ticket_type" name="ticket_type" type="hidden" value="0">
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <select class="form-control" name="state_of_residence" id="state_of_residence">
                                                        <option value="">Select Your State Of Residence</option>
                                                        <option value="Abia State">Abia State</option>
                                                        <option value="Adamawa State">Adamawa State</option>
                                                        <option value="Akwa Ibom State">Akwa Ibom State</option>
                                                        <option value="Anambra State">Anambra State</option>
                                                        <option value="Bauchi State">Bauchi State</option>
                                                        <option value="Bayelsa State">Bayelsa State</option>
                                                        <option value="Benue State">Benue State</option>
                                                        <option value="Borno State">Borno State</option>
                                                        <option value="Cross River State">Cross River State</option>
                                                        <option value="Delta State">Delta State</option>
                                                        <option value="Ebonyi State">Ebonyi State</option>
                                                        <option value="Edo State">Edo State</option>
                                                        <option value="Ekiti State">Ekiti State</option>
                                                        <option value="Enugu State">Enugu State</option>
                                                        <option value="FCT Abuja">FCT Abuja</option>
                                                        <option value="Gombe State">Gombe State</option>
                                                        <option value="Imo State">Imo State</option>
                                                        <option value="Jigawa State">Jigawa State</option>
                                                        <option value="Kaduna State">Kaduna State</option>
                                                        <option value="Kano State">Kano State</option>
                                                        <option value="Katsina State">Katsina State</option>
                                                        <option value="Kebbi State">Kebbi State</option>
                                                        <option value="Kogi State">Kogi State</option>
                                                        <option value="Kwara State">Kwara State</option>
                                                        <option value="Lagos State">Lagos State</option>
                                                        <option value="Nasarawa State">Nasarawa State</option>
                                                        <option value="Niger State">Niger State</option>
                                                        <option value="Ogun State">Ogun State</option>
                                                        <option value="Ondo State">Ondo State</option>
                                                        <option value="Osun State">Osun State</option>
                                                        <option value="Oyo State">Oyo State</option>
                                                        <option value="Plateau State">Plateau State</option>
                                                        <option value="Rivers State">Rivers State</option>
                                                        <option value="Sokoto State">Sokoto State</option>
                                                        <option value="Taraba State">Taraba State</option>
                                                        <option value="Yobe State">Yobe State</option>
                                                        <option value="Zamfara State">Zamfara State </option>
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <input type="text" class="form-control" placeholder="Facebook Username" id="facebook_id" name="facebook_id" value="">
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <input type="text" class="form-control" placeholder="Date Of Birth" id="d_o_b" name="d_o_b" required="">
                                                    <script type="text/javascript">
                                                        $(function ()
                                                        {
                                                            $('#d_o_b').datetimepicker(
                                                                {
                                                                    format: 'DD-MM-YYYY'
                                                                });
                                                        });
                                                    </script>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="row">
                                            <div class="col-md-12">
                                                <div class="form-group">
                                                    <textarea rows="3" class="form-control" placeholder="Comments (If Any)" id="comments" name="comments" ></textarea>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-md-12">
                                                <div class="text-center form-group">

                                                    <p>
                                                        <input type="radio" name="confirmation_status" value="1" required/>
                                                        Maybe I would be in attendance.
                                                    </p>


                                                    <p>
                                                        <input type="radio" name="confirmation_status" value="2" required/>
                                                        Yes, I would be in attendance.
                                                    </p>

                                                    <p>
                                                        <input type="radio" name="confirmation_status" value="3" required/>
                                                        No, I would not be in attendance.
                                                    </p

                                                </div>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-md-12">
                                                <div class="form-group">
                                                    <button type="submit" class="mu-reg-submit-btn">SUBMIT</button>
                                                </div>
                                            </div>
                                        </div>
                                    </form>
                                </div>

                            </div>
                        </div>
                    </div>
                </div>
            </section>
            <!-- End Register -->
        </main>
	    <!-- End main content -->
			
			
	    <!-- Start footer -->
	    <footer id="mu-footer" role="contentinfo">
			<div class="container">
				<div class="mu-footer-area">
					<div class="mu-footer-top">
						<div class="mu-social-media">
							<a href="#"><i class="fa fa-facebook"></i></a>
							<a href="#"><i class="fa fa-twitter"></i></a>
                            <a href="#"><i class="fa fa-instagram"></i></a>
							<a href="//www.youtube.com/channel/UC0Z9AISy_aMMa3OJjgX6SXw"><i class="fa fa-youtube"></i></a>
						</div>
					</div>
					<div class="mu-footer-bottom">
						<p class="mu-copy-right">&copy; Copyright <a rel="nofollow" href="http://markups.io">Instant Web Net Technologies</a>. All rights reserved.</p>
					</div>
				</div>
			</div>
	    </footer>
	    <!-- End footer -->

        <!-- jQuery library -->
        <script src="//ajax.googleapis.com/ajax/libs/jquery/1.11.3/jquery.min.js"></script>
        <!-- Include all compiled plugins (below), or include individual files as needed -->
        <!-- Bootstrap -->
        <script src="assets/js/bootstrap.min.js"></script>
	    <!-- Slick slider -->
        <script type="text/javascript" src="assets/js/slick.min.js"></script>
        <!-- Event Counter -->
        <script type="text/javascript" src="assets/js/jquery.countdown.min.js"></script>
        <!-- Ajax contact form  -->
        <script type="text/javascript" src="assets/js/app.js"></script>
        <!-- Custom js -->
	    <script type="text/javascript" src="assets/js/custom.js"></script>
        <script src="//cdnjs.cloudflare.com/ajax/libs/moment.js/2.9.0/moment-with-locales.js"></script>
        <script src="//cdn.rawgit.com/Eonasdan/bootstrap-datetimepicker/e8bddc60e73c1ec2475f827be36e1957af72e2ea/src/js/bootstrap-datetimepicker.js"></script>
    </body>
</html>