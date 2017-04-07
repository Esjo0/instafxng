<p>Payment notification is fast and can be completed in two easy steps</p>
<ul class="fa-ul">
    <li><i class="fa-li fa fa-check-square-o icon-tune"></i>Step 1: Enter your Transaction ID and click the submit button</li>
</ul>

<form class="form-horizontal" role="form" method="post" action="<?php echo $_SERVER['REQUEST_URI']; ?>">
    <div class="form-group">
        <label class="control-label col-sm-2" for="transaction_ID">Transaction ID:</label>
        <div class="col-sm-10 col-lg-5">
            <input name="transaction_ID" type="text" class="form-control" id="transaction_ID">
            <span class="help-block">It begins with IFX e.g. IFX1234567890</span>
        </div>
    </div>
    <div class="form-group">
        <div class="col-sm-offset-2 col-sm-10"><input name="deposit_notify_trans_id" type="submit" class="btn btn-success" value="Submit" /></div>
    </div>
</form>