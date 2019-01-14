<?php
$my_biodata = $obj_careers->get_applicant_biodata($_SESSION['cu_unique_code']);
$all_states = $system_object->get_all_states();

?>

<p><strong>Bio-Data</strong></p>
<p>Use the form below to save your bio-data. If you did not make any change, no need to click the save button, move on
Education.</p>

<form data-toggle="validator" class="form-horizontal" role="form" method="post" action="<?php echo $_SERVER['REQUEST_URI']; ?>">
    <input name="client_no" type="hidden" value="<?php echo dec_enc('encrypt',$_SESSION['cu_unique_code']); ?>" />

    <div class="form-group">
        <label class="control-label col-sm-3" for="last_name">Last Name:</label>
        <div class="col-sm-9 col-lg-5">
            <input name="last_name" type="text" class="form-control" id="last_name" value="<?php if(isset($my_biodata['last_name'])) { echo $my_biodata['last_name']; } ?>" required />
        </div>
    </div>
    <div class="form-group">
        <label class="control-label col-sm-3" for="first_name">First Name:</label>
        <div class="col-sm-9 col-lg-5">
            <input name="first_name" type="text" class="form-control" id="first_name" value="<?php if(isset($my_biodata['first_name'])) { echo $my_biodata['first_name']; } ?>" required />
        </div>
    </div>
    <div class="form-group">
        <label class="control-label col-sm-3" for="other_names">Other Names:</label>
        <div class="col-sm-9 col-lg-5">
            <input name="other_names" type="text" class="form-control" id="other_names" value="<?php if(isset($my_biodata['other_names'])) { echo $my_biodata['other_names']; } ?>" required />
        </div>
    </div>
    <div class="form-group">
        <label class="control-label col-sm-3" for="phone_no">Phone Number:</label>
        <div class="col-sm-9 col-lg-5">
            <input name="phone_no" type="text" class="form-control" id="phone_no" value="<?php if(isset($my_biodata['phone_number'])) { echo $my_biodata['phone_number']; } ?>" required />
        </div>
    </div>
    <div class="form-group">
        <label class="control-label col-sm-3" for="sex">Sex:</label>
        <div class="col-sm-9 col-lg-5">
            <select name="sex" class="form-control" id="sex" required>
                <option value="">--- Select ---</option>
                <option value="M" <?php if(isset($my_biodata['sex']) && $my_biodata['sex'] == 'M') { echo 'selected'; } ?>>Male</option>
                <option value="F" <?php if(isset($my_biodata['sex']) && $my_biodata['sex'] == 'F') { echo 'selected'; } ?>>Female</option>
            </select>
        </div>
    </div>
    <div class="form-group">
        <label class="control-label col-sm-3" for="marital_status">Marital Status:</label>
        <div class="col-sm-9 col-lg-5">
            <select name="marital_status" class="form-control" id="marital_status" required>
                <option value="">--- Select ---</option>
                <option value="S" <?php if(isset($my_biodata['marital_status']) && $my_biodata['marital_status'] == 'S') { echo 'selected'; } ?>>Single</option>
                <option value="M" <?php if(isset($my_biodata['marital_status']) && $my_biodata['marital_status'] == 'M') { echo 'selected'; } ?>>Married</option>
            </select>
        </div>
    </div>

    <div class="form-group">
        <label class="control-label col-sm-3" for="state_of_origin">State of Origin:</label>
        <div class="col-sm-9 col-lg-5">
            <select name="state_of_origin" class="form-control" id="state_of_origin" >
                <option value="" selected>Select State</option>
                <?php foreach($all_states as $key => $value) { ?>
                    <option value="<?php echo $value['state_id']; ?>" <?php if(isset($my_biodata['state_of_origin']) && $my_biodata['state_of_origin'] == $value['state_id']) { echo 'selected'; } ?>><?php echo $value['state']; ?></option>
                <?php } ?>
            </select>
        </div>
    </div>

    <div class="form-group">
        <label class="control-label col-sm-3" for="dob">Date of Birth:</label>
        <div class="col-sm-9 col-lg-5">
            <div class='input-group date' id='dob1'>
                <input name="dob" type="text" class="form-control" id='dob' value="<?php if(isset($my_biodata['dob'])) { echo $my_biodata['dob']; } ?>" required />
                <span class="input-group-addon"><span class="glyphicon glyphicon-calendar"></span></span>
            </div>
            <span class="help-block">Format: (YYYY-MM-DD) e.g. 2016-12-25</span>
        </div>
        <script type="text/javascript">
            $(function () {
                $('#dob1').datetimepicker({
                    format: 'YYYY-MM-DD'
                });
                $('#dob').datetimepicker({
                    format: 'YYYY-MM-DD'
                });
            });
        </script>
    </div>

    <div class="form-group">
        <label class="control-label col-sm-3" for="address">Address:</label>
        <div class="col-sm-9 col-lg-7">
            <textarea name="address" class="form-control" cols="65" rows="3" id="address" required><?php if(isset($my_biodata['address'])) { echo $my_biodata['address']; } ?></textarea>
        </div>
    </div>

    <div class="form-group">
        <label class="control-label col-sm-3" for="state_of_residence">State of Residence:</label>
        <div class="col-sm-9 col-lg-5">
            <select name="state_of_residence" class="form-control" id="state_of_residence" required>
                <option value="" selected>Select State</option>
                <?php foreach($all_states as $key => $value) { ?>
                    <option value="<?php echo $value['state_id']; ?>" <?php if(isset($my_biodata['state']) && $my_biodata['state'] == $value['state_id']) { echo 'selected'; } ?>><?php echo $value['state']; ?></option>
                <?php } ?>
            </select>
        </div>
    </div>
    <div class="form-group">
        <div class="col-sm-offset-3 col-sm-9">
            <?php if($application_editable) { ?><input name="biodata_save" type="submit" class="btn btn-info" value="Save Biodata" />&nbsp;&nbsp;<?php } ?>
        </div>
    </div>
</form>


<script src='https://www.google.com/recaptcha/api.js'></script>
<script src="//cdnjs.cloudflare.com/ajax/libs/moment.js/2.9.0/moment-with-locales.js"></script>
<script src="//cdn.rawgit.com/Eonasdan/bootstrap-datetimepicker/e8bddc60e73c1ec2475f827be36e1957af72e2ea/src/js/bootstrap-datetimepicker.js"></script>