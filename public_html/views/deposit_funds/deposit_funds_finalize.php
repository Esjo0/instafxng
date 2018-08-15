<?php
    $REQUEST_URI = strtok($_SERVER['REQUEST_URI'], '?');
?>
<ul class="fa-ul">
    <li><i class="fa-li fa fa-check-square-o icon-tune"></i>Deposit Order Summary - Accept terms and click on finalize</li>
</ul>

<p style="color: red">NOTE: Kindly make sure you pay into the account details specified on the payment invoice.</p>
<p style="color: red">When making your payment via internet banking, mobile transfer or filling your teller for cash deposit include your
    transaction ID and account number in the REMARK column E.g (D151112268 - 123456)</p>

<form data-toggle="validator" class="form-horizontal" role="form" method="post" action="<?php echo $REQUEST_URI . '?p=fnz'; ?>">
    <input name="transaction_no" type="hidden" value="<?php if(isset($trans_id_encrypted)) { echo $trans_id_encrypted; } ?>">
    <div class="form-group">
        <label class="control-label col-sm-3" for="client_name">Client Name:</label>
        <div class="col-sm-9 col-lg-5">
            <input name="client_name" type="text" class="form-control" id="client_name" value="<?php if(isset($client_full_name)) { echo $client_full_name; } ?>" readonly="readonly">
        </div>
    </div>
    <div class="form-group">
        <label class="control-label col-sm-3" for="ifx_acct_no">Instaforex Account:</label>
        <div class="col-sm-9 col-lg-5">
            <input name="ifx_acct_no" type="text" class="form-control" id="ifx_acct_no" value="<?php if(isset($ifx_acc_no)) { echo $ifx_acc_no; } ?>" readonly="readonly">
        </div>
    </div>
    <div class="form-group">
        <label class="control-label col-sm-3" for="trans_id">Transaction ID:</label>
        <div class="col-sm-9 col-lg-5">
            <input name="trans_id" type="text" class="form-control" id="trans_id" value="<?php if(isset($trans_id)) { echo $trans_id; } ?>" readonly="readonly">
        </div>
    </div>
    <div class="form-group">
        <label class="control-label col-sm-3" for="ifx_dollar_amount">Order Value:</label>
        <div class="col-sm-9 col-lg-5">
            <input name="ifx_dollar_amount" type="hidden" class="form-control" id="ifx_dollar_amount" value="<?php if(isset($ifx_dollar_amount)) { echo number_format($ifx_dollar_amount, 2, ".", ","); } ?>" readonly="readonly">
            <p style="font-size: 1.3em; padding: 0; color: green;"><strong>&dollar; <?php if(isset($ifx_dollar_amount)) { echo number_format($ifx_dollar_amount, 2, ".", ","); } ?></strong></p>
        </div>
    </div>
    
    <?php if(isset($point_claimed)) { ?>
    <div class="form-group">
        <label class="control-label col-sm-3" for="point_claimed_value"><span class="text-danger">Points Claimed Value (USD):</span></label>
        <div class="col-sm-9 col-lg-5">
            <input name="point_claimed" type="hidden" value="<?php echo $point_claimed; ?>">
            <input name="point_claimed_value" type="text" class="form-control" id="point_claimed_value" value="<?php echo number_format(($point_claimed * DOLLAR_PER_POINT), 2, ".", ","); ?>" readonly="readonly">
        </div>
    </div>

    <div class="form-group">
        <label class="control-label col-sm-3">Total Order Value:</label>
        <div class="col-sm-9 col-lg-5">
            <p style="font-size: 1.3em; padding: 0; color: green;"><strong>&dollar; <?php echo number_format(($ifx_dollar_amount + ($point_claimed * DOLLAR_PER_POINT)), 2, ".", ","); ?></strong> (points inclusive)</p>
        </div>
    </div>

    <?php } ?>
    
    <div class="form-group">
        <label class="control-label col-sm-3" for="ifx_naira_amount">Order Value (&#8358;):</label>
        <div class="col-sm-9 col-lg-5">
            <input name="ifx_naira_amount" type="text" class="form-control" id="ifx_naira_amount" value="<?php if(isset($ifx_naira_amount)) { echo number_format($ifx_naira_amount, 2, ".", ","); } ?>" readonly="readonly">
        </div>
    </div>
    <div class="form-group">
        <label class="control-label col-sm-3" for="service_charge">Service Charge (&#8358;):</label>
        <div class="col-sm-9 col-lg-5">
            <input name="service_charge" type="text" class="form-control" id="service_charge" value="<?php if(isset($service_charge)) { echo number_format($service_charge, 2, ".", ","); } ?>" readonly="readonly">
        </div>
    </div>
    <div class="form-group">
        <label class="control-label col-sm-3" for="vat">VAT (&#8358;):</label>
        <div class="col-sm-9 col-lg-5">
            <input name="vat" type="text" class="form-control" id="vat" value="<?php if(isset($vat)) { echo number_format($vat, 2, ".", ","); } ?>" readonly="readonly">
        </div>
    </div>
    <div class="form-group">
        <label class="control-label col-sm-3" for="stamp_duty">CBN Stamp Duty (&#8358;):</label>
        <div class="col-sm-9 col-lg-5">
            <input name="stamp_duty" type="text" class="form-control" id="stamp_duty" value="<?php echo number_format($stamp_duty, 2, ".", ","); ?>" readonly="readonly">
        </div>
    </div>
    <hr />
    <div class="form-group">
        <label class="control-label col-sm-3" for="total_payable">Total Payable:</label>
        <div class="col-sm-9 col-lg-5">
            <input name="total_payable" type="hidden" class="form-control" id="total_payable" value="<?php if(isset($total_payable)) { echo number_format($total_payable, 2, ".", ","); } ?>" readonly="readonly">
            <p style="font-size: 1.6em; padding: 0; color: green;"><strong>&#8358; <?php if(isset($total_payable)) { echo number_format($total_payable, 2, ".", ","); } ?></strong></p>
        </div>
    </div>
    <div class="form-group">
        <label class="control-label col-sm-3" for="terms"></label>
        <div class="col-sm-9 col-lg-5">
            <input type="checkbox" name="terms" value="1" required> I accept the <a href="terms.php" target="_blank" >terms and conditions</a>
        </div>
    </div>
    <div class="form-group">
        <label class="control-label col-sm-3" for="terms_notice"></label>
        <div class="col-sm-9 col-lg-5">
            <input type="checkbox" name="terms_notice" value="1" required> <span style="color: red">I will make sure to confirm and make payment into the exact account details specified on my payment invoice.</span>
        </div>
    </div>
    <div class="form-group">
        <div class="col-sm-offset-3 col-sm-9"><input name="deposit_funds_finalize" type="submit" class="btn btn-success" value="Finalize" /> <a href="deposit_funds.php" class="btn btn-danger">Cancel</a></div>
    </div>
</form>