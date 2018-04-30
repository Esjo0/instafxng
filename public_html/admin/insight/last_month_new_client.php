<?php
require_once("../../init/initialize_admin.php");
if (!$session_admin->is_logged_in()) {
    redirect_to("login.php");
}
$from_date = date('Y-m-d', strtotime('first day of last month'));
$to_date = date('Y-m-d', strtotime('last day of last month'));


//nbnm,./.lkjh
if(isset($_POST['search']))
{
    $search_text = $_POST['search_text'];
    $filter = $_POST['filter'];
    if(isset($filter) || isset($search_text))
    {
        switch ($filter)
        {
            case 1:
                $query = "SELECT u.user_code, CONCAT(u.last_name, SPACE(1), u.first_name) AS full_name, u.email, u.phone 
                          FROM user AS u 
                          WHERE (STR_TO_DATE(u.created, '%Y-%m-%d') 
                          BETWEEN '$from_date' AND '$to_date') AND (u.email LIKE '%$search_text%' OR u.first_name LIKE '%$search_text%' OR u.middle_name LIKE '%$search_text%' OR u.last_name LIKE '%$search_text%' OR u.phone LIKE '%$search_text%' OR u.created LIKE '$search_text%') 
                          ORDER BY u.created DESC ";
                $dispaly_msg = "Details of unique clients that joined the system last month.";
                break;
            case 2:
                $query = "SELECT DISTINCT user.user_code, user.first_name, user.email, user.phone 
                          FROM user,user_ifxaccount WHERE (STR_TO_DATE(user.created, '%Y-%m-%d') 
                          BETWEEN '$from_date' AND '$to_date') AND user.campaign_subscribe = '1' AND (user.user_code = user_ifxaccount.user_code) AND (user.email LIKE '%$search_text%' OR user.first_name LIKE '%$search_text%' OR user.middle_name LIKE '%$search_text%' OR user.last_name LIKE '%$search_text%' OR user.phone LIKE '%$search_text%' OR user.created LIKE '$search_text%') 
                          GROUP BY user.phone";
                $dispaly_msg = "Details of unique clients that joined the system last month New Clients with Accounts.";
                break;
            case 3:
                $query = "SELECT user.user_code, user.first_name, user.email, user.phone 
                          FROM user,user_ifxaccount,free_training_campaign WHERE (STR_TO_DATE(user.created, '%Y-%m-%d') 
                          BETWEEN '$from_date' AND '$to_date') AND user.campaign_subscribe = '1' AND NOT(user.user_code = user_ifxaccount.user_code) AND NOT(free_training_campaign.email = user.email) AND (user.email LIKE '%$search_text%' OR user.first_name LIKE '%$search_text%' OR user.middle_name LIKE '%$search_text%' OR user.last_name LIKE '%$search_text%' OR user.phone LIKE '%$search_text%' OR user.created LIKE '$search_text%') GROUP BY user.phone";
                $dispaly_msg = "Details of unique clients that joined the system last month New Clients without Accounts and NO training.";
                break;
            case 4:
                $query = "SELECT user.user_code, user.first_name, user.email, user.phone 
                FROM user,user_ifxaccount,free_training_campaign WHERE (STR_TO_DATE(user.created, '%Y-%m-%d') 
                BETWEEN '$from_date' AND '$to_date') AND user.campaign_subscribe = '1' AND NOT(user.user_code = user_ifxaccount.user_code) AND (free_training_campaign.email = user.email) AND (user.email LIKE '%$search_text%' OR user.first_name LIKE '%$search_text%' OR user.middle_name LIKE '%$search_text%' OR user.last_name LIKE '%$search_text%' OR user.phone LIKE '%$search_text%' OR user.created LIKE '$search_text%') 
                GROUP BY user.phone";
                $dispaly_msg = "Details of unique clients that joined the system last month New Clients without Accounts and have Training.";
                break;
            case 5:
                $query = "SELECT user.user_code, user.first_name, user.email, user.phone 
                          FROM user,user_ifxaccount,user_edu_deposits 
                          WHERE (STR_TO_DATE(user.created, '%Y-%m-%d') 
                          BETWEEN '$from_date' AND '$to_date') AND user.campaign_subscribe = '1' AND (user.user_code = user_edu_deposits.user_code) AND (MONTH(user_edu_deposits.created) = $current_month) AND (user.email LIKE '%$search_text%' OR user.first_name LIKE '%$search_text%' OR user.middle_name LIKE '%$search_text%' OR user.last_name LIKE '%$search_text%' OR user.phone LIKE '%$search_text%' OR user.created LIKE '$search_text%') 
                          GROUP BY user.phone";
                $dispaly_msg = "Details of unique clients that joined the system last month New Clients Still Forex Optimizer course in this current month.";
                break;
            case 6:
                $query = "SELECT DISTINCT user.user_code, user.first_name, user.email, user.phone
                          FROM user,user_deposit,user_ifxaccount WHERE (STR_TO_DATE(user.created, '%Y-%m-%d') 
                          BETWEEN '$from_date' AND '$to_date') AND user.campaign_subscribe = '1' AND (user.user_code = user_ifxaccount.user_code) AND (user_ifxaccount.ifxaccount_id = user_deposit.ifxaccount_id) AND (user_deposit.real_dollar_equivalent < 50) AND (user.email LIKE '%$search_text%' OR user.first_name LIKE '%$search_text%' OR user.middle_name LIKE '%$search_text%' OR user.last_name LIKE '%$search_text%' OR user.phone LIKE '%$search_text%' OR user.created LIKE '$search_text%') 
                          GROUP BY user.phone";
                $dispaly_msg = "Details of unique clients that joined the system last month New Clients not yet funded above $50.";
                break;

            default:
                $query = "SELECT u.user_code, CONCAT(u.last_name, SPACE(1), u.first_name) AS full_name, u.email, u.phone 
                          FROM user AS u 
                          WHERE (STR_TO_DATE(u.created, '%Y-%m-%d') 
                          BETWEEN '$from_date' AND '$to_date') AND (u.email LIKE '%$search_text%' OR u.first_name LIKE '%$search_text%' OR u.middle_name LIKE '%$search_text%' OR u.last_name LIKE '%$search_text%' OR u.phone LIKE '%$search_text%' OR u.created LIKE '$search_text%') 
                          ORDER BY u.created DESC ";
                $dispaly_msg = "Details of unique clients that joined the system last month.";
                break;
        }


        }
    else{
    $query = "SELECT user.user_code, CONCAT(u.last_name, SPACE(1), u.first_name) AS full_name, u.email, u.phone
        FROM user AS u
        WHERE (STR_TO_DATE(u.created, '%Y-%m-%d') BETWEEN '$from_date' AND '$to_date') ORDER BY u.created DESC ";
        $dispaly_msg = "Details of unique clients that joined the system last month New Clients.";
    }
}

if(isset($query))
{
    $_SESSION['query'] = $query;
}
$query = $_SESSION['query'];

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
$client_insight = $db_handle->fetchAssoc($result);


?>
<!DOCTYPE html>
<html lang="en" id="myForm">
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
                                <form data-toggle="validator" class="form-horizontal" role="form" method="post" action="">
                                    <div class="col-sm-4">
                                        <select name="filter" class="form-control" id="filter" onchange="myFunction()">
                                            <option <?php if($filter == 1){echo "selected";} ?> value="1">Last Months New Clients</option>
                                            <option <?php if($filter == 2){echo "selected";} ?> value="2">Last Months New Clients with Accounts</option>
                                            <option <?php if($filter == 3){echo "selected";} ?> value="3">Last Months New Clients without Accounts and have no Training</option>
                                            <option <?php if($filter == 4){echo "selected";} ?> value="4">Last Months New Clients without Accounts and have Training</option>
                                            <option <?php if($filter == 5){echo "selected";} ?> value="5">Last Months New Trainee Still in course 2 in current month</option>
                                            <option <?php if($filter == 6){echo "selected";} ?> value="6">Last Months New Clients not yet funded above $50</option>
                                        </select>
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
                        <p><?php echo $dispaly_msg; ?></p>

                        <?php if(isset($numrows)) { ?>
                            <p><strong>Result Found: </strong><?php echo number_format($numrows); ?></p>
                        <?php } ?>

                        <?php if(isset($client_insight) && !empty($client_insight)) { require 'layouts/pagination_links.php'; } ?>

                        <table class="table table-responsive table-striped table-bordered table-hover">
                            <thead>
                            <tr>
                                <th>Client Name</th>
                                <th>Email</th>
                                <th>Phone Number</th>
                                <th>Action</th>
                            </tr>
                            </thead>
                            <tbody>
                            <?php
                            if(isset($client_insight) && !empty($client_insight)) {
                                foreach ($client_insight as $row) {
                                    ?>
                                    <tr>
                                        <td><?php echo $row['full_name']; ?></td>
                                        <td><?php echo $row['email']; ?></td>
                                        <td><?php echo $row['phone']; ?></td>
                                        <td nowrap="nowrap">
                                            <a title="View" class="btn btn-success" href="../client_reach.php?x=<?php echo encrypt($row['user_code']); ?>&r=<?php echo 'insight/last_month_new_client'; ?>&c=<?php echo encrypt('LAST MONTH NEW CLIENT'); ?>&pg=<?php echo $currentpage; ?>"><i class="glyphicon glyphicon-comment icon-white"></i></a>
                                            <a target="_blank" title="View" class="btn btn-info" href="../client_detail.php?id=<?php echo encrypt($row['user_code']); ?>"><i class="glyphicon glyphicon-eye-open icon-white"></i> </a>
                                        </td>
                                    </tr>
                                <?php } } else { echo "<tr><td colspan='8' class='text-danger'><em>No results to display</em></td></tr>"; } ?>
                            </tbody>
                        </table>
                        <br /><br />

                        <?php if(isset($client_insight) && !empty($client_insight)) { ?>
                            <div class="tool-footer text-right">
                                <p class="pull-left">Showing <?php echo $prespagelow . " to " . $prespagehigh . " of " . $numrows; ?> entries</p>
                            </div>
                        <?php } ?>

                        <?php if(isset($client_insight) && !empty($client_insight)) { require 'layouts/pagination_links.php'; } ?>

                    </div>
                </div>

            </div>

            <!-- Unique Page Content Ends Here
            ================================================== -->

        </div>

    </div>
</div>
<script>
    function myFunction() {
        document.getElementById('search').click();
    }
</script>

<?php require_once '../layouts/footer.php'; ?>
</body>
</html>