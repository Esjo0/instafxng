<?php
require_once("../init/initialize_admin.php");

if (!$session_admin->is_logged_in()) {
    redirect_to("login.php");
}

if(isset($_POST['search_text']) && strlen($_POST['search_text']) > 3) {
    $search_text = $_POST['search_text'];
    $query = "SELECT u.user_code, CONCAT(u.last_name, SPACE(1), u.first_name) AS full_name, u.email, u.phone,
          u.academy_signup, CONCAT(a.last_name, SPACE(1), a.first_name) AS account_officer_full_name
          FROM user_edu_exercise_log AS ueel
          INNER JOIN user AS u ON ueel.user_code = u.user_code
          INNER JOIN account_officers AS ao ON u.attendant = ao.account_officers_id
          INNER JOIN admin AS a ON ao.admin_code = a.admin_code
          LEFT JOIN user_edu_deposits AS ued ON ued.user_code = u.user_code
          WHERE ued.status = '3' AND (u.email LIKE '%$search_text%' OR u.first_name LIKE '%$search_text%' OR u.middle_name LIKE '%$search_text%' OR u.last_name LIKE '%$search_text%' OR u.phone LIKE '%$search_text%')
          GROUP BY u.user_code ORDER BY u.academy_signup DESC ";
} else {
    $query = "SELECT u.user_code, CONCAT(u.last_name, SPACE(1), u.first_name) AS full_name, u.email, u.phone,
          u.academy_signup, CONCAT(a.last_name, SPACE(1), a.first_name) AS account_officer_full_name
          FROM user_edu_exercise_log AS ueel
          INNER JOIN user AS u ON ueel.user_code = u.user_code
          INNER JOIN account_officers AS ao ON u.attendant = ao.account_officers_id
          INNER JOIN admin AS a ON ao.admin_code = a.admin_code
          LEFT JOIN user_edu_deposits AS ued ON ued.user_code = u.user_code
          WHERE ued.status = '3'
          GROUP BY u.user_code ORDER BY u.academy_signup DESC ";
}

$numrows = $db_handle->numRows($query);

// For search, make rows per page equal total rows found, meaning, no pagination for search results
if (isset($_POST['search_text'])) {
    $rowsperpage = $numrows;
} else {
    $rowsperpage = 20;
}

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
$education_students = $db_handle->fetchAssoc($result);

?>
<!DOCTYPE html>
<html lang="en">
    <head>
        <base target="_self">
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>Instaforex Nigeria | Admin - All Category 4 Students</title>
        <meta name="title" content="Instaforex Nigeria | Admin - All Category 4 Students" />
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

                    <div class="search-section">
                        <div class="row">
                            <div class="col-xs-12">
                                <form data-toggle="validator" class="form-horizontal" role="form" method="post" action="">
                                    <div class="input-group">
                                        <input type="hidden" name="search_param" value="all" id="search_param">
                                        <input type="text" class="form-control" name="search_text" placeholder="Search term..." required>
                                        <span class="input-group-btn">
                                            <button class="btn btn-default" type="submit"><span class="glyphicon glyphicon-search"></span></button>
                                        </span>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-sm-12 text-danger">
                            <h4><strong>STUDENTS - Category 4</strong></h4>
                        </div>
                    </div>

                    <div class="section-tint super-shadow">
                        <div class="row">
                            <div class="col-sm-12">

                                <p>List of students that have made payments and taking course 2.</p>

                                <table class="table table-responsive table-striped table-bordered table-hover">
                                    <thead>
                                    <tr>
                                        <th>Client Name</th>
                                        <th>Client Phone</th>
                                        <th>First Login</th>
                                        <th>Officer</th>
                                        <th>Action</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    <?php if(isset($education_students) && !empty($education_students)) { foreach ($education_students as $row) { ?>
                                        <tr>
                                            <td><?php echo $row['full_name']; ?></td>
                                            <td><?php echo $row['phone']; ?></td>
                                            <td><?php echo date_to_text($row['academy_signup']); ?></td>
                                            <td><?php echo $row['account_officer_full_name']; ?></td>
                                            <td nowrap="nowrap">
                                                <a title="Comment" class="btn btn-success" href="sales_contact_view.php?x=<?php echo encrypt($row['user_code']); ?>&r=<?php echo 'edu_student_category_4'; ?>&c=<?php echo encrypt('STUDENT CATEGORY 4'); ?>&pg=<?php echo $currentpage; ?>"><i class="glyphicon glyphicon-comment icon-white"></i> </a>
                                                <a target="_blank" title="View" class="btn btn-info" href="client_detail.php?id=<?php echo encrypt($row['user_code']); ?>"><i class="glyphicon glyphicon-eye-open icon-white"></i> </a>
                                            </td>
                                        </tr>
                                    <?php } } else { echo "<tr><td colspan='5' class='text-danger'><em>No results to display</em></td></tr>"; } ?>
                                    </tbody>
                                </table>

                                <?php if(isset($education_students) && !empty($education_students)) { ?>
                                    <div class="tool-footer text-right">
                                        <p class="pull-left">Showing <?php echo $prespagelow . " to " . $prespagehigh . " of " . $numrows; ?> entries</p>
                                    </div>
                                <?php } ?>

                                <?php if(isset($education_students) && !empty($education_students)) { require_once 'layouts/pagination_links.php'; } ?>

                                <div id="student_details" tabindex="-1" role="dialog" aria-hidden="true" class="modal fade">
                                    <div class="modal-dialog">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <button type="button" data-dismiss="modal" aria-hidden="true"
                                                        class="close">&times;</button>
                                                <h4 class="modal-title">Student Details</h4></div>
                                            <div class="modal-body">
                                            </div>
                                            <div class="modal-footer"></div>
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