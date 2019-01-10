<?php $user_bank_account = $client_operation->get_user_bank_account($trans_detail['user_code']); ?>
<p><button onclick="history.go(-1);" class="btn btn-default" title="Go back to previous page"><i class="fa fa-arrow-circle-left"></i> Go Back!</button></p>
<p>Make Modification to this order below. You are to make payment to the client's bank account.</p>
<div class="row">
    <div class="col-sm-6">
        <div class="trans_item">
            <div class="trans_item_content">
                <div class="row">
                    <div class="col-sm-4 trans_item-thumb">
                        <p class="text-center"><a target="_blank" title="View Client Profile" class="btn btn-info" href="client_detail.php?id=<?php echo dec_enc('encrypt', $trans_detail['user_code']); ?>"><i class="glyphicon glyphicon-eye-open icon-white"></i> </a></p>
                        <?php
                        if(!empty($trans_detail['passport'])) {
                            $file_location = "../userfiles/" . $trans_detail['passport'];
                        }

                        if(file_exists($file_location)) {
                            ?>
                            <img src="<?php echo $file_location; ?>" alt="" height="120px" width="120px" />
                        <?php } else { ?>
                            <img src="../images/placeholder.jpg" alt="" class="img-responsive">
                        <?php } unset($file_location); // so that it will not remember for someone without passport ?>

                        <?php if($client_operation->account_flagged($trans_detail['user_code'])) { ?>
                            <p><img class="center-block" src="../images/red-flag.png" alt="" title="The account number associated with this transaction is flagged."></p>
                        <?php } ?>
                    </div>
                    <div class="col-sm-8 ">
                        <div class="row">
                            <div class="col-xs-12 trans_item_bio">
                                <span id="bio_name"><?php echo $trans_detail['full_name']; ?></span>
                                <span><?php echo $trans_detail['phone']; ?></span>
                                <span><?php echo $trans_detail['email']; ?></span>
                                <hr/>
                                <!--                                <span class="text-danger"><strong>Recent Transactions</strong></span>-->
                                <!--                                <span>## coming soon ##</span>-->
                                <span class="text-danger"><strong>IFX Accounts</strong></span>
                                <span>
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
                        <span><strong>Withdraw: </strong> &dollar; <?php echo $trans_detail['dollar_withdraw']; ?> - &#8358; <?php echo number_format($trans_detail['naira_total_withdrawable'], 2, ".", ","); ?></span>
                        <span><strong>Date: </strong><?php echo datetime_to_text($trans_detail['withdrawal_created']); ?></span>
                        <span><strong>Account: </strong> <?php echo $trans_detail['ifx_acct_no']; ?></span>
                        <hr/>
                        <span><strong>Bank Name: </strong><?php echo $user_bank_account['client_bank_name']; ?></span>
                        <span><strong>Account Name: </strong><?php echo $user_bank_account['client_acct_name']; ?></span>
                        <span><strong>Account Number: </strong><?php echo $user_bank_account['client_acct_no']; ?></span>
                        <span><strong>Amount To Pay: </strong>&#8358; <?php echo number_format($trans_detail['naira_total_withdrawable'], 2, ".", ","); ?></span>
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
                </div>
            </div>
        </div>
        <br/>
        <?php if($transaction_access['status']): ?>
        <form  data-toggle="validator" role="form" method="post" action="">
            <input type="hidden" class="form-control" id="client_id" name="transaction_id" value="<?php echo $trans_id; ?>">
            <div class="form-group">
                <label class="control-label" for="remarks">Your Remark:</label>
                <div><textarea name="remarks" id="message" rows="3" class="form-control" placeholder="Enter your remark" required></textarea></div>
            </div>
            <div class="form-group">
                <button type="button" data-target="#complete-payment-approve" data-toggle="modal" class="btn btn-success">Complete Payment</button>
                <button type="button" data-target="#complete-payment-decline" data-toggle="modal" class="btn btn-danger">Decline Payment</button>
                <button type="button" data-target="#complete-payment-pend" data-toggle="modal" class="btn btn-info">Pend Payment</button>
            </div>

            <!--Modal - confirmation boxes-->
            <div id="complete-payment-approve" tabindex="-1" role="dialog" aria-hidden="true" class="modal fade">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <button type="button" data-dismiss="modal" aria-hidden="true"
                                    class="close">&times;</button>
                            <h4 class="modal-title">Complete Payment</h4></div>
                        <div class="modal-body">Are you sure you want to COMPLETE this payment? This action cannot be reversed.</div>
                        <div class="modal-footer">
                            <input name="process" type="submit" class="btn btn-success" value="Complete Payment">
                            <button type="submit" name="close" onClick="window.close();" data-dismiss="modal" class="btn btn-danger">Close!</button>
                        </div>
                    </div>
                </div>
            </div>

            <div id="complete-payment-decline" tabindex="-1" role="dialog" aria-hidden="true" class="modal fade">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <button type="button" data-dismiss="modal" aria-hidden="true"
                                    class="close">&times;</button>
                            <h4 class="modal-title">Decline Payment</h4></div>
                        <div class="modal-body">Are you sure you want to DECLINE this payment? This action cannot be reversed.</div>
                        <div class="modal-footer">
                            <input name="process" type="submit" class="btn btn-success" value="Decline Payment">
                            <button type="submit" name="close" onClick="window.close();" data-dismiss="modal" class="btn btn-danger">Close !</button>
                        </div>
                    </div>
                </div>
            </div>

            <div id="complete-payment-pend" tabindex="-1" role="dialog" aria-hidden="true" class="modal fade">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <button type="button" data-dismiss="modal" aria-hidden="true"
                                    class="close">&times;</button>
                            <h4 class="modal-title">Pend Payment</h4></div>
                        <div class="modal-body">Are you sure you want to PEND this payment?</div>
                        <div class="modal-footer">
                            <input name="process" type="submit" class="btn btn-success" value="Pend Payment">
                            <button type="submit" name="close" onClick="window.close();" data-dismiss="modal" class="btn btn-danger">Close !</button>
                        </div>
                    </div>
                </div>
            </div>
        </form>
        <?php endif; ?>

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
    </div>

</div>