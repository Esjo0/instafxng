<?php require_once("../init/initialize_admin.php"); ?>
<?php
if($_POST['type'] == '1')
{
    $query_count = 0;
    $success_count = 0;
    $query = explode("*", $_POST['query']);
    foreach ($query as $row)
    {
        if(!empty($row))
        {
            $query_count++;
            $result = $db_handle->runQuery($row);
            $result ? $success_count++ : $success_count = $success_count;
        }
    }
    if($query_count == $success_count){?>
        <div id="success_alert" class="alert alert-success">
            <a id="alert_dismiss" style="display: none" href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
            <strong>Success!</strong> Your order was successfully submitted.
            <br/>
            Click <a href="accounting_system_requisition_orders.php">HERE</a> to view the status of this order.
            <br/>
            Review your last order below or click <a href="javascript:void(0);" onclick="acc_system.new_order_list();">HERE</a> to create a new order.
        </div>

<?php }else{ ?>
        <div class="alert alert-danger">
            <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
            <strong>Oops!</strong> Your order failed, please try again.
        </div>
<?php }} ?>

























