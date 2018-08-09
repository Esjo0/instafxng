<?php
require_once("../init/initialize_admin.php");
if (!$session_admin->is_logged_in()) {redirect_to("login.php");}
$bonus_operations = new Bonus_Operations();

$query = "SELECT * FROM bonus_packages ORDER BY package_id DESC ";
$numrows = $db_handle->numRows($query);
$rowsperpage = 20;
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
$packages = $db_handle->fetchAssoc($result);

?>
<!DOCTYPE html>
<html lang="en">
    <head>
        <base target="_self">
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>Instaforex Nigeria | Admin</title>
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
                            <h4><strong>BONUS PACKAGES</strong></h4>
                        </div>
                    </div>
                    
                    <div class="section-tint super-shadow">
                        <div class="row">
                            <div class="col-sm-12">
                                <?php require_once 'layouts/feedback_message.php'; ?>
                                <p class="pull-right"><a href="bonus_new.php" class="btn btn-default" title="Create New Bonus Package">Create New Bonus Package <i class="fa fa-arrow-circle-right"></i></a></p>
                                <table class="table table-bordered table-responsive">
                                    <thead>
                                        <tr>
                                            <th>Package Name</th>
                                            <th>Status</th>
                                            <th>Package Application URL</th>
                                            <th>Created</th>
                                            <th></th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                    <?php if(isset($packages) && !empty($packages)){ ?>
                                        <?php foreach($packages as $row){ ?>
                                        <tr>
                                            <td>
                                                <a href="javascript:void(0);" data-target="#_<?php echo $row['bonus_code'] ?>" data-toggle="modal" title="Package Description" ><?php echo $row['bonus_title']; ?></a>
                                                <div id="_<?php echo $row['bonus_code'] ?>" tabindex="-1" role="dialog" aria-hidden="true" class="modal fade">
                                                    <div class="modal-dialog modal-lg">
                                                        <div class="modal-content">
                                                            <div class="modal-header">
                                                                <button type="button" data-dismiss="modal" aria-hidden="true"  class="close">&times;</button>
                                                                <h4 class="modal-title"><?php echo $row['bonus_title']; ?></h4></div>
                                                            <div class="modal-body"><p class="text-justify"><?php echo $row['bonus_desc'] ?></p></div>
                                                            <div class="modal-footer">
                                                                <button type="button" name="close" onClick="window.close();" data-dismiss="modal" class="btn btn-sm btn-danger">Close!</button>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </td>
                                            <td><?php echo $bonus_operations->bonus_package_status($row['status']); ?></td>
                                            <td>
                                            <?php if($row['status'] == '2'): ?>
                                                <a href="../live_bonus_account.php?pc=<?php echo encrypt_ssl($row['bonus_code']);?>">https://instafxng.com/live_bonus_account.php?pc=<?php echo encrypt_ssl($row['bonus_code']);?></a>
                                            <?php endif; ?>
                                            </td>
                                            <td><?php echo datetime_to_text($row['created'])?></td>
                                            <td class="nowrap"><a class="btn-xs btn btn-default" href="bonus_view.php?pc=<?php echo encrypt_ssl($row['bonus_code']);?>"><i class="glyphicon glyphicon-arrow-right"></i></a></td>
                                        </tr>
                                        <?php } ?>
                                    <?php } ?>
                                    </tbody>
                                </table>
                                <?php if(isset($pending_moderation) && !empty($pending_moderation)) { ?>
                                    <div class="tool-footer text-right">
                                        <p class="pull-left">Showing <?php echo $prespagelow . " to " . $prespagehigh . " of " . $numrows; ?> entries</p>
                                    </div>
                                <?php } ?>
                            </div>
                        </div>
                        <?php if(isset($pending_moderation) && !empty($pending_moderation)) { require_once 'layouts/pagination_links.php'; } ?>

                    </div>

                    <!-- Unique Page Content Ends Here
                    ================================================== -->
                    
                </div>
                
            </div>
        </div>
        <?php require_once 'layouts/footer.php'; ?>
    </body>
</html>