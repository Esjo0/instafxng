<?php
require_once("../../init/initialize_admin.php");
if (!$session_admin->is_logged_in()) {
    redirect_to("login.php");
}

$get_params = allowed_get_params(['p']);
$page = $get_params['p'];

$result = $db_handle->numRows("SELECT * FROM dinner_2016 WHERE admin_interest = '1'");
$interested_notyet = $result;

$result = $db_handle->numRows("SELECT * FROM dinner_2016 WHERE admin_interest = '2'");
$interested_yes = $result;

$result = $db_handle->numRows("SELECT * FROM dinner_2016 WHERE admin_interest = '3'");
$interested_no = $result;

$result = $db_handle->numRows("SELECT * FROM dinner_2016 WHERE admin_interest = '4'");
$interested_maybe = $result;

$result = $db_handle->numRows("SELECT * FROM dinner_2016 WHERE attended = '2'");
$attended = $result;

if(isset($page) && !empty($page)) {
    switch($page) {
        case 'all':
            $query = "SELECT * FROM dinner_2016 ORDER BY created DESC ";
            $showing_msg = "Showing Result for All Registrations";
            break;
        case 'yes':
            $query = "SELECT * FROM dinner_2016 WHERE interest = '2' ORDER BY created DESC ";
            $showing_msg = "Showing Result for Yes Registrations";
            break;
        case 'no':
            $query = "SELECT * FROM dinner_2016 WHERE interest = '3' ORDER BY created DESC ";
            $showing_msg = "Showing Result for No Registrations";
            break;
        case 'maybe':
            $query = "SELECT * FROM dinner_2016 WHERE interest = '4' ORDER BY created DESC ";
            $showing_msg = "Showing Result for Maybe Registrations";
            break;
        default:
            $query = "SELECT * FROM dinner_2016 ORDER BY created DESC ";
            $showing_msg = "Showing Result for All Registrations";
            break;
    }
} else {
    $query = "SELECT * FROM dinner_2016 ORDER BY created DESC ";
}

$numrows = $db_handle->numRows($query);

$rowsperpage = 60;

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
$dinner_reg = $db_handle->fetchAssoc($result);

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <base target="_self">
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Instaforex Nigeria | All Dinner Registration</title>
    <meta name="title" content="Instaforex Nigeria | All Dinner Registration" />
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
                    <h4><strong>ALL DINNER REGISTRATION</strong></h4>
                </div>
            </div>

            <div class="section-tint super-shadow">
                <div class="row">
                    <div class="col-sm-12">
                        <?php require_once '../layouts/feedback_message.php'; ?>

                        <p>Details of 2016 Dinner Attendees.</p>

                        <p>Interest Insight: <br/><br/>
                            <strong>Not Yet:</strong> <?php echo $interested_notyet; ?><br />
                            <strong>Yes:</strong> <?php echo $interested_yes; ?><br />
                            <strong>No:</strong> <?php echo $interested_no; ?><br />
                            <strong>Maybe:</strong> <?php echo $interested_maybe; ?><br />
                        </p>

                        <p><strong>Attended:</strong> <?php echo $attended; ?></p>

                        <div class="row text-center">
                            <div class="col-sm-12">
                                <div class="btn-group btn-breadcrumb">
                                    <a href="<?php echo $_SERVER['PHP_SELF'] . '?p=all'; ?>" class="btn btn-default" title="See All Registrations">All</a>
                                    <a href="<?php echo $_SERVER['PHP_SELF'] . '?p=yes'; ?>" class="btn btn-default" title="Clients that responded Yes">Yes</a>
                                    <a href="<?php echo $_SERVER['PHP_SELF'] . '?p=no'; ?>" class="btn btn-default" title="Clients that responded No">No</a>
                                    <a href="<?php echo $_SERVER['PHP_SELF'] . '?p=maybe'; ?>" class="btn btn-default" title="Clients that responded Maybe">Maybe</a>
                                </div>
                            </div>
                        </div>
                        <br />

                        <?php if(isset($showing_msg)) { ?>
                        <p class="text-center"><?php echo $showing_msg; ?></p>
                        <?php } ?>

                        <table class="table table-responsive table-striped table-bordered table-hover">
                            <thead>
                            <tr>
                                <th>Client Name</th>
                                <th>Email</th>
                                <th>Phone Number</th>
                                <th>Admin Interest</th>
                                <th>Interest</th>
                                <th>Action</th>
                            </tr>
                            </thead>
                            <tbody>
                            <?php
                            if(isset($dinner_reg) && !empty($dinner_reg)) {
                                foreach ($dinner_reg as $row) {
                                    ?>
                                    <tr>
                                        <td><?php echo $row['full_name']; ?></td>
                                        <td><?php echo $row['email']; ?></td>
                                        <td><?php echo $row['phone']; ?></td>
                                        <td><?php echo dinner_interest_status($row['admin_interest']); ?></td>
                                        <td><?php echo dinner_interest_status($row['interest']); ?></td>
                                        <td>
                                            <a title="View" class="btn btn-info" href="dinner/view_reg.php?id=<?php echo encrypt_ssl($row['id_dinner_2016']); ?>"><i class="glyphicon glyphicon-eye-open icon-white"></i></a>
                                        </td>
                                    </tr>
                                <?php } } else { echo "<tr><td colspan='8' class='text-danger'><em>No results to display</em></td></tr>"; } ?>
                            </tbody>
                        </table>
                        <br /><br />

                        <?php if(isset($dinner_reg) && !empty($dinner_reg)) { ?>
                            <div class="tool-footer text-right">
                                <p class="pull-left">Showing <?php echo $prespagelow . " to " . $prespagehigh . " of " . $numrows; ?> entries</p>
                            </div>
                        <?php } ?>

                        <?php if(isset($dinner_reg) && !empty($dinner_reg)) { require_once '../layouts/pagination_links.php'; } ?>


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