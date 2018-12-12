<?php
require_once("../init/initialize_admin.php");
if (!$session_admin->is_logged_in()) {
    redirect_to("login.php");
}

if (isset($_POST['commission_view']) || isset($_GET['pg'])) {

    if(isset($_POST['commission_view'])) {
        foreach ($_POST as $key => $value) {
            $_POST[$key] = $db_handle->sanitizePost(trim($value));
        }

        $from_date = $_POST['from_date'];
        $to_date = $_POST['to_date'];
        $search_text = $_POST['search_text'];

        $query = "SELECT GROUP_CONCAT(DISTINCT td.ifx_acct_no SEPARATOR ', ') AS accounts, SUM(td.volume) AS sum_volume, SUM(td.commission) AS sum_commission,
                CONCAT(u.last_name, SPACE(1), u.first_name) AS full_name, u.user_code
                FROM trading_commission AS td
                INNER JOIN user_ifxaccount AS ui ON td.ifx_acct_no = ui.ifx_acct_no
                INNER JOIN user AS u ON ui.user_code = u.user_code
                WHERE date_earned BETWEEN '$from_date' AND '$to_date' ";
        if(isset($search_text) && strlen($search_text) > 3) {
            $query .= "AND (td.ifx_acct_no LIKE '%$search_text%' OR u.email LIKE '%$search_text%' OR u.first_name LIKE '%$search_text%' OR u.middle_name LIKE '%$search_text%' OR u.last_name LIKE '%$search_text%' OR u.phone LIKE '%$search_text%' OR td.date_earned LIKE '$search_text%') ";
        }
        $query .= "GROUP BY u.email ORDER BY sum_commission DESC ";

        $query2 = "SELECT SUM(sum_volume) AS total_sum_volume, SUM(sum_commission) AS total_sum_commission
                FROM (SELECT SUM(td.volume) AS sum_volume, SUM(td.commission) AS sum_commission
                FROM trading_commission AS td
                INNER JOIN user_ifxaccount AS ui ON td.ifx_acct_no = ui.ifx_acct_no
                INNER JOIN user AS u ON ui.user_code = u.user_code
                WHERE date_earned BETWEEN '$from_date' AND '$to_date'  ";
        if(isset($search_text) && strlen($search_text) > 3) {
            $query2 .= "AND (td.ifx_acct_no LIKE '%$search_text%' OR u.email LIKE '%$search_text%' OR u.first_name LIKE '%$search_text%' OR u.middle_name LIKE '%$search_text%' OR u.last_name LIKE '%$search_text%' OR u.phone LIKE '%$search_text%' OR td.date_earned LIKE '$search_text%') ";
        }
        $query2 .= "GROUP BY u.email) src ";

        $_SESSION['search_client_query'] = $query;
        $_SESSION['search_client_query2'] = $query2;
        $_SESSION['search_client_query_from_date'] = $from_date;
        $_SESSION['search_client_query_to_date'] = $to_date;
        $_SESSION['search_client_query_search'] = $search_text;
    } else {
        $query = $_SESSION['search_client_query'];
        $query2 = $_SESSION['search_client_query2'];
        $from_date = $_SESSION['search_client_query_from_date'];
        $to_date = $_SESSION['search_client_query_to_date'];
        $search_text = $_SESSION['search_client_query_search'];
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
    $all_commissions = $db_handle->fetchAssoc($result);

    $result2 = $db_handle->runQuery($query2);
    $all_commissions_stat = $db_handle->fetchAssoc($result2);
    $all_commissions_stat = $all_commissions_stat[0];

}

$query = "SELECT date_earned FROM trading_commission ORDER BY date_earned DESC LIMIT 1";
$result = $db_handle->runQuery($query);
$last_updated = $db_handle->fetchAssoc($result);
$last_updated = date_to_text($last_updated[0]['date_earned']);

?>
<!DOCTYPE html>
<html lang="en">
    <head>
        <base target="_self">
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>Instaforex Nigeria | Admin - View Commissions</title>
        <meta name="title" content="Instaforex Nigeria | Admin - View Commissions" />
        <meta name="keywords" content="" />
        <meta name="description" content="" />
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
                                        
                    <div class="row">
                        <div class="col-sm-12 text-danger">
                            <h4><strong>VIEW COMMISSIONS</strong></h4>
                        </div>
                    </div>
                    
                    <div class="section-tint super-shadow">
                        <div class="row">
                            <div class="col-sm-12">
                                <?php require_once 'layouts/feedback_message.php'; ?>
                                
                                <p>Choose a date range below and get commission reports</p>
                                <p>Please note that commission earlier than October 17, 2016 is not captured. To limit your search more, enter a search term in the
                                space for search parameter. Your search parameter can be account number, name, email, phone number and date. Date should be in the format 2016-12-25.</p>

                                <form data-toggle="validator" class="form-horizontal" role="form" method="post" action="<?php echo $_SERVER['REQUEST_URI']; ?>">
                                    <div class="form-group">
                                        <label class="control-label col-sm-3" for="from_date">From:</label>
                                        <div class="col-sm-9 col-lg-5">
                                            <div class="input-group date">
                                                <input name="from_date" type="text" class="form-control" id="datetimepicker" required>
                                                <span class="input-group-addon"><span class="glyphicon glyphicon-calendar"></span></span>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="control-label col-sm-3" for="to_date">To:</label>
                                        <div class="col-sm-9 col-lg-5">
                                            <div class="input-group date">
                                                <input name="to_date" type="text" class="form-control" id="datetimepicker2" required>
                                                <span class="input-group-addon"><span class="glyphicon glyphicon-calendar"></span></span>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="control-label col-sm-3" for="search_text">Search:</label>
                                        <div class="col-sm-9 col-lg-5">
                                            <div>
                                                <input type="text" class="form-control" name="search_text" value="" placeholder="Search term...">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <div class="col-sm-offset-3 col-sm-9"><input name="commission_view" type="submit" class="btn btn-success" value="Display" /></div>
                                    </div>
                                    <script type="text/javascript">
                                        $(function () {
                                            $('#datetimepicker, #datetimepicker2').datetimepicker({
                                                format: 'YYYY-MM-DD'
                                            });
                                        });
                                    </script>
                                </form>
                                <p class="text-danger"><small>Commission Last Updated: <?php echo $last_updated; ?></small></p>

                                <hr /><br />
                                <?php if(isset($numrows)) { ?>
                                    <p>
                                        Showing commission from <?php echo date_to_text($from_date); ?> to <?php echo date_to_text($to_date); ?><br />
                                        No of Clients: <?php echo $numrows; ?><br />
                                        Total Volume: <?php echo number_format($all_commissions_stat['total_sum_volume'], 2, ".", ","); ?><br />
                                        Total Commission: &dollar; <?php echo number_format($all_commissions_stat['total_sum_commission'], 2, ".", ","); ?><br />
                                    </p>
                                <?php } ?>

                                <table class="table table-responsive table-striped table-bordered table-hover">
                                    <thead>
                                        <tr>
                                            <th>Full Name</th>
                                            <th>Accounts</th>
                                            <th>Volume</th>
                                            <th>Commission</th>
                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php if(isset($all_commissions) && !empty($all_commissions)) { foreach ($all_commissions as $row) { ?>
                                        <tr>
                                            <td><?php echo $row['full_name']; ?></td>
                                            <td><?php echo $row['accounts']; ?></td>
                                            <td><?php echo number_format($row['sum_volume'], 2, ".", ","); ?></td>
                                            <td>&dollar; <?php echo number_format($row['sum_commission'], 2, ".", ","); ?></td>
                                            <td>
                                                <a target="_blank" title="View" class="btn btn-info" href="client_detail.php?id=<?php echo dec_enc('encrypt', $row['user_code']); ?>"><i class="glyphicon glyphicon-eye-open icon-white"></i> </a>
                                            </td>
                                        </tr>
                                        <?php } } else { echo "<tr><td colspan='5' class='text-danger'><em>No results to display</em></td></tr>"; } ?>
                                    </tbody>
                                </table>
                                
                                <?php if(isset($all_commissions) && !empty($all_commissions)) { ?>
                                <div class="tool-footer text-right">
                                    <p class="pull-left">Showing <?php echo $prespagelow . " to " . $prespagehigh . " of " . $numrows; ?> entries</p>
                                </div>
                                <?php } ?>

                            </div>
                        </div>
                        <?php if(isset($all_commissions) && !empty($all_commissions)) { require_once 'layouts/pagination_links.php'; } ?>
                        
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