<?php
require_once("../init/initialize_admin.php");
if (!$session_admin->is_logged_in()) {redirect_to("login.php");}
//$campaigns = $obj_loyalty_training->get_all_campaigns();

$query = "SELECT campaign_title, created, form_field_ids, landing_url, status, lead_image, campaign_code FROM campaign_leads_campaign ORDER BY created DESC ";
$numrows = $db_handle->numRows($query);
$rowsperpage = 10;
$totalpages = ceil($numrows / $rowsperpage);
// get the current page or set a default
if (isset($_GET['pg']) && is_numeric($_GET['pg'])) {$currentpage = (int) $_GET['pg'];
} else {$currentpage = 1;}
if ($currentpage > $totalpages) { $currentpage = $totalpages; }
if ($currentpage < 1) { $currentpage = 1; }

$prespagelow = $currentpage * $rowsperpage - $rowsperpage + 1;
$prespagehigh = $currentpage * $rowsperpage;
if($prespagehigh > $numrows) { $prespagehigh = $numrows; }

$offset = ($currentpage - 1) * $rowsperpage;
$query .= ' LIMIT ' . $offset . ',' . $rowsperpage;
$result = $db_handle->runQuery($query);
$campaigns = $db_handle->fetchAssoc($result);
?>
<!DOCTYPE html>
<html lang="en">
    <head>
        <base target="_self">
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>InstaFxNg | Lead's Campaigns</title>
        <meta name="title" content="InstaFxNg | Lead's Campaigns" />
        <meta name="keywords" content="" />
        <meta name="description" content="" />
        <?php require_once 'layouts/head_meta.php'; ?>
        <script src="//cdnjs.cloudflare.com/ajax/libs/clipboard.js/2.0.0/clipboard.min.js"></script>
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
                        <h4><strong>LEAD'S CAMPAIGNS</strong></h4>
                    </div>
                </div>
                <div class="section-tint super-shadow">
                    <div class="row">
                        <div class="col-sm-12">
                            <?php require_once 'layouts/feedback_message.php'; ?>
                            <p class="text-right"><a href="campaign_new.php" class="btn btn-default" title="New Lead's Campaign"> New Lead's Campaign  <i class="fa fa-arrow-circle-right"></i></a></p>

                            <?php if(isset($campaigns) && !empty($campaigns)) { require 'layouts/pagination_links.php'; } ?>

                            <table class="table table-bordered table-responsive table-hover">
                                <thead>
                                    <tr>
                                        <th></th>
                                        <th>Title</th>
                                        <th>Lead's Data</th>
                                        <th>Status</th>
                                        <th>Landing Page</th>
                                        <th>Created</th>
                                        <th></th>
                                    </tr>
                                </thead>
                                <tbody>
                                <?php if(!empty($campaigns)){ ?>
                                    <?php foreach($campaigns as $row){ ?>
                                        <tr>
                                            <td><img height="100px" width="70px" src="../images/campaigns/<?php echo $row['lead_image'];?>" class="img-thumbnail img-responsive" /></td>
                                            <td><?php echo $row['campaign_title'];?></td>
                                            <td><?php foreach(explode(',', $row['form_field_ids']) as $key){ echo Loyalty_Training::DYNAMIC_LANDING_PAGE_FORM_FIELDS[$key]['name'].'<br/> <br/>';} ?></td>
                                            <td><?php echo $obj_loyalty_training->get_campaign_status($row['status']);?></td>
                                            <td> <a target="_blank" href="<?php echo $row['landing_url'];?>"><?php echo $row['landing_url'];?></a> </td>
                                            <td><?php echo datetime_to_text($row['created']);?></td>
                                            <td class="nowrap"> <a class="btn btn-xs btn-info" href="campaign_new.php?cc=<?php echo $row['campaign_code'];?>&x=edit">Edit</a> </td>
                                        </tr>
                                    <?php } ?>
                                <?php }else{ ?>
                                    <tr>
                                        <td colspan="7">No campaigns yet....</td>
                                    </tr>
                                <?php } ?>
                                </tbody>
                            </table>
                            <?php if(isset($campaigns) && !empty($campaigns)) { ?>
                                <div class="tool-footer text-right">
                                    <p class="pull-left">Showing <?php echo $prespagelow . " to " . $prespagehigh . " of " . $numrows; ?> entries</p>
                                </div>
                            <?php } ?>
                    </div>
                </div>
                    <?php if(isset($campaigns) && !empty($campaigns)) { require_once 'layouts/pagination_links.php'; } ?>
            </div>

            <!-- Unique Page Content Ends Here
            ================================================== -->

        </div>

    </div>
</div>
<?php require_once 'layouts/footer.php'; ?>
</body>
</html>