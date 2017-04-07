<ul class="fa-ul">
    <li><i class="fa-li fa fa-check-square-o icon-tune"></i>Upload a picture of you with whoever your valentine is
        here (your wife/husband, girlfriend/boyfriend, pet, kids)</li>
</ul>

<form data-toggle="validator" role="form" method="post" action="" enctype="multipart/form-data">
    <input type="hidden" name="account_no" value="<?php if(isset($ifx_acc_no)) { echo $ifx_acc_no; } ?>" />

    <div class="form-group">
        <label class="control-label">Upload your picture:</label>
        <input type="file" name="pictures" class="file file-loading" data-allowed-file-extensions='["jpg", "gif", "png"]'>
    </div>

    <div class="form-group">
        <input name="val_offer_upload" type="submit" class="btn btn-success" value="Upload" />
    </div>
</form>