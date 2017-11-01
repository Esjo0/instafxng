<?php
require_once("../init/initialize_admin.php");
if (!$session_admin->is_logged_in())
{
    redirect_to("login.php");
}
$location = $db_handle->sanitizePost($_POST['location']);
$author_code = $_SESSION['admin_unique_code'];
//$req_order = $_POST['req_order'];
$req_order = $db_handle->sanitizePost($_POST['req_order']);
$req_order_code = $db_handle->sanitizePost($_POST['req_order_code']);
$req_order_total = $db_handle->sanitizePost($_POST['req_order_total']);

$query = "INSERT INTO accounting_system_req_order (author_code, req_order_code, req_order, req_order_total, location) 
          VALUES ('$author_code', '$req_order_code', '$req_order', '$req_order_total', '$location')";
$result = $db_handle->runQuery($query);
if($result):?>
    <div class="alert alert-success">
        <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
        <strong>Success!</strong> Your order was successfully submitted.
    </div>
<?php endif; ?>
<?php if(!$result): ?>
    <div class="alert alert-danger">
        <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
        <strong>Oops!</strong> Your order failed, please try again.
    </div>
<?php endif; ?>
