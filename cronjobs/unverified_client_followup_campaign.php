<?php
set_include_path('/home/tboy9/public_html/init/');
require_once 'initialize_general.php';
//Paid for the Forex optimizer Course but have not started
$my_subject_1 = "Your Forex Profit Optimizer course is on…";
$my_message_1 =
    <<<MAIL
<p>Dear [Name]</p>
<p>Trust you are doing well.</p>
<p>Your optimizer course has begun and you are late for class!!!</p>
<p>Your payment for the optimizer class has been long approved but you are yet to start the lessons.</p>
<p>Where have you been?</p>
<p>    I quite understand that you have been very busy, however, I’d like to encourage you to take out time to begin your training so you can start earning extra money.
Your training portal awaits and all you need to do is begin now.</p>
<p>Rest assured, after the training, you will be fully equipped to trade profitably and optimally.</p>
<p>Start taking your lessons now <a href="https://bit.ly/2iExTpN">here</a></p>
<p>Hurry Now! I can’t wait to for you to join our league of profitable traders</p>
MAIL;

$my_subject_2 = " You’ve Paid Your Dues [NAME], you deserve…";
$my_message_2 =
    <<<MAIL
<p>Hello [Name]</p>
<p>How are you doing?</p>
<p>    I noticed that you have paid for your optimizer course and you are yet to start the training.</p>
<p>If this is about not having enough time to take the course, I perfectly understand, because time seems to be very limited and there is so much to achieve every day.</p>
<p>The secret to time and harnessing it value is simply starting and completing all you need to do immediately.</p>
<p>That way you can be sure to get the expected results and move on to other tasks.</p>
<p>Easy and efficient right?</p>
<p>    Tag along [Name], you have paid your dues and you sure deserve to get your money’s worth.</p>
<p>Why not start your course this weekend? That way you can lay comfortably on your bed on a Saturday morning, take the course on your phone and practice your newly acquired skill.</p>
<p>Click <a href="https://bit.ly/2iExTpN">here</a> to begin your profit optimizer course now.</p>
<p>I would love for you to start and complete your optimizer course as soon as possible because it will equip you to be prepared to trade profitably.</p>
<p>If you have any concern, please feel free share with me</p>
<p>I am eager to read and respond to your email.</p>
MAIL;

$my_subject_3 = "Your Training Portal Awaits you";
$my_message_3 =
    <<<MAIL
<p>Hello [Name],</p>
<p>Just checking on you.</p>
<p>How have you been?</p>
<p>    You are yet to start your Forex Profit Optimizer course, and I just want to remind you that your payment for this course has been long approved and you can access your training now.</p>
<p>I am eager for you to begin this course because I am quite confident that after taking it, you will have a good start at trading and it would be easy for you to develop winning trading strategies and make good profits from trading Forex.</p>
<p>The Loyalty Reward Program for this year has begun and you are missing out!</p>
<p>The sooner you begin and conclude the Forex Profit Optimizer course, the earlier you start trading and making money from your trades.</p>
<p>[Name], you can still hit this year’s financial target and become a millionaire!</p>
<p>Complete your course and get equipped now! Click <a href="https://bit.ly/2iExTpN">here</a> to begin now.</p>
<p>I look forward to seeing you in class.</p>
<p>Please feel free to reach out to me if you need any assistance. I’ll always be here.</p>
MAIL;

$my_subject_4 = "All you Need to build a Successful Side Business is in here";
$my_message_4 =
    <<<MAIL
<p>Dear [Name],</p>
            <p>I hope you are doing well?</p>
            <p>    You have been off your training for a very long time, are you experiencing any difficulty? If yes, please click here to send me a mail so I can get in on it and assist you with it right away.</p>
            <p>Do you know that with the right knowledge, you can make millions of dollars while trading Forex? I bet you do!</p>
            <p>I would like to encourage you to take your Forex Profit optimizer course today as I want to see you make more money and see the result of signing up from the start.</p>
            <p>A lot of other traders who started the training with you have begun to cash out hundreds and thousands of dollars.</p>
            <p>Don’t wait a day more! Click here to start your Forex Profit Optimizer course so you can finish it in good time.</p>
            <p>I’m earnestly rooting for you [NAME] and I can’t wait for you to become a pro at this and start making money with ease.</p>
            <p>
                <center><a href="http://bit.ly/2iExTpN" target="_blank"><button style="background: #2f88bc; border-radius: 15px; height: 35px;text-decoration: none; text-align: center; color: white;"><strong>Get me Started</strong>
        </button></a></center></p>
MAIL;

$my_subject_5 = "All you Need to build a Successful Side Business is in here";
$my_message_5 =
    <<<MAIL
<p>Dear [Name],</p>
            <p>I hope you are doing well?</p>
            <p>    You have been off your training for a very long time, are you experiencing any difficulty? If yes, please click here to send me a mail so I can get in on it and assist you with it right away.</p>
            <p>Do you know that with the right knowledge, you can make millions of dollars while trading Forex? I bet you do!</p>
            <p>I would like to encourage you to take your Forex Profit optimizer course today as I want to see you make more money and see the result of signing up from the start.</p>
            <p>A lot of other traders who started the training with you have begun to cash out hundreds and thousands of dollars.</p>
            <p>Don’t wait a day more! Click here to start your Forex Profit Optimizer course so you can finish it in good time.</p>
            <p>I’m earnestly rooting for you [NAME] and I can’t wait for you to become a pro at this and start making money with ease.</p>
            <p>
                <center><a href="http://bit.ly/2iExTpN" target="_blank"><button style="background: #2f88bc; border-radius: 15px; height: 35px;text-decoration: none; text-align: center; color: white;"><strong>Get me Started</strong>
        </button></a></center></p>
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

function student_auto_mail_query($query_type, $day_value) {

    $today = date('Y-m-d');

    switch($query_type) {
        case 1:
            $query = "SELECT u.user_code, CONCAT(u.last_name, SPACE(1), u.first_name) AS full_name,
            u.email, u.phone, u.created, CONCAT(a.last_name, SPACE(1), a.first_name) AS account_officer_full_name
            FROM user AS u
            INNER JOIN account_officers AS ao ON u.attendant = ao.account_officers_id
            INNER JOIN admin AS a ON ao.admin_code = a.admin_code
            WHERE (u.password IS NULL OR u.password = '')
            GROUP BY u.email ORDER BY u.created DESC, u.last_name ASC ";
            break;
        case 2:
            $query = "SELECT u.first_name, u.email FROM user_edu_exercise_log AS ueel
INNER JOIN user AS u ON ueel.user_code = u.user_code
LEFT JOIN user_edu_fee_payment AS uefp ON ueel.user_code = uefp.user_code
WHERE (DATEDIFF('$today', STR_TO_DATE(ueel.created, '%Y-%m-%d')) = '$day_value')
AND u.user_code = ueel.user_code
AND (ueel.lesson_id BETWEEN '1' AND '6')
AND uefp.user_code = u.user_code
GROUP BY ueel.user_code ORDER BY u.last_name ASC ";
            break;
        case 3:
            $query = "SELECT u.first_name, u.email FROM user_edu_exercise_log AS ueel
INNER JOIN user AS u ON ueel.user_code = u.user_code
LEFT JOIN user_edu_fee_payment AS uefp ON ueel.user_code = uefp.user_code
WHERE (DATEDIFF('$today', STR_TO_DATE(ueel.created, '%Y-%m-%d')) = '$day_value')
AND u.user_code = ueel.user_code
AND (ueel.lesson_id BETWEEN '7' AND '13')
AND uefp.user_code = u.user_code
GROUP BY ueel.user_code ORDER BY u.last_name ASC ";
            break;
        case 4:
            $query = "SELECT u.first_name, u.email FROM user_edu_exercise_log AS ueel
INNER JOIN user AS u ON ueel.user_code = u.user_code
LEFT JOIN user_ifxaccount AS ui ON u.user_code = ui.user_code
LEFT JOIN user_deposit AS ud ON ui.ifxaccount_id = ud.ifxaccount_id
LEFT JOIN user_edu_fee_payment AS uefp ON ueel.user_code = uefp.user_code
WHERE (DATEDIFF('$today', STR_TO_DATE(ueel.created, '%Y-%m-%d')) = '$day_value' )
AND u.user_code = ueel.user_code
AND ueel.lesson_id = '13'
AND ui.user_code <> u.user_code
AND uefp.user_code = u.user_code
GROUP BY ueel.user_code ORDER BY u.last_name ASC ";
            break;
        case 5:
            $query = "SELECT u.first_name, u.email
                FROM user_edu_exercise_log AS ueel
                INNER JOIN user AS u ON ueel.user_code = u.user_code
                LEFT JOIN user_edu_fee_payment AS uefp ON ueel.user_code = uefp.user_code
                WHERE (DATEDIFF('$today', STR_TO_DATE(ueel.created, '%Y-%m-%d')) = '$day_value' )
                AND ueel.lesson_id = '5'
                AND uefp.user_code <> u.user_code
                GROUP BY ueel.user_code ORDER BY u.last_name ASC ";
            break;
        default:
            $query = "";
    }

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

// Clients who have paid for Foerex Optimizer Course but hav't started
$interval_1a = 7;

$query_1a = student_auto_mail_query(1, $interval_1a);
$get_mail_1a = student_auto_mail_template($my_message_1a);
$send_message_1a = student_auto_mail_send($query_1a, $my_subject_1a, $get_mail_1a);

$interval_1b = 14;

$query_1b = student_auto_mail_query(1, $interval_1b);
$get_mail_1b = student_auto_mail_template($my_message_1b);
$send_message_1b = student_auto_mail_send($query_1b, $my_subject_1b, $get_mail_1b);

$interval_1c = 21;

$query_1c = student_auto_mail_query(1, $interval_1c);
$get_mail_1c = student_auto_mail_template($my_message_1c);
$send_message_1c = student_auto_mail_send($query_1c, $my_subject_1c, $get_mail_1c);

$interval_1d = 28;

$query_1d = student_auto_mail_query(1, $interval_1d);
$get_mail_1d = student_auto_mail_template($my_message_1d);
$send_message_1d = student_auto_mail_send($query_1d, $my_subject_1d, $get_mail_1d);

// Clients who are between Lesson 1-6 of the forex money maker course
$interval_2a = 14;

$query_2a = student_auto_mail_query(2, $interval_2a);
$get_mail_2a = student_auto_mail_template($my_message_2a);
$send_message_2a = student_auto_mail_send($query_2a, $my_subject_2a, $get_mail_2a);

$interval_2b = 28;

$query_2b = student_auto_mail_query(2, $interval_2b);
$get_mail_2b = student_auto_mail_template($my_message_2b);
$send_message_2b = student_auto_mail_send($query_2b, $my_subject_2b, $get_mail_2b);

$interval_2c = 32;

$query_2c = student_auto_mail_query(2, $interval_2c);
$get_mail_2c = student_auto_mail_template($my_message_2c);
$send_message_2c = student_auto_mail_send($query_2c, $my_subject_2c, $get_mail_2c);

$interval_2d = 46;

$query_2d = student_auto_mail_query(2, $interval_2d);
$get_mail_2d = student_auto_mail_template($my_message_2d);
$send_message_2d = student_auto_mail_send($query_2d, $my_subject_2d, $get_mail_2d);

//Clients who have completed course two but have not Funded their accounts
$interval_3a = 7;

$query_3a = student_auto_mail_query(3, $interval_3a);
$get_mail_3a = student_auto_mail_template($my_message_3a);
$send_message_3a = student_auto_mail_send($query_3a, $my_subject_3a, $get_mail_3a);


$interval_3b = 14;

$query_3b = student_auto_mail_query(3, $interval_3b);
$get_mail_3b = student_auto_mail_template($my_message_3b);
$send_message_3b = student_auto_mail_send($query_3b, $my_subject_3b, $get_mail_3b);

$interval_3c = 21;

$query_3c = student_auto_mail_query(3, $interval_3c);
$get_mail_3c = student_auto_mail_template($my_message_3c);
$send_message_3c = student_auto_mail_send($query_3c, $my_subject_3c, $get_mail_3c);

$interval_3d =28;

$query_3d = student_auto_mail_query(3, $interval_3d);
$get_mail_3d = student_auto_mail_template($my_message_3d);
$send_message_3d = student_auto_mail_send($query_3d, $my_subject_3d, $get_mail_3d);

//Clients who have completed course two but have not Funded their accounts
$interval_4a = 15;

$query_4a = student_auto_mail_query(4, $interval_4a);
$get_mail_4a = student_auto_mail_template($my_message_4a);
$send_message_4a = student_auto_mail_send($query_4a, $my_subject_4a, $get_mail_4a);


$interval_4b = 30;

$query_4b = student_auto_mail_query(4, $interval_4b);
$get_mail_4b = student_auto_mail_template($my_message_4b);
$send_message_4b = student_auto_mail_send($query_4b, $my_subject_4b, $get_mail_4b);

$interval_4c = 45;

$query_4c = student_auto_mail_query(4, $interval_4c);
$get_mail_4c = student_auto_mail_template($my_message_4c);
$send_message_4c = student_auto_mail_send($query_4c, $my_subject_4c, $get_mail_4c);

$interval_4d = 60;

$query_4d = student_auto_mail_query(4, $interval_4d);
$get_mail_4d = student_auto_mail_template($my_message_4d);
$send_message_4d = student_auto_mail_send($query_4d, $my_subject_4d, $get_mail_4d);

//people who have reached lesson 5 of the Forex money maker course.
$interval_5a = 5;

$query_5a = student_auto_mail_query(5, $interval_5a);
$get_mail_5a = student_auto_mail_template($my_message_5a);
$send_message_5a = student_auto_mail_send($query_5a, $my_subject_5a, $get_mail_5a);


$interval_5b = 12;

$query_5b = student_auto_mail_query(5, $interval_5b);
$get_mail_5b = student_auto_mail_template($my_message_5b);
$send_message_5b = student_auto_mail_send($query_5b, $my_subject_5b, $get_mail_5b);

$interval_5c = 19;

$query_5c = student_auto_mail_query(5, $interval_5c);
$get_mail_5c = student_auto_mail_template($my_message_5c);
$send_message_5c = student_auto_mail_send($query_5c, $my_subject_5c, $get_mail_5c);

$interval_5d = 26;

$query_5d = student_auto_mail_query(5, $interval_5d);
$get_mail_5d = student_auto_mail_template($my_message_5d);
$send_message_5d = student_auto_mail_send($query_5d, $my_subject_5d, $get_mail_5d);