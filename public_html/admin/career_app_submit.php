<?php
require_once("../init/initialize_admin.php");
if (!$session_admin->is_logged_in()) {
    redirect_to("login.php");
}

if(empty($_SESSION['job_code_filter']) || !isset($_SESSION['job_code_filter'])) {
    $_SESSION['job_code_filter'] = 'all';
}

if (isset($_POST['apply_filter'])) {
    foreach ($_POST as $key => $value) {
        $_POST[$key] = $db_handle->sanitizePost(trim($value));
    }
    extract($_POST);
    $_SESSION['job_code_filter'] = $job_category;
}

$query = "SELECT CONCAT(last_name, SPACE(1), first_name) AS full_name, cua.career_user_application_id, cj.title, cua.created, cua.status
    FROM career_user_application AS cua
    INNER JOIN career_user_biodata AS cub ON cua.cu_user_code = cub.cu_user_code
    INNER JOIN career_jobs AS cj ON cua.job_code = cj.job_code WHERE cua.status = '2' ";

if(isset($_SESSION['job_code_filter']) && $_SESSION['job_code_filter'] <> 'all') {
    $job_code_filter = $_SESSION['job_code_filter'];
    $query .= "AND cua.job_code = '$job_code_filter' ORDER BY created DESC ";
} else {
    $query .= "ORDER BY created DESC ";
}

$numrows = $db_handle->numRows($query);

$rowsperpage = 20;

$totalpages = ceil($numrows / $rowsperpage);
// get the current page or set a default
if (isset($_GET['pg']) && is_numeric($_GET['pg'])) {
   $currentpage = (int) $_GET['pg'];
} else {
   $currentpage = 1;
}
if ($currentpage > $totalpages) { $currentpage = $totalpages; }
if ($currentpage < 1) { $currentpage = 1; }

$prespagelow = $currentpage * $rowsperpage - $rowsperpage + 1;
$prespagehigh = $currentpage * $rowsperpage;
if($prespagehigh > $numrows) { $prespagehigh = $numrows; }

$offset = ($currentpage - 1) * $rowsperpage;
$query .= 'LIMIT ' . $offset . ',' . $rowsperpage;
$result = $db_handle->runQuery($query);
$all_applications = $db_handle->fetchAssoc($result);

?>
<!DOCTYPE html>
<html lang="en">
    <head>
        <base target="_self">
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>Instaforex Nigeria | Admin - Career Submitted Applications</title>
        <meta name="title" content="Instaforex Nigeria | Admin - Career All Applications" />
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
                            <h4><strong>CAREERS - SUBMITTED APPLICATION</strong></h4>
                        </div>
                    </div>
                    
                    <div class="section-tint super-shadow">
                        <div class="row">
                            <div class="col-sm-12">
                                <p>All submitted applications. Apply filter using the form below to choose a specific
                                    job category.</p>

                                <?php $all_jobs = $obj_careers->get_all_jobs(); ?>
                                <form data-toggle="validator" class="form-inline" role="form" method="post" action="">
                                    <div class="form-group">
                                        <label for="job_category">Choose Filter:</label>
                                        <select name="job_category" class="form-control" id="job_category" required>
                                            <option value="all"> All Jobs</option>
                                            <?php if(isset($all_jobs) && !empty($all_jobs)) { foreach ($all_jobs as $row) { ?>
                                                <option value="<?php echo $row['job_code']; ?>" <?php if(isset($_SESSION['job_code_filter']) && $row['job_code'] == $_SESSION['job_code_filter']) { echo "selected='selected'"; } ?>><?php echo $row['title']; ?></option>
                                            <?php } } ?>
                                        </select>
                                    </div>
                                    <input name="apply_filter" type="submit" class="btn btn-primary" value="Apply Filter">
                                </form>
                                <br />
                                <p>Results Found: <?php echo number_format($numrows); ?></p>

                            </div>
                        </div>

                        <div class="row">
                            <div class="col-sm-12">
                                <table class="table table-responsive table-striped table-bordered table-hover">
                                    <thead>
                                        <tr>
                                            <th>Name</th>
                                            <th>Job Title</th>
                                            <th>Created</th>
                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php if(isset($all_applications) && !empty($all_applications)) { foreach ($all_applications as $row) { ?>
                                        <tr>
                                            <td><?php echo $row['full_name']; ?></td>
                                            <td><?php echo $row['title']; ?></td>
                                            <td><?php echo datetime_to_text($row['created']); ?></td>
                                            <td>
                                                <a target="_blank" title="View" class="btn btn-default" href="career_view_application.php?id=<?php echo encrypt($row['career_user_application_id']); ?>"><i class="fa fa-eye icon-white"></i> </a>
                                            </td>
                                        </tr>
                                        <?php } } else { echo "<tr><td colspan='4' class='text-danger'><em>No results to display</em></td></tr>"; } ?>
                                    </tbody>
                                </table>
                                
                                <?php if(isset($all_applications) && !empty($all_applications)) { ?>
                                <div class="tool-footer text-right">
                                    <p class="pull-left">Showing <?php echo $prespagelow . " to " . $prespagehigh . " of " . $numrows; ?> entries</p>
                                </div>
                                <?php } ?>
                            </div>
                        </div>
                        <?php if(isset($all_applications) && !empty($all_applications)) { require_once 'layouts/pagination_links.php'; } ?>
                    </div>

                    <!-- Unique Page Content Ends Here
                    ================================================== -->
                    
                </div>
                
            </div>
        </div>
        <?php require_once 'layouts/footer.php'; ?>
    </body>
</html>