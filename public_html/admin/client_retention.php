<?php
require_once("../init/initialize_admin.php");
if (!$session_admin->is_logged_in()) { redirect_to("login.php"); }

if(isset($_GET['r']) && $_GET['r'] == 1) {
    unset($_SESSION['client_retention_base_query']);
    unset($_SESSION['client_retention_base_query2']);
    unset($_SESSION['client_retention_query']);
    unset($_SESSION['client_retention_query2']);
    unset($_SESSION['client_retention_main_query']);
    unset($_SESSION['client_retention_prev_from_date']);
    unset($_SESSION['client_retention_prev_to_date']);
    unset($_SESSION['client_retention_from_date']);
    unset($_SESSION['client_retention_to_date']);
    unset($_SESSION['client_retention_period_title']);
    unset($_SESSION['client_retention_type_title']);
    unset($_SESSION['client_retention_type_title2']);
    unset($_SESSION['client_retention_type_main_title']);
    unset($_SESSION['client_retention_type_selected']);

    redirect_to("client_retention.php");
}

$current_year = date('Y');
$main_current_month = date('m');
$current_month = ltrim($main_current_month, '0');
$current_quarter = $obj_analytics->get_quarter_code($main_current_month);

// Get the page Analytics
$retention_analytics = $obj_analytics->get_retention_analytics();
extract($retention_analytics);

$m_retention_rate = ($m_client_retained / $m_client_to_retain) * 100;
$q_retention_rate = ($q_client_retained / $q_client_to_retain) * 100;

$query = "SELECT value AS target FROM admin_targets WHERE year = '$current_year' AND period = '$main_current_month' AND status = '1' AND type = '2' LIMIT 1";
$result = $db_handle->runQuery($query);
$m_current_target = $db_handle->fetchAssoc($result)[0]['target'];
$m_target_rate = ($m_retention_rate / $m_current_target) * 100;
$m_target_rate = number_format($m_target_rate, 2);

$query = "SELECT value AS target FROM admin_targets WHERE year = '$current_year' AND period = '$current_quarter' AND status = '1' AND type = '2' LIMIT 1";
$result = $db_handle->runQuery($query);
$q_current_target = $db_handle->fetchAssoc($result)[0]['target'];
$q_target_rate = ($q_retention_rate / $q_current_target) * 100;
$q_target_rate = number_format($q_target_rate, 2);

if (isset($_POST['retention_tracker']) || isset($_GET['pg'])) {

    if (isset($_POST['retention_tracker'])) {
        foreach ($_POST as $key => $value) {
            $_POST[$key] = $db_handle->sanitizePost(trim($value));
        }

        extract($_POST);

        $dates_selected = $obj_analytics->get_from_to_dates($year_date, $period);
        extract($dates_selected);

        $base_query = "SELECT SUM(td.volume) AS sum_volume, SUM(td.commission) AS sum_commission, u.user_code, CONCAT(u.last_name, SPACE(1), u.first_name) AS full_name,
            u.email, u.phone, u.created, MIN(td.date_earned) AS first_trade, MAX(td.date_earned) AS last_trade
            FROM trading_commission AS td
            INNER JOIN user_ifxaccount AS ui ON td.ifx_acct_no = ui.ifx_acct_no
            INNER JOIN user AS u ON ui.user_code = u.user_code
            WHERE date_earned BETWEEN '$prev_from_date' AND '$prev_to_date' GROUP BY u.email ORDER BY last_trade DESC ";

        $db_handle->runQuery("CREATE TEMPORARY TABLE reference_clients AS " . $base_query);

        $base_query2 = "SELECT SUM(td.volume) AS sum_volume, SUM(td.commission) AS sum_commission, u.user_code, CONCAT(u.last_name, SPACE(1), u.first_name) AS full_name,
            u.email, u.phone, u.created, MIN(td.date_earned) AS first_trade, MAX(td.date_earned) AS last_trade
            FROM trading_commission AS td
            INNER JOIN user_ifxaccount AS ui ON td.ifx_acct_no = ui.ifx_acct_no
            INNER JOIN user AS u ON ui.user_code = u.user_code
            WHERE date_earned BETWEEN '$from_date' AND '$to_date' GROUP BY u.email ORDER BY first_trade DESC ";

        $db_handle->runQuery("CREATE TEMPORARY TABLE reference_clients_2 AS " . $base_query2);

        $retention_type_title = "NOT YET RETAINED";
        $query = "SELECT rc1.sum_volume, rc1.sum_commission, rc1.user_code, rc1.full_name, rc1.email, rc1.phone, rc1.created, rc1.first_trade, rc1.last_trade 
            FROM reference_clients AS rc1
            LEFT JOIN reference_clients_2 AS rc2 ON rc1.user_code = rc2.user_code
            WHERE rc2.user_code IS NULL ORDER BY rc1.last_trade DESC ";

        $retention_type_title2 = "RETAINED";
        $query2 = "SELECT rc2.sum_volume, rc2.sum_commission, rc2.user_code, rc2.full_name, rc2.email, rc2.phone, rc2.created, rc2.first_trade, rc2.last_trade
            FROM reference_clients AS rc1
            LEFT JOIN reference_clients_2 AS rc2 ON rc1.user_code = rc2.user_code
            WHERE rc2.user_code IS NOT NULL ORDER BY rc2.first_trade DESC ";

        $main_query = $query;
        $retention_type_main_title = $retention_type_title;

        $_SESSION['client_retention_base_query'] = $base_query;
        $_SESSION['client_retention_base_query2'] = $base_query2;
        $_SESSION['client_retention_query'] = $query;
        $_SESSION['client_retention_query2'] = $query2;
        $_SESSION['client_retention_main_query'] = $query;
        $_SESSION['client_retention_prev_from_date'] = $prev_from_date;
        $_SESSION['client_retention_prev_to_date'] = $prev_to_date;
        $_SESSION['client_retention_from_date'] = $from_date;
        $_SESSION['client_retention_to_date'] = $to_date;
        $_SESSION['client_retention_period_title'] = $period_title;
        $_SESSION['client_retention_type_title'] = $retention_type_title;
        $_SESSION['client_retention_type_title2'] = $retention_type_title2;
        $_SESSION['client_retention_type_main_title'] = $retention_type_title;
        $_SESSION['client_retention_type_selected'] = $retention_type;

    } else {

        if (isset($_POST['retention_tracker_switch'])) {
            foreach ($_POST as $key => $value) {
                $_POST[$key] = $db_handle->sanitizePost(trim($value));
            }
            extract($_POST);

            switch($ret_type) {
                case '1':
                    $main_query = $_SESSION['client_retention_query'];
                    $_SESSION['client_retention_main_query'] = $main_query;

                    $retention_type_main_title = $_SESSION['client_retention_type_title'];
                    $_SESSION['client_retention_type_main_title'] = $retention_type_main_title;
                    break;
                case '2':
                    $main_query = $_SESSION['client_retention_query2'];
                    $_SESSION['client_retention_main_query'] = $main_query;

                    $retention_type_main_title = $_SESSION['client_retention_type_title2'];
                    $_SESSION['client_retention_type_main_title'] = $retention_type_main_title;
                    break;
            }
        }

        $base_query = $_SESSION['client_retention_base_query'];
        $base_query2 = $_SESSION['client_retention_base_query2'];
        $query = $_SESSION['client_retention_query'];
        $query2 = $_SESSION['client_retention_query2'];
        $main_query = $_SESSION['client_retention_main_query'];
        $prev_from_date = $_SESSION['client_retention_prev_from_date'];
        $prev_to_date = $_SESSION['client_retention_prev_to_date'];
        $from_date = $_SESSION['client_retention_from_date'];
        $to_date = $_SESSION['client_retention_to_date'];
        $period_title = $_SESSION['client_retention_period_title'];
        $retention_type_title = $_SESSION['client_retention_type_title'];
        $retention_type_title2 = $_SESSION['client_retention_type_title2'];
        $retention_type_main_title= $_SESSION['client_retention_type_main_title'];
        $retention_type = $_SESSION['client_retention_type_selected'];

        $db_handle->runQuery("CREATE TEMPORARY TABLE reference_clients AS " . $base_query);
        $db_handle->runQuery("CREATE TEMPORARY TABLE reference_clients_2 AS " . $base_query2);

    }
} else {

    $dates_selected = $obj_analytics->get_from_to_dates($current_year, $current_month);
    extract($dates_selected);

    $base_query = "SELECT SUM(td.volume) AS sum_volume, SUM(td.commission) AS sum_commission, u.user_code, CONCAT(u.last_name, SPACE(1), u.first_name) AS full_name,
            u.email, u.phone, u.created, MIN(td.date_earned) AS first_trade, MAX(td.date_earned) AS last_trade
            FROM trading_commission AS td
            INNER JOIN user_ifxaccount AS ui ON td.ifx_acct_no = ui.ifx_acct_no
            INNER JOIN user AS u ON ui.user_code = u.user_code
            WHERE date_earned BETWEEN '$prev_from_date' AND '$prev_to_date' GROUP BY u.email ORDER BY last_trade DESC ";

    $db_handle->runQuery("CREATE TEMPORARY TABLE reference_clients AS " . $base_query);

    $base_query2 = "SELECT SUM(td.volume) AS sum_volume, SUM(td.commission) AS sum_commission, u.user_code, CONCAT(u.last_name, SPACE(1), u.first_name) AS full_name,
            u.email, u.phone, u.created, MIN(td.date_earned) AS first_trade, MAX(td.date_earned) AS last_trade
            FROM trading_commission AS td
            INNER JOIN user_ifxaccount AS ui ON td.ifx_acct_no = ui.ifx_acct_no
            INNER JOIN user AS u ON ui.user_code = u.user_code
            WHERE date_earned BETWEEN '$from_date' AND '$to_date' GROUP BY u.email ORDER BY first_trade DESC ";

    $db_handle->runQuery("CREATE TEMPORARY TABLE reference_clients_2 AS " . $base_query2);

    $retention_type_title = "NOT YET RETAINED";
    $query = "SELECT rc1.sum_volume, rc1.sum_commission, rc1.user_code, rc1.full_name, rc1.email, rc1.phone, rc1.created, rc1.first_trade, rc1.last_trade 
            FROM reference_clients AS rc1
            LEFT JOIN reference_clients_2 AS rc2 ON rc1.user_code = rc2.user_code
            WHERE rc2.user_code IS NULL ORDER BY rc1.last_trade DESC ";

    $retention_type_title2 = "RETAINED";
    $query2 = "SELECT rc2.sum_volume, rc2.sum_commission, rc2.user_code, rc2.full_name, rc2.email, rc2.phone, rc2.created, rc2.first_trade, rc2.last_trade
            FROM reference_clients AS rc1
            LEFT JOIN reference_clients_2 AS rc2 ON rc1.user_code = rc2.user_code
            WHERE rc2.user_code IS NOT NULL ORDER BY rc2.first_trade DESC ";

    $main_query = $query;
    $retention_type_main_title = $retention_type_title;

    $_SESSION['client_retention_base_query'] = $base_query;
    $_SESSION['client_retention_base_query2'] = $base_query2;
    $_SESSION['client_retention_query'] = $query;
    $_SESSION['client_retention_query2'] = $query2;
    $_SESSION['client_retention_main_query'] = $query;
    $_SESSION['client_retention_prev_from_date'] = $prev_from_date;
    $_SESSION['client_retention_prev_to_date'] = $prev_to_date;
    $_SESSION['client_retention_from_date'] = $from_date;
    $_SESSION['client_retention_to_date'] = $to_date;
    $_SESSION['client_retention_period_title'] = $period_title;
    $_SESSION['client_retention_type_title'] = $retention_type_title;
    $_SESSION['client_retention_type_title2'] = $retention_type_title2;
    $_SESSION['client_retention_type_main_title'] = $retention_type_title;
    $_SESSION['client_retention_type_selected'] = $retention_type;
}

$total_to_retain = $db_handle->numRows($base_query);

$numrows = $db_handle->numRows($main_query);

if($retention_type_main_title == "NOT YET RETAINED") {
    $total_not_retained = $numrows;
} else {
    $total_not_retained = $total_to_retain - $numrows;
}

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
$main_query .= 'LIMIT ' . $offset . ',' . $rowsperpage;
$result = $db_handle->runQuery($main_query);
$retention_result = $db_handle->fetchAssoc($result);

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
                                <div class="pull-left"><a href="client_retention.php?r=1" title="Clear Search Parameter" class="btn btn-default">Refresh <i class="fa fa-recycle"></i></a></div>
                                <div class="pull-right"><a data-target="#get-help" data-toggle="modal" class="btn btn-default" title="Help">Help <i class="fa fa-arrow-circle-right"></i></a></div>

                                <div id="get-help" tabindex="-1" role="dialog" aria-hidden="true" class="modal fade">
                                    <div class="modal-dialog">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <button type="button" data-dismiss="modal" aria-hidden="true"  class="close">&times;</button>
                                                <h4 class="modal-title">CLIENT RETENTION TRACKER</h4></div>
                                            <div class="modal-body">
                                                <p><strong>Retention</strong></p>
                                                <p>Retention tracker checks for the number of clients that placed trades in a previous / past period and also
                                                placed trades in a current period. A period can be monthly, quarterly, half yearly and yearly.</p>
                                                <p>For example, to get the retention analysis for a month, say JUNE 2018, the system will calculate
                                                the number of people that placed a trade in a previous month, i.e. MAY 2018 and check those of them
                                                that have now placed a trade in JUNE.</p>
                                                <p>To get the retention analysis for a quarter, say APRIL - JUNE 2018, the system will calculate the
                                                number of people that placed a trade in a previous quarter, i.e. JANUARY - MARCH 2018 and check those
                                                of them that have now placed a trade in APRIL - JUNE 2018.</p>
                                                <p><strong>Retention Rate</strong> is the percentage of clients that have been retained.</p>
                                                <hr />
                                                <p><strong>Note:</strong> Retention tracker compares any period you choose (taken as <strong>CURRENT PERIOD</strong>)
                                                    with a <strong>PREVIOUS PERIOD.</strong> For example, if you want to analyze retention for a particular
                                                month, say JUNE 2018, the CURRENT PERIOD to be analyzed is JUNE 2018, while the PREVIOUS PERIOD to compare with
                                                is MAY 2018.</p>
                                                <p>Also, for each search, you can choose to see the <strong>NOT YET RETAINED</strong> clients (i.e. those that placed trades
                                                    in a previous period but yet to place trade in current period) or the <strong>RETAINED</strong> clients (i.e. those
                                                    that placed trades in a previous period and have also placed trades in a current period).</p>
                                                <p>The NOT YET RETAINED table gives the analysis of volume, commission and funding generated by the clients
                                                in their PREVIOUS PERIOD while the RETAINED table gives the analysis of volume, commission and funding generated
                                                by the clients in their CURRENT PERIOD.</p>
                                            </div>
                                            <div class="modal-footer">
                                                <button type="button" name="close" onClick="window.close();" data-dismiss="modal" class="btn btn-danger">Close!</button>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                            </div>
                        </div>

                        <div class="row">
                            <div class="col-sm-6">
                                <h5 class="text-center"><strong>Month Analysis - (<?php echo $month_title; ?>)</strong></h5>

                                <table class="table table-border table-responsive table-hover">
                                    <tr><td>Clients to Retain</td><td><?php echo number_format($m_client_to_retain); ?></td></tr>
                                    <tr><td>Total Retained</td><td><?php echo number_format($m_client_retained); ?></td></tr>
                                    <tr><td>Not Retained</td><td><?php echo number_format($m_client_to_retain - $m_client_retained); ?></td></tr>
                                    <tr><td>Retained Yesterday</td><td><?php echo number_format($m_retained_yesterday); ?></td></tr>
                                    <tr><td>Retention Rate</td><td><?php echo number_format($m_retention_rate, 2) . "%"; ?></td></tr>
                                </table>

                                <hr />

                                <p class="text-center"><strong>Performance progress relative to target (<?php echo $m_current_target; ?>%)</strong></p>

                                <div class="progress">
                                    <div class="progress-bar progress-bar-striped active" role="progressbar" aria-valuenow="<?php echo $m_target_rate; ?>" aria-valuemin="0" aria-valuemax="<?php echo $m_current_target; ?>" style="width: <?php echo $m_target_rate; ?>%"><?php echo $m_target_rate; ?>%</div>
                                </div>
                            </div>

                            <div class="col-sm-6">
                                <h5 class="text-center"><strong>Quarter Analysis - (<?php echo $quarter_title; ?>)</strong></h5>

                                <table class="table table-border table-responsive table-hover">
                                    <tr><td>Clients to Retain</td><td><?php echo number_format($q_client_to_retain); ?></td></tr>
                                    <tr><td>Total Retained</td><td><?php echo number_format($q_client_retained); ?></td></tr>
                                    <tr><td>Not Retained</td><td><?php echo number_format($q_client_to_retain - $q_client_retained); ?></td></tr>
                                    <tr><td>Retained Yesterday</td><td><?php echo number_format($q_retained_yesterday); ?></td></tr>
                                    <tr><td>Retention Rate</td><td><?php echo number_format($q_retention_rate, 2) . "%"; ?></td></tr>
                                </table>

                                <hr />

                                <p class="text-center"><strong>Performance progress relative to target (<?php echo $q_current_target; ?>%)</strong></p>

                                <div class="progress">
                                    <div class="progress-bar progress-bar-striped active" role="progressbar" aria-valuenow="<?php echo $q_target_rate; ?>" aria-valuemin="0" aria-valuemax="<?php echo $q_current_target; ?>" style="width: <?php echo $q_target_rate; ?>%"><?php echo $q_target_rate; ?>%</div>
                                </div>
                            </div>
                        </div>

                        <hr style="border: thin dotted #c5c5c5" />

                        <div class="row">
                            <div class="col-sm-12">

                                <div class="row">
                                    <div class="col-sm-12">
                                        <br />

                                        <p class="text-right">
                                            <a href="client_month_activity.php" target="_blank" class="btn btn-info" title="Month Activity">Month Activity <i class="fa fa-tasks"></i></a>

                                            <a data-target="#search-form" data-toggle="modal" class="btn btn-default" title="Apply Filter">Apply Filter <i class="glyphicon glyphicon-search"></i></a>
                                        </p>

                                        <div id="search-form" tabindex="-1" role="dialog" aria-hidden="true" class="modal fade">
                                            <div class="modal-dialog">
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <button type="button" data-dismiss="modal" aria-hidden="true"  class="close">&times;</button>
                                                        <p class="modal-title">Fill the form below, choose the year and period.</p>
                                                    </div>

                                                    <div class="modal-body">
                                                        <form data-toggle="validator" class="form-horizontal" role="form" method="post" action="client_retention.php">

                                                            <div class="form-group">
                                                                <label class="control-label col-sm-3" for="year_date">Year:</label>
                                                                <div class="col-sm-9 col-lg-8">
                                                                    <div class="input-group date">
                                                                        <input name="year_date" type="text" class="form-control" id="datetimepicker" required>
                                                                        <span class="input-group-addon"><span class="glyphicon glyphicon-calendar"></span></span>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <div class="form-group">
                                                                <label class="control-label col-sm-3" for="period">Period:</label>
                                                                <div class="col-sm-9 col-lg-8">
                                                                    <select type="text" name="period" id="period" class="form-control" required>
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
                                                                        <option value="1-12">Annual</option>
                                                                        <option value="1-6">First Half</option>
                                                                        <option value="7-12">Second Half</option>
                                                                        <option value="1-3">First Quarter</option>
                                                                        <option value="4-6">Second Quarter</option>
                                                                        <option value="7-9">Third Quarter</option>
                                                                        <option value="10-12">Fourth Quarter</option>
                                                                    </select>
                                                                </div>
                                                            </div>

                                                            <div class="form-group">
                                                                <div class="col-sm-offset-3 col-sm-9">
                                                                    <input name="retention_tracker" type="submit" class="btn btn-success" value="Display Result" />
                                                                </div>
                                                            </div>
                                                            <script type="text/javascript">
                                                                $(function () {
                                                                    $('#datetimepicker, #datetimepicker2').datetimepicker({
                                                                        format: 'YYYY'
                                                                    });
                                                                });
                                                            </script>
                                                        </form>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <?php if(isset($period_title)) { ?>
                                    <div class="row">
                                        <div class="col-sm-12 text-center">
                                            <p><strong>Period: <?php echo $period_title . " (" . $retention_type_main_title . ")"; ?></strong><br />
                                                Retention rate: <?php echo $retention_rate . '%'; ?></p>

                                            <form data-toggle="validator" class="form-horizontal" role="form" method="post" action="client_retention.php?pg=1">
                                                <div class="form-group">
                                                    <div class="col-sm-12">
                                                        <?php if($retention_type_main_title == "NOT YET RETAINED") { ?>
                                                            <input type="hidden" name="ret_type" value="2" />
                                                            <input name="retention_tracker_switch" type="submit" class="btn btn-default" value="See Retained List" />
                                                        <?php } else { ?>
                                                            <input type="hidden" name="ret_type" value="1" />
                                                            <input name="retention_tracker_switch" type="submit" class="btn btn-default" value="See Not Yet Retained List" />
                                                        <?php } ?>
                                                    </div>
                                                </div>
                                            </form>
                                        </div>
                                    </div>

                                <?php } ?>

                                <?php if(isset($retention_result) && !empty($retention_result)) { require 'layouts/pagination_links.php'; } ?>

                                <div class="table-wrap">
                                    <table class="table table-responsive table-striped table-bordered table-hover">
                                        <thead>
                                        <tr>
                                            <th>Client Detail</th>
                                            <th>Commission</th>
                                            <th>Funding</th>
                                            <th><?php if($retention_type_main_title == "NOT YET RETAINED") { echo "Last Trading"; } else { echo "First Trading"; } ?></th>
                                            <th>Action</th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                        <?php if(isset($retention_result) && !empty($retention_result)) { foreach ($retention_result as $row) {
                                            if($retention_type == '1') {
                                                $sum_funding = $obj_analytics->get_client_funding_in_period($row['user_code'], $prev_from_date, $prev_to_date);
                                            } else {
                                                $sum_funding = $obj_analytics->get_client_funding_in_period($row['user_code'], $from_date, $to_date);
                                            }
                                        ?>
                                            <tr>
                                                <td>
                                                    <?php echo $row['full_name']; ?><br />
                                                    <?php echo $row['email']; ?><br />
                                                    <?php echo $row['phone']; ?>
                                                </td>
                                                <td>&dollar; <?php echo number_format($row['sum_commission'], 2, ".", ","); ?></td>
                                                <td>&dollar; <?php echo number_format($sum_funding, 2, ".", ","); ?></td>
                                                <td><?php if($retention_type == '1') { echo datetime_to_text2($row['last_trade']); } else { echo datetime_to_text2($row['first_trade']); } ?></td>
                                                <td nowrap="nowrap">
                                                    <a title="View" target="_blank" class="btn btn-info" href="client_detail.php?id=<?php echo encrypt($row['user_code']); ?>"><i class="glyphicon glyphicon-eye-open icon-white"></i> </a>
                                                    <a title="Comment" target="_blank" class="btn btn-success" href="sales_contact_view.php?x=<?php echo encrypt($row['user_code']); ?>&r=<?php echo 'client_retention'; ?>&c=<?php echo encrypt('CLIENT RETENTION TRACKER'); ?>&pg=<?php echo $currentpage; ?>"><i class="glyphicon glyphicon-comment icon-white"></i> </a>
                                                    <a title="Send Email" target="_blank" class="btn btn-primary" href="campaign_email_single.php?name=<?php $name = $row['full_name']; echo encrypt_ssl($name) . '&email=' . encrypt_ssl($row['email']); ?>"><i class="glyphicon glyphicon-envelope"></i></a>
                                                    <a title="Send SMS" target="_blank" class="btn btn-success" href="campaign_sms_single.php?lead_phone=<?php echo encrypt_ssl($row['phone']) ?>"><i class="glyphicon glyphicon-phone-alt"></i></a>
                                                </td>
                                            </tr>
                                        <?php } } else { echo "<tr><td colspan='5' class='text-danger'><em>No results to display</em></td></tr>"; } ?>
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