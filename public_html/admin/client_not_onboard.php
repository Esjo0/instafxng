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

switch ($_SESSION['selected_cat']) {
    case 'all':
        $query = "SELECT u.user_code, CONCAT(u.last_name, SPACE(1), u.first_name) AS full_name, u.email, u.phone, u.created
FROM user AS u INNER JOIN user_ifxaccount AS ui ON u.user_code = ui.user_code
WHERE ui.ifx_acct_no NOT IN (SELECT ifx_acct_no FROM trading_commission WHERE commission > 0)
GROUP BY u.email ORDER BY u.created DESC, u.last_name ASC  ";
        $filter_category = "All Clients Not on board";
        $display_msg = "Below is a table listing all clients not yet on board.";
        break;

    case 'funded':
        $query = "SELECT u.user_code, CONCAT(u.last_name, SPACE(1), u.first_name) AS full_name, u.email, u.phone, u.created
FROM user AS u INNER JOIN user_ifxaccount AS ui ON u.user_code = ui.user_code
INNER JOIN user_deposit AS ud ON ui.ifxaccount_id = ud.ifxaccount_id
WHERE ud.status = '8' AND ui.ifx_acct_no NOT IN (SELECT ifx_acct_no FROM trading_commission WHERE commission > 0)
GROUP BY u.email ORDER BY u.created DESC, u.last_name ASC ";
        $filter_category = "Clients not yet on board but have funded their accounts";
        $display_msg = "Below is a table listing all clients not yet on board but have completed funding transactions.";
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
    default:
        $query = "SELECT u.user_code, CONCAT(u.last_name, SPACE(1), u.first_name) AS full_name, u.email, u.phone, u.created
FROM user AS u INNER JOIN user_ifxaccount AS ui ON u.user_code = ui.user_code
WHERE ui.ifx_acct_no NOT IN (SELECT ifx_acct_no FROM trading_commission WHERE commission > 0)
GROUP BY u.email ORDER BY u.created DESC, u.last_name ASC ";
        $filter_category = "All Unverified Clients";
        $display_msg = "Below is a table listing all unverified clients.";
        break;
}

if (isset($_POST['search'])) {
    $search_text = $db_handle->sanitizePost(trim($_POST['search_text']));
    switch ($_SESSION['selected_cat']) {
        case 'all':
            $query = "SELECT u.user_code, CONCAT(u.last_name, SPACE(1), u.first_name) AS full_name, u.email, u.phone, u.created
FROM user AS u INNER JOIN user_ifxaccount AS ui ON u.user_code = ui.user_code
WHERE ui.ifx_acct_no NOT IN (SELECT ifx_acct_no FROM trading_commission WHERE commission > 0)
AND (ui.ifx_acct_no LIKE '%$search_text%' OR u.email LIKE '%$search_text%' OR u.first_name LIKE '%$search_text%' OR u.middle_name LIKE '%$search_text%' OR u.last_name LIKE '%$search_text%' OR u.phone LIKE '%$search_text%' OR u.created LIKE '$search_text%')
GROUP BY u.email ORDER BY u.created DESC, u.last_name ASC  ";
            $filter_category = "All Unverified Clients";
            $display_msg = "Below is a table listing all clients not yet on board.";
            break;

        case 'funded':
            $query = "SELECT u.user_code, CONCAT(u.last_name, SPACE(1), u.first_name) AS full_name, u.email, u.phone, u.created
FROM user AS u INNER JOIN user_ifxaccount AS ui ON u.user_code = ui.user_code
INNER JOIN user_deposit AS ud ON ui.ifxaccount_id = ud.ifxaccount_id
WHERE ud.status = '8' AND ui.ifx_acct_no NOT IN (SELECT ifx_acct_no FROM trading_commission WHERE commission > 0)
AND (ui.ifx_acct_no LIKE '%$search_text%' OR u.email LIKE '%$search_text%' OR u.first_name LIKE '%$search_text%' OR u.middle_name LIKE '%$search_text%' OR u.last_name LIKE '%$search_text%' OR u.phone LIKE '%$search_text%' OR u.created LIKE '$search_text%')
GROUP BY u.email ORDER BY u.created DESC, u.last_name ASC ";
            $filter_category = "Clients not yet on board but have funded their accounts";
            $display_msg = "Below is a table listing all clients not yet on board but have completed funding transactions.";
            break;

        case 'ilpr':
            $query = "SELECT u.user_code, CONCAT(u.last_name, SPACE(1), u.first_name) AS full_name, u.email, u.phone, u.created
FROM user AS u INNER JOIN user_ifxaccount AS ui ON u.user_code = ui.user_code
WHERE ui.type = '1' AND ui.ifx_acct_no NOT IN (SELECT ifx_acct_no FROM trading_commission WHERE commission > 0)
AND (ui.ifx_acct_no LIKE '%$search_text%' OR u.email LIKE '%$search_text%' OR u.first_name LIKE '%$search_text%' OR u.middle_name LIKE '%$search_text%' OR u.last_name LIKE '%$search_text%' OR u.phone LIKE '%$search_text%' OR u.created LIKE '$search_text%')
GROUP BY u.email ORDER BY u.created DESC, u.last_name ASC ";
            $filter_category = "Clients not yet On-Board With ILPR Accounts";
            $display_msg = "Below is a table listing all clients not yet on board with ILPR account numbers.";
            break;

        case 'nonilpr':
            $query = "SELECT u.user_code, CONCAT(u.last_name, SPACE(1), u.first_name) AS full_name, u.email, u.phone, u.created
FROM user AS u INNER JOIN user_ifxaccount AS ui ON u.user_code = ui.user_code
WHERE ui.type = '2' AND ui.ifx_acct_no NOT IN (SELECT ifx_acct_no FROM trading_commission WHERE commission > 0)
AND (ui.ifx_acct_no LIKE '%$search_text%' OR u.email LIKE '%$search_text%' OR u.first_name LIKE '%$search_text%' OR u.middle_name LIKE '%$search_text%' OR u.last_name LIKE '%$search_text%' OR u.phone LIKE '%$search_text%' OR u.created LIKE '$search_text%')
GROUP BY u.email ORDER BY u.created DESC, u.last_name ASC ";
            $filter_category = "Clients not yet On-Board having NON-ILPR Accounts";
            $display_msg = "Below is a table listing all clients not yet on board and don't have ILPR account numbers.";
            break;

        case 'training':
            $query = "SELECT u.user_code, CONCAT(u.last_name, SPACE(1), u.first_name) AS full_name, u.email, u.phone, u.created
FROM user AS u INNER JOIN user_ifxaccount AS ui ON u.user_code = ui.user_code
WHERE ui.ifx_acct_no NOT IN (SELECT ifx_acct_no FROM trading_commission WHERE commission > 0)
AND  u.user_code IN (SELECT user_code FROM user AS U WHERE U.academy_signup IS NOT NULL)
AND (ui.ifx_acct_no LIKE '%$search_text%' OR u.email LIKE '%$search_text%' OR u.first_name LIKE '%$search_text%' OR u.middle_name LIKE '%$search_text%' OR u.last_name LIKE '%$search_text%' OR u.phone LIKE '%$search_text%' OR u.created LIKE '$search_text%')
GROUP BY u.email ORDER BY u.created DESC, u.last_name ASC ";
            $filter_category = "Training Clients Not yet ON-Board";
            $display_msg = "Below is a table listing all clients not yet on board but have enrolled in the FxAcademy.";
            break;

        default:
            $query = "SELECT u.user_code, CONCAT(u.last_name, SPACE(1), u.first_name) AS full_name, u.email, u.phone, u.created
FROM user AS u INNER JOIN user_ifxaccount AS ui ON u.user_code = ui.user_code
WHERE ui.ifx_acct_no NOT IN (SELECT ifx_acct_no FROM trading_commission WHERE commission > 0)
GROUP BY u.email ORDER BY u.created DESC, u.last_name ASC ";
            $filter_category = "All Unverified Clients";
            $display_msg = "Below is a table listing all clients not yet on board.";
            break;
    }
}

$numrows = $db_handle->numRows($query);

if (isset($_POST['search_text'])) {
    $rowsperpage = $numrows;
} else {
    $rowsperpage = 20;
}

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
    <title>Instaforex Nigeria | Admin - Clients Not ON-Board</title>
    <meta name="title" content="Instaforex Nigeria | Admin - Unverified Clients"/>
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
                    <h4><strong>CLIENTS NOT YET ON-BOARD</strong></h4>
                </div>
            </div>

            <div class="section-tint super-shadow">
                <div class="row">
                    <div class="col-sm-12">
                        <a href="client_onboarding.php"><button class="btn btn-sm btn-default"><i class="fa fa-arrow-left"></i>GO BACK</button></a>
                        <?php require_once 'layouts/feedback_message.php'; ?>
                        <div class="col-sm-12"></div>
                        <div class="row">
                            <div class="col-sm-6">
                                <form data-toggle="validator" class="form-horizontal" role="form" method="post"
                                      action="<?php echo $REQUEST_URI; ?>">
                                    <div class="input-group input-group-sm">
                                        <input value="<?php echo $filter_category; ?>" id="filter_display" readonly
                                               type="text" name="filter_val" class="form-control">
                                        <div class="input-group-btn input-group-select">
                                            <button type="button" class="btn btn-default dropdown-toggle"
                                                    data-toggle="dropdown">
                                                <span class="concept">Filter</span> <span class="caret"></span>
                                            </button>
                                            <ul class="dropdown-menu" role="menu">
                                                <li><a onclick="filter('all', 'All Clients Not ON Board')"
                                                       href="javascript:void(0);">All Clients Not ON-Board</a></li>
                                                <li><a onclick="filter('funded', 'Clients FUNDED but not on board')"
                                                       href="javascript:void(0);">Clients who Have FUNDED and are Not
                                                        ON-Board</a></li>
                                                <li>
                                                    <a onclick="filter('ilpr', 'Clients With ILPR Accounts But not On board')"
                                                       href="javascript:void(0);">Clients With ILPR Accounts Not
                                                        ON-Board</a></li>
                                                <li>
                                                    <a onclick="filter('nonilpr', 'Clients With NON-ILPR Accounts and not On board')"
                                                       href="javascript:void(0);">Clients With NON-ILPR Accounts Not
                                                        ON-Board</a></li>
                                                <li><a onclick="filter('training', 'Training Clients Not on board')"
                                                       href="javascript:void(0);">Training Clients Not ON-Board</a></li>
                                            </ul>
                                            <input id="filter_trigger" style="display: none" name="filter"
                                                   type="submit">
                                            <input id="filter_value" name="filter_value" type="hidden">
                                        </div>
                                    </div>
                                </form>
                            </div>
                            <div class="col-sm-1"></div>
                            <div class="col-sm-5">
                                <form data-toggle="validator" class="form-horizontal" role="form" method="post"
                                      action="<?php echo $REQUEST_URI; ?>">
                                    <div class="input-group input-group-sm">
                                        <input minlength="3" type="text" class="form-control"
                                               value="<?php echo $search_text ?>" name="search_text"
                                               placeholder="Search term..." required>
                                                <span class="input-group-btn">
                                                    <button name="search" class="btn btn-default" type="submit"><span
                                                            class="glyphicon glyphicon-search"></span></button>
                                                </span>
                                    </div>
                                </form>
                            </div>
                        </div>
                        <br/>


                        <div class="row">
                            <div class="col-sm-9">
                                <?php echo $display_msg; ?> <br/>
                            </div>
                            <div class="col-sm-3">
                                <div class="input-group input-group-sm <?php if (number_format($numrows) == 0) {
                                    echo 'has-danger';
                                } elseif (number_format($numrows) > 0) {
                                    echo 'has-success';
                                } ?> ">
                                    <span class="input-group-addon">Results Found</span>
                                    <input value="<?php echo number_format($numrows); ?>" class="form-control"
                                           disabled/>
                                </div>
                            </div>
                        </div>


                        <?php if (isset($unverified_clients) && !empty($unverified_clients)) {
                            require 'layouts/pagination_links.php';
                        } ?>

                        <table class="table table-responsive table-striped table-bordered table-hover">
                            <thead>
                            <tr>
                                <th>Full Name</th>
                                <th>Email</th>
                                <th>Phone</th>
                                <th>Reg Date</th>
                                <th>Action</th>
                            </tr>
                            </thead>
                            <tbody>
                            <?php if (isset($unverified_clients) && !empty($unverified_clients)) {
                                foreach ($unverified_clients as $row) { ?>
                                    <tr>
                                        <td><?php echo $row['full_name']; ?></td>
                                        <td><?php echo $row['email']; ?></td>
                                        <td><?php echo $row['phone']; ?></td>
                                        <td><?php echo datetime_to_text2($row['created']); ?></td>
                                        <td nowrap="nowrap">
                                            <a title="Comment" class="btn btn-xs btn-success"
                                               href="sales_contact_view.php?x=<?php echo encrypt($row['user_code']); ?>&r=<?php echo 'client_not_onboard'; ?>&c=<?php echo encrypt('CLIENT NOT ON BOARD'); ?>&pg=<?php echo $currentpage; ?>"><i
                                                    class="glyphicon glyphicon-comment icon-white"></i> </a>
                                            <a target="_blank" title="View" class="btn btn-xs btn-info"
                                               href="client_detail.php?id=<?php echo encrypt($row['user_code']); ?>"><i
                                                    class="glyphicon glyphicon-eye-open icon-white"></i> </a>
                                            <a class="btn btn-xs btn-primary" title="Send Email"
                                               href="campaign_email_single.php?name=<?php $name = $row['full_name'];
                                               echo encrypt_ssl($name) . '&email=' . encrypt_ssl($row['email']); ?>"><i
                                                    class="glyphicon glyphicon-envelope"></i></a>
                                            <a class="btn btn-xs btn-success" title="Send SMS"
                                               href="campaign_sms_single.php?lead_phone=<?php echo encrypt_ssl($row['phone']) ?>"><i
                                                    class="glyphicon glyphicon-phone-alt"></i></a>
                                        </td>
                                    </tr>
                                <?php }
                            } else {
                                echo "<tr><td colspan='6' class='text-danger'><em>No results to display</em></td></tr>";
                            } ?>
                            </tbody>
                        </table>


                        <?php if (isset($unverified_clients) && !empty($unverified_clients)) { ?>
                            <div class="tool-footer text-right">
                                <p class="pull-left">
                                    Showing <?php echo $prespagelow . " to " . $prespagehigh . " of " . $numrows; ?>
                                    entries</p>
                            </div>
                        <?php } ?>
                        <br/>
                    </div>

                    <?php if (isset($unverified_clients) && !empty($unverified_clients)) {
                        require 'layouts/pagination_links.php';
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