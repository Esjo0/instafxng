<?php
require_once("../init/initialize_admin.php");
if (!$session_admin->is_logged_in()) {
    redirect_to("login.php");
}

$get_params = allowed_get_params(['id']);
$user_code_encrypted = $get_params['id'];
$user_code = decrypt(str_replace(" ", "+", $user_code_encrypted));
$user_code = preg_replace("/[^A-Za-z0-9 ]/", '', $user_code);

$client_operation = new clientOperation();

if(is_null($user_code_encrypted) || empty($user_code_encrypted)) {
    redirect_to("./"); // page cannot display anything without the id
} else {
    $user_code = $db_handle->sanitizePost($user_code);
    $user_detail = $client_operation->get_user_by_user_code($user_code);
    
    if($user_detail) {
        extract($user_detail);

        if($middle_name) {
            $full_name = $last_name . ' ' . $middle_name . ' ' . $first_name;
        } else {
            $full_name = $last_name . ' ' . $first_name;
        }
        
        $total_client_account = $db_handle->numRows("SELECT ifx_acct_no FROM user_ifxaccount WHERE user_code = '$user_code'");
        $client_ilpr_account = $client_operation->get_client_ilpr_accounts_by_code($user_code);
        $client_non_ilpr_account = $client_operation->get_client_non_ilpr_accounts_by_code($user_code);
        $client_address = $client_operation->get_user_address_by_code($user_code);
        $client_verification = $client_operation->get_client_verification_status($user_code);
        $client_credential = $client_operation->get_user_credential($user_code);
        $client_bank_account = $client_operation->get_user_bank_account($user_code);
        $client_phone_code = $client_operation->get_user_phonecode($user_code);

        $total_point = $client_operation->get_loyalty_point_earned($user_code);
        $total_point_claimed = $client_operation->get_loyalty_point_claimed($user_code);
        $total_point_frozen = $client_operation->get_loyalty_point_frozen($user_code);
        $loyalty_point_balance = $total_point - ($total_point_claimed + $total_point_frozen);

        $selected_point_frozen_trans_id = $client_operation->get_loyalty_point_frozen_transaction($user_code);

        switch($client_verification) {
            case '0': $verification_level = "Not Verified"; break;
            case '1': $verification_level = "Level 1 Verified"; break;
            case '2': $verification_level = "Level 2 Verified"; break;
            case '3': $verification_level = "Level 3 Verified"; break;
        }
    } else {
        // No record for that client, it is possible that URL is tampered
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
        <title>Instaforex Nigeria | Admin - View Detail</title>
        <meta name="title" content="Instaforex Nigeria | Admin - View Clients" />
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
                            <h4><strong>VIEW CLIENT DETAIL</strong></h4>
                        </div>
                    </div>
                    
                    <div class="section-tint super-shadow">
                        <div class="row">
                            <div class="col-sm-12">
                                <?php require_once 'layouts/feedback_message.php'; ?>
                                <p><a href="<?php echo $_SERVER['HTTP_REFERER']; ?>" class="btn btn-default" title="Go Back"><i class="fa fa-arrow-circle-left"></i> Go Back</a></p>
                                <p>Below is the detail of the selected client. <strong>Note:</strong> If this client has an account that is flagged, it will display in red.</p>
                                
                                <!------------- Contact Section --->
                                <div class="row">
                                    <div class="col-sm-6">
                                        <h5>Client Information</h5>
                                        <span class="span-title">Full Name</span>
                                        <p><em><?php echo $full_name; ?></em></p>
                                        <span class="span-title">Email Address</span>
                                        <p><em><?php echo $email; ?></em></p>
                                        <span class="span-title">Phone Number</span>
                                        <p><em><?php echo $phone; ?></em></p>
                                        <span class="span-title">Opening Date</span>
                                        <p><em><?php echo datetime_to_text2($created); ?></em></p>
                                        <span class="span-title">Client Address</span>
                                        <p><em><?php echo $client_address['address']; ?></em></p>
                                        <span class="span-title">Client SMS Code</span>
                                        <p>Code: <?php echo $client_phone_code['phone_code']; ?> &nbsp;&nbsp; Status: <?php echo phone_code_status($client_phone_code['phone_status']); ?></p>
                                        <span class="span-title">Verification Status</span>
                                        <p><?php echo $verification_level; ?></p>
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
                                        
                                        <div class="row">
                                            <div class="col-sm-12">
                                                <p><strong>Loyalty Points: </strong> Total Earned is <?php if(!empty($total_point)) { echo $total_point; } else { echo '0.00'; } ?></p>
                                                <table class="table table-responsive table-striped table-bordered table-hover">
                                                    <thead>
                                                        <tr>
                                                            <th>Claimed</th>
                                                            <th>Held</th>
                                                            <th>Balance</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        <tr>
                                                            <td><?php if(!empty($total_point_claimed)) { echo $total_point_claimed; } else { echo '0.00'; } ?></td>
                                                            <td><?php if(!empty($total_point_frozen)) { echo $total_point_frozen; } else { echo '0.00'; } ?></td>
                                                            <td><?php if(!empty($loyalty_point_balance)) { echo $loyalty_point_balance; } else { echo '0.00'; } ?></td>
                                                        </tr>
                                                        <tr>
                                                            <td colspan="3">
                                                                Held Transactions:
                                                                <?php if(!empty($selected_point_frozen_trans_id)) {
                                                                    $count = 1;
                                                                    foreach($selected_point_frozen_trans_id as $value) {
                                                                        $trans_id = $value['trans_id'];
                                                                        if($count < count($selected_point_frozen_trans_id)) { $trans_id = $trans_id . ", "; }
                                                                        echo $trans_id;
                                                                        $count++;
                                                                    } } else { echo 'Nil'; }
                                                                ?>
                                                            </td>
                                                        </tr>
                                                    </tbody>
                                                </table>
                                                <hr />

                                                <p><strong>Total IFX Accounts: </strong><?php echo number_format($total_client_account); ?></p>
                                                <table class="table table-responsive table-striped table-bordered table-hover">
                                                    <thead>
                                                        <tr>
                                                            <th>ILPR Accounts: <?php echo count($client_ilpr_account); ?></th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        
                                                        <?php if($client_ilpr_account) { ?>
                                                        <tr>
                                                            <td>
                                                                <?php
                                                                $count = 1;
                                                                foreach($client_ilpr_account as $key => $value) {
                                                                    $ifx_acct_no = $value['ifx_acct_no'];
                                                                    if($count < count($client_ilpr_account)) { $ifx_acct_no = $ifx_acct_no . ", "; }
                                                                    if($client_operation->account_flagged($value['ifxaccount_id'])) {
                                                                        echo "<span class='text-danger'><strong>" . $ifx_acct_no . "</strong></span>";
                                                                    } else {
                                                                        echo $ifx_acct_no;
                                                                    }
                                                                    $count++;
                                                                }
                                                                ?>
                                                            </td>
                                                        </tr>
                                                        <?php } else { ?>
                                                        <tr><td colspan='' class='text-danger'><em>No results to display</em></td></tr>
                                                        <?php } ?>
                                                        
                                                    </tbody>
                                                </table>
                                                
                                                <table class="table table-responsive table-striped table-bordered table-hover">
                                                    <thead>
                                                        <tr>
                                                            <th>Non - ILPR Accounts: <?php echo count($client_non_ilpr_account); ?></th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        
                                                        <?php if($client_non_ilpr_account) { ?>
                                                        <tr>
                                                            <td>
                                                                <?php
                                                                $count = 1;
                                                                foreach($client_non_ilpr_account as $key => $value) {
                                                                    $ifx_acct_no = $value['ifx_acct_no'];
                                                                    if($count < count($client_non_ilpr_account)) { $ifx_acct_no = $ifx_acct_no . ", "; }
                                                                    if($client_operation->account_flagged($value['ifxaccount_id'])) {
                                                                        echo "<span class='text-danger'><strong>" . $ifx_acct_no . "</strong></span>";
                                                                    } else {
                                                                        echo $ifx_acct_no;
                                                                    }
                                                                    $count++;
                                                                }
                                                                ?>
                                                            </td>
                                                        </tr>
                                                        <?php } else { ?>
                                                        <tr><td colspan='' class='text-danger'><em>No results to display</em></td></tr>
                                                        <?php } ?>
                                                        
                                                    </tbody>
                                                </table>
                                                
                                            </div>
                                        </div>
                                    </div>
                                </div><!------- End Contact section ----->
                                
                                <hr />
                                
                                <!-------------- Transaction section ----->
                                <div class="row">
                                    <div class="col-sm-12">
                                        <h5>Bank Account Detail</h5>
                                        <table class="table table-responsive table-striped table-bordered table-hover">
                                            <thead>
                                                <tr>
                                                    <th>Bank Name</th>
                                                    <th>Account Name</th>
                                                    <th>Account Number</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <?php if($client_bank_account['client_acct_no']) { ?>
                                                <tr>
                                                    <td><?php echo $client_bank_account['client_bank_name']; ?></td>
                                                    <td><?php echo $client_bank_account['client_acct_name']; ?></td>
                                                    <td><?php echo $client_bank_account['client_acct_no']; ?></td>
                                                </tr>
                                                <?php } else { ?>
                                                <tr><td colspan='3' class='text-danger'><em>No results to display</em></td></tr>
                                                <?php } ?>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    
                    
                    <!-- Modal -->
                    <div id="myModalPass" class="modal fade" role="dialog">
                        <div class="modal-dialog">
                            <!-- Modal content-->
                            <div class="modal-content">
                                <div class="modal-body">
                                    <?php if(!empty($client_credential['passport'])) { ?>
                                        <a href="../userfiles/<?php echo $client_credential['passport']; ?>" title="click to enlarge" target="_blank"><img src="../userfiles/<?php echo $client_credential['passport']; ?>" class="img-responsive center-block"/></a>
                                    <?php } else { ?>
                                        <img src="../images/placeholder.jpg" alt="" class="img-responsive center-block">
                                    <?php } ?>
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-default" data-dismiss="modal" title="Close">Close</button>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <div id="myModalCard" class="modal fade" role="dialog">
                        <div class="modal-dialog">
                            <!-- Modal content-->
                            <div class="modal-content">
                                <div class="modal-body text-center">
                                    <?php if(!empty($client_credential['idcard'])) { ?>
                                        <a href="../userfiles/<?php echo $client_credential['idcard']; ?>" title="click to enlarge" target="_blank"><img src="../userfiles/<?php echo $client_credential['idcard']; ?>" class="img-responsive center-block"/></a>
                                    <?php } else { ?>
                                        <img src="../images/placeholder.jpg" alt="" class="img-responsive center-block">
                                    <?php } ?>
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-default" data-dismiss="modal" title="Close">Close</button>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <div id="myModalSign" class="modal fade" role="dialog">
                        <div class="modal-dialog">
                            <!-- Modal content-->
                            <div class="modal-content">
                                <div class="modal-body text-center">
                                    <?php if(!empty($client_credential['signature'])) { ?>
                                        <a href="../userfiles/<?php echo $client_credential['signature']; ?>" title="click to enlarge" target="_blank"><img src="../userfiles/<?php echo $client_credential['signature']; ?>" class="img-responsive center-block"/></a>
                                    <?php } else { ?>
                                        <img src="../images/placeholder.jpg" alt="" class="img-responsive center-block">
                                    <?php } ?>
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-default" data-dismiss="modal" title="Close">Close</button>
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