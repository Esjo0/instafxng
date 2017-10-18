<?php
require_once("../init/initialize_admin.php");
if (!$session_admin->is_logged_in()) {
    redirect_to("login.php");
}

$page_requested = "";

switch($page_requested) {
    case '': $deposit_ifx_transfer_trans_id_php = true; break;
    case 'deposit_ifx_transfer_summary_php': $deposit_ifx_transfer_summary_php = true; break;
    default: $deposit_ifx_transfer_trans_id_php = true;
}

$params = array(
    'request'=> array(
        'Amount' => 5,
        'TargetAccount' => 7807167,
        'TransactionId' => 'IFX_SLUDATEST_1'
    ),
    'account' => array(
        'Login' => 189035,
        'Password' => '3onxa7'
    )
);

$soap_client = new SoapClient('http://client-api.instaforex.com/soapservices/Umbrella.svc');

try {
    $result = $soap_client->SendTransfer($params);
    var_dump($result);
    var_dump('yes');
} catch(SoapFault $fault) {
    //Handle exception here
    echo $fault->faultstring;
    var_dump('no');
}

if(isset($_POST['deposit_ifx_transfer_trans_id'])) {

}

if(isset($_POST['deposit_ifx_transfer_summary'])) {



    $params = array(
        'request'=> array(
            'Amount' => 1,
            'TargetAccount' => 123456,
            'TransactionId' => '11'
        ),
        'account' => array(
            'Login' => 654321,
            'Password' => 'qwe123'
        )
    );

    $soap_client = new SoapClient('http://5.9.6.48/soapservices/Umbrella.svc?wsdl');

    try {
        $result = $soap_client->SendTransfer($params);
        var_dump($result);
    } catch(SoapFault $fault) {
        //Handle exception here
        echo $fault->faultstring;
    }
}

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <base target="_self">
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Instaforex Nigeria | Admin - IFX Transfer</title>
    <meta name="title" content="Instaforex Nigeria | Admin - IFX Transfer" />
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
                    <h4><strong>IFX Transfer</strong></h4>
                </div>
            </div>

            <div class="section-tint super-shadow">
                <div class="row">
                    <div class="col-sm-12">
                        <?php require_once 'layouts/feedback_message.php'; ?>

                        <?php
                        if($deposit_ifx_transfer_trans_id_php) { include_once 'views/deposit_ifx_transfer/deposit_ifx_transfer_trans_id.php'; }
                        if($deposit_ifx_transfer_summary_php) { include_once 'views/deposit_ifx_transfer/deposit_ifx_transfer_summary.php'; }
                        ?>

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