<?php
require_once '../../init/initialize_partner.php';
if (!$session_partner->is_logged_in()) {
    redirect_to("../login.php");
}

$partner_details = $_SESSION;
$partner_code = $partner_details['partner_code'];

$query = "SELECT u.user_code, CONCAT(u.last_name, SPACE(1), u.first_name) AS full_name, u.created
        FROM user AS u
        WHERE u.status = '1' AND u.partner_code = '$partner_code' ORDER BY u.created DESC ";

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
$my_referral = $db_handle->fetchAssoc($result);

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <base target="_self">
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Instaforex Nigeria | My Referrals</title>
    <meta name="title" content="Instaforex Nigeria | My Referrals" />
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
            <div class="section-tint super-shadow">
                <div class="row">
                    <div class="col-sm-12">
                        <h3>My Referrals</h3>
                        <p>Below is a list of your referrals.</p>
                    </div>
                </div>
                <div class="row">
                    <div class="col-sm-12">
                        <p>Total Referral: <strong><?php if($numrows) { echo number_format($numrows); } else { echo 0; } ?></strong></p>

                        <div class="table-wrap">
                            <table class="table table-responsive table-striped table-bordered table-hover">
                                <thead>
                                <tr>
                                    <th>Full Name</th>
                                    <th>Reg Date</th>
                                </tr>
                                </thead>
                                <tbody>
                                <?php if(isset($my_referral) && !empty($my_referral)) { foreach ($my_referral as $row) { ?>
                                    <tr>
                                        <td><?php echo $row['full_name']; ?></td>
                                        <td><?php echo datetime_to_text2($row['created']); ?></td>
                                    </tr>
                                <?php } } else { echo "<tr><td colspan='2' class='text-danger'><em>No results to display</em></td></tr>"; } ?>
                                </tbody>
                            </table>
                        </div>

                        <?php if(isset($my_referral) && !empty($my_referral)) { ?>
                            <div class="tool-footer text-right">
                                <p class="pull-left">Showing <?php echo $prespagelow . " to " . $prespagehigh . " of " . $numrows; ?> entries</p>
                            </div>
                        <?php } ?>
                    </div>
                </div>
                <?php if(isset($my_referral) && !empty($my_referral)) { require_once 'layouts/pagination_links.php'; } ?>
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