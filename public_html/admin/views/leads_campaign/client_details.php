<?php
$user_code = $db_handle->sanitizePost($user_code);
$user_detail = $client_operation->get_user_by_user_code($user_code);

if($user_detail) {

    // Get client education log history
    $client_education_history = $education_object->get_client_lesson_history($user_code);

    extract($user_detail);

    if ($middle_name) {
        $full_name = $last_name . ' ' . $middle_name . ' ' . $first_name;
    } else {
        $full_name = $last_name . ' ' . $first_name;
    }

    $total_client_account = $db_handle->numRows("SELECT ifx_acct_no FROM user_ifxaccount WHERE user_code = '$user_code'");
    $client_ilpr_account = $client_operation->get_client_ilpr_accounts_by_code($user_code);
    $client_non_ilpr_account = $client_operation->get_client_non_ilpr_accounts_by_code($user_code);
    $client_address = $client_operation->get_user_address_by_code($user_code);
    $client_verification = $client_operation->get_client_verification_status($user_code);
    $client_credential = $client_operation->get_user_credential($user_code);
    $client_bank_account = $client_operation->get_user_bank_account($user_code);
    $client_phone_code = $client_operation->get_user_phonecode($user_code);

    $client_flags = $client_operation->get_client_flag_by_code($user_code);

    $total_point = $client_operation->get_loyalty_point_earned($user_code);
    $total_point_claimed = $client_operation->get_loyalty_point_claimed($user_code);
    $total_point_frozen = $client_operation->get_loyalty_point_frozen($user_code);
    $loyalty_point_balance = $total_point - ($total_point_claimed + $total_point_frozen);

    $client_point_details = $obj_loyalty_point->get_user_point_details($user_code);

    $selected_point_frozen_trans_id = $client_operation->get_loyalty_point_frozen_transaction($user_code);

    $last_trade_date = $client_operation->get_last_trade_detail($user_code)['date_earned'];

    switch ($client_verification) {
        case '0':
            $verification_level = "Not Verified";
            break;
        case '1':
            $verification_level = "Level 1 Verified";
            break;
        case '2':
            $verification_level = "Level 2 Verified";
            break;
        case '3':
            $verification_level = "Level 3 Verified";
            break;
    }
}

// GET LATEST TRANSACTIONS
$latest_funding = $system_object->get_latest_funding($user_code);

// GET LATEST WITHDRAWALS
$latest_withdrawal = $system_object->get_latest_withdrawal($user_code);
?>

<div class="row">
    <div class="col-sm-12">
        <?php require_once 'layouts/feedback_message.php'; ?>
        <p><a href="<?php echo $_SERVER['HTTP_REFERER']; ?>" class="btn btn-default" title="Go Back"><i class="fa fa-arrow-circle-left"></i> Go Back</a></p>
        <p>Below is the detail of the selected client. <strong>Note:</strong> If this client has an account that is flagged, it will display in red.</p>

        <!------------- Contact Section --->
        <div class="row">
            <div class="col-sm-6">
                <h5>Client Information</h5>
                <span class="span-title text-right">Account Officer</span>
                <p class="text-right"><em><?php echo $account_officer_full_name; ?></em></p>
                <span class="span-title">Client Name</span>
                <p><em><?php echo $full_name; ?></em>&nbsp;&nbsp;
                    <?php if($client_operation->account_flagged($user_code)) { ?>
                        <img src="../images/red-flag.png" alt="" title="This client has an account flagged."> -
                        <span class="text-danger"> Scroll down for flag details</span>
                    <?php } ?>
                </p>

                <span class="span-title">Email Address</span>
                <p><em><?php echo $email; ?></em></p>
                <span class="span-title">Phone Number</span>
                <p><em><?php echo $phone; ?></em></p>
                <span class="span-title">Opening Date</span>
                <p><em><?php echo datetime_to_text2($created); ?></em></p>
                <span class="span-title">Client Address</span>
                <p><em><?php echo $client_address['address'] . ' ' . $client_address['address2'] . ' ' . $client_address['city'] . ' ' . $client_address['state']; ?></em></p>
                <span class="span-title">Client SMS Code</span>
                <p>Code: <?php echo $client_phone_code['phone_code']; ?> &nbsp;&nbsp; Status: <?php echo phone_code_status($client_phone_code['phone_status']); ?></p>
                <span class="span-title">Verification Status</span>
                <p><?php echo $verification_level; ?></p>
            </div>

            <div class="col-sm-6">
                <div class="row">
                    <div class="col-sm-4 text-center" style="margin-bottom: 4px !important">
                        <span>Identity Card</span>
                        <?php if(!empty($client_credential['idcard'])) { ?>
                            <a href="../userfiles/<?php echo $client_credential['idcard']; ?>" title="click to enlarge" target="_blank"><img src="../userfiles/<?php echo $client_credential['idcard']; ?>" width="120px" height="120px"/></a>
                        <?php } else { ?>
                            <img src="../images/placeholder.jpg" alt="" class="img-responsive center-block">
                        <?php } ?>
                        <a href="" data-toggle="modal" data-target="#myModalCard" class="btn btn-default" style="margin-top: 2px !important">View</a>
                    </div>
                    <div class="col-sm-4 text-center" style="margin-bottom: 4px !important">
                        <span>Passport</span>
                        <?php if(!empty($client_credential['passport'])) { ?>
                            <a href="../userfiles/<?php echo $client_credential['passport']; ?>" title="click to enlarge" target="_blank"><img src="../userfiles/<?php echo $client_credential['passport']; ?>" width="120px" height="120px"/></a>
                        <?php } else { ?>
                            <img src="../images/placeholder.jpg" alt="" class="img-responsive center-block">
                        <?php } ?>
                        <a href="" data-toggle="modal" data-target="#myModalPass" class="btn btn-default" style="margin-top: 2px !important">View</a>
                    </div>
                    <div class="col-sm-4 text-center" style="margin-bottom: 4px !important">
                        <span>Signature</span>
                        <?php if(!empty($client_credential['signature'])) { ?>
                            <a href="../userfiles/<?php echo $client_credential['signature']; ?>" title="click to enlarge" target="_blank"><img src="../userfiles/<?php echo $client_credential['signature']; ?>" width="120px" height="120px"/></a>
                        <?php } else { ?>
                            <img src="../images/placeholder.jpg" alt="" class="img-responsive center-block">
                        <?php } ?>
                        <a href="" data-toggle="modal" data-target="#myModalSign" class="btn btn-default" style="margin-top: 2px !important">View</a>
                    </div>
                </div>

                <div class="row">
                    <div class="col-sm-12">
                        <p><strong>Loyalty Points: </strong> Total Earned is <?php if(!empty($total_point)) { echo $total_point; } else { echo '0.00'; } ?></p>
                        <table class="table table-responsive table-striped table-bordered table-hover">
                            <thead>
                            <tr>
                                <th>Claimed</th>
                                <th>Held</th>
                                <th>Balance</th>
                                <th>Expired</th>
                            </tr>
                            </thead>
                            <tbody>
                            <tr>
                                <td><?php if(!empty($total_point_claimed)) { echo $total_point_claimed; } else { echo '0.00'; } ?></td>
                                <td><?php if(!empty($total_point_frozen)) { echo $total_point_frozen; } else { echo '0.00'; } ?></td>
                                <td><?php if(!empty($client_point_details)) { echo $client_point_details['point_balance']; } else { echo '0.00'; } ?></td>
                                <td><?php if(!empty($client_point_details)) { echo $client_point_details['expired_point']; } else { echo '0.00'; } ?></td>
                            </tr>
                            <tr>
                                <td colspan="4">
                                    Held Transactions:
                                    <?php if(!empty($selected_point_frozen_trans_id)) {
                                        $count = 1;
                                        foreach($selected_point_frozen_trans_id as $value) {
                                            $trans_id = $value['trans_id'];
                                            if($count < count($selected_point_frozen_trans_id)) { $trans_id = $trans_id . ", "; }
                                            echo $trans_id;
                                            $count++;
                                        } } else { echo 'Nil'; }
                                    ?>
                                </td>
                            </tr>
                            </tbody>
                        </table>
                        <hr />

                        <p><strong>Total IFX Accounts: </strong><?php echo number_format($total_client_account); ?></p>
                        <table class="table table-responsive table-striped table-bordered table-hover">
                            <thead>
                            <tr>
                                <th>ILPR Accounts: <?php echo count($client_ilpr_account); ?></th>
                            </tr>
                            </thead>
                            <tbody>

                            <?php if($client_ilpr_account) { ?>
                                <tr>
                                    <td>
                                        <?php
                                        $count = 1;
                                        foreach($client_ilpr_account as $key => $value) {
                                            $ifx_acct_no = $value['ifx_acct_no'];
                                            if($count < count($client_ilpr_account)) { $ifx_acct_no = $ifx_acct_no . ", "; }
                                            echo $ifx_acct_no;
                                            $count++;
                                        }
                                        ?>
                                    </td>
                                </tr>
                            <?php } else { ?>
                                <tr><td colspan='' class='text-danger'><em>No results to display</em></td></tr>
                            <?php } ?>

                            </tbody>
                        </table>

                        <table class="table table-responsive table-striped table-bordered table-hover">
                            <thead>
                            <tr>
                                <th>Non - ILPR Accounts: <?php echo count($client_non_ilpr_account); ?></th>
                            </tr>
                            </thead>
                            <tbody>

                            <?php if($client_non_ilpr_account) { ?>
                                <tr>
                                    <td>
                                        <?php
                                        $count = 1;
                                        foreach($client_non_ilpr_account as $key => $value) {
                                            $ifx_acct_no = $value['ifx_acct_no'];
                                            if($count < count($client_non_ilpr_account)) { $ifx_acct_no = $ifx_acct_no . ", "; }
                                            echo $ifx_acct_no;
                                            $count++;
                                        }
                                        ?>
                                    </td>
                                </tr>
                            <?php } else { ?>
                                <tr><td colspan='' class='text-danger'><em>No results to display</em></td></tr>
                            <?php } ?>

                            </tbody>
                        </table>

                        <p><strong>Last Trading Date: </strong> <?php if($last_trade_date) { echo datetime_to_text2($last_trade_date); } else { echo 'Nil'; } ?></p>
                    </div>
                </div>
            </div>
        </div><!------- End Contact section ----->

        <hr />

        <div class="row">
            <div class="col-sm-12">
                <h5>Bank Account Detail</h5>
                <table class="table table-responsive table-striped table-bordered table-hover">
                    <thead>
                    <tr>
                        <th>Bank Name</th>
                        <th>Account Name</th>
                        <th>Account Number</th>
                    </tr>
                    </thead>
                    <tbody>
                    <?php if($client_bank_account['client_acct_no']) { ?>
                        <tr>
                            <td><?php echo $client_bank_account['client_bank_name']; ?></td>
                            <td><?php echo $client_bank_account['client_acct_name']; ?></td>
                            <td><?php echo $client_bank_account['client_acct_no']; ?></td>
                        </tr>
                    <?php } else { ?>
                        <tr><td colspan='3' class='text-danger'><em>No results to display</em></td></tr>
                    <?php } ?>
                    </tbody>
                </table>
                <hr />

                <h5>Account Flag Detail</h5>
                <table class="table table-responsive table-striped table-bordered table-hover">
                    <thead>
                    <tr>
                        <th>Date</th>
                        <th>Account Number</th>
                        <th>Admin</th>
                        <th>Comment</th>
                    </tr>
                    </thead>
                    <tbody>
                    <?php if($client_flags) { foreach($client_flags as $value) { ?>
                        <tr>
                            <td><?php echo datetime_to_text2($value['created']); ?></td>
                            <td><?php echo $value['ifx_acct_no']; ?></td>
                            <td><?php echo $value['admin_full_name']; ?></td>
                            <td><?php echo $value['comment']; ?></td>
                        </tr>
                    <?php } } else { ?>
                        <tr><td colspan='4' class='text-danger'><em>No results to display</em></td></tr>
                    <?php } ?>
                    </tbody>
                </table>

                <h5>Recent Transactions</h5>
                <ul class="nav nav-tabs">
                    <li class="active"><a data-toggle="tab" href="#latest_funding">Deposit</a></li>
                    <li><a data-toggle="tab" href="#latest_withdrawal">Withdrawal</a></li>
                </ul>
                <div class="tab-content">
                    <div id="latest_funding" class="tab-pane fade in active">
                        <table class="table table-responsive table-striped table-bordered table-hover">
                            <thead>
                            <tr>
                                <th>Trans ID</th>
                                <th>Acct No</th>
                                <th>Amount Ordered</th>
                                <th>Status</th>
                                <th>Date</th>
                            </tr>
                            </thead>
                            <tbody>
                            <?php if(isset($latest_funding) && !empty($latest_funding)) { foreach ($latest_funding as $row) { ?>
                                <tr>
                                    <td><a target="_blank" title="View" href="deposit_search_view.php?id=<?php echo dec_enc('encrypt', $row['trans_id']); ?>"><?php echo $row['trans_id']; ?></a></td>
                                    <td><?php echo $row['ifx_acct_no']; ?></td>
                                    <td>&dollar; <?php echo $row['dollar_ordered']; ?></td>
                                    <td><?php echo status_user_deposit($row['status']); ?></td>
                                    <td><?php echo datetime_to_text($row['created']); ?></td>
                                </tr>
                            <?php } } else { echo "<tr><td colspan='5' class='text-danger'><em>No results to display</em></td></tr>"; } ?>
                            </tbody>
                        </table>
                    </div>
                    <div id="latest_withdrawal" class="tab-pane fade">
                        <table class="table table-responsive table-striped table-bordered table-hover">
                            <thead>
                            <tr>
                                <th>Trans ID</th>
                                <th>Acct No</th>
                                <th>Amount Ordered</th>
                                <th>Status</th>
                                <th>Date</th>
                            </tr>
                            </thead>
                            <tbody>
                            <?php if(isset($latest_withdrawal) && !empty($latest_withdrawal)) { foreach ($latest_withdrawal as $row) { ?>
                                <tr>
                                    <td><a target="_blank" title="View" href="withdrawal_search_view.php?id=<?php echo dec_enc('encrypt', $row['trans_id']); ?>"><?php echo $row['trans_id']; ?></a></td>
                                    <td><?php echo $row['ifx_acct_no']; ?></td>
                                    <td>&dollar; <?php echo $row['dollar_withdraw']; ?></td>
                                    <td><?php echo status_user_withdrawal($row['status']); ?></td>
                                    <td><?php echo datetime_to_text($row['created']); ?></td>
                                </tr>
                            <?php } } else { echo "<tr><td colspan='5' class='text-danger'><em>No results to display</em></td></tr>"; } ?>
                            </tbody>
                        </table>
                    </div>
                </div>

            </div>
        </div>

        <div class="row">
            <div class="col-sm-12">
                <!-- Display information about the client Education career here -->

                <br />
                <h5>Education History</h5>
                <table class="table table-responsive table-striped table-bordered table-hover">
                    <thead>
                    <tr>
                        <th>Date</th>
                        <th>Course</th>
                        <th>Lesson</th>
                    </tr>
                    </thead>
                    <tbody>
                    <?php if(isset($client_education_history) && !empty($client_education_history)) { foreach ($client_education_history as $row) { ?>
                        <tr>
                            <td><?php echo datetime_to_text($row['lesson_log_date']); ?></td>
                            <td><?php echo $row['course_title']; ?></td>
                            <td><?php echo $row['lesson_title']; ?></td>
                        </tr>
                    <?php } } else { echo "<tr><td colspan='3' class='text-danger'><em>No results to display</em></td></tr>"; } ?>
                    </tbody>
                </table>

            </div>
        </div>

    </div>
</div>

<!-- Modal -->
<div id="myModalPass" class="modal fade" role="dialog">
    <div class="modal-dialog">
        <!-- Modal content-->
        <div class="modal-content">
            <div class="modal-body">
                <?php if(!empty($client_credential['passport'])) { ?>
                    <a href="../userfiles/<?php echo $client_credential['passport']; ?>" title="click to enlarge" target="_blank"><img src="../userfiles/<?php echo $client_credential['passport']; ?>" class="img-responsive center-block"/></a>
                <?php } else { ?>
                    <img src="../images/placeholder.jpg" alt="" class="img-responsive center-block">
                <?php } ?>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal" title="Close">Close</button>
            </div>
        </div>
    </div>
</div>

<div id="myModalCard" class="modal fade" role="dialog">
    <div class="modal-dialog">
        <!-- Modal content-->
        <div class="modal-content">
            <div class="modal-body text-center">
                <?php if(!empty($client_credential['idcard'])) { ?>
                    <a href="../userfiles/<?php echo $client_credential['idcard']; ?>" title="click to enlarge" target="_blank"><img src="../userfiles/<?php echo $client_credential['idcard']; ?>" class="img-responsive center-block"/></a>
                <?php } else { ?>
                    <img src="../images/placeholder.jpg" alt="" class="img-responsive center-block">
                <?php } ?>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal" title="Close">Close</button>
            </div>
        </div>
    </div>
</div>

<div id="myModalSign" class="modal fade" role="dialog">
    <div class="modal-dialog">
        <!-- Modal content-->
        <div class="modal-content">
            <div class="modal-body text-center">
                <?php if(!empty($client_credential['signature'])) { ?>
                    <a href="../userfiles/<?php echo $client_credential['signature']; ?>" title="click to enlarge" target="_blank"><img src="../userfiles/<?php echo $client_credential['signature']; ?>" class="img-responsive center-block"/></a>
                <?php } else { ?>
                    <img src="../images/placeholder.jpg" alt="" class="img-responsive center-block">
                <?php } ?>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal" title="Close">Close</button>
            </div>
        </div>
    </div>
</div>
