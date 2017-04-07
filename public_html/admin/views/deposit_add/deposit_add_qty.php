<?php
$user_code_encrypted = encrypt($user_code);
?>
<ul class="fa-ul">
    <li><i class="fa-li fa fa-check-square-o icon-tune"></i>Enter the quantity that you want to fund..</li>
</ul>

<form data-toggle="validator" class="form-horizontal" role="form" method="post" action="<?php echo $_SERVER['REQUEST_URI']; ?>">
    <input type="hidden" name="ifx_acct_no" value="<?php if(isset($account_no)) { echo $account_no; } ?>" />

    <div class="form-group">
        <label class="control-label col-sm-3" for="client_name">Client Name:</label>
        <div class="col-sm-9 col-lg-5">
            <input name="client_name" type="text" class="form-control" id="client_name" value="<?php if(isset($client_full_name)) { echo $client_full_name; } ?>" readonly="readonly">
        </div>
    </div>

    <div class="form-group">
        <label class="control-label col-sm-3" for="client_email">Client Email:</label>
        <div class="col-sm-9 col-lg-5">
            <input name="client_email" type="text" class="form-control" id="client_email" value="<?php if(isset($client_email)) { echo $client_email; } ?>" readonly="readonly">
        </div>
    </div>

    <div class="form-group">
        <label class="control-label col-sm-3" for="client_phone">Client Phone:</label>
        <div class="col-sm-9 col-lg-5">
            <input name="client_phone" type="text" class="form-control" id="client_phone" value="<?php if(isset($client_phone_number)) { echo $client_phone_number; } ?>" readonly="readonly">
        </div>
    </div>

    <div class="form-group">
        <label class="control-label col-sm-3" for="naira_amount">Amount (&#8358;):</label>
        <div class="col-sm-9 col-lg-5">
            <input name="naira_amount" type="text" class="form-control" id="naira_amount" required>
            <span class="help-block">Fill in the amount to fund in Naira</span>
        </div>
    </div>

    <!-- Loyalty Points -->
    <div class="form-group">
        <label class="control-label col-sm-3" for="point_claimed">Loyalty Point:</label>
        <div class="col-sm-9 col-lg-5">
            <div class="alert alert-info">
                Points Earned: <?php if ($total_point_earned) { echo $total_point_earned; } else { echo 0; } ?><br/>
                <?php if($total_point_earned > 100) { ?>
                <br/><input style="width: 230px" name="point_claimed" type="text" class="form-control" id="point_claimed" placeholder="Enter points to redeem">
                <?php } ?>
            </div>
        </div>
    </div>
    <!-------------------->

    <div class="form-group">
        <label class="control-label col-sm-3" for="deposit_origin">Status:</label>
        <div class="col-sm-9 col-lg-5">
            <div class="radio">
                <label><input type="radio" name="deposit_origin" value="2" required>Diamond Office</label>
            </div>
            <div class="radio">
                <label><input type="radio" name="deposit_origin" value="3" required>Ikota Office</label>
            </div>
        </div>
    </div>

    <div class="form-group">
        <label class="control-label col-sm-3" for="remarks">Your Remark:</label>
        <div class="col-sm-9 col-lg-5"><textarea name="remarks" id="message" rows="3" class="form-control" placeholder="Enter your remark" required></textarea></div>
    </div>
    
    <div class="form-group">
        <div class="col-sm-offset-3 col-sm-9"><input name="deposit_add_qty" type="submit" class="btn btn-success" value="Calculate" /> <a href="deposit_add.php" class="btn btn-danger">Cancel</a></div>
    </div>
</form>