<?php
require_once 'init/initialize_general.php';
$thisPage = "Promotion";

// Select all the archived months
$query = "SELECT start_date FROM point_ranking_log WHERE type = '1' GROUP BY start_date DESC";
$result = $db_handle->runQuery($query);
$selected_months = $db_handle->fetchAssoc($result);

?>
<!DOCTYPE html>
<html lang="en">
    <head>
        <base target="_self">
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>Instaforex Nigeria | Point Based Loyalty Reward</title>
        <meta name="title" content="Instaforex Nigeria | Point Based Loyalty Reward" />
        <meta name="keywords" content="" />
        <meta name="description" content="" />
        <?php require_once 'layouts/head_meta.php'; ?>
    </head>
    <body>
        <?php require_once 'layouts/header.php'; ?>
        <!-- Main Body: The is the main content area of the web site, contains a side bar  -->
        <div id="main-body" class="container-fluid">
            <div class="row no-gutter">
                <?php require_once 'layouts/topnav.php'; ?>
                <!-- Main Body - Content Area: This is the main content area, unique for each page  -->
                <div id="main-body-content-area" class="col-md-8 col-md-push-4 col-lg-9 col-lg-push-3">
                    
                    <!-- Unique Page Content Starts Here
                    ================================================== -->
                    <div class="super-shadow page-top-section">
                        <div class="row ">
                            <div class="col-sm-6">
                                <h4>Instafxng Point Based Loyalty Program and Reward</h4>
                                <p>More rewards for choosing Instaforex. Gadgets, Brand new cars, all-expense paid vacations,
                                    Instaforex credits up for grab for our loyal customers.</p>
                            </div>

                            <div class="col-sm-6">
                                <img src="images/point-based-rewards.jpg" alt="" class="img-responsive" />
                            </div>
                        </div>
                    </div>
                    
                    <div class="section-tint super-shadow">
                        <div class="row">
                            <div class="col-sm-12">
                                <p><a href="loyalty.php" class="btn btn-success" title=""><i class="fa fa-arrow-circle-left"></i> Loyalty Rank For Current Month</a></p>
                                <h5>View Monthly Loyalty Ranks Archived</h5>
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
                                                            <th>First Name</th>
                                                            <th>Rank Value</th>
                                                        </tr>
                                                        </thead>
                                                        <tbody>

                                                        <?php
                                                        $query = "SELECT prl.position, prl.point_earned, u.last_name, u.first_name AS full_name
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
                                                                    <td><?php if($row['full_name'] == 'Management') { echo $row['last_name']; } else { echo $row['full_name']; }; ?></td>
                                                                    <td><?php echo $row['point_earned']; ?></td>
                                                                </tr>
                                                                <?php } } else { echo "<tr><td colspan='3' class='text-danger'><em>No results to display</em></td></tr>"; } ?>
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
                <!-- Main Body - Side Bar  -->
                <div id="main-body-side-bar" class="col-md-4 col-md-pull-8 col-lg-3 col-lg-pull-9 left-nav">
                <?php require_once 'layouts/sidebar.php'; ?>
                </div>
            </div>
        </div>
        <?php require_once 'layouts/footer.php'; ?>
    </body>
</html>