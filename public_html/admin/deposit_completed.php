<?php
require_once("../init/initialize_admin.php");
if (!$session_admin->is_logged_in()) {
    redirect_to("login.php");
}

$one_day = 24 * 60 * 60;
$yesterday = time() - $one_day;
$date_today = date('Y-m-d');
$date_yesterday = date('Y-m-d', $yesterday);

$query = "SELECT SUM(real_dollar_equivalent) AS sum_dollar, SUM(real_naira_confirmed) AS sum_naira FROM user_deposit WHERE status = '8' AND order_complete_time LIKE '$date_today%'";
$result = $db_handle->runQuery($query);
$fetched_data = $db_handle->fetchAssoc($result);
$deposit_today = $fetched_data[0];

$query = "SELECT SUM(real_dollar_equivalent) AS sum_dollar, SUM(real_naira_confirmed) AS sum_naira FROM user_deposit WHERE status = '8' AND order_complete_time LIKE '$date_yesterday%'";
$result = $db_handle->runQuery($query);
$fetched_data = $db_handle->fetchAssoc($result);
$deposit_yesterday = $fetched_data[0];

////
$query = "SELECT ud.trans_id, ud.dollar_ordered, ud.created, ud.naira_total_payable, ud.real_dollar_equivalent, ud.real_naira_confirmed,
        ud.client_naira_notified, ud.client_pay_date, ud.client_reference, ud.client_pay_method,
        ud.client_notified_date, ud.status AS deposit_status, ud.points_claimed_id, u.user_code,
        ui.ifx_acct_no, CONCAT(u.last_name, SPACE(1), u.first_name) AS full_name, u.phone,
        uc.passport, ui.ifxaccount_id, ud.updated, ud.order_complete_time, pbc.dollar_amount AS points_dollar_value
        FROM user_deposit AS ud
        INNER JOIN user_ifxaccount AS ui ON ud.ifxaccount_id = ui.ifxaccount_id
        INNER JOIN user AS u ON ui.user_code = u.user_code
        LEFT JOIN user_credential AS uc ON ui.user_code = uc.user_code
        LEFT JOIN point_based_claimed AS pbc ON ud.points_claimed_id = pbc.point_based_claimed_id
        WHERE ud.status = '8' ORDER BY ud.order_complete_time DESC ";
$numrows = $db_handle->numRows($query);

$rowsperpage = 20;

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
$completed_deposit_requests = $db_handle->fetchAssoc($result);

?>
<!DOCTYPE html>
<html lang="en">
    <head>
        <base target="_self">
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>Instaforex Nigeria | Admin - Completed Deposit</title>
        <meta name="title" content="Instaforex Nigeria | Admin - Completed Deposit" />
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
                            <h4><strong>COMPLETED DEPOSIT</strong></h4>
                        </div>
                    </div>
                    
                    <div class="section-tint super-shadow">
                        <div class="row">
                            <div class="col-sm-12">
                                <p class="text-right"><a href="deposit_completed_filter.php"  class="btn btn-default" title="Deposit Completed - Filter"><i class="fa fa-arrow-circle-right"></i> Deposit Completed - Filter</a></p>
                                <p>Below is a list of Deposit Transactions that have been completed and accounts funded. The table shows
                                the Naira Amount Confirmed and the Equivalent Dollar Funded.</p>
                                <p>
                                    <strong>Deposit Today: </strong>&dollar; <?php echo number_format($deposit_today['sum_dollar'], 2, ".", ","); ?> - &#8358; <?php echo number_format($deposit_today['sum_naira'], 2, ".", ","); ?><br />
                                    <strong>Deposit Yesterday: </strong>&dollar; <?php echo number_format($deposit_yesterday['sum_dollar'], 2, ".", ","); ?> - &#8358; <?php echo number_format($deposit_yesterday['sum_naira'], 2, ".", ","); ?><br />
                                </p>
                                <table class="table table-responsive table-striped table-bordered table-hover">
                                    <thead>
                                        <tr>
                                            <th>Transaction ID</th>
                                            <th>Client Name</th>
                                            <th>IFX Account</th>
                                            <th>Amount Confirmed</th>
                                            <th>Points Claimed</th>
                                            <th>Total Confirmed</th>
                                            <th>Date Created</th>
                                            <th>Order Completed Time</th>
                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php 
                                            if(isset($completed_deposit_requests) && !empty($completed_deposit_requests)) {
                                                foreach ($completed_deposit_requests as $row) {
                                                    $new_updated = date('Y-m-d H:i:s', $row['updated']);
                                        ?>
                                        <tr>
                                            <td><?php echo $row['trans_id']; ?></td>
                                            <td><?php echo $row['full_name']; ?></td>
                                            <td><?php echo $row['ifx_acct_no']; ?></td>
                                            <td class="nowrap">&dollar; <?php echo number_format($row['real_dollar_equivalent'], 2, ".", ","); ?></td>
                                            <td class="nowrap">&dollar; <?php echo number_format($row['points_dollar_value'], 2, ".", ","); ?></td>
                                            <td class="nowrap">&#8358; <?php echo number_format($row['real_naira_confirmed'], 2, ".", ","); ?></td>
                                            <td><?php echo datetime_to_text($row['created']); ?></td>
                                            <td><?php echo datetime_to_text($row['order_complete_time']); ?></td>
                                            <td><a target="_blank" title="View" class="btn btn-info" href="deposit_search_view.php?id=<?php echo encrypt($row['trans_id']); ?>"><i class="glyphicon glyphicon-eye-open icon-white"></i> </a></td>
                                        </tr>
                                        <tr>
                                            <td colspan='9' class="text-right text-success">
                                                <?php
                                                $start  = date_create($row['client_notified_date']);
                                                $end 	= date_create($row['updated']); // Current time and date
                                                $diff  	= date_diff( $start, $end );

                                                $output = '';
                                                $output .= $diff->y . ' years, ';
                                                $output .= $diff->m . ' months, ';
                                                $output .= $diff->d . ' days, ';
                                                $output .= $diff->h . ' hours, ';
                                                $output .= $diff->i . ' minutes, ';
                                                $output .= $diff->s . ' seconds';

                                                ?>
                                                <strong><?php echo $row['trans_id']; ?> Completion Time: <?php echo $output; ?></strong>
                                            </td>
                                        </tr>
                                        <?php } } else { echo "<tr><td colspan='9' class='text-danger'><em>No results to display</em></td></tr>"; } ?>
                                    </tbody>
                                </table>
                                
                                <?php if(isset($completed_deposit_requests) && !empty($completed_deposit_requests)) { ?>
                                <div class="tool-footer text-right">
                                    <p class="pull-left">Showing <?php echo $prespagelow . " to " . $prespagehigh . " of " . $numrows; ?> entries</p>
                                </div>
                                <?php } ?>
                            </div>
                        </div>
                        
                        <?php if(isset($completed_deposit_requests) && !empty($completed_deposit_requests)) { require_once 'layouts/pagination_links.php'; } ?>
                    </div>

                    <!-- Unique Page Content Ends Here
                    ================================================== -->
                    
                </div>
                
            </div>
        </div>
        <?php require_once 'layouts/footer.php'; ?>
    </body>
</html>