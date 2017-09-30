<?php
require_once("../init/initialize_admin.php");
if (!$session_admin->is_logged_in())
{
    redirect_to("login.php");
}

if(isset($_POST['delete_reminder']))
{
    $result = $obj_project_management->delete_reminder($_POST['reminder_id']);
    if($result)
    {
        $message_success = "You have successfully deleted a reminder.";
    }
    else
        {
            $message_error = "The operation was not successful.";
        }

}

if(isset($_POST['edit_reminder']))
{
    $result = $obj_project_management->update_reminders($_POST['reminder_id'], $_POST['description'], $_POST['effect_date']);
    if($result)
    {
        $message_success = "You have successfully deleted a reminder.";
    }
    else
    {
        $message_error = "The operation was not successful.";
    }
}

$current_date = date("Y-m-d");

$pending_query = "SELECT reminder_id, description, effect_date
                  FROM project_management_reminders
                  WHERE project_management_reminders.status = 'ON' 
                  AND project_management_reminders.effect_date > '$current_date'
                  ORDER BY effect_date DESC ";
$pending_numrows = $db_handle->numRows($pending_query);
$pending_rowsperpage = 20;
$pending_totalpages = ceil($pending_numrows / $pending_rowsperpage);
// get the current page or set a default
if (isset($_GET['pg']) && is_numeric($_GET['pg']))
{
    $pending_currentpage = (int) $_GET['pg'];
}
else
    {
    $pending_currentpage = 1;
}
if ($pending_currentpage > $pending_totalpages) { $pending_currentpage = $pending_totalpages; }
if ($pending_currentpage < 1) { $pending_currentpage = 1; }

$pending_prespagelow = $pending_currentpage * $pending_rowsperpage - $pending_rowsperpage + 1;
$pending_prespagehigh = $pending_currentpage * $pending_rowsperpage;
if($pending_prespagehigh > $pending_numrows) { $pending_prespagehigh = $pending_numrows; }

$pending_offset = ($pending_currentpage - 1) * $pending_rowsperpage;
$pending_query .= 'LIMIT ' . $pending_offset . ',' . $pending_rowsperpage;
//var_dump($pending_query);
$pending_result = $db_handle->runQuery($pending_query);
$pending_reminders = $db_handle->fetchAssoc($pending_result);



$expired_query = "SELECT reminder_id, description, effect_date
                  FROM project_management_reminders
                  WHERE status = 'OFF' 
                  AND effect_date <= '$current_date'
                  ORDER BY effect_date DESC ";
$expired_numrows = $db_handle->numRows($expired_query);
$expired_rowsperpage = 20;
$expired_totalpages = ceil($expired_numrows / $expired_rowsperpage);
// get the current page or set a default
if (isset($_GET['pg']) && is_numeric($_GET['pg'])) {
   $expired_currentpage = (int) $_GET['pg'];
} else {
   $expired_currentpage = 1;
}
if ($expired_currentpage > $expired_totalpages) { $expired_currentpage = $expired_totalpages; }
if ($expired_currentpage < 1) { $expired_currentpage = 1; }

$expired_prespagelow = $expired_currentpage * $expired_rowsperpage - $expired_rowsperpage + 1;
$expired_prespagehigh = $expired_currentpage * $expired_rowsperpage;
if($expired_prespagehigh > $expired_numrows) { $expired_prespagehigh = $expired_numrows; }

$expired_offset = ($expired_currentpage - 1) * $expired_rowsperpage;
$expired_query .= 'LIMIT ' . $expired_offset . ',' . $expired_rowsperpage;
//var_dump($expired_query);
$expired_result = $db_handle->runQuery($expired_query);
$expired_reminders = $db_handle->fetchAssoc($expired_result);
?>
<!DOCTYPE html>
<html lang="en">
    <head>
        <base target="_self">
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>Instaforex Nigeria | Admin - Reminders</title>
        <meta name="title" content="Instaforex Nigeria | Admin - Career Rejected Applications" />
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
                            <h4><strong>REMINDERS - ALL SCHEDULED REMINDERS</strong></h4>
                        </div>
                    </div>
                    
                    <div class="section-tint super-shadow">
                        <div class="row">
                            <div class="col-sm-12">
                                <ul class="nav nav-tabs">
                                    <li class="active"><a data-toggle="tab" href="#pending">Pending Reminders</a></li>
                                    <li><a data-toggle="tab" href="#expired">Expired Reminders</a></li>
                                </ul>
                                <div class="tab-content">
                                    <div id="pending" class="tab-pane fade in active">
                                        <p>Below are the pending reminders</p>
                                        <table class="table table-responsive table-striped table-bordered table-hover">
                                            <thead>
                                                <tr>
                                                    <th>Description</th>
                                                    <th>Effect Date</th>
                                                    <th>Edit</th>
                                                    <th>Delete</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <?php if(isset($pending_reminders) && !empty($pending_reminders)) { foreach ($pending_reminders as $row) { ?>
                                                <tr>
                                                    <td><?php echo $row['description']; ?></td>
                                                    <td><?php echo $row['effect_date']; ?></td>
                                                    <td>
                                                        <button data-target="#edit-reminder<?php echo $row['reminder_id']; ?>" data-toggle="modal" title="Edit Reminder" class="btn btn-success"><i class="glyphicon glyphicon-adjust"></i></button>
                                                        <!--Modal - confirmation boxes-->
                                                        <div id="edit-reminder<?php echo $row['reminder_id']; ?>" tabindex="-1" role="dialog" aria-hidden="true" class="modal fade">
                                                            <div class="modal-dialog">
                                                                <div class="modal-content">
                                                                    <div class="modal-header">
                                                                        <button type="button" data-dismiss="modal" aria-hidden="true" class="close">&times;</button>
                                                                        <h4 class="modal-title">Edit Reminder</h4>
                                                                    </div>
                                                                    <div class="modal-body">

                                                                        <form data-toggle="validator" class="form-horizontal" role="form" method="post" action="">
                                                                            <input name="reminder_id" type="hidden" value="<?php echo $row['reminder_id']; ?>">

                                                                            <div class="form-group">
                                                                                <label class="control-label col-sm-3" for="description">Description:</label>
                                                                                <div class="col-sm-9">
                                                                                    <textarea name="description" id="description" rows="10"  class="form-control" required><?php echo $row['description']; ?></textarea>
                                                                                </div>
                                                                            </div>
                                                                            <div class="form-group">
                                                                                <label class="control-label col-sm-3" for="effect_date">Effect Date:</label>
                                                                                <div class="col-sm-9 ">
                                                                                    <div class="input-group date" id="datetimepicker">
                                                                                        <input name="effect_date" type="text" class="form-control" id="datetimepicker2" value="<?php echo $row['effect_date']; ?>" required>
                                                                                        <span class="input-group-addon"><span class="glyphicon glyphicon-calendar"></span></span>
                                                                                    </div>
                                                                                    <br/>
                                                                                    <input name="edit_reminder" type="submit" class="btn btn-success" value="Update">
                                                                                </div>
                                                                                <script type="text/javascript">
                                                                                    $(function ()
                                                                                    {
                                                                                        $('#datetimepicker, #datetimepicker2').datetimepicker(
                                                                                            {
                                                                                                format: 'YYYY-MM-DD'
                                                                                            });
                                                                                    });
                                                                                </script>
                                                                            </div>
                                                                        </form>
                                                                    </div>
                                                                    <div class="modal-footer">
                                                                        <button type="submit" name="close" onClick="window.close();" data-dismiss="modal" class="btn btn-danger">Close!</button>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </td>
                                                    <td>
                                                        <button data-target="#confirm-delete-reminder<?php echo $row['reminder_id']; ?>" data-toggle="modal" title="Delete Reminder" class="btn btn-danger"><i class="glyphicon glyphicon-remove"></i></button>
                                                        <!--Modal - confirmation boxes-->
                                                        <div id="confirm-delete-reminder<?php echo $row['reminder_id']; ?>" tabindex="-1" role="dialog" aria-hidden="true" class="modal fade">
                                                            <div class="modal-dialog">
                                                                <div class="modal-content">
                                                                    <div class="modal-header">
                                                                        <button type="button" data-dismiss="modal" aria-hidden="true" class="close">&times;</button>
                                                                        <h4 class="modal-title">Delete Reminder</h4>
                                                                    </div>
                                                                    <div class="modal-body">Are you sure you want to delete this reminder?</div>
                                                                    <div class="modal-footer">
                                                                        <form data-toggle="validator" class="form-horizontal" role="form" method="post" action="">
                                                                            <input name="reminder_id" type="hidden" value="<?php echo $row['reminder_id']; ?>">
                                                                            <input name="delete_reminder" type="submit" class="btn btn-success" value="Proceed">
                                                                            <button type="submit" name="close" onClick="window.close();" data-dismiss="modal" class="btn btn-danger">Close!</button>
                                                                        </form>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </td>
                                                </tr>
                                                <?php } } else { echo "<tr><td colspan='5' class='text-danger'><em>No results to display</em></td></tr>"; } ?>
                                            </tbody>
                                        </table>
                                        <?php if(isset($pending_reminders) && !empty($pending_reminders)) { ?>
                                            <div class="tool-footer text-right">
                                                <p class="pull-left">Showing <?php echo $pending_prespagelow . " to " . $pending_prespagehigh . " of " . $pending_numrows; ?> entries</p>
                                            </div>
                                        <?php } ?>
                                        <?php if(isset($pending_reminders) && !empty($pending_reminders)) { require_once 'layouts/pagination_links.php'; } ?>
                                    </div>

                                    <div id="expired" class="tab-pane fade">
                                        <p>Below are the expired reminders</p>
                                        <table class="table table-responsive table-striped table-bordered table-hover">
                                            <thead>
                                            <tr>
                                                <th>Description</th>
                                                <th>Effect Date</th>
                                                <th>Delete</th>
                                            </tr>
                                            </thead>
                                            <tbody>
                                            <?php if(isset($expired_reminders) && !empty($expired_reminders)) { foreach ($expired_reminders as $row) { ?>
                                                <tr>
                                                    <td><?php echo $row['description']; ?></td>
                                                    <td><?php echo $row['effect_date']; ?></td>
                                                    <td>
                                                        <button data-target="#confirm-delete-reminder<?php echo $row['reminder_id']; ?>" data-toggle="modal" title="Delete Reminder" class="btn btn-danger"><i class="glyphicon glyphicon-remove"></i></button>
                                                        <!--Modal - confirmation boxes-->
                                                        <div id="confirm-delete-reminder<?php echo $row['reminder_id']; ?>" tabindex="-1" role="dialog" aria-hidden="true" class="modal fade">
                                                            <div class="modal-dialog">
                                                                <div class="modal-content">
                                                                    <div class="modal-header">
                                                                        <button type="button" data-dismiss="modal" aria-hidden="true" class="close">&times;</button>
                                                                        <h4 class="modal-title">Delete Reminder</h4>
                                                                    </div>
                                                                    <div class="modal-body">Are you sure you want to delete this reminder?</div>
                                                                    <div class="modal-footer">
                                                                        <form data-toggle="validator" class="form-horizontal" role="form" method="post" action="">
                                                                            <input name="reminder_id" type="hidden" value="<?php echo $row['reminder_id']; ?>">
                                                                            <input name="delete_reminder" type="submit" class="btn btn-success" value="Proceed">
                                                                            <button type="submit" name="close" onClick="window.close();" data-dismiss="modal" class="btn btn-danger">Close!</button>
                                                                        </form>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </td>
                                                </tr>
                                            <?php } } else { echo "<tr><td colspan='5' class='text-danger'><em>No results to display</em></td></tr>"; } ?>
                                            </tbody>
                                        </table>
                                        <?php if(isset($expired_reminders) && !empty($expired_reminders)) { ?>
                                            <div class="tool-footer text-right">
                                                <p class="pull-left">Showing <?php echo $expired_prespagelow . " to " . $expired_prespagehigh . " of " . $expired_numrows; ?> entries</p>
                                            </div>
                                        <?php } ?>
                                        <?php if(isset($expired_reminders) && !empty($expired_reminders)) { require_once 'layouts/pagination_links.php'; } ?>
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
        <script src="//cdnjs.cloudflare.com/ajax/libs/moment.js/2.9.0/moment-with-locales.js"></script>
        <script src="//cdn.rawgit.com/Eonasdan/bootstrap-datetimepicker/e8bddc60e73c1ec2475f827be36e1957af72e2ea/src/js/bootstrap-datetimepicker.js"></script>
    </body>
</html>