<?php
require_once("../init/initialize_admin.php");
if (!$session_admin->is_logged_in()) {
    redirect_to("login.php");
}

$get_params = allowed_get_params(['x', 'id']);
$user_code_encrypted = $get_params['id'];
$user_code = decrypt_ssl(str_replace(" ", "+", $user_code_encrypted));
$user_code = preg_replace("/[^A-Za-z0-9 ]/", '', $user_code);

switch($get_params['x']) {
    case 'view': $client_manage_view = true; $page_title = '- VIEW'; break;
    case 'edit': $client_manage_edit = true; $page_title = '- EDIT'; break;
    default: $no_valid_page = true; break;
}

if($no_valid_page) {
    header("Location: ./");
}

if ($client_manage_view && ($_SERVER['REQUEST_METHOD'] == 'POST' && $_POST['process'] == true)) {
    
    header("Location: client_view.php");
}

if ($client_manage_edit && ($_SERVER['REQUEST_METHOD'] == 'POST' && $_POST['process'] == true)) {
    
    
    header("Location: client_view.php");
}

?>
<!DOCTYPE html>
<html lang="en">
    <head>
        <base target="_self">
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>Instaforex Nigeria | Admin - Manage Client</title>
        <meta name="title" content="Instaforex Nigeria | Admin - Process Deposit" />
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
                            <h4><strong>MANAGE CLIENT <?php if($page_title) { echo $page_title; } ?></strong></h4>
                        </div>
                    </div>
                    
                    
                    <div class="section-tint super-shadow">
                        <div class="row">
                            <div class="col-sm-12">
                                <p><a href="client_view.php" class="btn btn-default" title="Go back to View Clients"><i class="fa fa-arrow-circle-left"></i> Go Back - View Clients</a></p>

                                <?php require_once 'layouts/feedback_message.php'; ?>
                                
                                <?php 
                                    if($client_manage_view) { include_once 'views/client_manage/client_manage_view.php'; }
                                    if($client_manage_edit) { include_once 'views/client_manage/client_manage_edit.php'; }
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