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

    // Log course deposit attempt
    public function log_course_deposit($user_code, $trans_id, $course_id, $course_cost, $stamp_duty, $card_processing, $pay_type, $origin_of_deposit, $client_name, $client_email) {
        global $db_handle;
        global $system_object;

        $query = "INSERT INTO user_edu_deposits (user_code, trans_id, course_id, amount, stamp_duty,
            gateway_charge, pay_method, deposit_origin) VALUES ('$user_code', '$trans_id', $course_id, $course_cost, $stamp_duty, $card_processing, '$pay_type', '$origin_of_deposit')";

        $db_handle->runQuery($query);

        $total_payment = $course_cost + $stamp_duty;
        $total_payment = number_format($total_payment, 2, ".", ",");

        // Send order invoice to client email address
        $subject = "Intermediate Mentorship Program Course Order Invoice and Schedule. - " . $trans_id;
        $body =
            <<<MAIL
            <div style="background-color: #F3F1F2">
    <div style="max-width: 80%; margin: 0 auto; padding: 10px; font-size: 14px; font-family: Verdana;">
        <img src="https://instafxng.com/images/ifxlogo.png" />
        <hr />
        <div style="background-color: #FFFFFF; padding: 15px; margin: 5px 0 5px 0;">
            <p>Dear $client_name,</p>

            <p>NOTE: This is a CONFIDENTIAL Document. Information herein should
            never be shared with anyone.</p>

            <p>THIS INVOICE IS VALID ONLY FOR 24 HOURS. IF PAYMENT IS NOT MADE BY THEN,
            YOU MUST SUBMIT ANOTHER ORDER.</p>

            <p>====================</p>

            <p>Your Transaction ID for the Intermediate Mentorship Program is [$trans_id]</p>

            <p>The details of your order are as follows:</p>

            <p>Payment for Intermediate Mentorship Program: (N $course_cost)</p>
            <p>Stamp Duty:        --                                  (N 50)</p>
            <p>Total Payment Due:           --            (N $total_payment)</p>

             <p>To complete your order, please make your payment as follows:</p>

            <p style="color: red">NOTE: Kindly make sure you pay into the account stated below.</p>

            <ol>
                <li>Any Branch of Guaranty Trust Bank (GTB)<br />
                Account Name: Instant Web-Net Technologies Ltd<br />
                Account Number: 0174516696
                </li>
                <li>After making the payment, visit <a href='https://instafxng.com/fxacademy'>https://instafxng.com/fxacademy</a> and click on NOTIFICATION.</li>
                <li>Fill in the column as stated on the page.</li>
            </ol>

            <p>Upon receipt of payment, you will be granted access to the Forex Profit Optimizer Course</p>

            <p>NOTE:</p>
            <ul>
                <li>Third party payments are not allowed.</li>
                <li>When making payment through internet banking platform, fill in your transaction ID $trans_id
                in the REMARK column.</li>
            </ul>

            <p>Kindly view your IMP training Schedule below.</p>




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

        $system_object->send_email($subject, $body, $client_email, $client_name);


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
                    <a href="https://facebook.com/InstaFxNg"><img src="https://instafxng.com/images/Facebook.png"></a>
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