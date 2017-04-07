<p><a href="system_message.php" class="btn btn-default" title="Go back to System Message"><i class="fa fa-arrow-circle-left"></i> Go Back - System Message</a></p>

<p><strong>Subject:</strong></p>
<p><?php if(isset($selected_message['subject'])) { echo $selected_message['subject']; } ?></p>
<p><strong>Content:</strong></p>
<?php if(isset($selected_message['value'])) { echo $selected_message['value']; } ?>

<form data-toggle="validator" class="form-horizontal" enctype="multipart/form-data" role="form" method="post" action="<?php echo $_SERVER['REQUEST_URI']; ?>">
    <input type="hidden" name="message_no" value="<?php if(isset($selected_message['system_message_id'])) { echo $selected_message['system_message_id']; } ?>" />
    <input type="hidden" name="message_type" value="<?php if(isset($selected_message['type'])) { echo $selected_message['type']; } ?>" />
    <hr /><br />

    <div class="form-group">
        <div class="col-sm-12"><label class="control-label" for="test_email">Test Email/Number:</label></div>
        <div class="col-sm-12">
            <textarea name="test_email" id="test_email" rows="3" class="form-control" required></textarea>
            <span class="help-block">Separate multiple email/number with comma</span>
        </div>
    </div>

    <div class="form-group">
        <div class="col-sm-12">
            <button type="button" data-target="#send-test-confirm" data-toggle="modal" class="btn btn-success"><i class="fa fa-paper-plane fa-fw"></i> Send Test</button>
        </div>
    </div>

    <!-- Modal - confirmation boxes -->
    <div id="send-test-confirm" tabindex="-1" role="dialog" aria-hidden="true" class="modal fade">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" data-dismiss="modal" aria-hidden="true"
                            class="close">&times;</button>
                    <h4 class="modal-title">Send Test Confirmation</h4></div>
                <div class="modal-body">Do you want to send test now?</div>
                <div class="modal-footer">
                    <input name="send_test" type="submit" class="btn btn-success" value="Send Test">
                    <button type="submit" name="decline" onClick="window.close();" data-dismiss="modal" class="btn btn-danger">Close !</button>
                </div>
            </div>
        </div>
    </div>
</form>