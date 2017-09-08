<?php
require_once("../init/initialize_admin.php");
if (!$session_admin->is_logged_in()) {
    redirect_to("login.php");
}
if(isset($_POST['process_delete']))
{
    if($obj_log->log_delete($_POST['log_id']))
    {
        $message_success = "You have successfully removed a log.";
    }
    else
    {
        $message_error = "Looks like something went wrong or you didn't make any change.";
    }
}

if(isset($_POST['process_treated']))
{
    if($obj_log->log_treated($_POST['log_id']))
    {
        $message_success = "You have successfully treated a log.";
    }
    else
    {
        $message_error = "Looks like something went wrong or you didn't make any change.";
    }
}

$admin_code = $_SESSION["admin_unique_code"];

$query = "SELECT * FROM customer_care_log WHERE status = 'PENDING' ORDER BY log_id DESC  ";
$numrows = $db_handle->numRows($query);
$rowsperpage = 20;

$totalpages = ceil($numrows / $rowsperpage);

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
$admin_all_logs = $db_handle->fetchAssoc($result);

?>
<!DOCTYPE html>
<html lang="en">
    <head>
        <base target="_self">
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>Instaforex Nigeria | Admin - Customer Care Logs</title>
        <meta name="title" content="Instaforex Nigeria | Admin - Customer Care Logs" />
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
                            <h4><strong>LOGS - ALL LOGGED IN INTERACTIONS</strong></h4>
                        </div>
                    </div>
                    
                    <div class="section-tint super-shadow">
                        <div class="row">
                            <div class="col-sm-12">
                                <p>All interaction logs that have been added on this platform, can be viewed below.</p>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-sm-12">
                                <table class="table table-responsive table-striped table-bordered table-hover">
                                    <thead>
                                        <tr>
                                            <th>Client/Customer's Details</th>
                                            <th>Last Conversation Description</th>
                                            <th>All Conversations</th>
                                            <th>Mark As Treated</th>
                                            <th>Delete</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php if(isset($admin_all_logs) && !empty($admin_all_logs)) { foreach ($admin_all_logs as $row){?>
                                        <tr>
                                            <td>
                                                <?php if($row['type'] == 'CLIENT')
                                                {
                                                    $client_details = $obj_log->client_details($row['tag']);
                                                    $client_details = $client_details[0];
                                                    echo "<strong>Full Name: </strong>".$client_details['first_name']." ".$client_details['middle_name']." ".$client_details['last_name']."<br/>";
                                                    echo "<strong>Phone : </strong>".$client_details['phone']."<br/>";
                                                    echo "<strong>Email : </strong>".$client_details['email']."<br/>";
                                                    echo "<strong>Account Number : </strong>".$client_details['ifx_acct_no']."<br/>";
                                                }
                                                elseif ($row['type'] == 'PROSPECT')
                                                {
                                                    $customer_details = $obj_log->customer_details($row['tag']);
                                                    $customer_details = $customer_details[0];
                                                    echo "<strong>Full Name: </strong>".$customer_details['first_name']." ".$customer_details['other_name']." ".$customer_details['last_name']."<br/>";
                                                    echo "<strong>Phone : </strong>".$customer_details['phone_number']."<br/>";
                                                    echo "<strong>Email : </strong>".$customer_details['email_address']."<br/>";
                                                }
                                                ?>
                                            </td>
                                            <td><?php echo $row['con_desc'] ?></td>
                                            <td>
                                                <button class="btn btn-info" data-target="#conversations<?php echo $row['log_id']?>" data-toggle="modal"><i class="glyphicon glyphicon-info-sign"></i></button>
                                                <!--Modal - confirmation boxes-->
                                                <div id="conversations<?php echo $row['log_id']?>" tabindex="-1" role="dialog" aria-hidden="true" class="modal fade">
                                                    <div class="modal-dialog">
                                                        <div class="modal-content">
                                                            <div class="modal-header">
                                                                <button type="button" data-dismiss="modal" aria-hidden="true" class="close">&times;</button>
                                                                <h4 class="modal-title">All Conversation Descriptions</h4>
                                                            </div>
                                                            <div class="modal-body">The table below contains all the logged in conversations with a unique Customer or Client.<br/>
                                                                <table class="table table-responsive table-striped table-bordered table-hover">
                                                                    <thead>
                                                                        <tr>
                                                                            <th>Conversation Description</th>
                                                                            <th>Status</th>
                                                                            <th>Created</th>
                                                                            <th>Mark As Treated</th>
                                                                            <th>Delete</th>
                                                                        </tr>
                                                                    </thead>
                                                                    <tbody>
                                                                    <?php if($row['type'] == 'CLIENT')
                                                                    {
                                                                        $client_details = $obj_log->client_details($row['tag']);
                                                                        $client_details = $client_details[0];
                                                                        echo "<strong>Full Name: </strong>".$client_details['first_name']." ".$client_details['middle_name']." ".$client_details['last_name']."<br/>";
                                                                        echo "<strong>Phone : </strong>".$client_details['phone']."<br/>";
                                                                        echo "<strong>Email : </strong>".$client_details['email']."<br/>";
                                                                        echo "<strong>Account Number : </strong>".$client_details['ifx_acct_no']."<br/>";
                                                                    }
                                                                    elseif ($row['type'] == 'PROSPECT')
                                                                    {
                                                                        $customer_details = $obj_log->customer_details($row['tag']);
                                                                        $customer_details = $customer_details[0];
                                                                        echo "<strong>Full Name: </strong>".$customer_details['first_name']." ".$customer_details['other_name']." ".$customer_details['last_name']."<br/>";
                                                                        echo "<strong>Phone : </strong>".$customer_details['phone_number']."<br/>";
                                                                        echo "<strong>Email : </strong>".$customer_details['email_address']."<br/>";
                                                                    }
                                                                    ?>
                                                                        <?php
                                                                        $all_conversations = $obj_log->get_all_conversations($row['tag']);
                                                                        if(isset($all_conversations) && !empty($all_conversations))  { foreach ($all_conversations as $row1) {
                                                                            ?>
                                                                        <tr>
                                                                            <td>
                                                                                <?php echo $row1['con_desc'] ?>
                                                                            </td>
                                                                            <td>
                                                                                <?php echo $row1['status'] ?>
                                                                            </td>
                                                                            <td>
                                                                                <?php echo $row1['created'] ?>
                                                                            </td>
                                                                            <td>
                                                                                <form>
                                                                                    <input type="hidden" name="log_id" value="<?php echo $row1['log_id']?>">
                                                                                    <button name="process_treated" type="submit" class="btn btn-success"><i class="glyphicon glyphicon-check"></i></button>
                                                                                </form>
                                                                            </td>
                                                                            <td>
                                                                                <form>
                                                                                    <input type="hidden" name="log_id" value="<?php echo $row1['log_id']?>">
                                                                                    <button name="process_delete" type="submit" class="btn btn-danger"><i class="glyphicon glyphicon-remove"></i></button>
                                                                                </form>
                                                                            </td>
                                                                        </tr>
                                                                        <?php } } else { echo "<tr><td colspan='5' class='text-danger'><em>No results to display</em></td></tr>"; } ?>
                                                                    </tbody>
                                                                </table>
                                                            </div>
                                                            <div class="modal-footer">
                                                                <button type="submit" name="close" onClick="window.close();" data-dismiss="modal" class="btn btn-danger">Close!</button>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </td>
                                            <td>
                                                <button data-target="#treated<?php echo $row['log_id']?>" data-toggle="modal" class="btn btn-success"><i class="glyphicon glyphicon-check"></i></button>
                                                <!--Modal - confirmation boxes-->
                                                <div id="confirm-add-customer-log" tabindex="-1" role="dialog" aria-hidden="true" class="modal fade">
                                                    <div class="modal-dialog">
                                                        <div class="modal-content">
                                                            <div class="modal-header">
                                                                <button type="button" data-dismiss="modal" aria-hidden="true" class="close">&times;</button>
                                                                <h4 class="modal-title">Mark As Treated</h4>
                                                            </div>
                                                            <div class="modal-body">Are you sure you have properly treated this complain or enquiry?</div>
                                                            <div class="modal-footer">
                                                                <form>
                                                                    <input type="hidden" name="log_id" value="<?php echo $row['log_id']?>">
                                                                    <input name="process_treated" type="submit" class="btn btn-success" value="Proceed">
                                                                </form>
                                                                <button type="submit" name="close" onClick="window.close();" data-dismiss="modal" class="btn btn-danger">Close!</button>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </td>
                                            <td>
                                                <form>
                                                    <input type="hidden" name="log_id" value="<?php echo $row['log_id']?>">
                                                    <button name="process_delete" type="submit" class="btn btn-danger"><i class="glyphicon glyphicon-remove"></i></button>
                                                </form>
                                            </td>
                                        </tr>
                                        <?php } } else { echo "<tr><td colspan='8' class='text-danger'><em>No results to display</em></td></tr>"; } ?>
                                    </tbody>
                                </table>
                                
                                <?php if(isset($admin_all_logs) && !empty($admin_all_logs)) { ?>
                                <div class="tool-footer text-right">
                                    <p class="pull-left">Showing <?php echo $prespagelow . " to " . $prespagehigh . " of " . $numrows; ?> entries</p>
                                </div>
                                <?php } ?>
                            </div>
                        </div>
                        <?php if(isset($admin_all_logs) && !empty($admin_all_logs)) { require_once 'layouts/pagination_links.php'; } ?>
                    </div>

                    <!-- Unique Page Content Ends Here
                    ================================================== -->
                    
                </div>
                
            </div>
        </div>
        <?php require_once 'layouts/footer.php'; ?>
    </body>
</html>