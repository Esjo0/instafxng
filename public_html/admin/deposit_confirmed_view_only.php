<?php
require_once("../init/initialize_admin.php");
if (!$session_admin->is_logged_in()) {
    redirect_to("login.php");
}

$client_operation = new clientOperation();

$query = "SELECT ud.trans_id, ud.dollar_ordered, ud.created, ud.naira_total_payable, ud.real_dollar_equivalent, ud.real_naira_confirmed,
        ud.client_naira_notified, ud.client_pay_date, ud.client_reference, ud.client_pay_method,
        ud.client_notified_date, ud.status AS deposit_status, u.user_code,
        ui.ifx_acct_no, CONCAT(u.last_name, SPACE(1), u.first_name) AS full_name, u.phone,
        uc.passport, ui.ifxaccount_id
        FROM user_deposit AS ud
        INNER JOIN user_ifxaccount AS ui ON ud.ifxaccount_id = ui.ifxaccount_id
        INNER JOIN user AS u ON ui.user_code = u.user_code
        LEFT JOIN user_credential AS uc ON ui.user_code = uc.user_code
        WHERE ud.status = '5' OR ud.status = '6' ORDER BY ud.client_notified_date DESC ";
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
$confirmed_deposit_requests = $db_handle->fetchAssoc($result);

?>
<!DOCTYPE html>
<html lang="en">
    <head>
        <base target="_self">
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>Instaforex Nigeria | Admin - Confirmed Deposit</title>
        <meta name="title" content="Instaforex Nigeria | Admin - Confirmed Deposit" />
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
                            <h4><strong>CONFIRMED DEPOSIT</strong></h4>
                        </div>
                    </div>
                    
                    <div class="section-tint super-shadow">
                        <div class="row">
                            <div class="col-sm-12">
                                <?php require_once 'layouts/feedback_message.php'; ?>
                                
                                <p>Below is the list of all deposit requests that have been confirmed.</p>
                                
                                <div class="row">
                                    
                                    <?php if(isset($confirmed_deposit_requests) && !empty($confirmed_deposit_requests)) {
                                        foreach ($confirmed_deposit_requests as $row) { ?>
                                    <!-- Order -->
                                    <div class="col-sm-6">
                                        <div class="trans_item">
                                            <div class="trans_item_content">
                                                <div class="row">
                                                    <div class="col-xs-8 trans_item_bio">
                                                        <span id="bio_name"><?php echo $row['full_name']; ?></span>
                                                        <span><?php echo $row['phone']; ?></span>
                                                    </div>
                                                    <div class="col-xs-4 trans_item_bio">
                                                        <?php if($row['deposit_status'] == '6') { ?>
                                                            <img src="../images/in-progress.png" alt="" class="img-responsive" title="This transaction is in progress">
                                                        <?php } ?>
                                                    </div>
                                                </div>
                                                <div class="row">
                                                    <div class="col-sm-4 trans_item-thumb">
                                                        <p class="text-center"><a target="_blank" title="View Client Profile" class="btn btn-info" href="client_detail.php?id=<?php echo encrypt($row['user_code']); ?>"><i class="glyphicon glyphicon-eye-open icon-white"></i> </a></p>
                                                        <?php
                                                        if(!empty($row['passport'])) { $file_location = "../userfiles/" . $row['passport']; }

                                                        if(file_exists($file_location)) {
                                                            ?>
                                                            <img src="<?php echo $file_location; ?>" alt="" height="120px" width="120px" />
                                                        <?php } else { ?>
                                                            <img src="../images/placeholder.jpg" alt="" class="img-responsive">
                                                        <?php } unset($file_location); // so that it will not remember for someone without passport ?>

                                                        <?php if($client_operation->account_flagged($row['user_code'])) { ?>
                                                            <p><img class="center-block" src="../images/red-flag.png" alt="" title="The account number associated with this transaction is flagged."></p>
                                                        <?php } ?>
                                                    </div>
                                                    <div class="col-sm-8 ">
                                                        <span id="transaction_identity"><?php echo $row['trans_id']; ?></span>
                                                        <span><strong>Order:</strong> &dollar; <?php echo $row['dollar_ordered']; ?> - &#8358; <?php echo number_format($row['naira_total_payable'], 2, ".", ","); ?></span>
                                                        <span><strong>Date Created: </strong><?php echo datetime_to_text($row['created']); ?></span>
                                                        <span><strong>Account:</strong> <?php echo $row['ifx_acct_no']; ?></span>
                                                        <hr/>
                                                        <span><strong>Paid:</strong> &#8358; <?php echo number_format($row['client_naira_notified'], 2, ".", ","); ?></span>
                                                        <span><strong>Payment Date:</strong> <?php if(!is_null($row['client_pay_date'])) { echo date_to_text($row['client_pay_date']); } ?></span>
                                                        <span><strong>Date Notified: </strong><?php echo datetime_to_text($row['client_notified_date']); ?></span>
                                                        <span><strong>Ref:</strong> <?php echo $row['client_reference']; ?></span>
                                                        <span><strong>Method:</strong> <?php echo status_user_deposit_pay_method($row['client_pay_method']); ?></span>
                                                    </div>
                                                </div>
                                                <div class="row" style="margin-top: 5px;">
                                                    <div class="col-xs-8">
                                                        <span class="text-danger" style="text-align: left;">
                                                            <?php if(!is_null($row['client_notified_date'])) { 
                                                                echo datetime_to_text($row['client_notified_date']) . "<br/>";
                                                                echo time_since($row['client_notified_date']);
                                                            } ?>
                                                        </span>
                                                    </div>
                                                    <div class="col-xs-4"><span style="text-align: right"><a class="btn btn-info" href="deposit_process_view_only.php?x=confirmed&id=<?php echo encrypt($row['trans_id']) ?>"><i class="glyphicon glyphicon-edit icon-white"></i> More Info</a></span></div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                            <!-- Modal-- View Order List -->
                                            <div id="view_order<?php echo $row['req_order_code']; ?>" class="modal fade" role="dialog">
                                                <div class="modal-dialog">
                                                    <!-- Modal content-->
                                                    <form data-toggle="validator" class="form-horizontal" role="form" method="post" action="<?php echo $_SERVER['REQUEST_URI']; ?>">
                                                        <div class="modal-content">
                                                            <div class="modal-header">
                                                                <button type="button" class="close" data-dismiss="modal">&times;</button>
                                                                <h4 class="modal-title">DEPOSIT CONFIRMED - DETAILS</h4>
                                                            </div>
                                                            <div class="modal-body">

                                                            </div>
                                                            <div class="modal-footer">
                                                                <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
                                                            </div>
                                                        </div>
                                                    </form>
                                                </div>
                                            </div>
                                    <!-- close -->
                                    <?php } } else { ?>
                                    <div class="col-sm-12">
                                        <div class="trans_item">
                                            <div class="trans_item_content">
                                                <div class="row">
                                                    <div class="col-sm-12 text-danger"><p><em>There is no result to display</em></p></div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <?php } ?>
                                    
                                </div>
                            </div>
                        </div>
                        
                        <?php if(isset($confirmed_deposit_requests) && !empty($confirmed_deposit_requests)) { ?>
                        <div class="tool-footer text-right">
                            <p class="pull-left">Showing <?php echo $prespagelow . " to " . $prespagehigh . " of " . $numrows; ?> entries</p>
                        </div>
                        <?php } ?>
                        <?php if(isset($confirmed_deposit_requests) && !empty($confirmed_deposit_requests)) { require_once 'layouts/pagination_links.php'; } ?>
                    </div>

                    <!-- Unique Page Content Ends Here
                    ================================================== -->
                    
                </div>
                
            </div>
        </div>
        <?php require_once 'layouts/footer.php'; ?>
    </body>
</html>