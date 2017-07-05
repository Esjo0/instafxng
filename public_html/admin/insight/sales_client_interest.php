<?php
require_once("../../init/initialize_admin.php");
if (!$session_admin->is_logged_in()) {
    redirect_to("login.php");
}

$query = "SELECT u.email FROM sales_contact_client_interest AS scci INNER JOIN user AS u ON scci.user_code = u.user_code WHERE scci.interest_training = '2'";
$result = $db_handle->numRows($query);
$interest_training = $result;

$query = "SELECT u.email FROM sales_contact_client_interest AS scci INNER JOIN user AS u ON scci.user_code = u.user_code WHERE scci.interest_funding = '2'";
$result = $db_handle->numRows($query);
$interest_funding = $result;

$query = "SELECT u.email FROM sales_contact_client_interest AS scci INNER JOIN user AS u ON scci.user_code = u.user_code WHERE scci.interest_bonus = '2'";
$result = $db_handle->numRows($query);
$interest_bonus = $result;

$query = "SELECT u.email FROM sales_contact_client_interest AS scci INNER JOIN user AS u ON scci.user_code = u.user_code WHERE scci.interest_investment = '2'";
$result = $db_handle->numRows($query);
$interest_investment = $result;

$query = "SELECT u.email FROM sales_contact_client_interest AS scci INNER JOIN user AS u ON scci.user_code = u.user_code WHERE scci.interest_services = '2'";
$result = $db_handle->numRows($query);
$interest_services = $result;

$query = "SELECT u.email FROM sales_contact_client_interest AS scci INNER JOIN user AS u ON scci.user_code = u.user_code WHERE scci.interest_other = '2'";
$result = $db_handle->numRows($query);
$interest_other = $result;

?>
<!DOCTYPE html>
<html lang="en">
    <head>
        <base target="_self">
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>Instaforex Nigeria | Admin - Sales Client Interest</title>
        <meta name="title" content="Instaforex Nigeria | Admin - Sales Client Interest" />
        <meta name="keywords" content="" />
        <meta name="description" content="" />
        <?php require_once '../layouts/head_meta.php'; ?>
    </head>
    <body>
        <?php require_once '../layouts/header.php'; ?>
        <!-- Main Body: The is the main content area of the web site, contains a side bar  -->
        <div id="main-body" class="container-fluid">
            <div class="row no-gutter">
                <!-- Main Body - Side Bar  -->
                <div id="main-body-side-bar" class="col-md-4 col-lg-3 left-nav">
                <?php require_once '../layouts/sidebar.php'; ?>
                </div>
                
                <!-- Main Body - Content Area: This is the main content area, unique for each page  -->
                <div id="main-body-content-area" class="col-md-8 col-lg-9">
                    
                    <!-- Unique Page Content Starts Here
                    ================================================== -->
                    <div class="row">
                        <div class="col-sm-12 text-danger">
                            <h4><strong>SALES - CLIENT INTEREST</strong></h4>
                        </div>
                    </div>
                    
                    <div class="section-tint super-shadow">

                        <div class="row">
                            <div class="col-sm-12">
                                <p>Below is the details of what clients are interested in. This data is generated from
                                what the call centre agents choose when calls were placed to clients.</p>

                                <table class="table table-responsive table-striped table-bordered table-hover">
                                    <thead>
                                    <tr>
                                        <th></th>
                                        <th></th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    <tr><td>Training</td><td><?php echo $interest_training; ?></td></tr>
                                    <tr><td>Funding</td><td><?php echo $interest_funding; ?></td></tr>
                                    <tr><td>Bonuses</td><td><?php echo $interest_bonus; ?></td></tr>
                                    <tr><td>Investment</td><td><?php echo $interest_investment; ?></td></tr>
                                    <tr><td>Services</td><td><?php echo $interest_services; ?></td></tr>
                                    <tr><td>Others</td><td><?php echo $interest_other; ?></td></tr>
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
        <?php require_once '../layouts/footer.php'; ?>
    </body>
</html>