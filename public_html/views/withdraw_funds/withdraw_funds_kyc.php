<?php 
$REQUEST_URI = strtok($_SERVER['REQUEST_URI'], '?');
?>
<ul class="fa-ul">
    <li><i class="fa-li fa fa-check-square-o icon-tune"></i>Fill the form below to proceed</li>
</ul>

<form class="form-horizontal" role="form" method="post" action="<?php echo $REQUEST_URI . '?p=kyc'; ?>">
    <input type="hidden" name="ifx_acct_no" value="<?php if(isset($account_no)) { echo $account_no; } ?>" />
    <div class="form-group">
        <label class="control-label col-sm-3" for="full_name">Full Name:</label>
        <div class="col-sm-9 col-lg-5">
            <input name="full_name" type="text" class="form-control" id="full_name" required="required">
        </div>
    </div>
    <div class="form-group">
        <label class="control-label col-sm-3" for="email">Email Address:</label>
        <div class="col-sm-9 col-lg-5">
            <input name="email" type="text" class="form-control" id="email" required="required">
        </div>
    </div>
    <div class="form-group">
        <label class="control-label col-sm-3" for="phone">Phone Number:</label>
        <div class="col-sm-9 col-lg-5">
            <input name="phone" type="text" class="form-control" id="phone" data-minlength="11" maxlength="11" required="required">
            <div class="help-block">Example - 08031234567</div>
        </div>
    </div>
    <div class="form-group">
        <div class="col-sm-offset-3 col-sm-9"><input name="withdraw_funds_kyc" type="submit" class="btn btn-success" value="Submit" /></div>
    </div>
</form>