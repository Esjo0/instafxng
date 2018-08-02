<?php
require_once("../init/initialize_admin.php");
if (!$session_admin->is_logged_in()) {  redirect_to("login.php");}

if(isset($_POST['unlock'])){
    $trans_id = $db_handle->sanitizePost(trim($_POST['trans_id']));
    $query = "DELETE FROM active_transactions WHERE transaction_id = '$trans_id' ";
    $db_handle->runQuery($query) ? $message_success = "Transaction Unlocked Successfully" : $message_error = "Failed to unlock transaction. Please try again.";
}

$query = "SELECT AT.transaction_id, AT.admin_code, CONCAT(A.first_name, SPACE(1), A.last_name) AS full_name, AT.created FROM active_transactions AS AT 
INNER JOIN admin AS A ON AT.admin_code = A.admin_code ORDER BY AT.active_transaction_id DESC";
$numrows = $db_handle->numRows($query);

$rowsperpage = 20;

$totalpages = ceil($numrows / $rowsperpage);
// get the current page or set a default
if (isset($_GET['pg']) && is_numeric($_GET['pg'])) {$currentpage = (int) $_GET['pg'];} else {$currentpage = 1;}
if ($currentpage > $totalpages) { $currentpage = $totalpages; }
if ($currentpage < 1) { $currentpage = 1; }

$prespagelow = $currentpage * $rowsperpage - $rowsperpage + 1;
$prespagehigh = $currentpage * $rowsperpage;
if($prespagehigh > $numrows) { $prespagehigh = $numrows; }

$offset = ($currentpage - 1) * $rowsperpage;
$query .= ' LIMIT ' . $offset . ',' . $rowsperpage;
$result = $db_handle->runQuery($query);
$admin_members = $db_handle->fetchAssoc($result);

?>
<!DOCTYPE html>
<html lang="en">
    <head>
        <base target="_self">
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>Instaforex Nigeria | Admin - View Locked Transactions</title>
        <meta name="title" content="Instaforex Nigeria | Admin - View Locked Transactions" />
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
                            <h4><strong>VIEW LOCKED TRANSACTIONS</strong></h4>
                        </div>
                    </div>
                    
                    <div class="section-tint super-shadow">
                        <div class="row">
                            <div class="col-sm-12">
                                <?php require_once 'layouts/feedback_message.php'; ?>
                                <p>The table below lists all transactions that are currently tied to an admin personnel.</p>
                                
                                <table class="table table-responsive table-striped table-bordered table-hover">
                                    <thead>
                                        <tr>
                                            <th>Transaction ID</th>
                                            <th>Admin Name</th>
                                            <th>Locked Since</th>
                                            <th></th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php if(isset($admin_members) && !empty($admin_members)) { foreach ($admin_members as $row) { ?>
                                        <tr>
                                            <td><?php echo $row['transaction_id']; ?></td>
                                            <td><?php echo $row['full_name']; ?></td>
                                            <td><?php echo datetime_to_text($row['created']) ?></td>
                                            <td>
                                                <form role="form" method="post" action="">
                                                    <input type="submit" title="Unlock this transaction" name="unlock" value="Unlock" class="btn btn-sm btn-info" />
                                                    <input value="<?php echo $row['transaction_id']; ?>" type="hidden" name="trans_id">
                                                </form>
                                            </td>
                                        </tr>
                                        <?php } } else { echo "<tr><td colspan='4' class='text-danger'><em>No results to display</em></td></tr>"; } ?>
                                    </tbody>
                                </table>
                                
                                <?php if(isset($admin_members) && !empty($admin_members)) { ?>
                                <div class="tool-footer text-right">
                                    <p class="pull-left">Showing <?php echo $prespagelow . " to " . $prespagehigh . " of " . $numrows; ?> entries</p>
                                </div>
                                <?php } ?>
                            </div>
                        </div>
                        <?php if(isset($admin_members) && !empty($admin_members)) { require_once 'layouts/pagination_links.php'; } ?>

                    </div>

                    <!-- Unique Page Content Ends Here
                    ================================================== -->
                    
                </div>
                
            </div>
        </div>
        <?php require_once 'layouts/footer.php'; ?>
    </body>
</html>