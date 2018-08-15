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
$date1 = $date1->modify('-1 day');
$date2=date_create($to_date);
$date2 = $date2->modify('-1 day');
$diff=date_diff($date1,$date2);
$diff = $diff->format("%R%a days");

$new_from_date = date_create($from_date);
date_sub($new_from_date, date_interval_create_from_date_string($diff));
$new_from_date = date_format($new_from_date, 'Y-m-d');
$new_to_date = date_format($date1, 'Y-m-d');

$query = "SELECT tc.ifx_acct_no, SUM(tc.commission) AS total_commissions, tc.volume, tc.date_earned FROM trading_commission AS tc
INNER JOIN user_ifxaccount AS ui ON ui.ifx_acct_no = tc.ifx_acct_no
WHERE (STR_TO_DATE(tc.date_earned, '%Y-%m-%d') BETWEEN '$new_from_date' AND '$new_to_date') AND tc.ifx_acct_no IN
(SELECT tc.ifx_acct_no FROM trading_commission AS tc
INNER JOIN user_ifxaccount AS ui ON tc.ifx_acct_no = ui.ifx_acct_no
WHERE (STR_TO_DATE(tc.date_earned, '%Y-%m-%d') BETWEEN '$from_date' AND '$to_date'))";

$numrows = $db_handle->numRows($query);
$query2 = "SELECT * FROM trading_commission WHERE (STR_TO_DATE(date_earned, '%Y-%m-%d') BETWEEN '$new_from_date' AND '$to_date')";
$numrows2 = $db_handle->numRows($query2);
$rate = ($numrows/$numrows) * 100;
$result = $db_handle->runQuery($query);
$all_retentions = $db_handle->fetchAssoc($result);
foreach($all_retentions AS $row){extract ($row);}
$total_commissions = $total_commissions;

$query_new = "SELECT tc.ifx_acct_no, SUM(tc.commission) AS total_commissions_new, tc.volume, tc.date_earned FROM trading_commission AS tc
INNER JOIN user_ifxaccount AS ui ON tc.ifx_acct_no = ui.ifx_acct_no
WHERE (STR_TO_DATE(tc.date_earned, '%Y-%m-%d') BETWEEN 'from_date' AND '$to_date') AND tc.ifx_acct_no NOT IN
(SELECT tc.ifx_acct_no FROM trading_commission AS tc
INNER JOIN user_ifxaccount AS ui ON tc.ifx_acct_no = ui.ifx_acct_no
WHERE (STR_TO_DATE(tc.date_earned, '%Y-%m-%d') BETWEEN '$new_from_date' AND '$new_to_date'))";

$numrows_new = $db_handle->numRows($query_new);
$result_new = $db_handle->runQuery($query_new);
$all_new = $db_handle->fetchAssoc($result_new);
foreach($all_new AS $row){extract ($row);}
$total_commissions_new = $total_commissions_new;
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
                                                <table class="table table-bordered">
                                                    <tbody>
                                                    <tr>
                                                        <th scope="row">Total Number of Retained Accounts</th>
                                                        <td><?php echo $numrows?></td>
                                                    </tr>
                                                    <tr>
                                                        <th scope="row">Retention rate</th>
                                                        <td><?php echo $rate?>%</td>
                                                    </tr>
                                                    <tr>
                                                        <th scope="row">Sum of Commisions</th>
                                                        <td colspan="2"><?php echo $total_commissions?></td>
                                                    </tr>
                                                    </tbody>
                                                </table>
                                            </div>
                                            <div id="latest_withdrawal" class="tab-pane fade">
                                                <table class="table table-bordered">
                                                    <tbody>
                                                    <tr>
                                                        <th scope="row">Total Number of New Accounts</th>
                                                        <td><?php echo $numrows_new?></td>
                                                    </tr>
                                                    <tr>
                                                        <th scope="row">Sum of Commisions</th>
                                                        <td colspan="2"><?php echo $total_commissions_new?></td>
                                                    </tr>
                                                    </tbody>
                                                </table>
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