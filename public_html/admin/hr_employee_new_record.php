<?php

require_once("../init/initialize_admin.php");
if (!$session_admin->is_logged_in())
{
    redirect_to("login.php");
}
require_once("../../app_assets/hr_managment_system_config.php");

$get_params = allowed_get_params(['id', 'x']);

if($get_params['x'] == 'edit')
{
    $employee_code_encrypted = $get_params['id'];
    $employee_code = decrypt(str_replace(" ", "+", $employee_code_encrypted));
    $employee_code = preg_replace("/[^A-Za-z0-9 ]/", '', $employee_code);
    $employee_details = $obj_hr_management->get_record_details($employee_code);
    //extract($employee_details);
}
if(isset($_POST['submit']))
{
    foreach($_POST as $key => $value)
    {
        $_POST[$key] = $db_handle->sanitizePost(trim($value));
    }
    extract($_POST);
    $validate_record = $obj_hr_management->check_duplicate($f_name, $l_name, $p_email);

    if(!$validate_record)
    {
        if(isset($_FILES["img"]["name"]) && !empty($_FILES["img"]["name"]))
        {
            $target_dir = "../images/employee_records/";
            $temp = explode(".", $_FILES["img"]["name"]);
            $newfilename = round(time()) . '.' . end($temp);
            $target_file = $target_dir.$newfilename;
            $uploadOk = 1;
            $imageFileType = pathinfo($target_file,PATHINFO_EXTENSION);
            $check = getimagesize($_FILES["img"]["tmp_name"]);
            if($check) {$uploadOk = 1;}
            else {$uploadOk = 0;}
            if (file_exists($target_file)){$uploadOk = 0;}
            if ($_FILES["fileToUpload"]["size"] > 500000) {$uploadOk = 0;}
            if($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg") {$uploadOk = 0;}
            move_uploaded_file($_FILES["img"]["tmp_name"], $target_file);
        }
        $result = $obj_hr_management->add_new_employee_record($_POST, $target_file);
        $result ? $message_success = "New Record Added" : $message_error = "No Changes Made";


    }


}

if(isset($_POST['save_edit']))
{
    foreach($_POST as $key => $value)
    {
        $_POST[$key] = $db_handle->sanitizePost(trim($value));
    }
    if(isset($_FILES["img"]["name"]) && !empty($_FILES["img"]["name"]))
    {
        $target_dir = "../images/employee_records/";
        $temp = explode(".", $_FILES["img"]["name"]);
        $newfilename = round(time()) . '.' . end($temp);
        $target_file = $target_dir.$newfilename;
        $uploadOk = 1;
        $imageFileType = pathinfo($target_file,PATHINFO_EXTENSION);
        $check = getimagesize($_FILES["img"]["tmp_name"]);
        if($check) {$uploadOk = 1;}
        else {$uploadOk = 0;}
        if (file_exists($target_file)){$uploadOk = 0;}
        if ($_FILES["fileToUpload"]["size"] > 500000) {$uploadOk = 0;}
        if($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg") {$uploadOk = 0;}
        move_uploaded_file($_FILES["img"]["tmp_name"], $target_file);
    }
    $result = $obj_hr_management->update_employee_record($_POST, $target_file, $employee_code);
    $result ? $message_success = "Record Updated" : $message_error = "No Changes Made";
}
?>
<!DOCTYPE html>
<html lang="en">
    <head>
        <base target="_self">
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>Instaforex Nigeria | HR Management - Employee Records</title>
        <meta name="title" content="Instaforex Nigeria | HR Management - Employee Records" />
        <meta name="keywords" content="" />
        <meta name="description" content="" />
        <link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
        <?php require_once 'layouts/head_meta.php'; ?>
        <script>
            function ValidateExtension()
            {
                var allowedFiles = [".png", ".jpg"];
                var fileUpload = document.getElementById("blah");
                //var lblError = document.getElementById("lblError");
                var regex = new RegExp("([a-zA-Z0-9\s_\\.\-:])+(" + allowedFiles.join('|') + ")$");
                if (!regex.test(fileUpload.value.toLowerCase()))
                {
                    window.alert("Please upload files having extensions: <b>" + allowedFiles.join(', ') + "</b> only.");
                    return false;
                }
                //lblError.innerHTML = "";
                return true;
            }
            function readURL(input)
            {
                //ValidateExtension();
                if (input.files && input.files[0])
                {
                    var reader = new FileReader();
                    reader.onload = function (e)
                    {
                        $('#blah').attr('src', e.target.result);
                    };
                    reader.readAsDataURL(input.files[0]);
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
                            <h4><strong><?php if($get_params['x'] == 'edit'){echo 'UPDATE';}else{ echo 'NEW';} ?> EMPLOYEE RECORD</strong></h4>
                        </div>
                    </div>
                    
                    <div class="section-tint super-shadow">
                        <div class="row">
                            <div class="col-sm-12">
                                <?php require_once 'layouts/feedback_message.php'; ?>
                                <p class="text-right"><a href="#"  class="btn btn-default" title="Employee Records">  Employee Records <i class="fa fa-arrow-circle-right"></i></a></p>
                                <p>Fill the form below to <?php if($get_params['x'] == 'edit'){echo 'update an employee ';}else{ echo 'create a new ';} ?>record.</p>
                                <form data-toggle="validator" class="form-horizontal" role="form" method="post" enctype="multipart/form-data" action="">
                                    <div class="col-sm-12" style="padding: 30px">
                                        <div class="row">
                                            <div class="col-sm-8" >
                                                <fieldset>
                                                    <legend>Employee Information:</legend>
                                                    <div class="form-group">
                                                        <label for="station">Station</label>
                                                        <select id="station" name="station" class="form-control">
                                                            <option value="">Select Station</option>
                                                            <?php if(isset($employee_details['station']) && !empty($employee_details['station'])): ?>
                                                                <option selected value="<?php echo $employee_details['station'] ?>"><?php echo $office_stations[$employee_details['station']] ?></option>
                                                            <?php endif; ?>
                                                            <?php foreach($office_stations as $key => $value) { ?>
                                                                <option value="<?php echo $key ?>"><?php echo $value ?></option>
                                                            <?php }?>
                                                        </select>
                                                    </div>
                                                    <div class="form-group">
                                                        <label for="dept">Department</label>
                                                        <select id="dept" name="dept" class="form-control">
                                                            <option value="">Select Department</option>
                                                            <?php if(isset($employee_details['dept']) && !empty($employee_details['dept'])): ?>
                                                                <option selected value="<?php echo $employee_details['dept'] ?>"><?php echo $department[$employee_details['dept']] ?></option>
                                                            <?php endif; ?>
                                                            <?php foreach($department as $key => $value) { ?>
                                                                <option value="<?php echo $key ?>"><?php echo $value ?></option>
                                                            <?php }?>
                                                        </select>
                                                    </div>
                                                    <div class="form-group">
                                                        <label for="e_type">Employee Type</label>
                                                        <select id="e_type" name="e_type" class="form-control">
                                                            <option value="">Select Type</option>
                                                            <?php if(isset($employee_details['e_type']) && !empty($employee_details['e_type'])): ?>
                                                                <option selected value="<?php echo $employee_details['e_type'] ?>"><?php echo $department[$employee_details['e_type']] ?></option>
                                                            <?php endif; ?>
                                                            <?php foreach($e_type as $key => $value) { ?>
                                                                <option value="<?php echo $key ?>"><?php echo $value ?></option>
                                                            <?php }?>
                                                        </select>
                                                    </div>
                                                    <div class="form-group">
                                                        <label for="e_cat">Employee Category</label>
                                                        <select id="e_cat" name="e_cat" class="form-control">
                                                            <option value="">Select Category</option>
                                                            <?php if(isset($employee_details['e_cat']) && !empty($employee_details['e_cat'])): ?>
                                                                <option selected value="<?php echo $employee_details['e_cat'] ?>"><?php echo $department[$employee_details['e_cat']] ?></option>
                                                            <?php endif; ?>
                                                            <?php foreach($e_cat as $key => $value) { ?>
                                                                <option value="<?php echo $key ?>"><?php echo $value ?></option>
                                                            <?php }?>
                                                        </select>
                                                    </div>
                                                    <div class="form-group">
                                                        <label for="j_title">Designation / Job Title</label>
                                                        <input id="j_title" name="j_title" class="form-control"
                                                            <?php if(isset($employee_details['j_title']) && !empty($employee_details['j_title'])): ?>
                                                                value="<?php echo $employee_details['j_title'] ?>"
                                                            <?php endif; ?>
                                                        />
                                                    </div>
                                                </fieldset>
                                            </div>
                                            <div class="col-sm-4">
                                                <div class="form-group">
                                                    <center>
                                                        <img width="150px" height="150px" class="img-thumbnail" id="blah" src="<?php if(isset($employee_details['img_url']) && !empty($employee_details['img_url'])) {echo $employee_details['img_url'];} else{ echo '../images/placeholder.jpg';} ?>" alt="your image" />
                                                        <br/>
                                                        <input name="img" style="display: none" id="img" class="btn btn-default" type='file' onchange="readURL(this);" accept="image/png" />
                                                        <label class="btn btn-sm btn-default" for="img">Upload</label>
                                                    </center>
                                                </div>
                                            </div>
                                        </div>
                                        <br/>
                                        <br/>
                                        <div class="row">
                                            <div class="col-sm-6">
                                                <fieldset style="padding-right: 10px ">
                                                        <legend>Employee Personal Information:</legend>
                                                        <div class="form-group">
                                                            <label for="title">Salutation</label>
                                                            <select id="title" name="title" class="form-control">
                                                                <option value="">Select Title</option>
                                                                <?php if(isset($employee_details['title']) && !empty($employee_details['title'])): ?>
                                                                    <option selected value="<?php echo $employee_details['title'] ?>"><?php echo $department[$employee_details['title']] ?></option>
                                                                <?php endif; ?>
                                                                <?php foreach($salutation as $key => $value) { ?>
                                                                    <option value="<?php echo $key ?>"><?php echo $value ?></option>
                                                                <?php }?>
                                                            </select>
                                                        </div>
                                                        <div class="form-group">
                                                            <label for="f_name">First Name</label>
                                                            <input id="f_name" name="f_name" class="form-control"
                                                                <?php if(isset($employee_details['f_name']) && !empty($employee_details['f_name'])): ?>
                                                                   value="<?php echo $employee_details['f_name'] ?>"
                                                            <?php endif; ?>
                                                            />
                                                        </div>
                                                        <div class="form-group">
                                                            <label for="l_name">Last Name</label>
                                                            <input id="l_name" name="l_name" class="form-control"
                                                                <?php if(isset($employee_details['l_name']) && !empty($employee_details['l_name'])): ?>
                                                                   value="<?php echo $employee_details['l_name'] ?>"
                                                            <?php endif; ?>
                                                            />
                                                        </div>
                                                        <div class="form-group">
                                                            <label for="m_name">Middle Name</label>
                                                            <input id="m_name" name="m_name" class="form-control"
                                                                <?php if(isset($employee_details['m_name']) && !empty($employee_details['m_name'])): ?>
                                                                   value="<?php echo $employee_details['m_name'] ?>"
                                                            <?php endif; ?>
                                                            />
                                                        </div>
                                                        <div class="form-group">
                                                            <label for="d_o_b">Date Of Birth</label>
                                                            <input id="d_o_b" name="d_o_b" class="form-control"
                                                                <?php if(isset($employee_details['d_o_b']) && !empty($employee_details['d_o_b'])): ?>
                                                                   value="<?php echo $employee_details['d_o_b'] ?>"
                                                            <?php endif; ?>
                                                            />
                                                            <!--<script type="text/javascript">
                                                                $(function() {
                                                                    $( "#d_o_b" ).datepicker({
                                                                        dateFormat : 'dd-mm-yy',
                                                                        changeMonth : true,
                                                                        changeYear : true,
                                                                        yearRange: '-100y:c+nn',
                                                                        maxDate: '-1d'
                                                                    });
                                                                });
                                                            </script>-->
                                                            <script type="text/javascript">
                                                                $(function () {
                                                                    $('#d_o_b').datetimepicker({
                                                                        format: 'DD-MM-YYYY'
                                                                    });
                                                                });
                                                            </script>
                                                        </div>
                                                        <div class="form-group">
                                                            <label for="gender">Gender</label>
                                                            <div class="form-group row">
                                                                <div class="col-sm-3">
                                                                    <div class="radio">
                                                                        <label for="Male">
                                                                            <input id="Male" type="radio" name="gender" value="Male" <?php if($employee_details['gender'] == 'Male'){echo 'selected';} ?>/>
                                                                            Male
                                                                        </label>
                                                                    </div>
                                                                </div>
                                                                <div class="col-sm-2">
                                                                    <div class="radio">
                                                                        <label for="Female">
                                                                            <input id="Female" type="radio" name="gender" value="Female" <?php if($employee_details['gender'] == 'Female'){echo 'selected';} ?>/>
                                                                            Female
                                                                        </label>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="form-group">
                                                            <label for="b_group">Blood Group</label>
                                                            <div class="form-group row">
                                                                <div class="col-sm-2">
                                                                    <div class="radio">
                                                                        <label for="A+">
                                                                            <input id="A+" type="radio" name="b_group" value="A+" <?php if($employee_details['b_group'] == 'A+'){echo 'selected';} ?>/>
                                                                            A+
                                                                        </label>
                                                                    </div>
                                                                </div>
                                                                <div class="col-sm-2">
                                                                    <div class="radio">
                                                                        <label for="A-">
                                                                            <input id="A-" type="radio" name="b_group" value="A-" <?php if($employee_details['b_group'] == 'A-'){echo 'selected';} ?>/>
                                                                            A-
                                                                        </label>
                                                                    </div>
                                                                </div>
                                                                <div class="col-sm-2">
                                                                    <div class="radio">
                                                                        <label for="B+">
                                                                            <input id="B+" type="radio" name="b_group" value="B+" <?php if($employee_details['b_group'] == 'B+'){echo 'selected';} ?>/>
                                                                            B+
                                                                        </label>
                                                                    </div>
                                                                </div>
                                                                <div class="col-sm-2">
                                                                    <div class="radio">
                                                                        <label for="B-">
                                                                            <input id="B-" type="radio" name="b_group" value="B-" <?php if($employee_details['b_group'] == 'B-'){echo 'selected';} ?>/>
                                                                            B-
                                                                        </label>
                                                                    </div>
                                                                </div>
                                                                <div class="col-sm-2">
                                                                    <div class="radio">
                                                                        <label for="O+">
                                                                            <input id="O+" type="radio" name="b_group" value="O+" <?php if($employee_details['b_group'] == 'O+'){echo 'selected';} ?>/>
                                                                            O+
                                                                        </label>
                                                                    </div>
                                                                </div>
                                                                <div class="col-sm-2">
                                                                    <div class="radio">
                                                                        <label for="O-">
                                                                            <input id="O-" type="radio" name="b_group" value="O-" <?php if($employee_details['b_group'] == 'O-'){echo 'selected';} ?>/>
                                                                            O-
                                                                        </label>
                                                                    </div>
                                                                </div>
                                                                <div class="col-sm-2">
                                                                    <div class="radio">
                                                                        <label for="AB+">
                                                                            <input id="AB+" type="radio" name="b_group" value="AB+" <?php if($employee_details['b_group'] == 'AB+'){echo 'selected';} ?>/>
                                                                            AB+
                                                                        </label>
                                                                    </div>
                                                                </div>
                                                                <div class="col-sm-2">
                                                                    <div class="radio">
                                                                        <label for="AB-">
                                                                            <input id="AB-" type="radio" name="b_group" value="AB-" <?php if($employee_details['b_group'] == 'AB-'){echo 'selected';} ?>/>
                                                                            AB-
                                                                        </label>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="form-group">
                                                            <label for="nationality">Nationality</label>
                                                            <input id="nationality" name="nationality" class="form-control"
                                                                <?php if(isset($employee_details['nationality']) && !empty($employee_details['nationality'])): ?>
                                                                   value="<?php echo $employee_details['nationality'] ?>"
                                                            <?php endif; ?>
                                                            />
                                                        </div>
                                                        <div class="form-group">
                                                            <label for="s_origin">State Of Origin</label>
                                                            <select id="s_origin" name="s_origin" class="form-control">
                                                                <option value="">Select State</option>
                                                                <?php if(isset($employee_details['s_of_origin']) && !empty($employee_details['s_of_origin'])): ?>
                                                                    <option selected value="<?php echo $employee_details['s_of_origin'] ?>"><?php echo $states[$employee_details['s_of_origin']] ?></option>
                                                                <?php endif; ?>
                                                                <?php foreach($states as $key => $value) { ?>
                                                                    <option value="<?php echo $key ?>"><?php echo $value ?></option>
                                                                <?php }?>
                                                            </select>
                                                        </div>
                                                        <div class="form-group">
                                                            <label for="l_g_a">Local Government Area</label>
                                                            <input id="l_g_a" name="l_g_a" class="form-control"
                                                                <?php if(isset($employee_details['l_g_a']) && !empty($employee_details['l_g_a'])): ?>
                                                                   value="<?php echo $employee_details['l_g_a'] ?>"
                                                            <?php endif; ?>
                                                            />
                                                        </div>
                                                        <div class="form-group">
                                                            <label for="tribe">Tribe</label>
                                                            <input id="tribe" name="tribe" class="form-control"
                                                                <?php if(isset($employee_details['tribe']) && !empty($employee_details['tribe'])): ?>
                                                                   value="<?php echo $employee_details['tribe'] ?>"
                                                            <?php endif; ?>
                                                            />
                                                        </div>
                                                        <div class="form-group">
                                                            <label for="religion">Religion</label>
                                                            <select id="religion" name="religion" class="form-control">
                                                                <option value="">Select Religion</option>
                                                                <?php if(isset($employee_details['religion']) && !empty($employee_details['religion'])): ?>
                                                                    <option selected value="<?php echo $employee_details['religion'] ?>"><?php echo $religion[$employee_details['religion']] ?></option>
                                                                <?php endif; ?>
                                                                <?php foreach($religion as $key => $value) { ?>
                                                                    <option value="<?php echo $key ?>"><?php echo $value ?></option>
                                                                <?php }?>
                                                            </select>
                                                        </div>
                                                        <div class="form-group">
                                                            <label for="m_stat">Marital Status</label>
                                                            <div class="form-group row">
                                                                <div class="col-sm-3">
                                                                    <div class="radio">
                                                                        <label for="Single">
                                                                            <input id="Single" type="radio" name="m_stat" value="Single" <?php if($employee_details['m_stat'] == 'Single'){echo 'selected';} ?>/>
                                                                            Single
                                                                        </label>
                                                                    </div>
                                                                </div>
                                                                <div class="col-sm-3">
                                                                    <div class="radio">
                                                                        <label for="Single">
                                                                            <input id="Married" type="radio" name="m_stat" value="Married" <?php if($employee_details['m_stat'] == 'Married'){echo 'selected';} ?>/>
                                                                            Married
                                                                        </label>
                                                                    </div>
                                                                </div>
                                                                <div class="col-sm-3">
                                                                    <div class="radio">
                                                                        <label for="Married">
                                                                            <input id="Married" type="radio" name="m_stat" value="Divorced" <?php if($employee_details['m_stat'] == 'Divorced'){echo 'selected';} ?>/>
                                                                            Divorced
                                                                        </label>
                                                                    </div>
                                                                </div>
                                                                <div class="col-sm-3">
                                                                    <div class="radio">
                                                                        <label for="Widowed">
                                                                            <input id="Widowed" type="radio" name="m_stat" value="Widowed" <?php if($employee_details['m_stat'] == 'Widowed'){echo 'selected';} ?>/>
                                                                            Widowed
                                                                        </label>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </fieldset>
                                            </div>
                                            <div class="col-sm-6">
                                                <fieldset style="padding-left: 10px ">
                                                    <legend>Employee Additional Information:</legend>
                                                    <div class="form-group">
                                                        <label for="j_date">Join Date</label>
                                                        <input id="j_date" name="j_date" class="form-control"
                                                            <?php if(isset($employee_details['j_date']) && !empty($employee_details['j_date'])): ?>
                                                               value="<?php echo $employee_details['j_date'] ?>"
                                                        <?php endif; ?>
                                                        />
                                                        <!--<script>
                                                            $(function() {
                                                                $( "#j_date" ).datepicker(
                                                                    {
                                                                    dateFormat : 'dd-mm-yy',
                                                                    changeMonth : true,
                                                                    changeYear : true,
                                                                    yearRange: '-100y:c+nn'
                                                                });
                                                            });
                                                        </script>-->
                                                        <script type="text/javascript">
                                                            $(function () {
                                                                $('#j_date').datetimepicker({
                                                                    format: 'DD-MM-YYYY'
                                                                });
                                                            });
                                                        </script>
                                                    </div>
                                                    <div class="form-group">
                                                        <label for="tin">Tax Identification Number (TIN)</label>
                                                        <input id="tin" name="tin" class="form-control"
                                                            <?php if(isset($employee_details['tin']) && !empty($employee_details['tin'])): ?>
                                                                value="<?php echo $employee_details['tin'] ?>"
                                                            <?php endif; ?>
                                                        />
                                                    </div>
                                                    <div class="form-group">
                                                        <label for="id_num">Identification Card Number</label>
                                                        <input id="id_num" name="id_num" class="form-control"
                                                            <?php if(isset($employee_details['id_num']) && !empty($employee_details['id_num'])): ?>
                                                                value="<?php echo $employee_details['id_num'] ?>"
                                                            <?php endif; ?>
                                                        />
                                                    </div>
                                                    <div class="form-group">
                                                        <label for="id_ex_date">Identification Card Expiration Date</label>
                                                        <input id="id_ex_date" name="id_ex_date" class="form-control"
                                                            <?php if(isset($employee_details['id_ex_date']) && !empty($employee_details['id_ex_date'])): ?>
                                                                value="<?php echo $employee_details['id_ex_date'] ?>"
                                                            <?php endif; ?>
                                                        />
                                                        <!--<script>
                                                            $(function() {
                                                                $( "#id_ex_date" ).datepicker(
                                                                    {
                                                                        dateFormat : 'dd-mm-yy',
                                                                        changeMonth : true,
                                                                        changeYear : true,
                                                                        yearRange: '-100y:c+nn'
                                                                    });
                                                            });
                                                        </script>-->
                                                        <script type="text/javascript">
                                                            $(function () {
                                                                $('#id_ex_date').datetimepicker({
                                                                    format: 'DD-MM-YYYY'
                                                                });
                                                            });
                                                        </script>
                                                    </div>
                                                </fieldset>
                                            </div>
                                        </div>
                                        <br/>
                                        <br/>
                                        <div class="row">
                                            <div class="col-sm-6">
                                                <fieldset style="padding-right: 10px ">
                                                    <legend>Contact Information:</legend>
                                                    <div class="form-group">
                                                        <label for="address">Address</label>
                                                        <textarea rows="2" id="address" name="address" class="form-control">
                                                            <?php if(isset($employee_details['address']) && !empty($employee_details['address'])): ?>
                                                                <?php echo $employee_details['address'] ?>
                                                            <?php endif; ?>
                                                        </textarea>
                                                    </div>
                                                    <div class="form-group">
                                                        <label for="city">City</label>
                                                        <input id="city" name="city" class="form-control"
                                                            <?php if(isset($employee_details['city']) && !empty($employee_details['city'])): ?>
                                                                value="<?php echo $employee_details['city'] ?>"
                                                            <?php endif; ?>
                                                        />
                                                    </div>
                                                    <div class="form-group">
                                                        <label for="state">State</label>
                                                        <select id="state" name="state" class="form-control">
                                                            <option value="">Select State</option>
                                                            <?php if(isset($employee_details['state']) && !empty($employee_details['state'])): ?>
                                                                <option selected value="<?php echo $employee_details['state'] ?>"><?php echo $states[$employee_details['state']] ?></option>
                                                            <?php endif; ?>
                                                            <?php foreach($states as $key => $value) { ?>
                                                                <option value="<?php echo $key ?>"><?php echo $value ?></option>
                                                            <?php }?>
                                                        </select>
                                                    </div>
                                                    <div class="form-group">
                                                        <label for="country">Country</label>
                                                        <input id="country" name="country" class="form-control"
                                                            <?php if(isset($employee_details['city']) && !empty($employee_details['country'])): ?>
                                                                value="<?php echo $employee_details['city'] ?>"
                                                            <?php endif; ?>
                                                        />
                                                    </div>
                                                    <div class="form-group">
                                                        <label for="p_phone">Personal Phone Number</label>
                                                        <input id="p_phone" name="p_phone" class="form-control"
                                                            <?php if(isset($employee_details['p_phone']) && !empty($employee_details['p_phone'])): ?>
                                                                value="<?php echo $employee_details['p_phone'] ?>"
                                                            <?php endif; ?>
                                                        />
                                                    </div>
                                                    <div class="form-group">
                                                        <label for="o_phone">Office Phone Number</label>
                                                        <input id="o_phone" name="o_phone" class="form-control"
                                                            <?php if(isset($employee_details['o_phone']) && !empty($employee_details['o_phone'])): ?>
                                                                value="<?php echo $employee_details['o_phone'] ?>"
                                                            <?php endif; ?>
                                                        />
                                                    </div>
                                                    <div class="form-group">
                                                        <label for="p_email">Personal Email Address</label>
                                                        <input id="p_email" name="p_email" class="form-control"
                                                            <?php if(isset($employee_details['p_email']) && !empty($employee_details['p_email'])): ?>
                                                                value="<?php echo $employee_details['p_email'] ?>"
                                                            <?php endif; ?>
                                                        />
                                                    </div>
                                                    <div class="form-group">
                                                        <label for="o_email">Office Email Address</label>
                                                        <input id="o_email" name="o_email" class="form-control"
                                                            <?php if(isset($employee_details['o_email']) && !empty($employee_details['o_email'])): ?>
                                                                value="<?php echo $employee_details['o_email'] ?>"
                                                            <?php endif; ?>
                                                        />
                                                    </div>
                                                </fieldset>
                                            </div>
                                            <div class="col-sm-6">
                                                <fieldset style="padding-left: 10px ">
                                                    <legend>Emergency Contact:</legend>
                                                    <div class="form-group">
                                                        <label for="e_name">Contact Person</label>
                                                        <input id="e_name" name="e_name" class="form-control"
                                                            <?php if(isset($employee_details['e_name']) && !empty($employee_details['e_name'])): ?>
                                                                value="<?php echo $employee_details['e_name'] ?>"
                                                            <?php endif; ?>
                                                        />
                                                    </div>
                                                    <div class="form-group">
                                                        <label for="e_rel">Relationship</label>
                                                        <input id="e_rel" name="e_rel" class="form-control"
                                                            <?php if(isset($employee_details['e_name']) && !empty($employee_details['e_name'])): ?>
                                                                value="<?php echo $employee_details['e_name'] ?>"
                                                            <?php endif; ?>
                                                        />
                                                    </div>
                                                    <div class="form-group">
                                                        <label for="e_phone">Phone Number</label>
                                                        <input id="e_phone" name="e-type" class="form-control"
                                                            <?php if(isset($employee_details['e_name']) && !empty($employee_details['e_name'])): ?>
                                                                value="<?php echo $employee_details['e_name'] ?>"
                                                            <?php endif; ?>
                                                        />
                                                    </div>
                                                </fieldset>
                                            </div>
                                        </div>
                                        <br/>
                                        <br/>
                                        <div class="row">
                                            <div class="col-sm-12">
                                                <fieldset style="padding-left: 10px ">
                                                    <legend>Additional Information:</legend>
                                                    <div class="form-group">
                                                        <label for="notes">Notes</label>
                                                        <textarea id="notes" name="notes" rows="5" class="form-control" <?php if($get_params['x'] == 'edit'){echo 'required';} ?>></textarea>
                                                    </div>
                                                </fieldset>
                                            </div>
                                        </div>
                                    </div>
                                    <button type="button" data-target="#rates-log-confirm" data-toggle="modal" class="btn btn-success"><i class="fa fa-fw fa-save"></i > Save</button>
                                    <!-- Modal - confirmation boxes -->
                                    <div id="rates-log-confirm" tabindex="-1" role="dialog" aria-hidden="true" class="modal fade">
                                        <div class="modal-dialog">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <button type="button" data-dismiss="modal" aria-hidden="true" class="close">&times;</button>
                                                    <h4 class="modal-title"><?php if($get_params['x'] == 'edit'){echo 'Update';}else{ echo 'New';} ?> Employee Record</h4>
                                                </div>
                                                <div class="modal-body">Are you sure you want to save this information?</div>
                                                <div class="modal-footer">
                                                    <input name="<?php if($get_params['x'] == 'edit'){echo 'save_edit';}else{ echo 'submit';} ?>" type="submit" class="btn btn-success" value="Save" />
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
    <script src="//cdnjs.cloudflare.com/ajax/libs/moment.js/2.9.0/moment-with-locales.js"></script>
    <script src="//cdn.rawgit.com/Eonasdan/bootstrap-datetimepicker/e8bddc60e73c1ec2475f827be36e1957af72e2ea/src/js/bootstrap-datetimepicker.js"></script>
    <script src="//code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
</html>