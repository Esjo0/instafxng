<?php
require_once("../init/initialize_admin.php");
if (!$session_admin->is_logged_in()) {
    redirect_to("login.php");
}
extract($_GET);
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    extract($_POST);
    
    if(empty($content) || empty($subject) || empty($name) || empty($email)) {
        $message_error = "All fields must be filled, please try again";
    } else {


        $name = ucwords(strtolower(trim($name)));

        // Replace [NAME] with clients full name
        $my_content_new = str_replace('[NAME]', $name, $content);
        $my_subject_new = str_replace('[NAME]', $name, $subject);

        $sent_email = $system_object->send_email($my_subject_new, $my_content_new, $email, $name, $sender);

        if($sent_email) {
            $message_success = "You have successfully sent the email.";
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
        <title>Instaforex Nigeria | Admin - Compose Single Email</title>
        <meta name="title" content="Instaforex Nigeria | Admin - Compose Single Email" />
        <meta name="keywords" content="" />
        <meta name="description" content="" />
        <?php require_once 'layouts/head_meta.php'; ?>
        <script src="tinymce/tinymce.min.js"></script>
        <script type="text/javascript">
            tinyMCE.init({
                selector: "textarea#content",
                height: 500,
                theme: "modern",
                relative_urls: false,
                remove_script_host: false,
                convert_urls: true,
                plugins: [
                    "advlist autolink lists link image charmap print preview hr anchor pagebreak",
                    "searchreplace wordcount visualblocks visualchars code fullscreen",
                    "insertdatetime media nonbreaking save table contextmenu directionality",
                    "emoticons template paste textcolor colorpicker textpattern responsivefilemanager"
                ],
                toolbar1: "insertfile undo redo | styleselect | bold italic | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | link image",
                toolbar2: "| responsivefilemanager print preview media | forecolor backcolor emoticons",
                image_advtab: true,
                external_filemanager_path: "../filemanager/",
                filemanager_title: "Instafxng Filemanager",
//                external_plugins: { "filemanager" : "../filemanager/plugin.min.js"}

            });
        </script>
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
                            <h4><strong>COMPOSE SINGLE EMAIL</strong></h4>
                        </div>
                    </div>
                    
                    <div class="section-tint super-shadow">
                        <div class="row">
                            <div class="col-sm-12">
                                <?php require_once 'layouts/feedback_message.php'; ?>

                                <p>Compose a new email you want to send to a single client, use [NAME] where you want
                                    the client name to be substituted in the email content.</p>
                                
                                <form data-toggle="validator" class="form-horizontal" enctype="multipart/form-data" role="form" method="post" action="<?php echo $_SERVER['REQUEST_URI']; ?>">

                                    <div class="form-group">
                                        <label class="control-label col-sm-2" for="sender">Sender:</label>
                                        <div class="col-sm-10">
                                            <input list="sender_names" type="text" name="sender" class="form-control" id="sender" value="<?php if(isset($selected_campaign_email['sender'])) { echo $selected_campaign_email['sender']; } ?>" placeholder="Sender" required/>
                                            <datalist id="sender_names">
                                                <option value="Bunmi from InstaFxNg">
                                                <option value="Mercy from InstaFxNg">
                                                <option value="Demola from InstaFxNg">
                                                <option value="InstaFxNg">
                                            </datalist>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="control-label col-sm-2" for="subject">Subject:</label>
                                        <div class="col-sm-10">
                                            <input type="text" name="subject" class="form-control" id="subject" value="<?php if(isset($subject)) { echo $subject; } ?>" placeholder="Your Subject" required/>
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <label class="control-label col-sm-2" for="name">Name:</label>
                                        <div class="col-sm-10">
                                            <input type="text" name="name" class="form-control" id="name" value="<?php if(isset($name)) { echo $name; } ?>" placeholder="Client Name" required/>
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <label class="control-label col-sm-2" for="email">Email:</label>
                                        <div class="col-sm-10">
                                            <input type="text" name="email" class="form-control" id="email" value="<?php if(isset($email)) { echo $email; } ?>" placeholder="Client Email" required/>
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <label class="control-label col-sm-2" for="content">Message:</label>
                                        <div class="col-sm-10"><textarea name="content" id="content" rows="3" class="form-control"><?php if(isset($content)) { echo $content; } ?></textarea></div>
                                    </div>

                                    <div class="form-group">
                                        <div class="col-sm-offset-2 col-sm-10">
                                            <button type="button" data-target="#send-email-confirm" data-toggle="modal" class="btn btn-success"><i class="fa fa-save fa-fw"></i> Send Email</button>
                                        </div>
                                    </div>

                                    <!-- Modal - confirmation boxes -->
                                    <div id="send-email-confirm" tabindex="-1" role="dialog" aria-hidden="true" class="modal fade">
                                        <div class="modal-dialog">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <button type="button" data-dismiss="modal" aria-hidden="true"
                                                        class="close">&times;</button>
                                                    <h4 class="modal-title">Send Email Confirmation</h4></div>
                                                <div class="modal-body">Do you want to send this email now?</div>
                                                <div class="modal-footer">
                                                    <input name="process" type="submit" class="btn btn-success" value="Send">
                                                    <button type="submit" name="decline" onClick="window.close();" data-dismiss="modal" class="btn btn-danger">Close !</button>
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