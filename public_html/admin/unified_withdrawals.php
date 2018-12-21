<?php
require_once("../init/initialize_admin.php");

if (!$session_admin->is_logged_in()) {
    redirect_to("login.php");
}
//Gets Administrator code
$admin_code = $_SESSION['admin_unique_code'];

if(isset($_POST['create'])){
    $trans_id = "WIT" . time();
    $amount = $db_handle->sanitizePost(trim($_POST['amount']));
    $rate = WITHDRATE;
    $amount_dollars = $amount * WITHDRATE;
    $query = "INSERT INTO unified_bonus_withdrawals (transaction_id, amount_naira, amount_dollar, rate)
              VALUE('$trans_id', '$amount', '$amount_dollar', '$rate')";
    $result = $db_handle->runQuery($query);
    if($result){
        $message_success = "Withdrawal Sussessful";
    }else{
        $message_error = "Withdrawal Not Successful";
    }
}

?>
<!DOCTYPE html>
<html lang="en">
    <head>
        <base target="_self">
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>Instaforex Nigeria | Admin - UNIFIED BONUS WITHDRAWAL</title>
        <meta name="title" content="Instaforex Nigeria | Admin" />
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
                            <h4><strong>CREATE WITHDRAWALS</strong></h4>
                        </div>
                    </div>
                    
                    <div class="section-tint super-shadow">
                        <?php require_once 'layouts/feedback_message.php'; ?>

                        <div class="row">
                            <div class="col-sm-12">
                                <p class="text-center">
                                    KINDLY FILL THE FORM BELOW TO CREATE A  BONUS WITHDRAWAL.
                                </p>
                                <form data-toggle="validator"  role="form" action="" class="form text-center" method="post">

                                    <div class="form-group">
                                        <label class="control-label col-sm-3" for="inventoryid">AMOUNT IN NAIRA</label>
                                        <div class="col-sm-6 col-lg-6">
                                            <div class="input-group">
                                                <span class="input-group-addon"><i class="fa fa-institution fa-fw"></i></span>
                                                <input name="amount" type="text" id=""class="form-control" required/>
                                            </div>
                                        </div>
                                    </div>

                                <div class="text-center">
                                    <button class="btn btn-success" type="submit" name="create">SUBMIT</button>
                                </div>

                                </form>
                                

                                
                            </div>
                        </div>
                    </div>

                    <!-- Unique Page Content Ends Here
                    ================================================== -->
                    
                </div>
                
            </div>
        </div>
        <?php require_once 'layouts/footer.php'; ?>
    </body>
</html>