<?php
require_once("../../init/initialize_admin.php");
if (!$session_admin->is_logged_in()) {
    redirect_to("login.php");
}

$query = "SELECT * FROM free_training_campaign";
$result = $db_handle->numRows($query);
$total_client = $result;

$total_active_training_clients = $system_object->get_total_active_training_clients();

$query = "SELECT * FROM free_training_campaign WHERE training_interest = '2'";
$result = $db_handle->numRows($query);
$interested = $result;

$query = "SELECT * FROM free_training_campaign WHERE training_interest = '1'";
$result = $db_handle->numRows($query);
$not_interested = $result;

$query = "SELECT * FROM free_training_campaign WHERE training_centre = '1'";
$result = $db_handle->numRows($query);
$diamond_office = $result;

$query = "SELECT * FROM free_training_campaign WHERE training_centre = '2'";
$result = $db_handle->numRows($query);
$lekki_office = $result;

$query = "SELECT * FROM free_training_campaign WHERE training_centre = '3'";
$result = $db_handle->numRows($query);
$online_office = $result;

$query = "SELECT *
        FROM user_deposit AS ud
        INNER JOIN user_ifxaccount AS ui ON ud.ifxaccount_id = ui.ifxaccount_id
        INNER JOIN user AS u ON ui.user_code = u.user_code
        INNER JOIN free_training_campaign AS ftc ON ftc.email = u.email
        WHERE ud.status = '8' GROUP BY u.email";
$result = $db_handle->numRows($query);
$funding_count = $result;

$query = "SELECT *
        FROM user_ifxaccount AS ui
        INNER JOIN user AS u ON ui.user_code = u.user_code
        INNER JOIN free_training_campaign AS ftc ON ftc.email = u.email";
$result = $db_handle->numRows($query);
$ifx_account_count = $result;

$query = "SELECT SUM(summ) AS total_sum
        FROM (SELECT SUM(ud.real_dollar_equivalent) AS summ
        FROM user_deposit AS ud
        INNER JOIN user_ifxaccount AS ui ON ud.ifxaccount_id = ui.ifxaccount_id
        INNER JOIN user AS u ON ui.user_code = u.user_code
        INNER JOIN free_training_campaign AS ftc ON ftc.email = u.email
        WHERE ud.status = '8' GROUP BY u.email) src";
$result = $db_handle->runQuery($query);
$funding_sum = $db_handle->fetchAssoc($result);
$funding_sum = $funding_sum[0]['total_sum'];

$from_date = date('Y-m-d', strtotime('first day of this month'));
$to_date = date('Y-m-d', strtotime('last day of this month'));
$query = "SELECT SUM(summ) AS total_sum
        FROM (SELECT SUM(ud.real_dollar_equivalent) AS summ
        FROM user_deposit AS ud
        INNER JOIN user_ifxaccount AS ui ON ud.ifxaccount_id = ui.ifxaccount_id
        INNER JOIN user AS u ON ui.user_code = u.user_code
        INNER JOIN free_training_campaign AS ftc ON ftc.email = u.email
        WHERE ud.status = '8' AND (STR_TO_DATE(ud.created, '%Y-%m-%d') BETWEEN '$from_date' AND '$to_date') GROUP BY u.email) src";
$result = $db_handle->runQuery($query);
$funding_sum_this_month = $db_handle->fetchAssoc($result);
$funding_sum_this_month = $funding_sum_this_month[0]['total_sum'];


// table table
// Query for select date selection since the training campaign started till date
$campaign_start_date = "2016-09-21";
$date_today = date("Y-m-d");
$query = "SELECT date_of_day FROM log_of_dates WHERE date_of_day BETWEEN '$campaign_start_date' AND '$date_today' ORDER BY date_of_day DESC ";

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
$log_of_dates = $db_handle->fetchAssoc($result);

?>
<!DOCTYPE html>
<html lang="en">
    <head>
        <base target="_self">
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>Instaforex Nigeria | Admin - Training Campaign Report</title>
        <meta name="title" content="Instaforex Nigeria | Admin - Training Campaign Report" />
        <meta name="keywords" content="" />
        <meta name="description" content="" />
        <?php require_once '../layouts/head_meta.php'; ?>
    </head>
    <body>
        <?php require_once '../layouts/header.php'; ?>
        <!-- Main Body: The is the main content area of the web site, contains a side bar  -->
        <div id="main-body" class="container-fluid">
            <div class="row no-gutter">
                <!-- Main Body - Side Bar  -->
                <div id="main-body-side-bar" class="col-md-4 col-lg-3 left-nav">
                <?php require_once '../layouts/sidebar.php'; ?>
                </div>
                
                <!-- Main Body - Content Area: This is the main content area, unique for each page  -->
                <div id="main-body-content-area" class="col-md-8 col-lg-9">
                    
                    <!-- Unique Page Content Starts Here
                    ================================================== -->
                    <div class="row">
                        <div class="col-sm-12 text-danger">
                            <h4><strong>VIEW TRAINING CAMPAIGN REPORTS</strong></h4>
                        </div>
                    </div>
                    
                    <div class="section-tint super-shadow">
                        <div class="row">
                            <div class="col-sm-12">
                                <p>Below is the free training campaign insight.</p>

                                <p>
                                    <strong>Total Clients:</strong> <?php echo $total_client; ?> |
                                    <strong>Active Clients:</strong> <?php echo $total_active_training_clients; ?> |
                                    <strong>Total IFX Accounts Opened:</strong> <?php echo $ifx_account_count; ?> |
                                    <strong>Interested:</strong> <?php echo $interested; ?> |
                                    <strong>Not Interested:</strong> <?php echo $not_interested; ?> |
                                    <strong>Diamond Centre:</strong> <?php echo $diamond_office; ?> |
                                    <strong>Ikota Centre:</strong> <?php echo $lekki_office; ?> |
                                    <strong>Online Centre:</strong> <?php echo $online_office; ?> |
                                    <strong>Clients That Funded:</strong> <?php echo $funding_count;  ?> |
                                    <strong>Dollars Funded: &dollar;</strong> <?php echo number_format($funding_sum, 2, ".", ","); ?>|
                                    <strong>Dollars Funded This Month: &dollar;</strong> <?php echo number_format($funding_sum_this_month, 2, ".", ","); ?>
                                </p>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-sm-12">
                                <p class="text-danger"><strong>Table Header Description</strong></p>
                                <ul>
                                    <li><strong>Leads Date:</strong> Date of New leads registered daily.</li>
                                    <li><strong>Leads Count:</strong> Sum of leads registered daily.</li>
                                    <li><strong>Accounts Opened:</strong> Sum of new accounts opened for leads
                                        on 'Leads Date' - may include accounts opened for leads that registered in a time past.</li>
                                    <li><strong>Dollar Funded:</strong> Total dollar funded by leads on 'Leads Date' - may
                                        include funding by leads that registered in a time past.</li>
                                </ul>

                                <table class="table table-responsive table-striped table-bordered table-hover">
                                    <thead>
                                    <tr>
                                        <th>Leads Date</th>
                                        <th>Leads Count</th>
                                        <th>Accounts Opened</th>
                                        <th>Dollar Funded</th>
                                        <th>Trans ID</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    <?php if(isset($log_of_dates) && !empty($log_of_dates)) { foreach ($log_of_dates as $row) { ?>
                                        <tr>
                                            <td><?php echo date_to_text($row['date_of_day']); ?></td>
                                            <td><?php
                                                $date_opened = $row['date_of_day'];
                                                $query = "SELECT COUNT(ftc.free_training_campaign_id) AS total_opened
                                                      FROM free_training_campaign AS ftc
                                                      WHERE STR_TO_DATE(ftc.created, '%Y-%m-%d') = '$date_opened'";
                                                $fetched_data = $db_handle->fetchAssoc($db_handle->runQuery($query));
                                                $total_opened = $fetched_data[0]['total_opened'];
                                                echo number_format($total_opened);
                                                ?>
                                            </td>
                                            <td><?php

                                                $date_opened = $row['date_of_day'];
                                                $query = "SELECT COUNT(ifx_acct_no) AS total_account
                                                    FROM user_ifxaccount AS ui
                                                    INNER JOIN user AS u ON ui.user_code = u.user_code
                                                    INNER JOIN free_training_campaign AS ftc ON ftc.email = u.email
                                                    WHERE STR_TO_DATE(ui.created, '%Y-%m-%d') = '$date_opened'";
                                                $fetched_data = $db_handle->fetchAssoc($db_handle->runQuery($query));
                                                $ifx_account_count = $fetched_data[0]['total_account'];
                                                echo $ifx_account_count;

                                                ?>
                                            </td>
                                            <td>&dollar;
                                                <?php

                                                $date_opened = $row['date_of_day'];
                                                $query = "SELECT SUM(summ) AS total_sum
                                                    FROM (SELECT SUM(ud.real_dollar_equivalent) AS summ
                                                    FROM user_deposit AS ud
                                                    INNER JOIN user_ifxaccount AS ui ON ud.ifxaccount_id = ui.ifxaccount_id
                                                    INNER JOIN user AS u ON ui.user_code = u.user_code
                                                    INNER JOIN free_training_campaign AS ftc ON ftc.email = u.email
                                                    WHERE ud.status = '8' AND (STR_TO_DATE(ud.created, '%Y-%m-%d') = '$date_opened') GROUP BY u.email) src";
                                                $fetched_data = $db_handle->fetchAssoc($db_handle->runQuery($query));
                                                $total_sum = $fetched_data[0]['total_sum'];
                                                echo number_format($total_sum, 2, ".", ",");

                                                ?>
                                            </td>
                                            <td>
                                                <?php

                                                $date_opened = $row['date_of_day'];
                                                $query = "SELECT ud.trans_id
                                                    FROM user_deposit AS ud
                                                    INNER JOIN user_ifxaccount AS ui ON ud.ifxaccount_id = ui.ifxaccount_id
                                                    INNER JOIN user AS u ON ui.user_code = u.user_code
                                                    INNER JOIN free_training_campaign AS ftc ON ftc.email = u.email
                                                    WHERE ud.status = '8' AND (STR_TO_DATE(ud.created, '%Y-%m-%d') = '$date_opened')";
                                                $fetched_data = $db_handle->fetchAssoc($db_handle->runQuery($query));

                                                foreach ($fetched_data as $row) {

                                                    foreach ($row as $value) {
                                                ?>
                                                <a target="_blank" title="View" href="deposit_search_view.php?id=<?php echo encrypt_ssl($value); ?>"><?php echo $value; ?></a>,&nbsp;
                                                <?php } } ?>
                                            </td>
                                        </tr>
                                    <?php } } else { echo "<tr><td colspan='5' class='text-danger'><em>No results to display</em></td></tr>"; } ?>
                                    </tbody>
                                </table>

                                <?php if(isset($log_of_dates) && !empty($log_of_dates)) { ?>
                                    <div class="tool-footer text-right">
                                        <p class="pull-left">Showing <?php echo $prespagelow . " to " . $prespagehigh . " of " . $numrows; ?> entries</p>
                                    </div>
                                <?php } ?>

                            </div>
                        </div>
                        <?php if(isset($log_of_dates) && !empty($log_of_dates)) { require_once '../layouts/pagination_links.php'; } ?>

                    </div>

                    <!-- Unique Page Content Ends Here
                    ================================================== -->
                    
                </div>
                
            </div>
        </div>
        <?php require_once '../layouts/footer.php'; ?>
    </body>
</html>