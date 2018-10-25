<?php
require_once 'init/initialize_general.php';
$thisPage = "";

$page_requested = '';

$all_banks = $system_object->get_all_banks();

$get_params = allowed_get_params(['x', 'id']);

$trans_id_encrypted = $get_params['id'];
$trans_id = decrypt(str_replace(" ", "+", $trans_id_encrypted));
$trans_id = preg_replace("/[^A-Za-z0-9 ]/", '', $trans_id);

$refund_type = $get_params['x'];

//Ensure only those that have an initiated refund can access this page
if (!empty($trans_id_encrypted) && !empty($refund_type)) {
    //since GET values are set, we will confirm if its a true refund transaction
    $query = "SELECT * FROM user_deposit_refund WHERE transaction_id = '$trans_id' AND refund_status = '0' LIMIT 1";
    $num_rows = $db_handle->numRows($query);

    if($num_rows != 1) {
        // No record found. Redirect to the home page.
        redirect_to("./");
        exit;
    }

} else {
    //Redirect to homepage - user trying to access page directly without been sent a link
    redirect_to("./");
    exit;
}

if (isset($_POST['deposit_refund'])) {
    $user_bank_name = $db_handle->sanitizePost($_POST['user_bank_name']);
    $user_acct_name = $db_handle->sanitizePost($_POST['user_acct_name']);
    $user_acct_no = $db_handle->sanitizePost($_POST['user_acct_no']);
    $comp_bank_name = $db_handle->sanitizePost($_POST['comp_bank_name']);
    $comp_acct_name = $db_handle->sanitizePost($_POST['comp_acct_name']);
    $comp_acct_no = $db_handle->sanitizePost($_POST['comp_acct_no']);
    $tp_name = $db_handle->sanitizePost($_POST['tp_name']);
    $tp_email = $db_handle->sanitizePost($_POST['tp_email']);
    $tp_phone = $db_handle->sanitizePost($_POST['tp_phone']);
    $wrong_remark = $db_handle->sanitizePost($_POST['wrong_remark']);

    $query = "SELECT * FROM user_deposit WHERE trans_id = '$trans_id'";
    $result = $db_handle->numRows($query);

    if ($result == 1) {
        switch ($refund_type) {
            case 1:
                $issue_desc = "Transaction ID: " . $trans_id;
                break;
            case 2:
                $issue_desc = "Third Party Details : Name: $tp_name <br> Email: $tp_email <br> Phone No.: $tp_phone <br>Transaction ID: $trans_id";
                break;
            case 3:
                $issue_desc = "Wrong Remark: $wrong_remark <br>Transaction ID: $trans_id";
                break;
        }

        $query = "UPDATE user_deposit_refund SET user_bank_name = '$user_bank_name', user_acct_name = '$user_acct_name', user_acct_no = '$user_acct_no', company_bank_name = '$comp_bank_name', company_acct_name = '$comp_acct_name', company_acct_no = '$comp_acct_no', issue_desc = '$issue_desc', refund_status = '1' WHERE transaction_id = '$trans_id'";
        $request = $db_handle->runQuery($query);

        if ($request == true) {
            $message_success = "Your Request has been submitted successfully";
            $refund_url = "https://instafxng.com/refund_declaration.php?x=" . $refund_type . "&id=" . encrypt($trans_id);
            var_dump($refund_url);
            header("Location: $refund_url");
        } else {
            $message_error = "Your Request was not successfully submitted";
        }
    } else {
        $message_error = "You Are Not due for a refund";
    }
}

?>
<!DOCTYPE html>
<html lang="en">
    <head>
        <base target="_self">
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>Instaforex Nigeria | Deposit refund</title>
        <meta name="title" content="Instaforex Nigeria | Deposit refund" />
        <meta name="keywords" content=" ">
        <meta name="description" content=" ">
        <?php require_once 'layouts/head_meta.php'; ?>
        <script>
            window.onload = function() {
                displayOptions();
            };
            function displayOptions() {
                var options = <?php echo $refund_type; ?>;
                if(options == 1) {
                    document.getElementById("no_trans_id").style.display = "block";
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
                    document.getElementById("trans_id").required = false;
                }
                else if(options == 3) {
                    document.getElementById("third_party_transaction").style.display = "none";
                    document.getElementById("wrong_remark").style.display = "block";
                    document.getElementById("tp_name").required = false;
                    document.getElementById("tp_email").required = false;
                    document.getElementById("tp_phone").required = false;
                    document.getElementById("trans_id").required = false;
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
                                <?php if($refund_type == 1){echo "<p class='text-center'><strong>You Entered the wrong Transaction ID number.</strong></p>";}?>
                                <?php if($refund_type == 2){echo "<p class='text-center'><strong>Your Deposit Transaction was a third party transaction</strong></p>";}?>
                                <?php if($refund_type == 3){echo "<p class='text-center'><strong>You Entered a wrong remark.</strong></p>";}?>
                                <ul class="fa-ul">
                                    <li ><i class="fa-li fa fa-check-square-o icon-tune"></i>Enter all requested information to process your deposit refund</li>
                                </ul>

                                <form data-toggle="validator" class="form-horizontal" role="form" method="post" action="<?php echo $_SERVER['REQUEST_URI']; ?>">

                                    <div class="form-group">
                                        <label class="control-label col-sm-3" for="pass_code">Transaction ID:</label>
                                        <div class="col-sm-9 col-lg-5">
                                            <input id="trans_id" name="transaction_id" value="<?php if(isset($trans_id)) { echo $trans_id; } ?>" type="text" class="form-control" id="transaction_id" disabled>
                                        </div>
                                    </div>

                                    <!-- Section for no transaction id -->
                                    <div id="no_trans_id" style="display:none;">

                                    </div>

                                    <!-- Section for third party transaction -->
                                    <div id="third_party_transaction" style="display:none;">
                                        <hr>
                                        <p class="text-center"><strong>Third Party Details</strong></p>
                                        <div class="form-group">
                                            <label class="control-label col-sm-3" for="pass_code">Third Party's Name:</label>
                                            <div class="col-sm-9 col-lg-5">
                                                <input name="tp-name" type="text" class="form-control" id="tp-name" required>
                                                <span class="help-block"><i class="fa fa-info-circle"></i>Name of the individual you paid with his/her account</span>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label class="control-label col-sm-3" for="pass_code">Third party's Email:</label>
                                            <div class="col-sm-9 col-lg-5">
                                                <input name="tp_email" type="text" class="form-control" id="tp_email" required>
                                                <span class="help-block"><i class="fa fa-info-circle"></i>Enter the Email Address of Third party</span>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label class="control-label col-sm-3" for="pass_code">Third Party's Phone No.:</label>
                                            <div class="col-sm-9 col-lg-5">
                                                <input name="tp_phone" type="text" class="form-control" id="tp_phone" required>
                                                <span class="help-block"><i class="fa fa-info-circle"></i>Enter the Phone Number of Third Party</span>
                                            </div>
                                        </div>
                                        <hr>
                                    </div>

                                    <!-- Section for wrong remark -->
                                    <div id="wrong_remark" style="display:none;">
                                        <hr>
                                        <div class="form-group">
                                            <label class="control-label col-sm-3" for="pass_code">Enter the right Remark:</label>
                                            <div class="col-sm-9 col-lg-5">
                                                <input name="wrong_remark" type="text" class="form-control" id="wrong_remark" required>
                                                <span class="help-block"><i class="fa fa-info-circle"></i>Enter remark</span>
                                            </div>
                                        </div>
                                        <hr>
                                    </div>

                                    <!-- Client bank details -->
                                    <p class="text-center"><strong>Enter Your Bank Details</strong></p>
                                    <div class="form-group">
                                        <label class="control-label col-sm-3" >Bank Name:</label>
                                        <div class="col-sm-9 col-lg-5">
                                            <select name="user_bank_name" class="form-control" id="bank_name" required>
                                                <option value="" selected>Select Bank</option>
                                                <?php foreach($all_banks as $key => $value) { ?>
                                                    <option value="<?php echo $value['bank_name']; ?>"><?php echo $value['bank_name']; ?></option>
                                                <?php } ?>
                                            </select>
                                            <span class="help-block"><i class="fa fa-info-circle"></i> Enter Your Bank Name</span>
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <label class="control-label col-sm-3" >Bank Account Name:</label>
                                        <div class="col-sm-9 col-lg-5">
                                            <input name="user_acct_name" type="text" class="form-control" id="" required>
                                            <span class="help-block"><i class="fa fa-info-circle"></i> Enter Your Bank Account Name</span>
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <label class="control-label col-sm-3" >Bank Account Number:</label>
                                        <div class="col-sm-9 col-lg-5">
                                            <input name="user_acct_no" type="text" class="form-control" id="" required>
                                            <span class="help-block"><i class="fa fa-info-circle"></i> Enter Your Bank Account Number</span>
                                        </div>
                                    </div>
                                    <hr />

                                    <!-- -->
                                    <p class="text-center"><strong>Enter Details of the Bank you paid into.</strong></p>
                                    <div class="form-group">
                                        <label class="control-label col-sm-3" for="pass_code">Bank Name:</label>
                                        <div class="col-sm-9 col-lg-5">
                                            <select name="comp_bank_name" class="form-control" id="bank_name" required>
                                                <option value="" selected>Select Bank</option>
                                                <?php foreach($all_banks as $key => $value) { ?>
                                                    <option value="<?php echo $value['bank_name']; ?>"><?php echo $value['bank_name']; ?></option>
                                                <?php } ?>
                                            </select>
                                            <span class="help-block"><i class="fa fa-info-circle"></i> Enter Bank Name</span>
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <label class="control-label col-sm-3" for="pass_code">Bank Account Name:</label>
                                        <div class="col-sm-9 col-lg-5">
                                            <input name="comp_acct_name" type="text" class="form-control" id="" required>
                                            <span class="help-block"><i class="fa fa-info-circle"></i> Enter Bank Account Name</span>
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <label class="control-label col-sm-3" for="pass_code">Bank Account Number:</label>
                                        <div class="col-sm-9 col-lg-5">
                                            <input name="comp_acct_no" type="text" class="form-control" id="" required>
                                            <span class="help-block"><i class="fa fa-info-circle"></i> Enter Bank Account Number</span>
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <div class="col-sm-offset-3 col-sm-9 "><input name="deposit_refund" type="submit" class="btn btn-success" value="Submit" /></div>
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