<p>Please find below your payment summary, choose your preferred method of payment and click the proceed to pay
    button to make payment.</p>
<form data-toggle="validator" class="form-horizontal" role="form" method="post" action="">
    <input name="course_no" type="hidden" value="<?php if(isset($course_id_encrypted)) { echo $course_id_encrypted; } ?>">
    <div class="form-group">
        <label class="control-label col-sm-3" for="course_name">Course:</label>
        <div class="col-sm-9 col-lg-5">
            <input name="course_name" type="text" class="form-control" id="course_name" value="<?php if(isset($course_name)) { echo $course_name; } ?>" readonly="readonly">
        </div>
    </div>
    <div class="form-group">
        <label class="control-label col-sm-3" for="client_name">Client Name:</label>
        <div class="col-sm-9 col-lg-5">
            <input name="client_name" type="text" class="form-control" id="client_name" value="<?php if(isset($client_name)) { echo $client_name; } ?>" readonly="readonly">
        </div>
    </div>
    <div class="form-group">
        <label class="control-label col-sm-3" for="client_email">Email Address:</label>
        <div class="col-sm-9 col-lg-5">
            <input name="client_email" type="text" class="form-control" id="client_email" value="<?php if(isset($client_email)) { echo $client_email; } ?>" readonly="readonly">
        </div>
    </div>
    <div class="form-group">
        <label class="control-label col-sm-3" for="amount">Amount (&#8358;):</label>
        <div class="col-sm-9 col-lg-5">
            <input name="amount" type="text" class="form-control" id="amount" value="<?php if(isset($course_cost)) { echo number_format($course_cost, 2, ".", ","); } ?>" readonly="readonly">
        </div>
    </div>
    <div class="form-group">
        <label class="control-label col-sm-3" for="stamp_duty">CBN Stamp Duty (&#8358;):</label>
        <div class="col-sm-9 col-lg-5">
            <input name="stamp_duty" type="text" class="form-control" id="stamp_duty" value="<?php echo number_format($stamp_duty, 2, ".", ","); ?>" readonly="readonly">
        </div>
    </div>
    <hr />
    <div class="form-group">
        <label class="control-label col-sm-3" for="total_payable">Total Payable:</label>
        <div class="col-sm-9 col-lg-5">
            <input name="total_payable" type="hidden" class="form-control" id="total_payable" value="<?php if(isset($total_payable)) { echo number_format($total_payable, 2, ".", ","); } ?>" readonly="readonly">
            <p style="font-size: 1.6em; padding: 0; color: green;"><strong>&#8358; <?php if(isset($total_payable)) { echo number_format($total_payable, 2, ".", ","); } ?></strong></p>
        </div>
    </div>
    <div class="form-group">
        <label class="control-label col-sm-3" for="payment_method">Choose Payment Method:</label>
        <div class="col-sm-9 col-lg-5">
            <div class="radio"><label><input name="pay_type" type="radio" value="1" checked="checked"> Instant Card Payments (MasterCard / Visa / Verve)
                    <br /><strong>(+ Card Processing: &#8358; <?php echo number_format($card_processing, 2, ".", ","); ?>)</strong></label></div>
            <div class="radio"><label><input name="pay_type" type="radio" value="2"> Internet Banking Transfer</label></div>
            <div class="radio"><label><input name="pay_type" type="radio" value="3"> ATM Transfer</label></div>
            <div class="radio"><label><input name="pay_type" type="radio" value="4"> Bank Transfer</label></div>
            <div class="radio"><label><input name="pay_type" type="radio" value="5"> Mobile Money Transfer</label></div>
            <div class="radio"><label><input name="pay_type" type="radio" value="6"> Cash Deposit</label></div>
            <div class="radio"><label><input name="pay_type" type="radio" value="9"> USSD Transfer</label></div>
        </div>
    </div>
    <div class="form-group">
        <div class="col-sm-offset-3 col-sm-9"><input name="course_payment_summary" type="submit" class="btn btn-success" value="Proceed To Pay" /></div>
    </div>
</form>