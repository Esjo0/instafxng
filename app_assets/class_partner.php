<?php

//this class handles activities pertaining to a partner
class Partner {
    
    public function email_phone_is_duplicate($email, $phone) {
        global $db_handle;

        $query = "SELECT * FROM partner WHERE email_address = '$email' OR phone_number = '$phone' LIMIT 1";

        if($db_handle->numRows($query) > 0) {
            return true;
        } else {
            return false;
        }
    }

    public function authenticate($email = "", $password = "") {
        global $db_handle;

        $email = $db_handle->sanitizePost($email);

        $query = "SELECT * FROM partner WHERE email_address = '$email' LIMIT 1";
        $result = $db_handle->runQuery($query);

        if($db_handle->numOfRows($result) == 1) {
            $found_user = $db_handle->fetchAssoc($result);
            $found_user = $found_user[0];

            $password_hash = $found_user['password'];

            if(password_verify($password, $password_hash)) {
                return $found_user;
            } else {
                return false;
            }
        } else {
            return false;
        }
    }
    
    // Check whether partner has an active status
    public function partner_is_active($partner_code) {
        global $db_handle;
        
        $query = "SELECT status FROM partner WHERE partner_code = '$partner_code'";
        $result = $db_handle->runQuery($query);
        $fetched_data = $db_handle->fetchAssoc($result);
        
        if($fetched_data[0]['status'] == '2') {
            return true;
        } else {
            return false;
        }
    }

    // generate a unique partner code
    public function generate_partner_code() {
        global $db_handle;

        unique_partner_code:
        $partner_code = strtoupper(rand_string_caps(5));
        if($db_handle->numRows("SELECT partner_code FROM partner WHERE partner_code = '$partner_code'") > 0) { goto unique_partner_code; };

        return $partner_code;
    }
    
    //this method is to register as a partner
    public function new_partner($first_name, $last_name, $email, $phone, $address, $city, $state_id, $middle_name = '') {
        global $db_handle, $system_object;

        $partner_code = $this->generate_partner_code();
        $phone_code = generate_sms_code();

        $query  = "INSERT INTO partner (partner_code, first_name, middle_name, last_name, email_address, phone_number, full_address, city, state_id, phone_code) VALUES
                  ('$partner_code', '$first_name', '$middle_name', '$last_name', '$email', '$phone', '$address', '$city', $state_id, '$phone_code')";

        $result = $db_handle->runQuery($query);

        if($result) {
            $subject = 'Instafxng Partner Application';
            $message_final = <<<MAIL
                    <div style="background-color: #F3F1F2">
                        <div style="max-width: 80%; margin: 0 auto; padding: 10px; font-size: 14px; font-family: Verdana;">
                            <img src="https://instafxng.com/images/ifxlogo.png" />
                            <hr />
                            <div style="background-color: #FFFFFF; padding: 15px; margin: 5px 0 5px 0;">

                                <p>Dear $first_name,</p>

                                <p>You have shown interest in the Instafxng Partnership Program (IPP), an administrator is
                                moderating your application and you should get a response shortly.</p>

                                <p>The IPP is an easy way to make money from the Forex Market by referring new clients. Once
                                your referrals make deposit into their trading account and place trades, you will earn a certain
                                amount of commission.</p>

                                <p>The more active referrals you have, the more your commission continues to grow.</p>

                                <p>If your application is approved, you will get an email with your password, your partner code
                                and the link to the partner cabinet.</p>

                                <p>Thank you for your interest in working with us.</p>

                                <p>InstaFxNg Team,<br />
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
                                    <p><strong>Office Number:</strong> 08139250268, 08083956750</p>
                                    <br />
                                </div>
                            </div>
                        </div>
                    </div>
MAIL;
            $system_object->send_email($subject, $message_final, $email, $first_name);
            return true;

        } else {
            return false;
        }
    }

    public function modify_partner_application($partner_code, $partner_status) {
        global $db_handle, $system_object;

        $partner_detail = $this->get_partner_by_code($partner_code);
        $first_name = $partner_detail['first_name'];
        $email = $partner_detail['email_address'];

        if($partner_status == '2') { // Partner is approved, send password and welcome email
            // Generate password
            $new_password = random_password();
            $password_hash = password_hash($new_password, PASSWORD_DEFAULT);
            $query = "UPDATE partner SET status = '$partner_status', password = '$password_hash' WHERE partner_code = '$partner_code' LIMIT 1";

            // Send welcome email
            $subject = 'Welcome to Instafxng Partnership Program';
            $message_final = <<<MAIL
                    <div style="background-color: #F3F1F2">
                        <div style="max-width: 80%; margin: 0 auto; padding: 10px; font-size: 14px; font-family: Verdana;">
                            <img src="https://instafxng.com/images/ifxlogo.png" />
                            <hr />
                            <div style="background-color: #FFFFFF; padding: 15px; margin: 5px 0 5px 0;">

                                <p>Dear $first_name,</p>

                                <p>Your Instafxng Partnership Program Application has been approved.</p>
                                <p>You are now almost set to start making money.</p>
                                <p>Login to the Partner Cabinet at <a href="https://instafxng.com/partner">https://instafxng.com/partner</a></p>
                                <p>Username: $email <br /> Password: $new_password </p>

                                <p>After login, proceed to the settings to verify your phone number, upload your identification
                                documents, add a bank account, without that you would be unable to withdraw your earnings.</p>

                                <p>We congratulate you and welcome you to the money making team.</p>


                                <p>InstaFxNg Team,<br />
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
                                    <p><strong>Office Number:</strong> 08139250268, 08083956750</p>
                                    <br />
                                </div>
                            </div>
                        </div>
                    </div>
MAIL;
        } elseif($partner_status == '3') { // Partner is not approved, send decline email
            $query = "UPDATE partner SET status = '$partner_status' WHERE partner_code = '$partner_code' LIMIT 1";

            // Send disapproval email
            $subject = 'Instafxng Partner Application Disapproval';
            $message_final = <<<MAIL
                    <div style="background-color: #F3F1F2">
                        <div style="max-width: 80%; margin: 0 auto; padding: 10px; font-size: 14px; font-family: Verdana;">
                            <img src="https://instafxng.com/images/ifxlogo.png" />
                            <hr />
                            <div style="background-color: #FFFFFF; padding: 15px; margin: 5px 0 5px 0;">

                                <p>Dear $first_name,</p>

                                <p>We regret to inform you that your Instafxng Partnership Program Application was
                                not approved at this time. This may be because you provided fictitious information during
                                your application.</p>

                                <p>If you need to talk to us about this, please <a href="https://instafxng.com/contact_info.php">contact us here</a>.</p>

                                <p>Thank you.</p>

                                <p>InstaFxNg Team,<br />
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
                                    <p><strong>Office Number:</strong> 08139250268, 08083956750</p>
                                    <br />
                                </div>
                            </div>
                        </div>
                    </div>
MAIL;
        }

        $db_handle->runQuery($query);

        if($db_handle->affectedRows() == 1) {
            $system_object->send_email($subject, $message_final, $email, $first_name);
            return true;
        } else {
            return false;
        }
    }

    public function get_partner_by_code($partner_code) {
        global $db_handle;

        $query = "SELECT * FROM partner WHERE partner_code = '$partner_code' LIMIT 1";
        $result = $db_handle->runQuery($query);

        if($db_handle->numOfRows($result) == 1) {
            $fetched_data = $db_handle->fetchAssoc($result);
            $fetched_data = $fetched_data[0];
            return $fetched_data;
        } else {
            return false;
        }
    }

    public function count_partner_referral($partner_code) {
        global $db_handle;

        $query = "SELECT user_code FROM user WHERE partner_code = '$partner_code'";
        $total = $db_handle->numRows($query);

        return $total ? $total : false;
    }

    public function sum_partner_earnings($partner_code) {
        global $db_handle;

        $query = "SELECT SUM(amount) AS total_earnings FROM partner_commission WHERE partner_code = '$partner_code'";
        $result = $db_handle->runQuery($query);
        $fetched_data = $db_handle->fetchAssoc($result);
        $total_earnings = $fetched_data[0]['total_earnings'];

        return $total_earnings ? $total_earnings : false;
    }

    /**
     * Calculate and log partner commission
     *
     * @param $reference
     * @param $type
     * @return bool
     */
    public function set_partner_commission($reference, $type) {
        global $db_handle;

        switch ($type) {
            // Financial Commission
            case 'FC':
                $trans_type = '2';

                // select the partner_code, transaction amount
                $select_query = "SELECT ud.user_deposit_id, ud.real_dollar_equivalent, u.partner_code FROM
                    user_deposit AS ud
                    INNER JOIN user_ifxaccount AS ui ON ud.ifxaccount_id = ui.ifxaccount_id
                    INNER JOIN user AS u ON ui.user_code = u.user_code
                    WHERE ud.trans_id = '$reference' LIMIT 1";
                $select_result = $db_handle->runQuery($select_query);
                $fetched_data = $db_handle->fetchAssoc($select_result);

                $deposit_id = $fetched_data[0]['user_deposit_id'];
                $deposit_amount = $fetched_data[0]['real_dollar_equivalent'];
                $partner_code = $fetched_data[0]['partner_code'];

                $commission_amount = $deposit_amount * (PARTNER_FINANCIAL_COMMISSION / 100);

                $query = "INSERT INTO partner_commission (partner_code, amount, reference_id, trans_type, created)
                    VALUES ('$partner_code', $commission_amount, $deposit_id, '$trans_type', NOW())";
                break;

            // Trading Commission
            case 'TC':
                $trans_type = '1';

                $query = '';
                break;
        }

        $db_handle->runQuery($query);

        return true;
    }

}

$partner_object = new Partner();