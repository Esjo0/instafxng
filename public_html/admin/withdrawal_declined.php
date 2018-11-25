<?php
require_once("../init/initialize_admin.php");
if (!$session_admin->is_logged_in()) {
    redirect_to("login.php");
}

$client_operation = new clientOperation();

$query = "SELECT uw.trans_id, uw.dollar_withdraw, uw.created, uw.naira_total_withdrawable,
        uw.client_phone_password, uw.status AS withdrawal_status,
        CONCAT(u.last_name, SPACE(1), u.first_name) AS full_name, u.phone,
        uc.passport, ui.ifxaccount_id, ui.ifx_acct_no
        FROM user_withdrawal AS uw
        INNER JOIN user_ifxaccount AS ui ON uw.ifxaccount_id = ui.ifxaccount_id
        INNER JOIN user AS u ON ui.user_code = u.user_code
        LEFT JOIN user_credential AS uc ON ui.user_code = uc.user_code
        WHERE uw.status IN ('3', '6', '9') ORDER BY uw.created DESC ";
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
$declined_withdrawal_requests = $db_handle->fetchAssoc($result);

?>
<!DOCTYPE html>
<html lang="en">
    <head>
        <base target="_self">
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>Instaforex Nigeria | Admin - Declined Withdrawal</title>
        <meta name="title" content="Instaforex Nigeria | Admin - Declined Withdrawal" />
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
                            <h4><strong>DECLINED WITHDRAWAL</strong></h4>
                        </div>
                    </div>
                    
                    <div class="section-tint super-shadow">
                        <div class="row">
                            <div class="col-sm-12">                                
                                <p>Below is the list of all declined withdrawal requests.</p>
                                <table class="table table-responsive table-striped table-bordered table-hover">
                                    <thead>
                                    <tr>
                                        <th>Transaction ID</th>
                                        <th>Client Name</th>
                                        <th>IFX Account</th>
                                        <th>Dollar Amount</th>
                                        <th>Amount To Pay</th>
                                        <th>Status</th>
                                        <th>Date Created</th>
                                        <th>Action</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    <?php
                                    if(isset($declined_withdrawal_requests) && !empty($declined_withdrawal_requests)) {
                                        foreach ($declined_withdrawal_requests as $row) {
                                            ?>
                                            <tr>
                                                <td><?php echo $row['trans_id']; ?></td>
                                                <td><?php echo $row['full_name']; ?></td>
                                                <td><?php echo $row['ifx_acct_no']; ?></td>
                                                <td>&dollar; <?php echo $row['dollar_withdraw']; ?></td>
                                                <td>&#8358; <?php echo number_format($row['naira_total_withdrawable'], 2, ".", ","); ?></td>
                                                <td><?php echo status_user_withdrawal($row['withdrawal_status']); ?></td>
                                                <td><?php echo datetime_to_text($row['created']); ?></td>
                                                <td>
                                                    <a target="_blank" title="View" class="btn btn-info" href="withdrawal_search_view.php?id=<?php echo encrypt_ssl($row['trans_id']); ?>"><i class="glyphicon glyphicon-eye-open icon-white"></i> </a>
                                                </td>
                                            </tr>
                                        <?php } } else { echo "<tr><td colspan='7' class='text-danger'><em>No results to display</em></td></tr>"; } ?>
                                    </tbody>
                                </table>
                                
                                <?php if(isset($declined_withdrawal_requests) && !empty($declined_withdrawal_requests)) { ?>
                                <div class="tool-footer text-right">
                                    <p class="pull-left">Showing <?php echo $prespagelow . " to " . $prespagehigh . " of " . $numrows; ?> entries</p>
                                </div>
                                <?php } ?>
                                
                            </div>
                        </div>
                        
                        <?php if(isset($declined_withdrawal_requests) && !empty($declined_withdrawal_requests)) { require_once 'layouts/pagination_links.php'; } ?>
                    </div>

                    <!-- Unique Page Content Ends Here
                    ================================================== -->
                    
                </div>
                
            </div>
        </div>
        <?php require_once 'layouts/footer.php'; ?>
    </body>
</html>