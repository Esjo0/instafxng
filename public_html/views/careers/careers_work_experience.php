<?php
$my_work_experience = $obj_careers->get_applicant_work_experience($_SESSION['cu_unique_code']);
$all_states = $system_object->get_all_states();
?>

<p><strong>Past Work Experience</strong></p>
<p>Use the form below to save your work experience.</p>

<table class="table table-responsive table-striped table-bordered table-hover">
    <thead>
    <tr>
        <th>Job Title</th>
        <th>Company</th>
        <th>Location</th>
        <th>Start Date</th>
        <th>End Date</th>
        <th>Work Description</th>
    </tr>
    </thead>
    <tbody>
    <?php if(isset($my_work_experience) && !empty($my_work_experience)) { foreach ($my_work_experience AS $row) { ?>
        <tr>
            <td><?php echo $row['job_title']; ?></td>
            <td><?php echo $row['company']; ?></td>
            <td><?php echo $row['state']; ?></td>
            <td><?php echo datetime_to_text2($row['date_from']); ?></td>
            <td><?php echo datetime_to_text2($row['date_to']); ?></td>
            <td><?php echo $row['description']; ?></td>
        </tr>
    <?php } } else { echo "<tr><td colspan='6' class='text-danger'><em>No results to display</em></td></tr>"; } ?>
    </tbody>
</table>
<hr />

<p>Add Another Work Experience</p>

<form data-toggle="validator" class="form-horizontal" role="form" method="post" action="<?php echo $_SERVER['REQUEST_URI']; ?>">
    <input name="client_no" type="hidden" value="<?php echo encrypt($_SESSION['cu_unique_code']); ?>" />

    <div class="form-group">
        <label class="control-label col-sm-3" for="c_job_title">Job Title:</label>
        <div class="col-sm-9 col-lg-5">
            <input name="c_job_title" type="text" class="form-control" id="c_job_title" value="" required />
        </div>
    </div>
    <div class="form-group">
        <label class="control-label col-sm-3" for="c_company">Company:</label>
        <div class="col-sm-9 col-lg-5">
            <input name="c_company" type="text" class="form-control" id="c_company" value="" required/>
        </div>
    </div>

    <div class="form-group">
        <label class="control-label col-sm-3" for="c_location">Location:</label>
        <div class="col-sm-9 col-lg-5">
            <select name="c_location" class="form-control" id="c_location" required>
                <option value="" selected>Select State</option>
                <?php foreach($all_states as $key => $value) { ?>
                    <option value="<?php echo $value['state_id']; ?>"><?php echo $value['state']; ?></option>
                <?php } ?>
            </select>
        </div>
    </div>

    <div class="form-group">
        <label class="control-label col-sm-3" for="start_date">Start Date:</label>
        <div class="col-sm-9 col-lg-5">
            <div class='input-group date' id='start_date1'>
                <input name="start_date" type="text" class="form-control" id='start_date' value="" required />
                <span class="input-group-addon"><span class="glyphicon glyphicon-calendar"></span></span>
            </div>
            <span class="help-block">Format: (YYYY-MM-DD) e.g. 2016-12-25</span>
        </div>
        <script type="text/javascript">
            $(function () {
                $('#start_date1').datetimepicker({
                    format: 'YYYY-MM-DD'
                });
                $('#start_date').datetimepicker({
                    format: 'YYYY-MM-DD'
                });
            });
        </script>
    </div>

    <div class="form-group">
        <label class="control-label col-sm-3" for="end_date">End Date:</label>
        <div class="col-sm-9 col-lg-5">
            <div class='input-group date' id='end_date1'>
                <input name="end_date" type="text" class="form-control" id='end_date' value="" required />
                <span class="input-group-addon"><span class="glyphicon glyphicon-calendar"></span></span>
            </div>
            <span class="help-block">Format: (YYYY-MM-DD) e.g. 2016-12-25</span>
        </div>
        <script type="text/javascript">
            $(function () {
                $('#end_date1').datetimepicker({
                    format: 'YYYY-MM-DD'
                });
                $('#end_date').datetimepicker({
                    format: 'YYYY-MM-DD'
                });
            });
        </script>
    </div>

    <div class="form-group">
        <label class="control-label col-sm-3" for="c_description">Job Description:</label>
        <div class="col-sm-9 col-lg-7">
            <textarea name="c_description" class="form-control" cols="65" rows="3" id="c_description" required></textarea>
        </div>
    </div>

    <div class="form-group">
        <div class="col-sm-offset-3 col-sm-9">
            <?php if($application_editable) { ?><input name="work_experience_save" type="submit" class="btn btn-info" value="Save Work Experience" /><?php } ?>
        </div>
    </div>
</form>


<script src='https://www.google.com/recaptcha/api.js'></script>
<script src="//cdnjs.cloudflare.com/ajax/libs/moment.js/2.9.0/moment-with-locales.js"></script>
<script src="//cdn.rawgit.com/Eonasdan/bootstrap-datetimepicker/e8bddc60e73c1ec2475f827be36e1957af72e2ea/src/js/bootstrap-datetimepicker.js"></script>