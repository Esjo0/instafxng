<?php
ob_start();
require_once('includes/inc_functions.php');

if(!isset($_SESSION)) { session_start(); }
include_once('../conn.php');
include_once('../Connections/functions.php');
include('../atimeout.php');
if(!isset($_SESSION['adminname'])) {
    header("Location: adminlogin.php");
}

$query = "SELECT * FROM deposit_attempt GROUP BY email ORDER BY created DESC ";

$numrows = $db_handle->numRows($query);

// For search, make rows per page equal total rows found, meaning, no pagination
// for search results
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
$deposit_attempt = $db_handle->fetchAssoc($result);

function calculate_attempt($email) {
    global $db_handle;
    $query = "SELECT * FROM deposit_attempt WHERE email = '$email'";
    return $db_handle->numRows($query);
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <title>DEPOSIT - ATTEMPTS</title>
    <?php include_once('includes/inc_head_tag.php'); ?>
</head>
<body>
<!--BEGIN TEMPLATE SETTING-->
<div>
    <!--BEGIN TO TOP-->
    <a id="totop" href="#"><i class="fa fa-angle-up"></i></a><!--END BACK TO TOP-->
    <div id="wrapper">
        <?php include_once('includes/inc_header.php');?>
        <?php include_once('includes/inc_nav.php');?>
        <!--BEGIN PAGE WRAPPER-->
        <div id="page-wrapper">
            <?php require_once 'layouts/topnav.php'; ?>
            <!--BEGIN PAGE HEADER & BREADCRUMB-->
            <div class="page-header-breadcrumb">
                <div class="page-heading hidden-xs"><h1 class="page-title">TRANSACTIONS</h1></div>
                <ol class="breadcrumb page-breadcrumb">
                    <li><i class="fa fa-home"></i>&nbsp;<a href="index.php">Home</a>&nbsp;&nbsp;<i class="fa fa-angle-right"></i>&nbsp;&nbsp;</li>
                    <li><a href="#">Deposit</a>&nbsp;&nbsp;<i class="fa fa-angle-right"></i>&nbsp;&nbsp;</li>
                    <li class="active">Deposit - View Attempted Requests</li>
                </ol>
            </div>
            <!--END PAGE HEADER & BREADCRUMB-->

            <!--BEGIN CONTENT-->
            <div class="page-content">
                <div id="table-advanced" class="row">
                    <div class="col-lg-12">
                        <div class="portlet">
                            <div class="portlet-header">
                                <div class="caption">VIEW ATTEMPTED DEPOSITS</div>
                                <div class="tools">
                                    <i class="fa fa-chevron-up"></i>
                                    <i data-toggle="modal" data-target="#modal-config" class="fa fa-cog"></i>
                                    <i class="fa fa-refresh"></i>
                                    <i class="fa fa-times"></i>
                                </div>
                            </div>
                            <div class="portlet-body">
                                

                                <div class="table-responsive mtl">
                                    <p>Below is the list of all deposit attempts.</p>
                                    <table class="table table-striped table-bordered table-hover">
                                        <thead>
                                            <tr>
                                                <th>Name</th>
                                                <th>Email</th>
                                                <th>Phone</th>
                                                <th>Account No</th>
                                                <th>Attempts</th>
                                                <th>Created</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php foreach ($deposit_attempt as $key => $row) { ?>
                                            <tr>
                                                <td><?php echo $row['name']; ?></td>
                                                <td><?php echo $row['email']; ?></td>
                                                <td><?php echo $row['phone']; ?></td>
                                                <td><?php echo $row['account']; ?></td>
                                                <td><?php echo calculate_attempt($row['email']); ?></td>
                                                <td><?php echo $row['created']; ?></td>
                                            </tr>
                                            <?php } ?>
                                        </tbody>
                                    </table>
                                </div>
                                <div class="tool-footer text-right">
                                    <p class="pull-left">Showing <?php echo $prespagelow . " to " . $prespagehigh . " of " . $numrows; ?> entries</p>
                                </div>
                                <?php require_once('includes/inc_pagination_links.php'); ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div><!--END CONTENT-->
            <?php include_once('includes/inc_footer.php'); ?>
        </div><!--END PAGE WRAPPER-->
    </div><!--END WRAPPER-->
</div>
<?php include_once('includes/inc_js_resources.php'); ?>
</body>
</html>