<?php
require_once("../init/initialize_admin.php");
if (!$session_admin->is_logged_in())
{
    redirect_to("login.php");
}

$get_params = allowed_get_params(['p']);
$page = $get_params['p'];

$result = $db_handle->numRows("SELECT * FROM dinner_2017 WHERE confirmation = '0'");
$interested_notyet = $result;

$result = $db_handle->numRows("SELECT * FROM dinner_2017 WHERE confirmation = '2'");
$interested_yes = $result;

$result = $db_handle->numRows("SELECT * FROM dinner_2017 WHERE confirmation = '3'");
$interested_no = $result;

$result = $db_handle->numRows("SELECT * FROM dinner_2017 WHERE confirmation = '1'");
$interested_maybe = $result;




$result = $db_handle->numRows("SELECT * FROM dinner_2017 WHERE ticket_type = '5'");
$total_staff = $result;

$result = $db_handle->numRows("SELECT * FROM dinner_2017 WHERE ticket_type = '4'");
$total_hired_help = $result;

$result = $db_handle->numRows("SELECT * FROM dinner_2017 WHERE ticket_type = '0'");
$total_single_clients = $result;

$result = $db_handle->numRows("SELECT * FROM dinner_2017 WHERE ticket_type = '1'");
$total_double_clients = $result;

$result = $db_handle->numRows("SELECT * FROM dinner_2017 WHERE ticket_type = '2'");
$total_vip_single_clients = $result;

$result = $db_handle->numRows("SELECT * FROM dinner_2017 WHERE ticket_type = '3'");
$total_vip_double_clients = $result;

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
        default:
            $query = "SELECT * FROM dinner_2017 WHERE email LIKE '%$page%' OR full_name LIKE '%$page%' OR state_of_residence LIKE '%$page%' ORDER BY reservation_id DESC ";
            $showing_msg = "Showing Search Results For ".'"'.$page.'"';
            break;
    }
} else {
    $query = "SELECT * FROM dinner_2017 ORDER BY reservation_id DESC ";
}

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
        function show_form(div)
        {
            var x = document.getElementById(div);
            if (x.style.display === 'none')
            {
                x.style.display = 'block';
            }
            else
            {
                x.style.display = 'none';
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
                    <h4><strong>ALL DINNER RESERVATION</strong></h4>
                </div>
            </div>

            <div class="section-tint super-shadow">
                <div class="row">
                    <div class="col-sm-12">
                        <?php require_once 'layouts/feedback_message.php'; ?>
                        <p>Details of 2017 Dinner Reservations.</p>
                        <p>Reservation Insight: <br/><br/>
                            <strong>Pending:</strong> <?php echo $interested_notyet; ?><br />
                            <strong>Confirmed:</strong> <?php echo $interested_yes; ?><br />
                            <strong>Declined:</strong> <?php echo $interested_no; ?><br />
                            <strong>Maybe:</strong> <?php echo $interested_maybe; ?><br />

                            <strong>Staff:</strong> <?php echo $total_staff; ?><br />
                            <strong>Hired Help:</strong> <?php echo $total_hired_help; ?><br />
                            <strong>Client Single:</strong> <?php echo $total_single_clients; ?><br />
                            <strong>Client Plus One (Double):</strong> <?php echo $total_double_clients; ?><br />
                            <strong>VIP Single:</strong> <?php echo $total_vip_single_clients; ?><br />
                            <strong>VIP Plus One (Double):</strong> <?php echo $total_vip_double_clients; ?><br />
                        </p>

                        <!--<p><strong>Attended:</strong> <?php /*echo $attended; */?></p>-->

                        <div class="row text-center">
                            <div class="col-sm-12">
                                <div class="btn-group-sm btn-block  btn-breadcrumb">
                                    <a href="<?php echo $_SERVER['PHP_SELF'] . '?p=all'; ?>" class="btn btn-default" title="See All Registrations">All</a>
                                    <a href="<?php echo $_SERVER['PHP_SELF'] . '?p=yes'; ?>" class="btn btn-default" title="Clients that responded Yes">Confirmed</a>
                                    <a href="<?php echo $_SERVER['PHP_SELF'] . '?p=no'; ?>" class="btn btn-default" title="Clients that responded No">Declined</a>
                                    <a href="<?php echo $_SERVER['PHP_SELF'] . '?p=maybe'; ?>" class="btn btn-default" title="Clients that responded Maybe">Waiting List</a>
                                    <a href="<?php echo $_SERVER['PHP_SELF'] . '?p=staff'; ?>" class="btn btn-default" title="Staff Reservations">Staff</a>
                                    <a href="<?php echo $_SERVER['PHP_SELF'] . '?p=hired_help'; ?>" class="btn btn-default" title="Hired Help Reservations">Hired Help</a>
                                    <a href="<?php echo $_SERVER['PHP_SELF'] . '?p=single'; ?>" class="btn btn-default" title="Single Client Reservations">Single Clients</a>
                                    <a href="<?php echo $_SERVER['PHP_SELF'] . '?p=double'; ?>" class="btn btn-default" title="Plus One (Double) Client Reservations">Plus One Clients</a>
                                    <a href="<?php echo $_SERVER['PHP_SELF'] . '?p=vip_single'; ?>" class="btn btn-default" title="Single VIP Reservations">Single VIP</a>
                                    <a href="<?php echo $_SERVER['PHP_SELF'] . '?p=vip_double'; ?>" class="btn btn-default" title="Plus One (Double) VIP Reservations">Plus One VIP</a>
                                    <button onclick="show_form('search_form')" class="btn btn-default" title="Search Through Reservations">Search</button>
                                </div>
                            </div>
                        </div>
                        <br />

                        <?php if(isset($showing_msg)) { ?>
                        <p class="text-center"><?php echo $showing_msg; ?></p>
                        <?php } ?>
                        <div id="search_form" style="display: none;" class="form-group">
                            <p>Enter the clients name or an email address to search...</p>
                            <div class="input-group">
                                <input class="form-control" type="text" id="search_item" name="search" placeholder="Search" required/>
                                <span class="input-group-btn">
                                    <button onclick="if(document.getElementById('search_item').value){window.location.href = '<?php echo $_SERVER['PHP_SELF'];?>'+'?p='+document.getElementById('search_item').value;}" class="btn btn-success" type="button">
                                        <span class="glyphicon glyphicon-search" aria-hidden="true"></span>
                                    </button>
                                </span>
                            </div>
                        </div>

                        <div style="max-width: 100%" class="table-responsive">
                            <table class="table table-bordered table-condensed">
                                <thead>
                                    <tr>
                                        <th>Full Name</th>
                                        <th>Email</th>
                                        <th>Phone Number</th>
                                        <th>Ticket Type</th>
                                        <th>Confirmation Status</th>
                                        <th>Created</th>
                                        <th>State Of Residence</th>
                                        <th>Comments</th>
                                        <th></th>
                                    </tr>
                                </thead>
                                <tbody>
                                <?php
                                if(isset($dinner_reg) && !empty($dinner_reg)) {
                                    foreach ($dinner_reg as $row) {
                                        ?>
                                        <tr>
                                            <td><?php echo $row['full_name']; ?></td>
                                            <td><?php echo $row['email']; ?></td>
                                            <td><?php echo $row['phone']; ?></td>
                                            <td><?php echo dinner_ticket_type($row['ticket_type']); ?></td>
                                            <td><?php echo dinner_confirmation_status($row['confirmation']); ?></td>
                                            <td><?php echo datetime_to_text2($row['created']); ?></td>
                                            <td><?php echo $row['state_of_residence']; ?></td>
                                            <td><?php if(strlen(nl2br(htmlspecialchars($row['comments']))) > 40){echo "<a href='#' data-toggle='tooltip' title='".nl2br(htmlspecialchars($row['comments']))."'>".substr(nl2br(htmlspecialchars($row['comments'])), 0, 40)."...";} else {echo nl2br(htmlspecialchars($row['comments']));}?></td>
                                            <td>
                                                <a title="View" class="btn btn-info" href="dinner_2017_view_reg.php?id=<?php echo encrypt($row['reservation_code']); ?>"><i class="glyphicon glyphicon-arrow-right icon-white"></i></a>
                                            </td>
                                        </tr>
                                    <?php } } else { echo "<tr><td colspan='8' class='text-danger'><em>No results to display</em></td></tr>"; } ?>
                                </tbody>
                            </table>
                        </div>
                        <br /><br />

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