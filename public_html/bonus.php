<?php
require_once 'init/initialize_general.php';
$thisPage = "Promotion";
$bonus_operations = new Bonus_Operations();
//$all_bonus_packages = $bonus_operations->get_active_packages();

$query = "SELECT bonus_title, bonus_img, bonus_desc, bonus_code FROM bonus_packages WHERE status = '2' ORDER BY created DESC ";
$numrows = $db_handle->numRows($query);
$rowsperpage = 10;
$totalpages = ceil($numrows / $rowsperpage);
if (isset($_GET['pg']) && is_numeric($_GET['pg'])) {$currentpage = (int) $_GET['pg'];
} else {$currentpage = 1;}
if ($currentpage > $totalpages) { $currentpage = $totalpages; }
if ($currentpage < 1) { $currentpage = 1; }
$prespagelow = $currentpage * $rowsperpage - $rowsperpage + 1;
$prespagehigh = $currentpage * $rowsperpage;
if($prespagehigh > $numrows) { $prespagehigh = $numrows; }
$offset = ($currentpage - 1) * $rowsperpage;
$query .= ' LIMIT ' . $offset . ',' . $rowsperpage;
$result = $db_handle->runQuery($query);
$all_bonus_packages = $db_handle->fetchAssoc($result);
?>
<!DOCTYPE html>
<html lang="en">
    <head>
        <base target="_self">
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>Instaforex Nigeria | InstaFxNg Bonuses</title>
        <meta name="title" content="" />
        <meta name="keywords" content="instaforex, promotions of instaforex, gifts for forex traders, contest and promotions" />
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
                            <div class="col-sm-12">
                                <h3 class="text-center"><strong>Bonus and Promotion Offers from InstaFxNg</strong></h3>
                                <p>InstaFxNg offers great opportunities and bonuses for Forex Traders in addition to the best trading conditions.
                                    We invite you to delight yourself by participating in any of our promotions and get a bonus also.</p>
                            </div>
                        </div>
                    </div>

                    <?php if (!empty($all_bonus_packages)){ ?>
                    <?php foreach ($all_bonus_packages as $row){ extract($row); ?>
                            <div class="section-tint super-shadow">
                                <div class="row">
                                    <div class="col-sm-12"><h5><?php echo $bonus_title; ?></h5></div>
                                </div>
                                <div class="row">
                                    <div class="col-sm-4">
                                        <a href="bonus_view.php?bc=<?php echo encrypt($bonus_code); ?>" title="click for full details" target="_blank">
                                            <img src="images/bonus_packages/<?php echo $bonus_img; ?>" alt="" class="img-responsive" />
                                        </a>
                                    </div>
                                    <div class="col-sm-8">
                                        <p><?php echo $bonus_desc; ?></p>
                                        <a class="btn btn-success" href="bonus_view.php?bc=<?php echo encrypt($bonus_code); ?>" title="click for full details" target="_blank">More Details</a>
                                    </div>
                                </div>
                            </div>
                        <?php } ?>
                    <?php } ?>

                    <?php if(isset($all_bonus_packages) && !empty($all_bonus_packages)) { ?>
                    <div class="section-tint super-shadow">
                        <div class="row">
                            <div class="col-sm-12">
                                <div class="tool-footer text-right">
                                    <p class="pull-left">Showing <?php echo $prespagelow . " to " . $prespagehigh . " of " . $numrows; ?> entries</p>
                                </div>
                            </div>
                        </div>
                    </div>
                    <?php } ?>



                    <?php if(isset($all_bonus_packages) && !empty($all_bonus_packages)) { require_once 'layouts/pagination_links.php'; } ?>

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