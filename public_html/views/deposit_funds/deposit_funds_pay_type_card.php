<?php
//$trans_id_encrypted = encrypt($client_trans_id);
$client_naira_total_kobo = $client_naira_total * 100; // Convert to Kobo, required by GTPay

// GTPay hashing instruction using hash id provide by GTPay
$to_hash = "4745" . $client_trans_id . $client_naira_total_kobo . "566" . "$client_account" . "https://instafxng.com/paygtpaycomplete.php?trans_id=" .  $client_trans_id . "804726DAB9A13B6A8BD1473A0EF6D9DA8D839DFADC9592D5AD3D0EC0A8E8866D927911F77C2B0162981068D0FA0BEEE27E29C4D02C4474583DCE385BE88CA7B8";
$hashed = hash("SHA512", $to_hash);

?>
<ul class="fa-ul">
    <li><i class="fa-li fa fa-check-square-o icon-tune"></i>Instant Card Payments (MasterCard / Visa / Verve)</li>
</ul>
<p>You are about to make payment with your Card, please get your card ready then click the 'Proceed' button below.
You will be redirected to the payment gateway.</p>

<form name="submit2gtpay_form" action="https://ibank.gtbank.com/GTPay/Tranx.aspx" target="_self" method="post">
    <input type="hidden" name="gtpay_mert_id" value="4745" />
    <input type="hidden" name="gtpay_tranx_id" value="<?php echo $client_trans_id; ?>" />
    <input type="hidden" name="gtpay_tranx_amt" value="<?php echo $client_naira_total_kobo; ?>" />
    <input type="hidden" name="gtpay_tranx_curr" value="566" />
    <input type="hidden" name="gtpay_cust_id" value="<?php echo $client_account; ?>" />
    <input type="hidden" name="gtpay_cust_name" value="<?php echo $client_full_name; ?>" />
    <input type="hidden" name="gtpay_tranx_memo" value="<?php echo $client_trans_id . "-" . $client_full_name . "-" . $client_account; ?>" />
    <input type="hidden" name="gtpay_echo_data" value="" />
    <input type="hidden" name="gtpay_gway_name" value="webpay" />
    <input type="hidden" name="gtpay_hash" value="<?php echo $hashed; ?>" />
    <input type="hidden" name="gtpay_tranx_noti_url" value="https://instafxng.com/paygtpaycomplete.php?trans_id=<?php echo $client_trans_id; ?>" />
    
    <input class="btn btn-success" type="submit" value="Proceed" name="btnSubmit"/>
</form>