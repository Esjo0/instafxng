<?php
require_once 'init/initialize_general.php';
header('Location: https://instafxng.com/forex-income/');

$thisPage = "Education";
$next_seminar_date = NEWSEMINARDATE;

if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    $full_name = $db_handle->sanitizePost(trim($_POST['name']));
    $email_address = $db_handle->sanitizePost(trim($_POST['email_add']));
    $phone_number = $db_handle->sanitizePost(trim($_POST['phone']));
    $venue = $db_handle->sanitizePost(trim($_POST['venue']));
    $date = date("Y-m-d H:i:s");

    // Perform the necessary validations and display the appropriate feedback
    if(empty($full_name) || empty($email_address) || empty($phone_number)  || empty($venue)) {
        $message_error = "All fields must be filled.";
    } elseif (!check_email($email_address)) {
        $message_error = "Invalid Email Supplied, Try Again.";
    } elseif (duplicate_seminar_registration($email_address)) {
        $message_error = "This email is already in our record.";
    } elseif (check_number($phone_number) != 5) {
        $message_error = "The supplied phone number is invalid.";
    } else {

        // Log New Registration in the DB
        $query = "INSERT INTO education_registrations (full_name, email, phone, venue, date) ";
        $query .= "VALUES ('$full_name', '$email_address', '$phone_number', '$venue', '$date')";

        $db_handle->runQuery($query);

        //Notification email to PR unit
        $web_master = "Lekan Esan <lekan@instafxng.com>";
        $pr_email = "Atolani <tolani@instafxng.com>";
        $pr_subject = "New Seminar Registration In Admin";
        $pr_message = ""
                . "Dear Tolani, "
                . ""
                . "Please Check the Admin for a new seminar registration."
                . "Thanks.";
        $pr_headers = "From: {$web_master}";
        mail($pr_email, $pr_subject, $pr_message, $pr_headers);

        // Autoresponse email to client
        if ($venue == "Diamond Estate") {
            $chosen_venue = "Block 1A, Plot 8, Diamond Estate, LASU/Isheri road, Isheri Olofin, Lagos.";
        } else if ($venue == "Ajah Office") {
            $chosen_venue = "Block A3, Suite 508/509 Eastline Shopping Complex, Opposite Abraham Adesanya Roundabout, along Lekki - Epe expressway, Lagos.";
        }
        $admin_email = "Instafxng Support <support@instafxng.com>";
        $email_subject = "Online Currency Trading Seminar";
        $email_message = "

Dear " . $full_name . ",

Thank you for reserving a seat in the next Forex seminar.

You are now on your way to become a member of our elite traders making
consistent daily income.

A representative will soon place a call to confirm your seat reservation.

Your Venue: " . $chosen_venue . "
Your Date: " . $next_seminar_date . "
Time: 10am


Best Regards,
InstaForex NG
www.instafxng.com

";

        $headers = "From: {$admin_email}";
        mail($email_address, $email_subject, $email_message, $headers);

        $message_success = "Your Seat Reservation Request Has Been Submitted.";
    }
} else {
    //
}

?>
<!DOCTYPE html>
<html lang="en">
    <head>
        <base target="_self">
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>Instaforex Nigeria | Forex Trading Seminar</title>
        <meta name="title" content="Instaforex Nigeria | Forex Trading Seminar for newbies" />
        <meta name="keywords" content="instaforex, forex seminar, forex trading seminar, how to trade forex, trade forex, instaforex nigeria.">
        <meta name="description" content="Learn how to trade forex, get free information about the forex market in our forex trading seminars.">
        <link rel="stylesheet" href="css/free_seminar.css">
        <?php require_once 'layouts/head_meta.php'; ?>
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
                    <div class="super-shadow page-top-section">
                        <div class="row">
                            <div class="col-sm-6">
                                <h1 style="margin: 0;">You really can make consistent and profitable living trading the forex market</h1>
                                <p style="margin-top: 0">In our years of operating as the foremost Forex Education Provider in Nigeria, we have groomed a good number of traders who make a very decent income trading forex. We call them &quot;Elite traders&quot;.</p>
                            </div>
                            <div class="col-sm-6">
                                <img src="images/forex-seminar-banner.jpg" alt="" class="img-responsive" />
                            </div>
                        </div>
                    </div>
                    
                    <div class="section-tint super-shadow">
                        <div class="row text-center">
                            <div class="col-sm-12 text-danger">
                                <h3><strong>Attend our next free Forex seminar and get the following</strong></h3>
                            </div>
                        </div>

                        <div class="row text-center">
                            <div class="col-sm-4">
                                <p><i class="fa fa-trophy fa-3x icon-tune"></i></p>
                                <p>You will be introduced to <em>proven profitable trading and investment</em> opportunities</p>
                            </div>
                            <div class="col-sm-4">
                                <p><i class="fa fa-book fa-3x icon-tune"></i></p>
                                <p>An amazing offer to get the best Forex <em>trading education for free!</em></p>
                            </div>
                            <div class="col-sm-4">
                                <p><i class="fa fa-line-chart fa-3x icon-tune"></i></p>
                                <p>Information on how to start making between <em>10% - 60% profit</em> on your forex trading</p>
                            </div>
                        </div>

                        <div class="row text-center">
                            <div class="col-sm-6">
                                <p><i class="fa fa-dollar fa-3x icon-tune"></i></p>
                                <p>Unique possibility to get <em>$50 start up capital</em></p>
                            </div>
                            <div class="col-sm-6">
                                <p><i class="fa fa-smile-o fa-3x icon-tune"></i></p>
                                <p>We are eager to walk you through the path of becoming one of our <em>&quot;Elite traders&quot;</em></p>
                            </div>
                        </div>

                        <div class="row" id="signup-section">

                            <div class="row">
                                <div class="col-sm-12">
                                    <?php if(isset($message_success)) { ?>
                                    <div class="alert alert-success">
                                        <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
                                        <strong>Success!</strong> <?php echo $message_success; ?>
                                    </div>
                                    <?php } ?>

                                    <?php if(isset($message_error)) { ?>
                                    <div class="alert alert-danger">
                                        <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
                                        <strong>Oops!</strong> <?php echo $message_error; ?>
                                    </div>
                                    <?php } ?>
                                </div>
                            </div>

                            <span id="opt"></span>

                            <div class="row">
                                <div class="col-sm-12">
                                    <form data-toggle="validator" id="signup-form" role="form"  method="post" action="<?php echo htmlentities($_SERVER['REQUEST_URI']); ?>">
                                        <h3 class="text-uppercase text-center signup-header">RESERVE A SEAT AT OUR NEXT SEMINAR NOW</h3>
                                        <p>Next seminar holds on <?php if(defined('NEWSEMINARDATE')) { echo NEWSEMINARDATE; } ?><br>Time: 10AM - 12PM</p>

                                        <div class="form-group has-feedback">
                                            <label for="name" class="control-label">Your Full Name</label>
                                            <div class="input-group margin-bottom-sm">
                                                <span class="input-group-addon"><i class="fa fa-user fa-fw"></i></span>
                                                <input type="text" class="form-control" id="name" name="name" placeholder="Your Name" data-minlength="5" required>
                                            </div>
                                            <span class="glyphicon form-control-feedback" aria-hidden="true"></span>
                                        </div>

                                        <div class="form-group has-feedback">
                                            <label for="email" class="control-label">Your Email Address</label>
                                            <div class="input-group margin-bottom-sm">
                                                <span class="input-group-addon"><i class="fa fa-envelope-o fa-fw"></i></span>
                                                <input type="email" class="form-control" id="email" name="email_add" placeholder="Your Email" data-error="Invalid Email" required>
                                            </div>
                                            <span class="glyphicon form-control-feedback" aria-hidden="true"></span>
                                            <div class="help-block with-errors"></div>
                                        </div>

                                        <div class="form-group has-feedback">
                                            <label for="phone" class="control-label">Your Phone Number</label>
                                            <div class="input-group">
                                                <span class="input-group-addon"><i class="fa fa-phone fa-fw"></i></span>
                                                <input type="text" class="form-control" id="phone" name="phone" placeholder="Your Phone" data-minlength="11" maxlength="11" required>
                                            </div>
                                            <span class="glyphicon form-control-feedback" aria-hidden="true"></span>
                                            <div class="help-block">Example - 08031234567</div>
                                        </div>

                                        <div class="form-group">
                                            <label for="venue" class="control-label">Choose your venue</label>
                                            <div class="radio">
                                                <label><input id="venue" type="radio" name="venue" value="Diamond Estate" required>Block 1A, Plot 8, Diamond Estate, LASU/Isheri road, Isheri Olofin, Lagos. <strong>(Hurry <?php echo rand(3, 9); ?> seats left)</strong></label>
                                            </div>
                                            <div class="radio">
                                                <label><input id="venue" type="radio" name="venue" value="Ajah Office" required>Block A3, Suite 508/509 Eastline Shopping Complex, Opposite Abraham Adesanya Roundabout, along Lekki - Epe expressway, Lagos. <strong>(Hurry <?php echo rand(3, 9); ?> seats left)</strong></label>
                                            </div>
                                            <span class="glyphicon form-control-feedback" aria-hidden="true"></span>
                                        </div>
                                        <div class="form-group">
                                            <button type="submit" name="reserve_seat" class="btn btn-default btn-lg">Reserve Your Seat&nbsp;<i class="fa fa-chevron-circle-right"></i></button>
                                        </div>
                                        <small>All fields are required</small>
                                    </form>
                                </div>
                            </div>

                        </div>

                        <div class="row text-center">
                            <h2 class="color-fancy">For further enquiries, please call 08028281192</h2>
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