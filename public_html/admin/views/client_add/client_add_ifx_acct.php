<p>The email provided is already associated with a client. Proceed with the process to add a new Instaforex Account to the client detail below. 
    Ensure the Instaforex Account is associated with the client information displayed.</p>

<hr/><br/>

<form data-toggle="validator" class="form-horizontal" role="form" method="post" action="<?php echo $REQUEST_URI; ?>">
    <input name="email_address" type="hidden" value="<?php if(isset($client_email)) { echo $client_email; } ?>">
    <div class="form-group">
        <label class="control-label col-sm-3" for="client_name">Client Name:</label>
        <div class="col-sm-9 col-lg-5">
            <input name="client_name" type="text" class="form-control" id="client_name" value="<?php if(isset($full_name)) { echo $full_name; } ?>" readonly="readonly">
        </div>
    </div>
    <div class="form-group">
        <label class="control-label col-sm-3" for="phone_number">Phone Number:</label>
        <div class="col-sm-9 col-lg-5">
            <input name="phone_number" type="text" class="form-control" id="phone_number" value="<?php if(isset($phone)) { echo $phone; } ?>" readonly="readonly">
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
            <button type="button" data-target="#add-account-confirm" data-toggle="modal" class="btn btn-success"><i class="fa fa-send-o fa-fw"></i> Submit</button>
        </div>
    </div>
    
    <!-- Modal - confirmation boxes -->
    <div id="add-account-confirm" tabindex="-1" role="dialog" aria-hidden="true" class="modal fade">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" data-dismiss="modal" aria-hidden="true"
                        class="close">&times;</button>
                    <h4 class="modal-title">Add Client Confirmation</h4>
                </div>
                <div class="modal-body">Are you sure you want to save this information?</div>
                <div class="modal-footer">
                    <input name="client_add_ifx_acct" type="submit" class="btn btn-success" value="Submit" />
                    <button type="submit" name="decline" onClick="window.close();" data-dismiss="modal" class="btn btn-danger">Close !</button>
                </div>
            </div>
        </div>
    </div>
</form>


