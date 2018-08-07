<?php
require_once '../../init/initialize_partner.php';
require_once '../../init/initialize_general.php';

if (!$session_partner->is_logged_in()) {
    redirect_to("../login.php");
}

$partner_code = $_SESSION['partner_code'];

if(isset($_GET['acc'])) {
    $acct_no = $_GET['acc'];
 } else {
    redirect_to("bankacc_view.php");
 }

if (isset($_POST['submit_code']) && !empty($_POST['submit_code'])) {
    $partner_code = $db_handle->sanitizePost($_POST['partner_code']);
    $acct_no = $db_handle->sanitizePost($_POST['acct_no']);
    $amount = $db_handle->sanitizePost($_POST['amount']);
    $comment = $db_handle->sanitizePost($_POST['comment']);
    
    if(empty($amount) || empty($comment)) {
        $message_error = "Please fill all the fields and try again.";
    } else {
        $result = $partner_object->request_withdrawal($partner_code, $amount, $acct_no, $comment, 2);

        if($result == 'insufficient') {
            $message_error = "Your account balance is insufficient for the Withrawal";
        } else if($result == 'success') {
            $message_success = "Your Withrawal request has been loged. We will get back to you shortly";
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
    <title>Instaforex Nigeria | Withdraw Commission</title>
    <meta name="title" content="Instaforex Nigeria | Withdraw Commission" />
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
            <div class="section-tint super-shadow">
                <div class="section-tint super-shadow">
                    <div class="row">
                        <div class="col-sm-12 text-danger">
                            <h4><strong>Withraw from <?php echo $acct_no; ?></strong></h4>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-sm-12">
                            <?php if(isset($message_success)): ?>
                                <div class="alert alert-success">
                                    <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
                                    <strong>Success!</strong> <?php echo $message_success; ?>
                                </div>
                            <?php endif ?>
                            <?php if(isset($message_error)): ?>
                                <div class="alert alert-danger">
                                    <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
                                    <strong>Oops!</strong> <?php echo $message_error; ?>
                                </div>
                            <?php endif ?>

                            <form data-toggle="validator" class="form-horizontal" role="form" method="post" action="<?php echo $_SERVER['REQUEST_URI']; ?>">
                                <!-- The next line does nothing other than deceive the browsers
                                that auto-fill password fields - This will be filled but we do not need it -->
                                <input type="hidden" name="partner_code" value="<?php echo $partner_code; ?>" />
                                <input type="hidden" name="acct_no" value="<?php echo $acct_no; ?>" />
                                <div class="form-group">
                                    <label class="control-label col-sm-3" for="pass_code">Amount:</label>
                                    <div class="col-sm-9 col-lg-5">
                                        <input name="amount" type="number" class="form-control"  value=""  required="required">
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="control-label col-sm-3" for="pass_code">Comment:</label>
                                    <div class="col-sm-9 col-lg-5">
                                            <textarea name="comment" class="form-control"  value=""  required="required">
                                            </textarea>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <div class="col-sm-offset-3 col-sm-9"><input name="submit_code" type="submit" class="btn btn-success" value="Submit" /></div>
                                </div>
                            </form>
                        </div>
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