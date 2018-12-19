<?php
require_once("../init/initialize_admin.php");
if (!$session_admin->is_logged_in()) {
    redirect_to("login.php");
}

if(isset($_POST['update'])){

    $id = $db_handle->sanitizePost($_POST['id']);
    $venue = $db_handle->sanitizePost($_POST['venue']);
    if($venue == 1){$date = $db_handle->sanitizePost($_POST['date1']);}
    if($venue == 2){$date = $db_handle->sanitizePost($_POST['date2']);}
    if($venue == 3){$date = $db_handle->sanitizePost($_POST['date3']);}
    $query = "UPDATE account_audit SET audit_date = '$date', audit_location = '$venue' WHERE id = $id";
    $result = $db_handle->runQuery($query);
    if($result){
        $message_success = "Successfully Update Client Audit date";
    }else{
        $message_error = "Not successful.";
    }
}

$query = "SELECT a.id, u.user_code, a.audit_location, a.audit_date, a.created, u.email AS email, u.phone AS phone, CONCAT(u.last_name, SPACE(1), u.first_name) AS full_name
    FROM account_audit AS a
INNER JOIN user AS u ON a.user_code = u.user_code
    ORDER BY a.audit_date DESC ";
$_SESSION['query'] = $query;

$query = $_SESSION['query'];

$numrows = $db_handle->numRows($query);

$rowsperpage = 40;

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
$participants = $db_handle->fetchAssoc($result);

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <base target="_self">
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Instaforex Nigeria | Admin - Account Audit</title>
    <meta name="title" content="Instaforex Nigeria | Admin - Dinner 2018"/>
    <meta name="keywords" content=""/>
    <meta name="description" content=""/>
    <?php require_once 'layouts/head_meta.php'; ?>
    <script>
        function proceed() {
            document.getElementById("proceed").style.display = "block";
            document.getElementById("pro").style.display = "none";
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

                <div class="col-sm-8 text-danger">
                    <h4><strong>List of Clients who have registered for account audit</strong></h4>
                </div>

                <div class="pull-right">
                    <button type="button" data-target="#schedule_date" data-toggle="modal"
                            class="btn btn-sm btn-success"> Schedule Audit date
                    </button>
                </div>

                <!--Modal - confirmation boxes-->
                <div id="schedule_date" tabindex="-1" role="dialog" aria-hidden="true"
                     class="modal fade">
                    <form class="form-inline text-center" role="form" method="post" action="">
                        <div class="modal-dialog modal-lg">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <button type="button" data-dismiss="modal" aria-hidden="true"
                                            class="close">&times;</button>
                                    <h4 class="modal-title">Schedule Personal Training Time</h4>
                                </div>
                                <div class="modal-body">
                                    <div class="form-group mx-sm-4 mb-2">
                                        <label for="input" class="sr-only">Training Time</label>
                                        <input name="schedule_time_private" type="text"
                                               id="pickdateprivate" class="form-control"
                                               placeholder="Enter Training Date" required>
                                    </div>
                                    <script type="text/javascript">
                                        $(function () {
                                            $('#pickdateprivate').datetimepicker({
                                                format: 'YYYY-MM-DD HH:mm'
                                            });
                                        });
                                    </script>

                                    <div class="form-group mx-sm-4 mb-2">
                                        <select type="text" name="location" class="form-control "
                                                id="location">
                                            <?php
                                            $query = "SELECT * FROM facility_location";
                                            $result = $db_handle->runQuery($query);
                                            $result = $db_handle->fetchAssoc($result);
                                            foreach ($result as $row_loc) {
                                                extract($row_loc)
                                                ?>
                                                <option
                                                    value="<?php echo $location_id; ?>"><?php echo $location; ?></option>
                                            <?php } ?>
                                        </select>
                                    </div>

                                    <div class="form-group mx-sm-4 mb-2">
                                        <select name="mode" id="mode" class="form-control" required>
                                            <option value="">Select Training Type</option>
                                            <option value="1">Online</option>
                                            <option value="2">Offline</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="modal-footer">
                                    <input name="schedule_private" type="submit"
                                           class="btn btn-sm btn-default" value="SUBMIT">
                                    <button type="button" name="close" onClick="window.close();"
                                            data-dismiss="modal" class="btn btn-sm btn-danger">Close!
                                    </button>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>

            <div class="section-tint super-shadow">
                <div class="row">
                    <div class="col-sm-12">
                        <?php include 'layouts/feedback_message.php'; ?>
                        <table class="table table-responsive table-striped table-bordered table-hover">
                            <thead>
                            <tr>
                                <th>Client Name</th>
                                <th>Date</th>
                                <th>Email Address</th>
                                <th>Phone Number</th>
                                <th>Audit Venue</th>
                                <th>Audit Date</th>
                                <th></th>
                            </tr>
                            </thead>
                            <tbody>
                            <?php
                            if (isset($participants) && !empty($participants)) {
                                foreach ($participants as $row) {
                                    extract($row) ?>
                                    <tr>
                                        <td><?php echo $full_name; ?></td>
                                        <td><?php echo datetime_to_text($created); ?></td>
                                        <td><?php echo $email; ?></td>
                                        <td><?php echo $phone; ?></td>
                                        <td><?php if ($audit_location == 1) {
                                                echo "Diamond Estate";
                                            } elseif ($audit_location == 2) {
                                                echo "HFP Office";
                                            } elseif ($audit_location == 3) {
                                                echo "Online";
                                            } ?></td>
                                        <td><?php echo datetime_to_text($audit_date); ?></td>
                                        <td nowrap="nowrap">
                                            <a target="_blank" title="View" class="btn btn-sm btn-info"
                                               href="client_detail.php?id=<?php echo dec_enc('encrypt', $user_code); ?>"><i
                                                    class="glyphicon glyphicon-eye-open icon-white"></i> </a>
                                            <a class="btn btn-sm btn-primary" target="_blank" title="Send Email"
                                               href="campaign_email_single.php?name=<?php $name;
                                               echo dec_enc('encrypt', $name) . '&email=' . dec_enc('encrypt', $email); ?>"><i
                                                    class="glyphicon glyphicon-envelope"></i></a>
                                            <a class="btn btn-sm btn-success" target="_blank" title="Send SMS"
                                               href="campaign_sms_single.php?lead_phone=<?php echo dec_enc('encrypt', $phone) ?>"><i
                                                    class="glyphicon glyphicon-phone-alt"></i></a>
                                            <button class="btn btn-primary btn-sm" type="button"
                                                    data-target="#edit<?php echo $id; ?>" data-toggle="modal" title="Edit Participants Ticket" >
                                                <i class="fa fa-pencil-square-o"></i>
                                            </button>
                                            <!-- Modal -->
                                            <div id="edit<?php echo $id; ?>" tabindex="-1" role="dialog" aria-hidden="true"
                                                 class="modal fade">
                                                <form class="form-vertical text-center" role="form" method="post" action="">
                                                    <input type="hidden" name="id" value="<?php echo $id?>">
                                                    <div class="modal-dialog modal-lg">
                                                        <div class="modal-content">
                                                            <div class="modal-header">
                                                                <button type="button" data-dismiss="modal" aria-hidden="true"
                                                                        class="close">&times;</button>
                                                                <h4 class="modal-title">Edit Audit date</h4>
                                                            </div>

                                                            <div class="modal-body">
                                                                <div class="form-group">
                                                                    <label for="venue" class="control-label">Choose venue</label>
                                                                    <div class="radio">
                                                                        <label><input id="offline1<?php echo $id;?>" type="radio" name="venue"
                                                                                      value="1" required>Block 1A, Plot
                                                                            8, Diamond Estate, LASU/Isheri road, Isheri Olofin,
                                                                            Lagos.</label>
                                                                    </div>
                                                                    <div class="form_group" id="entry1<?php echo $id;?>" >
                                                                        <select id="entry_channel1" class="form-control" name="date1">
                                                                            <option value="">Choose a date</option>
                                                                            <option value="2018-12-10 11:30:00">11:30am - 12:30pm Monday 10th December 2018</option>
                                                                            <option value="2018-12-11 11:30:00">11:30am - 12:30pm Tuesday 11th December 2018</option>
                                                                            <option value="2018-12-17 11:30:00">11:30am - 12:30pm Monday 17th December 2018</option>
                                                                            <option value="2018-12-18 11:30:00">11:30am - 12:30pm Tuesday 18th December 2018 </option>
                                                                        </select>
                                                                    </div>
                                                                    <div class="radio">
                                                                        <label><input id="offline2<?php echo $id;?>" type="radio" name="venue"
                                                                                      value="2" required>Block A3, Suite 508/509
                                                                            Eastline Shopping Complex, Opposite Abraham Adesanya Roundabout,
                                                                            along Lekki - Epe expressway, Lagos.</label>
                                                                    </div>

                                                                    <div class="form_group" id="entry2<?php echo $id;?>" >
                                                                        <select id="entry_channel2" class="form-control" name="date2" >
                                                                            <option value="">Choose a date</option>
                                                                            <option value="2018-12-05 11:30:00">11:30am - 12:30pm Wednesday 5th December 2018</option>
                                                                            <option value="2018-12-06 11:30:00">11:30am - 12:30pm Thursday 6th December 2018</option>
                                                                            <option value="2018-12-12 11:30:00">11:30am - 12:30pm Wednesday 12th December 2018</option>
                                                                            <option value="2018-12-13 11:30:00">11:30am - 12:30pm Thursday 13th December 2018</option>
                                                                            <option value="2018-12-19 11:30:00">11:30am - 12:30pm Wednesday 19th December 2018</option>
                                                                            <option value="2018-12-20 11:30:00">11:30am - 12:30pm Thursday 20th December 2018</option>
                                                                        </select>
                                                                    </div>

                                                                    <div class="radio">
                                                                        <label><input id="online<?php echo $id;?>" type="radio" name="venue"
                                                                                      value="3" required>Online -- Download Zoom Video
                                                                            Conferencing app from
                                                                            <a target="_blank" href="http://zoom.us">zoom.us</a> You will contacted and given the
                                                                            meeting ID before
                                                                            the session starts
                                                                        </label>
                                                                    </div>

                                                                    <div class="form_group" id="entry3<?php echo $id;?>" >
                                                                        <select id="entry_channel3" class="form-control" name="date3" >
                                                                            <option value="">Choose a date</option>
                                                                            <option value="2018-12-05 10:30:00">10:30am - 11:30am Wednesday 5th December 2018 Online</option>
                                                                            <option value="2018-12-06 10:30:00">10:30am - 11:30am Thursday 6th December 2018 Online</option>
                                                                            <option value="2018-12-10 10:30:00">10:30am - 11:30am Monday 10th December 2018 Online</option>
                                                                            <option value="2018-12-11 10:30:00">10:30am - 11:30am Tuesday 11th December 2018 Online</option>
                                                                            <option value="2018-12-12 10:30:00">10:30am - 11:30am Wednesday 12th December 2018 Online</option>
                                                                            <option value="2018-12-13 10:30:00">10:30am - 11:30am Thursday 13th December 2018 Online</option>
                                                                            <option value="2018-12-19 10:30:00">10:30am - 11:30am Wednesday 19th December 2018 Online</option>
                                                                            <option value="2018-12-20 10:30:00">10:30am - 11:30am Thursday 20th December 2018 Online</option>
                                                                        </select>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <div class="modal-footer">
                                                                <input name="update" type="submit"
                                                                       class="btn btn-sm btn-default" value="SUBMIT">
                                                                <button type="button" name="close"
                                                                        data-dismiss="modal" class="btn btn-sm btn-danger">Close!
                                                                </button>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </form>
                                            </div>


                                        </td>
                                    </tr>
                                <?php }
                            } else {
                                echo "<tr><td colspan='5' class='text-danger'><em>No results to display</em></td></tr>";
                            } ?>
                            </tbody>
                        </table>

                        <?php if (isset($participants) && !empty($participants)) { ?>
                            <div class="tool-footer text-right">
                                <p class="pull-left">
                                    Showing <?php echo $prespagelow . " to " . $prespagehigh . " of " . $numrows; ?>
                                    entries</p>
                            </div>
                        <?php } ?>
                    </div>
                </div>

                <?php if (isset($participants) && !empty($participants)) {
                    require_once 'layouts/pagination_links.php';
                } ?>
            </div>

            <!-- Unique Page Content Ends Here
            ================================================== -->

        </div>

    </div>
</div>
<?php require_once 'layouts/footer.php'; ?>
</body>
</html>