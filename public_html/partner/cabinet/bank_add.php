<?php
require_once '../../init/initialize_partner.php';
if (!$session_partner->is_logged_in()) {
    redirect_to("../login.php");
}

$partner_details = $_SESSION['partner_details'];
$partner_details['partner_code'];

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <base target="_self">
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Instaforex Nigeria | Add Bank Account</title>
    <meta name="title" content="Instaforex Nigeria | Add Bank Account" />
    <meta name="keywords" content="">
    <meta name="description" content="">
    <?php require_once 'layouts/head_meta.php'; ?>
</head>
<body>
<?php require_once 'layouts/header.php'; ?>
<!-- Main Body: The is the main content area of the web site, contains a side bar  -->
<div id="main-body" class="container-fluid">
    <div class="row no-gutter">
        <?php require_once 'layouts/navbar.php'; ?>
        <!-- Main Body - Content Area: This is the main content area, unique for each page  -->
        <div id="main-body-content-area" class="col-md-12">

            <!-- Unique Page Content Starts Here
            ================================================== -->
            <div class="section-tint super-shadow">
                <div class="row">
                    <div class="col-sm-12">
                        <h3>Add Bank Account</h3>
                        <p>You need to add a Nigerian bank account for you to be able to make withdrawals.</p>
                    </div>
                </div>
                <div class="row">

                </div>


            </div>
            <!-- Unique Page Content Ends Here
            ================================================== -->

        </div>

    </div>
    <div class="row no-gutter">
        <?php require_once 'layouts/footer.php'; ?>
    </div>
</div>

</body>
</html>