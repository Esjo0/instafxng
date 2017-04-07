<p>Fill the form below to add a new client to our system.</p>

<hr/><br/>

<form data-toggle="validator" class="form-horizontal" role="form" method="post" action="<?php echo $REQUEST_URI; ?>">
    <input name="user_code" type="hidden" value="<?php if(isset($user_code_encrypted)) { echo $user_code_encrypted; } ?>">
    <input name="email_address" type="hidden" value="<?php if(isset($client_email)) { echo $client_email; } ?>">
    <div class="form-group">
        <label class="control-label col-sm-3" for="client_name">Client Name:</label>
        <div class="col-sm-9 col-lg-5">
            <input name="client_name" type="text" class="form-control" id="client_name" value="" required>
        </div>
    </div>
    <div class="form-group">
        <label class="control-label col-sm-3" for="phone_number">Phone Number:</label>
        <div class="col-sm-9 col-lg-5">
            <input name="phone_number" type="text" class="form-control" id="phone_number" value="" required>
        </div>
    </div>
    <div class="form-group">
        <label class="control-label col-sm-3" for="ifx_acct_no">Instaforex Account:</label>
        <div class="col-sm-9 col-lg-5">
            <input name="ifx_acct_no" type="text" class="form-control" id="ifx_acct_no" required>
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
                    <h4 class="modal-title">Add Client Confirmation</h4>
                </div>
                <div class="modal-body">Are you sure you want to save this information?</div>
                <div class="modal-footer">
                    <input name="client_add_details" type="submit" class="btn btn-success" value="Submit" />
                    <button type="submit" name="decline" onClick="window.close();" data-dismiss="modal" class="btn btn-danger">Close !</button>
                </div>
            </div>
        </div>
    </div>
</form>