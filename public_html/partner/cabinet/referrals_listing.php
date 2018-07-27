<?php
require_once '../../init/initialize_partner.php';
if (!$session_partner->is_logged_in()) {
    redirect_to("../login.php");
}

$partner_details = $_SESSION['partner_details'];

$user_code = "BBLR";// $partner_details['partner_code'];

$query = "SELECT * FROM user_ifxaccount INNER JOIN user USING(user_code) WHERE user_ifxaccount.partner_code = '$user_code' ";
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
    $all_referrals = $db_handle->fetchAssoc($result);

//print_r($result[0]);


?>
<!DOCTYPE html>
<html lang="en">
    <head>
        <base target="_self">
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>Instafxng Partner | Referral Listing</title>
        <meta name="title" content="Instafxng Partner | Referral Listing" />
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
                            <h4><strong>REFERRALS</strong></h4>
                        </div>
                    </div>
                    
                    <div class="section-tint super-shadow">
                        <div class="row">
                            <div class="col-sm-12">

                                <div>

                                    <form method="get" action="cabinet/partner/filter.php" enctype="multipart/form-data" style="display: inline-block;">

                                        <label for="filter-by">Filter By</label>
                                        <select name="ft" title="Filter Peopled you Have Referred" id="filter-by">
                                            <optgroup label="Financial Activity">
                                                <option value="fc">Financial Commissions</option>
                                                <option value="dc">Deposit Commissions</option>
                                                <option value="wc">Withdrawal Commissions</option>

                                            </optgroup>
                                            <optgroup label="Trading Activity">
                                                <option value="tc">Trading Commissions</option>
                                            </optgroup>
                                        </select>
                                        <input type="submit" value="Filter" />

                                    </form>
                                    &nbsp;&nbsp;
                                    <form action="cabinet/partner/search.php" method="get" enctype="multipart/form-data" style="display: inline-block;">
                                        <span>Search</span>
                                        <input type="text" name="q" placeholder="e.g Name, Email or Phone" />
                                        <input type="submit" value="Search" />
                                    </form>

                                </div>

                                <p>Below is the list of all referrals


                                </p>

                                <table class="table table-responsive table-striped table-bordered table-hover">
                                    <thead>
                                        <tr>
                                            <th>Client Name</th>
                                            <th>Email</th>
                                            <th>Phone</th>
                                        </tr>

                                        <tbody>
                                        <?php if(isset($all_referrals) && !empty($all_referrals)) {  for($i = 0; $i < count($all_referrals); $i++) { ?>
                                        <tr>
                                            <td><?php echo $all_referrals[$i]['first_name'] . '  ' . $all_referrals[$i]['middle_name'] . '  ' . $all_referrals[$i]['last_name']; ?></td>
                                            <td><?php echo $all_referrals[$i]['email']; ?></td>
                                            <td><?php echo $all_referrals[$i]['phone']; ?></td>
                                        </tr>
                                        <?php } } else { echo "<tr><td colspan='7' class='text-danger'><em>No results to display</em></td></tr>"; } ?>
                                        </tbody>
                                    </thead>
                                </table>

                                <?php if(isset($all_referrals) && !empty($all_referrals)) { ?>
                                <div class="tool-footer text-right">
                                    <p class="pull-left">Showing <?php echo $prespagelow . " to " . $prespagehigh . " of " . $numrows; ?> entries</p>
                                </div>
                                <?php } ?>

                            </div>
                        </div>

                         <?php if(isset($all_referrals) && !empty($all_referrals)) {  require_once 'layouts/pagination_links.php'; } ?>

                    </div>

                    <!-- Unique Page Content Ends Here
                    ================================================== -->
                    
                </div>
                
            </div>
        </div>
        <?php require_once 'layouts/footer.php'; ?>
    </body>
</html>