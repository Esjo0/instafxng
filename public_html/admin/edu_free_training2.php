<?php
require_once("../init/initialize_admin.php");
if (!$session_admin->is_logged_in()) {
    redirect_to("login.php");
}

$client_operation = new clientOperation();

$total_entry = $db_handle->numRows("SELECT * FROM free_training_campaign");

$query = "SELECT email
        FROM free_training_campaign AS ftc
        INNER JOIN free_training_campaign_comment AS fctcc
        ON ftc.free_training_campaign_id = fctcc.training_campaign_id
        GROUP BY fctcc.training_campaign_id";
$total_contacted = $db_handle->numRows($query);
$total_not_contacted = $total_entry - $total_contacted;

if(isset($_GET['f'])) {
    $person = $_GET['f'];
    switch($person) {
        case '1': $selected_agent = "1"; $selected_agent_name = "Esther"; break;
        case '2': $selected_agent = "2"; $selected_agent_name = "Titi"; break;
        case '3': $selected_agent = "3"; $selected_agent_name = "Percy"; break;
        default: $selected_agent = false;
    }
} else {
    $selected_agent = false;
}

if(isset($_POST['search_text']) && strlen($_POST['search_text']) > 3) {
    $search_text = $_POST['search_text'];

    $query = "SELECT ftc.free_training_campaign_id, CONCAT(ftc.last_name, SPACE(1), ftc.first_name) AS full_name, ftc.email, ftc.phone,
        ftc.training_interest, ftc.training_centre, ftc.created, s.alias AS main_state, u.user_code
        FROM free_training_campaign AS ftc
        LEFT JOIN state AS s ON ftc.state_id = s.state_id
        LEFT JOIN free_training_campaign_comment AS fctcc ON ftc.free_training_campaign_id = fctcc.training_campaign_id
        LEFT JOIN user AS u on u.email = ftc.email
        WHERE fctcc.comment IS NULL AND (ftc.first_name LIKE '%$search_text%' OR ftc.last_name LIKE '%$search_text%' OR ftc.email LIKE '%$search_text%' OR ftc.phone LIKE '%$search_text%' OR ftc.created LIKE '$search_text%') GROUP BY ftc.email ";
} else {
    if(!empty($selected_agent)) {
        $query = "SELECT ftc.free_training_campaign_id, CONCAT(ftc.last_name, SPACE(1), ftc.first_name) AS full_name, ftc.email, ftc.phone,
                ftc.training_interest, ftc.training_centre, ftc.created, s.alias AS main_state, u.user_code
                FROM free_training_campaign AS ftc
                LEFT JOIN state AS s ON ftc.state_id = s.state_id
                LEFT JOIN free_training_campaign_comment AS fctcc ON ftc.free_training_campaign_id = fctcc.training_campaign_id
                LEFT JOIN user AS u on u.email = ftc.email
                WHERE fctcc.comment IS NULL AND ftc.attendant = $selected_agent ORDER BY ftc.created DESC ";
    } else {
        $query = "SELECT ftc.free_training_campaign_id, CONCAT(ftc.last_name, SPACE(1), ftc.first_name) AS full_name, ftc.email, ftc.phone,
                ftc.training_interest, ftc.training_centre, ftc.created, s.alias AS main_state, u.user_code
                FROM free_training_campaign AS ftc
                LEFT JOIN state AS s ON ftc.state_id = s.state_id
                LEFT JOIN free_training_campaign_comment AS fctcc ON ftc.free_training_campaign_id = fctcc.training_campaign_id
                LEFT JOIN user AS u on u.email = ftc.email
                WHERE fctcc.comment IS NULL
                ORDER BY ftc.created DESC ";
    }
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
$all_registrations = $db_handle->fetchAssoc($result);

?>
<!DOCTYPE html>
<html lang="en">
    <head>
        <base target="_self">
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>Instaforex Nigeria | Admin - Manage Bulletins</title>
        <meta name="title" content="Instaforex Nigeria | Admin - Manage Bulletins" />
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
                            <h4><strong>FREE TRAINING CAMPAIGN (NOT CONTACTED)</strong></h4>
                        </div>
                    </div>
                    
                    <div class="section-tint super-shadow">
                        <div class="row">
                            <div class="col-sm-12">
                                <?php require_once 'layouts/feedback_message.php'; ?>
                                <p>Below is the table of Free Forex Training prospects.</p>
                                <p><strong>Total Entry:</strong> <?php echo number_format($total_entry); ?><br />
                                <strong>Total Entry Not Contacted:</strong> <?php echo number_format($total_not_contacted); ?></p>

                                <div class="row text-center">
                                    <div class="col-sm-12">
                                        <div class="btn-group btn-breadcrumb">
                                            <a href="<?php echo $_SERVER['PHP_SELF']; ?>" class="btn btn-default" title="All Registrations">All Registrations</a>
                                            <a href="<?php echo $_SERVER['PHP_SELF'] . '?f=1'; ?>" class="btn btn-default" title="">Esther</a>
                                            <a href="<?php echo $_SERVER['PHP_SELF'] . '?f=2'; ?>" class="btn btn-default" title="">Titi</a>
                                            <a href="<?php echo $_SERVER['PHP_SELF'] . '?f=3'; ?>" class="btn btn-default" title="">Percy</a>
                                        </div>
                                    </div>
                                </div>
                                <br />

                                <?php if(isset($all_registrations) && !empty($all_registrations)) { require 'layouts/pagination_links.php'; } ?>

                                <?php if(isset($all_registrations) && !empty($all_registrations)) { ?>
                                    <div class="tool-footer text-right">
                                        <p class="pull-left">Showing <?php echo $prespagelow . " to " . $prespagehigh . " of " . $numrows; ?> entries</p>
                                    </div>
                                <?php } ?>

                                <table class="table table-responsive table-striped table-bordered table-hover">
                                    <thead>
                                        <tr>
                                            <th>Name</th>
                                            <th>Email Address</th>
                                            <th>Phone Number</th>
                                            <th>State</th>
                                            <th>IFX Accounts</th>
                                            <th>Created</th>
                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php if(isset($all_registrations) && !empty($all_registrations)) { foreach ($all_registrations as $row) { ?>
                                        <tr>
                                            <td><?php echo $row['full_name']; ?></td>
                                            <td><?php echo $row['email']; ?></td>
                                            <td><?php echo $row['phone']; ?></td>
                                            <td><?php echo $row['main_state']; ?></td>
                                            <td>
                                                <?php echo 'ILPR - ' . count($client_operation->get_client_ilpr_accounts_by_code($row['user_code'])); ?><br />
                                                <?php echo 'Non ILPR - ' . count($client_operation->get_client_non_ilpr_accounts_by_code($row['user_code'])); ?><br />
                                            </td>
                                            <td><?php echo datetime_to_text($row['created']); ?></td>
                                            <td>
                                                <a title="View" class="btn btn-info" href="edu_free_training_view.php?x=<?php echo dec_enc('encrypt', $row['free_training_campaign_id']); ?>&pg=<?php echo $currentpage; ?>"><i class="glyphicon glyphicon-eye-open icon-white"></i></a>
                                            </td>
                                        </tr>
                                        <?php } } else { echo "<tr><td colspan='7' class='text-danger'><em>No results to display</em></td></tr>"; } ?>
                                    </tbody>
                                </table>
                                
                                <?php if(isset($all_registrations) && !empty($all_registrations)) { ?>
                                <div class="tool-footer text-right">
                                    <p class="pull-left">Showing <?php echo $prespagelow . " to " . $prespagehigh . " of " . $numrows; ?> entries</p>
                                </div>
                                <?php } ?>
                            </div>
                        </div>

                        <?php if(isset($all_registrations) && !empty($all_registrations)) { require 'layouts/pagination_links.php'; } ?>

                    </div>

                    <!-- Unique Page Content Ends Here
                    ================================================== -->
                    
                </div>
                
            </div>
        </div>
        <?php require_once 'layouts/footer.php'; ?>
    </body>
</html>