<?php
require_once("../init/initialize_admin.php");
if (!$session_admin->is_logged_in()) {
    redirect_to("login.php");
}



if (isset($_POST['signal_report'])) {
    foreach($_POST as $key => $value) {
        $_POST[$key] = $db_handle->sanitizePost(trim($value));
    }

    $from_date = $_POST['from_date'];
    $to_date = $_POST['to_date'];

    $query = "SELECT SUM(views) AS Total FROM signal_daily WHERE trigger_date BETWEEN '$from_date' AND '$to_date'";
    $result_view = $db_handle->runQuery($query);

    $query = "SELECT trigger_status FROM signal_daily WHERE trigger_date BETWEEN '$from_date' AND '$to_date' AND trigger_status = '1'";
    $total_triggered = $db_handle->numRows($query);

    $query = "SELECT * FROM signal_daily WHERE trigger_date BETWEEN '$from_date' AND '$to_date' ORDER BY created DESC";
    $numrows = $db_handle->numRows($query);
    $total_untriggered = $numrows - $total_triggered;
    $rowsperpage = 10;
    $totalpages = ceil($numrows / $rowsperpage);
    if (isset($_GET['pg']) && is_numeric($_GET['pg'])) {    $currentpage = (int) $_GET['pg'];} else {    $currentpage = 1;}
    if ($currentpage > $totalpages) { $currentpage = $totalpages; }
    if ($currentpage < 1) { $currentpage = 1; }
    $prespagelow = $currentpage * $rowsperpage - $rowsperpage + 1;
    $prespagehigh = $currentpage * $rowsperpage;
    if($prespagehigh > $numrows) { $prespagehigh = $numrows; }
    $offset = ($currentpage - 1) * $rowsperpage;
    $query .= ' '.'LIMIT ' . $offset . ',' . $rowsperpage;
    $result = $db_handle->runQuery($query);
    $all_signals = $db_handle->fetchAssoc($result);
}
else
{
    $from_date = date('Y-m')."-1";
    $to_date = date('Y-m-d');

    $query = "SELECT SUM(views) AS Total FROM signal_daily WHERE trigger_date BETWEEN '$from_date' AND '$to_date'";
    $result_view = $db_handle->runQuery($query);

    $query = "SELECT trigger_status FROM signal_daily WHERE trigger_date BETWEEN '$from_date' AND '$to_date' AND trigger_status = '1'";
    $total_triggered = $db_handle->numRows($query);

    $query = "SELECT * FROM signal_daily WHERE trigger_date BETWEEN '$from_date' AND '$to_date' ORDER BY created DESC";
    $numrows = $db_handle->numRows($query);
    $total_untriggered = $numrows - $total_triggered;
    $rowsperpage = 10;
    $totalpages = ceil($numrows / $rowsperpage);
    if (isset($_GET['pg']) && is_numeric($_GET['pg'])) {    $currentpage = (int) $_GET['pg'];} else {    $currentpage = 1;}
    if ($currentpage > $totalpages) { $currentpage = $totalpages; }
    if ($currentpage < 1) { $currentpage = 1; }
    $prespagelow = $currentpage * $rowsperpage - $rowsperpage + 1;
    $prespagehigh = $currentpage * $rowsperpage;
    if($prespagehigh > $numrows) { $prespagehigh = $numrows; }
    $offset = ($currentpage - 1) * $rowsperpage;
    $query .= ' '.'LIMIT ' . $offset . ',' . $rowsperpage;
    $result = $db_handle->runQuery($query);
    $all_signals = $db_handle->fetchAssoc($result);
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <base target="_self">
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Instaforex Nigeria | Admin - Commissions Report</title>
    <meta name="title" content="Instaforex Nigeria | Admin - Commissions Report" />
    <meta name="keywords" content="" />
    <meta name="description" content="" />
    <?php require_once 'layouts/head_meta.php'; ?>
    <script type="text/javascript" src="//cdnjs.cloudflare.com/ajax/libs/mdbootstrap/4.5.0/js/mdb.min.js"></script>
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
                    <h4><strong>VIEW SIGNALS REPORTS</strong></h4>
                </div>
            </div>

            <div class="section-tint super-shadow">
                <div class="row">
                    <div class="col-sm-12">
                        <?php require_once 'layouts/feedback_message.php'; ?>
                        <h5>List of Posted signals from <strong><?php echo date_to_text($from_date); ?></strong> to <strong><?php echo date_to_text($to_date); ?></strong>  <button title="Apply Filter" id="trigger" onclick="show_form('filter')" class="btn btn-sm btn-default"><i class="glyphicon glyphicon-arrow-down"></i></button></h5>
                        <div style="display: none" id="filter">
                            <center>
                                <p>Select signals posted within a date range using the form below.</p>
                                <form data-toggle="validator" class="form-horizontal" role="form" method="post" action="<?php echo $_SERVER['REQUEST_URI']; ?>">
                                    <div class="row">
                                        <div class="col-sm-2"></div>
                                        <div class="col-sm-3">
                                            <div class="input-group date">
                                                <input  name="from_date" type="text" class="form-control" id="datetimepicker" required>
                                                <span class="input-group-addon"><span class="glyphicon glyphicon-calendar"></span></span>
                                            </div>
                                        </div>
                                        <div class="col-sm-3">
                                            <div class="input-group date">
                                                <input  name="to_date" type="text" class="form-control" id="datetimepicker2" required>
                                                <span class="input-group-addon"><span class="glyphicon glyphicon-calendar"></span></span>
                                            </div>
                                        </div>
                                        <script type="text/javascript">
                                            $(function () {
                                                $('#datetimepicker, #datetimepicker2').datetimepicker({
                                                    format: 'YYYY-MM-DD'
                                                });
                                            });
                                        </script>
                                        <div class="col-sm-2">
                                            <input name="signal_report" type="submit" class="btn btn-success" value="Submit" />
                                        </div>
                                        <div class="col-sm-2"></div>

                                    </div>

                                </form>
                            </center>
                        </div>
                        <br>
                        <br>
                            <div class="row">
                                <div class="col-sm-4"></div>
                        <ul class="list-group mb-3 col-sm-4">
                            <li class="list-group-item d-flex justify-content-between">
                                    <span>Total Signals Posted</span>
                                    <strong><?php echo $numrows;?></strong>
                                </li>
                            <li class="list-group-item d-flex justify-content-between">
                                <span>Total Triggered Signals</span>
                                <strong><?php echo $total_triggered;?></strong>
                            </li>
                            <li class="list-group-item d-flex justify-content-between">
                                <span>Total Un-Triggered Signals</span>
                                <strong><?php echo $total_untriggered;?></strong>
                            </li>

                                <li class="list-group-item d-flex justify-content-between">
                                    <span>Total Signals Views</span>
                                    <strong><i class="fa fa-eye fa-fw"></i>
									<?php
                                    if(isset($result_view)){ foreach($result_view AS $view){
									echo $view['Total'];
									}}
									?></strong>
                                </li>
                        </ul>
                                <div class="col-sm-4"></div>
                            </div>
                        <?php if(isset($all_signals) && !empty($all_signals)){?>

                        <div id="dvTable">
                            <h5>List of all Signals</h5>
                            <table  class="table table-responsive table-striped table-bordered table-hover">
                                <thead>
                                <tr>
                                    <th>Symbol</th>
                                    <th>Order Type</th>
                                    <th>Price</th>
                                    <th>Take Profit</th>
                                    <th>Stop Loss</th>
                                    <th>Trigger Date and Time</th>
                                    <th>Date Created</th>
                                    <th>Views</th>
                                    <th>Status</th>
                                    <th>Entry Price</th>
                                    <th>Entry Time</th>
                                    <th>Exit Time</th>
                                    <th>Pips</th>
                                </tr>
                                </thead>
                                <tbody>
                                <?php
                                foreach ($all_signals as $row)
                                {
                                ?>
                                <tr>
                                    <td><?php
                                        $check_id = $row['symbol_id'];
                                        $query = "SELECT symbol AS Currency_Pair FROM signal_symbol WHERE symbol_id = $check_id";
                                        $result = $db_handle->runQuery($query);
                                        $result = $db_handle->fetchAssoc($result);
                                        foreach ($result as $row4)
                                        {
                                            extract($row4);
                                            echo $Currency_Pair;
                                        } ?></td>
                                    <td><?php
                                        if($row['order_type'] == 2)
                                        {
                                            echo" <b class='text-danger'><i class='glyphicon glyphicon-arrow-down'></i></b> (BUY)";
                                        }
                                        elseif($row['order_type'] == 1)
                                        {
                                            echo"<b class='text-success'><i class='glyphicon glyphicon-arrow-up'></i></b> (SELL)";
                                        }
                                        ?></td>
                                    <td><?php echo $row['price']; ?></td>
                                    <td><?php echo $row['take_profit']; ?></td>
                                    <td><?php echo $row['stop_loss']; ?></td>
                                    <td><?php echo datetime_to_text($row['trigger_date']." ".$row['trigger_time']); ?></td>
                                    <td><?php echo datetime_to_text($row['created']); ?></td>
                                    <td><?php echo $row['views']; ?></td>
                                    <td><?php
                                        if($row['trigger_status'] == 0)
                                        {
                                            echo" <b class='text-danger'><i class='glyphicon glyphicon-remove'></i></b>";
                                        }
                                        elseif($row['trigger_status'] == 1)
                                        {
                                            echo"<b class='text-success'><i class='glyphicon glyphicon-ok'></i></b>";
                                        }
                                        ?></td>
                        </tr>
                    <?php
                    }?>
                        </tbody>
                        </table>
                    </div>

                    <?php }
                    else
                    { echo "<em>No results to display...</em>"; } ?>

                </div>
                    <?php require 'layouts/pagination_links.php'; ?>
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