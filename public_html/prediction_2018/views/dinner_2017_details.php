<?php
$REQUEST_URI = strtok($_SERVER['REQUEST_URI'], '?');
?>
<?php if($attendee_detail['invite'] < 1): ?>
<form data-toggle="validator" class="form-horizontal" role="form" method="post" action="<?php echo $REQUEST_URI . '?p=details'; ?>">
    <input name="reservation_code" type="hidden" value="<?php echo $attendee_detail['reservation_code']; ?>">
    <!--<div class="form-group">
        <img class="img-responsive" src="https://chart.googleapis.com/chart?chs=300x300&cht=qr&chl=<?php /*echo $attendee_detail['reservation_code']; */?>&choe=UTF-8" title="Reservation Barcode" />
    </div>-->
    <div class="form-group">
        <label class="control-label col-sm-3" for="name">Full Name:</label>
        <div class="col-sm-9 col-lg-5">
            <input type="text" class="form-control" id="name" name="name" value="<?php echo $attendee_detail['full_name']; ?>" readonly>
        </div>
    </div>
    <div class="form-group">
        <label class="control-label col-sm-3" for="email_address">Email Address:</label>
        <div class="col-sm-9 col-lg-5">
            <input type="text" class="form-control" id="email_address" name="email_address" value="<?php echo $attendee_detail['email']; ?>" readonly>
            <input type="hidden" class="form-control" id="email" name="email" value="<?php echo $attendee_detail['email']; ?>" >
        </div>
    </div>
    <div class="form-group">
        <label class="control-label col-sm-3" for="phone_number">Phone Number:</label>
        <div class="col-sm-9 col-lg-5">
            <input type="text" class="form-control" id="phone_number" name="phone_number" value="<?php echo $attendee_detail['phone']; ?>">
        </div>
    </div>
    <div class="form-group">
        <label class="control-label col-sm-3" for="state_of_residence">State Of Residence:</label>
        <div class="col-sm-9 col-lg-5">
            <input type="text" class="form-control" id="state_of_residence" name="state_of_residence" value="<?php echo $attendee_detail['state_of_residence']; ?>" readonly>
        </div>
    </div>

    <div class="form-group">
        <label class="control-label col-sm-3" for="confirmation_status">Confirmation Status:</label>
        <div class="col-sm-9 col-lg-5">
            <div class="radio">
                <label><input type="radio" name="confirmation_status" value="2" <?php if($attendee_detail['confirmation'] == '2') { echo "checked"; }; ?>>Confirm</label>
            </div>
            <div class="radio">
                <label><input type="radio" name="confirmation_status" value="3" <?php if($attendee_detail['confirmation'] == '3') { echo "checked"; }; ?>>Decline</label>
            </div>
        </div>
    </div>
    <div class="form-group">
        <div class="col-sm-offset-3 col-sm-9">
            <button type="button" data-target="#confirm-save-attendance" data-toggle="modal" class="btn btn-success">Update Reservation</button>
        </div>
    </div>

    <!--Modal - confirmation boxes-->
    <div id="confirm-save-attendance" tabindex="-1" role="dialog" aria-hidden="true" class="modal fade">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" data-dismiss="modal" aria-hidden="true"
                            class="close">&times;</button>
                    <h4 class="modal-title">Update Reservation</h4></div>
                <div class="modal-body">Are you sure you want to save the changes? </div>
                <div class="modal-footer">
                    <input name="process" type="submit" class="btn btn-success" value="Proceed">
                    <button type="submit" name="close" onClick="window.close();" data-dismiss="modal" class="btn btn-danger">Close!</button>
                </div>
            </div>
        </div>
    </div>
</form>
<?php endif; ?>

<?php if($attendee_detail['invite'] == 1): ?>
    <form id="invitation" data-toggle="validator" class="form-horizontal" role="form" method="post" action="<?php echo $REQUEST_URI . '?p=details'; ?>">
        <center><input name="reservation_code" type="hidden" value="<?php echo $attendee_detail['reservation_code']; ?>">
        <div class="form-group">
            <p>
                <b class=" col-sm-3" >Full Name:</b>
                <span class="col-sm-9 col-lg-5">
                    <?php echo $attendee_detail['full_name']; ?>
                </span>
            </p>
        </div>

            <div class="form-group">
                <p>
                    <b class=" col-sm-3" >Reservation Code:</b>
                    <span class="col-sm-9 col-lg-5">
                    <?php echo $attendee_detail['reservation_code']; ?>
                </span>
                </p>
            </div>
        <div class="form-group">
            <p><b class="col-sm-3">Ticket Type:</b>
            <span class="col-sm-9 col-lg-5">
                <?php echo dinner_ticket_type($attendee_detail['ticket_type']);?>
            </span></p>
        </div>
        <div class="form-group">
            <img height="200" width="200" class="img-responsive" src="https://chart.googleapis.com/chart?chs=300x300&cht=qr&chl=<?php echo $attendee_detail['reservation_code']; ?>&choe=UTF-8" title="Reservation Barcode" />
        </div>
            </center>
    </form>
    <div class="form-group">
        <div class="col-sm-offset-3 col-sm-9">
            <button type="button" onclick="print_report('invitation')" class="btn btn-info">Print Invite</button>
        </div>
    </div>
<?php endif; ?>
