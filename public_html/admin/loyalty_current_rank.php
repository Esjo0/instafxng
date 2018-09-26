<?php
require_once("../init/initialize_admin.php");
if (!$session_admin->is_logged_in()) {
    redirect_to("login.php");
}

/** Display the top earners for this MONTH ***********/
$query = "SELECT start_date, end_date FROM point_season WHERE is_active = '1' AND type = '1' LIMIT 1";
$result = $db_handle->runQuery($query);
$current_point_season = $db_handle->fetchAssoc($result);

$from_date = $current_point_season[0]['start_date'];
$to_date = $current_point_season[0]['end_date'];

$query = "SELECT pr.month_rank, pr.month_earned_archive, pr.point_claimed, u.last_name, u.first_name, u.email, u.phone, u.user_code
      FROM point_ranking AS pr
      INNER JOIN user AS u ON pr.user_code = u.user_code
      ORDER BY pr.month_rank DESC, first_name ASC LIMIT 20";

$result = $db_handle->runQuery($query);
$selected_loyalty = $db_handle->fetchAssoc($result);
/*****************************************************/

/** Display the top earners for this SEASON ***********/
$query = "SELECT start_date, end_date FROM point_season WHERE is_active = '1' AND type = '2' LIMIT 1";
$result = $db_handle->runQuery($query);
$current_point_season = $db_handle->fetchAssoc($result);

$from_date_year = $current_point_season[0]['start_date'];
$to_date_year = $current_point_season[0]['end_date'];

$query = "SELECT pr.year_rank, pr.year_earned_archive, pr.point_claimed, u.last_name, u.first_name, u.email, u.phone, u.user_code
      FROM point_ranking AS pr
      INNER JOIN user AS u ON pr.user_code = u.user_code
      ORDER BY pr.year_rank DESC, first_name ASC LIMIT 20";

$result = $db_handle->runQuery($query);
$selected_loyalty_year = $db_handle->fetchAssoc($result);
/*****************************************************/

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <base target="_self">
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Instaforex Nigeria | Admin - Loyalty Current Rank</title>
    <meta name="title" content="Instaforex Nigeria | Admin - Loyalty Current Rank" />
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
                    <h4><strong>LOYALTY CURRENT RANK</strong></h4>
                </div>
            </div>

            <div class="section-tint super-shadow">
                <div class="row">
                    <div class="col-sm-12">

                        <header><h5>Top 20 Rank in <?php echo date('F, Y', strtotime($from_date)); ?></h5></header>
                        <article>
                            <div class="table-responsive mtl" style="overflow: scroll; max-height: 500px;">
                                <table class="table table-striped table-bordered table-hover">
                                    <thead>
                                    <tr>
                                        <th>Position</th>
                                        <th>Full Name</th>
                                        <th>Email</th>
                                        <th>Phone</th>
                                        <th>Rank Value</th>
                                        <th></th>
                                    </tr>
                                    </thead>
                                    <tbody>

                                    <?php
                                    $count = 1;
                                    if(isset($selected_loyalty) && !empty($selected_loyalty)) {
                                        foreach ($selected_loyalty as $row) {
                                            ?>
                                            <tr>
                                                <td><?php echo $count; ?></td>
                                                <td><?php echo $row['first_name'] . ' ' . $row['last_name']  ?></td>
                                                <td><?php echo $row['email'] ?></td>
                                                <td><?php echo $row['phone'] ?></td>
                                                <td><?php echo number_format(($row['month_rank']), 2, ".", ","); ?></td>
                                                <td><a target="_blank" title="View" class="btn btn-info" href="client_detail.php?id=<?php echo encrypt($row['user_code']); ?>"><i class="glyphicon glyphicon-eye-open icon-white"></i> </a></td>
                                            </tr>
                                            <?php $count++; } } else { echo "<tr><td colspan='3' class='text-danger'><em>No results to display</em></td></tr>"; } ?>

                                    </tbody>
                                </table>
                            </div>
                        </article>

                        <p><hr /></p>

                        <header><h5>Top 20 Rank in current loyalty year</h5></header>
                        <article>
                            <div class="table-responsive mtl" style="overflow: scroll; max-height: 500px;">
                                <table class="table table-striped table-bordered table-hover">
                                    <thead>
                                    <tr>
                                        <th>Position</th>
                                        <th>Full Name</th>
                                        <th>Email</th>
                                        <th>Phone</th>
                                        <th>Rank Value</th>
                                        <th></th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    <tr>

                                        <?php
                                        $count = 1;
                                        if(isset($selected_loyalty_year) && !empty($selected_loyalty_year)) {
                                        foreach ($selected_loyalty_year as $row) {
                                        ?>
                                    <tr>
                                        <td><?php echo $count; ?></td>
                                        <td><?php echo $row['first_name'] . ' ' . $row['last_name']  ?></td>
                                        <td><?php echo $row['email'] ?></td>
                                        <td><?php echo $row['phone'] ?></td>
                                        <td><?php echo number_format(($row['year_rank']), 2, ".", ","); ?></td>
                                        <td><a target="_blank" title="View" class="btn btn-info" href="client_detail.php?id=<?php echo encrypt($row['user_code']); ?>"><i class="glyphicon glyphicon-eye-open icon-white"></i> </a></td>
                                    </tr>
                                    <?php $count++; } } else { echo "<tr><td colspan='3' class='text-danger'><em>No results to display</em></td></tr>"; } ?>

                                    </tbody>
                                </table>
                            </div>
                        </article>

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