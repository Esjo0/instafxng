<?php
require_once("../init/initialize_admin.php");

if (!$session_admin->is_logged_in()) {
    redirect_to("login.php");
}

if(isset($_POST['search_text']) && strlen($_POST['search_text']) > 3) {
    $search_text = $_POST['search_text'];
    $query = "SELECT uefp.reference, uefp.created, uefp.created, u.user_code, CONCAT(u.last_name, SPACE(1), u.first_name) AS full_name, u.email, u.phone,
            ec.title
            FROM user_edu_fee_payment AS uefp
            INNER JOIN user AS u ON uefp.user_code = u.user_code
            INNER JOIN edu_course AS ec ON uefp.course_id = ec.edu_course_id
            ORDER BY uefp.created DESC ";
} else {
    $query = "SELECT uefp.reference, uefp.created, uefp.created, u.user_code, CONCAT(u.last_name, SPACE(1), u.first_name) AS full_name, u.email, u.phone,
            ec.title
            FROM user_edu_fee_payment AS uefp
            INNER JOIN user AS u ON uefp.user_code = u.user_code
            INNER JOIN edu_course AS ec ON uefp.course_id = ec.edu_course_id
            ORDER BY uefp.created DESC ";
}
$numrows = $db_handle->numRows($query);

// For search, make rows per page equal total rows found, meaning, no pagination
// for search results
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
$education_payments = $db_handle->fetchAssoc($result);

?>
<!DOCTYPE html>
<html lang="en">
    <head>
        <base target="_self">
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>Instaforex Nigeria | Admin - Student Payments</title>
        <meta name="title" content="Instaforex Nigeria | Admin - Student Payments" />
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
                            <h4><strong>VIEW COMPLETED STUDENT PAYMENTS</strong></h4>
                        </div>
                    </div>
                    
                    <div class="section-tint super-shadow">
                        <div class="row">
                            <div class="col-sm-12">
                                <?php require_once 'layouts/feedback_message.php'; ?>
                                <p>Listed below is the payments that have been completed by the Forex Training Students</p>

                                <p><strong>Total Received (&#8358;): </strong></p>

                                <table class="table table-responsive table-striped table-bordered table-hover">
                                    <thead>
                                    <tr>
                                        <th>Reference ID</th>
                                        <th>Client Name</th>
                                        <th>Client Phone</th>
                                        <th>Course</th>
                                        <th>Created</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    <?php if(isset($education_payments) && !empty($education_payments)) { foreach ($education_payments as $row) { ?>
                                        <tr>
                                            <td><?php echo $row['reference']; ?></td>
                                            <td><?php echo $row['full_name']; ?></td>
                                            <td><?php echo $row['phone']; ?></td>
                                            <td><?php echo $row['title']; ?></td>
                                            <td><?php echo datetime_to_text($row['created']); ?></td>
                                        </tr>
                                    <?php } } else { echo "<tr><td colspan='5' class='text-danger'><em>No results to display</em></td></tr>"; } ?>
                                    </tbody>
                                </table>

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