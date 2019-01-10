<?php
require_once("../../init/initialize_admin.php");
if (!$session_admin->is_logged_in()) {
    redirect_to("login.php");
}
$from_date = date('Y-m-d', strtotime('first day of last month'));
$to_date = date('Y-m-d', strtotime('last day of last month'));
$current_month = date('n');
if(($_SESSION['last_month_query'] == NULL) || empty($_SESSION['last_month_query'])){
    $query = "SELECT u.user_code, CONCAT(u.last_name, SPACE(1), u.first_name) AS full_name, u.email, u.phone FROM user AS u WHERE (STR_TO_DATE(u.created, '%Y-%m-%d') BETWEEN '$from_date' AND '$to_date') ORDER BY u.created DESC";
    $display_msg = "Details of unique clients that joined the system last month.";
}
if(isset($_POST['called'])){
    $user_code = $db_handle->sanitizePost($_POST['user_code']);
    $query = "SELECT * FROM call_log WHERE user_code = '$user_code'";
    $numrows = $db_handle->numRows($query);
    if($numrows == 0){
        $query = "INSERT INTO call_log (user_code, status, source) VALUES ('$user_code', '1', 'LAST_MONTH_NEW_CLIENT')";
        $result = $db_handle->runQuery($query);
        if($result){
            $message_success = "Successfully updated as contacted";
        }else{
            $message_error = "Contact Update Not Successful.";
        }
    }elseif($numrows == 1){
        $query = "UPDATE call_log SET status = '1' WHERE user_code = '$user_code' AND source = 'LAST_MONTH_NEW_CLIENT' ";
        $result = $db_handle->runQuery($query);
        if($result){
            $message_success = "Successfully updated as contacted";
        }else{
            $message_error = "Contact Update Not Successful.";
        }
    }

}

if(isset($_POST['follow_up'])){
    $user_code = $db_handle->sanitizePost($_POST['user_code']);
    $comment = $db_handle->sanitizePost($_POST['comment']);
    $query = "SELECT * FROM call_log WHERE user_code = '$user_code' AND source = 'LAST_MONTH_NEW_CLIENT'";
    $numrows = $db_handle->numRows($query);
    if($numrows == 0){
        $sales_comment = "LAST MONTH NEW CLIENT:" . $comment;
        $admin_code = $_SESSION['admin_unique_code'];
        $query = "INSERT INTO sales_contact_comment (user_code, admin_code, comment) VALUES ('$user_code', '$admin_code', '$sales_comment')";
        $result = $db_handle->runQuery($query);
        $query = "INSERT INTO call_log (user_code, status, follow_up_comment, source) VALUES ('$user_code', '2', '$comment', 'LAST_MONTH_NEW_CLIENT')";
        $result = $db_handle->runQuery($query);
        if($result){
            $message_success = "Successfully saved for follow-up call";
        }else{
            $message_error = "Contact Update Not Successful.";
        }
    }elseif($numrows == 1){
        $sales_comment = "LAST MONTH NEW CLIENT:" . $comment;
        $admin_code = $_SESSION['admin_unique_code'];
        $query = "INSERT INTO sales_contact_comment (user_code, admin_code, comment) VALUES ('$user_code', '$admin_code', '$sales_comment')";
        $result = $db_handle->runQuery($query);
        $query = "UPDATE call_log SET status = '2', follow_up_comment = '$comment' WHERE user_code = '$user_code' AND source = 'LAST_MONTH_NEW_CLIENT'";
        $result = $db_handle->runQuery($query);
        if($result){
            $message_success = "Successfully saved for follow-up call";
        }else{
            $message_error = "Contact Update Not Successful.";
        }
    }

}

if(isset($_POST['search']))
{
    $search_text = $_POST['search_text'];
    $query = "SELECT u.user_code, CONCAT(u.last_name, SPACE(1), u.first_name) AS full_name, u.email, u.phone
              FROM user AS u
              WHERE u.email LIKE '%$search_text%'
              OR u.first_name LIKE '%$search_text%'
              OR u.middle_name LIKE '%$search_text%'
              OR u.last_name LIKE '%$search_text%'
              OR u.phone LIKE '%$search_text%'
              OR u.created LIKE '$search_text%'
              ORDER BY u.created DESC ";
    $display_msg = "Search result for '$search_text' ";
    $_SESSION['last_month_query'] = $query;
}

if(isset($_POST['selector']))
{
    $filter = $_POST['filter'];
    switch ($filter)
    {
        case 1:
            $query = "SELECT u.user_code, CONCAT(u.last_name, SPACE(1), u.first_name) AS full_name, u.email, u.phone FROM user AS u WHERE (STR_TO_DATE(u.created, '%Y-%m-%d') BETWEEN '$from_date' AND '$to_date') ORDER BY u.created DESC ";
            $display_msg = "Details of unique clients that joined the system last month.";
            break;
        case 2:
            $query = "SELECT u.user_code, CONCAT(u.last_name, SPACE(1), u.first_name) AS full_name, u.email, u.phone
                FROM user AS u
                INNER JOIN user_ifxaccount AS ui ON u.user_code = ui.user_code
                WHERE (STR_TO_DATE(u.created, '%Y-%m-%d') BETWEEN '$from_date' AND '$to_date')
                AND ui.user_code = u.user_code
                AND ui.type = '1'
                GROUP BY u.email ";
            $display_msg = "Details of unique clients that joined the system last month New Clients with Accounts.";
            break;
        case 3:
            $query = "SELECT u.user_code, CONCAT(u.last_name, SPACE(1), u.first_name) AS full_name, u.email, u.phone
                FROM user AS u
                LEFT JOIN user_ifxaccount AS ui ON u.user_code = ui.user_code
                LEFT JOIN free_training_campaign AS ftc ON u.email = ftc.email
                WHERE (STR_TO_DATE(u.created, '%Y-%m-%d') BETWEEN '$from_date' AND '$to_date')
                AND ui.user_code IS NULL
                AND ftc.email IS NULL GROUP BY u.email ";
            $display_msg = "Details of unique clients that joined the system last month New Clients with NO Accounts and NO training.";
            break;
        case 4:
            $query = "SELECT u.user_code, CONCAT(u.last_name, SPACE(1), u.first_name) AS full_name, u.email, u.phone
                FROM user AS u
                LEFT JOIN user_ifxaccount AS ui ON u.user_code = ui.user_code
                LEFT JOIN free_training_campaign AS ftc ON u.email = ftc.email
                WHERE (STR_TO_DATE(u.created, '%Y-%m-%d') BETWEEN '$from_date' AND '$to_date')
                AND ui.user_code IS NULL
                AND ftc.email IS NOT NULL GROUP BY u.email ";
            $display_msg = "Details of unique clients that joined the system last month New Clients without Accounts and have Training.";
            break;
        case 5:
            $query = "SELECT u.user_code, CONCAT(u.last_name, SPACE(1), u.first_name) AS full_name, u.email, u.phone
                FROM user AS u
                LEFT JOIN user_ifxaccount AS ui ON u.user_code = ui.user_code
                LEFT JOIN user_edu_deposits AS ued ON u.user_code = ued.user_code
                WHERE (STR_TO_DATE(u.created, '%Y-%m-%d') BETWEEN '$from_date' AND '$to_date')
                AND ued.user_code IS NOT NULL
                AND MONTH(ued.created) = $current_month
                GROUP BY u.email";
            $display_msg = "Details of unique clients that joined the system last month New Clients Still Forex Optimizer course in this current month.";
            break;
        case 6:
            $query = "SELECT DISTINCT u.user_code, CONCAT(u.last_name, SPACE(1), u.first_name) AS full_name, u.email, u.phone
                FROM user AS u
                LEFT JOIN user_ifxaccount AS ui ON u.user_code = ui.user_code
                LEFT JOIN user_deposit AS ud ON ui.ifxaccount_id = ud.ifxaccount_id
                WHERE (STR_TO_DATE(u.created, '%Y-%m-%d') BETWEEN '$from_date' AND '$to_date')
                AND u.user_code = ui.user_code
                AND ui.ifxaccount_id = ud.ifxaccount_id
                AND ud.real_dollar_equivalent < 50.00
                AND u.user_code NOT IN
                (SELECT DISTINCT u.user_code
                FROM user AS u
                LEFT JOIN user_ifxaccount AS ui ON u.user_code = ui.user_code
                LEFT JOIN user_deposit AS ud ON ui.ifxaccount_id = ud.ifxaccount_id
                WHERE (STR_TO_DATE(u.created, '%Y-%m-%d') BETWEEN '$from_date' AND '$to_date')
                AND u.user_code = ui.user_code
                AND ui.ifxaccount_id = ud.ifxaccount_id
                AND ud.real_dollar_equivalent >= 50.00
                GROUP BY u.email )
                GROUP BY u.email ";
            $display_msg = "Details of unique clients that joined the system last month New Clients not yet funded above $50.";
            break;
        case 7:
            $query = "SELECT DISTINCT u.user_code, CONCAT(u.last_name, SPACE(1), u.first_name) AS full_name, u.email, u.phone
                FROM user AS u
                LEFT JOIN user_ifxaccount AS ui ON u.user_code = ui.user_code
                LEFT JOIN user_deposit AS ud ON ui.ifxaccount_id = ud.ifxaccount_id
                WHERE (STR_TO_DATE(u.created, '%Y-%m-%d') BETWEEN '$from_date' AND '$to_date')
                AND u.user_code = ui.user_code
                AND ui.ifxaccount_id = ud.ifxaccount_id
                AND ud.real_dollar_equivalent >= 50.00
                GROUP BY u.email ";
            $display_msg = "Details of unique clients that joined the system last month New Clients funded above $50.";
            break;
        case 8:
            $query = "SELECT DISTINCT u.user_code, CONCAT(u.last_name, SPACE(1), u.first_name) AS full_name, u.email, u.phone
                FROM user AS u
                LEFT JOIN user_ifxaccount AS ui ON u.user_code = ui.user_code
                WHERE (STR_TO_DATE(u.created, '%Y-%m-%d') BETWEEN '$from_date' AND '$to_date')
                AND u.user_code = ui.user_code AND ui.ifxaccount_id NOT IN (SELECT ifxaccount_id FROM user_deposit)
                GROUP BY u.email ";
            $display_msg = "Details of unique clients that joined the system last month, But have not yet funded.";
            break;
        case 9:
            $query = "SELECT u.user_code, CONCAT(u.last_name, SPACE(1), u.first_name) AS full_name, u.email,
                u.phone, MIN(td.created) AS first_trade, u.created
                FROM user AS u
                INNER JOIN user_ifxaccount AS ui ON u.user_code = ui.user_code
                INNER JOIN trading_commission AS td ON td.ifx_acct_no = ui.ifx_acct_no
                WHERE (STR_TO_DATE(u.created, '%Y-%m-%d') BETWEEN '$from_date' AND '$to_date')
                GROUP BY u.user_code
                HAVING (STR_TO_DATE(first_trade, '%Y-%m-%d') BETWEEN '$from_date' AND '$to_date')
                ORDER BY u.created DESC ";
            $display_msg = "Details of unique clients that joined the system last month and also placed trades last month.";
            break;
        default:
            $query = "SELECT u.user_code, CONCAT(u.last_name, SPACE(1), u.first_name) AS full_name, u.email, u.phone
                FROM user AS u
                WHERE STR_TO_DATE(u.created, '%Y-%m-%d') BETWEEN '$from_date' AND '$to_date'
                ORDER BY u.created DESC ";
            $display_msg = "Details of unique clients that joined the system last month.";
            break;
    }
    $_SESSION['last_month_query'] = $query;
}

if(isset($_POST['contacted'])){
    $query = "SELECT u.user_code, CONCAT(u.last_name, SPACE(1), u.first_name) AS full_name, u.email, u.phone
                FROM user AS u
        INNER JOIN call_log AS cl ON u.user_code = cl.user_code
        LEFT JOIN account_officers AS ao ON u.attendant = ao.account_officers_id
        LEFT JOIN admin AS a ON ao.admin_code = a.admin_code
        WHERE (STR_TO_DATE(u.created, '%Y-%m-%d') BETWEEN '$from_date' AND '$to_date')
        AND u.status = '1' AND cl.status = '1' ORDER BY cl.created DESC ";
    $_SESSION['last_month_query'] = $query;
}
if(isset($_POST['not_contacted'])){
    $query = "SELECT u.user_code, CONCAT(u.last_name, SPACE(1), u.first_name) AS full_name, u.email, u.phone
                FROM user AS u
        LEFT JOIN account_officers AS ao ON u.attendant = ao.account_officers_id
        LEFT JOIN admin AS a ON ao.admin_code = a.admin_code
        WHERE (STR_TO_DATE(u.created, '%Y-%m-%d') BETWEEN '$from_date' AND '$to_date')
        AND u.status = '1' AND u.user_code NOT IN (SELECT user_code FROM call_log) ORDER BY u.created DESC ";
    $_SESSION['last_month_query'] = $query;
}
if(isset($_POST['follow_up_list'])){
    $query = "SELECT u.user_code, CONCAT(u.last_name, SPACE(1), u.first_name) AS full_name, u.email, u.phone
                FROM user AS u
        INNER JOIN call_log AS cl ON u.user_code = cl.user_code
        LEFT JOIN account_officers AS ao ON u.attendant = ao.account_officers_id
        LEFT JOIN admin AS a ON ao.admin_code = a.admin_code
        WHERE (STR_TO_DATE(u.created, '%Y-%m-%d') BETWEEN '$from_date' AND '$to_date')
        AND u.status = '1' AND cl.status = '2' ORDER BY u.created DESC ";
    $_SESSION['last_month_query'] = $query;
}

$query = $_SESSION['last_month_query'];

$numrows = $db_handle->numRows($query);
$rowsperpage = 20;
$totalpages = ceil($numrows / $rowsperpage);
// get the current page or set a default
if (isset($_GET['pg']) && is_numeric($_GET['pg'])) {$currentpage = (int) $_GET['pg'];}
else { $currentpage = 1;}
if ($currentpage > $totalpages) { $currentpage = $totalpages; }
if ($currentpage < 1) { $currentpage = 1; }
$prespagelow = $currentpage * $rowsperpage - $rowsperpage + 1;
$prespagehigh = $currentpage * $rowsperpage;
if($prespagehigh > $numrows) { $prespagehigh = $numrows; }
$offset = ($currentpage - 1) * $rowsperpage;
$query .= ' LIMIT ' . $offset . ',' . $rowsperpage;
$result = $db_handle->runQuery($query);
$clients = $db_handle->fetchAssoc($result);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <base target="_self">
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Instaforex Nigeria | Client Profile Insight</title>
    <meta name="title" content="Instaforex Nigeria | Client Profile Insight" />
    <meta name="keywords" content="" />
    <meta name="description" content="" />
    <?php require_once '../layouts/head_meta.php'; ?>
</head>
<body>
<?php require_once '../layouts/header.php'; ?>
<!-- Main Body: The is the main content area of the web site, contains a side bar  -->
<div id="main-body" class="container-fluid">
    <div class="row no-gutter">
        <!-- Main Body - Side Bar  -->
        <div id="main-body-side-bar" class="col-md-4 col-lg-3 left-nav">
            <?php require_once '../layouts/sidebar.php'; ?>
        </div>
        <!-- Main Body - Content Area: This is the main content area, unique for each page  -->
        <div id="main-body-content-area" class="col-md-8 col-lg-9">
            <!-- Unique Page Content Starts Here
            ================================================== -->
            <div class="row">
                <div class="col-sm-12 text-danger">
                    <h4><strong>CLIENT PROFILE INSIGHT</strong></h4>
                </div>
            </div>
            <div class="section-tint super-shadow">
                <div class="row">
                    <div class="col-sm-12">
                        <?php require_once '../layouts/feedback_message.php'; ?>
                        <div class="search-section">
                            <div class="row">
                                <form method="post" action="" class=" text-center form form-horizontal">
                                    <button type="submit" name="contacted" class="btn btn-default btn-sm">Clients Contacted</button>
                                    <button type="submit" name="not_contacted" class="btn btn-default btn-sm">Clients Not Contacted</button>
                                    <button type="submit" name="follow_up_list" class="btn btn-default btn-sm">Follow-up/call back</button>
                                </form>
                                <br>
                                <form data-toggle="validator" class="form-horizontal" role="form" method="post" action="">
                                    <div class="col-sm-4">
                                        <select name="filter" class="form-control" id="filter" onchange="selector_function()">
                                            <option <?php if($filter == 1){echo "selected";} ?> value="1">Last Month New Clients</option>
                                            <option <?php if($filter == 2){echo "selected";} ?> value="2">Last Month New Clients with Accounts</option>
                                            <option <?php if($filter == 3){echo "selected";} ?> value="3">Last Month New Clients without Accounts and have no Training</option>
                                            <option <?php if($filter == 4){echo "selected";} ?> value="4">Last Month New Clients without Accounts and have Training</option>
                                            <option <?php if($filter == 5){echo "selected";} ?> value="5">Last Month New Trainee Still in course 2 in current month</option>
                                            <option <?php if($filter == 6){echo "selected";} ?> value="6">Last Month New Clients not yet funded above $50</option>
                                            <option <?php if($filter == 7){echo "selected";} ?> value="7">Last Month New Clients funded above $50</option>
                                            <option <?php if($filter == 8){echo "selected";} ?> value="8">Last Month New Clients not yet funded</option>
                                            <option <?php if($filter == 9){echo "selected";} ?> value="9">Last Month New Clients - Also Traded Last Month</option>
                                        </select>
                                        <button style="display: none" id="_selector" name="selector" class="btn btn-default" type="submit"><span class="glyphicon glyphicon-search"></span></button>
                                    </div>
                                    <div class="col-sm-8">
                                        <div class="input-group">
                                            <input type="hidden" name="search_param" value="all" id="search_param">
                                            <input type="text" class="form-control" name="search_text" placeholder="Search term...">
                                                    <span class="input-group-btn">
                                                <button id="search" name="search" class="btn btn-default" type="submit"><span class="glyphicon glyphicon-search"></span></button>
                                            </span>
                                        </div>
                                    </div>
                                </form>

                            </div>
                        </div>
                        <p><?php echo $display_msg; ?></p>
                        <?php if(isset($numrows)) { ?>
                            <p><strong>Result Found: </strong><?php echo number_format($numrows); ?></p>
                        <?php } ?>
                        <?php if(isset($clients) && !empty($clients)) { include '../layouts/pagination_links.php'; } ?>
                        <table class="table table-responsive table-striped table-bordered table-hover">
                            <thead>
                            <tr>
                                <th>Client Name</th>
                                <th>Email</th>
                                <th>Phone Number</th>
                                <th>Action</th>
                                <th>Call Log</th>
                            </tr>
                            </thead>
                            <tbody>
                            <?php
                            if(isset($clients) && !empty($clients)) {
                                foreach ($clients as $row) {
                                    ?>
                                    <tr>
                                        <td><?php echo $row['full_name']; ?></td>
                                        <td><?php echo $row['email']; ?></td>
                                        <td><?php echo $row['phone']; ?></td>
                                        <td nowrap="nowrap">
                                            <a target="_blank" title="View" class="btn btn-success" href="client_reach.php?x=<?php echo dec_enc('encrypt', $row['user_code']); ?>&r=<?php echo 'insight/last_month_new_client'; ?>&c=<?php echo dec_enc('encrypt', 'LAST MONTH NEW CLIENT'); ?>&pg=<?php echo $currentpage; ?>"><i class="glyphicon glyphicon-comment icon-white"></i></a>
                                            <a target="_blank" title="View" class="btn btn-info" href="client_detail.php?id=<?php echo dec_enc('encrypt', $row['user_code']); ?>"><i class="glyphicon glyphicon-eye-open icon-white"></i> </a>
                                        </td>
                                        <td><?php call_log_status($row['user_code'], 'LAST_MONTH_NEW_CLIENT');?></td>
                                    </tr>
                                <?php } } else { echo "<tr><td colspan='8' class='text-danger'><em>No results to display</em></td></tr>"; } ?>
                            </tbody>
                        </table>
                        <br /><br />
                        <?php if(isset($clients) && !empty($clients)) { ?>
                            <div class="tool-footer text-right">
                                <p class="pull-left">Showing <?php echo $prespagelow . " to " . $prespagehigh . " of " . $numrows; ?> entries</p>
                            </div>
                        <?php } ?>
                        <?php if(isset($clients) && !empty($clients)) { include '../layouts/pagination_links.php'; } ?>
                    </div>
                </div>
            </div>
            <!-- Unique Page Content Ends Here
            ================================================== -->
        </div>
    </div>
</div>
<script> function selector_function() {  document.getElementById('_selector').click(); }</script>
<?php require_once '../layouts/footer.php'; ?>
</body>
</html>