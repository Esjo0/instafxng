<?php
require_once("../../init/initialize_admin.php");
if (!$session_admin->is_logged_in()) {
    redirect_to("login.php");
}

// table table
$query = "SELECT CONCAT(u.last_name, SPACE(1), u.first_name) AS client_full_name, u.phone, u.email,
    u.created AS user_reg_date, ud.real_dollar_equivalent, uft.trans_id, ud.created, uft.trans_type,
    uft.comment
    FROM user_first_transaction AS uft
    INNER JOIN user AS u ON u.user_code = uft.user_code
    INNER JOIN user_deposit AS ud ON ud.trans_id = uft.trans_id
    WHERE uft.status = '2' ORDER BY uft.created DESC ";

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
        <title>Instaforex Nigeria | Admin - Client First Transaction</title>
        <meta name="title" content="Instaforex Nigeria | Admin - Client First Transaction" />
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
                            <h4><strong>CLIENT FIRST TRANSACTION - REVIEWED</strong></h4>
                        </div>
                    </div>
                    
                    <div class="section-tint super-shadow">

                        <div class="row">
                            <div class="col-sm-12">
                                <p>Below is a list of clients that made their first transaction and was reviewed by compliance.</p>
                                <table class="table table-responsive table-striped table-bordered table-hover">
                                    <thead>
                                    <tr>
                                        <th>Client Details</th>
                                        <th>Transaction ID</th>
                                        <th>Transaction Date</th>
                                        <th>Amount</th>
                                        <th>Type</th>
                                        <th>Comment</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    <?php if(isset($first_time_transaction) && !empty($first_time_transaction)) { foreach ($first_time_transaction as $row) { ?>
                                        <tr>
                                            <td>
                                                <?php echo $row['client_full_name']; ?><br />
                                                <?php echo $row['email']; ?><br />
                                                <?php echo $row['phone']; ?>
                                            </td>
                                            <td><?php echo $row['trans_id']; ?></td>
                                            <td><?php echo datetime_to_text($row['created']); ?></td>
                                            <td><?php echo number_format($row['real_dollar_equivalent'], 2, ".", ","); ?></td>
                                            <td><?php echo financial_trans_type($row['trans_type']); ?></td>
                                            <td><?php echo $row['comment']; ?></td>
                                        </tr>
                                    <?php } } else { echo "<tr><td colspan='6' class='text-danger'><em>No results to display</em></td></tr>"; } ?>
                                    </tbody>
                                </table>

                                <?php if(isset($first_time_transaction) && !empty($first_time_transaction)) { ?>
                                    <div class="tool-footer text-right">
                                        <p class="pull-left">Showing <?php echo $prespagelow . " to " . $prespagehigh . " of " . $numrows; ?> entries</p>
                                    </div>
                                <?php } ?>

                            </div>
                        </div>
                        <?php if(isset($first_time_transaction) && !empty($first_time_transaction)) { require_once '../layouts/pagination_links.php'; } ?>

                    </div>

                    <!-- Unique Page Content Ends Here
                    ================================================== -->
                    
                </div>
                
            </div>
        </div>
        <?php require_once '../layouts/footer.php'; ?>
    </body>
</html>