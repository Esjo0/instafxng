<?php
require_once("../init/initialize_admin.php");
if (!$session_admin->is_logged_in())
{
    redirect_to("login.php");
}

function addImageWatermark($SourceFile, $WaterMark, $DestinationFile=NULL, $opacity)
{
    $main_img = $SourceFile;
    $watermark_img = $WaterMark;
    $padding = 5;
    $opacity = $opacity;
    // create watermark
    $watermark = imagecreatefrompng($watermark_img);
    $image = imagecreatefromjpeg($main_img);
    if(!$image || !$watermark) die("Error: main image or watermark image could not be loaded!");
    $watermark_size = getimagesize($watermark_img);
    $watermark_width = $watermark_size[0];
    $watermark_height = $watermark_size[1];
    $image_size = getimagesize($main_img);
    $dest_x = $image_size[0] - $watermark_width - $padding;
    $dest_y = $image_size[1] - $watermark_height - $padding;
    imagecopymerge($image, $watermark, $dest_x, $dest_y, 0, 0, $watermark_width, $watermark_height, $opacity);
    if ($DestinationFile<>'')
    {
        imagejpeg($image, $DestinationFile, 100);
    } else
    {
        header('Content-Type: image/jpeg');
        imagejpeg($image);
    }
    imagedestroy($image);
    imagedestroy($watermark);
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

$attendee_detail = $db_handle->fetchAssoc($db_handle->runQuery("SELECT * FROM dinner_2017 WHERE reservation_code = '$reg_code'"));
$attendee_detail = $attendee_detail[0];

extract($attendee_detail);

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
        $imgPath = '../images/final iv.jpg';
        $image = imagecreatefromjpeg($imgPath);
        $color = imagecolorallocate($image, 255, 255, 255);
        //NAME
        $string = strtoupper($full_name);
        $fontSize = 5;
        $x = 67;
        $y = 191;
        imagestring($image, $fontSize, $x, $y, $string, $color);
        //TICKET TYPE
        /*$string = strtoupper(dinner_ticket_type($ticket_type));
        $fontSize = 5;
        $x = 126;
        $y = 194;*/
        imagestring($image, $fontSize, $x, $y, $string, $color);
        //tICKET NO
        $string = strtoupper($reservation_code);
        $fontSize = 5;
        $x = 112;
        $y = 248;
        imagestring($image, $fontSize, $x, $y, $string, $color);

        //barcode
        $watermark = imagecreatefrompng('https://chart.googleapis.com/chart?chs=68x60&cht=qr&chl='.$reservation_code.'&choe=UTF-8');
        $water_width = imagesx($watermark);
        $water_height = imagesy($watermark);
        $main_width = imagesx($image);
        $main_height = imagesy($image);
        $dime_x = 626;
        $dime_y = 2;
        imagecopy($image, $watermark, $dime_x, $dime_y, 0, 0, $water_width, $water_height);
        $target_dir = "../dinner_2017/ivs/";

        if (!file_exists($target_dir))
        {
            mkdir($target_dir, 0777);
        }

        $newfilename = $reservation_code. '.jpg';
        $target_file = $target_dir.$newfilename;
        $ivs = imagejpeg($image, $target_file, 100);
        imagedestroy($image);

        $subject = "InstaFxNg Dinner 2017: THE ETHNIC IMPRESSION";
        $ticket_type = dinner_ticket_type($ticket_type);

        if($ivs)
        {
            $r_code = encrypt($reservation_code);
            $target_file = str_replace('../dinner_2017/', '', $target_file);
            $ticket_url = str_replace('ivs/', '', $target_file);
            $from_name ="";
            $stg = strtoupper($reservation_code);
            $message = <<<MAIL
            <div style="background-color: #F3F1F2">
            <div style="max-width: 80%; margin: 0 auto; padding: 10px; font-size: 14px; font-family: Verdana;">
            <img src="https://instafxng.com/images/ifxlogo.png" />
            <hr />
            <div style="background-color: #FFFFFF; padding: 15px; margin: 5px 0 5px 0;">
            <p>Yay! Its 2 days to the InstaFxNg Ethnic Impression Dinner.</p>
            <p>Did I mention that there will be a raffle draw and you can win amazing prizes during the InstaFxNg Ethnic Impression Dinner?</p>
			<p>Yea! We are all set to receive you on Sunday 17th December 2017 by 5PM and there's just one more thing to do...</p>
			<p><b>Your Ticket Number is $stg</b></p>
			<p>Kindly click on the image below to download your invite for the dinner.</p>
			<center><a href="https://instafxng.com/dinner_2017/ivs/download.php?x=$ticket_url"><img  style="width: 70%; height: 50%;" src="https://instafxng.com/dinner_2017/ivs/$ticket_url" ></a></center>
			<p>The invite grants you to the dinner and it will also be used in the raffle draw, 
			so endeavour to either download it on your mobile device or print it out and bring it along.</p>
            <p>I am really excited and I cannot wait to welcome you personally.</p>
            <p><a href="https://instafxng.com/dinner_2017/ivs/download.php?x=$ticket_url">Don't forget to download your ticket here.</a></p>
            <p>See you on Sunday by 5PM!</p>
            <br /><br />
            <p>Best Regards,</p>
            <p>Mercy,</p>
            <p>Marketing Executive,</p>
            <p>www.instafxng.com</p>
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
                <p><strong>Lekki Office Address:</strong> Block A3, Suite 508/509 Eastline Shopping Complex, Opposite Abraham Adesanya Roundabout, along Lekki - Epe expressway, Lagos. </p>
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
            $system_object->send_email($subject, $message, $email, $full_name, $from_name);
            $update = $db_handle->runQuery("UPDATE dinner_2017 SET invite = '1', iv_url = '$target_file' WHERE reservation_code = '$reservation_code'");
            $message_success = "You have successfully sent an invite.";
        }
        else
        {
            $message_success = "You have successfully sent an invite.";
        }

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

if ($_SERVER['REQUEST_METHOD'] == 'POST' && $_POST['attendance_confirmed'] == true)
{
    extract($_POST);
    $query = "UPDATE dinner_2017 SET confirmation = '4' WHERE reservation_code = '$reservation_code' ";
    $update = $db_handle->runQuery($query);
    if($update)
    {
        $message_success = "You have successfully <b class='text-uppercase'>confirmed</b> this guests attendance.";
    }
}

if ($_SERVER['REQUEST_METHOD'] == 'POST' && $_POST['attendance_declined'] == true)
{
    extract($_POST);
    $query = "UPDATE dinner_2017 SET confirmation = '2' WHERE reservation_code = '$reservation_code' ";
    $update = $db_handle->runQuery($query);
    if($update)
    {
        $message_success = "You have successfully <b class='text-uppercase'>declined</b> this guests attendance.";
    }
}

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
                                <form data-toggle="validator" class="form-horizontal" role="form" method="post" action="<?php echo $_SERVER['REQUEST_URI']; ?>">
                                    <input name="reservation_code" type="hidden" value="<?php echo $attendee_detail['reservation_code']; ?>">
                                    <input name="<?php if($attendee_detail['confirmation'] == '4'){echo 'attendance_declined';}else{echo 'attendance_confirmed';} ?>" value="<?php if($attendee_detail['confirmation'] == '4'){echo 'Will Not Be In Attendance';}else{echo 'Will Be In Attendance';} ?>" type="submit" class="pull-right btn btn-lg btn-success <?php if($attendee_detail['confirmation'] == '4'){echo 'btn-danger';}else{echo 'btn-success';} ?>" title="<?php if($attendee_detail['confirmation'] == '4'){echo 'Guest has confirmed his/her attendance.';}else{echo 'Confirm that the guest will attend the event.';} ?> " />
                                </form>
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
                                    <div  class="form-group">
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
                                                <label><input type="radio" name="ticket_type" value="5" <?php if($attendee_detail['ticket_type'] == '5') { echo  "checked"; }; ?>>Staff</label>
                                            </div>
                                        </div>
                                    </div>
                                    <div  class="form-group">
                                        <label class="control-label col-sm-3" for="confirmation_status">Confirmation Status:</label>
                                        <div class="col-sm-9 col-lg-5">
                                            <div class="radio">
                                                <label><input type="radio" name="confirmation_status" value="4" <?php if($attendee_detail['confirmation'] == '4') { echo "checked"; }; ?>><b class="text-uppercase text-success">Will Be In Attendance</b></label>
                                            </div>
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
                                    <?php if($attendee_detail['invite'] == '1'): ?>
                                    <div class="form-group">
                                        <label class="control-label col-sm-3" for="invite">Ticket No:</label>
                                        <div class="col-sm-9 col-lg-5">
                                        <input type="text" class="form-control" id="ticket_no" name="ticket_no" value="<?php echo $attendee_detail['reservation_code']; ?>" readonly>
                                        </div>
                                    </div>
                                    <?php endif; ?>

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
                                                <div class="modal-body">Are you sure you want to update this reservation? </div>
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

                            <div style="max-height: 300px; overflow-x: scroll" class="col-sm-12">
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