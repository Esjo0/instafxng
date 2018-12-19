<?php
    $REQUEST_URI = strtok($_SERVER['REQUEST_URI'], '?');
?>

<div class="alert alert-success">
    <p>See the summary of your withdrawal below. Your Withdrawal will
        be processed and payment made within one business day.</p>
    <p>In a few cases some requests fall outside the category of withdrawals we can process from
        our office and has to be sent to InstaForex office. Withdrawal requests in this category can take
        up to 3 Business days.</p>
    <p>If your withdrawal request falls within this category, we will inform you immediately.</p>
    <p>Thank you for choosing InstaFxNg.</p>
    <p class='text-danger' style='font-size: 1.3em'><strong>NOTE: </strong>Your payment will be made based on the rate as at the time the fund
        is debited from your Instaforex account.</p>
</div>

<hr />
<ul class="fa-ul">
    <li><i class="fa-li fa fa-check-square-o icon-tune"></i>ORDER SUMMARY</li>
</ul>
<table class="table table-responsive table-bordered awaiting-approval-watermark">
    <thead>
        <tr><th> </th><th> </th></tr>
    </thead>
    <tbody>
        <tr><td>Transaction ID</td><td><?php if(isset($trans_id)) { echo $trans_id; } ?></td></tr>
        <tr><td>Client Name</td><td><?php if(isset($full_name)) { echo $full_name; } ?></td></tr>
        <tr><td>Instaforex Account</td><td><?php if(isset($account_no)) { echo $account_no; } ?></td></tr>
        <tr><td>Withdrawal Value (USD)</td><td><?php if(isset($ifx_dollar_amount)) { echo number_format($ifx_dollar_amount, 2, ".", ","); } ?></td></tr>
        <tr><td>Withdrawal Value (&#8358;)</td><td><?php if(isset($ifx_naira_amount)) { echo number_format($ifx_naira_amount, 2, ".", ","); } ?></td></tr>
        <tr><td>Service Charge (&#8358;)</td><td><?php if(isset($service_charge)) { echo number_format($service_charge, 2, ".", ","); } ?></td></tr>
        <tr><td>VAT (&#8358;)</td><td><?php if(isset($vat)) { echo number_format($vat, 2, ".", ","); } ?></td></tr>
        <tr style="font-size: 1.2em; padding: 0; color: green; font-weight: bold"><td>Total Withdrawal Payable (&#8358;)</td><td><?php if(isset($total_withdrawal_payable)) { echo number_format($total_withdrawal_payable, 2, ".", ","); } ?></td></tr>
        <tr><td>Bank Name</td><td><?php if(isset($client_bank_name)) { echo $client_bank_name; } ?></td></tr>
        <tr><td>Bank Account Name</td><td><?php if(isset($client_acct_name)) { echo $client_acct_name; } ?></td></tr>
        <tr><td>Bank Account Number</td><td><?php if(isset($client_acct_no)) { echo $client_acct_no; } ?></td></tr>
    </tbody>
</table>