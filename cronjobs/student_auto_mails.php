<?php
set_include_path('/home/tboy9/public_html/init/');
require_once 'initialize_general.php';

//
$my_subject_1 = "I'm Just A Click Away";
$my_message_1 =
<<<MAIL
    <div style="background-color: #F3F1F2">
    <div style="max-width: 80%; margin: 0 auto; padding: 10px; font-size: 14px; font-family: Verdana;">
        <img src="https://instafxng.com/images/ifxlogo.png" />
        <hr />
        <div style="background-color: #FFFFFF; padding: 15px; margin: 5px 0 5px 0;">
            <p>Hello [NAME],</p>

            <p>How are you today?</p>

            <p>My name is Mercy, a client relations manager from InstaForex Nigeria and
            I would love to inform you that we miss you.</p>

            <p>We miss you a lot and I'm particularly worried about you.</p>

            <p>As you know, the profit opportunities available in the Forex market is limitless
            and you are yet to fully join the league of extraordinary achievers making money from
            Forex trading.</p>

            <p>Are you experiencing any challenge trading Forex on our platform?</p>

            <p>I'd like you to know that I'm here because of you and I'm particularly interested
            in holding your hands and guiding you through on your journey to consistent income
            trading Forex.</p>

            <p>If you'll let me of course!</p>

            <p>I'm ready to help you with whatever challenge you might be experiencing so I would
            like you to <a href="mailto:support@instafxng.com?subject=Challenges%20Faced%20Trading%20on%20InstaForex%20Platform%20&body=Hello%20%Mercy,%0A%0AHere%20are%20the%20Challenges%20Faced.%0A%0A%20Please%20type%20your%20challenges%20below.%0A%0AThanks!" title="Click to get help now">click here</a> to share it with me.</p>

            <p>I'm ready to attend to you and assist you on the journey to making consistent income
            from Forex trading.</p>

            <p>You'll let me do that, won't you? Please go ahead and <a href="mailto:support@instafxng.com?subject=Challenges%20Faced%20Trading%20on%20InstaForex%20Platform%20&body=Hello%20%Mercy,%0A%0AHere%20are%20the%20Challenges%20Faced.%0A%0A%20Please%20type%20your%20challenges%20below.%0A%0AThanks!" title="Click to get help now">click here</a>
            to share it with me.</p>

            <p>I am seriously rooting for you.</p>

            <br /><br />
            <p>Best Regards,</p>

            <p>Your friend,<br />
            Mercy,<br />
            Client Relations Manager.</p>
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



$date_start = "2014-01-01";
$date_end = "2017-12-31";
$today = date('Y-m-d');

$day1 = "2018-04-19";
$day2 = "2018-05-03";
$day3 = "2018-05-17";
$day4 = "2018-05-31";

if($today == $day1) { $my_subject = $my_subject_1; $my_message = $my_message_1; }
if($today == $day2) { $my_subject = $my_subject_2; $my_message = $my_message_2; }
if($today == $day3) { $my_subject = $my_subject_3; $my_message = $my_message_3; }
if($today == $day4) { $my_subject = $my_subject_4; $my_message = $my_message_4; }

$query = "SELECT u.first_name, u.email FROM user AS u
          WHERE (u.password IS NULL OR u.password = '')
          AND (STR_TO_DATE(u.created, '%Y-%m-%d') BETWEEN '$date_start' AND '$date_end')
          AND u.campaign_subscribe = '1'
          ORDER BY u.created ASC";

$result = $db_handle->runQuery($query);
$fetched_data = $db_handle->fetchAssoc($result);

foreach ($fetched_data as $row) {
    $client_name = ucwords(strtolower(trim($row['first_name'])));
    $client_email = strtolower(trim($row['email']));

    // Replace [NAME] with clients full name
    $my_message_new = str_replace('[NAME]', $client_name, $my_message);
    $my_subject_new = str_replace('[NAME]', $client_name, $my_subject);

    $system_object->send_email($my_subject_new, $my_message_new, $client_email, $client_name);
}