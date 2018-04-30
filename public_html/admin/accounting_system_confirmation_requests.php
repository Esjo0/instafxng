<?php
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
    foreach($_POST as $row)
    {
        if(is_array($row) && $row['app_item'] == 2)
        {
            extract($row);
            $query = "UPDATE accounting_system_req_item SET app_no_of_items = '$app_no_of_items', 
                      app_unit_cost = '$app_unit_cost', app_total_cost = '$app_total_cost', item_app = '$app_item' 
                      WHERE item_id = '$item_id' AND order_code = '$req_order_code'";
            $db_handle->runQuery($query);
        }
    }
    $query = "SELECT * FROM accounting_system_req_item WHERE order_code = '$req_order_code' AND item_app = '1' ";
    $list = $db_handle->fetchAssoc($db_handle->runQuery($query));
    foreach($list as $row)
    {
        extract($row);
        $query = "UPDATE accounting_system_req_item SET app_no_of_items = '0', app_unit_cost = '0', app_total_cost = '0', item_app = '0' WHERE item_id = '".$row['item_id']."' AND order_code = '$req_order_code'";
        $db_handle->runQuery($query);
    }
    $query = "SELECT SUM(app_total_cost) AS total_cost FROM accounting_system_req_item WHERE order_code = '$req_order_code' AND item_app = '2' ";
    $order_total = $db_handle->fetchAssoc($db_handle->runQuery($query))[0]['total_cost'];
    $query = "UPDATE accounting_system_req_order SET comments = '$comment', status = '2', admin_code = '$admin_code', req_order_total= '$order_total' WHERE req_order_code = '$req_order_code' LIMIT 1";
    $result = $db_handle->runQuery($query);
    $result ? $message_success = "Operation Successful" : $message_error = "Operation Failed" ;
}

if(isset($_POST['approve_refund']))
{
    $req_order_code = $_POST['req_order_code'];
    foreach($_POST as $row)
    {
        if(is_array($row))
        {
            extract($row);
            $query = "SELECT spent FROM accounting_system_refunds WHERE req_order_code = '$req_order_code' AND item_id = '$item_id'";
            $spent = $db_handle->fetchAssoc($db_handle->runQuery($query))[0]['spent'];
            $query = "UPDATE accounting_system_req_item SET app_total_cost = '$spent' WHERE item_id = '$item_id' AND order_code = '$req_order_code' ";
            $db_handle->runQuery($query);
            $query = "DELETE FROM accounting_system_refunds WHERE refund_id = '$refund_id' ";
            $db_handle->runQuery($query);
        }
    }
    $query = "SELECT SUM(app_total_cost) AS total_cost FROM accounting_system_req_item WHERE order_code = '$req_order_code' AND item_app = '2' ";
    $order_total = $db_handle->fetchAssoc($db_handle->runQuery($query))[0]['total_cost'];
    $query = "UPDATE accounting_system_req_order SET req_order_total= '$order_total' WHERE req_order_code = '$req_order_code' LIMIT 1";
    $result = $db_handle->runQuery($query);
    $result ? $message_success = "Operation Successful" : $message_error = "Operation Failed" ;
}

if(isset($_POST['decline']))
{
    $comment = $_POST['comment'];
    $req_order_code = $_POST['req_order_code'];
    $query = "SELECT * FROM accounting_system_req_item WHERE order_code = '$req_order_code' ";
    $list = $db_handle->fetchAssoc($db_handle->runQuery($query));
    foreach($list as $row)
    {
        extract($row);
        $query = "UPDATE accounting_system_req_item SET app_no_of_items = '0', app_unit_cost = '0', app_total_cost = '0', item_app = '0' WHERE item_id = '".$row['item_id']."' AND order_code = '$req_order_code'";
        $db_handle->runQuery($query);
    }
    $query = "UPDATE accounting_system_req_order SET comments = '$comment', status = '3', admin_code = '$admin_code', req_order_total= '0' WHERE req_order_code = '$req_order_code' LIMIT 1";
    $result = $db_handle->runQuery($query);
    $result ? $message_success = "Operation Successful" : $message_error = "Operation Failed" ;
}

$query = "SELECT 
          accounting_system_req_order.req_order_total,
          accounting_system_req_order.req_order_code, 
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
if (isset($_GET['pg']) && is_numeric($_GET['pg'])) {    $currentpage = (int) $_GET['pg'];}
else {    $currentpage = 1;}
if ($currentpage > $totalpages) { $currentpage = $totalpages; }
if ($currentpage < 1) { $currentpage = 1; }
$prespagelow = $currentpage * $rowsperpage - $rowsperpage + 1;
$prespagehigh = $currentpage * $rowsperpage;
if($prespagehigh > $numrows) { $prespagehigh = $numrows; }
$offset = ($currentpage - 1) * $rowsperpage;
$query .= 'LIMIT ' . $offset . ',' . $rowsperpage;
$result = $db_handle->runQuery($query);
$projects = $db_handle->fetchAssoc($result);

//SELECT column_name(s) FROM table_name WHERE condition GROUP BY column_name(s) ORDER BY column_name(s);
$query = "SELECT 
          accounting_system_refunds.item_id, 
          accounting_system_refunds.req_order_code, 
          accounting_system_refunds.spent,
          accounting_system_req_order.author_code, 
          accounting_system_req_order.req_order_total, 
          accounting_system_req_order.location,
          accounting_system_office_locations.location,
          accounting_system_refunds.created
          FROM 
          accounting_system_refunds, 
          accounting_system_office_locations, 
          accounting_system_req_order
          WHERE 
          accounting_system_refunds.req_order_code = accounting_system_req_order.req_order_code
          AND 
          accounting_system_req_order.location = accounting_system_office_locations.location_id 
          GROUP BY 
          accounting_system_refunds.req_order_code ";
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
    <script src="../js/class_accounting_system.js"></script>
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
                                foreach ($projects as $row)
                                { ?>
                                    <tr>
                                        <td><?php echo $row['author_name']; ?></td>
                                        <td><?php echo $row['location']; ?></td>
                                        <td>
                                            <button type="button" data-toggle="modal" data-target="#view_order<?php echo $row['req_order_code']; ?>" class="btn btn-default">View Order</button>
                                            <!-- Modal-- View Order List -->
                                            <div id="view_order<?php echo $row['req_order_code']; ?>" class="modal fade" role="dialog">
                                                <div  id="order_form<?php echo $row['req_order_code']; ?>" class="modal-dialog modal-lg">
                                                    <!-- Modal content-->
                                                    <form data-toggle="validator" class="form-horizontal" role="form" method="post" action="<?php echo $_SERVER['REQUEST_URI']; ?>">
                                                        <div class="modal-content">
                                                            <div class="modal-header">
                                                                <button type="button" class="close" data-dismiss="modal">&times;</button>
                                                                <h4 class="modal-title">REQUISITION ORDER</h4>
                                                            </div>
                                                            <div class="modal-body">
                                                                <p><b>NAME:</b> <?php echo $row['author_name']; ?></p>
                                                                <p><b>ORDER TOTAL:</b> ₦<?php echo number_format($row['req_order_total'], 2, ".", ","); ?></p>
                                                                <p><b>LOCATION:</b> <?php echo $row['location']; ?></p>
                                                                <p><b>DATE:</b> <?php echo datetime_to_text($row['created']); ?></p>
                                                                    <table class="table table-responsive table-striped table-bordered table-hover">
                                                                        <thead>
                                                                        <tr>
                                                                            <th><input id="trigger_<?php echo $row['req_order_code']; ?>" onclick="acc_system.setAllCheckboxes('order_form<?php echo $row['req_order_code']; ?>', this, 'trigger_<?php echo $row['req_order_code']; ?>')" title="Select All" type="checkbox" name="" value="1" id="" /></th>
                                                                            <th>Item Description</th>
                                                                            <th>Number Of Items (Requested)</th>
                                                                            <th>Number Of Items (Approved)</th>
                                                                            <th>Unit Cost (Requested)</th>
                                                                            <th>Unit Cost (Approved)</th>
                                                                            <th>Total Cost (Requested)</th>
                                                                            <th>Total Cost (Approved)</th>
                                                                        </tr>
                                                                        </thead>
                                                                        <tbody id="list_<?php echo $row['req_order_code']; ?>">
                                                                        <?php
                                                                        $order_list = $obj_acc_system->get_order_list($row['req_order_code']);
                                                                        foreach($order_list as $row3) {?>
                                                                            <tr id="<?php echo $row['req_order_code']; ?>_<?php echo $row3['item_id']; ?>">
                                                                                <td><input title="Select Item" onclick="acc_system.select_row('<?php echo $row['req_order_code']; ?>_<?php echo $row3['item_id']; ?>', '<?php echo $row3['item_id']; ?>[app_item]')" type="checkbox" id="<?php echo $row3['item_id']; ?>[app_item]" name="<?php echo $row3['item_id']; ?>[app_item]" value="2" /></td>
                                                                                <td><input type="hidden" name="<?php echo $row3['item_id']; ?>[item_id]" value="<?php echo $row3['item_id']; ?>" /><?php echo $row3['item_desc'];?></td>
                                                                                <td><?php echo $row3['no_of_items'];?></td>
                                                                                <td><input onblur="acc_system.get_total('app_no_of_items_<?php echo $row3['item_id']; ?>', 'app_unit_price_<?php echo $row3['item_id']; ?>', 'app_item_total_price_<?php echo $row3['item_id']; ?>')" id="app_no_of_items_<?php echo $row3['item_id']; ?>" type="number" name="<?php echo $row3['item_id']; ?>[app_no_of_items]" value="<?php echo $row3['no_of_items'];?>" class="form-control" disabled/></td>
                                                                                <td><?php echo $row3['unit_cost'];?></td>
                                                                                <td><div class="input-group"><span class="input-group-addon">₦</span><input type="number" onblur="acc_system.get_total('app_no_of_items_<?php echo $row3['item_id']; ?>', 'app_unit_price', 'item_total_price')" id="app_unit_price_<?php echo $row3['item_id']; ?>" name="<?php echo $row3['item_id']; ?>[app_unit_cost]" value="<?php echo $row3['unit_cost'];?>" class="form-control" disabled/></div></td>
                                                                                <td><?php echo $row3['total_cost'];?></td>
                                                                                <td><div class="input-group"><span class="input-group-addon">₦</span><input onfocus = "acc_system.get_total('app_no_of_items_<?php echo $row3['item_id']; ?>', 'app_unit_price_<?php echo $row3['item_id']; ?>', 'app_item_total_price_<?php echo $row3['item_id']; ?>')" type="number" name="<?php echo $row3['item_id']; ?>[app_total_cost]" value="<?php echo $row3['total_cost'];?>" id="app_item_total_price_<?php echo $row3['item_id']; ?>" class="form-control" disabled/></div></td>
                                                                            </tr>
                                                                        <?php }?>
                                                                        <!--<tr>
                                                                            <td colspan="6">
                                                                            </td>
                                                                            <td colspan="2">
                                                                                <center><input disabled class="form-control form-control-lg is-valid" id="app_order_total_<?php /*echo $row['req_order_code']; */?>" value="0.00" /></center>
                                                                            </td>
                                                                        </tr>-->
                                                                        </tbody>
                                                                    </table>
                                                                <textarea id="comment_<?php echo $row['req_order_code']; ?>" placeholder="Enter Your Remark..." name="comment" rows="3" class="form-control danger" required></textarea>
                                                            </div>
                                                            <div class="modal-footer">
                                                                <input type="hidden" name="req_order_code" value="<?php echo $row['req_order_code']; ?>">
                                                                <button onclick="validate_pre_approval('view_order<?php echo $row['req_order_code']; ?>', 'approve_order<?php echo $row['req_order_code']; ?>', 'comment_<?php echo $row['req_order_code']; ?>');" name="button" type="button" class="btn btn-success btn-sm">Approve</button>
                                                                <button id="approve_order<?php echo $row['req_order_code']; ?>" style="display: none" name="approve" type="submit" class="btn btn-danger btn-sm"></button>
                                                                <button name="decline" type="submit" class="btn btn-danger btn-sm">Decline</button>
                                                                <button name="print" onclick="print_report('printout<?php echo $row['req_order_code']; ?>')" type="button" class="btn btn-info btn-sm">Print</button>
                                                                <button type="button" class="btn btn-danger btn-sm" data-dismiss="modal">Close</button>
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


            <div class="row">
                <div class="col-sm-12 text-danger">
                    <h4><strong>REQUISITION ORDER - REFUND CONFIRMATION REQUESTS</strong></h4>
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
                                    <th>Location</th>
                                    <th>Order List</th>
                                    <th>Order Total</th>
                                    <th>Actual Spent</th>
                                    <th>Created </th>
                                </tr>
                            </thead>
                            <tbody>
                            <?php if(isset($refunds) && !empty($refunds))
                            {
                                foreach ($refunds as $row)
                                {
                                    extract($row);
                                    ?>
                                    <tr>
                                        <td><?php echo $admin_object->get_admin_name_by_code($row['author_code']); ?></td>
                                        <td><?php echo $row['location']; ?></td>
                                        <td>
                                            <button type="button" data-toggle="modal" data-target="#view_order<?php echo $row['req_order_code']; ?>" class="btn btn-default btn-sm">View Order</button>
                                            <!-- Modal-- View Order List -->
                                            <div id="view_order<?php echo $row['req_order_code']; ?>" class="modal fade" role="dialog">
                                                <div class="modal-dialog modal-lg">
                                                    <!-- Modal content-->
                                                    <form data-toggle="validator" class="form-horizontal" role="form" method="post" action="<?php echo $_SERVER['REQUEST_URI']; ?>">
                                                        <div class="modal-content">
                                                            <div class="modal-header">
                                                                <button type="button" class="close" data-dismiss="modal">&times;</button>
                                                                <h4 class="modal-title">REFUND CONFIRMATION REQUEST</h4>
                                                            </div>
                                                            <div class="modal-body">
                                                                <p><b>NAME:</b> <?php echo $admin_object->get_admin_name_by_code($row['author_code']); ?></p>
                                                                <p><b>DATE:</b> <?php echo datetime_to_text($row['created']); ?></p>
                                                                <table class="table table-responsive table-striped table-bordered table-hover">
                                                                    <thead>
                                                                    <tr>
                                                                        <th>Description</th>
                                                                        <th>Number Of Items</th>
                                                                        <th>Unit Cost</th>
                                                                        <th>Total Cost</th>
                                                                        <th>Total Spent</th>
                                                                    </tr>
                                                                    </thead>
                                                                    <tbody>
                                                                    <?php
                                                                    $req_order_code = $row['req_order_code'];
                                                                    $q = "SELECT 
                                                                          accounting_system_refunds.refund_id AS refund_no,
                                                                          accounting_system_refunds.item_id AS item_no,
                                                                          accounting_system_req_item.item_desc, 
                                                                          accounting_system_req_item.app_no_of_items,
                                                                          accounting_system_req_item.app_unit_cost, 
                                                                          accounting_system_req_item.app_total_cost,
                                                                          accounting_system_refunds.spent
                                                                          FROM accounting_system_refunds, accounting_system_req_item
                                                                          WHERE accounting_system_refunds.req_order_code = '$req_order_code' 
                                                                          AND accounting_system_refunds.item_id = accounting_system_req_item.item_id ";
                                                                    $refund_list = $db_handle->fetchAssoc($db_handle->runQuery($q));
                                                                    foreach($refund_list as $row3) {?>
                                                                        <tr>
                                                                            <td><?php echo $row3['item_desc'];?></td>
                                                                            <td><?php echo $row3['app_no_of_items']?></td>
                                                                            <td>N <?php echo number_format($row3['app_unit_cost'], 2, '.', ',')?></td>
                                                                            <td>N <?php echo number_format($row3['app_total_cost'], 2, '.', ',');?></td>
                                                                            <td>N <?php echo number_format($row3['spent'], 2, '.', ',');?></td>
                                                                            <input type="hidden" name="<?php echo $row3['item_no'];?>[item_id]" value="<?php echo $row3['item_no'];?>" />
                                                                            <input type="hidden" name="<?php echo $row3['item_no'];?>[refund_id]" value="<?php echo $row3['refund_no'];?>" />
                                                                        </tr>
                                                                    <?php }?>
                                                                    </tbody>
                                                                </table>

                                                            </div>
                                                            <div class="modal-footer">
                                                                <input type="hidden" name="req_order_code" value="<?php echo $row['req_order_code']; ?>"/>
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
                                        <?php
                                            $q = "SELECT SUM(spent) AS actual_spent FROM accounting_system_refunds WHERE req_order_code = '$req_order_code'";
                                            $actual_spent = $db_handle->fetchAssoc($db_handle->runQuery($q))[0]['actual_spent'];
                                        ?>
                                        <td>₦ <?php echo number_format($actual_spent, 2, ".", ","); ?></td>
                                        <td><?php echo datetime_to_text($row['created']); ?></td>
                                    </tr>
                                <?php
                                }
                            }?>
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
<script src="https://code.jquery.com/jquery-1.12.3.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/0.9.0rc1/jspdf.min.js"></script>