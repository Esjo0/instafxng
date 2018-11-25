<?php
require_once 'init/initialize_general.php';
$thisPage = "Promotion";
$bonus_operations = new Bonus_Operations();

$get_params = allowed_get_params(['bc']);
$bonus_code_encrypted = $get_params['bc'];
$bonus_code = decrypt_ssl(str_replace(" ", "+", $bonus_code_encrypted));
$bonus_code = preg_replace("/[^A-Za-z0-9 ]/", '', $bonus_code);
$package_details = $bonus_operations->get_package_by_code($bonus_code);
if(!$package_details || empty($package_details)) { redirect_to("bonus.php"); die();}
extract($package_details);

// This section processes - views/live_account_ilpr_reg.php
if(isset($_POST['live_account_ilpr_reg'])) {
    $page_requested = "live_account_ilpr_reg_php";

    $secret = '6LcKDhATAAAAALn9hfB0-Mut5qacyOxxMNOH6tov';
    $verifyResponse = file_get_contents('https://www.google.com/recaptcha/api/siteverify?secret='.$secret.'&response='.$_POST['g-recaptcha-response']);
    $responseData = json_decode($verifyResponse);

    $account_no = $db_handle->sanitizePost(trim($_POST['ifx_acct_no']));
    $full_name = $db_handle->sanitizePost(trim($_POST['full_name']));
    $email_address = $db_handle->sanitizePost(trim($_POST['email']));
    $phone_number = $db_handle->sanitizePost(trim($_POST['phone']));
    $bonus_code = $db_handle->sanitizePost(trim($_POST['bonus_code']));

    /*if(!$responseData->success) {
        $message_error = "You did not pass the robot verification, please try again.";
    } else*/if(empty($full_name) || empty($email_address) || empty($phone_number) || empty($account_no) || empty($bonus_code)) {
        $message_error = "All fields must be filled.";
    } elseif (!check_email($email_address)) {
        $message_error = "You have provided an invalid email address. Please try again.";
    } else {
        $log_new_ifxaccount = $bonus_operations->new_bonus_application($account_no, $full_name, $email_address, $phone_number, $bonus_code);
        if($log_new_ifxaccount) {
            $message_error = <<<MAIL
<div class="alert alert-success">
    <strong>Success!</strong> You have successfully completed your Instaforex Bonus Account opening . Note that your account details has been sent to your email address and your phone. Also, your bonus allocated shortly.
</div>

<p><strong>What Next?</strong></p>
<ol>
    <li><strong>Register for FREE Forex Education:</strong> Learn the basics of forex trading through demo-trading up to the point of profitable live trading. Take this one time opportunity, <a href="fxacademy/welcome/" title="Register for FREE Forex Education">Click here to register NOW</a>.</li>
    <li><strong>Fund your Instaforex Account:</strong> You can start trading and making profits 
        from Forex immediately. <a href="deposit_funds.php" title="Click here to fund your account">Click here to fund your account</a>, it is very fast and easy.</li>
</ol>
<p>If you encounter any challenge or if you simply want to make an inquiry, please contact our <a href="contact_info.php" target="_blank" title="Instafxng Support Department">support department.</a></p>
MAIL;
        } else {
            $message_error = "This account could not be enrolled here, please contact support for more details.";
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
        <title>Instaforex Nigeria | <?php echo $bonus_title ?></title>
        <meta name="title" content="Instaforex Nigeria | <?php echo $bonus_title ?>" />
        <meta name="keywords" content="" />
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
                    <div class="super-shadow page-top-section">
                        <div class="row ">
                            <div class="col-sm-6">
                                <h1><?php echo $bonus_title ?></h1>
                                <p><?php echo $bonus_desc ?></p>
                            </div>
                            <div class="col-sm-6">
                                <img src="images/bonus_packages/<?php echo $bonus_img ?>" alt="<?php echo $bonus_title ?>" class="img-responsive" />
                            </div>
                        </div>
                    </div>
                    
                    
                    <div class="section-tint super-shadow">
                        <div class="row">
                            <div class="col-sm-12 text-danger">
                                <h4><strong>Bonus Details</strong></h4>
                            </div>
                            <div class="col-sm-12">
                                <?php require_once 'layouts/feedback_message.php'; ?>
                            </div>
                        </div>
                        
                        <div class="row">
                            <div class="col-sm-12">
                                <a href="live_account.php" class="btn btn-group-justified btn-sm btn-info btn-sm">Open A Live Instaforex Account Now!</a>
                                <?php echo $bonus_details ?>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-sm-12">
                                <form data-toggle="validator" class="form-horizontal" role="form" method="post" action="<?php echo $_SERVER['REQUEST_URI']; ?>">
                                    <div class="form-group">
                                        <label class="control-label col-sm-3" for="full_name">Full Name:</label>
                                        <div class="col-sm-9 col-lg-5">
                                            <input placeholder="First name and Surname" name="full_name" type="text" class="form-control" id="full_name" required>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="control-label col-sm-3" for="email">Email Address:</label>
                                        <div class="col-sm-9 col-lg-5">
                                            <input name="email" type="text" class="form-control" id="email" required>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="control-label col-sm-3" for="phone">Phone Number:</label>
                                        <div class="col-sm-9 col-lg-5">
                                            <input name="phone" type="text" class="form-control" id="phone" required>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="control-label col-sm-3" for="ifx_acct_no">Instaforex Account Number:</label>
                                        <div class="col-sm-9 col-lg-5">
                                            <input name="ifx_acct_no" type="text" class="form-control" id="ifx_acct_no" required>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <input type="hidden" name="bonus_code" value="<?php echo $bonus_code ?>" id="bonus_code" />
                                    </div>


                                    <div class="form-group">
                                        <label class="control-label col-sm-3" for="recaptcha">&nbsp;</label>
                                        <div class="col-sm-9 col-lg-5 g-recaptcha" data-sitekey="6LcKDhATAAAAAF3bt-hC_fWA2F0YKKpNCPFoz2Jm"></div>
                                    </div>
                                    <div class="form-group">
                                        <div class="col-sm-offset-3 col-sm-9"><input name="live_account_ilpr_reg" type="submit" class="btn btn-success" value="Submit" /></div>
                                    </div>
                                </form>
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