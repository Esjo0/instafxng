<?php
require_once("../init/initialize_admin.php");
if (!$session_admin->is_logged_in()) {
    redirect_to("login.php");
}

$get_params = allowed_get_params(['id']);
$trans_id_encrypted = $get_params['id'];
$trans_id = decrypt(str_replace(" ", "+", $trans_id_encrypted));
$trans_id = preg_replace("/[^A-Za-z0-9 ]/", '', $trans_id);

$query = "SELECT ud.trans_id, ud.dollar_ordered, ud.created, ud.naira_total_payable, ud.real_dollar_equivalent, ud.real_naira_confirmed,
        ud.client_naira_notified, ud.client_pay_date, ud.client_reference, ud.client_pay_method, ud.naira_equivalent_dollar_ordered,
        ud.client_notified_date, ud.points_claimed_id, ud.transfer_reference, ud.status AS deposit_status, u.user_code, ud.updated,
        ui.ifx_acct_no, CONCAT(u.last_name, SPACE(1), u.first_name) AS full_name, u.phone, u.email
        FROM user_deposit AS ud
        INNER JOIN user_ifxaccount AS ui ON ud.ifxaccount_id = ui.ifxaccount_id
        INNER JOIN user AS u ON ui.user_code = u.user_code
        WHERE ud.trans_id = '$trans_id'";
$result = $db_handle->runQuery($query);

if($db_handle->numOfRows($result) > 0) {
    // result found, display transaction details
    $fetched_data = $db_handle->fetchAssoc($result);
    $trans_detail = $fetched_data[0];
    extract($trans_detail);

    $client_operation = new clientOperation();
    $trans_remark = $client_operation->get_deposit_remark($trans_id);

    if(!empty($points_claimed_id)) {
        $query = "SELECT dollar_amount FROM point_based_claimed WHERE point_based_claimed_id = $points_claimed_id LIMIT 1";
        $result = $db_handle->runQuery($query);
        $fetched_data = $db_handle->fetchAssoc($result);
        $point_dollar_amount = $fetched_data[0]['dollar_amount'];
    }
} else {
    // result not found, redirect back to search page
    redirect_to('deposit_search.php');
}

?>
<!DOCTYPE html>
<html lang="en">
    <head>
        <base target="_self">
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>Instaforex Nigeria | Admin - View Deposit Detail</title>
        <meta name="title" content="Instaforex Nigeria | Admin - View Deposit Detail" />
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
                            <h4><strong>DEPOSIT - FULL DETAILS</strong></h4>
                        </div>
                    </div>
                    
                    <div class="section-tint super-shadow">
                        <div class="row">
                            <div class="col-sm-12">
                                <p><a href="deposit_search.php" class="btn btn-default" title="Deposit Search"><i class="fa fa-arrow-circle-left"></i> Go Back</a></p>
                                <p>Below is the detail of the selected transaction.</p>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-7">
                                <table class="table table-responsive table-striped table-bordered table-hover">
                                    <thead>
                                        <tr><th> </th><th> </th></tr>
                                    </thead>
                                    <tbody>
                                        <tr><td>Transaction ID</td><td><?php if(isset($trans_id)) { echo $trans_id; } ?></td></tr>
                                        <tr><td>Created</td><td><?php if(isset($created)) { echo datetime_to_text($created); } ?></td></tr>
                                        <tr><td>Account Number</td><td><?php if(isset($ifx_acct_no)) { echo $ifx_acct_no; } ?></td></tr>
                                        <tr><td>Client Name</td><td><?php if(isset($full_name)) { echo $full_name; } ?></td></tr>
                                        <tr><td>Client Phone</td><td><?php if(isset($phone)) { echo $phone; } ?></td></tr>
                                        <tr><td>Client Email</td><td><?php if(isset($email)) { echo $email; } ?></td></tr>
                                        <tr><td>Amount Ordered (&dollar;)</td><td><?php if(isset($dollar_ordered)) { echo $dollar_ordered; } ?></td></tr>
                                        <tr><td>Equivalent (&#8358;) - Without Charges</td><td><?php if(isset($naira_equivalent_dollar_ordered)) { echo number_format($naira_equivalent_dollar_ordered, 2, ".", ","); } ?></td></tr>
                                        <tr><td title="After adding all the charges">Amount To Pay (&#8358;)</td><td><?php if(isset($naira_total_payable)) { echo number_format($naira_total_payable, 2, ".", ","); } ?></td></tr>
                                        <tr><td>Amount Paid</td><td><?php if(isset($client_naira_notified)) { echo number_format($client_naira_notified, 2, ".", ","); } ?></td></tr>
                                        <tr><td>Payment Date</td><td><?php if(isset($client_pay_date)) { echo $client_pay_date; } ?></td></tr>
                                        <tr><td>Payment Method</td><td><?php if(isset($client_pay_method)) { echo status_user_deposit_pay_method($client_pay_method); } ?></td></tr>
                                        <tr><td>Payment Confirmed (&#8358;)</td><td><?php if(isset($real_naira_confirmed)) { echo number_format($real_naira_confirmed, 2, ".", ","); } ?></td></tr>
                                        <tr><td class="text-success"><strong>Equivalent (&dollar;) - Amount Funded</strong></td><td class="text-success"><strong><?php if(isset($real_dollar_equivalent)) { echo $real_dollar_equivalent; } ?></strong></td></tr>
                                        <?php if(isset($point_dollar_amount)) { ?><tr><td class="text-success"><strong>Points Claimed (&dollar;)</strong></td><td class="text-success"><strong><?php if(isset($point_dollar_amount)) { echo $point_dollar_amount; } ?></strong></td></tr><?php } ?>
                                        <tr><td>Transfer Reference</td><td><?php if(isset($transfer_reference)) { echo $transfer_reference; } ?></td></tr>
                                        <tr><td>Status</td><td><?php if(isset($deposit_status)) { echo status_user_deposit($deposit_status); } ?></td></tr>
                                        <tr><td>Last Updated</td><td><?php if(isset($updated)) { echo datetime_to_text($updated); } ?></td></tr>
                                    </tbody>
                                </table>
                            </div>

                            <div class="col-md-5">
                                <h5>Admin Remarks</h5>
                                <div style="max-height: 550px; overflow: auto;">
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