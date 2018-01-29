<?php
require_once("../init/initialize_admin.php");
if (!$session_admin->is_logged_in()) {
    redirect_to("login.php");
}

$client_operation = new clientOperation();

$query = "SELECT uw.trans_id, uw.dollar_withdraw, uw.created, uw.naira_total_withdrawable,
        uw.client_phone_password, uw.status AS withdrawal_status,
        CONCAT(u.last_name, SPACE(1), u.first_name) AS full_name, u.phone, u.user_code,
        uc.passport, ui.ifxaccount_id, ui.ifx_acct_no
        FROM user_withdrawal AS uw
        INNER JOIN user_ifxaccount AS ui ON uw.ifxaccount_id = ui.ifxaccount_id
        INNER JOIN user AS u ON ui.user_code = u.user_code
        LEFT JOIN user_credential AS uc ON ui.user_code = uc.user_code
        WHERE uw.status = '4' OR uw.status = '5' ORDER BY uw.created DESC ";
$numrows = $db_handle->numRows($query);

$rowsperpage = 10;

$totalpages = ceil($numrows / $rowsperpage);
// get the current page or set a default
if (isset($_GET['pg']) && is_numeric($_GET['pg'])) {
   $currentpage = (int) $_GET['pg'];
} else {
   $currentpage = 1;
}
if ($currentpage > $totalpages) { $currentpage = $totalpages; }
if ($currentpage < 1) { $currentpage = 1; }

$prespagelow = $currentpage * $rowsperpage - $rowsperpage + 1;
$prespagehigh = $currentpage * $rowsperpage;
if($prespagehigh > $numrows) { $prespagehigh = $numrows; }

$offset = ($currentpage - 1) * $rowsperpage;
$query .= 'LIMIT ' . $offset . ',' . $rowsperpage;
$result = $db_handle->runQuery($query);
$confirmed_withdrawal_requests = $db_handle->fetchAssoc($result);

?>
<!DOCTYPE html>
<html lang="en">
    <head>
        <base target="_self">
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>Instaforex Nigeria | Admin - Confirmed Withdrawal</title>
        <meta name="title" content="Instaforex Nigeria | Admin - Confirmed Withdrawal" />
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
                            <h4><strong>CONFIRMED WITHDRAWAL</strong></h4>
                        </div>
                    </div>
                    
                    <div class="section-tint super-shadow">
                        <div class="row">
                            <div class="col-sm-12">
                                <?php require_once 'layouts/feedback_message.php'; ?>

                                <p>Below is the list of all confirmed withdrawal requests.</p>

                                <div class="row">

                                    <div class="col-sm-12">
                                        <table class="table table-responsive table-striped table-bordered table-hover">
                                            <thead>
                                            <tr>
                                                <th>Transaction ID</th>
                                                <th>Client Name</th>
                                                <th>Order Value</th>
                                                <th>Created</th>
                                                <th></th>
                                            </tr>
                                            </thead>
                                            <tbody>
                                    <?php if(isset($confirmed_withdrawal_requests) && !empty($confirmed_withdrawal_requests)) {
                                        foreach ($confirmed_withdrawal_requests as $row) { ?>

                                                    <tr>
                                                        <td><b><?php echo $row['trans_id']; ?></b></td>
                                                        <td><?php echo $row['full_name']; ?></td>
                                                        <td> &dollar; <?php echo $row['dollar_withdraw']; ?> - &#8358; <?php echo number_format($row['naira_total_withdrawable'], 2, ".", ","); ?></td>
                                                        <td><?php echo datetime_to_text($row['created']); ?></td>
                                                        <td>
                                                            <a href="client_detail.php?id=<?php echo encrypt($row['user_code']); ?>" class="btn btn-sm btn-info"><i class="glyphicon glyphicon-user"></i></a>
                                                            <a href="withdraw_process_view_only.php?x=confirmed&id=<?php echo encrypt($row['trans_id']); ?>" class="btn btn-sm btn-info"><i class="glyphicon glyphicon-info-sign"></i></a>

                                                            <!--<button type="button" data-target="#client_profile_<?php /*echo $row['user_code'];*/?>" data-toggle="modal" class="btn btn-sm btn-info"><i class="glyphicon glyphicon-user"></i></button>-->
                                                            <div id="client_profile_<?php echo $row['user_code'];?>" tabindex="-1" role="dialog" aria-hidden="true" class="modal modal-lg fade">
                                                                <div class="modal-dialog">
                                                                    <div class="modal-content">
                                                                        <div class="modal-header">
                                                                            <button type="button" data-dismiss="modal" aria-hidden="true" class="close">&times;</button>
                                                                            <h4 class="modal-title">CLIENT DETAILS</h4></div>
                                                                        <div class="modal-body">
                                                                            <div class="row">
                                                                                <div class="col-sm-12">
                                                                                    <?php
                                                                                    $user_code = $db_handle->sanitizePost($row['user_code']);
                                                                                    $user_detail = $client_operation->get_user_by_user_code($user_code);

                                                                                    if($user_detail) {

                                                                                        // Get client education log history
                                                                                        $client_education_history = $education_object->get_client_lesson_history($user_code);

                                                                                        extract($user_detail);

                                                                                        if ($middle_name) {
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

                                                                                        $client_flags = $client_operation->get_client_flag_by_code($user_code);

                                                                                        $total_point = $client_operation->get_loyalty_point_earned($user_code);
                                                                                        $total_point_claimed = $client_operation->get_loyalty_point_claimed($user_code);
                                                                                        $total_point_frozen = $client_operation->get_loyalty_point_frozen($user_code);
                                                                                        $loyalty_point_balance = $total_point - ($total_point_claimed + $total_point_frozen);

                                                                                        $client_point_details = $obj_loyalty_point->get_user_point_details($user_code);

                                                                                        $selected_point_frozen_trans_id = $client_operation->get_loyalty_point_frozen_transaction($user_code);

                                                                                        switch ($client_verification) {
                                                                                            case '0':
                                                                                                $verification_level = "Not Verified";
                                                                                                break;
                                                                                            case '1':
                                                                                                $verification_level = "Level 1 Verified";
                                                                                                break;
                                                                                            case '2':
                                                                                                $verification_level = "Level 2 Verified";
                                                                                                break;
                                                                                            case '3':
                                                                                                $verification_level = "Level 3 Verified";
                                                                                                break;
                                                                                        }
                                                                                    }
                                                                                    ?>


                                                                                    <?php require_once 'layouts/feedback_message.php'; ?>
                                                                                    <p><a href="<?php echo $_SERVER['HTTP_REFERER']; ?>" class="btn btn-default" title="Go Back"><i class="fa fa-arrow-circle-left"></i> Go Back</a></p>
                                                                                    <p>Below is the detail of the selected client. <strong>Note:</strong> If this client has an account that is flagged, it will display in red.</p>

                                                                                    <!------------- Contact Section --->
                                                                                    <div class="row">
                                                                                        <div class="col-sm-6">
                                                                                            <h5>Client Information</h5>
                                                                                            <span class="span-title text-right">Account Officer</span>
                                                                                            <p class="text-right"><em><?php echo $account_officer_full_name; ?></em></p>
                                                                                            <span class="span-title">Client Name</span>
                                                                                            <p><em><?php echo $full_name; ?></em>&nbsp;&nbsp;
                                                                                                <?php if($client_operation->account_flagged($user_code)) { ?>
                                                                                                    <img src="../images/red-flag.png" alt="" title="This client has an account flagged."> -
                                                                                                    <span class="text-danger"> Scroll down for flag details</span>
                                                                                                <?php } ?>
                                                                                            </p>

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
                                                                                                <div class="col-sm-12">
                                                                                                    <div class="col-sm-4 text-center" style="margin-bottom: 4px !important">
                                                                                                        <span>Identity Card</span>
                                                                                                        <?php if(!empty($client_credential['idcard'])) { ?>
                                                                                                            <a href="../userfiles/<?php echo $client_credential['idcard']; ?>" title="click to enlarge" target="_blank"><img src="../userfiles/<?php echo $client_credential['idcard']; ?>" width="120px" height="120px"/></a>
                                                                                                        <?php } else { ?>
                                                                                                            <img src="../images/placeholder.jpg" alt="" class="img-responsive center-block">
                                                                                                        <?php } ?>
                                                                                                        <!--<a href="" data-toggle="modal" data-target="#myModalCard" class="btn btn-default" style="margin-top: 2px !important">View</a>-->
                                                                                                    </div>
                                                                                                    <br/>
                                                                                                    <div class="col-sm-4 text-center" style="margin-bottom: 4px !important">
                                                                                                        <span>Passport</span>
                                                                                                        <?php if(!empty($client_credential['passport'])) { ?>
                                                                                                            <a href="../userfiles/<?php echo $client_credential['passport']; ?>" title="click to enlarge" target="_blank"><img src="../userfiles/<?php echo $client_credential['passport']; ?>" width="120px" height="120px"/></a>
                                                                                                        <?php } else { ?>
                                                                                                            <img src="../images/placeholder.jpg" alt="" class="img-responsive center-block">
                                                                                                        <?php } ?>
                                                                                                        <!--<a href="" data-toggle="modal" data-target="#myModalPass" class="btn btn-default" style="margin-top: 2px !important">View</a>-->
                                                                                                    </div>
                                                                                                    <br/>
                                                                                                    <div class="col-sm-4 text-center" style="margin-bottom: 4px !important">
                                                                                                        <span>Signature</span>
                                                                                                        <?php if(!empty($client_credential['signature'])) { ?>
                                                                                                            <a href="../userfiles/<?php echo $client_credential['signature']; ?>" title="click to enlarge" target="_blank"><img src="../userfiles/<?php echo $client_credential['signature']; ?>" width="120px" height="120px"/></a>
                                                                                                        <?php } else { ?>
                                                                                                            <img src="../images/placeholder.jpg" alt="" class="img-responsive center-block">
                                                                                                        <?php } ?>
                                                                                                        <!--<a href="" data-toggle="modal" data-target="#myModalSign" class="btn btn-default" style="margin-top: 2px !important">View</a>-->
                                                                                                    </div>
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
                                                                                                            <th>Expired</th>
                                                                                                        </tr>
                                                                                                        </thead>
                                                                                                        <tbody>
                                                                                                        <tr>
                                                                                                            <td><?php if(!empty($total_point_claimed)) { echo $total_point_claimed; } else { echo '0.00'; } ?></td>
                                                                                                            <td><?php if(!empty($total_point_frozen)) { echo $total_point_frozen; } else { echo '0.00'; } ?></td>
                                                                                                            <td><?php if(!empty($client_point_details)) { echo $client_point_details['point_balance']; } else { echo '0.00'; } ?></td>
                                                                                                            <td><?php if(!empty($client_point_details)) { echo $client_point_details['expired_point']; } else { echo '0.00'; } ?></td>
                                                                                                        </tr>
                                                                                                        <tr>
                                                                                                            <td colspan="4">
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
                                                                                                                        echo $ifx_acct_no;
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
                                                                                                                        echo $ifx_acct_no;
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
                                                                                            <hr />

                                                                                            <h5>Account Flag Detail</h5>
                                                                                            <table class="table table-responsive table-striped table-bordered table-hover">
                                                                                                <thead>
                                                                                                <tr>
                                                                                                    <th>Date</th>
                                                                                                    <th>Account Number</th>
                                                                                                    <th>Admin</th>
                                                                                                    <th>Comment</th>
                                                                                                </tr>
                                                                                                </thead>
                                                                                                <tbody>
                                                                                                <?php if($client_flags) { foreach($client_flags as $value) { ?>
                                                                                                    <tr>
                                                                                                        <td><?php echo datetime_to_text2($value['created']); ?></td>
                                                                                                        <td><?php echo $value['ifx_acct_no']; ?></td>
                                                                                                        <td><?php echo $value['admin_full_name']; ?></td>
                                                                                                        <td><?php echo $value['comment']; ?></td>
                                                                                                    </tr>
                                                                                                <?php } } else { ?>
                                                                                                    <tr><td colspan='4' class='text-danger'><em>No results to display</em></td></tr>
                                                                                                <?php } ?>
                                                                                                </tbody>
                                                                                            </table>

                                                                                            <h5>Recent Transactions</h5>
                                                                                            <ul class="nav nav-tabs">
                                                                                                <li class="active"><a data-toggle="tab" href="#latest_funding">Deposit</a></li>
                                                                                                <li><a data-toggle="tab" href="#latest_withdrawal">Withdrawal</a></li>
                                                                                            </ul>
                                                                                            <div class="tab-content">
                                                                                                <div id="latest_funding" class="tab-pane fade in active">
                                                                                                    <table class="table table-responsive table-striped table-bordered table-hover">
                                                                                                        <thead>
                                                                                                        <tr>
                                                                                                            <th>Trans ID</th>
                                                                                                            <th>Acct No</th>
                                                                                                            <th>Amount Ordered</th>
                                                                                                            <th>Status</th>
                                                                                                            <th>Date</th>
                                                                                                        </tr>
                                                                                                        </thead>
                                                                                                        <tbody>
                                                                                                        <?php if(isset($latest_funding) && !empty($latest_funding)) { foreach ($latest_funding as $row) { ?>
                                                                                                            <tr>
                                                                                                                <td><a target="_blank" title="View" href="deposit_search_view.php?id=<?php echo encrypt($row['trans_id']); ?>"><?php echo $row['trans_id']; ?></a></td>
                                                                                                                <td><?php echo $row['ifx_acct_no']; ?></td>
                                                                                                                <td>&dollar; <?php echo $row['dollar_ordered']; ?></td>
                                                                                                                <td><?php echo status_user_deposit($row['status']); ?></td>
                                                                                                                <td><?php echo datetime_to_text($row['created']); ?></td>
                                                                                                            </tr>
                                                                                                        <?php } } else { echo "<tr><td colspan='5' class='text-danger'><em>No results to display</em></td></tr>"; } ?>
                                                                                                        </tbody>
                                                                                                    </table>
                                                                                                </div>
                                                                                                <div id="latest_withdrawal" class="tab-pane fade">
                                                                                                    <table class="table table-responsive table-striped table-bordered table-hover">
                                                                                                        <thead>
                                                                                                        <tr>
                                                                                                            <th>Trans ID</th>
                                                                                                            <th>Acct No</th>
                                                                                                            <th>Amount Ordered</th>
                                                                                                            <th>Status</th>
                                                                                                            <th>Date</th>
                                                                                                        </tr>
                                                                                                        </thead>
                                                                                                        <tbody>
                                                                                                        <?php if(isset($latest_withdrawal) && !empty($latest_withdrawal)) { foreach ($latest_withdrawal as $row) { ?>
                                                                                                            <tr>
                                                                                                                <td><a target="_blank" title="View" href="withdrawal_search_view.php?id=<?php echo encrypt($row['trans_id']); ?>"><?php echo $row['trans_id']; ?></a></td>
                                                                                                                <td><?php echo $row['ifx_acct_no']; ?></td>
                                                                                                                <td>&dollar; <?php echo $row['dollar_withdraw']; ?></td>
                                                                                                                <td><?php echo status_user_withdrawal($row['status']); ?></td>
                                                                                                                <td><?php echo datetime_to_text($row['created']); ?></td>
                                                                                                            </tr>
                                                                                                        <?php } } else { echo "<tr><td colspan='5' class='text-danger'><em>No results to display</em></td></tr>"; } ?>
                                                                                                        </tbody>
                                                                                                    </table>
                                                                                                </div>
                                                                                            </div>

                                                                                        </div>
                                                                                    </div>

                                                                                    <div class="row">
                                                                                        <div class="col-sm-12">
                                                                                            <!-- Display information about the client Education career here -->

                                                                                            <br />
                                                                                            <h5>Education History</h5>
                                                                                            <table class="table table-responsive table-striped table-bordered table-hover">
                                                                                                <thead>
                                                                                                <tr>
                                                                                                    <th>Date</th>
                                                                                                    <th>Course</th>
                                                                                                    <th>Lesson</th>
                                                                                                </tr>
                                                                                                </thead>
                                                                                                <tbody>
                                                                                                <?php if(isset($client_education_history) && !empty($client_education_history)) { foreach ($client_education_history as $row) { ?>
                                                                                                    <tr>
                                                                                                        <td><?php echo datetime_to_text($row['lesson_log_date']); ?></td>
                                                                                                        <td><?php echo $row['course_title']; ?></td>
                                                                                                        <td><?php echo $row['lesson_title']; ?></td>
                                                                                                    </tr>
                                                                                                <?php } } else { echo "<tr><td colspan='3' class='text-danger'><em>No results to display</em></td></tr>"; } ?>
                                                                                                </tbody>
                                                                                            </table>

                                                                                        </div>
                                                                                    </div>

                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                        <div class="modal-footer">
                                                                            <button type="button" name="close" onClick="window.close();" data-dismiss="modal" class="btn btn-danger">Close!</button>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>

                                                            <!--<button type="button" data-target="#transaction_info_<?php /*echo $row['trans_id'];*/?>" data-toggle="modal" class="btn btn-sm btn-info"><i class="glyphicon glyphicon-info-sign"></i></button>-->
                                                            <div id="transaction_info_<?php echo $row['trans_id'];?>" tabindex="-1" role="dialog" aria-hidden="true" class="modal modal-lg fade">
                                                                <div class="modal-dialog">
                                                                    <div class="modal-content">
                                                                        <div class="modal-header">
                                                                            <button type="button" data-dismiss="modal" aria-hidden="true"
                                                                                    class="close">&times;</button>
                                                                            <h4 class="modal-title">CONFIRMED WITHDRAWAL <?php if($page_title) { echo $page_title; } ?></h4></div>
                                                                        <div class="modal-body">
                                                                            <div class="row">
                                                                                <div class="col-sm-12">
                                                                                    <?php require_once 'layouts/feedback_message.php'; ?>

                                                                                    <?php
                                                                                    switch('confirmed') {
                                                                                        case 'initiated': $withdraw_process_initiated = true; $page_title = '- INITIATED'; break;
                                                                                        case 'confirmed': $withdraw_process_confirmed = true; $page_title = '- CONFIRMED'; break;
                                                                                        case 'debited': $withdraw_process_ifx_debited = true; $page_title = '- IFX DEBITED'; break;
                                                                                        default: $no_valid_page = true; break;
                                                                                    }
                                                                                    $trans_detail = $client_operation->get_withdrawal_by_id_full($row['trans_id']);
                                                                                    $trans_remark = $client_operation->get_withdrawal_remark($row['trans_id']);
                                                                                    ?>
                                                                                    <?php
                                                                                    if($withdraw_process_initiated) { include_once 'views/withdraw_process_view_only/withdraw_process_initiated.php'; }
                                                                                    if($withdraw_process_confirmed) { include_once 'views/withdraw_process_view_only/withdraw_process_confirmed.php'; }
                                                                                    if($withdraw_process_ifx_debited) { include_once 'views/withdraw_process_view_only/withdraw_process_ifx_debited.php'; }
                                                                                    ?>

                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                        <div class="modal-footer">
                                                                            <button type="button" name="close" onClick="window.close();" data-dismiss="modal" class="btn btn-danger">Close!</button>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>

                                                        </td>
                                                    </tr>



                                                                                <?php } } else { ?>
                                    <div class="col-sm-12">
                                        <div class="trans_item">
                                            <div class="trans_item_content">
                                                <div class="row">
                                                    <div class="col-sm-12 text-danger"><p><em>There is no result to display</em></p></div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <?php } ?>
                                            </tbody>
                                        </table>
                                    </div>
                                    
                                </div>
                            </div>
                        </div>
                        
                        <?php if(isset($confirmed_withdrawal_requests) && !empty($confirmed_withdrawal_requests)) { ?>
                        <div class="tool-footer text-right">
                            <p class="pull-left">Showing <?php echo $prespagelow . " to " . $prespagehigh . " of " . $numrows; ?> entries</p>
                        </div>
                        <?php } ?>
                        <?php if(isset($confirmed_withdrawal_requests) && !empty($confirmed_withdrawal_requests)) { require_once 'layouts/pagination_links.php'; } ?>
                    </div>

                    <!-- Unique Page Content Ends Here
                    ================================================== -->
                    
                </div>
                
            </div>
        </div>
        <?php require_once 'layouts/footer.php'; ?>
    </body>
</html>