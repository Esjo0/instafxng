<?php
require_once("../init/initialize_admin.php");
if (!$session_admin->is_logged_in()) {redirect_to("login.php");}
$bonus_operations = new Bonus_Operations();
$app_id = decrypt_ssl(str_replace(" ", "+", $_GET['app_id']));
$app_details = $bonus_operations->get_app_by_id($app_id);
if(empty($app_details)) {redirect_to("bonus_app_moderation.php");}
$conditions = $bonus_operations->get_conditions_by_code($app_details['bonus_code']);
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
                            <h4><strong>VIEW BONUS PACKAGE</strong></h4>
                        </div>
                    </div>
                    
                    <div class="section-tint super-shadow">
                        <div class="row">
                            <div class="col-sm-12">
                                <?php require_once 'layouts/feedback_message.php'; ?>
                                <p class="pull-left"><a href="bonus_app_moderation.php" class="btn btn-default" title="Bonus Applications"><i class="fa fa-arrow-circle-left"></i> Bonus Applications</a></p>

                                <table class="table table-responsive table-bordered">
                                    <tbody>
                                        <tr>
                                            <td><b>Name:</b>  <?php echo $app_details['first_name']; ?> <?php echo $app_details['middle_name']; ?> <?php echo $app_details['last_name']; ?></td>
                                            <td><b>Application Date:</b>  <?php echo datetime_to_text($app_details['created']); ?></td>
                                        </tr>
                                        <tr>
                                            <td><b>Email:</b>  <?php echo $app_details['email']; ?></td>
                                            <td><b>Phone:</b>  <?php echo $app_details['phone']; ?></td>
                                        </tr>
                                        <tr>
                                            <td><b>Account No:</b>  <?php echo $app_details['ifx_acct_no']; ?></td>
                                        </tr>
                                    </tbody>
                                </table>

                                <table class="table table-responsive table-bordered">
                                    <tbody>
                                    <tr>
                                        <td><b>BONUS PACKAGE:</b>  <?php echo $app_details['bonus_title']; ?></td>
                                    </tr>
                                    <tr><td><b>PACKAGE DETAILS:</b>  <?php echo $app_details['bonus_desc']; ?></td></tr>
                                    <tr>
                                        <td>
                                            <center><b>PACKAGE CONDITIONS</b></center>
                                            <table class="table table_responsive">
                                                <tbody>
                                                <?php if(!empty($conditions)){ ?>
                                                    <?php foreach ($conditions as $key => $value){ ?>
                                                        <?php if(!empty($value)){ ?>
                                                    <tr>
                                                        <td><?php echo $key; ?></td>
                                                        <td><?php echo $value['title'].'<br/>'.$value['desc']; ?></td>
                                                        <td class="nowrap"><input class="checkbox" type="checkbox" name=""></td>
                                                    </tr>
                                                        <?php } ?>
                                                    <?php } ?>
                                                <?php }else{echo "<tr><td>This bonus package does not have any condition.</td></tr>";} ?>
                                                </tbody>
                                            </table>
                                        </td>
                                    </tr>
                                    </tbody>
                                </table>

                                <form data-toggle="validator" class="form-horizontal" role="form" method="post" action="">
                                    <div class="form-group">
                                        <label class="control-label col-sm-3">Allocate Bonus:</label>
                                        <div class="col-sm-9 col-md-4">
                                            <input class="form-control" placeholder="Amount" type="text" name="allocated_amount" id="3" required />
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <div class="col-sm-offset-3 col-sm-9">
                                            <button type="button" data-target="#confirm-add" data-toggle="modal" class="btn btn-sm btn-success">Process</button>
                                        </div>
                                    </div>
                                    <!--Modal - confirmation boxes-->
                                    <div id="confirm-add" tabindex="-1" role="dialog" aria-hidden="true" class="modal fade">
                                        <div class="modal-dialog">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <button type="button" data-dismiss="modal" aria-hidden="true"  class="close">&times;</button>
                                                    <h4 class="modal-title">Process Application</h4></div>
                                                <div class="modal-body">
                                                    <center>Are you sure you want to process this application?
                                                        <br/>This action cannot be reversed.</center></div>
                                                <div class="modal-footer">
                                                    <input name="process" type="submit" class="btn btn-sm btn-success" value="Proceed">
                                                    <button type="button" name="close" onClick="window.close();" data-dismiss="modal" class="btn btn-sm btn-danger">Close!</button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </form>
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