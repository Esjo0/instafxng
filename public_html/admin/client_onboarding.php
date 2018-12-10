<?php
require_once("../init/initialize_admin.php");

if (!$session_admin->is_logged_in()) { redirect_to("login.php"); }

if(isset($_GET['r']) && $_GET['r'] == 1) {
    unset($_SESSION['client_onboarding_query']);
    redirect_to("client_onboarding.php");
}

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

if (isset($_POST['onboarding_tracker']) || isset($_GET['pg']) || isset($_POST['filter'])) {

    if (isset($_POST['onboarding_tracker'])) {
        foreach ($_POST as $key => $value) {
            $_POST[$key] = $db_handle->sanitizePost(trim($value));
        }

        extract($_POST);

        $dates_selected = $obj_analytics->get_from_to_dates($year_date, $period);
        extract($dates_selected);

        $query = "SELECT u.user_code, CONCAT(u.last_name, SPACE(1), u.first_name) AS full_name, u.phone, u.email, u.created,
            MIN(tc.date_earned) AS date_earned FROM trading_commission AS tc
            INNER JOIN user_ifxaccount AS ui ON tc.ifx_acct_no = ui.ifx_acct_no
            INNER JOIN user AS u ON ui.user_code = u.user_code
            GROUP BY u.user_code
            HAVING date_earned BETWEEN '$from_date' AND '$to_date' ";

        $_SESSION['client_onboarding_query'] = $query;

    } elseif (isset($_POST['filter'])) {
        foreach ($_POST as $key => $value) {
            $_POST[$key] = $db_handle->sanitizePost(trim($value));
        }

        extract($_POST);

        switch ($filter_value) {
            case 'all':
                $query = "SELECT u.user_code, CONCAT(u.last_name, SPACE(1), u.first_name) AS full_name, u.email, u.phone, u.created 
                    FROM user AS u INNER JOIN user_ifxaccount AS ui ON u.user_code = ui.user_code 
                    WHERE ui.ifx_acct_no NOT IN (
                    SELECT ifx_acct_no FROM trading_commission WHERE commission > 0
                    ) 
                    GROUP BY u.email 
                    ORDER BY u.created DESC, u.last_name ASC ";
                $filter_category = "All clients not on board";
                $display_msg = "Below is a table listing all clients not yet on board.";
                break;

            case 'funded_ilpr':
                $query = "SELECT u.user_code, CONCAT(u.last_name, SPACE(1), u.first_name) AS full_name, u.email, u.phone, u.created
                    FROM user AS u INNER JOIN user_ifxaccount AS ui ON u.user_code = ui.user_code
                    INNER JOIN user_deposit AS ud ON ui.ifxaccount_id = ud.ifxaccount_id
                    WHERE ud.status = '8' AND ui.type = '1' AND ui.ifx_acct_no NOT IN (SELECT ifx_acct_no FROM trading_commission WHERE commission > 0)
                    GROUP BY u.email ORDER BY u.created DESC, u.last_name ASC ";
                $filter_category = "Clients not yet on board but have funded their ILPR accounts";
                $display_msg = "Below is a table listing all clients not yet on board but have completed funding transactions on a ILPR account.";
                break;

            case 'funded_nonilpr':
                $query = "SELECT u.user_code, CONCAT(u.last_name, SPACE(1), u.first_name) AS full_name, u.email, u.phone, u.created
                    FROM user AS u INNER JOIN user_ifxaccount AS ui ON u.user_code = ui.user_code
                    INNER JOIN user_deposit AS ud ON ui.ifxaccount_id = ud.ifxaccount_id
                    WHERE ud.status = '8' AND ui.type = '2' AND ui.ifx_acct_no NOT IN (SELECT ifx_acct_no FROM trading_commission WHERE commission > 0)
                    GROUP BY u.email ORDER BY u.created DESC, u.last_name ASC ";
                $filter_category = "Clients not yet on board but have funded their Non-ILPR accounts";
                $display_msg = "Below is a table listing all clients not yet on board but have completed funding transactions on a Non-ILPR account.";
                break;

            case 'ilpr':
                $query = "SELECT u.user_code, CONCAT(u.last_name, SPACE(1), u.first_name) AS full_name, u.email, u.phone, u.created
                    FROM user AS u INNER JOIN user_ifxaccount AS ui ON u.user_code = ui.user_code
                    WHERE ui.type = '1' AND ui.ifx_acct_no NOT IN (SELECT ifx_acct_no FROM trading_commission WHERE commission > 0)
                    GROUP BY u.email ORDER BY u.created DESC, u.last_name ASC ";
                $filter_category = "Clients not yet On-Board With ILPR Accounts";
                $display_msg = "Below is a table listing all clients not yet on board with ILPR account numbers.";
                break;

            case 'nonilpr':
                $query = "SELECT u.user_code, CONCAT(u.last_name, SPACE(1), u.first_name) AS full_name, u.email, u.phone, u.created
                    FROM user AS u INNER JOIN user_ifxaccount AS ui ON u.user_code = ui.user_code
                    WHERE ui.type = '2' AND ui.ifx_acct_no NOT IN (SELECT ifx_acct_no FROM trading_commission WHERE commission > 0)
                    GROUP BY u.email ORDER BY u.created DESC, u.last_name ASC ";
                $filter_category = "Clients not yet On-Board having NON-ILPR Accounts";
                $display_msg = "Below is a table listing all clients not yet on board and don't have ILPR account numbers.";
                break;

            case 'training':
                $query = "SELECT u.user_code, CONCAT(u.last_name, SPACE(1), u.first_name) AS full_name, u.email, u.phone, u.created
                    FROM user AS u INNER JOIN user_ifxaccount AS ui ON u.user_code = ui.user_code
                    WHERE ui.ifx_acct_no NOT IN (SELECT ifx_acct_no FROM trading_commission WHERE commission > 0)
                    AND  u.user_code IN (SELECT user_code FROM user AS U WHERE U.academy_signup IS NOT NULL)
                    GROUP BY u.email ORDER BY u.created DESC, u.last_name ASC ";
                $filter_category = "Training Clients Not yet ON-Board";
                $display_msg = "Below is a table listing all clients not yet on board but have enrolled in the FxAcademy.";
                break;
        }

        $_SESSION['client_onboarding_query'] = $query;

    } else {
        $query = $_SESSION['client_onboarding_query'];
    }
} else {

    $result_title = "Not Onboard Category";

    $query = "SELECT u.user_code, CONCAT(u.last_name, SPACE(1), u.first_name) AS full_name, u.email, u.phone, u.created 
          FROM user AS u INNER JOIN user_ifxaccount AS ui ON u.user_code = ui.user_code 
          WHERE ui.ifx_acct_no NOT IN (
              SELECT ifx_acct_no FROM trading_commission WHERE commission > 0
          ) 
          GROUP BY u.email 
          ORDER BY u.created DESC, u.last_name ASC ";

    $filter_category = "All clients not on board";
    $display_msg = "Below is a table listing all clients not yet on board.";

    $_SESSION['client_onboarding_query'] = $query;
    $_SESSION['client_onboarding_filter_category'] = $filter_category;
    $_SESSION['client_onboarding_display_msg'] = $display_msg;
    $_SESSION['client_onboarding_result_title'] = $result_title;
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

if (isset($_POST['send_bulk']) ) {
    if($numrows > 0 && $numrows <= 200) {
        $campaign_email_id = $_POST['campaign_email_id'];
        $selected_campaign_email = $system_object->get_campaign_email_by_id($campaign_email_id);

        $my_subject = trim($selected_campaign_email['subject']);
        $my_message = stripslashes($selected_campaign_email['content']);
        $mail_sender = trim($selected_campaign_email['sender']);

        $query = $_SESSION['client_onboarding_query'];
        $result = $db_handle->runQuery($query);
        $recipients = $db_handle->fetchAssoc($result);
        foreach ($recipients as $sendto) {
            extract($sendto);
            $query = "SELECT user_code, first_name FROM user WHERE email = '$email' LIMIT 1";
            $result = $db_handle->runQuery($query);
            $fetched_data = $db_handle->fetchAssoc($result);
            $selected_member = $fetched_data[0];

            $client_name = ucwords(strtolower(trim($selected_member['first_name'])));

            // Replace [NAME] with clients full name
            $my_message_new = str_replace('[NAME]', $client_name, $my_message);
            $my_subject_new = str_replace('[NAME]', $client_name, $my_subject);

            if (array_key_exists('user_code', $selected_member)) {
                $user_code = $selected_member['user_code'];

                $encrypted_user_code = encrypt($user_code);
                $black_friday_link = "<a title='Click Here to enjoy the splurge' href='https://instafxng.com/black_friday_splurge.php?x=$encrypted_user_code'><strong>Click Here to set your target Now!</strong></a>";
                $dinner_2018 = "<a title='Click Here to reserve your seat' href='https://instafxng.com/dinner.php?r=$encrypted_user_code'><strong>Click Here to reserve your seat</strong></a>";
                $found_position_month = in_array_r($user_code, $found_loyalty_month);
                $month_position = $found_position_month['position'];
                $month_rank = number_format(($found_position_month['rank']), 2, ".", ",");
                $month_rank_highest = number_format(($found_loyalty_month[0]['rank']), 2, ".", ",");
                $month_rank_difference = number_format(($month_rank_highest - $month_rank), 2, ".", ",");
                $month_rank_goal = number_format(($month_rank_difference / $days_left_this_month), 2, ".", ",");

                $found_position_year = in_array_r($user_code, $found_loyalty_year);
                $year_position = $found_position_year['position'];
                $year_rank = number_format(($found_position_year['rank']), 2, ".", ",");
                $year_rank_highest = number_format(($found_loyalty_year[0]['rank']), 2, ".", ",");
                $year_rank_difference = number_format(($year_rank_highest - $year_rank), 2, ".", ",");
                $year_rank_goal = number_format(($year_rank_difference / $days_left_this_month), 2, ".", ",");

                $last_trade_detail = $client_operation->get_last_trade_detail($user_code);
                $last_trade_volume = $last_trade_detail['volume'];
                $last_trade_date = $last_trade_detail['date_earned'];

                $funded = $client_operation->get_total_funding($user_code, $from_date, $to_date);
                $withdrawn = $client_operation->get_total_withdrawal($user_code, $from_date, $to_date);

                $splurge_detail = $client_operation->get_splurge_user_point($user_code);
                $splurge_total_points = $splurge_detail['total_points'];
                $splurge_tier_target = $splurge_detail['tier_target'];

                $my_message_new = str_replace('[DINNER]', $dinner_2018, $my_message_new);
                $my_message_new = str_replace('[LPMP]', $month_position, $my_message_new);
                $my_message_new = str_replace('[LPMR]', $month_rank, $my_message_new);
                $my_message_new = str_replace('[LPMHR]', $month_rank_highest, $my_message_new);
                $my_message_new = str_replace('[LPMD]', $month_rank_difference, $my_message_new);
                $my_message_new = str_replace('[LPMG]', $month_rank_goal, $my_message_new);
                $my_message_new = str_replace('[LPYP]', $year_position, $my_message_new);
                $my_message_new = str_replace('[LPYR]', $year_rank, $my_message_new);
                $my_message_new = str_replace('[LPYHR]', $year_rank_highest, $my_message_new);
                $my_message_new = str_replace('[LPYG]', $year_rank_difference, $my_message_new);
                $my_message_new = str_replace('[LPYD]', $year_rank_goal, $my_message_new);
                $my_message_new = str_replace('[UC]', encrypt($user_code), $my_message_new);
                $my_message_new = str_replace('[LTD]', $last_trade_date, $my_message_new);
                $my_message_new = str_replace('[LTV]', $last_trade_volume, $my_message_new);
                $my_message_new = str_replace('[FUNDED]', $funded, $my_message_new);
                $my_message_new = str_replace('[WITHDRAWN]', $withdrawn, $my_message_new);
                $my_message_new = str_replace('[BFL]', $black_friday_link, $my_message_new);
                $my_message_new = str_replace('[SLP]', $splurge_total_points, $my_message_new);
                $my_message_new = str_replace('[SLL]', $splurge_tier_target, $my_message_new);

                $my_message_new = str_replace('[LPMP]', '', $my_message_new);
                $my_message_new = str_replace('[LPMR]', '', $my_message_new);
                $my_message_new = str_replace('[LPMHR]', '', $my_message_new);
                $my_message_new = str_replace('[LPMD]', '', $my_message_new);
                $my_message_new = str_replace('[LPMG]', '', $my_message_new);
                $my_message_new = str_replace('[LPYP]', '', $my_message_new);
                $my_message_new = str_replace('[LPYR]', '', $my_message_new);
                $my_message_new = str_replace('[LPYHR]', '', $my_message_new);
                $my_message_new = str_replace('[LPYG]', '', $my_message_new);
                $my_message_new = str_replace('[LPYD]', '', $my_message_new);
                $my_message_new = str_replace('[UC]', '', $my_message_new);
                $my_message_new = str_replace('[LTD]', '', $my_message_new);
                $my_message_new = str_replace('[LTV]', '', $my_message_new);
                $my_message_new = str_replace('[FUNDED]', '', $my_message_new);
                $my_message_new = str_replace('[WITHDRAWN]', '', $my_message_new);
                $my_message_new = str_replace('[BFL]', '', $my_message_new);
                $my_message_new = str_replace('[SLP]', '', $my_message_new);
                $my_message_new = str_replace('[SLL]', '', $my_message_new);
            }

            $system_object->send_email($my_subject_new, $my_message_new, $email, $client_name, $mail_sender);

        }
        $message_success = "You have successfully sent the single email.";
    }elseif($numrows > 200) {
        $message_error = "You can only send bulk mail to a maximum of 200 recipients";
    }elseif($numrows == 0) {
        $message_error = "No Participant Selected";
    }else{
        $message_error = "Please Kindly use the single Email Button.";

    }
}
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
        <script>
            function filter(value, heading) {
                document.getElementById('filter_display').value = heading;
                document.getElementById('filter_value').value = value;
                document.getElementById('filter_trigger').click();
            }
        </script>
        <script>
            function proceed(){
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
                    <?php include 'layouts/feedback_message.php'; ?>

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
                                <div class="pull-left"><a href="client_onboarding.php?r=1" title="Clear Search Parameter" class="btn btn-default">Refresh <i class="fa fa-recycle"></i></a></div>
                            </div>
                        </div>

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

                        <hr style="border: thin dotted #c5c5c5; margin-bottom: 5px;" />

                        <div class="row">
                            <div class="col-sm-12"><br /></div>
                        </div>

                        <div class="row">
                            <div class="col-sm-6">
                                <form data-toggle="validator" class="form-horizontal" role="form" method="post" action="client_onboarding.php">
                                    <div class="input-group">
                                        <input value="<?php echo $filter_category; ?>" id="filter_display" readonly type="text" name="filter_val" class="form-control" />
                                        <div class="input-group-btn input-group-select">
                                            <button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown">
                                                <span class="concept">Not Onboard Filter</span> <span class="caret"></span>
                                            </button>
                                            <ul class="dropdown-menu" role="menu">
                                                <li><a onclick="filter('all', 'All clients not onboard')" href="javascript:void(0);">All clients not onboard</a></li>
                                                <li><a onclick="filter('funded_ilpr', 'FUNDED ILPR account but not onboard')" href="javascript:void(0);">FUNDED ILPR account but not onboard</a></li>
                                                <li><a onclick="filter('funded_nonilpr', 'FUNDED Non-ILPR account but not onboard')" href="javascript:void(0);">FUNDED Non-ILPR account but not onboard</a></li>
                                                <li><a onclick="filter('ilpr', 'ILPR accounts and not onboard')" href="javascript:void(0);">ILPR accounts and not onboard</a></li>
                                                <li><a onclick="filter('nonilpr', 'NON-ILPR accounts and not onboard')" href="javascript:void(0);">NON-ILPR accounts and not onboard</a></li>
                                                <li><a onclick="filter('training', 'Training clients not onboard')" href="javascript:void(0);">Training clients not onboard</a></li>
                                            </ul>

                                            <input id="filter_value" name="filter_value" type="hidden" />
                                            <input id="filter_trigger" style="display: none" name="filter" type="submit" />
                                        </div>
                                    </div>
                                </form>
                            </div>

                            <div class="col-sm-6">
                                <div class="pull-right">
                                    <form action="" method="post">
                                    <button class="btn btn-default" type="button"
                                            data-target="#mail" data-toggle="modal">SEND BULK MAIL
                                    </button>
                                        <a data-target="#confirm-campaign" data-toggle="modal" class="btn btn-default" title="Create Campaign Category">Create Category</a>
                                        <a data-target="#onboarding-filter" data-toggle="modal" class="btn btn-default" title="Filter clients already onboard">Onboard Filter <i class="glyphicon glyphicon-search"></i></a>
                                    </form>
                                </div>

                                <!--Modal - confirmation boxes-->
                                <div id="mail" tabindex="-1" role="dialog" aria-hidden="true"
                                     class="modal fade">
                                    <form class="form-vertical text-center" role="form" method="post" action="">
                                        <div class="modal-dialog modal-md">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <button type="button" data-dismiss="modal" aria-hidden="true"
                                                            class="close">&times;</button>
                                                    <h4 class="modal-title">Send Single Bulk Mail</h4>
                                                </div>
                                                <div class="modal-body">

                                                    <p>Total Number of Recipients <b><?php echo $numrows;?></b> <br> Select the <b>subject of the email</b> you want to Braodcast to clients
                                                        <br><i class="fa fa-info-circle"></i>Enter the first letter of the subject to search</p>
                                                    <div class="form-group mx-sm-4 mb-2">
                                                        <strong><select name="campaign_email_id" class="form-control " required>
                                                                <?php
                                                                $query = "SELECT ce.subject AS campaign_subject, ce.campaign_email_id AS campaign_email_id, ce.sender AS campaign_sender
                                                              FROM campaign_email AS ce
                                                              INNER JOIN campaign_category AS cc ON ce.campaign_category_id = cc.campaign_category_id
                                                              LEFT JOIN campaign_email_track AS cet ON cet.campaign_id = ce.campaign_email_id
                                                              ORDER BY ce.created DESC ";
                                                                $result = $db_handle->runQuery($query);
                                                                $result = $db_handle->fetchAssoc($result);
                                                                foreach ($result as $row_email) {
                                                                    extract($row_email)
                                                                    ?>
                                                                    <option
                                                                        value="<?php if(isset($campaign_email_id)) { echo $campaign_email_id; } ?>"><?php echo $campaign_subject.' - SENDER ~'.$campaign_sender ; ?></option>
                                                                <?php } ?>
                                                            </select>
                                                        </strong>
                                                    </div>

                                                </div>
                                                <div class="modal-footer">
                                                    <button id="pro" onclick="proceed()" type="button" class="btn btn-default">PROCEED</button>

                                            <span id="proceed" style="display:none"><b>Are you ready to send this mail?</b>
                                            <input name="send_bulk" type="submit"
                                                   class="btn btn-sm btn-danger" value="YES/SEND">
                                                </span>
                                                    <button type="button" name="close" onClick="window.close();"
                                                            data-dismiss="modal" class="btn btn-sm btn-danger">Close!
                                                    </button>
                                                </div>
                                            </div>
                                        </div>
                                    </form>
                                </div>
                                <!--                        Modal end-->

                                <div id="onboarding-filter" tabindex="-1" role="dialog" aria-hidden="true" class="modal fade">
                                    <div class="modal-dialog">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <button type="button" data-dismiss="modal" aria-hidden="true"  class="close">&times;</button>
                                                <p class="modal-title">Fill the form below to filter clients that are already onboard, choose the year and period.</p>
                                            </div>

                                            <div class="modal-body">
                                                <form data-toggle="validator" class="form-horizontal" role="form" method="post" action="client_onboarding.php">

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

                            </div>
                        </div>

                        <div class="row">
                            <div class="col-sm-12">

                                <?php if (isset($numrows)) { ?>
                                    <p><strong>Result Found: </strong><?php echo number_format($numrows); ?></p>
                                <?php } ?>

                                <?php if ((isset($onboarding_result) && !empty($onboarding_result))) { require 'layouts/pagination_links.php'; } ?>

                                <div class="table-wrap">
                                    <table class="table table-responsive table-striped table-bordered table-hover">
                                        <thead>
                                        <tr>
                                            <th>Client Detail</th>
                                            <th>Reg. Date</th>
                                            <th>Action</th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                        <?php if ((isset($onboarding_result) && !empty($onboarding_result)) ) { foreach ($onboarding_result as $row) { extract($row); ?>
                                            <tr>
                                                <td>
                                                    <?php echo $full_name; ?><br />
                                                    <?php echo $email; ?><br />
                                                    <?php echo $phone; ?>
                                                </td>
                                                <td><?php echo datetime_to_text2($created); ?></td>
                                                <td nowrap>
                                                    <a target="_blank" title="Comment" class="btn btn-xs btn-success" href="sales_contact_view.php?x=<?php echo encrypt_ssl($row['user_code']); ?>&r=<?php echo 'client_onboarding'; ?>&c=<?php echo encrypt_ssl('CLIENT ON-BOARDING'); ?>&pg=<?php echo $currentpage; ?>"><i class="glyphicon glyphicon-comment icon-white"></i> </a>
                                                    <a target="_blank" title="View" class="btn btn-xs btn-info" href="client_detail.php?id=<?php echo encrypt_ssl($row['user_code']); ?>"><i class="glyphicon glyphicon-eye-open icon-white"></i> </a>
                                                    <a target="_blank" title="Send Email" class="btn btn-xs btn-primary" href="campaign_email_single.php?name=<?php $name = $row['full_name']; echo encrypt_ssl($name) . '&email=' . encrypt_ssl($row['email']); ?>"><i class="glyphicon glyphicon-envelope"></i></a>
                                                    <a target="_blank" title="Send SMS" class="btn btn-xs btn-success" href="campaign_sms_single.php?lead_phone=<?php echo encrypt_ssl($row['phone']) ?>"><i class="glyphicon glyphicon-phone-alt"></i></a>
                                                </td>
                                            </tr>
                                        <?php } } else { echo "<tr><td colspan='3' class='text-danger'><em>No results to display</em></td></tr>"; } ?>
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