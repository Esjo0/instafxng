<?php
require_once("../init/initialize_admin.php");
if (!$session_admin->is_logged_in()) {
    redirect_to("login.php");
}

$current_year = date('Y');
$main_current_month = date('m');

$from_date = date('Y-m-d', strtotime('first day of this month'));
$to_date = date('Y-m-d', strtotime('last day of this month'));

$query = "SELECT commission_target, deposit_target FROM admin_targets WHERE year = '$current_year' AND period = '$main_current_month' AND status = '1' AND type = '2' LIMIT 1";
$result = $db_handle->runQuery($query);
$selected_targets = $db_handle->fetchAssoc($result)[0];
$commission_target = $selected_targets['commission_target'];
$deposit_target = $selected_targets['deposit_target'];

$query = "SELECT SUM(td.commission) AS sum_commission, u.user_code
    FROM trading_commission AS td
    INNER JOIN user_ifxaccount AS ui ON td.ifx_acct_no = ui.ifx_acct_no
    INNER JOIN user AS u ON ui.user_code = u.user_code 
    WHERE date_earned BETWEEN '$from_date' AND '$to_date'";
$result = $db_handle->runQuery($query);
$data_analysis = $db_handle->fetchAssoc($result);
$sum_of_commission = $data_analysis[0]['sum_commission'];

$query = "SELECT SUM(ud.real_dollar_equivalent) AS sum_deposit, u.user_code
    FROM trading_commission AS td
    INNER JOIN user_ifxaccount AS ui ON td.ifx_acct_no = ui.ifx_acct_no
    INNER JOIN user AS u ON ui.user_code = u.user_code 
    LEFT JOIN user_deposit AS ud ON ui.ifxaccount_id = ud.ifxaccount_id
    WHERE ud.status = '8' AND (STR_TO_DATE(ud.order_complete_time, '%Y-%m-%d') BETWEEN '$from_date' AND '$to_date') AND date_earned BETWEEN '$from_date' AND '$to_date'";
$result = $db_handle->runQuery($query);
$data_analysis = $db_handle->fetchAssoc($result);
$sum_of_funding = $data_analysis[0]['sum_deposit'];

$query = "SELECT SUM(td.commission) AS sum_commission, u.email, u.phone, u.created,
    CONCAT(u.last_name, SPACE(1), u.first_name) AS full_name, u.user_code, MIN(td.date_earned) AS first_trade, MAX(td.date_earned) AS last_trade
    FROM trading_commission AS td
    INNER JOIN user_ifxaccount AS ui ON td.ifx_acct_no = ui.ifx_acct_no
    INNER JOIN user AS u ON ui.user_code = u.user_code
    WHERE date_earned BETWEEN '$from_date' AND '$to_date' ";

if(isset($_POST['search_text']) && strlen($_POST['search_text']) > 3) {
    $search_text = $_POST['search_text'];
    $query .= "AND (td.ifx_acct_no LIKE '%$search_text%' OR u.email LIKE '%$search_text%' OR u.first_name LIKE '%$search_text%' OR u.middle_name LIKE '%$search_text%' OR u.last_name LIKE '%$search_text%' OR u.phone LIKE '%$search_text%' OR td.date_earned LIKE '$search_text%') ";
}

$query .= "GROUP BY u.email ORDER BY last_trade DESC ";

$numrows = $db_handle->numRows($query);

// For search, make rows per page equal total rows found, meaning, no pagination
// for search results
if (isset($_POST['search_text'])) {
    $rowsperpage = $numrows;
} else {
    $rowsperpage = 20;
}

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
$clients_activity = $db_handle->fetchAssoc($result);

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <base target="_self">
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Instaforex Nigeria | Admin - Month Activity</title>
    <meta name="title" content="Instaforex Nigeria | Admin - Month Activity" />
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
            <div class="search-section">
                <div class="row">
                    <div class="col-xs-12">
                        <form data-toggle="validator" class="form-horizontal" role="form" method="post" action="<?php echo $REQUEST_URI; ?>">
                            <div class="input-group">
                                <input type="hidden" name="search_param" value="all" id="search_param">
                                <input type="text" class="form-control" name="search_text" placeholder="Search term..." required>
                                <span class="input-group-btn">
                                            <button class="btn btn-default" type="submit"><span class="glyphicon glyphicon-search"></span></button>
                                        </span>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-sm-12 text-danger">
                    <h4><strong>CLIENTS - MONTH ACTIVITY</strong></h4>
                </div>
            </div>

            <div class="section-tint super-shadow">
                <div class="row">
                    <div class="col-sm-12">
                        <div><a href="client_retention.php" class="btn btn-default" title="Help"><i class="fa fa-arrow-circle-left"></i> Client Retention Tracker</a></div>

                        <?php require_once 'layouts/feedback_message.php'; ?>

                        <p>The table below displays the list of clients that are trading this month.</p>

                        <?php if(isset($numrows)) { ?>
                            <p>
                                <strong>Result Found: </strong><?php echo number_format($numrows); ?><br />
                                <strong>Commission: </strong>&dollar; <?php echo number_format($sum_of_commission, 2, ".", ","); ?> (Target: &dollar; <?php echo number_format($commission_target,2, ".", ","); ?>)<br />
                                <strong>Deposit: </strong>&dollar; <?php echo number_format($sum_of_funding,2, ".", ","); ?> (Target: &dollar; <?php echo number_format($deposit_target,2, ".", ","); ?>)
                            </p>
                        <?php } ?>

                        <?php if(isset($clients_activity) && !empty($clients_activity)) { require 'layouts/pagination_links.php'; } ?>

                        <table class="table table-responsive table-striped table-bordered table-hover">
                            <thead>
                            <tr>
                                <th>Client Detail</th>
                                <th>Commission</th>
                                <th>Funding</th>
                                <th>First Trade</th>
                                <th>Last Trade</th>
                                <th>Action</th>
                            </tr>
                            </thead>
                            <tbody>
                            <?php
                            if(isset($clients_activity) && !empty($clients_activity)) { foreach ($clients_activity as $row) {
                                $sum_funding = $obj_analytics->get_client_funding_in_period($row['user_code'], $from_date, $to_date);
                                ?>
                                <tr>
                                    <td>
                                        <?php echo $row['full_name']; ?><br />
                                        <?php echo $row['email']; ?><br />
                                        <?php echo $row['phone']; ?>
                                    </td>
                                    <td>&dollar; <?php echo number_format($row['sum_commission'], 2, ".", ","); ?></td>
                                    <td>&dollar; <?php echo number_format($sum_funding, 2, ".", ","); ?></td>
                                    <td><?php echo datetime_to_text2($row['first_trade']); ?></td>
                                    <td><?php echo datetime_to_text2($row['last_trade']); ?></td>
                                    <td nowrap="nowrap">
                                        <a title="View" target="_blank" class="btn btn-info" href="client_detail.php?id=<?php echo encrypt($row['user_code']); ?>"><i class="glyphicon glyphicon-eye-open icon-white"></i> </a>
                                        <a title="Comment" target="_blank" class="btn btn-success" href="sales_contact_view.php?x=<?php echo encrypt($row['user_code']); ?>&r=<?php echo 'client_month_activity'; ?>&c=<?php echo encrypt('CLIENT MONTH ACTIVITY'); ?>&pg=<?php echo $currentpage; ?>"><i class="glyphicon glyphicon-comment icon-white"></i> </a>
                                        <a title="Send Email" target="_blank" class="btn btn-primary" href="campaign_email_single.php?name=<?php $name = $row['full_name']; echo encrypt_ssl($name) . '&email=' . encrypt_ssl($row['email']); ?>"><i class="glyphicon glyphicon-envelope"></i></a>
                                        <a title="Send SMS" target="_blank" class="btn btn-success" href="campaign_sms_single.php?lead_phone=<?php echo encrypt_ssl($row['phone']) ?>"><i class="glyphicon glyphicon-phone-alt"></i></a>
                                    </td>
                                </tr>
                            <?php } } else { echo "<tr><td colspan='6' class='text-danger'><em>No results to display</em></td></tr>"; } ?>
                            </tbody>
                        </table>

                        <?php if(isset($clients_activity) && !empty($clients_activity)) { ?>
                            <div class="tool-footer text-right">
                                <p class="pull-left">Showing <?php echo $prespagelow . " to " . $prespagehigh . " of " . $numrows; ?> entries</p>
                            </div>
                        <?php } ?>
                    </div>
                </div>

                <?php if(isset($clients_activity) && !empty($clients_activity)) { require 'layouts/pagination_links.php'; } ?>
            </div>

            <!-- Unique Page Content Ends Here
            ================================================== -->

        </div>

    </div>
</div>
<?php require_once 'layouts/footer.php'; ?>
</body>
</html>