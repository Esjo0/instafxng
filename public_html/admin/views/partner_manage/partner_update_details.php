<p>Fill the form below to add a new client to our system.</p>

<hr/><br/>

<form data-toggle="validator" class="form-horizontal" role="form" method="post" action="<?php echo $REQUEST_URI; ?>">
    <input name="user_code" type="hidden" value="<?php if(isset($user_code)) { echo $user_code; } ?>">
    <input name="email_address" type="hidden" value="<?php if(isset($client_email)) { echo $client_email; } ?>">
    <div class="form-group">
        <label class="control-label col-sm-3" for="partner_code">Partner Code:</label>
        <div class="col-sm-9 col-lg-5">
            <input name="partner_code" type="text" required class="form-control" id="partner_code" value="<?php if(isset($partner_code)) { echo $partner_code; } ?>" readonly>
        </div>
    </div>
    <div class="form-group">
        <label class="control-label col-sm-3" for="ifxaccount">IFX Account:</label>
        <div class="col-sm-9 col-lg-5">
            <input name="ifxaccount" type="text" required class="form-control" id="ifxaccount" value="<?php if(isset($settlement_ifxaccount_id)) { echo $settlement_ifxaccount_id; } ?>" readonly>
        </div>
    </div>
    <div class="form-group">
        <label class="control-label col-sm-3" for="status">Status:</label>
        <div class="col-sm-9 col-lg-5">
            <select name="status" class="form-control" id="status">
                <option value="1" <?php if ($status=='1') { echo "selected='selected'";} ?>>New</option>
                <option value="2" <?php if ($status=='2') { echo "selected='selected'";} ?>>Active</option>
                <option value="3" <?php if ($status=='3') { echo "selected='selected'";} ?>>Inactive</option>
                <option value="3" <?php if ($status=='4') { echo "selected='selected'";} ?>>Suspended</option>
            </select>
        </div>
    </div>
    <div class="form-group">
        <div class="col-sm-offset-3 col-sm-9">
            <button type="button" data-target="#add-details-confirm" data-toggle="modal" class="btn btn-success"><i class="fa fa-send-o fa-fw"></i> Submit</button>
        </div>
    </div>
    
    
    <!-- Modal - confirmation boxes -->
    <div id="add-details-confirm" tabindex="-1" role="dialog" aria-hidden="true" class="modal fade">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" data-dismiss="modal" aria-hidden="true"
                        class="close">&times;</button>
                    <h4 class="modal-title">Update Partner's Confirmation</h4>
                </div>
                <div class="modal-body">Are you sure you want to save this information?</div>
                <div class="modal-footer">
                    <input name="update_partn_details" type="submit" class="btn btn-success" value="Submit" />
                    <button type="submit" name="decline" onClick="window.close();" data-dismiss="modal" class="btn btn-danger">Close !</button>
                </div>
            </div>
        </div>
    </div>
</form>