<?php
require_once("../init/initialize_admin.php");
if (!$session_admin->is_logged_in()) {
    redirect_to("login.php");
}

$query = "SELECT cs.admin_code, cs.campaign_sms_id, cs.content, cs.status, cs.send_date, cs.created,
    cs.send_status, cc.title
    FROM campaign_sms AS cs
    INNER JOIN campaign_category AS cc ON cs.campaign_category_id = cc.campaign_category_id
    ORDER BY cs.created DESC ";
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
$all_campaign_sms = $db_handle->fetchAssoc($result);

?>
<!DOCTYPE html>
<html lang="en">
    <head>
        <base target="_self">
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>Instaforex Nigeria | Admin - SMS Campaign</title>
        <meta name="title" content="Instaforex Nigeria | Admin - SMS Campaign" />
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
                            <h4><strong>SMS CAMPAIGN</strong></h4>
                        </div>
                    </div>
                    
                    <div class="section-tint super-shadow">
                        <div class="row">
                            <div class="col-sm-12">
                                <?php require_once 'layouts/feedback_message.php'; ?>
                                <p class="text-right"><a href="campaign_sms.php" class="btn btn-default" title="Create New Campaign SMS">New Campaign SMS <i class="fa fa-arrow-circle-right"></i></a></p>
                                <p>All SMS Campaigns.</p>

                                <table class="table table-responsive table-striped table-bordered table-hover">
                                    <thead>
                                    <tr>
                                        <th>Message</th>
                                        <th>Category</th>
                                        <th>Status</th>
                                        <th>Sent Date</th>
                                        <th>Created</th>
                                        <th>Actions</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    <?php if(isset($all_campaign_sms) && !empty($all_campaign_sms)) { foreach ($all_campaign_sms as $row) { ?>
                                        <tr>
                                            <td><?php echo $row['content']; ?></td>
                                            <td><?php echo $row['title']; ?></td>
                                            <td class="nowrap"><?php echo status_campaign_sms($row['status']); ?></td>
                                            <td><?php if(!is_null($row['send_date'])) { echo datetime_to_text($row['send_date']); } else { echo "Not Sent"; } ?></td>
                                            <td><?php echo datetime_to_text($row['created']); ?></td>
                                            <td class="nowrap">
                                                <?php if($row['send_status'] == '2' && ($row['status'] == '1' || $row['status'] == '2')) { // allow edit if campaign has not been sent ?>
                                                    <a title="Edit" class="btn btn-default" href="campaign_sms.php?x=edit&id=<?php echo dec_enc('encrypt', $row['campaign_sms_id']); ?>"><i class="glyphicon glyphicon-edit icon-white"></i> </a>
                                                <?php } ?>

                                                <a title="Send Test" class="btn btn-info" href="campaign_sms_broadcast.php?x=test&id=<?php echo dec_enc('encrypt', $row['campaign_sms_id']); ?>"><i class="fa fa-paper-plane fa-fw"></i> </a>

                                                <?php if($row['status'] == '2' && $row['send_status'] == '2') { ?>
                                                    <a title="Broadcast" class="btn btn-success" href="campaign_sms_broadcast.php?x=send&id=<?php echo dec_enc('encrypt', $row['campaign_sms_id']); ?>"><i class="fa fa-bullhorn fa-fw"></i> </a>
                                                <?php } ?>
                                            </td>
                                        </tr>
                                    <?php } } else { echo "<tr><td colspan='6' class='text-danger'><em>No results to display</em></td></tr>"; } ?>
                                    </tbody>
                                </table>

                                <?php if(isset($all_campaign_sms) && !empty($all_campaign_sms)) { ?>
                                    <div class="tool-footer text-right">
                                        <p class="pull-left">Showing <?php echo $prespagelow . " to " . $prespagehigh . " of " . $numrows; ?> entries</p>
                                    </div>
                                <?php } ?>

                            </div>
                        </div>
                        <?php if(isset($all_campaign_sms) && !empty($all_campaign_sms)) { require_once 'layouts/pagination_links.php'; } ?>
                    </div>

                    <!-- Unique Page Content Ends Here
                    ================================================== -->
                    
                </div>
                
            </div>
        </div>
        <?php require_once 'layouts/footer.php'; ?>
    </body>
</html>