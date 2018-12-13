<?php
//$trans_id_encrypted = dec_enc('encrypt', $client_trans_id);
$total_payable_card_kobo = $total_payable_card * 100; // Convert to Kobo, required by GTPay

// GTPay hashing instruction using hash id provide by GTPay
$to_hash = "4745" . $trans_id . $total_payable_card_kobo . "566" . "$client_email" . "https://instafxng.com/fxacademy/course_payment_complete.php?trans_id=" .  $trans_id . "804726DAB9A13B6A8BD1473A0EF6D9DA8D839DFADC9592D5AD3D0EC0A8E8866D927911F77C2B0162981068D0FA0BEEE27E29C4D02C4474583DCE385BE88CA7B8";
$hashed = hash("SHA512", $to_hash);

?>
<ul class="fa-ul">
    <li>Instant Card Payments (MasterCard / Visa / Verve)</li>
</ul>
<p>You are about to make payment with your Card, please get your card ready then click the 'Proceed' button below.
You will be redirected to the payment gateway.</p>

<form name="submit2gtpay_form" action="https://ibank.gtbank.com/GTPay/Tranx.aspx" target="_self" method="post">
    <input type="hidden" name="gtpay_mert_id" value="4745" />
    <input type="hidden" name="gtpay_tranx_id" value="<?php echo $trans_id; ?>" />
    <input type="hidden" name="gtpay_tranx_amt" value="<?php echo $total_payable_card_kobo; ?>" />
    <input type="hidden" name="gtpay_tranx_curr" value="566" />
    <input type="hidden" name="gtpay_cust_id" value="<?php echo $client_email; ?>" />
    <input type="hidden" name="gtpay_cust_name" value="<?php echo $client_name; ?>" />
    <input type="hidden" name="gtpay_tranx_memo" value="<?php echo $trans_id . "-" . $client_name . "-" . $client_email; ?>" />
    <input type="hidden" name="gtpay_echo_data" value="" />
    <input type="hidden" name="gtpay_gway_name" value="webpay" />
    <input type="hidden" name="gtpay_hash" value="<?php echo $hashed; ?>" />
    <input type="hidden" name="gtpay_tranx_noti_url" value="https://instafxng.com/fxacademy/course_payment_complete.php?trans_id=<?php echo $trans_id; ?>" />
    
    <input class="btn btn-success" type="submit" value="Proceed" name="btnSubmit"/>
</form>