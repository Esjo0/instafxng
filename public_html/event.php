<?php
require_once 'init/initialize_general.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    $full_name = $db_handle->sanitizePost(trim($_POST['name']));
    $phone_number = $db_handle->sanitizePost(trim($_POST['phone']));

    // Perform the necessary validations and display the appropriate feedback
    if(empty($full_name) || empty($phone_number)) {
        $message_error = "All fields must be filled.";
    } elseif (check_number($phone_number) != 5) {
        $message_error = "The supplied phone number is invalid.";
    } else {

        $query = "INSERT INTO lekki_office_training (full_name, phone) VALUES ('$full_name', '$phone_number')";
        $db_handle->runQuery($query);

        if($db_handle->affectedRows() > 0) {
            $message_success = "Your Seat Reservation Request Has Been Submitted.";
        } else {
            $message_error = "An error occurred, please contact support.";
        }

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
        <title>Instaforex Nigeria | Free Forex Training</title>
        <meta name="title" content="Instaforex Nigeria | Free Forex Training" />
        <meta name="keywords" content="" />
        <meta name="description" content="" />
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
                        <div class="row ">
                            <div class="col-sm-7">
                                <h1>Free Forex Training at Lekki</h1>
                                <p>Did you register to take the Free Forex training course in our Island Office and
                                    you haven't been able to attend?</p>
                                <p>Please be informed that a Free Forex training Program is scheduled to hold in our
                                    New Island office located at Block A3, Suite 508/509 East-line Shopping Complex,
                                    Opposite Abraham Adesanya Roundabout, along Lekki - Epe expressway, Lagos.</p>
                            </div>
                            <div class="col-sm-5">
                                <img src="images/free-forex-training.jpg" alt="" class="img-responsive" />
                            </div>
                        </div>
                    </div>                    
                    
                    <div class="section-tint super-shadow">
                        <div class="row">
                            <div class="col-sm-12">
                                <p>This training is coming up on Monday, 13th February – Friday, 17th February, 2017
                                    by 10:00am prompt. This is the first Forex training to be held in our new Island
                                    office this year and it promises to be thrilling and educating as you are going to
                                    have a pleasant learning experience in our State-of-the-art office with a conducive
                                    ambience for learning.</p>
                                <p>
                                    <strong>Venue:</strong> Block A3, Suite 508/509 Eastline Shopping Complex, Opposite Abraham Adesanya Roundabout, along Lekki - Epe expressway, Lagos.<br/>
                                    <strong>Time:</strong> 10am prompt<br/>
                                    <strong>Date:</strong> 13th – 17th of February, 2017
                                </p>
                            </div>
                        </div>
                        
                        <div class="row text-center">
                            <div class="col-sm-12 text-danger">
                                <h3><strong>Register to attend</strong></h3>
                            </div>
                        </div>

                        
                        <div class="row" id="signup-section">
                            <div class="row">
                                <div class="col-sm-12">
                                    <?php require_once 'layouts/feedback_message.php'; ?>

                                    <form data-toggle="validator" id="signup-form" role="form"  method="post" action="<?php echo htmlentities($_SERVER['REQUEST_URI']); ?>">
                                        <div class="form-group has-feedback">
                                            <label for="name" class="control-label">Your Name</label>
                                            <div class="input-group margin-bottom-sm">
                                                <span class="input-group-addon"><i class="fa fa-user fa-fw"></i></span>
                                                <input type="text" class="form-control" id="name" name="name" placeholder="Your Name" data-minlength="5" required>
                                            </div>
                                            <span class="glyphicon form-control-feedback" aria-hidden="true"></span>
                                        </div>

<!--                                        <div class="form-group has-feedback">-->
<!--                                            <label for="email" class="control-label">Your Email Address</label>-->
<!--                                            <div class="input-group margin-bottom-sm">-->
<!--                                                <span class="input-group-addon"><i class="fa fa-envelope-o fa-fw"></i></span>-->
<!--                                                <input type="email" class="form-control" id="email" name="email_add" placeholder="Your Email" data-error="Invalid Email" required>-->
<!--                                            </div>-->
<!--                                            <span class="glyphicon form-control-feedback" aria-hidden="true"></span>-->
<!--                                            <div class="help-block with-errors"></div>-->
<!--                                        </div>-->

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
                            <h4 class="color-fancy">For any inquiry, please call 08028281192</h4>
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