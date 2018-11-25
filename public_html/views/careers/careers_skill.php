<?php
$my_skill = $obj_careers->get_applicant_skill($_SESSION['cu_unique_code']);
$all_competency = array(
    '1' => 'Beginner',
    '2' => 'Advanced',
    '3' => 'Professional',
    '4' => 'Master',
    '5' => 'Certified'
)
?>

<p><strong>Skills</strong></p>
<p>Use the form below to save your skills.</p>

<table class="table table-responsive table-striped table-bordered table-hover">
    <thead>
    <tr>
        <th>Skill Title</th>
        <th>Competency</th>
        <th>Description</th>
    </tr>
    </thead>
    <tbody>
    <?php if(isset($my_skill) && !empty($my_skill)) { foreach ($my_skill AS $row) { ?>
        <tr>
            <td><?php echo $row['skill_title']; ?></td>
            <td><?php echo $all_competency[$row['competency']]; ?></td>
            <td><?php echo $row['description']; ?></td>
        </tr>
    <?php } } else { echo "<tr><td colspan='3' class='text-danger'><em>No results to display</em></td></tr>"; } ?>
    </tbody>
</table>
<hr />

<p>Add Another Skill</p>

<form data-toggle="validator" class="form-horizontal" role="form" method="post" action="<?php echo $_SERVER['REQUEST_URI']; ?>">
    <input name="client_no" type="hidden" value="<?php echo encrypt_ssl($_SESSION['cu_unique_code']); ?>" />

    <div class="form-group">
        <label class="control-label col-sm-3" for="c_skill_title">Skill Title:</label>
        <div class="col-sm-9 col-lg-5">
            <input name="c_skill_title" type="text" class="form-control" id="c_skill_title" value="" required />
        </div>
    </div>

    <div class="form-group">
        <label class="control-label col-sm-3" for="c_competency">Competency:</label>
        <div class="col-sm-9 col-lg-5">
            <select name="c_competency" class="form-control" id="c_competency" required>
                <option value="" selected>Select Skill Competence</option>
                <?php foreach($all_competency as $key => $value) { ?>
                    <option value="<?php echo $key; ?>"><?php echo $value; ?></option>
                <?php } ?>
            </select>
        </div>
    </div>

    <div class="form-group">
        <label class="control-label col-sm-3" for="c_description">Description:</label>
        <div class="col-sm-9 col-lg-7">
            <textarea name="c_description" class="form-control" cols="65" rows="3" id="c_description" required></textarea>
        </div>
    </div>

    <div class="form-group">
        <div class="col-sm-offset-3 col-sm-9">
            <?php if($application_editable) { ?><input name="skill_save" type="submit" class="btn btn-info" value="Save Skill" /><?php } ?>
        </div>
    </div>
</form>