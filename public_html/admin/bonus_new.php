<?php
require_once("../init/initialize_admin.php");
if (!$session_admin->is_logged_in()) {redirect_to("login.php");}
$bonus_operations = new Bonus_Operations();
$bonus_conditions = new Bonus_Condition();
$all_conditions = $bonus_conditions->BONUS_CONDITIONS;

$get_params = allowed_get_params(['package_code']);
$package_code_encrypted = $get_params['package_code'];
$package_code = decrypt_ssl(str_replace(" ", "+", $package_code_encrypted));

if(isset($_POST['process'])) {
    $bonus_title = $db_handle->sanitizePost(trim($_POST['bonus_title']));
    $bonus_desc = $db_handle->sanitizePost(trim($_POST['bonus_desc']));
    $bonus_details = $db_handle->sanitizePost($_POST['bonus_details']);

    $condition_id = $db_handle->sanitizePost(trim(implode(',', $_POST['condition_id'])));
    $status = $db_handle->sanitizePost(trim($_POST['status']));
    $type = $db_handle->sanitizePost(trim($_POST['type']));
    $type_value = $db_handle->sanitizePost(trim($_POST['bonus_type_value']));

    if(!empty($_FILES['bonus_img'])){
        $package_img = str_replace(' ', '-', $db_handle->sanitizePost(trim($_FILES["bonus_img"]["name"])));
        $img_upload_feedback = upload_file("bonus_img", "../images/bonus_packages/", $package_img);

        if($img_upload_feedback['status']){
            $new_package = $bonus_operations->create_new_package($bonus_title, $bonus_desc, $bonus_details, $img_upload_feedback['filename'], $condition_id, $status, $type, $_SESSION['admin_unique_code'], $_POST['extra'], $type_value);
        }
        $new_package ? $message_success = "Operation Successful.": $message_error = "Operation Failed.";
    }else{
        $new_package = $bonus_operations->create_new_package($bonus_title, $bonus_desc, $bonus_details, '', $condition_id, $status, $type, $_SESSION['admin_unique_code'], $_POST['extra'], $type_value);
        $new_package ? $message_success = "Operation Successful.": $message_error = "Operation Failed.";
    }

}

if(isset($_POST['update'])) {

    $bonus_code = $db_handle->sanitizePost(trim($_POST['bonus_code']));
    $bonus_title = $db_handle->sanitizePost(trim($_POST['bonus_title']));
    $bonus_desc = $db_handle->sanitizePost(trim($_POST['bonus_desc']));
    $bonus_details = $db_handle->sanitizePost($_POST['bonus_details']);

    $condition_id = $db_handle->sanitizePost(trim(implode(',', $_POST['condition_id'])));
    $status = $db_handle->sanitizePost(trim($_POST['status']));
    $type = $db_handle->sanitizePost(trim($_POST['type']));
    $type_value = $db_handle->sanitizePost(trim($_POST['bonus_type_value']));

    $package_img = str_replace(' ', '-', $db_handle->sanitizePost(trim($_FILES["bonus_img"]["name"])));
    $img_upload_feedback = upload_file("bonus_img", "../images/bonus_packages/", $package_img);

    if(isset($_FILES['bonus_img']['name']) && !empty($_FILES['bonus_img']['name'])){

        $package_img = str_replace(' ', '-', $db_handle->sanitizePost(trim($_FILES["bonus_img"]["name"]).time()));
        $img_upload_feedback = upload_file("bonus_img", "../images/bonus_packages/", $package_img);

        if ($img_upload_feedback['status']) {
            #($bonus_code, $bonus_title, $bonus_desc, $bonus_details, $bonus_image, $condition_id, $status, $type, $extra = '', $type_value)
            $update_package = $bonus_operations->update_bonus_package($bonus_code, $bonus_title, $bonus_desc, $bonus_details, filename, $condition_id, $status, $type, $_POST['extra'], $type_value);
        }
        $update_package ? $message_success = "Operation Successful.": $message_error = "Operation Failed.";
    }else{

        $update_package = $bonus_operations->update_bonus_package($bonus_code, $bonus_title, $bonus_desc, $bonus_details, '', $condition_id, $status, $type, $_POST['extra'], $type_value);
        $update_package ? $message_success = "Operation Successful.": $message_error = "Operation Failed.";
    }
}

if(!empty($package_code)){$package_details = $bonus_operations->get_package_by_code($package_code);}


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
        <script>
            function select_row (rowid, trigger) {
                divElement = document.getElementById(rowid);
                if(document.getElementById(trigger).checked) {
                    inputElements = divElement.getElementsByTagName('input');
                    for (i = 0; i < inputElements.length; i++) {
                        if (inputElements[i].type != 'text')
                            continue;
                        inputElements[i].disabled = false;
                        inputElements[i].required = true;
                    }
                } else {
                    inputElements = divElement.getElementsByTagName('input');
                    for (i = 0; i < inputElements.length; i++) {
                        if (inputElements[i].type != 'text')
                            continue;
                        inputElements[i].disabled = true;
                        inputElements[i].required = false;
                    }
                }
            }
            function readURL(input) {
                if (input.files && input.files[0]) {
                    var reader = new FileReader();
                    reader.onload = function (e) {$('#blah').attr('src', e.target.result);};
                    reader.readAsDataURL(input.files[0]);
                }
            }
        </script>
        <script src="tinymce/tinymce.min.js"></script>
        <script type="text/javascript">
            tinyMCE.init({
                selector: "textarea#bonus_details",
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
                browser_spellcheck: true
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
                            <h4><strong><?php if(!empty($package_details)){echo "UPDATE BONUS PACKAGE";}else{echo "CREATE NEW BONUS PACKAGE";}?></strong></h4>
                        </div>
                    </div>
                    
                    <div class="section-tint super-shadow">
                        <div class="row">
                            <div class="col-sm-12">
                                <?php require_once 'layouts/feedback_message.php'; ?>
                                <p><a href="bonus_list.php" class="btn btn-default" title="Manage Bonus Packages"><i class="fa fa-arrow-circle-left"></i> Manage Bonus Packages</a></p>
                                <p>Fill the form below to create a new bonus package.</p>
                                <form enctype="multipart/form-data" data-toggle="validator" class="form-horizontal" role="form" method="post" action="<?php echo $_SERVER['REQUEST_URI']; ?>">
                                    <?php if(!empty($package_details)):?>
                                        <input type="hidden" value="<?php echo $package_details['bonus_code'] ?>" name="bonus_code">
                                    <?php endif; ?>
                                    <div class="form-group">
                                        <label class="control-label col-sm-3" for="bonus_title">Package Name:</label>
                                        <div class="col-sm-9">
                                            <textarea maxlength="255" id="bonus_title" name="bonus_title" class="form-control" rows="2" required><?php if(!empty($package_details)){echo $package_details['bonus_title'];}?></textarea>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="control-label col-sm-3" for="bonus_desc">Package Description:</label>
                                        <div class="col-sm-9">
                                            <textarea maxlength="255" id="bonus_desc" name="bonus_desc" class="form-control" rows="7" required><?php if(!empty($package_details)){echo $package_details['bonus_desc'];}?></textarea>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="control-label col-sm-3" for="bonus_desc">Package Details:</label>
                                        <div class="col-sm-9">
                                            <textarea id="bonus_details" name="bonus_details" class="form-control" rows="7" required><?php if(!empty($package_details)){echo $package_details['bonus_details'];}?></textarea>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="control-label col-sm-3" for="bonus_img">Package Image:</label>
                                        <div class="col-sm-9">
                                            <label title="Click Here To Select An Image" class="btn btn-sm btn-default" for="img">
                                                <img width="200px" height="150px" class="img-thumbnail" id="blah" src="<?php if(isset($package_details['bonus_img']) && !empty($package_details['bonus_img'])) {echo "../images/bonus_packages/".$package_details['bonus_img'];} else{ echo '../images/placeholder.jpg';} ?>" alt="Bonus Image" />
                                            </label>
                                            <input name="bonus_img" style="display: none" id="img" class="btn btn-default" type='file' onchange="readURL(this);"  accept="['jpg', 'gif', 'png']" />
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="control-label col-sm-3">Package Conditions:</label>
                                        <div class="col-sm-9">
                                            <table class="table table-responsive table-bordered">
                                                <tbody>
                                                <?php foreach ($all_conditions as $key => $value){?>
                                                    <tr id="cond_<?php echo $key; ?>">
                                                        <td>
                                                            <input class="checkbox" <?php if(in_array_r($key,explode(',',$package_details['condition_id']))){echo 'checked';} ?> type="checkbox" onclick="select_row('cond_<?php echo $key; ?>','_<?php echo $key; ?>')" name="condition_id[]" value="<?php echo $key; ?>" id="_<?php echo $key; ?>" />
                                                        </td>
                                                        <td>
                                                            <label class="text-justify" for="_<?php echo $key; ?>"><?php echo $value['title']; ?></label><br/>
                                                            <span class="text-justify" ><?php echo $value['desc']; ?></span>
                                                        </td>
                                                        <td>
                                                            <?php foreach ($value['extra'] as $pin){ ?>
                                                                <input value="<?php echo $bonus_operations->get_condition_extras($package_details['bonus_code'],$key)[0]['meta_value'] ?>" placeholder="<?php echo $pin; ?>" type="text" name="extra[<?php echo $key; ?>][<?php echo $pin; ?>]" class="form-control" <?php if(!in_array_r($key,explode(',',$package_details['condition_id']))){echo 'disabled';} ?> />
                                                            <?php } ?>
                                                        </td>
                                                    </tr>
                                                <?php } ?>
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="control-label col-sm-3">Package Type:</label>
                                        <div class="col-sm-9">
                                            <table class="table table-responsive table-bordered">
                                                <tbody>
                                                    <!--<tr class="disabled">
                                                        <td>
                                                            <input onclick="document.getElementById('bonus_type_value_1').disabled = false; document.getElementById('bonus_type_value_2').disabled = true;" type="radio" name="type" value="1" id="type_1" required />
                                                        </td>
                                                        <td>
                                                            <label onclick="document.getElementById('bonus_type_value_1').disabled = false; document.getElementById('bonus_type_value_2').disabled = true;" for="type_1">
                                                                Percentage Based Bonus</label>
                                                        </td>
                                                        <td>
                                                            <div class="input-group">
                                                                <input id="bonus_type_value_1" placeholder="0.00" type="text" name="bonus_type_value" class="form-control" disabled />
                                                                <span class="input-group-addon">&#37;</span>
                                                            </div>
                                                        </td>
                                                    </tr>-->
                                                    <tr>
                                                        <td>
                                                            <input checked onclick="document.getElementById('bonus_type_value_2').disabled = false; document.getElementById('bonus_type_value_1').disabled = true;" type="radio" name="type" value="2" id="type_2" required />
                                                        </td>
                                                        <td>
                                                            <label onclick="document.getElementById('bonus_type_value_2').disabled = false; document.getElementById('bonus_type_value_1').disabled = true;" for="type_2">
                                                                Amount Based Bonus</label>
                                                        </td>
                                                        <td>
                                                            <div class="input-group">
                                                                <span class="input-group-addon">&dollar;</span>
                                                                <input value="<?php if(!empty($package_details)){echo $package_details['bonus_type_value'];}?>" id="bonus_type_value_2" placeholder="0.00" type="text" name="bonus_type_value" class="form-control" />
                                                            </div>
                                                        </td>
                                                    </tr>
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="control-label col-sm-3" for="firstname">Status:</label>
                                        <div class="col-sm-9">
                                            <div class="col-sm-12"><div class="radio"><label for="1"><input type="radio" name="status" <?php if(!empty($package_details) && $package_details['status'] == '1'){echo 'checked';}?> value="1" id="1" required /> Save As Draft</label></div></div>
                                            <div class="col-sm-12"><div class="radio"><label for="2"><input type="radio" name="status" <?php if(!empty($package_details) && $package_details['status'] == '2'){echo 'checked';}?> value="2" id="2" required /> Active Package</label></div></div>
                                            <div class="col-sm-12"><div class="radio"><label for="3"><input type="radio" name="status" <?php if(!empty($package_details) && $package_details['status'] == '3'){echo 'checked';}?> value="3" id="3" required /> Inactive Package</label></div></div>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <div class="col-sm-offset-3 col-sm-9">
                                            <button type="button" data-target="#confirm-add" data-toggle="modal" class="btn btn-sm btn-success">Process</button>
                                        </div>
                                    </div>
                                    <!--Modal - confirmation boxes--> 
                                    <div id="confirm-add" tabindex="-1" role="dialog" aria-hidden="true" class="modal fade">
                                        <div class="modal-dialog">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <button type="button" data-dismiss="modal" aria-hidden="true"  class="close">&times;</button>
                                                    <h4 class="modal-title">Process Package</h4></div>
                                                <div class="modal-body">
                                                    <center>Are you sure you want to process this bonus package?
                                                    <br/>This action cannot be reversed.</center></div>
                                                <div class="modal-footer">
                                                    <input name="<?php if(!empty($package_details)){echo "update";}else{echo "process";}?>" type="submit" class="btn btn-sm btn-success" value="Proceed">
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
    </body>
</html>