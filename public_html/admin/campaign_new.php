<?php
require_once("../init/initialize_admin.php");
if (!$session_admin->is_logged_in()) {redirect_to("login.php");}
$form_fields = Loyalty_Training::DYNAMIC_LANDING_PAGE_FORM_FIELDS;
$dlp_template = Loyalty_Training::DYNAMIC_LANDING_PAGE_TEMPLATE;

$get_params = allowed_get_params(['x', 'cc']);

if($get_params['x'] == 'edit' && !empty($get_params['cc'])){
    $selected_campaign = $obj_loyalty_training->get_campaign_by_code($get_params['cc']);
    extract($selected_campaign);
}else{ $campaign_code = $obj_loyalty_training->new_campaign_code();}

if(isset($_POST['process'])){
    $campaign_title = $db_handle->sanitizePost(trim($_POST['campaign_title']));
    $campaign_desc = $db_handle->sanitizePost(trim($_POST['campaign_desc']));
    $landing_type = $db_handle->sanitizePost(trim($_POST['landing_type']));
    $dynamic_url = $db_handle->sanitizePost(trim($_POST['dynamic_url']));
    $custom_url = $db_handle->sanitizePost(trim($_POST['custom_url']));
    $status = $db_handle->sanitizePost(trim($_POST['status']));
    $dlp_content = $_POST['dlp_content'];

    foreach ($_POST['fields'] as $key => $value){ $_form_fields[$key] = $db_handle->sanitizePost(trim($value)); }
    $_form_fields = implode(',', $_form_fields);

    if($_FILES["lead_image"]["error"] == UPLOAD_ERR_OK) {
        $tmp_name = $_FILES["lead_image"]["tmp_name"];
        $extension = explode(".", $_FILES["lead_image"]["name"]);
        $lead_image = strtolower($campaign_code.'.'.end($extension));
        move_uploaded_file($tmp_name, "../images/campaigns/$lead_image");
    }

    switch ($landing_type){
        case '2':
            $query = "INSERT INTO campaign_leads_campaign 
                      (campaign_title, campaign_desc, campaign_code, form_field_ids, landing_body, landing_url, status, lead_image) 
                      VALUES 
                      ('$campaign_title', '$campaign_desc', '$campaign_code', '$_form_fields', '$dlp_content', '$dynamic_url', '$status', '$lead_image')";
            break;
        case '1':
            $query = "INSERT INTO campaign_leads_campaign 
                      (campaign_title, campaign_desc, campaign_code, landing_url, status, lead_image) 
                      VALUES 
                      ('$campaign_title', '$campaign_desc', '$campaign_code', '$custom_url', '$status', '$lead_image')";
            break;
    }
    $result = $db_handle->runQuery($query);
    $result ? $message_success = "Operation Successful" : $message_error = "Operation Failed";
}

if(isset($_POST['update'])){
    $campaign_title = $db_handle->sanitizePost(trim($_POST['campaign_title']));
    $campaign_desc = $db_handle->sanitizePost(trim($_POST['campaign_desc']));
    $landing_type = $db_handle->sanitizePost(trim($_POST['landing_type']));
    $dynamic_url = $db_handle->sanitizePost(trim($_POST['dynamic_url']));
    $custom_url = $db_handle->sanitizePost(trim($_POST['custom_url']));
    $status = $db_handle->sanitizePost(trim($_POST['status']));
    $dlp_content = $_POST['dlp_content'];

    foreach ($_POST['fields'] as $key => $value){ $_form_fields[$key] = $db_handle->sanitizePost(trim($value)); }
    $_form_fields = implode(',', $_form_fields);

    if($_FILES["lead_image"]["error"] == UPLOAD_ERR_OK) {
        $tmp_name = $_FILES["lead_image"]["tmp_name"];
        $extension = explode(".", $_FILES["lead_image"]["name"]);
        $lead_image = strtolower($campaign_code.'.'.end($extension));
        move_uploaded_file($tmp_name, "../images/campaigns/$lead_image");
    }

    switch ($landing_type){
        case '2':
            $query = "INSERT INTO campaign_leads_campaign 
                      (campaign_title, campaign_desc, campaign_code, form_field_ids, landing_body, landing_url, status, lead_image) 
                      VALUES 
                      ('$campaign_title', '$campaign_desc', '$campaign_code', '$_form_fields', '$dlp_content', '$dynamic_url', '$status', '$lead_image')";
            break;
        case '1':
            $query = "INSERT INTO campaign_leads_campaign 
                      (campaign_title, campaign_desc, campaign_code, landing_url, status, lead_image) 
                      VALUES 
                      ('$campaign_title', '$campaign_desc', '$campaign_code', '$custom_url', '$status', '$lead_image')";
            break;
    }
    var_dump($query);
    //$result = $db_handle->runQuery($query);
    $result ? $message_success = "Operation Successful" : $message_error = "Operation Failed";
}

?>
<!DOCTYPE html>
<html lang="en">
    <head>
        <base target="_self">
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>InstaFxNg | Create New Leads Campaign</title>
        <meta name="title" content="InstaFxNg | Create New Leads Campaign" />
        <meta name="keywords" content="" />
        <meta name="description" content="" />
        <?php require_once 'layouts/head_meta.php'; ?>
        <script src="//cdnjs.cloudflare.com/ajax/libs/clipboard.js/2.0.0/clipboard.min.js"></script>
        <script src="tinymce/tinymce.min.js"></script>
        <script type="text/javascript">
            tinyMCE.init({
                selector: "textarea#dlp_content",
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
                content_css : "../css/instafx_admin.css, ../css/bootstrap_3.3.5.min.css, ../css/font-awesome_4.6.3.min.css, ../css/bootstrap-datetimepicker.css"
            });
            function copy_text(btn_id){
                var btn = document.getElementById(btn_id);
                var clipboard = new ClipboardJS(btn);
                clipboard.on('success', function(e) {
                    console.log(e);
                });
                clipboard.on('error', function(e) {
                    console.log(e);
                });
            }
            function ValidateExtension(input) {
                var id = input.getAttribute('id');
                console.log(id);
                var fileInput = document.getElementById(id);
                return true;
            }
            function readURL(input){
                var filePath = input.value;
                var allowedExtensions = /(\.jpg|\.jpeg|\.png|\.gif)$/i;
                if(!allowedExtensions.exec(filePath)){
                    alert('Please select a PNG or JPG file.');
                    return false;
                }
                if (input.files && input.files[0]){
                    var reader = new FileReader();
                    reader.onload = function(e){$('#blah').attr('src', e.target.result);};
                    reader.readAsDataURL(input.files[0]);
                }
            }
            function slugify(input_id, output_id){
                var string = document.getElementById(input_id).value;
                string = string
                    .toString()
                    .trim()
                    .toLowerCase()
                    .replace(/\s+/g, "-")
                    .replace(/[^\w\-]+/g, "")
                    .replace(/\-\-+/g, "-")
                    .replace(/^-+/, "")
                    .replace(/-+$/, "");
                console.log(string+'/');
                document.getElementById(output_id).value += string+'/';
            }
            function exchange_views(in_id, out_id){
                document.getElementById(in_id).style.display = 'block';
                document.getElementById(in_id).disabled = false;
                document.getElementById(out_id).style.display = 'none';
                document.getElementById(out_id).disabled = true;
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
                            <h4><strong>CREATE NEW LEADS CAMPAIGN</strong></h4>
                        </div>
                    </div>
                    <div class="section-tint super-shadow">
                        <div class="row">
                            <div class="col-sm-12">
                                <?php require_once 'layouts/feedback_message.php'; ?>
                                <p><a href="campaign_pages.php" class="btn btn-default" title="Manage Leads Campaigns"><i class="fa fa-arrow-circle-left"></i> Manage Leads Campaigns</a></p>
                                <p>Fill the form below to create a new leads campaign.</p>
                                <form data-toggle="validator" class="form-horizontal" enctype="multipart/form-data" role="form" method="post" action="<?php echo $_SERVER['REQUEST_URI']; ?>">
                                    <div class="form-group">
                                        <label class="control-label col-sm-3" for="campaign_title">Campaign Title:</label>
                                        <div class="col-sm-9 col-lg-9">
                                            <textarea id="campaign_title" onchange="slugify('campaign_title', 'dynamic_url')" required name="campaign_title" maxlength="255" class="form-control" rows="2" placeholder="Enter the flagship title of this leads campaign."><?php echo $campaign_title ?></textarea>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="control-label col-sm-3" for="campaign_desc">Campaign Description:</label>
                                        <div class="col-sm-9 col-lg-9">
                                            <textarea placeholder="Enter a precise description of this campaign" required id="campaign_desc" name="campaign_desc" class="form-control" rows="3" ><?php echo $campaign_desc ;?></textarea>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="control-label col-sm-3" for="email">Landing Page:</label>
                                        <div class="col-sm-9 col-lg-9">
                                            <div class="form-group row">
                                                <div class="col-sm-4"><div class="radio"><label for="landing_type_1"><input onclick="exchange_views('clp','dlp')" required type="radio" name="landing_type" value="1" <?php if(!empty($landing_body)){echo 'checked';} ?> id="landing_type_1" /> Custom Built Landing Page</label></div></div>

                                                <div class="col-sm-4"><div class="radio"><label for="landing_type_2"><input onclick="exchange_views('dlp','clp')" required type="radio" name="landing_type" value="2" <?php if(empty($landing_body)){echo 'checked';} ?> id="landing_type_2" /> Dynamic Landing Page</label></div></div>

                                            </div>
                                            <div id="dlp" style="display: none">
                                                <div class="form-group row">
                                                    <div class="col-sm-12">
                                                        <br/><button class="btn btn-default btn-sm" data-target="#dynamic_landing_page" data-toggle="modal" id="dlp_trigger" type="button">Add Landing Page Contents</button>
<!--                                                        <br/><br/><span class="text-muted"><b>NB: </b>This is the link to the lead form <a onclick="copy_text('btn_<?php /*echo $count*/?>')"  data-clipboard-text="l_e_a_d_f_o_r_m_l_i_n_k" data-clipboard-action="copy" title="Click here to copy this link" href="javascript:void(0);">l_e_a_d_f_o_r_m_l_i_n_k</a> </span><br/><br/>
-->                                                    </div>
                                                </div>
                                                <div class="form-group row">
                                                    <div class="col-sm-12">
                                                        <b>Form Fields: </b><br/>
                                                        <?php foreach($form_fields as $key => $value){ ?>
                                                            <div class="col-sm-4"><div class="checkbox"><label title="<?php echo $value['desc'] ?>" for="field_<?php echo $key ?>"><input title="<?php echo $value['desc'] ?>" type="checkbox" name="fields[]" value="<?php echo $key ?>" id="field_<?php echo $key ?>" <?php if(in_array($key, explode(',', $form_field_ids))){echo 'checked';} //echo $key ?> /> <?php echo $value['name'] ?></label></div></div>
                                                        <?php } ?>
                                                    </div>
                                                </div>
                                                <div class="form-group row">
                                                    <div class="col-sm-12">
                                                        <div class="input-group">
                                                            <span class="input-group-addon"><i class="glyphicon glyphicon-link"></i></span>
                                                            <input name="dynamic_url" type="text" id="dynamic_url" value="https://instafxng.com/campaign/id/<?php echo $campaign_code?>/" class="form-control" required/>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div id="clp" style="display: none">
                                                <div class="form-group row">
                                                <div class="col-sm-12">
                                                    <div class="input-group">
                                                        <span class="input-group-addon"><i class="glyphicon glyphicon-link"></i></span>
                                                        <input placeholder="Enter the url to the landing page" name="custom_url" type="text" id="custom_url" value="<?php if(!empty($landing_body)){echo $landing_url;} ?>" class="form-control" required/>
                                                    </div>
                                                </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="control-label col-sm-3" for="display_picture">Lead Image:</label>
                                        <div class="col-sm-9 col-lg-9">
                                            <label class="btn btn-sm btn-default" for="img"><img width="200px" height="100px" class="img-thumbnail" id="blah" src="<?php if(isset($lead_image) && !empty($lead_image)) {echo "../images/campaigns/".$$lead_image;} else{ echo '../images/placeholder.jpg';} ?>" alt="Lead Image" />
                                                <br/>
                                                <input name="lead_image" style="display: none" id="img" class="btn btn-default" type='file' onchange="readURL(this);"  accept="['jpg', 'gif', 'png']" />
                                            </label>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="control-label col-sm-3" for="article_status">Status:</label>
                                        <div class="col-sm-9 col-lg-9">
                                            <div class="radio">
                                                <label><input id="status_1" type="radio" name="status" value="1" <?php if($status == '1'){echo 'checked';} ?> required>Active</label>
                                            </div>
                                            <div class="radio">
                                                <label><input id="status_2" type="radio" name="status" value="2" <?php if($status == '2'){echo 'checked';} ?> required>Inactive</label>
                                            </div>
                                        </div>
                                    </div>
                                    <div id="dynamic_landing_page" tabindex="-1" role="dialog" aria-hidden="true" class="modal fade">
                                        <div class="modal-dialog modal-lg">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <button type="button" data-dismiss="modal" aria-hidden="true"  class="close">&times;</button>
                                                    <h4 class="modal-title"><center>Dynamic Landing Page</center></h4></div>
                                                <div class="modal-body">
                                                    <textarea id="dlp_content"><?php if(!empty($landing_body)){echo $landing_body;}else{echo $dlp_template;}//  ?></textarea>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <div class="col-sm-offset-3 col-sm-9">
                                            <button type="button" data-target="#process_form" data-toggle="modal" class="btn btn-success">Process</button>
                                        </div>
                                    </div>
                                    
                                    <div id="process_form" tabindex="-1" role="dialog" aria-hidden="true" class="modal fade">
                                        <div class="modal-dialog">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <button type="button" data-dismiss="modal" aria-hidden="true"  class="close">&times;</button>
                                                    <h4 class="modal-title">Process Form</h4></div>
                                                <div class="modal-body">Are you sure you want to create a new leads campaign? </div>
                                                <div class="modal-footer">
                                                    <input name="<?php if(!empty($selected_campaign)){echo 'update';}else{echo 'process';} ?>" type="submit" class="btn btn-sm btn-success" value="Proceed">
                                                    <button type="button" name="close" onClick="window.close();" data-dismiss="modal" class="btn btn-sm btn-danger">Close!</button>
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
        <?php if(!empty($landing_body)):?>
            <script>
                document.getElementById('landing_type_1').click();
            </script>
        <?php endif; ?>
        <?php if(empty($landing_body)):?>
            <script>
                document.getElementById('landing_type_2').click();
            </script>
        <?php endif; ?>
    </body>
</html>