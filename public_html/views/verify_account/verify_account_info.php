<ul class="fa-ul">
    <li><i class="fa-li fa fa-check-square-o icon-tune"></i>Upload your passport, ID, signature and enter your address.</li>
</ul>

<?php if(isset($client_doc_status) || isset($client_meta_status)) { ?>
    <p>To complete your verification, please upload the remaining required document below and click the submit button.</p>
<?php } else { ?>
    <p>Kindly upload the scanned copies of the following documents. While scanner is not available,
        you may also take clean photographs of the documents with your phone and upload.</p>
    <p>(Files must be less than 150KB and format must be jpg, gif, png).</p>
<?php } ?>

<form data-toggle="validator" role="form" method="post" action="<?php echo $_SERVER['REQUEST_URI']; ?>" enctype="multipart/form-data">
    <input type="hidden" name="account_no" value="<?php if(isset($ifx_acc_no)) { echo $ifx_acc_no; } ?>" />
    <?php if(isset($client_doc_status) || isset($client_meta_status)) { ?>
        <input type="hidden" name="doc_update" value="1" />
    <?php } ?>

    <?php if((isset($client_doc_status) && $client_doc_status[0] != '1') || !isset($client_doc_status)) { ?>
    <div class="form-group">
        <label class="control-label">ID (National ID Card, Driver's license, International Passport or Recognized ID Card):</label>
        <input type="file" name="pictures_id_card" class="file file-loading" data-allowed-file-extensions='["jpg", "gif", "png"]'>
    </div>
    <?php } ?>

    <?php if((isset($client_doc_status) && $client_doc_status[1] != '1') || !isset($client_doc_status)){ ?>
    <div class="form-group">
        <label class="control-label">Passport Photograph:</label>
        <input type="file" name="pictures_passport" class="file file-loading" data-allowed-file-extensions='["jpg", "gif", "png"]'>
    </div>
    <?php } ?>

    <?php if((isset($client_doc_status) && $client_doc_status[2] != '1') || !isset($client_doc_status)) { ?>
    <div class="form-group">
        <label class="control-label">Signature:</label>
        <input type="file" name="pictures_signature" class="file file-loading" data-allowed-file-extensions='["jpg", "gif", "png"]'>
    </div>
    <?php } ?>

    <?php if(((isset($client_meta_status)) && $client_meta_status != '2') || !isset($client_meta_status)) { ?>
    <div class="form-group">
        <label class="control-label">Address:</label>
        <textarea name="address" class="form-control" rows="3" id="address" required></textarea>
    </div>
    <div class="form-group">
        <label class="control-label" for="city">City:</label>
        <input name="city" type="text" class="form-control" id="city" required>
    </div>
    <div class="form-group">
        <label class="control-label" for="state">Your State:</label>
        <select name="state" class="form-control" id="state" required>
            <option value="" selected>Select State</option>
            <?php foreach($all_states as $key => $value) { ?>
                <option value="<?php echo $value['state_id']; ?>"><?php echo $value['state']; ?></option>
            <?php } ?>
        </select>
    </div>
    <?php } ?>

    <div class="form-group">
        <input name="verify_account_info" type="submit" class="btn btn-success" value="Submit" />
    </div>
</form>