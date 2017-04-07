<?php
require_once("../init/initialize_admin.php");
if (!$session_admin->is_logged_in()) {
    redirect_to("login.php");
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
$query = "SELECT user_code, CONCAT(last_name, SPACE(1), first_name) AS full_name, email, phone, created FROM user WHERE status = '1' ORDER BY created DESC ";
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


// Admin Allowed: Toye, Lekan, Demola, Bunmi "FWJK4",
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
                                <p>Below is a table of all clients.</p>
                                <p>
                                    <strong>Total Unique Clients: </strong><?php echo number_format($numrows); ?> <br />
                                    <strong>Clients Today: </strong> <?php echo number_format($clients_today); ?><br />
                                    <strong>Clients Yesterday: </strong><?php echo number_format($clients_yesterday); ?> <br />
                                </p>
                                <div class="table-wrap">
                                    <table class="table table-responsive table-striped table-bordered table-hover">
                                        <thead>
                                            <tr>
                                                <th>Full Name</th>
                                                <th>Email</th>
                                                <th>Phone</th>
                                                <th>Opening Date</th>
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
                                                <td>
                                                    <a target="_blank" title="View" class="btn btn-info" href="client_detail.php?id=<?php echo encrypt($row['user_code']); ?>"><i class="glyphicon glyphicon-eye-open icon-white"></i> </a>
                                                    <?php if($allowed_update_profile) { ?>
                                                        <a target="_blank" title="Update" class="btn btn-info" href="client_update.php?id=<?php echo encrypt($row['user_code']); ?>"><i class="glyphicon glyphicon-pencil icon-white"></i> </a>
                                                    <?php } ?>
                                                </td>
                                            </tr>
                                            <?php } } else { echo "<tr><td colspan='5' class='text-danger'><em>No results to display</em></td></tr>"; } ?>
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