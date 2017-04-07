<h5>Contact Details</h5>
<p>Make changes below to make an update</p>

<!------------- Contact Section --->
<form data-toggle="validator" class="form-horizontal" role="form" method="post" action="">
    <input name="client_unique" type="hidden" value="<?php if(isset($user_code_encrypted)) { echo $user_code_encrypted; } ?>">
    <div class="form-group">
        <label class="control-label col-sm-3" for="full_name">Full Name:</label>
        <div class="col-sm-9 col-lg-5">
            <p class="form-control-static"><?php echo $full_name; ?></p>
        </div>
    </div>
    <div class="form-group">
        <label class="control-label col-sm-3" for="email_address">Email Address:</label>
        <div class="col-sm-9 col-lg-5">
            <input name="email_address" type="text" class="form-control" id="email_address" value="<?php echo $email; ?>" required>
        </div>
    </div>
    <div class="form-group">
        <label class="control-label col-sm-3" for="phone_number">Phone Number:</label>
        <div class="col-sm-9 col-lg-5">
            <input name="phone_number" type="text" class="form-control" id="phone_number" value="<?php echo $phone; ?>" required>
        </div>
    </div>

    <div class="form-group">
        <div class="col-sm-offset-3 col-sm-9">
            <button type="button" data-target="#contact_update" data-toggle="modal" class="btn btn-success"><i class="fa fa-save fa-fw"></i> Update Contact</button>
        </div>
    </div>

    <!-- Modal - confirmation boxes -->
    <div id="contact_update" tabindex="-1" role="dialog" aria-hidden="true" class="modal fade">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" data-dismiss="modal" aria-hidden="true"
                            class="close">&times;</button>
                    <h4 class="modal-title">UPDATE CLIENT DETAILS</h4></div>
                <div class="modal-body">Are you sure about the changes you have made?</div>
                <div class="modal-footer">
                    <input name="contact_update" type="submit" class="btn btn-success" value="Approve !">
                    <button type="submit" name="decline" onClick="window.close();" data-dismiss="modal" class="btn btn-danger">Close !</button>
                </div>
            </div>
        </div>
    </div>
</form>