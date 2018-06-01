<?php
require_once 'init/initialize_general.php';
$thisPage = "Careers";

$get_params = allowed_get_params(['c']);
$job_code_encrypted = $get_params['c'];

$job_code = decrypt(str_replace(" ", "+", $job_code_encrypted));
$job_code = preg_replace("/[^A-Za-z0-9 ]/", '', $job_code);

if(!$obj_careers->is_valid_job_code($job_code)) {
    header('Location: careers.php');
    exit;
}

if(isset($_POST['careers_register'])) {
    if(isset($_POST['g-recaptcha-response']) && !empty($_POST['g-recaptcha-response'])) {
        $secret = '6LcKDhATAAAAALn9hfB0-Mut5qacyOxxMNOH6tov';
        $verifyResponse = file_get_contents('https://www.google.com/recaptcha/api/siteverify?secret='.$secret.'&response='.$_POST['g-recaptcha-response']);
        $responseData = json_decode($verifyResponse);
        if($responseData->success) {
            foreach ($_POST as $key => $value) {
                $_POST[$key] = $db_handle->sanitizePost(trim($value));
            }

            extract($_POST);

            $log_biodata = $obj_careers->set_new_career_user($first_name, $last_name, $other_names, $email_add, $phone_no, $sex,
                $marital_status, $state_of_origin, $dob, $address, $state_of_residence, $client_job_code);

            if($log_biodata) {
                redirect_to('careers_login.php?m=true');
            } else {
                $message_error = "Something went wrong, your data could not be saved. Please try again or contact support.";
            }
        } else {
            $message_error = "You did not answer the robot test correctly, please try again.";
        }
    } else {
        $message_error = "Kindly confirm that you are not a robot";
    }
}

$job_detail = $obj_careers->get_job_by_code($job_code);

$all_states = $system_object->get_all_states();

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <base target="_self">
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Instaforex Nigeria | Careers and Job Opportunity</title>
    <meta name="title" content="Instaforex Nigeria | Careers and Job Opportunity" />
    <meta name="keywords" content="" />
    <meta name="description" content="" />
    <?php require_once 'layouts/head_meta.php'; ?>
    <style>
        .container-fluid { max-width: 1020px !important; }
        hr { max-width: 1020px !important; }
        #footer { max-width: 1020px !important; margin: 0 auto; background: #ddd url(../../images/footerbg.png); padding: 5px 10px 10px 10px; }
    </style>
</head>

<body>
    <!-- Header Section: Logo and Live Chat  -->
    <header id="header">
        <div class="container-fluid no-gutter masthead">
            <div class="row">
                <div id="main-logo" class="col-sm-12 col-md-5">
                    <a href="./" target="_blank"><img src="images/ifxlogo.png" alt="Instaforex Nigeria Logo" /></a>
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

                <div class="super-shadow page-top-section">
                    <div class="row ">
                        <div class="col-sm-7">
                            <h2>Careers and Job Opportunity</h2>
                            <p>If you like scaling new heights and you have the drive for excellence, you
                                will fit into our team.</p>
                            <p>For 7 years, we have pushed the boundaries of service delivery in the Forex
                                Trading industry in Nigeria. Do you have what it takes to be on our team?</p>
                        </div>

                        <div class="col-sm-5">
                            <img src="images/instafxng-careers.jpg" alt="" class="img-responsive" />
                        </div>
                    </div>
                </div>

                <div class="section-tint super-shadow">
                    <div class="row">
                        <div class="col-sm-12">
                            <div class="row"><div class="col-sm-12"><p><a href="careers.php" class="btn btn-default" title="Job List"><i class="fa fa-arrow-circle-left"></i> Go Back - Job List</a></p></div></div>

                            <?php require_once 'layouts/feedback_message.php'; ?>

                            <p>Fill the form below to register an account and apply for your chosen position. Ensure
                                you fill in your true information as wrong information will lead to disqualification.</p>

                            <form data-toggle="validator" class="form-horizontal" role="form" method="post" action="<?php echo $_SERVER['REQUEST_URI']; ?>">
                                <input name="client_job_code" type="hidden" value="<?php echo $job_code; ?>" />

                                <div class="form-group">
                                    <label class="control-label col-sm-3" for="last_name">Last Name:</label>
                                    <div class="col-sm-9 col-lg-5">
                                        <input name="last_name" type="text" class="form-control" id="last_name" required />
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="control-label col-sm-3" for="first_name">First Name:</label>
                                    <div class="col-sm-9 col-lg-5">
                                        <input name="first_name" type="text" class="form-control" id="first_name" required />
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="control-label col-sm-3" for="other_names">Other Names:</label>
                                    <div class="col-sm-9 col-lg-5">
                                        <input name="other_names" type="text" class="form-control" id="other_names" required />
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="control-label col-sm-3" for="email_add">Email Address:</label>
                                    <div class="col-sm-9 col-lg-5">
                                        <input name="email_add" type="text" class="form-control" id="email_add" required />
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="control-label col-sm-3" for="phone_no">Phone Number:</label>
                                    <div class="col-sm-9 col-lg-5">
                                        <input name="phone_no" type="text" class="form-control" id="phone_no" required />
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="control-label col-sm-3" for="sex">Sex:</label>
                                    <div class="col-sm-9 col-lg-5">
                                        <select name="sex" class="form-control" id="sex" required>
                                            <option value="">--- Select ---</option>
                                            <option value="M">Male</option>
                                            <option value="F">Female</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="control-label col-sm-3" for="marital_status">Marital Status:</label>
                                    <div class="col-sm-9 col-lg-5">
                                        <select name="marital_status" class="form-control" id="marital_status" required>
                                            <option value="">--- Select ---</option>
                                            <option value="S">Single</option>
                                            <option value="M">Married</option>
                                        </select>
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label class="control-label col-sm-3" for="state_of_origin">State of Origin:</label>
                                    <div class="col-sm-9 col-lg-5">
                                        <select name="state_of_origin" class="form-control" id="state_of_origin" >
                                            <option value="" selected>Select State</option>
                                            <?php foreach($all_states as $key => $value) { ?>
                                                <option value="<?php echo $value['state_id']; ?>"><?php echo $value['state']; ?></option>
                                            <?php } ?>
                                        </select>
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label class="control-label col-sm-3" for="dob">Date of Birth:</label>
                                    <div class="col-sm-9 col-lg-5">
                                        <div class='input-group date' id='dob1'>
                                            <input name="dob" type="text" class="form-control" id='dob' required />
                                            <span class="input-group-addon"><span class="glyphicon glyphicon-calendar"></span></span>
                                        </div>
                                        <span class="help-block">Format: (YYYY-MM-DD) e.g. 2016-12-25</span>
                                    </div>
                                    <script type="text/javascript">
                                        $(function () {
                                            $('#dob1').datetimepicker({
                                                format: 'YYYY-MM-DD'
                                            });
                                            $('#dob').datetimepicker({
                                                format: 'YYYY-MM-DD'
                                            });
                                        });
                                    </script>
                                </div>

                                <div class="form-group">
                                    <label class="control-label col-sm-3" for="address">Address:</label>
                                    <div class="col-sm-9 col-lg-7">
                                        <textarea name="address" class="form-control" cols="65" rows="3" id="address" required></textarea>
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label class="control-label col-sm-3" for="state_of_residence">State of Residence:</label>
                                    <div class="col-sm-9 col-lg-5">
                                        <select name="state_of_residence" class="form-control" id="state_of_residence" required>
                                            <option value="" selected>Select State</option>
                                            <?php foreach($all_states as $key => $value) { ?>
                                                <option value="<?php echo $value['state_id']; ?>"><?php echo $value['state']; ?></option>
                                            <?php } ?>
                                        </select>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="control-label col-sm-3" for="recaptcha">&nbsp;</label>
                                    <div class="col-sm-9 col-lg-5 g-recaptcha" data-sitekey="6LcKDhATAAAAAF3bt-hC_fWA2F0YKKpNCPFoz2Jm"></div>
                                </div>

                                <div class="form-group">
                                    <div class="col-sm-offset-3 col-sm-9"><input name="careers_register" type="submit" class="btn btn-success" value="Submit" /></div>
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
        <footer id="footer" class="super-shadow">
            <div class="container-fluid no-gutter">
                <div class="col-sm-12">
                    <div class="row">
                        <p class="text-center" style="font-size: 16px !important;">&copy; <?php echo date('Y'); ?>, All rights reserved. Instant Web-Net Technologies Limited (www.instafxng.com)</p>
                    </div>
                </div>
            </div>
        </footer>

        <script src='https://www.google.com/recaptcha/api.js'></script>
        <script src="//cdnjs.cloudflare.com/ajax/libs/moment.js/2.9.0/moment-with-locales.js"></script>
        <script src="//cdn.rawgit.com/Eonasdan/bootstrap-datetimepicker/e8bddc60e73c1ec2475f827be36e1957af72e2ea/src/js/bootstrap-datetimepicker.js"></script>
    </body>
</html>