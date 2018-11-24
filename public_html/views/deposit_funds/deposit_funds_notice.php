<?php

$REQUEST_URI = strtok($_SERVER['REQUEST_URI'], '?');
$user_code_encrypted = encrypt_ssl($client_user_code);

?>
<ul class="fa-ul">
    <li class="text-danger"><i class="fa-li fa fa-check-square-o icon-tune"></i><strong>ATTENTION</strong></li>
</ul>

<ol class="text-success">
    <li>Kindly make sure you pay into the account details specified on the payment invoice.</li>
    <li>All invoice generated is valid for 24 hours.</li>
    <li>Failure to pay into the account number specified in the invoice may lead to a delay in your funding.</li>
    <li>Delay may take up to 6 months or more.</li>
    <li>We do not accept third party transfers. Failure to heed to this will delay your funding for up to
    6 months.</li>
    <li>When making your payment via internet banking, mobile transfer or filling your teller for cash
        deposit include your transaction ID and account number in the REMARK column
        E.g (D151112268 - 123456)</li>
    <li>Instant card payment is back! You can now fund your account straight from your debit card at your convenience!</li>
</ol>

<hr />

<form data-toggle="validator" class="form-horizontal" role="form" method="post" action="<?php echo $REQUEST_URI . '?p=notice'; ?>">
    <input type="hidden" name="ifx_acct_no" value="<?php if(isset($ifx_acc_no)) { echo $ifx_acc_no; } ?>" />

    <div class="form-group">
        <div class="col-sm-offset-1 col-sm-10">
            <input name="deposit_funds_notice" type="submit" class="btn btn-success" value="Continue" />
        </div>
    </div>
</form>