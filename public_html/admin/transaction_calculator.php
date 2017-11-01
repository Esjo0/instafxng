<?php
require_once("../init/initialize_admin.php");
if (!$session_admin->is_logged_in()) {
    redirect_to("login.php");
}

if (isset($_POST['calculator'])) {
    foreach($_POST as $key => $value) {
        $_POST[$key] = $db_handle->sanitizePost(trim($value));
    }

    //var_dump($_POST);
    extract($_POST);
    //$exchange_rate = $_POST['exchange_rate'];
    //$amount = $_POST['amount'];
    //$trans_type = $_POST['trans_type'];
    
    $amount = str_replace(",", "", $amount);
    
    if($trans_type == 'Deposit')
    {
        $new_naira_amount = $amount - CBN_STAMP_DUTY;
        $denom = DVAT * DSERVCHARGE + DSERVCHARGE + 1;	
        $realDolVal = number_format(($new_naira_amount / ($exchange_rate * $denom)), 2);
    }
    else
    {
        $new_naira_amount = $amount * $exchange_rate;
        $total_service_charge = $new_naira_amount * WSERVCHARGE;
        $total_vat = $total_service_charge * WVAT;
        $real_withdrawal = $new_naira_amount - ($total_service_charge + $total_vat);
        $real_withdrawal = number_format($real_withdrawal, 2);
    }   
}

?>
<!DOCTYPE html>
<html lang="en">
    <head>
        <base target="_self">
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>Instaforex Nigeria | Admin - Transaction Calculator</title>
        <meta name="title" content="Instaforex Nigeria | Admin - Transaction Calculator" />
        <meta name="keywords" content="" />
        <meta name="description" content="" />
        <?php require_once 'layouts/head_meta.php'; ?>
    </head>
    <body>
        <?php require_once 'layouts/header.php'; ?>
        <!-- Main Body: The is the main content area of the web site, contains a side bar  -->
        <div id="main-body" class="container-fluid">
            <div class="row no-gutter">
                <!-- Main Body - Side Bar  -->
                <div id="main-body-side-bar" class="col-md-4 col-lg-3 left-nav">
                <?php require_once 'layouts/sidebar.php'; ?>
                </div>
                
                <!-- Main Body - Content Area: This is the main content area, unique for each page  -->
                <div id="main-body-content-area" class="col-md-8 col-lg-9">
                    
                    <!-- Unique Page Content Starts Here
                    ================================================== -->
                    <div class="row">
                        <div class="col-sm-12 text-danger">
                            <h4><strong>TRANSACTION CALCULATOR</strong></h4>
                        </div>
                    </div>
                    
                    <div class="section-tint super-shadow">
                        <div class="row">
                            <div class="col-sm-12">
                                <?php require_once 'layouts/feedback_message.php'; ?>
                                
                                <p>Use the form below to calculate transactions. You are to provide the amount and the 
                                exchange rate you want to use for the deposit.</p>
                                
                                <p class="text-danger"><strong>Note: If you select "Deposit" for Transaction Type, the Amount entered will be taken as Naira,
                                    also, if you select "Withdrawal" for Transaction Type, the Amount entered will be taken as Dollars</strong></p>
                                
                                <form data-toggle="validator" class="form-horizontal" role="form" method="post" action="<?php echo $_SERVER['REQUEST_URI']; ?>">
                                    <div class="form-group">
                                        <label class="control-label col-sm-3" for="amount">Amount:</label>
                                        <div class="col-sm-9 col-lg-5">
                                            <input name="amount" type="text" class="form-control" required>
                                        </div>
                                    </div>
                                    <!--<div class="form-group">
                                        <label class="control-label col-sm-3" for="exchange_rate">Exchange Rate (&#8358;):</label>
                                        <div class="col-sm-9 col-lg-5">
                                            <input name="exchange_rate" type="text" class="form-control" required>
                                        </div>
                                    </div>-->
                                    <div class="form-group">
                                        <label class="control-label col-sm-3" for="trans_type">Transaction Type:</label>
                                        <div class="col-sm-9 col-lg-5">
                                            <select name="trans_type" class="form-control" id="service_charge_type">
                                                <option value="Deposit" selected="selected">Deposit</option>
                                                <option value="Withdrawal">Withdrawal</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="control-label col-sm-3" for="trans_type">Account Type:</label>
                                        <div class="col-sm-9 col-lg-5">
                                            <select name="acc_type" class="form-control" id="service_charge_type">
                                                <option value="ILPR" selected="selected">ILPR</option>
                                                <option value="NILPR">None-ILPR</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="control-label col-sm-3" for="trans_type">Funding Type:</label>
                                        <div class="col-sm-9 col-lg-5">
                                            <select name="fund_type" class="form-control" id="service_charge_type">
                                                <option value="Online Funding" selected="selected">Online Funding</option>
                                                <option value="Offline Funding">Offline Funding</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="control-label col-sm-3" for="trans_type">Currency Type:</label>
                                        <div class="col-sm-9 col-lg-5">
                                            <select name="curr_type" class="form-control" id="service_charge_type">
                                                <option value="Naira" selected="selected">Naira</option>
                                                <option value="Dollar">Dollar</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <div class="col-sm-offset-3 col-sm-9"><input name="calculator" type="submit" class="btn btn-success" value="Calculate" /></div>
                                    </div>
                                </form>
                                
                                <hr>
                                <?php if(isset($realDolVal)) { ?>
                                <p>
                                    Naira Amount: <?php echo $amount; ?><br/>
                                    Exchange Rate: <?php echo $exchange_rate; ?><br/><br/>
                                    
                                    Dollar Equivalent: <span class="text-success"><strong>&dollar; <?php echo $realDolVal; ?></strong></span><br/>
                                </p>
                                <?php } ?>
                                
                                <?php if(isset($real_withdrawal)) { ?>
                                <p>
                                    Dollar Amount: <?php echo $amount; ?><br/>
                                    Exchange Rate: <?php echo $exchange_rate; ?><br/><br/>
                                    
                                    Naira Withdrawable: <span class="text-success"><strong>&#8358; <?php echo $real_withdrawal; ?></strong></span><br/>
                                </p>
                                <?php } ?>
                                
                            </div>
                        </div>

                    </div>

                    <!-- Unique Page Content Ends Here
                    ================================================== -->
                    
                </div>
                
            </div>
        </div>
        <?php require_once 'layouts/footer.php'; ?>
        <script src="//cdnjs.cloudflare.com/ajax/libs/moment.js/2.9.0/moment-with-locales.js"></script>
        <script src="//cdn.rawgit.com/Eonasdan/bootstrap-datetimepicker/e8bddc60e73c1ec2475f827be36e1957af72e2ea/src/js/bootstrap-datetimepicker.js"></script>
    </body>
</html>