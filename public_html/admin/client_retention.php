<?php
require_once("../init/initialize_admin.php");
if (!$session_admin->is_logged_in()) { redirect_to("login.php"); }

if(isset($_GET['f'])) {
    $section = $_GET['f'];
    switch($section) {
        case '1': $selected_section = "1"; break;
        default: $selected_section = false;
    }
}

if (isset($_POST['retention_tracker']) || isset($_GET['pg'])) {

    if (isset($_POST['retention_tracker'])) {
        foreach ($_POST as $key => $value) {
            $_POST[$key] = $db_handle->sanitizePost(trim($value));
        }

        extract($_POST);

        $dates_selected = $obj_analytics->get_from_to_dates($year_date, $period);
        extract($dates_selected);

        $base_query = "SELECT SUM(td.volume) AS sum_volume, SUM(td.commission) AS sum_commission, u.user_code, CONCAT(u.last_name, SPACE(1), u.first_name) AS full_name,
            u.email, u.phone, u.created, CONCAT(a.last_name, SPACE(1), a.first_name) AS account_officer_full_name
            FROM trading_commission AS td
            INNER JOIN user_ifxaccount AS ui ON td.ifx_acct_no = ui.ifx_acct_no
            INNER JOIN user AS u ON ui.user_code = u.user_code
            LEFT JOIN account_officers AS ao ON u.attendant = ao.account_officers_id
            LEFT JOIN admin AS a ON ao.admin_code = a.admin_code
            WHERE date_earned BETWEEN '$prev_from_date' AND '$prev_to_date' GROUP BY u.email ORDER BY sum_commission DESC ";

        $db_handle->runQuery("CREATE TEMPORARY TABLE reference_clients AS " . $base_query);

        if($selected_section != '1') {
            $query = "SELECT sum_volume, sum_commission, user_code, full_name, email, phone, created, account_officer_full_name FROM reference_clients
                WHERE user_code NOT IN (
                    SELECT u.user_code
                    FROM trading_commission AS td
                    INNER JOIN user_ifxaccount AS ui ON td.ifx_acct_no = ui.ifx_acct_no
                    INNER JOIN user AS u ON ui.user_code = u.user_code
                    WHERE date_earned BETWEEN '$from_date' AND '$to_date' GROUP BY u.email ORDER BY sum_commission DESC
                ) ";
        } else {
            $query = "SELECT sum_volume, sum_commission, user_code, full_name, email, phone, created, account_officer_full_name FROM reference_clients
                WHERE user_code IN (
                    SELECT u.user_code
                    FROM trading_commission AS td
                    INNER JOIN user_ifxaccount AS ui ON td.ifx_acct_no = ui.ifx_acct_no
                    INNER JOIN user AS u ON ui.user_code = u.user_code
                    WHERE date_earned BETWEEN '$from_date' AND '$to_date' GROUP BY u.email ORDER BY sum_commission DESC
                ) ";
        }

        $_SESSION['client_retention_base_query'] = $base_query;
        $_SESSION['client_retention_query'] = $query;
        $_SESSION['client_retention_prev_from_date'] = $prev_from_date;
        $_SESSION['client_retention_prev_to_date'] = $prev_to_date;
        $_SESSION['client_retention_from_date'] = $from_date;
        $_SESSION['client_retention_to_date'] = $to_date;
        $_SESSION['client_retention_result_title'] = $result_title;
    } else {

        $base_query = $_SESSION['client_retention_base_query'];
        $query = $_SESSION['client_retention_query'];
        $prev_from_date = $_SESSION['client_retention_prev_from_date'];
        $prev_to_date = $_SESSION['client_retention_prev_to_date'];
        $from_date = $_SESSION['client_retention_from_date'];
        $to_date = $_SESSION['client_retention_to_date'];
        $result_title = $_SESSION['client_retention_result_title'];

        $db_handle->runQuery("CREATE TEMPORARY TABLE reference_clients AS " . $base_query);
    }

    $total_to_retain = $db_handle->numRows($base_query);

    $numrows = $db_handle->numRows($query);

    $total_not_retained = $numrows;
    $total_retained = $total_to_retain - $total_not_retained;
    $retention_rate = number_format((($total_retained / $total_to_retain) * 100), 2);

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
    $retention_result = $db_handle->fetchAssoc($result);
}

?>
<!DOCTYPE html>
<html lang="en">
    <head>
        <base target="_self">
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>Instaforex Nigeria | Admin - Client Retention Tracker</title>
        <meta name="title" content="Instaforex Nigeria | Admin - Client Retention Tracker" />
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
                            <h4><strong>CLIENT RETENTION TRACKER</strong></h4>
                        </div>
                    </div>

                    <div class="section-tint super-shadow">
                        <div class="row">
                            <div class="col-sm-12">
                                <p>Use the form below to select a year value and the type of retention that you want to
                                    analyse, the system will display the clients NOT YET RETAINED within the chosen period.</p>

                                <p><span class="text-danger">Note:</span> Please allow some time for the page to load. The system will be optimized.</p>

                                <form data-toggle="validator" class="form-horizontal" role="form" method="post" action="<?php echo $_SERVER['REQUEST_URI']; ?>">

                                    <div class="form-group">
                                        <label class="control-label col-sm-3" for="year_date">Year:</label>
                                        <div class="col-sm-9 col-lg-5">
                                            <div class="input-group date">
                                                <input name="year_date" type="text" class="form-control" id="datetimepicker" required>
                                                <span class="input-group-addon"><span class="glyphicon glyphicon-calendar"></span></span>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="control-label col-sm-3" for="period">Period:</label>
                                        <div class="col-sm-9 col-lg-5">
                                            <select type="text" name="period" id="period" class="form-control">
                                                <option value=""></option>
                                                <option value="1">January</option>
                                                <option value="2">February</option>
                                                <option value="3">March</option>
                                                <option value="4">April</option>
                                                <option value="5">May</option>
                                                <option value="6">June</option>
                                                <option value="7">July</option>
                                                <option value="8">August</option>
                                                <option value="9">September</option>
                                                <option value="10">October</option>
                                                <option value="11">November</option>
                                                <option value="12">December</option>
<!--                                                <option value="1-12">Annual</option>-->
<!--                                                <option value="1-6">First Half</option>-->
<!--                                                <option value="6-12">Second Half</option>-->
                                                <option value="1-3">First Quarter</option>
                                                <option value="3-6">Second Quarter</option>
                                                <option value="6-9">Third Quarter</option>
                                                <option value="9-12">Fourth Quarter</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <div class="col-sm-offset-3 col-sm-9"><input name="retention_tracker" type="submit" class="btn btn-success" value="Display Result" /></div>
                                    </div>
                                    <script type="text/javascript">
                                        $(function () {
                                            $('#datetimepicker, #datetimepicker2').datetimepicker({
                                                format: 'YYYY'
                                            });
                                        });
                                    </script>
                                </form>
                                <hr /><br />

                                <?php if(isset($result_title)) { ?>
                                <p class="text-center"><strong>Period: <?php echo $result_title; ?></strong><br />
                                Retention rate: <?php echo $retention_rate . '%'; ?></p>
                                <?php } ?>

<!--                                <div class="row text-center">-->
<!--                                    <div class="col-sm-12">-->
<!--                                        <div class="btn-group btn-breadcrumb">-->
<!--                                            <a href="--><?php //echo $_SERVER['PHP_SELF']; ?><!--" class="btn btn-default" title="All Registrations">Not Retained</a>-->
<!--                                            <a href="--><?php //echo $_SERVER['PHP_SELF'] . '?f=1'; ?><!--" class="btn btn-default" title="">Retained</a>-->
<!--                                        </div>-->
<!--                                    </div>-->
<!--                                </div>-->

                                <br />

                                <?php if(isset($retention_result) && !empty($retention_result)) { require 'layouts/pagination_links.php'; } ?>

                                <div class="table-wrap">
                                    <table class="table table-responsive table-striped table-bordered table-hover">
                                        <thead>
                                        <tr>
                                            <th>Client Detail</th>
                                            <th>Volume</th>
                                            <th>Commission</th>
                                            <th>Funding</th>
                                            <th>Action</th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                        <?php if(isset($retention_result) && !empty($retention_result)) { foreach ($retention_result as $row) { ?>
                                            <tr>
                                                <td>
                                                    <?php echo $row['full_name']; ?><br />
                                                    <?php echo $row['email']; ?><br />
                                                    <?php echo $row['phone']; ?>
                                                </td>
                                                <td><?php echo number_format($row['sum_volume'], 2, ".", ","); ?></td>
                                                <td>&dollar; <?php echo number_format($row['sum_commission'], 2, ".", ","); ?></td>
                                                <td></td>
                                                <td nowrap="nowrap">
                                                    <a target="_blank" title="View" class="btn btn-info" href="client_detail.php?id=<?php echo encrypt($row['user_code']); ?>"><i class="glyphicon glyphicon-eye-open icon-white"></i> </a>
                                                </td>
                                            </tr>
                                        <?php } } else { echo "<tr><td colspan='6' class='text-danger'><em>No results to display</em></td></tr>"; } ?>
                                        </tbody>
                                    </table>
                                </div>

                                <?php if(isset($retention_result) && !empty($retention_result)) { ?>
                                    <div class="tool-footer text-right">
                                        <p class="pull-left">Showing <?php echo $prespagelow . " to " . $prespagehigh . " of " . $numrows; ?> entries</p>
                                    </div>
                                <?php } ?>

                                <?php if(isset($retention_result) && !empty($retention_result)) { require 'layouts/pagination_links.php'; } ?>

                            </div>
                        </div>
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