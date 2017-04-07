<p>You can add a new client to our system by entering the client's email address below. 
    If the email is already associated with a client, you can proceed with the process
to add another Instaforex account to the client's profile.</p>

<hr/><br/>

<form data-toggle="validator" class="form-horizontal" role="form" method="post" action="<?php echo $REQUEST_URI; ?>">
    <div class="form-group">
        <label class="control-label col-sm-3" for="client_email">Email Address:</label>
        <div class="col-sm-9 col-lg-5">
            <input name="client_email" type="text" class="form-control" id="client_email" required>
        </div>
    </div>
    <div class="form-group">
        <div class="col-sm-offset-3 col-sm-9"><input name="client_add_email" type="submit" class="btn btn-success" value="Submit" /></div>
    </div>
</form>