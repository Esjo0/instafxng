<?php
require_once("../init/initialize_admin.php");
if (!$session_admin->is_logged_in()) {redirect_to("login.php");}
$bonus_operations = new Bonus_Operations();
$package_code = dec_enc('decrypt',  $_GET['pc']));
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
                                <p class="pull-right"><a href="bonus_new.php?package_code=<?php echo dec_enc('encrypt', $package_code);?>" class="btn btn-default" title="Edit Bonus Package">Edit Package <i class="fa fa-arrow-circle-right"></i></a></p>

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
                                        <tr>
                                            <td><b>Package Details:</b></td>
                                            <td><span class="text-justify"><?php echo $package_details['bonus_details']; ?></span></td>
                                        </tr>
                                        <tr>
                                            <td><b>Package Status:</b></td>
                                            <td><span class="text-justify"><?php echo $bonus_operations->bonus_package_status($package_details['status']); ?></span></td>
                                        </tr>
                                        <tr>
                                            <td><b>Package Type:</b></td>
                                            <td><span class="text-justify"><?php $bonus_operations->show_bonus_package_type($package_details['type'], $package_details['bonus_type_value']); ?></span></td>
                                        </tr>
                                        <tr>
                                            <td><b>Package Last Updated:</b></td>
                                            <td><span class="text-justify"><?php if(!empty($package_details['updated'])){ echo datetime_to_text($package_details['updated']); }  ?></span></td>
                                        </tr>
                                        <tr>
                                            <td><b>Total Pending Applications:</b></td>
                                            <td><span class="text-justify"><?php echo $bonus_operations->bonus_package_pending_applications($package_code)['sum'];  ?> Application(s)</span></td>
                                        </tr>
                                        <tr>
                                            <td><b>Total Approved Applications:</b></td>
                                            <td><span class="text-justify"><?php echo $bonus_operations->bonus_package_approved_applications($package_code)['sum'];  ?> Application(s)</span></td>
                                        </tr>
                                        <tr>
                                            <td><b>Total Declined Applications:</b></td>
                                            <td><span class="text-justify"><?php echo $bonus_operations->bonus_package_declined_applications($package_code)['sum'];  ?> Application(s)</span></td>
                                        </tr>
                                        <tr>
                                            <td><b>Total Active Accounts:</b></td>
                                            <td><span class="text-justify"><?php echo $bonus_operations->get_package_active_clients($package_code)['sum']  ?> Account(s)</span></td>
                                        </tr>
                                        <tr>
                                            <td><b>Total Recycled Accounts:</b></td>
                                            <td><span class="text-justify"><?php echo $bonus_operations->get_package_recycled_clients($package_code)['sum'];  ?> Account(s)</span></td>
                                        </tr>
                                        <tr>
                                            <td><b>Total Bonus Payouts:</b></td>
                                            <td><b><span class="text-justify"> &dollar;<?php echo $bonus_operations->get_total_bonus_package_payouts($package_code)['sum']; ?></span></b></td>
                                        </tr>
                                        <tr>
                                            <td><b>Total Bonus Withdrawn:</b></td>
                                            <td><span class="text-justify"> &dollar;<?php echo  $bonus_operations->get_total_bonus_package_withdrawals($package_code)['sum']; ?></span></td>
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