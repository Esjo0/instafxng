<?php
require_once("../init/initialize_admin.php");
if (!$session_admin->is_logged_in()) {
    redirect_to("login.php");
}

$query = "SELECT ub.user_bank_id, ub.bank_acct_name, ub.bank_acct_no, ub.created, b.bank_name,
        CONCAT(u.last_name, SPACE(1), u.first_name) AS full_name
        FROM user_bank AS ub
        INNER JOIN bank AS b ON ub.bank_id = b.bank_id
        INNER JOIN user AS u ON ub.user_code = u.user_code
        WHERE ub.status = '1' GROUP BY ub.user_code
        ORDER BY ub.created DESC ";
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
$new_bank_accts = $db_handle->fetchAssoc($result);

?>
<!DOCTYPE html>
<html lang="en">
    <head>
        <base target="_self">
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>Instaforex Nigeria | Admin - Bank Account Verification</title>
        <meta name="title" content="Instaforex Nigeria | Admin - Bank Account Verification" />
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
                            <h4><strong>BANK ACCOUNT VERIFICATION</strong></h4>
                        </div>
                    </div>
                    
                    <div class="section-tint super-shadow">
                        <div class="row">
                            <div class="col-sm-12">
                                <?php require_once 'layouts/feedback_message.php'; ?>
                                
                                <p>Below is a table of clients that have uploaded bank account details for verification.</p>
                                <table class="table table-striped table-bordered table-hover">
                                    <thead>
                                        <tr>
                                            <th>Full Name</th>
                                            <th>Account No</th>
                                            <th>Bank</th>
                                            <th>Created</th>
                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php 
                                            if(isset($new_bank_accts) && !empty($new_bank_accts)) { foreach ($new_bank_accts as $row) { 
                                        ?>
                                        <tr>
                                            <td><?php echo $row['full_name']; ?></td>
                                            <td><?php echo $row['bank_acct_no']; ?></td>
                                            <td><?php echo $row['bank_name']; ?></td>
                                            <td><?php echo datetime_to_text($row['created']); ?></td>
                                            <td>
                                                <a title="Process" class="btn btn-info" href="client_bank_verify_process.php?x=edit&id=<?php echo encrypt($row['user_bank_id']); ?>"><i class="glyphicon glyphicon-edit icon-white"></i> </a>
                                            </td>
                                        </tr>
                                        <?php } } else { echo "<tr><td colspan='5' class='text-danger'><em>No results to display</em></td></tr>"; } ?>
                                    </tbody>
                                </table>
                                
                                <?php if(isset($new_bank_accts) && !empty($new_bank_accts)) { ?>
                                <div class="tool-footer text-right">
                                    <p class="pull-left">Showing <?php echo $prespagelow . " to " . $prespagehigh . " of " . $numrows; ?> entries</p>
                                </div>
                                <?php } ?>
                            </div>
                        </div>
                        
                        <?php if(isset($new_bank_accts) && !empty($new_bank_accts)) { require_once 'layouts/pagination_links.php'; } ?>
                    </div>

                    <!-- Unique Page Content Ends Here
                    ================================================== -->
                    
                </div>
                
            </div>
        </div>
        <?php require_once 'layouts/footer.php'; ?>
    </body>
</html>