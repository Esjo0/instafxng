<?php
require_once("../init/initialize_admin.php");
if (!$session_admin->is_logged_in()) {
    redirect_to("login.php");
}

$get_params = allowed_get_params(['id', 'pm']);
$trans_id_encrypted = $get_params['id'];
$trans_id = decrypt(str_replace(" ", "+", $trans_id_encrypted));
$trans_id = preg_replace("/[^A-Za-z0-9 ]/", '', $trans_id);

$pay_method_encrypted = $get_params['pm'];
$pay_method = decrypt(str_replace(" ", "+", $pay_method_encrypted));
$pay_method = preg_replace("/[^A-Za-z0-9 ]/", '', $pay_method);

if($get_params['id'] && !empty($trans_id) && $get_params['pm'] && !empty($pay_method)) {
    $client_operation = new clientOperation();

    switch ($pay_method) {
        case '1':
            $requery_feedback = $client_operation->requery_webpay_deposit($trans_id);
            break;
        case '10':
            $requery_feedback = $client_operation->requery_paystack_deposit($trans_id);
            break;
    }

    if($requery_feedback) {
        switch ($requery_feedback['type']) {
            case 1: $message_success = $requery_feedback['resp']; break;
            case 2: $message_error = $requery_feedback['resp']; break;
            default: $message_error = "An error occurred, please try again.";
        }
    } else {
        $message_error = "The transaction ID specified could not be found.";
    }
}


if(isset($_POST['search_text']) && strlen($_POST['search_text']) > 3) {
    $search_text = $_POST['search_text'];

    $query = "SELECT ud.trans_id, ud.dollar_ordered, ud.created, ud.naira_total_payable, ud.real_dollar_equivalent, ud.real_naira_confirmed,
        ud.client_naira_notified, ud.client_pay_date, ud.client_reference, ud.client_pay_method,
        ud.client_notified_date, ud.status AS deposit_status, u.user_code,
        ui.ifx_acct_no, CONCAT(u.last_name, SPACE(1), u.first_name) AS full_name, u.phone,
        uc.passport, ui.ifxaccount_id
        FROM user_deposit AS ud
        INNER JOIN user_ifxaccount AS ui ON ud.ifxaccount_id = ui.ifxaccount_id
        INNER JOIN user AS u ON ui.user_code = u.user_code
        LEFT JOIN user_credential AS uc ON ui.user_code = uc.user_code
        WHERE ud.status = '9' AND (ud.trans_id LIKE '%$search_text%' OR ui.ifx_acct_no LIKE '%$search_text%' OR u.email LIKE '%$search_text%' OR u.first_name LIKE '%$search_text%' OR u.middle_name LIKE '%$search_text%' OR u.last_name LIKE '%$search_text%' OR u.phone LIKE '%$search_text%' OR ud.created LIKE '$search_text%') ORDER BY ud.created DESC ";
} else {
    $query = "SELECT ud.trans_id, ud.dollar_ordered, ud.created, ud.naira_total_payable, ud.real_dollar_equivalent, ud.real_naira_confirmed,
        ud.client_naira_notified, ud.client_pay_date, ud.client_reference, ud.client_pay_method,
        ud.client_notified_date, ud.status AS deposit_status, u.user_code,
        ui.ifx_acct_no, CONCAT(u.last_name, SPACE(1), u.first_name) AS full_name, u.phone,
        uc.passport, ui.ifxaccount_id
        FROM user_deposit AS ud
        INNER JOIN user_ifxaccount AS ui ON ud.ifxaccount_id = ui.ifxaccount_id
        INNER JOIN user AS u ON ui.user_code = u.user_code
        LEFT JOIN user_credential AS uc ON ui.user_code = uc.user_code
        WHERE ud.status = '9' ORDER BY ud.created DESC ";
}

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
$failed_deposit_requests = $db_handle->fetchAssoc($result);

// Admin Allowed: Toye, Lekan, Demola, Bunmi, Joshua
$update_allowed = array("FgI5p", "FWJK4", "5xVvl", "43am6", "Vi1DW");
$allowed_requery_button = in_array($_SESSION['admin_unique_code'], $update_allowed) ? true : false;

?>
<!DOCTYPE html>
<html lang="en">
    <head>
        <base target="_self">
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>Instaforex Nigeria | Admin - Failed Deposit</title>
        <meta name="title" content="Instaforex Nigeria | Admin - Failed Deposit" />
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
                            <h4><strong>FAILED DEPOSIT</strong></h4>
                        </div>
                    </div>

                    <div class="search-section">
                        <div class="row">
                            <div class="col-xs-12">
                                <form data-toggle="validator" class="form-horizontal" role="form" method="post" action="deposit_failed.php">
                                    <div class="input-group">
                                        <input type="hidden" name="search_param" value="all" id="search_param">
                                        <input type="text" class="form-control" name="search_text" value="" placeholder="Search term..." required>
                                        <span class="input-group-btn">
                                            <button class="btn btn-default" type="submit"><span class="glyphicon glyphicon-search"></span></button>
                                        </span>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                    
                    <div class="section-tint super-shadow">
                        <div class="row">
                            <div class="col-sm-12">
                                <p class="text-right"><a href="deposit_failed_filter.php"  class="btn btn-default" title="Deposit Failed - Filter"><i class="fa fa-arrow-circle-right"></i> Deposit Failed - Filter</a></p>

                                <?php require_once 'layouts/feedback_message.php'; ?>
                                
                                <p>Below is the list of all failed deposit requests.</p>
                                <table class="table table-responsive table-striped table-bordered table-hover">
                                    <thead>
                                    <tr>
                                        <th>Transaction ID</th>
                                        <th>Client Name</th>
                                        <th>IFX Account</th>
                                        <th>Amount Ordered</th>
                                        <th>Total Payable</th>
                                        <th>Date Created</th>
                                        <th>Action</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    <?php
                                    if(isset($failed_deposit_requests) && !empty($failed_deposit_requests)) {
                                        foreach ($failed_deposit_requests as $row) {
                                            ?>
                                            <tr>
                                                <td><?php echo $row['trans_id']; ?></td>
                                                <td><?php echo $row['full_name']; ?></td>
                                                <td><?php echo $row['ifx_acct_no']; ?></td>
                                                <td>&dollar; <?php echo $row['dollar_ordered']; ?></td>
                                                <td>&#8358; <?php echo number_format($row['naira_total_payable'], 2, ".", ","); ?></td>
                                                <td><?php echo datetime_to_text($row['created']); ?></td>
                                                <td class="nowrap">
                                                    <a title="Comment" class="btn btn-success" href="deposit_failed_comment.php?x=<?php echo encrypt($row['trans_id']); ?>&pg=<?php echo $currentpage; ?>"><i class="glyphicon glyphicon-comment icon-white"></i> </a>
                                                    <a target="_blank" title="View" class="btn btn-info" href="deposit_search_view.php?id=<?php echo encrypt($row['trans_id']); ?>"><i class="glyphicon glyphicon-eye-open icon-white"></i> </a>

                                                    <?php if($allowed_requery_button) { ?>
                                                    <a title="Re-Query" class="btn btn-default" href="deposit_failed.php?id=<?php echo encrypt($row['trans_id']); ?>&pm=<?php echo encrypt($row['client_pay_method']); ?>"><i class="fa fa-question icon-white"></i> </a>
                                                    <?php } ?>
                                                </td>
                                            </tr>
                                        <?php } } else { echo "<tr><td colspan='7' class='text-danger'><em>No results to display</em></td></tr>"; } ?>
                                    </tbody>
                                </table>
                                
                                <?php if(isset($failed_deposit_requests) && !empty($failed_deposit_requests)) { ?>
                                <div class="tool-footer text-right">
                                    <p class="pull-left">Showing <?php echo $prespagelow . " to " . $prespagehigh . " of " . $numrows; ?> entries</p>
                                </div>
                                <?php } ?>
                                
                            </div>
                        </div>
                        
                        <?php if(isset($failed_deposit_requests) && !empty($failed_deposit_requests)) { require_once 'layouts/pagination_links.php'; } ?>
                    </div>

                    <!-- Unique Page Content Ends Here
                    ================================================== -->
                    
                </div>
                
            </div>
        </div>
        <?php require_once 'layouts/footer.php'; ?>
    </body>
</html>