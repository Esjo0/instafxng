<?php 
$REQUEST_URI = strtok($_SERVER['REQUEST_URI'], '?');
?>
<p>Withdrawing from your Instaforex Account is very fast and simple</p>
<ul class="fa-ul">
    <li><i class="fa-li fa fa-check-square-o icon-tune"></i>Enter your Instaforex Account Number and click the submit button</li>
</ul>

<form class="form-horizontal" role="form" method="post" action="<?php echo $REQUEST_URI . '?p=acct'; ?>">
    <div class="form-group">
        <label class="control-label col-sm-3" for="ifx_acct_no">Instaforex Account:</label>
        <div class="col-sm-9 col-lg-5">
            <input name="ifx_acct_no" type="text" class="form-control" id="ifx_acct_no">
        </div>
    </div>
    <div class="form-group">
        <div class="col-sm-offset-3 col-sm-9"><input name="withdraw_funds_ifx_acct" type="submit" class="btn btn-success" value="Submit" /></div>
    </div>
</form>