<?php

ini_set("xdebug.var_display_max_children", -1);
ini_set("xdebug.var_display_max_data", -1);
ini_set("xdebug.var_display_max_depth", -1);

require_once("../init/initialize_admin.php");
if (!$session_admin->is_logged_in()) 
{
    redirect_to("login.php");
}
$admin_code = $_SESSION['admin_unique_code'];
if(isset($_POST['approve']))
{
    $comment = $_POST['comment'];
    $req_order_code = $_POST['req_order_code'];
    $query = "UPDATE accounting_system_req_order SET comments = '$comment', status = '2', admin_code = '$admin_code' WHERE req_order_code = '$req_order_code' LIMIT 1";
    $result = $db_handle->runQuery($query);
    if($result)
    {
        $message_success = "Operation Successful";
    }
    else
    {
        $message_error = "Operation Failed";
    }
}

if(isset($_POST['approve_refund']))
{
    $req_order_code = $_POST['req_order_code'];
    $actual_spent = $_POST['actual_spent'];
    $refund_id = $_POST['refund_id'];
    $result1 = $db_handle->runQuery("UPDATE accounting_system_req_order SET req_order_total = '$actual_spent', admin_code = '$admin_code' WHERE req_order_code = '$req_order_code' LIMIT 1");
    $result2 = $db_handle->runQuery("DELETE FROM accounting_system_refunds WHERE refund_id = '$refund_id'");
    if($result1 && $result2)
    {
        $message_success = "Operation Successful";
    }
    else
    {
        $message_error = "Operation Failed";
    }
}

if(isset($_POST['decline']))
{

    $decline_comment = $_POST['decline_comment'];
    $req_order_code = $_POST['req_order_code'];
    $query = "UPDATE accounting_system_req_order SET comments = '$decline_comment', status = '3', admin_code = '$admin_code' WHERE req_order_code = '$req_order_code' LIMIT 1";
    $result = $db_handle->runQuery($query);
    if($result)
    {
        $message_success = "Operation Successful";
    }
    else
    {
        $message_error = "Operation Failed";
    }
}

$query = "SELECT 
          accounting_system_req_order.req_order_total,
          accounting_system_req_order.req_order_code, 
          accounting_system_req_order.req_order, 
          accounting_system_req_order.created, 
          accounting_system_req_order.status,
          accounting_system_office_locations.location,
          CONCAT(admin.first_name, SPACE(1), admin.last_name) AS author_name
          FROM admin, accounting_system_req_order, accounting_system_office_locations
          WHERE accounting_system_req_order.author_code = admin.admin_code
          AND accounting_system_req_order.location = accounting_system_office_locations.location_id
          AND accounting_system_req_order.status = '1' 
          ORDER BY accounting_system_req_order.created DESC ";


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
//var_dump($query);
$result = $db_handle->runQuery($query);
$projects = $db_handle->fetchAssoc($result);


$query = "SELECT 
          accounting_system_refunds.refund_id,
          accounting_system_refunds.actual_spent,
          accounting_system_req_order.req_order_total,
          accounting_system_req_order.req_order_code, 
          accounting_system_req_order.req_order, 
          accounting_system_refunds.created, 
          accounting_system_req_order.status,
          accounting_system_office_locations.location,
          CONCAT(admin.first_name, SPACE(1), admin.last_name) AS author_name
          FROM admin, accounting_system_req_order, accounting_system_office_locations,accounting_system_refunds
          WHERE accounting_system_req_order.author_code = admin.admin_code
          AND accounting_system_req_order.location = accounting_system_office_locations.location_id
          AND accounting_system_refunds.req_order_code = accounting_system_req_order.req_order_code
          ORDER BY accounting_system_refunds.created DESC ";
$result = $db_handle->runQuery($query);
$refunds = $db_handle->fetchAssoc($result);

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <base target="_self">
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Instaforex Nigeria | Admin - Accounting System</title>
    <meta name="title" content="Instaforex Nigeria | Admin - Accounting System " />
    <meta name="keywords" content="" />
    <meta name="description" content="" />
    <?php require_once 'layouts/head_meta.php'; ?>
    <script>
        function print_report(divName)
        {
            //window.alert(divName);
            var printContents = document.getElementById(divName).innerHTML;
            var originalContents = document.body.innerHTML;
            document.body.innerHTML = printContents;
            window.print();
            document.body.innerHTML = originalContents;
        }

        function show_chat(div)
        {
            var x = document.getElementById(div);
            if (x.style.display === 'none') {x.style.display = 'block';}
            else {x.style.display = 'none';}
        }
    </script>
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
                    <h4><strong>REQUISITION ORDER CONFIRMATION REQUESTS</strong></h4>
                </div>
            </div>
            <div class="section-tint super-shadow">
                <div class="row">
                    <div class="col-sm-12">
                        <?php require_once 'layouts/feedback_message.php'; ?>
                    </div>
                    <div class="col-sm-12">
                    </div>
                    <div class="col-sm-12">
                        <table class="table table-striped table-bordered table-hover">
                            <thead>
                            <tr>
                                <th>Name</th>
                                <th>Office Location</th>
                                <th>Order List</th>
                                <th>Order Total</th>
                                <th>Created </th>
                            </tr>
                            </thead>
                            <tbody>

                            <?php if(isset($projects) && !empty($projects))
                            {
                                //var_dump($projects);
                                foreach ($projects as $row)
                                { ?>
                                    <tr>
                                        <td><?php echo $row['author_name']; ?></td>
                                        <td><?php echo $row['location']; ?></td>
                                        <td>
                                            <button type="button" data-toggle="modal" data-target="#view_order<?php echo $row['req_order_code']; ?>" class="btn btn-default">View Order</button>
                                            <!-- Modal-- View Order List -->
                                            <div id="view_order<?php echo $row['req_order_code']; ?>" class="modal fade" role="dialog">
                                                <div class="modal-dialog">
                                                    <!-- Modal content-->
                                                    <form data-toggle="validator" class="form-horizontal" role="form" method="post" action="<?php echo $_SERVER['REQUEST_URI']; ?>">
                                                        <div class="modal-content">
                                                            <div class="modal-header">
                                                                <button type="button" class="close" data-dismiss="modal">&times;</button>
                                                                <h4 class="modal-title">REQUISITION ORDER</h4>
                                                            </div>
                                                            <div class="modal-body">
                                                                <p><b>NAME:</b> <?php echo $row['author_name']; ?></p>
                                                                <p><b>DATE:</b> <?php echo $row['created']; ?></p>

                                                                <?php echo $row['req_order'];?>
                                                                <b>Your Remark:</b>
                                                                <textarea placeholder="" name="comment" rows="3" class="form-control" required></textarea>
                                                            </div>
                                                            <div class="modal-footer">
                                                                <input type="hidden" name="req_order_code" value="<?php echo $row['req_order_code']; ?>">
                                                                <button name="approve" type="submit" class="btn btn-success">Approve</button>
                                                                <button name="decline" type="submit" class="btn btn-success">Decline</button>
                                                                <button name="print" onclick="print_report('printout<?php echo $row['req_order_code']; ?>')" type="button" class="btn btn-info">Print</button>
                                                                <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
                                                            </div>
                                                        </div>
                                                    </form>
                                                </div>
                                            </div>
                                        </td>
                                        <td>₦<?php echo number_format($row['req_order_total'], 2, ".", ","); ?></td>
                                        <td><?php echo datetime_to_text($row['created']); ?></td>
                                    </tr>
                                    <?php
                                }
                            }
                            else
                            { echo "<em>No results to display...</em>"; } ?>

                            </tbody>
                        </table>
                        <?php if(isset($projects) && !empty($projects)) { ?>
                            <div class="tool-footer text-right">
                                <p class="pull-left">Showing <?php echo $prespagelow . " to " . $prespagehigh . " of " . $numrows; ?> entries</p>
                            </div>
                        <?php } ?>
                    </div>
                <?php if(isset($projects) && !empty($projects)) { require_once 'layouts/pagination_links.php'; } ?>
            </div>
            </div>

            <?php if(isset($refunds) && !empty($refunds))
            {?>
            <div class="row">
                <div class="col-sm-12 text-danger">
                    <h4><strong>REFUND CONFIRMATION REQUESTS</strong></h4>
                </div>
            </div>
            <div class="section-tint super-shadow">
                <div class="row">
                    <div class="col-sm-12">
                        <?php require_once 'layouts/feedback_message.php'; ?>
                    </div>
                    <div class="col-sm-12">
                        <table class="table table-striped table-bordered table-hover">
                            <thead>
                            <tr>
                                <th>Name</th>
                                <th>Office Location</th>
                                <th>Order List</th>
                                <th>Order Total</th>
                                <th>Actual Spent</th>
                                <th>Created </th>
                            </tr>
                            </thead>
                            <tbody>
                            <?php foreach ($refunds as $row)
                            { ?>
                                <tr>
                                    <td><?php echo $row['author_name']; ?></td>
                                    <td><?php echo $row['location']; ?></td>
                                    <td>
                                        <button type="button" data-toggle="modal" data-target="#view_order<?php echo $row['req_order_code']; ?>" class="btn btn-default">View Order</button>
                                        <!-- Modal-- View Order List -->
                                        <div id="view_order<?php echo $row['req_order_code']; ?>" class="modal fade" role="dialog">
                                            <div class="modal-dialog">
                                                <!-- Modal content-->
                                                <form data-toggle="validator" class="form-horizontal" role="form" method="post" action="<?php echo $_SERVER['REQUEST_URI']; ?>">
                                                    <div class="modal-content">
                                                        <div class="modal-header">
                                                            <button type="button" class="close" data-dismiss="modal">&times;</button>
                                                            <h4 class="modal-title">REQUISITION ORDER</h4>
                                                        </div>
                                                        <div class="modal-body">
                                                            <p><b>NAME:</b> <?php echo $row['author_name']; ?></p>
                                                            <p><b>DATE:</b> <?php echo $row['created']; ?></p>
                                                            <p><b>ACTUAL SPENT</b> ₦<?php echo number_format($row['actual_spent'], 2, ".", ","); ?></p>
                                                            <?php echo $row['req_order'];?>

                                                        </div>
                                                        <div class="modal-footer">
                                                            <input type="hidden" name="req_order_code" value="<?php echo $row['req_order_code']; ?>"/>
                                                            <input type="hidden" name="actual_spent" value="<?php echo $row['actual_spent']; ?>"/>
                                                            <input type="hidden" name="refund_id" value="<?php echo $row['refund_id']; ?>"/>
                                                            <button name="approve_refund" type="submit" class="btn btn-success">Approve Refund</button>
                                                            <button name="print" onclick="print_report('printout<?php echo $row['req_order_code']; ?>')" type="button" class="btn btn-info">Print</button>
                                                            <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
                                                        </div>
                                                    </div>
                                                </form>
                                            </div>
                                        </div>
                                    </td>
                                    <td>₦<?php echo number_format($row['req_order_total'], 2, ".", ","); ?></td>
                                    <td>₦<?php echo number_format($row['actual_spent'], 2, ".", ","); ?></td>
                                    <td><?php echo datetime_to_text($row['created']); ?></td>
                                </tr>
                                <?php
                            }?>
                            </tbody>
                            </table>

                    </div>
                </div>
            </div>
            <?php } ?>
            <!-- Unique Page Content Ends Here
            ================================================== -->
        </div>
    </div>
</div>
<?php require_once 'layouts/footer.php'; ?>
</body>
</html>
<script src="https://code.jquery.com/jquery-1.12.3.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/0.9.0rc1/jspdf.min.js"></script>