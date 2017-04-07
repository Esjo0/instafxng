<?php 
$REQUEST_URI = strtok($_SERVER['REQUEST_URI'], '?');
?>
<ul class="fa-ul">
    <li><i class="fa-li fa fa-check-square-o icon-tune"></i>InstaForex Withdrawal - Enter the amount you want to withdraw and your phone password.</li>
</ul>

<form data-toggle="validator" name="enter_amount" class="form-horizontal" role="form" method="post" action="<?php echo $REQUEST_URI . '?p=qty'; ?>">
    <input type="hidden" name="ifx_acct_no" value="<?php if(isset($account_no)) { echo $account_no; } ?>" />
    <div class="form-group">
        <label class="control-label col-sm-3" for="ifx_dollar_amount">Amount (&dollar;):</label>
        <div class="col-sm-9 col-lg-5">
            <input name="ifx_dollar_amount" type="text" class="form-control" id="ifx_dollar_amount" onBlur="checkInp()" required>
            <span class="help-block">Instaforex credit to withdraw (e.g 100 or 10.6 )</span>
        </div>
    </div>
    <div class="form-group">
        <label class="control-label col-sm-3" for="phone_password">Phone Password:</label>
        <div class="col-sm-9 col-lg-5">
            <input name="phone_password" type="text" class="form-control" id="phone_password" value="" required>
        </div>
    </div>
    <div class="form-group">
        <div class="col-sm-offset-3 col-sm-9"><input name="withdraw_funds_qty" type="submit" class="btn btn-success" value="Withdraw" /> <a href="withdraw_funds.php" class="btn btn-danger">Cancel</a></div>
    </div>
</form>
<p><strong>Note:</strong> Minimum Withdrawal Value is $20</p>