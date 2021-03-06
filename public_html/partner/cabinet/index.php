<?php
require_once '../../init/initialize_partner.php';
if (!$session_partner->is_logged_in()) {
    redirect_to("../login.php");
}

$partner_details = $_SESSION;
$partner_code = $partner_details['partner_code'];

// GET TOTAL  REFERRAL
$total_referral = $partner_object->count_partner_referral($partner_code);

// GET TOTAL EARNINGS
$total_earnings = $partner_object->sum_partner_earnings($partner_code);

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <base target="_self">
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Instaforex Nigeria | Partner Area</title>
    <meta name="title" content="Instaforex Nigeria | Partner Area" />
    <meta name="keywords" content="">
    <meta name="description" content="">
    <?php require_once 'layouts/head_meta.php'; ?>
</head>
<body>
<?php require_once 'layouts/header.php'; ?>
<!-- Main Body: The is the main content area of the web site, contains a side bar  -->
<div id="main-body" class="container-fluid">
    <div class="row no-gutter">
        <?php require_once 'layouts/navbar.php'; ?>
        <!-- Main Body - Content Area: This is the main content area, unique for each page  -->
        <div id="main-body-content-area" class="col-md-12">

            <!-- Unique Page Content Starts Here
            ================================================== -->
            <div class="row">
                <div class="col-xs-6 col-sm-3">
                    <div class="dashboard-stats">
                        <header>Account Balance</header>
                        <footer>&dollar; <?php echo number_format($total_earnings, 2, ".", ","); ?></footer>
                    </div>
                </div>
                <div class="col-xs-6 col-sm-3">
                    <div class="dashboard-stats">
                        <header>Earnings Yesterday</header>
                        <footer>&dollar; 0.00</footer>
                    </div>
                </div>
                <div class="col-xs-6 col-sm-3">
                    <div class="dashboard-stats">
                        <header>Total Referral</header>
                        <footer><?php echo number_format($total_referral); ?></footer>
                    </div>
                </div>
                <div class="col-xs-6 col-sm-3">
                    <div class="dashboard-stats">
                        <header>Active Referral</header>
                        <footer>0</footer>
                    </div>
                </div>
            </div>

            <div class="section-tint super-shadow">
                <div class="row">
                    <div class="col-sm-12 text-center">
                        <div class="alert alert-success">
                            Referral Link: <strong><a href="https://instafxng.com/?q=<?php echo $partner_code; ?>" target="_blank"> <?php echo "https://instafxng.com/?q=" . $partner_code; ?></a></strong>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-sm-12">
                        <h3>Partner Dashboard</h3>
                        <p>Follow the links in the navigation bar to get to other parts of the portal.</p>
                    </div>
                </div>
                <div class="row"><hr /></div>
                <div class="row">
                    <div class="col-sm-5">

                    </div>
                    <div class="col-sm-7">
                        <h5>Latest Commission</h5>
                        <ul class="nav nav-tabs">
                            <li class="active"><a data-toggle="tab" href="#latest_funding">Financial Commission</a></li>
                            <li><a data-toggle="tab" href="#latest_withdrawal">Trading Commission</a></li>
                        </ul>

                        <div class="tab-content">
                            <div id="latest_funding" class="tab-pane fade in active">
                                <table class="table table-responsive table-striped table-bordered table-hover">
                                    <thead>
                                    <tr>
                                        <th>Trans ID</th>
                                        <th>Transaction Type</th>
                                        <th>Amount</th>
                                        <th>Balance</th>
                                        <th>Date</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    <?php if(isset($fin_comm) && !empty($fin_comm)) { for ($i = 0; $i < 11; $i++) { if(!empty($fin_comm[0][$i]['reference_trans_id']))  { ?>
                                        <tr>

                                            <td><?php echo $fin_comm[0][$i]['reference_trans_id'] ?></td>
                                            <td><?php echo status_fc_type($fin_comm[0][$i]['trans_type']) ?></td>
                                            <td><?php echo $fin_comm[0][$i]['amount'] ?></td>
                                            <td><?php echo $fin_comm[0][$i]['balance'] ?></td>
                                            <td><?php echo datetime_to_text($fin_comm[0][$i]['created']) ?></td>
                                        </tr>
                                    <?php  }} } else { echo "<tr><td colspan='5' class='text-danger'><em>No results to display</em></td></tr>"; } ?>
                                    </tbody>
                                </table>
                            </div>
                            <div id="latest_withdrawal" class="tab-pane fade">
                                <table class="table table-responsive table-striped table-bordered table-hover">
                                    <thead>
                                    <tr>
                                        <th>Trans ID</th>
                                        <th>Transaction Type</th>
                                        <th>Amount</th>
                                        <th>Balance</th>
                                        <th>Date</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    <?php if(isset($trading_comm) && !empty($trading_comm)) { for ($i = 0; $i < 11; $i++) { if(!empty($trading_comm[0][$i]['amount']))  { ?>
                                        <tr>
                                            <td><?php echo sprintf("%07d", $trading_comm[0][$i]['reference_trans_id'])  ?></td>
                                            <td><?php echo status_trans_type($trading_comm[0][$i]['trans_type']) ?></td>
                                            <td><?php echo $trading_comm[0][$i]['amount'] ?></td>
                                            <td><?php echo $trading_comm[0][$i]['balance'] ?></td>
                                            <td><?php echo datetime_to_text($trading_comm[0][$i]['created']) ?></td>
                                        </tr>
                                    <?php  }} } else { echo "<tr><td colspan='5' class='text-danger'><em>No results to display</em></td></tr>"; } ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
            <!-- Unique Page Content Ends Here
            ================================================== -->

        </div>

    </div>
    <div class="row no-gutter">
        <?php require_once 'layouts/footer.php'; ?>
    </div>
</div>

</body>
</html>