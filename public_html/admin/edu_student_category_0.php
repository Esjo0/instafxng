<?php
require_once("../init/initialize_admin.php");
if (!$session_admin->is_logged_in()) {redirect_to("login.php");}

$this_cat = 'cat_0';

if(empty($_SESSION['cat'])) { $_SESSION['cat'] = '1';}

if(isset($_POST['edu_sale_track_reset'])){edu_sale_track_reset($this_cat);}

if(isset($_POST['cat'])){$_SESSION['cat'] = $_POST['cat'];}

if(isset($_POST['edu_sale_track'])){
    foreach ($_POST as $key => $value){$_POST[$key] = $db_handle->sanitizePost(trim($value));}
    extract($_POST);
    edu_sale_track($user_code, $category);
}

if(isset($_POST['search_text']) && strlen($_POST['search_text']) > 3) {
    $search_text = $_POST['search_text'];
    $query = "SELECT u.user_code, CONCAT(u.last_name, SPACE(1), u.first_name) AS full_name, u.email, ftc.created,
            u.phone, u.academy_signup, CONCAT(a.last_name, SPACE(1), a.first_name) AS account_officer_full_name, ftc.entry_point
            FROM free_training_campaign AS ftc
            INNER JOIN user AS u ON ftc.email = u.email
            INNER JOIN account_officers AS ao ON u.attendant = ao.account_officers_id
            INNER JOIN admin AS a ON ao.admin_code = a.admin_code
            WHERE u.academy_signup IS NULL AND (u.email LIKE '%$search_text%' OR u.first_name LIKE '%$search_text%' OR u.middle_name LIKE '%$search_text%' OR u.last_name LIKE '%$search_text%' OR u.phone LIKE '%$search_text%')
            ORDER BY ftc.created DESC, u.last_name ASC ";
} else {
    $query = "SELECT u.user_code, CONCAT(u.last_name, SPACE(1), u.first_name) AS full_name, u.email, ftc.created,
            u.phone, u.academy_signup, CONCAT(a.last_name, SPACE(1), a.first_name) AS account_officer_full_name, ftc.entry_point
            FROM free_training_campaign AS ftc
            INNER JOIN user AS u ON ftc.email = u.email
            INNER JOIN account_officers AS ao ON u.attendant = ao.account_officers_id
            INNER JOIN admin AS a ON ao.admin_code = a.admin_code
            WHERE u.academy_signup IS NULL
            ORDER BY ftc.created DESC, u.last_name ASC ";
}

$numrows = $db_handle->numRows($query);

// For search, make rows per page equal total rows found, meaning, no pagination for search results
if (isset($_POST['search_text'])) {$rowsperpage = $numrows;}
else {$rowsperpage = 20;}

$totalpages = ceil($numrows / $rowsperpage);
// get the current page or set a default
if (isset($_GET['pg']) && is_numeric($_GET['pg'])) {$currentpage = (int) $_GET['pg'];}
else {$currentpage = 1;}
if ($currentpage > $totalpages) { $currentpage = $totalpages; }
if ($currentpage < 1) { $currentpage = 1; }

$prespagelow = $currentpage * $rowsperpage - $rowsperpage + 1;
$prespagehigh = $currentpage * $rowsperpage;
if($prespagehigh > $numrows) { $prespagehigh = $numrows; }

$offset = ($currentpage - 1) * $rowsperpage;
$query .= ' LIMIT ' . $offset . ',' . $rowsperpage;
$result = $db_handle->runQuery($query);
$education_students = $db_handle->fetchAssoc($result);
$education_students = edu_sales_filter($education_students, $_SESSION['cat']);
?>
<!DOCTYPE html>
<html lang="en">
    <head>
        <base target="_self">
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>Instaforex Nigeria | Admin - All Category 0 Students</title>
        <meta name="title" content="Instaforex Nigeria | Admin - All Category 1 Students" />
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
                            <h4><strong>STUDENTS - Category 0</strong></h4>
                        </div>
                    </div>
                    
                    <div class="section-tint super-shadow">
                        <div class="row">
                            <div class="col-sm-12">
                                <div class="row">
                                    <div class="col-sm-12">
                                    <p class="pull-left">List of students that never logged in to the FX Academy portal. Covers Jan 2018 till date.</p>
                                    <p class="pull-right"><button data-target="#reset_contact_stat" data-toggle="modal" class="btn btn-xs btn-default" >Reset Contact List</button></p>
                                    </div>
                                </div>

                                <div id="reset_contact_stat" tabindex="-1" role="dialog" aria-hidden="true" class="modal fade">
                                    <form data-toggle="validator" class="form-horizontal" role="form" method="post" action="">
                                    <div class="modal-dialog">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <button type="button" data-dismiss="modal" aria-hidden="true" class="close">&times;</button>
                                                <h4 class="modal-title">Reset Contact Status</h4></div>
                                            <div class="modal-body">
                                                Are you sure you want to <b class="text-bold text-danger">reset</b> the contact status of this category?
                                            </div>
                                            <div class="modal-footer">
                                                <input name="edu_sale_track_reset" type="submit" class="btn btn-sm btn-danger" value="Reset">
                                                <button type="button" name="close" onClick="window.close();" data-dismiss="modal" class="btn btn-sm btn-danger">Close!</button>
                                            </div>
                                        </div>
                                    </div>
                                    </form>
                                </div>


                                <form data-toggle="validator" class="form-horizontal" role="form" method="post" action="">
                                    <center>
                                        <div class="form-group">
                                            <div class="input-group-sm">
                                                <button class="btn btn-sm <?php if($_SESSION['cat'] == '1'){echo 'btn-info';}else{echo 'btn-default';} ?>" name="cat" type="submit" value="1">All Clients</button>
                                                <button class="btn btn-sm <?php if($_SESSION['cat'] == '2'){echo 'btn-info';}else{echo 'btn-default';} ?>" name="cat" type="submit" value="2">All Clients Contacted</button>
                                                <button class="btn btn-sm <?php if($_SESSION['cat'] == '3'){echo 'btn-info';}else{echo 'btn-default';} ?>" name="cat" type="submit" value="3">All Clients Yet To Be Contacted</button>
                                            </div>
                                        </div>
                                    </center>

                                    <center>
                                        <div class="col-sm-3"></div>
                                        <div class="col-sm-6">
                                            <div class="search-section">
                                                <div class="row">
                                                    <div class="col-xs-12">
                                                        <form data-toggle="validator" class="form-horizontal" role="form" method="post" action="">
                                                            <div class="input-group">
                                                                <input type="hidden" name="search_param" value="all" id="search_param">
                                                                <input minlength="3" type="text" class="form-control" name="search_text" placeholder="Search term...">
                                                                <span class="input-group-btn">
                                                                    <button class="btn btn-default" type="submit"><span class="glyphicon glyphicon-search"></span></button>
                                                                </span>
                                                            </div>
                                                        </form>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-sm-3"></div>
                                    </center>
                                </form>

                                <?php if(isset($education_students) && !empty($education_students)) { include 'layouts/pagination_links.php'; } ?>

                                <table class="table table-responsive table-striped table-bordered table-hover">
                                    <thead>
                                    <tr>
                                        <th>Client Name</th>
                                        <th>Client Phone</th>
                                        <th>Email Address</th>
                                        <th>Reg Date</th>
                                        <th>Officer</th>
                                        <th>Action</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    <?php if(isset($education_students) && !empty($education_students)) { foreach ($education_students as $row) { ?>
                                        <tr>
                                            <td><?php echo $row['full_name']; ?></td>
                                            <td><?php echo $row['phone']; ?>
                                                <?php $entry_point =  training_entry_point($row['entry_point']); echo " <span class=\"badge badge-light\"> $entry_point</span>"; ?>
                                            </td>
                                            <td><?php echo $row['email']; ?></td>
                                            <td><?php echo datetime_to_text($row['created']); ?></td>
                                            <td><?php echo $row['account_officer_full_name']; ?></td>
                                            <td nowrap="nowrap">
                                                <a title="Comment" class="btn btn-xs btn-success" href="sales_contact_view.php?x=<?php echo dec_enc('encrypt', $row['user_code']); ?>&r=<?php echo 'edu_student_category_0'; ?>&c=<?php echo encrypt_ssl('STUDENT CATEGORY 0'); ?>&pg=<?php echo $currentpage; ?>"><i class="glyphicon glyphicon-comment icon-white"></i> </a>
                                                <a target="_blank" title="View" class="btn btn-xs btn-info" href="client_detail.php?id=<?php echo dec_enc('encrypt', $row['user_code']); ?>"><i class="glyphicon glyphicon-eye-open icon-white"></i> </a>
                                                <br/><br/>
                                                <?php UI_sale_status($row['user_code'], $this_cat); ?>
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

                                <?php if(isset($education_students) && !empty($education_students)) { include 'layouts/pagination_links.php'; } ?>

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