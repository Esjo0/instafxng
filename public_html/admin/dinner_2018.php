<?php
require_once("../init/initialize_admin.php");
if (!$session_admin->is_logged_in()) {
    redirect_to("login.php");
}
$client_operation = new clientOperation();

$query_count = "SELECT * FROM dinner_2018 WHERE choice = '1' AND type <> '6'";
$total_seats_taken = $db_handle->numRows($query_count);

if (isset($_POST['search_text'])){
    $search = $db_handle->sanitizePost($_POST['search_text']);
    $query = "SELECT d.invite_code, d.type, d.id, d.state, d.title, d.town, d.created, d.name, d.phone, d.email, d.user_code, d.choice
    FROM dinner_2018 AS d
    WHERE name LIKE '%$search%' OR email LIKE '%$search%' OR phone LIKE '%$search%'
    ORDER BY d.created DESC ";
    $_SESSION['query_dinner'] = $query;
}
if (isset($_POST['edit'])) {
    $gender = $db_handle->sanitizePost($_POST['gender']);
    $choice = $db_handle->sanitizePost($_POST['choice']);
    $type = $db_handle->sanitizePost($_POST['type']);
    $title = $db_handle->sanitizePost($_POST['title']);
    $town = $db_handle->sanitizePost($_POST['town']);
    $state = $db_handle->sanitizePost($_POST['state']);
    $name = $db_handle->sanitizePost($_POST['name']);
    split_name($name);
    $phone = $db_handle->sanitizePost($_POST['phone']);
    $email = $db_handle->sanitizePost($_POST['email']);
    $id = $db_handle->sanitizePost($_POST['id']);
    $email = filter_var($email, FILTER_VALIDATE_EMAIL);
    if (!empty($id) && $id != NULL) {
        $query = "UPDATE dinner_2018 SET type = '$type', name = '$name', email = '$email', choice = '$choice',
                          title = '$title', town = '$town', gender = '$gender', state = '$state', phone = '$phone'
                          WHERE id = '$id'";
        $result = $db_handle->runQuery($query);
        if ($result) {
            $message_success = "Sucessfully Update $name's ticket";
        } else {
            $message_error = "Edit Not Successful";
        }
    }
}
if (isset($_POST['add'])) {
    $gender = $db_handle->sanitizePost($_POST['gender']);
    $choice = $db_handle->sanitizePost($_POST['choice']);
    $type = $db_handle->sanitizePost($_POST['type']);
    $title = $db_handle->sanitizePost($_POST['title']);
    $town = $db_handle->sanitizePost($_POST['town']);
    $state = $db_handle->sanitizePost($_POST['state']);
    $name = $db_handle->sanitizePost($_POST['name']);
    split_name($name);
    $phone = $db_handle->sanitizePost($_POST['phone']);
    $email = $db_handle->sanitizePost($_POST['email']);
    $email = filter_var($email, FILTER_VALIDATE_EMAIL);

    if (!empty($name) && !empty($choice) && !empty($type)) {
        if ($choice == 1 && !empty($gender) && $gender != NULL && !empty($town) && $town != NULL && !empty($state) && $state != NULL && !empty($title) && $title != NULL && !empty($name) && $name != NULL) {
            $query = "INSERT INTO dinner_2018 (name, email, choice, title, town, gender, state, type, phone) VALUE('$name', '$email', '$choice', '$title', '$town', '$gender', '$state', '$type', '$phone')";
            $result = $db_handle->runQuery($query);
            if ($result) {
                if ($gender == 1) {
                    $gender = "his";
                } elseif ($gender == 2) {
                    $gender = "her";
                }
                $subject = 'Your seat has been reserved, ' . $first_name . '!';
                $message_final = <<<MAIL
                    <div style="background-color: #F3F1F2;  background-image: url('https://instafxng.com/imgsource/dinner-seamless-doodle.png');">
                        <div style="max-width: 80%; margin: 0 auto; padding: 10px; font-size: 14px; font-family: 'Comic Sans MS', cursive, sans-serif; background-image: url('https://instafxng.com/imgsource/Mail%20Images/full-bloom.png');">
                                <img src="https://instafxng.com/images/ifxlogo.png" />
                            <hr />
                            <div style="background-color: transparent; padding: 15px; margin: 5px 0 5px 0;">
                                <p>All hail <b>$title</b> $name, the first of $gender name from <b>the house of $town</b></p>
                                <p>It is our pleasure to receive your consent to attend the Royal Ball</p>
                                <p>Your seat has been reserved and your dynasty is being prepared to receive your presence.</p>
                                <p>The royal invite will be sent when all is set, brace up it's going to be a ball to remember.</p>
                                <p>Compliment of the season</p>
                                <br /><br />
                                <p>Best Regards,</p>
                                <p>The InstaFxNg Team,<br />
                                   www.instafxng.com</p>
                                <br /><br />
                            </div>
                            <hr />
                            <div style="background-color: #EBDEE9;">
                                <div style="font-size: 11px !important; padding: 15px;">
                                    <p style="text-align: center"><span style="font-size: 12px"><strong>We"re Social</strong></span><br /><br />
                                        <a href="https://facebook.com/InstaFxNg"><img src="https://instafxng.com/images/Facebook.png"></a>
                                        <a href="https://twitter.com/instafxng"><img src="https://instafxng.com/images/Twitter.png"></a>
                                        <a href="https://www.instagram.com/instafxng/"><img src="https://instafxng.com/images/instagram.png"></a>
                                        <a href="https://www.youtube.com/channel/UC0Z9AISy_aMMa3OJjgX6SXw"><img src="https://instafxng.com/images/Youtube.png"></a>
                                        <a href="https://linkedin.com/company/instaforex-ng"><img src="https://instafxng.com/images/LinkedIn.png"></a>
                                    </p>
                                    <p><strong>Head Office Address:</strong> TBS Place, Block 1A, Plot 8, Diamond Estate, Estate Bus-Stop, LASU/Isheri road, Isheri Olofin, Lagos.</p>
                                    <p><strong>Lekki Office Address:</strong> Block A3, Suite 508/509 Eastline Shopping Complex, Opposite Abraham Adesanya Roundabout, along Lekki - Epe expressway, Lagos.</p>
                                    <p><strong>Office Number:</strong> 08139250268, 08083956750</p>
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
                $system_object->send_email($subject, $message_final, $email, $first_name);
                $message_success = "YOU HAVE SUCCESSFULLY RESERVED A SEAT FOR THE INSTAFXNG ROYAL BALL";
            } else {
                $message_error = "Reservation Not Successful Kindly Try Again";
            }
        }
        if ($choice == 2) {
            $query = "INSERT INTO dinner_2018 (name, email, choice, title, town, gender, state, type, phone) VALUE('$name', '$email', '$choice', '$title', '$town', '$gender', '$state', '$type', '$phone')";
            $result = $db_handle->runQuery($query);
            if ($result) {
                $subject = 'The Ball Will Be Brighter With Your Presence';
                $message_final = <<<MAIL
                    <div style="background-color: #F3F1F2;  background-image: url('https://instafxng.com/imgsource/dinner-seamless-doodle.png');">
                        <div style="max-width: 80%; margin: 0 auto; padding: 10px; font-size: 14px; font-family: 'Comic Sans MS', cursive, sans-serif; background-image: url('https://instafxng.com/imgsource/Mail%20Images/full-bloom.png');">
                                <img src="https://instafxng.com/images/ifxlogo.png" />
                            <hr />
                            <div style="background-color: transparent; padding: 15px; margin: 5px 0 5px 0;">
                            <p>Your royal highness,</p>
                            <p>It will be a pleasure to receive you at this year's Royal ball.</p>
                            <p>However, we understand that the season is very eventful and you are quite uncertain of your attendance.</p>
                            <p>To this end, our dynasty has decided to reserve your spot for the next 5 nights!</p>
                            <p>The royal raven will be back to get your final decision by the fifth night.</p>
                            <p>This ball will be one to remember forever… We look forward to hosting your royalty.</p>
                                <br /><br />
                                <p>Best Regards,</p>
                                <p>Best Regards,</p>
                                <p>The InstaFxNg Team,<br />
                                   www.instafxng.com</p>
                                <br /><br />
                            </div>
                            <hr />
                            <div style="background-color: #EBDEE9;">
                                <div style="font-size: 11px !important; padding: 15px;">
                                    <p style="text-align: center"><span style="font-size: 12px"><strong>We"re Social</strong></span><br /><br />
                                        <a href="https://facebook.com/InstaFxNg"><img src="https://instafxng.com/images/Facebook.png"></a>
                                        <a href="https://twitter.com/instafxng"><img src="https://instafxng.com/images/Twitter.png"></a>
                                        <a href="https://www.instagram.com/instafxng/"><img src="https://instafxng.com/images/instagram.png"></a>
                                        <a href="https://www.youtube.com/channel/UC0Z9AISy_aMMa3OJjgX6SXw"><img src="https://instafxng.com/images/Youtube.png"></a>
                                        <a href="https://linkedin.com/company/instaforex-ng"><img src="https://instafxng.com/images/LinkedIn.png"></a>
                                    </p>
                                    <p><strong>Head Office Address:</strong> TBS Place, Block 1A, Plot 8, Diamond Estate, Estate Bus-Stop, LASU/Isheri road, Isheri Olofin, Lagos.</p>
                                    <p><strong>Lekki Office Address:</strong> Block A3, Suite 508/509 Eastline Shopping Complex, Opposite Abraham Adesanya Roundabout, along Lekki - Epe expressway, Lagos.</p>
                                    <p><strong>Office Number:</strong> 08139250268, 08083956750</p>
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
                $system_object->send_email($subject, $message_final, $email, $last_name);

                $message_success = "PARTICIPANTS SEAT HAS BEEN TEMPORARILY RESERVED. KINDLY CONFIRM YOUR RESERVATION WITHIN THE NEXT
                                        5 DAYS.";
            } else {
                $message_error = "Reservation Not Successful Kindly Try Again";
            }
        }
        if ($choice == 3) {
            $query = "INSERT INTO dinner_2018 (name, email, choice, title, town, gender, state, type, phone) VALUE('$name', '$email', '$choice', '$title', '$town', '$gender', '$state', '$type', '$phone')";
            $result = $db_handle->runQuery($query);
            if ($result) {
                $subject = 'The Ball Would have been more fun with you ' . $first_name . '!';
                $message_final = <<<MAIL
                    <div style="background-color: #F3F1F2;  background-image: url('https://instafxng.com/imgsource/dinner-seamless-doodle.png');">
                        <div style="max-width: 80%; margin: 0 auto; padding: 10px; font-family: 'Comic Sans MS', cursive, sans-serif; background-image: url('https://instafxng.com/imgsource/Mail%20Images/full-bloom.png');">
                            <img src="https://instafxng.com/images/ifxlogo.png" />
                            <hr />
                            <div style="background-color: transparent; padding: 15px; margin: 5px 0 5px 0;">
                                <p>Dear $first_name,</p>
                                <p>The ball would be incomplete without your presence!</p>
                                <p>Even though our desire is to have you grace this year's grand dinner, we understand that there are other pertinent tasks that will require your time this season, hence your inability to attend this event.</p>
                                <p>We look forward to celebrating a greater feat with you next year.</p>
                                <p>Your invite for this year's ball has been canceled.</p>
                                <p>Compliment of the season.</p>
                                <br /><br />
                                <p>Best Regards,</p>
                                <p>The InstaFxNg Team,<br />
                                   www.instafxng.com</p>
                                <br /><br />
                            </div>
                            <hr />
                            <div style="background-color: #EBDEE9;">
                                <div style="font-size: 11px !important; padding: 15px;">
                                    <p style="text-align: center"><span style="font-size: 12px"><strong>We"re Social</strong></span><br /><br />
                                        <a href="https://facebook.com/InstaFxNg"><img src="https://instafxng.com/images/Facebook.png"></a>
                                        <a href="https://twitter.com/instafxng"><img src="https://instafxng.com/images/Twitter.png"></a>
                                        <a href="https://www.instagram.com/instafxng/"><img src="https://instafxng.com/images/instagram.png"></a>
                                        <a href="https://www.youtube.com/channel/UC0Z9AISy_aMMa3OJjgX6SXw"><img src="https://instafxng.com/images/Youtube.png"></a>
                                        <a href="https://linkedin.com/company/instaforex-ng"><img src="https://instafxng.com/images/LinkedIn.png"></a>
                                    </p>
                                    <p><strong>Head Office Address:</strong> TBS Place, Block 1A, Plot 8, Diamond Estate, Estate Bus-Stop, LASU/Isheri road, Isheri Olofin, Lagos.</p>
                                    <p><strong>Lekki Office Address:</strong> Block A3, Suite 508/509 Eastline Shopping Complex, Opposite Abraham Adesanya Roundabout, along Lekki - Epe expressway, Lagos.</p>
                                    <p><strong>Office Number:</strong> 08139250268, 08083956750</p>
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
                $system_object->send_email($subject, $message_final, $email, $last_name);
                $message_success = "PARTICIPANT WILL SURELY INVITE YOU FOR SUBSEQUENT EVENTS.";
            } else {
                $message_error = "Reservation Not Successful Kindly Try Again";
            }
        }
    }else {
        $message_error = "Kindly ensure you fill Participant Name Choice and ticket type";

    }
}
if(isset($_POST['filter'])){
    $filter = $db_handle->sanitizePost(trim($_POST['filt_val']));
    if($filter == 1){
        $single = true;
        $filter = "d.type = '$filter'";
    }
    elseif($filter == 2){
        $double = true;
        $filter = "d.type = '$filter'";
    }
    elseif($filter == 3){
        $vip = true;
        $filter = "d.type = '$filter'";
    }
    elseif($filter == 4){
        $vvip = true;
        $filter = "d.type = '$filter'";
    }
    elseif($filter == 5){
        $team = true;
        $filter = "d.type = '$filter'";
    }
    elseif($filter == 6){
        $vendor = true;
        $filter = "d.type = '$filter'";
    }
    elseif($filter == 10){
        $yes = true;
        $filter = "d.choice = '1'";
    }
    elseif($filter == 20){
        $maybe = true;
        $filter = "d.choice = '2'";
    }
    elseif($filter == 30){
        $no = true;
        $filter = "d.choice = '3'";
    }


        $query = "SELECT d.invite_code, d.type, d.id, d.state, d.title, d.town, d.created, d.name, d.phone, d.email, d.user_code, d.choice
    FROM dinner_2018 AS d
    WHERE $filter
    ORDER BY d.created DESC ";

    $_SESSION['query_dinner'] = $query;

}elseif(empty($_SESSION['query_dinner']) || isset($_POST['all'])){

$query = "SELECT d.invite_code, d.type, d.id, d.state, d.title, d.town, d.created, d.name, d.phone, d.email, d.user_code, d.choice
    FROM dinner_2018 AS d
    WHERE d.choice IS NOT NULL
    ORDER BY d.created DESC ";
    $_SESSION['query_dinner'] = $query;
}
$query = $_SESSION['query_dinner'];

$numrows = $db_handle->numRows($query);

$rowsperpage = 40;

$totalpages = ceil($numrows / $rowsperpage);
// get the current page or set a default
if (isset($_GET['pg']) && is_numeric($_GET['pg'])) {
    $currentpage = (int)$_GET['pg'];
} else {
    $currentpage = 1;
}
if ($currentpage > $totalpages) {
    $currentpage = $totalpages;
}
if ($currentpage < 1) {
    $currentpage = 1;
}

$prespagelow = $currentpage * $rowsperpage - $rowsperpage + 1;
$prespagehigh = $currentpage * $rowsperpage;
if ($prespagehigh > $numrows) {
    $prespagehigh = $numrows;
}

$offset = ($currentpage - 1) * $rowsperpage;
$query .= 'LIMIT ' . $offset . ',' . $rowsperpage;
$result = $db_handle->runQuery($query);
$participants = $db_handle->fetchAssoc($result);

// Send bulk
if (isset($_POST['send_bulk']) ) {
if($numrows > 0) {
    $campaign_email_id = $_POST['campaign_email_id'];
    $selected_campaign_email = $system_object->get_campaign_email_by_id($campaign_email_id);

    $my_subject = trim($selected_campaign_email['subject']);
    $my_message = stripslashes($selected_campaign_email['content']);
    $mail_sender = trim($selected_campaign_email['sender']);

    $query = $_SESSION['query_dinner'];
    $result = $db_handle->runQuery($query);
    $recipients = $db_handle->fetchAssoc($result);
    foreach ($recipients as $sendto) {
        extract($sendto);
        if(empty($email)){$email = $client_email;}
        $query = "SELECT user_code, first_name FROM user WHERE email = '$email' LIMIT 1";
        $result = $db_handle->runQuery($query);
        $fetched_data = $db_handle->fetchAssoc($result);
        $selected_member = $fetched_data[0];

        $client_name = ucwords(strtolower(trim($selected_member['first_name'])));

        // Replace [NAME] with clients full name
        $my_message_new = str_replace('[NAME]', $client_name, $my_message);
        $my_subject_new = str_replace('[NAME]', $client_name, $my_subject);

        if (array_key_exists('user_code', $selected_member)) {
            $user_code = $selected_member['user_code'];
            $encode_dinner_id = dec_enc('encrypt', get_ticket_id($email));

            $encrypted_user_code = encrypt($user_code);
            $black_friday_link = "<a title='Click Here to enjoy the splurge' href='https://instafxng.com/black_friday_splurge.php?x=$encrypted_user_code'><strong>Click Here to set your target Now!</strong></a>";
            $dinner_2018 = "<a title='Click Here to reserve your seat' href='https://instafxng.com/dinner.php?r=$encrypted_user_code'><strong>Click Here to reserve your seat</strong></a>";
            $dinner_invite_2018 = "<a title='Click Here to Download your invite' href='https://instafxng.com/dinner_invite.php?i=$encode_dinner_id'><strong>Click Here to Download Your Royal Invite</strong></a>";
            $found_position_month = in_array_r($user_code, $found_loyalty_month);
            $month_position = $found_position_month['position'];
            $month_rank = number_format(($found_position_month['rank']), 2, ".", ",");
            $month_rank_highest = number_format(($found_loyalty_month[0]['rank']), 2, ".", ",");
            $month_rank_difference = number_format(($month_rank_highest - $month_rank), 2, ".", ",");
            $month_rank_goal = number_format(($month_rank_difference / $days_left_this_month), 2, ".", ",");

            $found_position_year = in_array_r($user_code, $found_loyalty_year);
            $year_position = $found_position_year['position'];
            $year_rank = number_format(($found_position_year['rank']), 2, ".", ",");
            $year_rank_highest = number_format(($found_loyalty_year[0]['rank']), 2, ".", ",");
            $year_rank_difference = number_format(($year_rank_highest - $year_rank), 2, ".", ",");
            $year_rank_goal = number_format(($year_rank_difference / $days_left_this_month), 2, ".", ",");

            $last_trade_detail = $client_operation->get_last_trade_detail($user_code);
            $last_trade_volume = $last_trade_detail['volume'];
            $last_trade_date = $last_trade_detail['date_earned'];

            $funded = $client_operation->get_total_funding($user_code, $from_date, $to_date);
            $withdrawn = $client_operation->get_total_withdrawal($user_code, $from_date, $to_date);

            $splurge_detail = $client_operation->get_splurge_user_point($user_code);
            $splurge_total_points = $splurge_detail['total_points'];
            $splurge_tier_target = $splurge_detail['tier_target'];

            $my_message_new = str_replace('[DINNER]', $dinner_2018, $my_message_new);
            $my_message_new = str_replace('[DINNER-INVITE]', $dinner_invite_2018, $my_message_new);

            $my_message_new = str_replace('[LPMP]', $month_position, $my_message_new);
            $my_message_new = str_replace('[LPMR]', $month_rank, $my_message_new);
            $my_message_new = str_replace('[LPMHR]', $month_rank_highest, $my_message_new);
            $my_message_new = str_replace('[LPMD]', $month_rank_difference, $my_message_new);
            $my_message_new = str_replace('[LPMG]', $month_rank_goal, $my_message_new);
            $my_message_new = str_replace('[LPYP]', $year_position, $my_message_new);
            $my_message_new = str_replace('[LPYR]', $year_rank, $my_message_new);
            $my_message_new = str_replace('[LPYHR]', $year_rank_highest, $my_message_new);
            $my_message_new = str_replace('[LPYG]', $year_rank_difference, $my_message_new);
            $my_message_new = str_replace('[LPYD]', $year_rank_goal, $my_message_new);
            $my_message_new = str_replace('[UC]', encrypt($user_code), $my_message_new);
            $my_message_new = str_replace('[LTD]', $last_trade_date, $my_message_new);
            $my_message_new = str_replace('[LTV]', $last_trade_volume, $my_message_new);
            $my_message_new = str_replace('[FUNDED]', $funded, $my_message_new);
            $my_message_new = str_replace('[WITHDRAWN]', $withdrawn, $my_message_new);
            $my_message_new = str_replace('[BFL]', $black_friday_link, $my_message_new);
            $my_message_new = str_replace('[SLP]', $splurge_total_points, $my_message_new);
            $my_message_new = str_replace('[SLL]', $splurge_tier_target, $my_message_new);

            $my_message_new = str_replace('[LPMP]', '', $my_message_new);
            $my_message_new = str_replace('[LPMR]', '', $my_message_new);
            $my_message_new = str_replace('[LPMHR]', '', $my_message_new);
            $my_message_new = str_replace('[LPMD]', '', $my_message_new);
            $my_message_new = str_replace('[LPMG]', '', $my_message_new);
            $my_message_new = str_replace('[LPYP]', '', $my_message_new);
            $my_message_new = str_replace('[LPYR]', '', $my_message_new);
            $my_message_new = str_replace('[LPYHR]', '', $my_message_new);
            $my_message_new = str_replace('[LPYG]', '', $my_message_new);
            $my_message_new = str_replace('[LPYD]', '', $my_message_new);
            $my_message_new = str_replace('[UC]', '', $my_message_new);
            $my_message_new = str_replace('[LTD]', '', $my_message_new);
            $my_message_new = str_replace('[LTV]', '', $my_message_new);
            $my_message_new = str_replace('[FUNDED]', '', $my_message_new);
            $my_message_new = str_replace('[WITHDRAWN]', '', $my_message_new);
            $my_message_new = str_replace('[BFL]', '', $my_message_new);
            $my_message_new = str_replace('[SLP]', '', $my_message_new);
            $my_message_new = str_replace('[SLL]', '', $my_message_new);
        }

        $system_object->send_email($my_subject_new, $my_message_new, $email, $client_name, $mail_sender);

    }
    $message_success = "You have successfully sent the single email.";
}elseif($numrows == 0) {
    $message_error = "No Participant Selected";
}else{
    $message_error = "Please Kindly use the single Email Button.";

}
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <base target="_self">
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Instaforex Nigeria | Admin - Dinner 2018</title>
    <meta name="title" content="Instaforex Nigeria | Admin - Dinner 2018"/>
    <meta name="keywords" content=""/>
    <meta name="description" content=""/>
    <?php require_once 'layouts/head_meta.php'; ?>
    <script>
        function proceed(){
            document.getElementById("proceed").style.display = "block";
            document.getElementById("pro").style.display = "none";
        }
    </script>
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
                    <h4><strong>Dinner Participants</strong></h4>
                </div>
            </div>

            <div class="section-tint super-shadow">
                <div class="row">
                    <div class="col-sm-12">
                        <?php include 'layouts/feedback_message.php'; ?>
                        <button type="" style="display:none" class="btn btn-danger" disabled>GENERATE INVITE CODE</button>

                        <div class="search-section">
                            <div class="row">

                                <form action="" method="post" class="form horizontal">
                                <div class="col-sm-6">
                                    <select name="filt_val" class="form-control  input-sm" id="filter" required>
                                        <option value="" >Filter by</option>
                                        <option value="1" <?php if($single){echo 'selected';}?>>Single Ticket</option>
                                        <option value="2" <?php if($double){echo 'selected';}?>>Double Ticket</option>
                                        <option value="3" <?php if($vip){echo 'selected';}?>>VIP</option>
                                        <option value="4" <?php if($vvip){echo 'selected';}?>>VVIP</option>
                                        <option value="5" <?php if($team){echo 'selected';}?>>TEAM MEMBERS</option>
                                        <option value="6" <?php if($vendor){echo 'selected';}?>>VENDORS</option>
                                        <option value="10" <?php if($yes){echo 'selected';}?>>All Tickets with Attendance Status - YES</option>
                                        <option value="20" <?php if($maybe){echo 'selected';}?>>All Tickets with Attendance Status - MAYBE</option>
                                        <option value="30" <?php if($no){echo 'selected';}?>>All Tickets with Attendance Status - NO</option>

                                    </select>
                                </div>
                                <span class="col-sm-1 input-group-btn">
                    <button class="btn btn-success btn-sm pull-left" type="submit" name="filter">FILTER</button>
                        </span>
                                </form>


                                <div class="col-sm-5 pull-right">
                                    <form data-toggle="validator" class="form-horizontal" role="form" method="post" action="<?php echo $REQUEST_URI; ?>">
                                        <div class="input-group">
                                            <input type="hidden" name="search_param" value="all" id="search_param">
                                            <input type="text" class="form-control" name="search_text" placeholder="Search term..." required>
                                        <span class="input-group-btn">
                                            <button class="btn btn-default" type="submit"><span class="glyphicon glyphicon-search"></span></button>
                                        </span>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                        <h3 class="text-center">Total Seats Taken <?php echo $total_seats_taken?></h3>
                <div class="col-md-7 pull-right">
                    <form action="" method="post">
                        <button class="btn btn-default  btn-sm pull-left" type="submit" name="all">View All</button></form>
                    <button class="btn btn-default  btn-sm pull-right" type="button"
                            data-target="#mail" data-toggle="modal">SEND BULK MAIL
                    </button>
                    <button class="btn btn-primary btn-sm pull-right" type="button"
                            data-target="#add" data-toggle="modal" >
                        <i class="fa fa-plus-circle"></i>ADD PARTICIPANT
                    </button>
                </div>

                        <!--Modal - confirmation boxes-->
                        <div id="add" tabindex="-1" role="dialog" aria-hidden="true"
                             class="modal fade">
                            <form class="form-vertical text-center" role="form" method="post" action="">
                                <div class="modal-dialog modal-sm">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <button type="button" data-dismiss="modal" aria-hidden="true"
                                                    class="close">&times;</button>
                                            <h4 class="modal-title">Add Dinner Participant</h4>
                                        </div>
                                        <div class="modal-body">
                                            <div class="form-group mx-sm-4 mb-2">
                                                <div for="input" class="sr-only">Email Address</div>
                                                <input name="email" type="text" class="form-control"
                                                       placeholder="Enter Client Email ">
                                            </div>
                                            <div class="form-group mx-sm-4 mb-2">
                                                <input name="name" type="text" class="form-control"
                                                       placeholder="Enter Full Name" required>
                                            </div>

                                            <div class="form-group mx-sm-4 mb-2">
                                                <input name="phone" type="text"
                                                       class="form-control"
                                                       placeholder="Enter Phone No." required>
                                            </div>

                                            <div class="form-group mx-sm-4 mb-2">
                                                <select name="choice" class="form-control " required>
                                                    <option value="">Select Participants Choice</option>
                                                    <option value="1">YES</option>
                                                    <option value="2">MAYBE</option>
                                                    <option value="3">NO</option>
                                                </select>
                                            </div>

                                            <div class="form-group mx-sm-4 mb-2">
                                                <select name="type" class="form-control ">
                                                    <option value="">Select Ticket Type</option>
                                                    <option value="1">Single</option>
                                                    <option value="2">Double</option>
                                                    <option value="3">VIP</option>
                                                    <option value="4">VVIP</option>
                                                    <option value="5">TEAM MEMBER</option>
                                                    <option value="6">VENDOR</option>

                                                </select>
                                            </div>

                                            <div class="form-group mx-sm-4 mb-1">
                                                <label for="input" class="sr-only">Select Title</label>
                                                <select name="title" id="mode" class="form-control" required>
                                                    <option value="">Select Title</option>
                                                    <optgroup label="Titles">
                                                        <option value="Emperor">Emperor</option>
                                                        <option value="King">King</option>
                                                        <option value="Duke">Duke</option>
                                                        <option value="Prince">Prince</option>
                                                        <option value="Knight">Knight</option>
                                                        <option value="Sir">Sir</option>
                                                        <option value="Viscount">Viscount</option>
                                                        <option value="Lord">Lord</option>
                                                        <option value="Empress">Empress</option>
                                                        <option value="Queen">Queen</option>
                                                        <option value="Princess">Princess</option>
                                                        <option value="Duchess">Duchess</option>
                                                        <option value="Lady">Lady</option>
                                                        <option value="Baronet">Baronet</option>
                                                        <option value="Earldom">Earldom</option>
                                                        <option value="Viscountess">Viscountess</option>
                                                    </optgroup>
                                                </select>
                                            </div>

                                            <div class="form-group mx-sm-4 mb-2">
                                                <label for="input" class="sr-only">Home Town</label>
                                                <input name="town" type="text" class="form-control"
                                                       placeholder="Enter Home town" required>
                                            </div>

                                            <div class="form-group mx-sm-4 mb-1">
                                                <select name="gender" id="mode" class="form-control" required>
                                                    <option value="">Select Gender</option>
                                                    <option value="1">Male</option>
                                                    <option value="2">Female</option>
                                                </select>
                                            </div>
                                            <div class="form-group mx-sm-4 mb-1">
                                                <label for="input" class="sr-only">Select State</label>
                                            <select id="state" name="state" class="form-control" required>
                                                <option value="">Select State of residence</option>
                                                <optgroup label="Nigerian States">
                                                    <option value="Abia State">Abia State</option>
                                                    <option value="Adamawa State">Adamawa State</option>
                                                    <option value="Akwa Ibom State">Akwa Ibom State</option>
                                                    <option value="Anambra State">Anambra State</option>
                                                    <option value="Bauchi State">Bauchi State</option>
                                                    <option value="Bayelsa State">Bayelsa State</option>
                                                    <option value="Benue State">Benue State</option>
                                                    <option value="Borno State">Borno State</option>
                                                    <option value="Cross River State">Cross River State</option>
                                                    <option value="Delta State">Delta State</option>
                                                    <option value="Ebonyi State">Ebonyi State</option>
                                                    <option value="Edo State">Edo State</option>
                                                    <option value="Ekiti State">Ekiti State</option>
                                                    <option value="Enugu State">Enugu State</option>
                                                    <option value="FCT Abuja">FCT Abuja</option>
                                                    <option value="Gombe State">Gombe State</option>
                                                    <option value="Imo State">Imo State</option>
                                                    <option value="Jigawa State">Jigawa State</option>
                                                    <option value="Kaduna State">Kaduna State</option>
                                                    <option value="Kano State">Kano State</option>
                                                    <option value="Katsina State">Katsina State</option>
                                                    <option value="Kebbi State">Kebbi State</option>
                                                    <option value="Kogi State">Kogi State</option>
                                                    <option value="Kwara State">Kwara State</option>
                                                    <option value="Lagos State">Lagos State</option>
                                                    <option value="Nasarawa State">Nasarawa State</option>
                                                    <option value="Niger State">Niger State</option>
                                                    <option value="Ogun State">Ogun State</option>
                                                    <option value="Ondo State">Ondo State</option>
                                                    <option value="Osun State">Osun State</option>
                                                    <option value="Oyo State">Oyo State</option>
                                                    <option value="Plateau State">Plateau State</option>
                                                    <option value="Rivers State">Rivers State</option>
                                                    <option value="Sokoto State">Sokoto State</option>
                                                    <option value="Taraba State">Taraba State</option>
                                                    <option value="Yobe State">Yobe State</option>
                                                    <option value="Zamfara State">Zamfara State</option>
                                                </optgroup>
                                            </select>
                                                </div>
                                        </div>
                                        <div class="modal-footer">
                                            <input name="add" type="submit"
                                                   class="btn btn-sm btn-default" value="ADD">
                                            <button type="button" name="close" onClick="window.close();"
                                                    data-dismiss="modal" class="btn btn-sm btn-danger">Close!
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </form>
                        </div>
<!--                        Modal end-->
                        <!--Modal - confirmation boxes-->
                        <div id="mail" tabindex="-1" role="dialog" aria-hidden="true"
                             class="modal fade">
                            <form class="form-vertical text-center" role="form" method="post" action="">
                                <div class="modal-dialog modal-md">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <button type="button" data-dismiss="modal" aria-hidden="true"
                                                    class="close">&times;</button>
                                            <h4 class="modal-title">Send Single Bulk Mail</h4>
                                        </div>
                                        <div class="modal-body">

                                            <p>Total Number of Recipients <b><?php echo $numrows;?></b> <br> Select the <b>subject of the email</b> you want to Braodcast to clients
                                            <br><i class="fa fa-info-circle"></i>Enter the first letter of the subject to search</p>
                                            <div class="form-group mx-sm-4 mb-2">
                                                <strong><select name="campaign_email_id" class="form-control " required>
                                                    <?php
                                                    $query = "SELECT ce.subject AS campaign_subject, ce.campaign_email_id AS campaign_email_id, ce.sender AS campaign_sender
                                                              FROM campaign_email AS ce
                                                              INNER JOIN campaign_category AS cc ON ce.campaign_category_id = cc.campaign_category_id
                                                              LEFT JOIN campaign_email_track AS cet ON cet.campaign_id = ce.campaign_email_id
                                                              ORDER BY ce.created DESC ";
                                                    $result = $db_handle->runQuery($query);
                                                    $result = $db_handle->fetchAssoc($result);
                                                    foreach ($result as $row_email) {
                                                        extract($row_email)
                                                        ?>
                                                        <option
                                                            value="<?php if(isset($campaign_email_id)) { echo $campaign_email_id; } ?>"><?php echo $campaign_subject.' - SENDER ~'.$campaign_sender ; ?></option>
                                                    <?php } ?>
                                                </select>
                                                    </strong>
                                            </div>

                                        </div>
                                        <div class="modal-footer">
                                            <button id="pro" onclick="proceed()" type="button" class="btn btn-default">PROCEED</button>

                                            <span id="proceed" style="display:none"><b>Are you ready to send this mail?</b>
                                            <input name="send_bulk" type="submit"
                                                   class="btn btn-sm btn-danger" value="YES/SEND">
                                                </span>
                                            <button type="button" name="close" onClick="window.close();"
                                                    data-dismiss="modal" class="btn btn-sm btn-danger">Close!
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </form>
                        </div>
                        <!--                        Modal end-->
                       <table class="table table-responsive table-striped table-bordered table-hover">
                            <thead>
                            <tr>
                                <th>Client Details</th>
                                <th>Date</th>
                                <th>Title</th>
                                <th>Residence</th>
                                <th></th>
                            </tr>
                            </thead>
                            <tbody>
                            <?php
                            if (isset($participants) && !empty($participants)) {
                                foreach ($participants as $row) {
                                    extract($row);
                                        if(empty($name)){$name = $client_full_name;}
                                        if(empty($phone)){$phone = $client_phone_number;}
                                        if(empty($email)){$email = $client_email;}

                                        ?>
                                    <tr>
                                        <td><?php echo $name; ?>
                                            <br>
                                            <?php echo $email ; ?>
                                            <br>
                                            <?php echo $phone ; ?>
                                            <span class="badge" title="Ticket Type">
                                                <?php if($type == 1){echo 'Single';}?>
                                                <?php if($type == 2){echo 'Double';}?>
                                                <?php if($type == 3){echo 'VIP';}?>
                                                <?php if($type == 4){echo 'VVIP';}?>
                                                <?php if($type == 5){echo 'TEAM MEMBER';}?>
                                                <?php if($type == 6){echo 'VENDOR';}?>
                                            </span>

                                        </td>
                                        <td><?php echo datetime_to_text($created)?>
                                            <span class="badge" title="Attendance Status">
                                            <?php if ($choice == 1) {
                                                echo "Yes";
                                            } elseif ($choice == 2) {
                                                echo "Maybe";
                                            } elseif ($choice == 3) {
                                                echo "No";
                                            }?>
                                                </span>
                                        </td>
                                        <td><?php if(!empty($title)){echo $title." of ".$town;}else{ echo "Nill"; }?></td>
                                        <td><?php if(!empty($state)){echo $state;}else{ echo "Nill"; }?></td>
                                        <td nowrap="nowrap">
                                            <?php if($user_code != NULL){?><a target="_blank" title="View" class="btn btn-sm btn-info"
                                               href="client_detail.php?id=<?php echo encrypt_ssl($user_code); ?>"><i
                                                    class="glyphicon glyphicon-eye-open icon-white"></i> </a><?php }?>
                                            <a class="btn btn-sm btn-primary" title="Send Email"
                                               href="campaign_email_single.php?name=<?php $name;
                                               echo encrypt_ssl($name) . '&email=' . encrypt_ssl($email); ?>"><i
                                                    class="glyphicon glyphicon-envelope"></i></a>
                                            <a class="btn btn-sm btn-success" title="Send SMS"
                                               href="campaign_sms_single.php?lead_phone=<?php echo encrypt_ssl($phone) ?>"><i
                                                    class="glyphicon glyphicon-phone-alt"></i></a>
                                            <button class="btn btn-primary btn-sm" type="button"
                                                    data-target="#edit<?php echo $id; ?>" data-toggle="modal" title="Edit Participants Ticket" >
                                                <i class="fa fa-pencil-square-o"></i>
                                            </button>
                                            <!--Modal - confirmation boxes-->
                                            <div id="edit<?php echo $id; ?>" tabindex="-1" role="dialog" aria-hidden="true"
                                                 class="modal fade">
                                                <form class="form-vertical text-center" role="form" method="post" action="">
                                                    <div class="modal-dialog modal-sm">
                                                        <div class="modal-content">
                                                            <div class="modal-header">
                                                                <button type="button" data-dismiss="modal" aria-hidden="true"
                                                                        class="close">&times;</button>
                                                                <h4 class="modal-title">Edit Dinner Ticket</h4>
                                                            </div>
                                                            <div class="modal-body">
                                                                <input value="<?php echo $id; ?>" name="id" type="hidden" class="form-control"
                                                                       required>
                                                                <label>Email</label>
                                                                <div class="form-group mx-sm-4 mb-2">
                                                                    <input value="<?php echo $email; ?>" name="email" type="text" class="form-control"
                                                                           placeholder="Enter Client Email "/>
                                                                </div>
                                                                <label>Name</label>
                                                                <div class="form-group mx-sm-4 mb-2">
                                                                    <input value="<?php echo $name; ?>" name="name" type="text" class="form-control"
                                                                           placeholder="Enter Client Email " required/>
                                                                </div>

                                                                <label>Phone</label>
                                                                <div class="form-group mx-sm-4 mb-2">
                                                                    <input value="<?php echo $phone; ?>" type="text"
                                                                           name="phone"
                                                                           class="form-control"
                                                                           placeholder="Enter Phone No." required/>
                                                                </div>



                                                                <label>Attendance Status</label>
                                                                <div class="form-group mx-sm-4 mb-2">
                                                                    <select name="choice" class="form-control " required>
                                                                        <option value="1" <?php if($choice == 1){echo 'selected';}?>>YES</option>
                                                                        <option value="2" <?php if($choice == 2){echo 'selected';}?>>MAYBE</option>
                                                                        <option value="3" <?php if($choice == 3){echo 'selected';}?>>NO</option>
                                                                    </select>
                                                                </div>

                                                                <label>Ticket Type</label>
                                                                <div class="form-group mx-sm-4 mb-2">
                                                                    <select name="type" class="form-control ">
                                                                        <option value="1" <?php if($type == 1){echo 'selected';}?>>Single</option>
                                                                        <option value="2" <?php if($type == 2){echo 'selected';}?>>Double</option>
                                                                        <option value="3" <?php if($type == 3){echo 'selected';}?>>VIP</option>
                                                                        <option value="4" <?php if($type == 4){echo 'selected';}?>>VVIP</option>
                                                                        <option value="5" <?php if($type == 5){echo 'selected';}?>>TEAM MEMBER</option>
                                                                        <option value="6" <?php if($type == 6){echo 'selected';}?>>VENDOR</option>

                                                                    </select>
                                                                </div>

                                                                <label>Title</label>
                                                                <div class="form-group mx-sm-4 mb-2">
                                                                    <input value="<?php echo $title; ?>" name="title" type="text" class="form-control"
                                                                           placeholder="Enter Title" required/>
                                                                </div>

                                                                <label >Home Town</label>
                                                                <div class="form-group mx-sm-4 mb-2">
                                                                    <input value="<?php echo $town; ?>" name="town" type="text" class="form-control"
                                                                           placeholder="Enter Home town" required/>
                                                                </div>

                                                                    <label>Gender</label>
                                                                    <div class="form-group mx-sm-4 mb-1">
                                                                    <select name="gender" id="mode" class="form-control" required>
                                                                        <option value="1" <?php if($type == 1){echo 'selected';}?>>Male</option>
                                                                        <option value="2" <?php if($type == 2){echo 'selected';}?>>Female</option>
                                                                    </select>
                                                                </div>

                                                                        <label>State of Residence</label>
                                                                        <div class="form-group mx-sm-4 mb-2">
                                                                    <input value="<?php echo $state; ?>" name="state" type="text" class="form-control"
                                                                           placeholder="Enter Home town" required/>
                                                                </div>
                                                            </div>
                                                            <div class="modal-footer">
                                                                <input name="edit" type="submit"
                                                                       class="btn btn-sm btn-default" value="SUBMIT">
                                                                <button type="button" name="close" onClick="window.close();"
                                                                        data-dismiss="modal" class="btn btn-sm btn-danger">Close!
                                                                </button>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </form>
                                            </div>
                                            <!--                        Modal end-->
                                            <br>
                                            <span class="badge" title="INVITE CODE">
                                                <?php if($invite_code != NULL){echo $invite_code;}?>
                                                </span>
                                        </td>
                                    </tr>
                                <?php }
                            } else {
                                echo "<tr><td colspan='5' class='text-danger'><em>No results to display</em></td></tr>";
                            } ?>
                            </tbody>
                        </table>

                        <?php if (isset($participants) && !empty($participants)) { ?>
                            <div class="tool-footer text-right">
                                <p class="pull-left">
                                    Showing <?php echo $prespagelow . " to " . $prespagehigh . " of " . $numrows; ?>
                                    entries</p>
                            </div>
                        <?php } ?>
                    </div>
                </div>

                <?php if (isset($participants) && !empty($participants)) {
                    require_once 'layouts/pagination_links.php';
                } ?>
            </div>

            <!-- Unique Page Content Ends Here
            ================================================== -->

        </div>

    </div>
</div>
<?php require_once 'layouts/footer.php'; ?>
</body>
</html>