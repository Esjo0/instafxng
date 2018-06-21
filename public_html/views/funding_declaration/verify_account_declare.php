<div id="old">
<ul class="fa-ul">
    <li><i class="fa-li fa fa-check-square-o icon-tune"></i>Read and Accept Declaration</li>
</ul>

<p><img src="https://instafxng.com/userfiles/<?php echo $passport; ?>" width="186" height="191"></p>
<p>
    I, <strong><?php echo $client_name; ?></strong> of <strong><?php echo $_client_address; ?></strong> declare that all the information I have submitted to Instant Web-Net Technologies Limited are genuine and true.
    <p> I declare that I shall only use any information including bank account belonging to Instant Web-Net Technologies Limited which may come into my possession through the use of this website (www.instafxng.com) for legitimate business activities including making deposits and withdrawal to and from my Instaforex account.</p>
    <p> I further declare that I shall not use the Website or any other information belonging to Instant Web-Net Technologies Limited for any fraudulent or malicious purposes.
      I willingly make this declaration on <?php echo date('d-M-Y'); ?>
</p>
<p><img src="userfiles/<?php echo $signature; ?>" width="146" height="91"></p>
<div style="display: none;" id="print_out">
    <div class="row">
        <div class="col-sm-2">
            </div>
        <div class="col-sm-8">
    <div class="row">
        <p><img src="https://instafxng.com/images/ifxlogo.png" class="img img-responsive pull-left" ></p>
        <p><img class="pull-right" src="userfiles/<?php echo $passport; ?>" width="186" height="191"></p>
    </div>
    <div class="row">
        <p class="text-center"><strong>Read and Accept Declaration</strong></p>
        <p class="text-justify">
            I, <strong><?php echo $client_name; ?></strong> of <strong><?php echo $_client_address; ?></strong> declare that all the information I have submitted to Instant Web-Net Technologies Limited are genuine and true.
        <p class="text-justify"> I declare that I shall only use any information including bank account belonging to Instant Web-Net Technologies Limited which may come into my possession through the use of this website (www.instafxng.com) for legitimate business activities including making deposits and withdrawal to and from my Instaforex account.</p>
        <p class="text-justify"> I further declare that I shall not use the Website or any other information belonging to Instant Web-Net Technologies Limited for any fraudulent or malicious purposes.
            I willingly make this declaration on <?php echo date('d-M-Y'); ?>
        </p>
        <p><img src="https://instafxng.com/userfiles/<?php echo $signature; ?>" width="146" height="91"></p>
    </div>
            </div>
        <div class="col-sm-2">
    </div>
</div>
</div>
<form role="form" method="post" action="<?php echo $_SERVER['REQUEST_URI']; ?>">
    <div class="form-group">
        <button onclick="print_report('print_out');" type="button" class="btn btn-sm btn-success">Accept Declaration</button>
    </div>
    <input type="hidden" name="ifx_acct_no" value="<?php if(isset($account_no)) { echo $account_no; } ?>" />
    <div class="form-group">
        <input id="nxt" style="display: none;" name="verify_account_declare" type="submit" class="btn btn-success" value="Accept Declaration" />
    </div>
</form>
    </div>
<div style="display: none" id="tnks">
    <ul class="fa-ul">
        <li><i class="fa-li fa fa-check-square-o icon-tune"></i>Instafxng Account Verification Completed</li>
    </ul>
    <p>
        You have successfully uploaded your documents for the Instafxng Account Verification.
        Our verification staff will confirm the documents for genuineness and hence approve or decline.
        Your documents will be checked shortly.
    </p>
    <p>Thank you for choosing Instafxng.</p>
</div>