<?php
require_once("../init/initialize_admin.php");
if (!$session_admin->is_logged_in()) {
    redirect_to("login.php");
}

if (isset($_POST['calculator']))
{
    //$trans_type 1(Deposit), 2(Withdrawal)
    //$acc_type 1(ILPR), 2(None ILPR)
    //$curr_type 1(Naira), 2(Dollar)
    //$fund_type 1(Online), 2(Offline)
    foreach($_POST as $key => $value)
    {
        $_POST[$key] = $db_handle->sanitizePost(trim($value));
    }

    //var_dump($_POST);
    extract($_POST);

    if(isset($trans_type) && $trans_type == '1')
    {
        if(isset($acc_type) && $acc_type == '1')
        {
            if(isset($curr_type) && $curr_type == '1')
            {
                if(isset($fund_type) && $fund_type == '1')
                {
                    $type = 'DINO';
                    //Deposit Transactions--ILPR Account--Naira--Online Funding
                    $amount = str_replace(",", "", $amount);
                    //$naira_amount = $amount - number_format(CBN_STAMP_DUTY, 2);
                    $service_charge = ($amount / 100) * DSERVCHARGE;
                    $vat = ($service_charge / 100) * DVAT;
                    $exchange_rate = number_format(IPLRFUNDRATE, 2);
                    $naira_amount = $naira_amount - ($vat + $service_charge);
                    $realDolVal = number_format((($amount + $vat + $service_charge + CBN_STAMP_DUTY) / IPLRFUNDRATE), 2);

                }
                elseif(isset($fund_type) && $fund_type == '2')
                {
                    $type = 'DINO2';
                    $service_charge = 0;
                    $vat = 0;
                    //Deposit Transactions--ILPR Account--Naira--Offline Funding
                    $amount = str_replace(",", "", $amount);
                    $exchange_rate = number_format(IPLRFUNDRATE, 2);
                    $realDolVal = number_format(($amount / IPLRFUNDRATE), 2);
                }
            }
        }
        elseif(isset($acc_type) && $acc_type == '2')
        {
            if(isset($curr_type) && $curr_type == '1')
            {
                if(isset($fund_type) && $fund_type == '1')
                {
                    $type = "DNNO";
                    //Deposit Transactions--None ILPR Account--Naira--Online Funding
                    $amount = str_replace(",", "", $amount);
                    //$naira_amount = $amount;
                    $service_charge = ($amount / 100) * DSERVCHARGE;
                    $vat = ($service_charge / 100) * DVAT;
                    $exchange_rate = number_format(NFUNDRATE, 2);
                    //$naira_amount = $naira_amount - ($vat + $service_charge);
                    $realDolVal = number_format((($amount + $vat + $service_charge + CBN_STAMP_DUTY) / NFUNDRATE), 2);
                }
                elseif(isset($fund_type) && $fund_type == '2')
                {
                    $type = "DNNO2";
                    //Deposit Transactions--None ILPR Account--Naira--Offline Funding
                    $service_charge = 0;
                    $vat = 0;
                    $exchange_rate = number_format(NFUNDRATE, 2);
                    $amount = str_replace(",", "", $amount);
                    $realDolVal = number_format(($amount / NFUNDRATE), 2);
                }
            }
            elseif(isset($curr_type) && $curr_type == '2')
            {
                if(isset($fund_type) && $fund_type == '1')
                {
                    $type = "DNDO";
                    $amount = str_replace(",", "", $amount);

                    $naira_amount = $amount  * NFUNDRATE;

                    $service_charge = ($naira_amount / 100) * DSERVCHARGE;

                    $vat = ($service_charge / 100) * DVAT;

                    $exchange_rate = number_format(NFUNDRATE, 2);

                    $realDolVal = number_format(($naira_amount + $vat + $service_charge + CBN_STAMP_DUTY), 2);
                }
            }
        }
    }
    //Withdrawal Transactions
    elseif(isset($trans_type) && $trans_type == '2')
    {
        //Withdrawal Transactions--Dollar
        if(isset($curr_type) && $curr_type == '2')
        {
            $amount = str_replace(",", "", $amount);
            $naira_amount = $amount * WITHDRATE;
            $service_charge = ($naira_amount / 100) * WSERVCHARGE;
            $vat = ($service_charge / 100) * WVAT;
            $exchange_rate = WITHDRATE;
            $real_naira_withdrawal = number_format(($naira_amount - ($service_charge + $vat + CBN_STAMP_DUTY)), 2);
        }
        //Withdrawal Transactions--Naira
        elseif(isset($curr_type) && $curr_type == '1')
        {
            $amount = str_replace(",", "", $amount);
            $service_charge = ($amount / 100) * WSERVCHARGE;
            $vat = ($service_charge / 100) * WVAT;
            $exchange_rate = WITHDRATE;
            $real_dollar_withdrawal = number_format(($amount + $service_charge + $vat + CBN_STAMP_DUTY) / WITHDRATE, 2);
        }
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
                                            <input value="1" id="trans_type" name="trans_type" type="radio"/> Deposit
                                            <br/>
                                            <input value="2" id="trans_type" name="trans_type" type="radio"/> Withdrawal
                                            <!--<select name="trans_type" class="form-control" id="service_charge_type">
                                                <option  ></option>
                                                <option  value="1" >Deposit</option>
                                                <option  value="2" >Withdrawal</option>
                                            </select>-->
                                        </div>
                                    </div>
                                    <div id="acc_type" class="form-group"  >
                                        <label class="control-label col-sm-3" for="acc_type">Account Type:</label>
                                        <div class="col-sm-9 col-lg-5">
                                            <input value="1" id="acc_type" name="acc_type" type="radio"/> ILPR
                                            <br/>
                                            <input value="2" id="acc_type" name="acc_type" type="radio"/> None ILPR
                                            <!--<select name="acc_type" class="form-control" id="service_charge_type">
                                                <option  ></option>
                                                <option value="1" >ILPR</option>
                                                <option value="2">None ILPR</option>
                                            </select>-->
                                        </div>
                                    </div>
                                    <div id="fund_type" class="form-group"  >
                                        <label class="control-label col-sm-3" for="trans_type">Funding Type:</label>
                                        <div class="col-sm-9 col-lg-5">
                                            <input value="1" id="fund_type" name="fund_type" type="radio"/> Online Funding
                                            <br/>
                                            <input value="2" id="fund_type" name="fund_type" type="radio"/> Offline Funding
                                            <!--<select name="fund_type" class="form-control" id="service_charge_type">
                                                <option  ></option>
                                                <option value="1" >Online Funding</option>
                                                <option value="2">Offline Funding</option>
                                            </select>-->
                                        </div>
                                    </div>
                                    <div id="curr_type" class="form-group"  >
                                        <label class="control-label col-sm-3" for="curr_type">Currency Type:</label>
                                        <div class="col-sm-9 col-lg-5">
                                            <input value="1" id="curr_type" name="curr_type" type="radio"/> Naira
                                            <br/>
                                            <input value="2" id="curr_type" name="curr_type" type="radio"/> Dollar
                                            <!--<select name="curr_type" class="form-control" id="service_charge_type">
                                                <option  ></option>
                                                <option value="1" >Naira</option>
                                                <option value="2">Dollar</option>
                                            </select>-->
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <div class="col-sm-offset-3 col-sm-9"><input name="calculator" type="submit" class="btn btn-success" value="Calculate" /></div>
                                    </div>
                                </form>
                                
                                <hr>
                                <br/>
                                <?php if(isset($type) && $type == "DNDO") { ?>
                                    <form data-toggle="validator" class="form-horizontal" role="form" method="post">
                                        <div class="form-group">
                                            <label class="control-label col-sm-3" for="exchange_rate">Dollar Amount($):</label>
                                            <div class="col-sm-9 col-lg-5">
                                                <input name="exchange_rate" value="<?php echo number_format($amount,2); ?>" type="text" class="form-control" disabled>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label class="control-label col-sm-3" for="exchange_rate">Exchange Rate (&#8358;):</label>
                                            <div class="col-sm-9 col-lg-5">
                                                <input name="exchange_rate" value="<?php echo number_format($exchange_rate,2); ?>" type="text" class="form-control" disabled>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label class="control-label col-sm-3" for="exchange_rate">Service Charge (&#8358;):</label>
                                            <div class="col-sm-9 col-lg-5">
                                                <input name="exchange_rate" value="<?php echo number_format($service_charge, 2); ?>" type="text" class="form-control" disabled>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label class="control-label col-sm-3" for="exchange_rate">VAT (&#8358;):</label>
                                            <div class="col-sm-9 col-lg-5">
                                                <input name="exchange_rate" value="<?php echo number_format($vat,2); ?>" type="text" class="form-control" disabled>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label class="control-label col-sm-3" for="exchange_rate">Naira Amount (&#8358;):</label>
                                            <div class="col-sm-9 col-lg-5">
                                                <input name="exchange_rate" value="<?php echo $realDolVal; ?>" type="text" class="form-control" disabled>
                                            </div>
                                        </div>
                                    </form>
                                <?php } ?>

                                <?php if(isset($type) && $type == "DNNO2") { ?>
                                    <form data-toggle="validator" class="form-horizontal" role="form" method="post">
                                        <div class="form-group">
                                            <label class="control-label col-sm-3" for="exchange_rate">Naira Amount(&#8358;):</label>
                                            <div class="col-sm-9 col-lg-5">
                                                <input name="exchange_rate" value="<?php echo number_format($amount,2); ?>" type="text" class="form-control" disabled>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label class="control-label col-sm-3" for="exchange_rate">Exchange Rate (&#8358;):</label>
                                            <div class="col-sm-9 col-lg-5">
                                                <input name="exchange_rate" value="<?php echo number_format($exchange_rate,2); ?>" type="text" class="form-control" disabled>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label class="control-label col-sm-3" for="exchange_rate">Service Charge (&#8358;):</label>
                                            <div class="col-sm-9 col-lg-5">
                                                <input name="exchange_rate" value="<?php echo number_format($service_charge, 2); ?>" type="text" class="form-control" disabled>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label class="control-label col-sm-3" for="exchange_rate">VAT (&#8358;):</label>
                                            <div class="col-sm-9 col-lg-5">
                                                <input name="exchange_rate" value="<?php echo number_format($vat,2); ?>" type="text" class="form-control" disabled>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label class="control-label col-sm-3" for="exchange_rate">Dollar Amount ($):</label>
                                            <div class="col-sm-9 col-lg-5">
                                                <input name="exchange_rate" value="<?php echo $realDolVal; ?>" type="text" class="form-control" disabled>
                                            </div>
                                        </div>
                                    </form>
                                <?php } ?>

                                <?php if(isset($type) && $type == "DNNO") { ?>
                                    <form data-toggle="validator" class="form-horizontal" role="form" method="post">
                                        <div class="form-group">
                                            <label class="control-label col-sm-3" for="exchange_rate">Naira Amount(&#8358;):</label>
                                            <div class="col-sm-9 col-lg-5">
                                                <input name="exchange_rate" value="<?php echo number_format($amount,2); ?>" type="text" class="form-control" disabled>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label class="control-label col-sm-3" for="exchange_rate">Exchange Rate (&#8358;):</label>
                                            <div class="col-sm-9 col-lg-5">
                                                <input name="exchange_rate" value="<?php echo number_format($exchange_rate,2); ?>" type="text" class="form-control" disabled>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label class="control-label col-sm-3" for="exchange_rate">Service Charge (&#8358;):</label>
                                            <div class="col-sm-9 col-lg-5">
                                                <input name="exchange_rate" value="<?php echo number_format($service_charge, 2); ?>" type="text" class="form-control" disabled>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label class="control-label col-sm-3" for="exchange_rate">VAT (&#8358;):</label>
                                            <div class="col-sm-9 col-lg-5">
                                                <input name="exchange_rate" value="<?php echo number_format($vat,2); ?>" type="text" class="form-control" disabled>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label class="control-label col-sm-3" for="exchange_rate">Dollar Amount ($):</label>
                                            <div class="col-sm-9 col-lg-5">
                                                <input name="exchange_rate" value="<?php echo $realDolVal; ?>" type="text" class="form-control" disabled>
                                            </div>
                                        </div>
                                    </form>
                                <?php } ?>

                                <?php if(isset($type) && $type == "DINO2") { ?>
                                    <form data-toggle="validator" class="form-horizontal" role="form" method="post">
                                        <div class="form-group">
                                            <label class="control-label col-sm-3" for="exchange_rate">Naira Amount(&#8358;):</label>
                                            <div class="col-sm-9 col-lg-5">
                                                <input name="exchange_rate" value="<?php echo number_format($amount,2); ?>" type="text" class="form-control" disabled>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label class="control-label col-sm-3" for="exchange_rate">Exchange Rate (&#8358;):</label>
                                            <div class="col-sm-9 col-lg-5">
                                                <input name="exchange_rate" value="<?php echo number_format($exchange_rate,2); ?>" type="text" class="form-control" disabled>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label class="control-label col-sm-3" for="exchange_rate">Service Charge (&#8358;):</label>
                                            <div class="col-sm-9 col-lg-5">
                                                <input name="exchange_rate" value="<?php echo number_format($service_charge, 2); ?>" type="text" class="form-control" disabled>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label class="control-label col-sm-3" for="exchange_rate">VAT (&#8358;):</label>
                                            <div class="col-sm-9 col-lg-5">
                                                <input name="exchange_rate" value="<?php echo number_format($vat,2); ?>" type="text" class="form-control" disabled>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label class="control-label col-sm-3" for="exchange_rate">Dollar Amount ($):</label>
                                            <div class="col-sm-9 col-lg-5">
                                                <input name="exchange_rate" value="<?php echo $realDolVal; ?>" type="text" class="form-control" disabled>
                                            </div>
                                        </div>
                                    </form>
                                <?php } ?>

                                <?php if(isset($type) && $type == "DINO") { ?>
                                    <form data-toggle="validator" class="form-horizontal" role="form" method="post">
                                        <div class="form-group">
                                            <label class="control-label col-sm-3" for="exchange_rate">Naira Amount(&#8358;):</label>
                                            <div class="col-sm-9 col-lg-5">
                                                <input name="exchange_rate" value="<?php echo number_format($amount,2); ?>" type="text" class="form-control" disabled>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label class="control-label col-sm-3" for="exchange_rate">Exchange Rate (&#8358;):</label>
                                            <div class="col-sm-9 col-lg-5">
                                                <input name="exchange_rate" value="<?php echo number_format($exchange_rate,2); ?>" type="text" class="form-control" disabled>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label class="control-label col-sm-3" for="exchange_rate">Service Charge (&#8358;):</label>
                                            <div class="col-sm-9 col-lg-5">
                                                <input name="exchange_rate" value="<?php echo number_format($service_charge, 2); ?>" type="text" class="form-control" disabled>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label class="control-label col-sm-3" for="exchange_rate">VAT (&#8358;):</label>
                                            <div class="col-sm-9 col-lg-5">
                                                <input name="exchange_rate" value="<?php echo number_format($vat,2); ?>" type="text" class="form-control" disabled>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label class="control-label col-sm-3" for="exchange_rate">Dollar Amount ($):</label>
                                            <div class="col-sm-9 col-lg-5">
                                                <input name="exchange_rate" value="<?php echo $realDolVal; ?>" type="text" class="form-control" disabled>
                                            </div>
                                        </div>
                                    </form>
                                <?php } ?>
                                
                                <?php if(isset($real_naira_withdrawal)) { ?>
                                    <form data-toggle="validator" class="form-horizontal" role="form" method="post">
                                    <div class="form-group">
                                        <label class="control-label col-sm-3" for="exchange_rate">Dollar Amount($):</label>
                                        <div class="col-sm-9 col-lg-5">
                                            <input name="exchange_rate" value="<?php echo number_format($amount,2); ?>" type="text" class="form-control" disabled>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="control-label col-sm-3" for="exchange_rate">Exchange Rate (&#8358;):</label>
                                        <div class="col-sm-9 col-lg-5">
                                            <input name="exchange_rate" value="<?php echo number_format($exchange_rate,2); ?>" type="text" class="form-control" disabled>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="control-label col-sm-3" for="exchange_rate">Service Charge (&#8358;):</label>
                                        <div class="col-sm-9 col-lg-5">
                                            <input name="exchange_rate" value="<?php echo number_format($service_charge, 2); ?>" type="text" class="form-control" disabled>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="control-label col-sm-3" for="exchange_rate">VAT (&#8358;):</label>
                                        <div class="col-sm-9 col-lg-5">
                                            <input name="exchange_rate" value="<?php echo number_format($vat,2); ?>" type="text" class="form-control" disabled>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="control-label col-sm-3" for="exchange_rate">Naira Amount (&#8358;):</label>
                                        <div class="col-sm-9 col-lg-5">
                                            <input name="exchange_rate" value="<?php echo $real_naira_withdrawal; ?>" type="text" class="form-control" disabled>
                                        </div>
                                    </div>
                                    </form>
                                <?php } ?>

                                <?php if(isset($real_dollar_withdrawal)) { ?>
                                    <form data-toggle="validator" class="form-horizontal" role="form" method="post">
                                        <div class="form-group">
                                            <label class="control-label col-sm-3" for="exchange_rate">Naira Amount(&#8358;):</label>
                                            <div class="col-sm-9 col-lg-5">
                                                <input name="exchange_rate" value="<?php echo number_format($amount,2); ?>" type="text" class="form-control" disabled>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label class="control-label col-sm-3" for="exchange_rate">Exchange Rate (&#8358;):</label>
                                            <div class="col-sm-9 col-lg-5">
                                                <input name="exchange_rate" value="<?php echo number_format($exchange_rate,2); ?>" type="text" class="form-control" disabled>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label class="control-label col-sm-3" for="exchange_rate">Service Charge (&#8358;):</label>
                                            <div class="col-sm-9 col-lg-5">
                                                <input name="exchange_rate" value="<?php echo number_format($service_charge, 2); ?>" type="text" class="form-control" disabled>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label class="control-label col-sm-3" for="exchange_rate">VAT (&#8358;):</label>
                                            <div class="col-sm-9 col-lg-5">
                                                <input name="exchange_rate" value="<?php echo number_format($vat,2); ?>" type="text" class="form-control" disabled>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label class="control-label col-sm-3" for="exchange_rate">Dollar Amount ($):</label>
                                            <div class="col-sm-9 col-lg-5">
                                                <input name="exchange_rate" value="<?php echo $real_dollar_withdrawal; ?>" type="text" class="form-control" disabled>
                                            </div>
                                        </div>
                                    </form>
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