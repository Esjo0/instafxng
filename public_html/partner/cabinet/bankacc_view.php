<?php
require_once '../../init/initialize_partner.php';
require_once '../../init/initialize_general.php';
if (!$session_partner->is_logged_in()) {
    redirect_to("../login.php");
}

$user_code = $_SESSION['partner_user_code'];

$query = "SELECT * FROM user_bank WHERE user_code = '$user_code'";

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
$selected_bank_account_details = $db_handle->fetchAssoc($result);

if(isset($_GET['msg']) && $_GET['msg'] == 1) {
    $message_success = "Bank account details was successfully added";
}

?>
<!DOCTYPE html>
<html lang="en">
    <head>
        <base target="_self">
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>Instafxng Partner | View Bank Accounts</title>
        <meta name="title" content="Instafxng Partner | View Bank Accounts" />
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
                            <h4><strong>BANK ACCOUNTS</strong></h4>
                        </div>
                    </div>
                    
                    <div class="section-tint super-shadow">
                        <div class="row">
                            <div class="col-sm-12">
                                <?php require_once 'layouts/feedback_message.php'; ?>

                                <div class="alert alert-info">
                                  Please <strong><a href="partner/cabinet/bankacct_add.php">click here</a></strong> to add a new bank account
                                </div>

                                

                                <p>Below is the list of all referalls</p>

                                <table class="table table-responsive table-striped table-bordered table-hover">
                                    <thead>
                                        <tr>
                                            <th>Account Name</th>
                                            <th>Account No</th>
                                            <th>Bank name</th>
                                            <th>Withdraw from account</th>
                                        </tr>

                                        <tbody>
                                        <?php if(isset($selected_bank_account_details) && !empty($selected_bank_account_details)) {  
                                            for($i = 0; $i < count($selected_bank_account_details); $i++) { 
                                        ?> 
                                        <tr>
                                            <td><?php echo $selected_bank_account_details[$i]['bank_acct_name']; ?></td>
                                            <td><?php echo $selected_bank_account_details[$i]['bank_acct_no']; ?></td>
                                            <td><?php echo $system_object->get_bank_by_bank_id($selected_bank_account_details[$i]['bank_id']); ?></td>
                                            <td><a href="partner/cabinet/bankacct_withdraw.php?acc=<?php echo $selected_bank_account_details[$i]['bank_acct_no']; ?>">withdraw</a></td>     
                                        </tr>
                                        <?php } } else { echo "<tr><td colspan='7' class='text-danger'><em>No results to display</em></td></tr>"; } ?>
                                        </tbody>
                                    </thead>
                                </table>

                                <?php if(isset($selected_bank_account_details) && !empty($selected_bank_account_details)) { ?>
                                <div class="tool-footer text-right">
                                    <p class="pull-left">Showing <?php echo $prespagelow . " to " . $prespagehigh . " of " . $numrows; ?> entries</p>
                                </div>
                                <?php } ?>

                            </div>
                        </div>

                         <?php if(isset($selected_bank_account_details) && !empty($selected_bank_account_details)) {  require_once 'layouts/pagination_links.php'; } ?>

                    </div>

                    <!-- Unique Page Content Ends Here
                    ================================================== -->
                    
                </div>
                
            </div>
        </div>
        <?php require_once 'layouts/footer.php'; ?>
    </body>
</html>