<?php
require_once 'init/initialize_general.php';
$thisPage = "Promo";

////
$query = "SELECT CONCAT(u.last_name, SPACE(1), u.first_name) AS full_name, uv2.user_val_2017_id
          FROM user_val_2017 AS uv2
          INNER JOIN user AS u ON uv2.user_code = u.user_code
          ORDER BY uv2.created DESC ";
$numrows = $db_handle->numRows($query);

$rowsperpage = 50;

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
$val_contest_pages = $db_handle->fetchAssoc($result);

?>
<!DOCTYPE html>
<html lang="en">
    <head>
        <base target="_self">
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>Instaforex Nigeria | Special Valentine Offer</title>
        <meta name="title" content=" " />
        <meta name="keywords" content=" ">
        <meta name="description" content=" ">
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
                    
                    <div class="row">
                        <div class="col-sm-12 text-center">
                            <img src="images/happy_val_day.jpg" alt="Happy Valentine's Day" class="img-responsive" />
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-sm-12">

                            <div class="section-tint super-shadow">
                                <h3 class="text-center">Who's your valentine contest</h3>
                                <p>Below is the list of participants in the Who's Your Valentine Contest, visit their
                                page and make sure no one is leading you, share your page and get more likes.</p>

                                <p class="text-center"><a href="valentine_offer.php" class="btn btn-success" title="Join">Join Contest <i class="fa fa-arrow-circle-left"></i></a></p>

                                <?php if(isset($val_contest_pages) && !empty($val_contest_pages)) { ?>
                                    <div class="tool-footer text-right">
                                        <p class="pull-left">Showing <?php echo $prespagelow . " to " . $prespagehigh . " of " . $numrows; ?> entries</p>
                                    </div>
                                <?php } ?>

                                <table class="table table-responsive table-striped table-bordered table-hover">
                                    <thead>
                                    <tr>
                                        <th>Participant Name</th>
                                        <th>Valentine Page</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    <?php
                                    if(isset($val_contest_pages) && !empty($val_contest_pages)) {
                                        foreach ($val_contest_pages as $row) {
                                            ?>
                                            <tr>
                                                <td><?php echo $row['full_name']; ?></td>
                                                <td><a class="btn btn-info" href="https://instafxng.com/my_val/id/<?php echo $row['user_val_2017_id']; ?>/" target="_blank" title="Click to visit page"><i class="glyphicon glyphicon-eye-open icon-white"> View</i></a></td>
                                            </tr>
                                        <?php } } else { echo "<tr><td colspan='2' class='text-danger'><em>No results to display</em></td></tr>"; } ?>
                                    </tbody>
                                </table>

                                <?php if(isset($val_contest_pages) && !empty($val_contest_pages)) { ?>
                                    <div class="tool-footer text-right">
                                        <p class="pull-left">Showing <?php echo $prespagelow . " to " . $prespagehigh . " of " . $numrows; ?> entries</p>
                                    </div>
                                <?php } ?>

                                <?php if(isset($val_contest_pages) && !empty($val_contest_pages)) { require_once 'layouts/pagination_links.php'; } ?>

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