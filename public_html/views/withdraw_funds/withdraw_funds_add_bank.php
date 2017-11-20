<?php 
$REQUEST_URI = strtok($_SERVER['REQUEST_URI'], '?');
?>
<ul class="fa-ul">
    <li><i class="fa-li fa fa-check-square-o icon-tune"></i>Please supply your bank account, withdrawals will be paid directly to it</li>
</ul>

<form data-toggle="validator" class="form-horizontal" role="form" method="post" action="<?php echo $REQUEST_URI . '?p=bank'; ?>">
    <input type="hidden" name="ifx_acct_no" value="<?php if(isset($account_no)) { echo $account_no; } ?>" />
    <div class="form-group">
        <label class="control-label col-sm-3" for="bank_name">Bank Name:</label>
        <div class="col-sm-9 col-lg-5">
            <select name="bank_name" class="form-control" id="bank_name" required>
                <option value="" selected>Select Bank</option>
                <?php foreach($all_banks as $key => $value) { ?>
                    <option value="<?php echo $value['bank_id']; ?>"><?php echo $value['bank_name']; ?></option>
                <?php } ?>
            </select>
        </div>
    </div>
    <div class="form-group">
        <label class="control-label col-sm-3" for="bank_acct_name">Account Name:</label>
        <div class="col-sm-9 col-lg-5">
            <input name="bank_acct_name" type="text" class="form-control" id="bank_acct_name" required>
        </div>
    </div>
    <div class="form-group">
        <label class="control-label col-sm-3" for="bank_acct_number">Account Number:</label>
        <div class="col-sm-9 col-lg-5">
            <input name="bank_acct_number" type="text" class="form-control" id="bank_acct_number" required maxlength="10">
            <span class="help-block">10 digit NUBAN account number</span>
        </div>
    </div>
    <?php if($submit_btn) { ?>
    <div class="form-group">
        <div class="col-sm-offset-3 col-sm-9"><input name="withdraw_funds_add_bank" type="submit" class="btn btn-success" value="Submit" /></div>
    </div>
    <?php } ?>
</form>