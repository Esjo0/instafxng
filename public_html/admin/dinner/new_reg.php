<?php
require_once("../../init/initialize_admin.php");
if (!$session_admin->is_logged_in()) {
    redirect_to("login.php");
}

// Process submitted form
if (isset($_POST['process'])) {

    foreach($_POST as $key => $value) {
        $_POST[$key] = $db_handle->sanitizePost(trim($value));
    }

    extract($_POST);

    if(empty($full_name) || empty($email) || empty($phone_number)) {
        $message_error = "All fields are compulsory, please try again.";
    } elseif (!check_email($email)) {
        $message_error = "You have provided an invalid email address. Please try again.";
    } else {

        $new_reg = $admin_object->add_new_dinner_attendee($full_name, $email, $phone_number);
        if($new_reg) {
            $message_success = "You have successfully created a new dinner attendee.";
        } else {
            $message_error = "Looks like something went wrong or you didn't make any change.";
        }
    }

}

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <base target="_self">
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Instaforex Nigeria | New Dinner Registration</title>
    <meta name="title" content="Instaforex Nigeria | New Dinner Registration" />
    <meta name="keywords" content="" />
    <meta name="description" content="" />
    <?php require_once '../layouts/head_meta.php'; ?>
</head>
<body>
<?php require_once '../layouts/header.php'; ?>
<!-- Main Body: The is the main content area of the web site, contains a side bar  -->
<div id="main-body" class="container-fluid">
    <div class="row no-gutter">
        <!-- Main Body - Side Bar  -->
        <div id="main-body-side-bar" class="col-md-4 col-lg-3 left-nav">
            <?php require_once '../layouts/sidebar.php'; ?>
        </div>

        <!-- Main Body - Content Area: This is the main content area, unique for each page  -->
        <div id="main-body-content-area" class="col-md-8 col-lg-9">

            <!-- Unique Page Content Starts Here
            ================================================== -->
            <div class="row">
                <div class="col-sm-12 text-danger">
                    <h4><strong>NEW DINNER REGISTRATION</strong></h4>
                </div>
            </div>

            <div class="section-tint super-shadow">
                <div class="row">
                    <div class="col-sm-12">
                        <?php require_once '../layouts/feedback_message.php'; ?>

                        <p>Fill the form below to register a client.</p>

                        <form data-toggle="validator" class="form-horizontal" role="form" method="post" action="">
                            <div class="form-group">
                                <label class="control-label col-sm-3" for="full_name">Full Name:</label>
                                <div class="col-sm-9 col-lg-5">
                                    <div class="input-group">
                                        <span class="input-group-addon"><i class="fa fa-user fa-fw"></i></span>
                                        <input name="full_name" type="text" id="" value="" class="form-control" required/>
                                    </div>
                                </div>
                            </div>

                            <div class="form-group">
                                <label class="control-label col-sm-3" for="email">Email Address:</label>
                                <div class="col-sm-9 col-lg-5">
                                    <div class="input-group">
                                        <span class="input-group-addon"><i class="fa fa-envelope fa-fw"></i></span>
                                        <input name="email" type="text" id="" value="" class="form-control" required/>
                                    </div>
                                </div>
                            </div>

                            <div class="form-group">
                                <label class="control-label col-sm-3" for="phone_number">Phone Number:</label>
                                <div class="col-sm-9 col-lg-5">
                                    <div class="input-group">
                                        <span class="input-group-addon"><i class="fa fa-phone fa-fw"></i></span>
                                        <input name="phone_number" type="text" id="" value="" class="form-control" required/>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group">
                                <div class="col-sm-offset-3 col-sm-9">
                                    <button type="button" data-target="#confirm-add-reg" data-toggle="modal" class="btn btn-success">Add Attendee</button>
                                </div>
                            </div>

                            <!--Modal - confirmation boxes-->
                            <div id="confirm-add-reg" tabindex="-1" role="dialog" aria-hidden="true" class="modal fade">
                                <div class="modal-dialog">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <button type="button" data-dismiss="modal" aria-hidden="true"
                                                    class="close">&times;</button>
                                            <h4 class="modal-title">Add New Dinner Attendee</h4></div>
                                        <div class="modal-body">Are you sure you want to save this information? This action cannot be reversed.</div>
                                        <div class="modal-footer">
                                            <input name="process" type="submit" class="btn btn-success" value="Proceed">
                                            <button type="submit" name="close" onClick="window.close();" data-dismiss="modal" class="btn btn-danger">Close!</button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </form>

                    </div>
                </div>

            </div>

            <!-- Unique Page Content Ends Here
            ================================================== -->

        </div>

    </div>
</div>
<?php require_once '../layouts/footer.php'; ?>
</body>
</html>