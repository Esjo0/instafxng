<?php
require_once("../init/initialize_admin.php");
if (!$session_admin->is_logged_in()) { redirect_to("login.php"); }

if (isset($_POST['retention_tracker']) || isset($_GET['pg'])) {

    if (isset($_POST['retention_tracker'])) {
        foreach ($_POST as $key => $value) {
            $_POST[$key] = $db_handle->sanitizePost(trim($value));
        }

        extract($_POST);

        $dates_selected = $obj_analytics->get_from_to_dates($year_date, $period);
        extract($dates_selected);

        $base_query = "SELECT SUM(td.volume) AS sum_volume, SUM(td.commission) AS sum_commission, u.user_code, CONCAT(u.last_name, SPACE(1), u.first_name) AS full_name,
            u.email, u.phone, u.created
            FROM trading_commission AS td
            INNER JOIN user_ifxaccount AS ui ON td.ifx_acct_no = ui.ifx_acct_no
            INNER JOIN user AS u ON ui.user_code = u.user_code
            WHERE date_earned BETWEEN '$prev_from_date' AND '$prev_to_date' GROUP BY u.email ORDER BY sum_commission DESC ";

        $db_handle->runQuery("CREATE TEMPORARY TABLE reference_clients AS " . $base_query);

        $base_query2 = "SELECT SUM(td.volume) AS sum_volume, SUM(td.commission) AS sum_commission, u.user_code, CONCAT(u.last_name, SPACE(1), u.first_name) AS full_name,
            u.email, u.phone, u.created
            FROM trading_commission AS td
            INNER JOIN user_ifxaccount AS ui ON td.ifx_acct_no = ui.ifx_acct_no
            INNER JOIN user AS u ON ui.user_code = u.user_code
            WHERE date_earned BETWEEN '$from_date' AND '$to_date' GROUP BY u.email ORDER BY sum_commission DESC ";

        $db_handle->runQuery("CREATE TEMPORARY TABLE reference_clients_2 AS " . $base_query2);

        if($ret_type == '1') {
            $retention_type = "NOT YET RETAINED";
            $query = "SELECT rc1.sum_volume, rc1.sum_commission, rc1.user_code, rc1.full_name, rc1.email, rc1.phone, rc1.created 
                FROM reference_clients AS rc1
                LEFT JOIN reference_clients_2 AS rc2 ON rc1.user_code = rc2.user_code
                WHERE rc2.user_code IS NULL ";
        } else {
            $retention_type = "RETAINED";
            $query = "SELECT rc2.sum_volume, rc2.sum_commission, rc2.user_code, rc2.full_name, rc2.email, rc2.phone, rc2.created 
                FROM reference_clients AS rc1
                LEFT JOIN reference_clients_2 AS rc2 ON rc1.user_code = rc2.user_code
                WHERE rc2.user_code IS NOT NULL ";
        }

        $_SESSION['client_retention_base_query'] = $base_query;
        $_SESSION['client_retention_base_query2'] = $base_query2;
        $_SESSION['client_retention_query'] = $query;
        $_SESSION['client_retention_prev_from_date'] = $prev_from_date;
        $_SESSION['client_retention_prev_to_date'] = $prev_to_date;
        $_SESSION['client_retention_from_date'] = $from_date;
        $_SESSION['client_retention_to_date'] = $to_date;
        $_SESSION['client_retention_result_title'] = $result_title;
        $_SESSION['client_retention_type'] = $retention_type;
        $_SESSION['client_retention_type_selected'] = $ret_type;
    } else {

        $base_query = $_SESSION['client_retention_base_query'];
        $base_query2 = $_SESSION['client_retention_base_query2'];
        $query = $_SESSION['client_retention_query'];
        $prev_from_date = $_SESSION['client_retention_prev_from_date'];
        $prev_to_date = $_SESSION['client_retention_prev_to_date'];
        $from_date = $_SESSION['client_retention_from_date'];
        $to_date = $_SESSION['client_retention_to_date'];
        $result_title = $_SESSION['client_retention_result_title'];
        $retention_type = $_SESSION['client_retention_type'];
        $ret_type = $_SESSION['client_retention_type_selected'];

        $db_handle->runQuery("CREATE TEMPORARY TABLE reference_clients AS " . $base_query);
        $db_handle->runQuery("CREATE TEMPORARY TABLE reference_clients_2 AS " . $base_query2);
    }

    $total_to_retain = $db_handle->numRows($base_query);

    $numrows = $db_handle->numRows($query);

    if($ret_type == 1) {
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
    $query .= 'LIMIT ' . $offset . ',' . $rowsperpage;
    $result = $db_handle->runQuery($query);
    $retention_result = $db_handle->fetchAssoc($result);
}

/**
 * Generate Page Top Analytics
 */
$current_year = date('Y');
$current_month = date('m');
$current_month = ltrim($current_month, '0');

$my_dates = $obj_analytics->get_from_to_dates($current_year, $current_month);
$my_prev_from_date = $my_dates['prev_from_date'];
$my_prev_to_date = $my_dates['prev_to_date'];
$my_from_date = $my_dates['from_date'];
$my_to_date = $my_dates['to_date'];

$the_query = "SELECT u.email
    FROM trading_commission AS td
    INNER JOIN user_ifxaccount AS ui ON td.ifx_acct_no = ui.ifx_acct_no
    INNER JOIN user AS u ON ui.user_code = u.user_code
    WHERE date_earned BETWEEN '$my_prev_from_date' AND '$my_prev_to_date' GROUP BY u.email";
$my_clients_to_retain = $db_handle->numRows($the_query);

$my_base_query = "SELECT SUM(td.volume) AS sum_volume, SUM(td.commission) AS sum_commission, u.user_code, CONCAT(u.last_name, SPACE(1), u.first_name) AS full_name,
    u.email, u.phone, u.created
    FROM trading_commission AS td
    INNER JOIN user_ifxaccount AS ui ON td.ifx_acct_no = ui.ifx_acct_no
    INNER JOIN user AS u ON ui.user_code = u.user_code
    WHERE date_earned BETWEEN '$my_prev_from_date' AND '$my_prev_to_date' GROUP BY u.email ORDER BY sum_commission DESC ";

$db_handle->runQuery("CREATE TEMPORARY TABLE my_reference_clients AS " . $my_base_query);

$my_base_query2 = "SELECT SUM(td.volume) AS sum_volume, SUM(td.commission) AS sum_commission, u.user_code, CONCAT(u.last_name, SPACE(1), u.first_name) AS full_name,
    u.email, u.phone, u.created
    FROM trading_commission AS td
    INNER JOIN user_ifxaccount AS ui ON td.ifx_acct_no = ui.ifx_acct_no
    INNER JOIN user AS u ON ui.user_code = u.user_code
    WHERE date_earned BETWEEN '$my_from_date' AND '$my_to_date' GROUP BY u.email ORDER BY sum_commission DESC ";

$db_handle->runQuery("CREATE TEMPORARY TABLE my_reference_clients_2 AS " . $my_base_query2);

$the_query = "SELECT rc2.sum_volume, rc2.sum_commission, rc2.user_code, rc2.full_name, rc2.email, rc2.phone, rc2.created 
    FROM my_reference_clients AS rc1
    LEFT JOIN my_reference_clients_2 AS rc2 ON rc1.user_code = rc2.user_code
    WHERE rc2.user_code IS NOT NULL ";

$my_clients_retained = $db_handle->numRows($the_query);
$my_clients_not_retained = $my_clients_to_retain - $my_clients_retained;
$my_retention_rate = number_format((($my_clients_retained / $my_clients_to_retain) * 100), 2);

////////////////////////////////////////////////

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
                                <p class="text-right"><a data-target="#get-help" data-toggle="modal" class="btn btn-default" title="Help">Help <i class="fa fa-arrow-circle-right"></i></a></p>

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
                            <div class="col-sm-12 text-center">
                                <h5><strong>Current Month Analysis - (<?php echo date('F, Y'); ?>)</strong></h5>
                            </div>
                        </div>

                        <div class="row">

                            <div class="col-sm-3">
                                <div class="super-shadow dashboard-stats">
                                    <header class="text-center"><strong>Clients to Retain</strong></header>
                                    <article class="text-center">
                                        <strong><?php echo number_format($my_clients_to_retain); ?></strong>
                                    </article>
                                </div>
                            </div>

                            <div class="col-sm-3">
                                <div class="super-shadow dashboard-stats">
                                    <header class="text-center"><strong>Retained</strong></header>
                                    <article class="text-center">
                                        <strong><?php echo number_format($my_clients_retained); ?></strong>
                                    </article>
                                </div>
                            </div>

                            <div class="col-sm-3">
                                <div class="super-shadow dashboard-stats">
                                    <header class="text-center"><strong>Not Retained</strong></header>
                                    <article class="text-center">
                                        <strong><?php echo number_format($my_clients_not_retained); ?></strong>
                                    </article>
                                </div>
                            </div>

                            <div class="col-sm-3">
                                <div class="super-shadow dashboard-stats">
                                    <header class="text-center"><strong>Retention Rate</strong></header>
                                    <article class="text-center">
                                        <strong><?php echo number_format($my_retention_rate, 2) . "%"; ?></strong>
                                    </article>
                                </div>
                            </div>

                        </div>

                        <div class="row">
                            <div class="col-sm-12">
                                <br />
                                <div class="progress">
                                    <div class="progress-bar progress-bar-striped active" role="progressbar" aria-valuenow="<?php echo number_format($my_retention_rate, 2); ?>" aria-valuemin="0" aria-valuemax="100" style="width: <?php echo number_format($my_retention_rate, 2); ?>%"><?php echo number_format($my_retention_rate, 2); ?>%</div>
                                </div>
                            </div>
                        </div>

                        <br />
                        <hr style="border: thin dotted #c5c5c5" />

                        <div class="row">
                            <div class="col-sm-12">
                                <p>Use the form below to generate retention report by selecting a year value, period and type.</p>

                                <form data-toggle="validator" class="form-horizontal" role="form" method="post" action="client_retention.php">

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
                                        <label class="control-label col-sm-3" for="retention_type">Type:</label>
                                        <div class="col-sm-9 col-lg-5">
                                            <select type="text" name="ret_type" id="retention_type" class="form-control" required>
                                                <option value=""></option>
                                                <option value="1">Not Yet Retained</option>
                                                <option value="2">Retained</option>
                                            </select>
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <div class="col-sm-offset-3 col-sm-9">
                                            <input name="retention_tracker" type="submit" class="btn btn-success" value="Display Result" />
                                            <a href="client_retention.php" title="Clear Search Parameter" class="btn btn-danger">Clear Search</a>
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
                                <hr /><br />

                                <?php if(isset($result_title)) { ?>
                                <p class="text-center"><strong>Period: <?php echo $result_title . " (" . $retention_type . ")"; ?></strong><br />
                                Retention rate: <?php echo $retention_rate . '%'; ?></p>
                                <?php } ?>

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
                                        <?php if(isset($retention_result) && !empty($retention_result)) { foreach ($retention_result as $row) {
                                            if($ret_type == '1') {
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
                                                <td><?php echo number_format($row['sum_volume'], 2, ".", ","); ?></td>
                                                <td>&dollar; <?php echo number_format($row['sum_commission'], 2, ".", ","); ?></td>
                                                <td>&dollar; <?php echo number_format($sum_funding, 2, ".", ","); ?></td>
                                                <td nowrap="nowrap">
                                                    <a title="View" target="_blank" class="btn btn-info" href="client_detail.php?id=<?php echo encrypt($row['user_code']); ?>"><i class="glyphicon glyphicon-eye-open icon-white"></i> </a>
                                                    <a title="Comment" target="_blank" class="btn btn-success" href="sales_contact_view.php?x=<?php echo encrypt($row['user_code']); ?>&r=<?php echo 'client_retention'; ?>&c=<?php echo encrypt('CLIENT RETENTION TRACKER'); ?>&pg=<?php echo $currentpage; ?>"><i class="glyphicon glyphicon-comment icon-white"></i> </a>
                                                    <a title="Send Email" target="_blank" class="btn btn-primary" href="campaign_email_single.php?name=<?php $name = $row['full_name']; echo encrypt_ssl($name) . '&email=' . encrypt_ssl($row['email']); ?>"><i class="glyphicon glyphicon-envelope"></i></a>
                                                    <a title="Send SMS" target="_blank" class="btn btn-success" href="campaign_sms_single.php?lead_phone=<?php echo encrypt_ssl($row['phone']) ?>"><i class="glyphicon glyphicon-phone-alt"></i></a>
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