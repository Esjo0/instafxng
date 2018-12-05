<?php
require_once 'init/initialize_general.php';
$get_params = allowed_get_params(['r']);
$user_code_encrypted = $get_params['r'];
if (empty($user_code_encrypted)) {
    redirect_to("https://instafxng.com");
    exit;
}

$dinner_emails = array("joshua@instafxng.com","austin.albert@circleflowmgt.com","semmymails@yahoo.com","abdulfx1@gmail.com","uchennaegbejiogu@gmail.com","icjustine@yahoo.com","shukrahbiz@gmail.com");
$user_code = decrypt_ssl(str_replace(" ", "+", $user_code_encrypted));
$user_code = preg_replace("/[^A-Za-z0-9 ]/", '', $user_code);
$client_operation = new clientOperation();
$details = $client_operation->get_user_by_code($user_code);
extract($details);
if (empty($client_email) || $client_email == NULL ) {
    redirect_to("https://instafxng.com");
    exit;
}
$query = "SELECT * FROM dinner_2018 WHERE choice = '1'";
$total_seats_taken = $db_handle->numRows($query);

$query = "SELECT * FROM dinner_2018 WHERE user_code = '$user_code' AND choice = '2'";
$numrows = $db_handle->numRows($query);
if($numrows == 1){
    $maybe = true;
}

if (isset($_POST['submit1']) || isset($_POST['submit2'])) {
    $avatar = $db_handle->sanitizePost($_POST['avatar']);
    $choice = $db_handle->sanitizePost($_POST['choice']);
    $title = $db_handle->sanitizePost($_POST['title']);
    $town = $db_handle->sanitizePost($_POST['town']);
    $state = $db_handle->sanitizePost($_POST['state']);
    $query = "SELECT * FROM dinner_2018 WHERE user_code = '$user_code' AND (choice = '1' OR choice = '3')";
    $numrows = $db_handle->numRows($query);
    if($total_seats_taken <= 82  || $maybe == true || (in_array($client_email, $dinner_emails))) {
        if ($numrows == 0) {
            if ($choice == 1 && !empty($avatar) && $avatar != NULL && !empty($town) && $town != NULL && !empty($state) && $state != NULL && !empty($title) && $title != NULL) {
                if ($maybe == true) {
                    $query = "UPDATE dinner_2018 SET choice = '$choice' WHERE user_code = '$user_code'";
                } else {
                    $query = "INSERT IGNORE INTO dinner_2018 (user_code, choice, title, town, gender, state, type, name, phone, email) VALUE('$client_user_code', '$choice', '$title', '$town', '$avatar', '$state', '1', '$client_full_name', '$client_phone_number', '$client_email')";

                }
                $result = $db_handle->runQuery($query);
                if ($result) {
                    if ($avatar == 1) {
                        $gender = "his";
                    } elseif ($avatar == 2) {
                        $gender = "her";
                    }
                    $subject = 'Your seat has been reserved, ' . $client_first_name . '!';
                    $message_final = <<<MAIL
                    <div style="background-color: #F3F1F2;  background-image: url('https://instafxng.com/imgsource/dinner-seamless-doodle.png');">
                        <div style="max-width: 80%; margin: 0 auto; padding: 10px; font-size: 14px; font-family: 'Comic Sans MS', cursive, sans-serif; background-image: url('https://instafxng.com/imgsource/Mail%20Images/full-bloom.png');">
                                <img src="https://instafxng.com/images/ifxlogo.png" />
                            <hr />
                            <div style="background-color: transparent; padding: 15px; margin: 5px 0 5px 0;">
                                <p>All hail <b>$title</b> $client_full_name, the first of $gender name from <b>the house of $town.</b></p>
                                <p>It is our pleasure to receive your consent to attend the Royal Ball.</p>
                                <p>Your seat has been reserved and your dynasty is being prepared to receive your presence.</p>
                                <p>The royal invite will be sent when all is set, brace up it's going to be a ball to remember.</p>
                                <p>Compliment of the season!</p>
                                <br /><br />
                                <p>Best Regards,</p>
                                <p>The InstaFxNg Team,<br />
                                   www.instafxng.com</p>
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
                                    <p><strong>Lekki Office Address:</strong> Block A3, Suite 508/509 Eastline Shopping Complex, Opposite Abraham Adesanya Roundabout, along Lekki - Epe expressway, Lagos.</p>
                                    <p><strong>Office Number:</strong> 08139250268, 08083956750</p>
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
                    $system_object->send_email($subject, $message_final, $client_email, $client_first_name);
                    $message_success = "YOU HAVE SUCCESSFULLY RESERVED A SEAT FOR THE INSTAFXNG ROYAL BALL";
                    header('Location: dinner_completed.php?z=' . $choice);
                } else {
                    $message_error = "Reservation Not Successful Kindly Try Again";
                }
            }
            if ($choice == 2) {
                $query = "INSERT IGNORE INTO dinner_2018 (user_code, choice) VALUE('$client_user_code', '$choice')";
                $result = $db_handle->runQuery($query);
                if ($result) {
                    $subject = 'The Ball Will Be Brighter With Your Presence';
                    $message_final = <<<MAIL
                    <div style="background-color: #F3F1F2;  background-image: url('https://instafxng.com/imgsource/dinner-seamless-doodle.png');">
                        <div style="max-width: 80%; margin: 0 auto; padding: 10px; font-size: 14px; font-family: 'Comic Sans MS', cursive, sans-serif; background-image: url('https://instafxng.com/imgsource/Mail%20Images/full-bloom.png');">
                                <img src="https://instafxng.com/images/ifxlogo.png" />
                            <hr />
                            <div style="background-color: transparent; padding: 15px; margin: 5px 0 5px 0;">
                            <p>Your royal highness,</p>
                            <p>It will be a pleasure to receive you at this year's Royal ball.</p>
                            <p>However, we understand that the season is very eventful and you are quite uncertain of your attendance.</p>
                            <p>To this end, our dynasty has decided to reserve your spot for the next 5 nights.</p>
                            <p>The royal raven will be back to get your final decision by the fifth night.</p>
                            <p>This ball will be one to remember forever… We look forward to hosting your royalty.</p>
                                <br /><br />
                                <p>Best Regards,</p>
                                <p>The InstaFxNg Team,<br />
                                   www.instafxng.com</p>
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
                                    <p><strong>Lekki Office Address:</strong> Block A3, Suite 508/509 Eastline Shopping Complex, Opposite Abraham Adesanya Roundabout, along Lekki - Epe expressway, Lagos.</p>
                                    <p><strong>Office Number:</strong> 08139250268, 08083956750</p>
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
                    $system_object->send_email($subject, $message_final, $client_email, $last_name);

                    $message_success = "YOUR SEAT HAS BEEN TEMPORARILY RESERVED. KINDLY CONFIRM YOUR RESERVATION WITHIN THE NEXT
                                        5 DAYS.";
                    header('Location: dinner_completed.php?z=' . $choice);
                } else {
                    $message_error = "Reservation Not Successful Kindly Try Again";
                }
            }
            if ($choice == 3) {
                $query = "INSERT IGNORE INTO dinner_2018 (user_code, choice) VALUE('$client_user_code', '$choice')";
                $result = $db_handle->runQuery($query);
                if ($result) {
                    $subject = 'The Ball Would have been more fun with you ' . $client_first_name . '!';
                    $message_final = <<<MAIL
                    <div style="background-color: #F3F1F2;  background-image: url('https://instafxng.com/imgsource/dinner-seamless-doodle.png');">
                        <div style="max-width: 80%; margin: 0 auto; padding: 10px; font-family: 'Comic Sans MS', cursive, sans-serif; background-image: url('https://instafxng.com/imgsource/Mail%20Images/full-bloom.png');">
                            <img src="https://instafxng.com/images/ifxlogo.png" />
                            <hr />
                            <div style="background-color: transparent; padding: 15px; margin: 5px 0 5px 0;">
                                <p>Dear $client_first_name,</p>
                                <p>The ball would be incomplete without your presence.</p>
                                <p>Even though our desire is to have you grace this year's grand dinner, we understand that there are other pertinent tasks that will require your time this season, hence your inability to attend this event.</p>
                                <p>We look forward to celebrating a greater feat with you next year.</p>
                                <p>Your invite for this year's ball has been canceled.</p>
                                <p>Compliment of the season.</p>
                                <br /><br />
                                <p>Best Regards,</p>
                                <p>The InstaFxNg Team,<br />
                                   www.instafxng.com</p>
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
                                    <p><strong>Lekki Office Address:</strong> Block A3, Suite 508/509 Eastline Shopping Complex, Opposite Abraham Adesanya Roundabout, along Lekki - Epe expressway, Lagos.</p>
                                    <p><strong>Office Number:</strong> 08139250268, 08083956750</p>
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
                    $system_object->send_email($subject, $message_final, $client_email, $last_name);
                    $message_success = "THANK YOU WE WILL SURELY INVITE YOU FOR SUBSEQUENT EVENTS.";
                    header('Location: dinner_completed.php?z=' . $choice);
                } else {
                    $message_error = "Reservation Not Successful Kindly Try Again";
                }
            }
        } else {
            $message_error = "You have completed the reservation process Earlier!!!.";

        }
    }else{
        $message_error = "All seat Have been reserved.";
    }
}

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
        <link rel="stylesheet" href="css/prettyPhoto.css">
        <style>
            .photo_g > ul, .photo_g > li {
                display: inline;
            }
        </style>
        <style>
            body {
                background: url("images/full-bloom.png") repeat;
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
            <div class="row text-center">
                <?php include 'layouts/feedback_message.php'; ?>

                <div class="col-sm-12 text-center">
                    <h1 style="color:#db281f; font-size: 45px !important;"><span style="font-family: 'Patua One', cursive;">InstaFxNg </span><span style="font-family: 'Pacifico', cursive;">Royal Ball</span></h1>
                    <p style="font-family: 'Josefin Sans', sans-serif !important; font-size: 22px !important;">A delightful evening to celebrate your loyalty and support to our brand</p>
                </div>
            </div>
        </section>
        <form data-toggle="validator" class="" role="form" method="post" action="">

        <section class="container-fluid no-gutter" style="background-color: white; margin-top: 15px;">
            <div class="row" style="background-color: white;">
                <div class="col-sm-6" style="padding: 0 !important;">
                    <center><img src="images/royal_ball_image.jpg" alt="" class="img img-responsive" /></center>
                </div>

                <?php if($total_seats_taken <= 82 || $maybe == true || (in_array($client_email, $dinner_emails))){?>
                <div class="col-sm-6">
                <div class="row">
                    <div class="col-md-1"></div>
                    <div class="col-md-10">
                        <p class="text-center">Please complete the form below to reserve your seat.</p>
                        <div class="form-group col-sm-12">
                            <div class="input-group">
                                <span class="input-group-addon"><i class="fa fa-user fa-fw"></i></span>
                                <input name="full_name" type="text" id="" value="<?php echo $client_full_name; ?>" class="form-control" placeholder="Your Full Name" required disabled/>
                            </div>
                        </div>

                        <div class="form-group col-sm-12">
                            <div class="input-group">
                                <span class="input-group-addon"><i class="fa fa-envelope fa-fw"></i></span>
                                <input name="email" type="text" id="" value="<?php echo $client_email; ?>" class="form-control" placeholder="Your email address" required disabled/>
                            </div>
                        </div>

                        <div class="form-group col-sm-12">
                            <small class="text-center"><strong>Will you be in attendance?</strong></small>
                        </div>

                        <div class="row">
                            <div class="col-md-2"></div>
                            <!-- Group of default radios - option 1 -->
                            <div class="col-md-3 ">
                                <input onchange="you('1')" value="1" type="radio"
                                       class="custom-control-input"
                                       id="defaultGroupExample1" name="choice">
                                <label class="custom-control-label"
                                       for="defaultGroupExample1">YES</label>
                            </div>

                            <!-- Group of default radios - option 2 -->
                            <div class="col-md-3 custom-control custom-radio">
                                <input onchange="you('2')" value="2" type="radio"
                                       class="custom-control-input"
                                       id="defaultGroupExample2" name="choice">
                                <label class="custom-control-label"
                                       for="defaultGroupExample2">MAYBE</label>
                            </div>

                            <!-- Group of default radios - option 3 -->
                            <div class="col-md-3 custom-control custom-radio">
                                <input onchange="you('3')" value="3" type="radio"
                                       class="custom-control-input"
                                       id="defaultGroupExample3" name="choice">
                                <label class="custom-control-label"
                                       for="defaultGroupExample3">NO</label>
                            </div>
                            <div class="col-md-1"></div>

                        </div>

                        <div class="text-center" style="display: none;" id="submit">
                            <hr>
                            <button name="submit1" type="submit" class="btn btn-success">SUBMIT</button>
                            </hr>
                        </div>
                        <div class="text-center row" id="proceed" style="display: none;">
                            <p class="row" style="margin-bottom:20px;">
                            <label class="col-md-12 text-center" for="form8">Select Your Royal Title</label>
                                <br>
                                <span class="fa fa-info-circle"></span>
                            <i class="col-md-12 text-center">Example: Emperor of Ile-Ife</i>
                            </p>
                            <div class="col-md-4">
                                <div class="form-append">Select Your Prefered Royal Title</div>
                                <select id="title" name="title" class="form-control" required>
                                    <option value="">  </option>
                                    <optgroup label="Titles">
                                    <option value="Emperor">Emperor</option>
                                    <option value="King">King</option>
                                    <option value="Duke">Duke</option>
                                    <option value="Prince">Prince</option>
                                    <option value="Knight">Knight</option>
                                    <option value="Sir">Sir</option>
                                    <option value="Viscount">Viscount</option>
                                    <option value="Lord">Lord</option>
                                    <option value="Empress">Empress</option>
                                    <option value="Queen">Queen</option>
                                    <option value="Princess">Princess</option>
                                    <option value="Duchess">Duchess</option>
                                    <option value="Lady">Lady</option>
                                    <option value="Baronet">Baronet</option>
                                    <option value="Earldom">Earldom</option>
                                    <option value="Viscountess">Viscountess</option>
                                        </optgroup>
                                </select>
                            </div>

                            <div class="col-md-4">
                                <div class="form-append">Enter Your Home Town</div>
                                <input name="town" type="text" id="town" class="form-control" required/>
                            </div>
                            <div class="col-md-4">
                                <div class="form-append">Select Your State of Residence</div>
                                <select id="state" name="state" class="form-control" required>
                                    <option value="">  </option>
                                    <optgroup label="Nigerian States">
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
                                    <option value="Zamfara State">Zamfara State</option>
                                        </optgroup>
                                </select>
                            </div>
                            <a href="#select_avatar" class="text-center btn btn-primary">Proceed</a>
                        </div>
                    </div>
                    <div class="col-md-1"></div>
                </div>
                </div>
                <?php }else{?>
                    <div class="col-md-6 text-center " style="margin-top:100px">
                        <h3>ALL SEATS HAVE BEEN RESERVED</h3>
                    </div>
                <?php }?>

        </section>
        <!--Section: Main info-->
        <section id="select_avatar" class=" view wow fadeIn" title="Click on an image to select your style"
                 style="display:none; margin:0px; padding:0px;">
            <h3 style="background-color: rgba(128, 128, 128, 0.15); font-family: Sofia;" class="h3 text-center">
                Select Your Style</h3>
            <div class="row">
                <div class="col-md-3"></div>
                <p class="col-md-6 card text-center" style="font-size:20px;"><i>"People will stare. Make it worth their while." Harry
                        Winston</i></p>
                <div class="col-md-3"></div>
            </div>
            <!--Grid row-->
            <div class="row wow fadeIn text-center">
                <!--Grid column-->
                <div class="col-md-1"></div>
                <div class="col-md-5 text-center">

                    <div class="row text-center" >

                        <!--Card-->
                        <div class="col-md-6 text-center" style="margin-top:20px">

                            <!--Card image-->
                                <center><img data-toggle="modal" style="background-color: white; height:150px; width:150px;" data-target="#sideModalTR4"
                                     src="images/male4.png"
                                     class="card-img-top" ></center>
                                <strong>
                                    <a  id="duke2" style="display:none;" href="" class="text-center dark-grey-text">
                                        <b
                                            style="background-color: #d7d7d7;
                                               border-radius:8px; color:green !important;
                                               box-shadow: 0 4px 8px 0 rgb(0, 128, 0), 0 6px 20px 0 rgba(255, 11, 0, 0.83)"">
                                        <span class="fa fa-check"></span>
                                        </b>
                                        <div class="text-center">
                                            <button name="submit2" type="submit" class="btn btn-success">SUBMIT</button>
                                        </div>
                                    </a>
                                </strong>
                            <!--Card image-->
                            <!-- Side Modal Top Right -->

                            <!-- To change the direction of the modal animation change .right class -->
                            <div class="modal" id="sideModalTR4" tabindex="-1" role="dialog"
                                 aria-labelledby="myModalLabel" aria-hidden="true">

                                <!-- Add class .modal-side and then add class .modal-top-right (or other classes from list above) to set a position to the modal -->
                                <div class="modal-dialog" role="document">


                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                <span aria-hidden="true">&times;</span>
                                            </button>
                                        </div>
                                        <div class="modal-body">
                                            <button class="btn btn-primary" onclick="select_avatar(1,2)" data-dismiss="modal" >SELECT</button>
                                            <br/>
                                            <img src="images/male4.png"
                                                 class="img img-responsive" alt="">

                                        </div>
                                    </div>
                                </div>
                            </div>
                            <!-- Side Modal Top Right -->

                        </div>
                        <!--Card-->
                        <!--Card-->
                        <div class="col-md-6 text-center" style="margin-top:20px">

                            <!--Card image-->
                                <center><img  data-toggle="modal" style="background-color: white; height:150px; width:150px;" data-target="#sideModalTR9" src="images/male9.png"
                                     class="img img-responsive" ></center>
                                <strong>
                                    <a id="duke3" style="display:none;" href="" class="text-center dark-grey-text">
                                        <b
                                           style="background-color: #d7d7d7;
                                               border-radius:8px; color:green !important;
                                               box-shadow: 0 4px 8px 0 rgb(0, 128, 0), 0 6px 20px 0 rgba(255, 11, 0, 0.83)"">
                                        <span class="fa fa-check"></span>
                                        </b>
                                        <div class="text-center">
                                            <button name="submit2" type="submit" class="btn btn-success">SUBMIT</button>
                                        </div>
                                    </a>
                                </strong>
                            <!--Card image-->
                            <!-- Side Modal Top Right -->

                            <!-- To change the direction of the modal animation change .right class -->
                            <div class="modal" id="sideModalTR9" tabindex="-1" role="dialog"
                                 aria-labelledby="myModalLabel" aria-hidden="true">

                                <!-- Add class .modal-side and then add class .modal-top-right (or other classes from list above) to set a position to the modal -->
                                <div class="modal-dialog" role="document">


                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                <span aria-hidden="true">&times;</span>
                                            </button>
                                        </div>
                                        <div class="modal-body">
                                            <button class="btn btn-primary" onclick="select_avatar(1,3)" data-dismiss="modal" >SELECT</button>
                                            <br/>
                                            <img src="images/male9.png"
                                                 class="img img-responsive" alt="">
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <!-- Side Modal Top Right -->
                        </div>
                        <!--Card-->
                        <!--Card-->
                        <div class="col-md-6 text-center" style="margin-top:20px">

                            <!--Card image-->
                               <center> <img data-toggle="modal" style="background-color: white; height:150px; width:150px;" data-target="#sideModalTR10"
                                     src="images/male10.jpg"
                                     class="img img-responsive"></center>
                                <strong>
                                    <a id="duke4" style="display:none;" href="" class="text-center dark-grey-text">
                                        <b
                                           style="background-color: #d7d7d7;
                                               border-radius:8px; color:green !important;
                                               box-shadow: 0 4px 8px 0 rgb(0, 128, 0), 0 6px 20px 0 rgba(255, 11, 0, 0.83)"">
                                        <span class="fa fa-check"></span>
                                        </b>
                                        <div class="text-center">
                                            <button name="submit2" type="submit" class="btn btn-success">SUBMIT</button>
                                        </div>
                                    </a>
                                </strong>
                            <!--Card image-->
                            <!-- Side Modal Top Right -->

                            <!-- To change the direction of the modal animation change .right class -->
                            <div class="modal" id="sideModalTR10" tabindex="-1" role="dialog"
                                 aria-labelledby="myModalLabel" aria-hidden="true">

                                <!-- Add class .modal-side and then add class .modal-top-right (or other classes from list above) to set a position to the modal -->
                                <div class="modal-dialog" role="document">


                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                <span aria-hidden="true">&times;</span>
                                            </button>
                                        </div>
                                        <div class="modal-body">
                                            <button class="btn btn-primary" onclick="select_avatar(1,4)" data-dismiss="modal" >SELECT</button>
                                            <br/>
                                            <img src="images/male10.jpg"
                                                 class="img img-responsive" alt="">
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <!-- Side Modal Top Right -->

                        </div>
                        <!--Card-->
                        <!--Card-->
                        <div class="col-md-6 text-center" style="margin-top:20px">

                            <!--Card image-->
                            <center><img data-toggle="modal" style="background-color: white; height:150px; width:150px;" data-target="#sideModalTR3"
                                         src="images/male3.png"
                                         class="img img-responsive" ><center>
                                    <strong>
                                        <a id="duke1" style="display:none;" href="" class="text-center dark-grey-text">
                                            <b
                                               style=" background-color: #d7d7d7;
                                               border-radius:8px; color:green !important;
                                               box-shadow: 0 4px 8px 0 rgb(0, 128, 0), 0 6px 20px 0 rgba(255, 11, 0, 0.83)"">
                                            <span class="fa fa-check"></span>
                                            </b>
                                            <div class="text-center">
                                                <button name="submit2" type="submit" class="btn btn-success">SUBMIT</button>
                                            </div>
                                        </a>
                                    </strong>
                                    <!--Card image-->
                                    <!-- Side Modal Top Right -->

                                    <!-- To change the direction of the modal animation change .right class -->
                                    <div class="modal"  id="sideModalTR3" tabindex="-1" role="dialog"
                                         aria-labelledby="myModalLabel" aria-hidden="true" >

                                        <!-- Add class .modal-side and then add class .modal-top-right (or other classes from list above) to set a position to the modal -->
                                        <div class="modal-dialog" role="document" >


                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                        <span aria-hidden="true">&times;</span>
                                                    </button>
                                                </div>
                                                <div class="modal-body" style="background-color:transparent">
                                                    <button class="btn btn-primary" onclick="select_avatar(1,1)" data-dismiss="modal" >SELECT</button>
                                                    <br/>
                                                    <img src="images/male3.png"
                                                         class="img img-responsive" alt="">
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <!-- Side Modal Top Right -->

                        </div>
                        <!--Card-->
                    </div>
                </div>
                <!--Grid column-->

                <!--Grid column-->
                <div class="col-md-5 text-center">
                        <div class="row text-center" >

                            <!--Card-->
                            <div class="col-md-6 text-center" style="margin-top:20px">

                                <!--Card image-->
                                <center><img data-toggle="modal" style="background-color: white; height:150px; width:150px;" data-target="#sideModalTR11"
                                             src="images/ball1.jpg"
                                             class="card-img-top" ></center>
                                <strong>
                                    <a id="duch1" style="display:none;" href="" class="text-center dark-grey-text">
                                        <b
                                           style=" background-color: #d7d7d7;
                                               border-radius:8px; color:green !important;
                                               box-shadow: 0 4px 8px 0 rgb(0, 128, 0), 0 6px 20px 0 rgba(255, 11, 0, 0.83)"">
                                        <span class="fa fa-check"></span>
                                        </b>
                                        <div class="text-center">
                                            <button name="submit2" type="submit" class="btn btn-success">SUBMIT</button>
                                        </div>
                                    </a>
                                </strong>
                                <!--Card image-->
                                <!-- Side Modal Top Right -->

                                <!-- To change the direction of the modal animation change .right class -->
                                <div class="modal" id="sideModalTR11" tabindex="-1" role="dialog"
                                     aria-labelledby="myModalLabel" aria-hidden="true">

                                    <!-- Add class .modal-side and then add class .modal-top-right (or other classes from list above) to set a position to the modal -->
                                    <div class="modal-dialog" role="document">


                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                    <span aria-hidden="true">&times;</span>
                                                </button>
                                            </div>
                                            <div class="modal-body">
                                                <button class="btn btn-primary" onclick="select_avatar(2,5)" data-dismiss="modal" >SELECT</button>
                                                <br/>
                                                <img src="images/ball1.jpg"
                                                     class="img img-responsive" alt="">

                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <!-- Side Modal Top Right -->

                            </div>
                            <!--Card-->
                            <!--Card-->
                            <div class="col-md-6 text-center" style="margin-top:20px">

                                <!--Card image-->
                                <center><img  data-toggle="modal" style="background-color: white; height:150px; width:150px;"
                                              data-target="#sideModalTR12" src="images/ball3.jpg"
                                              class="img img-responsive" ></center>
                                <strong>
                                    <a id="duch2" style="display:none;" href="" class="text-center dark-grey-text">
                                        <b
                                           style=" background-color: #d7d7d7;
                                               border-radius:8px; color:green !important;
                                               box-shadow: 0 4px 8px 0 rgb(0, 128, 0), 0 6px 20px 0 rgba(255, 11, 0, 0.83)"">
                                        <span class="fa fa-check"></span>
                                        </b>
                                        <div class="text-center">
                                            <button name="submit2" type="submit" class="btn btn-success">SUBMIT</button>
                                        </div>
                                    </a>
                                </strong>
                                <!--Card image-->
                                <!-- Side Modal Top Right -->

                                <!-- To change the direction of the modal animation change .right class -->
                                <div class="modal" id="sideModalTR12" tabindex="-1" role="dialog"
                                     aria-labelledby="myModalLabel" aria-hidden="true">

                                    <!-- Add class .modal-side and then add class .modal-top-right (or other classes from list above) to set a position to the modal -->
                                    <div class="modal-dialog" role="document">


                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                    <span aria-hidden="true">&times;</span>
                                                </button>
                                            </div>
                                            <div class="modal-body">
                                                <button class="btn btn-primary" onclick="select_avatar(2,6)" data-dismiss="modal" >SELECT</button>
                                                <br/>
                                                <img src="images/ball3.jpg"
                                                     class="img img-responsive" alt="">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <!-- Side Modal Top Right -->
                            </div>
                            <!--Card-->
                            <!--Card-->
                            <div class="col-md-6 text-center" style="margin-top:20px">

                                <!--Card image-->
                                <center> <img data-toggle="modal" style="background-color: white; height:150px; width:150px;" data-target="#sideModalTR13"
                                              src="images/ball6.jpg"
                                              class="img img-responsive"></center>
                                <strong>
                                    <a id="duch3" style="display:none;" href="" class="text-center dark-grey-text">
                                        <b
                                           style=" background-color: #d7d7d7;
                                               border-radius:8px; color:green !important;
                                               box-shadow: 0 4px 8px 0 rgb(0, 128, 0), 0 6px 20px 0 rgba(255, 11, 0, 0.83)"">
                                        <span class="fa fa-check"></span>
                                        </b>
                                        <div class="text-center">
                                            <button name="submit2" type="submit" class="btn btn-success">SUBMIT</button>
                                        </div>
                                    </a>
                                </strong>
                                <!--Card image-->
                                <!-- Side Modal Top Right -->

                                <!-- To change the direction of the modal animation change .right class -->
                                <div class="modal" id="sideModalTR13" tabindex="-1" role="dialog"
                                     aria-labelledby="myModalLabel" aria-hidden="true">

                                    <!-- Add class .modal-side and then add class .modal-top-right (or other classes from list above) to set a position to the modal -->
                                    <div class="modal-dialog" role="document">


                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                    <span aria-hidden="true">&times;</span>
                                                </button>
                                            </div>
                                            <div class="modal-body">
                                                <button class="btn btn-primary" onclick="select_avatar(2,7)" data-dismiss="modal" >SELECT</button>
                                                <br/>
                                                <img src="images/ball6.jpg"
                                                     class="img img-responsive" alt="">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <!-- Side Modal Top Right -->

                            </div>
                            <!--Card-->
                            <!--Card-->
                            <div class="col-md-6 text-center" style="margin-top:20px">

                                <!--Card image-->
                                <center><img data-toggle="modal" style="background-color: white; height:150px; width:150px;" data-target="#sideModalTR14"
                                             src="images/ball9.jpg"
                                             class="img img-responsive" ><center>
                                        <strong>
                                            <a id="duch4" style="display:none;" href="" class="text-center dark-grey-text">
                                                <b
                                                   style=" background-color: #d7d7d7;
                                               border-radius:8px; color:green !important;
                                               box-shadow: 0 4px 8px 0 rgb(0, 128, 0), 0 6px 20px 0 rgba(255, 11, 0, 0.83)"">
                                                <span class="fa fa-check"></span>
                                                </b>
                                                <div class="text-center">
                                                    <button name="submit2" type="submit" class="btn btn-success">SUBMIT</button>
                                                </div>
                                            </a>
                                        </strong>
                                        <!--Card image-->
                                        <!-- Side Modal Top Right -->

                                        <!-- To change the direction of the modal animation change .right class -->
                                        <div class="modal"  id="sideModalTR14" tabindex="-1" role="dialog"
                                             aria-labelledby="myModalLabel" aria-hidden="true" >

                                            <!-- Add class .modal-side and then add class .modal-top-right (or other classes from list above) to set a position to the modal -->
                                            <div class="modal-dialog" role="document" >


                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                            <span aria-hidden="true">&times;</span>
                                                        </button>
                                                    </div>
                                                    <div class="modal-body" style="background-color:transparent">
                                                        <button class="btn btn-primary" onclick="select_avatar(2,8)" data-dismiss="modal" >SELECT</button>
                                                        <br/>
                                                        <img src="images/ball9.jpg"
                                                             class="img img-responsive" alt="">
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <!-- Side Modal Top Right -->

                            </div>
                            <!--Card-->
                        </div>
                </div>
                <!--Grid column-->
                <div class="col-md-1"></div>
            </div>
            <input class="form-control" type="hidden" name="avatar" id="avatar" required/>
            <!--Grid row-->
            <br>
            <hr>
            </div>

        </section>
        <!--Section: Main info-->
        </form>
        <section class="container-fluid no-gutter" style="background-color: white; margin-top: 15px;">
            <div class="row">
                <div class="col-sm-6">
                    <h3>About the Royal Ball event</h3>
                    <p>The InstaFxNg 2018 dinner event is themed ‘Royal Ball' in celebration of your loyalty and
                        support to our brand.</p>
                    <p>This year's dinner is set to celebrate your royalty and loyalty in crown, wine and great
                        splendor!</p>
                    <p>We're set to bring you a wonderful and an unforgettable evening at the ball as we host you to
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
        <!-- SCRIPTS -->
        <script>
            function you(type) {
                console.log(type);
                if (type == '1') {
                    document.getElementById("submit").style.display = "none";
                    document.getElementById("proceed").style.display = "block";
                    document.getElementById("select_avatar").style.display = "block";
                    document.getElementById("title").required = true;
                    document.getElementById("town").required = true;
                    document.getElementById("state").required = true;
                    document.getElementById("avatar").required = true;


                }
                else if (type == '2' || type == '3') {
                    document.getElementById("submit").style.display = "block";
                    document.getElementById("proceed").style.display = "none";
                    document.getElementById("select_avatar").style.display = "none";
                    document.getElementById("title").required = false;
                    document.getElementById("town").required = false;
                    document.getElementById("state").required = false;
                    document.getElementById("avatar").required = false;
                }
            }
        </script>

        <script>
            function select_avatar(type,id) {
                if (type >= 1 && type <= 2) {
                    console.log(type);
                    document.getElementById("avatar").value = type;
                }
                if (id == 1) {
                    document.getElementById("duke1").style.display = "block";
                    document.getElementById("duke2").style.display = "none";
                    document.getElementById("duke3").style.display = "none";
                    document.getElementById("duke4").style.display = "none";
                    document.getElementById("duch1").style.display = "none";
                    document.getElementById("duch2").style.display = "none";
                    document.getElementById("duch3").style.display = "none";
                    document.getElementById("duch4").style.display = "none";
                }else if (id == 2) {
                    document.getElementById("duke1").style.display = "none";
                    document.getElementById("duke2").style.display = "block";
                    document.getElementById("duke3").style.display = "none";
                    document.getElementById("duke4").style.display = "none";
                    document.getElementById("duch1").style.display = "none";
                    document.getElementById("duch2").style.display = "none";
                    document.getElementById("duch3").style.display = "none";
                    document.getElementById("duch4").style.display = "none";
                }else if (id == 3) {
                    document.getElementById("duke1").style.display = "none";
                    document.getElementById("duke2").style.display = "none";
                    document.getElementById("duke3").style.display = "block";
                    document.getElementById("duke4").style.display = "none";
                    document.getElementById("duch1").style.display = "none";
                    document.getElementById("duch2").style.display = "none";
                    document.getElementById("duch3").style.display = "none";
                    document.getElementById("duch4").style.display = "none";
                }else if (id == 4) {
                    document.getElementById("duke1").style.display = "none";
                    document.getElementById("duke2").style.display = "none";
                    document.getElementById("duke3").style.display = "none";
                    document.getElementById("duke4").style.display = "block";
                    document.getElementById("duch1").style.display = "none";
                    document.getElementById("duch2").style.display = "none";
                    document.getElementById("duch3").style.display = "none";
                    document.getElementById("duch4").style.display = "none";
                }else if (id == 5) {
                    document.getElementById("duke1").style.display = "none";
                    document.getElementById("duke2").style.display = "none";
                    document.getElementById("duke3").style.display = "none";
                    document.getElementById("duke4").style.display = "none";
                    document.getElementById("duch1").style.display = "block";
                    document.getElementById("duch2").style.display = "none";
                    document.getElementById("duch3").style.display = "none";
                    document.getElementById("duch4").style.display = "none";
                }else if (id == 6) {
                    document.getElementById("duke1").style.display = "none";
                    document.getElementById("duke2").style.display = "none";
                    document.getElementById("duke3").style.display = "none";
                    document.getElementById("duke4").style.display = "none";
                    document.getElementById("duch1").style.display = "none";
                    document.getElementById("duch2").style.display = "block";
                    document.getElementById("duch3").style.display = "none";
                    document.getElementById("duch4").style.display = "none";
                }else if (id == 7) {
                    document.getElementById("duke1").style.display = "none";
                    document.getElementById("duke2").style.display = "none";
                    document.getElementById("duke3").style.display = "none";
                    document.getElementById("duke4").style.display = "none";
                    document.getElementById("duch1").style.display = "none";
                    document.getElementById("duch2").style.display = "none";
                    document.getElementById("duch3").style.display = "block";
                    document.getElementById("duch4").style.display = "none";
                }else if (id == 8) {
                    document.getElementById("duke1").style.display = "none";
                    document.getElementById("duke2").style.display = "none";
                    document.getElementById("duke3").style.display = "none";
                    document.getElementById("duke4").style.display = "none";
                    document.getElementById("duch1").style.display = "none";
                    document.getElementById("duch2").style.display = "none";
                    document.getElementById("duch3").style.display = "none";
                    document.getElementById("duch4").style.display = "block";
                }
            }
        </script>
    <script type="text/javascript" charset="utf-8">
            $(document).ready(function(){
                $("area[rel^='prettyPhoto']").prettyPhoto();

                $(".gallery:first a[rel^='prettyPhoto']").prettyPhoto({animation_speed:'normal',theme:'light_square',slideshow:3000, autoplay_slideshow: false});
                $(".gallery:gt(0) a[rel^='prettyPhoto']").prettyPhoto({animation_speed:'fast',slideshow:10000, hideflash: true});

                $("#custom_content a[rel^='prettyPhoto']:first").prettyPhoto({
                    custom_markup: '<div id="map_canvas" style="width:260px; height:265px"></div>',
                    changepicturecallback: function(){ initialize(); }
                });

                $("#custom_content a[rel^='prettyPhoto']:last").prettyPhoto({
                    custom_markup: '<div id="bsap_1259344" class="bsarocks bsap_d49a0984d0f377271ccbf01a33f2b6d6"></div><div id="bsap_1237859" class="bsarocks bsap_d49a0984d0f377271ccbf01a33f2b6d6" style="height:260px"></div><div id="bsap_1251710" class="bsarocks bsap_d49a0984d0f377271ccbf01a33f2b6d6"></div>',
                    changepicturecallback: function(){ _bsap.exec(); }
                });
            });
        </script>
        <script src="js/jquery.prettyPhoto.js"></script>
    </body>
</html>