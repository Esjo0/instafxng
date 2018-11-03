<?php
require_once '../init/initialize_client.php';

$get_params = allowed_get_params(['id']);
$email_forward = $get_params['id'];

if(isset($email_forward)) {
    $message_info = "We could not find your profile, but you can quickly create one below and proceed.";
}

if (isset($_POST['submit'])) {
//    if(isset($_POST['g-recaptcha-response']) && !empty($_POST['g-recaptcha-response'])) {
//        $secret = '6LcKDhATAAAAALn9hfB0-Mut5qacyOxxMNOH6tov';
//        $verifyResponse = file_get_contents('https://www.google.com/recaptcha/api/siteverify?secret=' . $secret . '&response=' . $_POST['g-recaptcha-response']);
//        $responseData = json_decode($verifyResponse);
//
//        if ($responseData->success) {

            foreach($_POST as $key => $value) {
                $_POST[$key] = $db_handle->sanitizePost(trim($value));
            }

            extract($_POST);

            if(empty($full_name) || empty($email_address) || empty($phone_number)) {
                $message_error = "All fields must be filled, please try again";
            } elseif (!check_email($email_address)) {
                $message_error = "You have supplied an invalid email address, please try again.";
            } elseif (check_number($phone_number) != 5) {
                $message_error = "The supplied phone number is invalid.";
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

                $query = "INSERT INTO free_training_campaign (first_name, last_name, email, phone, campaign_period) VALUE ('$first_name', '$last_name', '$email_address', '$phone_number', '2')";
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

                    redirect_to('https://instafxng.com/forex-income/thank-you/thank-you.php');
                } else {
                    $message_error = "An error occurred, please try again.";
                }
            }
//        } else {
//            $message_error = "You did not pass the robot verification check, please try again.";
//        }
//    } else {
//        $message_error = "Kindly take the robot verification test. Try again.";
//    }
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
                                        <div class="col-sm-9 col-lg-7"><input name="full_name" placeholder="First name and Surname" type="text" class="form-control" id="full_name" maxlength="120" required></div>
                                    </div>
                                    <div class="form-group">
                                        <label class="control-label col-sm-3" for="phone">Phone Number:</label>
                                        <div class="col-sm-9 col-lg-7">
                                            <input name="phone_number" type="text" class="form-control" id="phone" data-minlength="11" maxlength="11" required>
                                            <div class="help-block">Example - 08031234567</div>
                                        </div>
                                    </div>

                                    <div class="form-group"><div class="col-sm-offset-3 col-sm-9 g-recaptcha" data-sitekey="6LcKDhATAAAAAF3bt-hC_fWA2F0YKKpNCPFoz2Jm"></div></div>

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