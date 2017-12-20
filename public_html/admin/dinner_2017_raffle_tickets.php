<?php
require_once("../init/initialize_admin.php");
if (!$session_admin->is_logged_in())
{
    redirect_to("login.php");
}

$get_params = allowed_get_params(['p', 'x', 'q']);
$page = $get_params['p'];
$state = $get_params['x'];
$key_word = $get_params['q'];

$q = "SELECT * FROM dinner_2017 WHERE attended = '1' AND invite = '1' AND (ticket_type = '0' OR ticket_type = '1') ORDER BY reservation_id DESC ";
$q_entries = $db_handle->fetchAssoc($db_handle->runQuery($q));
$total_entries = $db_handle->numRows($q);

$result = $db_handle->numRows("SELECT * FROM dinner_2017 WHERE confirmation = '4' AND ticket_type IN ('0','2') AND attended = '1'");
$a_c_s = $result;

$result = $db_handle->numRows("SELECT * FROM dinner_2017 WHERE confirmation = '4' AND ticket_type IN ('1','3') AND attended = '1'");
$a_c_p = $result;

if(isset($page) && !empty($page))
{
    switch($page)
    {
        case 'all':
            $query = "SELECT * FROM dinner_2017 ORDER BY reservation_id DESC ";
            $showing_msg = "Showing Results for All Registered Guests";
            break;

        case 'yes':
            $query = "SELECT * FROM dinner_2017 WHERE confirmation = '2' ORDER BY reservation_id DESC ";
            $showing_msg = "Showing Results for Confirmed Registered Guests";
            break;

        case 'no':
            $query = "SELECT * FROM dinner_2017 WHERE confirmation = '3' ORDER BY reservation_id DESC ";
            $showing_msg = "Showing Results for Declined Registered Guests";
            break;

        case 'maybe':
            $query = "SELECT * FROM dinner_2017 WHERE confirmation = '1' ORDER BY reservation_id DESC ";
            $showing_msg = "Showing Results for the Waiting List (Maybe)";
            break;

        case 'staff':
            $query = "SELECT * FROM dinner_2017 WHERE ticket_type = '5' ORDER BY reservation_id DESC ";
            $showing_msg = "Showing Results for All Staff Reservations";
            break;

        case 'hired_help':
            $query = "SELECT * FROM dinner_2017 WHERE ticket_type = '4' ORDER BY reservation_id DESC ";
            $showing_msg = "Showing Results for All Hired Help Reservations";
            break;
        case 'single':
            $query = "SELECT * FROM dinner_2017 WHERE ticket_type = '0' ORDER BY reservation_id DESC ";
            $showing_msg = "Showing Results for All Single Client Reservations";
            break;
        case 'double':
            $query = "SELECT * FROM dinner_2017 WHERE ticket_type = '1' ORDER BY reservation_id DESC ";
            $showing_msg = "Showing Results for All Plus One Client Reservations";
            break;
        case 'vip_single':
            $query = "SELECT * FROM dinner_2017 WHERE ticket_type = '2' ORDER BY reservation_id DESC ";
            $showing_msg = "Showing Results for All Single VIP Reservations";
            break;
        case 'vip_double':
            $query = "SELECT * FROM dinner_2017 WHERE ticket_type = '3' ORDER BY reservation_id DESC ";
            $showing_msg = "Showing Results for All Plus One VIP Reservations";
            break;
        case 'lagos':
            $query = "SELECT * FROM dinner_2017 WHERE state_of_residence = 'Lagos State' ORDER BY reservation_id DESC ";
            $showing_msg = "Showing Results for All Reservations With Lagos State as the state of residence.     ";
            break;
        default:
            $query = "SELECT * FROM dinner_2017 ORDER BY reservation_id DESC ";
            $showing_msg = "Showing All Reservations";
            break;
    }
} else {    $query = "SELECT * FROM dinner_2017 WHERE attended = '1' ORDER BY reservation_id DESC "; }

$numrows = $db_handle->numRows($query);

$rowsperpage = 20;

$totalpages = ceil($numrows / $rowsperpage);

// get the current page or set a default
if (isset($_GET['pg']) && is_numeric($_GET['pg'])) {    $currentpage = (int) $_GET['pg'];} else {    $currentpage = 1;}
if ($currentpage > $totalpages) { $currentpage = $totalpages; }
if ($currentpage < 1) { $currentpage = 1; }
$prespagelow = $currentpage * $rowsperpage - $rowsperpage + 1;
$prespagehigh = $currentpage * $rowsperpage;
if($prespagehigh > $numrows) { $prespagehigh = $numrows; }
$offset = ($currentpage - 1) * $rowsperpage;
$query .= 'LIMIT ' . $offset . ',' . $rowsperpage;
$result = $db_handle->runQuery($query);
$dinner_reg = $db_handle->fetchAssoc($result);

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <base target="_self">
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Instaforex Nigeria | All 2017 Dinner Registrations</title>
    <meta name="title" content="Instaforex Nigeria | All Dinner Registration" />
    <meta name="keywords" content="" />
    <meta name="description" content="" />
    <?php require_once 'layouts/head_meta.php'; ?>
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
                    <h4><strong>DINNER RAFFLE TICKETS</strong></h4>
                </div>
            </div>

            <div class="section-tint super-shadow">
                <div class="row">
                    <div class="col-sm-12">

                        <p>Total Number of People In Attendance: <b><?php echo $a_c_s + ($a_c_p * 2); ?></b></p>

                        <div style="max-width: 100%" class="table-responsive">
                            <table class="table table-bordered table-condensed">
                                <thead>
                                    <tr>
                                        <th>Full Name</th>
                                        <th>Ticket Number</th>
                                    </tr>
                                </thead>
                                <tbody>
                                <?php
                                if(isset($dinner_reg) && !empty($dinner_reg)) {
                                    foreach ($dinner_reg as $row) {
                                        ?>
                                        <tr>
                                            <td><?php echo $row['full_name']; ?></td>
                                            <td><?php echo $row['reservation_code']; ?></td>
                                        </tr>
                                    <?php } } else { echo "<tr><td colspan='8' class='text-danger'><em>No results to display</em></td></tr>"; } ?>
                                </tbody>
                            </table>
                        </div>
                        <br />
                        <?php if(isset($q_entries) && !empty($q_entries)): ?>
                            <div id="output" style="display: none">
                                <?php if(isset($q_entries) && !empty($q_entries)) { ?>
                                    <div class="tool-footer text-right">
                                        <center><p class="text-center pull-left">Showing <?php echo $total_entries; ?> entries</p><center>
                                    </div>
                                <?php } ?>
                                <div class="container-fluid" >
                                    <table id="outputTable" class="table table-responsive table-striped table-bordered table-hover">
                                        <thead>
                                        <tr>
                                            <th>Full Name</th>
                                            <th>Ticket Number</th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                        <?php
                                        if(isset($q_entries) && !empty($q_entries)) {  foreach ($q_entries as $row) {  ?>
                                                <tr>
                                                    <td><?php echo $row['full_name']; ?></td>
                                                    <td><?php echo $row['reservation_code']; ?></td>
                                                </tr>
                                            <?php } } else { echo "<tr><td colspan='7' class='text-danger'><em>No results to display</em></td></tr>"; } ?>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                            <center><button onclick="print_report('output')" class="btn btn-info form-control">Print Tickets</button></center>
                        <?php endif; ?>
                        <br />

                        <?php if(isset($dinner_reg) && !empty($dinner_reg)) { ?>
                            <div class="tool-footer text-right">
                                <p class="pull-left">Showing <?php echo $prespagelow . " to " . $prespagehigh . " of " . $numrows; ?> entries</p>
                            </div>
                        <?php } ?>

                        <?php if(isset($dinner_reg) && !empty($dinner_reg)) { require_once 'layouts/pagination_links.php'; } ?>


                    </div>
                </div>

            </div>

            <!-- Unique Page Content Ends Here
            ================================================== -->

        </div>

    </div>
</div>
<?php require_once 'layouts/footer.php'; ?>
<script>
    $(document).ready(function(){
        $('[data-toggle="tooltip"]').tooltip();
    });
</script>
</body>
</html>