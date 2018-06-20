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
                                <table class="table table-bordered table-responsive">
                                    <thead>
                                        <tr>
                                            <th>Package Name</th>
                                            <th>Active Users</th>
                                            <th>Recycled Users</th>
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
                                            <td>
                                                <a href="javascript:void(0);" data-target="#activeUsers_<?php echo $row['bonus_code'] ?>" data-toggle="modal" title="Package Description" ><?php echo $bonus_operations->get_package_active_clients($row['bonus_code'], 0); ?> Active User(s)</a>
                                                <div id="activeUsers_<?php echo $row['bonus_code'] ?>" tabindex="-1" role="dialog" aria-hidden="true" class="modal fade">
                                                    <div class="modal-dialog modal-lg">
                                                        <div class="modal-content">
                                                            <div class="modal-header">
                                                                <button type="button" data-dismiss="modal" aria-hidden="true"  class="close">&times;</button>
                                                                <h4 class="modal-title"><?php echo $row['bonus_title']; ?></h4></div>
                                                            <div class="modal-body">
                                                                <p class="text-justify">Below is the list of this Bonus Package's active users.</p>
                                                                <table class="table table-bordered table-responsive">
                                                                    <thead>
                                                                        <tr>
                                                                            <th>Full Name</th>
                                                                            <th>Phone</th>
                                                                            <th>Email</th>
                                                                            <th>Account</th>
                                                                            <th>Date Of Activation</th>
                                                                        </tr>
                                                                    </thead>
                                                                    <tbody>
                                                                    <?php $active_users = $bonus_operations->get_package_active_clients($row['bonus_code'], 1);?>
                                                                    <?php if(isset($active_users) && !empty($active_users)): ?>
                                                                        <?php foreach ($active_users as $key){ ?>
                                                                            <tr>
                                                                                <td><?php echo $key['fullname']?></td>
                                                                                <td><?php echo $key['phone']?></td>
                                                                                <td><?php echo $key['email']?></td>
                                                                                <td><?php echo $key['ifx_acct_no']?></td>
                                                                                <td><?php echo datetime_to_text($key['created']); ?></td>
                                                                            </tr>
                                                                        <?php } ?>
                                                                    <?php endif; ?>
                                                                    <?php if(!isset($active_users) || empty($active_users)): ?>
                                                                        <tr>
                                                                            <td colspan="5"><center><em>No Active User</em></center></td>
                                                                        </tr>
                                                                    <?php endif; ?>
                                                                    </tbody>
                                                                </table>
                                                            </div>
                                                            <div class="modal-footer">
                                                                <button type="button" name="close" onClick="window.close();" data-dismiss="modal" class="btn btn-sm btn-danger">Close!</button>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </td>
                                            <td>
                                                <a href="javascript:void(0);" data-target="#recycledUsers_<?php echo $row['bonus_code'] ?>" data-toggle="modal" title="List Of Recycled Users" ><?php echo $bonus_operations->get_package_recycled_clients($row['bonus_code'], 0); ?> Recycled User(s)</a>
                                                <div id="recycledUsers_<?php echo $row['bonus_code'] ?>" tabindex="-1" role="dialog" aria-hidden="true" class="modal fade">
                                                    <div class="modal-dialog modal-lg">
                                                        <div class="modal-content">
                                                            <div class="modal-header">
                                                                <button type="button" data-dismiss="modal" aria-hidden="true"  class="close">&times;</button>
                                                                <h4 class="modal-title"><?php echo $row['bonus_title']; ?></h4></div>
                                                            <div class="modal-body">
                                                                <p class="text-justify">Below is the list of this Bonus Package's recycled users.</p>
                                                                <table class="table table-bordered table-responsive">
                                                                    <thead>
                                                                    <tr>
                                                                        <th>Full Name</th>
                                                                        <th>Phone</th>
                                                                        <th>Email</th>
                                                                        <th>Account</th>
                                                                        <th>Date Of Completion</th>
                                                                    </tr>
                                                                    </thead>
                                                                    <tbody>
                                                                    <?php $recycled_users = $bonus_operations->get_package_recycled_clients($row['bonus_code'], 1);?>
                                                                    <?php if(isset($recycled_users) && !empty($recycled_users)): ?>
                                                                        <?php foreach ($recycled_users as $key){ ?>
                                                                            <tr>
                                                                                <td><?php echo $key['fullname']?></td>
                                                                                <td><?php echo $key['phone']?></td>
                                                                                <td><?php echo $key['email']?></td>
                                                                                <td><?php echo $key['ifx_acct_no']?></td>
                                                                                <td><?php echo datetime_to_text($key['created']); ?></td>
                                                                            </tr>
                                                                        <?php } ?>
                                                                    <?php endif; ?>
                                                                    <?php if(!isset($recycled_users) || empty($recycled_users)): ?>
                                                                        <tr>
                                                                            <td colspan="5"><center><em>No Recycled User</em></center></td>
                                                                        </tr>
                                                                    <?php endif; ?>
                                                                    </tbody>
                                                                </table>
                                                            </div>
                                                            <div class="modal-footer">
                                                                <button type="button" name="close" onClick="window.close();" data-dismiss="modal" class="btn btn-sm btn-danger">Close!</button>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </td>
                                            <td><?php echo datetime_to_text($row['created'])?></td>
                                            <td class="nowrap"><a class="btn-xs btn btn-default" href="bonus_view.php?package_code=<?php echo encrypt_ssl($row['bonus_code']);?>"><i class="glyphicon glyphicon-arrow-right"></i></a></td>
                                        </tr>
                                        <?php } ?>
                                    <?php } ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>

                    </div>

                    <!-- Unique Page Content Ends Here
                    ================================================== -->
                    
                </div>
                
            </div>
        </div>
        <?php require_once 'layouts/footer.php'; ?>
    </body>
</html>