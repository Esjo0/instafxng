<?php
require_once("../init/initialize_admin.php");
if (!$session_admin->is_logged_in()) {redirect_to("login.php");}

if(isset($_POST['search_text']) && strlen($_POST['search_text']) > 3) {
    $search_text = $_POST['search_text'];
    $query = "SELECT u.user_code, CONCAT(u.last_name, SPACE(1), u.first_name) AS full_name, u.email, u.phone, u.created, CONCAT(a.last_name, SPACE(1), a.first_name) AS account_officer_full_name
            FROM user AS u
            INNER JOIN account_officers AS ao ON u.attendant = ao.account_officers_id
            INNER JOIN admin AS a ON ao.admin_code = a.admin_code
            LEFT JOIN user_ifxaccount AS ui ON u.user_code = ui.user_code
            WHERE (u.password IS NULL OR u.password = '') AND (ui.ifx_acct_no LIKE '%$search_text%' OR u.email LIKE '%$search_text%' OR u.first_name LIKE '%$search_text%' OR u.middle_name LIKE '%$search_text%' OR u.last_name LIKE '%$search_text%' OR u.phone LIKE '%$search_text%' OR u.created LIKE '$search_text%')
            GROUP BY u.email ORDER BY u.created DESC, u.last_name ASC ";
} else {
    $query = "SELECT u.user_code, CONCAT(u.last_name, SPACE(1), u.first_name) AS full_name, u.email, u.phone, u.created, CONCAT(a.last_name, SPACE(1), a.first_name) AS account_officer_full_name
            FROM user AS u
            INNER JOIN account_officers AS ao ON u.attendant = ao.account_officers_id
            INNER JOIN admin AS a ON ao.admin_code = a.admin_code
            WHERE (u.password IS NULL OR u.password = '')
            GROUP BY u.email ORDER BY u.created DESC, u.last_name ASC ";
}
$numrows = $db_handle->numRows($query);

// For search, make rows per page equal total rows found, meaning, no pagination
// for search results
if (isset($_POST['search_text'])) {$rowsperpage = $numrows;}
else {    $rowsperpage = 20;}

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
                                        <div class="form-group multiple-form-group input-group">
                                            <input id="filter_val" readonly type="text" name="filter_val" class="form-control">
                                            <div class="input-group-btn input-group-select">
                                                <button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown">
                                                    <span class="concept">Filter</span>  <span class="caret"></span>
                                                </button>
                                                <ul class="dropdown-menu" role="menu">
                                                    <li><a onclick="document.getElementById('filter_val').value = 'Phone';" href="javascript:void(0);">Phone</a></li>
                                                </ul>
                                                <input type="hidden" class="input-group-select-val" name="contacts['type'][]" value="phone">
                                            </div>
                                            <input type="submit">

                                            <!--<span class="input-group-btn">
                                                <button type="button" class="btn btn-success btn-add">+</button>
                                            </span>-->
                                        </div>
                                    </div>
                                    <div class="col-sm-1"></div>
                                    <div class="col-sm-5">
                                        <form data-toggle="validator" class="form-horizontal" role="form" method="post" action="<?php echo $REQUEST_URI; ?>">
                                            <div class="input-group input-group-sm">
                                                <input type="hidden" name="search_param" value="all" id="search_param">
                                                <input type="text" class="form-control" name="search_text" placeholder="Search term..." required>
                                                <span class="input-group-btn">
                                                    <button class="btn btn-default" type="submit"><span class="glyphicon glyphicon-search"></span></button>
                                                </span>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                                <br/>


                                <div class="row">
                                    <div class="col-sm-9">
                                        Below is a table of unverified clients. <br/>
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
                            </br>
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