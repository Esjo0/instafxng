<?php
require_once("../init/initialize_admin.php");
if (!$session_admin->is_logged_in())
{
    redirect_to("login.php");
}
$cash_out_code = $db_handle->sanitizePost($_POST['cash_out_code']);

$query = "SELECT 
          accounting_system_req_order.req_order_total AS req_order_total,
          accounting_system_req_order.req_order_code AS req_order_code, 
          accounting_system_req_order.req_order AS req_order, 
          accounting_system_req_order.created AS created, 
           accounting_system_req_order.status AS status,
          CONCAT(admin.first_name, SPACE(1), admin.last_name) AS author_name
          FROM admin, accounting_system_req_order 
          WHERE accounting_system_req_order.req_order_code = '$cash_out_code' 
          AND accounting_system_req_order.status = '2'
           AND accounting_system_req_order.payment_status = '1'LIMIT 1";
$result = $db_handle->runQuery($query);
$num_rows = $db_handle->numOfRows($result);
$result = $db_handle->fetchAssoc($result);
if($num_rows > 0):
    $result = $result[0];
?>
    <p><b>NAME: </b> <?php echo $result['author_name']; ?></p>
    <p><b>AMOUNT: </b>₦<?php echo number_format($result['req_order_total'], 2, ".", ","); ?></p>
    <p class="text-center">
        <b>REQUISITION ORDER SUMMARY</b>
    </p>
    <?php echo $result['req_order']; ?>
    <form data-toggle="validator" class="form-horizontal" role="form" method="post" action="">
        <input name="req_order_code" type="hidden" value="<?php echo $result['req_order_code']; ?>">
        <button type="submit" name="paid" class="btn btn-success"><i class="glyphicon glyphicon-credit-card"></i> Paid</button>
    </form>
<?php endif; ?>

<?php if($num_rows < 1):
?>
<p class="text-center"><b>Sorry This Cash Out Code Does Not Exist Or Its Order Is Yet To Be Approved Or It Has Already Been Cashed Out.</b></p>
<?php endif; ?>

