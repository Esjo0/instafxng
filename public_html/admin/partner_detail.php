<?php
require_once("../init/initialize_admin.php");
if (!$session_admin->is_logged_in()) {
    redirect_to("login.php");
}

$get_params = allowed_get_params(['id']);
$partner_code_encrypted = $get_params['id'];
$partner_code = dec_enc('decrypt',  $partner_code_encrypted));

if(is_null($partner_code_encrypted) || empty($partner_code_encrypted)) {
    redirect_to("./"); // page cannot display anything without the id
} else {
    $partner_code = $db_handle->sanitizePost($partner_code);
    $partner_detail = $partner_object->get_partner_by_code($partner_code);
    
    if($partner_detail) {
        extract($partner_detail);
    } else {
        // No record for the partner, it is possible that URL is tampered
        redirect_to("./");
    }
}

?>
<!DOCTYPE html>
<html lang="en">
    <head>
        <base target="_self">
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>Instaforex Nigeria | Admin - View Partner Details</title>
        <meta name="title" content="Instaforex Nigeria | Admin - View Partner Details" />
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
                            <h4><strong>VIEW PARTNER DETAIL</strong></h4>
                        </div>
                    </div>
                    
                    <div class="section-tint super-shadow">
                        <div class="row">
                            <div class="col-sm-12">
                                <?php require_once 'layouts/feedback_message.php'; ?>
                                <p><a href="partner_view.php" class="btn btn-default" title="Go Back"><i class="fa fa-arrow-circle-left"></i> Go Back</a></p>
                                <p>Below is the detail of the selected partner.</p>
                                
                                <!------------- Contact Section --->
                                <div class="row">
                                    <div class="col-sm-6">
                                        <h5>Partner Information</h5>
                                        <span class="span-title">Partner Name</span>
                                        <p><em><?php echo $last_name . ' ' . $middle_name . ' ' . $first_name; ?></em> - <strong><?php echo $partner_code; ?></strong></p>
                                        <span class="span-title">Email Address</span>
                                        <p><em><?php echo $email_address; ?></em></p>
                                        <span class="span-title">Phone Number</span>
                                        <p><em><?php echo $phone_number; ?></em></p>
                                        <span class="span-title">Opening Date</span>
                                        <p><em><?php echo datetime_to_text2($created); ?></em></p>
                                        <span class="span-title">Client Address</span>
                                        <p><em></em></p>
                                        <span class="span-title">Client SMS Code</span>
                                        <p>Code: <?php echo $phone_code; ?> &nbsp;&nbsp; Status: <?php echo phone_code_status($phone_code_status); ?></p>
                                    </div>

                                    <div class="col-sm-6">
                                        <div class="row">
                                            <div class="col-sm-4 text-center" style="margin-bottom: 4px !important">
                                                <span>Identity Card</span>
                                                <?php if(!empty($client_credential['idcard'])) { ?>
                                                <a href="../userfiles/<?php echo $client_credential['idcard']; ?>" title="click to enlarge" target="_blank"><img src="../userfiles/<?php echo $client_credential['idcard']; ?>" width="120px" height="120px"/></a>
                                                <?php } else { ?>
                                                <img src="../images/placeholder.jpg" alt="" class="img-responsive center-block">
                                                <?php } ?>
                                                <a href="" data-toggle="modal" data-target="#myModalCard" class="btn btn-default" style="margin-top: 2px !important">View</a>
                                            </div>
                                            <div class="col-sm-4 text-center" style="margin-bottom: 4px !important">
                                                <span>Passport</span>
                                                <?php if(!empty($client_credential['passport'])) { ?>
                                                <a href="../userfiles/<?php echo $client_credential['passport']; ?>" title="click to enlarge" target="_blank"><img src="../userfiles/<?php echo $client_credential['passport']; ?>" width="120px" height="120px"/></a>
                                                <?php } else { ?>
                                                <img src="../images/placeholder.jpg" alt="" class="img-responsive center-block">
                                                <?php } ?>
                                                <a href="" data-toggle="modal" data-target="#myModalPass" class="btn btn-default" style="margin-top: 2px !important">View</a>
                                            </div>
                                            <div class="col-sm-4 text-center" style="margin-bottom: 4px !important">
                                                <span>Signature</span>
                                                <?php if(!empty($client_credential['signature'])) { ?>
                                                <a href="../userfiles/<?php echo $client_credential['signature']; ?>" title="click to enlarge" target="_blank"><img src="../userfiles/<?php echo $client_credential['signature']; ?>" width="120px" height="120px"/></a>
                                                <?php } else { ?>
                                                <img src="../images/placeholder.jpg" alt="" class="img-responsive center-block">
                                                <?php } ?>
                                                <a href="" data-toggle="modal" data-target="#myModalSign" class="btn btn-default" style="margin-top: 2px !important">View</a>
                                            </div>
                                        </div>
                                        
                                    </div>
                                </div><!------- End Contact section ----->

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