<?php
require_once("../../init/initialize_admin.php");
if (!$session_admin->is_logged_in()) {
    redirect_to("login.php");
}

// table table
$query = "SELECT * FROM user_first_transaction ";

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
$first_time_transaction = $db_handle->fetchAssoc($result);

?>
<!DOCTYPE html>
<html lang="en">
    <head>
        <base target="_self">
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>Instaforex Nigeria | Admin - Daily Funding Report</title>
        <meta name="title" content="Instaforex Nigeria | Admin - Daily Funding Report" />
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
                            <h4><strong>DAILY FUNDING REPORTS</strong></h4>
                        </div>
                    </div>
                    
                    <div class="section-tint super-shadow">

                        <div class="row">
                            <div class="col-sm-12">
                                <p>Below is the breakdown of daily deposit transactions.</p>
                                <table class="table table-responsive table-striped table-bordered table-hover">
                                    <thead>
                                    <tr>
                                        <th>Date</th>
                                        <th>Order</th>
                                        <th>Not Notified</th>
                                        <th>Completed</th>
                                        <th>Failed</th>
                                        <th>Declined</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    <?php
                                        if(isset($total_deposit) && !empty($total_deposit)) { foreach ($total_deposit as $row) {
                                            $order_date = $row['order_date'];
                                    ?>
                                        <tr>
                                            <td><?php echo $row['order_date']; ?></td>
                                            <td><?php echo $row['total_deposit']; ?></td>
                                            <td><?php echo $db_handle->numRows("SELECT trans_id FROM user_deposit WHERE status = '1' AND STR_TO_DATE(created, '%Y-%m-%d') = '$order_date'"); ?></td>
                                            <td><?php echo $db_handle->numRows("SELECT trans_id FROM user_deposit WHERE status = '8' AND STR_TO_DATE(created, '%Y-%m-%d') = '$order_date'"); ?></td>
                                            <td><?php echo $db_handle->numRows("SELECT trans_id FROM user_deposit WHERE status = '9' AND STR_TO_DATE(created, '%Y-%m-%d') = '$order_date'"); ?></td>
                                            <td><?php echo $db_handle->numRows("SELECT trans_id FROM user_deposit WHERE status IN ('4', '7') AND STR_TO_DATE(created, '%Y-%m-%d') = '$order_date'"); ?></td>
                                        </tr>
                                    <?php } } else { echo "<tr><td colspan='2' class='text-danger'><em>No results to display</em></td></tr>"; } ?>
                                    </tbody>
                                </table>

                                <?php if(isset($total_deposit) && !empty($total_deposit)) { ?>
                                    <div class="tool-footer text-right">
                                        <p class="pull-left">Showing <?php echo $prespagelow . " to " . $prespagehigh . " of " . $numrows; ?> entries</p>
                                    </div>
                                <?php } ?>

                            </div>
                        </div>
                        <?php if(isset($total_deposit) && !empty($total_deposit)) { require_once '../layouts/pagination_links.php'; } ?>

                    </div>

                    <!-- Unique Page Content Ends Here
                    ================================================== -->
                    
                </div>
                
            </div>
        </div>
        <?php require_once '../layouts/footer.php'; ?>
    </body>
</html>