<?phprequire_once '../../init/initialize_partner.php';if (!$session_partner->is_logged_in()) {//    redirect_to("../login.php");}$query = "SELECT partner_payment_id, transaction_id, partner_code, account_id, amount, trans_type, transfer_reference, created, status, comment FROM partner_withdrawal ORDER BY created DESC ";$numrows = $db_handle->numRows($query);$rowsperpage = 20;$totalpages = ceil($numrows / $rowsperpage);// get the current page or set a defaultif (isset($_GET['pg']) && is_numeric($_GET['pg'])) {   $currentpage = (int) $_GET['pg'];} else {   $currentpage = 1;}if ($currentpage > $totalpages) { $currentpage = $totalpages; }if ($currentpage < 1) { $currentpage = 1; }$prespagelow = $currentpage * $rowsperpage - $rowsperpage + 1;$prespagehigh = $currentpage * $rowsperpage;if($prespagehigh > $numrows) { $prespagehigh = $numrows; }$offset = ($currentpage - 1) * $rowsperpage;$query .= 'LIMIT ' . $offset . ',' . $rowsperpage;$result = $db_handle->runQuery($query);$all_withdrawal_requests = $db_handle->fetchAssoc($result);?><!DOCTYPE html><html lang="en">    <head>        <base target="_self">        <meta charset="UTF-8">        <meta name="viewport" content="width=device-width, initial-scale=1">        <title>Instafxng Partner | Withdrawals</title>        <meta name="title" content="Instafxng Partner | Withdrawals" />        <meta name="keywords" content="" />        <meta name="description" content="" />        <?php require_once 'layouts/head_meta.php'; ?>    </head>    <body>        <?php require_once 'layouts/header.php'; ?>        <!-- Main Body: The is the main content area of the web site, contains a side bar  -->        <div id="main-body" class="container-fluid">            <div class="row no-gutter">                <!-- Main Body - Side Bar  -->                <div id="main-body-side-bar" class="col-md-4 col-lg-3 left-nav">                <?php require_once 'layouts/sidebar.php'; ?>                </div>                                <!-- Main Body - Content Area: This is the main content area, unique for each page  -->                <div id="main-body-content-area" class="col-md-8 col-lg-9">                                        <!-- Unique Page Content Starts Here                    ================================================== -->                    <div class="row">                        <div class="col-sm-12 text-danger">                            <h4><strong>WITHDRAWALS</strong></h4>                        </div>                    </div>                                        <div class="section-tint super-shadow">                        <div class="row">                            <div class="col-sm-12">                                <?php require_once 'layouts/feedback_message.php'; ?>                                <p>Below is the list of all withdrawal requests.</p>                                <table class="table table-responsive table-striped table-bordered table-hover">                                    <thead>                                        <tr>                                            <th>Transaction ID</th>                                            <th>IFX Account</th>                                            <th> Amount</th>                                            <th>Trans Type</th>                                            <th>Status</th>                                            <th>Date Created</th>                                        </tr>                                    </thead>                                    <tbody>                                        <?php                                             if(isset($all_withdrawal_requests) && !empty($all_withdrawal_requests)) { foreach ($all_withdrawal_requests as $row) {                                                $client = $client_object->get_user_name_ifxaccount($row['ifxaccount_id']);                                        ?>                                        <tr>                                            <td><?php echo $row['trans_id']; ?></td>                                            <td><?php echo $client['account_no']; ?></td>                                            <td>&#8358; <?php echo number_format($row['amount'], 2, ".", ","); ?></td>                                            <td><?php echo partner_withdrawal_trans_type($row['trans_type']); ?></td>                                            <td><?php echo partner_withdrawal_status($row['status']); ?></td>                                            <td><?php echo datetime_to_text($row['created']); ?></td>                                        </tr>                                        <?php } } else { echo "<tr><td colspan='7' class='text-danger'><em>No results to display</em></td></tr>"; } ?>                                    </tbody>                                </table>                                                                <?php if(isset($all_withdrawal_requests) && !empty($all_withdrawal_requests)) { ?>                                <div class="tool-footer text-right">                                    <p class="pull-left">Showing <?php echo $prespagelow . " to " . $prespagehigh . " of " . $numrows; ?> entries</p>                                </div>                                <?php } ?>                                                                                                                                <br /><br /><br /><br />                            </div>                        </div>                        <?php if(isset($all_withdrawal_requests) && !empty($all_withdrawal_requests)) { require_once 'layouts/pagination_links.php'; } ?>                                           </div>                    <!-- Unique Page Content Ends Here                    ================================================== -->                                    </div>                            </div>        </div>        <?php require_once 'layouts/footer.php'; ?>    </body></html>