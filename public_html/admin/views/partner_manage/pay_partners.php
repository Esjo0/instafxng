<p>Fill the form below to pay a partner.</p>

<hr/><br/>

<form data-toggle="validator" class="form-horizontal" role="form" method="post" action="<?php echo $REQUEST_URI; ?>">
    <input name="user_code" type="hidden" value="<?php if(isset($user_code)) { echo $user_code; } ?>">
    <input name="partner_code" type="hidden" value="<?php if(isset($partner_code)) { echo $partner_code; } ?>">
    <div class="form-group">
        <label class="control-label col-sm-3" for="">Username:</label>
        <div class="col-sm-9 col-lg-5">
            <?php echo $username; ?>
        </div>
    </div>
    <div class="form-group">
        <label class="control-label col-sm-3" for="">Bank Name:</label>
        <div class="col-sm-9 col-lg-5">
            <?php echo $bankname; ?>
        </div>
    </div>
        <div class="form-group">
        <label class="control-label col-sm-3" for="">Account Name:</label>
        <div class="col-sm-9 col-lg-5">
            <?php echo $bankacctname; ?>
        </div>
    </div>
	<div class="form-group">
        <label class="control-label col-sm-3" for="partner_code">Account No:</label>
        <div class="col-sm-9 col-lg-5">
            <input name="amount2pay" type="text" id="amount2pay" value="<?php echo $bankacctno; ?>" class="form-control" style="width:60%" size="20">
        </div>
    </div>
	
    <div class="form-group">
        <label class="control-label col-sm-3" for="ifxaccount">Amount Due (=N=):</label>
        <div class="col-sm-9 col-lg-5">
            <input name="amountdue" type="text" class="form-control" id="amountdue" style="width:60%" value="<?php echo $nairval; ?>" size="20" readonly>
        </div>
    </div>        
    <div class="form-group">
        <label class="control-label col-sm-3" for="partner_code">Amount to be Paid:</label>
        <div class="col-sm-9 col-lg-5">
            <input name="amount2pay" type="text" id="amount2pay" class="form-control" style="width:60%" size="20">
        </div>
    </div>
    <div class="form-group">
        <label class="control-label col-sm-3" for="partner_code">Comments:</label>
        <div class="col-sm-9 col-lg-5">
            <input name="comment" type="text" id="comment" class="form-control" style="width:60%" size="20">
        </div>
    </div>

    <div class="form-group">
        <div class="col-sm-offset-3 col-sm-9">
            <button type="button" data-target="#add-details-confirm" data-toggle="modal" class="btn btn-success"><i class="fa fa-send-o fa-fw"></i> Approve Payment</button>
        </div>
    </div>
    
    
    <!-- Modal - confirmation boxes -->
    <div id="add-details-confirm" tabindex="-1" role="dialog" aria-hidden="true" class="modal fade">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" data-dismiss="modal" aria-hidden="true"
                        class="close">&times;</button>
                    <h4 class="modal-title">Partner's  Payment Confirmation</h4>
                </div>
                <div class="modal-body">Are you sure you want to make this payment?</div>
                <div class="modal-footer">
                    <input name="update_partn_details" type="submit" class="btn btn-success" value="Submit" />
                    <button type="submit" name="decline" onClick="window.close();" data-dismiss="modal" class="btn btn-danger">Close !</button>
                </div>
            </div>
        </div>
    </div>
</form>