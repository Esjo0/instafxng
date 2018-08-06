<?php
require_once '../init/initialize_general.php';
require_once '../init/initialize_partner.php';

$thisPage = "Home";

$page_requested = "";

if (isset($_POST['partner_signup'])) {

    if(isset($_POST['g-recaptcha-response']) && !empty($_POST['g-recaptcha-response'])) {
        $secret = '6LcKDhATAAAAALn9hfB0-Mut5qacyOxxMNOH6tov';
        $verifyResponse = file_get_contents('https://www.google.com/recaptcha/api/siteverify?secret=' . $secret . '&response=' . $_POST['g-recaptcha-response']);
        $responseData = json_decode($verifyResponse);
        if ($responseData->success) {

            foreach ($_POST as $key => $value) {
                $_POST[$key] = $db_handle->sanitizePost(trim($value));
            }
            extract($_POST);

            if(
                empty($your_email) ||
                empty($your_first_name) ||
                empty($your_last_name) ||
                empty($your_phone_number) ||
                empty($your_address) ||
                empty($your_city) ||
                empty($your_state)
            ) {
                $message_error = "Kindly fill all required fields";
            } elseif(!check_email($your_email)) {
                $message_error = "You have entered an invalid email";
            } elseif($partner_object->email_phone_is_duplicate($email, $phone)) {
                $message_error = "You have entered a duplicate email or phone number, please try again.";
            } else {
                $new_partner = $partner_object->new_partner($your_first_name, $your_last_name, $your_email, $your_phone_number, $your_address, $your_city, $your_state, $your_email_address);

                if($new_partner) {
                    $message_success = $new_partner . " You have successfully registered, please check your email for further instructions.";
                } else {
                    $message_error = "An error occurred, your registration failed, please try again.";
                }
            }

        }
    }
}

switch($page_requested) {
    case '': $signup_request_email_php = true; break;
    case 'signup_request_email_php': $signup_request_email_php = true; break;
    default: $signup_request_email_php = true;
}

$all_states = $system_object->get_all_states();

?>
<!DOCTYPE html>
<html lang="en">
    <head>
        <base target="_self">
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>Instaforex Nigeria | Instafxng Partnership Program</title>
        <meta name="title" content="Instaforex Nigeria | Instafxng Partnership Program" />
        <meta name="keywords" content="instaforex, forex trading in nigeria, forex seminar, forex trading seminar, how to trade forex, trade forex, instaforex nigeria">
        <meta name="description" content="Instaforex, a multiple award winning international forex broker is the leading Forex broker in Nigeria, open account and enjoy forex trading with us.">
        <?php require_once 'layouts/head_meta.php'; ?>

        <style>

            .alert {
                margin: 10px 0 10px 0 !important;
                padding: 10px !important;
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
                <div id="main-body-content-area" class="col-md-12">
                    
                    <!-- Unique Page Content Starts Here
                    ================================================== -->
                    <div class="row">
                        <div class="col-sm-12">
                            <img src="images/partner_pc.png" alt="" class="img-responsive center-block" width="668px" height="226px" />
                        </div>
                        
                    </div>
                    
                    <div class="section-tint super-shadow">
                        <div class="row">
                            <div class="col-sm-12">
                                <h3>Instafxng Partnership Registration</h3>
                                <p>Our partnership program offers you a great opportunity to earn from the
                                Forex market by just introducing new clients. You earn for life for every new
                                client you refer as long as they are active.</p>
                            </div>
                        </div>

                        <!-- FORM CONTAINER -->
                        <div class="container-fluid form-container-fluid">
                            <div class="row">
                                <div class="col-sm-12">

                                    <div class="col-sm-6"></div>

                                    <div class="col-sm-6">
                                        <?php require_once 'layouts/feedback_message.php'; ?>
                                        <h5>Sign Up</h5>
                                        <p>Fill the form below to sign up for our partnership program.</p>

                                        <form data-toggle="validator" class="form-horizontal" role="form" method="post" action="">
                                            <div class="form-group">
                                                <label class="control-label" for="your_email">Email Address:*</label>
                                                <input name="your_email" type="text" class="form-control" id="your_email" required>
                                            </div>

                                            <div class="form-group">
                                                <label class="control-label" for="your_first_name">First Name:*</label>
                                                <input name="your_first_name" type="text" class="form-control" id="your_first_name" required>
                                            </div>

                                            <div class="form-group">
                                                <label class="control-label" for="your_middle_name">Middle Name:</label>
                                                <input name="your_middle_name" type="text" class="form-control" id="your_middle_name">
                                            </div>

                                            <div class="form-group">
                                                <label class="control-label" for="your_last_name">Last Name:*</label>
                                                <input name="your_last_name" type="text" class="form-control" id="your_last_name" required>
                                            </div>

                                            <div class="form-group">
                                                <label class="control-label" for="your_phone_number">Phone Number:*</label>
                                                <input name="your_phone_number" type="text" class="form-control" maxlength="11" id="your_phone_number" required>
                                            </div>

                                            <div class="form-group">
                                                <label class="control-label" for="your_address">Address:*</label>
                                                <textarea name="your_address" class="form-control" rows="3" id="your_address" required></textarea>
                                            </div>
                                            <div class="form-group">
                                                <label class="control-label" for="your_city">City:*</label>
                                                <input name="your_city" type="text" class="form-control" id="your_city" required>
                                            </div>
                                            <div class="form-group">
                                                <label class="control-label" for="your_state">Your State:*</label>
                                                <select name="your_state" class="form-control" id="your_state" required>
                                                    <option value="" selected>Select State</option>
                                                    <?php foreach($all_states as $key => $value) { ?>
                                                        <option value="<?php echo $value['state_id']; ?>"><?php echo $value['state']; ?></option>
                                                    <?php } ?>
                                                </select>
                                            </div>
                                            <div class="form-group"><div class="g-recaptcha" data-sitekey="6LcKDhATAAAAAF3bt-hC_fWA2F0YKKpNCPFoz2Jm"></div></div>
                                            <div class="form-group">
                                                <input name="partner_signup" type="submit" class="btn btn-success" value="Register" />
                                            </div>
                                        </form>
                                        <p class="text-center">Already have a Partnership Account? <a href="partner/login.php" title="Log in to your IPP">Login here</a></p>

                                    </div>

                                </div>
                            </div>
                        </div>

                    </div>
                    <!-- Unique Page Content Ends Here
                    ================================================== -->
                    
                </div>
                
            </div>
            <div class="row no-gutter">
            <?php require_once 'layouts/footer.php'; ?>
            </div>
        </div>
        <script src='https://www.google.com/recaptcha/api.js'></script>
    </body>
</html>