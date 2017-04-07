<?php
require_once("../init/initialize_admin.php");
if (!$session_admin->is_logged_in()) {
    redirect_to("login.php");
}

$get_params = allowed_get_params(['x', 'id']);
$bulletin_id_encrypted = $get_params['id'];
$bulletin_id = decrypt(str_replace(" ", "+", $bulletin_id_encrypted));
$bulletin_id = preg_replace("/[^A-Za-z0-9 ]/", '', $bulletin_id);


$selected_bulletin = $system_object->get_bulletin_by_id($bulletin_id);
$selected_bulletin = $selected_bulletin[0];

$allowed_admin = explode(",", $selected_bulletin['allowed_admin']);
if (!in_array($_SESSION['admin_unique_code'], $allowed_admin)) { unset($selected_bulletin); }

if(!$selected_bulletin) {
    redirect_to('bulletin_centre.php');
}


?>
<!DOCTYPE html>
<html lang="en">
    <head>
        <base target="_self">
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>Instaforex Nigeria | Admin - Bulletin Centre</title>
        <meta name="title" content="Instaforex Nigeria | Admin - Bulletin Centre" />
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
                    <div class="section-tint super-shadow">
                        <div class="row">
                            <div class="col-sm-12">
                                <p><i class="fa fa-arrow-circle-left"></i> <a href="bulletin_centre.php" title="Bulletin Centre">Go Back To Bulletin Centre</a></p>
                                <p><em>Posted on <?php echo datetime_to_text($selected_bulletin['created']); ?></em></p>
                                <p><strong>Author: </strong><?php echo $admin_object->get_admin_name_by_code($selected_bulletin['admin_code']); ?></p>
                                <hr/>
                            </div>
                        </div>
                        
                        <div class="row">
                            <div class="col-sm-12 text-danger">
                                <h4><strong><?php echo $selected_bulletin['title'];?></strong></h4>
                            </div>
                        </div>
                        
                        <div class="row">
                            <div class="col-sm-12">
                                <?php echo $selected_bulletin['content'];?>
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