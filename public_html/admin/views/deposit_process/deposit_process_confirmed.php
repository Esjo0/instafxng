<?php
// Get details of loyalty point claimed by client while submitting this transaction
if(!empty($trans_detail['points_claimed_id'])) {
    $point_dollar_amount = $client_operation->get_loyalty_point_dollar_amount($trans_detail['user_code'], $trans_detail['points_claimed_id']);
}
?>

<p><button onclick="history.go(-1);" class="btn btn-default" title="Go back to previous page"><i class="fa fa-arrow-circle-left"></i> Go Back!</button></p>
<p>Make Modification to this order below.</p>
<p>Fill the transfer reference, add your remark, then process this transaction. Please note that you must enter a remark for this transaction.</p>
<div class="row">
    <div class="col-sm-6">
        <div class="trans_item">
            <div class="trans_item_content">
                <div class="row">
                    <div class="col-sm-4 trans_item-thumb">
                        <p class="text-center"><a target="_blank" title="View Client Profile" class="btn btn-info" href="client_detail.php?id=<?php echo encrypt($trans_detail['user_code']); ?>"><i class="glyphicon glyphicon-eye-open icon-white"></i> </a></p>
                        <?php
                        if(!empty($trans_detail['passport'])) { $file_location = "../userfiles/" . $trans_detail['passport']; }

                        if(file_exists($file_location)) {
                            ?>
                            <img src="<?php echo $file_location; ?>" alt="" class="img-responsive">
                        <?php } else { ?>
                            <img src="../images/placeholder.jpg" alt="" class="img-responsive">
                        <?php } ?>

                        <?php if($client_operation->account_flagged($trans_detail['user_code'])) { ?>
                            <p><img class="center-block" src="../images/red-flag.png" alt="" title="This client has an account flagged."></p>
                        <?php } ?>
                    </div>
                    <div class="col-sm-8 ">
                        <div class="row">
                            <div class="col-xs-12 trans_item_bio">
                                <span id="bio_name"><?php echo $trans_detail['full_name']; ?></span>
                                <span><?php echo $trans_detail['phone']; ?></span>
                                <span><?php echo $trans_detail['email']; ?></span>
                                <hr/>
                                <span class="text-danger"><strong>IFX Accounts</strong></span>
                                <span style="max-width: 500px; overflow: scroll">
                                    <?php
                                    $client_ifxaccounts = $client_operation->get_client_ifxaccounts($trans_detail['user_code']);
                                    $count = 1;
                                    foreach($client_ifxaccounts as $key => $value) {
                                        $ifx_acct_no = $value['ifx_acct_no'];
                                        if($count < count($client_ifxaccounts)) { $ifx_acct_no = $ifx_acct_no . ", "; }
                                        echo $ifx_acct_no;
                                        $count++;
                                    }
                                    ?>
                                </span>
                                <hr/>
                                <span><?php $transaction_issue = $admin_object->get_transaction_issue($trans_id);
                                    if($transaction_issue == false){ ?>
                                        <i title="Add this transaction to the Operations log" type="button" data-target="#add<?php echo $trans_id; ?>" data-toggle="modal" class="fa fa-plus-circle btn btn-default" style="color:red;" aria-hidden="true"> </i>
                                        Add to Operations Log<?php
                                    }else{
                                        foreach ($transaction_issue as $row_issue){
                                            if($row_issue['status'] == '1'){ ?>
                                                <i title="Add this transaction to the Operations log" type="button" data-target="#add<?php echo $trans_id; ?>" data-toggle="modal" class="fa fa-plus-circle btn btn-default" style="color:red;" aria-hidden="true"> </i>
                                                Add to Operations Log<?php
                                            }}}?> </span>
                                <!--Modal-- to add new operations log-->
                                <div id="add<?php echo $trans_id; ?>" tabindex="-1" role="dialog" aria-hidden="true" class="modal fade">
                                    <div class="modal-dialog">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <button type="button" data-dismiss="modal" aria-hidden="true"
                                                        class="close">&times;</button>
                                                <h4 class="modal-title">Add New Record</h4></div>
                                            <div class="modal-body">
                                                <form data-toggle="validator" class="form-vertical" role="form" method="post" action="" enctype="multipart/form-data">
                                                    <input name="transaction_id" class="form-control" type="hidden" value="<?php echo $trans_id ?>"  required>
                                                    <div class="form-group row">
                                                        <label for="inputSubtile3" class="col-sm-2 col-form-label">Description</label>
                                                        <div class="col-sm-10">
                                                            <textarea name="details" class="form-control" rows="3" placeholder="Enter Detailed Description of Clients issue" required></textarea>
                                                        </div>
                                                    </div>

                                            </div>
                                            <div class="modal-footer">
                                                <input name="add" type="submit" class="btn btn-success" value="Add To Records">
                                                <button type="submit" name="close" onClick="window.close();" data-dismiss="modal" class="btn btn-danger">Close!</button>
                                            </div>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                                <?php
                                if (isset($_POST['add'])){
                                    $transaction_id = $db_handle->sanitizePost(trim($_POST['transaction_id']));
                                    $query = "SELECT transaction_id FROM operations_log WHERE transaction_id = '$trans_id'";
                                    $numrows = $db_handle->numRows($query);
                                    if($numrows == 0){
                                        $details = $db_handle->sanitizePost(trim($_POST['details']));
                                        $add = $admin_object->add_issues($trans_id,$details,$admin_code);
                                        if($add = true) {
                                            $message_success = "You have successfully added a new record";
                                        } else {
                                            $message_error = "Something went wrong. Please try again.";
                                        }

                                    }elseif($numrows == 1){
                                        $details = $db_handle->sanitizePost(trim($_POST['details']));
                                        $query = "SELECT details FROM operations_log WHERE transaction_id = '$trans_id' LIMIT 1";
                                        $result = $db_handle->runQuery($query);
                                        $old_details = $db_handle->fetchAssoc($result);
                                        foreach ($old_details AS $rowd){$old_details = $rowd['details'];}
                                        $date = date("D M d, Y G:i");
                                        $new_details = $old_details."</br><hr/></br>Re-Opened On ".$date."<br/><strong> >> </strong>".$details;
                                        $query = "UPDATE operations_log SET status = '0', details = '$new_details' WHERE transaction_id = '$trans_id'";
                                        $result = $db_handle->runQuery($query);
                                        if($result == true){
                                            $message_success = "You have reopened this issue";
                                        } else {
                                            $message_error = "Something went wrong. Please try again.";
                                        }
                                    }
                                }
                                ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-sm-6">
        <div class="trans_item">
            <div class="trans_item_content">
                <div class="row">
                    <div class="col-sm-12 ">
                        <span id="transaction_identity"><?php echo $trans_id; ?></span>
                        <span><strong>Order:</strong> &dollar; <?php echo $trans_detail['dollar_ordered']; ?> - &#8358; <?php echo number_format($trans_detail['naira_total_payable'], 2, ".", ","); ?></span>
                        <span><strong>Date Created: </strong><?php echo datetime_to_text($trans_detail['deposit_created']); ?></span>
                        <span><strong>Account:</strong> <?php echo $trans_detail['ifx_acct_no']; ?></span>
                        <hr/>
                        <span><strong>Paid:</strong> &#8358; <?php echo number_format($trans_detail['client_naira_notified'], 2, ".", ","); ?></span>
                        <span><strong>Payment Date:</strong> <?php if(!is_null($trans_detail['client_pay_date'])) { echo date_to_text($trans_detail['client_pay_date']); } ?></span>
                        <span><strong>Date Notified: </strong><?php echo datetime_to_text($trans_detail['client_notified_date']); ?></span>
                        <span><strong>Ref:</strong> <?php echo $trans_detail['client_reference']; ?></span>
                        <span><strong>Method:</strong> <?php echo status_user_deposit_pay_method($trans_detail['client_pay_method']); ?></span>
                    </div>
                </div>
            </div>
        </div>
    </div>

</div>

<hr/>

<div class="row">
    <div class="col-sm-6">
        <br/>
        <div class="row">
            <div class="col-sm-12">
                <div class="transaction-remarks">
                    <span id="trans_remark_author">Client Comment:</span>
                    <span class="text-danger"><em><?php if(isset($trans_detail['client_comment'])) { echo $trans_detail['client_comment']; } else { echo "-----"; } ?></em></span>

                    <?php
                    // allow admin to reply to client comment
                    if(isset($trans_detail['client_comment']) && strlen(trim($trans_detail['client_comment'])) > 10) {
                        if($trans_detail['client_comment_response'] == '1') { // Comment has been replied by an Admin
                            ?>
                            <hr />
                            <p style="text-align: right"><em>Comment Replied</em></p>

                        <?php } else { ?>

                            <?php if($transaction_access['status']): ?>
                            <p style="text-align: right">
                                <button type="button" data-target="#reply-client-comment" data-toggle="modal" class="btn btn-default">Reply Comment</button>
                            </p>
                            <?php endif; ?>

                            <!-- Modal - confirmation boxes -->
                            <div id="reply-client-comment" tabindex="-1" role="dialog" aria-hidden="true" class="modal fade">
                                <div class="modal-dialog">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <button type="button" data-dismiss="modal" aria-hidden="true"
                                                    class="close">&times;</button>
                                            <h4 class="modal-title">Reply Client Comment on Transaction</h4>
                                        </div>

                                        <form data-toggle="validator" class="form-horizontal" role="form" method="post" action="<?php echo $REQUEST_URI; ?>">
                                            <input type="hidden" name="client_name" value="<?php echo $trans_detail['full_name']; ?>" />
                                            <input type="hidden" name="client_email" value="<?php echo $trans_detail['email']; ?>" />
                                            <input type="hidden" name="trans_id" value="<?php echo $trans_id; ?>" />
                                            <div class="modal-body">
                                                <p>Type your reply in the space below. Use [NAME] wherever you want the client name inserted.</p>
                                                <label class="control-label" for="content">Message:</label>
                                                <div class="form-group">
                                                    <div class="col-sm-12"><textarea name="content" id="content" rows="3" class="form-control"></textarea></div>
                                                </div>
                                            </div>
                                            <div class="modal-footer">
                                                <input name="reply-deposit-comment" type="submit" class="btn btn-success" value="Send" />
                                                <button type="submit" name="decline" onClick="window.close();" data-dismiss="modal" class="btn btn-danger">Close !</button>
                                            </div>
                                        </form>

                                    </div>
                                </div>
                            </div>

                        <?php } } ?>

                </div>
            </div>
        </div>
        <br/>
        <form  data-toggle="validator" role="form" method="post" action="">
            <input type="hidden" class="form-control" id="transaction_id" name="transaction_id" value="<?php echo $trans_id; ?>">
            <div class="form-group">
                <label class="control-label text-danger" for="realamtpaid">Actual Amount Paid (&#8358;):</label>
                <div>
                    <input type="text" class="form-control" id="realamtpaid" name="realamtpaid" value="<?php echo number_format($trans_detail['real_naira_confirmed'], 2, ".", ","); ?>" readonly/>
                </div>
            </div>
            <div class="form-group">
                <label class="control-label text-danger" for="realDolVal">Real Dollar Value (&#36;):</label>
                <div id="realDol"><input type="text" class="form-control" id="realDolVal" name="realDolVal" value="<?php echo number_format($trans_detail['real_dollar_equivalent'], 2, ".", ","); ?>" readonly></div>
            </div>
            
            <?php if(isset($point_dollar_amount)) { ?>
            <div class="form-group">
                <label class="control-label text-danger" for="point_dollar_amount">Loyalty Point Dollar Value (&#36;):</label>
                <div>
                    <input type="hidden" name="points_claimed_id" value="<?php echo $trans_detail['points_claimed_id']; ?>" />
                    <input type="text" class="form-control" id="point_dollar_amount" name="point_dollar_amount" value="<?php echo number_format($point_dollar_amount, 2, ".", ","); ?>" readonly>
                </div>
            </div>

            <div class="form-group">
                <label class="control-label text-danger">Total Amount To Fund Client:</label>
                <div>
                    <p style="font-size: 1.3em; padding: 0; color: green;"><strong>&dollar; <?php echo number_format(($trans_detail['real_dollar_equivalent'] + $point_dollar_amount), 2, ".", ","); ?></strong> (points inclusive)</p>
                </div>
            </div>
            <?php } ?>


            <div class="form-group">
                <label class="control-label" for="trans_ref">IFX Transfer Reference:</label>
                <div><textarea name="trans_ref" id="trans_ref" rows="3" class="form-control" placeholder="Enter IFX Transfer Reference"></textarea></div>
            </div>
            <?php if($transaction_access['status']): ?>
            <div class="form-group">
                <label class="control-label" for="remarks">Your Remark:</label>
                <div><textarea name="remarks" id="remarks" rows="3" class="form-control" placeholder="Enter your remark" required></textarea></div>
            </div>
            <div class="form-group">
                <button type="button" data-target="#confirm-deposit-approve" data-toggle="modal" class="btn btn-success">Complete Deposit</button>
                <button type="button" data-target="#confirm-deposit-decline" data-toggle="modal" class="btn btn-danger">Decline Deposit</button>
                <button type="button" data-target="#confirm-deposit-pend" data-toggle="modal" class="btn btn-info">Pend Deposit</button>
            </div>
            <?php endif; ?>

            <!--Modal - confirmation boxes-->

            <div id="confirm-deposit-approve" tabindex="-1" role="dialog" aria-hidden="true" class="modal fade">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <button type="button" data-dismiss="modal" aria-hidden="true"
                                class="close">&times;</button>
                            <h4 class="modal-title">Approve Deposit</h4></div>
                        <div class="modal-body">Are you sure you want to APPROVE this deposit? This action cannot be reversed.</div>
                        <div class="modal-footer">
                            <input name="process" type="submit" class="btn btn-success" value="Complete Deposit">
                            <button type="submit" name="close" onClick="window.close();" data-dismiss="modal" class="btn btn-danger">Close!</button>
                        </div>
                    </div>
                </div>
            </div>

            <div id="confirm-deposit-decline" tabindex="-1" role="dialog" aria-hidden="true" class="modal fade">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <button type="button" data-dismiss="modal" aria-hidden="true"
                                class="close">&times;</button>
                            <h4 class="modal-title">Decline Deposit</h4></div>
                        <div class="modal-body">Are you sure you want to DECLINE this deposit? This action cannot be reversed.</div>
                        <div class="modal-footer">
                            <input name="process" type="submit" class="btn btn-success" value="Decline Deposit">
                            <button type="submit" name="close" onClick="window.close();" data-dismiss="modal" class="btn btn-danger">Close !</button>
                        </div>
                    </div>
                </div>
            </div>

            <div id="confirm-deposit-pend" tabindex="-1" role="dialog" aria-hidden="true" class="modal fade">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <button type="button" data-dismiss="modal" aria-hidden="true"
                                class="close">&times;</button>
                            <h4 class="modal-title">Pend Deposit</h4></div>
                        <div class="modal-body">Are you sure you want to PEND this deposit? This action cannot be reversed.</div>
                        <div class="modal-footer">
                            <input name="process" type="submit" class="btn btn-success" value="Pend Deposit">
                            <button type="submit" name="close" onClick="window.close();" data-dismiss="modal" class="btn btn-danger">Close !</button>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>

    <div class="col-sm-6">
        <h5>Admin Remarks</h5>
        <div style="max-height: 550px; overflow: scroll;">
            <?php
            if(isset($trans_remark) && !empty($trans_remark)) {
                foreach ($trans_remark as $row) {
                    ?>
                    <div class="row">
                        <div class="col-sm-12">
                            <div class="transaction-remarks">
                                <span id="trans_remark_author"><?php echo $row['admin_full_name']; ?></span>
                                <span id="trans_remark"><?php echo $row['comment']; ?></span>
                                <span id="trans_remark_date"><?php echo datetime_to_text($row['created']); ?></span>
                            </div>
                        </div>
                    </div>
                <?php } } else { ?>
                <div class="row">
                    <div class="col-sm-12">
                        <div class="transaction-remarks">
                            <span class="text-danger"><em>There is no remark to display.</em></span>
                        </div>
                    </div>
                </div>
            <?php } ?>
        </div>
        <?php $transaction_issue = $admin_object->get_transaction_issue($trans_id);
        foreach($transaction_issue as $row2) {
            if($row2['status'] == '0'){?>
                <h5>OPERATIONS LOG COMMENTS</h5>
                <div class="row" style="margin: 15px;">
                    <div class="col-sm-4"><strong>Issue Status</strong></div>
                    <div class="col-sm-8"> <?php echo status_operations($row2['status']); ?><?php if($row2['status'] == 1){ echo " on ".date_to_text($row2['date_closed']);}?></div>
                </div>

                <div class="row" style="margin: 15px;">
                    <div class="col-sm-4"><strong>Created By</strong></div>
                    <div class="col-sm-8"> <?php
                        $admin_code = $row2['admin'];
                        $destination_details = $obj_facility->get_admin_detail_by_code($admin_code);
                        $admin_name = $destination_details['first_name'];
                        $admin_lname = $destination_details['last_name'];
                        echo $admin_name . " " . $admin_lname;?></div>
                </div>

                <div class="row" style="margin: 15px;">
                    <div class="col-sm-4"><strong>Date Created</strong></div>
                    <div class="col-sm-8"><?php echo datetime_to_text($row2['date']); ?></div>
                </div>

                <div class="row" style="margin: 15px;">
                    <div class="col-sm-4"><strong>Issue Discription</strong></div>
                    <div class="col-sm-8"> <?php echo $row2['details'];?></div>
                </div>
                <div id="results" style="height: 150px; overflow: auto;">
                    <?php
                    $comments_details = $admin_object->get_comment_details( $trans_id );
                    if(!empty($comments_details)){
                        foreach($comments_details as $row3) { ?>
                            <div class="transaction-remarks">
                        <span id="trans_remark_author"><?php
                            $admin_code = $row3['admin_code'];
                            $destination_details = $obj_facility->get_admin_detail_by_code($admin_code);
                            $admin_name = $destination_details['first_name'];
                            $admin_lname = $destination_details['last_name'];
                            echo $admin_name . " " . $admin_lname;?></span>
                                <span id="trans_remark"><?php echo $row3['comment']; ?></span>
                                <span id="trans_remark_date"><?php echo datetime_to_text($row3['created']); ?></span>
                            </div>
                        <?php }} else{ ?> <img class="img-responsive" src="../images/No-Comments.png" /> <?php } ?>
                </div>
                <form id="myForm" method="post" data-toggle="validator" class="form-vertical" role="form" enctype="multipart/form-data">
                    <input id="admin" name="admin" type="hidden" value="<?php echo $admin_code;?>" required>
                    <input id="trans_id" name="trans_id" type="hidden" value="<?php echo $trans_id; ?>" required>
                    <div class="form-group row">
                        <div class="col-sm-12">
                            <textarea id="comment" name="comment" class="form-control" rows="2" placeholder="Enter Detailed Description of Clients issue" required></textarea>
                        </div>
                    </div>
                    <input type="button" name="addcomment"  class="btn btn-warning" onclick="SubmitFormData();" value="Add New Comment"></input>
                </form>
            <?php }}?>
    </div>

</div>