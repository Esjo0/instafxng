<?php
set_include_path('/home/tboy9/public_html/init/');
require_once 'initialize_general.php';

// Mail for Category 1 Students
// Logged in but did not take any lesson after 7 days
$my_subject_1 = "[NAME], You have not logged on in 7 Days";
$my_message_1 =
<<<MAIL
    <div style="background-color: #F3F1F2">
    <div style="max-width: 80%; margin: 0 auto; padding: 10px; font-size: 14px; font-family: Verdana;">
        <img src="https://instafxng.com/images/ifxlogo.png" />
        <hr />
        <div style="background-color: #FFFFFF; padding: 15px; margin: 5px 0 5px 0;">
            <p>Hello [NAME],</p>

            <p>I would like to thank you for enrolling for the Forex Profit Academy.</p>
            <p>You see, I’m so excited you have taken this one decision to get to be where you want to be financially this year.</p>
            <p>I am more excited that you selected us to walk you through the journey of making consistent income from Forex.</p>
            <p>Let me let you in on what to expect on this journey.</p>
            <p>This Forex trading is simple and easy to follow and it’s fun too.</p>
            <p>Your instructor, Curry will guide you through the process of trading Forex profitably and by the time you are done; you will be ready to start making steady profits from Forex trading.</p>
            <p>You would discover new and exciting ways to make money from the Forex Market and become financially free!</p>

            <p><a href="http://bit.ly/2ffEeKl" title="Start the training">Click here to start the training.</a></p>

            <p>[NAME], I noticed that you registered 7 days ago and you are yet to start the training. Do take advantage of this opportunity to learn how to earn in dollars while it’s still free.</p>
            <p>The training is free for now but it will be returned to its normal price soon.</p>
            <p>Be sure to lock in your spot while it’s still free.</p>

            <p><a href="http://bit.ly/2ffEeKl" title="Start the training">Click here to start the training immediately.</a></p>

            <br /><br />
            <p>Best Regards,</p>

            <p>Your friend,<br />
            Mercy,<br />
            Client Relations Manager.</p>
            <p>InstaFxNg,<br />
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

// Mail for Category 1 Students
// Logged in but did not take any lesson after 14 days
$my_subject_2 = "[NAME], are you encountering any challenge?";
$my_message_2 =
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

            <p>Your friend,<br />Mercy,<br />Client Relations Manager.</p>
            <p>InstaFxNg,<br />
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


// Mail for Category 1 Students
// Logged in but did not take any lesson after 28 days
$my_subject_3 = "What Would you do If you are not Afraid?";
$my_message_3 =
<<<MAIL
    <div style="background-color: #F3F1F2">
    <div style="max-width: 80%; margin: 0 auto; padding: 10px; font-size: 14px; font-family: Verdana;">
        <img src="https://instafxng.com/images/ifxlogo.png" />
        <hr />
        <div style="background-color: #FFFFFF; padding: 15px; margin: 5px 0 5px 0;">
            <p>Hello [NAME],</p>

            <p>When you're faced with a tough decision, it's common to sit back and "think about it." You compare the pros and the cons, and try to come to the right decision. You analyze every little detail, and eventually, you come to a conclusion: Yes or No.</p>
            <p>Here's the problem: </p>
            <p>Your analysis provides an illusion of confidence, giving you a one-time feeling of satisfaction that you analysed right and your decision is the best.</p>
            <p>Yet this conclusion of yours doesn’t bring you fulfillment, instead, you yearn for more...</p>
            <p>In the end, the only real way to know if something was the right or wrong decision is to act... </p>
            <p>Stop thinking too much. Start doing. If you're curious about whether you will make money from forex trading or not, DO IT. </p>
            <p>And see if it works. </p>
            <p>People who fail never get started. People who fail focus on the unpleasant happening around them and wait for things to be right. People who fail THINK about it.</p>
            <p>People who succeed, on the other hand, get started before they are ready. And they adjust course along the way.</p>
            <p>I know you are not one to be known as a failure and that’s why you would take a step today and complete the training.</p>
            <p><a href="http://bit.ly/2ffEeKl" title="Start the training">Click here </a> to start the training and join the elite traders who make consistent income from Forex.</p>

            <br /><br />
            <p>Best Regards,</p>

            <p>Your friend,<br />
            Mercy,<br />
            Client Relations Manager.</p>
            <p>InstaFxNg,<br />
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

// Mail 0 : This mail is to be sent exactly 7 days after someone registers and did not log in

$today = date('Y-m-d');

$query = "SELECT u.first_name, u.email
        FROM free_training_campaign AS ftc
        INNER JOIN user AS u ON ftc.email = u.email
        WHERE (DATEDIFF('$today', STR_TO_DATE(ftc.created, '%Y-%m-%d')) = 7) AND u.academy_signup IS NULL
        ORDER BY ftc.created DESC ";

$result = $db_handle->runQuery($query);
$fetched_data = $db_handle->fetchAssoc($result);

foreach ($fetched_data as $row) {
    $client_name = ucwords(strtolower(trim($row['first_name'])));
    $client_email = strtolower(trim($row['email']));

    $my_subject = $my_subject_1;
    $my_message = $my_message_1;

    // Replace [NAME] with clients full name
    $my_message_new = str_replace('[NAME]', $client_name, $my_message);
    $my_subject_new = str_replace('[NAME]', $client_name, $my_subject);

    $system_object->send_email($my_subject_new, $my_message_new, $client_email, $client_name);
}

// Mail 1 : This mail is to be sent exactly 7 days after registration, this client is still yet to start lesson 1

$today = date('Y-m-d');

$query = "SELECT u.first_name, u.email
        FROM user AS u
        LEFT JOIN user_edu_exercise_log AS ueel ON u.user_code = ueel.user_code
        WHERE (DATEDIFF('$today', STR_TO_DATE(u.academy_signup, '%Y-%m-%d')) = 7) AND u.academy_signup IS NOT NULL AND ueel.user_code IS NULL
        ORDER BY u.academy_signup DESC ";

$result = $db_handle->runQuery($query);
$fetched_data = $db_handle->fetchAssoc($result);

foreach ($fetched_data as $row) {
    $client_name = ucwords(strtolower(trim($row['first_name'])));
    $client_email = strtolower(trim($row['email']));

    $my_subject = $my_subject_1;
    $my_message = $my_message_1;

    // Replace [NAME] with clients full name
    $my_message_new = str_replace('[NAME]', $client_name, $my_message);
    $my_subject_new = str_replace('[NAME]', $client_name, $my_subject);

    $system_object->send_email($my_subject_new, $my_message_new, $client_email, $client_name);
}

// Mail 2 : This mail is to be sent exactly 14 days after registration, this client is still yet to start lesson 1

$today = date('Y-m-d');

$query = "SELECT u.first_name, u.email
        FROM user AS u
        LEFT JOIN user_edu_exercise_log AS ueel ON u.user_code = ueel.user_code
        WHERE (DATEDIFF('$today', STR_TO_DATE(u.academy_signup, '%Y-%m-%d')) = 14) AND u.academy_signup IS NOT NULL AND ueel.user_code IS NULL
        ORDER BY u.academy_signup DESC ";

$result = $db_handle->runQuery($query);
$fetched_data = $db_handle->fetchAssoc($result);

foreach ($fetched_data as $row) {
    $client_name = ucwords(strtolower(trim($row['first_name'])));
    $client_email = strtolower(trim($row['email']));

    $my_subject = $my_subject_2;
    $my_message = $my_message_2;

    // Replace [NAME] with clients full name
    $my_message_new = str_replace('[NAME]', $client_name, $my_message);
    $my_subject_new = str_replace('[NAME]', $client_name, $my_subject);

    $system_object->send_email($my_subject_new, $my_message_new, $client_email, $client_name);
}

// Mail 3 : This mail is to be sent exactly 28 days after registration, this client is still yet to start lesson 1

$today = date('Y-m-d');

$query = "SELECT u.first_name, u.email
        FROM user AS u
        LEFT JOIN user_edu_exercise_log AS ueel ON u.user_code = ueel.user_code
        WHERE (DATEDIFF('$today', STR_TO_DATE(u.academy_signup, '%Y-%m-%d')) = 28) AND u.academy_signup IS NOT NULL AND ueel.user_code IS NULL
        ORDER BY u.academy_signup DESC ";

$result = $db_handle->runQuery($query);
$fetched_data = $db_handle->fetchAssoc($result);

foreach ($fetched_data as $row) {
    $client_name = ucwords(strtolower(trim($row['first_name'])));
    $client_email = strtolower(trim($row['email']));

    $my_subject = $my_subject_3;
    $my_message = $my_message_3;

    // Replace [NAME] with clients full name
    $my_message_new = str_replace('[NAME]', $client_name, $my_message);
    $my_subject_new = str_replace('[NAME]', $client_name, $my_subject);

    $system_object->send_email($my_subject_new, $my_message_new, $client_email, $client_name);
}