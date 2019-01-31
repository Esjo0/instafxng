<?php
require_once '../init/initialize_client.php';
$thisPage = "";

if (!$session_client->is_logged_in()) {
    redirect_to("login.php");
}

$get_learning_position = $education_object->get_clients_max_lesson_completed($_SESSION['client_unique_code']);
$highest_lesson_published = $education_object->get_highest_lesson_published();

if($get_learning_position  != $highest_lesson_published){
    redirect_to("./");
}

$user_code = $_SESSION['client_unique_code'];

if (isset($_POST['schedule'])) {
    $date = $db_handle->sanitizePost($_POST['date']);
    $date_ = date_create($date);
    if(datetime_to_textday($date) != 'Tue'){
        date_add($date_, date_interval_create_from_date_string('8 days'));
        $follow_date = date_format($date_, 'Y-m-d H:i:s');
        date_add($date_, date_interval_create_from_date_string('7 days'));
        $final_date = date_format($date_, 'Y-m-d H:i:s');
    }elseif(datetime_to_textday($date) == 'Tue'){
        date_add($date_, date_interval_create_from_date_string('8 days'));
        $follow_date = date_format($date_, 'Y-m-d H:i:s');
        date_add($date_, date_interval_create_from_date_string('7 days'));
        $final_date = date_format($date_, 'Y-m-d H:i:s');
    }
    $id = $db_handle->sanitizePost($_POST['id']);
    $mode = $db_handle->sanitizePost($_POST['mode']);
    $mode = training_mode($mode);
    $client_name = $_SESSION['first_name'];
    $client_email = $_SESSION['client_email'];

    $subject = "Congratulations $client_name! You have been scheduled.";
    $_date = datetime_to_textday($date) . " " . datetime_to_text($date);
    $_date_follow = datetime_to_textday($follow_date) . " " . datetime_to_text($follow_date);
    $_date_final = datetime_to_textday($final_date) . " " . datetime_to_text($final_date);
    $core_msg = <<<MAIL
Dear $client_name,

Congratulations on the successful completion of the Fxacademy, we are so proud of you!

Kindly be informed that, your personalized $mode training with the analyst has been successfully scheduled as listed below.
<table class="table table-responsive hover">
<tr>
<th>Classes</th>
<th>Time</th>
</tr>
<tr>
<td>First Class</td>
<td>$_date</td>
</tr>
<tr>
<td>Follow Up Class</td>
<td>$_date_follow</td>
</tr>
<tr>
<td>Final Class</td>
<td>$_date_final</td>
</tr>
</table>

We look forward to hosting you.
MAIL;;
    $body =
        <<<MAIL
        <div style="background-color: #F3F1F2">
    <div style="max-width: 80%; margin: 0 auto; padding: 10px; font-size: 14px; font-family: Verdana;">
        <img src="https://instafxng.com/images/ifxlogo.png" />
        <hr />
        <div style="background-color: #FFFFFF; padding: 15px; margin: 5px 0 5px 0;">

            $core_msg

            <br /><br />
            <p>Best Regards,</p>
            <br /><br />
        </div>
        <hr />
        <div style="background-color: #EBDEE9;">
            <div style="font-size: 11px !important; padding: 15px;">
                <p style="text-align: center"><span style="font-size: 12px"><strong>We're Social</strong></span><br /><br />
                    <a href="https://facebook.com/InstaFxNg"><img src="https://instafxng.com/images/Facebook.png"></a>
                    <a href="https://twitter.com/instafxng"><img src="https://instafxng.com/images/Twitter.png"></a>
                    <a href="https://www.instagram.com/instafxng/"><img src="https://instafxng.com/images/instagram.png"></a>
                    <a href="https://www.youtube.com/channel/UC0Z9AISy_aMMa3OJjgX6SXw"><img src="https://instafxng.com/images/Youtube.png"></a>
                    <a href="https://linkedin.com/company/instaforex-ng"><img src="https://instafxng.com/images/LinkedIn.png"></a>
                </p>
                <p><strong>Head Office Address:</strong> TBS Place, Block 1A, Plot 8, Diamond Estate, Estate Bus-Stop, LASU/Isheri road, Isheri Olofin, Lagos.</p>
                <p><strong>Lekki Office Address:</strong> Block A3, Suite 508/509 Eastline Shopping Complex, Opposite Abraham Adesanya Roundabout, along Lekki - Epe expressway, Lagos.</p>
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
    $query = "SELECT * FROM training_schedule_students WHERE user_code = '$user_code' AND status IN('0', '1', '2', '4', '5') ";
    $result = $db_handle->numRows($query);
    if ($result == 0) {
        $query = "INSERT IGNORE INTO training_schedule_students (user_code, schedule_id, follow_up_class, final_class, status) VALUE('$user_code',  $id, '$follow_date', '$final_date', '0')";
        $result2 = $db_handle->runQuery($query);
        if ($result2) {
            $system_object->send_email($subject, $body, $client_email, $client_name);
            $message_success = "Successfully Scheduled for A free Training with our analyst";
        } else {
            $message_error = "Schedule not successful. Kindly Try again.";
        }
    } else {
        $message_error = "You have an already existing schedule kindly <a href='https://instafxng.com/contact_info.php'>contact support</a> for a reschedule";
    }
}

$from_date = date('Y-m-d');
$to_date = date('Y-m') . "-31";
$query = "SELECT schedule_id,schedule_date, location, schedule_mode, location FROM training_schedule_dates WHERE schedule_type = '1' AND (STR_TO_DATE(schedule_date, '%Y-%m-%d') BETWEEN '$from_date' AND '$to_date')";
$result = $db_handle->runQuery($query);
$available_dates = $db_handle->fetchArray($result);

$query = "SELECT tss.follow_up_class, tss.final_class, tsd.schedule_mode, tsd.location, tsd.schedule_type, tss.id, tss.status, CONCAT(u.last_name, SPACE(1), u.first_name) AS full_name, u.phone, u.email, tsd.schedule_date, u.user_code
    FROM training_schedule_students AS tss
    INNER JOIN user AS u ON u.user_code = tss.user_code
    INNER JOIN training_schedule_dates AS tsd ON tsd.schedule_id = tss.schedule_id
    WHERE  tss.user_code = '$user_code' AND (tss.status = '0' OR tss.status = '2' OR tss.status = '3') ORDER BY tsd.schedule_date ASC";
$result = $db_handle->runQuery($query);
$schedules = $db_handle->fetchArray($result);

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <base target="_self">
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Instaforex Nigeria</title>
    <meta name="title" content=""/>
    <meta name="keywords" content="">
    <meta name="description" content="">
    <?php require_once 'layouts/head_meta.php'; ?>
</head>
<body>
<?php require_once 'layouts/header.php'; ?>

<!-- Main Body: The is the main content area of the web site, contains a side bar  -->
<div id="main-body" class="container-fluid">
    <div class="row no-gutter">

        <!-- Main Body - Content Area: This is the main content area, unique for each page  -->
        <div id="main-body-content-area" class="col-md-12">

            <!-- Unique Page Content Starts Here
            ================================================== -->
            <?php require_once 'layouts/navigation.php'; ?>

            <div id="main-container" class="section-tint super-shadow">
                <div class="row">
                    <div class="col-md-12">
                        <h1 class="text-center">Forex Trading Academy Milestone</h1>
                        <p class="text-center"><strong>Congratulations</strong>, Kindly Schedule a Personal Training
                            Time with Our Analyst.</p>
                        <hr/>
                        <?php require_once 'layouts/feedback_message.php'; ?>
                        <li class="list-group-item d-flex justify-content-between lh-condensed" style="display:block">
                            <p><strong>Available Date - Select Your Prefered Time and click on submit</strong></p>
                            <div class="well">
                                <form data-toggle="validator" class="form-horizontal" role="form" method="post"
                                      action="">
                                    <div class="form-group row">
                                        <?php if (!empty($available_dates)) {
                                            foreach ($available_dates AS $row) {
                                                extract($row);
                                                if ($schedule_mode == 2) {
                                                    $venue = "<span class='text-center'><b>Training Venue</b> - " . office_addresses($location) . "</span><br>";
                                                }elseif($schedule_mode == 1){
                                                    $venue = "<span>Download & Install the ZOOM App from <a target='_blank' href='https://zoom.us'>www.zoom.us</a> , from Google PlayStore,
                                        or the Apple Store. You will be contacted by your Instructor for the Meeting ID to
                                        join the Online Training Class at the exact scheduled time.</span>";
                                                } ?>
                                                <div class="col-sm-4">
                                                    <div class="row">
                                                        <input type="hidden" name="date"
                                                               value="<?php echo $schedule_date; ?>">
                                                        <div class="col-md-2">
                                                            <input type="radio" name="id"
                                                                   value="<?php echo $schedule_id; ?>" required/>
                                                        </div>
                                                        <input value="<?php echo $schedule_mode; ?>" name="mode" type="hidden">
                                                        <div class="col-md-10">

                                                            <?php echo "<h5>" . datetime_to_textday($schedule_date) . " " . datetime_to_text($schedule_date) . " </h5><br><span class='text-center'><b>Training Type</b> - " . training_mode($schedule_mode) . "</span><br><br>" . $venue; ?>
                                                        </div>
                                                    </div>
                                                </div>
                                            <?php }
                                            echo"<p class='text-center'>
                                        <button name='schedule' class='btn btn-success' type='submit'>SUBMIT</button>
                                    </p>";
                                        }else{echo "<p class='text-center'>There are no available schedule time at the moment. Check back latter!!!</p>";} ?>
                                    </div>

                                </form>
                            </div>
                        </li>
                        <li class="list-group-item d-flex justify-content-between lh-condensed" style="display:block">
                        <?php if(!empty($schedules)) {
                            echo "<p><b>YOUR TRAINING</b></p>";
                            foreach($schedules AS $row){
                                extract($row);
                                ?>


                            <div class="panel panel-info">
                                <div class="panel-heading"><b><?php echo datetime_to_textday($schedule_date) . " " . datetime_to_text($schedule_date)?></b></div>
                                <div class="panel-body">
                                    <?php echo "<span class='text-center'><b>Training Type</b> - " . training_mode($schedule_mode) . "</span><hr>"?>
                                    <?php if ($schedule_mode == '2') {
                                         echo "<span class='text-center'><b>Training Venue</b> - " . office_addresses($location) . "</span><br>";
                                    }elseif($schedule_mode == '1'){
                                        echo "<span>Download & Install the ZOOM App from <a target='_blank' href='https://zoom.us'>www.zoom.us</a> , from Google PlayStore,
                                        or the Apple Store. You will be contacted by your Instructor for the Meeting ID to
                                        join the Online Training Class at the exact scheduled time.</span>";
                                    }?>
                                    <?php if($follow_up_class != NULL && !empty($follow_up_class)){?>
                                    <table class="table table-responsive hover">
                                        <tr>
                                            <td>Follow Up Class</td>
                                            <td><?php echo datetime_to_textday($follow_up_class) . " " . datetime_to_text($follow_up_class) ?></td>
                                        </tr>
                                        <tr>
                                            <td>Final Class</td>
                                            <td><?php echo datetime_to_textday($final_class) . " " . datetime_to_text($final_class) ?></td>
                                        </tr>
                                    </table>
                                    <?php }?>
                                </div>
                            </div>


                            <?php }
                        }?>
                        </li>
                    </div>
                </div>
            </div>

        </div>
    </div>

    <?php require_once 'layouts/footer.php'; ?>
</body>
</html>