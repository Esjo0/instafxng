<?php

class training
{

    public function schedule_public_time($date, $mode, $no_of_students, $admin, $location)
    {
        global $db_handle;
        $query = "INSERT INTO training_schedule_dates (schedule_date, no_of_students, schedule_type, schedule_mode, admin, location) VALUE('$date', $no_of_students, '1', '$mode', '$admin', '$location')";
        $result = $db_handle->runQuery($query);
        if ($result) {
            return true;
        } else {
            return false;
        }
    }

    public function schedule_private_time($date, $mode, $email, $admin, $location, $schedule_type)
    {
        global $db_handle;
        $query = "SELECT user_code FROM user WHERE email = '$email'";
        $result = $db_handle->runQuery($query);
        $email = $db_handle->fetchAssoc($result);
        foreach ($email as $row) {
            extract($row);
            $user_code = $user_code;
        }
        if ($email) {
            $query = "INSERT INTO training_schedule_dates (schedule_date, no_of_students, schedule_type, schedule_mode, admin, location) VALUE('$date', 1, '$schedule_type', '$mode', '$admin', '$location')";
            $result = $db_handle->runQuery($query);
            if ($result) {
                $query = "SELECT schedule_id FROM training_schedule_dates ORDER BY schedule_id DESC LIMIT 1";
                $result = $db_handle->runQuery($query);
                $id = $db_handle->fetchAssoc($result);
                foreach ($id as $row) {
                    extract($row);
                    $id = $schedule_id;
                }

                $query = "INSERT INTO training_schedule_students (user_code, schedule_id, status) VALUE('$user_code', $id, '0')";
                $result2 = $db_handle->runQuery($query);
                if ($result2 == false) {
                    return false;
                }
                return true;
            } else {
                return false;
            }
        } else {
            return false;
        }
    }

    public function reschedule($date, $mode, $id, $admin, $status, $location)
    {
        global $db_handle;
        $query = "INSERT INTO training_schedule_dates (schedule_date, no_of_students, schedule_type, schedule_mode, admin, location) VALUE('$date', 1, '2', '$mode', '$admin', '$location')";
        $result = $db_handle->runQuery($query);
        if ($result) {
            $query = "SELECT schedule_id FROM training_schedule_dates ORDER BY schedule_id DESC LIMIT 1";
            $result = $db_handle->runQuery($query);
            $get_id = $db_handle->fetchAssoc($result);
            foreach ($get_id as $row) {
                extract($row);
                $_id = $schedule_id;
            }

            $query = "UPDATE training_schedule_students SET schedule_id = '$_id', status = '$status' WHERE id = $id ";
            $result2 = $db_handle->runQuery($query);
            if ($result2 == false) {
                return false;
            }
            return true;
        } else {
            return false;
        }
    }

    public function get_available_dates()
    {
        $from_date = date('Y-m-d');
        $to_date = date('Y-m') . "-31";
        global $db_handle;
        $query = "SELECT schedule_id, schedule_date, no_of_students, location, schedule_mode FROM training_schedule_dates WHERE schedule_type = '1' AND (STR_TO_DATE(schedule_date, '%Y-%m-%d') BETWEEN '$from_date' AND '$to_date') ORDER BY schedule_date";
        $result = $db_handle->runQuery($query);
        $available_dates = $db_handle->fetchArray($result);
        return $available_dates;
    }

    public function send_mail($content, $email, $name)
    {
        $system_object = new InstafxngSystem();
        $sender = "INSTAFXNG";
        // Replace [NAME] with clients full name
        $my_content =
            <<<MAIL
    <div style="background-color: #F3F1F2">
    <div style="max-width: 80%; margin: 0 auto; padding: 10px; font-size: 14px; font-family: Verdana;">
        <img src="https://instafxng.com/images/ifxlogo.png" />
        <hr />
        <div style="background-color: #FFFFFF; padding: 15px; margin: 5px 0 5px 0;">

            $content

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
        $my_subject_new = "TRAINING SCHEDULE";
        $sent_email = $system_object->send_email($my_subject_new, $my_content, $email, $name, $sender);
        if ($sent_email) {
            return true;
        } else {
            return false;
        }
    }


}

$obj_training = new training();