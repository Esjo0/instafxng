<?php
require_once("../init/initialize_admin.php");
if (!$session_admin->is_logged_in()) {
    redirect_to("login.php");
}

$get_params = allowed_get_params(['x']);
$location = $get_params['x'];

if(isset($location) && !empty($location))
{
    switch($location)
    {
        case '1':
            $query = "SELECT CONCAT(first_name, SPACE(1), middle_name, SPACE(1), last_name) AS full_name, email, phone, venue, created, updated FROM forum_participant WHERE forum_activate = '1' AND venue = '1' ORDER BY updated DESC ";
            $showing_msg = "Showing Results for All Registered Guests";
            break;
        case '2':
            $query = "SELECT CONCAT(first_name, SPACE(1), middle_name, SPACE(1), last_name) AS full_name, email, phone, venue, created, updated FROM forum_participant WHERE forum_activate = '1' AND venue = '2' ORDER BY updated DESC ";
            $showing_msg = "Showing Results for Confirmed Registered Guests";
            break;
        default:
            $query = "SELECT CONCAT(first_name, SPACE(1), middle_name, SPACE(1), last_name) AS full_name, email, phone, venue, created, updated FROM forum_participant WHERE forum_activate = '1' ORDER BY updated DESC ";
            $showing_msg = "Showing All Reservations";
            break;
    }
} else {
    $query = "SELECT CONCAT(first_name, SPACE(1), middle_name, SPACE(1), last_name) AS full_name, email, phone, venue, created, updated FROM forum_participant WHERE forum_activate = '1' ORDER BY updated DESC ";
}


$numrows = $db_handle->numRows($query);

// For search, make rows per page equal total rows found, meaning, no pagination
// for search results
if (isset($location)) {
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
$forum_regs = $db_handle->fetchAssoc($result);


// Get forum entry route analysis
$query = "SELECT entry_route, COUNT(*) AS entry_count FROM forum_participant WHERE forum_activate = '1' GROUP BY entry_route";
$result = $db_handle->runQuery($query);
$forum_reg_route_analysis = $db_handle->fetchAssoc($result);

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <base target="_self">
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Instaforex Nigeria | Admin - Forum Registration</title>
    <meta name="title" content="Instaforex Nigeria | Admin - Forum Registration" />
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
                    <h4><strong>FORUM REGISTRATION</strong></h4>
                </div>
            </div>

            <div class="section-tint super-shadow">
                <div class="row">
                    <div class="col-sm-12">
                        <?php require_once 'layouts/feedback_message.php'; ?>

                        <p><a href="edu_forum_reg2.php" class="btn btn-default" title="All time registrations"><i class="fa fa-arrow-circle-right"></i> View All Forum Registrations</a></p>
                        <hr />
                        <p>Entry channel for current registrations.</p>
                        <table class="table table-responsive table-striped table-bordered table-hover">
                            <thead>
                            <tr>
                                <th>Channel</th>
                                <th>Total</th>
                            </tr>
                            </thead>
                            <tbody>
                            <?php if(isset($forum_reg_route_analysis) && !empty($forum_reg_route_analysis)) { foreach ($forum_reg_route_analysis as $row) { ?>
                                <tr>
                                    <td><?php echo entry_route_forum_participants($row['entry_route']); ?></td>
                                    <td><?php echo $row['entry_count']; ?></td>
                                </tr>
                            <?php } } else { echo "<tr><td colspan='5' class='text-danger'><em>No results to display</em></td></tr>"; } ?>
                            </tbody>
                        </table>

                        <hr />
                        <br />

                        <center>
                            <div class="btn-group btn-group-sm">
                                <a href="<?php echo $_SERVER['PHP_SELF']; ?>" class="<?php if(!$location){echo 'btn btn-info';}else{echo 'btn btn-default';} ?>">All</a>
                                <a href="<?php echo $_SERVER['PHP_SELF'] . '?x=1'; ?>" class="<?php if(isset($location) && !empty($location) && $location == '1'){echo 'btn btn-info';}else{echo 'btn btn-default';} ?>">Diamond Estate</a>
                                <a href="<?php echo $_SERVER['PHP_SELF'] . '?x=2'; ?>" class="<?php if(isset($location) && !empty($location) && $location == '2'){echo 'btn btn-info';}else{echo 'btn btn-default';} ?>">HFP Eastline</a>
                            </div>
                        </center>
                        <p>Below is the list of all new registrations for the upcoming forum event.</p>
                        <table class="table table-responsive table-striped table-bordered table-hover">
                            <thead>
                            <tr>
                                <th>Full Name</th>
                                <th>Email</th>
                                <th>Phone Number</th>
                                <th>Venue Chosen</th>
                                <th>Reg Date</th>
                            </tr>
                            </thead>
                            <tbody>
                            <?php if(isset($forum_regs) && !empty($forum_regs)) { foreach ($forum_regs as $row) { ?>
                                <tr>
                                    <td><?php echo $row['full_name']; ?></td>
                                    <td><?php echo $row['email']; ?></td>
                                    <td><?php echo $row['phone']; ?></td>
                                    <td><?php echo forum_reg_venue($row['venue']); ?></td>
                                    <td><?php if(!empty($row['updated'])) { echo datetime_to_text($row['updated']); } ?></td>
                                </tr>
                            <?php } } else { echo "<tr><td colspan='5' class='text-danger'><em>No results to display</em></td></tr>"; } ?>
                            </tbody>
                        </table>
                        <?php if(isset($forum_regs) && !empty($forum_regs)) { ?>
                            <div class="tool-footer text-right">
                                <p class="pull-left">Showing <?php echo $prespagelow . " to " . $prespagehigh . " of " . $numrows; ?> entries</p>
                            </div>
                        <?php } ?>
                    </div>
                </div>



                <?php if(isset($forum_regs) && !empty($forum_regs)):?>
                    <div class="row text-center" style="padding: 0 25px;">
                        <div class="col-sm-12">
                            <ul class="pagination pagination-sm">
                                <?php
                                $CurrentURL = getCurrentURL();
                                if (!strpos($CurrentURL, '?'))
                                {
                                    $CurrentURL = $CurrentURL.'?';
                                }

                                $range = 4;

                                // if not on page 1, don't show back links
                                if ($currentpage > 1) {
                                    // show << link to go back to page 1
                                    echo "<li><a href=\"{$CurrentURL}&pg=1\">First</a></li>";
                                    // get previous page num
                                    $prevpage = $currentpage - 1;
                                    // show < link to go back to 1 page
                                    echo "<li><a href='{$CurrentURL}&pg=$prevpage'><</a><li>";
                                }

                                // loop to show links to range of pages around current page
                                for ($x = ($currentpage - $range); $x < (($currentpage + $range) + 1); $x++) {
                                    // if it's a valid page number...
                                    if (($x > 0) && ($x <= $totalpages)) {
                                        // if we're on current page...
                                        if ($x == $currentpage) {
                                            // 'highlight' it but don't make a link
                                            echo "<li class=\"active\"><a href=''>$x</a></li>";
                                            // if not current page...
                                        } else {
                                            // make it a link
                                            echo "<li><a href='{$CurrentURL}&pg=$x'>$x</a></li>";
                                        } // end else
                                    } // end if
                                } // end for

                                // if not on last page, show forward and last page links
                                if ($currentpage != $totalpages) {
                                    // get next page
                                    $nextpage = $currentpage + 1;
                                    // echo forward link for next page
                                    echo "<li><a href='{$CurrentURL}&pg=$nextpage'>></a></li>";
                                    // echo forward link for lastpage
                                    echo "<li><a href='{$CurrentURL}&pg=$totalpages'>Last</a></li>";
                                } // end if
                                /****** end build pagination links ******/
                                ?>
                            </ul>
                        </div>
                    </div>
                <?php endif; ?>

            </div>

            <!-- Unique Page Content Ends Here
            ================================================== -->

        </div>

    </div>
</div>
<?php require_once 'layouts/footer.php'; ?>
</body>
</html>