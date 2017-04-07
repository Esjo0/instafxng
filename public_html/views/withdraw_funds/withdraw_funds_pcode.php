<?php
$REQUEST_URI = strtok($_SERVER['REQUEST_URI'], '?');
$user_code_encrypted = encrypt($user_code);
?>
<ul class="fa-ul">
    <li><i class="fa-li fa fa-check-square-o icon-tune"></i>Enter Pass Code and click the submit button</li>
</ul>

<form class="form-horizontal" role="form" method="post" action="<?php echo $REQUEST_URI . '?p=pcode'; ?>">
    <input type="hidden" name="uuc" value="<?php if(isset($user_code_encrypted)) { echo $user_code_encrypted; } ?>" />
    <input type="hidden" name="ifx_acct_no" value="<?php if(isset($account_no)) { echo $account_no; } ?>" />
    <input type="hidden" name="ifx_acct_type" value="<?php if(isset($type)) { echo $type; } ?>" />
    <input type="hidden" name="ifx_acct_status" value="<?php if(isset($status)) { echo $status; } ?>" />
    
    <input name="pass_code_dummy" type="password" class="form-control" id="pass_code" style="display:none">
    <div class="form-group">
        <label class="control-label col-sm-3" for="pass_code">Pass Code:</label>
        <div class="col-sm-9 col-lg-5">
            <input name="pass_code" type="password" class="form-control" id="pass_code">
            <span class="help-block">The Pass Code associated with your profile</span>
        </div>
    </div>
    <div class="form-group">
        <div class="col-sm-offset-3 col-sm-9">
            <input name="withdraw_funds_pcode" type="submit" class="btn btn-success" value="Submit" />
            <p>Can't remember your passcode? <a href="passcode_recovery.php?uuc=<?php if(isset($user_code_encrypted)) { echo $user_code_encrypted; } ?>">recover passcode here</a></p>
        </div>
    </div>
</form>