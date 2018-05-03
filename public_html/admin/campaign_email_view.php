<?php
require_once("../init/initialize_admin.php");
if (!$session_admin->is_logged_in()) {
    redirect_to("login.php");
}

if (isset($_POST['delete_campaign'])) {

    foreach($_POST as $key => $value) {
        $_POST[$key] = $db_handle->sanitizePost(trim($value));
    }

    extract($_POST);
    $campaign_id = decrypt(str_replace(" ", "+", $campaign_id));
    $campaign_id = preg_replace("/[^A-Za-z0-9 ]/", '', $campaign_id);
    $system_object->delete_campaign_by_id($campaign_id);
}

$query = "SELECT ce.sender AS campaign_sender, ce.send_date AS campaign_send_date, ce.created AS campaign_created, ce.subject AS campaign_subject, ce.status AS campaign_status,
      ce.send_status AS campaign_send_status, ce.campaign_email_id AS campaign_id, ce.send_date AS campaign_send_date, cc.title AS campaign_category
      FROM campaign_email AS ce
      INNER JOIN campaign_category AS cc ON ce.campaign_category_id = cc.campaign_category_id
      ORDER BY ce.created DESC ";
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
$all_campaign_email = $db_handle->fetchAssoc($result);

?>
<!DOCTYPE html>
<html lang="en">
    <head>
        <base target="_self">
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>Instaforex Nigeria | Admin - Email Campaign</title>
        <meta name="title" content="Instaforex Nigeria | Admin - Email Campaign" />
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
                            <h4><strong>EMAIL CAMPAIGN</strong></h4>
                        </div>
                    </div>
                    
                    <div class="section-tint super-shadow">
                        <div class="row">
                            <div class="col-sm-12">
                                <?php require_once 'layouts/feedback_message.php'; ?>
                                <p class="text-right"><a href="campaign_email.php" class="btn btn-default" title="Create New Campaign Email">New Campaign Email <i class="fa fa-arrow-circle-right"></i></a></p>
                                <p>All email campaigns</p>
                                
                                <table class="table table-responsive table-striped table-bordered table-hover">
                                    <thead>
                                        <tr>
                                            <th></th>
                                            <th>Sender</th>
                                            <th>Subject</th>
                                            <th>Category</th>
                                            <th>Status</th>
                                            <th>Sent Date</th>
                                            <th>Created</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php if(isset($all_campaign_email) && !empty($all_campaign_email)) { foreach ($all_campaign_email as $row) { ?>
                                        <tr>
                                            <td>
                                                <div class="dropdown">
                                                    <a class="btn btn-primary dropdown-toggle" type="button" id="dropdownMenu1" data-toggle="dropdown">Action <span class="caret"></span></a>
                                                    <ul class="dropdown-menu" role="menu" aria-labelledby="dropdownMenu1">
                                                        <?php if($row['campaign_send_status'] == '2' && ($row['campaign_status'] == '1' || $row['campaign_status'] == '2')) { // allow edit if campaign has not been sent ?>
                                                            <li role="presentation"><a role="menuitem" tabindex="-1" title="Edit Campaign" href="campaign_email.php?x=edit&id=<?php echo encrypt($row['campaign_id']); ?>"><i class="fa fa-edit fa-fw"></i> Edit</a></li>
                                                            <li role="presentation"><a role="menuitem" tabindex="-1" data-toggle="modal" data-target="#delete-campaign-confirm<?php echo $row['campaign_id']; ?>" title="Delete Campaign" href="#"><i class="fa fa-trash fa-fw"></i> Delete</a></li>
                                                        <?php } ?>

                                                        <li role="presentation"><a role="menuitem" tabindex="-1" title="Send Test" href="campaign_email_broadcast.php?x=test&id=<?php echo encrypt($row['campaign_id']); ?>"><i class="fa fa-paper-plane fa-fw"></i> Send Test</a></li>
                                                        <li role="presentation"><a role="menuitem" tabindex="-1" title="Duplicate Campaign" href="campaign_email.php?x=duplicate&id=<?php echo encrypt($row['campaign_id']); ?>"><i class="fa fa-save fa-fw"></i> Duplicate</a></li>
                                                        <li role="presentation"><a role="menuitem" tabindex="-1" title="View Recipients" href="javascript:void(0);" data-toggle="modal" data-target="#recipients_<?php echo $row['campaign_id']; ?>"><i class="fa fa-check fa-fw"></i> View Recipients</a></li>

                                                        <?php if($row['campaign_status'] == '2' && $row['campaign_send_status'] == '2') { ?>
                                                            <li role="presentation"><a role="menuitem" tabindex="-1" title="Broadcast" href="campaign_email_broadcast.php?x=send&id=<?php echo encrypt($row['campaign_id']); ?>"><i class="fa fa-bullhorn fa-fw"></i> Broadcast</a></li>
                                                        <?php } ?>
                                                    </ul>
                                                </div>

                                                <div id="recipients_<?php echo $row['campaign_id']; ?>" tabindex="-1" role="dialog" aria-hidden="true" class="modal fade">
                                                    <div class="modal-dialog modal-lg">
                                                        <div class="modal-content">
                                                            <div class="modal-header">
                                                                <button type="button" data-dismiss="modal" aria-hidden="true" class="close">&times;</button>
                                                                <h4 class="modal-title">Campaign Email Recipients</h4></div>
                                                            <div class="modal-body">
                                                                <?php
                                                                $recipient_list = file_get_contents("../../cronjobs/campaign_mails".DIRECTORY_SEPARATOR.$row['campaign_id'].".txt");
                                                                $recipient_list = explode("\n", $recipient_list);
                                                                ?>
                                                                <table class="table table-responsive table-striped table-bordered table-hover">
                                                                    <thead>
                                                                        <tr>
                                                                            <th>Name</th>
                                                                            <th>Email</th>
                                                                            <th>Time Sent</th>
                                                                        </tr>
                                                                    </thead>
                                                                    <tbody>
                                                                    <?php foreach ($recipient_list as $key) {
                                                                        $key = explode('*', $key)
                                                                        ?>
                                                                        <tr>
                                                                            <td><?php echo $key[0]; ?></td>
                                                                            <td><?php echo $key[1]; ?></td>
                                                                            <td><?php echo $key[2]; ?></td>
                                                                        </tr>
                                                                    <?php } ?>
                                                                    </tbody>
                                                                </table>
                                                            </div>
                                                            <div class="modal-footer">
                                                                <button type="submit" name="decline" data-dismiss="modal" class="btn btn-default">Cancel</button>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div id="delete-campaign-confirm<?php echo $row['campaign_id']; ?>" tabindex="-1" role="dialog" aria-hidden="true" class="modal fade">
                                                    <div class="modal-dialog">
                                                        <div class="modal-content">
                                                            <div class="modal-header">
                                                                <button type="button" data-dismiss="modal" aria-hidden="true"
                                                                        class="close">&times;</button>
                                                                <h4 class="modal-title">Delete Campaign Confirmation</h4></div>
                                                            <div class="modal-body">
                                                                <p>Do you want to delete this campaign? This action cannot be reversed.</p>
                                                            </div>
                                                            <div class="modal-footer">
                                                                <form class="form-horizontal" role="form" method="post" action="">
                                                                    <input type="hidden" name="campaign_id" value="<?php echo encrypt($row['campaign_id']); ?>" />
                                                                    <input name="delete_campaign" type="submit" class="btn btn-danger" value="Delete Campaign">
                                                                    <button type="submit" name="decline" data-dismiss="modal" class="btn btn-default">Cancel</button>
                                                                </form>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </td>
                                            <td><?php echo $row['campaign_sender']; ?></td>
                                            <td><?php echo $row['campaign_subject']; ?></td>
                                            <td><?php echo $row['campaign_category']; ?></td>
                                            <td class="nowrap"><?php echo status_campaign_email($row['campaign_status']); ?></td>
                                            <td><?php if(!is_null($row['campaign_send_date'])) { echo datetime_to_text($row['campaign_send_date']); } else { echo "Not Sent"; } ?></td>
                                            <td><?php echo datetime_to_text($row['campaign_created']); ?></td>
                                        </tr>
                                        <?php } } else { echo "<tr><td colspan='5' class='text-danger'><em>No results to display</em></td></tr>"; } ?>
                                    </tbody>
                                </table>
                                
                                <?php if(isset($all_campaign_email) && !empty($all_campaign_email)) { ?>
                                <div class="tool-footer text-right">
                                    <p class="pull-left">Showing <?php echo $prespagelow . " to " . $prespagehigh . " of " . $numrows; ?> entries</p>
                                </div>
                                <?php } ?>
                            </div>
                        </div>
                        <?php if(isset($all_campaign_email) && !empty($all_campaign_email)) { require_once 'layouts/pagination_links.php'; } ?>
                    </div>

                    <!-- Unique Page Content Ends Here
                    ================================================== -->
                    
                </div>
                
            </div>
        </div>
        <?php require_once 'layouts/footer.php'; ?>
    </body>
</html>