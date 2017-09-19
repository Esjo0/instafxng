<?php
require_once '../init/initialize_client.php';

$get_params = allowed_get_params(['id']);
$email_forward = $get_params['id'];

if(isset($email_forward)) {
    $message_info = "We could not find your profile, but you can quickly create one below and proceed.";
}

if (isset($_POST['submit'])) {
    foreach($_POST as $key => $value) {
        $_POST[$key] = $db_handle->sanitizePost(trim($value));
    }

    extract($_POST);

    if(empty($full_name) || empty($email_address) || empty($phone_number)) {
        $message_error = "All fields must be filled, please try again";
    } elseif (!check_email($email_address)) {
        $message_error = "You have supplied an invalid email address, please try again.";
    } else {

        $client_full_name = $full_name;
        $full_name = str_replace(".", "", $full_name);
        $full_name = ucwords(strtolower(trim($full_name)));
        $full_name = explode(" ", $full_name);

        if(count($full_name) == 3) {
            $last_name = trim($full_name[0]);

            if(strlen($full_name[2]) < 3) {
                $middle_name = trim($full_name[2]);
                $first_name = trim($full_name[1]);
            } else {
                $middle_name = trim($full_name[1]);
                $first_name = trim($full_name[2]);
            }

        } else {
            $last_name = trim($full_name[0]);
            $middle_name = "";
            $first_name = trim($full_name[1]);
        }

        if(empty($first_name)) {
            $first_name = $last_name;
            $last_name = "";
        }

        $query = "INSERT INTO free_training_campaign (first_name, last_name, email, phone) VALUE ('$first_name', '$last_name', '$email_address', '$phone_number')";
        $result = $db_handle->runQuery($query);
        $inserted_id = $db_handle->insertedId();

        if($result) {

            $assigned_account_officer = $system_object->next_account_officer();

            $query = "UPDATE free_training_campaign SET attendant = $assigned_account_officer WHERE free_training_campaign_id = $inserted_id LIMIT 1";
            $db_handle->runQuery($query);

            // create profile for this client
            $client_operation = new clientOperation();
            $log_new_client = $client_operation->new_user_ordinary($client_full_name, $email_address, $phone_number, $assigned_account_officer);
            //...//

            $subject = "Welcome to the world of Money making!";
            $body =
                <<<MAIL
                <div style="background-color: #F3F1F2">
    <div style="max-width: 80%; margin: 0 auto; padding: 10px; font-size: 14px; font-family: Verdana;">
        <img src="https://instafxng.com/images/ifxlogo.png" />
        <hr />
        <div style="background-color: #FFFFFF; padding: 15px; margin: 5px 0 5px 0;">
            <p>Hi $first_name,</p>

            <p>I wanted to take a second to welcome you and to let you in on all the
                benefits you are going to enjoy being with us.</p>
            <p>My name is Bunmi, Client Relationship Manager at InstaForex Nigeria for over
            six years.</p>
            <p>You know, I'm truly excited and grateful that you've decided to join over
                seven thousands of Nigerians making consistent income from the Forex market
                during this economic recession Nigeria is undergoing.</p>
            <p>This is going to be something you haven't experienced before...</p>
            <p>Because you will be taught all you need to know to be profitable in Forex
                and you will be guided all the way. Below are a few of the things you are going
                to learn:</p>
            <ul>
                <li>How to trade profitably</li>
                <li>Money Management</li>
                <li>How to remain profitable while making money</li>
                <li>How to conquer greed</li>
            </ul>
            <p>But here's the thing...</p>
            <p>Remember... this is designed to teach you how to get to where you want to
                be (making consistent income in this economy meltdown).</p>
            <p>I believe each of these lessons are vital components for making money in
                this economy...</p>
            <p>So, here's what you can expect from me...</p>
            <p>Very shortly you're going to receive a call welcoming you on board and
                getting to know a little bit of you.</p>
            <p>Subsequently you will receive Emails.</p>
            <p>These emails will continue to educate you on how to trade and keep growing
                your Profits. (I promise not to disturb you with my mails).</p>
            <p>These are only for you, our subscriber.</p>
            <p>Sound fair? GOOD!</p>
            <p>Here's what you need to do now to get started...</p>
            <p><strong>STEP 1:</strong> Whitelist and prioritize all emails from Instafxng</p>
            <p>This is important!</p>
            <p>Not only will you receive updates about new articles on the blog, you'll
                also receive notifications about new projects we are working on, exclusive
                interviews and all the 'subscriber only' content I just told you about.</p>
            <p>But if my emails aren't getting through to you, you will miss these important
                updates and you won't receive the full benefits of being a subscriber.</p>
            <p>So please follow this quick one-step guide to make sure nothing slips through
                the cracks:</p>
            <p>1) If you are a Gmail user or you use any other web-based email that filters
                broadcasts away from your main inbox, be sure to 'drag' any emails from
                'Instafxng' into your Priority Inbox. (Again, you don't want to miss
                something.)</p>
            <p><strong>STEP 2:</strong> Take two-seconds and join the InstaForex Nigeria
                Facebook page, as this will be our primary method of sending signals, and
                again you won't want to miss a thing:</p>
            <p><strong>Facebook: https://www.facebook.com/InstaForexNigeria/</strong></p>
            <p>You can also follow us on Twitter and Instagram:</p>
            <p>
                <strong>Twitter: <a href='https://twitter.com/instafxng'>@instafxng</a><br />
                    Instagram: <a href='https://www.instagram.com/instafxng/'>@instafxng</a>
                </strong>
            </p>

            <p><strong>STEP 3:</strong> In case you haven't commenced your free
            Forex training at our Forex Profit Academy, follow the link below to start now and be one of
            the Nigerians thriving well in this unfriendly economy.</p>
            <p style='text-align: center'><strong><a href='https://instafxng.com/fxacademy/'>Click here to start now</a></strong><br /><br />
            or copy and paste the URL below in your address bar<br /><br />
            https://instafxng.com/fxacademy/</p>

            <p>If you need to get in touch with me directly, you can simply reply this mail</p>
            <p>Do you have any question or any inquiry? You can call us on any of our help
                desk lines 08182045184 or 08083956750</p>
            <p>Our help desk lines are always available from Monday through Fridays between
                9:00 a.m - 5:00 p.m</p>

            <br /><br />
            <p>Talk soon.</p>
            <p>Bunmi,<br />
                Client Relationship Manager,<br />
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
                <p><strong>Lekki Office Address:</strong> Block A3, Suite 508/509 Eastline Shopping Complex, Opposite Abraham Adesanya Roundabout, along Lekki - Epe expressway, Lagos State.</p>
                <p><strong>Office Number:</strong> 08028281192</p>
                <br />
            </div>
            <div style="font-size: 10px !important; padding: 15px; text-align: center;">
                <p>This email was sent to you by Instant Web-Net Technologies Limited, the
                    official Nigerian Representative of Instaforex, operator and administrator
                    of the website www.instafxng.com</p>
                <p>To ensure you continue to receive special offers and updates from us,
                    please add support@instafxng.com to your address book. You may click
                    <a href='https://instafxng.com/unsubscribe.php'>unsubscribe</a> if you wish
                    to stop receiving newsletter emails and other special promotions, offers
                    and complementary gifts.</p>
            </div>
        </div>
    </div>
</div>
MAIL;

            $from_name = "Bunmi - InstaFxNg";
            $system_object->send_email($subject, $body, $email_address, $first_name, $from_name);

            redirect_to('https://instafxng.com/forex-income/thank_you.php');
        } else {
            $message_error = "An error occurred, please try again.";
        }
    }
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
                    <div class="section-tint-red super-shadow">
                        <div class="row">
                            <div class="col-sm-9 text-center">
                                <h4 class="white-text">Forex Profit Academy</h4>
                            </div>
                            <div class="col-sm-3 text-center">
                                <i id="special-i" class="fa fa-line-chart fa-fw white-text"></i>
                            </div>
                        </div>
                    </div>

                    <div id="main-container" class="section-tint super-shadow">

                        <div class="row">
                            <div class="col-sm-12">
                                <p><img src="images/training_course_banner.jpg" class="img-responsive center-block" alt="" /></p>

                                <blockquote>
                                    <span style="font-size: 0.9em">Money is plentiful for those who understand the simple laws which govern its acquisition.</span>
                                    <footer>George S. Clason</footer>
                                </blockquote>

                                <h4>Register a profile</h4>
                                <p>Fill the form below to gain access to the free Online Forex Trading Academy.</p>
                            </div>
                        </div>


                        <div class="row" style="max-width: 650px !important; margin: 0 auto">
                            <div class="col-sm-12">
                                <?php require_once 'layouts/feedback_message.php'; ?>

                                <!-- Registration Form -->
                                <form data-toggle="validator" class="form-horizontal" role="form" method="post" action="<?php echo $_SERVER['REQUEST_URI']; ?>">
                                    <div class="form-group">
                                        <label class="control-label col-sm-3" for="email">Email Address:</label>
                                        <div class="col-sm-9 col-lg-7"><input name="email_address" type="email" value="<?php if(isset($email_forward)) { echo $email_forward; } ?>" class="form-control" id="email" maxlength="50" required></div>
                                    </div>
                                    <div class="form-group">
                                        <label class="control-label col-sm-3" for="full_name">Full Name:</label>
                                        <div class="col-sm-9 col-lg-7"><input name="full_name" type="text" class="form-control" id="full_name" maxlength="120" required></div>
                                    </div>
                                    <div class="form-group">
                                        <label class="control-label col-sm-3" for="phone">Phone Number:</label>
                                        <div class="col-sm-9 col-lg-7"><input name="phone_number" type="text" class="form-control" id="phone" maxlength="11" required></div>
                                    </div>

                                    <div class="form-group">
                                        <div class="col-sm-offset-3 col-sm-9">
                                            <input name="submit" type="submit" class="btn btn-success btn-lg" value="Register!" />
                                        </div>
                                        <div class="col-sm-offset-3 col-sm-9">
                                            <br />
                                            <span>*All fields are required. <a href="fxacademy/login.php">Login here</a> if you already have a profile.</span>
                                        </div>
                                    </div>

                                </form>
                            </div>
                        </div>

                    </div>

                    <!-- Unique Page Content Ends Here
                    ================================================== -->
                </div>
            </div>


        </div>

        <?php require_once 'layouts/footer.php'; ?>
        <script src='https://www.google.com/recaptcha/api.js'></script>
    </body>
</html>