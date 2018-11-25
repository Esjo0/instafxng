<?php
require_once("../init/initialize_admin.php");
if (!$session_admin->is_logged_in()) {
    redirect_to("login.php");
}

$query = "SELECT SUM(point_claimed) AS total_point_claimed, SUM(dollar_amount) AS total_dollar_amount FROM point_based_claimed WHERE status = '2'";
$result = $db_handle->runQuery($query);
$fetched_data = $db_handle->fetchAssoc($result);
$total_point_claimed = $fetched_data[0]['total_point_claimed'];
$total_dollar_amount = $fetched_data[0]['total_dollar_amount'];

$query = "SELECT ud.trans_id, ud.dollar_ordered, ud.created, ui.ifx_acct_no, pbc.point_claimed,
        pbc.dollar_amount, CONCAT(u.last_name, SPACE(1), u.first_name) AS full_name
        FROM point_based_claimed AS pbc
        INNER JOIN user_deposit AS ud ON pbc.point_based_claimed_id = ud.points_claimed_id
        INNER JOIN user_ifxaccount AS ui ON ud.ifxaccount_id = ui.ifxaccount_id
        INNER JOIN user AS u ON ui.user_code = u.user_code
        WHERE pbc.status = '2' ORDER BY ud.created DESC ";
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
$loyalty_points_claimed = $db_handle->fetchAssoc($result);

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <base target="_self">
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Instaforex Nigeria | Admin - Loyalty Point Claimed</title>
    <meta name="title" content="Instaforex Nigeria | Admin - Loyalty Point Claimed" />
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
                    <h4><strong>LOYALTY POINT CLAIMED</strong></h4>
                </div>
            </div>

            <div class="section-tint super-shadow">
                <div class="row">
                    <div class="col-sm-12">
                        <p class="text-right"><a href="loyalty_point_claimed_search.php" class="btn btn-default" title="Search"><i class="fa fa-arrow-circle-right"></i> Search Loyalty Point Claimed</a></p>
                        <p>Find below the list of clients that have claimed points and the associated transaction IDs, you
                            can click the view button to see more details about the transaction.</p>
                        <p>
                            <strong>Total Point Claimed: </strong><?php echo number_format($total_point_claimed, 2, ".", ","); ?><br />
                            <strong>Total Dollar Amount: </strong>&dollar; <?php echo number_format($total_dollar_amount, 2, ".", ","); ?>
                        </p>

                        <table class="table table-responsive table-striped table-bordered table-hover">
                            <thead>
                            <tr>
                                <th>Transaction ID</th>
                                <th>Client Name</th>
                                <th>IFX Account</th>
                                <th>Funding Request</th>
                                <th>Point Claimed</th>
                                <th>Point Convertion</th>
                                <th>Date</th>
                                <th>Action</th>
                            </tr>
                            </thead>
                            <tbody>
                            <?php
                            if(isset($loyalty_points_claimed) && !empty($loyalty_points_claimed)) {
                                foreach ($loyalty_points_claimed as $row) {
                                    ?>
                                    <tr>
                                        <td><?php echo $row['trans_id']; ?></td>
                                        <td><?php echo $row['full_name']; ?></td>
                                        <td><?php echo $row['ifx_acct_no']; ?></td>
                                        <td class="nowrap">&dollar; <?php echo number_format($row['dollar_ordered'], 2, ".", ","); ?></td>
                                        <td><?php echo $row['point_claimed']; ?></td>
                                        <td>&dollar; <?php echo $row['dollar_amount']; ?></td>
                                        <td><?php echo datetime_to_text($row['created']); ?></td>
                                        <td><a target="_blank" title="View" class="btn btn-info" href="deposit_search_view.php?id=<?php echo encrypt_ssl($row['trans_id']); ?>"><i class="glyphicon glyphicon-eye-open icon-white"></i> </a></td>
                                    </tr>
                                <?php } } else { echo "<tr><td colspan='8' class='text-danger'><em>No results to display</em></td></tr>"; } ?>
                            </tbody>
                        </table>

                        <?php if(isset($loyalty_points_claimed) && !empty($loyalty_points_claimed)) { ?>
                            <div class="tool-footer text-right">
                                <p class="pull-left">Showing <?php echo $prespagelow . " to " . $prespagehigh . " of " . $numrows; ?> entries</p>
                            </div>
                        <?php } ?>
                    </div>
                </div>

                <?php if(isset($loyalty_points_claimed) && !empty($loyalty_points_claimed)) { require_once 'layouts/pagination_links.php'; } ?>
            </div>

            <!-- Unique Page Content Ends Here
            ================================================== -->

        </div>

    </div>
</div>
<?php require_once 'layouts/footer.php'; ?>
</body>
</html>