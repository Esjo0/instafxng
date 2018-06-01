<?php
require_once("../init/initialize_admin.php");
if (!$session_admin->is_logged_in()) {
    redirect_to("login.php");
}

$client_operation = new clientOperation();

/*$query = "SELECT ud.trans_id, ud.dollar_ordered, ud.created, ud.naira_total_payable,
        ui.ifx_acct_no, CONCAT(u.last_name, SPACE(1), u.first_name) AS full_name, u.phone,
        uc.passport, ui.ifxaccount_id
        FROM user_deposit AS ud
        INNER JOIN user_ifxaccount AS ui ON ud.ifxaccount_id = ui.ifxaccount_id
        INNER JOIN user AS u ON ui.user_code = u.user_code
        LEFT JOIN user_credential AS uc ON ui.user_code = uc.user_code
        WHERE ud.status = '1' ORDER BY ud.user_deposit_id DESC ";*/
$query = "SELECT ud.trans_id, ud.dollar_ordered, ud.created, ud.naira_total_payable,
        ui.ifx_acct_no, CONCAT(u.last_name, SPACE(1), u.first_name) AS full_name, u.phone,
        uc.passport, ui.ifxaccount_id, MAX(ud.user_deposit_id) AS user_deposit_id , u.user_code 
        FROM user_deposit AS ud
        INNER JOIN user_ifxaccount AS ui ON ud.ifxaccount_id = ui.ifxaccount_id
        INNER JOIN user AS u ON ui.user_code = u.user_code
        LEFT JOIN user_credential AS uc ON ui.user_code = uc.user_code
        WHERE ud.status = '1' 
        GROUP BY u.user_code 
        ORDER BY ud.user_deposit_id DESC ";
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
$pending_deposit_requests = $db_handle->fetchAssoc($result);

function get_pending_transactions($user_code)
{
    global $db_handle;
    $query = "SELECT ud.trans_id, ud.dollar_ordered, ud.created, ud.naira_total_payable, ui.ifx_acct_no, ui.ifxaccount_id, ud.user_deposit_id, u.user_code 
FROM user_deposit AS ud
INNER JOIN user_ifxaccount AS ui ON ud.ifxaccount_id = ui.ifxaccount_id
INNER JOIN user AS u ON ui.user_code = u.user_code
WHERE ud.status = '1' 
AND u.user_code = '$user_code' 
ORDER BY ud.user_deposit_id DESC ";
    $result = $db_handle->fetchAssoc($db_handle->runQuery($query));
    return $result;
}

function bulk_sms_url($from, $to)
{
    global $db_handle;
    $query = "SELECT ud.trans_id, ud.dollar_ordered, ud.created, ud.naira_total_payable,
        ui.ifx_acct_no, CONCAT(u.last_name, SPACE(1), u.first_name) AS full_name, u.phone,
        uc.passport, ui.ifxaccount_id, MAX(ud.user_deposit_id) AS user_deposit_id , u.user_code 
        FROM user_deposit AS ud
        INNER JOIN user_ifxaccount AS ui ON ud.ifxaccount_id = ui.ifxaccount_id
        INNER JOIN user AS u ON ui.user_code = u.user_code
        LEFT JOIN user_credential AS uc ON ui.user_code = uc.user_code
        WHERE ud.status = '1' 
        GROUP BY u.user_code 
        ORDER BY ud.user_deposit_id DESC ";
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <base target="_self">
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Instaforex Nigeria | Admin - Pending Deposit</title>
    <meta name="title" content="Instaforex Nigeria | Admin - Pending Deposit" />
    <meta name="keywords" content="" />
    <meta name="description" content="" />
    <?php require_once 'layouts/head_meta.php'; ?>
    <script src="operations_comment.js"></script>
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
                    <h4><strong>PENDING DEPOSIT</strong></h4>
                </div>
            </div>
            <div class="section-tint super-shadow">
                <div class="row">
                    <div class="col-sm-12">
                        <?php require_once 'layouts/feedback_message.php'; ?>
                        <p class="pull-right"><a class="btn btn-sm btn-default">Send Bulk SMS For <?php echo  date_to_text(date('Y-m-d'));?>  <i class="glyphicon glyphicon-arrow-right"></i></a>
                        <p>Deposit Transactions Initiated by Clients. These transactions have not been notified.</p>

                            <?php if(isset($pending_deposit_requests) && !empty($pending_deposit_requests)) {foreach ($pending_deposit_requests as $row) {?>
                        <table class="table table-responsive table-striped table-hover">
                            <thead>
                                <tr>
                                    <th>Client Details</th>
                                    <th>Transaction ID</th>
                                    <th>IFX Account</th>
                                    <th>Amount Ordered</th>
                                    <th>Total Payable</th>
                                    <th>Date Created</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php $transactions = get_pending_transactions($row['user_code']);?>
                                    <tr>
                                        <td rowspan="<?php echo count(get_pending_transactions($row['user_code'])); ?>"><?php echo $row['full_name']; ?><br/><?php echo $row['phone']; ?></td>
                                        <td><?php echo $transactions[0]['trans_id']; ?></td>
                                        <td><?php echo $transactions[0]['ifx_acct_no']; ?></td>
                                        <td class="nowrap">&dollar; <?php echo number_format($transactions[0]['dollar_ordered'], 2, ".", ","); ?></td>
                                        <td class="nowrap">&#8358; <?php echo number_format($transactions[0]['naira_total_payable'], 2, ".", ","); ?></td>
                                        <td><?php echo datetime_to_text($transactions[0]['created']); ?></td>
                                        <td class="nowrap">
                                            <a class="btn btn-xs btn-info" href="deposit_pay_notify.php?id=<?php echo encrypt($transactions[0]['trans_id']); ?>" title="Payment Notification"><i class="fa fa-bell-o" aria-hidden="true"></i></a>
                                            <a class="btn btn-xs btn-info" href="deposit_process.php?x=pending&id=<?php echo encrypt($transactions[0]['trans_id']); ?>" title="Comment"><i class="fa fa-comments-o" aria-hidden="true"></i></a>
                                        </td>
                                    </tr>
                                <?php unset($transactions[0]);?>
                                <?php foreach ($transactions as $key){ ?>
                                    <tr>
                                        <td><?php echo $key['trans_id']; ?></td>
                                        <td><?php echo $key['ifx_acct_no']; ?></td>
                                        <td class="nowrap">&dollar; <?php echo number_format($key['dollar_ordered'], 2, ".", ","); ?></td>
                                        <td class="nowrap">&#8358; <?php echo number_format($key['naira_total_payable'], 2, ".", ","); ?></td>
                                        <td><?php echo datetime_to_text($key['created']); ?></td>
                                        <td class="nowrap">
                                            <a class="btn btn-xs btn-info" href="deposit_pay_notify.php?id=<?php echo encrypt($key['trans_id']); ?>" title="Payment Notification"><i class="fa fa-bell-o" aria-hidden="true"></i></a>
                                            <a class="btn btn-xs btn-info" href="deposit_process.php?x=pending&id=<?php echo encrypt($key['trans_id']); ?>" title="Comment"><i class="fa fa-comments-o" aria-hidden="true"></i></a>
                                        </td>
                                    </tr>
                                    <?php } ?>
                            </tbody>
                        </table><br/><br/>
                                <?php } } else { echo "<table><thead><th>Client Details</th><th>Transaction ID</th><th>IFX Account</th><th>Amount Ordered</th><th>Total Payable</th><th>Date Created</th><th>Action</th></thead><tbody><tr><td colspan='7' class='text-danger'><em>No results to display</em></td></tr></tbody></table>"; } ?>

                        <?php if(isset($pending_deposit_requests) && !empty($pending_deposit_requests)) { ?>
                            <div class="tool-footer text-right">
                                <p class="pull-left">Showing <?php echo $prespagelow . " to " . $prespagehigh . " of " . $numrows; ?> entries</p>
                            </div>
                        <?php } ?>
                    </div>
                </div>
                <?php if(isset($pending_deposit_requests) && !empty($pending_deposit_requests)) { require_once 'layouts/pagination_links.php'; } ?>
            </div>

            <!-- Unique Page Content Ends Here
            ================================================== -->

        </div>

    </div>
</div>
<?php require_once 'layouts/footer.php'; ?>
</body>
</html>