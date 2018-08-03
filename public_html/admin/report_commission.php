<?php
require_once("../init/initialize_admin.php");
if (!$session_admin->is_logged_in()) {
    redirect_to("login.php");
}

if (isset($_POST['commission_report'])) {
    foreach($_POST as $key => $value) {
        $_POST[$key] = $db_handle->sanitizePost(trim($value));
    }

    $from_date = $_POST['from_date'];
    $to_date = $_POST['to_date'];

    $commission = $system_object->get_comission_report($from_date, $to_date);
    $commission_details = $system_object->get_comission_report_details($from_date, $to_date);
    
    $total_ifx_acct = $commission['accounts'];
    $total_volume = number_format($commission['volume'], 2, ".", ",");
    $total_commission = number_format($commission['commission'], 2, ".", ",");
}
else
{
    $from_date = date('Y-m')."-1";
    $to_date = date('Y-m-d');

    $commission = $system_object->get_comission_report($from_date, $to_date);
    $commission_details = $system_object->get_comission_report_details($from_date, $to_date);

    $total_ifx_acct = $commission['accounts'];
    $total_volume = number_format($commission['volume'], 2, ".", ",");
    $total_commission = number_format($commission['commission'], 2, ".", ",");
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
        <!-- JQuery -->
<!--        <script type="text/javascript" src="//cdnjs.cloudflare.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>-->
        <!-- Bootstrap tooltips -->
<!--        <script type="text/javascript" src="//cdnjs.cloudflare.com/ajax/libs/popper.js/1.13.0/umd/popper.min.js"></script>-->
        <!-- Bootstrap core JavaScript -->
        <!--/<script type="text/javascript" src="//cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.0.0/js/bootstrap.min.js"></script>-->
        <!-- MDB core JavaScript -->
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
                            <h4><strong>VIEW COMMISSIONS REPORTS</strong></h4>
                        </div>
                    </div>
                    
                    <div class="section-tint super-shadow">
                        <div class="row">
                            <div class="col-sm-12">
                                <?php require_once 'layouts/feedback_message.php'; ?>
                                <h5>Commissions Report from <strong><?php echo date_to_text($from_date); ?></strong> to <strong><?php echo date_to_text($to_date); ?></strong>  <button title="Apply Filter" id="trigger" onclick="show_form('filter')" class="btn btn-sm btn-default"><i class="glyphicon glyphicon-arrow-down"></i></button></h5>
                                <div style="display: none" id="filter">
                                    <center>
                                    <p>Calculate Commission earned within a date range using the form below.</p>
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
                                                <input name="commission_report" type="submit" class="btn btn-success" value="Calculate" />
                                            </div>
                                            <div class="col-sm-2"></div>

                                        </div>

                                    </form>
                                    </center>
                                </div>
                                <p><b>Total Accounts: <?php echo $total_ifx_acct; ?></b></p>
                                <p><strong>Total Volume: <?php echo $total_volume; ?></strong></p>
                                <p><strong>Total Commission: &dollar; <?php echo $total_commission; ?></strong></p>
                                <ul class="nav nav-tabs">
                                    <li class="active"><a data-toggle="tab" href="#commission">Commissions</a></li>
                                    <li><a data-toggle="tab" href="#volume">Volume</a></li>
                                    <li><a data-toggle="tab" href="#account">Accounts</a></li>
                                </ul>
                                <div class="tab-content">
                                    <div id="commission" class="tab-pane fade in active">
                                        <p>Chart of Commissions Earned</p>
                                        <canvas style="min-width: 50%" id="commissionChart"></canvas>
                                        <script>
                                            var ctxL = document.getElementById("commissionChart").getContext('2d');
                                            var myLineChart = new Chart(ctxL, {
                                                type: 'line',
                                                data: {
                                                    labels: [
                                                        <?php
                                                        foreach($commission_details as $row)
                                                        {
                                                            echo '"'.date_to_text($row['date_earned']).'",';
                                                        }
                                                            ?>
                                                    ],
                                                    datasets: [
                                                        {
                                                            label: "Commissions",
                                                            data: [
                                                                <?php
                                                                foreach($commission_details as $row)
                                                                {
                                                                    echo '"'.$row['commission'].'",';
                                                                }
                                                                ?>
                                                            ]
                                                        }
                                                    ]
                                                },
                                                options: {
                                                    responsive: true
                                                }
                                            });

                                        </script>
                                    </div>
                                    <div id="volume" class="tab-pane fade">
                                        <p>Chart of Volume Traded</p>
                                        <canvas id="volumeChart"></canvas>
                                        <script>
                                            var ctxL = document.getElementById("volumeChart").getContext('2d');
                                            var myLineChart = new Chart(ctxL, {
                                                type: 'line',
                                                data: {
                                                    labels: [
                                                        <?php
                                                        foreach($commission_details as $row)
                                                        {
                                                            echo '"'.date_to_text($row['date_earned']).'",';
                                                        }
                                                        ?>
                                                    ],
                                                    datasets: [
                                                        {
                                                            label: "Volume",
                                                            data: [
                                                                <?php
                                                                foreach($commission_details as $row)
                                                                {
                                                                    echo '"'.$row['volume'].'",';
                                                                }
                                                                ?>
                                                            ]
                                                        }
                                                    ]
                                                },
                                                options: {
                                                    responsive: true
                                                }
                                            });

                                        </script>
                                    </div>
                                    <div id="account" class="tab-pane fade">
                                        <p>Chart of Accounts that Traded</p>
                                        <canvas id="accountChart"></canvas>
                                        <script>
                                            var ctxL = document.getElementById("accountChart").getContext('2d');
                                            var myLineChart = new Chart(ctxL, {
                                                type: 'line',
                                                data: {
                                                    labels: [
                                                        <?php
                                                        foreach($commission_details as $row)
                                                        {
                                                            echo '"'.date_to_text($row['date_earned']).'",';
                                                        }
                                                        ?>
                                                    ],
                                                    datasets: [
                                                        {
                                                            label: "Account",
                                                            data: [
                                                                <?php
                                                                foreach($commission_details as $row)
                                                                {
                                                                    echo '"'.$row['accounts'].'",';
                                                                }
                                                                ?>
                                                            ]
                                                        }
                                                    ]
                                                },
                                                options: {
                                                    responsive: true
                                                }
                                            });

                                        </script>
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
        <script src="//cdnjs.cloudflare.com/ajax/libs/moment.js/2.9.0/moment-with-locales.js"></script>
        <script src="//cdn.rawgit.com/Eonasdan/bootstrap-datetimepicker/e8bddc60e73c1ec2475f827be36e1957af72e2ea/src/js/bootstrap-datetimepicker.js"></script>
    </body>
</html>