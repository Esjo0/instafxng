<?php
require_once("../init/initialize_admin.php");

if (!$session_admin->is_logged_in()) {
    redirect_to("login.php");
}

$total_point_balance = $obj_loyalty_point->get_general_point_analysis();

if(isset($_POST['search_text']) && strlen($_POST['search_text']) > 3) {
    $search_text = $_POST['search_text'];
    $query = "SELECT ull.expired_point AS total_expired_point, CONCAT(u.last_name, SPACE(1), u.first_name) AS full_name, u.user_code, u.email, u.phone, u.created,
        CONCAT(a.last_name, SPACE(1), a.first_name) AS account_officer_full_name
        FROM user_loyalty_log AS ull
        INNER JOIN user AS u ON ull.user_code = u.user_code
        INNER JOIN account_officers AS ao ON u.attendant = ao.account_officers_id
        INNER JOIN admin AS a ON ao.admin_code = a.admin_code
        LEFT JOIN user_ifxaccount AS ui ON ull.user_code = ui.user_code
        WHERE (ull.expired_point > 0.00) AND (ui.ifx_acct_no LIKE '%$search_text%' OR u.email LIKE '%$search_text%' OR u.first_name LIKE '%$search_text%' OR u.middle_name LIKE '%$search_text%' OR u.last_name LIKE '%$search_text%' OR u.phone LIKE '%$search_text%' OR u.created LIKE '$search_text%') GROUP BY ull.user_code ORDER BY total_expired_point DESC ";

} else {
    $query = "SELECT ull.expired_point AS total_expired_point, CONCAT(u.last_name, SPACE(1), u.first_name) AS full_name, u.user_code, u.email, u.phone, u.created,
        CONCAT(a.last_name, SPACE(1), a.first_name) AS account_officer_full_name
        FROM user_loyalty_log AS ull
        INNER JOIN user AS u ON ull.user_code = u.user_code
        INNER JOIN account_officers AS ao ON u.attendant = ao.account_officers_id
        INNER JOIN admin AS a ON ao.admin_code = a.admin_code
        WHERE ull.expired_point > 0.00 GROUP BY ull.user_code ORDER BY total_expired_point DESC ";
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
$clients_expired_points = $db_handle->fetchAssoc($result);

?>

<!DOCTYPE html>
<html lang="en">
    <head>
        <base target="_self">
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>Instaforex Nigeria | Admin - Expired Points</title>
        <meta name="title" content="Instaforex Nigeria | Admin - Expired Points" />
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
                            <h4><strong>CLIENTS - EXPIRED POINTS</strong></h4>
                        </div>
                    </div>
                    
                    <div class="section-tint super-shadow">
                        <div class="row">
                            <div class="col-sm-12">

                                <p>Table of general point analysis.</p>
                                <table class="table table-responsive table-striped table-bordered table-hover">
                                    <thead>
                                        <tr>
                                            <th>Points Earned</th>
                                            <th>Points Claimed</th>
                                            <th>Expired Points</th>
                                            <th>Point Balance</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <td><?php echo number_format($total_point_balance['total_point_earned'], 2, ".", ","); ?></td>
                                            <td><?php echo number_format($total_point_balance['total_point_claimed'], 2, ".", ","); ?></td>
                                            <td><?php echo number_format($total_point_balance['total_expired_point'], 2, ".", ","); ?></td>
                                            <td><?php echo number_format($total_point_balance['total_point_balance'], 2, ".", ","); ?></td>
                                        </tr>
                                    </tbody>
                                </table>

                                <hr />

                                <?php if(isset($clients_expired_points) && !empty($clients_expired_points)) { require 'layouts/pagination_links.php'; } ?>

                                <p>Table of clients with points that have expired.</p>
                                <table class="table table-responsive table-striped table-bordered table-hover">
                                    <thead>
                                    <tr>
                                        <th>Full Name</th>
                                        <th>Email</th>
                                        <th>Phone</th>
                                        <th>Account Officer</th>
                                        <th>Expired Points</th>
                                        <th>Reg Date</th>
                                        <th>Action</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    <?php
                                    if(isset($clients_expired_points) && !empty($clients_expired_points)) { foreach ($clients_expired_points as $row) {
                                        ?>
                                        <tr>
                                            <td><?php echo $row['full_name']; ?></td>
                                            <td><?php echo $row['email']; ?></td>
                                            <td><?php echo $row['phone']; ?></td>
                                            <td><?php echo $row['account_officer_full_name']; ?></td>
                                            <td><?php echo number_format($row['total_expired_point'], 2, ".", ","); ?></td>
                                            <td><?php echo datetime_to_text2($row['created']); ?></td>
                                            <td nowrap="nowrap">
                                                <a target="_blank" title="View" class="btn btn-info" href="client_detail.php?id=<?php echo encrypt_ssl($row['user_code']); ?>"><i class="glyphicon glyphicon-eye-open icon-white"></i> </a>
                                            </td>
                                        </tr>
                                    <?php } } else { echo "<tr><td colspan='6' class='text-danger'><em>No results to display</em></td></tr>"; } ?>
                                    </tbody>
                                </table>
                                
                                <?php if(isset($clients_expired_points) && !empty($clients_expired_points)) { ?>
                                <div class="tool-footer text-right">
                                    <p class="pull-left">Showing <?php echo $prespagelow . " to " . $prespagehigh . " of " . $numrows; ?> entries</p>
                                </div>
                                <?php } ?>
                            </div>
                        </div>
                        
                        <?php if(isset($clients_expired_points) && !empty($clients_expired_points)) { require 'layouts/pagination_links.php'; } ?>
                    </div>

                    <!-- Unique Page Content Ends Here
                    ================================================== -->
                    
                </div>
                
            </div>
        </div>
        <?php require_once 'layouts/footer.php'; ?>
    </body>
</html>