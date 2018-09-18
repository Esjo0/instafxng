<?php
require_once("../init/initialize_admin.php");
if (!$session_admin->is_logged_in()) {
    redirect_to("login.php");
}


if (isset($_POST['filter_deposit'])) {

    foreach ($_POST as $key => $value) {
        $_POST[$key] = $db_handle->sanitizePost(trim($value));
    }

    extract($_POST);

    $query = "SELECT ud.trans_id, ud.created AS last_deposit_date, ud.real_dollar_equivalent,
            u.user_code, u.email,
            ui.ifx_acct_no, CONCAT(u.last_name, SPACE(1), u.first_name) AS full_name, u.phone,
            ui.ifxaccount_id, uw.created AS last_withdrawal_date, tc.date_earned AS last_trading_date
          FROM user_deposit AS ud
          INNER JOIN user_ifxaccount AS ui ON ui.ifxaccount_id = ud.ifxaccount_id
          INNER JOIN user AS u ON u.user_code = ui.user_code
          INNER JOIN user_withdrawal AS uw ON ui.ifxaccount_id = uw.ifxaccount_id
          INNER JOIN trading_commission AS tc ON tc.ifx_acct_no = ui.ifx_acct_no
          LEFT JOIN user_credential AS uc ON ui.user_code = uc.user_code WHERE ud.status = '8' AND uw.status = '7' AND ud.real_dollar_equivalent > 100
          AND (STR_TO_DATE(ud.order_complete_time, '%Y-%m-%d') BETWEEN '$from_date' AND '$to_date')
          GROUP BY ud.ifxaccount_id  ORDER BY ud.created DESC";

} else {
    $query = "SELECT ud.trans_id, ud.created AS last_deposit_date, ud.real_dollar_equivalent,
            u.user_code, u.email,
            ui.ifx_acct_no, CONCAT(u.last_name, SPACE(1), u.first_name) AS full_name, u.phone,
            ui.ifxaccount_id, uw.created AS last_withdrawal_date, tc.date_earned AS last_trading_date
          FROM user_deposit AS ud
          INNER JOIN user_ifxaccount AS ui ON ui.ifxaccount_id = ud.ifxaccount_id
          INNER JOIN user AS u ON u.user_code = ui.user_code
          INNER JOIN user_withdrawal AS uw ON ui.ifxaccount_id = uw.ifxaccount_id
          INNER JOIN trading_commission AS tc ON tc.ifx_acct_no = ui.ifx_acct_no
          LEFT JOIN user_credential AS uc ON ui.user_code = uc.user_code WHERE ud.status = '8' AND uw.status = '7' AND ud.real_dollar_equivalent > 100
          GROUP BY ud.ifxaccount_id  ORDER BY ud.created DESC";
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
                            <div>
                                <p>Showing <?php echo $prespagelow . " to " . $prespagehigh . " of " . $numrows; ?> entries</p>
                            </div>

                            <?php if($allowed_export_profile) { ?>
                                <p class="text-center">
                                    <!--<a id="create_pdf" type="button" class="btn btn-sm btn-info" >Export Result to PDF</a>-->
                                    <a type="button" class="btn btn-sm btn-info" onclick="window.exportExcel()">Export Result to Excel</a>
                                </p>
                            <?php } ?>

                        <?php } ?>

                        <?php if(isset($completed_deposit_requests_filter) && !empty($completed_deposit_requests_filter)) { require 'layouts/pagination_links.php'; } ?>

                        <table  class="table table-responsive table-striped table-bordered table-hover">
                            <thead>
                            <tr>
                                <th>Transaction ID</th>
                                <th>Client Name</th>
                                <th>Phone</th>
                                <th>Email</th>
                                <th>IFX Account</th>
                                <th>Phone</th>
                                <th>Email</th>
                                <th>Amount Funded</th>
                                <th>LAST WITHDRAWAL DATE</th>
                                <th>LAST DEPOSIT DATE</th>
                                <th>LAST TRADING DATE</th>
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
                                        <td><?php echo $row['phone']; ?></td>
                                        <td><?php echo $row['email']; ?></td>
                                        <td class="nowrap">&dollar; <?php echo number_format($row['real_dollar_equivalent'], 2, ".", ","); ?></td>
                                        <td><?php echo datetime_to_text($row['last_withdrawal_date']); ?></td>
                                        <td><?php echo datetime_to_text($row['last_deposit_date']); ?></td>
                                        <td><?php echo datetime_to_text($row['last_trading_date']); ?></td>
                                    </tr>
                                <?php } } else { echo "<tr><td colspan='7' class='text-danger'><em>No results to display</em></td></tr>"; } ?>
                            </tbody>
                        </table>



                        <?php
                        if(isset($completed_deposit_requests_filter_export) && !empty($completed_deposit_requests_filter_export)) :
                            ?>
                            <div id="output" style="display: none">
                                <div class="container-fluid" >
                                    <table id="outputTable" class="table table-responsive table-striped table-bordered table-hover">
                                        <thead>
                                        <tr>
                                            <th>Transaction ID</th>
                                            <th>Client Name</th>
                                            <th>Phone</th>
                                            <th>Email</th>
                                            <th>IFX Account</th>
                                            <th>Phone</th>
                                            <th>Email</th>
                                            <th>Amount Funded</th>
                                            <th>LAST WITHDRAWAL DATE</th>
                                            <th>LAST DEPOSIT DATE</th>
                                            <th>LAST TRADING DATE</th>
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
                                                    <td><?php echo $row['phone']; ?></td>
                                                    <td><?php echo $row['email']; ?></td>
                                                    <td class="nowrap">&dollar; <?php echo number_format($row['real_dollar_equivalent'], 2, ".", ","); ?></td>
                                                    <td><?php echo datetime_to_text($row['last_withdrawal_date']); ?></td>
                                                    <td><?php echo datetime_to_text($row['last_deposit_date']); ?></td>
                                                    <td><?php echo datetime_to_text($row['last_trading_date']); ?></td>
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
                                var filename = 'VIP_clients_list'+Math.floor(Date.now() / 1000);
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