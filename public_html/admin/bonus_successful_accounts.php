<?php
ini_set("xdebug.var_display_max_children", -1);
ini_set("xdebug.var_display_max_data", -1);
ini_set("xdebug.var_display_max_depth", -1);

require_once("../init/initialize_admin.php");
if (!$session_admin->is_logged_in()) {redirect_to("login.php");}
$bonus_operations = new Bonus_Operations();
$pending_moderation = $bonus_operations->get_successful_accounts();

$numrows = count($pending_moderation);
$rowsperpage = 20;
$totalpages = ceil($numrows / $rowsperpage);
if (isset($_GET['pg']) && is_numeric($_GET['pg'])) {$currentpage = (int) $_GET['pg'];}
else {$currentpage = 1;}
if ($currentpage > $totalpages) { $currentpage = $totalpages; }
if ($currentpage < 1) { $currentpage = 1; }
$prespagelow = $currentpage * $rowsperpage - $rowsperpage + 1;
$prespagehigh = $currentpage * $rowsperpage;
if($prespagehigh > $numrows) { $prespagehigh = $numrows; }
$offset = ($currentpage - 1) * $rowsperpage;
$pending_moderation = paginate_array($offset, $pending_moderation, $rowsperpage);
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
                            <h4><strong>REVIEW SUCCESSFUL BONUS ACCOUNTS</strong></h4>
                        </div>
                    </div>
                    
                    <div class="section-tint super-shadow">
                        <div class="row">
                            <div class="col-sm-12">
                                <?php require_once 'layouts/feedback_message.php'; ?>
                                <table class="table table-bordered table-responsive">
                                    <thead>
                                        <tr>
                                            <th>Full Name</th>
                                            <th>Phone</th>
                                            <th>Email</th>
                                            <th>Bonus Package</th>
                                            <th>Created</th>
                                            <th></th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                    <?php if(isset($pending_moderation) && !empty($pending_moderation)){ ?>
                                        <?php foreach($pending_moderation as $row){ extract($row);?>
                                            <tr>
                                                <td><?php echo $first_name ?> <?php echo $middle_name ?> <?php echo $last_name ?></td>
                                                <td><?php echo $phone ?> </td>
                                                <td><?php echo $email ?> </td>
                                                <td><?php echo $bonus_title ?> </td>
                                                <td><?php echo datetime_to_text($created); ?></td>
                                                <td class="nowrap"><a class="btn-xs btn btn-default" href="bonus_success_view.php?app_id=<?php echo dec_enc('encrypt', $row['app_id']);?>"><i class="glyphicon glyphicon-arrow-right"></i></a></td>
                                            </tr>
                                        <?php } ?>
                                    <?php }else{ ?>
                                        <tr><td colspan="6"><em class="text-center">No Pending Application</em></td></tr>
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