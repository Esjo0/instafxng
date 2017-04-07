<ul class="fa-ul">
    <li><i class="fa-li fa fa-check-square-o icon-tune"></i>Deposit Order Completed</li>
</ul>

<p>You have successfully added a deposit transaction. This transaction will be confirmed and completed shortly.
Below is the summary of the deposit.</p>

<table class="table table-responsive table-striped table-bordered table-hover">
    <thead>
    <tr><th> </th><th> </th></tr>
    </thead>
    <tbody>
    <tr><td>Transaction ID</td><td><?php if(isset($trans_id)) { echo $trans_id; } ?></td></tr>
    <tr><td>Client Name</td><td><?php if(isset($client_full_name)) { echo $client_full_name; } ?></td></tr>
    <tr><td>Instaforex Account</td><td><?php if(isset($account_no)) { echo $account_no; } ?></td></tr>
    <tr><td>Funding Value (USD)</td><td><?php if(isset($ifx_dollar_amount)) { echo number_format($ifx_dollar_amount, 2, ".", ","); } ?></td></tr>
    <tr><td>Funding Value (&#8358;)</td><td><?php if(isset($ifx_naira_amount)) { echo number_format($ifx_naira_amount, 2, ".", ","); } ?></td></tr>
    </tbody>
</table>