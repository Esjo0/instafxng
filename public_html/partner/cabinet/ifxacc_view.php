<?php
require_once '../../init/initialize_partner.php';
if (!$session_partner->is_logged_in()) {
//    redirect_to("../login.php");
}

$user_code = $_SESSION['partner_user_code'];

$query = "SELECT * FROM user_ifxaccount WHERE user_code = '$user_code' ";

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
$selected_ifx_accounts = $db_handle->fetchAssoc($result);

?>
<!DOCTYPE html>
<html lang="en">
    <head>
        <base target="_self">
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>Instafxng Partner | View IFX Account</title>
        <meta name="title" content="Instafxng Partner | View IFX Account" />
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
                            <h4><strong>IFX ACCOUNTS</strong></h4>
                        </div>
                    </div>
                    
                    <div class="section-tint super-shadow">
                        <div class="row">

                            <div class="col-sm-12">
                                <div class="alert alert-info">
                                  Please <strong><a href="https://instafxng.com/live_account.php" target="_blank">click here</a></strong> to open a new insta forex account
                                </div>

                                <p>Below is the list of all referalls</p>

                                <table class="table table-responsive table-striped table-bordered table-hover">
                                    <thead>
                                        <tr>
                                            <th>Account No</th>
                                            <th>Widthraw from account</th>
                                        </tr>

                                        <tbody>
                                        <?php if(isset($selected_ifx_accounts) && !empty($selected_ifx_accounts)) {  for($i = 0; $i < count($selected_ifx_accounts); $i++) { ?> 
                                        <tr>
                                            <td><?php echo $selected_ifx_accounts[$i]['ifx_acct_no']; ?></td>
                                            <td><a href="partner/cabinet/ifxacct_withdraw.php?acc=<?php echo $selected_ifx_accounts[$i]['ifx_acct_no']; ?>">withdraw</a></td>     
                                        </tr>
                                        <?php } } else { echo "<tr><td colspan='7' class='text-danger'><em>No results to display</em></td></tr>"; } ?>
                                        </tbody>
                                    </thead>
                                </table>

                                <?php if(isset($selected_ifx_accounts) && !empty($selected_ifx_accounts)) { ?>
                                <div class="tool-footer text-right">
                                    <p class="pull-left">Showing <?php echo $prespagelow . " to " . $prespagehigh . " of " . $numrows; ?> entries</p>
                                </div>
                                <?php } ?>

                            </div>
                        </div>

                         <?php if(isset($selected_ifx_accounts) && !empty($selected_ifx_accounts)) {  require_once 'layouts/pagination_links.php'; } ?>

                    </div>

                    <!-- Unique Page Content Ends Here
                    ================================================== -->
                    
                </div>
                
            </div>
        </div>
        <?php require_once 'layouts/footer.php'; ?>
    </body>
</html>