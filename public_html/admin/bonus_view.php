<?php
require_once("../init/initialize_admin.php");
if (!$session_admin->is_logged_in()) {redirect_to("login.php");}
$bonus_operations = new Bonus_Operations();
$package_code = decrypt_ssl(str_replace(" ", "+", $_GET['package_code']));
$package_details = $bonus_operations->get_package_by_code($package_code);
?>
<!DOCTYPE html>
<html lang="en">
    <head>
        <base target="_self">
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>Instaforex Nigeria | Admin - View Bonus Package</title>
        <meta name="title" content="Instaforex Nigeria | Admin - View Bonus Package" />
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
                            <h4><strong>VIEW BONUS PACKAGE</strong></h4>
                        </div>
                    </div>
                    
                    <div class="section-tint super-shadow">
                        <div class="row">
                            <div class="col-sm-12">
                                <?php require_once 'layouts/feedback_message.php'; ?>
                                <p class="pull-left"><a href="bonus_list.php" class="btn btn-default" title="Bonus Packages"><i class="fa fa-arrow-circle-left"></i> Bonus Packages</a></p>
                                <p class="pull-right"><a href="bonus_new.php?package_code=<?php echo encrypt_ssl($package_code);?>" class="btn btn-default" title="Edit Bonus Package">Edit Package <i class="fa fa-arrow-circle-right"></i></a></p>

                                <table class="table table-responsive table-responsive">
                                    <tbody>
                                        <tr>
                                            <td><b>Package Name:</b></td>
                                            <td><span class="text-justify"><?php echo $package_details['bonus_title']; ?></span></td>
                                        </tr>
                                        <tr>
                                            <td><b>Package Description:</b></td>
                                            <td><span class="text-justify"><?php echo $package_details['bonus_desc']; ?></span></td>
                                        </tr>
                                        <tr>
                                            <td><b>Package Conditions:</b></td>
                                            <td><span class="text-justify"><?php echo $bonus_operations->show_conditions_by_code($package_code); ?></span></td>
                                        </tr>
                                    </tbody>
                                </table>
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