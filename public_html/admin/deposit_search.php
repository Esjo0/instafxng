<?php
require_once("../init/initialize_admin.php");

if (!$session_admin->is_logged_in()) {
    redirect_to("login.php");
}

if(isset($_POST['search_text']) && strlen($_POST['search_text']) > 3 || isset($_GET['pg'])) {

    if(isset($_POST['search_text'])) {
        foreach($_POST as $key => $value) {
            $_POST[$key] = $db_handle->sanitizePost(trim($value));
        }

        $search_text = $_POST['search_text'];

        $query = "SELECT CONCAT(u.last_name, SPACE(1), u.first_name) AS full_name, ui.ifx_acct_no, ud.trans_id, ud.dollar_ordered, ud.naira_total_payable, ud.status, ud.created
            FROM user_deposit AS ud
            INNER JOIN user_ifxaccount AS ui ON ud.ifxaccount_id = ui.ifxaccount_id
            INNER JOIN user AS u ON ui.user_code = u.user_code
            WHERE (ud.trans_id LIKE '%$search_text%' OR ui.ifx_acct_no LIKE '%$search_text%' OR u.email LIKE '%$search_text%' OR u.first_name LIKE '%$search_text%' OR u.middle_name LIKE '%$search_text%' OR u.last_name LIKE '%$search_text%' OR u.phone LIKE '%$search_text%' OR ud.created LIKE '$search_text%') ORDER BY ud.created DESC ";
        $_SESSION['search_client_query'] = $query;

    } else {
        $query = $_SESSION['search_client_query'];
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
    $searched_deposit = $db_handle->fetchAssoc($result);
            
} else {
    // search form has not been submitted
}


?>
<!DOCTYPE html>
<html lang="en">
    <head>
        <base target="_self">
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>Instaforex Nigeria | Admin - Search Deposit Transactions</title>
        <meta name="title" content="Instaforex Nigeria | Admin" />
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
                            <h4><strong>SEARCH DEPOSIT TRANSACTIONS</strong></h4>
                        </div>
                    </div>
                    
                    <div class="section-tint super-shadow">
                        <div class="row">
                            <div class="col-sm-12">
                                <p><a href="deposit_search.php" class="btn btn-default" title="New Search"><i class="fa fa-arrow-circle-left"></i> New Search</a></p>
                                
                                <p>Enter a search term in the field below to search for any deposit transaction. You can search by transaction ID,
                                    name, email, phone number and date. Date should be in the format 2015-12-25.</p>
                                
                                <div class="search-section">
                                    <div class="row">    
                                        <div class="col-xs-12">
                                            <form data-toggle="validator" class="form-horizontal" role="form" method="post" action="deposit_search.php">
                                                <div class="input-group">
                                                    <input type="hidden" name="search_param" value="all" id="search_param">         
                                                    <input type="text" class="form-control" name="search_text" value="" placeholder="Search term..." required>
                                                    <span class="input-group-btn">
                                                        <button class="btn btn-default" type="submit"><span class="glyphicon glyphicon-search"></span></button>
                                                    </span>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                                
                                <div>
                                    <table class="table table-responsive table-striped table-bordered table-hover">
                                        <thead>
                                            <tr>
                                                <th>Transaction ID</th>
                                                <th>Client Name</th>
                                                <th>IFX Account</th>
                                                <th>Amount Ordered</th>
                                                <th>Total Payable</th>
                                                <th>Status</th>
                                                <th>Date Created</th>
                                                <th>Action</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php 
                                                if(isset($searched_deposit) && !empty($searched_deposit)) {
                                                    foreach ($searched_deposit as $row) {
                                            ?>
                                            <tr>
                                                <td><?php echo $row['trans_id']; ?></td>
                                                <td><?php echo $row['full_name']; ?></td>
                                                <td><?php echo $row['ifx_acct_no']; ?></td>
                                                <td>&dollar; <?php echo $row['dollar_ordered']; ?></td>
                                                <td>&#8358; <?php echo number_format($row['naira_total_payable'], 2, ".", ","); ?></td>
                                                <td><?php echo status_user_deposit($row['status']); ?></td>
                                                <td><?php echo datetime_to_text($row['created']); ?></td>
                                                <td>
                                                    <a target="_blank" title="View" class="btn btn-info" href="deposit_search_view.php?id=<?php echo encrypt($row['trans_id']); ?>"><i class="glyphicon glyphicon-eye-open icon-white"></i> </a>
                                                </td>
                                            </tr>
                                            <?php } } else { echo "<tr><td colspan='8' class='text-danger'><em>No results to display</em></td></tr>"; } ?>
                                        </tbody>
                                    </table>
                                </div>
                                
                                <?php if(isset($searched_deposit) && !empty($searched_deposit)) { ?>
                                <div class="tool-footer text-right">
                                    <p class="pull-left">Showing <?php echo $prespagelow . " to " . $prespagehigh . " of " . $numrows; ?> entries</p>
                                </div>
                                <?php } ?>
                                
                            </div>
                        </div>
                        
                        <?php if(isset($searched_deposit) && !empty($searched_deposit)) { require_once 'layouts/pagination_links.php'; } ?>

                    </div>

                    <!-- Unique Page Content Ends Here
                    ================================================== -->
                    
                </div>
                
            </div>
        </div>
        <?php require_once 'layouts/footer.php'; ?>
    </body>
</html>