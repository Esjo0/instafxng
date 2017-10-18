<?php
require_once("../init/initialize_admin.php");
if (!$session_admin->is_logged_in()) {
    redirect_to("login.php");
}

$page_requested = "";

if(isset($_POST['deposit_confirm_ifx_account'])) {

    foreach($_POST as $key => $value) {
        $_POST[$key] = $db_handle->sanitizePost(trim($value));
    }

    extract($_POST);

    $Login = 189025; #Must be Changed
    $apiPassword = "gbedu_2018"; #Must be Changed
    $data = array("Login" => $Login, "Password" => $apiPassword);
    $data_string = json_encode($data);

    $ch = curl_init('http://client-api.instaforex.com/api/Authentication/RequestPartnerApiToken');

    curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
    curl_setopt($ch, CURLOPT_POSTFIELDS, $data_string);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json', 'Content-Length: ' . strlen($data_string)));
    $token = curl_exec($ch);
    curl_close($ch);

    $apiMethodUrl = 'partner/CheckReferral/189025/' . $ifx_acct; #possibly Must be Changed
    $parameters = ''; #possibly Must be Changed. Depends on the method param
    $ch = curl_init('http://client-api.instaforex.com/'.$apiMethodUrl.$parameters);
    curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "GET");
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true); # Turn it ON to get result to the variable
    curl_setopt($ch, CURLOPT_HTTPHEADER, array('passkey: '.$token));
    $result = curl_exec($ch);
    curl_close($ch);

    if(strtoupper($result) == "TRUE") {
        $message_success = "This is an ILPR Account";
    }

    if(strtoupper($result) == "FALSE") {
        $message_error = "This is a NON-ILPR Account";
    }

}

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <base target="_self">
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Instaforex Nigeria | Admin - Confirm IFX Account</title>
    <meta name="title" content="Instaforex Nigeria | Admin - Confirm IFX Account" />
    <meta name="keywords" content="" />
    <meta name="description" content="" />
    <?php require_once 'layouts/head_meta.php'; ?>
</head>
<body>
<?php require_once 'layouts/header.php'; ?>
<!-- Main Body: The is the main content area of the web site, contains a side bar  -->
<div id="main-body" class="container-fluid">
    <div class="row no-gutter">
        <!-- Main Body - Side Bar  -->
        <div id="main-body-side-bar" class="col-md-4 col-lg-3 left-nav">
            <?php require_once 'layouts/sidebar.php'; ?>
        </div>

        <!-- Main Body - Content Area: This is the main content area, unique for each page  -->
        <div id="main-body-content-area" class="col-md-8 col-lg-9">

            <!-- Unique Page Content Starts Here
            ================================================== -->
            <div class="row">
                <div class="col-sm-12 text-danger">
                    <h4><strong>Confirm IFX Account</strong></h4>
                </div>
            </div>

            <div class="section-tint super-shadow">
                <div class="row">
                    <div class="col-sm-12">
                        <?php require_once 'layouts/feedback_message.php'; ?>

                        <p>Confirm IFX Account</p>
                        <ul class="fa-ul">
                            <li><i class="fa-li fa fa-check-square-o icon-tune"></i>
                            Enter an account number below to confirm if it is ILPR or Not, this confirmation
                            is done directly from Instaforex.com server.</li>
                        </ul>

                        <form data-toggle="validator" class="form-horizontal" role="form" method="post" action="">
                            <div class="form-group">
                                <label class="control-label col-sm-3" for="ifx_acct">IFX Account:</label>
                                <div class="col-sm-9 col-lg-5">
                                    <input name="ifx_acct" type="text" class="form-control" id="ifx_acct" required>
                                </div>
                            </div>
                            <div class="form-group">
                                <div class="col-sm-offset-3 col-sm-9"><input name="deposit_confirm_ifx_account" type="submit" class="btn btn-success" value="Submit" /></div>
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
</body>
</html>