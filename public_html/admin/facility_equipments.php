<?php
require_once("../init/initialize_admin.php");
if (!$session_admin->is_logged_in()) {
    redirect_to("login.php");
}

$query = "SELECT SUM(facility_inventory.cost) AS total_cost
              FROM facility_inventory";
$result = $db_handle->runQuery($query);
$stats = $db_handle->fetchAssoc($result);
$stats = $stats[0];
$total = $stats['total_cost'];
$all_admin_member = $admin_object->get_all_admin_member();

if(isset($_POST['assign'])){
    $invent_id = $db_handle->sanitizePost($_POST['invent_id']);
    $allowed_admin = ($_POST['allowed_admin']);
    for ($i = 0; $i < count($allowed_admin); $i++)
    {
        $all_allowed_admin = $all_allowed_admin . "," . $allowed_admin[$i];
    }

    $all_allowed_admin = substr_replace($all_allowed_admin, "", 0, 1);


    $query = "UPDATE facility_inventory SET users = '$all_allowed_admin' WHERE invent_id = '$invent_id' ";
    $result2 =$db_handle->runQuery($query);
    if($result2) {
        $message_success = "You have successfully added a new client to the system";
    } else {
        $message_error = "Something went wrong. Please try again.";
    }
}

{
    $query2 = "SELECT facility_inventory.invent_id AS inventoryid,

          facility_inventory.name AS name, 

          facility_inventory.cost AS cost, 

          facility_inventory.date AS date, 

          facility_inventory.admin AS admin,

          facility_inventory.users AS user,
          
          facility_inventory.location AS location,
          
          facility_inventory.category AS cart,

          facility_location.location AS location

           FROM facility_inventory,facility_location
           WHERE  facility_inventory.location = facility_location.location_id
          ORDER BY facility_inventory.created DESC ";
    $numrows = $db_handle->numRows($query2);
    $rowsperpage = 20;
    $totalpages = ceil($numrows / $rowsperpage);
    if (isset($_GET['pg']) && is_numeric($_GET['pg'])) {    $currentpage = (int) $_GET['pg'];} else {    $currentpage = 1;}
    if ($currentpage > $totalpages) { $currentpage = $totalpages; }
    if ($currentpage < 1) { $currentpage = 1; }
    $prespagelow = $currentpage * $rowsperpage - $rowsperpage + 1;
    $prespagehigh = $currentpage * $rowsperpage;
    if($prespagehigh > $numrows) { $prespagehigh = $numrows; }
    $offset = ($currentpage - 1) * $rowsperpage;
    $query2 .= 'LIMIT ' . $offset . ',' . $rowsperpage;
    $result = $db_handle->runQuery($query2);
    $reports = $db_handle->fetchAssoc($result);

}
if(isset($_POST['search'])){
    $filt = $db_handle->sanitizePost(trim($_POST['filter']));
    $search = $db_handle->sanitizePost(trim($_POST['x']));
    $query = "SELECT facility_inventory.invent_id AS inventoryid,

          facility_inventory.name AS name, 

          facility_inventory.cost AS cost, 

          facility_inventory.date AS date, 

          facility_inventory.admin AS admin,

          facility_inventory.users AS user,
          
          facility_inventory.location AS location,
          
          facility_inventory.category AS cart,

          facility_location.location AS location

           FROM facility_inventory,facility_location 
           WHERE  facility_inventory.location = facility_location.location_id 
           AND $filt LIKE '%$search%'";
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
                    <h4><button onclick="goBack()"><i class="fa fa-mail-reply fa-fw"></i>back</button><strong>FACILITY EQUIPMENT</strong></h4>
                </div>
            </div>
            <div class="col-lg-12">
                <?php require_once 'layouts/feedback_message.php'; ?>

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
                                                    <option value="facility_inventory.name" >Name</option>
                                                    <option value="facility_inventory.invent_id" >Inventory ID</option>
                                                    <option value="facility_inventory.date">Purchase Date</option>
                                                    <option value="facility_inventory.category">Category</option>
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
                    <?php if(isset($reports) && !empty($reports)):?>
                        <div id="dvTable">
                            <h5>List of Equipment</h5>
                            <table  class="table table-responsive table-striped table-bordered table-hover">
                                <thead>
                                <tr>
                                    <th>Purchase Date</th>
                                    <th>Inventory ID</th>
                                    <th>Name</th>
                                    <th>Purchase Cost</th>
                                    <th>Category</th>
                                    <th>Current Location</th>
                                </tr>
                                </thead>
                                <tbody>
                                <?php
                                foreach ($reports as $row)
                                {
                                    ?>
                                    <tr>
                                        <td><?php echo datetime_to_text2($row['date']); ?></td>
                                        <td><?php echo $row['inventoryid']; ?></td>
                                        <td><?php echo $row['name']; ?></td>
                                        <td><?php echo $row['cost']; ?></td>
                                        <td><?php echo $row['cart']; ?></td>
                                        <td><?php echo $row['location']; ?></td>
                                        <td><div class="form-group">
                                                <button type="button" data-target="#complete<?php echo $row['inventoryid'];?>" data-toggle="modal" class="btn btn-success"><i class="fa fa-history fa-fw"></i></button>
                                                <button type="button" data-target="#assign<?php echo $row['inventoryid'];?>" data-toggle="modal" class="btn btn-success">Assign</button>
                                            </div>
                                            <!--Modal - confirmation boxes-->
                                            <div id="complete<?php echo $row['inventoryid'];?>" tabindex="-1" role="dialog" aria-hidden="true" class="modal fade">
                                                <div class="modal-dialog">
                                                    <div class="modal-content">
                                                        <div class="modal-header">
                                                            <button type="button" data-dismiss="modal" aria-hidden="true"
                                                                    class="close">&times;</button>
                                                            <h4 class="modal-title">Details</h4></div>
                                                        <div class="modal-body">Details for <?php echo $row['name'];?>
                                                            <div class="row">
                                                                <div class="col-md-6">
                                                                    <ul class="list-group">
                                                                        <li class="list-group-item active">Administrator</li>
                                                                        <?php $all_allowed_admin = $row['admin'];
                                                                        $all_allowed_admin = explode("," ,$all_allowed_admin);
                                                                        for ($i = 0; $i < count($all_allowed_admin); $i++)
                                                                        {
                                                                            $destination_details = $obj_facility->get_admin_detail_by_code($all_allowed_admin[$i]);
                                                                            $admin_name = $destination_details['first_name'];
                                                                            $admin_email = $destination_details['last_name'];
                                                                            echo "<li class='list-group-item'>" . $admin_name . " " . $admin_email . "</li>";
                                                                        }
                                                                        ?>
                                                                    </ul>
                                                                </div>
                                                                <div class="col-md-6">
                                                                    <ul class="list-group">
                                                                        <li class="list-group-item active">Users</li>
                                                                        <?php $all_allowed_admin = $row['user'];
                                                                        $all_allowed_admin = explode("," ,$all_allowed_admin);
                                                                        for ($i = 0; $i < count($all_allowed_admin); $i++)
                                                                        {
                                                                            $destination_details = $obj_facility->get_admin_detail_by_code($all_allowed_admin[$i]);
                                                                            $admin_name = $destination_details['first_name'];
                                                                            $admin_email = $destination_details['last_name'];
                                                                            echo "<li class='list-group-item'>" . $admin_name . " " . $admin_email . "</li>";
                                                                        }
                                                                        ?>
                                                                    </ul>
                                                                </div>
                                                            </div>
                                                            <table  class="table table-responsive table-striped table-bordered table-hover">
                                                                <thead>
                                                                <tr>
                                                                    <th>Date</th>
                                                                    <th>Details</th>
                                                                    <th>Service Cost</th>
                                                                    <th>Office Location</th>
                                                                    <th>Executor</th>
                                                                    <th>Service Type</th>
                                                                </tr>
                                                                </thead>
                                                                <tbody>
                                                                <?php
                                                                $service_list = $obj_facility->get_service_list($row['inventoryid']);
                                                                foreach ($service_list as $row2) {?>
                                                                    <tr>
                                                                        <td><?php echo datetime_to_text2($row2['date']); ?></td>
                                                                        <td><?php echo $row2['details']; ?></td>
                                                                        <td>₦<?php echo number_format($row2['cost'],2,".",",");?>
                                                                        <td><?php echo $row['location']; ?></td>
                                                                        <td><?php echo $row2['author_name']; ?></td>
                                                                        <td><?php echo $row2['type']; ?></td>
                                                                    </tr>
                                                                    <?php
                                                                }?>
                                                                </tbody>
                                                            </table>
                                                        </div>
                                                        <form>

                                                            <div class="modal-footer">

                                                                <button type="submit" name="close" onClick="window.close();" data-dismiss="modal" class="btn btn-danger">Close!</button>
                                                            </div>
                                                        </form>
                                                    </div>
                                                </div>
                                            </div>
                                            <!--Modal - confirmation boxes-->
                                            <div id="assign<?php echo $row['inventoryid'];?>" tabindex="-1" role="dialog" aria-hidden="true" class="modal fade">
                                                <div class="modal-dialog">
                                                    <div class="modal-content">
                                                        <div class="modal-header">
                                                            <button type="button" data-dismiss="modal" aria-hidden="true"
                                                                    class="close">&times;</button>
                                                            <h4 class="modal-title">Assign Equipments</h4></div>

                                                        <form data-toggle="validator" class="form-vertical" role="form" method="post" action="">
                                                            <div class="modal-body">Details for <?php echo $row['name'];?>
                                                                <div class="row">

                                                                    <input name="invent_id" type="hidden" id="id" value="<?php echo $row['inventoryid'];?>" class="form-control">

                                                                    <div class="form-group">
                                                                        <label class="control-label col-sm-2" for="allowed_admin">Select Equipment User's:</label>
                                                                        <div class="col-sm-10">
                                                                            <?php foreach($all_admin_member AS $key) { ?>
                                                                                <div class="col-sm-5"><div class="checkbox"><label for=""><input type="checkbox" name="allowed_admin[]" value="<?php echo $key['admin_code']; ?>" <?php if (in_array($key['admin_code'], $allowed_admin)) { echo 'checked="checked"'; } ?>/> <?php echo $key['full_name']; ?></label></div></div>
                                                                            <?php } ?>
                                                                        </div>
                                                                    </div>
                                                                </div>

                                                            </div>
                                                            <div class="modal-footer">
                                                                <input name="assign" type="submit" class="btn btn-success" value="Assign">
                                                                <button type="submit" name="close" onClick="window.close();" data-dismiss="modal" class="btn btn-danger">Close!</button>
                                                            </div>
                                                        </form>
                                                    </div>
                                                </div>
                                            </div>
                                        </td>
                                    </tr>
                                    <?php
                                }?>
                                </tbody>
                            </table>
                        </div>

                    <?php endif; ?>
                </div>


            </div>



        </div>


    </div>
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