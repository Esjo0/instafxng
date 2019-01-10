<?php
require_once 'init/initialize_general.php';
$thisPage = "Education";
$today = date('Y-m-d');
if (isset($_POST['reserve_seat'])) {
    $email = $db_handle->sanitizePost(trim($_POST['email']));
    $email = filter_var($email, FILTER_VALIDATE_EMAIL);
    $venue = $db_handle->sanitizePost($_POST['venue']);
    if($venue == 1){$date = $db_handle->sanitizePost($_POST['date1']);}
    if($venue == 2){$date = $db_handle->sanitizePost($_POST['date2']);}
    if($venue == 3){$date = $db_handle->sanitizePost($_POST['date3']);}

    $query = "SELECT user_code FROM user WHERE email = '$email' LIMIT 1";
    $result = $db_handle->runQuery($query);
    $result = $db_handle->fetchAssoc($result);
    if ($result) {
        foreach ($result AS $row) {
            extract($row);
        }
        $query = "SELECT * FROM account_audit WHERE user_code = '$user_code'";
        $numrows = $db_handle->numRows($query);
        if ($numrows == 0) {
            $query = "INSERT INTO account_audit (user_code, audit_location, audit_date) VALUES ('$user_code', '$venue', '$date')";
            $result = $db_handle->runQuery($query);
            if ($result) {
                $message_success = "Your Seat Has been successfully booked";
            } else {
                $message_error = "Not successful Kindly try Again";
            }
        } else {
            $message_error = "You have registered Earlier.";
        }
    } else {
        $message_error = "This email Address is not registed with InstaFxNg. Fill Step 1 and 2 to open an ILPR account<a target='_blank' href='https://instafxng.com/live_account.php'> Click Here NOW</a>";
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <base target="_self">
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Instaforex Nigeria | Forex Account Audit</title>
    <meta name="title" content="Instaforex Nigeria | Forex Account Audit"/>
    <meta name="keywords"
          content="instaforex, forex seminar, forex trading seminar, forex traders froum, how to trade forex, trade forex, instaforex nigeria.">
    <meta name="description"
          content="Learn how to trade forex, get free information about the forex market in our forex traders forum">
    <link rel="stylesheet" href="css/free_seminar.css">
    <?php require_once 'layouts/head_meta.php'; ?>
    <link rel="stylesheet" href="css/prettyPhoto.css">
    <script>
        function select_date(enter){
            if (enter == 1){
                document.getElementById("entry1").style.display = "block";
                document.getElementById("entry2").style.display = "none";
                document.getElementById("entry3").style.display = "none";
                document.getElementById("entry_channel2").required = false;
                document.getElementById("entry_channel3").required = false;
                document.getElementById("entry_channel1").required = true;
            } else if (enter == 2){
                document.getElementById("entry1").style.display = "none";
                document.getElementById("entry2").style.display = "block";
                document.getElementById("entry3").style.display = "none";
                document.getElementById("entry_channel1").required = false;
                document.getElementById("entry_channel3").required = false;
                document.getElementById("entry_channel2").required = true;
            } else if (enter == 3) {
                document.getElementById("entry1").style.display = "none";
                document.getElementById("entry2").style.display = "none";
                document.getElementById("entry3").style.display = "block";
                document.getElementById("entry_channel1").required = false;
                document.getElementById("entry_channel2").required = false;
                document.getElementById("entry_channel3").required = true;
            }
        }
    </script>
</head>
<body>
<?php require_once 'layouts/header.php'; ?>
<!-- Main Body: The is the main content area of the web site, contains a side bar  -->
<div id="main-body" class="container-fluid">
    <div class="row no-gutter">
        <?php require_once 'layouts/topnav.php'; ?>
        <!-- Main Body - Content Area: This is the main content area, unique for each page  -->
        <div id="main-body-content-area" class="col-md-8 col-md-push-4 col-lg-9 col-lg-push-3">

            <!-- Unique Page Content Starts Here
            ================================================== -->

            <div class="section-tint super-shadow">
                <div class="row text-center">
                    <div class="col-sm-12 text-danger">
                        <h3><strong>Account Audit Registration</strong></h3>
                    </div>
                </div>

                <br/>

                <div class="row" id="signup-section">

                    <div class="row">
                        <div class="col-sm-12">
                            <?php if (isset($message_success)) { ?>
                                <div class="alert alert-success">
                                    <a href="#" class="close" data-dismiss="alert"
                                       aria-label="close">&times;</a>
                                    <strong>Success!</strong> <?php echo $message_success; ?>
                                </div>
                            <?php } ?>

                            <?php if (isset($message_error)) { ?>
                                <div class="alert alert-danger">
                                    <a href="#" class="close" data-dismiss="alert"
                                       aria-label="close">&times;</a>
                                    <strong>Oops!</strong> <?php echo $message_error; ?>
                                </div>
                            <?php } ?>
                        </div>
                    </div>

                    <span id="opt"></span>

                    <section id="more">

                        <div class="row">
                            <div class="col-sm-12">
                                <form data-toggle="validator" role="form" method="post"
                                      action="">
                                    <h3 class="text-uppercase text-center signup-header">RESERVE A SEAT NOW</h3>
                                    <br/>

                                    <div class="form-group has-feedback">
                                        <label for="email" class="control-label">Your Email Address</label>
                                        <div class="input-group margin-bottom-sm">
                                                    <span class="input-group-addon"><i
                                                            class="fa fa-envelope-o fa-fw"></i></span>
                                            <input type="email" class="form-control" id="email" name="email"
                                                   placeholder="Your Email" data-error="Invalid Email" required>
                                        </div>
                                        <span class="glyphicon form-control-feedback" aria-hidden="true"></span>
                                        <div class="help-block with-errors"></div>
                                    </div>

                                    <div class="form-group">
                                        <label for="venue" class="control-label">Choose your venue</label>
                                        <div class="radio">
                                            <label><input onchange="select_date(1)" id="offline1" type="radio" name="venue"
                                                          value="1" required>Block 1A, Plot
                                                8, Diamond Estate, LASU/Isheri road, Isheri Olofin,
                                                Lagos.</label>
                                        </div>
                                        <div class="radio">
                                            <label><input onchange="select_date(2)" id="offline2" type="radio" name="venue"
                                                          value="2" required>Block A3, Suite 508/509
                                                Eastline Shopping Complex, Opposite Abraham Adesanya Roundabout,
                                                along Lekki - Epe expressway, Lagos.</label>
                                        </div>
                                        <div class="radio">
                                                <label><input onchange="select_date(3)" id="online" type="radio" name="venue"
                                                          value="3" required>Online -- Download Zoom Video
                                                Conferencing app from
                                                <a target="_blank" href="http://zoom.us">zoom.us</a> You will contacted and given the
                                                meeting ID before
                                                the session starts
                                            </label>
                                        </div>
                                        <span class="glyphicon form-control-feedback" aria-hidden="true"></span>
                                    </div>
                                        <label for="entry_channel" class="control-label">Select your convenient audit
                                            date</label>

                                        <div class="form_group" id="entry1" style="display:none;">
                                            <select id="entry_channel1" class="form-control" name="date1">
                                                <option value="">Choose a date</option>
                                                <?php
                                                $query = "SELECT * FROM account_audit_date WHERE venue = '1' AND STR_TO_DATE(audit_date, '%Y-%m-%d') >= '$today' AND audit_date NOT IN (SELECT audit_date FROM account_audit WHERE audit_location = '1')";
                                                $result = $db_handle->runQuery($query);
                                                $result = $db_handle->fetchAssoc($result);
                                                foreach ($result as $row_loc) {
                                                    extract($row_loc)
                                                    ?>
                                                    <option
                                                        value="<?php echo $audit_date; ?>"><?php echo datetime_to_textday($audit_date) . " " . datetime_to_text($audit_date) ?></option>
                                                <?php } ?>
                                            </select>
                                        </div>

                                        <div class="form_group" id="entry2" style="display:none;">
                                            <select id="entry_channel2" class="form-control" name="date2" >
                                                <option value="">Choose a date</option>
                                                <?php
                                                $query = "SELECT * FROM account_audit_date WHERE venue = '2' AND STR_TO_DATE(audit_date, '%Y-%m-%d') >= '$today' AND audit_date NOT IN (SELECT audit_date FROM account_audit WHERE audit_location = '2')";
                                                $result = $db_handle->runQuery($query);
                                                $result = $db_handle->fetchAssoc($result);
                                                foreach ($result as $row_loc) {
                                                    extract($row_loc)
                                                    ?>
                                                    <option
                                                        value="<?php echo $audit_date; ?>"><?php echo datetime_to_textday($audit_date) . " " . datetime_to_text($audit_date) ?></option>
                                                <?php } ?>
                                            </select>
                                        </div>

                                        <div class="form_group" id="entry3" style="display:none;">
                                            <select id="entry_channel3" class="form-control" name="date3" >
                                                <option value="">Choose a date</option>
                                                <?php
                                                $query = "SELECT * FROM account_audit_date WHERE venue = '3' AND STR_TO_DATE(audit_date, '%Y-%m-%d') >= '$today' AND audit_date NOT IN (SELECT audit_date FROM account_audit WHERE audit_location = '3')";
                                                $result = $db_handle->runQuery($query);
                                                $result = $db_handle->fetchAssoc($result);
                                                foreach ($result as $row_loc) {
                                                    extract($row_loc)
                                                    ?>
                                                    <option
                                                        value="<?php echo $audit_date; ?>"><?php echo datetime_to_textday($audit_date) . " " . datetime_to_text($audit_date) ?></option>
                                                <?php } ?>
                                            </select>
                                        </div>

                                        <span class="glyphicon form-control-feedback" aria-hidden="true"></span>

                                    <div><br/></div>
                                    <div class="form-group">
                                        <button type="submit" name="reserve_seat" class="btn btn-default btn-lg">Reserve
                                            Your Seat&nbsp;<i class="fa fa-chevron-circle-right"></i></button>
                                    </div>

                                    <small>All fields are required</small>
                                </form>
                            </div>
                        </div>

                    </section>

                </div>
                <div class="row text-center">
                    <h2 class="color-fancy">For further enquiries, please call 08182045184, 07081036115</h2>
                </div>
            </div>
            <!-- Unique Page Content Ends Here
            ================================================== -->

        </div>
        <!-- Main Body - Side Bar  -->
        <div id="main-body-side-bar" class="col-md-4 col-md-pull-8 col-lg-3 col-lg-pull-9 left-nav">
            <?php require_once 'layouts/sidebar.php'; ?>
        </div>
    </div>
</div>
<?php require_once 'layouts/footer.php'; ?>

</body>
</html>