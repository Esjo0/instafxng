<?php
$my_education = $obj_careers->get_applicant_education($_SESSION['cu_unique_code']);

?>

<p><strong>Education</strong></p>
<p>Use the form below to save your education. Saved information will appear in the table below.</p>
<table class="table table-responsive table-striped table-bordered table-hover">
    <thead>
    <tr>
        <th>Institution</th>
        <th>Degree</th>
        <th>Grade</th>
        <th>Course</th>
        <th>Start Date</th>
        <th>End Date</th>
    </tr>
    </thead>
    <tbody>
    <?php if(isset($my_education) && !empty($my_education)) { foreach ($my_education AS $row) { ?>
        <tr>
            <td><?php echo $row['institution']; ?></td>
            <td><?php echo $row['degree']; ?></td>
            <td><?php echo $row['grade']; ?></td>
            <td><?php echo $row['field_of_study']; ?></td>
            <td><?php echo datetime_to_text2($row['date_from']); ?></td>
            <td><?php echo datetime_to_text2($row['date_to']); ?></td>
        </tr>
    <?php } } else { echo "<tr><td colspan='6' class='text-danger'><em>No results to display</em></td></tr>"; } ?>
    </tbody>
</table>
<hr />

<p>Add Another Education</p>

<form data-toggle="validator" class="form-horizontal" role="form" method="post" action="<?php echo $_SERVER['REQUEST_URI']; ?>">
    <input name="client_no" type="hidden" value="<?php echo dec_enc('encrypt',$_SESSION['cu_unique_code']); ?>" />

    <div class="form-group">
        <label class="control-label col-sm-3" for="c_institute">Institution:</label>
        <div class="col-sm-9 col-lg-5">
            <input name="c_institute" type="text" class="form-control" id="c_institute" value="" required />
        </div>
    </div>
    <div class="form-group">
        <label class="control-label col-sm-3" for="c_degree">Degree:</label>
        <div class="col-sm-9 col-lg-5">
            <input name="c_degree" type="text" class="form-control" id="c_degree" value="" />
            <span class="help-block">E.g Primary, HND, BSc, BEd etc</span>
        </div>
    </div>
    <div class="form-group">
        <label class="control-label col-sm-3" for="c_grade">Grade:</label>
        <div class="col-sm-9 col-lg-5">
            <input name="c_grade" type="text" class="form-control" id="c_grade" value="" />
            <span class="help-block">E.g. First Class etc</span>
        </div>
    </div>
    <div class="form-group">
        <label class="control-label col-sm-3" for="c_course">Course of Study:</label>
        <div class="col-sm-9 col-lg-5">
            <input name="c_course" type="text" class="form-control" id="c_course" value="" />
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
        <div class="col-sm-offset-3 col-sm-9">
            <?php if($application_editable) { ?><input name="education_save" type="submit" class="btn btn-info" value="Save Education" />&nbsp;&nbsp;<?php } ?>
        </div>
    </div>
</form>

<script src='https://www.google.com/recaptcha/api.js'></script>
<script src="//cdnjs.cloudflare.com/ajax/libs/moment.js/2.9.0/moment-with-locales.js"></script>
<script src="//cdn.rawgit.com/Eonasdan/bootstrap-datetimepicker/e8bddc60e73c1ec2475f827be36e1957af72e2ea/src/js/bootstrap-datetimepicker.js"></script>