<?php
require_once("../init/initialize_admin.php");
if (!$session_admin->is_logged_in())
{
    redirect_to("login.php");
}

function get_dinner_reg_remark($reg_code)
{
    global $db_handle;
    $query = "SELECT CONCAT(a.last_name, SPACE(1), a.first_name) AS admin_full_name, dc.comment, dc.created
                FROM dinner2017_comment AS dc
                INNER JOIN admin AS a ON dc.admin_code = a.admin_code
                WHERE dc.reservation_code = '$reg_code' ORDER BY dc.created DESC";
    $result = $db_handle->runQuery($query);
    $fetched_data = $db_handle->fetchAssoc($result);

    return $fetched_data ? $fetched_data : false;
}

$get_params = allowed_get_params(['x', 'id']);

$reg_code_encrypted = $get_params['id'];
$reg_code = decrypt(str_replace(" ", "+", $reg_code_encrypted));
$reg_code = preg_replace("/[^A-Za-z0-9 ]/", '', $reg_code);

// Process comment
if ($_SERVER['REQUEST_METHOD'] == 'POST' && $_POST['process'] == true)
{
    foreach($_POST as $key => $value)
    {
        $_POST[$key] = $db_handle->sanitizePost(trim($value));
    }
    extract($_POST);

    $update = $admin_object->update_dinner_guest_2017($reservation_code, $ticket_type, $confirmation_status);
    if($update)
    {
        $message_success = "You have successfully updated this reservation.";
    }
    if(isset($_POST['send_invite']) && !empty($_POST['send_invite']))
    {
        $attendee_detail = $db_handle->fetchAssoc($db_handle->runQuery("SELECT * FROM dinner_2017 WHERE id_dinner_2017 = '$reservation_code'"));
        $attendee_detail = $attendee_detail[0];
        extract($attendee_detail);
        $subject = "SPECIAL INVITATION";
        $message = <<<MAIL
    <div style="background-color: #F3F1F2">
    <div style="max-width: 80%; margin: 0 auto; padding: 10px; font-size: 14px; font-family: Verdana;">
        <img src="https://instafxng.com/images/ifxlogo.png" />
        <hr />
        <div style="background-color: #FFFFFF; padding: 15px; margin: 5px 0 5px 0;">
            <p>Dear $full_name,</p>

            <p>It’s been almost 365 days and a lot has happened during the year,
            but get ready for one more!</p>

            <p>I want to take this opportunity to appreciate you for showing
            consistent confidence in the services we render and allowing us to
            service you this year. You are truly one of the most loyal clients
            of our company and serving you remains our pleasure.</p>

            <p>To roundup the year together, I humbly request that you join us
            for an exciting and entertaining time at our annual Christmas Dinner
            Event specially designed so you could relax and have fun with the
            members of our team and other Forex traders like yourself.</p>

            <p>This year’s dinner is themed the <strong>Classic 80s Night</strong>. When you step
            inside the venue; the music, the theme and overall ambience will
            transport you to a simpler and more innocent time where you can have the
            feel of what it used to be in the 80s.</p>

            <p>If you love to have fun, you should be at this event as the outfit,
            the looks the music and the venue will represent what it used to be
            from way back 80s, and there would be lots of exciting activities lined
            up alongside the 3 course dinner to be served.</p>

            <p style="text-align: center"><strong>One last thing ...</strong></p>

            <p>We have one last surprise for you before the year ends, you will
            witness the launch of a new service created to make you more money
            daily even as you trade.</p>

            <p style="text-align: center">You can
            <a href="https://instafxng.com/dinner.php?x=$id_encrypt">click here</a>
            to reserve your seat now.</p>

            <p><strong>NOTE: Admission is strictly by Invitation and you can only secure
            your reservation by clicking on the link.</strong></p>

            <p>It would be an honor to have you at the event as I earnestly look
            forward to welcoming you and meeting you in person.</p>

            <br /><br />
            <p>Best Regards,</p>
            <p>Fujah Abideen,<br />
            Corporate Communications Manager<br />
                www.instafxng.com</p>
            <br /><br />
        </div>
        <hr />
        <div style="background-color: #EBDEE9;">
            <div style="font-size: 11px !important; padding: 15px;">
                <p style="text-align: center"><span style="font-size: 12px"><strong>We"re Social</strong></span><br /><br />
                    <a href="https://facebook.com/InstaForexNigeria"><img src="https://instafxng.com/images/Facebook.png"></a>
                    <a href="https://twitter.com/instafxng"><img src="https://instafxng.com/images/Twitter.png"></a>
                    <a href="https://www.instagram.com/instafxng/"><img src="https://instafxng.com/images/instagram.png"></a>
                    <a href="https://www.youtube.com/channel/UC0Z9AISy_aMMa3OJjgX6SXw"><img src="https://instafxng.com/images/Youtube.png"></a>
                    <a href="https://linkedin.com/company/instaforex-ng"><img src="https://instafxng.com/images/LinkedIn.png"></a>
                </p>
                <p><strong>Head Office Address:</strong> TBS Place, Block 1A, Plot 8, Diamond Estate, Estate Bus-Stop, LASU/Isheri road, Isheri Olofin, Lagos.</p>
                <p><strong>Lekki Office Address:</strong> Road 5, Suite K137, Ikota Shopping Complex, Lekki/Ajah Express Road, Lagos State</p>
                <p><strong>Office Number:</strong> 08028281192</p>
                <br />
            </div>
            <div style="font-size: 10px !important; padding: 15px; text-align: center;">
                <p>This email was sent to you by Instant Web-Net Technologies Limited, the
                    official Nigerian Representative of Instaforex, operator and administrator
                    of the website www.instafxng.com</p>
                <p>To ensure you continue to receive special offers and updates from us,
                    please add support@instafxng.com to your address book.</p>
            </div>
        </div>
    </div>
</div>
MAIL;
        $system_object->send_email($subject, $message, $email, $full_name);
        $db_handle->runQuery("UPDATE dinner_2017 SET invite = '1' WHERE reservation_code = '$reservation_code'");
    }
    if(isset($comments) && !empty($comments))
    {
        $admin_code = $_SESSION['admin_unique_code'];
        $query = "INSERT INTO dinner2017_comment (reservation_code, admin_code, comment) VALUES ('$reservation_code', '$admin_code', '$comments')";
        $comment = $db_handle->runQuery($query);
        if($comment)
        {
            $message_success = "You have successfully added a comment about this guest.";
        }
    }

}


$attendee_detail = $db_handle->fetchAssoc($db_handle->runQuery("SELECT * FROM dinner_2017 WHERE reservation_code = '$reg_code'"));
$attendee_detail = $attendee_detail[0];

if(empty($attendee_detail))
{
    redirect_to("./");
    exit;
} else
    {
    $admin_remark = get_dinner_reg_remark($reg_code);
}

?>
<!DOCTYPE html>
<html lang="en">
    <head>
        <base target="_self">
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>Instaforex Nigeria | View 2017 Dinner Reservation</title>
        <meta name="title" content="Instaforex Nigeria | View Dinner Registration" />
        <meta name="keywords" content="" />
        <meta name="description" content="" />
        <?php require_once 'layouts/head_meta.php'; ?>
    </head>
    <body>
    <?php require_once 'layouts/header.php'; ?>
        <!-- Main Body: The is the main content area of the web site, contains a side bar  -->
        <div id="main-body" class="container-fluid">
            <div class="row no-gutter">
                <!-- Main Body - Side Bar  -->
                <div id="main-body-side-bar" class="col-md-4 col-lg-3 left-nav">
                <?php require_once 'layouts/sidebar.php'; ?>
                </div>
                
                <!-- Main Body - Content Area: This is the main content area, unique for each page  -->
                <div id="main-body-content-area" class="col-md-8 col-lg-9">
                    
                    <!-- Unique Page Content Starts Here
                    ================================================== -->
                    <div class="row">
                        <div class="col-sm-12 text-danger">
                            <h4><strong>VIEW DINNER RESERVATION</strong></h4>
                        </div>
                    </div>
                    
                    <div class="section-tint super-shadow">
                        <div class="row">
                            <div class="col-sm-12">
                                <p>
                                    <a href="dinner_2017_all_reg.php" class="btn btn-default" title="Go back to All Registrations">
                                        <i class="fa fa-arrow-circle-left"></i>
                                        Go Back - All Reservations
                                    </a>
                                </p>
                                
                                <?php require_once '../layouts/feedback_message.php'; ?>
                                <p>Update and record your comment.</p>

                                <form data-toggle="validator" class="form-horizontal" role="form" method="post" action="<?php echo $_SERVER['REQUEST_URI']; ?>">
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
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="control-label col-sm-3" for="phone_number">Phone Number:</label>
                                        <div class="col-sm-9 col-lg-5">
                                            <input type="text" class="form-control" id="phone_number" name="phone_number" value="<?php echo $attendee_detail['phone']; ?>" readonly>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="control-label col-sm-3" for="state_of_residence">State Of Residence:</label>
                                        <div class="col-sm-9 col-lg-5">
                                            <input type="text" class="form-control" id="state_of_residence" name="state_of_residence" value="<?php echo $attendee_detail['state_of_residence']; ?>" readonly>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="control-label col-sm-3" for="ticket_type">Ticket Type:</label>
                                        <div class="col-sm-9 col-lg-5">
                                            <div class="radio">
                                                <label><input type="radio" name="ticket_type" value="0" <?php if($attendee_detail['ticket_type'] == '0') { echo "checked"; }; ?>>Single</label>
                                            </div>
                                            <div class="radio">
                                                <label><input type="radio" name="ticket_type" value="1" <?php if($attendee_detail['ticket_type'] == '1') { echo "checked"; }; ?>>Plus One (Double)</label>
                                            </div>
                                            <div class="radio">
                                                <label><input type="radio" name="ticket_type" value="2" <?php if($attendee_detail['ticket_type'] == '2') { echo "checked"; }; ?>>VIP Single</label>
                                            </div>
                                            <div class="radio">
                                                <label><input type="radio" name="ticket_type" value="3" <?php if($attendee_detail['ticket_type'] == '3') { echo "checked"; }; ?>>VIP Plus One (Double)</label>
                                            </div>
                                            <div class="radio">
                                                <label><input type="radio" name="ticket_type" value="4" <?php if($attendee_detail['ticket_type'] == '4') { echo "checked"; }; ?>>Hired Help</label>
                                            </div>
                                            <div class="radio">
                                                <label><input type="radio" name="ticket_type" value="5" <?php if($attendee_detail['ticket_type'] == '5') { echo "checked"; }; ?>>Staff</label>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="control-label col-sm-3" for="confirmation_status">Confirmation Status:</label>
                                        <div class="col-sm-9 col-lg-5">
                                            <div class="radio">
                                                <label><input type="radio" name="confirmation_status" value="1" <?php if($attendee_detail['confirmation'] == '1') { echo "checked"; }; ?>>Maybe</label>
                                            </div>
                                            <div class="radio">
                                                <label><input type="radio" name="confirmation_status" value="2" <?php if($attendee_detail['confirmation'] == '2') { echo "checked"; }; ?>>Confirmed</label>
                                            </div>
                                            <div class="radio">
                                                <label><input type="radio" name="confirmation_status" value="3" <?php if($attendee_detail['confirmation'] == '3') { echo "checked"; }; ?>>Declined</label>
                                            </div>
                                            <div class="radio">
                                                <label><input type="radio" name="confirmation_status" value="0" <?php if($attendee_detail['confirmation'] == '0') { echo "checked"; }; ?>>Pending</label>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="control-label col-sm-3" for="invite">Invite:</label>
                                        <div class="col-sm-9 col-lg-5">
                                            <input type="text" class="form-control" id="invite" name="invite" value="<?php echo dinner_2017_invite_status($attendee_detail['invite']); ?>" readonly>
                                        </div>
                                    </div>
                                    <?php if($attendee_detail['invite'] == '0'): ?>
                                    <div class="form-group">
                                        <label class="control-label col-sm-3" for="invite">Send Invite:</label>
                                        <div class="col-sm-9 col-lg-1">
                                            <input type="checkbox" class="form-control" id="invite" name="send_invite" value="<?php echo $attendee_detail['reservation_code']; ?>">
                                        </div>
                                    </div>
                                    <?php endif; ?>

                                    <div class="form-group">
                                        <label class="control-label col-sm-3" for="comments">Comments:</label>
                                        <div class="col-sm-9 col-lg-5">
                                            <div class="input-group">
                                                <span class="input-group-addon"><i class="fa fa-envelope fa-fw"></i></span>
                                                <textarea id="comments" rows="3" name="comments" class="form-control" required></textarea>
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
                                                <div class="modal-body">Are you sure you want to save attendance? This action cannot be reversed.</div>
                                                <div class="modal-footer">
                                                    <input name="process" type="submit" class="btn btn-success" value="Proceed">
                                                    <button type="submit" name="close" onClick="window.close();" data-dismiss="modal" class="btn btn-danger">Close!</button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </form>
                                
                                
                                <hr/>
                                
                            </div>

                            <div class="col-sm-12">
                                <h5>Admin Remarks</h5>

                                <?php
                                if(isset($admin_remark) && !empty($admin_remark)) {
                                    foreach ($admin_remark as $row) {
                                        ?>
                                        <div class="row">
                                            <div class="col-sm-12">
                                                <div class="transaction-remarks">
                                                    <span id="trans_remark_author"><?php echo $row['admin_full_name']; ?></span>
                                                    <span id="trans_remark"><?php echo $row['comment']; ?></span>
                                                    <span id="trans_remark_date"><?php echo datetime_to_text($row['created']); ?></span>
                                                </div>
                                            </div>
                                        </div>
                                    <?php } } else { ?>
                                    <div class="row">
                                        <div class="col-sm-12">
                                            <div class="transaction-remarks">
                                                <span class="text-danger"><em>There is no remark to display.</em></span>
                                            </div>
                                        </div>
                                    </div>
                                <?php } ?>

                            </div>
                        </div>
                    </div>

                    <!-- Unique Page Content Ends Here
                    ================================================== -->
                    
                </div>
                
            </div>
        </div>
        <?php require_once 'layouts/footer.php'; ?>
    </body>
</html>