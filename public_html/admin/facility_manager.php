<?php
require_once("../init/initialize_admin.php");
if (!$session_admin->is_logged_in()) {
    redirect_to("login.php");
}

$query5 = "SELECT * FROM facility_inventory";
$equip = $db_handle->numRows($query5);

$query6 = "SELECT * FROM facility_work WHERE status = 'pending'";
$pwork = $db_handle->numRows($query6);

$query7 = "SELECT * FROM facility_report WHERE status = '0'";
$preport = $db_handle->numRows($query7);

$s=date("Y/m/d");
$query8 = "SELECT * FROM facility_sevicing WHERE next <= '$s'";
$serv = $db_handle->numRows($query8);

if (isset($_POST['upload'])) {
    //get file details
    $fileName = $_FILES['Filename']['name'];
    $tempFileName = $_FILES["Filename"]["tmp_name"];

    //Insert into Data base from file upload path
    $csv_file = $tempFileName;
    if (($handle = fopen($csv_file, "r")) !== FALSE) {
        fgetcsv($handle);
        while (($data = fgetcsv($handle, 1000, ",")) !== FALSE) {
            $num = count($data);
            for ($c=0; $c < $num; $c++) {
                $col[$c] = $data[$c];
            }

            $col1 = $col[0];
            $col2 = $col[1];
            $col3 = $col[2];
            $col4 = $col[3];
            $col5 = $col[4];
            //generate inventory id
            insert_invent_id:
            $invent_id = rand_string(5);
            if ($db_handle->numRows("SELECT invent_id FROM facility_inventory WHERE invent_id = '$invent_id'") > 0) {
                goto insert_invent_id;
            };

            $invent_id = "IWNT" . $invent_id;

// SQL Query to insert data into DataBase
            $query = "INSERT INTO facility_inventory(invent_id,name,cost,date,location,category) VALUES('" . $invent_id . "','" . $col1 . "','" . $col2 . "','" . $col3 . "','" . $col4 . "','" . $col5 . "')";
            $result = $db_handle->runQuery($query);
            if ($result) {
                $message_success = "You have successfully added new equipments to the inventory";
            } else {
                $message_error = "Something went wrong. Please try again.";
            }
        }
        fclose($handle);
    }
}

$all_admin_member = $admin_object->get_all_admin_member();
if (isset($_POST['add']))
{
    invent_id:
    $invent_id = rand_string(5);
    if($db_handle->numRows("SELECT invent_id FROM facility_inventory WHERE invent_id = '$invent_id'") > 0) { goto invent_id; };

    $invent_id = "IWNT".$invent_id;
    $location = $db_handle->sanitizePost($_POST['location']);
    $cart = $db_handle->sanitizePost($_POST['cart']);
    $iname = $db_handle->sanitizePost($_POST['name']);
    $cost = $db_handle->sanitizePost(trim($_POST['cost']));
    $idate = $db_handle->sanitizePost($_POST['date']);
    $allowed_admin = ($_POST['allowed_admin']);
    for ($i = 0; $i < count($allowed_admin); $i++)
    {
        $all_allowed_admin = $all_allowed_admin . "," . $allowed_admin[$i];
    }

    $all_allowed_admin = substr_replace($all_allowed_admin, "", 0, 1);

        $new_user = $obj_facility->inventory($invent_id, $iname, $cost, $idate, $all_allowed_admin,$location,$cart);
        if($new_user) {
            $message_success = "You have successfully added a new equipment to this facility.";
        } else {
            $message_error = "Something went wrong. Please try again.";
        }

}

if(isset($_POST['process'])){
    $name = $db_handle->sanitizePost($_POST['name']);
    $details = ($_POST['details']);

    $query = "INSERT INTO facility_category (name, description) VALUES ('$name','$details')";

    $result2 =$db_handle->runQuery($query);
    if($result2) {
        $message_success = "You have successfully added a new facility category to the system";
    } else {
        $message_error = "Something went wrong. Please try again.";
    }
}
$query2 = "SELECT
facility_file.name AS name,
facility_file.path AS path
          FROM facility_file
          ORDER BY facility_file.created DESC ";
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
                            <h4><strong>FACILITY MANAGER</strong></h4>
                        </div>
                    </div>
                    <!-- /.row -->
                    <div class="row">
                        <div class="col-lg-3 col-md-6">
                            <div class="panel panel-primary">
                                <div class="panel-heading">
                                    <div class="row">
                                        <div class="col-xs-3">
                                            <i class="fa fa-institution fa-5x"></i>
                                        </div>
                                        <div class="col-xs-9 text-right">
                                            <div class="huge"><?php echo $equip; ?></div>
                                            <div>Total Number of Equipments!</div>
                                        </div>
                                    </div>
                                </div>
                                <a href="facility_equipments.php">
                                    <div class="panel-footer">
                                        <span class="pull-left">View Inventory</span>
                                        <span class="pull-right"><i class="fa fa-arrow-circle-right"></i></span>
                                        <div class="clearfix"></div>
                                    </div>
                                </a>
                            </div>
                        </div>
                        <div class="col-lg-3 col-md-6">
                            <div class="panel panel-success">
                                <div class="panel-heading">
                                    <div class="row">
                                        <div class="col-xs-3">
                                            <i class="fa fa-tasks fa-5x"></i>
                                        </div>
                                        <div class="col-xs-9 text-right">
                                            <div class="huge"><?php echo $preport; ?></div>
                                            <div>Total Pending Reports</div>
                                        </div>
                                    </div>
                                </div>
                                <a href="facility_reports.php">
                                    <div class="panel-footer">
                                        <span class="pull-left">View Details</span>
                                        <span class="pull-right"><i class="fa fa-arrow-circle-right"></i></span>
                                        <div class="clearfix"></div>
                                    </div>
                                </a>
                            </div>
                        </div>
                        <div class="col-lg-3 col-md-6">
                            <div class="panel panel-warning">
                                <div class="panel-heading">
                                    <div class="row">
                                        <div class="col-xs-3">
                                            <i class="fa fa-shopping-cart fa-5x"></i>
                                        </div>
                                        <div class="col-xs-9 text-right">
                                            <div class="huge"><?php echo $pwork; ?></div>
                                            <div>Pending Work Request!</div>
                                        </div>
                                    </div>
                                </div>
                                <a href="facility_work.php">
                                    <div class="panel-footer">
                                        <span class="pull-left">View Details</span>
                                        <span class="pull-right"><i class="fa fa-arrow-circle-right"></i></span>
                                        <div class="clearfix"></div>
                                    </div>
                                </a>
                            </div>
                        </div>
                        <div class="col-lg-3 col-md-6">
                            <div class="panel panel-danger">
                                <div class="panel-heading">
                                    <div class="row">
                                        <div class="col-xs-3">
                                            <i class="fa fa-support fa-5x"></i>
                                        </div>
                                        <div class="col-xs-9 text-right">
                                            <div class="huge"><?php echo $serv; ?></div>
                                            <div>Overdue For Servicing</div>
                                        </div>
                                    </div>
                                </div>
                                <a href="facility_servicing.php">
                                    <div class="panel-footer">
                                        <span class="pull-left">View Details</span>
                                        <span class="pull-right"><i class="fa fa-arrow-circle-right"></i></span>
                                        <div class="clearfix"></div>
                                    </div>
                                </a>
                            </div>
                        </div>
                    </div>
                    <!-- /.row -->
                    <hr style="border: thin dotted #c5c5c5" />
                    <div class="row">
                        <div class="col-lg-8">

                            <?php require_once 'layouts/feedback_message.php'; ?>

                            <div class="section-tint super-shadow">
                                <div class="row">
                                    <div class="col-sm-12">
                                        <h5><a href="facility_equipments.php" >

                                            </a>ADD NEW EQUIPMENT</h5>
                                        <form data-toggle="validator" class="form-horizontal" role="form" method="post" action="">
                                            <p>Kindly fill all Equipment details All fields are required</p>
                                            <div class="form-group">
                                                <label class="control-label col-sm-3" for="inventoryid">Name/Description:</label>
                                                <div class="col-sm-12 col-lg-8">
                                                    <div class="input-group">
                                                        <span class="input-group-addon"><i class="fa fa-user fa-fw"></i></span>
                                                        <input name="name" type="text" id="" value="" class="form-control" required/>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <label class="control-label col-sm-3" for="inventoryid">Cost:</label>
                                                <div class="col-sm-12 col-lg-8">
                                                    <div class="input-group">
                                                        <span class="input-group-addon">₦</span>
                                                        <input name="cost" type="number" id="" value="" class="form-control" required/>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <label class="control-label col-sm-3" for="from_date">Purchase Date:</label>
                                                <div class="col-sm-9 col-lg-5">
                                                    <div class="input-group date">
                                                        <input name="date" type="text" class="form-control" id="datetimepicker">
                                                        <span class="input-group-addon"><span class="glyphicon glyphicon-calendar"></span></span>
                                                    </div>
                                                </div>
                                                <script type="text/javascript">
                                                    $(function () {
                                                        $('#datetimepicker').datetimepicker({
                                                            format: 'YYYY-MM-DD'
                                                        });
                                                    });
                                                </script>
                                            </div>

                                            <div class="form-group">
                                                <label class="control-label col-sm-3" for="from_date">Category:</label>
                                                <div class="col-sm-12 col-lg-8">
                                                    <div class="input-group">
                                                        <span class="input-group-addon"><i class="fa fa-shopping-cart"></i></span>
                                                        <select  type="text" name="cart" class="form-control " id="location" >
                                                            <?php
                                                            $query = "SELECT * FROM facility_category ";
                                                            $result = $db_handle->runQuery($query);
                                                            $result = $db_handle->fetchAssoc($result);
                                                            foreach ($result as $row)
                                                            {
                                                                extract($row)
                                                                ?>
                                                                <option value="<?php echo $name;?>"><?php echo $name;?></option>
                                                            <?php } ?>
                                                        </select>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="form-group">
                                                <label class="control-label col-sm-3" for="from_date">Location:</label>
                                                <div class="col-sm-12 col-lg-8">
                                            <div class="input-group">
                                                <span class="input-group-addon"><i class="fa fa-map-marker "></i></span>
                                                <select  type="text" name="location" class="form-control " id="location" >
                                                    <?php
                                                    $query = "SELECT * FROM facility_location";
                                                    $result = $db_handle->runQuery($query);
                                                    $result = $db_handle->fetchAssoc($result);
                                                    foreach ($result as $row)
                                                    {
                                                        extract($row)
                                                        ?>
                                                        <option value="<?php echo $location_id;?>"><?php echo $location;?></option>
                                                    <?php } ?>
                                                </select>
                                            </div>
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <label class="control-label col-sm-2" for="allowed_admin">Administrator In Charge:</label>
                                                <div class="col-sm-10">
                                                    <?php foreach($all_admin_member AS $key) { ?>
                                                        <div class="col-sm-5"><div class="checkbox"><label for=""><input type="checkbox" name="allowed_admin[]" value="<?php echo $key['admin_code']; ?>" <?php if (in_array($key['admin_code'], $allowed_admin)) { echo 'checked="checked"'; } ?>/> <?php echo $key['full_name']; ?></label></div></div>
                                                    <?php } ?>
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <div class="col-sm-offset-3 col-sm-9">
                                                    <input name="add" type="submit" class="btn btn-success" value="ADD">                                                </div>
                                            </div>
                                        </form>

                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <div class="col-lg-4">

                            <div class="section-tint super-shadow">
                                <h5>Create New Category</h5>
                                <?php require_once 'layouts/feedback_message.php'; ?>
                                <hr/>
                                <p>Add a new equipment category</p>
                                <form data-toggle="validator" class="form-horizontal" role="form" method="post" action="">
                                    <div class="form-group">
                                        <label class="control-label col-sm-3" for="inventoryid">Category name</label>
                                        <div class="col-sm-12 col-lg-8">
                                            <div class="input-group">
                                                <span class="input-group-addon"><i class="fa fa-institution fa-fw"></i></span>
                                                <input name="name" type="text" id="" value="" class="form-control" required/>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="control-label col-sm-3" for="comment">Details:</label>
                                        <div class="col-sm-12 col-lg-8">
                                            <div class="input-group">
                                        <textarea name="details" class="form-control" rows="3" id="comment"></textarea>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <div class="col-sm-offset-3 col-sm-9">
                                            <button type="button" data-target="#confirm-add-admin" data-toggle="modal" class="btn btn-success">Continue</button>
                                        </div>
                                    </div>

                                    <!--Modal - confirmation boxes-->
                                    <div id="confirm-add-admin" tabindex="-1" role="dialog" aria-hidden="true" class="modal fade">
                                        <div class="modal-dialog">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <button type="button" data-dismiss="modal" aria-hidden="true"
                                                            class="close">&times;</button>
                                                    <h4 class="modal-title">Create New Category</h4></div>
                                                <div class="modal-body">Are you sure you want to create a new Category</div>
                                                <div class="modal-footer">
                                                    <input name="process" type="submit" class="btn btn-success" value="Proceed">
                                                    <button type="submit" name="close" onClick="window.close();" data-dismiss="modal" class="btn btn-danger">Close!</button>
                                                </div>
                                            </div>
                                        </div>
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

                            <div  class="section-tint super-shadow">
                                <h5>Upload Excel Into Inventory</h5>
                                <hr/>

                                <form data-toggle="validator" class="form-vertical dropzone" role="form" method="post" action="" enctype="multipart/form-data">
                                    <div class="form-group">
                                        <label for="exampleInputFile">Select File</label>
                                        <input name="Filename" type="file" class="form-control-file" id="exampleInputFile" aria-describedby="fileHelp">
                                        <small id="fileHelp" class="form-text text-muted">Select .csv file from your document</small>
                                    </div>

                                    <div class="form-group">
                                       <center><button type="submit" name="upload"  class="btn btn-success"><i class="fa fa-upload fa-fw"></i>Insert Into Inventory</button></center>
                                    </div>
                                </form>
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