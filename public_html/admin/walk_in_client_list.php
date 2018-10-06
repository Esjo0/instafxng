<?php
require_once("../init/initialize_admin.php");
if (!$session_admin->is_logged_in()) {
    redirect_to("login.php");
}

$query = "SELECT CONCAT(a.last_name, SPACE(1), a.first_name) AS admin_name, wic.full_name, wic.email_address, wic.phone, wic.time_in, wic.time_out 
      FROM walk_in_client AS wic 
      INNER JOIN admin AS a ON wic.admin_code = a.admin_code
      ORDER BY id DESC ";
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
$walkin_clients = $db_handle->fetchAssoc($result);

?>
<!DOCTYPE html>
<html lang="en">
    <head>
        <base target="_self">
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>Instaforex Nigeria | Walk In Clients</title>
        <meta name="title" content="Instaforex Nigeria | Walk In Clients" />
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
                            <h4><strong>LIST OF WALK-IN CLIENTS</strong></h4>
                        </div>
                    </div>
                    
                    <div class="section-tint super-shadow">
                        <div class="row">
                            <div class="col-sm-12">
                                <?php require_once 'layouts/feedback_message.php'; ?>
                                <p class="text-left"><a href="walk_in_client_add.php" class="btn btn-default" title="Add Walk-in Client">Add Walk-in Client <i class="fa fa-arrow-circle-left"></i></a></p>
                                <p>Below is a list of walk-in clients.</p>

                                <table class="table table-responsive table-striped table-bordered table-hover">
                                    <thead>
                                    <tr>
                                        <th>Author</th>
                                        <th>Full Name</th>
                                        <th>Email</th>
                                        <th>Phone</th>
                                        <th>Time in</th>
                                        <th>Time out</th>
                                        <th></th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    <?php if(isset($walkin_clients) && !empty($walkin_clients)) { foreach ($walkin_clients as $row) { ?>
                                        <tr>
                                            <td><?php echo $row['admin_name']; ?></td>
                                            <td><?php echo $row['full_name']; ?></td>
                                            <td><?php echo $row['email_address']; ?></td>
                                            <td><?php echo $row['phone']; ?></td>
                                            <td><?php echo $row['time_in']; ?></td>
                                            <td><?php echo $row['time_out']; ?></td>
                                            <td>
                                                <a title="View" class="btn btn-info" href="#"><i class="glyphicon glyphicon-eye-open icon-white"></i> </a>
                                            </td>
                                        </tr>
                                    <?php } } else { echo "<tr><td colspan='4' class='text-danger'><em>No results to display</em></td></tr>"; } ?>
                                    </tbody>
                                </table>

                                <?php if(isset($walkin_clients) && !empty($walkin_clients)) { ?>
                                    <div class="tool-footer text-right">
                                        <p class="pull-left">Showing <?php echo $prespagelow . " to " . $prespagehigh . " of " . $numrows; ?> entries</p>
                                    </div>
                                <?php } ?>
                                
                            </div>
                        </div>
                        <?php if(isset($walkin_clients) && !empty($walkin_clients)) { require_once 'layouts/pagination_links.php'; } ?>
                        
                    </div>

                    <!-- Unique Page Content Ends Here
                    ================================================== -->
                    
                </div>
                
            </div>
        </div>
        <?php require_once 'layouts/footer.php'; ?>
    </body>
</html>