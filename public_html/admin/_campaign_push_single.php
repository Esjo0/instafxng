<?php
require_once("../init/initialize_admin.php");
if (!$session_admin->is_logged_in()) {redirect_to("login.php");}

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['send']))
{
    //foreach($_POST as $key => $value) {$_POST[$key] = $db_handle->sanitizePost(trim($value));}
    extract($_POST);
    if(empty($content)) {$message_error = "All fields must be filled, please try again";}
    else
    {
        $recipeints = get_recipients($_POST['recipeints']);
        $new_push = $system_object->send_push_mobile($recipeints, $_POST['content'], $_POST['title']);
        if(count($recipeints) == json_decode($new_push)->success) {
            $message_success = "Push Notification Message delivered to ".json_decode($new_push)->success." user(s).";}
        else {$message_error = "Push Notification Message delivered to ".json_decode($new_push)->success." user(s).";}
        /*foreach ($recipeints as $row)
        {
            $new_sms = $system_object->send_sms($row, $content);
            if($new_sms) {$message_success = "You have successfully sent the sms.";} else {$message_error = "Looks like something went wrong or you didn't make any change.";}
        }*/
    }
}

function get_recipients($id){
    global $db_handle;
    switch ($id){
        case "1":
            $result = $db_handle->fetchAssoc($db_handle->runQuery("SELECT user_token FROM fxacademy_app_users WHERE (user_code IS NOT NULL) AND (user_token IS NOT NULL)"));
            $recipeints = array();
            foreach ($result as $key=>$value) {$recipeints[count($recipeints)] = $value['user_token'];}
            break;
        default:
            $result = $db_handle->fetchAssoc($db_handle->runQuery("SELECT user_token FROM fxacademy_app_users WHERE (user_code IS NOT NULL) AND (user_token IS NOT NULL)"));
            $recipeints = array();
            foreach ($result as $key=>$value) {$recipeints[count($recipeints)] = $value['user_token'];}
            break;
    }
    return $recipeints;
}
?>
<!DOCTYPE html>
<html lang="en">
    <head>
        <base target="_self">
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>Instaforex Nigeria | Admin - Compose SMS</title>
        <meta name="title" content="Instaforex Nigeria | Admin - Compose SMS" />
        <meta name="keywords" content="" />
        <meta name="description" content="" />
        <?php require_once 'layouts/head_meta.php'; ?>
        <script language="javascript" type="text/javascript">
            function limitText(limitField, limitCount, limitNum)
            {
                if (limitField.value.length > limitNum)
                {
                        limitField.value = limitField.value.substring(0, limitNum);
                }
                else
                {
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
                            <h4><strong>COMPOSE A NOTIFICATION MESSAGE</strong></h4>
                        </div>
                    </div>
                    
                    <div class="section-tint super-shadow">
                        <div class="row">
                            <div class="col-sm-12">
                                <?php require_once 'layouts/feedback_message.php'; ?>
                                <p>Compose a message below.</p>
                                <form data-toggle="validator" class="form-horizontal" enctype="multipart/form-data" role="form" method="post" action="">
<!--                                    <input type="hidden" name="campaign_sms_no" value="<?php /*if(isset($selected_campaign_sms['campaign_sms_id'])) { echo $selected_campaign_sms['campaign_sms_id']; } */?>" />
-->                                    <!--<div class="form-group">
                                        <label class="control-label col-sm-2" for="phone_num">Phone Number:</label>
                                        <div class="col-sm-10">
                                            <textarea  name="phone_num" id="phone_num" rows="1" class="form-control" placeholder="Phone Number(s)" required><?php /*if(isset($_GET['lead_phone'])) { echo decrypt_ssl(str_replace(" ", "+", $_GET['lead_phone']));} */?></textarea>
                                            <small>Multiple phone numbers should be comma(,) seperated.</small>
                                        </div>
                                    </div>-->
                                    <div class="form-group">
                                        <label class="control-label col-sm-2" for="message">Title:</label>
                                        <div class="col-sm-10">
                                            <input name="title" class="form-control" placeholder="Enter A Title" required/>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="control-label col-sm-2" for="message">Message:</label>
                                        <div class="col-sm-10">
                                            <textarea onChange="javascript:check_count();document.form1.count_display.value=document.form1.message.value.length;check_count();" onkeypress="document.form1.count_display.value=document.form1.message.value.length;check_count();"  onBlur="document.form1.count_display.value=document.form1.message.value.length;check_count();" name="content" id="message" rows="3" class="form-control" placeholder="Enter Message"  onKeyDown="limitText(this.form.message,this.form.countdown,320);" onKeyUp="limitText(this.form.message,this.form.countdown,320);" required></textarea>
                                            <small>(Maximum characters: 320)<br>You have <input readonly type="text" name="countdown" size="3" value="320"> characters left.</small>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="control-label col-sm-2" for="recipients">Recipients:</label>
                                        <div class="col-sm-10 col-lg-5">
                                            <div class="radio">
                                                <label><input type="radio" name="recipients" value="1" checked required>All App Users</label>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <div class="col-sm-offset-2 col-sm-10">
                                            <button type="button" data-target="#save-sms-confirm" data-toggle="modal" class="btn btn-success"><i class="fa fa-send fa-fw"></i> Send</button>
                                        </div>
                                    </div>
                                    <!-- Modal - confirmation boxes -->
                                    <div id="save-sms-confirm" tabindex="-1" role="dialog" aria-hidden="true" class="modal fade">
                                        <div class="modal-dialog">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <button type="button" data-dismiss="modal" aria-hidden="true"
                                                            class="close">&times;</button>
                                                    <h4 class="modal-title">Send SMS Confirmation</h4></div>
                                                <div class="modal-body">Do you want to send this message now?</div>
                                                <div class="modal-footer">
                                                    <input name="send" type="submit" class="btn btn-success" value="Send">
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