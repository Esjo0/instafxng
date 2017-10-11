<?php
set_include_path('/home/tboy9/public_html/init/');
require_once 'initialize_general.php';

// Get all training clients who registered 7 days ago and have not signed into the training
// portal yet

$today = date('Y-m-d');

$query = "SELECT u.first_name, u.email
    FROM user AS u
    INNER JOIN free_training_campaign AS ftc ON ftc.email = u.email
    WHERE (DATEDIFF('$today', STR_TO_DATE(ftc.created, '%Y-%m-%d')) = 7) AND u.academy_signup IS NULL";

$result = $db_handle->runQuery($query);
$fetched_data = $db_handle->fetchAssoc($result);

foreach ($fetched_data as $row) {
    $client_name = ucwords(strtolower(trim($row['first_name'])));
    $client_email = strtolower(trim($row['email']));

    $my_subject = "[NAME], are you encountering any challenge?";
    $my_message =
<<<MAIL
<div style="background-color: #F3F1F2">
    <div style="max-width: 80%; margin: 0 auto; padding: 10px; font-size: 14px; font-family: Verdana;">
        <img src="https://instafxng.com/images/ifxlogo.png" />
        <hr />
        <div style="background-color: #FFFFFF; padding: 15px; margin: 5px 0 5px 0;">
            <p>Hello [NAME],</p>

            <p>Did you know that the Forex Market is the most liquid market in the world with
            over 5 trillion dollars traded on a daily basis?</p>

            <p>With such liquidity, there is a huge opportunity for intending Forex traders
            like you to profit from the Forex market.</p>

            <p>Forex trading is highly profitable; in fact, you can make more money trading the
            Forex market and even gain financial freedom from it.</p>

            <p>As an intending Forex trader, you need to acquire all the knowledge you need to
            avoid frustration in the process as forex trading is a business and as such should
            be taking seriously.</p>

            <p>[NAME], you showed interest in our online training course and you are yet to start
            your lessons. Are you experiencing any challenge?</p>

            <p>Kindly
            <a href="mailto:support@instafxng.com?subject=I%20Need%20Help&body=%20Hello%20Curry,%20%0A%0AI%20encountered%20the%20following%20challenges:%0A%0A%0APlease%20type%20the%20challenge(s)%20you%20encountered%20below%0A%0A%0A%0A%0A%0A%0AThank%20you">click here</a>
            to send me a mail if you are encountering any difficulty in taking
            the course and I will swiftly look into and get it resolved so you can start your journey
            to making money from Forex trading with no hassle.</p>

            <p>I look forward to hearing from you.</p>


            <br /><br />
            <p>Best Regards,</p>

            <p>Your friend,<br />Bunmi,<br />Client Relations Manager.</p>
            <p>Instaforex Nigeria,<br />
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

    // Replace [NAME] with clients full name
    $my_message_new = str_replace('[NAME]', $client_name, $my_message);
    $my_subject_new = str_replace('[NAME]', $client_name, $my_subject);

    $system_object->send_email($my_subject_new, $my_message_new, $client_email, $client_name);
}