<?php
require_once 'init/initialize_general.php';
$thisPage = "Education";

if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    $full_name = $db_handle->sanitizePost(trim($_POST['name']));
    $email_address = $db_handle->sanitizePost(trim($_POST['email_add']));
    $phone_number = $db_handle->sanitizePost(trim($_POST['phone']));

    // Perform the necessary validations and display the appropriate feedback
    if(empty($full_name) || empty($email_address) || empty($phone_number)) {
        $message_error = "All fields must be filled.";
    } elseif (!check_email($email_address)) {
        $message_error = "Invalid Email Supplied, Try Again.";
    } elseif (check_number($phone_number) != 5) {
        $message_error = "The supplied phone number is invalid.";
    } else {

        //Notification email to PR unit
        $web_master = "Lekan Esan <lekan@instafxng.com>";
        $pr_email = "Atolani <tolani@instafxng.com>";
        $pr_subject = "New Ibadan City Tour Registration";
        $pr_message = ""
                . "Dear Tolani, "
                . ""
                . "NEW REG:"
                . ""
                . "Name: $full_name"
                . "Email: $email_address"
                . "Phone Number: $phone_number"
                . ""
                . ""
                . "Thanks.";
        $pr_headers = "From: {$web_master}";
        mail($pr_email, $pr_subject, $pr_message, $pr_headers);

        // Autoresponse email to client
        $admin_email = "Instafxng Support <support@instafxng.com>";
        $email_subject = "Instaforex Ibadan City Tour";
        $email_message = "

Dear " . $full_name . ",

Thank you for reserving a seat at our Ibadan City Tour.

A representative will soon place a call to confirm your seat reservation.

Venue: Travel House Budget Hotels, Ibadan, Oyo State
Date: March 17th & 18th, 2016


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
        <title>Instaforex Nigeria | Ibadan City Tour</title>
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
                                <h1 style="margin: 0;">Instaforex Ibadan City Tour</h1>
                                <p>Meet Forex Experts and learn how to profit from the Forex market during the Instaforex Ibadan City Tour.</p>
                                <p>
                                    <strong>Date:</strong> March 17th & 18th, 2016<br/>
                                    <strong>Venue:</strong> Travel House Budget Hotels, Ibadan, Oyo State
                                </p>
                                <p><strong>RESERVE YOUR SEAT BELOW</strong></p>
                            </div>
                            <div class="col-sm-6">
                                <img src="images/instafxng-ibadan-city-tour.jpg" alt="" class="img-responsive" />
                            </div>
                        </div>
                    </div>
                    
                    <div class="section-tint super-shadow">
                        <div class="row">
                            <div class="col-xs-12">
                                <p>Every challenge opens new opportunities for those who search and seek knowledge.</p>
                                <p>Times are getting harder in Nigeria, our beloved Naira continues to depreciate in value, prices of goods are skyrocketing and even our highly paid salary can no longer cater for monthly expenses.</p>
                                <p>Despite all these, the Foreign exchange market continues to yield more profit for professional Forex traders. A little dollar earned is a lot of money when converted to Naira.</p>
                            </div>
                        </div>
                        
                        <div class="row text-center">
                            <div class="col-sm-12 text-danger">
                                <h3><strong>Sign up to attend InstaForex Ibadan City tour </strong></h3>
                            </div>
                        </div>

                        <div class="row text-center">
                            <div class="col-sm-4">
                                <p><i class="fa fa-group fa-3x icon-tune"></i></p>
                                <p><em>Forex Trading Conference</em></p>
                            </div>
                            <div class="col-sm-4">
                                <p><i class="fa fa-line-chart fa-3x icon-tune"></i></p>
                                <p><em>Forex Trading Consultation </em></p>
                            </div>
                            <div class="col-sm-4">
                                <p><i class="fa fa-book fa-3x icon-tune"></i></p>
                                <p><em>Forex Trading Training</em></p>
                            </div>
                        </div>
                        <br/>

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
                                        <h3 class="text-uppercase text-center signup-header">RESERVE YOUR SEAT NOW</h3>

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
                                            <button type="submit" name="reserve_seat" class="btn btn-default btn-lg">Reserve Your Seat&nbsp;<i class="fa fa-chevron-circle-right"></i></button>
                                        </div>
                                        <small>All fields are required</small>
                                    </form>
                                </div>
                            </div>

                        </div>

                        <div class="row text-center">
                            <br/>
                            <h4 class="color-fancy">For further enquiries, please call 08028281192</h4>
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