<?php
require_once("../init/initialize_admin.php");
if (!$session_admin->is_logged_in()) {
    redirect_to("login.php");
}

$get_params = allowed_get_params(['id']);
$user_code_encrypted = $get_params['id'];
$user_code = dec_enc('decrypt',  $user_code_encrypted);


$client_operation = new clientOperation();

if(is_null($user_code_encrypted) || empty($user_code_encrypted)) {
    redirect_to("./"); // page cannot display anything without the id
} else {

    $user_code = $db_handle->sanitizePost($user_code);
    $user_detail = $client_operation->get_user_by_user_code($user_code);
    
    if($user_detail) {

        extract($user_detail);

        if($middle_name) {
            $full_name = $last_name . ' ' . $middle_name . ' ' . $first_name;
        } else {
            $full_name = $last_name . ' ' . $first_name;
        }
        
        $total_client_account = $db_handle->numRows("SELECT ifx_acct_no FROM user_ifxaccount WHERE user_code = '$user_code'");
        $client_ilpr_account = $client_operation->get_client_ilpr_accounts_by_code($user_code);
        $client_non_ilpr_account = $client_operation->get_client_non_ilpr_accounts_by_code($user_code);
        $client_address = $client_operation->get_user_address_by_code($user_code);
        $client_verification = $client_operation->get_client_verification_status($user_code);
        $client_credential = $client_operation->get_user_credential($user_code);
        $last_trade_date = $client_operation->get_last_trade_detail($user_code)['date_earned'];

        switch($client_verification) {
            case '0': $verification_level = "Not Verified"; break;
            case '1': $verification_level = "Level 1 Verified"; break;
            case '2': $verification_level = "Level 2 Verified"; break;
            case '3': $verification_level = "Level 3 Verified"; break;
        }
    } else {
        // No record for that client, it is possible that URL is tampered
        redirect_to("./");
    }
}

$sum_successful_withdrawal = $system_object->get_sum_client_completed_withdrawal($user_code);
$sum_successful_funding = $system_object->get_sum_client_completed_funding($user_code);

$successful_withdrawal = $system_object->get_client_completed_withdrawal($user_code);
$successful_funding = $system_object->get_client_completed_funding($user_code);


if(isset($_POST['declare_doc'])) {

    $passport = $client_credential['passport'];
    $signature = $client_credential['signature'];
    $date = datetime_to_text($client_credential['updated']);
    $client_name = $user_detail['last_name'] . ' ' . $user_detail['middle_name'] . ' ' . $user_detail['first_name'];
    $full_address = $client_address['address'] . ' ' . $client_address['address2'] . ' ' . $client_address['city'] . ' ' . $client_address['state'];

    $message_final = <<<MAIL
<div>
    <div style="max-width: 80%; margin: 0 auto; padding: 10px; font-size: 14px; font-family: Verdana;">
        <p><strong>Client Declaration</strong></p>
        <hr />
        <div style="background-color: #FFFFFF; padding: 15px; margin: 5px 0 5px 0;">
            <p><img src="https://instafxng.com/userfiles/$passport" width="186" height="191"></p>
            <p>
            I, <strong>$client_name</strong> of <strong>$full_address</strong> declare that all the information I have submitted to Instant Web-Net Technologies Limited are genuine and true.
            <p> I declare that I shall only use any information including bank account belonging to Instant Web-Net Technologies Limited which may come into my possession through the use of this website (www.instafxng.com) for legitimate business activities including making deposits and withdrawal to and from my Instaforex account.</p>
            <p> I further declare that I shall not use the Website or any other information belonging to Instant Web-Net Technologies Limited for any fraudulent or malicious purposes.
                I willingly make this declaration on <strong>$date</strong>.
            </p>
            <p><img src="https://instafxng.com/userfiles/$signature" width="146" height="91"></p>
            <p>Signature</p>
        </div>
        <hr />
        <div style="background-color: #EBDEE9;">
            <div style="font-size: 11px !important; padding: 15px; text-align: center">
                <p>Instant Web-Net Technologies Ltd</p>
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

    $date_now = datetime_to_text(date('Y-m-d H:i:s'));

    $mpdf->SetFooter("Date Generated: " . $date_now . " - {PAGENO}");

    $mpdf->WriteHTML($message_final);
    $mpdf->Output($full_name . ' - Declaration.pdf', \Mpdf\Output\Destination::DOWNLOAD);
}

if(isset($_POST['biodata_doc'])) {

    $passport = $client_credential['passport'];
    $signature = $client_credential['signature'];
    $idcard = $client_credential['idcard'];
    $date = datetime_to_text($client_credential['updated']);
    $client_name = $user_detail['last_name'] . ' ' . $user_detail['middle_name'] . ' ' . $user_detail['first_name'];
    $full_address = $client_address['address'] . ' ' . $client_address['address2'] . ' ' . $client_address['city'] . ' ' . $client_address['state'];
    $date_registered = datetime_to_text2($created);
    $total_ifx_accounts = number_format($total_client_account);
    $successful_funding_naira = number_format($sum_successful_funding['total_naira'], 2, ".", ",");
    $successful_withdrawal_naira = number_format($sum_successful_withdrawal['total_naira'], 2, ".", ",");

    $identification_docs = <<<MAIL
<div>
    <div style="max-width: 80%; margin: 0 auto; padding: 10px; font-size: 14px; font-family: Verdana;">
        <p><strong>Client Biodata</strong></p>
        <hr />
        <h4 style="text-align: center">$full_name</h4>

        <div class="row">
            <table style="border: 1px solid black; border-collapse: collapse; width: 100%">
                <thead>
                <tr><th style="border: 1px solid black; padding: 5px;"></th></tr>
                </thead>
                <tbody>
                    <tr>
                        <td style="border: 1px solid black; padding: 5px; text-align: center;">
                            <p>ID Card</p>
                            <p><img src="https://instafxng.com/userfiles/$idcard" width="400px" /></p>
                        </td>
                    </tr>
                    <tr>
                        <td style="border: 1px solid black; padding: 5px; text-align: center;">
                            <p>Passport</p>
                            <p><img src="https://instafxng.com/userfiles/$passport" width="400px" /></p>
                        </td>
                    </tr>
                    <tr>
                        <td style="border: 1px solid black; padding: 5px; text-align: center;">
                            <p>Signature</p>
                            <p><img src="https://instafxng.com/userfiles/$signature" width="400px" /></p>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
        <br />
    </div>
</div>
MAIL;

    $message_final = <<<MAIL
<div>
    <div style="max-width: 80%; margin: 0 auto; padding: 10px; font-size: 14px; font-family: Verdana;">

        <h4 style="text-align: center">$full_name</h4>

        <div>
            <table style="border: 1px solid black; border-collapse: collapse; width: 100%">
                <thead>
                <tr>
                    <th style="border: 1px solid black; padding: 5px; text-align: center;"></th>
                    <th style="border: 1px solid black; padding: 5px; text-align: center;"></th>
                </tr>
                </thead>
                <tbody>
                <tr><td style="border: 1px solid black; padding: 5px;"><strong>Client Name</strong></td><td style="border: 1px solid black; padding: 5px;">$full_name</td></tr>
                <tr><td style="border: 1px solid black; padding: 5px;"><strong>Email Address</strong></td><td style="border: 1px solid black; padding: 5px;">$email</td></tr>
                <tr><td style="border: 1px solid black; padding: 5px;"><strong>Phone Number</strong></td><td style="border: 1px solid black; padding: 5px;">$phone</td></tr>
                <tr><td style="border: 1px solid black; padding: 5px;"><strong>Opening Date</strong></td><td style="border: 1px solid black; padding: 5px;">$date_registered</td></tr>
                <tr><td style="border: 1px solid black; padding: 5px;"><strong>Client Address</strong></td><td style="border: 1px solid black; padding: 5px;">$full_address</td></tr>
                <tr><td style="border: 1px solid black; padding: 5px;"><strong>Verification Status</strong></td><td style="border: 1px solid black; padding: 5px;">$verification_level</td></tr>
                <tr><td style="border: 1px solid black; padding: 5px;"><strong>IFX Accounts Added</strong></td><td style="border: 1px solid black; padding: 5px;">$total_ifx_accounts</td></tr>
                <tr><td style="border: 1px solid black; padding: 5px;"><strong>Successful Funding</strong></td><td style="border: 1px solid black; padding: 5px;">N$successful_funding_naira </td></tr>
                <tr><td style="border: 1px solid black; padding: 5px;"><strong>Successful Withdrawal</strong></td><td style="border: 1px solid black; padding: 5px;">N$successful_withdrawal_naira </td></tr>
                </tbody>
            </table>
        </div>

        <hr />
        <div style="background-color: #EBDEE9;">
            <div style="font-size: 11px !important; padding: 15px; text-align: center">
                <p>Instant Web-Net Technologies Ltd</p>
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
    $mpdf->SetTitle("Client Biodata - Instafxng.com");
    $mpdf->SetAuthor("Instant Web-Net Technologies Ltd");
    $mpdf->SetWatermarkText("Confidential - Instafxng");
    $mpdf->showWatermarkText = true;
    $mpdf->watermark_font = 'DejaVuSansCondensed';
    $mpdf->watermarkTextAlpha = 0.1;
    $mpdf->SetDisplayMode('fullpage');

    $date_now = datetime_to_text(date('Y-m-d H:i:s'));

    $mpdf->SetFooter("Date Generated: " . $date_now . " - {PAGENO}");

    $mpdf->WriteHTML($identification_docs);
    $mpdf->WriteHTML('<pagebreak />');
    $mpdf->WriteHTML($message_final);
    $mpdf->Output($full_name . ' - Biodata.pdf', \Mpdf\Output\Destination::DOWNLOAD);
}


if(isset($_POST['main_funding_doc'])) {

    $full_address = $client_address['address'] . ' ' . $client_address['address2'] . ' ' . $client_address['city'] . ' ' . $client_address['state'];
    $successful_funding_naira = number_format($sum_successful_funding['total_naira'], 2, ".", ",");
    $successful_withdrawal_naira = number_format($sum_successful_withdrawal['total_naira'], 2, ".", ",");

    $header = <<<HEADER
<div>
    <div style="max-width: 80%; margin: 0 auto; padding: 10px; font-size: 14px; font-family: Verdana;">


        <h3>
        Client Name: $full_name <br />
        Phone Number: $phone<br />
        Address: $full_address<br />
        </h3>

        <div>
            <table style="border: 1px solid black; border-collapse: collapse; width: 100%">
                <thead>
                <tr>
                    <th style="border: 1px solid black; padding: 5px; text-align: center;">Transaction ID</th>
                    <th style="border: 1px solid black; padding: 5px; text-align: center;">Client Name</th>
                    <th style="border: 1px solid black; padding: 5px; text-align: center;">IFX Account</th>
                    <th style="border: 1px solid black; padding: 5px; text-align: center;">Amount Confirmed</th>
                    <th style="border: 1px solid black; padding: 5px; text-align: center;">Total Confirmed</th>
                    <th style="border: 1px solid black; padding: 5px; text-align: center;">Date Created</th>
                </tr>

                </thead>
                <tbody>
HEADER;


    $body = <<<BODY
BODY;

if(isset($successful_funding) && !empty($successful_funding)) {
    $counter = 1;
    $prev_month = "";

    foreach ($successful_funding as $row) {
        $g_trans_id = $row['trans_id'];
        $g_full_name = $row['full_name'];
        $g_ifx_acct_no = $row['ifx_acct_no'];
        $g_real_dollar_equivalent = number_format($row['real_dollar_equivalent'], 2, ".", ",");
        $g_real_naira_confirmed = number_format($row['real_naira_confirmed'], 2, ".", ",");
        $g_created = datetime_to_text($row['created']);

        $strip_date = $row['strip_date'];
        $myArray = explode('-', $strip_date);

        $year = $myArray[0];
        $month = $myArray[1];
        $day = $myArray[2];

        $dateObj   = DateTime::createFromFormat('!m', $month);
        $monthName = $dateObj->format('F');

        $g_date = "<p style='font-size: 1.5em'><strong>" . $monthName . ', ' . $year . "</strong></p>";

        $g_month = $month;

        if($prev_month != $g_month) {
            $display_head = true;
        } else {
            $display_head = false;
        }

        if($counter == 1 || $display_head == true) {
            $body .= "<tr><td colspan = \"6\" style=\"border: 0; padding: 15px;\">$g_date</td></tr>";
        }

        $body .= "<tr>
            <td style=\"border: 1px solid black; padding: 5px; text-align: center;\">$g_trans_id</td>
            <td style=\"border: 1px solid black; padding: 5px; text-align: center;\">$g_full_name</td>
            <td style=\"border: 1px solid black; padding: 5px; text-align: center;\">$g_ifx_acct_no</td>
            <td style=\"border: 1px solid black; padding: 5px; text-align: center;\">($) $g_real_dollar_equivalent</td>
            <td style=\"border: 1px solid black; padding: 5px; text-align: center;\">N $g_real_naira_confirmed</td>
            <td style=\"border: 1px solid black; padding: 5px; text-align: center;\">$g_created</td>
        </tr>";

        $counter++;
        $prev_month = $g_month;
    }
}

    $footer = <<<FOOTER
        </tbody>
        </table>

        <pagebreak>

        <div>
            <table style="border: 1px solid black; border-collapse: collapse; width: 100%">
                <thead>
                <tr>
                    <th style="border: 1px solid black; padding: 5px; text-align: center;"></th>
                    <th style="border: 1px solid black; padding: 5px; text-align: center;"></th>
                </tr>
                </thead>
                <tbody>
                <tr><td style="border: 1px solid black; padding: 5px;"><strong>Client Name</strong></td><td style="border: 1px solid black; padding: 5px;">$full_name</td></tr>
                <tr><td style="border: 1px solid black; padding: 5px;"><strong>Email Address</strong></td><td style="border: 1px solid black; padding: 5px;">$email</td></tr>
                <tr><td style="border: 1px solid black; padding: 5px;"><strong>Phone Number</strong></td><td style="border: 1px solid black; padding: 5px;">$phone</td></tr>
                <tr><td style="border: 1px solid black; padding: 5px;"><strong>Address</strong></td><td style="border: 1px solid black; padding: 5px;">$full_address</td></tr>
                <tr><td style="border: 1px solid black; padding: 5px;"><strong>Successful Funding</strong></td><td style="border: 1px solid black; padding: 5px;">N$successful_funding_naira</td></tr>
                <tr><td style="border: 1px solid black; padding: 5px;"><strong>Successful Withdrawal</strong></td><td style="border: 1px solid black; padding: 5px;">N$successful_withdrawal_naira </td></tr>
                </tbody>
            </table>
        </div>

        </div>
        <hr />
        <div style="background-color: #EBDEE9;">
            <div style="font-size: 11px !important; padding: 15px; text-align: center">
                <p>Instant Web-Net Technologies Ltd</p>
            </div>
        </div>
    </div>
</div>
FOOTER;

    $message_final = $header . $body . $footer;

    $mpdf = new \Mpdf\Mpdf([
        'margin_left' => 15,
        'margin_right' => 15,
        'margin_top' => 20,
        'margin_bottom' => 20,
        'margin_header' => 10,
        'margin_footer' => 10
    ]);
    $mpdf->SetProtection(array('print'));
    $mpdf->SetTitle("Client Deposits - Instafxng.com");
    $mpdf->SetAuthor("Instant Web-Net Technologies Ltd");
    $mpdf->SetWatermarkText("Confidential - Instafxng");
    $mpdf->showWatermarkText = true;
    $mpdf->watermark_font = 'DejaVuSansCondensed';
    $mpdf->watermarkTextAlpha = 0.1;
    $mpdf->SetDisplayMode('fullpage');

    $date_now = datetime_to_text(date('Y-m-d H:i:s'));

    $mpdf->SetFooter("Date Generated: " . $date_now . " - {PAGENO}");

    $mpdf->WriteHTML($message_final);
    $mpdf->Output($full_name . ' - Deposits.pdf', \Mpdf\Output\Destination::DOWNLOAD);

}

if(isset($_POST['main_withdrawal_doc'])) {

    $full_address = $client_address['address'] . ' ' . $client_address['address2'] . ' ' . $client_address['city'] . ' ' . $client_address['state'];
    $successful_funding_naira = number_format($sum_successful_funding['total_naira'], 2, ".", ",");
    $successful_withdrawal_naira = number_format($sum_successful_withdrawal['total_naira'], 2, ".", ",");

    $header = <<<HEADER
<div>
    <div style="max-width: 80%; margin: 0 auto; padding: 10px; font-size: 14px; font-family: Verdana;">


        <h3>
        Client Name: $full_name <br />
        Phone Number: $phone<br />
        Address: $full_address<br />
        </h3>

        <div>
            <table style="border: 1px solid black; border-collapse: collapse; width: 100%">
                <thead>
                <tr>
                    <th style="border: 1px solid black; padding: 5px; text-align: center;">Transaction ID</th>
                    <th style="border: 1px solid black; padding: 5px; text-align: center;">Client Name</th>
                    <th style="border: 1px solid black; padding: 5px; text-align: center;">IFX Account</th>
                    <th style="border: 1px solid black; padding: 5px; text-align: center;">Amount Withdrawn</th>
                    <th style="border: 1px solid black; padding: 5px; text-align: center;">Total Paid</th>
                    <th style="border: 1px solid black; padding: 5px; text-align: center;">Date</th>
                </tr>

                </thead>
                <tbody>
HEADER;


    $body = <<<BODY
BODY;

    if(isset($successful_withdrawal) && !empty($successful_withdrawal)) {
        $counter = 1;
        $prev_month = "";

        foreach ($successful_withdrawal as $row) {
            $g_trans_id = $row['trans_id'];
            $g_full_name = $row['full_name'];
            $g_ifx_acct_no = $row['ifx_acct_no'];
            $g_dollar_withdraw = number_format($row['dollar_withdraw'], 2, ".", ",");
            $g_total_payable = number_format($row['naira_total_withdrawable'], 2, ".", ",");
            $g_created = datetime_to_text($row['created']);

            $strip_date = $row['strip_date'];
            $myArray = explode('-', $strip_date);

            $year = $myArray[0];
            $month = $myArray[1];
            $day = $myArray[2];

            $dateObj   = DateTime::createFromFormat('!m', $month);
            $monthName = $dateObj->format('F');

            $g_date = "<p style='font-size: 1.5em'><strong>" . $monthName . ', ' . $year . "</strong></p>";

            $g_month = $month;

            if($prev_month != $g_month) {
                $display_head = true;
            } else {
                $display_head = false;
            }

            if($counter == 1 || $display_head == true) {
                $body .= "<tr><td colspan = \"6\" style=\"border: 0; padding: 15px;\">$g_date</td></tr>";
            }

            $body .= "<tr>
            <td style=\"border: 1px solid black; padding: 5px; text-align: center;\">$g_trans_id</td>
            <td style=\"border: 1px solid black; padding: 5px; text-align: center;\">$g_full_name</td>
            <td style=\"border: 1px solid black; padding: 5px; text-align: center;\">$g_ifx_acct_no</td>
            <td style=\"border: 1px solid black; padding: 5px; text-align: center;\">($) $g_dollar_withdraw</td>
            <td style=\"border: 1px solid black; padding: 5px; text-align: center;\">N $g_total_payable</td>
            <td style=\"border: 1px solid black; padding: 5px; text-align: center;\">$g_created</td>
        </tr>";

            $counter++;
            $prev_month = $g_month;
        }
    }

    $footer = <<<FOOTER
        </tbody>
        </table>

        <pagebreak>

        <div>
            <table style="border: 1px solid black; border-collapse: collapse; width: 100%">
                <thead>
                <tr>
                    <th style="border: 1px solid black; padding: 5px; text-align: center;"></th>
                    <th style="border: 1px solid black; padding: 5px; text-align: center;"></th>
                </tr>
                </thead>
                <tbody>
                <tr><td style="border: 1px solid black; padding: 5px;"><strong>Client Name</strong></td><td style="border: 1px solid black; padding: 5px;">$full_name</td></tr>
                <tr><td style="border: 1px solid black; padding: 5px;"><strong>Email Address</strong></td><td style="border: 1px solid black; padding: 5px;">$email</td></tr>
                <tr><td style="border: 1px solid black; padding: 5px;"><strong>Phone Number</strong></td><td style="border: 1px solid black; padding: 5px;">$phone</td></tr>
                <tr><td style="border: 1px solid black; padding: 5px;"><strong>Address</strong></td><td style="border: 1px solid black; padding: 5px;">$full_address</td></tr>
                <tr><td style="border: 1px solid black; padding: 5px;"><strong>Successful Funding</strong></td><td style="border: 1px solid black; padding: 5px;">N$successful_funding_naira</td></tr>
                <tr><td style="border: 1px solid black; padding: 5px;"><strong>Successful Withdrawal</strong></td><td style="border: 1px solid black; padding: 5px;">N$successful_withdrawal_naira </td></tr>
                </tbody>
            </table>
        </div>

        </div>
        <hr />
        <div style="background-color: #EBDEE9;">
            <div style="font-size: 11px !important; padding: 15px; text-align: center">
                <p>Instant Web-Net Technologies Ltd</p>
            </div>
        </div>
    </div>
</div>
FOOTER;

    $message_final = $header . $body . $footer;

    $mpdf = new \Mpdf\Mpdf([
        'margin_left' => 15,
        'margin_right' => 15,
        'margin_top' => 20,
        'margin_bottom' => 20,
        'margin_header' => 10,
        'margin_footer' => 10
    ]);
    $mpdf->SetProtection(array('print'));
    $mpdf->SetTitle("Client Withdrawals - Instafxng.com");
    $mpdf->SetAuthor("Instant Web-Net Technologies Ltd");
    $mpdf->SetWatermarkText("Confidential - Instafxng");
    $mpdf->showWatermarkText = true;
    $mpdf->watermark_font = 'DejaVuSansCondensed';
    $mpdf->watermarkTextAlpha = 0.1;
    $mpdf->SetDisplayMode('fullpage');

    $date_now = datetime_to_text(date('Y-m-d H:i:s'));

    $mpdf->SetFooter("Date Generated: " . $date_now . " - {PAGENO}");

    $mpdf->WriteHTML($message_final);
    $mpdf->Output($full_name . ' - Withdrawals.pdf', \Mpdf\Output\Destination::DOWNLOAD);

}

?>
<!DOCTYPE html>
<html lang="en">
    <head>
        <base target="_self">
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>Instaforex Nigeria | Admin - View Client Analysis</title>
        <meta name="title" content="Instaforex Nigeria | Admin - View Client Analysis" />
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
                            <h4><strong>VIEW CLIENT ANALYSIS</strong></h4>
                        </div>
                    </div>
                    
                    <div class="section-tint super-shadow">
                        <div class="row">
                            <div class="col-sm-12">
                                <?php require_once 'layouts/feedback_message.php'; ?>
                                <p><a href="<?php echo $_SERVER['HTTP_REFERER']; ?>" class="btn btn-default" title="Go Back"><i class="fa fa-arrow-circle-left"></i> Go Back</a></p>

                                <p>Below is the analysis of the selected client. You can click to download pdf of important documents.</p>
                                <hr />
                                
                                <!------------- Contact Section --->
                                <div class="row">

                                    <div class="col-sm-6">
                                        <div class="row">
                                            <div class="col-sm-4 text-center" style="margin-bottom: 4px !important">
                                                <span>Identity Card</span>
                                                <?php if(!empty($client_credential['idcard'])) { ?>
                                                <a href="../userfiles/<?php echo $client_credential['idcard']; ?>" title="click to enlarge" target="_blank"><img src="../userfiles/<?php echo $client_credential['idcard']; ?>" width="120px" height="120px"/></a>
                                                <?php } else { ?>
                                                <img src="../images/placeholder.jpg" alt="" class="img-responsive center-block">
                                                <?php } ?>
                                            </div>
                                            <div class="col-sm-4 text-center" style="margin-bottom: 4px !important">
                                                <span>Passport</span>
                                                <?php if(!empty($client_credential['passport'])) { ?>
                                                <a href="../userfiles/<?php echo $client_credential['passport']; ?>" title="click to enlarge" target="_blank"><img src="../userfiles/<?php echo $client_credential['passport']; ?>" width="120px" height="120px"/></a>
                                                <?php } else { ?>
                                                <img src="../images/placeholder.jpg" alt="" class="img-responsive center-block">
                                                <?php } ?>
                                            </div>
                                            <div class="col-sm-4 text-center" style="margin-bottom: 4px !important">
                                                <span>Signature</span>
                                                <?php if(!empty($client_credential['signature'])) { ?>
                                                <a href="../userfiles/<?php echo $client_credential['signature']; ?>" title="click to enlarge" target="_blank"><img src="../userfiles/<?php echo $client_credential['signature']; ?>" width="120px" height="120px"/></a>
                                                <?php } else { ?>
                                                <img src="../images/placeholder.jpg" alt="" class="img-responsive center-block">
                                                <?php } ?>
                                            </div>
                                        </div>

                                        <div class="row">
                                            <div class="col-sm-12">
                                                <table class="table table-responsive table-striped table-bordered table-hover">
                                                    <thead>
                                                    <tr>
                                                        <th></th>
                                                        <th></th>
                                                    </tr>
                                                    </thead>
                                                    <tbody>
                                                    <tr><td>Client Name</td><td><?php echo $full_name; ?></td></tr>
                                                    <tr><td>Email Address</td><td><?php echo $email; ?></td></tr>
                                                    <tr><td>Phone Number</td><td><?php echo $phone; ?></td></tr>
                                                    <tr><td>Opening Date</td><td><?php echo datetime_to_text2($created); ?></td></tr>
                                                    <tr><td>Client Address</td><td><?php echo $client_address['address'] . ' ' . $client_address['address2'] . ' ' . $client_address['city'] . ' ' . $client_address['state']; ?></td></tr>
                                                    <tr><td>Verification Status</td><td><?php echo $verification_level; ?></td></tr>
                                                    </tbody>
                                                </table>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-sm-6">
                                        <div class="row">
                                            <div class="col-sm-12">
                                                <table class="table table-responsive table-striped table-bordered table-hover">
                                                    <thead>
                                                    <tr>
                                                        <th></th>
                                                        <th></th>
                                                    </tr>
                                                    </thead>
                                                    <tbody>
                                                    <tr><td>Total IFX Accounts</td><td><?php echo number_format($total_client_account); ?></td></tr>
                                                    <tr><td>ILPR Accounts</td><td><?php echo count($client_ilpr_account); ?></td></tr>
                                                    <tr><td>Non - ILPR Accounts</td><td><?php echo count($client_non_ilpr_account); ?></td></tr>
                                                    <tr><td>Last Trading Date</td><td><?php if($last_trade_date) { echo datetime_to_text2($last_trade_date); } else { echo 'Nil'; } ?></td></tr>
                                                    <tr><td>Successful Funding</td>
                                                        <td>
                                                            <?php if($sum_successful_funding) { echo "$" . number_format($sum_successful_funding['total_dollar'], 2, '.', ','); } else { echo 'Nil'; } ?><br />
                                                            <?php if($sum_successful_funding) { echo "&#8358;" . number_format($sum_successful_funding['total_naira'], 2, '.', ','); } else { echo 'Nil'; } ?>
                                                        </td>
                                                    </tr>
                                                    <tr><td>Successful Withdrawal</td>
                                                        <td>
                                                            <?php if($sum_successful_withdrawal) { echo "$" . number_format($sum_successful_withdrawal['total_dollar'], 2, '.', ','); } else { echo 'Nil'; } ?><br />
                                                            <?php if($sum_successful_withdrawal) { echo "&#8358;" . number_format($sum_successful_withdrawal['total_naira'], 2, '.', ','); } else { echo 'Nil'; } ?>

                                                        </td>
                                                    </tr>
                                                    </tbody>
                                                </table>

                                                <table class="table table-responsive table-striped table-bordered table-hover">
                                                    <thead>
                                                    <tr>
                                                        <th>Document</th>
                                                        <th>Action</th>
                                                    </tr>
                                                    </thead>
                                                    <tbody>
                                                    <tr><td>Declaration</td>
                                                        <td>
                                                            <form class="form-horizontal" role="form" method="post" action="">
                                                                <input type="submit" name="declare_doc" value="Download" class="btn btn-success" />
                                                            </form>
                                                        </td>
                                                    </tr>
                                                    <tr><td>Biodata</td>
                                                        <td>
                                                            <form class="form-horizontal" role="form" method="post" action="">
                                                                <input type="submit" name="biodata_doc" value="Download" class="btn btn-success" />
                                                            </form>
                                                        </td>
                                                    </tr>
                                                    <tr><td>Funding</td>
                                                        <td>
                                                            <form class="form-horizontal" role="form" method="post" action="">
                                                                <input type="submit" name="main_funding_doc" value="Download" class="btn btn-success" />
                                                            </form>
                                                        </td>
                                                    </tr>
                                                    <tr><td>Withdrawal</td>
                                                        <td>
                                                            <form class="form-horizontal" role="form" method="post" action="">
                                                                <input type="submit" name="main_withdrawal_doc" value="Download" class="btn btn-success" />
                                                            </form>
                                                        </td>
                                                    </tr>

                                                    </tbody>
                                                </table>


                                            </div>
                                        </div>
                                    </div>

                                </div>

                                <div class="row">
                                    <div class="col-sm-12">
                                        <p><strong>Completed Deposit Transactions</strong></p>
                                        <div style="max-height: 500px; overflow: scroll;">
                                            <table class="table table-responsive table-striped table-bordered table-hover">
                                                <thead>
                                                <tr>
                                                    <th></th>
                                                    <th>Transaction ID</th>
                                                    <th>Client Name</th>
                                                    <th>IFX Account</th>
                                                    <th>Amount Confirmed</th>
                                                    <th>Total Confirmed</th>
                                                    <th>Date Created</th>
                                                    <th>Order Completed Time</th>
                                                    <th>Action</th>
                                                </tr>
                                                </thead>
                                                <tbody>
                                                <?php
                                                if(isset($successful_funding) && !empty($successful_funding)) {
                                                    $deposit_count = 1;
                                                    foreach ($successful_funding as $row) {
                                                        $new_updated = date('Y-m-d H:i:s', $row['updated']);
                                                        ?>
                                                        <tr>
                                                            <td><?php echo $deposit_count; ?></td>
                                                            <td><?php echo $row['trans_id']; ?></td>
                                                            <td><?php echo $row['full_name']; ?></td>
                                                            <td><?php echo $row['ifx_acct_no']; ?></td>
                                                            <td class="nowrap">&dollar; <?php echo number_format($row['real_dollar_equivalent'], 2, ".", ","); ?></td>
                                                            <td class="nowrap">&#8358; <?php echo number_format($row['real_naira_confirmed'], 2, ".", ","); ?></td>
                                                            <td><?php echo datetime_to_text($row['created']); ?></td>
                                                            <td><?php echo datetime_to_text($row['order_complete_time']); ?></td>
                                                            <td>
                                                                <form class="form-horizontal" role="form" method="post" action="https://instafxng.com/admin/transactions_download.php" target="_blank">
                                                                    <input type="hidden" name="trans_id" value="<?php echo $row['trans_id']; ?>" />
                                                                    <input type="hidden" name="trans_type" value="D" />
                                                                    <input type="submit" name="deposit_trans_<?php echo $deposit_count; ?>" value="Download" class="btn btn-success" />
                                                                </form>
                                                            </td>
                                                        </tr>

                                                    <?php $deposit_count++; } } else { echo "<tr><td colspan='9' class='text-danger'><em>No results to display</em></td></tr>"; } ?>
                                                </tbody>
                                            </table>
                                            <br /><hr />
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-sm-12">
                                        <p><strong>Completed Withdrawal Transactions</strong></p>
                                        <div style="max-height: 500px; overflow: scroll;">
                                            <table class="table table-responsive table-striped table-bordered table-hover">
                                                <thead>
                                                <tr>
                                                    <th>Transaction ID</th>
                                                    <th>Client Name</th>
                                                    <th>IFX Account</th>
                                                    <th>Dollar Amount</th>
                                                    <th>Amount To Pay</th>
                                                    <th>Date Created</th>
                                                    <th>Last Updated</th>
                                                    <th>Action</th>
                                                </tr>
                                                </thead>
                                                <tbody>
                                                <?php
                                                if(isset($successful_withdrawal) && !empty($successful_withdrawal)) {
                                                    $withdrawal_count = 1;
                                                    foreach ($successful_withdrawal as $row) {
                                                        ?>
                                                        <tr>
                                                            <td><?php echo $row['trans_id']; ?></td>
                                                            <td><?php echo $row['full_name']; ?></td>
                                                            <td><?php echo $row['ifx_acct_no']; ?></td>
                                                            <td class="nowrap">&dollar; <?php echo number_format($row['dollar_withdraw'], 2, ".", ","); ?></td>
                                                            <td class="nowrap">&#8358; <?php echo number_format($row['naira_total_withdrawable'], 2, ".", ","); ?></td>
                                                            <td><?php echo datetime_to_text($row['created']); ?></td>
                                                            <td><?php echo datetime_to_text($row['updated']); ?></td>
                                                            <td>
                                                                <form class="form-horizontal" role="form" method="post" action="https://instafxng.com/admin/transactions_download.php" target="_blank">
                                                                    <input type="hidden" name="trans_id" value="<?php echo $row['trans_id']; ?>" />
                                                                    <input type="hidden" name="trans_type" value="W" />
                                                                    <input type="submit" name="deposit_trans_<?php echo $withdrawal_count; ?>" value="Download" class="btn btn-success" />
                                                                </form>
                                                            </td>
                                                        </tr>
                                                    <?php $withdrawal_count++; } } else { echo "<tr><td colspan='7' class='text-danger'><em>No results to display</em></td></tr>"; } ?>
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>


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