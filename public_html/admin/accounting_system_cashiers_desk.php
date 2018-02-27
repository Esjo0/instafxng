<?php
require_once("../init/initialize_admin.php");
if (!$session_admin->is_logged_in())
{
    redirect_to("login.php");
}

if(isset($_POST['paid']))
{
    $result = $obj_acc_system->paid_req_order($_POST['req_order_code']);
    $result ? $message_success = "Operation Successful" : $message_error = "Operation Failed";
}

if(isset($_POST['search']))
{
    $cash_out_details = $obj_acc_system->get_cash_out_details($_POST['cash_out_code']);
    !isset($cash_out_details) && empty($cash_out_details) ? $message_error = "No Result Found!" : $message_success = "<b>1</b> Result Found!";

}

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
                            <h4><strong>REQUISITION FORM</strong></h4>
                        </div>
                    </div>
                    
                    <div class="section-tint super-shadow">
                        <div class="row">
                            <div class="col-sm-12">
                                <?php require_once 'layouts/feedback_message.php'; ?>
                                <div class="col-sm-12">
                                    <form id="requisition_form" data-toggle="validator" class="form-horizontal" role="form" method="post" action="">
                                        <p>Enter a cash out code below.</p>
                                        <div class="form-group">
                                            <!--<label class="control-label col-sm-3" for="full_name">Customer's Name:</label>-->
                                            <div class="col-sm-12">
                                                <div class="row">
                                                    <div class="col-md-5">
                                                        <input name="cash_out_code" type="text" id="cash_out_code" placeholder="Enter A Cash Out Code Here..." class="form-control" required>
                                                    </div>
                                                    <div class="col-md-1">
                                                        <button name="search" type="submit" class="btn btn-success"><i class="glyphicon glyphicon-search"></i></button>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </form>
                                </div>
                                <div  class="col-sm-12">
                                    <?php if(isset($_POST['search']))
                                    {
                                        if(isset($cash_out_details) && !empty($cash_out_details))
                                        {  ?>
                                        <p><b>NAME: </b> <?php echo $cash_out_details['author_name']; ?></p>
                                        <p><b>AMOUNT: </b>₦<?php echo number_format($cash_out_details['req_order_total'], 2, ".", ","); ?></p>
                                        <p class="text-center"><b>REQUISITION ORDER SUMMARY</b></p>
                                        <div class="col-sm-12">
                                            <table class="table table-striped table-bordered table-hover">
                                                <thead>
                                                <tr>
                                                    <th></th>
                                                    <th>Item Description</th>
                                                    <th>Number Of Items</th>
                                                    <th>Approved Unit Cost</th>
                                                    <th>Approved Total Cost</th>
                                                </tr>
                                                </thead>
                                                <tbody>
                                                <?php
                                                $order_list = $obj_acc_system->get_order_list($cash_out_details['req_order_code']);
                                                foreach($order_list as $row3) {?>
                                                    <tr>
                                                        <td><?php echo $obj_acc_system->item_app_status($row3['item_app']);?></td>
                                                        <td><?php echo $row3['item_desc'];?></td>
                                                        <td><?php echo $row3['app_no_of_items'];?></td>
                                                        <td>₦ <?php echo number_format($row3['app_unit_cost'], 2, ".", ",") ;?></td>
                                                        <td>₦ <?php echo number_format($row3['app_total_cost'], 2, ".", ",") ;?></td>
                                                    </tr>
                                                <?php }?>
                                                </tbody>
                                            </table>
                                        </div>
                                        <form data-toggle="validator" class="form-horizontal" role="form" method="post" action="">
                                            <input name="req_order_code" type="hidden" value="<?php echo $cash_out_details['req_order_code']; ?>">
                                            <button type="submit" name="paid" class="btn btn-success"><i class="glyphicon glyphicon-credit-card"></i> Paid</button>
                                        </form>
                                    <?php }} ?>
                                </div>
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