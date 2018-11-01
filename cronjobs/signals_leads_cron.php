<?php
//Kindly Schedule for 12 midnight daily
set_include_path('/home/tboy9/public_html/init/');
require_once 'initialize_general.php';

$my_subject_1 = "There’s more from where the Signals came… allow me to show you!";
$my_message_1 =
<<<MAIL
<p>Dear [NAME],</p>
<p>This month on the signal channel, we are working towards 1000 pips.</p>
<p>To be a part of this, all you have to do is become a member of our loyalty reward programme. Trust me, you do not want to miss out on this month’s profits.</p>
<p><a href="http://bit.ly/2mpqehQ"><button style="border-radius:10px; background-color:blue; color:white";><strong>Click here to open an account and enjoy this offer from today.</strong></button></a></p>
<p>Now, Here is More!</p>
<p>Other than the signals, I am offering you a chance to get between 30-100% welcome bonus on your first deposit and 30% on your subsequent deposits.</p>
<p>This offer will be open for a month only, so <a href="http://bit.ly/2mpqehQ">click here to create your account now!</a></p>
<p>However, if you need my assistance to create your account or claim your bonus, do not hesitate to hit the reply button and I will be sure to assist you immediately.</p>
MAIL;

$my_subject_2 = "[NAME], You can’t miss the next entry point with this news!";
$my_message_2 =
<<<MAIL
<p>Dear [NAME],</p>
<p>How are you doing today?</p>
<p>How awesome will it be, if you got notified every time signals are posted on our site?</p>
<p>This means that you will be among the first persons to see all our signals immediately they are posted and you’ll never miss out on a single entry price or pip gained!</p>
<p>Guess what? We are ready to send you notifications every time our signals are posted!</p>
<p>Thousands of clients are already benefiting from this already, and you need to join them now. All you need to do is become a member of our loyalty reward program and begin to enjoy these benefits right away.</p>
<p><a href="http://bit.ly/2mpqehQ"><button style="border-radius:10px; background-color:blue; color:white";><strong>Click here to create your account now.</strong></button></a></p>
<p>Your 100% welcome bonus offer expires in 2 weeks, ensure to claim it before it does, because the more money you have, the more trades you can place and the more profit you can earn.</p>
<p><a href="http://bit.ly/2mpqehQ">Click here to create your account now</a> and hit the claim offer button below.</p>
<p><a href="mailto:support@instafxng.com?subject=Offer%20Claim%20&body=Hi Bukky,%0A%0A I will like to claim the Signal Update Offer.%0A%0AThanks!"><button style="border-radius:10px; background-color:blue; color:white";><strong>Claim Offer button</strong></button></a></p>
<p>I will be here to assist you if you need further guide on setting up your account, claiming your bonus and getting subscribed to the signals, all you need to do is reply to this mail.</p>
MAIL;

$my_subject_3 = "One Million Rewards! Up to 2.5 Million Naira Every Year!";
$my_message_3 =
    <<<MAIL
<p>Dear [NAME],</p>
<p>How are you doing today?</p>
<p>Did you know that you could get paid extra for trading? Yes, you can!</p>
<p>Let me explain how that works to you.</p>
<p>When you become a member of our loyalty reward program, You get rewarded with loyalty points while you take your normal trades.</p>
<p>You can earn up to $4,200 and N1,000,000 every year, and below is a breakdown of how easy it is.</p>
<p>If your deposit and trading activities earn you the highest point every single month of the year, then you will earn the following.</p>
<p>
<ol>
<li>As first prize monthly winner you will get $150 every month making a total of $1, 800 for one year.</li>
<li>With the highest points monthly for 12 months you will have 24, 000 points and you can redeem that to get $2, 400. ($2,400 + $1,800 = $4,200).</li>
<li>As the first prize annual cumulative winner, you will get one million Naira (N1,000,000).</li>
</ol>
</p>
<p>This is an awesome opportunity for you to make extra while you earn. Get set and take your positions immediately and make the points start counting.</p>
<p>Now that’s not all! There’s More!</p>
<p>These points are redeemable and you can claim your point in dollars! So apart from hitting your daily profits, you also get to earn extra for all your hard work.</p>
<p>So if you must trade Forex in Nigeria, get rewarded maximally! Join thousands of clients enjoying this premium experience on our platform now. <a href="http://bit.ly/2mpqehQ">Click here to create you account now!</a></p>
<p>To Join the Loyalty Reward Programme: There are 3 steps to
<ul>
<li>Open a live InstaForex account. <a href="http://bit.ly/2mpqehQ">Click here to create one now.</a></li>
<li>Proceed to step 2 to enrol your InstaForex Account in the Loyalty reward programme (Please note that only accounts enrolled into our loyalty programme can emerge winners)</li>
<li>Make deposits and Trade Trade Trade!</li>
</ul>
</p>
<p>The more you trade, the more you earn! <a href="https://www.instafxng.com/loyalty.php">Click here to read more on the programme and see winners of the 2017 loyalty rewards</a></p>
<p>This is an exciting opportunity to earn more from Forex trading, would you take it?</p>
<p>Feel free to write me up if you’ll like to inquire about the loyalty reward programme and other services and I will be sure to respond immediately.</p>
MAIL;


function auto_mail_template($core_msg) {

$my_message_template =
<<<MAIL
<div style="background-color: #F3F1F2">
    <div style="max-width: 80%; margin: 0 auto; padding: 10px; font-size: 14px; font-family: Verdana;">
        <img src="https://instafxng.com/images/ifxlogo.png" />
        <hr />
        <div style="background-color: #FFFFFF; padding: 15px; margin: 5px 0 5px 0;">

            $core_msg

            <br /><br />
            <p>Regards,</p>

            <p>Bukola,<br />
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

    return $my_message_template;
}

function auto_mail_query($query_type) {

    $today = date('Y-m-d');

    switch($query_type) {
        case 1:
            $query = "SELECT f_name AS first_name, email FROM `campaign_leads` WHERE (DATEDIFF('$today', STR_TO_DATE(created, '%Y-%m-%d')) > '15') AND source = '3' AND interest = '2' GROUP BY email ORDER BY created DESC ";
            break;
        case 2:
            $query = "SELECT f_name AS first_name, email FROM `campaign_leads` WHERE (DATEDIFF('$today', STR_TO_DATE(created, '%Y-%m-%d')) = '28') AND source = '3' AND interest = '2' GROUP BY email ORDER BY created DESC ";
            break;
        case 3:
            $query = "SELECT f_name AS first_name, email FROM `campaign_leads` WHERE (DATEDIFF('$today', STR_TO_DATE(created, '%Y-%m-%d')) = '42') AND source = '3' AND interest = '2' GROUP BY email ORDER BY created DESC ";
            break;
        default:
            $query = "";
    }

    return $query;
}

function auto_mail_send($query, $my_subject_raw, $my_message_raw) {
    global $db_handle;
    global $system_object;

    $result = $db_handle->runQuery($query);
    $fetched_data = $db_handle->fetchAssoc($result);

    foreach ($fetched_data as $row) {
        $client_name = ucwords(strtolower(trim($row['first_name'])));
        $client_email = strtolower(trim($row['email']));

        $my_subject = $my_subject_raw;
        $my_message = $my_message_raw;

        // Replace [NAME] with clients full name
        $my_message_new = str_replace('[NAME]', $client_name, $my_message);
        $my_subject_new = str_replace('[NAME]', $client_name, $my_subject);

        $system_object->send_email($my_subject_new, $my_message_new, $client_email, $client_name);
    }

    return true;
}

//TODO: Refactor and make it dynamic

$query_1 = auto_mail_query(1);
$get_mail_1 = auto_mail_template($my_message_1);
$send_message_1 = auto_mail_send($query_1, $my_subject_1, $get_mail_1);

$query_2 = auto_mail_query(2);
$get_mail_2 = auto_mail_template($my_message_2);
$send_message_2 = auto_mail_send($query_2, $my_subject_2, $get_mail_2);

$query_3 = auto_mail_query(3);
$get_mail_3 = auto_mail_template($my_message_3);
$send_message_3 = auto_mail_send($query_3, $my_subject_3, $get_mail_3);