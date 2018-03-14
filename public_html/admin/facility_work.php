<?php
require_once("../init/initialize_admin.php");
if (!$session_admin->is_logged_in()) {
    redirect_to("login.php");
}

$all_admin_member = $admin_object->get_all_admin_member();

if(isset($_POST['assign'])){
    $work_id = $db_handle->sanitizePost($_POST['work_id']);
    $allowed_admin = ($_POST['allowed_admin']);
    for ($i = 0; $i < count($allowed_admin); $i++)
    {
        $all_allowed_admin = $all_allowed_admin . "," . $allowed_admin[$i];
    }

    $all_allowed_admin = substr_replace($all_allowed_admin, "", 0, 1);


    $query = "UPDATE facility_work SET assign = '$all_allowed_admin',status = 'processing',pro ='50' WHERE id = '$work_id' ";
    $result2 =$db_handle->runQuery($query);
    if($result2) {
        $message_success = "You have successfully added a new client to the system";
    } else {
        $message_error = "Something went wrong. Please try again.";
    }
}
{
    $query2 = "SELECT
facility_work.id AS id,
facility_work.created AS date,
          facility_work.title AS subject,
          facility_work.details AS details, 
          facility_work.location AS location,
          facility_work.priority AS prior,
          facility_work.status AS status,
          accounting_system_office_locations.location AS location, 
          CONCAT(admin.first_name, SPACE(1), admin.last_name) AS name
          FROM admin, facility_work,accounting_system_office_locations
          WHERE facility_work.created_by = admin.admin_code AND facility_work.location = accounting_system_office_locations.location_id
          ORDER BY facility_work.created DESC ";
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
    $rep = $db_handle->fetchAssoc($result);
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
                    <h4><button onclick="goBack()"><i class="fa fa-mail-reply fa-fw"></i>back</button><strong>WORK REQUEST</strong></h4>
                </div>
            </div>
            <div class="row">
                <div class="col-lg-12">

                    <div class="section-tint super-shadow">
                        <div class="row">
                            <div class="col-sm-12">
                                <h5>List of All Work Request </h5>
                                <table class="table table-responsive table-striped table-bordered table-hover">
                                    <thead>
                                    <th>Date</th>
                                    <th>Work Title</th>
                                    <th>Work Description</th>
                                    <th>Created By</th>
                                    <th>Location</th>
                                    <th>Priority</th>
                                    <th></th>
                                    <th>Status</th>
                                    </thead>
                                    <tbody>
                                    <?php if(isset($rep) && !empty($rep))
                                    {
                                        foreach ($rep as $row)
                                        { ?>
                                            <tr>
                                                <td><?php echo datetime_to_text2($row['date']); ?></td>
                                                <td><?php echo $row['subject']; ?></td>
                                                <td><?php echo $row['details']; ?></td>
                                                <td><?php echo $row['name']; ?></td>
                                                <td><?php echo $row['location']; ?></td>
                                                <td><?php echo $row['prior']; ?></td>
                                                <td> <button type="button" data-target="#check<?php echo $row['id']; ?>" data-toggle="modal"
                                                             class="btn btn-success">Assign Work
                                                    </button>
                                                    <div id="check<?php echo $row['id']; ?>" tabindex="-1" role="dialog" aria-hidden="true" class="modal fade">
                                                        <div class="modal-dialog">
                                                            <div class="modal-content">
                                                                <div class="modal-header">
                                                                    <button type="button" data-dismiss="modal" aria-hidden="true"
                                                                            class="close">&times;</button>
                                                                    <h4 class="modal-title">Assign Equipment User</h4></div>
                                                                <form data-toggle="validator" class="form-vertical" role="form" method="post" action="">
                                                                    <div class="modal-body">
                                                                        <input name="invent_id" type="hidden" id="id" value="<?php echo $row['inventoryid'];?>" class="form-control">

                                                                        <div class="form-group">
                                                                            <label class="control-label col-sm-2" for="allowed_admin">Assign Admin:</label>
                                                                            <div class="col-sm-10">
                                                                                <?php foreach($all_admin_member AS $key) { ?>
                                                                                    <div class="col-sm-5"><div class="checkbox"><label for=""><input type="checkbox" name="allowed_admin[]" value="<?php echo $key['admin_code']; ?>" <?php if (in_array($key['admin_code'], $allowed_admin)) { echo 'checked="checked"'; } ?>/> <?php echo $key['full_name']; ?></label></div></div>
                                                                                <?php } ?>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                    <input name="work_id" type="text"  value="<?php echo $row['id']; ?>" hidden>
                                                                    <div class="modal-footer">
                                                                        <input name="assign" type="submit" class="btn btn-success" value="Assign">
                                                                        <button type="submit" name="close" onClick="window.close();" data-dismiss="modal" class="btn btn-danger">Close!</button>
                                                                    </div>
                                                                </form>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </td>
                                                <td><?php echo $row['status']; ?></td>
                                            </tr>
                                            <?php
                                        }
                                    }
                                    else
                                    { echo "<em>No results to display...</em>"; } ?>
                                    </tbody>

                                </table>
                                <?php if(isset($rep) && !empty($rep)) { ?>
                                    <div class="tool-footer text-right">
                                        <p class="pull-left">Showing <?php echo $prespagelow . " to " . $prespagehigh . " of " . $numrows; ?> entries</p>
                                    </div>
                                <?php } ?>
                            </div>
                        </div>
                    </div>


                    <?php if(isset($rep) && !empty($rep)) { require_once 'layouts/pagination_links.php'; } ?>

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
<?php require_once 'layouts/footer.php'; ?>
<script src="//cdnjs.cloudflare.com/ajax/libs/moment.js/2.9.0/moment-with-locales.js"></script>
<script src="//cdn.rawgit.com/Eonasdan/bootstrap-datetimepicker/e8bddc60e73c1ec2475f827be36e1957af72e2ea/src/js/bootstrap-datetimepicker.js"></script>
</body>
</html>