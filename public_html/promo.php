<?php
session_start();
require_once 'init/initialize_general.php';
$thisPage = "Promotion";

$interest_yes = "i_have_traded_forex_before.";
$interest_no = "i_have_not_traded_forex_before";
if(isset($_POST['sign_up'])) {
    if(isset($_POST['g-recaptcha-response']) && !empty($_POST['g-recaptcha-response'])) {
        $secret = '6LcKDhATAAAAALn9hfB0-Mut5qacyOxxMNOH6tov';
        $verifyResponse = file_get_contents('https://www.google.com/recaptcha/api/siteverify?secret=' . $secret . '&response=' . $_POST['g-recaptcha-response']);
        $responseData = json_decode($verifyResponse);
        if ($responseData->success) {
            $name = $db_handle->sanitizePost(trim($_POST['name']));
            $email = $db_handle->sanitizePost(trim($_POST['email']));
            $phone = $db_handle->sanitizePost(trim($_POST['phone']));
            $interest = $db_handle->sanitizePost(trim($_POST['interest']));
            if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                $message_error = "You entered an invalid email address, please try again.";
            }
            extract(split_name($name));
            if ($interest == $interest_no) {
                if ($obj_loyalty_training->is_duplicate_training($email, $phone)) {
                    $training = $obj_loyalty_training->add_training($first_name, $last_name, $email, $phone, 1);
                    $_SESSION['f_name'] = $first_name;
                    $_SESSION['l_name'] = $last_name;
                    $_SESSION['m_name'] = $middle_name;
                    $_SESSION['email'] = $email;
                    $_SESSION['phone'] = $phone;
                    redirect_to("../forex-income/");
                    exit();
                } else {
                    $message_error = "Sorry, you have previously registered for the FxAcademy Online Training.
					<a href='../fxacademy/'><b>Click here to visit the FxAcademy now</b></a>";
                }
            }
            if ($interest == $interest_yes) {
                if ($obj_loyalty_training->is_duplicate_loyalty($email, $phone)) {
                    $loyalty = $obj_loyalty_training->add_loyalty($first_name, $last_name, $email, $phone, 1);
                    $_SESSION['f_name'] = $first_name;
                    $_SESSION['l_name'] = $last_name;
                    $_SESSION['m_name'] = $middle_name;
                    $_SESSION['email'] = $email;
                    $_SESSION['phone'] = $phone;
                    $_SESSION['source'] = 'lp';
                    redirect_to("live_account.php");
                    exit();
                } else {
                    $message_error = "Sorry, you have previously enrolled into the InstaFxNg Loyalty Promotions And Rewards";
                }
            }
        } else {
            $message_error = "Kindly take the robot test.";
        }
    } else {
        $message_error = "Kindly take the robot test.";
    }
}
?>
<!DOCTYPE html>
<html lang="en">
    <head>
        <base target="_self">
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>Instaforex Nigeria | Instaforex Promotions</title>
        <meta name="title" content="" />
        <meta name="keywords" content="instaforex, promotions of instaforex, gifts for forex traders, contest and promotions" />
        <meta name="description" content="" />
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
                    <div  class="modal video-modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModal">
                        <div class="modal-dialog" role="document">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                                    <br/>
                                </div>
                                <div class="modal-body">
                                    <div style="padding: 10px">
                                        <h2>Fill the form below to begin making money.</h2>
                                        <form data-toggle="validator" class="form-horizontal" role="form" method="post" action="">
                                            <?php if(isset($message_success)): ?>
                                                <div class="alert alert-success">
                                                    <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
                                                    <strong>Success!</strong> <?php echo $message_success; ?>
                                                </div>
                                            <?php endif ?>
                                            <?php if(isset($message_error)): ?>
                                                <div class="alert alert-danger">
                                                    <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
                                                    <strong>Oops!</strong> <?php echo $message_error; ?>
                                                </div>
                                            <?php endif;?>
                                            <div class="form-group">
                                                <div class="col-sm-12">
                                                    <input name="name" placeholder="Full Name" type="text" class="form-control" required>
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <div class="col-sm-12"><input name="email" placeholder="Email Address" type="email"  class="form-control" maxlength="50" required></div>
                                            </div>
                                            <div class="form-group">
                                                <div class="col-sm-12"><input name="phone" placeholder="080********" type="text" class="form-control" maxlength="11" required></div>
                                            </div>
                                            <div class="form-group">
                                                <div class="col-sm-12">
                                                    <div class="checkbox">
                                                        <label for="">
                                                            <input type="radio" name="interest" value="<?php echo $interest_no;?>" id=""/> I have not traded Forex
                                                        </label>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <div class="col-sm-12">
                                                    <div class="checkbox">
                                                        <label for="">
                                                            <input type="radio" name="interest" value="<?php echo $interest_yes;?>" id=""/> I have traded Forex
                                                        </label>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <center><div class="g-recaptcha" data-sitekey="6LcKDhATAAAAAF3bt-hC_fWA2F0YKKpNCPFoz2Jm"></div></center>
                                            </div>

                                            <div class="col-sm-12">
                                                <span style="color: #ffffff">*All fields are required</span>
                                            </div>
                                            <div class="form-group">
                                                <div class="col-sm-12">
                                                    <input name="sign_up" type="submit" class="btn btn-success btn-lg btn-group-justified"  value="Get Me Started Now!"/>
                                                </div>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="super-shadow page-top-section">
                        <div class="row ">
                            <div class="col-sm-12">
                                <h3 class="text-center"><strong>Promotions and Campaigns from InstaFxNg</strong></h3>
                                <p>InstaFxNg offers great opportunities and rewards for Forex Traders in addition to the best trading conditions.
                                    We invite you to delight yourself by participating in any of our promotions and get a chance to be a winner.</p>
                            </div>
                        </div>
                    </div>

                    <div class="section-tint super-shadow">
                        <div class="row">
                            <div class="col-sm-12"><h5>InstaFxNg Point Based Loyalty Program and Rewards</h5></div>
                        </div>
                        <div class="row">
                            <div class="col-sm-4">
                                <a href="loyalty.php" title="click for full details" target="_blank"><img src="images\Loyalty_Points_Images\ilpr_landing_image.jpg" alt="" class="img-responsive" /></a>
                            </div>
                            <div class="col-sm-8">
                                <p>Make Up To $4, 200 and N1, 000, 000 Extra While You Take Your Normal Trades</p>
                                <p>Each funding transaction you make earn you points in our loyalty program, also
                                    every trade you execute earn you points in our loyalty program.</p>
                                <a class="btn btn-success" href="loyalty.php" title="click for full details" target="_blank">More Details</a>
                            </div>
                        </div>
                    </div>

                    <div class="section-tint super-shadow">
                        <div class="row">
                            <div class="col-sm-12"><h5>Instaforex Who's Your Valentine Contest</h5></div>
                        </div>
                        <div class="row">
                            <div class="col-sm-4">
                                <a href="https://instafxng.com/valentine_offer.php" title="click for full details" target="_blank"><img src="images/instafxng-valentine-2.png" alt="" class="img-responsive" /></a>
                            </div>
                            <div class="col-sm-8">
                                <p>Happy valentine. We love you!</p>
                                <p>Join the Instaforex Who’s your Valentine Contest.
                                    You stand a chance to emerge one of 3 winners that will walk away with a
                                    giveaway sum of $20. This means that 3 lucky winners are going to get $20
                                    each in this contest.</p>
                                <a class="btn btn-success" href="https://instafxng.com/valentine_offer.php" title="click for full details" target="_blank">More Details</a>
                            </div>
                        </div>
                    </div>
                    
                    <div class="section-tint super-shadow">
                        <div class="row">
                            <div class="col-sm-12"><h5>Hyundai Sonata for Nigerian Forex Trader</h5></div>
                        </div>
                        <div class="row">
                            <div class="col-sm-4">
                                <a href="forex_hyundai_sonata.php" title="click for full details" target="_blank"><img src="images/instaforex-hyundai-sonata.jpg" alt="" class="img-responsive" /></a>
                            </div>
                            <div class="col-sm-8">
                                <p>The international broker InstaForex conducts a new Hyundai Sonata Campaign 
                                    among its Nigerian clients with the capital prize - Hyundai Sonata automobile.</p>
                                <a class="btn btn-success" href="forex_hyundai_sonata.php" title="click for full details" target="_blank">More Details</a>
                            </div>
                        </div>
                    </div>
                    
                    <div class="section-tint super-shadow">
                        <div class="row">
                            <div class="col-sm-12"><h5>BMW X6 from Instaforex</h5></div>
                        </div>
                        <div class="row">
                            <div class="col-sm-4">
                                <a href="forex_bmw_contest.php" title="click for full details" target="_blank"><img src="images/instaforex-bmw-x6.jpg" alt="" class="img-responsive" /></a>
                            </div>
                            <div class="col-sm-8">
                                <p>Be the winner of Instaforex sporty and stylish car 
                                    in the new super campaign Win BMW X6 from InstaForex, with the main prize – powerful luxury cross-over BMW X6.</p>
                                <a class="btn btn-success" href="forex_bmw_contest.php" title="click for full details" target="_blank">More Details</a>
                            </div>
                        </div>
                    </div>
                    
                    <div class="section-tint super-shadow">
                        <div class="row">
                            <div class="col-sm-12"><h5>Instaforex 100% LFC Partnership Bonus</h5></div>
                        </div>
                        <div class="row">
                            <div class="col-sm-4">
                                <a href="forex_100bonus.php" title="click for full details" target="_blank"><img src="images/instaforex-100bonus.jpg" alt="" class="img-responsive" /></a>
                            </div>
                            <div class="col-sm-8">
                                <p>Within the framework of partnership with eighteen Premier League titles holder - the 
                                    legendary Liverpool FC, InstaForex is pleased to announce a new campaign! The 100% bonus offer.</p>
                                <a class="btn btn-success" href="forex_100bonus.php" title="click for full details" target="_blank">More Details</a>
                            </div>
                        </div>
                    </div>
                    
                    <div class="section-tint super-shadow">
                        <div class="row">
                            <div class="col-sm-12"><h5>Spring in Liverpool by Instaforex</h5></div>
                        </div>
                        <div class="row">
                            <div class="col-sm-4">
                                <a href="spring_in_liverpool.php" title="click for full details" target="_blank"><img src="images/instaforex-spring-liverpool.jpg" alt="" class="img-responsive" /></a>
                            </div>
                            <div class="col-sm-8">
                                <p>We are happy to announce the launch of a new contest - Spring in Liverpool! 
                                    The main prizes are the VIP tickets for a home game of our partners from 
                                    Liverpool FC and a trip to the Beatles motherland.</p>
                                <a class="btn btn-success" href="spring_in_liverpool.php" title="click for full details" target="_blank">More Details</a>
                            </div>
                        </div>
                    </div>
                    
                    <div class="section-tint super-shadow">
                        <div class="row">
                            <div class="col-sm-12"><h5>Hyundai Accent for Nigerian Forex Trader</h5></div>
                        </div>
                        <div class="row">
                            <div class="col-sm-4">
                                <a href="hyundai_accent.php" title="click for full details" target="_blank"><img src="images/hyundai_en.jpg" alt="" class="img-responsive" /></a>
                            </div>
                            <div class="col-sm-8">
                                <p>We are glad to announce that Hyundai Accent from InstaForex campaign is over. 
                                    On August 14, 2015 the Hyundai-number was fixed on the basis of five digits of forex majors as of 23:59. </p>
                                <a class="btn btn-success" href="hyundai_accent.php" title="click for full details" target="_blank">More Details</a>
                            </div>
                        </div>
                    </div>
                    
                    <div class="section-tint super-shadow">
                        <div class="row">
                            <div class="col-sm-12"><h5>Instaforex KIA Picanto for Nigerian Trader</h5></div>
                        </div>
                        <div class="row">
                            <div class="col-sm-4">
                                <a href="kia_picanto.php" title="click for full details" target="_blank"><img src="images/kia-picanto.jpg" alt="" class="img-responsive" /></a>
                            </div>
                            <div class="col-sm-8">
                                <p>The brand new city car KIA Picanto was awarded to the winner of the contest during the Instaforex Annual Currency Trading Conference in Lagos, Nigeria.</p>
                                <a class="btn btn-success" href="kia_picanto.php" title="click for full details" target="_blank">More Details</a>
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