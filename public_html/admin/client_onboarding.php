<?php
require_once("../init/initialize_admin.php");
if (!$session_admin->is_logged_in()) { redirect_to("login.php"); }

$current_year = date('Y');
$main_current_month = date('m');
$current_month = ltrim($main_current_month, '0');
$current_quarter = $obj_analytics->get_quarter_code($main_current_month);
$current_half_year = $obj_analytics->get_half_year_code($main_current_month);
$current_year_code = "1-12";

// Get the page Analytics
$onboarding_analytics = $obj_analytics->get_onboarding_analytics();
extract($onboarding_analytics);

$query = "SELECT value AS target FROM admin_targets WHERE year = '$current_year' AND period = '$main_current_month' AND status = '1' AND type = '1' LIMIT 1";
$result = $db_handle->runQuery($query);
$m_current_target = $db_handle->fetchAssoc($result)[0]['target'];

$query = "SELECT value AS target FROM admin_targets WHERE year = '$current_year' AND period = '$current_quarter' AND status = '1' AND type = '1' LIMIT 1";
$result = $db_handle->runQuery($query);
$q_current_target = $db_handle->fetchAssoc($result)[0]['target'];

$query = "SELECT value AS target FROM admin_targets WHERE year = '$current_year' AND period = '$current_half_year' AND status = '1' AND type = '1' LIMIT 1";
$result = $db_handle->runQuery($query);
$h_current_target = $db_handle->fetchAssoc($result)[0]['target'];

$query = "SELECT value AS target FROM admin_targets WHERE year = '$current_year' AND period = '$current_year_code' AND status = '1' AND type = '1' LIMIT 1";
$result = $db_handle->runQuery($query);
$y_current_target = $db_handle->fetchAssoc($result)[0]['target'];

//get number clients not on board
$query = "SELECT u.user_code, CONCAT(u.last_name, SPACE(1), u.first_name) AS full_name, u.email, u.phone, u.created FROM user AS u INNER JOIN user_ifxaccount AS ui ON u.user_code = ui.user_code WHERE ui.ifx_acct_no NOT IN (SELECT ifx_acct_no FROM trading_commission WHERE commission > 0) GROUP BY u.email ORDER BY u.created DESC, u.last_name ASC ";
$not_on_board = $db_handle->numRows($query);

if(isset($_POST['view'])){
    unset($_SESSION['query']);
}

//filter parameters
if (isset($_POST['filter'])) {
    foreach ($_POST as $key => $value) {
        $_POST[$key] = $db_handle->sanitizePost(trim($value));
    }
    $year = $_POST['year'];
    $period = $_POST['period'];

    $dates_selected = $obj_analytics->get_from_to_dates($year, $period);
    extract($dates_selected);

    //get target value if any
    $query = "SELECT value AS target FROM admin_targets WHERE year = '$year' AND period = '$period' AND status = '1' AND type = '1' LIMIT 1";
    $result = $db_handle->runQuery($query);
    foreach ($result AS $row) {
        extract($row);
    }
    if ($target == NULL) {
        $target = "NO Target Set.";
    }
    $_SESSION['target'] = $target;

    $query = "SELECT u.user_code, CONCAT(u.last_name, SPACE(1), u.first_name) AS full_name, u.phone, u.email, u.created,
        MIN(tc.date_earned) AS date_earned FROM trading_commission AS tc
        INNER JOIN user_ifxaccount AS ui ON tc.ifx_acct_no = ui.ifx_acct_no
        INNER JOIN user AS u ON ui.user_code = u.user_code
        GROUP BY u.user_code
        HAVING date_earned BETWEEN '$from_date' AND '$to_date' ";
    $_SESSION['query'] = $query;
    $total_onboard = $db_handle->numRows($query);
}

if (empty($_SESSION['query']) || ($_SESSION['query'] == NULL)) {
    $period = date('m');
    $year = date('Y');

    //get target value
    $query = "SELECT value AS target FROM admin_targets WHERE year = '$year' AND period = '$period' AND status = '1' AND type = '1' LIMIT 1";
    $result = $db_handle->runQuery($query);

    foreach ($result AS $row) {
        extract($row);
    }

    if ($target == NULL) {
        $target = "NO Target Set.";
    }

    $_SESSION['target'] = $target;
    $query_analysis = "SELECT u.user_code, CONCAT(u.last_name, SPACE(1), u.first_name) AS full_name, u.phone, u.email, u.created, MIN(tc.date_earned) AS date_earned
        FROM trading_commission AS tc
        INNER JOIN user_ifxaccount AS ui ON tc.ifx_acct_no = ui.ifx_acct_no
        INNER JOIN user AS u ON ui.user_code = u.user_code
        GROUP BY u.user_code HAVING MONTH(date_earned) = $period AND YEAR(date_earned) = $year ";
    $total_onboard = $db_handle->numRows($query_analysis);
    $query = "SELECT u.user_code, CONCAT(u.last_name, SPACE(1), u.first_name) AS full_name, u.email, u.phone, u.created
FROM user AS u INNER JOIN user_ifxaccount AS ui ON u.user_code = ui.user_code
WHERE ui.ifx_acct_no NOT IN (SELECT ifx_acct_no FROM trading_commission WHERE commission > 0)
GROUP BY u.email ORDER BY u.created DESC, u.last_name ASC ";
    $_SESSION['query'] = $query;
}

//search to display clients on boarding date
if (isset($_POST['search_text']) && strlen($_POST['search_text']) > 3) {
    $search_text = $_POST['search_text'];
    $query = "SELECT MIN(tc.date_earned) AS date_earned, u.user_code, CONCAT(u.last_name, SPACE(1), u.first_name) AS full_name, u.email, u.phone, u.created
        FROM trading_commission AS tc
        INNER JOIN user_ifxaccount AS ui ON tc.ifx_acct_no = ui.ifx_acct_no
        INNER JOIN user AS u ON ui.user_code = u.user_code
        WHERE (ui.ifx_acct_no LIKE '%$search_text%' OR u.email LIKE '%$search_text%' OR u.first_name LIKE '%$search_text%' OR u.middle_name LIKE '%$search_text%' OR u.last_name LIKE '%$search_text%' OR u.phone LIKE '%$search_text%' OR u.created LIKE '$search_text%') GROUP BY u.email ORDER BY u.created DESC ";
    $_SESSION['query'] = $query;
    $_SESSION['target'] = "NO Target Set.";
}

$query = $_SESSION['query'];

if (isset($_POST['onboarding_tracker']) || isset($_GET['pg'])) {
    
} else {
    $query = "SELECT u.user_code, CONCAT(u.last_name, SPACE(1), u.first_name) AS full_name, u.email, u.phone, u.created 
          FROM user AS u INNER JOIN user_ifxaccount AS ui ON u.user_code = ui.user_code 
          WHERE ui.ifx_acct_no NOT IN (
              SELECT ifx_acct_no FROM trading_commission WHERE commission > 0
          ) 
          GROUP BY u.email 
          ORDER BY u.created DESC, u.last_name ASC ";

}

$numrows = $db_handle->numRows($query);

// For search, make rows per page equal total rows found, meaning, no pagination
// for search results
if (isset($_POST['search_text'])) {
    $rowsperpage = $numrows;
} else {
    $rowsperpage = 20;
}

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
$onboarding_result = $db_handle->fetchAssoc($result);

if(isset($_POST['campaign_category'])){
    $recipients = array();
    foreach($onboarding_result AS $row){
        extract($row);
        array_push($recipients,"$user_code");
    }
    $title = $db_handle->sanitizePost($_POST['name']);
    $description = $db_handle->sanitizePost($_POST['details']);

    $new_category = $system_object->add_new_campaign_category($title, $description, '1', $campaign_category_no, '1');
    if($new_category == true) {
        $message_success = "You Have successfully created a new Campaign group";
    } else {
        $message_error = "Not Successful, Kindly Try again";
    }
    // Left with how best to store $recipents and retrieve it .
}

?>
<!DOCTYPE html>
<html lang="en">
    <head>
        <base target="_self">
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>Instaforex Nigeria | Admin - Client Onboarding Tracker</title>
        <meta name="title" content="Instaforex Nigeria | Admin - Client Onboarding Tracker"/>
        <meta name="keywords" content=""/>
        <meta name="description" content=""/>
        <?php require_once 'layouts/head_meta.php'; ?>
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
                    <div class="search-section">
                        <div class="row">
                            <div class="col-xs-12">
                                <form data-toggle="validator" class="form-horizontal" role="form" method="post" action="<?php echo $REQUEST_URI; ?>">
                                    <div class="input-group">
                                        <input type="hidden" name="search_param" value="all" id="search_param">
                                        <input type="text" class="form-control" name="search_text" placeholder="Search term..." required>
                                        <span class="input-group-btn">
                                            <button class="btn btn-default" type="submit"><span class="glyphicon glyphicon-search"></span></button>
                                        </span>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-sm-12 text-danger">
                            <h4><strong>CLIENT ONBOARDING TRACKER</strong></h4>
                        </div>
                    </div>

                    <div class="section-tint super-shadow">
                        <div class="row">
                            <div class="col-sm-12">
                                <div id="myAnalytics" class="carousel slide" data-interval="false">
                                    <!-- Indicators -->
                                    <ol class="carousel-indicators">
                                        <li data-target="#myAnalytics" data-slide-to="0" class="active"></li>
                                        <li data-target="#myAnalytics" data-slide-to="1"></li>
                                    </ol>

                                    <!-- Wrapper for slides -->
                                    <div class="carousel-inner" style="height: 300px;">

                                        <div class="item active">

                                            <div class="row">
                                                <div class="col-sm-6">
                                                    <h5 class="text-center"><strong>Month Analysis - (<?php echo $month_title; ?>)</strong></h5>

                                                    <table class="table table-border table-responsive table-hover">
                                                        <tr><td>Month Target</td><td><?php echo number_format($m_current_target); ?></td></tr>
                                                        <tr><td>Total On board</td><td><?php echo number_format($m_total_onboard); ?></td></tr>
                                                        <tr><td>Target Deficit</td><td><?php echo number_format($m_current_target - $m_total_onboard) ?></td></tr>
                                                        <tr><td>Percentage progress</td><td><?php echo number_format((($m_total_onboard / $m_current_target) * 100), 2) . "%"; ?></td></tr>
                                                    </table>
                                                    <hr />
                                                    <div class="progress">
                                                        <div class="progress-bar progress-bar-striped active" role="progressbar" aria-valuenow="<?php echo number_format((($m_total_onboard / $m_current_target) * 100), 2); ?>" aria-valuemin="0" aria-valuemax="<?php echo $m_current_target; ?>" style="width:<?php echo number_format((($m_total_onboard / $m_current_target) * 100), 2) . "%"; ?>"> <?php echo number_format((($m_total_onboard / $m_current_target) * 100), 2) . "%"; ?></div>
                                                    </div>
                                                </div>

                                                <div class="col-sm-6">
                                                    <h5 class="text-center"><strong>Quarter Analysis - (<?php echo $quarter_title; ?>)</strong></h5>

                                                    <table class="table table-border table-responsive table-hover">
                                                        <tr><td>Quarter Target</td><td><?php echo number_format($q_current_target); ?></td></tr>
                                                        <tr><td>Total On board</td><td><?php echo number_format($q_total_onboard) ?></td></tr>
                                                        <tr><td>Target Deficit</td><td><?php echo number_format($q_current_target - $q_total_onboard) ?></td></tr>
                                                        <tr><td>Percentage progress</td><td><?php echo number_format((($q_total_onboard / $q_current_target) * 100), 2) . "%"; ?></td></tr>
                                                    </table>
                                                    <hr />
                                                    <div class="progress">
                                                        <div class="progress-bar progress-bar-striped active" role="progressbar" aria-valuenow="<?php echo number_format((($q_total_onboard / $q_current_target) * 100), 2); ?>" aria-valuemin="0" aria-valuemax="<?php echo $q_current_target; ?>" style="width:<?php echo number_format((($q_total_onboard / $q_current_target) * 100), 2) . "%"; ?>"> <?php echo number_format((($q_total_onboard / $q_current_target) * 100), 2) . "%"; ?></div>
                                                    </div>
                                                </div>
                                            </div>

                                        </div>

                                        <div class="item">

                                            <div class="row">
                                                <div class="col-sm-6">
                                                    <h5 class="text-center"><strong>Half Year Analysis - (<?php echo $half_year_title; ?>)</strong></h5>

                                                    <table class="table table-border table-responsive table-hover">
                                                        <tr><td>Half Year Target</td><td><?php echo number_format($h_current_target); ?></td></tr>
                                                        <tr><td>Total On board</td><td><?php echo number_format($h_total_onboard) ?></td></tr>
                                                        <tr><td>Target Deficit</td><td><?php echo number_format($h_current_target - $h_total_onboard) ?></td></tr>
                                                        <tr><td>Percentage progress</td><td><?php echo number_format((($h_total_onboard / $h_current_target) * 100), 2) . "%"; ?></td></tr>
                                                    </table>
                                                    <hr />
                                                    <div class="progress">
                                                        <div class="progress-bar progress-bar-striped active" role="progressbar" aria-valuenow="<?php echo number_format((($h_total_onboard / $h_current_target) * 100), 2); ?>" aria-valuemin="0" aria-valuemax="<?php echo $h_current_target; ?>" style="width:<?php echo number_format((($h_total_onboard / $h_current_target) * 100), 2) . "%"; ?>"> <?php echo number_format((($h_total_onboard / $h_current_target) * 100), 2) . "%"; ?></div>
                                                    </div>
                                                </div>

                                                <div class="col-sm-6">
                                                    <h5 class="text-center"><strong>Year Analysis - (<?php echo $year_title; ?>)</strong></h5>

                                                    <table class="table table-border table-responsive table-hover">
                                                        <tr><td>Year Target</td><td><?php echo number_format($y_current_target); ?></td></tr>
                                                        <tr><td>Total On board</td><td><?php echo number_format($y_total_onboard) ?></td></tr>
                                                        <tr><td>Target Deficit</td><td><?php echo number_format($y_current_target - $y_total_onboard) ?></td></tr>
                                                        <tr><td>Percentage progress</td><td><?php echo number_format((($y_total_onboard / $y_current_target) * 100), 2) . "%"; ?></td></tr>
                                                    </table>
                                                    <hr />
                                                    <div class="progress">
                                                        <div class="progress-bar progress-bar-striped active" role="progressbar" aria-valuenow="<?php echo number_format((($y_total_onboard / $y_current_target) * 100), 2); ?>" aria-valuemin="0" aria-valuemax="<?php echo $y_current_target; ?>" style="width:<?php echo number_format((($y_total_onboard / $y_current_target) * 100), 2) . "%"; ?>"> <?php echo number_format((($y_total_onboard / $y_current_target) * 100), 2) . "%"; ?></div>
                                                    </div>
                                                </div>
                                            </div>

                                        </div>

                                    </div>

                                    <!-- Left and right controls -->
                                    <a class="left carousel-control" href="#myAnalytics" data-slide="prev">
                                        <span class="glyphicon glyphicon-chevron-left"></span>
                                        <span class="sr-only">Previous</span>
                                    </a>
                                    <a class="right carousel-control" href="#myAnalytics" data-slide="next">
                                        <span class="glyphicon glyphicon-chevron-right"></span>
                                        <span class="sr-only">Next</span>
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="section-tint super-shadow">
                        <div class="row">
                            <div class="col-sm-12">
                                <?php require_once 'layouts/feedback_message.php'; ?>

                                <div class="pull-right">
                                    <button type="button" data-target="#onboarding-filter" data-toggle="modal" class="btn btn-sm btn-default"><i class="glyphicon glyphicon-search"></i> Apply Filter</button>
                                </div>

                                <div id="onboarding-filter" tabindex="-1" role="dialog" aria-hidden="true" class="modal fade">
                                    <div class="modal-dialog">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <button type="button" data-dismiss="modal" aria-hidden="true"  class="close">&times;</button>
                                                <p class="modal-title">Fill the form below, choose the year and period.</p>
                                            </div>

                                            <div class="modal-body">
                                                <form data-toggle="validator" class="form-horizontal" role="form" method="post" action="client_retention.php">

                                                    <div class="form-group">
                                                        <label class="control-label col-sm-3" for="year_date">Year:</label>
                                                        <div class="col-sm-9 col-lg-8">
                                                            <div class="input-group date">
                                                                <input name="year_date" type="text" class="form-control" id="datetimepicker" required>
                                                                <span class="input-group-addon"><span class="glyphicon glyphicon-calendar"></span></span>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="form-group">
                                                        <label class="control-label col-sm-3" for="period">Period:</label>
                                                        <div class="col-sm-9 col-lg-8">
                                                            <select type="text" name="period" id="period" class="form-control" required>
                                                                <option value=""></option>
                                                                <option value="1">January</option>
                                                                <option value="2">February</option>
                                                                <option value="3">March</option>
                                                                <option value="4">April</option>
                                                                <option value="5">May</option>
                                                                <option value="6">June</option>
                                                                <option value="7">July</option>
                                                                <option value="8">August</option>
                                                                <option value="9">September</option>
                                                                <option value="10">October</option>
                                                                <option value="11">November</option>
                                                                <option value="12">December</option>
                                                                <option value="1-12">Annual</option>
                                                                <option value="1-6">First Half</option>
                                                                <option value="7-12">Second Half</option>
                                                                <option value="1-3">First Quarter</option>
                                                                <option value="4-6">Second Quarter</option>
                                                                <option value="7-9">Third Quarter</option>
                                                                <option value="10-12">Fourth Quarter</option>
                                                            </select>
                                                        </div>
                                                    </div>

                                                    <div class="form-group">
                                                        <div class="col-sm-offset-3 col-sm-9">
                                                            <input name="onboarding_tracker" type="submit" class="btn btn-success" value="Display Result" />
                                                        </div>
                                                    </div>
                                                    <script type="text/javascript">
                                                        $(function () {
                                                            $('#datetimepicker, #datetimepicker2').datetimepicker({
                                                                format: 'YYYY'
                                                            });
                                                        });
                                                    </script>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <form class="pull-right" method="post" action="">
                                    <button name="view" type="submit" class="btn btn-info btn-sm"><i class="glyphicon glyphicon-eye-circle"></i>Refresh</button>
                                </form>
                                <button class="btn btn-sm btn-default pull-right" type="button" data-target="#confirm-campaign" data-toggle="modal">Create Campaign Category</button>

                                <div id="confirm-campaign" tabindex="-1" role="dialog" aria-hidden="true" class="modal fade">
                                    <form data-toggle="validator" class="form-horizontal" role="form" method="post" action="<?php echo $_SERVER['REQUEST_URI']; ?>">
                                        <div class="modal-dialog modal-md">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <button type="button" data-dismiss="modal" aria-hidden="true" class="close">&times;</button>
                                                    <h4 class="modal-title">Create Campaign Category</h4>
                                                </div>
                                                <div class="modal-body">
                                                    <p>Enter Title and Description</p>
                                                    <div class="form-group row">
                                                        <div class="col-sm-12">
                                                            <label for="inputHeading3" class="col-form-label">Title/Name:</label>
                                                            <input name="name" type="text" class="form-control" id="forum_title" placeholder="Enter Target Name or title" required>
                                                        </div>
                                                    </div>
                                                    <div class="form-group row">
                                                        <div class="col-sm-12">
                                                            <label for="inputHeading3" class="col-form-label">Description</label>
                                                            <textarea rows="3" name="details" type="text" class="form-control" id="forum_title" placeholder="Enter Detailed Description of this category" required></textarea>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="modal-footer">
                                                    <input name="campaign_category" type="submit" class="btn btn-sm btn-success" value="Proceed">
                                                    <button type="button" data-dismiss="modal" aria-hidden="true" class="btn btn-sm btn-danger">Close!</button>
                                                </div>
                                            </div>
                                        </div>
                                    </form>
                                </div>

                                <?php if (isset($numrows)) { ?>
                                    <p><strong>Result Found: </strong><?php echo number_format($numrows); ?></p>
                                <?php } ?>

                                <?php if ((isset($onboarding_result) && !empty($onboarding_result))) { require 'layouts/pagination_links.php'; } ?>

                                <div class="table-wrap">
                                    <table class="table table-responsive table-striped table-bordered table-hover">
                                        <thead>
                                        <tr>
                                            <th>Full Name</th>
                                            <th>Email</th>
                                            <th>Phone</th>
                                            <th>Reg. Date</th>
                                            <th>Action</th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                        <?php if ((isset($onboarding_result) && !empty($onboarding_result)) ) { foreach ($onboarding_result as $row) { extract($row); ?>
                                            <tr>
                                                <td><?php echo $full_name; ?></td>
                                                <td><?php echo $email; ?></td>
                                                <td><?php echo $phone; ?></td>
                                                <td><?php echo $created; ?></td>
                                                <td nowrap>
                                                    <a target="_blank" title="Comment" class="btn btn-sm btn-success" href="sales_contact_view.php?x=<?php echo encrypt($row['user_code']); ?>&r=<?php echo 'client_onboarding'; ?>&c=<?php echo encrypt('CLIENT ON-BOARDING'); ?>&pg=<?php echo $currentpage; ?>"><i class="glyphicon glyphicon-comment icon-white"></i> </a>
                                                    <a target="_blank" title="View" class="btn btn-sm btn-info" href="client_detail.php?id=<?php echo encrypt($row['user_code']); ?>"><i class="glyphicon glyphicon-eye-open icon-white"></i> </a>
                                                    <a target="_blank" class="btn btn-sm btn-primary" title="Send Email" href="campaign_email_single.php?name=<?php $name = $row['full_name']; echo encrypt_ssl($name) . '&email=' . encrypt_ssl($row['email']); ?>"><i class="glyphicon glyphicon-envelope"></i></a>
                                                    <a target="_blank" class="btn btn-sm btn-success" title="Send SMS" href="campaign_sms_single.php?lead_phone=<?php echo encrypt_ssl($row['phone']) ?>"><i class="glyphicon glyphicon-phone-alt"></i></a>
                                                </td>
                                            </tr>
                                        <?php } } else { echo "<tr><td colspan='5' class='text-danger'><em>No results to display</em></td></tr>"; } ?>
                                        </tbody>
                                    </table>
                                </div>

                                <?php if (isset($onboarding_result) && !empty($onboarding_result)) { ?>
                                    <div class="tool-footer text-right">
                                        <p class="pull-left">Showing <?php echo $prespagelow . " to " . $prespagehigh . " of " . $numrows; ?> entries</p>
                                    </div>
                                <?php } ?>
                            </div>
                        </div>

                        <?php if (isset($onboarding_result) && !empty($onboarding_result)) {
                            require 'layouts/pagination_links.php';
                        } ?>
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