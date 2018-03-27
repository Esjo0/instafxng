<?php
require_once 'init/initialize_general.php';
$thisPage = "Education";

if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    $full_name = $db_handle->sanitizePost(trim($_POST['name']));
    $email_address = $db_handle->sanitizePost(trim($_POST['email_add']));
    $phone_number = $db_handle->sanitizePost(trim($_POST['phone']));
    $venue = $db_handle->sanitizePost(trim($_POST['venue']));
    $date = date("Y-m-d H:i:s");

    // Perform the necessary validations and display the appropriate feedback
    if(empty($full_name) || empty($email_address) || empty($phone_number)  || empty($venue)) {
        $message_error = "All fields must be filled.";
    } elseif (!check_email($email_address)) {
        $message_error = "Invalid Email Supplied, Try Again.";
    } elseif (duplicate_forum_registration($email_address)) {
        $message_error = "This email is already in our record.";
    } elseif (check_number($phone_number) != 5) {
        $message_error = "The supplied phone number is invalid.";
    } else {

        $query = "SELECT email FROM forum_participant WHERE email = '$email_address' LIMIT 1";
        $result = $db_handle->runQuery($query);

        if($venue == 'Diamond Estate') { $cvenue = '1'; } else { $cvenue = '2'; }

        if($db_handle->numOfRows($result) > 0) {
            $query = "UPDATE forum_participant SET venue = '$cvenue', forum_activate = '1' WHERE email = '$email_address' LIMIT 1";
            $db_handle->runQuery($query);

        } else {

            $full_name = ucwords(strtolower(trim($full_name)));
            $full_name = explode(" ", $full_name);

            if(count($full_name) == 3) {
                $last_name = $full_name[0];
                if(strlen($full_name[2]) < 3) {
                    $middle_name = $full_name[2];
                    $first_name = $full_name[1];
                } else {
                    $middle_name = $full_name[1];
                    $first_name = $full_name[2];
                }
            } else {
                $last_name = $full_name[0];
                $middle_name = "";
                $first_name = $full_name[1];
            }

            $query = "INSERT INTO forum_participant (first_name, middle_name, last_name, email, phone, venue, forum_activate)
                VALUES ('$first_name', '$middle_name', '$last_name', '$email_address', '$phone_number', '$cvenue', '1')";

            $db_handle->runQuery($query);

        }

        // Autoresponse email to client
        if ($venue == "Diamond Estate") {
            $chosen_venue = "Block 1A, Plot 8, Diamond Estate, LASU/Isheri road, Isheri Olofin, Lagos.";
        } else if ($venue == "Ajah Office") {
            $chosen_venue = "Block A3, Suite 508/509 Eastline Shopping Complex, Opposite Abraham Adesanya Roundabout, along Lekki - Epe expressway, Lagos.";
        }

        $subject = "Instafxng Forex Traders Forum";
        $body =
<<<MAIL
<div style="background-color: #F3F1F2">
    <div style="max-width: 80%; margin: 0 auto; padding: 10px; font-size: 14px; font-family: Verdana;">
        <img src="https://instafxng.com/images/ifxlogo.png" />
        <hr />
        <div style="background-color: #FFFFFF; padding: 15px; margin: 5px 0 5px 0;">
            <p>Dear $full_name,</p>

            <p>Thank you for reserving a seat at the next Forex Traders Forum.</p>

            <p>At the Forum this month, we will discuss Harnessing ForexCopy for Big Profit.</p>

            <p>You will also have the opportunity of meeting other Forex traders and you
            could be one of two lucky winners to win $20 trading bonus. Isn’t that cool?</p>

            <p>Please mark your calendar for this date; we will also remind you via sms.</p>

            <p>Your Venue: $chosen_venue<br /><br />
            Date: 10th of March, 2018<br /><br />
            Time: 12 - 2pm</p>

            <br /><br />
            <p>Best Regards,</p>
            <p>Instafxng Support,<br />
                www.instafxng.com</p>
            <br /><br />
        </div>
        <hr />
        <div style="background-color: #EBDEE9;">
            <div style="font-size: 11px !important; padding: 15px;">
                <p style="text-align: center"><span style="font-size: 12px"><strong>We're Social</strong></span><br /><br />
                    <a href="https://facebook.com/InstaForexNigeria"><img src="https://instafxng.com/images/Facebook.png"></a>
                    <a href="https://twitter.com/instafxng"><img src="https://instafxng.com/images/Twitter.png"></a>
                    <a href="https://www.instagram.com/instafxng/"><img src="https://instafxng.com/images/instagram.png"></a>
                    <a href="https://www.youtube.com/channel/UC0Z9AISy_aMMa3OJjgX6SXw"><img src="https://instafxng.com/images/Youtube.png"></a>
                    <a href="https://linkedin.com/company/instaforex-ng"><img src="https://instafxng.com/images/LinkedIn.png"></a>
                </p>
                <p><strong>Head Office Address:</strong> TBS Place, Block 1A, Plot 8, Diamond Estate, Estate Bus-Stop, LASU/Isheri road, Isheri Olofin, Lagos.</p>
                <p><strong>Lekki Office Address:</strong> Block A3, Suite 508/509 Eastline Shopping Complex, Opposite Abraham Adesanya Roundabout, along Lekki - Epe expressway, Lagos.</p>
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

        $system_object->send_email($subject, $body, $email_address, $full_name);
        $message_success = "Your Seat Reservation Request Has Been Submitted.";
    }
} else {
    //
}

$s=date("m");
$d=date("d");
$query = "SELECT * FROM forum_schedule WHERE ((MONTH(s_date) = $s AND DAY(s_date) >=$d) OR MONTH(s_date) = ($s+1) OR MONTH(s_date) = $s) ORDER BY s_date DESC LIMIT 1";
$result = $db_handle->runQuery($query);
$forum = $db_handle->fetchAssoc($result);
?>
<!DOCTYPE html>
<html lang="en">
    <head>
        <base target="_self">
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>Instaforex Nigeria | Forex Trading Seminar</title>
        <meta name="title" content="Instaforex Nigeria | Forex Traders Forum" />
        <meta name="keywords" content="instaforex, forex seminar, forex trading seminar, forex traders froum, how to trade forex, trade forex, instaforex nigeria.">
        <meta name="description" content="Learn how to trade forex, get free information about the forex market in our forex traders forum">
        <link rel="stylesheet" href="css/free_seminar.css">
        <?php require_once 'layouts/head_meta.php'; ?>
        <link rel="stylesheet" href="css/prettyPhoto.css">
        <style>
            .photo_g > ul, .photo_g > li {
                display: inline;
            }
        </style>
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
                    <?php if(isset($forum) && !empty($forum)):?>
                    <div class="super-shadow page-top-section">
                        <?php
                        foreach ($forum as $row)
                        {
                        ?>
                        <div class="row">
                            <div class="col-sm-6">
                                <h3 style="margin: 0;"><?php echo $row['main1']; ?></h3>
                                <?php echo $row['sub1']; ?>
                                <p><strong><span style="color: black;"><a href="<?php echo $row['link']; ?>"><?php echo $row['linkt']; ?></a></span></strong>
                                </p>
                            </div>
                            <div class="col-sm-6">
                                <img src="<?php echo $row['image_path']; ?>" alt="" class="img-responsive"/>
                            </div>
                        </div>
                    </div>

                    <div class="section-tint super-shadow">
                        <div class="row text-center">
                            <div class="col-sm-12 text-danger">
                                <h3><strong><?php echo $row['main2']; ?></strong></h3>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-sm-12">
                                <?php echo $row['sub2']; ?>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-sm-12">
                                <h5>See photos from previous traders forums.</h5>

                                <ul class="gallery clearfix photo_g">
                                    <li><a href="images/traders-forum/traders-forum-1.jpg" rel="prettyPhoto[gallery1]"
                                           title=""><img src="images/traders-forum/thumbnails/traders-forum-1.jpg"
                                                         alt=" "/></a></li>
                                    <li><a href="images/traders-forum/traders-forum-2.jpg" rel="prettyPhoto[gallery1]"
                                           title=""><img src="images/traders-forum/thumbnails/traders-forum-2.jpg"
                                                         alt=" "/></a></li>
                                    <li><a href="images/traders-forum/traders-forum-3.jpg" rel="prettyPhoto[gallery1]"
                                           title=""><img src="images/traders-forum/thumbnails/traders-forum-3.jpg"
                                                         alt=" "/></a></li>
                                    <li><a href="images/traders-forum/traders-forum-4.jpg" rel="prettyPhoto[gallery1]"
                                           title=""><img src="images/traders-forum/thumbnails/traders-forum-4.jpg"
                                                         alt=" "/></a></li>
                                    <li><a href="images/traders-forum/traders-forum-5.jpg" rel="prettyPhoto[gallery1]"
                                           title=""><img src="images/traders-forum/thumbnails/traders-forum-5.jpg"
                                                         alt=" "/></a></li>
                                    <li><a href="images/traders-forum/traders-forum-6.jpg" rel="prettyPhoto[gallery1]"
                                           title=""><img src="images/traders-forum/thumbnails/traders-forum-6.jpg"
                                                         alt=" "/></a></li>
                                    <li><a href="images/traders-forum/traders-forum-7.jpg" rel="prettyPhoto[gallery1]"
                                           title=""><img src="images/traders-forum/thumbnails/traders-forum-7.jpg"
                                                         alt=" "/></a></li>
                                    <li><a href="images/traders-forum/traders-forum-8.jpg" rel="prettyPhoto[gallery1]"
                                           title=""><img src="images/traders-forum/thumbnails/traders-forum-8.jpg"
                                                         alt=" "/></a></li>
                                    <li><a href="images/traders-forum/traders-forum-9.jpg" rel="prettyPhoto[gallery1]"
                                           title=""><img src="images/traders-forum/thumbnails/traders-forum-9.jpg"
                                                         alt=" "/></a></li>
                                    <li><a href="images/traders-forum/traders-forum-10.jpg" rel="prettyPhoto[gallery1]"
                                           title=""><img src="images/traders-forum/thumbnails/traders-forum-10.jpg"
                                                         alt=" "/></a></li>
                                </ul>
                            </div>
                        </div>
                        <br/>

                        <div class="row" id="signup-section">

                            <div class="row">
                                <div class="col-sm-12">
                                    <?php if (isset($message_success)) { ?>
                                        <div class="alert alert-success">
                                            <a href="#" class="close" data-dismiss="alert"
                                               aria-label="close">&times;</a>
                                            <strong>Success!</strong> <?php echo $message_success; ?>
                                        </div>
                                    <?php } ?>

                                    <?php if (isset($message_error)) { ?>
                                        <div class="alert alert-danger">
                                            <a href="#" class="close" data-dismiss="alert"
                                               aria-label="close">&times;</a>
                                            <strong>Oops!</strong> <?php echo $message_error; ?>
                                        </div>
                                    <?php } ?>
                                </div>
                            </div>

                            <span id="opt"></span>

                            <section id="more">

                                <div class="row">
                                    <div class="col-sm-12">
                                        <form data-toggle="validator" id="signup-form" role="form" method="post"
                                              action="<?php echo htmlentities($_SERVER['REQUEST_URI']); ?>">
                                            <h3 class="text-uppercase text-center signup-header">RESERVE A SEAT NOW</h3>
                                            <br/>

                                            <div class="form-group has-feedback">
                                                <label for="name" class="control-label">Your Full Name</label>
                                                <div class="input-group margin-bottom-sm">
                                                    <span class="input-group-addon"><i
                                                                class="fa fa-user fa-fw"></i></span>
                                                    <input type="text" class="form-control" id="name" name="name"
                                                           placeholder="Your Name" data-minlength="5" required>
                                                </div>
                                                <span class="glyphicon form-control-feedback" aria-hidden="true"></span>
                                            </div>

                                            <div class="form-group has-feedback">
                                                <label for="email" class="control-label">Your Email Address</label>
                                                <div class="input-group margin-bottom-sm">
                                                    <span class="input-group-addon"><i
                                                                class="fa fa-envelope-o fa-fw"></i></span>
                                                    <input type="email" class="form-control" id="email" name="email_add"
                                                           placeholder="Your Email" data-error="Invalid Email" required>
                                                </div>
                                                <span class="glyphicon form-control-feedback" aria-hidden="true"></span>
                                                <div class="help-block with-errors"></div>
                                            </div>

                                            <div class="form-group has-feedback">
                                                <label for="phone" class="control-label">Your Phone Number</label>
                                                <div class="input-group">
                                                    <span class="input-group-addon"><i
                                                                class="fa fa-phone fa-fw"></i></span>
                                                    <input type="text" class="form-control" id="phone" name="phone"
                                                           placeholder="Your Phone" data-minlength="11" maxlength="11"
                                                           required>
                                                </div>
                                                <span class="glyphicon form-control-feedback" aria-hidden="true"></span>
                                                <div class="help-block">Example - 08031234567</div>
                                            </div>

                                            <div class="form-group">
                                                <label for="venue" class="control-label">Choose your venue</label>
                                                <div class="radio">
                                                    <label><input id="venue" type="radio" name="venue"
                                                                  value="Diamond Estate" checked required>Block 1A, Plot
                                                        8, Diamond Estate, LASU/Isheri road, Isheri Olofin,
                                                        Lagos.</label>
                                                </div>
                                                <div class="radio">
                                                    <label><input id="venue" type="radio" name="venue"
                                                                  value="Ajah Office" required>Block A3, Suite 508/509
                                                        Eastline Shopping Complex, Opposite Abraham Adesanya Roundabout,
                                                        along Lekki - Epe expressway, Lagos.</label>
                                                </div>
                                                <span class="glyphicon form-control-feedback" aria-hidden="true"></span>
                                            </div>
                                            <div class="form-group">
                                                <button type="submit" name="reserve_seat"
                                                        class="btn btn-default btn-lg">Reserve Your Seat&nbsp;<i
                                                            class="fa fa-chevron-circle-right"></i></button>
                                            </div>
                                            <small>All fields are required</small>
                                        </form>
                                    </div>
                                </div>

                            </section>

                        </div>
                        <?php
                        }
                        ?>
                        <div class="row text-center">
                            <h2 class="color-fancy">For further enquiries, please call 08182045184, 07081036115</h2>
                        </div>
                    </div>
                    <?php endif; ?>
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