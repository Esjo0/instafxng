<ul class="fa-ul">
    <li><i class="fa-li fa fa-check-square-o icon-tune"></i>Step 2: Fill your payment details and click the submit button. All fields are required.</li>
</ul>

<form data-toggle="validator" class="form-horizontal" role="form" method="post" action="<?php echo $_SERVER['REQUEST_URI']; ?>">
    <input name="trans_id" type="hidden" value="<?php if(isset($trans_id)) { echo $trans_id; } ?>">
    <div class="form-group">
        <label class="control-label col-sm-3" for="transaction">Transaction ID:</label>
        <div class="col-sm-9 col-lg-5"><input name="transaction" type="text" value="<?php if(isset($trans_id)) { echo $trans_id; } ?>" class="form-control" id="transaction" disabled></div>
    </div>
    <div class="form-group">
        <label class="control-label col-sm-3" for="full_name">Full Name:</label>
        <div class="col-sm-9 col-lg-5"><input name="full_name" type="text" value="<?php if(isset($full_name)) { echo $full_name; } ?>" class="form-control" id="full_name" disabled></div>
    </div>
    <div class="form-group">
        <label class="control-label col-sm-3" for="ifx_account">IFX Account Number:</label>
        <div class="col-sm-9 col-lg-5"><input name="ifx_account" type="text" value="<?php if(isset($ifx_acct_no)) { echo $ifx_acct_no; } ?>" class="form-control" id="ifx_account" disabled></div>
    </div>
    <div class="form-group">
        <label class="control-label col-sm-3" for="dollar_ordered">Amount Ordered (&dollar;):</label>
        <div class="col-sm-9 col-lg-5"><input name="dollar_ordered" type="text" value="<?php if(isset($dollar_ordered)) { echo $dollar_ordered; } ?>" class="form-control" id="dollar_ordered" disabled></div>
    </div>
    <div class="form-group">
        <label class="control-label col-sm-3" for="total_payable">Total Payable (&#8358;):</label>
        <div class="col-sm-9 col-lg-5"><input name="total_payable" type="text" value="<?php if(isset($naira_total_payable)) { echo number_format($naira_total_payable, 2, ".", ","); } ?>" class="form-control" id="dollar_ordered" disabled></div>
    </div>
    <div class="form-group">
        <label class="control-label col-sm-3" for="order_date">Order Date:</label>
        <div class="col-sm-9 col-lg-5"><input name="order_date" type="text" value="<?php if(isset($created)) { echo datetime_to_text($created) . " (" . time_since($created) . ")"; } ?>" class="form-control" id="dollar_ordered" disabled></div>
    </div>
    
    <?php if(!$expired) { // allow to notify if transaction has not expired ?>
    <div class="form-group">
        <label class="control-label col-sm-3" for="pay_method">Payment Method:</label>
        <div class="col-sm-9 col-lg-5">
            <select name="pay_method" class="form-control" id="pay_method" required>
                <option value="">---Select Type---</option>
                <option value="2" <?php if(isset($client_pay_method) && $client_pay_method == '2') { echo "selected='selected'"; } ?>>Internet Transfer</option>
                <option value="3" <?php if(isset($client_pay_method) && $client_pay_method == '3') { echo "selected='selected'"; } ?>>ATM Transfer</option>
                <option value="4" <?php if(isset($client_pay_method) && $client_pay_method == '4') { echo "selected='selected'"; } ?>>Bank Transfer</option>
                <option value="5" <?php if(isset($client_pay_method) && $client_pay_method == '5') { echo "selected='selected'"; } ?>>Mobile Money Transfer</option>
                <option value="6" <?php if(isset($client_pay_method) && $client_pay_method == '6') { echo "selected='selected'"; } ?>>Cash Deposit</option>
                <option value="9" <?php if(isset($client_pay_method) && $client_pay_method == '9') { echo "selected='selected'"; } ?>>USSD Transfer</option>
<!--                <option value="8" --><?php //if(isset($client_pay_method) && $client_pay_method == '8') { echo "selected='selected'"; } ?><!-->- Not Listed -</option>-->
            </select>
        </div>
    </div>
    <div class="form-group">
        <label class="control-label col-sm-3" for="pay_date">Payment Date:</label>
        <div class="col-sm-9 col-lg-5">
            <div class='input-group date' id='datetimepicker'>
                <input name="pay_date" type="text" class="form-control" id='datetimepicker2' required>
                <span class="input-group-addon"><span class="glyphicon glyphicon-calendar"></span></span>
            </div>
            <span class="help-block">Format: (YYYY-MM-DD) e.g. 2016-12-25</span>
        </div>
        <script type="text/javascript">
            $(function () {
                $('#datetimepicker').datetimepicker({
                    format: 'YYYY-MM-DD'
                });
                $('#datetimepicker2').datetimepicker({
                    format: 'YYYY-MM-DD'
                });
            });
        </script>
    </div>
    <div class="form-group">
        <label class="control-label col-sm-3" for="teller_no">Teller / Reference Number:</label>
        <div class="col-sm-9 col-lg-5"><input name="teller_no" type="text" class="form-control" id="teller_no"></div>
    </div>
    <div class="form-group">
        <label class="control-label col-sm-3" for="naira_amount">Amount Paid in Naira (&#8358;):</label>
        <div class="col-sm-9 col-lg-5"><input name="naira_amount" type="text" class="form-control" id="naira_amount" required></div>
    </div>
    <div class="form-group">
        <label class="control-label col-sm-3" for="comment">Any Comment?</label>
        <div class="col-sm-9 col-lg-7"><textarea name="comment" class="form-control" rows="5" id="comment"></textarea></div>
    </div>
    <div class="form-group">
        <div class="col-sm-offset-3 col-sm-9"><input name="deposit_notify_detail_form" type="submit" class="btn btn-success" value="Submit" /></div>
    </div>
    <?php } ?>
    
</form>
