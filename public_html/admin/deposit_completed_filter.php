<?php
require_once("../init/initialize_admin.php");
if (!$session_admin->is_logged_in()) {
    redirect_to("login.php");
}

////
if (isset($_POST['filter_deposit']) || isset($_GET['pg'])) {

    if (isset($_POST['filter_deposit'])) {

        foreach ($_POST as $key => $value) {
            $_POST[$key] = $db_handle->sanitizePost(trim($value));
        }

        extract($_POST);

        switch($trans_type) {
            // All transactions - No addition of any query
            case '1': $query_add = ""; break;

            // Cash Transactions at Diamond Estate Office
            case '2': $query_add = " AND deposit_origin = '2' "; break;

            // Cash Transactions at Eastline Office
            case '3': $query_add = " AND deposit_origin = '3' "; break;

            // GTPay card transactions
            case '4': $query_add = " AND client_pay_method = '1' "; break;

            // All Transactions - No addition of any query
            default: $query_add = "";
        }

        $query = "SELECT ud.trans_id, ud.dollar_ordered, ud.created, ud.naira_total_payable, ud.real_dollar_equivalent, ud.real_naira_confirmed,
            ud.client_naira_notified, ud.client_pay_date, ud.client_reference, ud.client_pay_method,
            ud.client_notified_date, ud.status AS deposit_status, u.user_code,
            ui.ifx_acct_no, CONCAT(u.last_name, SPACE(1), u.first_name) AS full_name, u.phone,
            uc.passport, ui.ifxaccount_id, ud.updated
            FROM user_deposit AS ud
            INNER JOIN user_ifxaccount AS ui ON ud.ifxaccount_id = ui.ifxaccount_id
            INNER JOIN user AS u ON ui.user_code = u.user_code
            LEFT JOIN user_credential AS uc ON ui.user_code = uc.user_code ";

        $where_clause = " WHERE ud.status = '8' ";
        $where_clause .= $query_add;
        $where_clause .= " AND (STR_TO_DATE(ud.created, '%Y-%m-%d') BETWEEN '$from_date' AND '$to_date') ORDER BY ud.updated DESC ";

        $query .= $where_clause;

        $_SESSION['search_client_query'] = $query;
        $_SESSION['search_client_where_clause'] = $where_clause;
        $_SESSION['from_date'] = $from_date;
        $_SESSION['to_date'] = $to_date;

    } else {
        // The lines below helps to keep the search query and the dates while going through results
        // by clicking on the pagination links.
        $query = $_SESSION['search_client_query'];
        $from_date = $_SESSION['from_date'];
        $to_date = $_SESSION['to_date'];
        $where_clause = $_SESSION['search_client_where_clause'];
    }

    $result = $db_handle->runQuery($query);
    $completed_deposit_requests_filter_export = $db_handle->fetchAssoc($result);

    $numrows = $db_handle->numRows($query);

    $rowsperpage = 20;

    $totalpages = ceil($numrows / $rowsperpage);
    // get the current page or set a default
    if (isset($_GET['pg']) && is_numeric($_GET['pg'])) {
        $currentpage = (int)$_GET['pg'];
    } else {
        $currentpage = 1;
    }
    if ($currentpage > $totalpages) {
        $currentpage = $totalpages;
    }
    if ($currentpage < 1) {
        $currentpage = 1;
    }

    $prespagelow = $currentpage * $rowsperpage - $rowsperpage + 1;
    $prespagehigh = $currentpage * $rowsperpage;
    if ($prespagehigh > $numrows) {
        $prespagehigh = $numrows;
    }

    $offset = ($currentpage - 1) * $rowsperpage;
    $query .= 'LIMIT ' . $offset . ',' . $rowsperpage;
    $result = $db_handle->runQuery($query);
    $completed_deposit_requests_filter = $db_handle->fetchAssoc($result);

    $query = "SELECT SUM(ud.real_naira_confirmed) AS total_real_naira_confirmed,
              SUM(ud.real_dollar_equivalent) AS total_real_dollar_equivalent
              FROM user_deposit AS ud
              INNER JOIN user_ifxaccount AS ui ON ud.ifxaccount_id = ui.ifxaccount_id
              INNER JOIN user AS u ON ui.user_code = u.user_code
              LEFT JOIN user_credential AS uc ON ui.user_code = uc.user_code ";
    $query .= $where_clause;
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
        <title>Instaforex Nigeria | Admin - Completed Deposit</title>
        <meta name="title" content="Instaforex Nigeria | Admin - Completed Deposit" />
        <meta name="keywords" content="" />
        <meta name="description" content="" />
        <?php require_once 'layouts/head_meta.php'; ?>
        <script src="//cdn.jsdelivr.net/alasql/0.3/alasql.min.js"></script>
        <script src="//cdnjs.cloudflare.com/ajax/libs/xlsx/0.7.12/xlsx.core.min.js"></script>
        <script src="//code.jquery.com/jquery-1.12.4.min.js" integrity="sha256-ZosEbRLbNQzLpnKIkEdrPv7lOy9C27hHQ+Xp8a4MxAQ=" crossorigin="anonymous"></script>
        <script src="//cdnjs.cloudflare.com/ajax/libs/jspdf/1.3.5/jspdf.min.js"></script>
        <script>
            (function () {
                var
                    form = $('#output'),
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
                            <h4><strong>COMPLETED DEPOSIT</strong></h4>
                        </div>
                    </div>
                    
                    <div class="section-tint super-shadow">
                        <div class="row">
                            <div class="col-sm-12">

                                <p class="text-right"><a href="deposit_completed.php"  class="btn btn-default" title="Completed Deposit"><i class="fa fa-arrow-circle-left"></i> Completed Deposit</a></p>
                                <p>Select date range below to get deposit transactions. Please note that when you filter, the result will be saved, if you
                                    want to get a new result, simply perform another filter.</p>

                                <form data-toggle="validator" class="form-horizontal" role="form" method="post" action="<?php echo $_SERVER['REQUEST_URI']; ?>">
                                    <div class="form-group">
                                        <label class="control-label col-sm-3" for="from_date">From:</label>
                                        <div class="col-sm-9 col-lg-5">
                                            <div class="input-group date">
                                                <input name="from_date" type="text" class="form-control" id="datetimepicker" value="<?php if(isset($from_date)) { echo $from_date; } ?>" required>
                                                <span class="input-group-addon"><span class="glyphicon glyphicon-calendar"></span></span>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="control-label col-sm-3" for="to_date">To:</label>
                                        <div class="col-sm-9 col-lg-5">
                                            <div class="input-group date">
                                                <input name="to_date" type="text" class="form-control" id="datetimepicker2" value="<?php if(isset($to_date)) { echo $to_date; } ?>" required>
                                                <span class="input-group-addon"><span class="glyphicon glyphicon-calendar"></span></span>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="control-label col-sm-3" for="trans_type">Transaction Category:</label>
                                        <div class="col-sm-9 col-lg-5">
                                            <select name="trans_type" class="form-control" id="trans_type">
                                                <option value="1" <?php if(isset($trans_type) && $trans_type == '1') { echo "selected"; } ?>>All Transactions</option>
                                                <option value="2" <?php if(isset($trans_type) && $trans_type == '2') { echo "selected"; } ?>>Cash - Diamond</option>
                                                <option value="3" <?php if(isset($trans_type) && $trans_type == '3') { echo "selected"; } ?>>Cash - Eastline</option>
                                                <option value="4" <?php if(isset($trans_type) && $trans_type == '4') { echo "selected"; } ?>>GTPay Transaction</option>
                                            </select>
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <div class="col-sm-offset-3 col-sm-9"><input name="filter_deposit" type="submit" class="btn btn-success" value="Filter" /></div>
                                    </div>
                                    <script type="text/javascript">
                                        $(function () {
                                            $('#datetimepicker, #datetimepicker2').datetimepicker({
                                                format: 'YYYY-MM-DD'
                                            });
                                        });
                                    </script>
                                </form>

                                <hr /><br />

                                <?php if(isset($completed_deposit_requests_filter) && !empty($completed_deposit_requests_filter)) { ?>

                                    <h5>Deposit transactions between <strong><?php echo $from_date . " and " . $to_date; ?> </strong></h5>
                                    <p><strong>Amount Paid: </strong>&#8358; <?php echo number_format($stats['total_real_naira_confirmed'], 2, ".", ","); ?></p>
                                    <p><strong>Amount Ordered: </strong>&dollar; <?php echo number_format($stats['total_real_dollar_equivalent'], 2, ".", ","); ?></p>

                                    <div>
                                        <p>Showing <?php echo $prespagelow . " to " . $prespagehigh . " of " . $numrows; ?> entries</p>
                                    </div>

                                    <p class="text-center">
                                        <a id="create_pdf" type="button" class="btn btn-sm btn-info" >Export Result to PDF</a>
                                        <a type="button" class="btn btn-sm btn-info" onclick="window.exportExcel()">Export Result to Excel</a>
                                    </p>
                                <?php } ?>

                                <?php if(isset($completed_deposit_requests_filter) && !empty($completed_deposit_requests_filter)) { require 'layouts/pagination_links.php'; } ?>

                                <table  class="table table-responsive table-striped table-bordered table-hover">
                                    <thead>
                                        <tr>
                                            <th>Transaction ID</th>
                                            <th>Client Name</th>
                                            <th>IFX Account</th>
                                            <th>Amount Funded</th>
                                            <th>Total Confirmed</th>
                                            <th>Date Created</th>
                                            <th>Last Updated</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php 
                                            if(isset($completed_deposit_requests_filter) && !empty($completed_deposit_requests_filter)) {
                                                foreach ($completed_deposit_requests_filter as $row) {
                                        ?>
                                        <tr>
                                            <td><a target="_blank" title="View" href="deposit_search_view.php?id=<?php echo encrypt($row['trans_id']); ?>"><?php echo $row['trans_id']; ?></a></td>
                                            <td><?php echo $row['full_name']; ?></td>
                                            <td><?php echo $row['ifx_acct_no']; ?></td>
                                            <td class="nowrap">&dollar; <?php echo number_format($row['real_dollar_equivalent'], 2, ".", ","); ?></td>
                                            <td class="nowrap">&#8358; <?php echo number_format($row['real_naira_confirmed'], 2, ".", ","); ?></td>
                                            <td><?php echo datetime_to_text($row['created']); ?></td>
                                            <td><?php echo datetime_to_text($row['updated']); ?></td>
                                        </tr>
                                        <?php } } else { echo "<tr><td colspan='7' class='text-danger'><em>No results to display</em></td></tr>"; } ?>
                                    </tbody>
                                </table>



                                <?php
                                if(isset($completed_deposit_requests_filter_export) && !empty($completed_deposit_requests_filter_export)) :
                                ?>
                                <div id="output" style="display: none">
                                    <?php if(isset($completed_deposit_requests_filter_export) && !empty($completed_deposit_requests_filter_export)) { ?>
                                        <h5>Deposit transactions between <strong><?php echo $from_date." and ".$to_date; ?> </strong></h5>
                                        <p><strong>Amount Paid: </strong>&#8358; <?php echo number_format($stats['total_real_naira_confirmed'], 2, ".", ","); ?></p>
                                        <p><strong>Amount Ordered: </strong>&dollar; <?php echo number_format($stats['total_real_dollar_equivalent'], 2, ".", ","); ?></p>

                                        <div class="tool-footer text-right">
                                            <p class="pull-left">Showing <?php echo $prespagelow . " to " . $prespagehigh . " of " . $numrows; ?> entries</p>
                                        </div>
                                    <?php } ?>
                                    <div class="container-fluid" >
                                        <table id="outputTable" class="table table-responsive table-striped table-bordered table-hover">
                                            <thead>
                                            <tr>
                                                <th>Transaction ID</th>
                                                <th>Client Name</th>
                                                <th>IFX Account</th>
                                                <th>Amount Funded</th>
                                                <th>Total Confirmed</th>
                                                <th>Date Created</th>
                                                <th>Last Updated</th>
                                            </tr>
                                            </thead>
                                            <tbody>
                                            <?php
                                            if(isset($completed_deposit_requests_filter_export) && !empty($completed_deposit_requests_filter_export)) {
                                                foreach ($completed_deposit_requests_filter_export as $row) {
                                                    ?>
                                                    <tr>
                                                        <td><?php echo $row['trans_id']; ?></td>
                                                        <td><?php echo $row['full_name']; ?></td>
                                                        <td><?php echo $row['ifx_acct_no']; ?></td>
                                                        <td class="nowrap">&dollar; <?php echo number_format($row['real_dollar_equivalent'], 2, ".", ","); ?></td>
                                                        <td class="nowrap">&#8358; <?php echo number_format($row['real_naira_confirmed'], 2, ".", ","); ?></td>
                                                        <td><?php echo datetime_to_text($row['created']); ?></td>
                                                        <td><?php echo datetime_to_text($row['updated']); ?></td>
                                                    </tr>
                                                <?php } } else { echo "<tr><td colspan='7' class='text-danger'><em>No results to display</em></td></tr>"; } ?>
                                            </tbody>
                                        </table>
                                    </div>

                                </div>
                                <?php endif; ?>

                                <script>
                                    window.exportExcel =     function exportExcel()
                                    {
                                        var filename = 'deposit_completed_filter'+Math.floor(Date.now() / 1000);
                                        alasql('SELECT * INTO XLSX("'+filename+'.xlsx",{headers:true}) FROM HTML("#outputTable",{headers:true})');
                                    }
                                </script>
                                <?php if(isset($completed_deposit_requests_filter) && !empty($completed_deposit_requests_filter)) { ?>
                                <div class="tool-footer text-right">
                                    <p class="pull-left">Showing <?php echo $prespagelow . " to " . $prespagehigh . " of " . $numrows; ?> entries</p>
                                </div>
                                <?php } ?>
                            </div>
                        </div>
                        
                        <?php if(isset($completed_deposit_requests_filter) && !empty($completed_deposit_requests_filter)) { require 'layouts/pagination_links.php'; } ?>
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