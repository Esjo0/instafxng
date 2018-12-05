<?php
$my_achievements = $obj_careers->get_applicant_achievement($_SESSION['cu_unique_code']);
$all_achievement = array(
    '1' => 'Certification',
    '2' => 'Course',
    '3' => 'Honour/Award',
    '4' => 'Project'
)
?>

<p><strong>Achievements</strong></p>
<p>Use the form below to save your achievements.</p>

<table class="table table-responsive table-striped table-bordered table-hover">
    <thead>
    <tr>
        <th>Title</th>
        <th>Description</th>
        <th>Category</th>
        <th>Date</th>
    </tr>
    </thead>
    <tbody>
    <?php if(isset($my_achievements) && !empty($my_achievements)) { foreach ($my_achievements AS $row) { ?>
        <tr>
            <td><?php echo $row['achieve_title']; ?></td>
            <td><?php echo $row['description']; ?></td>
            <td><?php echo $all_achievement[$row['category']]; ?></td>
            <td><?php echo datetime_to_text2($row['achieve_date']); ?></td>
        </tr>
    <?php } } else { echo "<tr><td colspan='4' class='text-danger'><em>No results to display</em></td></tr>"; } ?>
    </tbody>
</table>
<hr />

<p>Add Another Achievement</p>

<form data-toggle="validator" class="form-horizontal" role="form" method="post" action="<?php echo $_SERVER['REQUEST_URI']; ?>">
    <input name="client_no" type="hidden" value="<?php echo encrypt_ssl($_SESSION['cu_unique_code']); ?>" />

    <div class="form-group">
        <label class="control-label col-sm-3" for="c_title">Title:</label>
        <div class="col-sm-9 col-lg-5">
            <input name="c_title" type="text" class="form-control" id="c_title" value="" required />
        </div>
    </div>

    <div class="form-group">
        <label class="control-label col-sm-3" for="c_description">Description:</label>
        <div class="col-sm-9 col-lg-7">
            <textarea name="c_description" class="form-control" cols="65" rows="3" id="c_description" required></textarea>
        </div>
    </div>

    <div class="form-group">
        <label class="control-label col-sm-3" for="c_category">Category:</label>
        <div class="col-sm-9 col-lg-5">
            <select name="c_category" class="form-control" id="c_category" required>
                <option value="" selected>Select Category</option>
                <?php foreach($all_achievement as $key => $value) { ?>
                    <option value="<?php echo $key; ?>"><?php echo $value; ?></option>
                <?php } ?>
            </select>
        </div>
    </div>

    <div class="form-group">
        <label class="control-label col-sm-3" for="c_date">Date Achieved:</label>
        <div class="col-sm-9 col-lg-5">
            <div class='input-group date' id='c_date1'>
                <input name="c_date" type="text" class="form-control" id='c_date' value="" required />
                <span class="input-group-addon"><span class="glyphicon glyphicon-calendar"></span></span>
            </div>
            <span class="help-block">Format: (YYYY-MM-DD) e.g. 2016-12-25</span>
        </div>
        <script type="text/javascript">
            $(function () {
                $('#c_date1').datetimepicker({
                    format: 'YYYY-MM-DD'
                });
                $('#c_date').datetimepicker({
                    format: 'YYYY-MM-DD'
                });
            });
        </script>
    </div>

    <div class="form-group">
        <div class="col-sm-offset-3 col-sm-9">
            <?php if($application_editable) { ?><input name="achievement_save" type="submit" class="btn btn-info" value="Save Skill" /><?php } ?>
        </div>
    </div>
</form>

<script src='https://www.google.com/recaptcha/api.js'></script>
<script src="//cdnjs.cloudflare.com/ajax/libs/moment.js/2.9.0/moment-with-locales.js"></script>
<script src="//cdn.rawgit.com/Eonasdan/bootstrap-datetimepicker/e8bddc60e73c1ec2475f827be36e1957af72e2ea/src/js/bootstrap-datetimepicker.js"></script>