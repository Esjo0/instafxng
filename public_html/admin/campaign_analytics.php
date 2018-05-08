<?php
require_once("../init/initialize_admin.php");
if (!$session_admin->is_logged_in()) {redirect_to("login.php");}

if(isset($_POST['from_date']) && !empty($_POST['from_date'])){ $from = $_POST['from_date'];  }else{ $from = date('Y-m')."-1";}
if(isset($_POST['to_date']) && !empty($_POST['to_date'])){ $to = $_POST['to_date'];  }else{ $to = date('Y-m-d');}

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

                        <div class="row">
                            <div class="col-sm-6">
                                <table class="table table-responsive table-striped table-hover">
                                    <tbody>
                                        <tr>
                                            <td><b>Days</b></td><td><?php echo $numrows?></td>
                                        </tr>
                                        <tr>
                                            <td><b>Leads Count</b></td><td><?php echo $obj_loyalty_training->sum_leads_generated($from, $to, 1); ?></td>
                                        </tr>
                                        <tr>
                                            <td><b>Conversions</b></td><td><?php echo $obj_loyalty_training->sum_leads_with_accounts($from, $to, 1);?></td>
                                        </tr>
                                        <tr>
                                            <td><b>Leads Funded</b></td><td><?php echo $obj_loyalty_training->sum_leads_funded($from, $to, 1);?></td>
                                        </tr>
                                        <tr>
                                            <td><b>Training Leads (Forex Money Maker Course)</b></td><td><?php echo $obj_loyalty_training->sum_training_leads($from, $to, 1, 1);?></td>
                                        </tr>
                                        <tr>
                                            <td><b>Training Leads (Forex Optimizer Course)</b></td><td><?php echo $obj_loyalty_training->sum_training_leads($from, $to, 2, 1);?></td>
                                        </tr>
                                        <tr>
                                            <td><b>Active Traders (PRESENT MONTH)</b></td><td><?php echo $obj_loyalty_training->sum_active_leads($from, $to, 1); ?></td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-sm-12">
                                <table class="table table-responsive table-striped table-bordered table-hover">
                                    <thead>
                                    <tr>
                                        <th>Date</th>
                                        <th>Leads Count</th>
                                        <th>Conversions</th>
                                        <th>Leads Funded</th>
                                        <th>Training Leads (Forex Money Maker Course)</th>
                                        <th>Training Leads (Forex Optimizer Course)</th>
                                        <th>Active Trader (PRESENT MONTH)</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    <?php if(isset($log_of_dates) && !empty($log_of_dates)) { foreach ($log_of_dates as $row) { ?>
                                        <tr>
                                            <td><?php echo date_to_text($row); ?></td>
                                            <td><?php echo $obj_loyalty_training->sum_leads_generated($row, $row, 1); ?></td>
                                            <td><?php echo $obj_loyalty_training->sum_leads_with_accounts($row, $row, 1); ?></td>
                                            <td><?php echo $obj_loyalty_training->sum_leads_funded($row, $row, 1); ?></td>
                                            <td><?php echo $obj_loyalty_training->sum_training_leads($row, $row, 1, 1); ?></td>
                                            <td><?php echo $obj_loyalty_training->sum_training_leads($row, $row, 2, 1); ?></td>
                                            <td><?php echo $obj_loyalty_training->sum_active_leads($row, $row, 1); ?></td>
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
    </body>
</html>