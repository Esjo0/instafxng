<?php 
$REQUEST_URI = strtok($_SERVER['REQUEST_URI'], '?');
?>
<ul class="fa-ul">
    <li><i class="fa-li fa fa-check-square-o icon-tune"></i>Select Payment Type - Select one of the options below to make your payment. Please take note of your Transaction ID as you can use it to trace your transaction with us.</li>
</ul>

<p>
    Instaforex Account: <strong><?php if(isset($client_account)) { echo $client_account; } ?></strong><br/>
    Transaction ID: <strong><?php if(isset($client_trans_id)) { echo $client_trans_id; } ?></strong><br/>
    Instaforex Credit Requested: <strong>&dollar; <?php if(isset($client_dollar)) { echo $client_dollar; } ?></strong><br/>
    Total Amount Payable: <strong>&#8358; <?php if(isset($client_naira_total)) { echo number_format($client_naira_total, 2, ".", ","); } ?></strong>
</p>
<hr/>

<p style="color: red">NOTE: Kindly make sure you pay into the account details specified on the payment invoice.</p>
<p style="color: red">When making your payment via internet banking, mobile transfer or filling your teller for cash deposit include your
    transaction ID and account number in the REMARK column E.g (D151112268 - 123456)</p>

<form role="form" method="post" action="<?php echo $REQUEST_URI . '?p=ptype'; ?>">
    <input name="transaction_no" type="hidden" value="<?php if(isset($trans_id_encrypted)) { echo $trans_id_encrypted; } ?>">

<!--    <div class="radio" title="Note!!! You will not need to make payment Notification using this option">-->
<!--        <label>-->
<!--            <input name="pay_type" type="radio" value="1" checked="checked"> Instant Card Payments (MasterCard / Visa / Verve)-->
<!--        </label>-->
<!--    </div>-->
    <div class="radio">
        <label>
            <input name="pay_type" type="radio" value="2"> Internet Banking Transfer
        </label>
    </div>
    <div class="radio">
        <label>
            <input name="pay_type" type="radio" value="3"> ATM Transfer 
        </label>
    </div>
    <div class="radio">
        <label>
            <input name="pay_type" type="radio" value="4"> Bank Transfer
        </label>
    </div>
    <div class="radio">
        <label>
            <input name="pay_type" type="radio" value="5"> Mobile Money Transfer
        </label>
    </div>
    <div class="radio">
        <label>
            <input name="pay_type" type="radio" value="6"> Cash Deposit
        </label>
    </div>
    <div class="radio">
        <label>
            <input name="pay_type" type="radio" value="9"> USSD Transfer
        </label>
    </div>
    <div class="form-group">
        <input name="deposit_funds_pay_type" type="submit" class="btn btn-success" value="Make Payment" /> <a href="deposit_funds.php" class="btn btn-danger">Cancel Process</a>
    </div>
    <br/>
    <p><img src="images/interswitch-logo.png" alt="" class="img-responsive"/></p>
</form>