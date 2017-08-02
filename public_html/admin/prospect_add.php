<?php
require_once("../init/initialize_admin.php");
if (!$session_admin->is_logged_in()) {
    redirect_to("login.php");
}

// Add New Admin User - Process submitted form
if (isset($_POST['process'])) {
    
    foreach($_POST as $key => $value) {
        $_POST[$key] = $db_handle->sanitizePost(trim($value));
    }

    extract($_POST);

    if(empty($last_name) || empty($first_name) || empty($email_address) || empty($phone) || empty($prospect_source)) {
        $message_error = "All fields are compulsory, please try again.";
    } elseif (!check_email($email_address)) {
        $message_error = "You have provided an invalid email address. Please try again.";
    } elseif($admin_object->prospect_is_duplicate($email_address)) {
        $message_error = "Email already exists! Please try again";
    } else {
        $new_prospect = $admin_object->add_new_prospect_profile($_SESSION['admin_unique_code'], $last_name, $first_name, $middle_name, $email_address, $phone, $prospect_source);

        if($new_prospect) {
            $message_success = "You have successfully created a new Prospect profile.";
        } else {
            $message_error = "Looks like something went wrong or you didn't make any change.";
        }
    }

}

$all_prospect_source = $admin_object->get_all_prospect_source();
if(empty($all_prospect_source)) {
    $message_error = "You cannot add a prospect until you have added at least one prospect source <a href='prospect_source.php'>here</a>.";
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
                            <h4><strong>ADD NEW PROSPECT</strong></h4>
                        </div>
                    </div>
                    
                    <div class="section-tint super-shadow">
                        <div class="row">
                            <div class="col-sm-12">
                                <?php require_once 'layouts/feedback_message.php'; ?>

                                <p>Fill the form below to add a new prospect to the system</p>
                                <form data-toggle="validator" class="form-horizontal" role="form" method="post" action="">
                                    <div class="form-group">
                                        <label class="control-label col-sm-3" for="last_name">Last Name:</label>
                                        <div class="col-sm-9 col-lg-5">
                                            <input name="last_name" type="text" id="last_name" value="" class="form-control" required/>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="control-label col-sm-3" for="first_name">First Name:</label>
                                        <div class="col-sm-9 col-lg-5">
                                            <input name="first_name" type="text" id="first_name" value="" class="form-control" required/>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="control-label col-sm-3" for="middle_name">Middle Name:</label>
                                        <div class="col-sm-9 col-lg-5">
                                            <input name="middle_name" type="text" id="middle_name" value="" class="form-control" />
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="control-label col-sm-3" for="email_address">Email Address:</label>
                                        <div class="col-sm-9 col-lg-5">
                                            <input name="email_address" type="text" id="email_address" value="" class="form-control" required/>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="control-label col-sm-3" for="phone">Phone Number:</label>
                                        <div class="col-sm-9 col-lg-5">
                                            <input name="phone" type="text" id="phone" value="" class="form-control" required/>
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <label class="control-label col-sm-3" for="prospect_source">Prospect Source:</label>
                                        <div class="col-sm-9 col-lg-5">
                                            <select name="prospect_source" class="form-control" id="prospect_source" >
                                                <option value="" selected>Select Prospect Source</option>
                                                <?php foreach($all_prospect_source as $key => $value) { ?>
                                                    <option value="<?php echo $value['prospect_source_id']; ?>"><?php echo $value['source_name']; ?></option>
                                                <?php } ?>
                                            </select>
                                        </div>
                                    </div>

                                    <?php if(!empty($all_prospect_source)) { ?>
                                    <div class="form-group">
                                        <div class="col-sm-offset-3 col-sm-9">
                                            <button type="button" data-target="#confirm-add-prospect" data-toggle="modal" class="btn btn-success">Add Prospect</button>
                                        </div>
                                    </div>
                                    <?php } ?>

                                    <!--Modal - confirmation boxes-->
                                    <div id="confirm-add-prospect" tabindex="-1" role="dialog" aria-hidden="true" class="modal fade">
                                        <div class="modal-dialog">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <button type="button" data-dismiss="modal" aria-hidden="true"
                                                            class="close">&times;</button>
                                                    <h4 class="modal-title">Add Prospect</h4></div>
                                                <div class="modal-body">Are you sure you want to add a prospect?</div>
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
    </body>
</html>