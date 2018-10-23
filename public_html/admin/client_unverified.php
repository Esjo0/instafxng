<?php
require_once("../init/initialize_admin.php");

if (!$session_admin->is_logged_in()) {
    redirect_to("login.php");
}

if (empty($_SESSION['selected_cat'])) {
    $_SESSION['selected_cat'] = 'all';
}

if (isset($_POST['filter'])) {
    foreach ($_POST as $key => $value) {
        $_POST[$key] = $db_handle->sanitizePost(trim($value));
    }
    $_SESSION['selected_cat'] = $_POST['filter_value'];
}

$base_query = "SELECT u.user_code, CONCAT(u.last_name, SPACE(1), u.first_name) AS full_name, 
    u.email, u.phone, u.created, CONCAT(a.last_name, SPACE(1), a.first_name) AS account_officer_full_name
    FROM user AS u
    INNER JOIN account_officers AS ao ON u.attendant = ao.account_officers_id
    INNER JOIN admin AS a ON ao.admin_code = a.admin_code
    WHERE (u.password IS NULL OR u.password = '')
    GROUP BY u.email ORDER BY u.created DESC, u.last_name ASC ";
$db_handle->runQuery("CREATE TEMPORARY TABLE unverified_clients AS " . $base_query);

switch ($_SESSION['selected_cat']) {
    case 'all':
        $query = "SELECT user_code, full_name, email, phone, created, account_officer_full_name FROM unverified_clients GROUP BY email ORDER BY created DESC ";
        $filter_category = "All Unverified Clients";
        $display_msg = "Below is a table listing all unverified clients.";
        break;

    case 'ilpr':
        $query = "SELECT user_code, full_name, email, phone, created, account_officer_full_name FROM unverified_clients
            WHERE user_code IN (SELECT user_code FROM user_ifxaccount AS UI WHERE UI.type = '1')
            GROUP BY email ORDER BY created DESC ";
        $filter_category = "Clients With ILPR Accounts";
        $display_msg = "Below is a table listing all unverified clients with ILPR account numbers.";
        break;

    case 'nonilpr':
        $query = "SELECT user_code, full_name, email, phone, created, account_officer_full_name FROM unverified_clients
            WHERE (user_code NOT IN (SELECT user_code FROM user_ifxaccount)) OR (user_code IN (SELECT user_code FROM user_ifxaccount AS UI WHERE UI.type = '2')
            GROUP BY email ORDER BY created DESC ) ";
        $filter_category = "Clients Without ILPR Accounts";
        $display_msg = "Below is a table listing all unverified clients without ILPR account numbers.";
        break;

    case 'training':
        $query = "SELECT user_code, full_name, email, phone, created, account_officer_full_name FROM unverified_clients 
            WHERE user_code IN (SELECT user_code FROM user AS U WHERE U.academy_signup IS NOT NULL)
            GROUP BY email ORDER BY created DESC ";
        $filter_category = "Training Clients";
        $display_msg = "Below is a table listing all unverified clients that have enrolled in the FxAcademy.";
        break;

    case 'profile':
        $query = "SELECT user_code, full_name, email, phone, created, account_officer_full_name FROM unverified_clients
            WHERE (user_code NOT IN (SELECT user_code FROM user AS U WHERE U.academy_signup IS NOT NULL))
            AND (user_code NOT IN (SELECT user_code FROM user_ifxaccount))
            GROUP BY email ORDER BY created DESC ";
        $filter_category = "Unverified Clients with Profile only";
        $display_msg = "Below is a table listing all unverified clients that have profile only.";
        break;

    case 'level1':
        $query = "SELECT u.user_code, CONCAT(u.last_name, SPACE(1), u.first_name) AS full_name, u.email, u.phone, u.created,
            CONCAT(a.last_name, SPACE(1), a.first_name) AS account_officer_full_name
            FROM user_verification AS uv
            INNER JOIN user AS u ON uv.user_code = u.user_code
            INNER JOIN account_officers AS ao ON u.attendant = ao.account_officers_id
            INNER JOIN admin AS a ON ao.admin_code = a.admin_code
            LEFT JOIN user_bank AS ub ON u.user_code = ub.user_code
            LEFT JOIN user_credential AS uc ON u.user_code = uc.user_code
            WHERE (uv.phone_status = '2') AND (uc.doc_status != '111') AND (ub.status != '2' OR ub.bank_acct_no IS NULL) AND u.email IN(SELECT email FROM unverified_campaign_mail_log)
            GROUP BY u.email ORDER BY u.created DESC ";
        $filter_category = "Unverified to Level1";
        $display_msg = "Unverified clients who moved to the Level 1 Through the scheduled mail campaign.";
        break;

    case 'level2':
        $query = "SELECT u.user_code, CONCAT(u.last_name, SPACE(1), u.first_name) AS full_name, u.email, u.phone, u.created, CONCAT(a.last_name, SPACE(1), a.first_name) AS account_officer_full_name
            FROM user_credential AS uc
            INNER JOIN user AS u ON uc.user_code = u.user_code
            INNER JOIN account_officers AS ao ON u.attendant = ao.account_officers_id
            INNER JOIN admin AS a ON ao.admin_code = a.admin_code
            LEFT JOIN user_bank AS ub ON u.user_code = ub.user_code
            WHERE (uc.doc_status = '111') AND (ub.status != '2' OR ub.bank_acct_no IS NULL) AND u.email IN(SELECT email FROM unverified_campaign_mail_log)
            GROUP BY u.email ORDER BY u.created DESC ";
        $filter_category = "Unverified to Level2";
        $display_msg = "Unverified clients who moved to the Level 2 Through the scheduled mail campaign.";
        break;

    case 'level3':
        $query = "SELECT u.user_code, CONCAT(u.last_name, SPACE(1), u.first_name) AS full_name, u.email, u.phone, u.created, CONCAT(a.last_name, SPACE(1), a.first_name) AS account_officer_full_name
            FROM user_bank AS ub
            INNER JOIN user AS u ON ub.user_code = u.user_code
            INNER JOIN account_officers AS ao ON u.attendant = ao.account_officers_id
            INNER JOIN admin AS a ON ao.admin_code = a.admin_code
            WHERE (ub.is_active = '1' AND ub.status = '2') AND u.email IN(SELECT email FROM unverified_campaign_mail_log)
            GROUP BY u.email ORDER BY u.created DESC ";
        $filter_category = "Unverified to Level3";
        $display_msg = "Unverified clients who moved to the Level 3 Through the scheduled mail campaign";
        break;

    case 'previous':
        $query = "SELECT u.user_code, uc.full_name, u.email, u.phone, uc.created, uc.account_officer_full_name FROM unverified_clients AS uc
            INNER JOIN user AS u ON uc.user_code = u.user_code
            INNER JOIN user_ifxaccount AS ui ON u.user_code = ui.user_code
            INNER JOIN user_deposit AS ud ON ui.ifxaccount_id = ud.ifxaccount_id
            INNER JOIN trading_commission AS tc ON ui.ifx_acct_no = tc.ifx_acct_no
            WHERE YEAR(tc.date_earned) > 2013 OR YEAR(ud.created) > 2013
            GROUP BY u.email ORDER BY u.created DESC ) ";
        $filter_category = "Unverified Clients with previous Activity";
        $display_msg = "Below is a table listing all unverified clients who have traded or funded with us before";
        break;

    default:
        $query = "SELECT user_code, full_name, email, phone, created, account_officer_full_name FROM unverified_clients GROUP BY email ORDER BY created DESC ";
        $filter_category = "All Unverified Clients";
        $display_msg = "Below is a table listing all unverified clients.";
        break;
}

if (isset($_POST['search'])) {
    $search_text = $db_handle->sanitizePost(trim($_POST['search_text']));
    switch ($_SESSION['selected_cat']) {
        case 'all':
            $query = "SELECT user_code, full_name, email, phone, created, account_officer_full_name FROM unverified_clients 
                WHERE full_name LIKE '%$search_text%' OR email LIKE '%$search_text%' OR phone LIKE '%$search_text%'
                GROUP BY email ORDER BY created DESC ";
            $filter_category = "All Unverified Clients";
            $display_msg = "Below is a table listing all unverified clients.";
            break;

        case 'ilpr':
            $query = "SELECT user_code, full_name, email, phone, created, account_officer_full_name FROM unverified_clients
                WHERE (user_code IN (SELECT user_code FROM user_ifxaccount AS UI WHERE UI.type = '1')) 
                AND (full_name LIKE '%$search_text%' OR email LIKE '%$search_text%' OR phone LIKE '%$search_text%')
                GROUP BY email ORDER BY created DESC ";
            $filter_category = "Clients With ILPR Accounts";
            $display_msg = "Below is a table listing all unverified clients with ILPR account numbers.";
            break;

        case 'nonilpr':
            $query = "SELECT user_code, full_name, email, phone, created, account_officer_full_name FROM unverified_clients
                WHERE ((user_code NOT IN (SELECT user_code FROM user_ifxaccount)) OR (user_code IN (SELECT user_code FROM user_ifxaccount AS UI WHERE UI.type = '2')))
                AND (full_name LIKE '%$search_text%' OR email LIKE '%$search_text%' OR phone LIKE '%$search_text%')
                GROUP BY email ORDER BY created DESC ";
            $filter_category = "Clients Without ILPR Accounts";
            $display_msg = "Below is a table listing all unverified clients without ILPR account numbers.";
            break;

        case 'training':
            $query = "SELECT user_code, full_name, email, phone, created, account_officer_full_name FROM unverified_clients 
                WHERE (user_code IN (SELECT user_code FROM user AS U WHERE U.academy_signup IS NOT NULL)) 
                AND (full_name LIKE '%$search_text%' OR email LIKE '%$search_text%' OR phone LIKE '%$search_text%')
                GROUP BY email ORDER BY created DESC ";
            $filter_category = "Training Clients";
            $display_msg = "Below is a table listing all unverified clients that have enrolled in the FxAcademy.";
            break;

        case 'profile':
            $query = "SELECT user_code, full_name, email, phone, created, account_officer_full_name FROM unverified_clients
                WHERE (user_code NOT IN (SELECT user_code FROM user AS U WHERE U.academy_signup IS NOT NULL))
                AND (user_code NOT IN (SELECT user_code FROM user_ifxaccount))
                AND (full_name LIKE '%$search_text%' OR email LIKE '%$search_text%' OR phone LIKE '%$search_text%')
                GROUP BY email ORDER BY created DESC ";
            $filter_category = "Unverified Clients with Profile only";
            $display_msg = "Below is a table listing all unverified clients that have profile only.";
            break;

        case 'level1':
            $query = "SELECT u.user_code, CONCAT(u.last_name, SPACE(1), u.first_name) AS full_name, u.email, u.phone, u.created, CONCAT(a.last_name, SPACE(1), a.first_name) AS account_officer_full_name
                FROM user_verification AS uv
                INNER JOIN user AS u ON uv.user_code = u.user_code
                INNER JOIN account_officers AS ao ON u.attendant = ao.account_officers_id
                INNER JOIN admin AS a ON ao.admin_code = a.admin_code
                LEFT JOIN user_ifxaccount AS ui ON uv.user_code = ui.user_code
                LEFT JOIN user_bank AS ub ON u.user_code = ub.user_code
                LEFT JOIN user_credential AS uc ON u.user_code = uc.user_code
                WHERE (uv.phone_status = '2') AND (uc.doc_status != '111') AND (ub.status != '2' OR ub.bank_acct_no IS NULL) AND (ui.ifx_acct_no LIKE '%$search_text%' OR u.email LIKE '%$search_text%' OR u.first_name LIKE '%$search_text%' OR u.middle_name LIKE '%$search_text%' OR u.last_name LIKE '%$search_text%' OR u.phone LIKE '%$search_text%' OR u.created LIKE '$search_text%')
                AND u.email IN(SELECT email FROM unverified_campaign_mail_log)
                GROUP BY u.email ORDER BY u.created DESC ";
            $filter_category = "Unverified to Level1";
            $display_msg = "Unverified clients who moved to the Level 1 Through the scheduled mail campaign.";
            break;

        case 'level2':
            $query = "SELECT u.user_code, CONCAT(u.last_name, SPACE(1), u.first_name) AS full_name, u.email, u.phone, u.created, CONCAT(a.last_name, SPACE(1), a.first_name) AS account_officer_full_name
                FROM user_credential AS uc
                INNER JOIN user AS u ON uc.user_code = u.user_code
                INNER JOIN account_officers AS ao ON u.attendant = ao.account_officers_id
                INNER JOIN admin AS a ON ao.admin_code = a.admin_code
                LEFT JOIN user_ifxaccount AS ui ON uc.user_code = ui.user_code
                LEFT JOIN user_bank AS ub ON u.user_code = ub.user_code
                WHERE (uc.doc_status = '111') AND (ub.status != '2' OR ub.bank_acct_no IS NULL) AND (ui.ifx_acct_no LIKE '%$search_text%' OR u.email LIKE '%$search_text%' OR u.first_name LIKE '%$search_text%' OR u.middle_name LIKE '%$search_text%' OR u.last_name LIKE '%$search_text%' OR u.phone LIKE '%$search_text%' OR u.created LIKE '$search_text%') GROUP BY u.email ORDER BY u.created DESC \";
                AND u.email IN(SELECT email FROM unverified_campaign_mail_log)
                GROUP BY u.email ORDER BY u.created DESC ";
            $filter_category = "Unverified to Level2";
            $display_msg = "Unverified clients who moved to the Level 2 Through the scheduled mail campaign.";
            break;

        case 'level3':
            $query = "SELECT u.user_code, CONCAT(u.last_name, SPACE(1), u.first_name) AS full_name, u.email, u.phone, u.created, CONCAT(a.last_name, SPACE(1), a.first_name) AS account_officer_full_name
                FROM user_bank AS ub
                INNER JOIN user AS u ON ub.user_code = u.user_code
                INNER JOIN account_officers AS ao ON u.attendant = ao.account_officers_id
                INNER JOIN admin AS a ON ao.admin_code = a.admin_code
                LEFT JOIN user_ifxaccount AS ui ON ub.user_code = ui.user_code
                WHERE (ub.is_active = '1' AND ub.status = '2') AND (ui.ifx_acct_no LIKE '%$search_text%' OR u.email LIKE '%$search_text%' OR u.first_name LIKE '%$search_text%' OR u.middle_name LIKE '%$search_text%' OR u.last_name LIKE '%$search_text%' OR u.phone LIKE '%$search_text%' OR u.created LIKE '$search_text%') GROUP BY u.email ORDER BY u.created DESC \";
                AND u.email IN(SELECT email FROM unverified_campaign_mail_log)
                GROUP BY u.email ORDER BY u.created DESC ";
            $filter_category = "Unverified to Level3";
            $display_msg = "Unverified clients who moved to the Level 3 Through the scheduled mail campaign";
            break;

        case 'previous':
            $query = "SELECT u.user_code, uc.full_name, u.email, u.phone, uc.created, uc.account_officer_full_name FROM unverified_clients AS uc
                INNER JOIN user AS u ON uc.user_code = u.user_code
                INNER JOIN user_ifxaccount AS ui ON u.user_code = ui.user_code
                INNER JOIN user_deposit AS ud ON ui.ifxaccount_id = ud.ifxaccount_id
                INNER JOIN trading_commission AS tc ON ui.ifx_acct_no = tc.ifx_acct_no
                WHERE YEAR(tc.date_earned) > 2013 OR YEAR(ud.created) > 2013
                AND (ui.ifx_acct_no LIKE '%$search_text%' OR u.email LIKE '%$search_text%' OR u.first_name LIKE '%$search_text%' OR u.middle_name LIKE '%$search_text%' OR u.last_name LIKE '%$search_text%' OR u.phone LIKE '%$search_text%' OR u.created LIKE '$search_text%')
                GROUP BY u.email ORDER BY u.created DESC ) ";
            $filter_category = "Unverified Clients with previous Activity";
            $display_msg = "Below is a table listing all unverified clients who have traded or funded with us before since n";
            break;

        default:
            $query = "SELECT user_code, full_name, email, phone, created, account_officer_full_name FROM unverified_clients 
                WHERE full_name LIKE '%$search_text%' OR email LIKE '%$search_text%' OR phone LIKE '%$search_text%'
                GROUP BY email ORDER BY created DESC ";
            $filter_category = "All Unverified Clients";
            $display_msg = "Below is a table listing all unverified clients.";
            break;
    }
}

$numrows = $db_handle->numRows($query);

if (isset($_POST['search_text'])) {$rowsperpage = $numrows;} else {$rowsperpage = 20;}

$totalpages = ceil($numrows / $rowsperpage);
// get the current page or set a default
if (isset($_GET['pg']) && is_numeric($_GET['pg'])) {$currentpage = (int) $_GET['pg'];}
else {$currentpage = 1;}
if ($currentpage > $totalpages) { $currentpage = $totalpages; }
if ($currentpage < 1) { $currentpage = 1; }

$prespagelow = $currentpage * $rowsperpage - $rowsperpage + 1;
$prespagehigh = $currentpage * $rowsperpage;
if($prespagehigh > $numrows) { $prespagehigh = $numrows; }

$offset = ($currentpage - 1) * $rowsperpage;
$query .= ' LIMIT ' . $offset . ',' . $rowsperpage;
$result = $db_handle->runQuery($query);
$unverified_clients = $db_handle->fetchAssoc($result);

$db_handle->closeDB();
?>
<!DOCTYPE html>
<html lang="en">
    <head>
        <base target="_self">
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>Instaforex Nigeria | Admin - Unverified Clients</title>
        <meta name="title" content="Instaforex Nigeria | Admin - Unverified Clients" />
        <meta name="keywords" content="" />
        <meta name="description" content="" />
        <?php require_once 'layouts/head_meta.php'; ?>
        <script>
            function filter(value, heading) {
                document.getElementById('filter_display').value = heading;
                document.getElementById('filter_value').value = value;
                document.getElementById('filter_trigger').click();
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
                            <h4><strong>UNVERIFIED CLIENTS</strong></h4>
                        </div>
                    </div>
                    
                    <div class="section-tint super-shadow">
                        <div class="row">
                            <div class="col-sm-12">
                                <?php require_once 'layouts/feedback_message.php'; ?>
                                <div class="col-sm-12"></div>
                                <div class="row">
                                    <div class="col-sm-6">
                                        <form data-toggle="validator" class="form-horizontal" role="form" method="post" action="<?php echo $REQUEST_URI; ?>">
                                            <div class="input-group input-group-sm">
                                                <input value="<?php echo $filter_category; ?>" id="filter_display" readonly type="text" name="filter_val" class="form-control">
                                                <div class="input-group-btn input-group-select">
                                                    <button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown">
                                                        <span class="concept">Filter</span>  <span class="caret"></span>
                                                    </button>
                                                    <ul class="dropdown-menu" role="menu">
                                                        <li><a onclick="filter('all', 'All Unverified Clients')" href="javascript:void(0);">All Unverified Clients</a></li>
                                                        <li><a onclick="filter('ilpr', 'Clients With ILPR Accounts')" href="javascript:void(0);">Clients With ILPR Accounts</a></li>
                                                        <li><a onclick="filter('nonilpr', 'Clients Without ILPR Accounts')" href="javascript:void(0);">Clients Without ILPR Accounts</a></li>
                                                        <li><a onclick="filter('training', 'Training Clients')" href="javascript:void(0);">Training Clients</a></li>
                                                        <li><a onclick="filter('profile', 'Unverified clients with profile only')" href="javascript:void(0);">Profile only</a></li>
                                                        <li><a onclick="filter('level1', 'Unverified to Level1')" href="javascript:void(0);">Unverified to Level 1 from Campaign</a></li>
                                                        <li><a onclick="filter('level2', 'Unverified to level2')" href="javascript:void(0);">Unverified to Level 2 from Campaign</a></li>
                                                        <li><a onclick="filter('level3', 'Unverified to level3')" href="javascript:void(0);">Unverified to Level 3 from Campaign</a></li>
                                                        <li><a onclick="filter('previous', 'Unverified With Previous activity')" href="javascript:void(0);">Unverified Clients with Previous activity</a></li>
                                                    </ul>
                                                    <input id="filter_trigger" style="display: none" name="filter" type="submit">
                                                    <input id="filter_value" name="filter_value" type="hidden">
                                                </div>
                                            </div>
                                        </form>
                                    </div>
                                    <div class="col-sm-1"></div>
                                    <div class="col-sm-5">
                                        <form data-toggle="validator" class="form-horizontal" role="form" method="post" action="<?php echo $REQUEST_URI; ?>">
                                            <div class="input-group input-group-sm">
                                                <input minlength="3" type="text" class="form-control" value="<?php echo $search_text ?>" name="search_text" placeholder="Search term..." required>
                                                <span class="input-group-btn">
                                                    <button name="search" class="btn btn-default" type="submit"><span class="glyphicon glyphicon-search"></span></button>
                                                </span>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                                <br/>


                                <div class="row">
                                    <div class="col-sm-9">
                                        <?php echo $display_msg; ?> <br/>
                                            These clients have not done any form of
                                            transaction since the last quarter of year 2014.
                                    </div>
                                    <div class="col-sm-3">
                                        <div class="input-group input-group-sm <?php if(number_format($numrows) == 0){echo 'has-danger';}elseif(number_format($numrows) > 0){echo 'has-success';} ?> ">
                                            <span class="input-group-addon">Results Found</span>
                                            <input value="<?php echo number_format($numrows); ?>" class="form-control" disabled/>
                                        </div>
                                    </div>
                                </div>


                                <?php if(isset($unverified_clients) && !empty($unverified_clients)) { require 'layouts/pagination_links.php'; } ?>

                                <table class="table table-responsive table-striped table-bordered table-hover">
                                    <thead>
                                    <tr>
                                        <th>Full Name</th>
                                        <th>Email</th>
                                        <th>Phone</th>
                                        <th>Reg Date</th>
                                        <th>Account Officer</th>
                                        <th>Action</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    <?php if(isset($unverified_clients) && !empty($unverified_clients)) { foreach ($unverified_clients as $row) {?>
                                        <tr>
                                            <td><?php echo $row['full_name']; ?></td>
                                            <td><?php echo $row['email']; ?></td>
                                            <td><?php echo $row['phone']; ?></td>
                                            <td><?php echo datetime_to_text2($row['created']); ?></td>
                                            <td><?php echo $row['account_officer_full_name']; ?></td>
                                            <td nowrap="nowrap">
                                                <a title="Comment" class="btn btn-xs btn-success" href="sales_contact_view.php?x=<?php echo encrypt($row['user_code']); ?>&r=<?php echo 'client_unverified'; ?>&c=<?php echo encrypt('UNVERIFIED CLIENT'); ?>&pg=<?php echo $currentpage; ?>"><i class="glyphicon glyphicon-comment icon-white"></i> </a>
                                                <a target="_blank" title="View" class="btn btn-xs btn-info" href="client_detail.php?id=<?php echo encrypt($row['user_code']); ?>"><i class="glyphicon glyphicon-eye-open icon-white"></i> </a>
                                                <a target="_blank" title="View Campaign Mail Status" class="btn btn-xs btn-primary" href="client_unverified_email_log_details.php?id=<?php echo encrypt($row['user_code']); ?>"><i class="glyphicon glyphicon-envelope icon-white"></i> </a>
                                            </td>
                                        </tr>
                                    <?php } } else { echo "<tr><td colspan='6' class='text-danger'><em>No results to display</em></td></tr>"; } ?>
                                    </tbody>
                                </table>



                                <?php if(isset($unverified_clients) && !empty($unverified_clients)) { ?>
                                <div class="tool-footer text-right">
                                    <p class="pull-left">Showing <?php echo $prespagelow . " to " . $prespagehigh . " of " . $numrows; ?> entries</p>
                                </div>
                                <?php } ?>
                            <br />
                        </div>
                        
                        <?php if(isset($unverified_clients) && !empty($unverified_clients)) { require 'layouts/pagination_links.php'; } ?>
                    </div>

                    <!-- Unique Page Content Ends Here
                    ================================================== -->
                    
                </div>
                
            </div>
        </div>
        <?php require_once 'layouts/footer.php'; ?>
    </body>
</html>