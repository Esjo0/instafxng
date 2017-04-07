<?php
require_once("../init/initialize_admin.php");
if (!$session_admin->is_logged_in()) {
    redirect_to("login.php");
}

$query = "SELECT ces.campaign_email_solo_id, ces.subject, ces.status, ces.day_to_send, ces.created, cesg.group_name
        FROM campaign_email_solo AS ces
        INNER JOIN campaign_email_solo_group AS cesg ON ces.solo_group = cesg.campaign_email_solo_group_id
        ORDER BY ces.created DESC ";
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
$all_solo_campaign_email = $db_handle->fetchAssoc($result);
?>
<!DOCTYPE html>
<html lang="en">
    <head>
        <base target="_self">
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>Instaforex Nigeria | Admin - All Solo Campaigns</title>
        <meta name="title" content="Instaforex Nigeria | Admin - All Solo Campaigns" />
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
                            <h4><strong>ALL SOLO EMAIL CAMPAIGNS</strong></h4>
                        </div>
                    </div>
                    
                    <div class="section-tint super-shadow">
                        <div class="row">
                            <div class="col-sm-12">
                                <?php require_once 'layouts/feedback_message.php'; ?>
                                <p class="text-right"><a href="campaign_solo_view.php" class="btn btn-default" title="Create New Campaign Solo Email">New Campaign Solo Email <i class="fa fa-arrow-circle-right"></i></a></p>
                                <p>All solo email campaigns</p>
                                
                                <table class="table table-responsive table-striped table-bordered table-hover">
                                    <thead>
                                        <tr>
                                            <th>Subject</th>
                                            <th>Group</th>
                                            <th>Status</th>
                                            <th>Day to Send</th>
                                            <th>Created</th>
                                            <th>Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php if(isset($all_solo_campaign_email) && !empty($all_solo_campaign_email)) { foreach ($all_solo_campaign_email as $row) { ?>
                                        <tr>
                                            <td><?php echo $row['subject']; ?></td>
                                            <td><?php echo $row['group_name']; ?></td>
                                            <td class="nowrap"><?php echo status_solo_campaign_email($row['status']); ?></td>
                                            <td><?php echo $row['day_to_send']; ?></td>
                                            <td><?php echo datetime_to_text($row['created']); ?></td>
                                            <td class="nowrap">
                                                <a title="Edit" class="btn btn-default" href="campaign_solo_view.php?x=edit&id=<?php echo encrypt($row['campaign_email_solo_id']); ?>"><i class="glyphicon glyphicon-edit icon-white"></i> </a>
                                            </td>
                                        </tr>
                                        <?php } } else { echo "<tr><td colspan='5' class='text-danger'><em>No results to display</em></td></tr>"; } ?>
                                    </tbody>
                                </table>
                                
                                <?php if(isset($all_solo_campaign_email) && !empty($all_solo_campaign_email)) { ?>
                                <div class="tool-footer text-right">
                                    <p class="pull-left">Showing <?php echo $prespagelow . " to " . $prespagehigh . " of " . $numrows; ?> entries</p>
                                </div>
                                <?php } ?>
                            </div>
                        </div>
                        <?php if(isset($all_solo_campaign_email) && !empty($all_solo_campaign_email)) { require_once 'layouts/pagination_links.php'; } ?>
                    </div>

                    <!-- Unique Page Content Ends Here
                    ================================================== -->
                    
                </div>
                
            </div>
        </div>
        <?php require_once 'layouts/footer.php'; ?>
    </body>
</html>