<h5>Bank Account Details</h5>
<p>Make changes below to make an update</p>

<form data-toggle="validator" class="form-horizontal" role="form" method="post" action="">
    <input name="client_unique" type="hidden" value="<?php if(isset($user_code_encrypted)) { echo $user_code_encrypted; } ?>">
    <div class="form-group">
        <label class="control-label col-sm-3" for="bank_name">Bank Name:</label>
        <div class="col-sm-9 col-lg-5">
            <select name="bank_name" class="form-control" id="bank_name" required>
                <option value="" selected>Select Bank</option>
                <?php foreach($all_banks as $key => $value) { ?>
                    <option value="<?php echo $value['bank_id']; ?>" <?php if($client_bank_account['client_bank_name'] == $value['bank_name']) { echo 'selected'; } ?>><?php echo $value['bank_name']; ?></option>
                <?php } ?>
            </select>
        </div>
    </div>
    <div class="form-group">
        <label class="control-label col-sm-3" for="bank_acct_name">Account Name:</label>
        <div class="col-sm-9 col-lg-5">
            <input name="bank_acct_name" type="text" class="form-control" id="bank_acct_name" value="<?php echo $client_bank_account['client_acct_name']; ?>" required>
        </div>
    </div>
    <div class="form-group">
        <label class="control-label col-sm-3" for="bank_acct_number">Account Number:</label>
        <div class="col-sm-9 col-lg-5">
            <input name="bank_acct_number" type="text" class="form-control" id="bank_acct_number" value="<?php echo $client_bank_account['client_acct_no']; ?>" required maxlength="10">
            <span class="help-block">10 digit NUBAN account number</span>
        </div>
    </div>

    <div class="form-group">
        <div class="col-sm-offset-3 col-sm-9">
            <button type="button" data-target="#account_update" data-toggle="modal" class="btn btn-success"><i class="fa fa-save fa-fw"></i> Update Bank Account</button>
        </div>
    </div>

    <!-- Modal - confirmation boxes -->
    <div id="account_update" tabindex="-1" role="dialog" aria-hidden="true" class="modal fade">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" data-dismiss="modal" aria-hidden="true"
                            class="close">&times;</button>
                    <h4 class="modal-title">UPDATE CLIENT DETAILS</h4></div>
                <div class="modal-body">Are you sure about the changes you have made?</div>
                <div class="modal-footer">
                    <input name="account_update" type="submit" class="btn btn-success" value="Approve !">
                    <button type="submit" name="decline" onClick="window.close();" data-dismiss="modal" class="btn btn-danger">Close !</button>
                </div>
            </div>
        </div>
    </div>
</form>