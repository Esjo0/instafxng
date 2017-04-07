<p><strong>Order Not Completed</strong></p>
<p>Your payment was not successful. Something went wrong. Did you terminate your payment? <a href="deposit.php">Click here</a> to re-start the deposit process or
    contact our <a href="contact_info.php">support department</a> for assistance.</p>
<div class="alert alert-danger">
    <p>Your transaction was not successful</p>
    <p>
        Reason: <?php echo $gtpay_tranx_status_msg; ?><br/>
        Transaction ID: <?php echo $trans_id; ?><br/>
        <a href="deposit.php">Click here</a> to try again
    </p>
</div>