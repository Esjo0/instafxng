<?php
require_once("../init/initialize_admin.php");
if (!$session_admin->is_logged_in()) {
    redirect_to("login.php");
}

$client_operation = new clientOperation();

$query = "SELECT uw.trans_id, uw.dollar_withdraw, uw.created, uw.naira_total_withdrawable,
        uw.client_phone_password, uw.status AS withdrawal_status,
        CONCAT(u.last_name, SPACE(1), u.first_name) AS full_name, u.phone,
        uc.passport, ui.ifxaccount_id, ui.ifx_acct_no
        FROM user_withdrawal AS uw
        INNER JOIN user_ifxaccount AS ui ON uw.ifxaccount_id = ui.ifxaccount_id
        INNER JOIN user AS u ON ui.user_code = u.user_code
        LEFT JOIN user_credential AS uc ON ui.user_code = uc.user_code
        WHERE uw.status = '7' OR uw.status = '8' ORDER BY uw.created DESC ";
$numrows = $db_handle->numRows($query);

$rowsperpage = 10;

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
$confirmed_withdrawal_requests = $db_handle->fetchAssoc($result);

?>
<!DOCTYPE html>
<html lang="en">
    <head>
        <base target="_self">
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>Instaforex Nigeria | Admin - Confirmed Withdrawal</title>
        <meta name="title" content="Instaforex Nigeria | Admin - Confirmed Withdrawal" />
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
                            <h4><strong>WITHDRAWAL - IFX FUND DEBITED</strong></h4>
                        </div>
                    </div>
                    
                    <div class="section-tint super-shadow">
                        <div class="row">
                            <div class="col-sm-12">
                                <?php require_once 'layouts/feedback_message.php'; ?>
                                
                                <p>Below is the list of all withdrawal requests that their IFX accounts have been debited.</p>
                                
                                <div class="row">
                                    
                                    <?php if(isset($confirmed_withdrawal_requests) && !empty($confirmed_withdrawal_requests)) {
                                        foreach ($confirmed_withdrawal_requests as $row) { ?>
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
                                                        <?php if($row['withdrawal_status'] == '8') { ?>
                                                            <img src="../images/in-progress.png" alt="" class="img-responsive" title="This transaction is in progress">
                                                        <?php } ?>
                                                        <?php if($client_operation->account_flagged($row['ifxaccount_id'])) { ?>
                                                            <img src="../images/red-flag.png" alt="" title="The account number associated with this transaction is flagged.">
                                                        <?php } ?>
                                                    </div>
                                                </div>
                                                <div class="row">
                                                    <div class="col-sm-4 trans_item-thumb">
                                                        <?php
                                                        if(!empty($row['passport'])) { $file_location = "../userfiles/" . $row['passport']; }

                                                        if(file_exists($file_location)) {
                                                            ?>
                                                            <img src="<?php echo $file_location; ?>" alt="" height="120px" width="120px" />
                                                        <?php } else { ?>
                                                            <img src="../images/placeholder.jpg" alt="" class="img-responsive">
                                                        <?php } unset($file_location); // so that it will not remember for someone without passport ?>
                                                    </div>
                                                    <div class="col-sm-8 ">
                                                        <span id="transaction_identity"><?php echo $row['trans_id']; ?></span>
                                                        <span><strong>Withdraw:</strong> &dollar; <?php echo $row['dollar_withdraw']; ?> - &#8358; <?php echo number_format($row['naira_total_withdrawable'], 2, ".", ","); ?></span>
                                                        <span><strong>Date: </strong><?php echo datetime_to_text($row['created']); ?></span>
                                                        <span><strong>Account:</strong> <?php echo $row['ifx_acct_no']; ?></span>
                                                        <span><strong>Phone Password:</strong> 
                                                            <?php 
                                                                $phone_password_encrypted = $row['client_phone_password'];
                                                                $client_phone_password = decrypt(str_replace(" ", "+", $phone_password_encrypted));
                                                                $client_phone_password = preg_replace("/[^A-Za-z0-9 ]/", '', $client_phone_password);
                                                                echo $client_phone_password;
                                                            ?>
                                                        </span>
                                                        <hr/>
                                                    </div>
                                                </div>
                                                <div class="row" style="margin-top: 5px;">
                                                    <div class="col-xs-8">
                                                        <span class="text-danger" style="text-align: left;">
                                                            <?php if(!is_null($row['created'])) { 
                                                                echo datetime_to_text($row['created']) . "<br/>";
                                                                echo time_since($row['created']);
                                                            } ?>
                                                        </span>
                                                    </div>
                                                    <div class="col-xs-4"><span style="text-align: right"><a class="btn btn-info" href="withdraw_process.php?x=debited&id=<?php echo encrypt($row['trans_id']); ?>"><i class="glyphicon glyphicon-edit icon-white"></i> Process</a></span></div>
                                                </div>
                                            </div>
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
                        
                        <?php if(isset($confirmed_withdrawal_requests) && !empty($confirmed_withdrawal_requests)) { ?>
                        <div class="tool-footer text-right">
                            <p class="pull-left">Showing <?php echo $prespagelow . " to " . $prespagehigh . " of " . $numrows; ?> entries</p>
                        </div>
                        <?php } ?>
                        <?php if(isset($confirmed_withdrawal_requests) && !empty($confirmed_withdrawal_requests)) { require_once 'layouts/pagination_links.php'; } ?>
                    </div>

                    <!-- Unique Page Content Ends Here
                    ================================================== -->
                    
                </div>
                
            </div>
        </div>
        <?php require_once 'layouts/footer.php'; ?>
    </body>
</html>