<?php
require_once("../init/initialize_admin.php");
if (!$session_admin->is_logged_in()) { redirect_to("login.php"); }

$base_query = "SELECT SUM(td.volume) AS sum_volume, SUM(td.commission) AS sum_commission, u.user_code, CONCAT(u.last_name, SPACE(1), u.first_name) AS full_name,
    u.email, u.phone, u.created, CONCAT(a.last_name, SPACE(1), a.first_name) AS account_officer_full_name
    FROM trading_commission AS td
    INNER JOIN user_ifxaccount AS ui ON td.ifx_acct_no = ui.ifx_acct_no
    INNER JOIN user AS u ON ui.user_code = u.user_code
    LEFT JOIN account_officers AS ao ON u.attendant = ao.account_officers_id
    LEFT JOIN admin AS a ON ao.admin_code = a.admin_code
    WHERE date_earned BETWEEN '2018-10-01' AND '2018-10-31' GROUP BY u.email ORDER BY sum_commission DESC ";

$db_handle->runQuery("CREATE TEMPORARY TABLE reference_clients AS " . $base_query);

$query = "SELECT sum_volume, sum_commission, user_code, full_name, email, phone, created, account_officer_full_name FROM reference_clients
    WHERE user_code IN (
        SELECT u.user_code
        FROM trading_commission AS td
        INNER JOIN user_ifxaccount AS ui ON td.ifx_acct_no = ui.ifx_acct_no
        INNER JOIN user AS u ON ui.user_code = u.user_code
        WHERE date_earned BETWEEN '2018-11-01' AND '2018-11-30' GROUP BY u.email ORDER BY sum_commission DESC
    ) ";

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
$retained_clients = $db_handle->fetchAssoc($result);

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
                        <p>Select a period below.</p>

                        <div class="table-wrap">
                            <table class="table table-responsive table-striped table-bordered table-hover">
                                <thead>
                                <tr>
                                    <th>Full Name</th>
                                    <th>Email</th>
                                    <th>Phone</th>
                                    <th>Reg Date</th>
                                    <th>Account Officer</th>
                                    <th>Action</th>
                                </tr>
                                </thead>
                                <tbody>
                                <?php if(isset($retained_clients) && !empty($retained_clients)) { foreach ($retained_clients as $row) { ?>
                                    <tr>
                                        <td><?php echo $row['full_name']; ?></td>
                                        <td><?php echo $row['email']; ?></td>
                                        <td><?php echo $row['phone']; ?></td>
                                        <td><?php echo datetime_to_text2($row['created']); ?></td>
                                        <td><?php echo $row['account_officer_full_name']; ?></td>
                                        <td nowrap="nowrap">
                                            <a target="_blank" title="View" class="btn btn-info" href="client_detail.php?id=<?php echo encrypt($row['user_code']); ?>"><i class="glyphicon glyphicon-eye-open icon-white"></i> </a>                                            
                                        </td>
                                    </tr>
                                <?php } } else { echo "<tr><td colspan='6' class='text-danger'><em>No results to display</em></td></tr>"; } ?>
                                </tbody>
                            </table>
                        </div>

                        <?php if(isset($retained_clients) && !empty($retained_clients)) { ?>
                            <div class="tool-footer text-right">
                                <p class="pull-left">Showing <?php echo $prespagelow . " to " . $prespagehigh . " of " . $numrows; ?> entries</p>
                            </div>
                        <?php } ?>

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