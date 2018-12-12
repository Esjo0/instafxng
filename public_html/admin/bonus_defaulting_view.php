<?php
require_once("../init/initialize_admin.php");
if (!$session_admin->is_logged_in()) {redirect_to("login.php");}
$bonus_operations = new Bonus_Operations();
$bonus_conditions = new Bonus_Condition();
//TODO: Get a list all the reasons a bonus application can be declined
if(isset($_POST['reviewed'])){
    foreach ($_POST as $key => $value){$_POST[$key] = $db_handle->sanitizePost(trim($value));}
    extract($_POST);
    var_dump($_POST);
    $hello = $bonus_operations->retrieve_bonus($_SESSION['admin_unique_code'], $remark, $ret_trans_ref, $ret_amount, $bonus_account_id);
    $hello ? $message_success = "Operation Successful" : $message_warning = "Operation Failed: Please review this bonus profile afresh.";
}

$app_id = dec_enc('decrypt',  $_GET['app_id']));
$app_details = $bonus_operations->get_app_by_id($app_id);
if(empty($app_details)) {redirect_to("bonus_app_moderation.php");}
$conditions = $bonus_operations->get_conditions_by_code($app_details['bonus_code']);
$_meta = $bonus_operations->get_app_meta_by_id($app_id);


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
                            <h4><strong>REVIEW DEFAULTING BONUS ACCOUNT</strong></h4>
                        </div>
                    </div>
                    
                    <div class="section-tint super-shadow">
                        <div class="row">
                            <div class="col-sm-12">
                                <?php require_once 'layouts/feedback_message.php'; ?>
                                <div class="row">
                                    <div class="col-sm-12">
                                        <p class="pull-left"><a href="bonus_defaulting_accounts.php" class="btn btn-default" title="Bonus Accounts"><i class="fa fa-arrow-circle-left"></i> Bonus Accounts</a></p>
                                        <p class="pull-right"><button data-toggle="modal" data-target="#app_approve" class="btn btn-sm btn-danger" title=""><b style="font-size: medium"><i class="glyphicon glyphicon-remove"></i> Retrieve Funds</b></button></p>
                                    </div>
                                </div>
                                <form data-toggle="validator" class="form-horizontal" role="form" method="post" action="">

                                    <div id="app_approve" tabindex="-1" role="dialog" aria-hidden="true" class="modal fade">
                                        <div class="modal-dialog">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <button type="button" data-dismiss="modal" aria-hidden="true"  class="close">&times;</button>
                                                    <h4 class="modal-title">Bonus Withdrawal</h4></div>
                                                <div class="modal-body">
                                                    This bonus account has defaulted as regards its specified Terms and Conditions.
                                                    Please fill in the form details below to retrieve the bonus funds from this account.

                                                    <div class="form-group"></div>
                                                    <input type="hidden" value="<?php echo $app_details['bonus_account_id'] ?>" name="bonus_account_id">
                                                    <input type="hidden" value="<?php echo $app_details['bonus_code'] ?>" name="bonus_code">
                                                    <div class="form-group">
                                                        <label class="control-label col-sm-4" for="firstname">Amount Retrieved: </label>
                                                        <div class="col-sm-8">
                                                            <input name="ret_amount" type="text" id="" value="" class="form-control" required/>
                                                        </div>
                                                    </div>
                                                    <div class="form-group">
                                                        <label class="control-label col-sm-4" for="lastname">Transfer Reference: </label>
                                                        <div class="col-sm-8">
                                                            <input name="ret_trans_ref" type="text" id="" value="" class="form-control" required/>
                                                        </div>
                                                    </div>
                                                    <div class="form-group">
                                                        <div class="col-sm-12">
                                                            <textarea required name="remark" PLACEHOLDER="Your Remarks..." rows="3" class="form-control"></textarea>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="modal-footer">
                                                    <input name="reviewed" type="submit" class="btn btn-xs btn-success" value="Proceed">
                                                    <button type="button" name="close" onClick="window.close();" data-dismiss="modal" class="btn btn-xs btn-danger">Close!</button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

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
                                            <td><b>Compliance Officer:</b> <?php echo $admin_object->get_admin_name_by_code($app_details['compliance_officer']); ?></td>
                                            <td><b>Date:</b>  <?php echo datetime_to_text($app_details['updated']) ?></td>
                                        </tr>
                                        <tr>
                                            <td><b>Allocated Amount:</b>  &dollar; <?php echo number_format($app_details['allocated_amount'], 2, ".", ","); ?></td>
                                            <td><b>Date Of Allocation:</b>  <?php echo datetime_to_text($app_details['allocation_date'])?></td>
                                        </tr>
                                        <tr>
                                            <td><b>Bonus Status:</b>  <?php echo $bonus_operations->bonus_status($app_details['allocation_status']); ?></td>
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
                                                            <?php $func = $bonus_conditions->{$value['api']}($app_details[$value['args']]); ?>
                                                            <td><?php echo $key; ?></td>
                                                            <td><?php echo $value['title'].'<br/>'.$value['desc']; ?></td>
                                                            <td class="nowrap">
                                                                <?php $cond_feedback = $bonus_conditions->{$value['api']}($app_details['bonus_account_id']) ?>
                                                                <!--<input class="checkbox" type="checkbox" <?php /*if($cond_feedback['status']== true){echo "checked";} */?> name="">-->
                                                                <?php if($cond_feedback['status']== false){echo '<p style="font-size: larger" class="text-center text-danger"><b>&times;</b></p>';} ?>
                                                                <?php if($cond_feedback['status']== true){echo '<p style="font-size: larger" class="text-center text-success"><b><i class="fa fa-check"></i></b></p>';} ?>


<!--                                                                <input class="checkbox" type="checkbox" <?php /*if($func['status']){ echo 'checked';} */?> value="<?php /*echo $func['status']*/?>"  name="condition[<?php /*echo $key; */?>]">
-->                                                            </td>
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