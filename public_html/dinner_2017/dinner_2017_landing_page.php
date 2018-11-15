<?php
    require_once("../init/initialize_general.php");
    require_once("../init/initialize_admin.php");
    redirect_to("signin.php");
    exit;
?>
<?php
$get_params = allowed_get_params(['x', 'id']);
$user_code_encrypted = $get_params['id'];
$user_code = decrypt(str_replace(" ", "+", $user_code_encrypted));
$user_code = preg_replace("/[^A-Za-z0-9 ]/", '', $user_code);


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

        if(empty($email) ||  empty($phone) || empty($full_name) || empty($state_of_residence) || !isset($ticket_type))
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
            $query = "UPDATE user_meta SET d_o_b = '$d_o_b', fb_name = '$fb_name' WHERE user_code = '$user_code' ";
            $db_handle->runQuery($query);
            $new_reg = $admin_object->add_new_dinner_guest_2017($confirmation_status, $full_name, $email, $phone, $ticket_type, $state_of_residence, $comments);
            if($new_reg)
            {
                $subject = "InstaFxNg Dinner 2017: THE ETHNIC IMPRESSION";
                switch($confirmation_status)
                {
                    case '1':
                        //Maybe Clients
                        $message = <<<MAIL
    <div style="background-color: #F3F1F2">
    <div style="max-width: 80%; margin: 0 auto; padding: 10px; font-size: 14px; font-family: Verdana;">
        <img src="https://instafxng.com/images/ifxlogo.png" />
        <hr />
        <div style="background-color: #FFFFFF; padding: 15px; margin: 5px 0 5px 0;">
            <p>Dear $full_name,</p>
            <p>Thank you for your response.</p>
            <p>I would really love you to make up your mind on time as the dinner promises 
            to be fun and we have a lot of clients on the waiting list.</p>
            <p>It wouldn’t be fair to them if you say maybe and you never get to come.</p>
            <p>To this end, we have reserved your seat for the next 5 days as we expect 
            that you would have checked your schedule for December 17th and decide if you 
            would be able to make it to the dinner by then.</p>
            <p>I really do hope you would be able to attend as I don’t want you to miss the fun that awaits you.</p>
            <p><a target="_blank" href="https://www.instafxng.com/dinner_2017/">Click here to update your status now.</a></p>
            <br /><br />
            <p>Best Regards,</p>
            <p>Mercy,</p>
            <p>Marketing Executive,</p>
            <p>www.instafxng.com</p>
            <br /><br />
        </div>
        <hr />
        <div style="background-color: #EBDEE9;">
            <div style="font-size: 11px !important; padding: 15px;">
                <p style="text-align: center"><span style="font-size: 12px"><strong>We"re Social</strong></span><br /><br />
                    <a href="https://facebook.com/InstaFxNg"><img src="https://instafxng.com/images/Facebook.png"></a>
                    <a href="https://twitter.com/instafxng"><img src="https://instafxng.com/images/Twitter.png"></a>
                    <a href="https://www.instagram.com/instafxng/"><img src="https://instafxng.com/images/instagram.png"></a>
                    <a href="https://www.youtube.com/channel/UC0Z9AISy_aMMa3OJjgX6SXw"><img src="https://instafxng.com/images/Youtube.png"></a>
                    <a href="https://linkedin.com/company/instaforex-ng"><img src="https://instafxng.com/images/LinkedIn.png"></a>
                </p>
                <p><strong>Head Office Address:</strong> TBS Place, Block 1A, Plot 8, Diamond Estate, Estate Bus-Stop, LASU/Isheri road, Isheri Olofin, Lagos.</p>
                <p><strong>Lekki Office Address:</strong> Block A3, Suite 508/509 Eastline Shopping Complex, Opposite Abraham Adesanya Roundabout, along Lekki - Epe expressway, Lagos. </p>
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
                        //Maybe Clients
                        break;

                    case '2':
                        //Yes Clients
                        $message = <<<MAIL
    <div style="background-color: #F3F1F2">
    <div style="max-width: 80%; margin: 0 auto; padding: 10px; font-size: 14px; font-family: Verdana;">
        <img src="https://instafxng.com/images/ifxlogo.png" />
        <hr />
        <div style="background-color: #FFFFFF; padding: 15px; margin: 5px 0 5px 0;">
            <p>Dear $full_name,</p>
            <p>Yay! I’m so excited you are coming to the InstaFxNg 2017 Dinner themed The Ethnic Impression.</p>
            <p>Your VIP seat has been reserved at the dinner.</p> 
            <p>I assure you nothing short of a great time of fun, great food and entertainment.</p>
            <p>It promises to be awesome and I can’t wait to receive you at the dinner.</p>
            <p>I look forward to giving you a royal welcome on the 17th of December.</p>
            <p>From me to you…. It’s see you soon!</p>
            <br /><br />
            <p>Best Regards,</p>
            <p>Mercy,</p>
            <p>Marketing Executive,</p>
            <p>www.instafxng.com</p>
            <br /><br />
        </div>
        <hr />
        <div style="background-color: #EBDEE9;">
            <div style="font-size: 11px !important; padding: 15px;">
                <p style="text-align: center"><span style="font-size: 12px"><strong>We"re Social</strong></span><br /><br />
                    <a href="https://facebook.com/InstaFxNg"><img src="https://instafxng.com/images/Facebook.png"></a>
                    <a href="https://twitter.com/instafxng"><img src="https://instafxng.com/images/Twitter.png"></a>
                    <a href="https://www.instagram.com/instafxng/"><img src="https://instafxng.com/images/instagram.png"></a>
                    <a href="https://www.youtube.com/channel/UC0Z9AISy_aMMa3OJjgX6SXw"><img src="https://instafxng.com/images/Youtube.png"></a>
                    <a href="https://linkedin.com/company/instaforex-ng"><img src="https://instafxng.com/images/LinkedIn.png"></a>
                </p>
                <p><strong>Head Office Address:</strong> TBS Place, Block 1A, Plot 8, Diamond Estate, Estate Bus-Stop, LASU/Isheri road, Isheri Olofin, Lagos.</p>
                <p><strong>Lekki Office Address:</strong> Block A3, Suite 508/509 Eastline Shopping Complex, Opposite Abraham Adesanya Roundabout, along Lekki - Epe expressway, Lagos. </p>
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
                        //Yes Clients
                        break;

                    case '3':
                        //No Clients
                        $message = <<<MAIL
    <div style="background-color: #F3F1F2">
    <div style="max-width: 80%; margin: 0 auto; padding: 10px; font-size: 14px; font-family: Verdana;">
        <img src="https://instafxng.com/images/ifxlogo.png" />
        <hr />
        <div style="background-color: #FFFFFF; padding: 15px; margin: 5px 0 5px 0;">
            <p>Dear $full_name,</p>
            <p>Aww, I’m sad you wouldn’t be able to attend this year’s dinner.</p> 
            <p>We’ll really miss you this time and I look forward to welcoming you another time.</p>
            <p>Not to worry, I’ll document the happenings at the dinner for your viewing pleasure and let you know how it went.</p>
            <p>Have a merry Christmas and Prosperous New Year!</p>
            <p>Keep making money with InstaFxNg!</p>
            <br /><br />
            <p>Best Regards,</p>
            <p>Mercy,</p>
            <p>Marketing Executive,</p>
            <p>www.instafxng.com</p>
            <br /><br />
        </div>
        <hr />
        <div style="background-color: #EBDEE9;">
            <div style="font-size: 11px !important; padding: 15px;">
                <p style="text-align: center"><span style="font-size: 12px"><strong>We"re Social</strong></span><br /><br />
                    <a href="https://facebook.com/InstaFxNg"><img src="https://instafxng.com/images/Facebook.png"></a>
                    <a href="https://twitter.com/instafxng"><img src="https://instafxng.com/images/Twitter.png"></a>
                    <a href="https://www.instagram.com/instafxng/"><img src="https://instafxng.com/images/instagram.png"></a>
                    <a href="https://www.youtube.com/channel/UC0Z9AISy_aMMa3OJjgX6SXw"><img src="https://instafxng.com/images/Youtube.png"></a>
                    <a href="https://linkedin.com/company/instaforex-ng"><img src="https://instafxng.com/images/LinkedIn.png"></a>
                </p>
                <p><strong>Head Office Address:</strong> TBS Place, Block 1A, Plot 8, Diamond Estate, Estate Bus-Stop, LASU/Isheri road, Isheri Olofin, Lagos.</p>
                <p><strong>Lekki Office Address:</strong> Block A3, Suite 508/509 Eastline Shopping Complex, Opposite Abraham Adesanya Roundabout, along Lekki - Epe expressway, Lagos. </p>
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
                        //No Clients
                        break;
                    default:
                        //Yes Clients
                        $message = <<<MAIL
    <div style="background-color: #F3F1F2">
    <div style="max-width: 80%; margin: 0 auto; padding: 10px; font-size: 14px; font-family: Verdana;">
        <img src="https://instafxng.com/images/ifxlogo.png" />
        <hr />
        <div style="background-color: #FFFFFF; padding: 15px; margin: 5px 0 5px 0;">
            <p>Dear $full_name,</p>
            <p>Yay! I’m so excited you are coming to the InstaFxNg 2017 Dinner themed The Ethnic Impression.</p>
            <p>Your VIP seat has been reserved at the dinner.</p> 
            <p>I assure you nothing short of a great time of fun, great food and entertainment.</p>
            <p>It promises to be awesome and I can’t wait to receive you at the dinner.</p>
            <p>I look forward to giving you a royal welcome on the 17th of December.</p>
            <p>From me to you…. It’s see you soon!</p>
            <br /><br />
            <p>Best Regards,</p>
            <p>Mercy,</p>
            <p>Marketing Executive,</p>
            <p>www.instafxng.com</p>
            <br /><br />
        </div>
        <hr />
        <div style="background-color: #EBDEE9;">
            <div style="font-size: 11px !important; padding: 15px;">
                <p style="text-align: center"><span style="font-size: 12px"><strong>We"re Social</strong></span><br /><br />
                    <a href="https://facebook.com/InstaFxNg"><img src="https://instafxng.com/images/Facebook.png"></a>
                    <a href="https://twitter.com/instafxng"><img src="https://instafxng.com/images/Twitter.png"></a>
                    <a href="https://www.instagram.com/instafxng/"><img src="https://instafxng.com/images/instagram.png"></a>
                    <a href="https://www.youtube.com/channel/UC0Z9AISy_aMMa3OJjgX6SXw"><img src="https://instafxng.com/images/Youtube.png"></a>
                    <a href="https://linkedin.com/company/instaforex-ng"><img src="https://instafxng.com/images/LinkedIn.png"></a>
                </p>
                <p><strong>Head Office Address:</strong> TBS Place, Block 1A, Plot 8, Diamond Estate, Estate Bus-Stop, LASU/Isheri road, Isheri Olofin, Lagos.</p>
                <p><strong>Lekki Office Address:</strong> Block A3, Suite 508/509 Eastline Shopping Complex, Opposite Abraham Adesanya Roundabout, along Lekki - Epe expressway, Lagos. </p>
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
                        //Yes Clients
                        break;
                }
                $system_object->send_email($subject, $message, $email, $full_name);
                $message_success = "You have successfully created a new dinner reservation.";
            }
            if(!$new_reg)
            {
                $message_error = "Looks like something went wrong or you didn't make any change.";
            }
        }

    }

}


$attendee_detail = $db_handle->fetchAssoc($db_handle->runQuery("SELECT CONCAT(first_name, SPACE(1), last_name) AS full_name, email FROM user WHERE user_code = '$user_code'"));
$attendee_detail = $attendee_detail[0];

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
        <title>Instaforex NG Dinner 2017</title>
        <link rel="shortcut icon" type="image/icon" href="../images/favicon.png"/>
        <link href="https://maxcdn.bootstrapcdn.com/font-awesome/4.6.3/css/font-awesome.min.css" rel="stylesheet">
        <link href="assets/css/bootstrap.min.css" rel="stylesheet">
        <link href="assets/css/slick.css" rel="stylesheet">
        <link id="switcher" href="assets/css/theme-color/default-theme.css" rel="stylesheet">
        <link href="assets/style.css" rel="stylesheet">
	    <link href="//fonts.googleapis.com/css?family=Open+Sans:300,400,400i,600,700,800" rel="stylesheet">
	    <link href="//fonts.googleapis.com/css?family=Montserrat" rel="stylesheet">
        <link href=' http://fonts.googleapis.com/css?family=Amazone+BT' rel='stylesheet' type='text/css'>
        <script src="//oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
        <script src="//oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
        <script src="../js/bootstrap-datetimepicker.js"></script>
        <script src="../js/jquery_2.1.1.min.js"></script>
        <link href="//cdn.rawgit.com/Eonasdan/bootstrap-datetimepicker/e8bddc60e73c1ec2475f827be36e1957af72e2ea/build/css/bootstrap-datetimepicker.css" rel="stylesheet">
        <link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
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
                            <li><a href="#mu-hero">Reservation</a></li>
                            <li><a href="#mu-about">About The Event</a></li>
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
                            <div class="col-sm-12">
                                <div class="row">
                                    <div class="col-sm-7">
                                        <!-- Start center Logo -->
                                        <div class="mu-logo-area">
                                            <!-- text based logo -->
                                            <!--<a class="mu-logo" href="#"><center><img height="50%" width="50%" class="img-responsive" src="../images/ifxlogo-xmas.png"></center></a>-->
                                            <!-- image based logo -->
                                            <a class="mu-logo" href="#"><img src="../images/ifxlogo-xmas.png" alt="logo img"></a>
                                        </div>
                                        <!-- End center Logo -->

                                        <div class="mu-hero-featured-content">
                                            <h2>Presents</h2>
                                            <h1 style="font-family: 'Times New Roman'">THE ETHNIC IMPRESSION 2017</h1>
                                            <!--<h1>WELCOME TO INSTAFXNG DINNER 2017</h1>
                                            <h2>The Ethnic Impression</h2>-->
                                            <p class="mu-event-date-line">17 December, 2017. Lagos State, Nigeria</p>

                                            <div class="mu-event-counter-area">
                                                <div id="mu-event-counter">
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-sm-5">
                                        <div style="display: -webkit-flex;  -webkit-flex-wrap: wrap;  display: flex;  flex-wrap: wrap;" class="modal-content">
                                            <div class="panel-heading">
                                                <div class="row">
                                                    <div class="col-sm-12 text-center text-danger">
                                                        <h5><strong>2017 DINNER RESERVATION</strong></h5>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="panel-body">
                                                <form data-toggle="validator" class="form-horizontal" role="form" method="post" action="">
                                                    <fieldset>
                                                        <?php require_once '../layouts/feedback_message.php'; ?>
                                                        <p>Please fill the form below to reserve a spot at the Dinner.</p>
                                                        <div class="form-group">
                                                            <div class="col-sm-12">
                                                                <div class="input-group">
                                                                    <span class="input-group-addon"><i class="fa fa-user fa-fw"></i></span>
                                                                    <input value="<?php echo $attendee_detail['full_name']; ?>" placeholder="Full Name" name="full_name" type="text" id="full_name" class="form-control" required disabled/>
                                                                    <input value="<?php echo $attendee_detail['full_name']; ?>"  name="full_name" type="hidden" id="full_name" class="form-control" />
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="form-group">
                                                            <div class="col-sm-12">
                                                                <div class="input-group">
                                                                    <span class="input-group-addon"><i class="fa fa-envelope fa-fw"></i></span>
                                                                    <input placeholder="Email Address" name="email" type="text" id="email" value="<?php echo $attendee_detail['email']; ?>" class="form-control" required disabled/>
                                                                    <input placeholder="Email Address" name="email" type="hidden" id="email" value="<?php echo $attendee_detail['email']; ?>" class="form-control"/>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="form-group">
                                                            <div class="col-sm-12">
                                                                <div class="input-group">
                                                                    <span class="input-group-addon"><i class="fa fa-phone fa-fw"></i></span>
                                                                    <input placeholder="Phone Number" name="phone" type="text" id="phone_number" value="" class="form-control" required/>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <input id="ticket_type" name="ticket_type" type="hidden" value="0">
                                                        <div class="form-group">
                                                            <div class="col-sm-12">
                                                                <div class="input-group">
                                                                    <span class="input-group-addon"><i class="fa fa-location-arrow fa-fw"></i></span>
                                                                    <select id="state_of_residence" name="state_of_residence" class="form-control" required>
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
                                                        </div>
                                                        <div class="form-group">
                                                            <div class="col-sm-12">
                                                                <div class="input-group">
                                                                    <span class="input-group-addon"><i class="fa fa-facebook fa-fw"></i></span>
                                                                    <input placeholder="Facebook Name" name="fb_name" type="text" id="fb_name" value="" class="form-control" />
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="form-group">
                                                            <div class="col-sm-12">
                                                                <div class="input-group">
                                                                    <span class="input-group-addon"><i class="fa fa-calendar fa-fw"></i></span>
                                                                    <input  type="text" class="form-control" placeholder="Date Of Birth" id="d_o_b" name="d_o_b" />
                                                                    <script type="text/javascript">
                                                                        $(function() {
                                                                            $( "#d_o_b" ).datepicker({
                                                                                dateFormat : 'dd-mm-yy',
                                                                                changeMonth : true,
                                                                                changeYear : true,
                                                                                yearRange: '-100y:c+nn',
                                                                                maxDate: '-1d'
                                                                            });
                                                                        });
                                                                    </script>

                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="form-group">
                                                            <div class="col-sm-12">
                                                                <div class="input-group">
                                                                    <span class="input-group-addon"><i class="fa fa-envelope fa-fw"></i></span>
                                                                    <textarea placeholder="Comments (If Any)" id="comments" rows="3" name="comments" class="form-control"></textarea>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="form-group">
                                                            <div class="col-sm-12">
                                                                <div class="text-justify input-group">
                                                                    <input id="confirmation_status" type="radio" name="confirmation_status" value="1" required/> <label for="confirmation_status">Maybe I would be in attendance.</label>
                                                                    <br/>
                                                                    <input id="confirmation_status" type="radio" name="confirmation_status" value="2" required/> <label for="confirmation_status">Yes, I would be in attendance.</label>
                                                                    <br/>
                                                                    <input id="confirmation_status" type="radio" name="confirmation_status" value="3" required/> <label for="confirmation_status">No, I would not be in attendance.</label>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="text-center form-group">
                                                            <div class=" col-sm-12">
                                                                <input name="process" type="submit" class="btn btn-success" value="Reserve My Spot Now!">
                                                            </div>
                                                        </div>
                                                    </fieldset>
                                                </form>
                                            </div>
                                        </div>
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
                                            <img class="" src="assets/images/dinner_2017.jpg" alt="Men Speaker">
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="mu-about-right" style='font-family: "Arial Black" font-weight: bold'>
                                            <h2>About The Event</h2>
                                            <p class="text-justify" >This year’s dinner promises to be filled with great fun as we look deep into the riches of our culture and heritage.</p>
                                            <p class="text-justify">The expression of our cultural beauty, the ambience of our arts, the melody of our music, the rhythm of our folk story,
                                                the beauty of our clothing and the uniqueness of our languages makes up our native identities.</p>
                                            <p class="text-justify">In this Edition, we would be celebrating the Native heritage of the indigenous groups in Nigeria by creating an aura that embraces our roots as a people.</p>
                                            <p class="text-justify">The theme,the dressing, the food and the activities lined up for the dinner are harmonized to celebrate you, the uniqueness of your origin, the source that birthed you and the ideology that groomed you.</p>
                                            <p class="text-justify">Come dressed in your tribal attire, have a taste of your food and the groove of your music.</p>
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
            <section id="mu-video" >
                <div class="mu-video-overlay">
                    <div class="container">
                        <div class="row">
                            <div   class="col-md-12">
                                <div   class="mu-video-area" >
                                    <h2>Watch Previous Event Video</h2>
                                    <!--<a class="mu-video-play-btn" href="#">
                                        <i class="glyphicon glyphicon-play-circle" aria-hidden="true"></i>
                                    </a>-->
                                    <div style="opacity: 1!important;" class="embed-responsive embed-responsive-16by9">
                                        <iframe class="embed-responsive-item" src="//www.youtube.com/embed/tdHp4WGw7YE" frameborder="0" allowfullscreen></iframe>
                                    </div>
                                </div>

                            </div>
                        </div>
                    </div>



                <!--<div class="mu-video-content">
                    <div class="mu-video-iframe-area">
                        <a class="mu-video-close-btn" href="#"><i class="fa fa-times" aria-hidden="true"></i></a>
                        <iframe width="854" height="480" src="//www.youtube.com/embed/tdHp4WGw7YE" frameborder="0" allowfullscreen></iframe>
                    </div>
                </div>-->

                </div>

                <!-- End Video content -->

            </section>
            <!-- End Video -->


            <!-- Start Venue -->
            <section id="mu-video" >
                <div class="mu-video-overlay">
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
        </main>
	    <!-- End main content -->
			
			
	    <!-- Start footer -->
	    <footer id="mu-footer" role="contentinfo">
			<div class="container">
				<div class="mu-footer-area">
					<div class="mu-footer-top">
						<div class="mu-social-media">
							<a href="//www.facebook.com/InstaForexNigeria"><i class="fa fa-facebook"></i></a>
							<a href="//www.twitter.com/instafxng"><i class="fa fa-twitter"></i></a>
                            <a href="//www.instagram.com/instafxng/"><i class="fa fa-instagram"></i></a>
                            <a href="//www.linkedin.com/company/instaforex-ng"><i class="fa fa-linkedin"></i></a>
							<a href="//www.youtube.com/channel/UC0Z9AISy_aMMa3OJjgX6SXw"><i class="fa fa-youtube"></i></a>
						</div>
					</div>
					<div class="mu-footer-bottom">
						<p class="mu-copy-right">&copy; Copyright <a rel="nofollow" href="#">Instant Web Net Technologies Ltd</a>. All rights reserved.</p>
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
        <script src="//code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
    </body>
</html>