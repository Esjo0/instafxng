<?php
require_once("../../init/initialize_admin.php");
if (!$session_admin->is_logged_in()) {
    redirect_to("login.php");
}

$from_date = date('Y-m-d', strtotime('first day of last month'));
$to_date = date('Y-m-d', strtotime('last day of last month'));

$query = "SELECT u.user_code, CONCAT(u.last_name, SPACE(1), u.first_name) AS full_name, u.email, u.phone
        FROM user AS u
        WHERE (STR_TO_DATE(u.created, '%Y-%m-%d') BETWEEN '$from_date' AND '$to_date') ORDER BY u.created DESC ";
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
$client_insight = $db_handle->fetchAssoc($result);


?>
<!DOCTYPE html>
<html lang="en">
<head>
    <base target="_self">
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Instaforex Nigeria | Client Profile Insight</title>
    <meta name="title" content="Instaforex Nigeria | Client Profile Insight" />
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
                    <h4><strong>CLIENT PROFILE INSIGHT</strong></h4>
                </div>
            </div>

            <div class="section-tint super-shadow">
                <div class="row">
                    <div class="col-sm-12">
                        <?php require_once '../layouts/feedback_message.php'; ?>

                        <p>Details of unique clients that joined the system last month.</p>

                        <table class="table table-responsive table-striped table-bordered table-hover">
                            <thead>
                            <tr>
                                <th>Client Name</th>
                                <th>Email</th>
                                <th>Phone Number</th>
                                <th>Action</th>
                            </tr>
                            </thead>
                            <tbody>
                            <?php
                            if(isset($client_insight) && !empty($client_insight)) {
                                foreach ($client_insight as $row) {
                                    ?>
                                    <tr>
                                        <td><?php echo $row['full_name']; ?></td>
                                        <td><?php echo $row['email']; ?></td>
                                        <td><?php echo $row['phone']; ?></td>
                                        <td>
                                            <a title="View" class="btn btn-info" href="client_reach.php?x=<?php echo encrypt($row['user_code']); ?>"><i class="glyphicon glyphicon-eye-open icon-white"></i></a>
                                        </td>
                                    </tr>
                                <?php } } else { echo "<tr><td colspan='8' class='text-danger'><em>No results to display</em></td></tr>"; } ?>
                            </tbody>
                        </table>
                        <br /><br />

                        <?php if(isset($client_insight) && !empty($client_insight)) { ?>
                            <div class="tool-footer text-right">
                                <p class="pull-left">Showing <?php echo $prespagelow . " to " . $prespagehigh . " of " . $numrows; ?> entries</p>
                            </div>
                        <?php } ?>

                        <?php if(isset($client_insight) && !empty($client_insight)) { require_once '../layouts/pagination_links.php'; } ?>


                    </div>
                </div>

            </div>

            <!-- Unique Page Content Ends Here
            ================================================== -->

        </div>

    </div>
</div>
<?php require_once '../layouts/footer.php'; ?>
</body>
</html>