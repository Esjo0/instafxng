<ul class="fa-ul">
    <li>Order Submitted</li>
</ul>
<p>Your transaction details have been submitted. Please proceed to make payment/transfer into the
    account details below.</p>
<ol>
    <li>Guaranty Trust Bank (GTB)<br />
        Account Name: Instant Web-Net Technologies Ltd<br />
        Account Number: 0174516696</li>

    <?php
        switch($pay_type) {
            case '2':
                $msg = "<li>Please include your Transaction ID <strong>(" . $trans_id . ") ONLY </strong>in the space for remarks.</li>";
                echo $msg;
                break;
            case '4':
                $msg = "<li>Please include your Transaction ID <strong>(" . $trans_id . ")</strong> before making the transfer.</li>";
                echo $msg;
                break;
            case '5':
                $msg = "<li>Please include your Transaction ID <strong>(" . $trans_id . ") ONLY </strong>in the space for remarks.</li>";
                echo $msg;
                break;
            case '6':
                $msg = "<li>Please include your Transaction ID <strong>(" . $trans_id . ")</strong> on the teller.</li>";
                echo $msg;
                break;
            default:
                $msg = "<li>Please include your Transaction ID <strong>(" . $trans_id . ")</strong>.</li>";
                echo $msg;
        }
    ?>
</ol>

<p>Your transaction ID for this order is <strong>(<?php echo $trans_id; ?>)</strong>.
    After making the payment, Visit <a href="https://instafxng.com/fxacademy">https://instafxng.com/fxacademy</a> and click on PAYMENT NOTIFICATION to
    notify us of your payment. You will need to input your transaction ID in the column and submit so we
    can be notified of your payment and process it accordingly.
</p>
<p>Your payment will be completed within 5 to 30 minutes.</p>
<hr />

<p>NOTE:</p>
<ul>
    <li>Third party payments are not allowed.</li>
    <li>Your only be given access to the course you paid for after you have completed payment notification
        as advised above.</li>
</ul>

<?php if($pay_type == '9') { // i.e. clients that want to use USSD, guide them ?>

<p>BANK USSD CODES - This works with phone numbers registered with your account.</p>
<ul>
    <li>Guaranty Trust Bank (GTB): *737# </li>
    <li>Fidelity Bank: *770#</li>
    <li>First Bank: *894#</li>
    <li>Sterling Bank: *822#</li>
    <li>Skye Bank: *833#</li>
    <li>United Bank for Africa (UBA): *919#</li>
    <li>EcoBank: *326#</li>
    <li>Zenith Bank: *966#</li>
    <li>Stanbic Bank: *909#</li>
    <li>Access Bank Bank: *901#</li>
    <li>Wema Bank: *945#</li>
    <li>Diamond Bank: *710#</li>
    <li>Unity Bank: *389*215#</li>
    <li>Heritage Bank: *322*030#</li>
    <li>KeyStone Bank: *322*082#</li>
    <li>Union Bank: *826#</li>
    <li>FCMB: *329#</li>
</ul>

<?php } ?>

<p>If you have any questions, please contact our <a href="https://instafxng.com/contact_info.php">support desk</a>
    And please mention your transaction ID <?php echo $trans_id; ?> when you call.</p>

<p>Thank you for using our services.</p>