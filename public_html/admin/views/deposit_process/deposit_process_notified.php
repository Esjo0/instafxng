<p><button onclick="history.go(-1);" class="btn btn-default" title="Go back to previous page"><i class="fa fa-arrow-circle-left"></i> Go Back!</button></p>
<p>Make Modification to this order below.</p>
<p>Fill the actual amount paid for the order as confirmed with the bank, add your remark, then process this transaction. Please note that
you must enter a remark for this transaction.</p>
<div class="row">
    <div class="col-sm-6">
        <div class="trans_item">
            <div class="trans_item_content">
                <div class="row">
                    <div class="col-sm-4 trans_item-thumb">
                        <?php
                        if(!empty($trans_detail['passport'])) { $file_location = "../userfiles/" . $trans_detail['passport']; }

                        if(file_exists($file_location)) {
                            ?>
                            <img src="<?php echo $file_location; ?>" alt="" class="img-responsive">
                        <?php } else { ?>
                            <img src="../images/placeholder.jpg" alt="" class="img-responsive">
                        <?php } ?>

                        <?php if($client_operation->account_flagged($trans_detail['ifxaccount_id'])) { ?>
                            <img src="../images/red-flag.png" alt="" title="The account number associated with this transaction is flagged.">
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
                        <span><strong>Date: </strong><?php echo datetime_to_text($trans_detail['deposit_created']); ?></span>
                        <span><strong>Account:</strong> <?php echo $trans_detail['ifx_acct_no']; ?></span>
                        <hr/>
                        <span><strong>Paid:</strong> &#8358; <?php echo number_format($trans_detail['client_naira_notified'], 2, ".", ","); ?></span>
                        <span><strong>Date:</strong> <?php if(!is_null($trans_detail['client_pay_date'])) { echo date_to_text($trans_detail['client_pay_date']); } ?></span>
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
                        <p style="text-align: right">
                            <button type="button" data-target="#reply-client-comment" data-toggle="modal" class="btn btn-default">Reply Comment</button>
                        </p>
                        
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
            <input type="hidden" class="form-control" id="client_id" name="transaction_id" value="<?php echo $trans_id; ?>">
            <?php if(!empty($trans_detail['points_claimed_id'])) { ?>
            <input type="hidden" name="points_claimed_id" value="<?php echo $trans_detail['points_claimed_id']; ?>" />
            <?php } ?>
            <div class="form-group">
                <label class="control-label text-danger" for="realamtpaid">Actual Amount Paid (&#8358;):</label>
                <div>
                    <input type="text" class="form-control" id="realamtpaid" name="realamtpaid" value=""  onchange="showdolval(this.value,<?php echo $trans_detail['exchange_rate']; ?>,<?php echo $trans_detail['deposit_origin']; ?>);" onFocus="showdolval(this.value,<?php echo $trans_detail['exchange_rate']; ?>,<?php echo $trans_detail['deposit_origin']; ?>)" onBlur="showdolval(this.value,<?php echo $trans_detail['exchange_rate']; ?>,<?php echo $trans_detail['deposit_origin']; ?>)" />
                </div>
            </div>
            <div class="form-group">
                <label class="control-label text-danger" for="realDolVal">Real Dollar Value (&#36;):</label>
                <div id="realDol"> </div>
            </div>
            <div class="form-group">
                <label class="control-label" for="remarks">Your Remark:</label>
                <div><textarea name="remarks" id="message" rows="3" class="form-control" placeholder="Enter your remark" required></textarea></div>
            </div>
            <div class="form-group">
                <button type="button" data-target="#confirm-deposit-approve" data-toggle="modal" class="btn btn-success">Confirm Deposit</button>
                <button type="button" data-target="#confirm-deposit-decline" data-toggle="modal" class="btn btn-danger">Decline Deposit</button>
                <button type="button" data-target="#confirm-deposit-pend" data-toggle="modal" class="btn btn-info">Pend Deposit</button>
            </div>

             <!--Modal - confirmation boxes--> 
            <div id="confirm-deposit-approve" tabindex="-1" role="dialog" aria-hidden="true" class="modal fade">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <button type="button" data-dismiss="modal" aria-hidden="true"
                                class="close">&times;</button>
                            <h4 class="modal-title">Approve Deposit</h4></div>
                        <div class="modal-body">Are you sure you want to CONFIRM this deposit? This action cannot be reversed.</div>
                        <div class="modal-footer">
                            <input name="process" type="submit" class="btn btn-success" value="Confirm Deposit">
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
    </div>

</div>