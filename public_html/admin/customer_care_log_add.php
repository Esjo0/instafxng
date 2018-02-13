<?php
require_once("../init/initialize_admin.php");
if (!$session_admin->is_logged_in()) {
    redirect_to("login.php");
}
if (isset($_POST['process_client']))
{
    foreach($_POST as $key => $value) {
        $_POST[$key] = $db_handle->sanitizePost(trim($value));
    }
    extract($_POST);
    if(empty($con_desc) || empty($acc_no))
    {
        $message_error = "All fields are compulsory, please try again.";
    }
    elseif (!$system_object->valid_ifxacct($acc_no))
    {
        $message_error = "You have provided an invalid InstaForex Account Number.
        Please check the account number to ensure that it belongs to an existing client.";
    }
    else {
        $new_log = $obj_customer_care_log->add_new_client_log($_SESSION['admin_unique_code'], $acc_no, $con_desc);
        if($new_log)
        {
            $message_success = "You have successfully created a new log.";
        } else
        {
            $message_error = "Looks like something went wrong or you didn't make any change.";
        }
    }

}

if (isset($_POST['process_customer']))
{
    foreach($_POST as $key => $value)
    {
        $_POST[$key] = $db_handle->sanitizePost(trim($value));
    }
    extract($_POST);
    if(empty($first_name) || empty($last_name) || empty($phone) || empty($con_desc))
    {
        $message_error = "All fields are compulsory, please try again.";
    }
    elseif(isset($email) && !empty($email))
    {
        if(!check_email($email))
        {
            $message_error = "You have provided an invalid email address. Please try again.";
        }
    }
    else
        {
        $new_log = $obj_customer_care_log->add_new_customer_log($_SESSION['admin_unique_code'], $first_name, $last_name, $email, $phone, $con_desc, $prospect_source, $other_name);
        //($_SESSION['admin_unique_code'], $first_name, $other_name, $last_name, $email_address, $phone_no, $con_desc, $prospect_source);
            if($new_log)
            {
                $message_success = "You have successfully created a new log.";
            } else
            {
                $message_error = "Looks like something went wrong or you didn't make any change.";
            }
    }

}

$all_prospect_source = $admin_object->get_all_prospect_source();
if(empty($all_prospect_source)) {    $message_error = "You cannot add a prospect until you have added at least one prospect source <a href='prospect_source.php'>here</a>.";}

?>
<!DOCTYPE html>
<html lang="en">
    <head>
        <base target="_self">
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>Instaforex Nigeria | Admin - Customer Care Log</title>
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
                            <h4><strong>ADD NEW LOG</strong></h4>
                        </div>
                    </div>
                    
                    <div class="section-tint super-shadow">
                        <div class="row">
                            <div class="col-sm-12">
                                <?php require_once 'layouts/feedback_message.php'; ?>
                                <ul class="nav nav-tabs">
                                    <li class="active"><a data-toggle="tab" href="#customer_log">Customer Log</a></li>
                                    <li><a data-toggle="tab" href="#client_log">Client Log</a></li>
                                </ul>
                                <div class="tab-content">
                                    <div id="customer_log" class="tab-pane fade in active">
                                        <p>Fill the form below to add a new log about a customer.</p>
                                        <form data-toggle="validator" class="form-horizontal" role="form" method="post" action="">
                                            <div class="form-group">
                                                <label class="control-label col-sm-3" for="full_name">Customer's Name:</label>
                                                <div class="col-sm-9">
                                                    <div class="row">
                                                        <div class="col-md-3">
                                                            <input name="last_name" type="text" id="last_name" placeholder="Surname" class="form-control" required>
                                                        </div>
                                                        <div class="col-md-3">
                                                            <input name="first_name" type="text" id="first_name" placeholder="First Name" class="form-control" required>
                                                        </div>
                                                        <div class="col-md-3">
                                                            <input name="other_name" type="text" id="other_name" placeholder="Other Names"  class="form-control">
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <label class="control-label col-sm-3" for="email">Customer's Email Address:</label>
                                                <div class="col-sm-9">
                                                    <div class="row">
                                                        <div class="col-md-9">
                                                            <input name="email" type="text" id="email" value="" class="form-control" />
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <label class="control-label col-sm-3" for="phone">Customer's Phone Number:</label>
                                                <div class="col-sm-9">
                                                    <div class="row">
                                                        <div class="col-md-9">
                                                            <input name="phone" type="text" id="phone"  class="form-control" required/>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <label class="control-label col-sm-3" for="prospect_source">Source:</label>
                                                <div class="col-sm-9">
                                                    <div class="row">
                                                        <div class="col-md-9">
                                                            <select name="prospect_source" class="form-control" id="prospect_source" >
                                                                <option value="" selected>Select Source</option>
                                                                <?php foreach($all_prospect_source as $key => $value) { ?>
                                                                    <option value="<?php echo $value['prospect_source_id']; ?>"><?php echo $value['source_name']; ?></option>
                                                                <?php } ?>
                                                            </select>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <label class="control-label col-sm-3" for="description">Conversation Description:</label>
                                                <div class="col-sm-9">
                                                    <div class="row">
                                                        <div class="col-md-9">
                                                            <textarea placeholder="Enter a brief and precise description of your conversation..." name="con_desc" id="con_desc" rows="3" class="form-control" required></textarea>
                                                        </div>
                                                    </div>

                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <div class="col-sm-offset-3 col-sm-9">
                                                    <button type="button" data-target="#confirm-add-customer-log" data-toggle="modal" class="btn btn-success">Add Log</button>
                                                </div>
                                            </div>
                                            <!--Modal - confirmation boxes-->
                                            <div id="confirm-add-customer-log" tabindex="-1" role="dialog" aria-hidden="true" class="modal fade">
                                                <div class="modal-dialog">
                                                    <div class="modal-content">
                                                        <div class="modal-header">
                                                            <button type="button" data-dismiss="modal" aria-hidden="true" class="close">&times;</button>
                                                            <h4 class="modal-title">Add Log</h4>
                                                        </div>
                                                        <div class="modal-body">Are you sure you want to add a log about this customer?</div>
                                                        <div class="modal-footer">
                                                            <input name="process_customer" type="submit" class="btn btn-success" value="Proceed">
                                                            <button type="submit" name="close" onClick="window.close();" data-dismiss="modal" class="btn btn-danger">Close!</button>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </form>
                                    </div>
                                    <div id="client_log" class="tab-pane fade">
                                        <p>Fill the form below to add a new log about a client.</p>
                                        <form data-toggle="validator" class="form-horizontal" role="form" method="post" action="">
                                            <div class="form-group">
                                                <label class="control-label col-sm-3" for="acc_no">Client's IFX Account Number:</label>
                                                <div class="col-sm-9 col-lg-5">
                                                    <input name="acc_no" type="text" id="acc_no" value="" class="form-control" required/>
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <label class="control-label col-sm-3" for="description">Conversation Description:</label>
                                                <div class="col-sm-9 col-lg-5">
                                                    <textarea placeholder="Enter a brief and precise description of your convesation..." name="con_desc" id="con_desc" rows="3" class="form-control" required></textarea>
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <div class="col-sm-offset-3 col-sm-9">
                                                    <button type="button" data-target="#confirm-add-client-log" data-toggle="modal" class="btn btn-success">Add Log</button>
                                                </div>
                                            </div>
                                            <!--Modal - confirmation boxes-->
                                            <div id="confirm-add-client-log" tabindex="-1" role="dialog" aria-hidden="true" class="modal fade">
                                                <div class="modal-dialog">
                                                    <div class="modal-content">
                                                        <div class="modal-header">
                                                            <button type="button" data-dismiss="modal" aria-hidden="true" class="close">&times;</button>
                                                            <h4 class="modal-title">Add Log</h4>
                                                        </div>
                                                        <div class="modal-body">Are you sure you want to add a log about this client?</div>
                                                        <div class="modal-footer">
                                                            <input name="process_client" type="submit" class="btn btn-success" value="Proceed">
                                                            <button type="submit" name="close" onClick="window.close();" data-dismiss="modal" class="btn btn-danger">Close!</button>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </form>
                                    </div>
                                </div>
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