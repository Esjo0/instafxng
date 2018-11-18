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

$result = $db_handle->numRows("SELECT * FROM dinner_2017 WHERE confirmation = '4' AND ticket_type IN ('0','2') ");
$a_c_s = $result;

$result = $db_handle->numRows("SELECT * FROM dinner_2017 WHERE confirmation = '4' AND ticket_type IN ('1','3') ");
$a_c_p = $result;




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
            $query = "SELECT * FROM dinner_2017 WHERE ticket_type = '0' ORDER BY reservation_id ASC ";
            $showing_msg = "Showing Results for All Single Client Reservations";
            break;
        case 'double':
            $query = "SELECT * FROM dinner_2017 WHERE ticket_type = '1' ORDER BY reservation_id ASC ";
            $showing_msg = "Showing Results for All Plus One Client Reservations";
            break;
        case 'vip_single':
            $query = "SELECT * FROM dinner_2017 WHERE ticket_type = '2' ORDER BY reservation_id ASC ";
            $showing_msg = "Showing Results for All Single VIP Reservations";
            break;
        case 'vip_double':
            $query = "SELECT * FROM dinner_2017 WHERE ticket_type = '3' ORDER BY reservation_id ASC ";
            $showing_msg = "Showing Results for All Plus One VIP Reservations";
            break;
        case 'lagos':
            $query = "SELECT * FROM dinner_2017 WHERE state_of_residence = 'Lagos State' ORDER BY reservation_id ASC ";
            $showing_msg = "Showing Results for All Reservations With Lagos State as the state of residence.     ";
            break;
        case 'attendance_confirmed':
            $query = "SELECT * FROM dinner_2017 WHERE confirmation = '4' ORDER BY reservation_id ASC ";
            $showing_msg = 'Showing Results for All Guests whose Attendance has been <b>CONFIRMED</b>.';
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
    $query = "SELECT * FROM dinner_2017 WHERE state_of_residence = '$state' ORDER BY reservation_id ASC ";
    $showing_msg = "Showing Results for All $state Residents";
}
if(isset($key_word) && !empty($key_word))
{
    $query = "SELECT * FROM dinner_2017 WHERE email LIKE '%$key_word%' OR full_name LIKE '%$key_word%'";
    $showing_msg = "Showing Search Results For ".'"'.$key_word.'"';
}

$export_list = $db_handle->fetchAssoc($db_handle->runQuery("SELECT * FROM dinner_2017 WHERE confirmation = '4' AND ticket_type IN ('0','2', '1', '3') "));

if(($_SERVER['REQUEST_METHOD'] == 'POST' && $_POST['iv_text'] == true))
{
    $query = "SELECT * FROM dinner_2017 WHERE confirmation = '4' AND ticket_type IN ('0', '1', '2', '3') ";
    $list = $db_handle->runQuery($query);
    $list = $db_handle->fetchAssoc($list);
    foreach ($list as $row)
    {
        extract($row);
        $client_phone = strtolower(trim($row['phone']));
        $my_message = "We look forward to seeing you tomorrow 
        17/12/17 by 5pm at Four Points by Sheraton Hotel,Plot 9/10, 
        Block 2, Oniru Chieftaincy Estate,Victoria Island,Lagos. Ticket No: ".strtoupper($reservation_code);
        $system_object->send_sms($client_phone, $my_message);
        $message_success = "You have successfully sent invites via Text Message.";
    }
    $query = "SELECT * FROM dinner_2017 WHERE confirmation = '4' ORDER BY reservation_id ASC ";
    $showing_msg = 'Showing Results for All Guests whose Attendance has been <b>CONFIRMED</b>.';
}

if(($_SERVER['REQUEST_METHOD'] == 'POST' && $_POST['iv_mail'] == true))
{
    $query = "SELECT * FROM dinner_2017 WHERE confirmation = '4' AND ticket_type IN ('0', '1', '2', '3') ";
    $list = $db_handle->runQuery($query);
    $list = $db_handle->fetchAssoc($list);
    foreach ($list as $row)
    {
        extract($row);
        $imgPath = '../images/final iv.jpg';
        $image = imagecreatefromjpeg($imgPath);
        $color = imagecolorallocate($image, 255, 255, 255);
        //NAME
        $string = strtoupper($full_name);
        $fontSize = 5;
        $x = 67;
        $y = 191;
        imagestring($image, $fontSize, $x, $y, $string, $color);
        //TICKET TYPE
        /*$string = strtoupper(dinner_ticket_type($ticket_type));
        $fontSize = 5;
        $x = 126;
        $y = 194;*/
        imagestring($image, $fontSize, $x, $y, $string, $color);
        //tICKET NO
        $string = strtoupper($reservation_code);
        $fontSize = 5;
        $x = 112;
        $y = 248;
        imagestring($image, $fontSize, $x, $y, $string, $color);

        //barcode
        $watermark = imagecreatefrompng('https://chart.googleapis.com/chart?chs=68x60&cht=qr&chl='.$reservation_code.'&choe=UTF-8');
        $water_width = imagesx($watermark);
        $water_height = imagesy($watermark);
        $main_width = imagesx($image);
        $main_height = imagesy($image);
        $dime_x = 626;
        $dime_y = 2;
        imagecopy($image, $watermark, $dime_x, $dime_y, 0, 0, $water_width, $water_height);
        $target_dir = "../dinner_2017/ivs/";

        if (!file_exists($target_dir))
        {
            mkdir($target_dir, 0777);
        }

        $newfilename = $reservation_code. '.jpg';
        $target_file = $target_dir.$newfilename;
        $ivs = imagejpeg($image, $target_file, 100);
        imagedestroy($image);

        $subject = "InstaFxNg Dinner 2017: THE ETHNIC IMPRESSION";
        $ticket_type = dinner_ticket_type($ticket_type);

        if($ivs)
        {
            $r_code = encrypt($reservation_code);
            $target_file = str_replace('../dinner_2017/', '', $target_file);
            $ticket_url = str_replace('ivs/', '', $target_file);
            $from_name ="";
            $message = <<<MAIL
            <div style="background-color: #F3F1F2">
            <div style="max-width: 80%; margin: 0 auto; padding: 10px; font-size: 14px; font-family: Verdana;">
            <img src="https://instafxng.com/images/ifxlogo.png" />
            <hr />
            <div style="background-color: #FFFFFF; padding: 15px; margin: 5px 0 5px 0;">
            <p>Yay! Its 5 days to the InstaFxNg Ethnic Impression Dinner.</p>
            <p>Did I mention that there will be a raffle draw and you can win amazing prizes during the InstaFxNg Ethnic Impression Dinner?</p>
			<p>Yea! We are all set to receive you on Sunday 17th December 2017 by 5PM and there's just one more thing to do...</p>
			<p>Kindly click on the image below to download your invite for the dinner.</p>
			<center><a href="https://instafxng.com/dinner_2017/ivs/download.php?x=$ticket_url"><img  style="width: 70%; height: 50%;" src="https://instafxng.com/dinner_2017/ivs/$ticket_url" ></a></center>
			<p>The invite grants you to the dinner and it will also be used in the raffle draw, 
			so endeavour to either download it on your mobile device or print it out and bring it along.</p>
            <p>I am really excited and I cannot wait to welcome you personally.</p>
            <p><a href="https://instafxng.com/dinner_2017/ivs/download.php?x=$ticket_url">Don't forget to download your ticket here.</a></p>
            <p>See you on Sunday by 5PM!</p>
            <br /><br />
            <p>Best Regards,</p>
            <p>Mercy,</p>
            <p>Marketing Executive,</p>
            <p>www.instafxng.com</p>
            <br /><br />
            </div>
            <hr />
            <div style="background-color: #EBDEE9;">
            <div style="font-size: 11px !important; padding: 15px;">
                <p style="text-align: center"><span style="font-size: 12px"><strong>We"re Social</strong></span><br /><br />
                    <a href="https://facebook.com/InstaFxNg"><img src="https://instafxng.com/images/Facebook.png"></a>
                    <a href="https://twitter.com/instafxng"><img src="https://instafxng.com/images/Twitter.png"></a>
                    <a href="https://www.instagram.com/instafxng/"><img src="https://instafxng.com/images/instagram.png"></a>
                    <a href="https://www.youtube.com/channel/UC0Z9AISy_aMMa3OJjgX6SXw"><img src="https://instafxng.com/images/Youtube.png"></a>
                    <a href="https://linkedin.com/company/instaforex-ng"><img src="https://instafxng.com/images/LinkedIn.png"></a>
                </p>
                <p><strong>Head Office Address:</strong> TBS Place, Block 1A, Plot 8, Diamond Estate, Estate Bus-Stop, LASU/Isheri road, Isheri Olofin, Lagos.</p>
                <p><strong>Lekki Office Address:</strong> Block A3, Suite 508/509 Eastline Shopping Complex, Opposite Abraham Adesanya Roundabout, along Lekki - Epe expressway, Lagos. </p>
                <p><strong>Office Number:</strong> 08028281192</p>
                <br />
            </div>
            <div style="font-size: 10px !important; padding: 15px; text-align: center;">
                <p>This email was sent to you by Instant Web-Net Technologies Limited, the
                    official Nigerian Representative of Instaforex, operator and administrator
                    of the website www.instafxng.com</p>
                <p>To ensure you continue to receive special offers and updates from us,
                    please add support@instafxng.com to your address book.</p>
            </div>
        </div>
    </div>
</div>
MAIL;
            $system_object->send_email($subject, $message, $email, $full_name, $from_name);
            $update = $db_handle->runQuery("UPDATE dinner_2017 SET invite = '1', iv_url = '$target_file' WHERE reservation_code = '$reservation_code'");
            $message_success = "You have successfully sent invites via E-mail.";
        }
        else
        {
            $message_error = "The action was not successfull.";
        }
    }
    $query = "SELECT * FROM dinner_2017 WHERE confirmation = '4' ORDER BY reservation_id ASC ";
    $showing_msg = 'Showing Results for All Guests whose Attendance has been <b>CONFIRMED</b>.';
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
    <script src="//cdn.jsdelivr.net/alasql/0.3/alasql.min.js"></script>
    <script src="//cdnjs.cloudflare.com/ajax/libs/xlsx/0.7.12/xlsx.core.min.js"></script>
    <script src="//code.jquery.com/jquery-1.12.4.min.js" integrity="sha256-ZosEbRLbNQzLpnKIkEdrPv7lOy9C27hHQ+Xp8a4MxAQ=" crossorigin="anonymous"></script>
    <script src="//cdnjs.cloudflare.com/ajax/libs/jspdf/1.3.5/jspdf.min.js"></script>
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
                        <p class="pull-right"><a href="dinner_2017_raffle_tickets.php" class="btn btn-default" title="Guests In Attendance">Guests In Attendance    <i class="fa fa-arrow-circle-right"></i></a></p>
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
                            <br />
                            <br />
                            <strong>Attendance Confirmed: </strong>
                            <br/>
                            Single <?php echo $a_c_s; ?> person(s).
                            <br/>
                            Plus One <?php echo $a_c_p; ?> person(s), each coming with a companion.
                            <br/>
                            Total: <?php echo $a_c_s + ($a_c_p * 2); ?> person(s)
                            <br />
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
                                        <li role="presentation"><a role="menuitem" tabindex="-1" title="Guest who have confirmed attendance." href="<?php echo $_SERVER['PHP_SELF'] . '?p=attendance_confirmed'; ?>">Confirmed Attendance</a></li>
                                        <li role="presentation" class="divider"></li>
                                        <li role="presentation"><a onclick="show_form('search_form')" role="menuitem" tabindex="-1" href="javascript:void(0);">Search By Name or Email</a></li>
                                        <li role="presentation" class="divider"></li>
                                        <li role="presentation"><a onclick="show_form('state_form')" role="menuitem" tabindex="-1" href="javascript:void(0);">Filter By State Of Residence</a></li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                        <br />
                        <?php if(isset($showing_msg)) { ?>
                        <p class="text-center"><?php echo $showing_msg; ?></p>
                        <?php if($showing_msg == "Showing Results for All Guests whose Attendance has been <b>CONFIRMED</b>.") { ?>

                                <form data-toggle="validator" class="form-horizontal" role="form" method="post" action="">
                                    <div class="row text-center">
                                        <div class="col-sm-12">
                                            <div class="btn-group btn-breadcrumb">
                                        <a type="button" class="btn btn-sm btn-info" onclick="window.exportExcel()">Export Result to Excel</a>
                                        <input name="iv_mail" type="submit" class="btn btn-sm btn-info" value="Send Invites (Mail)" title="Send Invites To This Category." />
                                        <input name="iv_text" type="submit" class="btn btn-sm btn-info" value="Send Invites (Text Message)" title="Send Invites To This Category." />
                                            </div>
                                        </div>
                                    </div>
                                </form>
                                <br/>
                                <br/>

                            <?php } ?>
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

                        <?php if(isset($dinner_reg) && !empty($dinner_reg)):?>
                            <div class="row text-center" style="padding: 0 25px;">
                                <div class="col-sm-12">
                                    <ul class="pagination pagination-sm">
                                        <?php
                                        $CurrentURL = getCurrentURL();
                                        $pos = stripos($CurrentURL, '&pg=');
                                        $num = strlen($CurrentURL);
                                        $CurrentURL = substr_replace($CurrentURL, '', $pos);//, $num - $pos
                                        $range = 4;

                                        // if not on page 1, don't show back links
                                        if ($currentpage > 1) {
                                            // show << link to go back to page 1
                                            echo "<li><a href=\"{$CurrentURL}&pg=1\">First</a></li>";
                                            // get previous page num
                                            $prevpage = $currentpage - 1;
                                            // show < link to go back to 1 page
                                            echo "<li><a href='{$CurrentURL}&pg=$prevpage'><</a><li>";
                                        }

                                        // loop to show links to range of pages around current page
                                        for ($x = ($currentpage - $range); $x < (($currentpage + $range) + 1); $x++) {
                                            // if it's a valid page number...
                                            if (($x > 0) && ($x <= $totalpages)) {
                                                // if we're on current page...
                                                if ($x == $currentpage) {
                                                    // 'highlight' it but don't make a link
                                                    echo "<li class=\"active\"><a href=''>$x</a></li>";
                                                    // if not current page...
                                                } else {
                                                    // make it a link
                                                    echo "<li><a href='{$CurrentURL}&pg=$x'>$x</a></li>";
                                                } // end else
                                            } // end if
                                        } // end for

                                        // if not on last page, show forward and last page links
                                        if ($currentpage != $totalpages) {
                                            // get next page
                                            $nextpage = $currentpage + 1;
                                            // echo forward link for next page
                                            echo "<li><a href='{$CurrentURL}&pg=$nextpage'>></a></li>";
                                            // echo forward link for lastpage
                                            echo "<li><a href='{$CurrentURL}&pg=$totalpages'>Last</a></li>";
                                        } // end if
                                        /****** end build pagination links ******/
                                        ?>
                                    </ul>
                                </div>
                            </div>
                        <?php endif; ?>

                        <div id="outputTable" style="display: none" >
                            <table class="table">
                                <thead>
                                <tr>
                                    <th>Full Name</th>
                                    <th>Email</th>
                                    <th>Phone Number</th>
                                    <th>Ticket Type</th>
                                    <th>Ticket No</th>
                                    <th>Confirmation Status</th>
                                    <th>Created</th>
                                    <th>State Of Residence</th>
                                    <th>Guests Comments</th>
                                </tr>
                                </thead>
                                <tbody>
                                <?php
                                if(isset($export_list) && !empty($export_list)) {
                                    foreach ($export_list as $row) {
                                        ?>
                                        <tr>
                                            <td><?php echo $row['full_name']; ?></td>
                                            <td><?php echo $row['email']; ?></td>
                                            <td><?php echo $row['phone']; ?></td>
                                            <td><?php echo dinner_ticket_type($row['ticket_type']); ?></td>
                                            <td><?php echo $row['reservation_code'] ?></td>
                                            <td><?php echo dinner_confirmation_status($row['confirmation']); ?></td>
                                            <td><?php echo datetime_to_text2($row['created']); ?></td>
                                            <td><?php echo $row['state_of_residence']; ?></td>
                                            <td><?php echo nl2br(htmlspecialchars($row['comments'])) ?></td>
                                        </tr>
                                    <?php } } else { echo "<tr><td colspan='8' class='text-danger'><em>No results to display</em></td></tr>"; } ?>
                                </tbody>
                            </table>
                        </div>
                        <script>
                            window.exportExcel =     function exportExcel()
                            {
                                var filename = 'Guest_list_'+Math.floor(Date.now() / 1000);
                                alasql('SELECT * INTO XLSX("'+filename+'.xlsx",{headers:true}) FROM HTML("#outputTable",{headers:true})');
                            }
                        </script>


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