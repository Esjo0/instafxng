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

        // Get client education log history
        $client_education_history = $education_object->get_client_lesson_history($user_code);

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

        $client_flags = $client_operation->get_client_flag_by_code($user_code);

        $total_point = $client_operation->get_loyalty_point_earned($user_code);
        $total_point_claimed = $client_operation->get_loyalty_point_claimed($user_code);
        $total_point_frozen = $client_operation->get_loyalty_point_frozen($user_code);
        $loyalty_point_balance = $total_point - ($total_point_claimed + $total_point_frozen);

        $client_point_details = $obj_loyalty_point->get_user_point_details($user_code);

        $selected_point_frozen_trans_id = $client_operation->get_loyalty_point_frozen_transaction($user_code);

        $last_trade_date = $client_operation->get_last_trade_detail($user_code)['date_earned'];

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

// GET LATEST TRANSACTIONS
$latest_funding = $system_object->get_latest_funding($user_code);

// GET LATEST WITHDRAWALS
$latest_withdrawal = $system_object->get_latest_withdrawal($user_code);

$querys = "SELECT email_flag, created FROM unverified_campaign_mail_log WHERE email = '$email'";
$results = $db_handle->runQuery($querys);
$mail_detailss = $db_handle->fetchAssoc($results);
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
                            <h4><strong>VIEW CLIENT CAMPAIGN EMAIL DETAILS</strong></h4>
                        </div>
                    </div>
                    
                    <div class="section-tint super-shadow">
                        <div class="row">
                            <div class="col-sm-12">
                                <p><a href="<?php echo $_SERVER['HTTP_REFERER']; ?>" class="btn btn-default" title="Go Back"><i class="fa fa-arrow-circle-left"></i> Go Back</a></p>
                                <h5 class="col-sm-12">Client Information</h5>
                                <!------------- Contact Section --->
                                <div class="row">
                                    <div class="col-sm-6">

                                        <span class="span-title">Email Campaign Details</span>
                                        <table class="table table-responsive">
                                            <thead>
                                            <tr>
                                                <th>Mail Recieved</th>
                                                <th>Date Recieved</th>
                                            </tr>
                                            </thead>
                                            <tbody>
                                            <?php
                                            foreach($mail_detailss AS $row_email){?>
                                                <tr>
                                                    <td><?php echo position_status($row_email['email_flag']);?> Mail</td>
                                                    <td><?php echo datetime_to_text($row_email['created']);?></td>
                                                </tr>
                                            <?php }?>
                                            </tbody>
                                        </table>
                                    </div>
                                    <div class="col-sm-6">
                                        <span class="span-title">Client Name</span>
                                        <p class="text-right"><em><?php echo $account_officer_full_name; ?></em></p>
                                        <span class="span-title">Verification Status</span>
                                        <p><?php echo $verification_level; ?></p>
                                        </div>
                                
                                <hr />
                                        <div class="col-sm-12">
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