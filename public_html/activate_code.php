<?php
require_once 'init/initialize_general.php';
$thisPage = "";

if(isset($_GET['uuc'])) {
    $user_code_encrypted = $_GET['uuc'];
} else {
    redirect_to("./");
}

if (isset($_POST['submit_code']) && !empty($_POST['submit_code'])) {
    $client_operation = new clientOperation();

    $pass_code = $db_handle->sanitizePost($_POST['pass_code']);
    $pass_code_again = $db_handle->sanitizePost($_POST['pass_code_again']);
    $sms_code = $db_handle->sanitizePost($_POST['sms_code']);
    
    $user_code_encrypted = $db_handle->sanitizePost($_POST['uuc']);
    $user_code = dec_enc('decrypt',  $user_code_encrypted));
    
    
    if(empty($pass_code) || empty($sms_code)) {
        $message_error = "Please fill all the fields and try again.";
    } elseif($pass_code != $pass_code_again) {
        $message_error = "Passcode did not match, please try again.";
    } elseif(!$client_operation->authenticate_smscode($user_code, $sms_code)) {
        $message_error = "You have entered an incorrect sms code."; // Factor in resending of code here <a href=\"resendactivcode.php?dx=$email\">Click Here to resend activation code</a><br>
    } else {
        //TODO: Check for SMS Code expiration
        if($client_operation->update_user_password($user_code, $pass_code)) {
            $message_success ="Your passcode activation was successful, use the menu to continue.<br>";
            $message_success .="<a href=\"deposit_funds.php\">Click Here to Fund Your Account</a> or <a href=\"withdraw_funds.php\">Click Here to Withdraw From Your Account.</a>";
        } else {
            $message_error = "Passcode activation failed, please try again or contact our support department.";
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
        <title>Instaforex Nigeria | Account Activation</title>
        <meta name="title" content=" " />
        <meta name="keywords" content=" ">
        <meta name="description" content=" ">
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
                    
                    <div class="section-tint super-shadow">
                        <div class="row">
                            <div class="col-sm-12 text-danger">
                                <h4><strong>Account Activation</strong></h4>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-sm-12">
                                <?php require_once 'layouts/feedback_message.php'; ?>
                                
                                <p>Create a secured PASSCODE (4 - 8 characters) for yourself, Enter the SMS code you received on your mobile phone, then click the "Activate Pass Code" button</p>
                                <form data-toggle="validator" class="form-horizontal" role="form" method="post" action="<?php echo $_SERVER['REQUEST_URI']; ?>">
                                    <!-- The next line does nothing other than deceive the browsers 
                                    that auto-fill password fields - This will be filled but we do not need it -->
                                    <input name="control_password" type="password" style="display: none">
                                    <input name="uuc" type="hidden" value="<?php if(isset($user_code_encrypted)) { echo $user_code_encrypted; } ?>">
                                    <div class="form-group">
                                        <label class="control-label col-sm-3" for="pass_code">Create Pass Code:</label>
                                        <div class="col-sm-9 col-lg-5">
                                            <input name="pass_code" type="password" class="form-control" id="pass_code" value="" data-minlength="4" maxlength="8"  required="required">
                                            <div class="help-block">Create PASSCODE (4 - 8 characters)</div>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="control-label col-sm-3" for="pass_code_again">Re-enter Pass Code:</label>
                                        <div class="col-sm-9 col-lg-5">
                                            <input name="pass_code_again" type="password" class="form-control" id="pass_code_again" value="" data-minlength="4" maxlength="8"  required="required">
                                            <div class="help-block">Re-enter PASSCODE (4 - 8 characters)</div>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="control-label col-sm-3" for="sms_code">SMS Code:</label>
                                        <div class="col-sm-9 col-lg-5">
                                            <input name="sms_code" type="text" class="form-control" id="sms_code" value="" required="required">
                                            <div class="help-block">Code sent to your phone</div>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <div class="col-sm-offset-3 col-sm-9"><input name="submit_code" type="submit" class="btn btn-success" value="Activate Pass Code" /></div>
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