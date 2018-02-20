<?php
require_once("../init/initialize_admin.php");
if (!$session_admin->is_logged_in()) {
    redirect_to("login.php");
}

if(isset($_POST['new_device']))
{
    extract($_POST);
    $query = "INSERT INTO digital_signage_system (device_name, device_location, device_ip) VALUES ('$name', '$location', '$ip')";
    $new_comment = $db_handle->runQuery($query);
    if ($new_comment)
    {
        $message_success = "The operation was successful.";
    }
    else
    {
        $message_error = "The operation was not successful, please try again.";
    }
}

$query = "SELECT * FROM digital_signage_system ";

$numrows = $db_handle->numRows($query);

$rowsperpage = 20;

$totalpages = ceil($numrows / $rowsperpage);
// get the current page or set a default
if (isset($_GET['pg']) && is_numeric($_GET['pg'])) {    $currentpage = (int)$_GET['pg'];} else {    $currentpage = 1;}
if ($currentpage > $totalpages) {    $currentpage = $totalpages;}
if ($currentpage < 1) {    $currentpage = 1;}
$prespagelow = $currentpage * $rowsperpage - $rowsperpage + 1;
$prespagehigh = $currentpage * $rowsperpage;
if ($prespagehigh > $numrows) {    $prespagehigh = $numrows;}
$offset = ($currentpage - 1) * $rowsperpage;
$query .= 'LIMIT ' . $offset . ',' . $rowsperpage;
$result = $db_handle->runQuery($query);
$completed_deposit_requests_filter = $db_handle->fetchAssoc($result);

?>
<!DOCTYPE html>
<html lang="en">
    <head>
        <base target="_self">
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>Instaforex Nigeria | Admin - Setting Rates</title>
        <meta name="title" content="Instaforex Nigeria | Admin - View Admin Members" />
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
                            <h4><strong>DIGITAL SIGNAGE SYSTEM</strong></h4>
                        </div>
                    </div>
                    <div class="section-tint super-shadow">
                        <div class="row">
                            <div class="col-sm-12">
                                    <div class="col-sm-12 well" style="display: inline-flex">
                                        <div id="search" class="col-sm-8 form-group input-group">
                                            <?php require_once 'layouts/feedback_message.php'; ?>
                                            <p><b>NOTE: </b>Please ensure you are on the same network with the device you want to manage.</p>
                                        </div>
                                        <div id="add_new_contact" class="col-sm-4 form-group input-group" >
                                        <span class="text-center input-group-btn">
                                            <button class="btn btn-default" data-toggle="modal" data-target="#add_project" type="button"><i class="glyphicon glyphicon-plus-sign"></i>  Add New Device</button>
                                        </span>
                                        </div>
                                        <!-- Modal-- Add New Project -->
                                        <div id="add_project" class="modal fade" role="dialog">
                                            <div class="modal-dialog">
                                                <!-- Modal content-->
                                                <form data-toggle="validator" class="form-horizontal" role="form" method="post" action="">
                                                    <div class="modal-content modal-sm">
                                                        <div class="modal-header">
                                                            <button type="button" class="close" data-dismiss="modal">&times;</button>
                                                            <h4 class="modal-title">Add New Device</h4>
                                                        </div>
                                                        <div class="modal-body">
                                                            <div class="row">
                                                                <div class="col-sm-12">
                                                                    <p><strong>Device Name(nickname)</strong></p>
                                                                    <input type="text" name="name" class="form-control" placeholder="Title" required/>
                                                                    <hr/>
                                                                    <p><strong>Device Location</strong></p>
                                                                    <input type="text" name="location" class="form-control" placeholder="Name" required/>
                                                                    <hr/>
                                                                    <p><strong>Device IP Address</strong></p>
                                                                    <input type="text" name="ip" class="form-control" placeholder="Ip Address" required/>
                                                                    <hr/>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="modal-footer">
                                                            <input name="new_device" type="submit" class="btn btn-success btn-sm" value="Add"/>
                                                            <button type="button" class="btn btn-danger btn-sm" data-dismiss="modal">Close</button>
                                                        </div>
                                                    </div>
                                                </form>
                                            </div>
                                        </div>
                                    </div>

                                <table id="outputTable" class="table table-responsive table-striped table-bordered table-hover">
                                    <thead>
                                    <tr>
                                        <th>Name</th>
                                        <th>Location</th>
                                        <th>IP Address</th>
                                        <th></th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    <?php
                                    if(isset($completed_deposit_requests_filter) && !empty($completed_deposit_requests_filter)) {
                                        foreach ($completed_deposit_requests_filter as $row) {
                                            ?>
                                            <tr>
                                                <td><?php echo $row['device_name']; ?></td>
                                                <td><?php echo $row['device_location']; ?></td>
                                                <td><?php echo $row['device_ip']; ?></td>
                                                <td>
                                                    <a href="http://<?php echo $row['device_ip']; ?>" target="_blank" class="btn btn-sm btn-default">View</a>
                                                </td>
                                            </tr>
                                        <?php } } else { echo "<tr><td colspan='4' class='text-danger'><em>No results to display</em></td></tr>"; } ?>
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