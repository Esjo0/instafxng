<?php
require_once("../init/initialize_admin.php");
if (!$session_admin->is_logged_in()) {redirect_to("login.php");}

if(isset($_POST['from_date']) && !empty($_POST['from_date'])){ $_SESSION['from_date'] = $_POST['from_date'];}
if(isset($_POST['to_date']) && !empty($_POST['to_date'])){ $_SESSION['to_date'] = $_POST['to_date'];}

if(empty($_SESSION['to_date'])){$_SESSION['to_date'] = date('Y-m-d');}
if(empty($_SESSION['from_date'])){$_SESSION['from_date'] = date('Y-m')."-1";}

$to =  $_SESSION['to_date'];
$from = $_SESSION['from_date'];

$_dates = date_range($from, $to, 'Y-m-d');
krsort($_dates);
$numrows = count($_dates);

$rowsperpage = 20;

$totalpages = ceil($numrows / $rowsperpage);
// get the current page or set a default
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

$log_of_dates = paginate_array($offset, $_dates, $rowsperpage);
krsort($log_of_dates);

$client_operation = new clientOperation();
?>
<!DOCTYPE html>
<html lang="en">
    <head>
        <base target="_self">
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>Instaforex Nigeria | Admin - Campaign Report</title>
        <meta name="title" content="Instaforex Nigeria | Admin - Campaign Report" />
        <meta name="keywords" content="" />
        <meta name="description" content="" />
        <?php require_once 'layouts/head_meta.php'; ?>
        <script>
            function show_form(div) {
                var x = document.getElementById(div);
                if (x.style.display === 'none'){
                    x.style.display = 'block';
                    document.getElementById('trigger').innerHTML = '<i class="glyphicon glyphicon-arrow-up"></i>';}
                else{
                    x.style.display = 'none';
                    document.getElementById('trigger').innerHTML = '<i class="glyphicon glyphicon-arrow-down"></i>';}}
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
                            <h4><strong>CAMPAIGN REPORTS</strong></h4>
                        </div>
                    </div>
                    <div class="section-tint super-shadow">
                        <div class="row">
                            <div class="col-sm-12">
                                <p>Leads overview, from <?php echo date_to_text($from)?> to <?php echo date_to_text($to)?>. <button title="Apply Filter" id="trigger" onclick="show_form('filter')" class="btn btn-sm btn-default"><i class="glyphicon glyphicon-arrow-down"></i></button></p>
                                <div style="display: none;" id="filter">
                                    <p>Fetch campaign reports within a date range using the form below.</p>
                                    <form data-toggle="validator" class="form-horizontal" role="form" method="post" action="<?php echo $_SERVER['REQUEST_URI']; ?>">
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
                                        <div class="col-sm-offset-3 col-sm-9"><input name="report" type="submit" class="btn btn-success" value="Search" /></div>
                                    </div>
                                    <script type="text/javascript">$(function () {$('#datetimepicker, #datetimepicker2').datetimepicker({format: 'YYYY-MM-DD'});});</script>
                                </form>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-sm-6">
                                <table class="table table-responsive table-striped table-hover">
                                    <tbody>
                                        <tr><td><b>Total Number Of Days </b><br/><span class="text-muted" style="font-size: smaller">Total number of days within the selected date range.</span></td><td><?php echo $numrows?></td></tr>
                                        <tr>
                                            <td>
                                                <a href="javascript:void(0);" data-target="#lc_<?php echo $from.$to;?>" data-toggle="modal"><b>Total Leads Count</b><br/>
                                                <span class="text-muted" style="font-size: smaller">Total number of people that were added to the pool within the selected date range.</span></a>
                                                <div id="lc_<?php echo $from.$to;?>" tabindex="-1" role="dialog" aria-hidden="true" class="modal fade">
                                                    <div class="modal-dialog modal-lg">
                                                        <div class="modal-content">
                                                            <div class="modal-header">
                                                                <button type="button" data-dismiss="modal" aria-hidden="true"  class="close">&times;</button>
                                                                <h4 class="modal-title">New Leads (<?php echo date_to_text($from); ?> -> <?php echo date_to_text($to); ?>)</h4></div>
                                                            <div class="modal-body">
                                                                <div style="width: 100%; word-break:break-all; max-height: 550px; overflow-y: scroll; overflow-x: hidden">
                                                                    <div class="row">
                                                                        <div class="col-sm-12">
                                                                            <table class="table table-responsive table-striped table-bordered table-hover">
                                                                                <thead><tr><th></th><th>Name</th><th>Phone</th><th>Email</th><th>Joined</th></tr></thead>
                                                                                <tbody>
                                                                                <?php $leads_generated = $obj_loyalty_training->sum_leads_generated($from, $to, 2);
                                                                                if(isset($leads_generated) && !empty($leads_generated))
                                                                                {
                                                                                    $count = 1;
                                                                                    foreach ($leads_generated as $key)
                                                                                    {?>
                                                                                        <tr>
                                                                                            <td><?php echo $count; $count++;?></td>
                                                                                            <td><?php echo $key['f_name']." ".strtoupper($key['l_name']);?></td>
                                                                                            <td><?php echo $key['phone'];?></td>
                                                                                            <td><?php echo $key['email'];?></td>
                                                                                            <td><?php echo datetime_to_text($key['created']);?></td>
                                                                                        </tr>
                                                                                    <?php }
                                                                                }
                                                                                ?>
                                                                                </tbody>
                                                                            </table>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <div class="modal-footer">
                                                                <button type="button" name="close" onClick="window.close();" data-dismiss="modal" class="btn btn-danger">Close!</button>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </td>
                                            <td><?php echo $obj_loyalty_training->sum_leads_generated($from, $to, 1); ?></td>
                                        </tr>
                                        <tr>
                                            <td>
                                                <a href="javascript:void(0);" data-target="#c_<?php echo $from.$to;?>" data-toggle="modal"><b>Conversions</b><br/>
                                                <span class="text-muted" style="font-size: smaller">Total number of people that were added to the pool and opened accounts.</span></a>
                                                <div id="c_<?php echo $from.$to;?>" tabindex="-1" role="dialog" aria-hidden="true" class="modal fade">
                                                    <div class="modal-dialog modal-lg">
                                                        <div class="modal-content">
                                                            <div class="modal-header">
                                                                <button type="button" data-dismiss="modal" aria-hidden="true"  class="close">&times;</button>
                                                                <h4 class="modal-title">Conversions (<?php echo date_to_text($from); ?> -> <?php echo date_to_text($to); ?>)</h4>
                                                            </div>
                                                            <div class="modal-body">
                                                                <div style="width: 100%; word-break:break-all; max-height: 550px; overflow-y: scroll; overflow-x: hidden">
                                                                    <div class="row">
                                                                        <div class="col-sm-12">
                                                                            <table class="table table-responsive table-striped table-bordered table-hover">
                                                                                <thead><tr><th></th><th>Name</th><th>Phone</th><th>Email</th><th>Joined</th><th>Account Number</th></tr></thead>
                                                                                <tbody>
                                                                                <?php $leads_generated = $obj_loyalty_training->sum_leads_with_accounts($from, $to, 2);
                                                                                if(isset($leads_generated) && !empty($leads_generated))
                                                                                {
                                                                                    $count = 1;
                                                                                    foreach ($leads_generated as $key)
                                                                                    {?>
                                                                                        <tr>
                                                                                            <td><?php echo $count; $count++;?></td>
                                                                                            <td><?php echo $key['f_name']." ".strtoupper($key['l_name']);?></td>
                                                                                            <td><?php echo $key['phone'];?></td>
                                                                                            <td><?php echo $key['email'];?></td>
                                                                                            <td><?php echo datetime_to_text($key['created']);?></td>
                                                                                            <td>
                                                                                                <?php
                                                                                                $client_ilpr_account = $client_operation->get_client_ilpr_accounts_by_code($key['user_code']);
                                                                                                $client_non_ilpr_account = $client_operation->get_client_non_ilpr_accounts_by_code($key['user_code']);
                                                                                                if(isset($client_ilpr_account) && !empty($client_ilpr_account)) {foreach ($client_ilpr_account as $key1){echo $key1['ifx_acct_no']."(ILPR), ";}}
                                                                                                if(isset($client_non_ilpr_account) && !empty($client_non_ilpr_account)){foreach ($client_non_ilpr_account as $key1){echo $key1['ifx_acct_no']."(None-ILPR), ";}}
                                                                                                ?>
                                                                                            </td>
                                                                                        </tr>
                                                                                    <?php }
                                                                                }
                                                                                ?>
                                                                                </tbody>
                                                                            </table>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <div class="modal-footer">
                                                                <button type="button" name="close" onClick="window.close();" data-dismiss="modal" class="btn btn-danger">Close!</button>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </td>
                                            <td><?php echo $obj_loyalty_training->sum_leads_with_accounts($from, $to, 1);?></td>
                                        </tr>
                                        <tr>
                                            <td>
                                                <a href="javascript:void(0);" data-target="#funded_<?php echo $from.$to;?>" data-toggle="modal"><b>Leads Funded</b><br/>
                                                <span class="text-muted" style="font-size: smaller">Total number of people that were added to the pool within the selected time frame and also funded within the selected time frame.</span></a>
                                                <div id="funded_<?php echo $from.$to;?>" tabindex="-1" role="dialog" aria-hidden="true" class="modal fade">
                                                    <div class="modal-dialog modal-lg">
                                                        <div class="modal-content">
                                                            <div class="modal-header">
                                                                <button type="button" data-dismiss="modal" aria-hidden="true"  class="close">&times;</button>
                                                                <h4 class="modal-title">Funded (<?php echo date_to_text($from); ?> -> <?php echo date_to_text($to); ?>)</h4>
                                                            </div>
                                                            <div class="modal-body">
                                                                <div style="width: 100%; word-break:break-all; max-height: 550px; overflow-y: scroll; overflow-x: hidden">
                                                                    <div class="row">
                                                                        <div class="col-sm-12">
                                                                            <table class="table table-responsive table-striped table-bordered table-hover">
                                                                                <thead><tr><th></th><th>Name</th><th>Phone</th><th>Email</th><th>Joined</th><th>Total Amount</th></tr></thead>
                                                                                <tbody>
                                                                                <?php $leads_generated = $obj_loyalty_training->sum_leads_funded($from, $to, 2);
                                                                                if(isset($leads_generated) && !empty($leads_generated))
                                                                                {
                                                                                    $count = 1;
                                                                                    foreach ($leads_generated as $key)
                                                                                    {?>
                                                                                        <tr>
                                                                                            <td><?php echo $count; $count++;?></td>
                                                                                            <td><?php echo $key['f_name']." ".strtoupper($key['l_name']);?></td>
                                                                                            <td><?php echo $key['phone'];?></td>
                                                                                            <td><?php echo $key['email'];?></td>
                                                                                            <td><?php echo datetime_to_text($key['created']);?></td>
                                                                                            <td><?php echo $key['dollar_amount'];?></td>
                                                                                        </tr>
                                                                                    <?php }
                                                                                }
                                                                                ?>
                                                                                </tbody>
                                                                            </table>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <div class="modal-footer">
                                                                <button type="button" name="close" onClick="window.close();" data-dismiss="modal" class="btn btn-danger">Close!</button>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </td>
                                            <td><?php echo $obj_loyalty_training->sum_leads_funded($from, $to, 1);?></td>
                                        </tr>
                                        <tr>
                                            <td>
                                                <a href="javascript:void(0);" data-target="#funded_<?php echo date('Y');?>" data-toggle="modal"><b>Leads Funded (<?php echo date('Y');?>)</b><br/>
                                                    <span class="text-muted" style="font-size: smaller">Total number of people that were added to the pool this year and also funded within the stated year.</span></a>
                                                <div id="funded_<?php echo date('Y');?>" tabindex="-1" role="dialog" aria-hidden="true" class="modal fade">
                                                    <div class="modal-dialog modal-lg">
                                                        <div class="modal-content">
                                                            <div class="modal-header">
                                                                <button type="button" data-dismiss="modal" aria-hidden="true"  class="close">&times;</button>
                                                                <h4 class="modal-title">Funded (<?php echo date_to_text('01-01-'.date('Y')); ?> -> <?php echo date_to_text(date('Y-m-d')); ?>)</h4>
                                                            </div>
                                                            <div class="modal-body">
                                                                <div style="width: 100%; word-break:break-all; max-height: 550px; overflow-y: scroll; overflow-x: hidden">
                                                                    <div class="row">
                                                                        <div class="col-sm-12">
                                                                            <table class="table table-responsive table-striped table-bordered table-hover">
                                                                                <thead><tr><th></th><th>Name</th><th>Phone</th><th>Email</th><th>Joined</th><th>Total Amount</th></tr></thead>
                                                                                <tbody>
                                                                                <?php $leads_generated = $obj_loyalty_training->sum_leads_funded(date('Y').'-01-01', date('Y-m-d'), 2);
                                                                                if(isset($leads_generated) && !empty($leads_generated))
                                                                                {
                                                                                    $count = 1;
                                                                                    foreach ($leads_generated as $key)
                                                                                    {?>
                                                                                        <tr>
                                                                                            <td><?php echo $count; $count++;?></td>
                                                                                            <td><?php echo $key['f_name']." ".strtoupper($key['l_name']);?></td>
                                                                                            <td><?php echo $key['phone'];?></td>
                                                                                            <td><?php echo $key['email'];?></td>
                                                                                            <td><?php echo $key['created'];?></td>
                                                                                            <td><?php echo $key['dollar_amount'];?></td>
                                                                                        </tr>
                                                                                    <?php }
                                                                                }
                                                                                ?>
                                                                                </tbody>
                                                                            </table>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <div class="modal-footer">
                                                                <button type="button" name="close" onClick="window.close();" data-dismiss="modal" class="btn btn-danger">Close!</button>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </td>
                                            <td><?php echo $obj_loyalty_training->sum_leads_funded(date('Y').'-01-01', date('Y-m-d'), 1);?></td>
                                        </tr>
                                        <tr>
                                            <td>
                                                <a href="javascript:void(0);" data-target="#fmmc_<?php echo $from.$to;?>" data-toggle="modal"><b>Training Leads (Forex Money Maker Course)</b><br/>
                                                <span class="text-muted" style="font-size: smaller">Total number of people that were added to the pool that are currently taking the Forex Money Maker Course.</span></a>
                                                <div id="fmmc_<?php echo $from.$to;?>" tabindex="-1" role="dialog" aria-hidden="true" class="modal fade">
                                                    <div class="modal-dialog modal-lg">
                                                        <div class="modal-content">
                                                            <div class="modal-header">
                                                                <button type="button" data-dismiss="modal" aria-hidden="true"  class="close">&times;</button>
                                                                <h4 class="modal-title">Training Leads (Forex Money Maker Course) (<?php echo date_to_text($from); ?> -> <?php echo date_to_text($to); ?>)</h4></div>
                                                            <div class="modal-body">
                                                                <div style="width: 100%; word-break:break-all; max-height: 550px; overflow-y: scroll; overflow-x: hidden">
                                                                    <div class="row">
                                                                        <div class="col-sm-12">
                                                                            <table class="table table-responsive table-striped table-bordered table-hover">
                                                                                <thead><tr><th></th><th>Name</th><th>Phone</th><th>Email</th><th>Joined</th></tr></thead>
                                                                                <tbody>
                                                                                <?php $leads_generated = $obj_loyalty_training->sum_training_leads($from, $to, 2, 2);
                                                                                if(isset($leads_generated) && !empty($leads_generated))
                                                                                {
                                                                                    $count = 1;
                                                                                    foreach ($leads_generated as $key)
                                                                                    {?>
                                                                                        <tr>
                                                                                            <td><?php echo $count; $count++;?></td>
                                                                                            <td><?php echo $key['f_name']." ".strtoupper($key['l_name']);?></td>
                                                                                            <td><?php echo $key['phone'];?></td>
                                                                                            <td><?php echo $key['email'];?></td>
                                                                                            <td><?php echo datetime_to_text($key['created']);?></td>
                                                                                        </tr>
                                                                                    <?php }
                                                                                }
                                                                                ?>
                                                                                </tbody>
                                                                            </table>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <div class="modal-footer">
                                                                <button type="button" name="close" onClick="window.close();" data-dismiss="modal" class="btn btn-danger">Close!</button>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </td>
                                            <td><?php echo $obj_loyalty_training->sum_training_leads($from, $to, 2, 1);?></td>
                                        </tr>
                                        <tr>
                                            <td>
                                                <a href="javascript:void(0);" data-target="#foc_<?php echo $from.$to;?>" data-toggle="modal"><b>Training Leads (Forex Optimizer Course)</b><br/>
                                                <span class="text-muted" style="font-size: smaller">Total number of people that were added to the pool within the selected time frame and paid for the Forex Optimizer Course within the selected time frame.</span></a>
                                                <div id="foc_<?php echo $from.$to;?>" tabindex="-1" role="dialog" aria-hidden="true" class="modal fade">
                                                    <div class="modal-dialog modal-lg">
                                                        <div class="modal-content">
                                                            <div class="modal-header">
                                                                <button type="button" data-dismiss="modal" aria-hidden="true"  class="close">&times;</button>
                                                                <h4 class="modal-title">Training Leads (Forex Optimizer Course) (<?php echo date_to_text($from); ?> -> <?php echo date_to_text($to); ?>)</h4></div>
                                                            <div class="modal-body">
                                                                <div style="width: 100%; word-break:break-all; max-height: 550px; overflow-y: scroll; overflow-x: hidden">
                                                                    <div class="row">
                                                                        <div class="col-sm-12">
                                                                            <table class="table table-responsive table-striped table-bordered table-hover">
                                                                                <thead><tr><th></th><th>Name</th><th>Phone</th><th>Email</th><th>Joined</th><th>Transaction ID</th></tr></thead>
                                                                                <tbody>
                                                                                <?php $leads_generated = $obj_loyalty_training->sum_training_leads($from, $to, 1, 2);
                                                                                if(isset($leads_generated) && !empty($leads_generated))
                                                                                {
                                                                                    $count = 1;
                                                                                    foreach ($leads_generated as $key)
                                                                                    {?>
                                                                                        <tr>
                                                                                            <td><?php echo $count; $count++;?></td>
                                                                                            <td><?php echo $key['f_name']." ".strtoupper($key['l_name']);?></td>
                                                                                            <td><?php echo $key['phone'];?></td>
                                                                                            <td><?php echo $key['email'];?></td>
                                                                                            <td><?php echo datetime_to_text($key['created']);?></td>
                                                                                            <td><?php echo $key['trans_id'];?></td>
                                                                                        </tr>
                                                                                    <?php }
                                                                                }
                                                                                ?>
                                                                                </tbody>
                                                                            </table>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <div class="modal-footer">
                                                                <button type="button" name="close" onClick="window.close();" data-dismiss="modal" class="btn btn-danger">Close!</button>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </td>
                                            <td><?php echo $obj_loyalty_training->sum_training_leads($from, $to, 1, 1);?></td></tr>
                                        <tr>
                                            <td>
                                                <a href="javascript:void(0);" data-target="#at_<?php echo $from.$to;?>" data-toggle="modal"><b>Active Traders (PRESENT MONTH)</b><br/>
                                                <span class="text-muted" style="font-size: smaller">Total number of people that were added to the pool within the selected time frame and traded actively within the selected time frame.</span></a>
                                                <div id="at_<?php echo $from.$to;?>" tabindex="-1" role="dialog" aria-hidden="true" class="modal fade">
                                                    <div class="modal-dialog modal-lg">
                                                        <div class="modal-content">
                                                            <div class="modal-header">
                                                                <button type="button" data-dismiss="modal" aria-hidden="true"  class="close">&times;</button>
                                                                <h4 class="modal-title">Active Traders (<?php echo date_to_text($from); ?> -> <?php echo date_to_text($to); ?>)</h4></div>
                                                            <div class="modal-body">
                                                                <div style="width: 100%; word-break:break-all; max-height: 550px; overflow-y: scroll; overflow-x: hidden">
                                                                    <div class="row">
                                                                        <div class="col-sm-12">
                                                                            <table class="table table-responsive table-striped table-bordered table-hover">
                                                                                <thead><tr><th></th><th>Name</th><th>Phone</th><th>Email</th><th>Last Trade Date</th></tr></thead>
                                                                                <tbody>
                                                                                <?php $leads_generated = $obj_loyalty_training->sum_active_leads($from, $to, 2);
                                                                                if(isset($leads_generated) && !empty($leads_generated))
                                                                                {
                                                                                    $count = 1;
                                                                                    foreach ($leads_generated as $key)
                                                                                    {?>
                                                                                        <tr>
                                                                                            <td><?php echo $count; $count++;?></td>
                                                                                            <td><?php echo $key['f_name']." ".strtoupper($key['l_name']);?></td>
                                                                                            <td><?php echo $key['phone'];?></td>
                                                                                            <td><?php echo $key['email'];?></td>
                                                                                            <td><?php echo $key['last_trade_date'];?></td>
                                                                                        </tr>
                                                                                    <?php }
                                                                                }
                                                                                ?>
                                                                                </tbody>
                                                                            </table>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <div class="modal-footer">
                                                                <button type="button" name="close" onClick="window.close();" data-dismiss="modal" class="btn btn-danger">Close!</button>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </td>
                                            <td>
                                                <?php echo $obj_loyalty_training->sum_active_leads($from, $to, 1); ?>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>
                                                <a href="javascript:void(0);" data-target="#at_<?php echo date('Y');?>" data-toggle="modal"><b>Active Traders (<?php echo date('Y');?>)</b><br/>
                                                    <span class="text-muted" style="font-size: smaller">Total number of people that were added to the pool this year and are actively trading within the current year.</span></a>
                                                <div id="at_<?php echo date('Y');?>" tabindex="-1" role="dialog" aria-hidden="true" class="modal fade">
                                                    <div class="modal-dialog modal-lg">
                                                        <div class="modal-content">
                                                            <div class="modal-header">
                                                                <button type="button" data-dismiss="modal" aria-hidden="true"  class="close">&times;</button>
                                                                <h4 class="modal-title">Active Traders (<?php echo date_to_text('01-01-'.date('Y')); ?> -> <?php echo date_to_text(date('d-m-Y')); ?>)</h4></div>
                                                            <div class="modal-body">
                                                                <div style="width: 100%; word-break:break-all; max-height: 400px; overflow-y: scroll; overflow-x: hidden">
                                                                    <div class="row">
                                                                        <div class="col-sm-12">
                                                                            <table class="table table-responsive table-striped table-bordered table-hover">
                                                                                <thead><tr><th></th><th>Name</th><th>Phone</th><th>Email</th><th>Last Trade Date</th></tr></thead>
                                                                                <tbody>
                                                                                <?php $leads_generated = $obj_loyalty_training->sum_active_leads(date('Y').'-01-01', date('Y-m-d'), 2);
                                                                                if(isset($leads_generated) && !empty($leads_generated))
                                                                                {
                                                                                    $count = 1;
                                                                                    foreach ($leads_generated as $key)
                                                                                    {?>
                                                                                        <tr>
                                                                                            <td><?php echo $count; $count++;?></td>
                                                                                            <td><?php echo $key['f_name']." ".strtoupper($key['l_name']);?></td>
                                                                                            <td><?php echo $key['phone'];?></td>
                                                                                            <td><?php echo $key['email'];?></td>
                                                                                            <td><?php echo $key['last_trade_date'];?></td>
                                                                                        </tr>
                                                                                    <?php }
                                                                                }
                                                                                ?>
                                                                                </tbody>
                                                                            </table>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <div class="modal-footer">
                                                                <button type="button" name="close" onClick="window.close();" data-dismiss="modal" class="btn btn-danger">Close!</button>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </td>
                                            <td>
                                                <?php echo $obj_loyalty_training->sum_active_leads(date('Y').'-01-01', date('Y-m-d'), 1); ?>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>
                                                <a href="javascript:void(0);" data-target="#na1_<?php echo $from.$to;?>" data-toggle="modal"><b>No Action (Training Leads)</b><br/>
                                                    <span class="text-muted" style="font-size: smaller">
                                                        Total number of people that were added to the pool and have not taken the <span style="text-decoration: underline">primary</span> desired action for Training Leads.
                                                        <br/><b>Primary Action: </b>Leads are to login to FxAcademy.
                                                    </span></a>
                                                <div id="na1_<?php echo $from.$to;?>" tabindex="-1" role="dialog" aria-hidden="true" class="modal fade">
                                                    <div class="modal-dialog modal-lg">
                                                        <div class="modal-content">
                                                            <div class="modal-header">
                                                                <button type="button" data-dismiss="modal" aria-hidden="true"  class="close">&times;</button>
                                                                <h4 class="modal-title">No Action (Training Leads)(<?php echo date_to_text('01-01-'.date('Y')); ?> -> <?php echo date_to_text(date('d-m-Y')); ?>)</h4></div>
                                                            <div class="modal-body">
                                                                <div style="width: 100%; word-break:break-all; max-height: 400px; overflow-y: scroll; overflow-x: hidden">
                                                                    <div class="row">
                                                                        <div class="col-sm-12">
                                                                            <table class="table table-responsive table-striped table-bordered table-hover">
                                                                                <thead><tr><th></th><th>Name</th><th>Phone</th><th>Email</th><th>Joined</th></tr></thead>
                                                                                <tbody>
                                                                                <?php $leads_generated = $obj_loyalty_training->sum_none_active('01-01-'.date('Y'), date('d-m-Y'),1, 2);
                                                                                if(isset($leads_generated) && !empty($leads_generated))
                                                                                {
                                                                                    $count = 1;
                                                                                    foreach ($leads_generated as $key)
                                                                                    {?>
                                                                                        <tr>
                                                                                            <td><?php echo $count; $count++;?></td>
                                                                                            <td><?php echo $key['f_name']." ".strtoupper($key['l_name']);?></td>
                                                                                            <td><?php echo $key['phone'];?></td>
                                                                                            <td><?php echo $key['email'];?></td>
                                                                                            <td><?php echo $key['created'];?></td>
                                                                                        </tr>
                                                                                    <?php }
                                                                                }
                                                                                ?>
                                                                                </tbody>
                                                                            </table>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <div class="modal-footer">
                                                                <button type="button" name="close" onClick="window.close();" data-dismiss="modal" class="btn btn-danger">Close!</button>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </td>
                                            <td>
                                                <?php echo $obj_loyalty_training->sum_none_active(date('Y').'-01-01', date('Y-m-d'),1, 1); ?>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>
                                                <a href="javascript:void(0);" data-target="#na2_<?php echo $from.$to;?>" data-toggle="modal"><b>No Action (ILPR Leads)</b><br/>
                                                    <span class="text-muted" style="font-size: smaller">
                                                        Total number of people that were added to the pool and have not taken the <span style="text-decoration: underline">primary</span> desired action for Training Leads.
                                                        <br/><b>Primary Action: </b>Leads are to open ILPR Accounts.
                                                    </span></a>
                                                <div id="na2_<?php echo $from.$to;?>" tabindex="-1" role="dialog" aria-hidden="true" class="modal fade">
                                                    <div class="modal-dialog modal-lg">
                                                        <div class="modal-content">
                                                            <div class="modal-header">
                                                                <button type="button" data-dismiss="modal" aria-hidden="true"  class="close">&times;</button>
                                                                <h4 class="modal-title">No Action (ILPR Leads)(<?php echo date_to_text('01-01-'.date('Y')); ?> -> <?php echo date_to_text(date('d-m-Y')); ?>)</h4></div>
                                                            <div class="modal-body">
                                                                <div style="width: 100%; word-break:break-all; max-height: 400px; overflow-y: scroll; overflow-x: hidden">
                                                                    <div class="row">
                                                                        <div class="col-sm-12">
                                                                            <table class="table table-responsive table-striped table-bordered table-hover">
                                                                                <thead><tr><th></th><th>Name</th><th>Phone</th><th>Email</th><th>Joined</th></tr></thead>
                                                                                <tbody>
                                                                                <?php $leads_generated = $obj_loyalty_training->sum_none_active(date('Y').'-01-01', date('Y-m-d'),2, 2);
                                                                                if(isset($leads_generated) && !empty($leads_generated))
                                                                                {
                                                                                    $count = 1;
                                                                                    foreach ($leads_generated as $key)
                                                                                    {?>
                                                                                        <tr>
                                                                                            <td><?php echo $count; $count++;?></td>
                                                                                            <td><?php echo $key['f_name']." ".strtoupper($key['l_name']);?></td>
                                                                                            <td><?php echo $key['phone'];?></td>
                                                                                            <td><?php echo $key['email'];?></td>
                                                                                            <td><?php echo $key['created'];?></td>
                                                                                        </tr>
                                                                                    <?php }
                                                                                }
                                                                                ?>
                                                                                </tbody>
                                                                            </table>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <div class="modal-footer">
                                                                <button type="button" name="close" onClick="window.close();" data-dismiss="modal" class="btn btn-danger">Close!</button>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </td>
                                            <td>
                                                <?php echo $obj_loyalty_training->sum_none_active(date('Y').'-01-01', date('Y-m-d'),2, 1); ?>
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-sm-12">
                                <table class="table table-responsive table-striped table-bordered table-hover">
                                    <thead><tr><th>Date</th><th>Leads Count</th><th>Conversions</th><th>Leads Funded</th><th>Training Leads<br/><span class="text-muted">(Forex Money Maker Course)</span></th><th>Training Leads<br/><span class="text-muted">(Forex Optimizer Course)</span></th><th>Active Traders<br/><span class="text-muted">(PRESENT MONTH)</span></th></tr></thead>
                                    <tbody>
                                    <?php if(isset($log_of_dates) && !empty($log_of_dates)) { foreach ($log_of_dates as $row) { ?>
                                        <tr>
                                            <td title="Date"><?php echo date_to_text($row); ?></td>
                                            <td title="Total number of people that were added to the pool on this date.">
                                                <a href="javascript:void(0);" data-target="#leads_<?php echo $row?>" data-toggle="modal"><?php echo $obj_loyalty_training->sum_leads_generated($row, $row, 1); ?></a>
                                                <div id="leads_<?php echo $row?>" tabindex="-1" role="dialog" aria-hidden="true" class="modal fade">
                                                    <div class="modal-dialog modal-lg">
                                                        <div class="modal-content">
                                                            <div class="modal-header">
                                                                <button type="button" data-dismiss="modal" aria-hidden="true"  class="close">&times;</button>
                                                                <h4 class="modal-title">Leads Generated (<?php echo date_to_text($row); ?>)</h4></div>
                                                            <div class="modal-body">
                                                                <div style="width: 100%; word-break:break-all; max-height: 550px; overflow-y: scroll; overflow-x: hidden">
                                                                    <div class="row">
                                                                        <div class="col-sm-12">
                                                                            <table class="table table-responsive table-striped table-bordered table-hover">
                                                                                <thead><tr><th></th><th>Name</th><th>Phone</th><th>Email</th></tr></thead>
                                                                                <tbody>
                                                                                    <?php $leads_generated = $obj_loyalty_training->sum_leads_generated($row, $row, 2);
                                                                                    if(isset($leads_generated) && !empty($leads_generated))
                                                                                    {
                                                                                        $count = 1;
                                                                                        foreach ($leads_generated as $key)
                                                                                        {?>
                                                                                            <tr>
                                                                                                <td><?php echo $count; $count++;?></td>
                                                                                                <td><?php echo $key['f_name']." ".strtoupper($key['l_name']);?></td>
                                                                                                <td><?php echo $key['phone'];?></td>
                                                                                                <td><?php echo $key['email'];?></td>
                                                                                            </tr>
                                                                                        <?php }
                                                                                    }
                                                                                    ?>
                                                                                </tbody>
                                                                            </table>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <div class="modal-footer">
                                                                <button type="button" name="close" onClick="window.close();" data-dismiss="modal" class="btn btn-danger">Close!</button>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </td>
                                            <td title="Total number of people that were added to the pool and opened accounts.">
                                                <a href="javascript:void(0);" data-target="#accounts_<?php echo $row?>" data-toggle="modal"><?php echo $obj_loyalty_training->sum_leads_with_accounts($row, $row, 1); ?></a>
                                                    <div id="accounts_<?php echo $row?>" tabindex="-1" role="dialog" aria-hidden="true" class="modal fade">
                                                    <div class="modal-dialog modal-lg">
                                                        <div class="modal-content">
                                                            <div class="modal-header">
                                                                <button type="button" data-dismiss="modal" aria-hidden="true"  class="close">&times;</button>
                                                                <h4 class="modal-title">Accounts Created (<?php echo date_to_text($row); ?>)</h4></div>
                                                            <div class="modal-body">
                                                                <div style="width: 100%; word-break:break-all; max-height: 550px; overflow-y: scroll; overflow-x: hidden">
                                                                    <div class="row">
                                                                        <div class="col-sm-12">
                                                                            <table class="table table-responsive table-striped table-bordered table-hover">
                                                                                <thead><tr><th></th><th>Name</th><th>Phone</th><th>Email</th><th>Accounts</th></tr></thead>
                                                                                <tbody>
                                                                                    <?php $leads_generated = $obj_loyalty_training->sum_leads_with_accounts($row, $row, 2);
                                                                                    if(isset($leads_generated) && !empty($leads_generated))
                                                                                    {
                                                                                        $count = 1;
                                                                                        foreach ($leads_generated as $key)
                                                                                        {?>
                                                                                            <tr>
                                                                                                <td><?php echo $count; $count++; ?></td>
                                                                                                <td><?php echo $key['f_name']." ".strtoupper($key['l_name']);?></td>
                                                                                                <td><?php echo $key['phone'];?></td>
                                                                                                <td><?php echo $key['email'];?></td>
                                                                                                <td>
                                                                                                    <?php
                                                                                                    $client_ilpr_account = $client_operation->get_client_ilpr_accounts_by_code($key['user_code']);
                                                                                                    $client_non_ilpr_account = $client_operation->get_client_non_ilpr_accounts_by_code($key['user_code']);
                                                                                                    if(isset($client_ilpr_account) && !empty($client_ilpr_account)) {foreach ($client_ilpr_account as $key1){echo $key1['ifx_acct_no']."(ILPR), ";}}
                                                                                                    if(isset($client_non_ilpr_account) && !empty($client_non_ilpr_account)){foreach ($client_non_ilpr_account as $key1){echo $key1['ifx_acct_no']."(None-ILPR), ";}}
                                                                                                    ?>
                                                                                                </td>
                                                                                            </tr>
                                                                                        <?php }
                                                                                    }
                                                                                    ?>
                                                                                </tbody>
                                                                            </table>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <div class="modal-footer">
                                                                <button type="button" name="close" onClick="window.close();" data-dismiss="modal" class="btn btn-danger">Close!</button>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </td>
                                            <td title="Total number of people that were added to the pool that have funded.">
                                                <a href="javascript:void(0);" data-target="#funds_<?php echo $row?>" data-toggle="modal"><?php echo $obj_loyalty_training->sum_leads_funded($row, $row, 1); ?></a>
                                                <div id="funds_<?php echo $row?>" tabindex="-1" role="dialog" aria-hidden="true" class="modal fade">
                                                    <div class="modal-dialog modal-lg">
                                                        <div class="modal-content">
                                                            <div class="modal-header">
                                                                <button type="button" data-dismiss="modal" aria-hidden="true"  class="close">&times;</button>
                                                                <h4 class="modal-title">Funded (<?php echo date_to_text($row); ?>)</h4></div>
                                                            <div class="modal-body">
                                                                <div style="width: 100%; word-break:break-all; max-height: 550px; overflow-y: scroll; overflow-x: hidden">
                                                                    <div class="row">
                                                                        <div class="col-sm-12">
                                                                            <table class="table table-responsive table-striped table-bordered table-hover">
                                                                                <thead><tr><th></th><th>Name</th><th>Phone</th><th>Email</th><th>Total Amount</th></tr></thead>
                                                                                <tbody>
                                                                                <?php $leads_generated = $obj_loyalty_training->sum_leads_funded($row, $row, 2);
                                                                                if(isset($leads_generated) && !empty($leads_generated))
                                                                                {
                                                                                    $count = 1;
                                                                                    foreach ($leads_generated as $key)
                                                                                    {?>
                                                                                        <tr>
                                                                                            <td><?php echo $count; $count++;?></td>
                                                                                            <td><?php echo $key['f_name']." ".strtoupper($key['l_name']);?></td>
                                                                                            <td><?php echo $key['phone'];?></td>
                                                                                            <td><?php echo $key['email'];?></td>
                                                                                            <td><?php echo '$'.$key['dollar_amount'];?></td>
                                                                                        </tr>
                                                                                    <?php }
                                                                                }
                                                                                ?>
                                                                                </tbody>
                                                                            </table>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <div class="modal-footer">
                                                                <button type="button" name="close" onClick="window.close();" data-dismiss="modal" class="btn btn-danger">Close!</button>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </td>
                                            <td title="Total number of people that were added to the pool that are currently taking the Forex Money Maker Course.">
                                                <a href="javascript:void(0);" data-target="#fmmc_<?php echo $row?>" data-toggle="modal"><?php echo $obj_loyalty_training->sum_training_leads($row, $row, 2, 1); ?></a>
                                                <div id="fmmc_<?php echo $row?>" tabindex="-1" role="dialog" aria-hidden="true" class="modal fade">
                                                    <div class="modal-dialog modal-lg">
                                                        <div class="modal-content">
                                                            <div class="modal-header">
                                                                <button type="button" data-dismiss="modal" aria-hidden="true"  class="close">&times;</button>
                                                                <h4 class="modal-title">Forex Money Maker Course (<?php echo date_to_text($row); ?>)</h4></div>
                                                            <div class="modal-body">
                                                                <div style="width: 100%; word-break:break-all; max-height: 550px; overflow-y: scroll; overflow-x: hidden">
                                                                    <div class="row">
                                                                        <div class="col-sm-12">
                                                                            <table class="table table-responsive table-striped table-bordered table-hover">
                                                                                <thead><tr><th></th><th>Name</th><th>Phone</th><th>Email</th></tr></thead>
                                                                                <tbody>
                                                                                <?php $leads_generated = $obj_loyalty_training->sum_training_leads($row, $row, 2, 2);
                                                                                if(isset($leads_generated) && !empty($leads_generated))
                                                                                {
                                                                                    $count = 1;
                                                                                    foreach ($leads_generated as $key)
                                                                                    {?>
                                                                                        <tr>
                                                                                            <td><?php echo $count; $count++;?></td>
                                                                                            <td><?php echo $key['f_name']." ".strtoupper($key['l_name']);?></td>
                                                                                            <td><?php echo $key['phone'];?></td>
                                                                                            <td><?php echo $key['email'];?></td>
                                                                                        </tr>
                                                                                    <?php }
                                                                                }
                                                                                ?>
                                                                                </tbody>
                                                                            </table>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <div class="modal-footer">
                                                                <button type="button" name="close" onClick="window.close();" data-dismiss="modal" class="btn btn-danger">Close!</button>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </td>
                                            <td title="Total number of people that were added to the pool that are currently taking the Forex Optimizer Course.">
                                                <a href="javascript:void(0);" data-target="#foc_<?php echo $row?>" data-toggle="modal"><?php echo $obj_loyalty_training->sum_training_leads($row, $row, 1, 1); ?></a>
                                                <div id="foc_<?php echo $row?>" tabindex="-1" role="dialog" aria-hidden="true" class="modal fade">
                                                    <div class="modal-dialog modal-lg">
                                                        <div class="modal-content">
                                                            <div class="modal-header">
                                                                <button type="button" data-dismiss="modal" aria-hidden="true"  class="close">&times;</button>
                                                                <h4 class="modal-title">Forex Optimizer Course (<?php echo date_to_text($row); ?>)</h4></div>
                                                            <div class="modal-body">
                                                                <div style="width: 100%; word-break:break-all; max-height: 550px; overflow-y: scroll; overflow-x: hidden">
                                                                    <div class="row">
                                                                        <div class="col-sm-12">
                                                                            <table class="table table-responsive table-striped table-bordered table-hover">
                                                                                <thead><tr><th></th><th>Name</th><th>Phone</th><th>Email</th><th>Transaction ID</th></tr></thead>
                                                                                <tbody>
                                                                                <?php $leads_generated = $obj_loyalty_training->sum_training_leads($row, $row, 1, 2);
                                                                                if(isset($leads_generated) && !empty($leads_generated))
                                                                                {
                                                                                    $count = 1;
                                                                                    foreach ($leads_generated as $key)
                                                                                    {?>
                                                                                        <tr>
                                                                                            <td><?php echo $count; $count++;?></td>
                                                                                            <td><?php echo $key['f_name']." ".strtoupper($key['l_name']);?></td>
                                                                                            <td><?php echo $key['phone'];?></td>
                                                                                            <td><?php echo $key['email'];?></td>
                                                                                            <td><?php echo $key['trans_id'];?></td>
                                                                                        </tr>
                                                                                    <?php }
                                                                                }
                                                                                ?>
                                                                                </tbody>
                                                                            </table>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <div class="modal-footer">
                                                                <button type="button" name="close" onClick="window.close();" data-dismiss="modal" class="btn btn-danger">Close!</button>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </td>
                                            <td title="Total number of people that were added to the pool that are actively trading withing the current month.">
                                                <a href="javascript:void(0);" data-target="#at_<?php echo $row?>" data-toggle="modal"><?php echo $obj_loyalty_training->sum_active_leads($row, $row, 1); ?></a>
                                                <div id="at_<?php echo $row?>" tabindex="-1" role="dialog" aria-hidden="true" class="modal fade">
                                                    <div class="modal-dialog modal-lg">
                                                        <div class="modal-content">
                                                            <div class="modal-header">
                                                                <button type="button" data-dismiss="modal" aria-hidden="true"  class="close">&times;</button>
                                                                <h4 class="modal-title">Active Traders (<?php echo date_to_text($row); ?>)</h4></div>
                                                            <div class="modal-body">
                                                                <div style="width: 100%; word-break:break-all; max-height: 550px; overflow-y: scroll; overflow-x: hidden">
                                                                    <div class="row">
                                                                        <div class="col-sm-12">
                                                                            <table class="table table-responsive table-striped table-bordered table-hover">
                                                                                <thead><tr><th></th><th>Name</th><th>Phone</th><th>Email</th><th>Last Trade Date</th></tr></thead>
                                                                                <tbody>
                                                                                <?php $leads_generated = $obj_loyalty_training->sum_active_leads($row, $row, 2);
                                                                                if(isset($leads_generated) && !empty($leads_generated))
                                                                                {
                                                                                    $count = 1;
                                                                                    foreach ($leads_generated as $key)
                                                                                    {?>
                                                                                        <tr>
                                                                                            <td><?php echo $count; $count++;?></td>
                                                                                            <td><?php echo $key['f_name']." ".strtoupper($key['l_name']);?></td>
                                                                                            <td><?php echo $key['phone'];?></td>
                                                                                            <td><?php echo $key['email'];?></td>
                                                                                            <td><?php echo $key['last_trade_date'];?></td>
                                                                                        </tr>
                                                                                    <?php }
                                                                                }
                                                                                ?>
                                                                                </tbody>
                                                                            </table>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <div class="modal-footer">
                                                                <button type="button" name="close" onClick="window.close();" data-dismiss="modal" class="btn btn-danger">Close!</button>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </td>
                                        </tr>
                                    <?php } } else { echo "<tr><td colspan='5' class='text-danger'><em>No results to display</em></td></tr>"; } ?>
                                    </tbody>
                                </table>

                                <?php if(isset($log_of_dates) && !empty($log_of_dates)) { ?>
                                    <div class="tool-footer text-right">
                                        <p class="pull-left">Showing <?php echo $prespagelow . " to " . $prespagehigh . " of " . $numrows; ?> entries</p>
                                    </div>
                                <?php } ?>

                            </div>
                        </div>
                        <?php if(isset($log_of_dates) && !empty($log_of_dates)) { require_once 'layouts/pagination_links.php'; } ?>
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