<?php
require_once("../init/initialize_admin.php");
if (!$session_admin->is_logged_in()) {
    redirect_to("login.php");
}

?>
<!DOCTYPE html>
<html lang="en">
    <head>
        <base target="_self">
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>Instaforex Nigeria | Admin - View Student Payments</title>
        <meta name="title" content="Instaforex Nigeria | Admin - View Student Payments" />
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
                            <h4><strong>VIEW SELECTED EDUCATION PAYMENT</strong></h4>
                        </div>
                    </div>
                    
                    <div class="section-tint super-shadow">
                        <div class="row">
                            <div class="col-sm-7">
                                <p><a href="edu_payment.php" class="btn btn-default" title="Education Payments"><i class="fa fa-arrow-circle-left"></i> Go Back</a></p>
                                <p>View full details of student payment below</p>

                                <table class="table table-responsive table-striped table-bordered table-hover">
                                    <thead>
                                        <tr><th> </th><th> </th></tr>
                                    </thead>
                                    <tbody>
                                        <tr><td>Transaction ID</td><td><?php if(isset($trans_id)) { echo $trans_id; } ?></td></tr>
                                        <tr><td>Created</td><td><?php if(isset($created)) { echo datetime_to_text($created); } ?></td></tr>
                                    </tbody>
                                </table>

                            </div>

                            <div class="col-md-5">
                                <h5>Admin Remarks</h5>
                                <div style="max-height: 550px; overflow: scroll;">
                                    <?php
                                    if(isset($trans_remark) && !empty($trans_remark)) {
                                        foreach ($trans_remark as $row) {
                                            ?>
                                            <div class="row">
                                                <div class="col-sm-12">
                                                    <div class="transaction-remarks">
                                                        <span id="trans_remark_author"><?php echo $row['admin_full_name']; ?></span>
                                                        <span id="trans_remark"><?php echo $row['comment']; ?></span>
                                                        <span id="trans_remark_date"><?php echo datetime_to_text($row['created']); ?></span>
                                                    </div>
                                                </div>
                                            </div>
                                        <?php } } else { ?>
                                        <div class="row">
                                            <div class="col-sm-12">
                                                <div class="transaction-remarks">
                                                    <span class="text-danger"><em>There is no remark to display.</em></span>
                                                </div>
                                            </div>
                                        </div>
                                    <?php } ?>
                                </div>
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