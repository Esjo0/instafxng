<?php
require_once 'init/initialize_general.php';
$thisPage = "";

$client_operation = new clientOperation();

if (isset($_POST['submit']) && !empty($_POST['submit'])) {
    $email = $db_handle->sanitizePost(trim($_POST['email']));

    $query = "SELECT user_code, first_name FROM user WHERE email = '$email' LIMIT 1";
    $result = $db_handle->runQuery($query);

    if($db_handle->numOfRows($result) > 0) {
        $fetched_data = $db_handle->fetchAssoc($result);
        $user_code = $fetched_data[0]['user_code'];
        $first_name = $fetched_data[0]['first_name'];

        $passcode_reset = $client_operation->start_password_reset($user_code, $first_name, $email);

        if($passcode_reset) {
            $message_success = "An email has been sent to the email address provided, please check your email immediately for further directives.";
        } else {
            $message_error = "Something went wrong, we could not begin the password reset, please try again or contact our support department.";
        }

    } else {
        $message_error = "No luck, that email is not associated with any profile here.";
    }
}
?>
<!DOCTYPE html>
<html lang="en">
    <head>
        <base target="_self">
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>Instaforex Nigeria | Pass Code Recovery</title>
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
                                <h4><strong>Pass Code Recovery</strong></h4>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-sm-12">
                                <?php require_once 'layouts/feedback_message.php'; ?>
                                
                                <p>To recover your pass code, please enter your email address below.</p>
                                <form class="form-horizontal" role="form" method="post" action="<?php echo $_SERVER['REQUEST_URI'] ?>">
                                    <div class="form-group">
                                        <label class="control-label col-sm-2" for="email">Email Address:</label>
                                        <div class="col-sm-10 col-lg-7">
                                            <input name="email" type="text" class="form-control" id="ifx_acct_no">
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <div class="col-sm-offset-2 col-sm-10"><input name="submit" type="submit" class="btn btn-success" value="Recover Passcode" /></div>
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