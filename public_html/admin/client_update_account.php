<?php
require_once("../init/initialize_admin.php");
if (!$session_admin->is_logged_in()) {
    redirect_to("login.php");
}

$page_requested = "";

if (isset($_POST['update_account_ifx'])) {
    $account_no = $db_handle->sanitizePost($_POST['ifx_acct_no']);

    $client_operation = new clientOperation($account_no);
    $user_ifx_details = $client_operation->get_client_data();

    if($user_ifx_details) {
        extract($user_ifx_details); // turn table columns selected into variables

        $page_requested = 'update_account_detail_php';
    } else {
        $message_error = "The Instaforex account does not exist, You can add the Instaforex account <a href=\"client_add.php\">here</a>.";
    }
}

if (isset($_POST['update_account_detail'])) {
    $account_no = $db_handle->sanitizePost($_POST['acct_no']);
    $account_type = $db_handle->sanitizePost($_POST['acct_type']);

    $client_operation = new clientOperation();

    $query = "UPDATE user_ifxaccount SET type = '$account_type' WHERE ifx_acct_no = '$account_no' LIMIT 1";
    $db_handle->runQuery($query);

    if ($db_handle->affectedRows() > 0) {
        $message_success = "Update successful";
    } else {
        $message_error = "Something went wrong, it seems you did not make any change or an error occurred";
    }

}


switch($page_requested) {
    case '': $update_account_ifx_php = true; break;
    case 'update_account_detail_php': $update_account_detail_php = true; break;
    default: $update_account_ifx_php = true;
}

?>
<!DOCTYPE html>
<html lang="en">
    <head>
        <base target="_self">
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>Instaforex Nigeria | Admin - Update IFX Account</title>
        <meta name="title" content="Instaforex Nigeria | Admin - Update IFX Account" />
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
                            <h4><strong>UPDATE IFX ACCOUNTS</strong></h4>
                        </div>
                    </div>
                    
                    <div class="section-tint super-shadow">
                        <div class="row">
                            <div class="col-sm-12">
                                <?php require_once 'layouts/feedback_message.php'; ?>

                                <?php
                                    if($update_account_ifx_php) { include_once 'views/update_account/update_account_ifx.php'; }
                                    if($update_account_detail_php) { include_once 'views/update_account/update_account_detail.php'; }
                                ?>

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