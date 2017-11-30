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

    $query = "SELECT 
          accounting_system_req_order.req_order_total AS req_order_total,
          accounting_system_req_order.req_order_code AS req_order_code, 
          accounting_system_req_order.req_order AS req_order, 
          accounting_system_req_order.created AS created, 
          accounting_system_req_order.status AS status,
          accounting_system_office_locations.location AS location,
          CONCAT(admin.first_name, SPACE(1), admin.last_name) AS author_name
          FROM admin, accounting_system_req_order, accounting_system_office_locations
          WHERE accounting_system_req_order.author_code = admin.admin_code
          AND accounting_system_req_order.location = accounting_system_office_locations.location_id
          AND STR_TO_DATE(accounting_system_req_order.created, '%Y-%m-%d') BETWEEN '$from_date' AND '$to_date' 
          ORDER BY accounting_system_req_order.created DESC ";

    //var_dump($query);

    $numrows = $db_handle->numRows($query);

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
    $query .= 'LIMIT ' . $offset . ',' . $rowsperpage;
    //var_dump($query);
    $result = $db_handle->runQuery($query);
    $reports = $db_handle->fetchAssoc($result);

    $month = date('F', mktime(0, 0, 0, $month, 10));

    $query = "SELECT SUM(accounting_system_req_order.req_order_total) AS total_expenses
              FROM accounting_system_req_order
              WHERE STR_TO_DATE(created, '%Y-%m-%d') BETWEEN '$from_date' AND '$to_date' ";
    $result = $db_handle->runQuery($query);
    $stats = $db_handle->fetchAssoc($result);
    $stats = $stats[0];
}

?>
<!DOCTYPE html>
<html lang="en">
    <head>
        <base target="_self">
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>Instaforex Nigeria | Admin - Accounting System</title>
        <meta name="title" content="Instaforex Nigeria | Admin - Saldo Report" />
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
                                <?php if(isset($reports)):?>
                                    <div id="divTable">
                                    <h5>Requisition Reports between <strong><?php echo $from_date." and ".$to_date; ?> </strong></h5>
                                    <p><strong>Total Expenses:</strong>₦<?php echo number_format($stats['total_expenses'], 2, ".", ","); ?></p>


                                    <table  class="table table-responsive table-striped table-bordered table-hover">
                                        <thead>
                                            <tr>
                                                <th>Name</th>
                                                <th>Office Location</th>
                                                <th>Order List</th>
                                                <th>Order Total (Actual Spent)</th>
                                                <th>Date</th>
                                            </tr>
                                        </thead>

                                        <tbody>
                                        <?php
                                        foreach ($reports as $row)
                                        { ?>
                                        <tr>
                                            <td><?php echo $row['author_name']; ?></td>
                                            <td><?php echo $row['location']; ?></td>
                                            <td>
                                                <button type="button" data-toggle="modal" data-target="#view_order<?php echo $row['req_order_code']; ?>" class="btn btn-default">View Order</button>
                                                <!-- Modal-- View Order List -->
                                                <div id="view_order<?php echo $row['req_order_code']; ?>" class="modal fade" role="dialog">
                                                    <div class="modal-dialog">
                                                        <!-- Modal content-->
                                                        <form data-toggle="validator" class="form-horizontal" role="form" method="post" action="<?php echo $_SERVER['REQUEST_URI']; ?>">
                                                            <div class="modal-content">
                                                                <div class="modal-header">
                                                                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                                                                    <h4 class="modal-title">REQUISITION ORDER</h4>
                                                                </div>
                                                                <div class="modal-body">
                                                                    <p><b>AUTHOR NAME:</b> <?php echo $row['author_name']; ?></p>
                                                                    <p><b>DATE:</b> <?php echo $row['created']; ?></p>

                                                                    <?php echo $row['req_order'];?>
                                                                <div class="modal-footer">
                                                                    <button name="print" onclick="print_report('printout<?php echo $row['req_order_code']; ?>')" type="button" class="btn btn-info">Print</button>
                                                                    <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
                                                                </div>
                                                            </div>
                                                        </form>
                                                    </div>
                                                </div>
                                            </td>
                                            <td>₦<?php echo number_format($row['req_order_total'], 2, ".", ","); ?></td>
                                            <td><?php echo datetime_to_text2($row['created']); ?></td>
                                        </tr>
                                        <?php
                                        }?>
                                        </tbody>
                                    </table>
                                    </div>
                                    <center>
                                        <button id="create_pdf" type="button" class="btn btn-sm btn-info" >Export table to PDF</button>
                                        <script src="//code.jquery.com/jquery-1.12.4.min.js" integrity="sha256-ZosEbRLbNQzLpnKIkEdrPv7lOy9C27hHQ+Xp8a4MxAQ=" crossorigin="anonymous"></script>
                                        <script src="//cdnjs.cloudflare.com/ajax/libs/jspdf/1.3.5/jspdf.min.js"></script>
                                        <script>
                                            (function () {
                                                var
                                                    form = $('#divTable'),
                                                    cache_width = form.width(),
                                                    a4 = [595.28, 841.89]; // for a4 size paper width and height

                                                $('#create_pdf').on('click', function () {
                                                    $('body').scrollTop(0);
                                                    createPDF();
                                                });
                                                //create pdf
                                                function createPDF() {
                                                    getCanvas().then(function (canvas) {
                                                        var
                                                            img = canvas.toDataURL("image/png"),
                                                            doc = new jsPDF({ unit: 'px',     format: 'a4'    });
                                                        doc.addImage(img, 'JPEG', 20, 20);
                                                        var filename = 'requisition_reports_'+Math.floor(Date.now() / 1000);
                                                        doc.save(filename+'.pdf');
                                                        form.width(cache_width);
                                                    });
                                                }

                                                // create canvas object
                                                function getCanvas() {
                                                    form.width((a4[0] * 1.33333) - 80).css('max-width', 'none');
                                                    return html2canvas(form, {
                                                        imageTimeout: 2000,
                                                        removeContainer: true
                                                    });
                                                }

                                            }());
                                        </script>
                                        <script>
                                            /*
                                             * jQuery helper plugin for examples and tests
                                             */
                                            (function ($) {
                                                $.fn.html2canvas = function (options) {
                                                    var date = new Date(),
                                                        $message = null,
                                                        timeoutTimer = false,
                                                        timer = date.getTime();
                                                    html2canvas.logging = options && options.logging;
                                                    html2canvas.Preload(this[0], $.extend({
                                                        complete: function (images) {
                                                            var queue = html2canvas.Parse(this[0], images, options),
                                                                $canvas = $(html2canvas.Renderer(queue, options)),
                                                                finishTime = new Date();

                                                            $canvas.css({ position: 'absolute', left: 0, top: 0 }).appendTo(document.body);
                                                            $canvas.siblings().toggle();

                                                            $(window).click(function () {
                                                                if (!$canvas.is(':visible')) {
                                                                    $canvas.toggle().siblings().toggle();
                                                                    throwMessage("Canvas Render visible");
                                                                } else {
                                                                    $canvas.siblings().toggle();
                                                                    $canvas.toggle();
                                                                    throwMessage("Canvas Render hidden");
                                                                }
                                                            });
                                                            throwMessage('Screenshot created in ' + ((finishTime.getTime() - timer) / 1000) + " seconds<br />", 4000);
                                                        }
                                                    }, options));

                                                    function throwMessage(msg, duration) {
                                                        window.clearTimeout(timeoutTimer);
                                                        timeoutTimer = window.setTimeout(function () {
                                                            $message.fadeOut(function () {
                                                                $message.remove();
                                                            });
                                                        }, duration || 2000);
                                                        if ($message)
                                                            $message.remove();
                                                        $message = $('<div ></div>').html(msg).css({
                                                            margin: 0,
                                                            padding: 10,
                                                            background: "#000",
                                                            opacity: 0.7,
                                                            position: "fixed",
                                                            top: 10,
                                                            right: 10,
                                                            fontFamily: 'Tahoma',
                                                            color: '#fff',
                                                            fontSize: 12,
                                                            borderRadius: 12,
                                                            width: 'auto',
                                                            height: 'auto',
                                                            textAlign: 'center',
                                                            textDecoration: 'none'
                                                        }).hide().fadeIn().appendTo('body');
                                                    }
                                                };
                                            })(jQuery);

                                        </script>
                                        <button type="button" class="btn btn-sm btn-info" onclick="window.exportExcel()">Export table to Excel</button>
                                    </center>
                                <?php endif; ?>
                                
                                
                            </div>
                        </div>

                    </div>

                    <!-- Unique Page Content Ends Here
                    ================================================== -->
                    <script>
                        window.exportExcel =     function exportExcel()
                        {
                            var filename = 'deposit_completed_filter'+Math.floor(Date.now() / 1000);
                            alasql('SELECT * INTO XLSX("'+filename+'.xlsx",{headers:true}) FROM HTML("#dvTable",{headers:true})');
                        }
                    </script>
                </div>
                
            </div>
        </div>
        <?php require_once 'layouts/footer.php'; ?>
        <script src="//cdnjs.cloudflare.com/ajax/libs/moment.js/2.9.0/moment-with-locales.js"></script>
        <script src="//cdn.rawgit.com/Eonasdan/bootstrap-datetimepicker/e8bddc60e73c1ec2475f827be36e1957af72e2ea/src/js/bootstrap-datetimepicker.js"></script>
    </body>
</html>