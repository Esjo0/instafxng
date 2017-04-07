<ul class="fa-ul">
    <li><i class="fa-li fa fa-check-square-o icon-tune"></i>Read and Accept Declaration</li>
</ul>

<p><img src="userfiles/<?php echo $passport; ?>" width="186" height="191"></p>
<p>
    I, <strong><?php echo $client_name; ?></strong> of <strong><?php echo $client_address; ?></strong> declare that all the information I have submitted to Instant Web-Net Technologies Limited are genuine and true.
    <p> I declare that I shall only use any information including bank account belonging to Instant Web-Net Technologies Limited which may come into my possession through the use of this website (www.instafxng.com) for legitimate business activities including making deposits and withdrawal to and from my Instaforex account.</p>
    <p> I further declare that I shall not use the Website or any other information belonging to Instant Web-Net Technologies Limited for any fraudulent or malicious purposes.
      I willingly make this declaration on <?php echo date('d-M-Y'); ?>
</p>
<p><img src="userfiles/<?php echo $signature; ?>" width="146" height="91"></p>
<form role="form" method="post" action="<?php echo $_SERVER['REQUEST_URI']; ?>">
    <input type="hidden" name="ifx_acct_no" value="<?php if(isset($account_no)) { echo $account_no; } ?>" />
    <div class="form-group">
        <input name="verify_account_declare" type="submit" class="btn btn-success" value="Accept Declaration" />
    </div>
</form>