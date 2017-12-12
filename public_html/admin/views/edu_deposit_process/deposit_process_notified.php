<p><a href="<?php if (isset($return_page)) { echo $return_page; } ?>" class="btn btn-default" title="Go back to Education Deposits"><i class="fa fa-arrow-circle-left"></i> Go Back - Education Deposits</a></p>

<p>See details of deposit transactions for Education below, once confirmed, client will be able
    to access the course paid for.</p>

<div class="row">

    <div class="col-sm-7">
        <form data-toggle="validator" class="form-horizontal" role="form" method="post" action="">

            <input name="transaction_no" type="hidden" value="<?php if (isset($trans_id)) { echo encrypt($trans_id); } ?>">
            <input name="user_no" type="hidden" value="<?php if (isset($trans_detail['user_code'])) { echo encrypt($trans_detail['user_code']); } ?>">
            <input name="course_no" type="hidden"
                   value="<?php if (isset($trans_detail['edu_course_id'])) {
                       echo encrypt($trans_detail['edu_course_id']);
                   } ?>">

            <div class="form-group">
                <label class="control-label col-sm-4" for="transaction_id">Transaction ID:</label>
                <div class="col-sm-8">
                    <input name="transaction_id" type="text" class="form-control" id="transaction_id"
                           value="<?php if (isset($trans_id)) {
                               echo $trans_id;
                           } ?>" readonly="readonly">
                </div>
            </div>

            <div class="form-group">
                <label class="control-label col-sm-4" for="course_name">Course:</label>
                <div class="col-sm-8">
                    <input name="course_name" type="text" class="form-control" id="course_name"
                       value="<?php if (isset($trans_detail['title'])) {
                           echo $trans_detail['title'];
                       } ?>" readonly="readonly">
                </div>
            </div>
            <div class="form-group">
                <label class="control-label col-sm-4" for="client_name">Client Name:</label>
                <div class="col-sm-8">
                    <input name="client_name" type="text" class="form-control" id="client_name"
                       value="<?php if (isset($trans_detail['client_full_name'])) {
                           echo $trans_detail['client_full_name'];
                       } ?>" readonly="readonly">
                </div>
            </div>
            <div class="form-group">
                <label class="control-label col-sm-4" for="client_phone">Phone:</label>
                <div class="col-sm-8">
                    <input name="client_phone" type="text" class="form-control" id="client_phone" value="<?php if (isset($trans_detail['phone'])) { echo $trans_detail['phone']; } ?>" readonly="readonly">
                </div>
            </div>
            <div class="form-group">
                <label class="control-label col-sm-4" for="client_email">Email Address:</label>
                <div class="col-sm-8">
                    <input name="client_email" type="text" class="form-control" id="client_email" value="<?php if (isset($trans_detail['email'])) { echo $trans_detail['email']; } ?>" readonly="readonly">
                </div>
            </div>
            <div class="form-group">
                <label class="control-label col-sm-4" for="camount">Course Cost
                    (&#8358;):</label>
                <div class="col-sm-8">
                    <input name="camount" type="text" class="form-control" id="camount"
                           value="<?php if (isset($trans_detail['course_cost'])) {
                               echo number_format($trans_detail['course_cost'], 2, ".", ",");
                           } ?>" readonly="readonly">
                </div>
            </div>
            <hr/>
            <div class="form-group">
                <label class="control-label col-sm-4" for="total_payable">Total Payable:</label>
                <div class="col-sm-8">
                    <input name="total_payable" type="hidden" class="form-control"
                           id="total_payable" value="<?php if (isset($total_payable)) {
                        echo number_format($total_payable, 2, ".", ",");
                    } ?>" readonly="readonly">
                    <p style="font-size: 1.6em; padding: 0; color: green;"><strong>
                            &#8358; <?php if (isset($total_payable)) {
                                echo number_format($total_payable, 2, ".", ",");
                            } ?></strong></p>
                </div>
            </div>
            <div class="form-group">
                <label class="control-label col-sm-4" for="deposit_status">Choose
                    Status:</label>
                <div class="col-sm-8">
                    <div class="radio"><label><input name="deposit_status" type="radio" value="3"> Confirmed</label></div>
                    <div class="radio"><label><input name="deposit_status" type="radio" value="4"> Declined</label></div>
                </div>
            </div>
            <div class="form-group">
                <label class="control-label col-sm-4" for="admin_comment">Comment:</label>
                <div class="col-sm-8"><textarea name="admin_comment" class="form-control" rows="7" id="admin_comment"></textarea></div>
            </div>

            <div class="form-group">
                <div class="col-sm-offset-4 col-sm-8"><input name="edu_deposit_process_notified" type="submit" class="btn btn-success" value="Process Deposit"/></div>
            </div>
        </form>
    </div>

    <div class="col-sm-5">
        <h5>Admin Remarks</h5>
        <div style="max-height: 550px; overflow: scroll;">
            <?php
            if (isset($trans_remark) && !empty($trans_remark)) {
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
                <?php }
            } else { ?>
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