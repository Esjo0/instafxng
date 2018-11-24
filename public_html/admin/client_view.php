<?php
require_once("../init/initialize_admin.php");
if (!$session_admin->is_logged_in()) {
    redirect_to("login.php");
}

if(empty($_SESSION['account_officer_filter']) || !isset($_SESSION['account_officer_filter'])) {
    $_SESSION['account_officer_filter'] = 'all';
}

if (isset($_POST['apply_filter'])) {
    foreach ($_POST as $key => $value) {
        $_POST[$key] = $db_handle->sanitizePost(trim($value));
    }
    extract($_POST);
    $_SESSION['account_officer_filter'] = $account_officer;
}

$one_day = 24 * 60 * 60;
$yesterday = time() - $one_day;
$date_today = date('Y-m-d');
$date_yesterday = date('Y-m-d', $yesterday);

$query = "SELECT user_code FROM user WHERE created LIKE '$date_yesterday%'";
$clients_yesterday = $db_handle->numRows($query);

$query = "SELECT user_code FROM user WHERE created LIKE '$date_today%'";
$clients_today = $db_handle->numRows($query);

///////////
if(isset($_SESSION['account_officer_filter']) && $_SESSION['account_officer_filter'] <> 'all') {
    $account_officer_filter = $_SESSION['account_officer_filter'];

    $query = "SELECT u.user_code, CONCAT(u.last_name, SPACE(1), u.first_name) AS full_name,
        u.email, u.phone, u.created, CONCAT(a.last_name, SPACE(1), a.first_name) AS account_officer_full_name
        FROM user AS u
        LEFT JOIN account_officers AS ao ON u.attendant = ao.account_officers_id
        LEFT JOIN admin AS a ON ao.admin_code = a.admin_code
        WHERE u.status = '1' AND ao.account_officers_id = $account_officer_filter ORDER BY u.created DESC ";
} else {
    $query = "SELECT u.user_code, CONCAT(u.last_name, SPACE(1), u.first_name) AS full_name,
        u.email, u.phone, u.created, CONCAT(a.last_name, SPACE(1), a.first_name) AS account_officer_full_name
        FROM user AS u
        LEFT JOIN account_officers AS ao ON u.attendant = ao.account_officers_id
        LEFT JOIN admin AS a ON ao.admin_code = a.admin_code
        WHERE u.status = '1' ORDER BY u.created DESC ";
}

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
$all_clients = $db_handle->fetchAssoc($result);


// Admin Allowed: Toye, Lekan, Demola, Bunmi
$update_allowed = array("FgI5p", "FWJK4", "5xVvl", "43am6");
$allowed_update_profile = in_array($_SESSION['admin_unique_code'], $update_allowed) ? true : false;

?>
<!DOCTYPE html>
<html lang="en">
    <head>
        <base target="_self">
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>Instaforex Nigeria | Admin - View Clients</title>
        <meta name="title" content="Instaforex Nigeria | Admin - View Clients" />
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
                            <h4><strong>VIEW CLIENTS</strong></h4>
                        </div>
                    </div>
                    
                    <div class="section-tint super-shadow">
                        <div class="row">
                            <div class="col-sm-12">
                                <?php require_once 'layouts/feedback_message.php'; ?>

                                <?php $all_account_officers = $admin_object->get_all_account_officers(); ?>
                                <form data-toggle="validator" class="form-inline" role="form" method="post" action="">
                                    <div class="form-group">
                                        <label for="account_officer">Filter By Account Officer:</label>
                                        <select name="account_officer" class="form-control" id="account_officer" required>
                                            <option value="all"> All Clients</option>
                                            <?php if(isset($all_account_officers) && !empty($all_account_officers)) { foreach ($all_account_officers as $row) { ?>
                                                <option value="<?php echo $row['account_officers_id']; ?>" <?php if(isset($_SESSION['account_officer_filter']) && $row['account_officers_id'] == $_SESSION['account_officer_filter']) { echo "selected='selected'"; } ?>><?php echo $row['account_officer_full_name']; ?></option>
                                            <?php } } ?>
                                        </select>
                                    </div>
                                    <input name="apply_filter" type="submit" class="btn btn-primary" value="Apply Filter">
                                </form>

                                <p>Below is a table of all clients.</p>
                                <p>
                                    <strong>Total Unique Clients: </strong><?php echo number_format($numrows); ?> <br />
                                    <strong>Clients Today: </strong> <?php echo number_format($clients_today); ?><br />
                                    <strong>Clients Yesterday: </strong><?php echo number_format($clients_yesterday); ?> <br />
                                </p>

                                <?php if(isset($account_officer_filter)) { ?>
                                    <hr />
                                    <p><strong>Filter Result: </strong> <?php echo number_format($numrows); ?></p>
                                <?php } ?>

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
                                            <?php if(isset($all_clients) && !empty($all_clients)) { foreach ($all_clients as $row) { ?>
                                            <tr>
                                                <td><?php echo $row['full_name']; ?></td>
                                                <td><?php echo $row['email']; ?></td>
                                                <td><?php echo $row['phone']; ?></td>
                                                <td><?php echo datetime_to_text2($row['created']); ?></td>
                                                <td><?php echo $row['account_officer_full_name']; ?></td>
                                                <td nowrap="nowrap">
                                                    <a target="_blank" title="Comment" class="btn btn-success" href="sales_contact_view.php?x=<?php echo encrypt_ssl($row['user_code']); ?>&r=<?php echo 'client_view'; ?>&c=<?php echo encrypt_ssl('CLIENT VIEW'); ?>&pg=<?php echo $currentpage; ?>"><i class="glyphicon glyphicon-comment icon-white"></i> </a>
                                                    <a target="_blank" title="View" class="btn btn-info" href="client_detail.php?id=<?php echo encrypt_ssl($row['user_code']); ?>"><i class="glyphicon glyphicon-eye-open icon-white"></i> </a>
                                                    <?php if($allowed_update_profile) { ?>
                                                        <a target="_blank" title="Update" class="btn btn-info" href="client_update.php?id=<?php echo encrypt_ssl($row['user_code']); ?>"><i class="glyphicon glyphicon-pencil icon-white"></i> </a>
                                                    <?php } ?>
                                                </td>
                                            </tr>
                                            <?php } } else { echo "<tr><td colspan='6' class='text-danger'><em>No results to display</em></td></tr>"; } ?>
                                        </tbody>
                                    </table>
                                </div>
                                
                                <?php if(isset($all_clients) && !empty($all_clients)) { ?>
                                <div class="tool-footer text-right">
                                    <p class="pull-left">Showing <?php echo $prespagelow . " to " . $prespagehigh . " of " . $numrows; ?> entries</p>
                                </div>
                                <?php } ?>
                            </div>
                        </div>
                        <?php if(isset($all_clients) && !empty($all_clients)) { require_once 'layouts/pagination_links.php'; } ?>
                        
                    </div>

                    <!-- Unique Page Content Ends Here
                    ================================================== -->
                    
                </div>
                
            </div>
        </div>
        <?php require_once 'layouts/footer.php'; ?>
    </body>
</html>