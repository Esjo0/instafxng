<?php
require_once("../init/initialize_admin.php");
if (!$session_admin->is_logged_in()) {
    redirect_to("login.php");
}

$get_params = allowed_get_params(['type', 'id']);

$id_encrypted = $get_params['id'];
$id = decrypt(str_replace(" ", "+", $id_encrypted));
$id = preg_replace("/[^A-Za-z0-9 ]/", '', $id);

$type_encrypted = $get_params['type'];
$type = decrypt(str_replace(" ", "+", $type_encrypted));
$type = preg_replace("/[^A-Za-z0-9 ]/", '', $type);


$admin_code = $_SESSION['admin_unique_code'];
global $email_content;
global $type;
$assigned = "assigned";
$inbox = "inbox";
$sentbox = "sentbox";

if(isset($id) && !empty($id))
{
    $email_id = $id;
    if($type == $inbox)
    {
        $check_assigned = $obj_support_emails->check_assigned($email_id, $admin_code);
        if($check_assigned )
        {
            $message_error = "This case has alredy been assigned to another Admin User.";
            redirect_to("support_email_inbox.php");
        }
        else
        {
            $email_content = $obj_support_emails->read_email($email_id);
            $email_content = $email_content['0'];
            $obj_support_emails->set_as_assigned($email_id, $admin_code);
        }

    }
    else if($type == $sentbox)
    {
        $email_content = $obj_support_emails->read_sent_email($email_id);
        $email_content = $email_content['0'];
    }
    else if($type == $assigned)
    {
        $check_assigned = $obj_support_emails->check_assigned($email_id, $admin_code);
        if($check_assigned )
        {
            $email_content = $obj_support_emails->read_email($email_id);
            $email_content = $email_content['0'];
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
        <title>Instaforex Nigeria | Admin - View Email</title>
        <meta name="title" content="Instaforex Nigeria | Admin - Market Analysis" />
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
                            <h4><strong>VIEW SUPPORT MAIL</strong></h4>
                        </div>
                    </div>
                    
                    <div class="section-tint super-shadow">
                        <div class="row">
                            <div class="col-sm-12">
                                <?php require_once 'layouts/feedback_message.php'; ?>
                                <?php if($type == $inbox):?>
                                <div class="pull-left">
                                    <p>
                                        <a href="support_email_inbox.php" class="btn btn-default" title="Support Mail Inbox">
                                            <i class="fa fa-arrow-circle-left"></i> Support Mail Inbox
                                        </a>
                                    </p>
                                </div>
                                <div class="pull-right">
                                    <p>
                                        <a class="btn btn-default" href="support_email_compose.php?id=<?php echo encrypt($email_content['email_id'])?>">
                                            Reply <i class="fa fa-arrow-circle-right"></i>
                                        </a>
                                    </p>
                                </div>
                                <?php endif; ?>
                                <?php if($type == $assigned):?>
                                <div class="row">
                                    <div class="pull-left">
                                        <p>
                                            <a href="support_email_assigned.php" class=" btn btn-default" title="Assigned Support Mails">
                                                <i class="fa fa-arrow-circle-left"></i>Assigned Support Mails
                                            </a>
                                        </p>
                                    </div>
                                    <div class="pull-right">
                                        <p>
                                            <a class="btn btn-default" href="support_email_compose.php?id=<?php echo encrypt($email_content['email_id'])?>">
                                                Reply <i class="fa fa-arrow-circle-right"></i>
                                            </a>
                                        </p>
                                    </div>
                                </div>
                                <?php endif; ?>
                                <?php if($type == $sentbox):?>
                                    <div class="row">
                                        <div class="pull-left">
                                        <p>
                                            <a href="support_email_sent_box.php" class="btn btn-default" title="Support Mail Sentbox">
                                                <i class="fa fa-arrow-circle-left"></i>Support Mail Sentbox
                                            </a>
                                        </p>
                                        </div>
                                        <div class="pull-right">
                                            <p>
                                                <a class="btn btn-default" href="support_email_compose.php?id=<?php echo encrypt($email_content['email_id'])?>">
                                                    Reply <i class="fa fa-arrow-circle-right"></i>
                                                </a>
                                            </p>
                                        </div>
                                    </div>
                                <?php endif; ?>
                                <p>
                                    <b>
                                        <?php  echo strtoupper($email_content['email_subject']);  ?>
                                    </b>
                                </p>
                                <?php if($type == $inbox || $type == $assigned): ?>
                                    <p>Sender's Address : <?php echo $email_content['email_sender']; ?></p>
                                <?php endif; ?>
                                <?php if($type == $sentbox ): ?>
                                <p>Recipient's Address : <?php echo $email_content['email_sender']; ?></p>
                                <?php endif; ?>
                                <em>Sent On: <?php  echo datetime_to_text($email_content['email_created']);  ?></em>
                                <textarea rows="25" class="form-control" readonly><?php  echo $email_content['email_body'];  ?></textarea>
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