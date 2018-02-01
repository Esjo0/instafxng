<?php
require_once("../init/initialize_admin.php");
if (!$session_admin->is_logged_in()) {
    redirect_to("login.php");
}

if (isset($_POST['saldo_report'])) {
    foreach($_POST as $key => $value) {
        $_POST[$key] = $db_handle->sanitizePost(trim($value));
    }
    
    $from_date = $_POST['from_date'];
    $to_date = $_POST['to_date'];
    $trans_type = $_POST['trans_type'];

    $saldo = $system_object->get_saldo_report($from_date, $to_date, $trans_type);
    
    $total_deposit = number_format($saldo['deposit'], 2, ".", ",");
    $total_deposit_dollar = number_format($saldo['deposit_dollar'], 2, ".", ",");
    $total_withdrawal = number_format($saldo['withdrawal'], 2, ".", ",");
    $total_withdrawal_dollar = number_format($saldo['withdrawal_dollar'], 2, ".", ",");
    $saldo_calculated = number_format($saldo['saldo'], 2, ".", ",");
    $saldo_calculated_dollar = number_format($saldo['saldo_dollar'], 2, ".", ",");

    $deposit_avg = number_format($saldo['deposit_avg'], 2, ".", ",");
    $withdrawal_avg = number_format($saldo['withdrawal_avg'], 2, ".", ",");

    switch ($saldo['category']) {
        case 'all': $trans_category = "All Transactions"; break;
        case '1': $trans_category = "ILPR Transactions"; break;
        case '2': $trans_category = "NON - ILPR Transactions"; break;
        default: $trans_category = "Unknown"; break;
    }
}

?>
<!DOCTYPE html>
<html lang="en">
    <head>
        <base target="_self">
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>Instaforex Nigeria | Admin - Saldo Report</title>
        <meta name="title" content="Instaforex Nigeria | Admin - Saldo Report" />
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
                            <h4><strong>VIEW SALDO REPORTS</strong></h4>
                        </div>
                    </div>
                    
                    <div class="section-tint super-shadow">
                        <div class="row">
                            <div class="col-sm-12">
                                <?php require_once 'layouts/feedback_message.php'; ?>
                                
                                <p>Calculate Saldo within a date range using the form below.</p>
                                
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
                                        <label class="control-label col-sm-3" for="trans_type">Transaction Category:</label>
                                        <div class="col-sm-9 col-lg-5">
                                            <select name="trans_type" class="form-control" id="trans_type">
                                                <option value="all" selected>All Transactions</option>
                                                <option value="1">ILPR Transactions</option>
                                                <option value="2">Non-ILPR Transactions</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <div class="col-sm-offset-3 col-sm-9"><input name="saldo_report" type="submit" class="btn btn-success" value="Calculate" /></div>
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
                                <?php if(isset($saldo_calculated)) { ?>
                                    <h5>Saldo Report from <strong><?php echo date('d-M-Y', strtotime($from_date)); ?></strong> to <strong><?php echo date('d-M-Y', strtotime($to_date)); ?></strong></h5>
                                    <p><strong>Average Deposit Rate:</strong> &dollar; <br />
                                        <strong>Average Withdrawal Rate:</strong> &dollar; <br />
                                        <strong>Selected Category: </strong> <?php echo $trans_category; ?>
                                    </p>

                                    <table class="table table-responsive table-striped table-bordered table-hover">
                                        <thead>
                                        <tr>
                                            <th>Total Deposit</th>
                                            <th>Total Deposit</th>
                                            <th>Total Withdrawal</th>
                                            <th>Total Withdrawal</th>
                                            <th>Saldo</th>
                                            <th>Saldo</th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                            <tr>
                                                <td>&#8358; <?php echo $total_deposit; ?></td>
                                                <td>&dollar; <?php echo $total_deposit_dollar; ?></td>
                                                <td>&#8358; <?php echo $total_withdrawal; ?></td>
                                                <td>&dollar; <?php echo $total_withdrawal_dollar; ?></td>
                                                <td>&#8358; <?php echo $saldo_calculated ?></td>
                                                <td>&dollar; <?php echo $saldo_calculated_dollar ?></td>
                                            </tr>
                                        </tbody>
                                    </table>
                                <?php } ?>
                                
                                
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