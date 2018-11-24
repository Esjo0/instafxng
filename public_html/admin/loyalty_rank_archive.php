<?php
require_once("../init/initialize_admin.php");
if (!$session_admin->is_logged_in()) {
    redirect_to("login.php");
}

// Select all the archived months
$query = "SELECT start_date FROM point_ranking_log WHERE type = '1' GROUP BY start_date DESC";
$result = $db_handle->runQuery($query);
$selected_months = $db_handle->fetchAssoc($result);

// Select all the archived years
$query = "SELECT start_date FROM point_ranking_log WHERE type = '2' GROUP BY start_date DESC";
$result = $db_handle->runQuery($query);
$selected_years = $db_handle->fetchAssoc($result);

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <base target="_self">
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Instaforex Nigeria | Admin - Loyalty Rank Archive</title>
    <meta name="title" content="Instaforex Nigeria | Admin - Loyalty Rank Archive" />
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
                    <h4><strong>LOYALTY RANK ARCHIVE</strong></h4>
                </div>
            </div>

            <div class="section-tint super-shadow">
                <div class="row">
                    <div class="col-sm-12">
                        <h5>View Yearly Loyalty Ranks Archived</h5>
                        <p>Top 10 ranks for each year are rewarded with the equivalent money prize</p>

                        <div class="panel-group" id="accordion">
                            <?php
                            $count = 1;
                            foreach ($selected_years as $row) {
                                $start_date = $row['start_date'];
                                ?>
                                <div class="panel panel-default">
                                    <div class="panel-heading">
                                        <h5 class="panel-title"><a data-toggle="collapse" data-parent="#accordion" href="#collapse_year<?php echo $count; ?>">Top 20 Rank in 2017</a></h5>
                                    </div>
                                    <div id="collapse_year<?php echo $count; ?>" class="panel-collapse collapse">
                                        <div class="panel-body">
                                            <div class="table-responsive mtl">
                                                <table class="table table-striped table-bordered table-hover">
                                                    <thead>
                                                    <tr>
                                                        <th>Position</th>
                                                        <th>Full Name</th>
                                                        <th>Phone</th>
                                                        <th>Rank Value</th>
                                                        <th>Action</th>
                                                    </tr>
                                                    </thead>
                                                    <tbody>

                                                    <?php
                                                    $query = "SELECT prl.position, prl.point_earned, CONCAT(u.first_name, SPACE(1), u.last_name) AS full_name, u.phone, u.user_code
                                                              FROM point_ranking_log AS prl
                                                              INNER JOIN user AS u ON prl.user_code = u.user_code
                                                              WHERE prl.start_date = '$start_date' AND prl.type = '2'
                                                              ORDER BY prl.point_earned DESC LIMIT 20";

                                                    $result = $db_handle->runQuery($query);
                                                    $selected_loyalty = $db_handle->fetchAssoc($result);

                                                    if(isset($selected_loyalty) && !empty($selected_loyalty)) {
                                                        foreach ($selected_loyalty as $row) {
                                                            ?>
                                                            <tr>
                                                                <td><?php echo $row['position']; ?></td>
                                                                <td><?php echo $row['full_name']; ?></td>
                                                                <td><?php echo $row['phone']; ?></td>
                                                                <td><?php echo number_format(($row['point_earned']), 2, ".", ","); ?></td>
                                                                <td><a target="_blank" title="View" class="btn btn-info" href="client_detail.php?id=<?php echo encrypt_ssl($row['user_code']); ?>"><i class="glyphicon glyphicon-eye-open icon-white"></i> </a></td>
                                                            </tr>
                                                        <?php } } else { echo "<tr><td colspan='5' class='text-danger'><em>No results to display</em></td></tr>"; } ?>
                                                    </tbody>
                                                </table>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <?php $count++; } ?>
                        </div>
                        <hr />

                        <h5>View Monthly Loyalty Ranks Archived.</h5>
                        <p>Top 5 ranks for each month are rewarded with the equivalent money prize</p>

                        <div class="panel-group" id="accordion">
                            <?php
                            $count = 1;
                            foreach ($selected_months as $row) {
                                $start_date = $row['start_date'];
                                ?>
                                <div class="panel panel-default">
                                    <div class="panel-heading">
                                        <h5 class="panel-title"><a data-toggle="collapse" data-parent="#accordion" href="#collapse<?php echo $count; ?>">Top 20 Rank in <?php echo date('F, Y', strtotime($start_date)); ?></a></h5>
                                    </div>
                                    <div id="collapse<?php echo $count; ?>" class="panel-collapse collapse">
                                        <div class="panel-body">
                                            <div class="table-responsive mtl">
                                                <table class="table table-striped table-bordered table-hover">
                                                    <thead>
                                                    <tr>
                                                        <th>Position</th>
                                                        <th>Full Name</th>
                                                        <th>Phone</th>
                                                        <th>Rank Value</th>
                                                        <th>Action</th>
                                                    </tr>
                                                    </thead>
                                                    <tbody>

                                                    <?php
                                                    $query = "SELECT prl.position, prl.point_earned, CONCAT(u.first_name, SPACE(1), u.last_name) AS full_name, u.phone, u.user_code
                                                              FROM point_ranking_log AS prl
                                                              INNER JOIN user AS u ON prl.user_code = u.user_code
                                                              WHERE prl.start_date = '$start_date' AND prl.type = '1'
                                                              ORDER BY prl.point_earned DESC LIMIT 20";

                                                    $result = $db_handle->runQuery($query);
                                                    $selected_loyalty = $db_handle->fetchAssoc($result);

                                                    if(isset($selected_loyalty) && !empty($selected_loyalty)) {
                                                        foreach ($selected_loyalty as $row) {
                                                            ?>
                                                            <tr>
                                                                <td><?php echo $row['position']; ?></td>
                                                                <td><?php echo $row['full_name']; ?></td>
                                                                <td><?php echo $row['phone']; ?></td>
                                                                <td><?php echo number_format(($row['point_earned']), 2, ".", ","); ?></td>
                                                                <td><a target="_blank" title="View" class="btn btn-info" href="client_detail.php?id=<?php echo encrypt_ssl($row['user_code']); ?>"><i class="glyphicon glyphicon-eye-open icon-white"></i> </a></td>
                                                            </tr>
                                                        <?php } } else { echo "<tr><td colspan='5' class='text-danger'><em>No results to display</em></td></tr>"; } ?>
                                                    </tbody>
                                                </table>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <?php $count++; } ?>

                        </div>

                    </div>
                </div>

            </div>

            <!-- Unique Page Content Ends Here
            ================================================== -->

        </div>

    </div>
</div>
<?php require_once 'layouts/footer.php'; ?>
</body>
</html>