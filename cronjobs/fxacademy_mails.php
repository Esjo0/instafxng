<?php
set_include_path('/home/tboy9/public_html/init/');
require_once 'initialize_general.php';

$my_subject_1a = "So Nice to Meet You [NAME]";
$my_message_1a =
<<<MAIL
<p>Dear [NAME],</p>
<p>It's been 10 days since you started the Forex Money Maker course.</p>
<p>You are making notable progress and I'm super proud of you.</p>
<p>Now is not the time to relax at all. You've just started and you have some more lessons to take.</p>
<p>Get right on it [NAME]. <a href="http://bit.ly/2iExTpN" title="Click to continue your training">Click here</a> to pick up from where you left off.</p>
<p>The course you are currently on is not an end itself, It's the means to an end.</p>
<p>You are on a journey to making consistent income from Forex trading and this is where it begins.</p>
<p>Don't forget to <a href="http://bit.ly/2iExTpN" title="Click to continue your training">click here</a> to continue where you stopped.</p>
<p>See you at the other end.</p>
MAIL;

$my_subject_1b = "Don't be like Tare ...";
$my_message_1b =
<<<MAIL
<p>Dear [NAME],</p>
<p>The main purpose of forex trading is to make more money.</p>
<p>You probably wonder sometimes, how does this training translates to money for me?</p>
<p>It really does and I'll explain that to you shortly.</p>
<p>Tare has heard a lot about Forex trading, how many Nigerians are earning in foreign currencies by trading Forex.</p>
<p>As soon as he gets the opportunity to trade Forex, he jumped on it! He was thrilled and went ahead to open an account and trade.</p>
<p>He couldn't wait, he just wanted to get to it immediately so he funded his account and after placing a few trades, he lost all his initial capital.</p>
<p>What happened to Tare? He thought Forex was a get rich quick scheme and he didn't take his time to learn how to trade before he started trading.</p>
<p>The truth: Forex trading is no joke so it's important that you gain adequate knowledge of how to trade in the Forex market with lots of profits.</p>
<p>Like every business, it's important to learn the ropes of Forex trading before starting or committing your funds to it and this is what this training offers.</p>
<p>Many of the clients who started the training have gone ahead and are already making money from Forex trading.</p>
<p>Don't put money on the table [NAME], <a href="http://bit.ly/2iExTpN" title="Click to continue your training">click here</a> to continue now.</p>
MAIL;

$my_subject_1c = "Are you losing money?";
$my_message_1c =
<<<MAIL
<p>Carrie Wilkerson once said, 'The longer you're not taking action, the more money you're losing.'</p>
<p>Is this true [NAME]?</p>
<p>Everyone has a dream to live full and free; live in the best of houses, travel around the world, have enough time to spend with the people they love, the list goes on...</p>
<p>Let's face it, will everyone be rich and achieve this goals?</p>
<p>Not at all and the reason is not far-fetched.</p>
<p>The measure of your success will be to the degree which you will stretch yourself.</p>
<p>It's true [NAME], If you give 50% attention and time to things you do, you won't get 200% result from it.</p>
<p>When the input is little, the output will equally be small. This is one rule of thumb that holds true for everyone.</p>
<p>If you want the best things of life and you really want to live your dreams, you need to give things your 100% so you can get great results.</p>
<p>Don't miss this chance to make more money from a side business that is hassle free.</p>
<p>You're on your way to making steady profits from Forex trading, don't stop now.</p>
<p><a href="http://bit.ly/2iExTpN" title="Click to continue your training">Click here to continue from where you stopped</a> so you can join the league of extra ordinary achievers making steady profits from Forex trading.</p>
<p>Click <a href="http://bit.ly/2iExTpN" title="Click to continue your training">here</a> to get in immediately!</p>
MAIL;

$my_subject_1d = "You're not Alone [NAME]";
$my_message_1d =
<<<MAIL
<p>Hello there,</p>
<p>How are you doing today?</p>
<p>It's been a while since you last checked into your training portal.</p>
<p>I guess you have been very busy.</p>
<p>Don't I understand? I really do.</p>
<p>There are only 24 hours in a day and it's not enough to do all that we have to do within the day.</p>
<p>A quote from an unknown author says... ‘Many things aren't equal but everyone gets the same 24 hours a day, 7 days a week. We make time for what we truly want.'</p>
<p>[NAME], how about creating time to go through the training so you can start generating income from Forex trading right after the training.</p>
<p>With the current Nigeria economy, it's has become increasingly difficult to thrive on just one source of income.</p>
<p>Ready to fire that dream of having more than enough money again? Let's help you achieve your goal!</p>
<p><a href="http://bit.ly/2iExTpN" title="Click to continue your training">Click here to go to class right away</a>.</p>
MAIL;




function student_auto_mail_template($core_msg) {

$my_message_template =
<<<MAIL
<div style="background-color: #F3F1F2">
    <div style="max-width: 80%; margin: 0 auto; padding: 10px; font-size: 14px; font-family: Verdana;">
        <img src="https://instafxng.com/images/ifxlogo.png" />
        <hr />
        <div style="background-color: #FFFFFF; padding: 15px; margin: 5px 0 5px 0;">

            $core_msg

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

    return $my_message_template;
}

function student_auto_mail_query($day_value) {

    $today = date('Y-m-d');

    $query = "SELECT u.first_name, u.email
                FROM user_edu_exercise_log AS ueel
                INNER JOIN user AS u ON ueel.user_code = u.user_code
                INNER JOIN account_officers AS ao ON u.attendant = ao.account_officers_id
                INNER JOIN admin AS a ON ao.admin_code = a.admin_code
                LEFT JOIN user_edu_fee_payment AS uefp ON ueel.user_code = uefp.user_code
                WHERE (DATEDIFF('$today', STR_TO_DATE(ueel.created, '%Y-%m-%d')) = $day_value) AND ueel.lesson_id = 1 AND uefp.user_code IS NULL AND u.user_code NOT IN
                (
                    SELECT u.user_code
                    FROM user_edu_exercise_log AS ueel
                    INNER JOIN user AS u ON ueel.user_code = u.user_code
                    INNER JOIN account_officers AS ao ON u.attendant = ao.account_officers_id
                    INNER JOIN admin AS a ON ao.admin_code = a.admin_code
                    LEFT JOIN user_edu_fee_payment AS uefp ON ueel.user_code = uefp.user_code
                    WHERE ueel.lesson_id IN (2, 3, 4, 5) AND uefp.user_code IS NULL
                )
                GROUP BY ueel.user_code ORDER BY u.academy_signup DESC, u.last_name ASC";

    return $query;
}

function student_auto_mail_send($query, $my_subject_raw, $my_message_raw) {
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

// 10 day interval
$interval_1 = 10;

$query_1a = student_auto_mail_query(1, $interval_1);
$get_mail_1a = student_auto_mail_template($my_message_1a);
$send_message_1a = student_auto_mail_send($query_1a, $my_subject_1a, $get_mail_1a);


// 20 day interval
$interval_2 = 20;

$query_1b = student_auto_mail_query(1, $interval_2);
$get_mail_1b = student_auto_mail_template($my_message_1b);
$send_message_1b = student_auto_mail_send($query_1b, $my_subject_1b, $get_mail_1b);



// 30 day interval
$interval_3 = 30;

$query_1c = student_auto_mail_query(1, $interval_3);
$get_mail_1c = student_auto_mail_template($my_message_1c);
$send_message_1c = student_auto_mail_send($query_1c, $my_subject_1c, $get_mail_1c);



// 40 day interval
$interval_4 = 40;

$query_1d = student_auto_mail_query(1, $interval_4);
$get_mail_1d = student_auto_mail_template($my_message_1d);
$send_message_1d = student_auto_mail_send($query_1d, $my_subject_1d, $get_mail_1d);

