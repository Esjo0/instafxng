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
        $all_open_jobs = $db_handle->fetchAssoc($result);

        return $all_open_jobs;
    }

    public function get_all_jobs() {
        global $db_handle;

        $query = "SELECT * FROM career_jobs";
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
              other_names, phone_number, sex, marital_status, state_of_origin, dob, address, state, is_active)
              VALUES ('$client_code', '$email_add', '$hashed_password', '$pass_salt', '$first_name', '$last_name',
              '$other_names', '$phone_no', '$sex', '$marital_status', $state_of_origin, '$dob', '$address',
              $state_of_residence, '1')";

        $db_handle->runQuery($query);

        if($db_handle->affectedRows() > 0) { // Send success message and the system generated password

            // Login the postion this client is applying for
            $query = "INSERT INTO career_user_application (cu_user_code, job_code) VALUES ('$client_code', '$job_code')";
            $db_handle->runQuery($query);

            $subject = "Job Application Guide - Instant Web-Net Technologies Ltd";
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
            $system_object->send_email($subject, $body, $email_add, $first_name);

            return $client_password;
        } else {
            return false;
        }
    }

    public function get_applications_by_user_code($user_code) {
        global $db_handle;

        $query = "SELECT cua.career_user_application_id, cj.title, cua.created, cua.status FROM career_user_application AS cua
              INNER JOIN career_jobs AS cj ON cua.job_code = cj.job_code
              WHERE cua.cu_user_code = '$user_code'";
        $result = $db_handle->runQuery($query);
        $all_my_applications = $db_handle->fetchAssoc($result);
        $all_my_applications = $all_my_applications[0];

        return $all_my_applications;

    }

    public function get_applicant_biodata($user_code) {
        global $db_handle;

        $query = "SELECT cub.first_name, cub.last_name, cub.other_names, cub.phone_number, cub.sex, cub.marital_status,
              cub.state_of_origin, cub.dob, cub.address, cub.state, cub.email_address,
              CONCAT(cub.first_name, SPACE(1), cub.last_name, SPACE(1), cub.other_names) AS full_name
              FROM career_user_biodata AS cub
              WHERE cub.cu_user_code = '$user_code'";
        $result = $db_handle->runQuery($query);
        $my_biodata = $db_handle->fetchAssoc($result);
        $my_biodata = $my_biodata[0];

        return $my_biodata;

    }

    public function get_applicant_education($user_code) {
        global $db_handle;

        $query = "SELECT * FROM career_user_education WHERE cu_user_code = '$user_code'";
        $result = $db_handle->runQuery($query);
        $my_education = $db_handle->fetchAssoc($result);

        return $my_education;
    }

    public function get_applicant_work_experience($user_code) {
        global $db_handle;

        $query = "SELECT *, s.state FROM career_user_work_experience AS cuwe INNER JOIN state AS s ON cuwe.location = s.state_id
              WHERE cu_user_code = '$user_code'";
        $result = $db_handle->runQuery($query);
        $my_work_experience = $db_handle->fetchAssoc($result);

        return $my_work_experience;
    }

    public function get_applicant_skill($user_code) {
        global $db_handle;

        $query = "SELECT * FROM career_user_skill WHERE cu_user_code = '$user_code'";
        $result = $db_handle->runQuery($query);
        $my_skill = $db_handle->fetchAssoc($result);

        return $my_skill;
    }

    public function get_applicant_achievement($user_code) {
        global $db_handle;

        $query = "SELECT * FROM career_user_achievement WHERE cu_user_code = '$user_code'";
        $result = $db_handle->runQuery($query);
        $my_achievement = $db_handle->fetchAssoc($result);

        return $my_achievement;
    }

    public function final_application_submit($application_no) {
        global $db_handle;
        global $system_object;

        $query = "UPDATE career_user_application SET status = '2' WHERE career_user_application_id = $application_no
              LIMIT 1";
        $db_handle->runQuery($query);

        if($db_handle->affectedRows() > 0) {
            $query = "SELECT cub.first_name, cub.email_address, cj.title
                FROM career_user_application AS cua
                INNER JOIN career_user_biodata AS cub ON cua.cu_user_code = cub.cu_user_code
                INNER JOIN career_jobs AS cj ON cua.job_code = cj.job_code
                WHERE career_user_application_id = $application_no LIMIT 1";
            $result = $db_handle->runQuery($query);
            $applicant_detail = $db_handle->fetchAssoc($result);
            $applicant_detail = $applicant_detail[0];

            $first_name = $applicant_detail['first_name'];
            $email_add = $applicant_detail['email_address'];
            $job_title = $applicant_detail['title'];

            // send email
            $subject = "Application Acknowledgement - Instant Web-Net Technologies Ltd";
            $body = <<<MAIL
<div style="background-color: #F3F1F2">
    <div style="max-width: 80%; margin: 0 auto; padding: 10px; font-size: 14px; font-family: Verdana;">
        <img src="https://instafxng.com/images/ifxlogo.png" />
        <hr />
        <div style="background-color: #FFFFFF; padding: 15px; margin: 5px 0 5px 0;">
            <p>Dear $first_name,</p>

            <p>We recently received your correspondence indicating an interest in a position of $job_title
            at Instant Web-Net Technologies Limited. We want to thank you for taking the time to
            send us information about yourself, and we want to assure you that your application will
            be considered very carefully.</p>

            <p>If your qualifications match our needs, you will hear from us by phone or email to
            schedule an interview.</p>

            <p>Thank you again for your interest.</p>

            <br /><br />
            <p>Best Regards,</p>
            <p>HR, Instant Web-Net Technologies Ltd<br />
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
            $system_object->send_email($subject, $body, $email_add, $first_name);
            return true;

        } else {
            return false;
        }
    }

    public function update_user_biodata($first_name, $last_name, $other_names, $phone_no, $sex,
            $marital_status, $state_of_origin, $dob, $address, $state_of_residence, $client_no) {
        global $db_handle;

        $query = "UPDATE career_user_biodata SET first_name = '$first_name', last_name = '$last_name', other_names = '$other_names',
            phone_number = '$phone_no', sex = '$sex', marital_status = '$marital_status', state_of_origin = $state_of_origin, dob = '$dob',
            address = '$address', state = $state_of_residence WHERE cu_user_code = '$client_no' LIMIT 1";
        $db_handle->runQuery($query);

        if($db_handle->affectedRows() > 0) {
            return true;
        } else {
            return false;
        }
    }

    public function set_user_education($c_institute, $c_degree, $c_grade, $c_course, $start_date, $end_date, $client_no) {
        global $db_handle;

        $query = "INSERT INTO career_user_education (cu_user_code, institution, degree, grade, field_of_study, date_from, date_to)
              VALUES ('$client_no', '$c_institute', '$c_degree', '$c_grade', '$c_course', '$start_date', '$end_date')";
        $db_handle->runQuery($query);

        if($db_handle->affectedRows() > 0) {
            return true;
        } else {
            return false;
        }
    }

    public function set_user_work_experience($c_job_title, $c_company, $c_location, $start_date, $end_date, $c_description, $client_no) {
        global $db_handle;

        $query = "INSERT INTO career_user_work_experience (cu_user_code, job_title, company, location, date_from,
              date_to, description) VALUES ('$client_no', '$c_job_title', '$c_company', $c_location, '$start_date', '$end_date', '$c_description')";
        $db_handle->runQuery($query);

        if($db_handle->affectedRows() > 0) {
            return true;
        } else {
            return false;
        }
    }

    public function set_user_skill($c_skill_title, $c_competency, $c_description, $client_no) {
        global $db_handle;

        $query = "INSERT INTO career_user_skill (cu_user_code, skill_title, competency, description) VALUES
              ('$client_no', '$c_skill_title', '$c_competency', '$c_description')";
        $db_handle->runQuery($query);

        if($db_handle->affectedRows() > 0) {
            return true;
        } else {
            return false;
        }
    }

    public function set_user_achievement($c_title, $c_description, $c_category, $c_date, $client_no) {
        global $db_handle;

        $query = "INSERT INTO career_user_achievement (cu_user_code, achieve_title, description, category, achieve_date)
              VALUES ('$client_no', '$c_title', '$c_description', '$c_category', '$c_date')";
        $db_handle->runQuery($query);

        if($db_handle->affectedRows() > 0) {
            return true;
        } else {
            return false;
        }
    }

    public function get_admin_remark($application_id) {
        global $db_handle;

        $query = "SELECT CONCAT(a.last_name, SPACE(1), a.first_name) AS admin_full_name, cac.comment, cac.created
                FROM career_application_comment AS cac
                INNER JOIN admin AS a ON cac.admin_code = a.admin_code
                WHERE cac.application_id = '$application_id' ORDER BY cac.created DESC";
        $result = $db_handle->runQuery($query);
        $fetched_data = $db_handle->fetchAssoc($result);

        return $fetched_data ? $fetched_data : false;

    }

    public function application_process_comment($transaction_id, $admin_code, $comment) {
        global $db_handle;

        $query = "INSERT INTO career_application_comment (application_id, admin_code, comment) VALUES ($transaction_id, '$admin_code', '$comment')";
        $db_handle->runQuery($query);

        return true;
    }

    public function update_user_application($admin_unique_code, $application_no, $application_status, $admin_comment) {
        global $db_handle;

        $this->application_process_comment($application_no, $admin_unique_code, $admin_comment);

        $query = "UPDATE career_user_application SET status = '$application_status' WHERE career_user_application_id = $application_no LIMIT 1";
        $db_handle->runQuery($query);

        return $db_handle->affectedRows() > 0 ? true : false;
    }


}

$obj_careers = new careers();