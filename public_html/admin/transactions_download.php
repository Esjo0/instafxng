<?php
require_once("../init/initialize_admin.php");
if (!$session_admin->is_logged_in()) {
    redirect_to("login.php");
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    foreach($_POST as $key => $value) {
        $_POST[$key] = $db_handle->sanitizePost(trim($value));
    }

    extract($_POST);

    if($trans_type == 'D') {

        $query = "SELECT ud.trans_id, ud.dollar_ordered, ud.created, ud.naira_total_payable, ud.real_dollar_equivalent, ud.real_naira_confirmed,
        ud.client_naira_notified, ud.client_pay_date, ud.client_reference, ud.client_pay_method, ud.naira_equivalent_dollar_ordered,
        ud.client_notified_date, ud.points_claimed_id, ud.transfer_reference, ud.status AS deposit_status, u.user_code, ud.updated,
        ui.ifx_acct_no, CONCAT(u.last_name, SPACE(1), u.first_name) AS full_name, u.phone, u.email
        FROM user_deposit AS ud
        INNER JOIN user_ifxaccount AS ui ON ud.ifxaccount_id = ui.ifxaccount_id
        INNER JOIN user AS u ON ui.user_code = u.user_code
        WHERE ud.trans_id = '$trans_id'";
        $result = $db_handle->runQuery($query);

        if($db_handle->numOfRows($result) > 0) {
            // result found, display transaction details
            $fetched_data = $db_handle->fetchAssoc($result);
            $trans_detail = $fetched_data[0];
            extract($trans_detail);
        }

        $created = datetime_to_text($created);
        $naira_equivalent_dollar_ordered = number_format($naira_equivalent_dollar_ordered, 2, ".", ",");
        $naira_total_payable = number_format($naira_total_payable, 2, ".", ",");
        $client_naira_notified = number_format($client_naira_notified, 2, ".", ",");
        $client_pay_method = status_user_deposit_pay_method($client_pay_method);
        $real_naira_confirmed = number_format($real_naira_confirmed, 2, ".", ",");
        $deposit_status = status_user_deposit($deposit_status);

        $message_final = <<<MAIL
<div>
    <div style="max-width: 80%; margin: 0 auto; padding: 10px; font-size: 14px; font-family: Verdana;">

        <h4 style="text-align: center">$full_name</h4>

        <div>
            <table style="border: 1px solid black; border-collapse: collapse; width: 100%">
                <thead>
                    <tr><th> </th><th> </th></tr>
                </thead>
                <tbody>
                    <tr><td style="border: 1px solid black; padding: 5px;">Transaction ID</td><td style="border: 1px solid black; padding: 5px;">$trans_id</td></tr>
                    <tr><td style="border: 1px solid black; padding: 5px;">Created</td><td style="border: 1px solid black; padding: 5px;">$created</td></tr>
                    <tr><td style="border: 1px solid black; padding: 5px;">Account Number</td><td style="border: 1px solid black; padding: 5px;">$ifx_acct_no</td></tr>
                    <tr><td style="border: 1px solid black; padding: 5px;">Client Name</td><td style="border: 1px solid black; padding: 5px;">$full_name</td></tr>
                    <tr><td style="border: 1px solid black; padding: 5px;">Client Phone</td><td style="border: 1px solid black; padding: 5px;">$phone</td></tr>
                    <tr><td style="border: 1px solid black; padding: 5px;">Client Email</td><td style="border: 1px solid black; padding: 5px;">$email</td></tr>
                    <tr><td style="border: 1px solid black; padding: 5px;">Amount Ordered ($)</td><td style="border: 1px solid black; padding: 5px;">$dollar_ordered</td></tr>
                    <tr><td style="border: 1px solid black; padding: 5px;">Equivalent (N) - Without Charges</td><td style="border: 1px solid black; padding: 5px;">$naira_equivalent_dollar_ordered</td></tr>
                    <tr><td style="border: 1px solid black; padding: 5px;" title="After adding all the charges">Amount To Pay (N)</td><td style="border: 1px solid black; padding: 5px;">$naira_total_payable</td></tr>
                    <tr><td style="border: 1px solid black; padding: 5px;">Amount Paid</td><td style="border: 1px solid black; padding: 5px;">$client_naira_notified</td></tr>
                    <tr><td style="border: 1px solid black; padding: 5px;">Payment Date</td><td style="border: 1px solid black; padding: 5px;">$client_pay_date</td></tr>
                    <tr><td style="border: 1px solid black; padding: 5px;">Payment Method</td><td style="border: 1px solid black; padding: 5px;">$client_pay_method</td></tr>
                    <tr><td style="border: 1px solid black; padding: 5px;">Payment Confirmed (N)</td><td style="border: 1px solid black; padding: 5px;">$real_naira_confirmed</td></tr>
                    <tr><td style="border: 1px solid black; padding: 5px;"><strong>Equivalent ($) - Amount Funded</strong></td><td style="border: 1px solid black; padding: 5px;" style="border: 1px solid black; padding: 5px;"><strong>$real_dollar_equivalent</strong></td></tr>
                    <tr><td style="border: 1px solid black; padding: 5px;">Transfer Reference</td><td>$transfer_reference</td></tr>
                    <tr><td>Status</td><td style="border: 1px solid black; padding: 5px;">$deposit_status</td></tr>
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

    }

    $mpdf = new \Mpdf\Mpdf([
        'margin_left' => 15,
        'margin_right' => 15,
        'margin_top' => 20,
        'margin_bottom' => 20,
        'margin_header' => 10,
        'margin_footer' => 10
    ]);
    $mpdf->SetProtection(array('print'));
    $mpdf->SetTitle("Client Transaction - Instafxng.com");
    $mpdf->SetAuthor("Instant Web-Net Technologies Ltd");
    $mpdf->SetWatermarkText("Confidential - Instafxng");
    $mpdf->showWatermarkText = true;
    $mpdf->watermark_font = 'DejaVuSansCondensed';
    $mpdf->watermarkTextAlpha = 0.1;
    $mpdf->SetDisplayMode('fullpage');

    $date_now = datetime_to_text(date('Y-m-d H:i:s'));

    $mpdf->SetFooter("Date Generated: " . $date_now . " - {PAGENO}");

    $mpdf->WriteHTML($message_final);
    $mpdf->Output($full_name . ' - ' . $trans_id . '.pdf', \Mpdf\Output\Destination::DOWNLOAD);

} else {
    redirect_to("/");
}