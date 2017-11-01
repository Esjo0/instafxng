<?php
require_once("../init/initialize_admin.php");
if (!$session_admin->is_logged_in()) {
    redirect_to("login.php");
}

////
if (isset($_POST['filter_deposit']) || isset($_GET['pg'])) {

    if (isset($_POST['filter_deposit'])) {

        foreach ($_POST as $key => $value) {
            $_POST[$key] = $db_handle->sanitizePost(trim($value));
        }

        $from_date = $_POST['from_date'];
        $to_date = $_POST['to_date'];

        $query = "SELECT ud.trans_id, ud.dollar_ordered, ud.created, ud.naira_total_payable, ud.real_dollar_equivalent, ud.real_naira_confirmed,
            ud.client_naira_notified, ud.client_pay_date, ud.client_reference, ud.client_pay_method,
            ud.client_notified_date, ud.status AS deposit_status, u.user_code,
            ui.ifx_acct_no, CONCAT(u.last_name, SPACE(1), u.first_name) AS full_name, u.phone,
            uc.passport, ui.ifxaccount_id, ud.updated
            FROM user_deposit AS ud
            INNER JOIN user_ifxaccount AS ui ON ud.ifxaccount_id = ui.ifxaccount_id
            INNER JOIN user AS u ON ui.user_code = u.user_code
            LEFT JOIN user_credential AS uc ON ui.user_code = uc.user_code
            WHERE ud.status = '8' AND ud.created BETWEEN '$from_date' AND '$to_date' ORDER BY ud.updated DESC ";

        $_SESSION['search_client_query'] = $query;
    } else
        {
        $query = $_SESSION['search_client_query'];
    }

    $result = $db_handle->runQuery($query);
    $completed_deposit_requests_filter_export = $db_handle->fetchAssoc($result);

    $numrows = $db_handle->numRows($query);

    $rowsperpage = 20;

    $totalpages = ceil($numrows / $rowsperpage);
    // get the current page or set a default
    if (isset($_GET['pg']) && is_numeric($_GET['pg'])) {
        $currentpage = (int)$_GET['pg'];
    } else {
        $currentpage = 1;
    }
    if ($currentpage > $totalpages) {
        $currentpage = $totalpages;
    }
    if ($currentpage < 1) {
        $currentpage = 1;
    }

    $prespagelow = $currentpage * $rowsperpage - $rowsperpage + 1;
    $prespagehigh = $currentpage * $rowsperpage;
    if ($prespagehigh > $numrows) {
        $prespagehigh = $numrows;
    }

    $offset = ($currentpage - 1) * $rowsperpage;
    $query .= 'LIMIT ' . $offset . ',' . $rowsperpage;
    $result = $db_handle->runQuery($query);
    $completed_deposit_requests_filter = $db_handle->fetchAssoc($result);
}

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
        <script src="//cdn.jsdelivr.net/alasql/0.3/alasql.min.js"></script>
        <script src="//cdnjs.cloudflare.com/ajax/libs/xlsx/0.7.12/xlsx.core.min.js"></script>

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

                                <p class="text-right"><a href="deposit_completed.php"  class="btn btn-default" title="Completed Deposit"><i class="fa fa-arrow-circle-left"></i> Completed Deposit</a></p>
                                <p>Select date range below to get deposit transactions. Please note that when you filter, the result will be saved, if you
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
                                        <div class="col-sm-offset-3 col-sm-9"><input name="filter_deposit" type="submit" class="btn btn-success" value="Filter" /></div>
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

                                <?php if(isset($completed_deposit_requests_filter) && !empty($completed_deposit_requests_filter)) { ?>
                                    <div class="tool-footer text-right">
                                        <p class="pull-left">Showing <?php echo $prespagelow . " to " . $prespagehigh . " of " . $numrows; ?> entries</p>
                                    </div>
                                <?php } ?>

                                <?php
                                if(isset($completed_deposit_requests_filter) && !empty($completed_deposit_requests_filter)) :
                                ?>
                                    require 'layouts/pagination_links.php';
                                <center><button type="button" class="btn btn-sm btn-info" onclick="window.exportExcel()">Export table to Excel</button></center>
                                <br/>
                                <?php endif; ?>

                                <br/>

                                <table  class="table table-responsive table-striped table-bordered table-hover">
                                    <thead>
                                        <tr>
                                            <th>Transaction ID</th>
                                            <th>Client Name</th>
                                            <th>IFX Account</th>
                                            <th>Amount Funded</th>
                                            <th>Total Confirmed</th>
                                            <th>Date Created</th>
                                            <th>Last Updated</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php 
                                            if(isset($completed_deposit_requests_filter) && !empty($completed_deposit_requests_filter)) {
                                                foreach ($completed_deposit_requests_filter as $row) {
                                        ?>
                                        <tr>
                                            <td><?php echo $row['trans_id']; ?></td>
                                            <td><?php echo $row['full_name']; ?></td>
                                            <td><?php echo $row['ifx_acct_no']; ?></td>
                                            <td class="nowrap">&dollar; <?php echo number_format($row['real_dollar_equivalent'], 2, ".", ","); ?></td>
                                            <td class="nowrap">&#8358; <?php echo number_format($row['real_naira_confirmed'], 2, ".", ","); ?></td>
                                            <td><?php echo datetime_to_text($row['created']); ?></td>
                                            <td><?php echo datetime_to_text($row['updated']); ?></td>
                                        </tr>
                                        <?php } } else { echo "<tr><td colspan='7' class='text-danger'><em>No results to display</em></td></tr>"; } ?>
                                    </tbody>
                                </table>


                                <div class="container-fluid" style="display: none">
                                    <table id="dvTable" class="table table-responsive table-striped table-bordered table-hover">
                                        <thead>
                                        <tr>
                                            <th>Transaction ID</th>
                                            <th>Client Name</th>
                                            <th>IFX Account</th>
                                            <th>Amount Funded</th>
                                            <th>Total Confirmed</th>
                                            <th>Date Created</th>
                                            <th>Last Updated</th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                        <?php
                                        if(isset($completed_deposit_requests_filter_export) && !empty($completed_deposit_requests_filter_export)) {
                                            foreach ($completed_deposit_requests_filter_export as $row) {
                                                ?>
                                                <tr>
                                                    <td><?php echo $row['trans_id']; ?></td>
                                                    <td><?php echo $row['full_name']; ?></td>
                                                    <td><?php echo $row['ifx_acct_no']; ?></td>
                                                    <td class="nowrap">&dollar; <?php echo number_format($row['real_dollar_equivalent'], 2, ".", ","); ?></td>
                                                    <td class="nowrap">&#8358; <?php echo number_format($row['real_naira_confirmed'], 2, ".", ","); ?></td>
                                                    <td><?php echo datetime_to_text($row['created']); ?></td>
                                                    <td><?php echo datetime_to_text($row['updated']); ?></td>
                                                </tr>
                                            <?php } } else { echo "<tr><td colspan='7' class='text-danger'><em>No results to display</em></td></tr>"; } ?>
                                        </tbody>
                                    </table>
                                </div>

                                <script>
                                    window.exportExcel =     function exportExcel()
                                    {
                                        var filename = 'deposit_completed_filter'+Math.floor(Date.now() / 1000);
                                        alasql('SELECT * INTO XLSX("'+filename+'.xlsx",{headers:true}) FROM HTML("#dvTable",{headers:true})');
                                    }
                                </script>
                                <?php if(isset($completed_deposit_requests_filter) && !empty($completed_deposit_requests_filter)) { ?>
                                <div class="tool-footer text-right">
                                    <p class="pull-left">Showing <?php echo $prespagelow . " to " . $prespagehigh . " of " . $numrows; ?> entries</p>
                                </div>
                                <?php } ?>
                            </div>
                        </div>
                        
                        <?php if(isset($completed_deposit_requests_filter) && !empty($completed_deposit_requests_filter)) { require 'layouts/pagination_links.php'; } ?>
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