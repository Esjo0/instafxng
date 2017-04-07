<?php
require_once("../init/initialize_admin.php");
if (!$session_admin->is_logged_in()) {
    redirect_to("login.php");
}

$page_requested = "";

$get_params = allowed_get_params(['id','action']);
$system_message_id_encrypted = $get_params['id'];
$system_message_id = decrypt(str_replace(" ", "+", $system_message_id_encrypted));
$system_message_id = preg_replace("/[^A-Za-z0-9 ]/", '', $system_message_id);
$action = $get_params['action'];

if (isset($_POST['process'])) {
    foreach($_POST as $key => $value) {
        $_POST[$key] = $db_handle->sanitizePost(trim($value));
    }
    $system_message_id = $_POST['message_no'];
    $system_message_type = $_POST['message_type'];
    $subject = $_POST['subject'];
    $content = str_replace('â€™', "'", $_POST['content']);

    if(empty($content)) {
        $message_error = "All fields must be filled, please try again";
    } else {
        $update_system_message = $admin_object->update_system_message($system_message_id, $system_message_type, $subject, $content);
        
        if($update_system_message) {
            $message_success = "You have successfully updated system message";
        } else {
            $message_error = "Looks like something went wrong or you didn't make any change.";
        }
    }
}

// Send test
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['send_test'])) {

    $message_type = $_POST['message_type'];
    $system_message_id = $_POST['message_no'];
    $selected_message = $system_object->get_system_message_by_id($system_message_id);
    $selected_message = $selected_message[0];

    $my_subject = trim($selected_message['subject']);
    $my_message = stripslashes($selected_message['value']);

    if($message_type == '1') {
        $email = $_POST['test_email'];  //only specified
        $email = trim(str_replace(" ", "", $email));
        $arr1 = explode(',', $email);

        foreach($arr1 as $sendto) {
            $query = "SELECT first_name FROM user WHERE email = '$sendto' LIMIT 1";
            $result = $db_handle->runQuery($query);
            $selected_member = $db_handle->fetchAssoc($result);

            $client_name = ucwords(strtolower(trim($selected_member[0]['first_name'])));

            // Replace [NAME] with clients full name
            $my_message_new = str_replace('[FIRST_NAME]', $client_name, $my_message);
            $my_subject_new = str_replace('[FIRST_NAME]', $client_name, $my_subject);

            $my_message_new = str_replace('[FULL_NAME]', $client_name, $my_message_new);
            $my_subject_new = str_replace('[FULL_NAME]', $client_name, $my_subject_new);

            $system_object->send_email($my_subject_new, $my_message_new, $sendto, $client_name);
        }

        $message_success = "You have successfully sent the email test.";
    }
}

if(isset($action)) {
    $selected_message = $system_object->get_system_message_by_id($system_message_id);
    $selected_message = $selected_message[0];

    switch($action) {
        case 'update':
            $page_requested = "system_message_update"; break;
        case 'test':
            $page_requested = "system_message_test"; break;
    }
}

switch($page_requested) {
    case '': $system_message_default = true; break;
    case 'system_message_update': $system_message_update = true; break;
    case 'system_message_test': $system_message_test = true; break;
    default: $system_message_default = true;
}

?>
<!DOCTYPE html>
<html lang="en">
    <head>
        <base target="_self">
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>Instaforex Nigeria | Admin - Setting Rates</title>
        <meta name="title" content="Instaforex Nigeria | Admin - View Admin Members" />
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
        <script language="javascript" type="text/javascript">
            function limitText(limitField, limitCount, limitNum) {
                if (limitField.value.length > limitNum) {
                        limitField.value = limitField.value.substring(0, limitNum);
                } else {
                        limitCount.value = limitNum - limitField.value.length;
                }
            }
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
                            <h4><strong>SYSTEM MESSAGES</strong></h4>
                        </div>
                    </div>
                    
                    <div class="section-tint super-shadow">
                        <div class="row">
                            <div class="col-sm-12">
                                <?php require_once 'layouts/feedback_message.php'; ?>
                                
                                <?php 
                                    if($system_message_default) { include_once 'views/system_message/system_message_default.php'; }
                                    if($system_message_update) { include_once 'views/system_message/system_message_update.php'; }
                                    if($system_message_test) { include_once 'views/system_message/system_message_test.php'; }
                                ?>
                                
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