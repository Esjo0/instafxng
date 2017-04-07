<ul class="fa-ul">
    <li><i class="fa-li fa fa-check-square-o icon-tune"></i>Order Transaction Invoice</li>
</ul>

<p>THIS INVOICE IS VALID ONLY FOR 24 HOURS. IF PAYMENT IS NOT MADE BY THEN, YOU MUST SUBMIT ANOTHER ORDER.</p>
<p>Your Transaction ID for this order is <?php echo $client_trans_id; ?></p>
<p>The details of your order are as follows:</p>
<p>Amount of InstaForex Funding ordered: USD <?php echo $client_dollar; ?> Equivalent cost in Naira: =N= <?php echo $formated_client_naira_total; ?></p>
<?php echo $invoice_msg; ?>