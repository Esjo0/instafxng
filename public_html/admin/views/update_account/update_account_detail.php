<p>Modify the account type of the selected account below.</p>

<form data-toggle="validator" class="form-horizontal" role="form" method="post" action="">
    <input name="acct_no" type="hidden" value="<?php echo $ifx_acc_no;  ?>">
    <div class="form-group">
        <label class="control-label col-sm-3" for="full_name">Full Name:</label>
        <div class="col-sm-9 col-lg-5">
            <p class="form-control-static"><?php echo $client_full_name; ?></p>
        </div>
    </div>
    <div class="form-group">
        <label class="control-label col-sm-3" for="email_address">Email Address:</label>
        <div class="col-sm-9 col-lg-5">
            <p class="form-control-static"><?php echo $client_email; ?></p>
        </div>
    </div>
    <div class="form-group">
        <label class="control-label col-sm-3" for="phone_number">Phone Number:</label>
        <div class="col-sm-9 col-lg-5">
            <p class="form-control-static"><?php echo $client_phone_number; ?></p>
        </div>
    </div>
    <div class="form-group">
        <label class="control-label col-sm-3" for="ifx_account">IFX Account Number:</label>
        <div class="col-sm-9 col-lg-5">
            <p class="form-control-static"><?php echo $ifx_acc_no; ?></p>
        </div>
    </div>
    <div class="form-group">
        <label class="control-label col-sm-3" for="acct_type">Account Type:</label>
        <div class="col-sm-9 col-lg-5">
            <div class="radio">
                <label><input type="radio" name="acct_type" value="1" <?php if($ifx_acc_type == '1') { echo "checked"; } ?> required>ILPR</label>
            </div>
            <div class="radio">
                <label><input type="radio" name="acct_type" value="2" <?php if($ifx_acc_type == '2') { echo "checked"; } ?> required>Non-ILPR</label>
            </div>
        </div>
    </div>


    <div class="form-group">
        <div class="col-sm-offset-3 col-sm-9">
            <button type="button" data-target="#acct_update" data-toggle="modal" class="btn btn-success"><i class="fa fa-save fa-fw"></i> Update Account</button>
        </div>
    </div>

    <!-- Modal - confirmation boxes -->
    <div id="acct_update" tabindex="-1" role="dialog" aria-hidden="true" class="modal fade">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" data-dismiss="modal" aria-hidden="true"
                            class="close">&times;</button>
                    <h4 class="modal-title">UPDATE CLIENT IFX ACCOUNT</h4></div>
                <div class="modal-body">Are you sure about the changes you have made?</div>
                <div class="modal-footer">
                    <input name="update_account_detail" type="submit" class="btn btn-success" value="Approve !">
                    <button type="submit" name="decline" onClick="window.close();" data-dismiss="modal" class="btn btn-danger">Close !</button>
                </div>
            </div>
        </div>
    </div>
</form>