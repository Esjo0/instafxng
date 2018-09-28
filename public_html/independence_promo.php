<?php
require_once 'init/initialize_general.php';
$thisPage = "Home";
if(isset($_POST['opt_in'])){
    $acct_no = $db_handle->sanitizePost($_POST['acct_no']);
    $query = "SELECT u.user_code
            FROM user_credential AS uc
            INNER JOIN user AS u ON uc.user_code = u.user_code
            LEFT JOIN user_ifxaccount AS ui ON u.user_code = ui.user_code
            INNER JOIN account_officers AS ao ON u.attendant = ao.account_officers_id
            INNER JOIN admin AS a ON ao.admin_code = a.admin_code
            LEFT JOIN user_bank AS ub ON u.user_code = ub.user_code
            WHERE (uc.doc_status = '111') AND ui.ifx_acct_no = '$acct_no'";
    $result = $db_handle->runQuery($query);
    $details = $db_handle->fetchArray($result);
    if($details) {
        foreach ($details AS $row) {
            extract($row);
            $query = "INSERT IGNORE INTO independence_promo (user_code,ifx_acct_no) VALUE($user_code, $acct_no)";
            $result = $db_handle->runQuery($query);
        }
    }else {
        $message_error = "This account is not an ILPR account
<a href='https://instafxng.com/live_account.php'> Click Here to Open ILPR account</a>";
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <base target="_self">
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Instaforex Nigeria | Online Instant Forex Trading Services</title>
    <meta name="title" content="Instaforex Nigeria | Online Instant Forex Trading Services" />
    <meta name="keywords" content="instaforex, forex trading in nigeria, forex seminar, forex trading seminar, how to trade forex, trade forex, instaforex nigeria">
    <meta name="description" content="Instaforex, a multiple award winning international forex broker is the leading Forex broker in Nigeria, open account and enjoy forex trading with us.">
    <?php require_once 'layouts/head_meta.php'; ?>

    <meta property="og:site_name" content="Instaforex Nigeria" />
    <meta property="og:title" content="Instaforex Nigeria | Online Instant Forex Trading Services" />
    <meta property="og:description" content="Instaforex, a multiple award winning international forex broker is the leading Forex broker in Nigeria, open account and enjoy forex trading with us." />
    <meta property="og:image" content="images/instaforex-100bonus.jpg" />
    <meta property="og:url" content="https://instafxng.com/" />
    <meta property="og:type" content="website" />
    <script src="https://www.wpiece.com/js/webcomponents.min.js"></script>
    <link  rel="import" href="http://www.wpiece.com/p/10_26" />
</head>
<body style="height:100vh; background: rgba(198, 255, 198, 0.88);" >
<?php require_once 'layouts/header.php'; ?>
<!-- Main Body: The is the main content area of the web site, contains a side bar  -->
<div id="main-body" class="container" >
    <img id="img_div_2" width="100%" class="img img-responsive" src="images/titc.jpg" style="height:100px; box-shadow: 0 4px 8px 0 rgba(0, 0, 0, 0.2), 0 6px 20px 0 rgba(0, 0, 0, 0.19)">
    <center><a href="#open" role="button" class="btn btn-success text-center" data-toggle="modal"><strong>JOIN</strong></a></center>
        <img id="img_div_2" width="100%" class="img img-responsive img-thumbnail" src="images/independence-banner-1.jpg" style="height:300px; box-shadow: 0 4px 8px 0 rgba(0, 0, 0, 0.2), 0 6px 20px 0 rgba(0, 0, 0, 0.19)">
</div>


<!--///////////////////////////////
                        Login Form Scripting-->

    <!--Modal - confirmation boxes-->
    <!--                <div  data-backdrop="static" id="confirm-add-admin" tabindex="-1" role="dialog" aria-hidden="true" class="modal fade">-->
    <div id="open" class="modal" data-easein="perspectiveDownIn"  tabindex="-1" role="dialog" aria-labelledby="costumModalLabel" aria-hidden="true">
        <form class="form-horizontal" role="form" method="post" action="">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-body">
                        <?php include 'layouts/feedback_message.php'; ?>

                        <h3 class="text-center"><strong>INSTAFXNG INDEPENDENCE PROMO</strong></h3>

                        <p class="text-center">Enter Your INSTAFOREX ILPR account number.</p>
                        <div class="form-group">
                            <div class="col-sm-12 col-lg-12">
                                <input maxlength="10" value="" placeholder="INSTAFOREX ACCOUNT NUMBER" name="acct_no" type="text" class="form-control" id="email" required>
                            </div>
                        </div>

                    </div>

                    <div class="modal-footer">
                        <button type="submit" name="opt_in" class="btn btn-success">JOIN</button>
                    </div>
                </div>
            </div>
        </form>
    </div>
    <script>$(document).ready(function () {
            $('#open').modal("show");
        });</script>
<!--//////////////////////////////-->


<div class="wthree_copy_right">
    <div class="container">
        <hr />
        <p class="text-center">&copy; 2018 Instant Web Net Technologies. All rights reserved <a href="http://Instafxng.com/">Instafxng.com</a>
            Contact us on 08028281192.
        </p>
    </div>
</div>
</body>
</html>