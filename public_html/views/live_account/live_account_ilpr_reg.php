<ul class="fa-ul">
    <li><i class="fa-li fa fa-check-square-o icon-tune"></i>Step 2: Enrol Your Account Into INSTAFXNG LOYALTY PROGRAM AND REWARDS (ILPR) for FREE</li>
</ul>

<form data-toggle="validator" class="form-horizontal" role="form" method="post" action="<?php echo $_SERVER['REQUEST_URI']; ?>">
    <div class="form-group">
        <label class="control-label col-sm-3" for="full_name">Full Name:</label>
        <div class="col-sm-9 col-lg-5">
            <input placeholder="First name and Surname" name="full_name" type="text" class="form-control" id="full_name" required>
        </div>
    </div>
    <div class="form-group">
        <label class="control-label col-sm-3" for="email">Email Address:</label>
        <div class="col-sm-9 col-lg-5">
            <input name="email" type="text" class="form-control" id="email" required>
        </div>
    </div>
    <div class="form-group">
        <label class="control-label col-sm-3" for="phone">Phone Number:</label>
        <div class="col-sm-9 col-lg-5">
            <input name="phone" type="text" class="form-control" id="phone" required>
        </div>
    </div>
    <div class="form-group">
        <label class="control-label col-sm-3" for="ifx_acct_no">Instaforex Account Number:</label>
        <div class="col-sm-9 col-lg-5">
            <input name="ifx_acct_no" type="text" class="form-control" id="ifx_acct_no" required>
        </div>
    </div>
    <p class="text-muted"  ><strong> Help <i class="fa fa-exclamation"></i></strong> Us Fight Spam.</p>
    <div class="form-group">
        <label class="control-label col-sm-3" for="recaptcha">&nbsp;</label>
        <div class="col-sm-9 col-lg-5 g-recaptcha" data-sitekey="6LcKDhATAAAAAF3bt-hC_fWA2F0YKKpNCPFoz2Jm"></div>
    </div>
    <div class="form-group">
        <div class="col-sm-offset-3 col-sm-9"><input name="live_account_ilpr_reg" type="submit" class="btn btn-success" value="Submit" /></div>
    </div>
</form>