<?php
require_once("../init/initialize_admin.php");
if (!$session_admin->is_logged_in()) {
    redirect_to("login.php");
}

$query = "SELECT * FROM edu_course ORDER BY course_order ASC ";
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
$education_courses = $db_handle->fetchAssoc($result);

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <base target="_self">
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Instaforex Nigeria | Admin - All Courses</title>
    <meta name="title" content="Instaforex Nigeria | Admin - All Courses" />
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
                    <h4><strong>ALL COURSES</strong></h4>
                </div>
            </div>

            <div class="section-tint super-shadow">
                <div class="row">
                    <div class="col-sm-12">
                        <?php require_once 'layouts/feedback_message.php'; ?>
                        <p class="text-right"><a href="edu_course_new.php" class="btn btn-default" title="Create New Course">Create New Course <i class="fa fa-arrow-circle-right"></i></a></p>
                        <p>Below is a table of course, click the view button for more details and also create lessons for the
                            courses.</p>

                        <table class="table table-responsive table-striped table-bordered table-hover">
                            <thead>
                            <tr>
                                <th>Course Code</th>
                                <th>Title</th>
                                <th>Description</th>
                                <th>Order</th>
                                <th>Lesson Count</th>
                                <th>Cost</th>
                                <th>Status</th>
                                <th>Actions</th>
                            </tr>
                            </thead>
                            <tbody>
                            <?php if(isset($education_courses) && !empty($education_courses)) { foreach ($education_courses as $row) { ?>
                                <tr>
                                    <td><?php echo $row['course_code']; ?></td>
                                    <td><?php echo $row['title']; ?></td>
                                    <td><?php echo substr($row['description'], 0, 160) . ' ...'; ?></td>
                                    <td><?php echo $row['course_order']; ?></td>
                                    <td><?php echo $db_handle->numRows("SELECT edu_lesson_id FROM edu_lesson WHERE course_id = {$row['edu_course_id']}"); ?></td>
                                    <td><?php echo $row['course_cost']; ?></td>
                                    <td><?php echo status_edu_course($row['status']); ?></td>
                                    <td>
                                        <a title="View" class="btn btn-info" href="edu_course_view.php?id=<?php echo encrypt($row['edu_course_id']); ?>"><i class="glyphicon glyphicon-eye-open icon-white"></i> </a>
                                    </td>
                                </tr>
                            <?php } } else { echo "<tr><td colspan='8' class='text-danger'><em>No results to display</em></td></tr>"; } ?>
                            </tbody>
                        </table>

                        <?php if(isset($education_courses) && !empty($education_courses)) { ?>
                            <div class="tool-footer text-right">
                                <p class="pull-left">Showing <?php echo $prespagelow . " to " . $prespagehigh . " of " . $numrows; ?> entries</p>
                            </div>
                        <?php } ?>
                    </div>
                </div>
                <?php if(isset($education_courses) && !empty($education_courses)) { require_once 'layouts/pagination_links.php'; } ?>

            </div>

            <!-- Unique Page Content Ends Here
            ================================================== -->

        </div>

    </div>
</div>
<?php require_once 'layouts/footer.php'; ?>
</body>
</html>