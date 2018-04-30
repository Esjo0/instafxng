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
if(isset($_POST['process_refund']))
{
    $req_order_code = $db_handle->sanitizePost(trim($_POST['req_order_code']));
    foreach($_POST as $row)
    {
        if(is_array($row))
        {
            extract($row);
            $query = "INSERT INTO accounting_system_refunds (req_order_code, item_id, spent) VALUES ('$req_order_code', '$item_id', '$spent')";
            $db_handle->runQuery($query);
        }
    }
    $db_handle->affectedRows() > 0 ? $message_success = "Your refund would be processed shortly." : $message_error = "The process failed please try again.";
}

if(isset($_POST['send_code']))
{
    $title = "New Cash Out Notification";
    $message = 'A new requisition cash out code was added <br><b>Code:</b>'.$_POST['code'].'<br>Copy the code and click the link below to disburse cash.</a>';
    $recipients = $_POST['send_code'];
    $author = $admin_object->get_admin_name_by_code($_SESSION['admin_unique_code']);
    $source_url = "https://instafxng.com/admin/accounting_system_confirmation_requests.php";
    $notify_support = $obj_push_notification->add_new_notification($title, $message, $recipients, $author, $source_url);
}

$query = "SELECT 
          accounting_system_req_order.payment_status,
          accounting_system_req_order.req_order_total AS req_order_total,
          accounting_system_req_order.req_order_code AS req_order_code, 
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
if (isset($_GET['pg']) && is_numeric($_GET['pg'])) {    $currentpage = (int) $_GET['pg'];} else {    $currentpage = 1;}
if ($currentpage > $totalpages) { $currentpage = $totalpages; }
if ($currentpage < 1) { $currentpage = 1; }
$prespagelow = $currentpage * $rowsperpage - $rowsperpage + 1;
$prespagehigh = $currentpage * $rowsperpage;
if($prespagehigh > $numrows) { $prespagehigh = $numrows; }
$offset = ($currentpage - 1) * $rowsperpage;
$query .= 'LIMIT ' . $offset . ',' . $rowsperpage;
$result = $db_handle->runQuery($query);
$projects = $db_handle->fetchAssoc($result);


$reviewers = $obj_push_notification->get_recipients_by_access(224);
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
        <script>
            function validate_refund(id, max_value, btn_id)
            {
                var value = parseFloat(document.getElementById(id).value);
                var max_value1 = parseFloat(max_value);
                if(value > max_value1)
                {
                    alert("Please cross check the amount that was spent\n" +
                        "It should not be above the allocated amount for this item.");
                    document.getElementById(id).value = max_value;
                    return;
                }
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
                            <h4><strong>REQUISITION ORDERS</strong></h4>
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
                                                        <div class="modal-dialog modal-lg">
                                                            <!-- Modal content-->
                                                            <form data-toggle="validator" class="form-horizontal" role="form" method="post" action="<?php echo $_SERVER['REQUEST_URI']; ?>">
                                                                <div class="modal-content">
                                                                    <div class="modal-header">
                                                                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                                                                        <h4 class="modal-title">REQUISITION ORDER</h4>
                                                                    </div>
                                                                    <div class="modal-body">
                                                                        <p><b>Date:</b> <?php echo datetime_to_text($row['created']); ?></p>
                                                                        <p><b>Order Total:</b> ₦ <?php echo number_format($row['req_order_total'], 2, ".", ","); ?></p>

                                                                        <table class="table table-striped table-bordered table-hover">
                                                                                <thead>
                                                                                    <tr>
                                                                                        <th></th>
                                                                                        <th>Item Description</th>
                                                                                        <th>Number Of Items (Requested)</th>
                                                                                        <th>Number Of Items (Approved)</th>
                                                                                        <th>Unit Cost (Requested)</th>
                                                                                        <th>Approved Unit Cost</th>
                                                                                        <th>Requested Total Cost</th>
                                                                                        <th>Approved Total Cost</th>
                                                                                    </tr>
                                                                                </thead>
                                                                                <tbody>
                                                                                <?php
                                                                                $order_list = $obj_acc_system->get_order_list($row['req_order_code']);
                                                                                foreach($order_list as $row3) {?>
                                                                                    <tr>
                                                                                        <td><?php echo $obj_acc_system->item_app_status($row3['item_app']);?></td>
                                                                                        <td><?php echo $row3['item_desc'];?></td>
                                                                                        <td><?php echo $row3['no_of_items'];?></td>
                                                                                        <td><?php echo $row3['app_no_of_items'];?></td>
                                                                                        <td>₦ <?php echo number_format($row3['unit_cost'], 2, ".", ","); ?></td>
                                                                                        <td>₦ <?php echo number_format($row3['app_unit_cost'], 2, ".", ","); ?></td>
                                                                                        <td>₦ <?php echo number_format($row3['total_cost'], 2, ".", ","); ?></td>
                                                                                        <td>₦ <?php echo number_format($row3['app_total_cost'], 2, ".", ","); ?></td>
                                                                                    </tr>
                                                                                <?php }?>
                                                                                </tbody>
                                                                            </table>
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
                                                                                                <div class="col-sm-12">
                                                                                                    <table class="table table-striped table-bordered table-hover">
                                                                                                        <thead>
                                                                                                        <tr>
                                                                                                            <th></th>
                                                                                                            <th>Item Description</th>
                                                                                                            <th>Number Of Items(Requested)</th>
                                                                                                            <th>Number Of Items(Approved)</th>
                                                                                                            <th>Unit Cost(Requested)</th>
                                                                                                            <th>Unit Cost(Approved)</th>
                                                                                                            <th>Total Cost(Requested)</th>
                                                                                                            <th>Total Cost(Approved)</th>
                                                                                                        </tr>
                                                                                                        </thead>
                                                                                                        <tbody>
                                                                                                        <?php
                                                                                                        $order_list = $obj_acc_system->get_order_list($row['req_order_code']);
                                                                                                        foreach($order_list as $row3) {?>
                                                                                                            <tr>
                                                                                                                <td><?php echo $obj_acc_system->item_app_status($row3['item_app']);?></td>
                                                                                                                <td><?php echo $row3['item_desc'];?></td>
                                                                                                                <td><?php echo $row3['no_of_items'];?></td>
                                                                                                                <td><?php echo $row3['app_no_of_items'];?></td>
                                                                                                                <td>₦ <?php echo number_format($row3['unit_cost'], 2, ".", ","); ?></td>
                                                                                                                <td>₦ <?php echo number_format($row3['app_unit_cost'], 2, ".", ","); ?></td>
                                                                                                                <td>₦ <?php echo number_format($row3['total_cost'], 2, ".", ","); ?></td>
                                                                                                                <td>₦ <?php echo number_format($row3['app_total_cost'], 2, ".", ","); ?></td>
                                                                                                            </tr>
                                                                                                        <?php }?>
                                                                                                        </tbody>
                                                                                                    </table>
                                                                                                </div>
                                                                                                <br/>
                                                                                                <br/>
                                                                                                <?php if ($row['status'] == "APPROVED"): ?>
                                                                                                    <p class="text-center"><b>This requisition order has been APPROVED.</b></p>
                                                                                                <?php endif; ?>
                                                                                                <?php if ($row['status'] == "DECLINED"): ?>
                                                                                                    <p class="text-center"><b>This requisition order has been DECLINED.</b></p>
                                                                                                <?php endif; ?>
                                                                                                <?php //include 'layouts/footer.php'; ?>
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
                                                                        <button name="print" onclick="print_report('printout<?php echo $row['req_order_code']; ?>')" type="button" class="btn btn-info">Print</button>
                                                                        <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
                                                                    </div>
                                                                </div>
                                                            </form>
                                                        </div>
                                                    </div>
                                                </td>
                                                <td>₦ <?php echo number_format($row['req_order_total'], 2, ".", ","); ?> </td>
                                                <td><?php if(isset($row['comments']) && !empty($row['comments'])){echo $row['comments'];} else{echo '<b>N/A</b>';}  ?></td>
                                                <td>
                                                    <?php if($row['status'] == '2'){echo 'APPROVED';} ?>
                                                    <?php if($row['status'] == '1'){echo 'AWAITING CONFIRMATION';} ?>
                                                    <?php if($row['status'] == '3'){echo 'DECLINED';} ?>
                                                    <?php if($row['status'] == '0'){echo '<b>N/A</b>';} ?>
                                                </td>
                                                <td>
                                                    <div class="row">
                                                        <div class="col-sm-6">
                                                            <?php if($row['status'] == '2'){ echo strtoupper($row['req_order_code']);}else{echo "<b>N/A</b>";} ?>
                                                        </div>
                                                        <div class="col-sm-6">
                                                            <?php if($row['status'] == '2'): ?>
                                                                <form data-toggle="validator" class="form-horizontal" role="form" method="post" action="<?php echo $_SERVER['REQUEST_URI']; ?>">
                                                                    <input name="code" type="hidden" value="<?php echo $row['req_order_code'];?>">
                                                                    <div class="pull-right">
                                                                        <div class="btn-group">
                                                                            <button title="Send Cash Out Code" class="btn btn-success btn-secondary btn-sm dropdown-toggle" type="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                                                                <i class="glyphicon glyphicon-share"></i>
                                                                            </button>
                                                                            <div class="dropdown-menu">
                                                                                <?php if(isset($reviewers) && !empty($reviewers)){ $x = explode(',', $reviewers);  ?>
                                                                                    <?php foreach ($x as $key)
                                                                                    {if($key != ''): ?>
                                                                                        <button name="send_code" class="btn btn-group-justified btn-sm btn-default" type="submit" value="<?php echo $key;?>">
                                                                                            <?php echo $admin_object->get_admin_name_by_code($key);?>
                                                                                        </button>
                                                                                    <?php endif;
                                                                                    } ?>
                                                                                <?php }else{ echo '<em>No Reviewer Available</em>';} ?>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                </form>
                                                            <?PHP endif; ?>
                                                        </div>
                                                    </div>
                                                </td>
                                                <td><?php echo datetime_to_text($row['created']); ?></td>
                                                <td>
                                                    <?php if($row['status'] == '2' && $row['payment_status'] == '2'){?>
                                                        <button type="button" data-toggle="modal" data-target="#refund<?php echo $row['req_order_code']; ?>" class="btn btn-success btn-sm"><i class="glyphicon glyphicon-repeat"></i></button>
                                                    <?php }else{echo "<b>N/A</b>";} ?>
                                                    <!-- Modal-- refund -->
                                                    <div id="refund<?php echo $row['req_order_code']; ?>" class="modal fade" role="dialog">
                                                        <div class="modal-dialog modal-lg">
                                                            <!-- Modal content-->
                                                            <form data-toggle="validator" class="form-horizontal" role="form" method="post" action="<?php echo $_SERVER['REQUEST_URI']; ?>">
                                                                <div class="modal-content">
                                                                    <div class="modal-header">
                                                                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                                                                        <h4 class="modal-title">REFUND</h4>
                                                                    </div>
                                                                    <div class="modal-body">
                                                                        <p><b>Date:</b> <?php echo datetime_to_text($row['created']); ?></p>
                                                                        <p><b>Order Total:</b> ₦ <?php echo number_format($row['req_order_total'], 2, ".", ","); ?></p>

                                                                        <table class="table table-striped table-bordered table-hover">
                                                                            <thead>
                                                                            <tr>
                                                                                <th>Item Description</th>
                                                                                <th>Approved Total Cost</th>
                                                                                <th>Actual Spent</th>
                                                                            </tr>
                                                                            </thead>
                                                                            <tbody>
                                                                            <?php
                                                                            $refund_list = $obj_acc_system->get_order_refund_list($row['req_order_code']);
                                                                            foreach($refund_list as $row4) {?>
                                                                                <tr>
                                                                                    <td><?php echo $row4['item_desc'];?></td>
                                                                                    <td>₦ <?php echo number_format($row4['app_total_cost'], 2, ".", ","); ?></td>
                                                                                    <td><input type="hidden" name="<?php echo $row4['item_id'] ?>[item_id]" value="<?php echo $row4['item_id'] ?>" />
                                                                                        <div class="input-group">
                                                                                            <span class="input-group-addon">₦</span>
                                                                                        <input id="<?php echo $row4['item_id'] ?>_spent" onchange="validate_refund('<?php echo $row4['item_id'] ?>_spent', <?php echo $row4['app_total_cost']; ?>, 'process_refund_<?php echo $row4['item_id'] ?>')" class="form-control" type="number" name="<?php echo $row4['item_id'] ?>[spent]" required/>
                                                                                        </div>
                                                                                    </td>
                                                                                </tr>
                                                                            <?php }?>
                                                                            </tbody>
                                                                        </table>
                                                                    </div>
                                                                    <div class="modal-footer">
                                                                        <input type="hidden" name="req_order_code" value="<?php echo $row['req_order_code']; ?>">
                                                                        <button id="process_refund_<?php echo $row4['item_id'] ?>" name="process_refund" type="submit" class="btn btn-info">Process</button>
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