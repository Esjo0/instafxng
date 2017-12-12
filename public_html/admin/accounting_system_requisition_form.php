<?php
require_once("../init/initialize_admin.php");
if (!$session_admin->is_logged_in())
{
    redirect_to("login.php");
}

$query = "SELECT * FROM accounting_system_office_locations ";
$result = $db_handle->runQuery($query);
$locations = $db_handle->fetchAssoc($result);
$author_code = $_SESSION['admin_unique_code'];
if(isset($_POST['send_order']))
{
    $office_location = $db_handle->sanitizePost($_POST['office_location']);

    $req_order = $db_handle->sanitizePost($_POST['req_order']);
    $req_order_code = $db_handle->sanitizePost($_POST['req_order_code']);
    $req_order_total = $db_handle->sanitizePost($_POST['req_order_total']);

    $query = "INSERT INTO accounting_system_req_order (author_code, req_order_code, req_order, req_order_total, location) 
          VALUES ('$author_code', '$req_order_code', '$req_order', '$req_order_total', '$office_location')";
    $result = $db_handle->runQuery($query);
}

if(isset($_POST['process_refund']))
{
    $req_order_total = $db_handle->sanitizePost(trim($_POST['req_order_total']));
    $req_order_code = $db_handle->sanitizePost(trim($_POST['req_order_code']));
    $order_total_spent = $db_handle->sanitizePost(trim($_POST['order_total_spent']));

    if($order_total_spent > $req_order_total)
    {
        $message_error = "Operation Failed, Please Crosscheck The Amount You Want To Refund.";
    }
    $query = "INSERT INTO accounting_system_refunds (req_order_code, actual_spent) VALUES ('$req_order_code', '$order_total_spent') ";
    $result = $db_handle->runQuery($query);
    if($result)
    {
        $message_success = "Your refund would be processed shortly.";
    }
}

$query = "SELECT 
          payment_status,
          accounting_system_req_order.req_order_total AS req_order_total,
          accounting_system_req_order.req_order_code AS req_order_code, 
          accounting_system_req_order.req_order AS req_order, 
          accounting_system_req_order.created AS created, 
          accounting_system_req_order.status AS status,
          accounting_system_req_order.comments AS comments,
          CONCAT(admin.first_name, SPACE(1), admin.last_name) AS author_name
          FROM admin, accounting_system_req_order 
          WHERE accounting_system_req_order.author_code = '$author_code'
          AND admin.admin_code = '$author_code'
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

$result = $db_handle->runQuery($query);
$projects = $db_handle->fetchAssoc($result);
?>
<!DOCTYPE html>
<html lang="en">
    <head>
        <base target="_self">
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>Instaforex Nigeria | Admin - Accounting System</title>
        <meta name="title" content="Instaforex Nigeria | Admin - Accounting System" />
        <meta name="keywords" content="" />
        <meta name="description" content="" />
        <?php require_once 'layouts/head_meta.php'; ?>
        <?php require_once 'accounting_system_ajax_scripts.php'; ?>
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
                            <h4><strong>REQUISITION FORM</strong></h4>
                        </div>
                    </div>
                    
                    <div class="section-tint super-shadow">
                        <div class="row">
                            <div class="col-sm-12">
                                <?php require_once 'layouts/feedback_message.php'; ?>
                                <div id="messageDiv">
                                    </div>
                                <div class="col-sm-12">
                                    <form id="requisition_form" data-toggle="validator" class="form-horizontal" role="form" method="post" action="">
                                        <p>Fill the form below to add a new item to your requisition order.</p>
                                        <div class="form-group">
                                            <div class="col-sm-12">
                                                <div class="row">
                                                    <div class="col-md-4">
                                                        <input name="item_name" type="text" id="item_name" placeholder="Item Description" class="form-control" required>
                                                    </div>
                                                    <div class="col-md-2">
                                                        <div class="input-group">
                                                            <input  name="no_of_items" type="number" id="no_of_items" placeholder="No Of Items"  class="form-control" required>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-2">
                                                        <div class="input-group">
                                                            <span class="input-group-addon">₦</span>
                                                            <input onblur="get_total()"  name="unit_price" type="text" id="unit_price" placeholder="Unit Cost"  class="form-control" required>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-3">
                                                        <div class="input-group">
                                                            <span class="input-group-addon">₦</span>
                                                            <input onfocus="get_total()"  name="item_total_price" type="text" id="item_total_price" placeholder="Total Cost"  class="form-control" required>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-1">
                                                        <button onclick="AddtoList()" type="button" class="btn btn-success"><i class="glyphicon glyphicon-plus"></i></button>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </form>
                                </div>
                                <div  class="col-sm-12">
                                    <div id="order_list">
                                        <table class="table table-striped table-bordered table-hover">
                                            <thead>
                                            <tr>
                                                <th>Description</th>
                                                <th>No Of Items</th>
                                                <th>Unit Cost</th>
                                                <th>Total Cost</th>
                                            </tr>
                                            </thead>
                                            <tbody id="requisitionOrder">
                                            <tr>
                                                <td colspan="4" >
                                                    <p class="pull-right">
                                                        Order Total : <b>₦</b><b id="requisition_total">0</b>
                                                    </p>
                                                </td>
                                            </tr>
                                            </tbody>
                                        </table>
                                    </div>
                                    <p><b>Select Location:</b></p>
                                    <?php if(isset($locations) && !empty($locations))
                                    {
                                    foreach ($locations as $row)
                                    { ?>
                                    <div class="form-group row">
                                        <div class="col-sm-6">
                                            <div class="radio-inline">
                                                <label for="">
                                                    <input id="location" type="radio" name="office_location" value="<?php echo $row['location_id']; ?>"  required/>
                                                    <?php echo strtoupper($row['location']); ?>
                                                </label>
                                            </div>
                                        </div>

                                        <?php
                                        }
                                        }
                                        else
                                        { echo "No results to display..."; } ?>
                                    </div>
                                    <div class="form-group">
                                        <div class="col-sm-offset-10 col-sm-3">
                                            <button onclick="send_order()"  name="send_order" type="button" class="btn btn-success"><i class="glyphicon glyphicon-send"></i> Submit Order</button>
                                            <!--<button data-toggle="modal" data-target="#submit_order" type="button" class="btn btn-success">
                                                <i class="glyphicon glyphicon-send"></i> Submit Order</button>-->
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-sm-12 text-danger">
                            <h4><strong>REQUISITION ORDERS</strong></h4>
                        </div>
                    </div>
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
                                    <th>Order List</th>
                                    <th>Order Total</th>
                                    <th>Comments</th>
                                    <th>Status</th>
                                    <th>Cash Out Code</th>
                                    <th>Created </th>
                                    <th>Refund</th>
                                </tr>
                                </thead>
                                <tbody>

                                <?php if(isset($projects) && !empty($projects))
                                {
                                    foreach ($projects as $row)
                                    { ?>
                                        <tr>
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
                                                                    <h4 class="modal-title">REQUSITION ORDER</h4>
                                                                </div>
                                                                <div class="modal-body">
                                                                    <p><b>DATE:</b> <?php echo $row['created']; ?></p>
                                                                    <?php echo $row['req_order']?>
                                                                    <div id="printout<?php echo $row['req_order_code']; ?>" class="container-fluid" style="display: none">
                                                                        <div class="row no-gutter">
                                                                            <div class="col-lg-1">
                                                                            </div>
                                                                            <div id="main-body-content-area" class="col-lg-10">
                                                                                <!-- Unique Page Content Starts Here
                                                                                ==================================================--->
                                                                                <div class="section-tint super-shadow">
                                                                                    <div class="row">
                                                                                        <div class="col-sm-12">
                                                                                            <div id="main-logo" class=" col-sm-12 col-md-9">
                                                                                                <a title="Home Page"><img src="../images/ifxlogo.png?v=1.1" alt="Instaforex Nigeria Logo" /></a>
                                                                                            </div>
                                                                                            <div class="row">
                                                                                                <div class="col-sm-12 text-danger">
                                                                                                    <h4 class="text-center"><strong>REQUISITION ORDER</strong></h4>
                                                                                                </div>
                                                                                            </div>
                                                                                            <p><b>AUTHOR NAME:</b> <?php echo $row['author_name']; ?></p>
                                                                                            <p><b>DATE:</b> <?php echo $row['created']; ?></p>
                                                                                            <br/>
                                                                                            <br/>
                                                                                            <p class="text-justify"> <?php echo $row['req_order'] ?> </p>
                                                                                            <br/>
                                                                                            <br/>
                                                                                            <?php if ($row['status'] == "APPROVED"): ?>
                                                                                                <p class="text-center"><b>This requisition order has been APPROVED.</b></p>
                                                                                            <?php endif; ?>
                                                                                            <?php if ($row['status'] == "DECLINED"): ?>
                                                                                                <p class="text-center"><b>This requisition order has been DECLINED.</b></p>
                                                                                            <?php endif; ?>
                                                                                            <?php include 'layouts/footer.php'; ?>
                                                                                        </div>
                                                                                    </div>
                                                                                </div>
                                                                                <!-- Unique Page Content Ends Here
                                                                                ==================================================-->
                                                                            </div>
                                                                            <div class="col-lg-1">
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                                <div class="modal-footer">
                                                                    <input type="hidden" name="req_order_code" value="<?php echo $row['req_order_code']; ?>">
                                                                    <button name="print" onclick="print_report('printout<?php echo $row['req_order_code']; ?>')" type="button" class="btn btn-info">Print</button>
                                                                    <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
                                                                </div>
                                                            </div>
                                                        </form>
                                                    </div>
                                                </div>
                                            </td>
                                            <td>₦<?php echo $row['req_order_total']; ?></td>
                                            <td><?php echo $row['comments']; ?></td>
                                            <td>
                                                <?php if($row['status'] == '2'){echo 'APPROVED';} ?>
                                                <?php if($row['status'] == '1'){echo 'AWAITING CONFIRMATION';} ?>
                                                <?php if($row['status'] == '3'){echo 'DECLINED';} ?>
                                            </td>
                                            <td><?php if($row['status'] == '2'){echo $row['req_order_code'];} ?></td>
                                            <td><?php echo datetime_to_text($row['created']); ?></td>
                                            <td>
                                                <?php if($row['status'] == '2' && $row['payment_status'] == '2'):?>
                                                    <button type="button" data-toggle="modal" data-target="#refund<?php echo $row['req_order_code']; ?>" class="btn btn-success"><i class="glyphicon glyphicon-repeat"></i></button>
                                                <?php endif; ?>
                                                <!-- Modal-- refund -->
                                                <div id="refund<?php echo $row['req_order_code']; ?>" class="modal fade" role="dialog">
                                                    <div class="modal-dialog">
                                                        <!-- Modal content-->
                                                        <form data-toggle="validator" class="form-horizontal" role="form" method="post" action="<?php echo $_SERVER['REQUEST_URI']; ?>">
                                                            <div class="modal-content">
                                                                <div class="modal-header">
                                                                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                                                                    <h4 class="modal-title">REFUND</h4>
                                                                </div>
                                                                <div class="modal-body">
                                                                    <div class="form-group">
                                                                        <div class="col-sm-12">
                                                                            <div class="row">
                                                                                <div class="col-md-4">
                                                                                    <label for="order_total_spent"><b><b>ORDER DATE : </b></b><label>
                                                                                </div>
                                                                                <div class="col-md-7">
                                                                                    <div class="input-group">
                                                                                        <span class="input-group-addon">₦</span>
                                                                                        <input value="<?php echo datetime_to_text($row['created']); ?>"  type="text"   class="form-control" disabled>
                                                                                    </div>
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                    <div class="form-group">
                                                                        <div class="col-sm-12">
                                                                            <div class="row">
                                                                                <div class="col-md-4">
                                                                                    <label for="order_total_spent"><b>ORDER TOTAL:</b><label>
                                                                                </div>
                                                                                <div class="col-md-7">
                                                                                    <div class="input-group">
                                                                                        <span class="input-group-addon">₦</span>
                                                                                        <input value="<?php echo number_format($row['req_order_total'], 2, ".", ","); ?>"  type="text"   class="form-control" disabled>
                                                                                    </div>
                                                                                </div>

                                                                            </div>
                                                                        </div>
                                                                    </div>

                                                                    <div class="form-group">
                                                                        <div class="col-sm-12">
                                                                            <div class="row">
                                                                                <div class="col-md-4">
                                                                                    <label for="order_total_spent">TOTAL AMOUNT SPENT : <label>
                                                                                </div>
                                                                                <div class="col-md-7">
                                                                                    <div class="input-group">
                                                                                        <span class="input-group-addon">₦</span>
                                                                                        <input  name="order_total_spent" type="text" id="order_total_spent"  class="form-control" required>
                                                                                    </div>
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                                <div class="modal-footer">
                                                                    <input type="hidden" name="req_order_total" value="<?php echo $row['req_order_total']; ?>">
                                                                    <input type="hidden" name="req_order_code" value="<?php echo $row['req_order_code']; ?>">
                                                                    <button name="process_refund" type="submit" class="btn btn-info">Process</button>
                                                                    <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
                                                                </div>
                                                            </div>
                                                        </form>
                                                    </div>
                                                </div>
                                            </td>
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
                    </div>
                    <?php if(isset($projects) && !empty($projects)) { require_once 'layouts/pagination_links.php'; } ?>


                    <!-- Unique Page Content Ends Here
                    ================================================== -->

                </div>
            </div>
        </div>
        <?php require_once 'layouts/footer.php'; ?>
    </body>
</html>