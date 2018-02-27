<?php
require_once("../init/initialize_admin.php");
if (!$session_admin->is_logged_in()) {
    redirect_to("login.php");
}

if (isset($_POST['report']))
{

    foreach($_POST as $key => $value)
    {
        $_POST[$key] = $db_handle->sanitizePost(trim($value));
    }
    extract($_POST);

    if(isset($location) && !empty($location))
    {
        $query = "SELECT 
          accounting_system_req_item.order_code AS req_order_code, 
          accounting_system_req_item.item_desc AS item_desc, 
          accounting_system_req_item.item_id AS item_id,
          accounting_system_req_item.app_total_cost AS app_total_cost,
          accounting_system_req_order.created AS created, 
          accounting_system_office_locations.location AS location,
          CONCAT(admin.first_name, SPACE(1), admin.last_name) AS author_name
          FROM admin, accounting_system_req_order, accounting_system_office_locations, accounting_system_req_item
          WHERE accounting_system_req_order.author_code = admin.admin_code
          AND accounting_system_req_order.payment_status = '2'
          AND accounting_system_req_order.location = accounting_system_office_locations.location_id
          AND STR_TO_DATE(accounting_system_req_order.created, '%Y-%m-%d') BETWEEN '$from_date' AND '$to_date' 
          AND accounting_system_req_item.item_app = '$options'
          AND accounting_system_office_locations.location_id = '$location'
          AND accounting_system_req_item.order_code = accounting_system_req_order.req_order_code
          ORDER BY accounting_system_req_order.created DESC ";
    }
    else
    {
        $query = "SELECT 
          accounting_system_req_item.order_code AS req_order_code, 
          accounting_system_req_item.item_desc AS item_desc, 
          accounting_system_req_item.item_id AS item_id,
          accounting_system_req_item.app_total_cost AS app_total_cost,
          accounting_system_req_order.created AS created, 
          accounting_system_office_locations.location AS location,
          CONCAT(admin.first_name, SPACE(1), admin.last_name) AS author_name
          FROM admin, accounting_system_req_order, accounting_system_office_locations, accounting_system_req_item
          WHERE accounting_system_req_order.author_code = admin.admin_code
          AND accounting_system_req_order.payment_status = '2'
          AND accounting_system_req_order.location = accounting_system_office_locations.location_id
          AND STR_TO_DATE(accounting_system_req_order.created, '%Y-%m-%d') BETWEEN '$from_date' AND '$to_date' 
          AND accounting_system_req_item.item_app = '$options'
          AND accounting_system_req_item.order_code = accounting_system_req_order.req_order_code
          ORDER BY accounting_system_req_order.created DESC ";
    }

    $numrows = $db_handle->numRows($query);

    $rowsperpage = 100;

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
    $query .= 'LIMIT ' . $offset . ',' . $rowsperpage;
    $result = $db_handle->runQuery($query);
    $reports = $db_handle->fetchAssoc($result);
    //var_dump($reports);

    $month = date('F', mktime(0, 0, 0, $month, 10));

    $query = "SELECT SUM(accounting_system_req_order.req_order_total) AS total_expenses
              FROM accounting_system_req_order
              WHERE accounting_system_req_order.payment_status = '2' AND STR_TO_DATE(created, '%Y-%m-%d') BETWEEN '$from_date' AND '$to_date' ";
    $result = $db_handle->runQuery($query);
    $stats = $db_handle->fetchAssoc($result);
    $stats = $stats[0];
    //var_dump($stats);
}

?>
<!DOCTYPE html>
<html lang="en">
    <head>
        <base target="_self">
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>Instaforex Nigeria | Admin - Accounting System</title>
        <meta name="title" content="Instaforex Nigeria | Admin - Requisition Report" />
        <meta name="keywords" content="" />
        <meta name="description" content="" />
        <?php require_once 'layouts/head_meta.php'; ?>
        <script src="//cdn.jsdelivr.net/alasql/0.3/alasql.min.js"></script>
        <script src="//cdnjs.cloudflare.com/ajax/libs/xlsx/0.7.12/xlsx.core.min.js"></script>
        <script>
            function print_report(divName)
            {
                var printContents = document.getElementById(divName).innerHTML;
                var originalContents = document.body.innerHTML;

                document.body.innerHTML = printContents;

                window.print();

                document.body.innerHTML = originalContents;
            }

            function exportExcel()
            {
                var filename = 'deposit_completed_filter'+Math.floor(Date.now() / 1000);
                var result = alasql('SELECT * INTO XLSX("'+filename+'.xlsx",{headers:true}) FROM HTML("#dvTable",{headers:true})');
                console.log(result);

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
                            <h4><strong>REQUISITION REPORTS</strong></h4>
                        </div>
                    </div>
                    
                    <div class="section-tint super-shadow">
                        <div class="row">
                            <div class="col-sm-12">
                                <?php require_once 'layouts/feedback_message.php'; ?>

                                <p>Fetch requisition reports within a date range using the form below.</p>
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
                                        <label class="control-label col-sm-3" for="location">Locations:</label>
                                        <div class="col-sm-9 col-lg-5">
                                            <div class="input-group date">
                                                <select name="location" class="form-control" id="location">
                                                    <option value="" selected>All Offices</option>
                                                    <?php
                                                    $query = "SELECT * FROM accounting_system_office_locations ";
                                                    $result = $db_handle->runQuery($query);
                                                    $result = $db_handle->fetchAssoc($result);
                                                    foreach ($result as $row)
                                                    {
                                                        extract($row)
                                                    ?>
                                                        <option value="<?php echo $location_id;?>"><?php echo $location;?></option>
                                                    <?php } ?>
                                                </select>
                                                <span class="input-group-addon"><span class="glyphicon glyphicon-home"></span></span>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="control-label col-sm-3" for="location">Report Type:</label>
                                        <div class="col-sm-9">
                                            <div class="form-group row">
                                                <div class="col-sm-4">
                                                    <div class="radio-inline">
                                                        <label for="">
                                                            <input id="2" class="radio" type="radio" name="options" value="2" required checked/> Approved
                                                        </label>
                                                    </div>
                                                </div>
                                                <div class="col-sm-4">
                                                    <div class="radio-inline">
                                                        <label for="">
                                                            <input id="0" class="radio" type="radio" name="options" value="0" required /> Declined
                                                        </label>
                                                    </div>
                                                </div>
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

                                <hr>
                                <?php if((isset($from_date) && isset($to_date)) && isset($reports)):?>
                                    <div id="dvTable">
                                    <h5>Requisition Reports between <strong><?php echo $from_date." and ".$to_date; ?> </strong></h5>
                                    <p><strong>Total Expenses:</strong>₦<?php echo number_format($stats['total_expenses'], 2, ".", ","); ?></p>


                                    <table  class="table table-responsive table-striped table-bordered table-hover">
                                        <thead>
                                            <tr>
                                                <th>Date</th>
                                                <th>Item Description</th>
                                                <th>Total Cost</th>
                                                <th>Office Location</th>
                                                <th>Name</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                        <?php
                                        foreach ($reports as $row)
                                        {
                                            extract($row);
                                            //$order_list = $obj_acc_system->get_order_list($row['req_order_code']);
                                            //foreach ($order_list as $row2) {?>
                                                <tr>
                                                    <td><?php echo datetime_to_text($created); ?></td>
                                                    <td><?php echo $item_desc; ?></td>
                                                    <td>₦<?php echo number_format($app_total_cost, 2, ".", ","); ?></td>
                                                    <td><?php echo $location; ?></td>
                                                    <td><?php echo $author_name; ?></td>
                                                </tr>
                                                <?php
                                            }//}?>
                                        </tbody>
                                    </table>
                                    </div>
                                    <center>
                                        <button type="button" class="btn btn-sm btn-info" onclick="window.exportExcel()">Export table to Excel</button>
                                    </center>
                                <?php endif; ?>
                                
                                
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