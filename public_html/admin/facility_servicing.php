<?php
require_once("../init/initialize_admin.php");
if (!$session_admin->is_logged_in()) {
    redirect_to("login.php");
}
$admin_code = $_SESSION['admin_unique_code'];
$all_admin_member = $admin_object->get_all_admin_member();

if(isset($_POST['search'])){
    $filt = $db_handle->sanitizePost(trim($_POST['filter']));
    $search = $db_handle->sanitizePost(trim($_POST['x']));
$query = "SELECT * FROM `facility_inventory` WHERE $filt LIKE '%$search%'";
    $numrows = $db_handle->numRows($query);
    $rowsperpage = 100;

    $totalpages = ceil($numrows / $rowsperpage);
// get the current page or set a default
    if (isset($_GET['pg']) && is_numeric($_GET['pg'])) {
        $currentpage = (int)$_GET['pg'];
    } else {
        $currentpage = 1;
    }
    if ($currentpage > $totalpages) {
        $currentpage = $totalpages;
    }
    if ($currentpage < 1) {
        $currentpage = 1;
    }

    $prespagelow = $currentpage * $rowsperpage - $rowsperpage + 1;
    $prespagehigh = $currentpage * $rowsperpage;
    if ($prespagehigh > $numrows) {
        $prespagehigh = $numrows;
    }

    $offset = ($currentpage - 1) * $rowsperpage;
    $query .= 'LIMIT ' . $offset . ',' . $rowsperpage;
    $result = $db_handle->runQuery($query);
    $reports = $db_handle->fetchAssoc($result);
}

if(isset($_POST['service'])){
    $cost = $db_handle->sanitizePost(trim($_POST['cost']));
    $details = $db_handle->sanitizePost($_POST['details']);
    $invent_id = $db_handle->sanitizePost(trim($_POST['invent_id']));
    $next = $db_handle->sanitizePost($_POST['next']);
    $type = $db_handle->sanitizePost(trim($_POST['type']));
    $executor = $admin_code;
    $new_user = $obj_facility->servicing($invent_id, $cost, $executor, $type ,$next, $details);

    if($new_user) {
        $message_success = "You have successfully carried out ".$type;
    } else {
        $message_error = "Something went wrong. Please try again.";
    }


}
$s=date("m");
$query = "SELECT * FROM `facility_servicing` WHERE MONTH(next) = $s";
$numrows = $db_handle->numRows($query);
$rowsperpage = 100;

$totalpages = ceil($numrows / $rowsperpage);
// get the current page or set a default
if (isset($_GET['pg']) && is_numeric($_GET['pg'])) {
    $currentpage = (int)$_GET['pg'];
} else {
    $currentpage = 1;
}
if ($currentpage > $totalpages) {
    $currentpage = $totalpages;
}
if ($currentpage < 1) {
    $currentpage = 1;
}

$prespagelow = $currentpage * $rowsperpage - $rowsperpage + 1;
$prespagehigh = $currentpage * $rowsperpage;
if ($prespagehigh > $numrows) {
    $prespagehigh = $numrows;
}

$offset = ($currentpage - 1) * $rowsperpage;
$query .= ' LIMIT ' . $offset . ',' . $rowsperpage;

$result = $db_handle->runQuery($query);
$reps = $db_handle->fetchAssoc($result);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <base target="_self">
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Instaforex Nigeria | Admin</title>
    <meta name="title" content="Instaforex Nigeria | Admin" />
    <meta name="keywords" content="" />
    <meta name="description" content="" />
    <?php require_once 'layouts/head_meta.php'; ?>
    <?php require_once 'hr_attendance_system.php'; ?>
    <script src="//cdn.jsdelivr.net/alasql/0.3/alasql.min.js"></script>
    <script src="//cdnjs.cloudflare.com/ajax/libs/xlsx/0.7.12/xlsx.core.min.js"></script>
    <script>
        function goBack() {
            window.history.back();
        }
    </script>
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
                    <h4><button onclick="goBack()"><i class="fa fa-mail-reply fa-fw"></i>back</button><strong>FACILITY SERVICING</strong></h4>
                </div>
            </div>
                <div class="col-lg-12">
                    <div class="section-tint super-shadow">
                            <div class="row">
                                <div class="col-sm-6">
                                    <form id="requisition_form" data-toggle="validator" class="form-horizontal" role="form" method="post" action="">

                                        <div class="form-group">
                                            <div class="col-sm-12">
                                                <div class="row">
                                                        <div class="col-md-4">
                                                        <select name="filter" class="form-control" id="filter" placeholder="Filter by">
                                                            <option value="" >Filter by:</option>
                                                            <option value="name" >Name</option>
                                                            <option value="invent_id" >Inventory ID</option>
                                                            <option value="date">Purchase Date</option>
                                                            <option value="category">Category</option>

                                                        </select>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <div class="input-group">
                                                            <input type="text" class="form-control" name="x" placeholder="Search..." required>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-2 ">
                                                        <div class="input-group">
                                                            <button name="search" class="btn btn-success" type="submit"><span class="glyphicon glyphicon-search"></span></button>

                                                        </div>
                                                    </div>

                                                </div>
                                            </div>
                                        </div>
                                    </form>
                                </div>

                            </div>
                        <hr>
                        <?php if(isset($reports)):?>
                            <div id="dvTable">
                                <h5>List of Related Equipment</h5>
                                <table  class="table table-responsive table-striped table-bordered table-hover">
                                    <thead>
                                    <tr>
                                        <th>Last Service Date</th>
                                        <th>Inventory ID</th>
                                        <th>Name</th>
                                        <th>Activity</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    <?php
                                    foreach ($reports as $row)
                                    {
                                        ?>
                                        <tr>
                                            <td><?php echo datetime_to_text2($row['date']); ?></td>
                                            <td><?php echo $row['invent_id']; ?></td>
                                            <td><?php echo $row['name']; ?></td>
                                            <td><button data-target="#service<?php echo $row['invent_id'];?>" data-toggle="modal" class="btn btn-primary">Service Equipment</button>
                                                <!--Modal - confirmation boxes-->
                                            <div id="service<?php echo $row['invent_id'];?>" tabindex="-1" role="dialog" aria-hidden="true" class="modal fade">
                                                <div class="modal-dialog">
                                                    <div class="modal-content">
                                                        <div class="modal-header">
                                                            <button type="button" data-dismiss="modal" aria-hidden="true"
                                                                    class="close">&times;</button>
                                                            <h4 class="modal-title">Details</h4></div>
                                                        <div class="modal-body">Details for <?php echo $row['name'];?>

                                                            <div class="row">
                                                                <div class="col-sm-12">
                                                                <form data-toggle="validator" class="form-vertical" role="form" method="post" action="">
                                                                <input name="invent_id" type="hidden" id="id" value="<?php echo $row['invent_id'];?>" class="form-control">
                                                                <div class="form-group">
                                                                        <label class="control-label col-sm-3" for="inventoryid">Service Cost:</label>
                                                                        <div class="col-sm-12 col-lg-8">
                                                                            <div class="input-group">
                                                                                <span class="input-group-addon">₦</span>
                                                                                <input name="cost" type="text" id="cost" class="form-control" required/>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                <div class="form-group">
                                                                    <label class="control-label col-sm-3" for="comment">Service Type:</label>
                                                                    <div class="col-sm-8 ">
                                                                        <div class="input-group">
                                                                            <span class="input-group-addon"><i class="fa fa-support fa-fw"></i></span>
                                                                    <select name="type" class="form-control" id="filter" placeholder="Filter by">
                                                                        <option value="Sevicing" >Servicing</option>
                                                                        <option value="Maintenance" >Maintenace</option>
                                                                        <option value="Inspection" >Inspection</option>
                                                                    </select>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                                    <div class="form-group">
                                                                        <label for="comment">Details:</label>
                                                                        <div class="col-sm-8 ">
                                                                        <div class="input-group">
                                                                            <span class="input-group-addon"><i class="fa fa-edit fa-fw"></i></span>
                                                                            <textarea name="details" class="form-control" rows="3" id="comment"></textarea>
                                                                        </div>
                                                                        </div>
                                                                    </div>
                                                                    <div class="form-group">
                                                                        <label class="control-label col-sm-3" for="from_date">Next:</label>
                                                                        <div class="">
                                                                            <div class="input-group date">
                                                                                <input name="next" type="text" class="form-control" id="datetimepicker" required>
                                                                                <span class="input-group-addon"><span class="glyphicon glyphicon-calendar"></span></span>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                    <div class="form-group">
                                                                        <button type="submit"  name="service" class="btn btn-success"><i class="fa fa-send fa-fw"></i>Send</button>
                                                                    </div>
                                                                </form></div>

                                                            </div>
                                                        </div>
                                                        <form>

                                                            <div class="modal-footer">
                                                                <input name="process" type="submit" class="btn btn-success" value="Report">
                                                                <button type="submit" name="close" onClick="window.close();" data-dismiss="modal" class="btn btn-danger">Close!</button>
                                                            </div>
                                                            <script type="text/javascript">
                                                                $(function () {
                                                                    $('#datetimepicker, #datetimepicker2').datetimepicker({
                                                                        format: 'YYYY-MM-DD'
                                                                    });
                                                                });
                                                            </script>
                                                        </form>
                                                    </div>
                                                </div>
                                            </div>
                        <!--Modal - confirmation boxes-->

                                        </td>
                                        </tr>
                                        <?php
                                    }?>
                                    </tbody>
                                </table>
                            </div>

                        <?php endif; ?>

                </div>
                    <div class="section-tint super-shadow">
                        <div class="row">
                            <div class="col-sm-12">
                                <h5>List of All Servicing For this month </h5>
                                <?php if(isset($reps)):?>
                                    <table  class="table table-responsive table-striped table-bordered table-hover">
                                        <thead>
                                        <tr>
                                            <th>Last Service Date</th>
                                            <th>Inventory ID</th>
                                            <th>Activity</th>
                                            <th>Due Date</th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                        <?php
                                        foreach ($reps as $row)
                                        {
                                            ?>
                                            <tr>
                                                <td><?php echo datetime_to_text2($row['date']); ?></td>
                                                <td><?php echo $row['invent_id']; ?></td>
                                                <td><button data-target="#service<?php echo $row['invent_id'];?>" data-toggle="modal" class="btn btn-primary">Service Equipment</button>
                                                    <!--Modal - confirmation boxes-->
                                                    <div id="service<?php echo $row['invent_id'];?>" tabindex="-1" role="dialog" aria-hidden="true" class="modal fade">
                                                        <div class="modal-dialog">
                                                            <div class="modal-content">
                                                                <div class="modal-header">
                                                                    <button type="button" data-dismiss="modal" aria-hidden="true"
                                                                            class="close">&times;</button>
                                                                    <h4 class="modal-title">Details</h4></div>
                                                                <div class="modal-body">Details for <?php echo $row['invent_id'];?>

                                                                    <div class="row">
                                                                        <div class="col-sm-12">
                                                                            <form data-toggle="validator" class="form-vertical" role="form" method="post" action="">
                                                                                <input name="invent_id" type="hidden" id="id" value="<?php echo $row['invent_id'];?>" class="form-control">
                                                                                <div class="form-group">
                                                                                    <label class="control-label col-sm-3" for="inventoryid">Service Cost:</label>
                                                                                    <div class="col-sm-12 col-lg-8">
                                                                                        <div class="input-group">
                                                                                            <span class="input-group-addon">₦</span>
                                                                                            <input name="cost" type="text" id="cost" class="form-control" required/>
                                                                                        </div>
                                                                                    </div>
                                                                                </div>
                                                                                <div class="form-group">
                                                                                    <label class="control-label col-sm-3" for="comment">Service Type:</label>
                                                                                    <div class="col-sm-8 ">
                                                                                        <div class="input-group">
                                                                                            <span class="input-group-addon"><i class="fa fa-support fa-fw"></i></span>
                                                                                            <select name="type" class="form-control" id="filter" placeholder="Filter by">
                                                                                                <option value="Sevicing" >Servicing</option>
                                                                                                <option value="Maintenance" >Maintenace</option>
                                                                                                <option value="Inspection" >Inspection</option>
                                                                                            </select>
                                                                                        </div>
                                                                                    </div>
                                                                                </div>
                                                                                <div class="form-group">
                                                                                    <label class="control-label col-sm-3" for="comment">Details:</label>
                                                                                    <div class="col-sm-8 ">
                                                                                        <div class="input-group">
                                                                                            <span class="input-group-addon"><i class="fa fa-edit fa-fw"></i></span>
                                                                                            <textarea name="details" class="form-control" rows="3" id="comment"></textarea>
                                                                                        </div>
                                                                                    </div>
                                                                                </div>
                                                                                <div class="form-group">
                                                                                    <label class="control-label col-sm-3" for="from_date">Next:</label>
                                                                                    <div class="">
                                                                                        <div class="input-group date col-sm-8">
                                                                                            <input name="next" type="text" class="form-control" id="datetimepicker" required>
                                                                                            <span class="input-group-addon"><span class="glyphicon glyphicon-calendar"></span></span>
                                                                                        </div>
                                                                                    </div>
                                                                                </div>
                                                                                <div class="form-group">
                                                                                    <button type="submit"  name="service" class="btn btn-success"><i class="fa fa-send fa-fw"></i>Send</button>
                                                                                </div>
                                                                            </form></div>

                                                                    </div>
                                                                </div>
                                                                <form>
                                                                    <script type="text/javascript">
                                                                        $(function () {
                                                                            $('#datetimepicker, #datetimepicker2').datetimepicker({
                                                                                format: 'YYYY-MM-DD'
                                                                            });
                                                                        });
                                                                    </script>
                                                                </form>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <!--Modal - confirmation boxes-->

                                                </td>
                                                <td><?php echo datetime_to_text2($row['next']); ?></td>
                                            </tr>
                                            <?php
                                        }?>
                                        </tbody>
                                    </table>
                                <?php endif; ?>
                                    <?php if(isset($reps) && !empty($reps)) { ?>
                                        <div class="tool-footer text-right">
                                            <p class="pull-left">Showing <?php echo $prespagelow . " to " . $prespagehigh . " of " . $numrows; ?> entries</p>
                                        </div>
                                    <?php } ?>

                            </div>
                        </div>
                    </div>


                    <?php if(isset($reps) && !empty($reps)) { require_once 'layouts/pagination_links.php'; } ?>

</div>
        <!-- Unique Page Content Ends Here
        ================================================== -->

    </div>

</div>
<?php require_once 'layouts/footer.php'; ?>
<script src="//cdnjs.cloudflare.com/ajax/libs/moment.js/2.9.0/moment-with-locales.js"></script>
<script src="//cdn.rawgit.com/Eonasdan/bootstrap-datetimepicker/e8bddc60e73c1ec2475f827be36e1957af72e2ea/src/js/bootstrap-datetimepicker.js"></script>
</body>
</html>