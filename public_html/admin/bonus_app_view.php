<?php
require_once("../init/initialize_admin.php");
if (!$session_admin->is_logged_in()) {redirect_to("login.php");}
$bonus_operations = new Bonus_Operations();
$bonus_conditions = new Bonus_Condition();
$client_operation = new clientOperation();

//TODO: Get a list all the reasons a bonus application can be declined
if(isset($_POST['process-app'])){
    $_app_id = $db_handle->sanitizePost($_POST['app_id']);
    $_app_mod_comment = $db_handle->sanitizePost($_POST['app_mod_comment']);
    $result = $bonus_operations->approve_app($_app_id, $_app_mod_comment, $_SESSION['admin_unique_code']);
    $result ? $message_success = "Operation Successful" : $message_error = "Operation Failed";
}
if(isset($_POST['process-dec'])){
    $_app_id = $db_handle->sanitizePost($_POST['app_id']);
    $_reasons = $db_handle->sanitizePost($_POST['reasons']);
    $result = $bonus_operations->decline_app($_app_id, $_reasons, $_SESSION['admin_unique_code']);
    $result ? $message_success = "Operation Successful" : $message_error = "Operation Failed";
}


#Process ILPR Approval
if(isset($_POST['approve_ilpr'])){
    $account_type_update = '1';
    $user_ilpr_enrolment_id = $db_handle->sanitizePost($_POST['user_ilpr_enrolment_id']);
    $ifxaccount_id = $db_handle->sanitizePost($_POST['ifxaccount_id']);
    $update_account = $client_operation->update_account_type($ifxaccount_id, $account_type_update);
    $message_success = "You have successfully updated the account";
}

$app_id = dec_enc('decrypt',  $_GET['app_id']));
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
                            <h4><strong>VIEW BONUS APPLICATION</strong></h4>
                        </div>
                    </div>
                    
                    <div class="section-tint super-shadow">
                        <div class="row">
                            <div class="col-sm-12">
                                <?php require_once 'layouts/feedback_message.php'; ?>
                                <div class="row">
                                    <div class="col-sm-12">
                                    <p class="pull-left"><a href="bonus_app_moderation.php" class="btn btn-default" title="Bonus Applications"><i class="fa fa-arrow-circle-left"></i> Bonus Applications</a></p>
                                        <?php if($app_details['enrolment_status'] == '0'){?>
                                            <p class="pull-right">
                                                <button data-toggle="modal" data-target="#app_decline" class="btn btn-sm btn-danger" title="Decline Application."><b style="font-size: medium">&times;</b> Decline</button>
                                                <button data-toggle="modal" data-target="#app_approve" class="btn btn-sm btn-success" title="Approve Application"><b style="font-size: medium"><i class="fa fa-check"></i></b> Approve</button>
                                                <a target="_blank" class="btn btn-sm btn-default" href="campaign_email_single.php?name=<?php echo dec_enc('encrypt', $app_details['first_name']." ".$app_details['middle_name']." ".$app_details['last_name']).'&email='.dec_enc('encrypt', $app_details['email']);?>" title="Send This Client A Mail"><b style="font-size: medium"><i class="glyphicon glyphicon-envelope"></i></b> Send Mail</a>
                                                <a target="_blank" class="btn btn-sm btn-default" href="campaign_sms_single.php?lead_phone=<?php echo dec_enc('encrypt', $app_details['phone']) ?>" title="Send This Client An SMS"><b style="font-size: medium"><i class="glyphicon glyphicon-phone"></i></b> Send SMS</a>
                                            </p>
                                        <?php } ?>
                                    </div>
                                </div>

                                <form data-toggle="validator" class="form-horizontal" role="form" method="post" action="">
                                    <div id="app_decline" tabindex="-1" role="dialog" aria-hidden="true" class="modal fade">
                                        <div class="modal-dialog">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <button type="button" data-dismiss="modal" aria-hidden="true"  class="close">&times;</button>
                                                    <h4 class="modal-title">Decline Bonus Application</h4></div>
                                                <div class="modal-body">
                                                    <input type="hidden" name="app_id" value="<?php echo $app_id; ?>" />
                                                    <textarea maxlength="255" placeholder="Please state explicitly why this application is being denied." rows="4" class="form-control" name="reasons" required></textarea>
                                                </div>
                                                <div class="modal-footer">
                                                    <input name="process-dec" type="submit" class="btn btn-sm btn-success" value="Proceed">
                                                    <button type="button" name="close" onClick="window.close();" data-dismiss="modal" class="btn btn-sm btn-danger">Close!</button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div id="app_approve" tabindex="-1" role="dialog" aria-hidden="true" class="modal fade">
                                        <div class="modal-dialog">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <button type="button" data-dismiss="modal" aria-hidden="true"  class="close">&times;</button>
                                                    <h4 class="modal-title">Approve Bonus Application</h4></div>
                                                <div class="modal-body">
                                                    <div class="form-group">
                                                        <div class="col-sm-12">
                                                            <input type="hidden" name="app_id" value="<?php echo $app_id; ?>" />
                                                            <textarea name="app_mod_comment" rows="3" class="form-control" placeholder="Enter a remark." required></textarea>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="modal-footer">
                                                    <input name="process-app" type="submit" class="btn btn-sm btn-success" value="Proceed">
                                                    <button type="button" name="close" onClick="window.close();" data-dismiss="modal" class="btn btn-sm btn-danger">Close!</button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </form>

                                <?php if($app_details['enrolment_status'] == '1'){$_meta = $bonus_operations->get_app_meta_by_id($app_id); ?>
                                    <div class="alert alert-danger ">
                                        This bonus application has been declined by <?php echo $admin_object->get_admin_name_by_code($_meta['admin_code']); ?> on <?php echo datetime_to_text($_meta['_created']) ?>.<br/>
                                        <strong>Reasons: </strong><?php echo $_meta['comments']?>
                                    </div>
                                <?php } ?>
                                <?php if($app_details['enrolment_status'] == '2'){$_meta = $bonus_operations->get_app_meta_by_id($app_id); ?>
                                    <div class="alert alert-success">
                                        This bonus application has been approved by <?php echo $admin_object->get_admin_name_by_code($app_details['compliance_officer']); ?> on <?php echo datetime_to_text($app_details['updated']) ?>.<br/>
<!--                                        <strong>Allocated Amount: </strong> &dollar; <?php /*echo number_format($app_details['allocated_amount'], 2, ".", ","); */?><br/>
<!--                                        <strong>Date Of Review: </strong> <?php /*echo datetime_to_text($app_details['updated'])*/?><br/>
                                        <!--<strong>Bonus Status: </strong> --><?php /*echo $bonus_operations->bonus_status($app_details['allocation_status']); */?>
                                    </div>
                                <?php } ?>



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
                                            <td>
                                                <div class="col-sm-12">
                                                    <?php if($app_details['account_type'] == '2'):?>
                                                    <form data-toggle="validator" class="form-horizontal" role="form" method="post" action="">
                                                        <input class="btn btn-sm btn-group-justified btn-success" type="submit" name="approve_ilpr" value="Enroll into ILPR" />
                                                        <input type="hidden" value="<?php echo $app_details['user_ilpr_enrolment_id'];?>" name="user_ilpr_enrolment_id">
                                                        <input type="hidden" value="<?php echo $app_details['ifxaccount_id'];?>" name="ifxaccount_id">
                                                    </form>
                                                        <?php endif; ?>
                                                    <?php if($app_details['account_type'] == '1'):?>
                                                        <b>ILPR Enrollment Status:</b>  Enrolled
                                                    <?php endif; ?>
                                                </div>
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>



                                <table class="table table-responsive table-bordered">
                                    <tbody>
                                        <tr>
                                            <td>
                                                <center><b>Withdrawal Transaction History</b></center><br/>
                                                <b>Total Withdrawn:</b>  <?php echo number_format($bonus_operations->get_withdrawals_history($app_details['ifx_acct_no'])['total'], 2, '.', ','); ?><br/>
                                                <b>Average Withdrawal:</b>  <?php echo number_format($bonus_operations->get_withdrawals_history($app_details['ifx_acct_no'])['average'], 2, '.', ','); ?><br/>
                                            </td>
                                            <td rowspan="2">
                                                <center><b>Client Flags</b></center><br/>
                                                <?php $client_flag = $client_operation->get_client_flag_by_code($app_details['user_code']) ?>
                                                <div style="max-height: 400px; overflow: scroll;">
                                                    <?php if(isset($client_flag) && !empty($client_flag)) {
                                                        foreach ($client_flag as $row) {?>
                                                            <div class="col-sm-12">
                                                                <div class="transaction-remarks">
                                                                    <span id="trans_remark_author"><?php echo $row['admin_full_name']; ?></span>
                                                                    <span id="trans_remark"><?php echo $row['comment']; ?></span>
                                                                    <span id="trans_remark_date"><?php echo datetime_to_text($row['created']); ?></span>
                                                                </div>
                                                            </div>
                                                        <?php } } else { ?>
                                                        <div class="row"><div class="col-sm-12"><div class="transaction-remarks"><span class="text-danger"><em>There is no remark to display.</em></span></div></div></div>
                                                    <?php } ?>
                                                </div>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>
                                                <center><b>Funding Transaction History</b></center><br/>
                                                <b>Total Funded:</b>  <?php echo number_format($bonus_operations->get_funding_history($app_details['ifx_acct_no'])['total'], 2, '.', ','); ?><br/>
                                                <b>Average Funding:</b>  <?php echo number_format($bonus_operations->get_funding_history($app_details['ifx_acct_no'])['average'], 2, '.', ','); ?><br/>
                                            </td>

                                        </tr>
                                    </tbody>
                                </table>

                                <table class="table table-responsive table-bordered">
                                    <tbody>
                                    <tr>
                                        <td><b>BONUS PACKAGE:</b>  <?php echo $app_details['bonus_title']; ?></td>
                                    </tr>
                                    <tr><td><b>PACKAGE DESCRIPTION:  </b>  <?php echo $app_details['bonus_desc']; ?></td></tr>
                                    <tr style="display: none"><td><b>PACKAGE DETAILS:  </b>  <?php echo $app_details['bonus_details']; ?></td></tr>
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
                                                        <td>
                                                            <?php echo $value['title'].'<br/>'.$value['desc']; ?>
                                                            <br/>
                                                            <?php
                                                                $package_metas = $bonus_operations->get_single_package_meta_by_code($bonus_code, $key);
                                                                foreach ($package_metas as $package_meta){
                                                                    echo $package_meta['meta_name']." : ".$package_meta['meta_value']."<br/>";
                                                                }
                                                            ?>
                                                        </td>
                                                        <td class="nowrap">
                                                            <?php $cond_feedback = $bonus_conditions->{$value['api']}($app_details['bonus_account_id']) ?>
                                                            <!--<input class="checkbox" type="checkbox" <?php /*if($cond_feedback['status']== true){echo "checked";} */?> name="">-->
                                                            <?php if($cond_feedback['status']== false){echo '<p style="font-size: larger" class="text-center text-danger"><b>&times;</b></p>';} ?>
                                                            <?php if($cond_feedback['status']== true){echo '<p style="font-size: larger" class="text-center text-success"><b><i class="fa fa-check"></i></b></p>';} ?>
                                                        </td>
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