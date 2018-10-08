<?php
require_once("../init/initialize_admin.php");
if (!$session_admin->is_logged_in()) {
    redirect_to("login.php");
}

$office_locations = $system_object->get_office_locations();

$query = "SELECT CONCAT(a.last_name, SPACE(1), a.first_name) AS admin_name, wic.id, wic.full_name, wic.email_address, wic.phone, wic.time_in, wic.time_out, wic.created,
      wic.trans_type, wic.client_feedback, wic.admin_comment, wic.issues_record, wic.office_location
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
                                        <th>Contact</th>
                                        <th>Time in</th>
                                        <th>Time out</th>
                                        <th>Created</th>
                                        <th></th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    <?php if(isset($walkin_clients) && !empty($walkin_clients)) { foreach ($walkin_clients as $row) { ?>
                                        <tr>
                                            <td><?php echo $row['admin_name']; ?></td>
                                            <td><?php echo $row['full_name']; ?></td>
                                            <td><?php echo $row['email_address']; ?><br /><?php echo $row['phone']; ?></td>
                                            <td><?php echo $row['time_in']; ?></td>
                                            <td><?php echo $row['time_out']; ?></td>
                                            <td><?php echo datetime_to_text($row['created']); ?></td>
                                            <td>
                                                <button type="button" data-toggle="modal" data-target="#view-detail-<?php echo $row['id']; ?>" class="btn btn-info" title="View"><i class="glyphicon glyphicon-eye-open icon-white"></i></button>

                                                <div id="view-detail-<?php echo $row['id']; ?>" tabindex="-1" role="dialog" aria-hidden="true" class="modal fade">
                                                    <div class="modal-dialog">
                                                        <div class="modal-content">
                                                            <div class="modal-header">
                                                                <button type="button" data-dismiss="modal" aria-hidden="true" class="close">&times;</button>
                                                                <h4 class="modal-title">Walk-in Client Log Detail</h4></div>
                                                            <div class="modal-body">
                                                                <table class="table table-responsive table-striped table-bordered table-hover">
                                                                    <thead>
                                                                        <tr><th> </th><th> </th></tr>
                                                                    </thead>
                                                                    <tbody>
                                                                        <tr><td>Office Location</td><td><?php echo office_location_walkin_client($row['office_location']); ?></td></tr>
                                                                        <tr><td>Client Name</td><td><?php echo $row['full_name']; ?></td></tr>
                                                                        <tr><td>Email</td><td><?php echo $row['email_address']; ?></td></tr>
                                                                        <tr><td>Phone Number</td><td><?php echo $row['phone']; ?></td></tr>
                                                                        <tr><td>Time in</td><td><?php echo $row['time_in']; ?></td></tr>
                                                                        <tr><td>Time out</td><td><?php echo $row['time_out']; ?></td></tr>
                                                                        <tr><td>Transaction</td><td><?php echo $row['trans_type']; ?></td></tr>
                                                                        <tr><td>Client Feedback</td><td><?php echo $row['client_feedback']; ?></td></tr>
                                                                        <tr><td>Issues</td><td><?php echo $row['issues_record']; ?></td></tr>
                                                                        <tr><td>Admin Comment</td><td><?php echo $row['admin_comment']; ?></td></tr>
                                                                        <tr><td>Admin</td><td><?php echo $row['admin_name']; ?></td></tr>
                                                                        <tr><td>Created</td><td><?php echo datetime_to_text($row['created']); ?></td></tr>
                                                                    </tbody>
                                                                </table>
                                                            </div>
                                                            <div class="modal-footer">
                                                                <button type="submit" name="close" data-dismiss="modal" class="btn btn-danger">Close!</button>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>

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