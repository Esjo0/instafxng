<?php
require_once("../init/initialize_admin.php");
if (!$session_admin->is_logged_in()) {
    redirect_to("login.php");
}

if(isset($_POST['process_delete']))
{
    if($obj_customer_care_log->log_delete($_POST['log_id']))
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
    if($obj_customer_care_log->log_treated($_POST['log_id']))
    {
        $message_success = "You have successfully treated a log.";
    }
    else
    {
        $message_error = "Looks like something went wrong or you didn't make any change.";
    }
}
$admin_code = $_SESSION["admin_unique_code"];


if(isset($_POST['filter_report']))
{
    $query = "SELECT * FROM customer_care_log WHERE ";
    extract($_POST);
    if((isset($from_date) && !empty($from_date)) && (isset($to_date) && !empty($to_date)))
    {
        $query = $query."(STR_TO_DATE(customer_care_log.created, '%Y-%m-%d') BETWEEN '$from_date' AND '$to_date')";
    }

    if((isset($prospect) && !empty($prospect)))
    {
        $query = $query." AND log_type = '$prospect'";
    }
    if((isset($client) && !empty($client)))
    {
        $query = $query." AND log_type = '$client'";
    }
    if((isset($log_status_resolved) && !empty($log_status_resolved)))
    {
        $query = $query." AND log_type = '$log_status_resolved'";
    }
    if((isset($log_status_unresolved) && !empty($log_status_unresolved)))
    {
        $query = $query." AND log_type = '$log_status_unresolved'";
    }
    if((isset($p_s) && !empty($p_s)))
    {
        for ($i = 0; $i < count($p_s); $i++)
        {
            $totpageids = $totpageids . "," . "'" .$p_s[$i]."'";
        }
        $p_s_selected = substr_replace($totpageids, "", 0, 1);
        $query = $query." AND prospect_source IN ($p_s_selected)";
    }
    if((isset($personnel) && !empty($personnel)))
    {
        for ($i = 0; $i < count($personnel); $i++)
        {
            $totpageid = $totpageid. "," . "'".$personnel[$i]."'";
        }
        $p_selected = substr_replace($totpageid, "", 0, 1);
        $query = $query." AND admin_code IN ($p_selected)";
    }
}
else{    $query = "SELECT * FROM customer_care_log ORDER BY log_id DESC  "; }


$numrows = $db_handle->numRows($query);
$rowsperpage = 20;

$totalpages = ceil($numrows / $rowsperpage);

if (isset($_GET['pg']) && is_numeric($_GET['pg'])) {    $currentpage = (int) $_GET['pg'];} else {    $currentpage = 1;}
if ($currentpage > $totalpages) { $currentpage = $totalpages; }
if ($currentpage < 1) { $currentpage = 1; }

$prespagelow = $currentpage * $rowsperpage - $rowsperpage + 1;
$prespagehigh = $currentpage * $rowsperpage;
if($prespagehigh > $numrows) { $prespagehigh = $numrows; }

$offset = ($currentpage - 1) * $rowsperpage;
$query .= 'LIMIT ' . $offset . ',' . $rowsperpage;
$result = $db_handle->runQuery($query);
$admin_all_logs = $db_handle->fetchAssoc($result);



$all_prospect_source = $admin_object->get_all_prospect_source();
if(empty($all_prospect_source)) {    $message_error = "You cannot add a prospect until you have added at least one prospect source <a href='prospect_source.php'>here</a>.";}

$personnel = $db_handle->fetchAssoc($db_handle->runQuery("SELECT * FROM admin ORDER BY admin_id DESC "));

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
    <script>
        function show_form(div)
        {
            var x = document.getElementById(div);
            if (x.style.display === 'none')
            {
                x.style.display = 'block';
                document.getElementById('trigger').innerHTML = '<i class="glyphicon glyphicon-arrow-up"></i>';
            }
            else
            {
                x.style.display = 'none';
                document.getElementById('trigger').innerHTML = '<i class="glyphicon glyphicon-arrow-down"></i>';
            }
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
                    <h4><strong>LOGS - ALL LOGGED IN INTERACTIONS</strong></h4>
                </div>
            </div>



            <div class="section-tint super-shadow">
                <p>Filter the interaction logs using the form below. <button id="trigger" onclick="show_form('filter')" class="btn btn-sm btn-default"><i class="glyphicon glyphicon-arrow-down"></i></button></p>
                <form style="display: none" id="filter" data-toggle="validator" class="form-horizontal" role="form" method="post" action="<?php echo $_SERVER['REQUEST_URI']; ?>">
                    <div class="form-group">
                        <label class="control-label col-sm-3" for="from_date">From:</label>
                        <div class="col-sm-9 col-lg-5">
                            <div class="input-group date">
                                <input name="from_date" type="text" class="form-control" id="datetimepicker" required>
                                <span class="input-group-addon"><span class="glyphicon glyphicon-calendar"></span></span>
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="control-label col-sm-3" for="to_date">To:</label>
                        <div class="col-sm-9 col-lg-5">
                            <div class="input-group date">
                                <input name="to_date" type="text" class="form-control" id="datetimepicker2" required>
                                <span class="input-group-addon"><span class="glyphicon glyphicon-calendar"></span></span>
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="control-label col-sm-3" for="location">Other Options:</label>
                        <div class="col-sm-9 col-lg-5">
                            <div class="form-group row">
                                <div class="col-sm-6">
                                    <div class="checkbox">
                                        <label for="">
                                            <input type="checkbox" name="prospect" value="1" id="" /> Prospect</label>
                                    </div>
                                </div>

                                <div class="col-sm-6">
                                    <div class="checkbox">
                                        <label for="">
                                            <input type="checkbox" name="client" value="2" id="" /> Client</label>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group row">
                                <div class="col-sm-6">
                                    <div class="checkbox">
                                        <label for="">
                                            <input type="checkbox" name="log_status_resolved" value="2" id="" /> Resolved</label>
                                    </div>
                                </div>

                                <div class="col-sm-6">
                                    <div class="checkbox">
                                        <label for="">
                                            <input type="checkbox" name="log_status_unresolved" value="1" id="" /> Unresolved</label>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group row">
                                <?php foreach($all_prospect_source as $key => $value) { ?>
                                <div class="col-sm-6">
                                    <div class="checkbox">
                                        <label for="">
                                            <input type="checkbox" name="p_s[]" value="<?php echo $value['prospect_source_id']; ?>"/> Prospect Source(<?php echo $value['source_name']; ?>)</label>
                                    </div>
                                </div>
                                <?php } ?>

                            </div>
                            <div class="form-group row">
                                <?php foreach($personnel as $key => $value) { ?>
                                    <div class="col-sm-6">
                                        <div class="checkbox">
                                            <label for="">
                                                <input type="checkbox" name="personnel[]" value="<?php echo $value['admin_code']; ?>"/> Personnel(<?php echo $value['last_name'] . ' ' . $value['middle_name'] . ' ' . $value['first_name']; ?>)</label>
                                        </div>
                                    </div>
                                <?php } ?>
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="col-sm-offset-3 col-sm-9"><input name="filter_report" type="submit" class="btn btn-success" value="Search" /></div>
                    </div>
                    <script type="text/javascript">
                        $(function () {
                            $('#datetimepicker, #datetimepicker2').datetimepicker({
                                format: 'YYYY-MM-DD'
                            });
                        });
                    </script>
                </form>

                <hr>

                <!--<div class="row">
                    <div class="col-sm-12">
                        <p>All interaction logs that have been added on this platform, can be viewed below.</p>
                    </div>
                </div>-->

                <div class="row">
                    <div class="col-sm-12">
                        <table class="table table-responsive table-striped table-bordered table-hover">
                            <thead>
                            <tr>
                                <th>Client/Customer's Details</th>
                                <th>Last Conversation Description</th>
                                <th>All Conversations</th>
                                <th>Mark As Treated</th>
                            </tr>
                            </thead>
                            <tbody>
                            <?php if(isset($admin_all_logs) && !empty($admin_all_logs)) { foreach ($admin_all_logs as $row){?>

                                <tr>
                                    <td colspan="4">Personnel: <?php echo $admin_object->get_admin_name_by_code($row['admin_code']); ?></td>
                                </tr>
                                <tr>
                                    <td>
                                        <?php if($row['log_type'] == '1')
                                        {
                                            $client_details = $obj_customer_care_log->client_details($row['tag']);
                                            $client_details = $client_details[0];
                                            echo "<strong>Full Name: </strong>".$client_details['first_name']." ".$client_details['middle_name']." ".$client_details['last_name']."<br/>";
                                            echo "<strong>Phone : </strong>".$client_details['phone']."<br/>";
                                            echo "<strong>Email : </strong>".$client_details['email']."<br/>";
                                            echo "<strong>Account Number : </strong>".$row['ifx_acc_no']."<br/>";
                                        }
                                        elseif ($row['log_type'] == '2')
                                        {
                                            $customer_details = $obj_customer_care_log->customer_details($row['tag']);
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
                                            <div class="modal-dialog modal-lg">
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
                                                            </tr>
                                                            </thead>
                                                            <tbody>
                                                            <?php if($row['log_type'] == '1')
                                                            {
                                                                $client_details = $obj_customer_care_log->client_details($row['tag']);
                                                                $client_details = $client_details[0];
                                                                echo "<strong>Full Name: </strong>".$client_details['first_name']." ".$client_details['middle_name']." ".$client_details['last_name']."<br/>";
                                                                echo "<strong>Phone : </strong>".$client_details['phone_number']."<br/>";
                                                                echo "<strong>Email : </strong>".$client_details['email_address']."<br/>";
                                                                echo "<strong>Account Number : </strong>".$row['ifx_acc_no']."<br/>";
                                                            }
                                                            elseif ($row['log_type'] == '2')
                                                            {
                                                                $customer_details = $obj_customer_care_log->customer_details($row['tag']);
                                                                $customer_details = $customer_details[0];
                                                                echo "<strong>Full Name: </strong>".$customer_details['first_name']." ".$customer_details['other_name']." ".$customer_details['last_name']."<br/>";
                                                                echo "<strong>Phone : </strong>".$customer_details['phone_number']."<br/>";
                                                                echo "<strong>Email : </strong>".$customer_details['email_address']."<br/>";
                                                            }
                                                            ?>
                                                            <?php
                                                            $all_conversations = $obj_customer_care_log->get_all_conversations($row['tag']);
                                                            if(isset($all_conversations) && !empty($all_conversations))  { foreach ($all_conversations as $row1) {
                                                                ?>
                                                                <tr>
                                                                    <td>
                                                                        <?php echo $row1['con_desc'] ?>
                                                                    </td>
                                                                    <td>
                                                                        <?php
                                                                        if($row1['status'] == '1')
                                                                        {
                                                                            echo 'PENDING';
                                                                        }
                                                                        if($row1['status'] == '2')
                                                                            :?>
                                                                            TREATED <br/> <?php echo datetime_to_text($row1['updated']);?>
                                                                        <?php endif; ?>
                                                                    </td>
                                                                    <td>
                                                                        <?php echo datetime_to_text($row1['created']); ?>
                                                                    </td>
                                                                    <td>
                                                                        <?php
                                                                        if($row1['status'] == '1'):?>
                                                                        <form data-toggle="validator" class="form-horizontal" role="form" method="post" action="">
                                                                            <input type="hidden" name="log_id" value="<?php echo $row1['log_id']?>">
                                                                            <button name="process_treated" type="submit" class="btn btn-success btn-sm"><i class="glyphicon glyphicon-check"></i></button>
                                                                        </form>
                                                                            <?php endif;?>
                                                                    </td>
                                                                </tr>
                                                            <?php } } else { echo "<tr><td colspan='5' class='text-danger'><em>No results to display</em></td></tr>"; } ?>
                                                            </tbody>
                                                        </table>
                                                    </div>
                                                    <div class="modal-footer">
                                                        <button type="submit" name="close" onClick="window.close();" data-dismiss="modal" class="btn btn-danger btn-sm">Close!</button>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                <?php if($row['status'] == '2'):?>
                                    TREATED <br/> <?php echo datetime_to_text2($row['updated']);
                                endif;
                                if($row['status'] == '1'):?>
                                        <form data-toggle="validator" class="form-horizontal" role="form" method="post" action="">
                                            <input type="hidden" name="log_id" value="<?php echo $row['log_id']?>">
                                            <button name="process_treated" type="submit" class="btn btn-success btn-sm"><i class="glyphicon glyphicon-check"></i></button>
                                        </form>
                                    <?php endif; ?>
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
<script src="//cdnjs.cloudflare.com/ajax/libs/moment.js/2.9.0/moment-with-locales.js"></script>
<script src="//cdn.rawgit.com/Eonasdan/bootstrap-datetimepicker/e8bddc60e73c1ec2475f827be36e1957af72e2ea/src/js/bootstrap-datetimepicker.js"></script>
</body>
</html>