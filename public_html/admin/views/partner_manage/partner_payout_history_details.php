<p></p>

<hr/><br/>

<form data-toggle="validator" class="form-horizontal" role="form" method="post" action="<?php echo $REQUEST_URI; ?>">
    <input name="user_code" type="hidden" value="<?php if(isset($user_code)) { echo $user_code; } ?>">
    <input name="email_address" type="hidden" value="<?php if(isset($client_email)) { echo $client_email; } ?>">
    <div class="form-group">
        <label class="control-label col-sm-3" for="partner_code">Partner Code:</label>
        <div class="col-sm-9 col-lg-5">
            <input name="partner_code" type="text" required class="form-control" id="partner_code" value="<?php if(isset($partner_code)) { echo $partner_code; } ?>" readonly>
        </div>
    </div>
    <div class="form-group">
        <label class="control-label col-sm-3" for="ifxaccount">IFX Account:</label>
        <div class="col-sm-9 col-lg-5">
            <input name="ifxaccount" type="text" required class="form-control" id="ifxaccount" value="<?php if(isset($account_id)) { echo $settlement_ifxaccount_id; } ?>" readonly>
        </div>
    </div>
    <div class="form-group">
        <label class="control-label col-sm-3" for="amount">Amount:</label>
        <div class="col-sm-9 col-lg-5">
            <input name="amount" type="text" required class="form-control" id="amount" value="<?php if(isset($amount)) { echo $amount; } ?>" readonly>
        </div>
    </div>    
    <div class="form-group">
        <label class="control-label col-sm-3" for="status">Comment:</label>
        <div class="col-sm-9 col-lg-5">
            <?php if(isset($comment)) { echo $comment; } ?>
        </div>
    </div>
    
</form>