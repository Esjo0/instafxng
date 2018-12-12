<?php
$c_full_name = $_SESSION['cu_first_name'] . ' ' . $_SESSION['cu_last_name'];

?>

<p><strong>Welcome, <?php if(isset($c_full_name)) { echo $c_full_name; } ?></strong></p>
<p>Below is the details of the job you have applied for, use the links above to save
    your bio-data, education, achievements, skills and work experience.</p>

<p class="text-danger"><strong>Only click the submit button when you are done with uploading all your information.</strong></p>

<table class="table table-responsive table-striped table-bordered table-hover">
    <thead>
    <tr>
        <th>Job Title</th>
        <th>Date</th>
        <th>Status</th>
        <th>Action</th>
    </tr>
    </thead>
    <tbody>
    <?php if(isset($my_application) && !empty($my_application)) { ?>
        <tr>
            <td><?php echo $my_application['title']; ?></td>
            <td><?php echo datetime_to_text($my_application['created']); ?></td>
            <td><?php echo career_application_status($my_application['status']); ?></td>
            <td>
                <?php if($application_editable) { ?>
                <form role="form" method="post" action="<?php echo $_SERVER['REQUEST_URI']; ?>">
                    <input name="application_no" type="hidden" value="<?php echo dec_enc('encrypt',$my_application['career_user_application_id']); ?>" />

                    <button type="button" data-target="#confirm-final-submit" data-toggle="modal" class="btn btn-success">Submit</button>

                    <div id="confirm-final-submit" tabindex="-1" role="dialog" aria-hidden="true" class="modal fade">
                        <div class="modal-dialog">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <button type="button" data-dismiss="modal" aria-hidden="true"
                                            class="close">&times;</button>
                                    <h4 class="modal-title">Final Application Submission</h4></div>
                                <div class="modal-body">Are you sure you want to submit now? Ensure you have uploaded your biodata,
                                education, work experience, skills, achievements. After submission, you cannot make edits again.</div>
                                <div class="modal-footer">
                                    <input name="final_submit_application" type="submit" class="btn btn-success" value="Submit" />
                                    <button type="submit" name="close" onClick="window.close();" data-dismiss="modal" class="btn btn-danger">Close!</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </form>
                <?php } ?>
            </td>
        </tr>
    <?php } else { echo "<tr><td colspan='4' class='text-danger'><em>No results to display</em></td></tr>"; } ?>
    </tbody>
</table>