<?php
require_once 'init/initialize_general.php';
$thisPage = "";

$get_params = allowed_get_params(['x', 'c']);

$user_code_encrypted = $get_params['x'];
$user_code = decrypt_ssl(str_replace(" ", "+", $user_code_encrypted));
$user_code = preg_replace("/[^A-Za-z0-9 ]/", '', $user_code);

$reset_code = $get_params['c'];

if(!isset($get_params['x']) OR !isset($get_params['c'])) {
    redirect_to("./");
} else {
    //TODO: confirm that the passcode reset did not pass 1 hour
    $query = "SELECT * FROM user WHERE user_code = '$user_code' AND reset_code = '$reset_code' LIMIT 1";
    $result = $db_handle->runQuery($query);

    if($db_handle->numOfRows($result) > 0) {
        $allow_reset = true;
    } else {
        redirect_to("./");
    }
}

if (isset($_POST['submit_code']) && !empty($_POST['submit_code'])) {
    $client_operation = new clientOperation();

    $pass_code = $db_handle->sanitizePost($_POST['pass_code']);
    $pass_code_again = $db_handle->sanitizePost($_POST['pass_code_again']);

    $user_code_encrypted = $db_handle->sanitizePost($_POST['uuc']);
    $user_code = decrypt_ssl(str_replace(" ", "+", $user_code_encrypted));
    $user_code = preg_replace("/[^A-Za-z0-9 ]/", '', $user_code);
    
    if(empty($pass_code) || empty($pass_code_again)) {
        $message_error = "Please fill all the fields and try again.";
    } elseif($pass_code != $pass_code_again) {
        $message_error = "Passcode did not match, please try again.";
    } else {
        // Check for SMS Code expiration
        if($client_operation->update_user_password($user_code, $pass_code)) {
            $message_success ="Your passcode has been reset, use the menu to continue.<br>";
            $message_success .="<a href=\"deposit_funds.php\">Click Here to Fund Your Account</a> or <a href=\"withdraw_funds.php\">Click Here to Withdraw From Your Account.</a>";
        } else {
            $message_error = "Passcode reset failed, please try again or contact our support department.";
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
        <title>Instaforex Nigeria | Password Reset</title>
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
                                <h4><strong>Password Reset</strong></h4>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-sm-12">
                                <?php require_once 'layouts/feedback_message.php'; ?>

                                <?php if($allow_reset) { ?>
                                    <p>Create a new secured PASSCODE between 4 - 8 characters long, then click the submit button</p>
                                    <p><strong>Note:</strong> Do not enter more than 8 characters, else the system will use only the first 8 characters.</p>
                                    <form data-toggle="validator" class="form-horizontal" role="form" method="post" action="<?php echo $_SERVER['REQUEST_URI']; ?>">
                                        <!-- The next line does nothing other than deceive the browsers
                                        that auto-fill password fields - This will be filled but we do not need it -->
                                        <input name="control_password" type="password" style="display: none">
                                        <input name="uuc" type="hidden" value="<?php if(isset($user_code_encrypted)) { echo $user_code_encrypted; } ?>">
                                        <div class="form-group">
                                            <label class="control-label col-sm-3" for="pass_code">New Pass Code:</label>
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
                                            <div class="col-sm-offset-3 col-sm-9"><input name="submit_code" type="submit" class="btn btn-success" value="Reset Passcode" /></div>
                                        </div>
                                    </form>
                                <?php } ?>

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