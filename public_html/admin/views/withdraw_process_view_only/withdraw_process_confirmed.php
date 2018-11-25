<!--<p><button onclick="history.go(-1);" class="btn btn-default" title="Go back to previous page"><i class="fa fa-arrow-circle-left"></i> Go Back!</button></p>
--><p>Make Modification to this order below. You are to debit the client's Instaforex account.</p>
<div class="row">
    <div class="col-sm-6">
        <div class="trans_item">
            <div class="trans_item_content">
                <div class="row">
                    <div class="col-sm-4 trans_item-thumb">
                        <!--<p class="text-center"><a target="_blank" title="View Client Profile" class="btn btn-info" href="client_detail.php?id=<?php /*echo encrypt_ssl($trans_detail['user_code']); */?>"><i class="glyphicon glyphicon-eye-open icon-white"></i> </a></p>
                        --><?php
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
                        <span><strong>Withdraw:</strong> &dollar; <?php echo $trans_detail['dollar_withdraw']; ?> - &#8358; <?php echo number_format($trans_detail['naira_total_withdrawable'], 2, ".", ","); ?></span>
                        <span><strong>Date: </strong><?php echo datetime_to_text($trans_detail['withdrawal_created']); ?></span>
                        <span><strong>Account:</strong> <?php echo $trans_detail['ifx_acct_no']; ?></span>
                        <hr/>
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
            <!--<div class="form-group">
                <label class="control-label" for="trans_ref">IFX Transfer Reference:</label>
                <div><textarea name="trans_ref" id="message" rows="3" class="form-control" placeholder="Enter IFX Transfer Reference"></textarea></div>
            </div>
            <div class="form-group">
                <label class="control-label" for="remarks">Your Remark:</label>
                <div><textarea name="remarks" id="message" rows="3" class="form-control" placeholder="Enter your remark" required></textarea></div>
            </div>
            <div class="form-group">
                <button type="button" data-target="#account-debit-approve" data-toggle="modal" class="btn btn-success">Confirm IFX Debit</button>
                <button type="button" data-target="#account-debit-decline" data-toggle="modal" class="btn btn-danger">Decline IFX Debit</button>
                <button type="button" data-target="#account-debit-pend" data-toggle="modal" class="btn btn-info">Pend IFX Debit</button>
            </div>-->

             <!--Modal - confirmation boxes--> 
            <div id="account-debit-approve" tabindex="-1" role="dialog" aria-hidden="true" class="modal fade">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <button type="button" data-dismiss="modal" aria-hidden="true"
                                class="close">&times;</button>
                            <h4 class="modal-title">Confirm IFX Debit</h4></div>
                        <div class="modal-body">Are you sure you want to CONFIRM this withdrawal? This action cannot be reversed.</div>
                        <div class="modal-footer">
                            <input name="process" type="submit" class="btn btn-success" value="Confirm IFX Debit">
                            <button type="submit" name="close" onClick="window.close();" data-dismiss="modal" class="btn btn-danger">Close!</button>
                        </div>
                    </div>
                </div>
            </div>

            <div id="account-debit-decline" tabindex="-1" role="dialog" aria-hidden="true" class="modal fade">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <button type="button" data-dismiss="modal" aria-hidden="true"
                                class="close">&times;</button>
                            <h4 class="modal-title">Decline IFX Debit</h4></div>
                        <div class="modal-body">Are you sure you want to DECLINE this withdrawal? This action cannot be reversed.</div>
                        <div class="modal-footer">
                            <input name="process" type="submit" class="btn btn-success" value="Decline IFX Debit">
                            <button type="submit" name="close" onClick="window.close();" data-dismiss="modal" class="btn btn-danger">Close !</button>
                        </div>
                    </div>
                </div>
            </div>

            <div id="account-debit-pend" tabindex="-1" role="dialog" aria-hidden="true" class="modal fade">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <button type="button" data-dismiss="modal" aria-hidden="true"
                                class="close">&times;</button>
                            <h4 class="modal-title">Pend IFX Debit</h4></div>
                        <div class="modal-body">Are you sure you want to PEND this withdrawal? This action cannot be reversed.</div>
                        <div class="modal-footer">
                            <input name="process" type="submit" class="btn btn-success" value="Pend IFX Debit">
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