<?php
require_once("../init/initialize_admin.php");
if (!$session_admin->is_logged_in()) {
    redirect_to("login.php");
}

$client_operation = new clientOperation();

$partner = new partner();

if (isset($_POST['update_comm_rate']) ) {
    $comm_rate = $db_handle->sanitizePost($_POST['comm_rate']);

    $updpartn = $partner->setcommission_rate($comm_rate);
    if($updpartn) {
        $message_success = "You have successfully updated your commission rate";
    } else {
        $message_error = "Something went wrong. Please try again.";
    }
}

?>
<!DOCTYPE html>
<html lang="en">
    <head>
        <base target="_self">
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>Instaforex Nigeria | Admin - Set Partner Commission</title>
        <meta name="title" content="Instaforex Nigeria | Admin - Set Partner Commission" />
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
                            <h4><strong>SET PARTNER COMMISSION</strong></h4>
                        </div>
                    </div>
                    
                    <div class="section-tint super-shadow">
                        <div class="row">
                            <div class="col-sm-12">
                                <?php require_once 'layouts/feedback_message.php'; ?>

                                <form data-toggle="validator" class="form-horizontal" role="form" method="post" action="<?php echo $REQUEST_URI; ?>">

                                    <div class="form-group">
                                        <label class="control-label col-sm-3" for="partner_code">Commission Raate:</label>
                                        <div class="col-sm-9 col-lg-5">
                                            <input name="comm_rate" type="text" required class="form-control" id="comm_rate" value="<?php if(isset($comm_rate)) { echo $comm_rate; } ?>">
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <div class="col-sm-offset-3 col-sm-9">
                                            <button type="button" data-target="#update-comm-confirm" data-toggle="modal" class="btn btn-success"><i class="fa fa-send-o fa-fw"></i> Submit</button>
                                        </div>
                                    </div>

                                    <!--Modal - confirmation boxes-->
                                    <div id="update-comm-confirm" tabindex="-1" role="dialog" aria-hidden="true" class="modal fade">
                                        <div class="modal-dialog">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <button type="button" data-dismiss="modal" aria-hidden="true"
                                                            class="close">&times;</button>
                                                    <h4 class="modal-title">Update Commission Rate</h4></div>
                                                <div class="modal-body">Are you sure you want to save this information?</div>
                                                <div class="modal-footer">
                                                    <input name="process" type="submit" class="btn btn-success" value="Proceed">
                                                    <button type="submit" name="close" onClick="window.close();" data-dismiss="modal" class="btn btn-danger">Close!</button>
                                                </div>
                                            </div>
                                        </div>
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