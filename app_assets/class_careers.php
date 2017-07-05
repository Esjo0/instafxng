<?php

class careers {

    public function authenticate($username = "", $password = "") {
        global $db_handle;
        $username = $db_handle->sanitizePost($username);

        $query = "SELECT pass_salt FROM career_user_biodata WHERE email_address = '{$username}' LIMIT 1";

        $result = $db_handle->runQuery($query);

        if($db_handle->numOfRows($result) == 1) {
            $user = $db_handle->fetchAssoc($result);
            $pass_salt = $user[0]['pass_salt'];
            $hashed_password = hash("SHA512", "$pass_salt.$password");

            $query = "SELECT * FROM career_user_biodata WHERE email_address = '{$username}' AND cu_password = '{$hashed_password}' LIMIT 1";
            $result = $db_handle->runQuery($query);

            if($db_handle->numOfRows($result) == 1) {
                $found_user = $db_handle->fetchAssoc($result);
                return $found_user;
            } else {
                return false;
            }
        } else {
            return false;
        }
    }

    // Check whether applicant has an active status
    public function applicant_is_active($email) {
        global $db_handle;

        $query = "SELECT is_active FROM career_user_biodata WHERE email_address = '$email'";
        $result = $db_handle->runQuery($query);
        $fetched_data = $db_handle->fetchAssoc($result);

        if($fetched_data[0]['is_active'] == '1') {
            return true;
        } else {
            return false;
        }
    }

    public function get_open_jobs() {
        global $db_handle;

        $query = "SELECT * FROM career_jobs WHERE status = '2'";
        $result = $db_handle->runQuery($query);
        $all_jobs = $db_handle->fetchAssoc($result);

        return $all_jobs;
    }

    public function get_job_by_code($job_code) {
        global $db_handle;

        $query = "SELECT * FROM career_jobs WHERE job_code = '$job_code' AND status = '2' LIMIT 1";
        $result = $db_handle->runQuery($query);
        $all_jobs = $db_handle->fetchAssoc($result);
        $selected_job = $all_jobs[0];

        return $selected_job;

    }

    public function is_valid_job_code($job_code) {
        global $db_handle;

        $query = "SELECT * FROM career_jobs WHERE job_code = '$job_code' LIMIT 1";
        if($db_handle->numRows($query) == 0) {
            return false;
        } else {
            return true;
        }
    }

    public function set_new_career_user($first_name, $last_name, $other_names, $email_add, $phone_no, $sex,
        $marital_status, $state_of_origin, $dob, $address, $state_of_residence, $job_code) {

        global $db_handle;
        global $system_object;

        usercode:
        $client_code = rand_string(7);
        if($db_handle->numRows("SELECT cu_user_code FROM career_user_biodata WHERE cu_user_code = '$client_code'") > 0) { goto usercode; };

        $pass_salt = hash("SHA256", "$client_code");

        $client_password = strtolower(rand_string(7));
        $hashed_password = hash("SHA512", "$pass_salt.$client_password");

        $query = "INSERT INTO career_user_biodata (cu_user_code, email_address, cu_password, pass_salt, first_name, last_name,
              other_names, phone_number, sex, marital_status, state_of_origin, dob, address, state, is_active, status)
              VALUES ('$client_code', '$email_add', '$hashed_password', '$pass_salt', '$first_name', '$last_name',
              '$other_names', '$phone_no', '$sex', '$marital_status', $state_of_origin, '$dob', '$address',
              $state_of_residence, '1', '1')";

        $db_handle->runQuery($query);

        if($db_handle->affectedRows() > 0) { // Send success message and the system generated password

            // Login the postion this client is applying for
            $query = "INSERT INTO career_user_application (cu_user_code, job_code) VALUES ('$client_code', '$job_code')";
            $db_handle->runQuery($query);

            $subject = "Instaforex NG - Job Application Guide";
            $body =
<<<MAIL
<div style="background-color: #F3F1F2">
    <div style="max-width: 80%; margin: 0 auto; padding: 10px; font-size: 14px; font-family: Verdana;">
        <img src="https://instafxng.com/images/ifxlogo.png" />
        <hr />
        <div style="background-color: #FFFFFF; padding: 15px; margin: 5px 0 5px 0;">
            <p>Dear $first_name,</p>

            <p>You have successfully created a profile on our Job Application platform. This is the first
            step in the application process.</p>

            <p>Using the details below, log in to the Job Application portal to complete your application.
            Please note that you have to complete your application, all incomplete applications will not
            be considered.</p>

            <p>Login at https://instafxng.com/careers_login.php<br/>
            Username: $email_add<br/>
            Password: $client_password</p>

            <p>If you have any questions concerning your application, please call our Human Resource Personnel
            on 08028281192 between 8:30am and 5pm during week days.</p>

            <p>We wish you all the best and hope you join our amazing team, soon.</p>

            <p>Thank you.</p>

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
            $system_object->send_email($subject, $body, $email_add, $first_name);

            return true;
        } else {
            return false;
        }
    }

}

$obj_careers = new careers();