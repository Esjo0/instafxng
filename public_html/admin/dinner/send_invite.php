<?php
require_once("../../init/initialize_admin.php");
if (!$session_admin->is_logged_in()) {
    redirect_to("login.php");
}

$get_params = allowed_get_params(['x', 'id']);

$reg_id_encrypted = $get_params['id'];
$reg_id = decrypt(str_replace(" ", "+", $reg_id_encrypted));
$reg_id = preg_replace("/[^A-Za-z0-9 ]/", '', $reg_id);

$attendee_detail = $db_handle->fetchAssoc($db_handle->runQuery("SELECT * FROM dinner_2016 WHERE id_dinner_2016 = $reg_id"));
$attendee_detail = $attendee_detail[0];

extract($attendee_detail);
$id_encrypt = encrypt($id_dinner_2016);

$subject = "You're Specially Invited";
$message =
<<<MAIL
    <div style="background-color: #F3F1F2">
    <div style="max-width: 80%; margin: 0 auto; padding: 10px; font-size: 14px; font-family: Verdana;">
        <img src="https://instafxng.com/images/ifxlogo.png" />
        <hr />
        <div style="background-color: #FFFFFF; padding: 15px; margin: 5px 0 5px 0;">
            <p>Dear $full_name,</p>

            <p>It’s been almost 365 days and a lot has happened during the year,
            but get ready for one more!</p>

            <p>I want to take this opportunity to appreciate you for showing
            consistent confidence in the services we render and allowing us to
            service you this year. You are truly one of the most loyal clients
            of our company and serving you remains our pleasure.</p>

            <p>To roundup the year together, I humbly request that you join us
            for an exciting and entertaining time at our annual Christmas Dinner
            Event specially designed so you could relax and have fun with the
            members of our team and other Forex traders like yourself.</p>

            <p>This year’s dinner is themed the <strong>Classic 80s Night</strong>. When you step
            inside the venue; the music, the theme and overall ambience will
            transport you to a simpler and more innocent time where you can have the
            feel of what it used to be in the 80s.</p>

            <p>If you love to have fun, you should be at this event as the outfit,
            the looks the music and the venue will represent what it used to be
            from way back 80s, and there would be lots of exciting activities lined
            up alongside the 3 course dinner to be served.</p>

            <p style="text-align: center"><strong>One last thing ...</strong></p>

            <p>We have one last surprise for you before the year ends, you will
            witness the launch of a new service created to make you more money
            daily even as you trade.</p>

            <p style="text-align: center">You can
            <a href="https://instafxng.com/dinner.php?x=$id_encrypt">click here</a>
            to reserve your seat now.</p>

            <p><strong>NOTE: Admission is strictly by Invitation and you can only secure
            your reservation by clicking on the link.</strong></p>

            <p>It would be an honor to have you at the event as I earnestly look
            forward to welcoming you and meeting you in person.</p>

            <br /><br />
            <p>Best Regards,</p>
            <p>Fujah Abideen,<br />
            Corporate Communications Manager<br />
                www.instafxng.com</p>
            <br /><br />
        </div>
        <hr />
        <div style="background-color: #EBDEE9;">
            <div style="font-size: 11px !important; padding: 15px;">
                <p style="text-align: center"><span style="font-size: 12px"><strong>We"re Social</strong></span><br /><br />
                    <a href="https://facebook.com/InstaForexNigeria"><img src="https://instafxng.com/images/Facebook.png"></a>
                    <a href="https://twitter.com/instafxng"><img src="https://instafxng.com/images/Twitter.png"></a>
                    <a href="https://www.instagram.com/instafxng/"><img src="https://instafxng.com/images/instagram.png"></a>
                    <a href="https://www.youtube.com/channel/UC0Z9AISy_aMMa3OJjgX6SXw"><img src="https://instafxng.com/images/Youtube.png"></a>
                    <a href="https://linkedin.com/company/instaforex-ng"><img src="https://instafxng.com/images/LinkedIn.png"></a>
                </p>
                <p><strong>Head Office Address:</strong> TBS Place, Block 1A, Plot 8, Diamond Estate, Estate Bus-Stop, LASU/Isheri road, Isheri Olofin, Lagos.</p>
                <p><strong>Lekki Office Address:</strong> Road 5, Suite K137, Ikota Shopping Complex, Lekki/Ajah Express Road, Lagos State</p>
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

$system_object->send_email($subject, $message, $email, $full_name);

$db_handle->runQuery("UPDATE dinner_2016 SET invite = '2' WHERE id_dinner_2016 = $id_dinner_2016");

redirect_to("all_reg.php");