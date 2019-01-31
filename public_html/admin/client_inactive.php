<?php
require_once("../init/initialize_admin.php");
if (!$session_admin->is_logged_in()) {
    redirect_to("login.php");
}

$client_operation = new clientOperation();

//<!-- Reminder Plug in-->
$call_reminder = $client_operation->get_call_reminder('INACTIVE TRADING CLIENT');
//<!-- Reminder Plug in-->

if (isset($_POST['inactive_trading_client']) || isset($_GET['pg'])) {

    if(isset($_POST['inactive_trading_client'])) {
        foreach ($_POST as $key => $value) {
            $_POST[$key] = $db_handle->sanitizePost(trim($value));
        }

        $from_date = $_POST['from_date'];
        $to_date = $_POST['to_date'];
        $search_text = $_POST['search_text'];
        $order = $_POST['order'];

        $query = "SELECT u.user_code, CONCAT(u.last_name, SPACE(1), u.first_name) AS full_name, u.email, u.phone, u.created, CONCAT(a.last_name, SPACE(1), a.first_name) AS account_officer_full_name, SUM(td.volume) AS my_volume, MAX(td.date_earned) AS last_trade_date
            FROM trading_commission AS td
            INNER JOIN user_ifxaccount AS ui ON td.ifx_acct_no = ui.ifx_acct_no
            INNER JOIN user AS u ON ui.user_code = u.user_code
            INNER JOIN account_officers AS ao ON u.attendant = ao.account_officers_id
            INNER JOIN admin AS a ON ao.admin_code = a.admin_code
            WHERE u.user_code NOT IN (
                SELECT u.user_code
                FROM trading_commission AS td
                INNER JOIN user_ifxaccount AS ui ON td.ifx_acct_no = ui.ifx_acct_no
                INNER JOIN user AS u ON ui.user_code = u.user_code
                WHERE STR_TO_DATE(td.date_earned, '%Y-%m-%d') BETWEEN '$from_date' AND '$to_date'
            ) AND STR_TO_DATE(td.date_earned, '%Y-%m-%d') <= '$to_date' ";
        if(isset($search_text) && strlen($search_text) > 3) {
            $query .= "AND (td.ifx_acct_no LIKE '%$search_text%' OR u.email LIKE '%$search_text%' OR u.first_name LIKE '%$search_text%' OR u.middle_name LIKE '%$search_text%' OR u.last_name LIKE '%$search_text%' OR u.phone LIKE '%$search_text%' OR td.date_earned LIKE '$search_text%') ";
        }
        $query .= "GROUP BY u.user_code ORDER BY $order DESC ";

        $_SESSION['search_client_query'] = $query;
        $_SESSION['search_client_query_from_date'] = $from_date;
        $_SESSION['search_client_query_to_date'] = $to_date;
        $_SESSION['search_client_query_search'] = $search_text;

    } else {
        $query = $_SESSION['search_client_query'];
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
    $query .= ' LIMIT ' . $offset . ',' . $rowsperpage;
    $result = $db_handle->runQuery($query);
    $selected_inactive_clients = $db_handle->fetchAssoc($result);
}

?>
<!DOCTYPE html>
<html lang="en">
    <head>
        <base target="_self">
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>Instaforex Nigeria | Admin - Inactive Trading Clients</title>
        <meta name="title" content="Instaforex Nigeria | Admin - Inactive Trading Clients" />
        <meta name="keywords" content="" />
        <meta name="description" content="" />
        <?php require_once 'layouts/head_meta.php'; ?>
        <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.10.19/css/jquery.dataTables.css">

        <script>
            $(document).ready( function () {
                $('#list_table').DataTable();
            } );
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
                            <h4><strong>INACTIVE TRADING CLIENTS</strong></h4>
                        </div>
                    </div>
                    
                    <div class="section-tint super-shadow">
                        <div class="row">
                            <div class="col-sm-12">
                                <?php require_once 'layouts/feedback_message.php'; ?>

                                <p>Pick a date range below to see <strong>Inactive Trading Clients</strong>. If you want to search for a client, enter a parameter in the search field.</p>

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
                                        <label class="control-label col-sm-3" for="search_text">Order:</label>
                                        <div class="col-sm-9 col-lg-5">
                                            <div class="row">
                                                <div class="col-sm-6"><div class="radio"><label for="my_volume"><input type="radio" name="order" value="my_volume" id="my_volume" required/> Trade Volume</label></div></div>
                                                <div class="col-sm-6"><div class="radio"><label for="last_trade_date"><input type="radio" name="order" value="last_trade_date" id="last_trade_date" /> Last Trade Date</label></div></div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <div class="col-sm-offset-3 col-sm-9"><input name="inactive_trading_client" type="submit" class="btn btn-success" value="Display" /></div>
                                    </div>
                                    <script type="text/javascript">
                                        $(function () {
                                            $('#datetimepicker, #datetimepicker2').datetimepicker({
                                                format: 'YYYY-MM-DD'
                                            });
                                        });
                                    </script>
                                </form>

                                <?php if(isset($numrows)) { ?>
                                    <p>
                                        Showing results from <?php echo date_to_text($from_date); ?> to <?php echo date_to_text($to_date); ?><br />
                                        <strong>Result Found: </strong><?php echo number_format($numrows); ?>
                                    </p>
                                <?php } ?>

                                <hr /><br />

                                <?php if(isset($selected_inactive_clients) && !empty($selected_inactive_clients)) { require 'layouts/pagination_links.php'; } ?>

                                <!-- Reminder Plug in-->
                                <div id="reminder" tabindex="-1" role="dialog" aria-hidden="true" class="modal fade">
                                    <div class="modal-dialog modal-lg">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <button type="button" data-dismiss="modal" aria-hidden="true"
                                                        class="close">&times;</button>
                                                <h4 class="modal-title">Contact Reminder.</h4>
                                            </div>
                                            <div class="modal-body">

                                                <table id="list_table" class="table table-responsive table-striped table-bordered table-hover">
                                                    <thead>
                                                    <tr>
                                                        <th>SN</th>
                                                        <th>Client Details</th>
                                                        <th>Comment</th>
                                                        <th>Reminder Date</th>
                                                        <th>Status</th>
                                                        <th>Date Created</th>
                                                        <th>Created By</th>
                                                    </tr>
                                                    </thead>
                                                    <tbody>
                                                    <?php if(isset($call_reminder) && !empty($call_reminder)){$total_rem_num = 1; $total_rem_pending = 0; foreach ($call_reminder AS $rem){?>

                                                        <tr>
                                                            <td><?php echo $total_rem_num; ?></td>
                                                            <td>
                                                                <?php echo $rem['full_name']; ?><br />
                                                            </td>
                                                            <td><?php echo $rem['comment']; ?></td>
                                                            <td><?php echo datetime_to_text($rem['reminder_date']); ?></td>
                                                            <td><?php
                                                                $encrypt_usercode = dec_enc('encrypt', $rem['user_code']);
                                                                $encrypt_page = dec_enc('encrypt', 'INACTIVE TRADING CLIENT');
                                                                $reminder_date = date_create($rem['reminder_date']);
                                                                $reminder_date = date_format($reminder_date,"d-m-Y");
                                                                $diff = dateDifference($reminder_date,$today);

                                                                if( ($rem['status'] == '0') && ($diff >= 1) ){
                                                                    $total_rem_pending++;
                                                                    echo "<b><i class='text-danger'>Over Due</i></b><br>
                                                                    <a target='_blank' title='Comment' class='btn btn-success btn-sm' href='sales_contact_view.php?x=$encrypt_usercode&r=client_inactive&c=$encrypt_page&pg=$currentpage'>Contact Client</a>";

                                                                }elseif($rem['status'] == '1'){
                                                                    echo "<b><i class='text-success'>Contacted</i></b>";
                                                                }elseif($rem['status'] == '0'){
                                                                    $total_rem_pending++;
                                                                    echo "<b><i class='text-warning'>Pending</i></b><br>
                                                                    <a target='_blank' title='Comment' class='btn btn-success btn-sm' href='sales_contact_view.php?x=$encrypt_usercode&r=client_inactive&c=$encrypt_page&pg=$currentpage'>Contact Client</a>";
                                                                }
                                                                ?></td>
                                                            <td><?php echo datetime_to_text($rem['created']); ?></td>
                                                            <td><?php echo $rem['admin']?></td>
                                                        </tr>
                                                        <?php $total_rem_num++; } } else { echo "<tr><td colspan='5' class='text-danger'><em>No results to display</em></td></tr>"; } ?>
                                                    </tbody>
                                                </table>
                                            </div>
                                            <div class="modal-footer">
                                                <button data-dismiss="modal" class="btn btn-danger">Close !</button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-sm-12">
                                        <button type="button" data-target="#reminder" data-toggle="modal" class="btn btn-success pull-right"><i class="fa fa-bell "></i> Contact Reminder  <span class="badge"><?php echo $total_rem_pending;?></span></button>
                                    </div>
                                </div>
                                <!-- Reminder Plug in-->

                                <table id="list_table" class="table table-responsive table-striped table-bordered table-hover">
                                    <thead>
                                    <tr>
                                        <th>Full Name</th>
                                        <th>Email</th>
                                        <th>Phone</th>
                                        <th>Reg Date</th>
                                        <th>Last Trade Date</th>
                                        <th>Account Officer</th>
                                        <th>Action</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    <?php
                                    if(isset($selected_inactive_clients) && !empty($selected_inactive_clients)) { foreach ($selected_inactive_clients as $row) {
                                        ?>
                                        <tr>
                                            <td><?php echo $row['full_name']; ?></td>
                                            <td><?php echo $row['email']; ?></td>
                                            <td><?php echo $row['phone']; ?></td>
                                            <td><?php echo datetime_to_text2($row['created']); ?></td>
                                            <td><?php echo datetime_to_text2($row['last_trade_date']); ?></td>
                                            <td><?php echo $row['account_officer_full_name']; ?></td>
                                            <td nowrap="nowrap">
                                                <a title="Comment" class="btn btn-xs btn-success" href="sales_contact_view.php?x=<?php echo dec_enc('encrypt', $row['user_code']); ?>&r=<?php echo 'client_inactive'; ?>&c=<?php echo dec_enc('encrypt', 'INACTIVE TRADING CLIENT'); ?>&pg=<?php echo $currentpage; ?>"><i class="glyphicon glyphicon-comment icon-white"></i> </a>
                                                <a target="_blank" title="View" class="btn btn-xs btn-info" href="client_detail.php?id=<?php echo dec_enc('encrypt', $row['user_code']); ?>"><i class="glyphicon glyphicon-eye-open icon-white"></i> </a>
                                            </td>
                                        </tr>
                                    <?php } } else { echo "<tr><td colspan='6' class='text-danger'><em>No results to display</em></td></tr>"; } ?>
                                    </tbody>
                                </table>
                                
                                <?php if(isset($selected_inactive_clients) && !empty($selected_inactive_clients)) { ?>
                                <div class="tool-footer text-right">
                                    <p class="pull-left">Showing <?php echo $prespagelow . " to " . $prespagehigh . " of " . $numrows; ?> entries</p>
                                </div>
                                <?php } ?>
                            </div>
                        </div>
                        
                        <?php if(isset($selected_inactive_clients) && !empty($selected_inactive_clients)) { require 'layouts/pagination_links.php'; } ?>
                    </div>

                    <!-- Unique Page Content Ends Here
                    ================================================== -->
                    
                </div>
                
            </div>
        </div>
        <?php require_once 'layouts/footer.php'; ?>
        <script type="text/javascript" charset="utf8" src="https://cdn.datatables.net/1.10.19/js/jquery.dataTables.js"></script>
        <script src="//cdnjs.cloudflare.com/ajax/libs/moment.js/2.9.0/moment-with-locales.js"></script>
        <script src="//cdn.rawgit.com/Eonasdan/bootstrap-datetimepicker/e8bddc60e73c1ec2475f827be36e1957af72e2ea/src/js/bootstrap-datetimepicker.js"></script>
    </body>
</html>