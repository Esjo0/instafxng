<p><button onclick="history.go(-1);" class="btn btn-default" title="Go back to previous page"><i class="fa fa-arrow-circle-left"></i> Go Back!</button></p>
<p>Make your comment on the pending order below.</p>

<div class="row">
    <div class="col-sm-6">
        <div class="trans_item">
            <div class="trans_item_content">
                <div class="row">
                    <div class="col-sm-12 trans_item-thumb">
                        <?php
                        $refund_details = $client_operation->get_refund_details($trans_detail['trans_id']);
                        foreach($refund_details AS $refund) {
                            extract($refund);
                            ?>
                            <span id="transaction_identity"><strong>Refund Account Number : </strong><?php echo $refund_acct; ?></span>
                            <span><strong>Refund Type : </strong><?php echo refund_type($refund_type); ?></span>
                            <span><strong>Details : </strong><?php echo $issue_desc; ?></span>
                            <span><strong>Amount Deposited : </strong>&#8358;<?php echo $amount_paid; ?></span>
                            <span><strong>Payment method : </strong><?php echo status_user_deposit_pay_method($payment_method); ?></span>
                            <span><strong>Date Refund was Requested : </strong> <?php echo datetime_to_text($created); ?></span>
                            <hr>
                            <p>Client Account Details</p>
                            <span><strong>Clients Bank Name : </strong><?php echo $user_bank_name; ?></span>
                            <span><strong>Clients Bank Account Name : </strong><?php echo $user_acct_name; ?></span>
                            <span><strong>Clients Bank Account Number : </strong><?php echo $user_acct_no; ?></span>
                            <hr>
                            <p>Company Bank Account Details</p>
                            <span><strong>Clients Bank Name : </strong><?php echo $company_bank_name; ?></span>
                            <span><strong>Clients Bank Account Name : </strong><?php echo $company_acct_name; ?></span>
                            <span><strong>Clients Bank Account Number : </strong><?php echo $company_acct_no; ?></span>
                        <?php
                        }
                        ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
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
        <form  data-toggle="validator" role="form" method="post" action="">
            <input type="hidden" class="form-control" id="client_id" name="transaction_id" value="<?php echo $trans_id; ?>">

            <?php if($transaction_access['status']): ?>
            <div class="form-group">
                <label class="control-label" for="remarks">Your Remark:</label>
                <div><textarea name="remarks" id="message" rows="3" class="form-control" placeholder="Enter your remark" required></textarea></div>
            </div>
            <div class="form-group">
                <button type="button" data-target="#save-comment" data-toggle="modal" class="btn btn-success">Save Comment</button>
            </div>
            <?php endif; ?>


            <!--Modal - confirmation boxes-->
            <div id="save-comment" tabindex="-1" role="dialog" aria-hidden="true" class="modal fade">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <button type="button" data-dismiss="modal" aria-hidden="true"
                                    class="close">&times;</button>
                            <h4 class="modal-title">Save Comment</h4></div>
                        <div class="modal-body">Do you want to save the information?</div>
                        <div class="modal-footer">
                            <input name="pending_refund" type="submit" class="btn btn-primary" value="PEND REFUND">
                            <input name="approve_refund" type="submit" class="btn btn-success" value="APPROVE REFUND">
                            <button type="submit" name="close" onClick="window.close();" data-dismiss="modal" class="btn btn-danger">Close!</button>
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