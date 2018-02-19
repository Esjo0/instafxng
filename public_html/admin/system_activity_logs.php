<?php
require_once("../init/initialize_admin.php");
if (!$session_admin->is_logged_in()) {
    redirect_to("login.php");
}

$query = "SELECT * FROM admin ";

$numrows = $db_handle->numRows($query);

$rowsperpage = 20;

$totalpages = ceil($numrows / $rowsperpage);
// get the current page or set a default
if (isset($_GET['pg']) && is_numeric($_GET['pg'])) {    $currentpage = (int)$_GET['pg'];} else {    $currentpage = 1;}
if ($currentpage > $totalpages) {    $currentpage = $totalpages;}
if ($currentpage < 1) {    $currentpage = 1;}
$prespagelow = $currentpage * $rowsperpage - $rowsperpage + 1;
$prespagehigh = $currentpage * $rowsperpage;
if ($prespagehigh > $numrows) {    $prespagehigh = $numrows;}
$offset = ($currentpage - 1) * $rowsperpage;
$query .= 'LIMIT ' . $offset . ',' . $rowsperpage;
$result = $db_handle->runQuery($query);
$completed_deposit_requests_filter = $db_handle->fetchAssoc($result);

?>
<!DOCTYPE html>
<html lang="en">
    <head>
        <base target="_self">
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>Instaforex Nigeria | Admin - Setting Rates</title>
        <meta name="title" content="Instaforex Nigeria | Admin - View Admin Members" />
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
                            <h4><strong>SYSTEM ACTIVITY LOGS</strong></h4>
                        </div>
                    </div>
                    <div class="section-tint super-shadow">
                        <div class="row">
                            <div class="col-sm-12">
                                <?php require_once 'layouts/feedback_message.php'; ?>
                                <table id="outputTable" class="table table-responsive table-striped table-bordered table-hover">
                                    <thead>
                                    <tr>
                                        <th>Name</th>
                                        <th>Action</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    <?php
                                    if(isset($completed_deposit_requests_filter) && !empty($completed_deposit_requests_filter)) {
                                        foreach ($completed_deposit_requests_filter as $row) {
                                            ?>
                                            <tr>
                                                <td>
                                                    <?php echo $row['last_name'] . ' ' . $row['middle_name'] . ' ' . $row['first_name']; ?>
                                                </td>
                                                <td>
                                                    <button type="button" data-target="#log_<?php echo $row['admin_code'];?>" data-toggle="modal" class="btn btn-sm btn-default">View</button>
                                                    <!--Modal - confirmation boxes-->
                                                    <div id="log_<?php echo $row['admin_code'];?>" tabindex="-1" role="dialog" aria-hidden="true" class="modal fade">
                                                        <div class="modal-dialog modal-lg">
                                                            <div class="modal-content">
                                                                <div class="modal-header">
                                                                    <button type="button" data-dismiss="modal" aria-hidden="true"  class="close">&times;</button>
                                                                    <h4 class="modal-title"><?php echo $row['last_name'] . ' ' . $row['middle_name'] . ' ' . $row['first_name']; ?>'s Activity Log</h4></div>
                                                                <div class="modal-body">
                                                                    <textarea rows="20" class="form-control" disabled><?php echo file_get_contents("logs".DIRECTORY_SEPARATOR.$row['admin_code'].".txt"); ?></textarea>
                                                                </div>
                                                                <div class="modal-footer">
                                                                    <button type="button" name="close" onClick="window.close();" data-dismiss="modal" class="btn btn-danger">Close!</button>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </td>
                                            </tr>
                                        <?php } } else { echo "<tr><td colspan='3' class='text-danger'><em>No results to display</em></td></tr>"; } ?>
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