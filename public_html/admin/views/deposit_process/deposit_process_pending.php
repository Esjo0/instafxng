<p><button onclick="history.go(-1);" class="btn btn-default" title="Go back to previous page"><i class="fa fa-arrow-circle-left"></i> Go Back!</button></p>
<p>Make your comment on the pending order below.</p>

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
                                        <i title="Add this transaction to the Operations log" type="button" data-target="#add<?php echo $trans_id; ?>" data-toggle="modal" class="fa fa-plus-circle" style="color:red;" aria-hidden="true"> </i>
                                        Add to Operations Log<?php
                                    }?> </span>
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

                                    }else{
                                        $message_error = "This issue has been raised earlier!!";
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


            <div class="form-group">
                <label class="control-label" for="remarks">Your Remark:</label>
                <div><textarea name="remarks" id="message" rows="3" class="form-control" placeholder="Enter your remark" required></textarea></div>
            </div>
            <div class="form-group">
                <button type="button" data-target="#save-comment" data-toggle="modal" class="btn btn-success">Save Comment</button>
            </div>


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
                            <input name="process" type="submit" class="btn btn-success" value="Save Comment">
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