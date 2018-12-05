<?php
require_once("../init/initialize_admin.php");
if (!$session_admin->is_logged_in()) {
    redirect_to("login.php");
}

if (isset($_POST['process_application'])) {
    foreach ($_POST as $key => $value) {
        $_POST[$key] = $db_handle->sanitizePost(trim($value));
    }
    extract($_POST);

    if(empty($application_status) || empty($admin_comment)) {
        $message_error = "All fields are compulsory, please try again.";
    } else {
        $update_application = $obj_careers->update_user_application($_SESSION['admin_unique_code'], $application_no, $application_status, $admin_comment);
        if($update_application) {
            $message_success = "You have successfully made the change.";
        } else {
            $message_error = "Looks like something went wrong or you didn't make any change.";
        }
    }
}


$get_params = allowed_get_params(['id']);
$application_id_encrypted = $get_params['id'];
$application_id = decrypt_ssl(str_replace(" ", "+", $application_id_encrypted));
$application_id = preg_replace("/[^A-Za-z0-9 ]/", '', $application_id);

if(is_null($application_id_encrypted) || empty($application_id_encrypted)) {
    redirect_to("./"); // page cannot display anything without the id
} else {

    $application_id = $db_handle->sanitizePost($application_id);

    $query = "SELECT cua.cu_user_code, cua.created, cua.status, cj.title
          FROM career_user_application AS cua
          INNER JOIN career_jobs AS cj ON cua.job_code = cj.job_code WHERE career_user_application_id = $application_id LIMIT 1 ";
    $result = $db_handle->runQuery($query);
    $fetched_data = $db_handle->fetchAssoc($result);
    $applicant_reg = $fetched_data[0];

    $applicant_biodata = $obj_careers->get_applicant_biodata($applicant_reg['cu_user_code']);
    $applicant_education = $obj_careers->get_applicant_education($applicant_reg['cu_user_code']);
    $applicant_work_experience = $obj_careers->get_applicant_work_experience($applicant_reg['cu_user_code']);
    $applicant_skills = $obj_careers->get_applicant_skill($applicant_reg['cu_user_code']);
    $applicant_achievement = $obj_careers->get_applicant_achievement($applicant_reg['cu_user_code']);

    $all_states = $system_object->get_all_states();

    foreach($all_states as $key => $value) {
        if($value['state_id'] == $applicant_biodata['state_of_origin']) {
            $applicant_biodata['state_of_origin'] = $value['state'];
        }

        if($value['state_id'] == $applicant_biodata['state']) {
            $applicant_biodata['state'] = $value['state'];
        }
    }
}

$trans_remark = $obj_careers->get_admin_remark($application_id);


?>
<!DOCTYPE html>
<html lang="en">
    <head>
        <base target="_self">
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>Instaforex Nigeria | Admin - View Application Detail</title>
        <meta name="title" content="Instaforex Nigeria | Admin - View Application Detail" />
        <meta name="keywords" content="" />
        <meta name="description" content="" />
        <?php require_once 'layouts/head_meta.php'; ?>
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
                            <h4><strong>VIEW APPLICATION DETAIL</strong></h4>
                        </div>
                    </div>
                    
                    <div class="section-tint super-shadow">
                        <div class="row">
                            <div class="col-sm-8">
                                <?php require_once 'layouts/feedback_message.php'; ?>

                                <p>The details of the selected applicant is shown below.</p>

                                <h4>Bio-data</h4>
                                <table class="table table-responsive table-striped table-bordered table-hover">
                                    <thead>
                                        <tr><th></th><th></th></tr>
                                    </thead>
                                    <tbody>
                                        <tr><td>Job Title</td><td><?php echo $applicant_reg['title']; ?></td></tr>
                                        <tr><td>Full Name</td><td><?php echo $applicant_biodata['full_name']; ?></td></tr>
                                        <tr><td>Phone</td><td><?php echo $applicant_biodata['phone_number']; ?></td></tr>
                                        <tr><td>Email</td><td><?php echo $applicant_biodata['email_address']; ?></td></tr>
                                        <tr><td>Sex</td><td><?php echo biodata_sex_status($applicant_biodata['sex']); ?></td></tr>
                                        <tr><td>Marital Status</td><td><?php echo biodata_marriage_status($applicant_biodata['marital_status']); ?></td></tr>
                                        <tr><td>State of Origin</td><td><?php echo $applicant_biodata['state_of_origin']; ?></td></tr>
                                        <tr><td>DOB</td><td><?php echo $applicant_biodata['dob']; ?></td></tr>
                                        <tr><td>Address</td><td><?php echo $applicant_biodata['address']; ?></td></tr>
                                        <tr><td>State of Residence</td><td><?php echo $applicant_biodata['state']; ?></td></tr>
                                        <tr><td>Date Applied</td><td><?php echo datetime_to_text($applicant_reg['created']); ?></td></tr>
                                    </tbody>
                                </table>

                                <h4>Education</h4>
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
                                    <?php if(isset($applicant_education) && !empty($applicant_education)) { foreach ($applicant_education AS $row) { ?>
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

                                <h4>Work Experience</h4>
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
                                    <?php if(isset($applicant_work_experience) && !empty($applicant_work_experience)) { foreach ($applicant_work_experience AS $row) { ?>
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

                                <h4>Skills</h4>
                                <table class="table table-responsive table-striped table-bordered table-hover">
                                    <thead>
                                    <tr>
                                        <th>Skill Title</th>
                                        <th>Competency</th>
                                        <th>Description</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    <?php if(isset($applicant_skills) && !empty($applicant_skills)) { foreach ($applicant_skills AS $row) { ?>
                                        <tr>
                                            <td><?php echo $row['skill_title']; ?></td>
                                            <td><?php echo biodata_competency_status($row['competency']); ?></td>
                                            <td><?php echo $row['description']; ?></td>
                                        </tr>
                                    <?php } } else { echo "<tr><td colspan='3' class='text-danger'><em>No results to display</em></td></tr>"; } ?>
                                    </tbody>
                                </table>

                                <h4>Achievement</h4>
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
                                    <?php if(isset($applicant_achievement) && !empty($applicant_achievement)) { foreach ($applicant_achievement AS $row) { ?>
                                        <tr>
                                            <td><?php echo $row['achieve_title']; ?></td>
                                            <td><?php echo $row['description']; ?></td>
                                            <td><?php echo biodata_achievement_status($row['category']); ?></td>
                                            <td><?php echo datetime_to_text2($row['achieve_date']); ?></td>
                                        </tr>
                                    <?php } } else { echo "<tr><td colspan='4' class='text-danger'><em>No results to display</em></td></tr>"; } ?>
                                    </tbody>
                                </table>


                            </div>
                            <div class="col-sm-4">
                                <h5>Process Application</h5>
                                <p>Process the client's application, choose a new status, leave a comment, then submit.</p>
                                <p><strong>Current Status:</strong> <?php echo career_application_status($applicant_reg['status']); ?></p>

                                <form data-toggle="validator" class="form-horizontal" role="form" method="post" action="<?php echo $_SERVER['REQUEST_URI']; ?>">
                                    <input name="application_no" type="hidden" value="<?php echo $application_id; ?>" />

                                    <div class="form-group">
                                        <div class="col-sm-12">
                                            <label class="control-label" for="application_status">Application Status:</label>
                                            <select name="application_status" class="form-control" id="application_status" required>
                                                <option value="3">Review</option>
                                                <option value="4">No Review</option>
                                                <option value="5">Interview</option>
                                                <option value="6">Employ</option>
                                                <option value="7">Not Employed</option>
                                            </select>
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <div class="col-sm-12">
                                            <label class="control-label" for="admin_comment">Comment:</label>
                                            <textarea name="admin_comment" class="form-control" rows="3" id="admin_comment" required></textarea>
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <div class="col-sm-12"><input name="process_application" type="submit" class="btn btn-success" value="Process Application" /></div>
                                    </div>
                                </form>

                                <hr />
                                <div class="row">
                                    <div class="col-sm-12">
                                        <h5>Admin Remarks</h5>
                                        <div style="max-height: 550px; overflow: scroll;">
                                            <?php
                                            if(isset($trans_remark) && !empty($trans_remark)) {
                                                foreach ($trans_remark as $row) {
                                                    ?>
                                                    <div class="row">
                                                        <div class="col-sm-12">
                                                            <div class="transaction-remarks">
                                                                <span id="trans_remark_author"><?php echo $row['admin_full_name']; ?></span>
                                                                <span id="trans_remark"><?php echo $row['comment']; ?></span>
                                                                <span id="trans_remark_date"><?php echo datetime_to_text($row['created']); ?></span>
                                                            </div>
                                                        </div>
                                                    </div>
                                                <?php } } else { ?>
                                                <div class="row">
                                                    <div class="col-sm-12">
                                                        <div class="transaction-remarks">
                                                            <span class="text-danger"><em>There is no remark to display.</em></span>
                                                        </div>
                                                    </div>
                                                </div>
                                            <?php } ?>
                                        </div>
                                    </div>
                                </div>
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