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
        $subject = "InstaFxNg Dinner 2017: THE ETHNIC IMPRESSION";
        $ticket_type = dinner_ticket_type($ticket_type);
        $message = <<<MAIL

<head>
  <link href='https://fonts.googleapis.com/css?family=Lobster|Kreon:400,700' rel='stylesheet' type='text/css'>
  <meta content="width=device-width, initial-scale=1.0" name="viewport">
  <meta http-equiv="content-type" content="text-html; charset=utf-8">
</head>

<style>
  body {
    margin: 0;
    color: #494140;
    font-family: "Kreon", serif;
    font-weight: 400;
    font-size: 30px;
  }

  .container {
    width: 100%;
    margin: 0 auto;
  }

  section {
    position: relative;
    float: left;
    width: 685px;
  }
  section .special {
    -moz-box-sizing: border-box;
    -webkit-box-sizing: border-box;
    box-sizing: border-box;
    position: relative;
    height: 60px;
    padding: 10px 150px 0;
    background-color: #494140;
    color: #fff;
    text-align: center;
  }
  section .special:nth-child(2n+1) {
    background-color: #93ACA2;
  }
  section .special:nth-child(6), section .special:nth-child(7) {
    z-index: 1;
  }
  section .circle {
    position: absolute;
    top: 10px;
    left: 215px;
    width: 255px;
    height: 255px;
    background-color: #fff;
    border-radius: 50%;
    box-shadow: 0px 10px 4px 0px rgba(0, 0, 0, 0.5);
    text-align: center;
    line-height: 30px;
    z-index: 1;
  }
  section .circle .event {
    width: 150px;
    margin: 25px auto 25px;
    font-size: 1.12em;
    font-weight: 700;
    text-transform: uppercase;
  }
  section .circle .title {
    color: #93ACA2;
    font-family: "Lobster", cursive;
    font-size: 2.48em;
  }
  section .seats {
    position: absolute;
    top: 10px;
    left: 30px;
    color: #fff;
    font-weight: 700;
  }
  section .seats span .barcode {
    display: inline-block;
  }
  section .seats .label {
    width: 40px;
    margin-right: 20px;
    padding-top: 8px;
    font-size: 0.36em;
    font-weight: 400;
    text-align: right;
    text-transform: uppercase;
    vertical-align: top;
  }
  
    section .barcode .label {
    width: 60px;
    margin-right: 20px;
    padding-top: 8px;
    font-size: 0.36em;
    font-weight: 400;
    text-align: left;
    text-transform: uppercase;
    vertical-align: top;
  }


  aside {
    float: right;
    width: 110px;
  }
  aside figure {
    height: 100%;
    margin: 0;
    text-align: center;
  }
  aside figure img {
    margin-top: 25px;
  }
</style>
  <div class="container">
    <section>
      <div class="circle">
        <img style="width: 100%; height: 100%; border-radius: 50%" src="http://instafxng.master/dinner_2017/assets/images/dinner_2017.jpg">
      </div>
      <div class="special">
      <div style="position: absolute;
    top: 0px;
    right: 0px;">
          <span><img style="height: 60px; width: 60px;" src="https://chart.googleapis.com/chart?chs=300x300&cht=qr&chl=$reservation_code&choe=UTF-8"></span>
        </div>
      </div>
      <div class="special"></div>
      <div class="special">
        <div class="seats">
          <span>NAME: $full_name</span>
        </div>
      </div>
      <div class="special">
      <div style="200px" class="seats">
          <span>TICKET TYPE: $ticket_type</span>
        </div>
      </div>
      <div class="special">
        <div class="seats">
          <span>TICKET NO: $reservation_code</span>
        </div>
        SUNDAY, DECEMBER 17 2017
      </div>
      <div class="special">
        <b>Four Points by Sheraton Lagos, 
        Plot 9/10, Block 2, Oniru Chieftaincy Estate,
        Lagos State, Nigeria.</b>
        
      </div>
    </section>
  </div>
MAIL;

        /*//echo $message;
        $dompdf->loadHtml($message);
        // Render the HTML as PDF
        $dompdf->render();
        // Output the generated PDF (1 = download and 0 = preview)
        $dompdf->stream($subject,array("Attachment"=>1));*/

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
                                                <label><input type="radio" name="ticket_type" value="5" <?php if($attendee_detail['ticket_type'] == '5') { echo  "checked"; }; ?>>Staff</label>
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