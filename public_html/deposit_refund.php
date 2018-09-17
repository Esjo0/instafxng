<?php
require_once 'init/initialize_general.php';
$thisPage = "";

$page_requested = '';


?>
<!DOCTYPE html>
<html lang="en">
    <head>
        <base target="_self">
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>Instaforex Nigeria | Deposit refund</title>
        <meta name="title" content="Instaforex Nigeria | Client Document Verification" />
        <meta name="keywords" content=" ">
        <meta name="description" content=" ">
        <?php require_once 'layouts/head_meta.php'; ?>
        <script>
            function displayOptions() {
                var options = $("#refund_type").val();
                if(options == 1) {
                    document.getElementById("third_party_transaction").style.display = "none";
                    document.getElementById("wrong_remark").style.display = "none";
                    document.getElementById("tp_name").required = false;
                    document.getElementById("tp_email").required = false;
                    document.getElementById("tp_phone").required = false;
                    document.getElementById("wrong_remark_desc").required = false;
                }
                else if(options == 2) {
                    document.getElementById("third_party_transaction").style.display = "block";
                    document.getElementById("wrong_remark").style.display = "none";
                    document.getElementById("wrong_remark_desc").required = false;
                }
                else if(options == 3) {
                    document.getElementById("third_party_transaction").style.display = "none";
                    document.getElementById("wrong_remark").style.display = "block";
                    document.getElementById("tp_name").required = false;
                    document.getElementById("tp_email").required = false;
                    document.getElementById("tp_phone").required = false;
                }
            }
        </script>
    </head>
    <body>
        <?php require_once 'layouts/header.php'; ?>
        <!-- Main Body: The is the main content area of the web site, contains a side bar  -->
        <div id="main-body" class="container-fluid">
            <div class="row no-gutter">
               <?php require_once 'layouts/topnav.php'; ?>
                <!-- Main Body - Content Area: This is the main content area, unique for each page  -->
                <div id="main-body-content-area" class="col-md-8 col-md-push-4 col-lg-9 col-lg-push-3">
                    
                    <!-- Unique Page Content Starts Here
                    ================================================== -->
                    
                    <div class="section-tint super-shadow">
                        <div class="row">
                            <div class="col-sm-12 text-danger">
                                <h4><strong>Instafxng Client Deposit Refund.</strong></h4>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-sm-12">
                                <?php require_once 'layouts/feedback_message.php'; ?>

                                <ul class="fa-ul">
                                    <li><i class="fa-li fa fa-check-square-o icon-tune"></i>Enter all requested information to process your deposit refund</li>
                                </ul>

                                <form data-toggle="validator" class="form-horizontal" role="form" method="post" action="<?php echo $_SERVER['REQUEST_URI']; ?>">
                                    </hr>
                                    <div class="form-group">
                                        <label class="control-label col-sm-3" for="ifx_acct_no">Instaforex Account No.:</label>
                                        <div class="col-sm-9 col-lg-5">
                                            <input name="ifx_acct_no" type="text" class="form-control" id="ifx_acct_no" required>
                                        </div>
                                        <span class="help-block"><i class="fa fa-info-circle"></i> Instaforex Account Number</span>
                                    </div>

                                    <div class="form-group">
                                        <label class="control-label col-sm-3" for="ifx_acct_no">Select Refund type:</label>
                                        <div class="col-sm-9 col-lg-5">
                                            <select class="form-control" id="refund_type" onchange="displayOptions()">
                                                <option value="1">Omission of Transaction ID</option>
                                                <option value="2">Third Party Transaction</option>
                                                <option value="3">Wrong Remarks</option>
                                            </select>
                                            <span class="help-block"><i class="fa fa-info-circle"></i> Select Reason for Refund</span>
                                        </div>
                                    </div>

                                    <div id="third_party_transaction" style="display:none;">
                                        <hr>
                                        <p class="text-center"><u>Third Party Details<u></p>
                                        <div class="form-group">
                                            <label class="control-label col-sm-3" for="pass_code">Third Party's Name:</label>
                                            <div class="col-sm-9 col-lg-5">
                                                <input name="transaction_id" type="text" class="form-control" id="tp-name" required>
                                                <span class="help-block"><i class="fa fa-info-circle"></i>Name of the individual you paid with his/her account</span>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label class="control-label col-sm-3" for="pass_code">Third party's Email:</label>
                                            <div class="col-sm-9 col-lg-5">
                                                <input name="transaction_id" type="text" class="form-control" id="tp_email" required>
                                                <span class="help-block"><i class="fa fa-info-circle"></i>Enter the Email Address of Third party</span>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label class="control-label col-sm-3" for="pass_code">Third Party's Phone No.:</label>
                                            <div class="col-sm-9 col-lg-5">
                                                <input name="transaction_id" type="text" class="form-control" id="tp_phone" required>
                                                <span class="help-block"><i class="fa fa-info-circle"></i>Enter the Phone Number of Third Party</span>
                                            </div>
                                        </div>
                                        <hr>
                                    </div>
                                    <div id="wrong_remark" style="display:none;">
                                        <hr>
                                        <div class="form-group">
                                            <label class="control-label col-sm-3" for="pass_code">Enter Previous Wrong Remark:</label>
                                            <div class="col-sm-9 col-lg-5">
                                                <input name="transaction_id" type="text" class="form-control" id="wrong_remark" required>
                                                <span class="help-block"><i class="fa fa-info-circle"></i>Enter your previously wrong remark</span>
                                            </div>
                                        </div>
                                        <hr>
                                    </div>

                                    <div class="form-group">
                                        <label class="control-label col-sm-3" for="pass_code">Transaction ID:</label>
                                        <div class="col-sm-9 col-lg-5">
                                            <input name="transaction_id" type="text" class="form-control" id="" required>
                                            <span class="help-block"><i class="fa fa-info-circle"></i> Deposit Transaction details</span>
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <label class="control-label col-sm-3" for="pass_code">Amount paid in Naira:</label>
                                        <div class="col-sm-9 col-lg-5">
                                            (&#8358;)
                                            <input name="amount_paid" type="text" class="form-control" id="" required>
                                            <span class="help-block"><i class="fa fa-info-circle"></i> Deposit Transaction details</span>
                                        </div>
                                    </div>

                                    <hr>
                                    <p class="text-center"><u>Enter Your Bank Details</u></p>
                                    <div class="form-group">
                                        <label class="control-label col-sm-3" for="pass_code">Bank Name:</label>
                                        <div class="col-sm-9 col-lg-5">
                                            <input name="bank_name" type="text" class="form-control" id="" required>
                                            <span class="help-block"><i class="fa fa-info-circle"></i> Enter Your Bank Name</span>
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <label class="control-label col-sm-3" for="pass_code">Bank Account Name:</label>
                                        <div class="col-sm-9 col-lg-5">
                                            <input name="bank_acct_name" type="text" class="form-control" id="" required>
                                            <span class="help-block"><i class="fa fa-info-circle"></i> Enter Your Bank Account Name</span>
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <label class="control-label col-sm-3" for="pass_code">Bank Account Number:</label>
                                        <div class="col-sm-9 col-lg-5">
                                            <input name="bank_acct_name" type="text" class="form-control" id="" required>
                                            <span class="help-block"><i class="fa fa-info-circle"></i> Enter Your Bank Account Number</span>
                                        </div>
                                    </div>

                                    <hr>
                                    <p class="text-center"><u>Enter Details of the bank you paid into.</u></p>
                                    <div class="form-group">
                                        <label class="control-label col-sm-3" for="pass_code">Enter Payment Type:</label>
                                        <div class="col-sm-9 col-lg-5">
                                            <input name="company_bank_name" type="text" class="form-control" id="" required>
                                            <span class="help-block"><i class="fa fa-info-circle"></i> Enter the payment method you selected on instafxng.com</span>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="control-label col-sm-3" for="pass_code">Bank Name:</label>
                                        <div class="col-sm-9 col-lg-5">
                                            <input name="company_bank_name" type="text" class="form-control" id="" required>
                                            <span class="help-block"><i class="fa fa-info-circle"></i> Enter Bank Name</span>
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <label class="control-label col-sm-3" for="pass_code">Bank Account Name:</label>
                                        <div class="col-sm-9 col-lg-5">
                                            <input name="company_acct_name" type="text" class="form-control" id="" required>
                                            <span class="help-block"><i class="fa fa-info-circle"></i> Enter Bank Account Name</span>
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <label class="control-label col-sm-3" for="pass_code">Bank Account Number:</label>
                                        <div class="col-sm-9 col-lg-5">
                                            <input name="company_acct_no" type="text" class="form-control" id="" required>
                                            <span class="help-block"><i class="fa fa-info-circle"></i> Enter Bank Account Number</span>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <div class="col-sm-offset-3 col-sm-9"><input name="verify_account_ifx_acct" type="submit" class="btn btn-success" value="Submit" /></div>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>

                    <!-- Unique Page Content Ends Here
                    ================================================== -->
                    
                </div>
                <!-- Main Body - Side Bar  -->
                <div id="main-body-side-bar" class="col-md-4 col-md-pull-8 col-lg-3 col-lg-pull-9 left-nav">
                <?php require_once 'layouts/sidebar.php'; ?>
                </div>
            </div>
        </div>
        <?php require_once 'layouts/footer.php'; ?>
    </body>
</html>