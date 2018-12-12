<?php
require_once("../init/initialize_admin.php");
if (!$session_admin->is_logged_in()) {
    redirect_to("login.php");
}

if(isset($_POST['search_text']) && strlen($_POST['search_text']) > 3) {
    $search_text = $_POST['search_text'];
    $query = "SELECT SUM(td.commission) AS total_commission, u.user_code, CONCAT(u.last_name, SPACE(1), u.first_name) AS full_name, u.email, u.phone, u.created, CONCAT(a.last_name, SPACE(1), a.first_name) AS account_officer_full_name
            FROM trading_commission AS td
            INNER JOIN user_ifxaccount AS ui ON ui.ifx_acct_no = td.ifx_acct_no
            INNER JOIN user AS u ON ui.user_code = u.user_code
            INNER JOIN account_officers AS ao ON u.attendant = ao.account_officers_id
            INNER JOIN admin AS a ON ao.admin_code = a.admin_code
            WHERE date_earned BETWEEN '2017-12-01' AND '2018-09-30' AND (ui.ifx_acct_no LIKE '%$search_text%' OR u.email LIKE '%$search_text%' OR u.first_name LIKE '%$search_text%' OR u.middle_name LIKE '%$search_text%' OR u.last_name LIKE '%$search_text%' OR u.phone LIKE '%$search_text%' OR u.created LIKE '$search_text%')
            GROUP BY u.email
            HAVING total_commission BETWEEN 300 AND 499
            ORDER BY total_commission DESC ";

} else {
    $query = "SELECT SUM(td.commission) AS total_commission, u.user_code, CONCAT(u.last_name, SPACE(1), u.first_name) AS full_name, u.email, u.phone, u.created, CONCAT(a.last_name, SPACE(1), a.first_name) AS account_officer_full_name
            FROM trading_commission AS td
            INNER JOIN user_ifxaccount AS ui ON ui.ifx_acct_no = td.ifx_acct_no
            INNER JOIN user AS u ON ui.user_code = u.user_code
            INNER JOIN account_officers AS ao ON u.attendant = ao.account_officers_id
            INNER JOIN admin AS a ON ao.admin_code = a.admin_code
            WHERE date_earned BETWEEN '2017-12-01' AND '2018-09-30' 
            GROUP BY u.email
            HAVING total_commission BETWEEN 300 AND 499
            ORDER BY total_commission DESC ";
}
$numrows = $db_handle->numRows($query);

// For search, make rows per page equal total rows found, meaning, no pagination
// for search results
if (isset($_POST['search_text'])) {
    $rowsperpage = $numrows;
} else {
    $rowsperpage = 20;
}

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
$selected_silver_comm_clients = $db_handle->fetchAssoc($result);

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <base target="_self">
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Instaforex Nigeria | Admin - Silver Commission Clients</title>
    <meta name="title" content="Instaforex Nigeria | Admin - Silver Commission Clients" />
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
            <div class="search-section">
                <div class="row">
                    <div class="col-xs-12">
                        <form data-toggle="validator" class="form-horizontal" role="form" method="post" action="<?php echo $REQUEST_URI; ?>">
                            <div class="input-group">
                                <input type="hidden" name="search_param" value="all" id="search_param">
                                <input type="text" class="form-control" name="search_text" placeholder="Search term..." required>
                                <span class="input-group-btn">
                                            <button class="btn btn-default" type="submit"><span class="glyphicon glyphicon-search"></span></button>
                                        </span>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-sm-12 text-danger">
                    <h4><strong>SILVER COMMISSION CLIENTS</strong></h4>
                </div>
            </div>

            <div class="section-tint super-shadow">
                <div class="row">
                    <div class="col-sm-12">
                        <?php require_once 'layouts/feedback_message.php'; ?>

                        <p>The list of clients that produced between $300 and $499 commission and above between December 1, 2017 to September 30, 2018</p>

                        <?php if(isset($numrows)) { ?>
                            <p><strong>Result Found: </strong><?php echo number_format($numrows); ?></p>
                        <?php } ?>

                        <?php if(isset($selected_silver_comm_clients) && !empty($selected_silver_comm_clients)) { require 'layouts/pagination_links.php'; } ?>

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
                            <?php
                            if(isset($selected_silver_comm_clients) && !empty($selected_silver_comm_clients)) { foreach ($selected_silver_comm_clients as $row) {
                                ?>
                                <tr>
                                    <td><?php echo $row['full_name']; ?></td>
                                    <td><?php echo $row['email']; ?></td>
                                    <td><?php echo $row['phone']; ?></td>
                                    <td><?php echo datetime_to_text2($row['created']); ?></td>
                                    <td><?php echo $row['account_officer_full_name']; ?></td>
                                    <td nowrap="nowrap">
                                        <a title="Comment" class="btn btn-success" href="sales_contact_view.php?x=<?php echo dec_enc('encrypt', $row['user_code']); ?>&r=<?php echo 'client_comm_silver'; ?>&c=<?php echo encrypt_ssl('SILVER COMMISSION CLIENTS'); ?>&pg=<?php echo $currentpage; ?>"><i class="glyphicon glyphicon-comment icon-white"></i> </a>
                                        <a target="_blank" title="View" class="btn btn-info" href="client_detail.php?id=<?php echo dec_enc('encrypt', $row['user_code']); ?>"><i class="glyphicon glyphicon-eye-open icon-white"></i> </a>
                                    </td>
                                </tr>
                            <?php } } else { echo "<tr><td colspan='6' class='text-danger'><em>No results to display</em></td></tr>"; } ?>
                            </tbody>
                        </table>

                        <?php if(isset($selected_silver_comm_clients) && !empty($selected_silver_comm_clients)) { ?>
                            <div class="tool-footer text-right">
                                <p class="pull-left">Showing <?php echo $prespagelow . " to " . $prespagehigh . " of " . $numrows; ?> entries</p>
                            </div>
                        <?php } ?>
                    </div>
                </div>

                <?php if(isset($selected_silver_comm_clients) && !empty($selected_silver_comm_clients)) { require 'layouts/pagination_links.php'; } ?>
            </div>

            <!-- Unique Page Content Ends Here
            ================================================== -->

        </div>

    </div>
</div>
<?php require_once 'layouts/footer.php'; ?>
</body>
</html>