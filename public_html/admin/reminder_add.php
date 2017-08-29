<?php
require_once("../init/initialize_admin.php");
if (!$session_admin->is_logged_in()) {
    redirect_to("login.php");
}
$current_date = date("Y-m-d");
if (isset($_POST['process'])) {
    
    foreach($_POST as $key => $value) {
        $_POST[$key] = $db_handle->sanitizePost(trim($value));
    }

    extract($_POST);

    if(empty($full_name) || empty($email_address) || empty($phone) || empty($description) || empty($effect_date))
    {
        $message_error = "All fields are compulsory, please try again.";
    }
    elseif (!check_email($email_address))
    {
        $message_error = "You have provided an invalid email address. Please try again.";
    }
    elseif ($effect_date <= $current_date)
    {
        $message_error = "The selected Effect Date is invalid!";
    }

    else {
        $new_reminder = $obj_reminders->add_new_reminder($_SESSION['admin_unique_code'], $full_name, $email_address, $phone, $ifx_acc_no, $description, $effect_date);

        if($new_reminder)
        {
            $message_success = "You have successfully created a new reminder.";
        } else
            {
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
        <title>Instaforex Nigeria | Admin</title>
        <meta name="title" content="Instaforex Nigeria | Admin" />
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
                            <h4><strong>ADD NEW REMINDER</strong></h4>
                        </div>
                    </div>
                    
                    <div class="section-tint super-shadow">
                        <div class="row">
                            <div class="col-sm-12">
                                <?php require_once 'layouts/feedback_message.php'; ?>
                                <p>Fill the form below to add a new reminder about a prospective customer/client.</p>
                                <form data-toggle="validator" class="form-horizontal" role="form" method="post" action="">
                                    <div class="form-group">
                                        <label class="control-label col-sm-3" for="last_name">Full Name:</label>
                                        <div class="col-sm-9 ">
                                            <input name="full_name" type="text" id="full_name" value="" class="form-control" required/>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="control-label col-sm-3" for="email_address">Email Address:</label>
                                        <div class="col-sm-9 ">
                                            <input name="email_address" type="text" id="email_address" value="" class="form-control" required/>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="control-label col-sm-3" for="phone">Phone Number:</label>
                                        <div class="col-sm-9 ">
                                            <input name="phone" type="text" id="phone" value="" class="form-control" required/>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="control-label col-sm-3" for="ifx_acc_no">IFX Account Number(If any):</label>
                                        <div class="col-sm-9 ">
                                            <input name="ifx_acc_no" type="text" id="ifx_acc_no" value="" class="form-control"/>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="control-label col-sm-3" for="description">Description:</label>
                                        <div class="col-sm-9">
                                            <textarea name="description" id="description" rows="5"  class="form-control" required></textarea>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="control-label col-sm-3" for="effect_date">Effect Date:</label>
                                        <div class="col-sm-9 ">
                                            <div class="input-group date" id="datetimepicker">
                                                <input name="effect_date" type="text" class="form-control" id="datetimepicker2" required>
                                                <span class="input-group-addon"><span class="glyphicon glyphicon-calendar"></span></span>
                                            </div>
                                        </div>
                                        <script type="text/javascript">
                                            $(function ()
                                            {
                                                $('#datetimepicker, #datetimepicker2').datetimepicker(
                                                    {
                                                        format: 'YYYY-MM-DD'
                                                    });
                                            });
                                        </script>
                                    </div>
                                    <div class="form-group">
                                        <div class="col-sm-offset-3 col-sm-9">
                                            <button type="button" data-target="#confirm-add-reminder" data-toggle="modal" class="btn btn-success">Add Reminder</button>
                                        </div>
                                    </div>

                                    <!--Modal - confirmation boxes-->
                                    <div id="confirm-add-reminder" tabindex="-1" role="dialog" aria-hidden="true" class="modal fade">
                                        <div class="modal-dialog">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <button type="button" data-dismiss="modal" aria-hidden="true"
                                                            class="close">&times;</button>
                                                    <h4 class="modal-title">Add Reminder</h4></div>
                                                <div class="modal-body">Are you sure you want to add a reminder adout this customer/client?</div>
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
        <?php require_once 'layouts/footer.php'; ?>
        <script src="//cdnjs.cloudflare.com/ajax/libs/moment.js/2.9.0/moment-with-locales.js"></script>
        <script src="//cdn.rawgit.com/Eonasdan/bootstrap-datetimepicker/e8bddc60e73c1ec2475f827be36e1957af72e2ea/src/js/bootstrap-datetimepicker.js"></script>
    </body>
</html>