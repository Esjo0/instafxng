<?php
require_once '../../app_assets/initialize_partner.php';
if (!$session_partner->is_logged_in()) {
    redirect_to("../login.php");
}
 
//$partner_details = $_SESSION['partner_details'];
//$partner_code= $partner_details['partner_code'];
$partner_code = $_SESSION['partner_code'];

$query = "SELECT * FROM partner_trading_commission WHERE partner_code = '$partner_code' ORDER BY partner_trading_commission_id DESC ";
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
$trading_comm = $db_handle->fetchAssoc($result);

?>
<!DOCTYPE html>
<html lang="en">
    <head>
        <base target="_self">
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>Instafxng Partner | Commission History</title>
        <meta name="title" content="Instafxng Partner | Commission History" />
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
                            <h4><strong>TRADING COMMISSION HISTORY</strong></h4>
                        </div>
                    </div>
                    
                    <div class="section-tint super-shadow">
                        <div class="row">
                            <div class="col-sm-12">
                                <p>Below is the list of all trading commission history</p>

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
                                                        <?php if(isset($trading_comm) && !empty($trading_comm)) { for ($i = 0; $i < count($trading_comm); $i++) { if(!empty($trading_comm[$i]['amount']))  { ?>
                                                        <tr>
                                                            <td><?php echo sprintf("%07d", $trading_comm[$i]['reference_trans_id'])  ?></td>
                                                            <td><?php echo status_trans_type($trading_comm[$i]['trans_type']) ?></td>
                                                             <td><?php echo number_format($trading_comm[$i]['amount'],2); ?></td>
                                                             <td><?php echo number_format($trading_comm[$i]['balance'],2); ?></td>
                                                             <td><?php echo datetime_to_text($trading_comm[$i]['created']) ?></td>
                                                        </tr>
                                                        <?php  }} } else { echo "<tr><td colspan='5' class='text-danger'><em>No results to display</em></td></tr>"; } ?>
                                                    </tbody>
                                                </table>

                                <?php if(isset($trading_comm) && !empty($trading_comm)) { ?>
                                <div class="tool-footer text-right">
                                    <p class="pull-left">Showing <?php echo $prespagelow . " to " . $prespagehigh . " of " . $numrows; ?> entries</p>
                                </div>
                                <?php } ?>

                            </div>
                        </div>

                         <?php if(isset($trading_comm) && !empty($trading_comm)) {  require_once 'layouts/pagination_links.php'; } ?>

                    </div>

                    <!-- Unique Page Content Ends Here
                    ================================================== -->
                    
                </div>
                
            </div>
        </div>
        <?php require_once 'layouts/footer.php'; ?>
    </body>
</html>