<ul class="fa-ul">
    <li><i class="fa-li fa fa-check-square-o icon-tune"></i>Enter your Instaforex Account Number and click the submit button</li>
</ul>

<form data-toggle="validator" class="form-horizontal" role="form" method="post" action="<?php echo $_SERVER['REQUEST_URI']; ?>">
    <input name="pass_code_dummy" type="password" class="form-control" id="pass_code" style="display:none">
    <div class="form-group">
        <label class="control-label col-sm-3" for="ifx_acct_no">Instaforex Account:</label>
        <div class="col-sm-9 col-lg-5">
            <input name="ifx_acct_no" type="text" class="form-control" id="ifx_acct_no" required>
        </div>
    </div>
    <div class="form-group">
        <label class="control-label col-sm-3" for="pass_code">Pass Code:</label>
        <div class="col-sm-9 col-lg-5">
            <input name="pass_code" type="password" class="form-control" id="pass_code" required>
            <span class="help-block">The Pass Code associated with your profile</span>
        </div>
    </div>
    <div class="form-group">
        <div class="col-sm-offset-3 col-sm-9"><input name="verify_account_ifx_acct" type="submit" class="btn btn-success" value="Submit" /></div>
    </div>
</form>