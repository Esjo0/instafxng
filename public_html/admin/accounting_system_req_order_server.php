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
if($result):
    $message_main = '<p style="font-size: small">Author: '.$admin_object->get_admin_name_by_code($author_code)."<br/>";
    $message_main .= 'Order Total: N'.number_format($req_order_total, 2)."<br/>";
    $query ="SELECT location FROM accounting_system_office_locations WHERE location_id = '$location' LIMIT 1";
    $result = $db_handle->runQuery($query);
    $result = $db_handle->fetchAssoc($result);
    $result = $result[0]['location'];
    $message_main .= 'Order Location: '.$result."</p>";
    $recipients = "narCT";
    $type = '4';
    $obj_push_notification->add_new_notification($message_main, $recipients, $type);
    ?>
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
