<ul class="fa-ul">
    <li><i class="fa-li fa fa-check-square-o icon-tune"></i>Step 2: Enrol Your Account Into INSTAFXNG LOYALTY PROGRAM AND REWARDS (ILPR) for FREE and select a bonus package</li>
</ul>

<form data-toggle="validator" class="form-horizontal" role="form" method="post" action="<?php echo $_SERVER['REQUEST_URI']; ?>">
    <div class="form-group">
        <label class="control-label col-sm-3" for="full_name">Full Name:</label>
        <div class="col-sm-9 ">
            <input placeholder="First name and Surname" name="full_name" type="text" class="form-control" id="full_name" required>
        </div>
    </div>
    <div class="form-group">
        <label class="control-label col-sm-3" for="email">Email Address:</label>
        <div class="col-sm-9 ">
            <input name="email" type="text" class="form-control" id="email" required>
        </div>
    </div>
    <div class="form-group">
        <label class="control-label col-sm-3" for="phone">Phone Number:</label>
        <div class="col-sm-9 ">
            <input name="phone" type="text" class="form-control" id="phone" required>
        </div>
    </div>
    <div class="form-group">
        <label class="control-label col-sm-3" for="ifx_acct_no">Instaforex Account Number:</label>
        <div class="col-sm-9 ">
            <input name="ifx_acct_no" type="text" class="form-control" id="ifx_acct_no" required>
        </div>
    </div>
    <div class="form-group">
        <label class="control-label col-sm-3" for="bonus_package">Bonus Package:</label>
        <div class="col-sm-9 ">
            <textarea rows="1" class="form-control" required disabled><?php echo $package_details['bonus_title']; ?></textarea>
        </div>
    </div>
    <?php if($package_details['type'] == 1): ?>
        <div class="form-group">
            <label class="control-label col-sm-3" for="bonus_package">Deposit Order Transaction ID:</label>
            <div class="col-sm-9 ">
                <input name="transaction_id" value="" type="text" class="form-control" id="ifx_acct_no" required>
            </div>
        </div>
    <?php endif; ?>
    <div class="form-group">
        <label class="control-label col-sm-3">Terms Of Use:</label>
        <div class="col-sm-9 ">
            <div style="border: 1px solid #e5e5e5; height: 200px; overflow: auto; padding: 10px;">
                <p class="text-justify">This signal is provided as is by InstaForex Nigeria (www.InstaFxNg.com) and though we have taken
                    utmost care and used the best available tools to generate these signals, by using it you agree
                    to release us from every liability arising directly or indirectly from the use of same signals.</p>
                <p class="text-justify">Although the source of the signals has been producing about 85% accurate signals, we will advice
                    that you should try it on your demo account before you use them on your live account.</p>
                <p class="text-justify">Remember Online Forex carries a degree of risk so do not use borrowed or money you cannot afford
                    to lose to trade Forex. Always make sure you apply money management, high lot (volume) can be
                    dangerous. Never enter a trade with more than 10% of your account size.</p>
            </div>
            <div class="checkbox"><label for="agree"><input type="checkbox" name="" value="" id="agree" required/> I agree with the terms and conditions.</label></div>
        </div>
    </div>

    <input name="bonus_code" value="<?php echo $package_details['bonus_code']; ?>" type="hidden" class="form-control" required>


    <div class="form-group">
        <label class="control-label col-sm-3" for="recaptcha">&nbsp;</label>
        <div class="col-sm-9 col-lg-5 g-recaptcha" data-sitekey="6LcKDhATAAAAAF3bt-hC_fWA2F0YKKpNCPFoz2Jm"></div>
    </div>
    <div class="form-group">
        <div class="col-sm-offset-3 col-sm-9"><input name="live_account_ilpr_reg" type="submit" class="btn btn-success" value="Submit" /></div>
    </div>
</form>