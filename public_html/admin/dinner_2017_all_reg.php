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
        case 'lagos':
            $query = "SELECT * FROM dinner_2017 WHERE state_of_residence = 'Lagos State' ORDER BY reservation_id DESC ";
            $showing_msg = "Showing Results for All Reservations With Lagos State as the state of residence.     ";
            break;
        default:
            $query = "SELECT * FROM dinner_2017 ORDER BY reservation_id DESC ";
            $showing_msg = "Showing All Reservations";
            break;
    }
} else {
    $query = "SELECT * FROM dinner_2017 ORDER BY reservation_id DESC ";
}

if(isset($state) && !empty($state))
{
    $query = "SELECT * FROM dinner_2017 WHERE state_of_residence = '$state' ORDER BY reservation_id DESC ";
    $showing_msg = "Showing Results for All $state Residents";
}
if(isset($key_word) && !empty($key_word))
{
    $query = "SELECT * FROM dinner_2017 WHERE email LIKE '%$key_word%' OR full_name LIKE '%$key_word%'";
    $showing_msg = "Showing Search Results For ".'"'.$key_word.'"';
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

                        <div class="row">
                            <div class="col-sm-12">
                                <div class="dropdown">
                                    <button class=" btn btn-info dropdown-toggle" type="button" id="menu1" data-toggle="dropdown">Apply Filter<span class="caret"></span></button>
                                    <ul class="dropdown-menu" role="menu" aria-labelledby="menu1">
                                        <li role="presentation"><a role="menuitem" tabindex="-1" title="See All Registrations" href="<?php echo $_SERVER['PHP_SELF'] . '?p=all'; ?>" >All</a></li>
                                        <li role="presentation"><a role="menuitem" tabindex="-1" title="Clients that responded Yes" href="<?php echo $_SERVER['PHP_SELF'] . '?p=yes'; ?>">Confirmed</a></li>
                                        <li role="presentation"><a role="menuitem" tabindex="-1" title="Clients that responded No" href="<?php echo $_SERVER['PHP_SELF'] . '?p=no'; ?>">Declined</a></li>
                                        <li role="presentation"><a role="menuitem" tabindex="-1" title="Clients that responded Maybe" href="<?php echo $_SERVER['PHP_SELF'] . '?p=maybe'; ?>">Waiting List</a></li>
                                        <li role="presentation"><a role="menuitem" tabindex="-1" title="Staff Reservations" href="<?php echo $_SERVER['PHP_SELF'] . '?p=staff'; ?>">Staff</a></li>
                                        <li role="presentation"><a role="menuitem" tabindex="-1" title="Hired Help Reservations" href="<?php echo $_SERVER['PHP_SELF'] . '?p=hired_help'; ?>">Hired Help</a></li>
                                        <li role="presentation"><a role="menuitem" tabindex="-1" title="Single Client Reservations" href="<?php echo $_SERVER['PHP_SELF'] . '?p=single'; ?>">Single Clients</a></li>
                                        <li role="presentation"><a role="menuitem" tabindex="-1" title="Plus One (Double) Client Reservations" href="<?php echo $_SERVER['PHP_SELF'] . '?p=double'; ?>">Plus One Clients</a></li>
                                        <li role="presentation"><a role="menuitem" tabindex="-1" title="Single VIP Reservations" href="<?php echo $_SERVER['PHP_SELF'] . '?p=vip_single'; ?>">Single VIP</a></li>
                                        <li role="presentation"><a role="menuitem" tabindex="-1" title="Plus One (Double) VIP Reservations" href="<?php echo $_SERVER['PHP_SELF'] . '?p=vip_double'; ?>">Plus One VIP</a></li>
                                        <li role="presentation" class="divider"></li>
                                        <li role="presentation"><a onclick="show_form('search_form')" role="menuitem" tabindex="-1" href="#">Search By Name or Email</a></li>
                                        <li role="presentation" class="divider"></li>
                                        <li role="presentation"><a onclick="show_form('state_form')" role="menuitem" tabindex="-1" href="#">Filter By State Of Residence</a></li>
                                    </ul>
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
                                    <button onclick="if(document.getElementById('search_item').value){window.location.href = '<?php echo $_SERVER['PHP_SELF'];?>'+'?q='+document.getElementById('search_item').value;}" class="btn btn-success" type="button">
                                        <span class="glyphicon glyphicon-search" aria-hidden="true"></span>
                                    </button>
                                </span>
                            </div>
                        </div>
                        <div id="state_form" style="display: none"  class="form-group">
                            <p>Select The State To Search...</p>
                            <?php
                            $query = "SELECT state_of_residence FROM dinner_2017";
                            $result = $db_handle->runQuery($query);
                            $states = $db_handle->fetchAssoc($result);
                            $states_list = array();
                            foreach ($states as $row)
                            {
                                $states_list[] = $row['state_of_residence'];
                            }
                            $states_list = array_unique($states_list);
                            sort($states_list);
                            ?>
                            <div class="input-group">
                                <select id="state" class="form-control">
                                    <?php foreach($states_list as $key => $value) { ?>
                                        <option value="<?php echo $value ?>"><?php echo $value ?></option>
                                    <?php }?>
                                </select>
                                <span class="input-group-btn">
                                    <button onclick="if(document.getElementById('state').value){window.location.href = '<?php echo $_SERVER['PHP_SELF'];?>'+'?x='+document.getElementById('state').value;}" class="btn btn-success" type="button">
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