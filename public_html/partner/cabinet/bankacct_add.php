<?php
require_once '../../init/initialize_partner.php';
require_once '../../init/initialize_general.php';

if (!$session_partner->is_logged_in()) {
    redirect_to("../login.php");
}

$user_code = $_SESSION['partner_user_code'];

$banks = $system_object->get_all_banks();

if (isset($_POST['submit_code']) && !empty($_POST['submit_code'])) {
    $bank_id = $db_handle->sanitizePost($_POST['bank_id']);
    $account_name = $db_handle->sanitizePost($_POST['acc_name']);
    $account_number = $db_handle->sanitizePost($_POST['acc_no']);
    
    if(empty($bank_id) || empty($account_number) || empty($account_name)) {
        $message_error = "Please fill all the fields and try again.";
    } else {
        $result = $client_object->set_bank_account($user_code, $account_name, $account_number, $bank_id);
        if($result) {
            redirect_to('bankacc_view.php?msg=1');
        } else {
            $message_error = "An error occurred, the bank details could not be saved.";
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
    <title>Instaforex Nigeria | Partner Area</title>
    <meta name="title" content="Instaforex Nigeria | Partner Area" />
    <meta name="keywords" content="">
    <meta name="description" content="">
    <?php require_once 'layouts/head_meta.php'; ?>
</head>
<body>
<?php require_once 'layouts/header.php'; ?>
<!-- Main Body: The is the main content area of the web site, contains a side bar  -->
<div id="main-body" class="container-fluid">
    <div class="row no-gutter">
        <?php require_once 'layouts/sidebar.php'; ?>
        <!-- Main Body - Content Area: This is the main content area, unique for each page  -->
        <div id="main-body-content-area" class="col-md-12">

            <!-- Unique Page Content Starts Here
            ================================================== -->
            <div class="row">
                <div class="col-sm-12">
                    <img src="images/partner_pc.png" alt="" class="img-responsive center-block" width="668px" height="226px" />
                </div>

            </div>

            <div class="section-tint super-shadow">
                <div class="row">
                    <div class="col-sm-12">
                        <h3>Partner Dashboard</h3>
                        <p>Follow the links in the navigation bar to get to other parts of the portal.</p>
                    </div>
                </div>
                <div class="row"><hr /></div>
                <div class="row">
                    <div class="col-sm-5">

                    </div>
                    <div class="col-sm-7">

                    </div>
                </div>

            </div>
            <!-- Unique Page Content Ends Here
            ================================================== -->

        </div>

    </div>
    <div class="row no-gutter">
        <?php require_once 'layouts/footer.php'; ?>
    </div>
</div>

</body>
</html>





<!DOCTYPE html>
<html lang="en">
    <head>
        <base target="_self">
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>Instafxng Partner | Add Bank Account</title>
        <meta name="title" content="Instafxng Partner | Add Bank Account" />
        <meta name="keywords" content=" ">
        <meta name="description" content=" ">
        <?php require_once 'layouts/head_meta.php'; ?>
    </head>
    <body>
        <?php require_once 'layouts/header.php'; ?>
        <!-- Main Body: The is the main content area of the web site, contains a side bar  -->
        <div id="main-body" class="container-fluid">
            <div class="row no-gutter">
                
                <!-- Main Body - Content Area: This is the main content area, unique for each page  -->
                <div id="main-body-content-area" class="col-md-8 col-md-push-4 col-lg-9 col-lg-push-3">
                    
                    <!-- Unique Page Content Starts Here
                    ================================================== -->
                    <div class="section-tint super-shadow">
                        <div class="row">
                            <div class="col-sm-12 text-danger">
                                <p><a href="partner/cabinet/bankacc_view.php" class="btn btn-default" title="Go back to Bank Accounts"><i class="fa fa-arrow-circle-left"></i> Go Back - Bank Accounts</a></p>
                                <h4><strong>Add Bank Account</strong></h4>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-sm-12">
                                <?php require_once 'layouts/feedback_message.php'; ?>
                                
                                <p>Fill the form below to add a new bank account to your partner profile.</p>
                                <form data-toggle="validator" class="form-horizontal" role="form" method="post" action="<?php echo $_SERVER['REQUEST_URI']; ?>">
                                    <!-- The next line does nothing other than deceive the browsers 
                                    that auto-fill password fields - This will be filled but we do not need it -->
                                    <div class="form-group">
                                        <label class="control-label col-sm-3" for="acc_name">Bank account name:</label>
                                        <div class="col-sm-9 col-lg-5">
                                            <input name="acc_name" type="text" class="form-control"  value=""  required="required">
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="control-label col-sm-3" for="acc_no">Bank account number:</label>
                                        <div class="col-sm-9 col-lg-5">
                                            <input name="acc_no" type="text" class="form-control"  value=""  required="required" maxlength="10">
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="control-label col-sm-3" for="bank_name">Bank name:</label>
                                        <div class="col-sm-9 col-lg-5">
                                            <select name="bank_id" class="form-control"  value=""  required="required">
                                                <option value="">select bank</option>
                                                <?php for($i = 0; $i < count($banks); $i++) { ?>
                                                <option value="<?php echo $banks[$i]['bank_id']; ?>"><?php echo $banks[$i]['bank_name']; ?></option>
                                                <?php } ?>
                                            </select>
                                        </div>
                                    </div>
                                    
                                    <div class="form-group">
                                        <div class="col-sm-offset-3 col-sm-9"><input name="submit_code" type="submit" class="btn btn-success" value="Add Bank Details" /></div>
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