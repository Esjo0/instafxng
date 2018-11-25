<?php
require_once("../init/initialize_admin.php");
if (!$session_admin->is_logged_in()) {
    redirect_to("login.php");
}

if (isset($_POST['view'])) {
    unset($_SESSION['query']);
}

if(isset($_POST['search_text']) && strlen($_POST['search_text']) > 3) {
    $search_text = $_POST['search_text'];
    $query = "SELECT MIN(ud.order_complete_time) AS order_complete_time, u.user_code, CONCAT(u.last_name, SPACE(1), u.first_name) AS full_name, u.email, u.phone, u.created
        FROM user_deposit AS ud
        INNER JOIN user_ifxaccount AS ui ON ud.ifxaccount_id = ui.ifxaccount_id
        INNER JOIN user AS u ON ui.user_code = u.user_code
        INNER JOIN free_training_campaign AS ftc ON ftc.email = u.email
        WHERE ud.status = '8' AND (ui.ifx_acct_no LIKE '%$search_text%' OR u.email LIKE '%$search_text%' OR u.first_name LIKE '%$search_text%' OR u.middle_name LIKE '%$search_text%' OR u.last_name LIKE '%$search_text%' OR u.phone LIKE '%$search_text%' OR u.created LIKE '$search_text%') GROUP BY u.email ORDER BY u.created DESC ";
    $_SESSION['query'] = $query;
}
if(isset($_POST['filter'])){
    foreach ($_POST as $key => $value) {
        $_POST[$key] = $db_handle->sanitizePost(trim($value));
    }
    $from_date = $_POST['from_date'];
    $to_date = $_POST['to_date'];

    $query = "SELECT MIN(ud.order_complete_time) AS order_complete_time, u.user_code, CONCAT(u.last_name, SPACE(1), u.first_name) AS full_name, u.email, u.phone, u.created
        FROM user_deposit AS ud
        INNER JOIN user_ifxaccount AS ui ON ud.ifxaccount_id = ui.ifxaccount_id
        INNER JOIN user AS u ON ui.user_code = u.user_code
        INNER JOIN free_training_campaign AS ftc ON ftc.email = u.email
        WHERE ud.status = '8'
        GROUP BY u.user_code
        HAVING order_complete_time BETWEEN '$from_date' AND '$to_date'";
    $_SESSION['query'] = $query;
}

if(isset($_POST['funding_date'])){
    $query = "SELECT MIN(ud.order_complete_time) AS order_complete_time, u.user_code, CONCAT(u.last_name, SPACE(1), u.first_name) AS full_name, u.email, u.phone, u.created
        FROM user_deposit AS ud
        INNER JOIN user_ifxaccount AS ui ON ud.ifxaccount_id = ui.ifxaccount_id
        INNER JOIN user AS u ON ui.user_code = u.user_code
        INNER JOIN free_training_campaign AS ftc ON ftc.email = u.email
        WHERE ud.status = '8'
        GROUP BY u.email ORDER BY order_complete_time DESC ";
    $_SESSION['query'] = $query;
}
if(empty($_SESSION['query'])) {
    $query = "SELECT MIN(ud.order_complete_time) AS order_complete_time, u.user_code, CONCAT(u.last_name, SPACE(1), u.first_name) AS full_name, u.email, u.phone, u.created
        FROM user_deposit AS ud
        INNER JOIN user_ifxaccount AS ui ON ud.ifxaccount_id = ui.ifxaccount_id
        INNER JOIN user AS u ON ui.user_code = u.user_code
        INNER JOIN free_training_campaign AS ftc ON ftc.email = u.email
        WHERE ud.status = '8'
        GROUP BY u.email ORDER BY u.created DESC ";
    $_SESSION['query'] = $query;
}
$query = $_SESSION['query'];

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
$client_training_funded = $db_handle->fetchAssoc($result);
?>
<!DOCTYPE html>
<html lang="en">
    <head>
        <base target="_self">
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>Instaforex Nigeria | Admin - Training Clients Funded</title>
        <meta name="title" content="Instaforex Nigeria | Admin - Training Clients Funded" />
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
                            <h4><strong>TRAINING CLIENTS THAT FUNDED</strong></h4>
                        </div>
                    </div>
                    
                    <div class="section-tint super-shadow">
                        <div class="row">
                            <div class="col-sm-12">
                                <?php require_once 'layouts/feedback_message.php'; ?>

                                <p>Below is a table of training clients that have funded their IFX accounts in the past.</p>

                                <div class="pull-right"><button type="button" data-target="#confirm-add-admin" data-toggle="modal" class="btn btn-sm btn-default"><i class="glyphicon glyphicon-search"></i> Apply Filter</button></div>

                                <!--Modal - confirmation boxes-->
                                <div id="confirm-add-admin" tabindex="-1" role="dialog" aria-hidden="true" class="modal fade">
                                    <form data-toggle="validator" class="form-horizontal" role="form" method="post" action="<?php echo $_SERVER['REQUEST_URI']; ?>">
                                        <div class="modal-dialog modal-md">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <button type="button" data-dismiss="modal" aria-hidden="true"  class="close">&times;</button>
                                                    <h4 class="modal-title">Apply Search Filter</h4></div>
                                                <div class="modal-body">
                                                    <p>Select Students who have funded their Instaforex Account for the first time within a date range using the form below.</p>

                                                    <div class="input-group date">
                                                        <input placeholder="Select start date" name="from_date" type="text" class="form-control" id="datetimepicker" required>
                                                        <span class="input-group-addon"><span class="glyphicon glyphicon-calendar"></span></span>
                                                    </div>

                                                    <br/>

                                                    <div class="input-group date">
                                                        <input placeholder="Select end date" name="to_date" type="text" class="form-control" id="datetimepicker2" required>
                                                        <span class="input-group-addon"><span class="glyphicon glyphicon-calendar"></span></span>
                                                    </div>
                                                    <script type="text/javascript">
                                                        $(function () {$('#datetimepicker, #datetimepicker2').datetimepicker({format: 'YYYY-MM-DD'});});
                                                    </script>
                                                </div>
                                                <div class="modal-footer">
                                                    <input name="filter" type="submit" class="btn btn-sm btn-success" value="Proceed">
                                                    <button type="button" name="close" onClick="window.close();" data-dismiss="modal" class="btn btn-sm btn-danger">Close!</button>
                                                </div>
                                            </div>
                                        </div>
                                    </form>
                                </div>
                                <form class="pull-right" method="post" action="">
                                    <button name="funding_date" type="submit" class="btn btn-primary btn-sm">
                                        <i class="glyphicon glyphicon-circle"></i>Arrange By Funding Date
                                    </button>
                                </form>
                                <form class="pull-right" method="post" action="">
                                    <button name="view" type="submit" class="btn btn-info btn-sm"><i
                                            class="glyphicon glyphicon-eye-circle"></i>View All
                                    </button>
                                </form>

                                <?php if(isset($numrows)) { ?>
                                    <p><strong>Result Found: </strong><?php echo number_format($numrows); ?></p>
                                <?php } ?>

                                <?php if(isset($client_training_funded) && !empty($client_training_funded)) { require 'layouts/pagination_links.php'; } ?>

                                <table class="table table-responsive table-striped table-bordered table-hover">
                                    <thead>
                                    <tr>
                                        <th>Full Name</th>
                                        <th>Email</th>
                                        <th>Phone</th>
                                        <th>Opening Date</th>
                                        <th>1st. Funding Date</th>
                                        <th>Action</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    <?php
                                    if(isset($client_training_funded) && !empty($client_training_funded)) { foreach ($client_training_funded as $row) {
                                        ?>
                                        <tr>
                                            <td><?php echo $row['full_name']; ?></td>
                                            <td><?php echo $row['email']; ?></td>
                                            <td><?php echo $row['phone']; ?></td>
                                            <td><?php echo datetime_to_text2($row['created']); ?></td>
                                            <td><?php echo datetime_to_text2($row['order_complete_time']); ?></td>
                                            <td nowrap>
                                                <a title="Comment" class="btn btn-success" href="sales_contact_view.php?x=<?php echo encrypt_ssl($row['user_code']); ?>&r=<?php echo 'edu_client_training_funded'; ?>&c=<?php echo encrypt_ssl('TRAINING CLIENT FUNDED'); ?>&pg=<?php echo $currentpage; ?>"><i class="glyphicon glyphicon-comment icon-white"></i> </a>
                                                <a target="_blank" title="View" class="btn btn-info" href="client_detail.php?id=<?php echo encrypt_ssl($row['user_code']); ?>"><i class="glyphicon glyphicon-eye-open icon-white"></i> </a>
                                            </td>
                                        </tr>
                                    <?php } } else { echo "<tr><td colspan='5' class='text-danger'><em>No results to display</em></td></tr>"; } ?>
                                    </tbody>
                                </table>
                                
                                <?php if(isset($client_training_funded) && !empty($client_training_funded)) { ?>
                                <div class="tool-footer text-right">
                                    <p class="pull-left">Showing <?php echo $prespagelow . " to " . $prespagehigh . " of " . $numrows; ?> entries</p>
                                </div>
                                <?php } ?>
                            </div>
                        </div>
                        
                        <?php if(isset($client_training_funded) && !empty($client_training_funded)) { require 'layouts/pagination_links.php'; } ?>
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