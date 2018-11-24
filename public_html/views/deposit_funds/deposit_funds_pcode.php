<?php
$REQUEST_URI = strtok($_SERVER['REQUEST_URI'], '?');
$user_code_encrypted = encrypt_ssl($client_user_code);
?>
<ul class="fa-ul">
    <li><i class="fa-li fa fa-check-square-o icon-tune"></i>Enter Pass Code and click the submit button</li>
</ul>

<form data-toggle="validator" class="form-horizontal" role="form" method="post" action="<?php echo $REQUEST_URI . '?p=pcode'; ?>">
    <input type="hidden" name="ifx_acct_no" value="<?php if(isset($ifx_acc_no)) { echo $ifx_acc_no; } ?>" />
    
    <input name="pass_code_dummy" type="password" class="form-control" id="pass_code" style="display:none">
    <div class="form-group">
        <label class="control-label col-sm-3" for="pass_code">Pass Code:</label>
        <div class="col-sm-9 col-lg-5">
            <input name="pass_code" type="password" class="form-control" id="pass_code" required>
            <span class="help-block">The Pass Code associated with your profile</span>
        </div>
    </div>
    <div class="form-group">
        <div class="col-sm-offset-3 col-sm-9">
            <input name="deposit_funds_pcode" type="submit" class="btn btn-success" value="Submit" />
            <p>Can't remember your passcode? <a href="passcode_recovery.php">recover passcode here</a></p>
        </div>
    </div>
</form>