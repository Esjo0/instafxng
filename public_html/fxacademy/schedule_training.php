<?php
require_once '../init/initialize_client.php';
$thisPage = "";

if (!$session_client->is_logged_in()) {
    redirect_to("login.php");
}

$user_code = $_SESSION['client_unique_code'];

if (isset($_POST['schedule'])) {
    $date = $db_handle->sanitizePost($_POST['date']);
    $id = $db_handle->sanitizePost($_POST['id']);
    $client_name = $_SESSION['first_name'];
    $client_email = $_SESSION['client_email'];
    $subject = "FOREX ACADEMY TRAINING";
    $_date = datetime_to_textday($date) . " " . datetime_to_text($date);
    $body = <<<MAIL
Dear $client_name,
You have Successfully Scheduled a training with our analyst for
$_date.
MAIL;
    $query = "SELECT * FROM training_schedule_students WHERE user_code = '$user_code' AND status = '0'";
    $result = $db_handle->numRows($query);
    if ($result == 0) {
        $query = "INSERT IGNORE INTO training_schedule_students (user_code, schedule_id, status) VALUE('$user_code', $id, '0')";
        $result2 = $db_handle->runQuery($query);
        if ($result2) {
            $system_object->send_email($subject, $body, $client_email, $client_name);
            $message_success = "Successfully Scheduled for A free Training with our analyst";
        } else {
            $message_error = "Schedule not successful. Kindly Try again.";
        }
    } else {
        $message_error = "You Have and already existing schedule kindly <a href='https://instafxng.com/contact_info.php'>contact support</a> for a reschedule";
    }
}

$from_date = date('Y-m-d');
$to_date = date('Y-m') . "-31";
$query = "SELECT schedule_id,schedule_date, location, schedule_mode, location FROM training_schedule_dates WHERE schedule_type = '1' AND (STR_TO_DATE(schedule_date, '%Y-%m-%d') BETWEEN '$from_date' AND '$to_date')";
$result = $db_handle->runQuery($query);
$available_dates = $db_handle->fetchArray($result);

$query = "SELECT  tsd.schedule_mode, tsd.location, tsd.schedule_type, tss.id, tss.status, CONCAT(u.last_name, SPACE(1), u.first_name) AS full_name, u.phone, u.email, tsd.schedule_date, u.user_code
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
                                                } ?>
                                                <div class="col-sm-4">
                                                    <div class="row">
                                                        <input type="hidden" name="date"
                                                               value="<?php echo $schedule_date; ?>">
                                                        <div class="col-md-2">
                                                            <input type="radio" name="id"
                                                                   value="<?php echo $schedule_id; ?>" required/>
                                                        </div>
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
                                    <?php if ($schedule_mode == 2) {
                                         echo "<span class='text-center'><b>Training Venue</b> - " . office_addresses($location) . "</span><br>";
                                    }?>
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