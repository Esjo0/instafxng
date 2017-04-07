<p><a href="system_message.php" class="btn btn-default" title="Go back to System Message"><i class="fa fa-arrow-circle-left"></i> Go Back - System Message</a></p>

<p>Update the selected system message below. Use the below placeholder for inserting client first name, account number. Just copy and paste as it is without modifying.</p>
<ul>
    <li>[FIRST_NAME] - Inserts client first name</li>
    <li>[FULL_NAME] - Inserts client full name</li>
    <li>[IFX_ACCOUNT] - Inserts client instaforex account relevant to the message</li>
</ul>
<br />
<form data-toggle="validator" class="form-horizontal" enctype="multipart/form-data" role="form" method="post" action="<?php echo $_SERVER['REQUEST_URI']; ?>">
    <input type="hidden" name="MAX_FILE_SIZE" value="500000" />
    <input type="hidden" name="POST_MAX_SIZE" value="500000" />
    <input type="hidden" name="message_no" value="<?php if(isset($selected_message['system_message_id'])) { echo $selected_message['system_message_id']; } ?>" />
    <input type="hidden" name="message_type" value="<?php if(isset($selected_message['type'])) { echo $selected_message['type']; } ?>" />
    <div class="form-group">
        <label class="control-label col-sm-2" for="description">Description:</label>
        <div class="col-sm-10"><textarea name="description" id="description" rows="3" class="form-control" required disabled><?php if(isset($selected_message['description'])) { echo $selected_message['description']; } ?></textarea></div>
    </div>
    <?php if($selected_message['type'] == '1') { ?>
    <div class="form-group">
        <label class="control-label col-sm-2" for="subject">Subject:</label>
        <div class="col-sm-10"><input type="text" name="subject" class="form-control" id="subject" value="<?php if(isset($selected_message['subject'])) { echo $selected_message['subject']; } ?>" /></div>
    </div>
    <?php } ?>
    
    <?php if($selected_message['type'] == '1') { ?>
    <div class="form-group">
        <label class="control-label col-sm-2" for="content">Content:</label>
        <div class="col-sm-10"><textarea name="content" id="content" rows="3" class="form-control" required><?php if(isset($selected_message['value'])) { echo $selected_message['value']; } ?></textarea></div>
    </div>
    <?php } else { ?>
    <div class="form-group">
        <label class="control-label col-sm-2" for="message">Message:</label>
        <div class="col-sm-10">
            <textarea onChange="javascript:check_count();document.form1.count_display.value=document.form1.message.value.length;check_count();" onkeypress="document.form1.count_display.value=document.form1.message.value.length;check_count();"  onBlur="document.form1.count_display.value=document.form1.message.value.length;check_count();" name="content" id="message" rows="3" class="form-control" placeholder="Enter Message"  onKeyDown="limitText(this.form.message,this.form.countdown,140);" onKeyUp="limitText(this.form.message,this.form.countdown,140);" required><?php if(isset($selected_message['value'])) { echo $selected_message['value']; } ?></textarea>
            <small>(Maximum characters: 140)<br>You have <input readonly type="text" name="countdown" size="3" value="140"> characters left.</small>
        </div>
    </div>
    <?php } ?>
    
    <div class="form-group">
        <div class="col-sm-offset-2 col-sm-10">
            <button type="button" data-target="#save-system-message-confirm" data-toggle="modal" class="btn btn-success"><i class="fa fa-save fa-fw"></i> Save</button>
        </div>
    </div>

    <!-- Modal - confirmation boxes -->
    <div id="save-system-message-confirm" tabindex="-1" role="dialog" aria-hidden="true" class="modal fade">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" data-dismiss="modal" aria-hidden="true"
                        class="close">&times;</button>
                    <h4 class="modal-title">Save System Message Confirmation</h4>
                </div>
                <div class="modal-body">Are you sure you want to save this update?</div>
                <div class="modal-footer">
                    <input name="process" type="submit" class="btn btn-success" value="Save">
                    <button type="submit" name="decline" onClick="window.close();" data-dismiss="modal" class="btn btn-danger">Close !</button>
                </div>
            </div>
        </div>
    </div>
</form>