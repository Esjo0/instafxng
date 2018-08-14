<?php
require_once("../init/initialize_admin.php");
if (!$session_admin->is_logged_in()) {
    redirect_to("login.php");
}
if (isset($_POST['signal_report'])){
    foreach($_POST as $key => $value) {$_POST[$key] = $db_handle->sanitizePost(trim($value));}
    $from_date = $_POST['from_date'];
    $to_date = $_POST['to_date'];
}else{
    $from_date = date('Y-m')."-1";
    $to_date = date('Y-m-d');
}

$date1=date_create($from_date);
$date2=date_create($to_date);
$diff=date_diff($date1,$date2);
$diff = $diff->format("%R%a days");
echo $diff;
echo $from_date;
$from_date = date_create($from_date);
date_sub($from_date, date_interval_create_from_date_string($diff));
$new_from_date = date_format($from_date, 'Y-m-d');

$query = "SELECT ifx_account_no, commissions, volume FROM trading_commissions AS tc
INNER JOIN user AS u.ifx_account_no = tc.ifx_account_no
WHERE (STR_TO_DATE(date_earned, '%Y-%m-%d') BETWEEN '$from_date' AND '$to_date')  ";


$numrows = $db_handle->numRows($query);
//$total_untriggered = $numrows - $total_triggered;
$rowsperpage = 10;
$totalpages = ceil($numrows / $rowsperpage);
if (isset($_GET['pg']) && is_numeric($_GET['pg'])) {    $currentpage = (int) $_GET['pg'];} else {    $currentpage = 1;}
if ($currentpage > $totalpages) { $currentpage = $totalpages; }
if ($currentpage < 1) { $currentpage = 1; }
$prespagelow = $currentpage * $rowsperpage - $rowsperpage + 1;
$prespagehigh = $currentpage * $rowsperpage;
if($prespagehigh > $numrows) { $prespagehigh = $numrows; }
$offset = ($currentpage - 1) * $rowsperpage;
$query .= ' ORDER BY created DESC LIMIT ' . $offset . ',' . $rowsperpage;
$result = $db_handle->runQuery($query);
$all_retentions = $db_handle->fetchAssoc($result);
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
        <?php  require_once 'layouts/head_meta.php'; ?>
        <?php require_once 'hr_attendance_system.php'; ?>

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
                            <h4><strong>CLIENT RETENTION AND NEW CLIENT ON BOARDING REPORT</strong></h4>
                        </div>
                    </div>

                    <hr style="border: thin dotted #c5c5c5" />
                                        
                    <div class="row">
                        <div class="col-lg-12">
                                                        
                            <div class="section-tint super-shadow">
                                <div class="row">
                                    <div class="col-md-12">
                                        <form data-toggle="validator" class="form-horizontal" role="form" method="post" action="<?php echo $_SERVER['REQUEST_URI']; ?>">
                                            <div class="modal-dialog modal-md">
                                                <div class="modal-content">
                                                    <div class="modal-body">
                                                        <p>Select the range you want to compare</p>

                                                        <div class="input-group date">
                                                            <input placeholder="Select start date" name="from_date" type="text" class="form-control" id="datetimepicker" required>
                                                            <span class="input-group-addon"><span class="glyphicon glyphicon-calendar"></span></span>
                                                        </div>

                                                        <br/>

                                                        <div class="input-group date">
                                                            <input placeholder="Select end date" name="to_date" type="text" class="form-control" id="datetimepicker2" required>
                                                            <span class="input-group-addon"><span class="glyphicon glyphicon-calendar"></span></span>
                                                        </div>
                                                        <script type="text/javascript">
                                                            $(function () {$('#datetimepicker, #datetimepicker2').datetimepicker({format: 'YYYY-MM-DD'});});
                                                        </script>
                                                    </div>
                                                    <div class="modal-footer">
                                                        <input name="signal_report" type="submit" class="btn btn-sm btn-success" value="Proceed">
                                                        <button type="button" name="close" onClick="window.close();" data-dismiss="modal" class="btn btn-sm btn-danger">Close!</button>
                                                    </div>
                                                </div>
                                            </div>
                                        </form>
                                    </div>
                                    <div class="col-sm-12">
                                        <h5></h5>
                                        <ul class="nav nav-tabs">
                                            <li class="active"><a data-toggle="tab" href="#latest_funding">Retention</a></li>
                                            <li><a data-toggle="tab" href="#latest_withdrawal">On Boarding</a></li>
                                        </ul>
                                        <div class="tab-content">
                                            <div id="latest_funding" class="tab-pane fade in active">

                                            </div>
                                            <div id="latest_withdrawal" class="tab-pane fade">

                                            </div>
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
        </div>
        <?php require_once 'layouts/footer.php'; ?>
    </body>
</html>