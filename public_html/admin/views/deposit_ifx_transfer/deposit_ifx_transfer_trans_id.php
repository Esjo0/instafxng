<p>IFX Internal Transfer</p>
<ul class="fa-ul">
    <li><i class="fa-li fa fa-check-square-o icon-tune"></i>Enter a valid transaction ID below. Please note that this process is only
    valid with a transaction ID with status CONFIRMED.</li>
</ul>

<form data-toggle="validator" class="form-horizontal" role="form" method="post" action="">
    <div class="form-group">
        <label class="control-label col-sm-3" for="trans_id">Trans ID:</label>
        <div class="col-sm-9 col-lg-5">
            <input name="trans_id" type="text" class="form-control" id="trans_id" required>
        </div>
    </div>
    <div class="form-group">
        <div class="col-sm-offset-3 col-sm-9"><input name="deposit_ifx_transfer_trans_id" type="submit" class="btn btn-success" value="Submit" /></div>
    </div>
</form>