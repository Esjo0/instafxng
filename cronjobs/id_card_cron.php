<?php
set_include_path('/home/tboy9/public_html/init/');
require_once 'initialize_general.php';
//require_once '../app_assets/initialize_general.php';

$current_month = date('m');
$current_day = date('d');

if(($current_day == 1) || ($current_day == 15)){
//Check for id that expires this month and send then a mail
$query = "SELECT * FROM user_credential AS uc
INNER JOIN user AS u ON uc.user_code = u.user_code
WHERE MONTH(uc.id_exp_date) = '$current_day' AND uc.doc_status = '111' ";
$result = $db_handle->runQuery($query);
    $all_exp_cards = $db_handle->fetchArray($result);
    foreach($all_exp_cards AS $row){
        extract($row);
        $query = "UPDATE user_credentials SET doc_status = '011'";
        $result = $db_handle->runQuery($query);
        $query = "INSERT INTO user_id_card_archive (user_code, file_name) VALUE('$user_code', '$idcard')";
        $result = $db_handle->runQuery($query);
        $subject = "Instafxng ID Card update";
        $message =
            <<<MAIL
            <div style="background-color: #F3F1F2">
    <div style="max-width: 80%; margin: 0 auto; padding: 10px; font-size: 14px; font-family: Verdana;">
        <img src="https://instafxng.com/images/ifxlogo.png" />
        <hr />
        <div style="background-color: #FFFFFF; padding: 15px; margin: 5px 0 5px 0;">
            <p>Dear $first_name,</p>

            <p>Your ID Card is due to expire This month.</p>
            <p>You are advised to kindly <a href="https://instafxng.com/verify_account.php">Click Here</a> to upload a valid ID Card.</p>

            <p>Thank you for choosing Instafxng.</p>

            <br /><br />
            <p>Best Regards,</p>
            <p>Instafxng Support,<br />
                www.instafxng.com</p>
            <br /><br />
        </div>
        <hr />
        <div style="background-color: #EBDEE9;">
            <div style="font-size: 11px !important; padding: 15px;">
                <p style="text-align: center"><span style="font-size: 12px"><strong>We're Social</strong></span><br /><br />
                    <a href="https://facebook.com/InstaFxNg"><img src="https://instafxng.com/images/Facebook.png"></a>
                    <a href="https://twitter.com/instafxng"><img src="https://instafxng.com/images/Twitter.png"></a>
                    <a href="https://www.instagram.com/instafxng/"><img src="https://instafxng.com/images/instagram.png"></a>
                    <a href="https://www.youtube.com/channel/UC0Z9AISy_aMMa3OJjgX6SXw"><img src="https://instafxng.com/images/Youtube.png"></a>
                    <a href="https://linkedin.com/company/instaforex-ng"><img src="https://instafxng.com/images/LinkedIn.png"></a>
                </p>
                <p><strong>Head Office Address:</strong> TBS Place, Block 1A, Plot 8, Diamond Estate, Estate Bus-Stop, LASU/Isheri road, Isheri Olofin, Lagos.</p>
                <p><strong>Lekki Office Address:</strong> Block A3, Suite 508/509 Eastline Shopping Complex, Opposite Abraham Adesanya Roundabout, along Lekki - Epe expressway, Lagos State</p>
                <p><strong>Office Number:</strong> 08028281192</p>
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

        $system_object->send_email($subject, $message, $email, $first_name);
    }
}