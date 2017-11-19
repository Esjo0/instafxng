<?php
require_once("../init/initialize_admin.php");
if (!$session_admin->is_logged_in()) {
    redirect_to("login.php");
}
function getWorkingDays($startDate,$endDate,$holidays)
{
    // do strtotime calculations just once
    $endDate = strtotime($endDate);
    $startDate = strtotime($startDate);


    //The total number of days between the two dates. We compute the no. of seconds and divide it to 60*60*24
    //We add one to inlude both dates in the interval.
    $days = ($endDate - $startDate) / 86400 + 1;

    $no_full_weeks = floor($days / 7);
    $no_remaining_days = fmod($days, 7);

    //It will return 1 if it's Monday,.. ,7 for Sunday
    $the_first_day_of_week = date("N", $startDate);
    $the_last_day_of_week = date("N", $endDate);

    //---->The two can be equal in leap years when february has 29 days, the equal sign is added here
    //In the first case the whole interval is within a week, in the second case the interval falls in two weeks.
    if ($the_first_day_of_week <= $the_last_day_of_week) {
        if ($the_first_day_of_week <= 6 && 6 <= $the_last_day_of_week) $no_remaining_days--;
        if ($the_first_day_of_week <= 7 && 7 <= $the_last_day_of_week) $no_remaining_days--;
    }
    else {
        // (edit by Tokes to fix an edge case where the start day was a Sunday
        // and the end day was NOT a Saturday)

        // the day of the week for start is later than the day of the week for end
        if ($the_first_day_of_week == 7) {
            // if the start date is a Sunday, then we definitely subtract 1 day
            $no_remaining_days--;

            if ($the_last_day_of_week == 6) {
                // if the end date is a Saturday, then we subtract another day
                $no_remaining_days--;
            }
        }
        else {
            // the start date was a Saturday (or earlier), and the end date was (Mon..Fri)
            // so we skip an entire weekend and subtract 2 days
            $no_remaining_days -= 2;
        }
    }

    //The no. of business days is: (number of weeks between the two dates) * (5 working days) + the remainder
//---->february in none leap years gave a remainder of 0 but still calculated weekends between first and last day, this is one way to fix it
    $workingDays = $no_full_weeks * 5;
    if ($no_remaining_days > 0 )
    {
        $workingDays += $no_remaining_days;
    }

    //We subtract the holidays
    foreach($holidays as $holiday){
        $time_stamp=strtotime($holiday);
        //If the holiday doesn't fall in weekend
        if ($startDate <= $time_stamp && $time_stamp <= $endDate && date("N",$time_stamp) != 6 && date("N",$time_stamp) != 7)
            $workingDays--;
    }

    return $workingDays;
}


$req_time = "08:30:00";
if (isset($_POST['filter_deposit']) || isset($_GET['pg']))
{

    if (isset($_POST['filter_deposit']))
    {

        foreach ($_POST as $key => $value)
        {
            $_POST[$key] = $db_handle->sanitizePost(trim($value));
        }

        extract($_POST);
        $query = "SELECT * FROM admin ";

        $holidays = array();
        global $total_week_days;
        $total_week_days = getWorkingDays($from_date, $to_date, $holidays);
        //$query = "SELECT * FROM hr_attendance_system WHERE (STR_TO_DATE(created, '%d-%m-%Y') BETWEEN '$from_date' AND '$to_date') ORDER BY created ASC ";

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
        <!--<script src="//code.jquery.com/jquery-1.12.4.min.js" integrity="sha256-ZosEbRLbNQzLpnKIkEdrPv7lOy9C27hHQ+Xp8a4MxAQ=" crossorigin="anonymous"></script>
        <script src="//cdnjs.cloudflare.com/ajax/libs/jspdf/1.3.5/jspdf.min.js"></script>-->
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
                            <h4><strong>ATTENDANCE LOGS - FILTER</strong></h4>
                        </div>
                    </div>
                    
                    <div class="section-tint super-shadow">
                        <div class="row">
                            <div class="col-sm-12">

                                <p class="text-left"><a href="hr_attendance_system_logs.php"  class="btn btn-default" title="Attendance Logs"><i class="fa fa-arrow-circle-left"></i> Attendance Logs</a></p>
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
                                                format: 'DD-MM-YYYY'
                                            });
                                        });
                                    </script>
                                </form>

                                <hr /><br />

                                <?php if(isset($completed_deposit_requests_filter) && !empty($completed_deposit_requests_filter)) { ?>

                                    <div>
                                        <p>Showing <?php echo $prespagelow . " to " . $prespagehigh . " of " . $numrows; ?> entries</p>
                                    </div>

                                    <p class="text-center">
                                        <a type="button" class="btn btn-sm btn-info" onclick="window.exportExcel()">Export Result to Excel</a>
                                    </p>
                                <?php } ?>

                                <?php if(isset($completed_deposit_requests_filter) && !empty($completed_deposit_requests_filter)) { require 'layouts/pagination_links.php'; } ?>

                                <table id="outputTable" class="table table-responsive table-striped table-bordered table-hover">
                                    <thead>
                                        <tr>
                                            <th>Name</th>
                                            <th>Total Days Present</th>
                                            <th>Attendance Percentage</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php 
                                            if(isset($completed_deposit_requests_filter) && !empty($completed_deposit_requests_filter)) {
                                                foreach ($completed_deposit_requests_filter as $row) {
                                        ?>
                                        <tr>
                                            <td>
                                                <?php echo $row['last_name'] . ' ' . $row['middle_name'] . ' ' . $row['first_name']; ?>
                                            </td>
                                            <td>
                                                <?php
                                                    $admin_code = $row['admin_code'];
                                                    $query = "SELECT * FROM hr_attendance_log WHERE admin_code = '$admin_code' AND (hr_attendance_log.date BETWEEN '$from_date' AND '$to_date')";
                                                    $result = $db_handle->numOfRows($db_handle->runQuery($query));
                                                    echo $result;
                                                ?>
                                            </td>
                                            <td>
                                                <?php
                                                $query = "SELECT * FROM hr_attendance_log WHERE admin_code = '$admin_code' AND (hr_attendance_log.date BETWEEN '$from_date' AND '$to_date')";
                                                $result = $db_handle->runQuery($query);
                                                $result = $db_handle->fetchAssoc($result);
                                                $count = 0;
                                                foreach ($result as $row1)
                                                {
                                                    if(date('H:i:s',strtotime($row1['time'])) <= date('H:i:s',strtotime($req_time)))
                                                    {
                                                        $count = $count + 1;
                                                    }
                                                }
                                                $count = ($count *100) / $total_week_days;
                                                echo round($count, 2).'%';
                                                ?>
                                            </td>
                                        </tr>
                                        <?php } } else { echo "<tr><td colspan='3' class='text-danger'><em>No results to display</em></td></tr>"; } ?>
                                    </tbody>
                                </table>
                                <script>
                                    window.exportExcel =     function exportExcel()
                                    {
                                        var filename = 'attendance_logs_<?php echo $from_date.'_to_'.$to_date ?>';
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