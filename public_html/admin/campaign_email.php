<?php
require_once("../init/initialize_admin.php");
if (!$session_admin->is_logged_in()) {
    redirect_to("login.php");
}
$mail_templates = get_all_mail_templates();

$get_params = allowed_get_params(['x', 'id']);
$campaign_email_id_encrypted = $get_params['id'];
$campaign_email_id = decrypt(str_replace(" ", "+", $campaign_email_id_encrypted));
$campaign_email_id = preg_replace("/[^A-Za-z0-9 ]/", '', $campaign_email_id);

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Save this campaign email
    foreach($_POST as $key => $value) {
        $_POST[$key] = $db_handle->sanitizePost(trim($value));
    }
    
    extract($_POST);
    
    if(empty($content) || empty($subject)) {
        $message_error = "All fields must be filled, please try again";
    } else {
        
        if($_POST['process'] == 'Save') {            
            $new_campaign_email = $system_object->add_new_campaign_email($campaign_email_no, $sender, $subject, $campaign_category, $content, $_SESSION['admin_unique_code'], $campaign_email_status);
        } else {
            unset($campaign_email_no);
            $new_campaign_email = $system_object->add_new_campaign_email($campaign_email_no, $sender, $subject, $campaign_category, $content, $_SESSION['admin_unique_code'], $campaign_email_status);
        }
        
        if($new_campaign_email) {
            $message_success = "You have successfully saved the email campaign";
        } else {
            $message_error = "Looks like something went wrong or you didn't make any change.";
        }
            
    }
}

// Confirm that campaign category exist before a new email campaign is saved
$all_campaign_category = $system_object->get_all_campaign_category();

if(!$all_campaign_category) {
    $message_error = "No campaign category created, you must create a category before any campaign. <a href=\"campaign_new_category.php\" title=\"Create new category\">Click here</a> to create one.";
}

if($get_params['x'] == 'edit') {
    
    $selected_campaign_email = $system_object->get_campaign_email_by_id($campaign_email_id);

    if(!empty($campaign_email_id)) {
        if($selected_campaign_email['send_status'] != '2') {
            redirect_to("campaign_email_view.php"); // campaign sent and cannot be edited or URL tampered
        }
    } else {
        redirect_to("campaign_email_view.php"); // cannot find campaign or URL tampered
    }   
}

if($get_params['x'] == 'duplicate') {

    $selected_campaign_email = $system_object->get_campaign_email_by_id($campaign_email_id);

    //We have to unset the ID since this is like save new from an old campaign
    unset($selected_campaign_email['campaign_email_id']);
}

?>
<!DOCTYPE html>
<html lang="en">
    <head>
        <base target="_self">
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>Instaforex Nigeria | Admin - Compose Email</title>
        <meta name="title" content="Instaforex Nigeria | Admin - Compose Email" />
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

            function add_template(template_name)
            {
                var xhttp = new XMLHttpRequest();
                xhttp.onreadystatechange = function()
                {
                    if (this.readyState == 4 && this.status == 200)
                    {
                        tinymce.get("content").execCommand('mceSetContent', false, this.responseText);
                    }
                };
                xhttp.open("GET", "../mail_templates/"+template_name, true);
                xhttp.send();
                return;
            }
            function show_form(div, trigger)
            {
                var x = document.getElementById(div);
                if (x.style.display === 'none') {x.style.display = 'block';
                    document.getElementById(trigger).innerHTML = 'Placeholder List <i class="fa fa-caret-up"></i>';}
                else {x.style.display = 'none';
                    document.getElementById(trigger).innerHTML = 'Placeholder List <i class="fa fa-caret-down"></i>';}
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
                            <h4><strong>COMPOSE EMAIL</strong></h4>
                        </div>
                    </div>
                    
                    <div class="section-tint super-shadow">
                        <div class="row">
                            <div class="col-sm-12">
                                <?php require_once 'layouts/feedback_message.php'; ?>
                                <p><a href="campaign_email_view.php" class="btn btn-default" title="Manage Email Campaigns"><i class="fa fa-arrow-circle-left"></i> Manage Email Campaigns</a></p>
                                <p>Create a campaign email below, you can save or send test. Note: When sending to all clients, level 1, 2, 3, unverified clients, Lagos clients,
                                    use placeholders for personalisation. <button onclick="show_form('p_list', this.id)" id="p_trigger" title="Click here to see full list of place holders." class="btn btn-xs btn-default">Placeholder List <i class="fa fa-caret-down"></i></button></p>
                                <ul id="p_list" style="display: none;">
                                    <li>[NAME] - Client First Name</li>
                                    <li>[LPMP] - Loyalty Point Month Position</li>
                                    <li>[LPMR] - Loyalty Point Month Rank Value</li>
                                    <li>[LPMHR] - Loyalty Point Month Highest Rank Value</li>
                                    <li>[LPMD] - Loyalty Point Month Difference (Compared to Highest)</li>
                                    <li>[LPMG] - Loyalty Point Month Goal (Lots to Trade to Meet Highest)</li>
                                    <li>[LPYP] - Loyalty Point Year Position</li>
                                    <li>[LPYR] - Loyalty Point Year Rank Value</li>
                                    <li>[LPYHR] - Loyalty Point Year Highest Rank Value</li>
                                    <li>[LPYG] - Loyalty Point Year Goal (Lots to Trade to Meet Highest)</li>
                                    <li>[LPYD] - Loyalty Point Year Difference (Compared to Highest)</li>
                                    <li>[UC] - User Code (Unique to every client)</li>
                                    <li>[LTD] - Last Trade Date</li>
                                    <li>[LTV] - Last Trade Volume</li>
                                </ul>
                                
                                <form data-toggle="validator" class="form-horizontal" enctype="multipart/form-data" role="form" method="post" action="<?php echo $_SERVER['REQUEST_URI']; ?>">
                                    <input type="hidden" name="campaign_email_no" value="<?php if(isset($selected_campaign_email['campaign_email_id'])) { echo $selected_campaign_email['campaign_email_id']; } ?>" />
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
                                            <input type="text" name="subject" class="form-control" id="subject" value="<?php if(isset($selected_campaign_email['subject'])) { echo $selected_campaign_email['subject']; } ?>" placeholder="Your Subject" required/>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="control-label col-sm-2" for="category">Category:</label>
                                        <div class="col-sm-10 col-lg-6">
                                            <select name="campaign_category" class="form-control" id="category" required>
                                                <option value=""> - select category - </option>
                                                <?php if(isset($all_campaign_category) && !empty($all_campaign_category)) { foreach ($all_campaign_category as $row) { ?>
                                                <option value="<?php echo $row['campaign_category_id']; ?>" <?php if(isset($selected_campaign_email['campaign_category_id']) && $row['campaign_category_id'] == $selected_campaign_email['campaign_category_id']) { echo "selected='selected'"; } ?>><?php echo $row['title']; ?></option>
                                                <?php } } ?>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="control-label col-sm-2" for="subject">Mail Templates:</label>
                                        <div class="col-sm-10">
                                            <?php if(isset($mail_templates) && !empty($mail_templates)){ ?>
                                                <?php foreach ($mail_templates as $row){ ?>
                                                <div class="col-sm-3">
                                                    <a onclick="add_template('<?php echo $row['html']?>')" href="javascript:void(0);">
                                                        <img class="img-thumbnail img-responsive" src="../mail_templates/<?php echo $row['image']?>"/>
                                                    </a>
                                                </div>
                                                <?php } ?>
                                            <?php } ?>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="control-label col-sm-2" for="content">Message:</label>
                                        <div class="col-sm-10"><textarea name="content" id="content" rows="3" class="form-control"><?php if(isset($selected_campaign_email['content'])) { echo $selected_campaign_email['content']; } ?></textarea></div>
                                    </div>
                                    <div class="form-group">
                                        <label class="control-label col-sm-2" for="status">Status:</label>
                                        <div class="col-sm-10 col-lg-5">
                                            <div class="radio">
                                                <label><input id="venue" type="radio" name="campaign_email_status" value="1" <?php if($selected_campaign_email['status'] == '1') { echo "checked"; } ?> required>Draft</label>
                                            </div>
                                            <div class="radio">
                                                <label><input id="venue" type="radio" name="campaign_email_status" value="2" <?php if($selected_campaign_email['status'] == '2') { echo "checked"; } ?> required>Publish</label>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <div class="col-sm-offset-2 col-sm-10">
                                            <button type="button" data-target="#save-email-confirm" data-toggle="modal" class="btn btn-success"><i class="fa fa-save fa-fw"></i> Save</button>
                                            <button type="button" data-target="#savenew-email-confirm" data-toggle="modal" class="btn btn-info"><i class="fa fa-save fa-fw"></i> Save As New</button>
                                        </div>
                                    </div>

                                    <!-- Modal - confirmation boxes -->
                                    <div id="save-email-confirm" tabindex="-1" role="dialog" aria-hidden="true" class="modal fade">
                                        <div class="modal-dialog">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <button type="button" data-dismiss="modal" aria-hidden="true"
                                                        class="close">&times;</button>
                                                    <h4 class="modal-title">Save Email Confirmation</h4></div>
                                                <div class="modal-body">Do you want to save this email now?</div>
                                                <div class="modal-footer">
                                                    <input name="process" type="submit" class="btn btn-success" value="Save">
                                                    <button type="submit" name="decline" onClick="window.close();" data-dismiss="modal" class="btn btn-danger">Close !</button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    
                                    <div id="savenew-email-confirm" tabindex="-1" role="dialog" aria-hidden="true" class="modal fade">
                                        <div class="modal-dialog">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <button type="button" data-dismiss="modal" aria-hidden="true"
                                                        class="close">&times;</button>
                                                    <h4 class="modal-title">Save Email Confirmation</h4></div>
                                                <div class="modal-body">Do you want to save email as new?</div>
                                                <div class="modal-footer">
                                                    <input name="process" type="submit" class="btn btn-success" value="Save As New">
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