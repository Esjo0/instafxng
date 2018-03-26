<?php
$REQUEST_URI = strtok($_SERVER['REQUEST_URI'], '?');
$total_point_earned = $client_operation->get_loyalty_point($client_user_code);
?>
<ul class="fa-ul">
    <li><i class="fa-li fa fa-check-square-o icon-tune"></i>Enter the quantity that you want to fund. Minimum per order is $<?php echo FUNDING_MIN_VALUE; ?>.</li>
</ul>
<p><strong>Note:</strong> Your InstaForex Account Is<strong> Not Enrolled in INSTAFXNG LOYALTY PROGRAM AND REWARDS (ILPR)</strong>. Your Account Will Be Funded At The Rate Of &#8358; <span class="text-danger"><?php if(defined('NFUNDRATE')) { echo NFUNDRATE; } ?></span> / $1. <strong><a href="live_account.php" >CLICK HERE NOW</a> </strong> to Open an Account and Enrol It into The INSTAFXNG LOYALTY PROGRAM AND REWARDS (ILPR) To Fund Your Account At Discounted Rate Of &#8358; <span class="text-danger"><?php if(defined('IPLRFUNDRATE')) { echo IPLRFUNDRATE; } ?></span> / $1</p>
<p style="color: red">Kindly make sure you pay into the account details specified on the payment invoice.</p>
<form name="enter_amount" data-toggle="validator" class="form-horizontal" role="form" method="post" action="<?php echo $REQUEST_URI . '?p=niqty'; ?>">
    <input type="hidden" name="ifx_acct_no" value="<?php if(isset($ifx_acc_no)) { echo $ifx_acc_no; } ?>" />
    <div class="form-group">
        <div class="col-sm-9 col-lg-5">
            <label class="control-label" for="ifx_dollar_amount">Amount (&dollar;):</label>
            <input name="ifx_dollar_amount" type="text" class="form-control" id="ifx_dollar_amount" onBlur="checkInp()" required>
            <span class="help-block">Fill in the amount of Instaforex credit (e.g 100 or 10.6 )</span>
        </div>
    </div>
    
    <!-- Loyalty Points -->
    <div class="alert alert-info">
        <strong>Loyalty Point Rewards - <a href="" data-toggle="modal" data-target="#myModal">Click for Details!</a></strong><br/>
        Points Earned: <?php if(!is_null($total_point_earned)) { echo $total_point_earned; } else { echo 0; } ?><br/>
        <?php if(!is_null($total_point_earned) && $total_point_earned > 100) { ?>
        <br/><input style="width: 230px" name="point_claimed" type="text" class="form-control" id="point_claimed" placeholder="Enter points to redeem">
        <?php } ?>
    </div>
    
    <!-- -->
    
    <div class="form-group">
        <div class="col-sm-9"><input name="deposit_funds_qty_nonilpr" type="submit" class="btn btn-success" value="Calculate" /> <a href="deposit_funds.php" class="btn btn-danger">Cancel</a></div>
    </div>
</form>
<p>Available Payment METHODS includes:</p>
<!--<p><strong>Note:</strong> Available Payment METHODS includes:</p>-->
<ul class="fa-ul">
<!--    <li><i class="fa-li fa fa-check-square-o icon-tune"></i>Instant Card Payments (MasterCard / Visa / Verve)</li>-->
    <li><i class="fa-li fa fa-check-square-o icon-tune"></i>Internet Banking Transfer</li>
    <li><i class="fa-li fa fa-check-square-o icon-tune"></i>ATM Transfer</li>
    <li><i class="fa-li fa fa-check-square-o icon-tune"></i>Bank Transfer i.e. transferring over the counter at your bank</li>
    <li><i class="fa-li fa fa-check-square-o icon-tune"></i>Mobile Money Transfer</li>
    <li><i class="fa-li fa fa-check-square-o icon-tune"></i>Cash Deposit</li>
</ul>


<!-- Modal -->
<div id="myModal" class="modal fade" role="dialog">
    <div class="modal-dialog">
        <!-- Modal content-->
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h5 class="modal-title">Instafxng Loyalty Program and Reward</h5>
            </div>
            <div class="modal-body">
                <p class="text-danger">Earn points for funding and trading</p>
                <p>Each funding transaction you make earn you points in our loyalty program, also
                    every trade you execute earn you points in our loyalty program.</p>
                <p class="text-danger">Redeem points for Instaforex credit</p>
                <p>You can redeem your points when you have 100 points and above when making a funding
                transaction, if you have redeemable points a check box will appear to confirm that you
                want to redeem points.</p>
                <p><a target="_blank" href="loyalty.php" title="Instafxng Loyalty Progam and Rewards">Click here to learn more</a></p>
                
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal" title="Close">Close</button>
            </div>
        </div>
    </div>
</div>