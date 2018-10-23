<?php
require_once 'init/initialize_client.php';
$thisPage = "";

$get_params = allowed_get_params(['x', 'id']);

$trans_id_encrypted = $get_params['id'];
$trans_id = decrypt(str_replace(" ", "+", $trans_id_encrypted));
$trans_id = preg_replace("/[^A-Za-z0-9 ]/", '', $trans_id);

$system_object = new InstafxngSystem();
//Ensure only those that have an initiated refund can access this page
if (!empty($trans_id_encrypted) && !empty($refund_type)) {
    //since GET values are set, we will confirm if its a true refund transaction
    $query = "SELECT * FROM user_deposit_refund WHERE transaction_id = '$trans_id' AND refund_status = '0' LIMIT 1";
    $num_rows = $db_handle->numRows($query);
    //get  user credentials
    $query = "SELECT U.email, UC.passport, CONCAT(U.first_name, SPACE(1), U.last_name) AS client_name, U.middle_name,
UM.address, UC.signature
        FROM user_ifxaccount AS UI
        INNER JOIN user_deposit AS UD ON UD.ifxaccount_id = UI.ifxaccount_id
        INNER JOIN user AS U ON UI.user_code = U.user_code
        INNER JOIN user_meta AS UM ON UI.user_code = UM.user_code
        INNER JOIN user_credential AS UC ON UI.user_code = UC.user_code
         WHERE UD.trans_id = '$trans_id'";
    $result = $db_handle->fetchAssoc($db_handle->runQuery($query))[0];
    extract($result);

    if($num_rows != 1) {
//         No record found. Redirect to the home page.
        redirect_to("./");
        exit;
    }

} else {
//    Redirect to homepage - user trying to access page directly without been sent a link
    redirect_to("./");
    exit;
}
if(isset($_POST['submit'])){
    $date_now = datetime_to_text(date('Y-m-d H:i:s'));
    $declaration = <<<MAIL
 <div class="row">
                                <div class="col-sm-3">
                                </div>
                                <div class="col-sm-6">
                                    <div class="row">
                                        <p><img src="images/ifxlogo.png" class="img img-responsive pull-left" ></p>
                                        <p><img class="pull-right" src="userfiles/$passport" width="186" height="191"></p>
                                    </div>
                                    <div class="row">
                                        <p class="text-center"><strong>REFUND DECLARATION FOR TRANSACTION - $trans_id</strong></p>
                                        <p class="text-justify">
                                            I, <strong>$client_name</strong> of <strong>$client_address</strong> declare that all the information I have submitted on InstaFxNg Website(www.instafxng.com) are genuine and true.
                                        <p class="text-justify">I declare that all information provided is true to the best of my knowledge and I had no fraudulent intentions in the course of funding my account.</p>
                                        <p class="text-justify"> I further declare that I shall not use the Website or any other information belonging to the website for any fraudulent or malicious purposes.
                                            I willingly make this declaration on $date_now'
                                        </p>
                                        <p><img src="userfiles/$signature" width="146" height="91"></p>
                                    </div>
                                </div>
                                <div class="col-sm-3">
                                </div>
                            </div>
MAIL;
//content fo rthe email attachement
    $mail_message = <<<MAIL
<div style="background-color: #F3F1F2">
    <div style="max-width: 80%; margin: 0 auto; padding: 10px; font-size: 14px; font-family: Verdana;">
        <img src="https://instafxng.com/images/ifxlogo.png" />
        <hr />
        <div style="background-color: #FFFFFF; padding: 15px; margin: 5px 0 5px 0;">
            <p>Dear $client_name,</p>

            <p>Kindly find attached a copy of your refund declaration.
            For the refund of your deposit Transaction with Transaction ID : $trans_id .</p>
            <br /><br />
            <p>Best Regards,</p>
            <p>InstaFxNg Support,<br />
               www.instafxng.com</p>
            <br /><br />
        </div>
        <hr />
        <div style="background-color: #EBDEE9;">
            <div style="font-size: 11px !important; padding: 15px;">
                <p style="text-align: center"><span style="font-size: 12px"><strong>We're Social</strong></span><br /><br />
                    <a href="https://facebook.com/InstaForexNigeria"><img src="https://instafxng.com/images/Facebook.png"></a>
                    <a href="https://twitter.com/instafxng"><img src="https://instafxng.com/images/Twitter.png"></a>
                    <a href="https://www.instagram.com/instafxng/"><img src="https://instafxng.com/images/instagram.png"></a>
                    <a href="https://www.youtube.com/channel/UC0Z9AISy_aMMa3OJjgX6SXw"><img src="https://instafxng.com/images/Youtube.png"></a>
                    <a href="https://linkedin.com/company/instaforex-ng"><img src="https://instafxng.com/images/LinkedIn.png"></a>
                </p>
                <p><strong>Head Office Address:</strong> TBS Place, Block 1A, Plot 8, Diamond Estate, Estate Bus-Stop, LASU/Isheri road, Isheri Olofin, Lagos.</p>
                <p><strong>Lekki Office Address:</strong> Block A3, Suite 508/509 Eastline Shopping Complex, Opposite Abraham Adesanya Roundabout, along Lekki - Epe expressway, Lagos.</p>
                <p><strong>Office Number:</strong> 08139250268, 08083956750</p>
                <br />
            </div>
            <div style="font-size: 10px !important; padding: 15px; text-align: center;">
                <p>This email was sent to you by Instant Web-Net Technologies Limited, the
                    official Nigerian Representative of Instaforex, operator and administrator
                    of the website www.instafxng.com</p>
                <p>To ensure you continue to receive special offers and updates from us,
                    please add support@instafxng.com to your address book.</p>
            </div>
        </div>
    </div>
</div>
MAIL;

        $mpdf = new \Mpdf\Mpdf([
            'margin_left' => 15,
            'margin_right' => 15,
            'margin_top' => 20,
            'margin_bottom' => 20,
            'margin_header' => 10,
            'margin_footer' => 10
        ]);
        $mpdf->SetProtection(array('print'));
        $mpdf->SetTitle("Client Declaration - Instafxng.com");
        $mpdf->SetAuthor("Instant Web-Net Technologies Ltd");
        $mpdf->SetWatermarkText("Confidential - Instafxng");
        $mpdf->showWatermarkText = true;
        $mpdf->watermark_font = 'DejaVuSansCondensed';
        $mpdf->watermarkTextAlpha = 0.1;
        $mpdf->SetDisplayMode('fullpage');

        $mpdf->SetFooter("Date Generated: " . $date_now . " - {PAGENO}");

        $mpdf->WriteHTML($declaration);
        $mpdf->Output('/home/tboy9/public_html/models/refund_declarations/'.$trans_id. ' - Refund Declaration.pdf', \Mpdf\Output\Destination::FILE);// save a copy of the file
        $system_object->send_email('Refund Declaraton', $mail_message, $email, $client_name, 'Instafxng', '/home/tboy9/public_html/models/refund_declarations/'.$trans_id. ' - Refund Declaration.pdf');//send to clients email
        $mpdf->Output($trans_id. ' - Refund Declaration.pdf', \Mpdf\Output\Destination::DOWNLOAD);//open download option for clients.
}
?>
<!DOCTYPE html>
<html lang="en">
    <head>
        <base target="_self">
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>Instaforex Nigeria</title>
        <meta name="title" content="" />
        <meta name="keywords" content="">
        <meta name="description" content="">
        <?php require_once 'layouts/head_meta.php'; ?>

    </head>
    <body>
    <?php require_once 'layouts/header.php'; ?>
    <!-- Main Body: The is the main content area of the web site, contains a side bar  -->
    <div id="main-body" class="container-fluid">
        <div class="row no-gutter">
            <?php require_once 'layouts/topnav.php'; ?>
            <!-- Main Body - Content Area: This is the main content area, unique for each page  -->
            <div id="main-body-content-area" class="col-md-12">

                <!-- Unique Page Content Starts Here
                ================================================== -->

                <div class="section-tint super-shadow">
                            <div class="row">
                                <div class="col-sm-3">
                                </div>
                                <div class="col-sm-6">
                                    <div class="row">
                                        <p><img src="images/ifxlogo.png" class="img img-responsive pull-left" ></p>
                                        <p><img class="pull-right" src="userfiles/<?php echo $passport; ?>" width="186" height="191"></p>
                                    </div>
                                    <div class="row">
                                        <p class="text-center"><strong>Read and Accept Declaration</strong></p>
                                        <p class="text-justify">
                                            I, <strong><?php echo $client_name; ?></strong> of <strong><?php echo $client_address; ?></strong> declare that all the information I have submitted on InstaFxNg Website(www.instafxng.com) are genuine and true.
                                        <p class="text-justify">I declare that all information provided is true to the best of my knowledge and I had no fraudulent intentions in the course of funding my account.</p>
                                        <p class="text-justify"> I further declare that I shall not use the Website or any other information belonging to the website for any fraudulent or malicious purposes.
                                            I willingly make this declaration on <?php echo date('d-M-Y'); ?>
                                        </p>
                                        <p><img src="userfiles/<?php echo $signature; ?>" width="146" height="91"></p>
                                    </div>
                                    <div class="row text-center">
                                        <form role="form" method="post" action="<?php echo $_SERVER['REQUEST_URI']; ?>">
                                            <input type="hidden" name="ifx_acct_no" value="<?php if(isset($account_no)) { echo $account_no; } ?>" />
                                            <div class="form-group">
                                                <button name="submit" type="submit" class="btn btn-md btn-success">Accept Declaration</button>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                                <div class="col-sm-3">
                                </div>
                            </div>
                        </div>
                    </div>
            </div>
        </div>
        <?php require_once 'layouts/footer.php'; ?>
    </body>
</html>