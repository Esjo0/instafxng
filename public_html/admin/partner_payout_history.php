<?php
require_once("../init/initialize_admin.php");
if (!$session_admin->is_logged_in()) {
    redirect_to("login.php");
}

if(isset($_POST['search_text']) && strlen($_POST['search_text']) > 3) {
    $search_text = $_POST['search_text'];
    $query = "SELECT us.first_name,us.middle_name,us.last_name,us.email,us.phone,par.partner_code,parpay.account_id,parpay.amount,parpay.comment FROM user us,partner_payment parpay WHERE us.user_code=parpay.user_code AND (CONCAT_WS(' ', us.first_name, us.last_name) LIKE '%$search_text%' OR CONCAT_WS(' ', us.first_name, us.middle_name, us.last_name) LIKE '%$search_text%' OR us.email LIKE '%$search_text%' OR us.phone LIKE '%$search_text%' OR parpay.partner_code LIKE '%$search_text%'  OR us.first_name LIKE '%$search_text%' OR us.last_name LIKE '%$search_text%') ORDER BY parpay.partner_pay_id DESC ";
} else {
    $query = "SELECT us.first_name,us.middle_name,us.last_name,us.email,us.phone,par.partner_code,parpay.account_id,parpay.amount,parpay.comment FROM user us,partner_payment parpay WHERE us.user_code=parpay.user_code ORDER BY parpay.partner_pay_id DESC ";
}
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
$all_partner_payout = $db_handle->fetchAssoc($result);

?>
<!DOCTYPE html>
<html lang="en">
    <head>
        <base target="_self">
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>Instaforex Nigeria | Admin - Partner Payout History</title>
        <meta name="title" content="Instaforex Nigeria | Admin - Partner Payout History" />
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
                            <h4><strong>PARTNER PAYOUT HISTORY</strong></h4>
                        </div>
                    </div>
                    
                    <div class="section-tint super-shadow">
                        <div class="row">
                            <div class="col-sm-12">
                                <?php require_once 'layouts/feedback_message.php'; ?>

                                <table class="table table-responsive table-striped table-bordered table-hover">
                                    <thead>
                                        <tr>
                                            <th>Full Name</th>
                                            <th>Account No</th>
                                            <th>Partner Code</th>
                                            <th>Amount</th>
                                            <th>Trans Type</th>
                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                    <?php if(isset($all_partner_payout) && !empty($all_partner_payout)) { foreach ($all_partner_payout as $row) { ?>
                                        <tr>
                                            <td><?php echo $row['first_name'].' '.$row['middle_name'].' '.$row['last_name']; ?></td>
                                            <td><?php echo $row['ifxaccount_id']; ?></td>
                                            <td><?php echo $row['partner_code']; ?></td>
                                            <td><?php echo $row['amount']; ?></td>
                                            <td><?php echo $row['trans_type']; ?></td>
                                            <td><a class="btn btn-success" href="partner_payout_history_details.php?action=view&id=<?php echo $row['partner_pay_id']; ?>"><i class="glyphicon glyphicon-zoom-in icon-white"></i> View</a></td>
                                        </tr>
                                    <?php } } else { echo "<tr><td colspan='5' class='text-danger'><em>No results to display</em></td></tr>"; } ?>
                                    </tbody>
                                </table>

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