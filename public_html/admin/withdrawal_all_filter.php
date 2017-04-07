<?php
require_once("../init/initialize_admin.php");
if (!$session_admin->is_logged_in()) {
    redirect_to("login.php");
}

if (isset($_POST['filter_withdrawal']) || isset($_GET['pg'])) {

    if (isset($_POST['filter_withdrawal'])) {
        foreach ($_POST as $key => $value) {
            $_POST[$key] = $db_handle->sanitizePost(trim($value));
        }

        $from_date = $_POST['from_date'];
        $to_date = $_POST['to_date'];

        $query = "SELECT uw.trans_id, uw.dollar_withdraw, uw.created, uw.naira_total_withdrawable,
                uw.client_phone_password, uw.status AS withdrawal_status,
                CONCAT(u.last_name, SPACE(1), u.first_name) AS full_name, u.phone,
                uc.passport, ui.ifxaccount_id, ui.ifx_acct_no
                FROM user_withdrawal AS uw
                INNER JOIN user_ifxaccount AS ui ON uw.ifxaccount_id = ui.ifxaccount_id
                INNER JOIN user AS u ON ui.user_code = u.user_code
                LEFT JOIN user_credential AS uc ON ui.user_code = uc.user_code
                WHERE uw.created BETWEEN '$from_date' AND '$to_date' ORDER BY uw.created DESC ";

        $_SESSION['search_client_query'] = $query;
    } else {
        $query = $_SESSION['search_client_query'];
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
    $all_withdrawal_requests_filter = $db_handle->fetchAssoc($result);
}




?>
<!DOCTYPE html>
<html lang="en">
    <head>
        <base target="_self">
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>Instaforex Nigeria | Admin - All Withdrawal Request</title>
        <meta name="title" content="Instaforex Nigeria | Admin - All Withdrawal Request" />
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
                            <h4><strong>ALL WITHDRAWAL REQUEST</strong></h4>
                        </div>
                    </div>
                    
                    <div class="section-tint super-shadow">
                        <div class="row">
                            <div class="col-sm-12">

                                <p class="text-right"><a href="withdrawal_all.php"  class="btn btn-default" title="All Withdrawals"><i class="fa fa-arrow-circle-left"></i> All Withdrawals</a></p>
                                <p>Select date range below to get withdrawal transactions. Please note that when you filter, the result will be saved, if you
                                    want to get a new result, simply perform another filter.</p>

                                <form data-toggle="validator" class="form-horizontal" role="form" method="post" action="<?php echo $_SERVER['REQUEST_URI']; ?>">
                                    <div class="form-group">
                                        <label class="control-label col-sm-3" for="from_date">From:</label>
                                        <div class="col-sm-9 col-lg-5">
                                            <div class="input-group date">
                                                <input name="from_date" type="text" class="form-control" id="datetimepicker" required>
                                                <span class="input-group-addon"><span class="glyphicon glyphicon-calendar"></span></span>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="control-label col-sm-3" for="to_date">To:</label>
                                        <div class="col-sm-9 col-lg-5">
                                            <div class="input-group date">
                                                <input name="to_date" type="text" class="form-control" id="datetimepicker2" required>
                                                <span class="input-group-addon"><span class="glyphicon glyphicon-calendar"></span></span>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <div class="col-sm-offset-3 col-sm-9"><input name="filter_withdrawal" type="submit" class="btn btn-success" value="Filter" /></div>
                                    </div>
                                    <script type="text/javascript">
                                        $(function () {
                                            $('#datetimepicker, #datetimepicker2').datetimepicker({
                                                format: 'YYYY-MM-DD'
                                            });
                                        });
                                    </script>
                                </form>

                                <hr /><br />

                                <?php if(isset($all_withdrawal_requests_filter) && !empty($all_withdrawal_requests_filter)) { ?>
                                    <div class="tool-footer text-right">
                                        <p class="pull-left">Showing <?php echo $prespagelow . " to " . $prespagehigh . " of " . $numrows; ?> entries</p>
                                    </div>
                                <?php } ?>

                                <?php if(isset($all_withdrawal_requests_filter) && !empty($all_withdrawal_requests_filter)) { require 'layouts/pagination_links.php'; } ?>

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
                                    </tr>
                                    </thead>
                                    <tbody>
                                    <?php
                                    if(isset($all_withdrawal_requests_filter) && !empty($all_withdrawal_requests_filter)) {
                                        foreach ($all_withdrawal_requests_filter as $row) {
                                            ?>
                                            <tr>
                                                <td><?php echo $row['trans_id']; ?></td>
                                                <td><?php echo $row['full_name']; ?></td>
                                                <td><?php echo $row['ifx_acct_no']; ?></td>
                                                <td class="nowrap">&dollar; <?php echo number_format($row['dollar_withdraw'], 2, ".", ","); ?></td>
                                                <td class="nowrap">&#8358; <?php echo number_format($row['naira_total_withdrawable'], 2, ".", ","); ?></td>
                                                <td><?php echo status_user_withdrawal($row['withdrawal_status']); ?></td>
                                                <td><?php echo datetime_to_text($row['created']); ?></td>
                                            </tr>
                                        <?php } } else { echo "<tr><td colspan='7' class='text-danger'><em>No results to display</em></td></tr>"; } ?>
                                    </tbody>
                                </table>
                                
                                <?php if(isset($all_withdrawal_requests_filter) && !empty($all_withdrawal_requests_filter)) { ?>
                                <div class="tool-footer text-right">
                                    <p class="pull-left">Showing <?php echo $prespagelow . " to " . $prespagehigh . " of " . $numrows; ?> entries</p>
                                </div>
                                <?php } ?>
                                
                            </div>
                        </div>
                        
                        <?php if(isset($all_withdrawal_requests_filter) && !empty($all_withdrawal_requests_filter)) { require 'layouts/pagination_links.php'; } ?>
                    </div>

                    <!-- Unique Page Content Ends Here
                    ================================================== -->
                    
                </div>
                
            </div>
        </div>
        <?php require_once 'layouts/footer.php'; ?>
        <script src="//cdnjs.cloudflare.com/ajax/libs/moment.js/2.9.0/moment-with-locales.js"></script>
        <script src="//cdn.rawgit.com/Eonasdan/bootstrap-datetimepicker/e8bddc60e73c1ec2475f827be36e1957af72e2ea/src/js/bootstrap-datetimepicker.js"></script>
    </body>
</html>