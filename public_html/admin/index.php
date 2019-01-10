<?php
require_once("../init/initialize_admin.php");
if (!$session_admin->is_logged_in()) {
    redirect_to("login.php");
}

// GET LATEST TRANSACTIONS
$latest_funding = $system_object->get_latest_funding();

// GET LATEST WITHDRAWALS
$latest_withdrawal = $system_object->get_latest_withdrawal();

// GET LATEST BULLETINS
$latest_bulletin = $system_object->get_latest_bulletin();

// GET ACTIVE CLIENTS
$total_active_clients = $system_object->get_total_active_clients();

// GET FREE TRAINING ACTIVE CLIENTS
$total_active_training_clients = $system_object->get_total_active_training_clients();

// Total training clients
$query = "SELECT * FROM free_training_campaign";
$result = $db_handle->numRows($query);
$total_training_client = $result;

$percentage_active_training_client = ($total_active_training_clients / $total_training_client) * 100;

// GET ACTIVE ACCOUNTS
$total_active_accounts = $system_object->get_total_active_accounts();

// GET TOTAL ACCOUNTS
$total_accounts = $system_object->get_total_accounts();

// GET TOTAL CLIENTS
$total_clients = $system_object->get_total_clients();

// GET TOTAL POINT BALANCE
$total_point_balance = $obj_loyalty_point->point_balance_sum();
$total_point_balance_dollar_amount = $total_point_balance * DOLLAR_PER_POINT;

$total_expired_point = $obj_loyalty_point->total_expired_point();
$total_expired_point_dollar_amount = $total_expired_point * DOLLAR_PER_POINT;

$failed_sms_code = $system_object->get_failed_sms_code();
?>

<!DOCTYPE html>
<html lang="en">
    <head>
        <base target="_self">
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>Instaforex Nigeria | Admin</title>
        <meta name="title" content="Instaforex Nigeria | Admin" />
        <meta name="keywords" content="" />
        <meta name="description" content="" />
        <?php  require_once 'layouts/head_meta.php'; ?>
        <?php require_once 'hr_attendance_system.php'; ?>
        <script type="text/javascript" src="//www.gstatic.com/charts/loader.js"></script>
        <script>
            var total_clients = <?php echo $total_clients; ?>;
            var total_active_clients = <?php echo $total_active_clients; ?>;
            var total_inactive_clients = total_clients - total_active_clients;
            google.charts.load('current', {'packages':['corechart']});
            google.charts.setOnLoadCallback(drawChart_Activity);
            // Draw the chart and set the chart values
            function drawChart_Activity() {
                var data = google.visualization.arrayToDataTable([
                    ['Category', 'Number'],
                    ['Active Clients', total_active_clients],
                    ['Inactive Clients', total_inactive_clients]
                ]);
                // Optional; add a title and set the width and height of the chart
                var options = {'title':'Client Activity Overview', is3D:true};

                // Display the chart inside the <div> element with id="piechart"
                var chart = new google.visualization.PieChart(document.getElementById('piechart_activity'));
                chart.draw(data, options);
            }
        </script>
        <script>
            var total_accounts = <?php echo $total_accounts; ?>;
            var total_active_accounts = <?php echo $total_active_accounts; ?>;
            var total_inactive_accounts = total_accounts - total_active_accounts;
            google.charts.load('current', {'packages':['corechart']});
            google.charts.setOnLoadCallback(drawChart_Activity);
            // Draw the chart and set the chart values
            function drawChart_Activity() {
                var data = google.visualization.arrayToDataTable([
                    ['Category', 'Number'],
                    ['Active Accounts', total_active_accounts],
                    ['Inactive Accounts', total_inactive_accounts]
                ]);
                // Optional; add a title and set the width and height of the chart
                var options = {'title':'Client Accounts Activity Overview', is3D:true};

                // Display the chart inside the <div> element with id="piechart"
                var chart = new google.visualization.PieChart(document.getElementById('piechart_accounts'));
                chart.draw(data, options);
            }
        </script>
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
                            <h4><strong>ADMIN DASHBOARD</strong></h4>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-sm-12">
                            <div class="super-shadow dashboard-stats">
                                <header class="text-center"><strong>Clients Overview</strong></header>
                                <article class="text-center">
                                     <div class="row">
                                        <div class="col-sm-6">
                                            <div id="piechart_activity"></div>
                                            <div class="text-center">
                                                Active Clients: <strong><?php echo number_format($total_active_clients); ?></strong>&nbsp; | &nbsp;
                                                Inactive Clients: <strong><?php echo number_format($total_clients - $total_active_clients); ?></strong> |
                                                Total Clients: <strong><?php echo number_format($total_clients); ?></strong>
                                            </div>
                                        </div>
                                        <div class="col-sm-6">
                                            <div id="piechart_accounts"></div>
                                            <div class="text-center">
                                                Active Accounts: <strong><?php echo number_format($total_active_accounts); ?></strong>&nbsp; | &nbsp;
                                                Inactive Accounts: <strong><?php echo number_format($total_accounts - $total_active_accounts); ?></strong> |
                                                Total Accounts: <strong><?php echo number_format($total_accounts); ?></strong>
                                            </div>
                                        </div>
                                    </div>
                                </article>
                            </div>
                        </div>


                        <!--<div class="col-sm-3">
                            <div class="super-shadow dashboard-stats">
                                <header class="text-center"><strong>Active Clients</strong></header>
                                <article class="text-center">
                                    Clients: <strong><?php /*echo number_format($total_active_clients); */?></strong>&nbsp; | &nbsp;
                                    Accts: <strong><?php /*echo number_format($total_active_accounts); */?></strong>
                                </article>
                            </div>
                        </div>-->

                        <div class="col-sm-3">
                            <div class="super-shadow dashboard-stats">
                                <header class="text-center"><strong>Training Active Clients</strong></header>
                                <article class="text-center">
                                    <strong><?php echo number_format($total_active_training_clients); ?></strong>&nbsp; | &nbsp;
                                    <strong><?php echo number_format($percentage_active_training_client, 2, ".", ",") . "%"; ?></strong>
                                </article>
                            </div>
                        </div>

                        <!--<div class="col-sm-3">
                            <div class="super-shadow dashboard-stats">
                                <header class="text-center"><strong>Total Clients</strong></header>
                                <article class="text-center">
                                    <strong><?php /*echo number_format($total_clients); */?></strong>
                                </article>
                            </div>
                        </div>-->

                        <div class="col-sm-3">
                            <div class="super-shadow dashboard-stats">
                                <header class="text-center"><strong>Points Balance</strong></header>
                                <article class="text-center">
                                    <strong><?php echo number_format($total_point_balance, 2, ".", ","); ?></strong>&nbsp; | &nbsp;
                                    <strong>$ <?php echo number_format($total_point_balance_dollar_amount, 2, ".", ","); ?></strong>
                                </article>
                            </div>
                        </div>

                        <div class="col-sm-3">
                            <div class="super-shadow dashboard-stats">
                                <header class="text-center"><strong>Expired Points</strong></header>
                                <article class="text-center">
                                    <strong><?php echo number_format($total_expired_point, 2, ".", ","); ?></strong>&nbsp; | &nbsp;
                                    <strong>$ <?php echo number_format($total_expired_point_dollar_amount, 2, ".", ","); ?></strong>
                                </article>
                            </div>
                        </div>

                        <div class="col-sm-3">
                            <div class="super-shadow dashboard-stats">
                                <header class="text-center"><strong>Failed SMS Code</strong></header>
                                <article class="text-center">
                                    <strong><?php echo number_format(count($failed_sms_code)); ?></strong>
                                </article>
                            </div>
                        </div>

                    </div>
                    <hr style="border: thin dotted #c5c5c5" />
                                        
                    <div class="row">
                        <div class="col-lg-8">
                                                        
                            <div class="section-tint super-shadow">
                                <div class="row">
                                    <div class="col-sm-12">
                                        <h5>Latest Transactions</h5>
                                        <ul class="nav nav-tabs">
                                            <li class="active"><a data-toggle="tab" href="#latest_funding">Deposit</a></li>
                                            <li><a data-toggle="tab" href="#latest_withdrawal">Withdrawal</a></li>
                                        </ul>
                                        <div class="tab-content">
                                            <div id="latest_funding" class="tab-pane fade in active">
                                                <table class="table table-responsive table-striped table-bordered table-hover">
                                                    <thead>
                                                        <tr>
                                                            <th>Trans ID</th>
                                                            <th>Client Name</th>
                                                            <th>Acct No</th>
                                                            <th>Amount Ordered</th>
                                                            <th>Status</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        <?php if(isset($latest_funding) && !empty($latest_funding)) { foreach ($latest_funding as $row) { ?>
                                                        <tr>
                                                            <td><?php echo $row['trans_id']; ?></td>
                                                            <td><?php echo $row['full_name']; ?></td>
                                                            <td><?php echo $row['ifx_acct_no']; ?></td>
                                                            <td>&dollar; <?php echo $row['dollar_ordered']; ?></td>
                                                            <td><?php echo status_user_deposit($row['status']); ?></td>
                                                        </tr>
                                                        <?php } } else { echo "<tr><td colspan='5' class='text-danger'><em>No results to display</em></td></tr>"; } ?>
                                                    </tbody>
                                                </table>
                                            </div>
                                            <div id="latest_withdrawal" class="tab-pane fade">
                                                <table class="table table-responsive table-striped table-bordered table-hover">
                                                    <thead>
                                                        <tr>
                                                            <th>Trans ID</th>
                                                            <th>Client Name</th>
                                                            <th>Acct No</th>
                                                            <th>Amount Ordered</th>
                                                            <th>Status</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        <?php if(isset($latest_withdrawal) && !empty($latest_withdrawal)) { foreach ($latest_withdrawal as $row) { ?>
                                                        <tr>
                                                            <td><?php echo $row['trans_id']; ?></td>
                                                            <td><?php echo $row['full_name']; ?></td>
                                                            <td><?php echo $row['ifx_acct_no']; ?></td>
                                                            <td>&dollar; <?php echo $row['dollar_withdraw']; ?></td>
                                                            <td><?php echo status_user_withdrawal($row['status']); ?></td>
                                                        </tr>
                                                        <?php } } else { echo "<tr><td colspan='5' class='text-danger'><em>No results to display</em></td></tr>"; } ?>
                                                    </tbody>
                                                </table>
                                            </div>
                                        </div>

                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <div class="col-lg-4">

                            <div class="section-tint super-shadow">
                                <h5>Exchange Rate</h5>
                                <hr/>
                                <p>
                                    Deposit ILPR: <?php echo IPLRFUNDRATE; ?><br />
                                    Deposit Non-ILPR: <?php echo NFUNDRATE; ?><br />
                                    Withdrawal: <?php echo WITHDRATE; ?>
                                </p>
                            </div>

                            <div class="section-tint super-shadow">
                                <h5>Latest Bulletins</h5>
                                <hr/>
                                <?php if(isset($latest_bulletin) && !empty($latest_bulletin)) { foreach ($latest_bulletin as $row) {
                                    $allowed_admin = explode(",", $row['allowed_admin']);
                                    if (!in_array($_SESSION['admin_unique_code'], $allowed_admin)) { continue; }
                                ?>
                                <div class="row">
                                    <div class="col-sm-12">
                                        <p><a href="bulletin_read.php?id=<?php echo dec_enc('encrypt', $row['admin_bulletin_id']); ?>" title="<?php echo $row['title']; ?>"><?php echo $row['title']; ?></a></p>
                                        <em><strong>Author:</strong> <?php echo $row['bulletin_author']; ?></em><br/>
                                        <em><strong>Posted:</strong> <?php echo datetime_to_text($row['created']); ?></em><br/><br/>
                                        <hr/>
                                    </div>
                                </div>
                                <?php } } else { echo "<div class='row'><div class='col-sm-12'><span class='text-danger'><em>No results to display</em></span></div></div>"; } ?>
                                
                                <div class="row">
                                    <div class="col-sm-12"><small>Go to <a href="bulletin_centre.php">Bulletin Centre</a> to read other bulletins</small></div>
                                </div>
                            </div>

                        </div>
                    </div>

                    <!-- Unique Page Content Ends Here
                    ================================================== -->
                    
                </div>
                
            </div>
        </div>
        <?php require_once 'layouts/footer.php'; ?>
    </body>
</html>