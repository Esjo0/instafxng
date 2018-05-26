<?php

class clientOperation {
    private $client_data;
    
    public function __construct($ifx_account = '', $email_address = '') {
        if(isset($ifx_account) && !empty($ifx_account)) {
            $this->client_data = $this->set_client_data($ifx_account);
        }
    }
    
    public function set_client_data($ifx_account) {
        global $db_handle;
        
        $query = "SELECT ui.ifxaccount_id AS ifx_acc_id, ui.ifx_acct_no AS ifx_acc_no,
                ui.is_bonus_account AS ifx_acc_bonus_status, ui.type AS ifx_acc_type,
                ui.status AS ifx_acc_status, ui.created AS ifx_acc_created, u.user_id AS client_user_id,
                u.user_code AS client_user_code, u.email AS client_email,
                u.pass_salt AS client_pass_salt, u.password AS client_password,
                CONCAT(u.last_name, SPACE(1), u.first_name) AS client_full_name, u.first_name AS client_first_name,
                u.last_name AS client_last_name, u.phone AS client_phone_number, u.user_type AS client_user_type,
                u.referred_by_code AS client_referrer, u.status AS client_status, u.created AS client_created,
                uv.verification_id AS client_verification_id
                FROM user_ifxaccount AS ui
                INNER JOIN user AS u ON ui.user_code = u.user_code
                LEFT JOIN user_verification AS uv ON ui.user_code = uv.user_code
                WHERE ui.ifx_acct_no = '$ifx_account' LIMIT 1";
        
        $result = $db_handle->runQuery($query);
        
        if($db_handle->numOfRows($result) == 1) {
            $user = $db_handle->fetchAssoc($result);
            $user_data = $user[0];
            return $user_data;
        } else {
            return false;
        }
    }
    
    public function get_client_data() {
        return $this->client_data;
    }

    /**Get user basic details
     * Including all account numbers owned by the user
     * @param $user_code
     * @return bool
     */
    public function get_user_by_code($user_code) {
        global $db_handle;

        $query = "SELECT u.user_code AS client_user_code, u.email AS client_email, u.first_name AS client_first_name,
                u.last_name AS client_last_name, CONCAT(u.last_name, SPACE(1), u.first_name) AS client_full_name,
                u.phone AS client_phone_number, u.user_type AS client_user_type,
                u.status AS client_status, u.created AS client_created, u.academy_signup AS client_academy_first_login,
                GROUP_CONCAT(DISTINCT ui.ifx_acct_no) AS client_accounts
                FROM user AS u
                LEFT JOIN user_ifxaccount AS ui ON u.user_code = ui.user_code
                WHERE u.user_code = '$user_code' LIMIT 1";

        $result = $db_handle->runQuery($query);

        if($db_handle->numOfRows($result) > 0) {
            $user = $db_handle->fetchAssoc($result);
            $user_details = $user[0];
            return $user_details;
        } else {
            return false;
        }
    }



    public function update_user_status($user_code, $client_status) {
        global $db_handle;

        $query = "UPDATE user SET status = '$client_status' WHERE user_code = '$user_code' LIMIT 1";
        $db_handle->runQuery($query);

        return $db_handle->affectedRows() > 0 ? true : false;
    }

    // Check whether user has an active status
    public function user_is_active($email) {
        global $db_handle;

        $query = "SELECT status FROM user WHERE email = '$email'";
        $result = $db_handle->runQuery($query);
        $fetched_data = $db_handle->fetchAssoc($result);

        if($fetched_data[0]['status'] == '1') {
            return true;
        } else {
            return false;
        }
    }

    public function update_account_type($account_id, $account_type) {
        global $db_handle;

        $query = "UPDATE user_ifxaccount SET type = '$account_type' WHERE ifxaccount_id = $account_id LIMIT 1";
        $db_handle->runQuery($query);
        return $db_handle->affectedRows() > 0 ? true : false;
    }

    public function get_client_doc_details($user_code) {
        global $db_handle;

        $query = "SELECT doc_status AS client_doc_status, um.status AS client_meta_status
                FROM user_credential AS uc
                LEFT JOIN user_meta AS um ON uc.user_code = um.user_code
                WHERE uc.user_code = '$user_code' LIMIT 1";

        $result = $db_handle->runQuery($query);

        if($db_handle->numOfRows($result) > 0) {
            $user = $db_handle->fetchAssoc($result);
            $client_doc_details = $user[0];
            return $client_doc_details;
        } else {
            return false;
        }

    }

    public function get_client_meta_details($user_code) {

    }

    public function ifx_account_is_duplicate($account_no) {
        global $db_handle;
        
        // Check whether the ifx account number is existing
        $query = "SELECT ifx_acct_no FROM user_ifxaccount WHERE ifx_acct_no = '$account_no' LIMIT 1";
        $result = $db_handle->runQuery($query);

        return $db_handle->numOfRows($result) > 0 ? true : false;
    }

    public function send_welcome_email($client_name, $client_email) {
        global $db_handle;
        global $system_object;

        $sendto = $client_email;

        $query = "SELECT * FROM system_message WHERE constant = 'WELCOME_MAIL' LIMIT 1";
        $result = $db_handle->runQuery($query);
        $fetched_data = $db_handle->fetchAssoc($result);
        $selected_message = $fetched_data[0];

        $my_subject = trim($selected_message['subject']);
        $my_message = stripslashes($selected_message['value']);

        // Replace placeholders
        $my_message_new = str_replace('[FIRST_NAME]', $client_name, $my_message);
        $my_subject_new = str_replace('[FIRST_NAME]', $client_name, $my_subject);

        $my_message_new = str_replace('[FULL_NAME]', $client_name, $my_message_new);
        $my_subject_new = str_replace('[FULL_NAME]', $client_name, $my_subject_new);

        $system_object->send_email($my_subject_new, $my_message_new, $sendto, $client_name);
    }

    /**
     * @param $account_no
     * @param $full_name
     * @param $email_address
     * @param $phone_number
     * @param $type - 1 if KYC, 2 if ILPR enrolment
     * @return bool|string
     */
    public function new_user($account_no, $full_name, $email_address, $phone_number, $type, $my_refferer = "", $attendant = "", $source="") {
        global $db_handle;
        global $system_object;

        $full_name = preg_replace("/[^A-Za-z0-9 ]/", '', $full_name);
        $full_name = ucwords(strtolower(trim($full_name)));
        $full_name = explode(" ", $full_name);

        if(count($full_name) == 3) {
            $last_name = $full_name[0];
            if(strlen($full_name[2]) < 3) {
                $middle_name = $full_name[2];
                $first_name = $full_name[1];
            } else {
                $middle_name = $full_name[1];
                $first_name = $full_name[2];
            }
        } else {
            $last_name = $full_name[0];
            $middle_name = "";
            $first_name = $full_name[1];
        }
        
        // Check whether the email is existing
        $query = "SELECT user_code FROM user WHERE email = '$email_address' LIMIT 1";
        $result = $db_handle->runQuery($query);
        
        if($db_handle->numOfRows($result) > 0) {
            $fetched_data = $db_handle->fetchAssoc($result);
            $user_code = $fetched_data[0]['user_code'];

            // We want to send welcome email to someone who has a profile but no account,
            // since we are logging his first account, he should get a welcome email
            $query = "SELECT ui.ifx_acct_no FROM user AS u INNER JOIN user_ifxaccount AS ui ON u.user_code = ui.user_code WHERE u.email = '$email_address'";
            $result = $db_handle->runQuery($query);

            //TODO: Come back later to understand this....
            if($db_handle->numOfRows($result) == 0)
            {
                if(empty($source) || $source != "lp")
                {
                    $this->send_welcome_email($last_name, $email_address);
                }
                else
                {
                    $subject = $last_name.', Your Welcome Bonus Is Here!';
                    $message_final = <<<MAIL
                    <div style="background-color: #F3F1F2">
                        <div style="max-width: 80%; margin: 0 auto; padding: 10px; font-size: 14px; font-family: Verdana;">
                            <img src="https://instafxng.com/images/ifxlogo.png" />
                            <hr />
                            <div style="background-color: #FFFFFF; padding: 15px; margin: 5px 0 5px 0;">
                                <p>It's official! Welcome to the money making gang! lol</p>
                                <p>You’ve just joined InstaForex Nigeria and said YES to making consistent income from Forex trading.</p>
                                <p>You have made a fantastic decision $last_name!</p>
                                <p>You stand to enjoy a whole lot of mind-blowing offers, promos and bonuses and other information that will help you on your journey to financial freedom trading Forex.</p>
                                <p>This is what’s happening right now…</p>                                
                                <p>Your Welcome bonus is here!</p>
                                <p>Yes $last_name, for opening an account with InstaForex Nigeria, you get a 130% bonus on your first deposit of either $50, $100 and $150</p>
                                <p>This one-time bonus is specially designed for you and you can get the 130% bonus within the next 7 days, so you need to act immediately.</p>
                                <p>Let me quickly explain how to get the bonus...</p>
                                <p>The 130% bonus allows you to get a double of your deposit so you can have more money to trade, make more profit from your trades, earn loyalty points and get the monthly and annual rewards.</p>
                                <p>Isn’t this amazing? You bet!</p>
                                <p>Within the next 7 days, this means that you will get double of your deposit if you fund your account with $50, $100 or $150.</p>
                                <p><a href="mailto:support@instafxng.com?subject=130%20Percent%20Bonus%20&body=Hello%20Mercy,I%20am%20interested%20in%20getting%20the%20130%20percent%20bonus.Thanks!">Click the here to claim your bonus now.</a></p>
                                <p>Yesterday, 20 people who joined InstaForex newly, funded their accounts and got 130% bonus on their deposit.</p>
                                <p>How amazing will it be for you to get a double of your deposit so you can have more money to trade and more profit to make?</p>
                                <p>Super amazing, right? </p>
                                <p><a href="mailto:support@instafxng.com?subject=130%20Percent%20Bonus%20&body=Hello%20Mercy,I%20am%20interested%20in%20getting%20the%20130%20percent%20bonus.Thanks!">Click the button here to claim your bonus now.</a></p>
                                <br /><br />
                                <p>Best Regards,</p>
                                <p>Mercy,</p>
                                <p>Client Relationship Manager</p>
                                <p>InstaFxNg Team,<br />
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
                    $system_object->send_email($subject, $message_final, $email_address, $last_name);
                }
            }
            
            if($this->ifx_account_is_duplicate($account_no)) {
                return false; //TODO: Send a proper message stating that this ifxaccount already exist in the database
            } else {
                if(isset($my_refferer) && !empty($my_refferer)) {
                    $query = "INSERT INTO user_ifxaccount (user_code, ifx_acct_no, partner_code) VALUES ('$user_code', '$account_no', '$my_refferer')";
                } else {
                    $query = "INSERT INTO user_ifxaccount (user_code, ifx_acct_no) VALUES ('$user_code', '$account_no')";
                }

                $db_handle->runQuery($query);

                // get returned ifxaccount_id generated
                $ifxaccount_id = $db_handle->insertedId();
            }
        } else {
            usercode:
            $user_code = rand_string(11);
            if($db_handle->numRows("SELECT user_code FROM user WHERE user_code = '$user_code'") > 0) { goto usercode; };
            
            $pass_salt = hash("SHA256", "$user_code");

            if(empty($attendant)) {
                $attendant = $system_object->next_account_officer();
            }
            
            if(empty($middle_name)) {
                $query = "INSERT INTO user (user_code, attendant, email, pass_salt, first_name, last_name, phone) VALUES ('$user_code', $attendant, '$email_address', '$pass_salt', '$first_name', '$last_name', '$phone_number')";
                $db_handle->runQuery($query);
                if(empty($source) || $source != "lp")
                {
                    $this->send_welcome_email($last_name, $email_address);
                }
                else
                {
                    $subject = $last_name.', Your Welcome Bonus Is Here!';
                    $message_final = <<<MAIL
                    <div style="background-color: #F3F1F2">
                        <div style="max-width: 80%; margin: 0 auto; padding: 10px; font-size: 14px; font-family: Verdana;">
                            <img src="https://instafxng.com/images/ifxlogo.png" />
                            <hr />
                            <div style="background-color: #FFFFFF; padding: 15px; margin: 5px 0 5px 0;">
                                <p>It's official! Welcome to the money making gang! lol</p>
                                <p>You’ve just joined InstaForex Nigeria and said YES to making consistent income from Forex trading.</p>
                                <p>You have made a fantastic decision $last_name!</p>
                                <p>You stand to enjoy a whole lot of mind-blowing offers, promos and bonuses and other information that will help you on your journey to financial freedom trading Forex.</p>
                                <p>This is what’s happening right now…</p>                                
                                <p>Your Welcome bonus is here!</p>
                                <p>Yes $last_name, for opening an account with InstaForex Nigeria, you get a 130% bonus on your first deposit of either $50, $100 and $150</p>
                                <p>This one-time bonus is specially designed for you and you can get the 130% bonus within the next 7 days, so you need to act immediately.</p>
                                <p>Let me quickly explain how to get the bonus...</p>
                                <p>The 130% bonus allows you to get a double of your deposit so you can have more money to trade, make more profit from your trades, earn loyalty points and get the monthly and annual rewards.</p>
                                <p>Isn’t this amazing? You bet!</p>
                                <p>Within the next 7 days, this means that you will get double of your deposit if you fund your account with $50, $100 or $150.</p>
                                <p><a href="mailto:support@instafxng.com?subject=130%20Percent%20Bonus%20&body=Hello%20Mercy,I%20am%20interested%20in%20getting%20the%20130%20percent%20bonus.Thanks!">Click the here to claim your bonus now.</a></p>
                                <p>Yesterday, 20 people who joined InstaForex newly, funded their accounts and got 130% bonus on their deposit.</p>
                                <p>How amazing will it be for you to get a double of your deposit so you can have more money to trade and more profit to make?</p>
                                <p>Super amazing, right? </p>
                                <p><a href="mailto:support@instafxng.com?subject=130%20Percent%20Bonus%20&body=Hello%20Mercy,I%20am%20interested%20in%20getting%20the%20130%20percent%20bonus.Thanks!">Click the button here to claim your bonus now.</a></p>
                                <br /><br />
                                <p>Best Regards,</p>
                                <p>Mercy,</p>
                                <p>Client Relationship Manager</p>
                                <p>InstaFxNg Team,<br />
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
                    $system_object->send_email($subject, $message_final, $email_address, $last_name);
                }

            } else {
                $query = "INSERT INTO user (user_code, attendant, email, pass_salt, first_name, middle_name, last_name, phone) VALUES ('$user_code', $attendant, '$email_address', '$pass_salt', '$first_name', '$middle_name', '$last_name', '$phone_number')";
                $db_handle->runQuery($query);
                if(empty($source) || $source != "lp"){
                    //This client came in through the landing page
                    $this->send_welcome_email($last_name, $email_address);
                }
                else
                {
                    $subject = $last_name.', Your Welcome Bonus Is Here!';
                    $message_final = <<<MAIL
                    <div style="background-color: #F3F1F2">
                        <div style="max-width: 80%; margin: 0 auto; padding: 10px; font-size: 14px; font-family: Verdana;">
                            <img src="https://instafxng.com/images/ifxlogo.png" />
                            <hr />
                            <div style="background-color: #FFFFFF; padding: 15px; margin: 5px 0 5px 0;">
                                <p>It's official! Welcome to the money making gang! lol</p>
                                <p>You’ve just joined InstaForex Nigeria and said YES to making consistent income from Forex trading.</p>
                                <p>You have made a fantastic decision $last_name!</p>
                                <p>You stand to enjoy a whole lot of mind-blowing offers, promos and bonuses and other information that will help you on your journey to financial freedom trading Forex.</p>
                                <p>This is what’s happening right now…</p>                                
                                <p>Your Welcome bonus is here!</p>
                                <p>Yes $last_name, for opening an account with InstaForex Nigeria, you get a 130% bonus on your first deposit of either $50, $100 and $150</p>
                                <p>This one-time bonus is specially designed for you and you can get the 130% bonus within the next 7 days, so you need to act immediately.</p>
                                <p>Let me quickly explain how to get the bonus...</p>
                                <p>The 130% bonus allows you to get a double of your deposit so you can have more money to trade, make more profit from your trades, earn loyalty points and get the monthly and annual rewards.</p>
                                <p>Isn’t this amazing? You bet!</p>
                                <p>Within the next 7 days, this means that you will get double of your deposit if you fund your account with $50, $100 or $150.</p>
                                <p><a href="mailto:support@instafxng.com?subject=130%20Percent%20Bonus%20&body=Hello%20Mercy,I%20am%20interested%20in%20getting%20the%20130%20percent%20bonus.Thanks!">Click the here to claim your bonus now.</a></p>
                                <p>Yesterday, 20 people who joined InstaForex newly, funded their accounts and got 130% bonus on their deposit.</p>
                                <p>How amazing will it be for you to get a double of your deposit so you can have more money to trade and more profit to make?</p>
                                <p>Super amazing, right? </p>
                                <p><a href="mailto:support@instafxng.com?subject=130%20Percent%20Bonus%20&body=Hello%20Mercy,I%20am%20interested%20in%20getting%20the%20130%20percent%20bonus.Thanks!">Click the button here to claim your bonus now.</a></p>
                                <br /><br />
                                <p>Best Regards,</p>
                                <p>Mercy,</p>
                                <p>Client Relationship Manager</p>
                                <p>InstaFxNg Team,<br />
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
                    $system_object->send_email($subject, $message_final, $email_address, $last_name);
                }
            }

            if(isset($my_refferer) && !empty($my_refferer)) {
                $query = "INSERT INTO user_ifxaccount (user_code, ifx_acct_no, partner_code) VALUES ('$user_code', '$account_no', '$my_refferer')";
            } else {
                $query = "INSERT INTO user_ifxaccount (user_code, ifx_acct_no) VALUES ('$user_code', '$account_no')";
            }
            $db_handle->runQuery($query);

            // get returned ifxaccount_id generated
            $ifxaccount_id = $db_handle->insertedId();

            // Create a record for the user on the loyalty log table
            $this->user_loyalty_log_record($user_code);
        }

        // log ilpr application
        if($type == 2) {
            $query = "INSERT INTO user_ilpr_enrolment (ifxaccount_id) VALUES ($ifxaccount_id)";
            $db_handle->runQuery($query);
        }

        return $user_code ? $user_code : false;
    }

    /**
     * Create a user profile without an associated ifx account number, this works for the
     * fxacademy https://instafxng.com/fxacademy/
     * @param $full_name
     * @param $email_address
     * @param $phone_number
     * @return mixed
     */
    public function new_user_ordinary($full_name, $email_address, $phone_number, $attendant = 1) {
        global $db_handle;

        // Check whether the email is existing
        $query = "SELECT user_code, email FROM user WHERE email = '$email_address' LIMIT 1";
        $result = $db_handle->runQuery($query);

        if($db_handle->numOfRows($result) > 0) {
            $fetched_data = $db_handle->fetchAssoc($result);
            $user_email = $fetched_data[0]['email'];
        } else {
            usercode:
            $user_code = rand_string(11);
            if($db_handle->numRows("SELECT user_code FROM user WHERE user_code = '$user_code'") > 0) { goto usercode; };

            $pass_salt = hash("SHA256", "$user_code");

            $full_name = preg_replace("/[^A-Za-z0-9 ]/", '', $full_name);
            $full_name = ucwords(strtolower(trim($full_name)));
            $full_name = explode(" ", $full_name);

            if(count($full_name) == 3) {
                $last_name = $full_name[0];
                if(strlen($full_name[2]) < 3) {
                    $middle_name = $full_name[2];
                    $first_name = $full_name[1];
                } else {
                    $middle_name = $full_name[1];
                    $first_name = $full_name[2];
                }
            } else {
                $last_name = $full_name[0];
                $middle_name = "";
                $first_name = $full_name[1];
            }

            if(empty($middle_name)) {
                $query = "INSERT INTO user (user_code, attendant, email, pass_salt, first_name, last_name, phone) VALUES ('$user_code', $attendant, '$email_address', '$pass_salt', '$first_name', '$last_name', '$phone_number')";
                $db_handle->runQuery($query);

            } else {
                $query = "INSERT INTO user (user_code, attendant, email, pass_salt, first_name, middle_name, last_name, phone) VALUES ('$user_code', $attendant, '$email_address', '$pass_salt', '$first_name', '$middle_name', '$last_name', '$phone_number')";
                $db_handle->runQuery($query);

            }

            // Create a record for the user on the loyalty log table
            $this->user_loyalty_log_record($user_code);

        }

        return $user_email ? $user_email : $email_address;
    }

    public function user_loyalty_log_record($user_code) {
        global $db_handle;

        $query = "INSERT INTO user_loyalty_log (user_code) VALUES ('$user_code')";
        $db_handle->runQuery($query);

        return true;
    }
    
    // Confirm that the client has uploaded approved ID, Signature, Passport Photography
    public function confirm_credential($user_code) {
        global $db_handle;
        
        $query = "SELECT doc_status FROM user_credential WHERE user_code = '$user_code' LIMIT 1";
        $result = $db_handle->runQuery($query);
        $fetched_data = $db_handle->fetchAssoc($result);
        $doc_status = $fetched_data[0]['doc_status'];

        return $doc_status == '111' ? true : false;
    }

    // Confirm that the client has saved a valid bank account
    public function confirm_bank_account($user_code) {
        global $db_handle;

        $query = "SELECT status FROM user_bank WHERE is_active = 1 AND user_code = '$user_code' LIMIT 1";
        $result = $db_handle->runQuery($query);
        $fetched_data = $db_handle->fetchAssoc($result);
        $bank_status = $fetched_data[0]['status'];

        return $bank_status == '2' ? true : false;
    }

    public function confirm_client_address($user_code) {
        global $db_handle;

        $query = "SELECT status FROM user_meta WHERE user_code = '$user_code' LIMIT 1";
        $result = $db_handle->runQuery($query);
        $fetched_data = $db_handle->fetchAssoc($result);
        $address_status = $fetched_data[0]['status'];

        return $address_status == '2' ? true : false;
    }

    // set a new bank account
    public function set_bank_account($user_code, $bank_acct_name, $bank_acct_number, $bank_id) {
        global $db_handle;

        $query = "INSERT INTO user_bank (user_code, bank_acct_name, bank_acct_no, bank_id) VALUES
            ('$user_code', '$bank_acct_name', '$bank_acct_number', $bank_id)";
        $db_handle->runQuery($query);

        return $db_handle->affectedRows() > 0 ? true : false;
    }



    // Get the bank details of a particular user
    public function get_user_bank_account($user_code) {
        global $db_handle;

        $query = "SELECT bank_acct_name AS client_acct_name, bank_acct_no AS client_acct_no,
                b.bank_name AS client_bank_name FROM user_bank AS ub
                INNER JOIN bank AS b ON ub.bank_id = b.bank_id
                WHERE ub.user_code = '$user_code' AND ub.status = '2' AND is_active = '1' LIMIT 1";
        $result = $db_handle->runQuery($query);
        $fetched_data = $db_handle->fetchAssoc($result);

        $bank_details = $fetched_data[0];
        return $bank_details ? $bank_details : false;
    }

    public function get_user_bank_by_id($user_bank_id) {
        global $db_handle;

        $query = "SELECT ub.bank_acct_name, ub.bank_acct_no, ub.is_active, ub.status,
                ub.created, b.bank_name, u.phone, GROUP_CONCAT(DISTINCT ui.ifx_acct_no) AS ifx_accounts,
                CONCAT(u.last_name, SPACE(1), u.first_name) AS full_name, u.user_code
                FROM user_bank AS ub
                INNER JOIN bank AS b ON ub.bank_id = b.bank_id
                INNER JOIN user AS u ON ub.user_code = u.user_code
                INNER JOIN user_ifxaccount AS ui ON u.user_code = ui.user_code
                WHERE ub.user_bank_id = '$user_bank_id' LIMIT 1";
        $result = $db_handle->runQuery($query);
        $fetched_data = $db_handle->fetchAssoc($result);

        $bank_details = $fetched_data[0];
        return $bank_details ? $bank_details : false;
    }

    public function update_bank_account_status($user_bank_id, $bank_account_status) {
        global $db_handle;

        if($bank_account_status == '3') {
            $query = "UPDATE user_bank SET status = '$bank_account_status', is_active = '2' WHERE user_bank_id = '$user_bank_id' LIMIT 1";
        } else {
            $query = "UPDATE user_bank SET status = '$bank_account_status' WHERE user_bank_id = '$user_bank_id' LIMIT 1";
        }
        $db_handle->runQuery($query);

        return $db_handle->affectedRows() > 0 ? true : false;
    }

    public function get_client_verification_status($user_code) {
        global $db_handle;

        $query = "SELECT * FROM user_verification WHERE phone_status = '2' AND user_code = '$user_code' LIMIT 1";
        if($db_handle->numRows($query) > 0) { $level_one = true; }

        $query = "SELECT * FROM user_credential AS uc
            LEFT JOIN user_meta AS um ON uc.user_code = um.user_code
            WHERE uc.doc_status = '111' AND um.status = '2' AND uc.user_code = '$user_code' LIMIT 1";
        if($db_handle->numRows($query) > 0) { $level_two = true; }

        $query = "SELECT * FROM user_bank WHERE is_active = '1' AND status = '2' AND user_code = '$user_code' LIMIT 1";
        if($db_handle->numRows($query) > 0) { $level_three = true; }

        if($level_three) {
            return '3';
        } elseif($level_two) {
            return '2';
        } elseif($level_one) {
            return '1';
        } else {
            return '0';
        }
    }

    public function get_user_verification_docs($user_credential_id) {
        global $db_handle;

        $query = "SELECT uc.user_credential_id, uc.idcard, uc.passport, uc.signature, uc.doc_status,
                CONCAT(um.address, SPACE(1), um.city, SPACE(1), s.state) AS full_address, um.user_meta_id,
                GROUP_CONCAT(DISTINCT ui.ifx_acct_no) AS ifx_accounts,
                u.phone, CONCAT(u.last_name, SPACE(1), u.first_name) AS full_name, u.user_code
                FROM user_credential AS uc
                LEFT JOIN user_meta AS um ON uc.user_code = um.user_code
                INNER JOIN state AS s ON um.state_id = s.state_id
                INNER JOIN user AS u ON um.user_code = u.user_code
                INNER JOIN user_ifxaccount AS ui ON u.user_code = ui.user_code
                WHERE uc.user_credential_id = $user_credential_id LIMIT 1";
        $result = $db_handle->runQuery($query);
        $fetched_data = $db_handle->fetchAssoc($result);

        $verification_docs = $fetched_data[0];
        return $verification_docs ? $verification_docs : false;
    }

    public function get_user_docs_by_code($user_code) {
        global $db_handle;

        $query = "SELECT idcard, passport, signature FROM user_credential WHERE user_code = '$user_code' LIMIT 1";
        $result = $db_handle->runQuery($query);
        $fetched_data = $db_handle->fetchAssoc($result);
        return $db_handle->numOfRows($result) > 0 ? $fetched_data[0] : false;
    }

    public function update_document_verification_status($credential_id, $meta_id, $doc_status, $address_status, $remarks) {
        global $db_handle;
        global $system_object;

        $query = "SELECT u.email, CONCAT(u.last_name, SPACE(1), u.first_name) AS full_name
                FROM user_credential AS uc
                INNER JOIN user AS u ON uc.user_code = u.user_code
                WHERE uc.user_credential_id = $credential_id LIMIT 1";
        $result = $db_handle->runQuery($query);
        $fetched_data = $db_handle->fetchAssoc($result);
        extract($fetched_data[0]);

        $query = "UPDATE user_credential SET status = '2', doc_status = '$doc_status', remark = '$remarks' WHERE user_credential_id = $credential_id LIMIT 1";
        $db_handle->runQuery($query);

        $query = "UPDATE user_meta SET status = '$address_status' WHERE user_meta_id = $meta_id LIMIT 1";
        $db_handle->runQuery($query);

        switch ($doc_status) {
            case '000': $message = "None of the documents was Approved"; break;
            case '001': $message = "ID Card and Passport Not Approved"; break;
            case '010': $message = "ID Card and Signature Not Approved"; break;
            case '011': $message = "ID Card Not Approved"; break;
            case '100': $message = "Passport and Signature Not Approved"; break;
            case '101': $message = "Passport Not Approved"; break;
            case '110': $message = "Signature Not Approved"; break;
            case '111': $message = "All Approved"; break;
        }


        if($doc_status <> '111' || $address_status <> '2') { // Send fail message
            if($address_status = '3') { $message2 = 'Address does not look genuine.'; }
            $subject = "Instafxng Verification Status";
            $body =
<<<MAIL
<div style="background-color: #F3F1F2">
    <div style="max-width: 80%; margin: 0 auto; padding: 10px; font-size: 14px; font-family: Verdana;">
        <img src="https://instafxng.com/images/ifxlogo.png" />
        <hr />
        <div style="background-color: #FFFFFF; padding: 15px; margin: 5px 0 5px 0;">
            <p>Dear $full_name,</p>

            <p>Your documents was not fully approved, see reason and remark below.
            Please <a href="https://instafxng.com/verify_account.php">click here</a> to update
            and submit again.</p>

            <p>Reason: $message <br /></p>

            <p>Remark: $remarks</p>

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
            $system_object->send_email($subject, $body, $email, $full_name);

        } else { // Send success message
            $subject = "Instafxng Verification Status";
            $body =
<<<MAIL
<div style="background-color: #F3F1F2">
    <div style="max-width: 80%; margin: 0 auto; padding: 10px; font-size: 14px; font-family: Verdana;">
        <img src="https://instafxng.com/images/ifxlogo.png" />
        <hr />
        <div style="background-color: #FFFFFF; padding: 15px; margin: 5px 0 5px 0;">
            <p>Dear $full_name,</p>

            <p>Your documents have been checked and all approved, you can now use all
            our services without any limitation.</p>

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
            $system_object->send_email($subject, $body, $email, $full_name);

        }

        return true;

    }

    public function get_deposit_transaction($trans_id) {
        global $db_handle;

        $query = "SELECT ud.dollar_ordered, ud.naira_total_payable, ud.status, ud.created, ud.client_pay_method,
                ui.user_code, ui.ifx_acct_no, u.first_name, u.last_name, CONCAT(u.last_name, SPACE(1), u.first_name) AS full_name,
                u.phone, u.email
                FROM user_deposit AS ud
                INNER JOIN user_ifxaccount AS ui ON ud.ifxaccount_id = ui.ifxaccount_id
                INNER JOIN user AS u ON ui.user_code = u.user_code
                WHERE ud.trans_id = '$trans_id' LIMIT 1";

        $result = $db_handle->runQuery($query);
        $fetched_data = $db_handle->fetchAssoc($result);
        $deposit_details = $fetched_data[0];

        return $deposit_details ? $deposit_details : false;
    }

    public function user_payment_notification($trans_id, $pay_method, $pay_date, $teller_no, $naira_amount, $comment = "") {
        global $db_handle;

        $transaction_detail = $this->get_deposit_transaction($trans_id);

        if($transaction_detail['status'] == 1) {
            $query = "UPDATE user_deposit SET status = '2', client_pay_date = '$pay_date', client_pay_method = '$pay_method',
            client_reference = '$teller_no', client_naira_notified = '$naira_amount', client_comment = '$comment',
            client_notified_date = NOW() WHERE trans_id = '$trans_id' LIMIT 1";

            $db_handle->runQuery($query);
            return $db_handle->affectedRows() > 0 ? true : false;
        } else {
            return false;
        }

    }

    public function is_deposit_order_expired($created) {
        $current_time = time();
        $allowed_time = DEPOSIT_EXPIRE_HOUR * 60 * 60; // convert hour to seconds.

        // $created is in datetime format, convert it to timestamp
        $created = strtotime($created);

        $time_difference = $current_time - $created;
        return $time_difference > $allowed_time ? true : false;
    }

    public function set_user_meta_data($client_user_code, $address, $city, $state) {
        global $db_handle;

        if($db_handle->numRows("SELECT user_code FROM user_meta WHERE user_code = '$client_user_code' LIMIT 1") > 0) {
            $query = "UPDATE user_meta SET address = '$address', city = '$city', state_id = $state, status = '1' WHERE user_code = '$client_user_code' LIMIT 1";
            $db_handle->runQuery($query);
        } else {
            $query = "INSERT INTO user_meta (user_code, address, city, state_id) VALUES ('$client_user_code', '$address', '$city', '$state')";
            $db_handle->runQuery($query);
        }

        return $db_handle->affectedRows() > 0 ? true : false;
    }

    public function set_user_credential($client_user_code, $idcard='', $passport='', $signature='') {
        global $db_handle;

        if($db_handle->numRows("SELECT user_code FROM user_credential WHERE user_code = '$client_user_code' LIMIT 1") > 0) {
            $query = "UPDATE user_credential SET status = '1'";
            if(!empty($idcard)) { $query .= ", idcard = '$idcard'"; }
            if(!empty($passport)) { $query .= ", passport = '$passport'"; }
            if(!empty($signature)) { $query .= ", signature = '$signature'"; }

            $query .= " WHERE user_code = '$client_user_code' LIMIT 1";
            $db_handle->runQuery($query);

            return $db_handle->affectedRows() > 0 ? true : false;
        } else {
            $query = "INSERT INTO user_credential (user_code, idcard, passport, signature) VALUES
                ('$client_user_code', '$idcard', '$passport', '$signature')";
            $db_handle->runQuery($query);

            return $db_handle->affectedRows() > 0 ? true : false;
        }


    }

    public function get_user_credential($user_code) {
        global $db_handle;

        $query = "SELECT idcard, passport, signature, doc_status, updated FROM user_credential WHERE user_code = '$user_code' LIMIT 1";
        $result = $db_handle->runQuery($query);
        $fetched_data = $db_handle->fetchAssoc($result);

        $credentials = $fetched_data[0];
        return $credentials ? $credentials : false;

    }

    public function credential_uploaded($client_user_code) {
        global $db_handle;
        $query = "SELECT status FROM user_credential WHERE user_code = '$client_user_code' LIMIT 1";
        $result = $db_handle->runQuery($query);
        $fetched_data = $db_handle->fetchAssoc($result);

        $status = $fetched_data[0]['status'];

        return $status == '1' ? true : false;
    }
    
    // Check whether an unverified user has not passed the allowed daily limit of 1
    public function deposit_limit_exceeded($client_user_code, $ifx_dollar_amount) {
        global $db_handle;
        
        $date_today = date('Y-m-d', time());
        
        $query = "SELECT SUM(ud.dollar_ordered) AS total_deposit_ordered
                FROM user_ifxaccount AS ui
                LEFT JOIN user_deposit AS ud ON ui.ifxaccount_id = ud.ifxaccount_id
                WHERE ui.user_code = '$client_user_code'
                AND STR_TO_DATE(ud.created, '%Y-%m-%d') = '$date_today'";
        $result = $db_handle->runQuery($query);
        $fetched_data = $db_handle->fetchAssoc($result);

        $total_deposit_ordered = $fetched_data[0]['total_deposit_ordered'];
        return $total_deposit_ordered + $ifx_dollar_amount > LEVEL_ONE_MAX_PER_DEPOSIT ? true : false;
    }
    
    public function get_deposit_by_id_mini($trans_id) {
        global $db_handle;
        
        $query = "SELECT u.first_name AS client_first_name, u.last_name AS client_last_name, u.email AS client_email,
                ud.trans_id AS client_trans_id, ud.dollar_ordered AS client_dollar,
                ud.naira_total_payable AS client_naira_total, ui.ifx_acct_no AS client_account
                FROM user_deposit AS ud
                INNER JOIN user_ifxaccount AS ui ON ud.ifxaccount_id = ui.ifxaccount_id
                INNER JOIN user AS u ON ui.user_code = u.user_code
                WHERE ud.trans_id = '$trans_id' AND ud.status = '1' LIMIT 1";
        
        $result = $db_handle->runQuery($query);
        
        if($db_handle->numOfRows($result) == 1) {
            $transaction = $db_handle->fetchAssoc($result);
            $transaction_detail = $transaction[0];
            return $transaction_detail;
        } else {
            return false;
        }
    }
    
    public function send_order_invoice($client_full_name, $client_email, $client_trans_id, $client_account, $client_dollar, $client_naira_total, $pay_type) {
        global $system_object;

        switch($pay_type) {
            case '2':
                $mail_body = <<<INVOICE
<p>To complete your order, please make your payment as follows:</p>

<p>Log into your bank internet banking platform</p>
<p>When making your payment on your internet banking platform, fill in your transaction ID, account number <br />
($client_trans_id - $client_account) in the REMARK column.<br />
If you don't fill it as stated, your order will be delayed, becuase it will be difficult
to track your payment.</p>

<p>Pay =N= $client_naira_total into our account listed below.</p>

<p style="color: red">NOTE: Kindly make sure you pay into the account stated below.</p>

<ol>
    <li>Any Branch of <span style="color: blue; font-weight: bolder">Guaranty Trust Bank</span><br />
    Account Name:   <span style="color: blue; font-weight: bolder">Instant Web-Net Technologies Ltd</span><br />
    Account Number: <span style="color: blue; font-weight: bolder">0174516696</span></li>
    <li>After making the payment, visit https://instafxng.com/ and click on PAYMENT NOTIFICATION</li>
    <li>Your funding can be delayed for 6 months to 1 year if you fail to pay into the account on
    the invoice.</li>
</ol>

<p>Funding will be completed within 5 minutes to 2 hours on work days. Funding is completed
normally within the same day. If there is any delay we will inform you.</p>

<p>NOTE:</p>
<ul>
    <li>Third party payments are not allowed.</li>
    <li>REMARK section must be written as<br />
    ($client_trans_id - $client_account)</li>
    <li>Your account can only be funded after you have completed payment notification
    as advised in (2) above.</li>
</ul>

<p>If you have any questions, please contact our <a href="https://instafxng.com/contact_info.php">support desk</a>
And please mention your transaction ID $client_trans_id when you call.</p>

<p>Thank you for using our services.</p>
INVOICE;
                break;
            case '3':
                $mail_body = <<<INVOICE
<p>To complete your order, please make your payment as follows:</p>

<p>Visit any bank ATM.</p>

<p>Transfer =N= $client_naira_total into our account listed below.</p>

<p style="color: red">NOTE: Kindly make sure you pay into the account stated below.</p>

<ol>
    <li>Any Branch of <span style="color: blue; font-weight: bolder">Guaranty Trust Bank</span><br />
    Account Name:   <span style="color: blue; font-weight: bolder">Instant Web-Net Technologies Ltd</span><br />
    Account Number: <span style="color: blue; font-weight: bolder">0174516696</span></li>
    <li>After making the payment, visit https://instafxng.com/ and click on PAYMENT NOTIFICATION,
    Type in the location where you made the payment in the comment box, <strong>e.g. Apapa Lagos</strong>.
    If you do not fill it as stated, your order will be delayed, because it will be difficult to track
    your payment.</li>
    <li>Your funding can be delayed for 6 months to 1 year if you fail to pay into the account on
    the invoice.</li>
</ol>

<p>Funding will be completed within 5 minutes to 2 hours on work days. Funding is completed
normally within the same day. If there is any delay we will inform you.</p>

<p>NOTE:</p>
<ul>
    <li>Third party payments are not allowed.</li>
    <li>Your account can only be funded after you have completed payment notification
    as advised in (2) above.</li>
</ul>

<p>If you have any questions, please contact our <a href="https://instafxng.com/contact_info.php">support desk</a>
And please mention your transaction ID $client_trans_id when you call.</p>

<p>Thank you for using our services.</p>
INVOICE;
                break;
            case '4':
                $mail_body = <<<INVOICE
<p>To complete your order, please make your payment as follows:</p>

<p>When making your payment through the banking application, fill in the transfer memo/ description/ narration as <br />
($client_trans_id - $client_full_name - $client_account) <br />
If you don't fill it as stated, your order will be unnecessarily delayed.</p>

<p>Pay =N= $client_naira_total into our account listed below.</p>

<p style="color: red">NOTE: Kindly make sure you pay into the account stated below.</p>

<ol>
    <li>Any Branch of <span style="color: blue; font-weight: bolder">Guaranty Trust Bank</span><br />
    Account Name:   <span style="color: blue; font-weight: bolder">Instant Web-Net Technologies Ltd</span><br />
    Account Number: <span style="color: blue; font-weight: bolder">0174516696</span></li>
    <li>After making the payment, visit https://instafxng.com/ and click on PAYMENT NOTIFICATION</li>
    <li>Your funding can be delayed for 6 months to 1 year if you fail to pay into the account on
    the invoice.</li>
</ol>

<p>Funding will be completed within 5 minutes to 2 hours on work days. Funding is completed
normally within the same day. If there is any delay we will inform you.</p>

<p>NOTE:</p>
<ul>
    <li>Third party payments are not allowed.</li>
    <li>Depositor Name / Transfer Memo / Description / Narration must be written as<br />
    ($client_trans_id - $client_full_name - $client_account)</li>
    <li>Your account can only be funded after you have completed payment notification
    as advised in (2) above.</li>
</ul>

<p>If you have any questions, please contact our <a href="https://instafxng.com/contact_info.php">support desk</a>
And please mention your transaction ID $client_trans_id when you call.</p>

<p>Thank you for using our services.</p>
INVOICE;
                break;
            case '9':
                $mail_body = <<<INVOICE
<p>To complete your order, please make your payment as follows:</p>

<p>Pay =N= $client_naira_total into our account listed below using the USSD transfer feature
for your bank, find your bank USSD below.</p>

<p style="color: red">NOTE: Kindly make sure you pay into the account stated below.</p>

<ol>
    <li>Any Branch of <span style="color: blue; font-weight: bolder">Guaranty Trust Bank</span><br />
    Account Name:   <span style="color: blue; font-weight: bolder">Instant Web-Net Technologies Ltd</span><br />
    Account Number: <span style="color: blue; font-weight: bolder">0174516696</span></li>
    <li>After making the payment, visit https://instafxng.com/ and click on PAYMENT NOTIFICATION</li>
    <li>Your funding can be delayed for 6 months to 1 year if you fail to pay into the account on
    the invoice.</li>
</ol>

<p>Funding will be completed within 5 minutes to 2 hours on work days. Funding is completed
normally within the same day. If there is any delay we will inform you.</p>

<p>NOTE:</p>
<ul>
    <li>Third party payments are not allowed.</li>
    <li>Your account can only be funded after you have completed payment notification
    as advised in (2) above.</li>
</ul>

<p>BANK USSD CODES - This works with phone numbers registered with your account</p>
<ul>
    <li>Guaranty Trust Bank (GTB): *737# </li>
    <li>Fidelity Bank: *770#</li>
    <li>First Bank: *894#</li>
    <li>Sterling Bank: *822#</li>
    <li>Skye Bank: *833#</li>
    <li>United Bank for Africa (UBA): *919#</li>
    <li>EcoBank: *326#</li>
    <li>Zenith Bank: *966#</li>
    <li>Stanbic Bank: *909#</li>
    <li>Access Bank Bank: *901#</li>
    <li>Wema Bank: *945#</li>
    <li>Diamond Bank: *710#</li>
    <li>Unity Bank: *389*215#</li>
    <li>Heritage Bank: *322*030#</li>
    <li>KeyStone Bank: *322*082#</li>
    <li>Union Bank: *826#</li>
    <li>FCMB: *329#</li>
</ul>

<p>If you have any questions, please contact our <a href="https://instafxng.com/contact_info.php">support desk</a>
And please mention your transaction ID $client_trans_id when you call.</p>

<p>Thank you for using our services.</p>
INVOICE;
                break;
            default:
                $mail_body = <<<INVOICE
<p>To complete your order, please make your payment as follows:</p>

<p>When making your payment at the bank, fill in the depositor name as <br />
($client_trans_id - $client_full_name - $client_account) <br />
If you don't fill it as stated, your order will be unnecessarily delayed.</p>

<p>Pay =N= $client_naira_total into our account listed below.</p>

<p style="color: red">NOTE: Kindly make sure you pay into the account stated below.</p>

<ol>
    <li>Any Branch of <span style="color: blue; font-weight: bolder">Guaranty Trust Bank</span><br />
    Account Name:   <span style="color: blue; font-weight: bolder">Instant Web-Net Technologies Ltd</span><br />
    Account Number: <span style="color: blue; font-weight: bolder">0174516696</span></li>
    <li>After making the payment, visit https://instafxng.com/ and click on PAYMENT NOTIFICATION</li>
    <li>Your funding can be delayed for 6 months to 1 year if you fail to pay into the account on
    the invoice.</li>
</ol>

<p>Funding will be completed within 5 minutes to 2 hours on work days. Funding is completed
normally within the same day. If there is any delay we will inform you.</p>

<p>NOTE:</p>
<ul>
    <li>Third party payments are not allowed.</li>
    <li>Depositor Name / Transfer Memo must be written as<br />
    ($client_trans_id - $client_full_name - $client_account)</li>
    <li>Your account can only be funded after you have completed payment notification
    as advised in (2) above.</li>
</ul>

<p>If you have any questions, please contact our <a href="https://instafxng.com/contact_info.php">support desk</a>
And please mention your transaction ID $client_trans_id when you call.</p>

<p>Thank you for using our services.</p>
INVOICE;
                break;
        }
        
        // Send order invoice to client email address
        $subject = "InstaForex Funding Order Invoice - " . $client_trans_id;
        $body =
<<<MAIL
<div style="background-color: #F3F1F2">
    <div style="max-width: 80%; margin: 0 auto; padding: 10px; font-size: 14px; font-family: Verdana;">
        <img src="https://instafxng.com/images/ifxlogo.png" />
        <hr />
        <div style="background-color: #FFFFFF; padding: 15px; margin: 5px 0 5px 0;">
            <p>Dear $client_full_name,</p>

            <p>NOTE: This is a CONFIDENTIAL Document. Information herein should
            never be shared with anyone.</p>

            <p>THIS INVOICE IS VALID ONLY FOR 24 HOURS. IF PAYMENT IS NOT MADE BY THEN,
            YOU MUST SUBMIT ANOTHER ORDER.</p>

            <p>====================</p>

            <p>Your Transaction ID for this order is $client_trans_id</p>

            <p>The details of your order are as follows:</p>

            <p>Amount of InstaForex Funding ordered: USD $client_dollar
            Equivalent cost in Naira: =N= $client_naira_total</p>

            $mail_body

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
        
        $system_object->send_email($subject, $body, $client_email, $client_full_name);
        return $mail_body;
    }
    
    public function log_deposit($trans_id, $ifx_acc_id, $exchange, $ifx_dollar_amount, $ifx_naira_amount, $service_charge, $vat, $stamp_duty, $total_payable, $origin_of_deposit, $point_claimed_id = '') {
        global $db_handle;

        if(!empty($point_claimed_id)) {
            $query = "INSERT INTO user_deposit (trans_id, ifxaccount_id, exchange_rate, dollar_ordered, naira_equivalent_dollar_ordered, naira_service_charge, naira_vat_charge, naira_stamp_duty, naira_total_payable, deposit_origin, points_claimed_id)
                    VALUES ('$trans_id', $ifx_acc_id, $exchange, $ifx_dollar_amount, $ifx_naira_amount, $service_charge, $vat, $stamp_duty, $total_payable, '$origin_of_deposit', $point_claimed_id)";
        } else {
            $query = "INSERT INTO user_deposit (trans_id, ifxaccount_id, exchange_rate, dollar_ordered, naira_equivalent_dollar_ordered, naira_service_charge, naira_vat_charge, naira_stamp_duty, naira_total_payable, deposit_origin)
                    VALUES ('$trans_id', $ifx_acc_id, $exchange, $ifx_dollar_amount, $ifx_naira_amount, $service_charge, $vat, $stamp_duty, $total_payable, '$origin_of_deposit')";
        }
        
        $db_handle->runQuery($query);

        return $db_handle->affectedRows() > 0 ? true : false;
    }

    public function log_deposit_pay_method($trans_id, $pay_method) {
        global $db_handle;

        $query = "UPDATE user_deposit SET client_pay_method = '$pay_method' WHERE trans_id = '$trans_id' LIMIT 1";
        $db_handle->runQuery($query);

        return $db_handle->affectedRows() > 0 ? true : false;
    }

    public function requery_webpay_deposit($trans_id) {
        global $db_handle;

        /***************** GTPAY REQUERY FEATURE **********************************/
        $query = "SELECT naira_total_payable, status FROM user_deposit WHERE trans_id = '$trans_id' LIMIT 1";
        $result = $db_handle->runQuery($query);
        $fetched_data = $db_handle->fetchAssoc($result);
        $trans_detail = $fetched_data[0];

        if(!empty($trans_detail)) {
            $naira_total_payable = $trans_detail['naira_total_payable'];
            $amount = $naira_total_payable * 100;

            // GTPay hashing instruction using hash id provide by GTPay
            $to_hash = "4745" . $trans_id . "804726DAB9A13B6A8BD1473A0EF6D9DA8D839DFADC9592D5AD3D0EC0A8E8866D927911F77C2B0162981068D0FA0BEEE27E29C4D02C4474583DCE385BE88CA7B8";
            $hash_key = hash("SHA512", $to_hash);

            $verifyResponse = file_get_contents('https://ibank.gtbank.com/GTPayService/gettransactionstatus.json?mertid=4745&amount=' . $amount . '&tranxid=' . $trans_id . '&hash=' . $hash_key);
            $responseData = json_decode($verifyResponse);

            if($responseData->ResponseCode == '00') {
                $client_naira_notified = $responseData->Amount / 100;
                $query = "UPDATE user_deposit SET client_naira_notified = '$client_naira_notified', client_pay_date = NOW(), client_notified_date = NOW(), status = '2' WHERE trans_id = '$trans_id' LIMIT 1";
                $db_handle->runQuery($query);

                $message_success = "The transaction ID: $trans_id was SUCCESSFUL on the GTPay platform and has been moved to Notified Deposit.";
                $return_msg = array(
                    'type' => 1,
                    'resp' => $message_success
                );

                return $return_msg;
            } else {
                // Move Transaction to Failed if not already in failed
                if($trans_detail['status'] != '9') {
                    $query = "UPDATE user_deposit SET status = '9' WHERE trans_id = '$trans_id' LIMIT 1";
                    $db_handle->runQuery($query);
                }

                $message_error = "The transaction ID: $trans_id was NOT SUCCESSFUL on the GTPay platform and would remain in Deposit Failed. <strong>GTPAY Response: " . $responseData->ResponseDescription . "</strong>";
                $return_msg = array(
                    'type' => 2,
                    'resp' => $message_error
                );

                return $return_msg;
            }
        } else {
            return false;
        }
    }

    public function log_withdrawal($trans_id, $ifx_acc_id, $exchange, $ifx_dollar_amount, $ifx_naira_amount, $service_charge, $vat, $total_withdrawal_payable, $phone_password) {
        global $db_handle;

        $phone_password_encrypted = encrypt($phone_password);

        $query = "INSERT INTO user_withdrawal (trans_id, ifxaccount_id, exchange_rate, dollar_withdraw, naira_equivalent_dollar_withdraw, naira_service_charge, naira_vat_charge, naira_total_withdrawable, client_phone_password)
            VALUES ('$trans_id', $ifx_acc_id, $exchange, $ifx_dollar_amount, $ifx_naira_amount, $service_charge, $vat, $total_withdrawal_payable, '$phone_password_encrypted')";
        $db_handle->runQuery($query);

        return $db_handle->affectedRows() > 0 ? true : false;
    }

    public function send_withdrawal_invoice($client_full_name, $client_email, $trans_id, $account_no, $ifx_dollar_amount, $ifx_naira_amount, $service_charge, $vat, $total_withdrawal_payable, $client_bank_name, $client_acct_name, $client_acct_no) {
        global $system_object;

        // Send order invoice to client email address
        $subject = "InstaForex Withdrawal Order Invoice - " . $trans_id;
        $body =
<<<MAIL
<div style="background-color: #F3F1F2">
    <div style="max-width: 80%; margin: 0 auto; padding: 10px; font-size: 14px; font-family: Verdana;">
        <img src="https://instafxng.com/images/ifxlogo.png" />
        <hr />
        <div style="background-color: #FFFFFF; padding: 15px; margin: 5px 0 5px 0;">
            <p>Dear $client_full_name,</p>

            <p>Withdrawal Request Was Successfully Submitted - See the summary of your withdrawal below.
            Your Withdrawal will be processed and payment made within one business day.</p>

            <p>In a few cases some requests fall outside the category of withdrawals we can process from
            our office and has to be sent to InstaForex office. Withdrawal requests in this category can take
            up to 3 Business days.</p>

            <p>If your withdrawal request falls within this category, we will inform you immediately.</p>

            <p style="font-size: 1.3em; color: #AE0000;"><strong>NOTE: </strong>Your
            payment will be made based on the rate as at the time the fund is debited from your
            Instaforex account.</p>

            <table style="margin-left: auto; margin-right: auto;" border="1" width="400">
                <thead>
                    <tr><th> </th><th> </th></tr>
                </thead>
                <tbody>
                    <tr><td>Transaction ID</td><td>$trans_id</td></tr>
                    <tr><td>Instaforex Account</td><td>$account_no</td></tr>
                    <tr><td>Withdrawal Value (USD)</td><td>$ifx_dollar_amount</td></tr>
                    <tr><td>Withdrawal Value (&#8358;)</td><td>$ifx_naira_amount</td></tr>
                    <tr><td>Service Charge (&#8358;)</td><td>$service_charge</td></tr>
                    <tr><td>VAT (&#8358;)</td><td>$vat</td></tr>
                    <tr style="font-size: 1.2em; padding: 0; color: green; font-weight: bold"><td>Total Withdrawal Payable (&#8358;)</td><td>$total_withdrawal_payable</td></tr>
                    <tr><td>Bank Name</td><td>$client_bank_name</td></tr>
                    <tr><td>Bank Account Name</td><td>$client_acct_name</td></tr>
                    <tr><td>Bank Account Number</td><td>$client_acct_no</td></tr>
                </tbody>
            </table>

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

        $system_object->send_email($subject, $body, $client_email, $client_full_name);
        return true;
    }
    
    public function authenticate_user_password($password_submitted, $client_pass_salt, $client_password) {
        $hashed_user_password = hash("SHA512", "$client_pass_salt.$password_submitted");

        return $hashed_user_password == $client_password ? true : false;
    }

    // Get the transaction IDs associated with frozen points
    public function get_loyalty_point_frozen_transaction($user_code) {
        global $db_handle;

        $query = "SELECT ud.trans_id
              FROM user_deposit AS ud
              INNER JOIN point_based_claimed AS pbc
              ON ud.points_claimed_id = pbc.point_based_claimed_id
              WHERE pbc.user_code = '$user_code' AND pbc.status = '1'";
        $result = $db_handle->runQuery($query);
        $selected_data = $db_handle->fetchAssoc($result);

        return $selected_data;
    }

    public function get_loyalty_point_earned($user_code) {
        global $db_handle;

        if($user_code == "all") {
            $query = "SELECT SUM(pbr.point_earned) AS total_point
                FROM point_based_reward AS pbr";
        } else {
            $query = "SELECT SUM(pbr.point_earned) AS total_point
                FROM point_based_reward AS pbr
                WHERE pbr.user_code = '$user_code'";
        }

        $result = $db_handle->runQuery($query);
        $selected_data = $db_handle->fetchAssoc($result);
        $total_point = $selected_data[0]['total_point'];

        return $total_point;
    }

    public function get_loyalty_point_claimed($user_code) {
        global $db_handle;

        if($user_code == "all") {
            $query = "SELECT SUM(pbc.point_claimed) AS total_point_claimed
                FROM point_based_claimed AS pbc
                WHERE status = '2'";
        } else {
            $query = "SELECT SUM(pbc.point_claimed) AS total_point_claimed
                FROM point_based_claimed AS pbc
                WHERE pbc.user_code = '$user_code' AND status = '2'";
        }

        $result = $db_handle->runQuery($query);
        $selected_data = $db_handle->fetchAssoc($result);
        $total_point_claimed = $selected_data[0]['total_point_claimed'];

        return $total_point_claimed;
    }

    public function get_loyalty_point_frozen($user_code) {
        global $db_handle;

        if($user_code == "all") {
            $query = "SELECT SUM(pbc.point_claimed) AS total_point_frozen
                FROM point_based_claimed AS pbc
                WHERE status = '1'";
        } else {
            $query = "SELECT SUM(pbc.point_claimed) AS total_point_frozen
                FROM point_based_claimed AS pbc
                WHERE pbc.user_code = '$user_code' AND status = '1'";
        }

        $result = $db_handle->runQuery($query);
        $selected_data = $db_handle->fetchAssoc($result);
        $total_point_frozen = $selected_data[0]['total_point_frozen'];

        return $total_point_frozen;
    }
    
    public function get_loyalty_point($user_code) {
        $total_point = $this->get_loyalty_point_earned($user_code);
        $total_point_claimed = $this->get_loyalty_point_claimed($user_code);
        $total_point_frozen = $this->get_loyalty_point_frozen($user_code);
        $loyalty_point_balance = $total_point - ($total_point_claimed + $total_point_frozen);
        
        return $loyalty_point_balance;
    }

    public function get_point_based_claimed_by_id($points_claimed_id) {
        global $db_handle;

        $query = "SELECT point_claimed, user_code FROM point_based_claimed WHERE point_based_claimed_id = $points_claimed_id LIMIT 1";
        $result = $db_handle->runQuery($query);
        $selected_data = $db_handle->fetchAssoc($result);
        $point_details = $selected_data[0];

        return $point_details;
    }

    public function update_loyalty_point($points_claimed_id, $point_status) {
        global $db_handle;

        $query = "UPDATE point_based_claimed SET status = '$point_status' WHERE point_based_claimed_id = $points_claimed_id LIMIT 1";
        $db_handle->runQuery($query);

        // REVERSAL: When transaction failed, return deducted point back to the point balance
        if($point_status == '3') {
            $point_details = $this->get_point_based_claimed_by_id($points_claimed_id);
            $point_claimed = $point_details['point_claimed'];
            $user_code = $point_details['user_code'];

            // Update client point balance
            $query = "UPDATE user SET point_balance = point_balance + $point_claimed WHERE user_code = '$user_code' LIMIT 1";
            $db_handle->runQuery($query);
        }

        return true;
    }

    public function get_loyalty_point_dollar_amount($user_code, $points_claimed_id) {
        global $db_handle;

        $query = "SELECT dollar_amount FROM point_based_claimed WHERE point_based_claimed_id = $points_claimed_id AND user_code = '$user_code' AND status = '1'";
        $result = $db_handle->runQuery($query);
        $selected_data = $db_handle->fetchAssoc($result);
        $dollar_amount = $selected_data[0]['dollar_amount'];

        return $dollar_amount;
    }

    public function set_deposit_loyalty_point($transaction_id) {
        global $db_handle;

        $query = "SELECT ud.user_deposit_id, ud.real_dollar_equivalent, ud.ifxaccount_id, ui.user_code
                FROM user_deposit AS ud
                INNER JOIN user_ifxaccount AS ui ON ud.ifxaccount_id = ui.ifxaccount_id
                WHERE ud.trans_id = '$transaction_id' AND ud.status = '8' LIMIT 1";
        $result = $db_handle->runQuery($query);

        if($db_handle->numOfRows($result) > 0) {
            // Transaction exist and qualifies

            $fetched_data = $db_handle->fetchAssoc($result);

            $user_code = $fetched_data[0]['user_code'];
            $ifx_account_id = $fetched_data[0]['ifxaccount_id'];
            $real_dollar = $fetched_data[0]['real_dollar_equivalent'];
            $reference = $fetched_data[0]['user_deposit_id'];
            $rate_used = POINT_PER_DOLLAR_FUND;
            $point_earned = $real_dollar * $rate_used;

            // Let us confirm that loyalty point has not been set for this transaction
            $query = "SELECT point_based_reward_id FROM point_based_reward WHERE reference = $reference AND type = '1'";
            $result = $db_handle->runQuery($query);

            if($db_handle->numOfRows($result) > 0) {
                // Loyalty point already set for this transaction
                return false;
            } else {
                // Loyalty point not set, so set it.
                $query = "INSERT INTO point_based_reward (user_code, point_earned, rate_used, type, reference, date_earned) VALUES "
                    . "('$user_code', $point_earned, $rate_used, '1', $reference, NOW())";
                $db_handle->runQuery($query);

                // Update client point balance
                $query = "UPDATE user SET point_balance = point_balance + $point_earned WHERE user_code = '$user_code' LIMIT 1";
                $db_handle->runQuery($query);

                return true;
            }
        }

    }

    public function set_trading_loyalty_point($account_no, $volume, $reference, $commission_date) {
        global $db_handle;

        $query = "SELECT user_code FROM user_ifxaccount WHERE ifx_acct_no = '$account_no' LIMIT 1";
        $result = $db_handle->runQuery($query);
        $fetched_data = $db_handle->fetchAssoc($result);
        $user_code = $fetched_data[0]['user_code'];

        $rate_used = POINT_ON_TRADING;
        $point_earned = ($volume / 0.01) * POINT_ON_TRADING;

        // Let us confirm that loyalty point has not been set for this transaction
        $query = "SELECT point_based_reward_id FROM point_based_reward WHERE reference = $reference AND type = '2'";
        $result = $db_handle->runQuery($query);

        if($db_handle->numOfRows($result) > 0) {
            // Loyalty point already set for this transaction
            return false;
        } else {
            // Loyalty point not set, so set it.
            $query = "INSERT INTO point_based_reward (user_code, point_earned, rate_used, type, reference, date_earned) VALUES "
                . "('$user_code', $point_earned, $rate_used, '2', $reference, '$commission_date')";
            $db_handle->runQuery($query);

            // Update client point balance
            $query = "UPDATE user SET point_balance = point_balance + $point_earned WHERE user_code = '$user_code' LIMIT 1";
            $db_handle->runQuery($query);

            return true;
        }
    }

    public function set_point_claimed($point_claimed, $client_user_code) {
        global $db_handle;

        $rate_used = DOLLAR_PER_POINT;
        $dollar_amount = $point_claimed * $rate_used;

        // Update client point balance
        $query = "UPDATE user SET point_balance = point_balance - $point_claimed WHERE user_code = '$client_user_code' LIMIT 1";
        $db_handle->runQuery($query);

        // Log points
        $query = "INSERT INTO point_based_claimed (user_code, point_claimed, dollar_amount, rate_used) VALUES ('$client_user_code', $point_claimed, $dollar_amount, $rate_used)";
        $result = $db_handle->runQuery($query);

        return $result ? $db_handle->insertedId() : false;
    }
    
    public function send_verification_message($client_user_code, $client_email, $client_phone_number, $client_first_name, $client_verification_id) {
        global $db_handle;
        global $system_object;
        
        $user_code_encrypted = encrypt($client_user_code);

        $sms_code = generate_sms_code();
        $sms_message = "Your activation code is: $sms_code A message has been sent to your email, click the activation link in it and enter this code.";
        
        $system_object->send_sms($client_phone_number, $sms_message);
        
        if(!is_null($client_verification_id)) {
            $query = "UPDATE user_verification SET phone_code = '$sms_code' WHERE verification_id = $client_verification_id LIMIT 1";
            $db_handle->runQuery($query);
        } else {
            $query = "INSERT INTO user_verification (user_code, phone_code) VALUES ('$client_user_code', '$sms_code')";
            $db_handle->runQuery($query);
        }
        
        // Send activation link to email
        $subject = "Instafxng Activation Link";
        $body =
<<<MAIL
<div style="background-color: #F3F1F2">
    <div style="max-width: 80%; margin: 0 auto; padding: 10px; font-size: 14px; font-family: Verdana;">
        <img src="https://instafxng.com/images/ifxlogo.png" />
        <hr />
        <div style="background-color: #FFFFFF; padding: 15px; margin: 5px 0 5px 0;">
            <p>Dear $client_first_name,</p>

            <p>Please activate your account now by clicking the link below.
            You can also copy and past the link to your browser address box.</p>

            <p>https://instafxng.com/activate_code.php?uuc=$user_code_encrypted</p>

            <p>You will be requested for the activation code sent to your phone.</p>

            <p>Also, create a secured PASSCODE (4 - 8 characters) for yourself. The PASSCODE you
            create will be requested any time you want to make funding or withdrawal transactions</p>

            <p>Thank you for using our services.</p>

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
        
        $system_object->send_email($subject, $body, $client_email, $client_first_name);
    }

    public function send_document_verify_email($client_first_name, $client_email) {
        global $system_object;

        $subject = "Instafxng Document Verification";
        $message =
<<<MAIL
<div style="background-color: #F3F1F2">
    <div style="max-width: 80%; margin: 0 auto; padding: 10px; font-size: 14px; font-family: Verdana;">
        <img src="https://instafxng.com/images/ifxlogo.png" />
        <hr />
        <div style="background-color: #FFFFFF; padding: 15px; margin: 5px 0 5px 0;">
            <p>Dear $client_first_name,</p>

            <p>You have successfully uploaded your documents for the Instafxng Document Verification.
            Our verification staff will confirm the documents for genuineness and hence approve or decline.
            Your documents will be checked shortly.<p>

            <p>Thank you for choosing Instafxng.</p>

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

        $system_object->send_email($subject, $message, $client_email, $client_first_name);
        return true;
    }

    public function get_user_by_email($email_address) {
        global $db_handle;

        $query = "SELECT user_code FROM user WHERE email = '$email_address' LIMIT 1";
        $result = $db_handle->runQuery($query);

        if($db_handle->numOfRows($result) > 0) {
            $fetched_data = $db_handle->fetchAssoc($result);
            $fetched_data = $fetched_data[0];
            return $fetched_data;
        } else {
            return false;
        }
    }

    public function get_user_by_user_code($user_code) {
        global $db_handle;

        $query = "SELECT u.user_id, u.user_code, u.email, u.phone, u.password, u.first_name,
            u.middle_name, u.last_name, u.created, u.status, CONCAT(a.last_name, SPACE(1), a.first_name) AS account_officer_full_name
            FROM user AS u
            INNER JOIN account_officers AS ao ON u.attendant = ao.account_officers_id
            INNER JOIN admin AS a ON ao.admin_code = a.admin_code
            WHERE u.user_code = '$user_code' LIMIT 1";
        $result = $db_handle->runQuery($query);
        $fetched_data = $db_handle->fetchAssoc($result);
        $fetched_data = $fetched_data[0];

        return $fetched_data;
    }

    public function get_account_id_by_account_no($account_no) {
        global $db_handle;

        $query = "SELECT ifxaccount_id FROM user_ifxaccount WHERE ifx_acct_no = '$account_no' LIMIT 1";
        $result = $db_handle->runQuery($query);

        if($db_handle->numOfRows($result) > 0) {
            $fetched_data = $db_handle->fetchAssoc($result);
            $fetched_data = $fetched_data[0]['ifxaccount_id'];
            return $fetched_data;
        } else {
            return false;
        }

    }

    public function get_user_code_by_account_no($account_no) {
        global $db_handle;

        $query = "SELECT user_code FROM user_ifxaccount WHERE ifx_acct_no = '$account_no' LIMIT 1";
        $result = $db_handle->runQuery($query);

        if($db_handle->numOfRows($result) > 0) {
            $fetched_data = $db_handle->fetchAssoc($result);
            $fetched_data = $fetched_data[0]['user_code'];
            return $fetched_data;
        } else {
            return false;
        }

    }

    // Add a new account flag
    public function add_new_account_flag($account_flag_no, $client_user_code, $ifx_account_id, $flag_account_comment, $flag_account_status = '1', $admin_code) {
        global $db_handle;

        if(!empty($account_flag_no)) {
            $query = "UPDATE user_account_flag SET user_code = '$client_user_code', ifxaccount_id = $ifx_account_id, comment = '$flag_account_comment', status = '$flag_account_status' WHERE user_account_flag_id = $account_flag_no LIMIT 1";
        } else {
            $query = "INSERT INTO user_account_flag (user_code, admin_code, ifxaccount_id, comment, status) VALUES ('$client_user_code', '$admin_code', $ifx_account_id, '$flag_account_comment', '$flag_account_status')";
        }

        $db_handle->runQuery($query);

        if($db_handle->affectedRows() > 0) {
            return true;
        } else {
            return false;
        }
    }

    // get flag details
    public function get_client_flag_by_code($user_code) {
        global $db_handle;

        $query = "SELECT uaf.user_account_flag_id, uaf.comment, uaf.status, uaf.created, ui.ifx_acct_no,
            CONCAT(u.last_name, SPACE(1), u.first_name) AS client_full_name,
            CONCAT(a.last_name, SPACE(1), a.first_name) AS admin_full_name
            FROM user_account_flag AS uaf
            INNER JOIN user_ifxaccount AS ui ON uaf.ifxaccount_id = ui.ifxaccount_id
            INNER JOIN user AS u ON ui.user_code = u.user_code
            INNER JOIN admin AS a ON uaf.admin_code = a.admin_code
            WHERE uaf.user_code = '$user_code' ORDER BY uaf.created DESC";

        $result = $db_handle->runQuery($query);

        if($db_handle->numOfRows($result) > 0) {
            $fetched_data = $db_handle->fetchAssoc($result);
            return $fetched_data;
        } else {
            return false;
        }
    }

    // Check if account is flagged and  details
    public function account_flagged($user_code) {
        global $db_handle;

        $query = "SELECT * FROM user_account_flag WHERE user_code = '$user_code' AND status = '1'";
        $result = $db_handle->runQuery($query);
        $fetch_flag = $db_handle->fetchAssoc($result);
        return $fetch_flag  ? $fetch_flag  : false;
    }

    // This is for a single client
    public function get_client_ilpr_accounts_by_code($user_code) {
        global $db_handle;

        $query = "SELECT ifx_acct_no, ifxaccount_id FROM user_ifxaccount WHERE user_code = '$user_code' AND type = '1'";
        $result = $db_handle->runQuery($query);
        $fetched_data = $db_handle->fetchAssoc($result);

        return $fetched_data;
    }

    // This is for a single client
    public function get_client_non_ilpr_accounts_by_code($user_code) {
        global $db_handle;

        $query = "SELECT ifx_acct_no FROM user_ifxaccount WHERE user_code = '$user_code' AND type = '2'";
        $result = $db_handle->runQuery($query);
        $fetched_data = $db_handle->fetchAssoc($result);

        return $fetched_data;
    }

    public function get_client_ifxaccounts($user_code) {
        global $db_handle;

        $query = "SELECT ifx_acct_no FROM user_ifxaccount WHERE user_code = '$user_code'";
        $result = $db_handle->runQuery($query);
        $fetched_data = $db_handle->fetchAssoc($result);

        return $fetched_data;
    }

    public function get_user_address_by_code($user_code) {
        global $db_handle;

        $query = "SELECT um.address, um.address2, um.city, s.state, um.state_id
                FROM user_meta AS um
                LEFT JOIN state AS s ON um.state_id = s.state_id
                WHERE um.user_code = '$user_code' LIMIT 1";
        $result = $db_handle->runQuery($query);
        $fetched_data = $db_handle->fetchAssoc($result);
        $client_address = $fetched_data[0];

        return $client_address ? $client_address : false;
    }

    public function get_user_phonecode($user_code) {
        global $db_handle;

        $query = "SELECT phone_code, phone_status FROM user_verification WHERE user_code = '$user_code' LIMIT 1";
        $result = $db_handle->runQuery($query);
        $fetched_data = $db_handle->fetchAssoc($result);
        $phone_code = $fetched_data[0]['phone_code'];
        $phone_status = $fetched_data[0]['phone_status'];

        $data = array('phone_code' => $phone_code, 'phone_status' => $phone_status);

        return $data;
    }

    public function deposit_monitoring($admin_code, $transaction_id, $status) {
        global $db_handle;

        $status = status_user_deposit($status);
        $query = "INSERT INTO deposit_monitoring (admin_code, trans_id, description) VALUES ('$admin_code', '$transaction_id', '$status')";
        $db_handle->runQuery($query);

        return true;
    }

    public function deposit_comment($transaction_id, $admin_code, $comment) {
        global $db_handle;

        $query = "INSERT INTO deposit_comment (trans_id, admin_code, comment) VALUES ('$transaction_id', '$admin_code', '$comment')";
        $db_handle->runQuery($query);

        return true;
    }

    public function get_deposit_remark($trans_id) {
        global $db_handle;

        $query = "SELECT CONCAT(a.last_name, SPACE(1), a.first_name) AS admin_full_name, dc.comment, dc.created
                FROM deposit_comment AS dc
                INNER JOIN admin AS a ON dc.admin_code = a.admin_code
                WHERE dc.trans_id = '$trans_id' ORDER BY dc.created DESC";
        $result = $db_handle->runQuery($query);
        $fetched_data = $db_handle->fetchAssoc($result);

        return $fetched_data ? $fetched_data : false;

    }

    public function get_deposit_by_id_full($trans_id) {
        global $db_handle;

        $query = "SELECT ud.trans_id, ud.exchange_rate, ud.dollar_ordered, ud.naira_total_payable, ud.created AS deposit_created,
                ud.client_naira_notified, ud.client_pay_date, ud.client_pay_method, ud.client_reference,
                ud.client_comment, ud.client_comment_response, ud.client_notified_date, ud.real_naira_confirmed,
                ud.real_dollar_equivalent, ud.points_claimed_id, ud.transfer_reference, ud.deposit_origin,
                ud.status AS deposit_status, ui.ifx_acct_no, ui.is_bonus_account, ui.type AS account_type, ui.user_code,
                ui.ifxaccount_id, ui.status AS account_status, u.email, CONCAT(u.last_name, SPACE(1), u.first_name) AS full_name, u.phone,
                uc.passport, uaf.ifxaccount_id AS account_flagged
                FROM user_deposit AS ud
                INNER JOIN user_ifxaccount AS ui ON ud.ifxaccount_id = ui.ifxaccount_id
                INNER JOIN user AS u ON ui.user_code = u.user_code
                LEFT JOIN user_credential AS uc ON ui.user_code = uc.user_code
                LEFT JOIN user_account_flag AS uaf ON ui.ifxaccount_id = uaf.ifxaccount_id
                WHERE ud.trans_id = '$trans_id' LIMIT 1";

        $result = $db_handle->runQuery($query);
        $fetched_data = $db_handle->fetchAssoc($result);
        $trans_detail = $fetched_data[0];

        return $trans_detail ? $trans_detail : false;
    }

    public function update_deposit_client_comment_response($transaction_id) {
        global $db_handle;

        $query = "UPDATE user_deposit SET client_comment_response = '1' WHERE trans_id = '$transaction_id' LIMIT 1";
        $db_handle->runQuery($query);

        return $db_handle->affectedRows() > 0 ? true : false;
    }

    public function deposit_transaction_confirmation($transaction_id, $realamtpaid, $realDolVal, $status, $remarks, $admin_code) {
        global $db_handle;

        // Update transaction
        $query = "UPDATE user_deposit SET status = '$status', real_naira_confirmed = '$realamtpaid', "
            . "real_dollar_equivalent = '$realDolVal' WHERE trans_id = '$transaction_id' LIMIT 1";
        $db_handle->runQuery($query);

        // Log comment on transaction
        $this->deposit_comment($transaction_id, $admin_code, $remarks);

        // Log deposit monitoring
        $this->deposit_monitoring($admin_code, $transaction_id, $status);

        return true;
    }

    public function deposit_transaction_completion($transaction_id, $transaction_reference, $status, $remarks, $admin_code) {
        global $db_handle;

        if($status == '8') {
            $query = "UPDATE user_deposit SET order_complete_time = NOW(), status = '$status', transfer_reference = '$transaction_reference' "
                . "WHERE trans_id = '$transaction_id' LIMIT 1";
        } else {
            $query = "UPDATE user_deposit SET status = '$status', transfer_reference = '$transaction_reference' "
                . "WHERE trans_id = '$transaction_id' LIMIT 1";
        }

        $db_handle->runQuery($query);

        // Log comment on transaction
        $this->deposit_comment($transaction_id, $admin_code, $remarks);

        // Log deposit monitoring
        $this->deposit_monitoring($admin_code, $transaction_id, $status);

        // calculate and log loyalty points earned, send email
        if($status == '8') {
            $this->set_deposit_loyalty_point($transaction_id);
            $this->deposit_complete_notify($transaction_id);
        }

        return true;
    }

    public function deposit_complete_notify($transaction_id) {
        global $db_handle;
        global $system_object;

        $query = "SELECT u.first_name, u.email, ud.points_claimed_id, STR_TO_DATE(ud.created, '%Y-%m-%d') AS date_ordered,
                ud.real_naira_confirmed, ud.real_dollar_equivalent, u.user_code, ud.dollar_ordered, ui.ifx_acct_no
                FROM user_deposit AS ud
                INNER JOIN user_ifxaccount AS ui ON ud.ifxaccount_id = ui.ifxaccount_id
                INNER JOIN user AS u ON ui.user_code = u.user_code
                WHERE trans_id = '$transaction_id' LIMIT 1";
        $result = $db_handle->runQuery($query);
        $fetched_data = $db_handle->fetchAssoc($result);
        $fetched_data = $fetched_data[0];
        extract($fetched_data);

        if(!empty($points_claimed_id)) {
            $point_dollar_amount = $this->get_loyalty_point_dollar_amount($user_code, $points_claimed_id);
        }

        $dollar_ordered = number_format($dollar_ordered, 2, ".", ",");
        $real_naira_confirmed = number_format($real_naira_confirmed, 2, ".", ",");
        $real_dollar_equivalent = number_format($real_dollar_equivalent, 2, ".", ",");

        $subject = "Instaforex Account Deposit Receipt (REF: $transaction_id)";
        $message =
<<<MAIL
<div style="background-color: #F3F1F2">
    <div style="max-width: 80%; margin: 0 auto; padding: 10px; font-size: 14px; font-family: Verdana;">
        <img src="https://instafxng.com/images/ifxlogo.png" />
        <hr />
        <div style="background-color: #FFFFFF; padding: 15px; margin: 5px 0 5px 0;">
            <p>Dear $first_name,</p>

            <p>Your funding order with transaction ID: $transaction_id has been completed and your Instaforex Account has been funded.
            Kindly login to your client cabinet to view your account.<p>

            <table style="height: 145px; margin-left: auto; margin-right: auto;" border="1" width="332">
                <tbody>
                    <tr>
                        <td style="width: 158px;">Transaction ID</td>
                        <td style="width: 158px;">$transaction_id</td>
                    </tr>
                    <tr>
                        <td style="width: 158px;">Instaforex Account</td>
                        <td style="width: 158px;">$ifx_acct_no</td>
                    </tr>
                    <tr>
                        <td style="width: 158px;">Amount Ordered ($)</td>
                        <td style="width: 158px;">$dollar_ordered</td>
                    </tr>
                    <tr>
                        <td style="width: 158px;">Payment Confirmed N</td>
                        <td style="width: 158px;">$real_naira_confirmed</td>
                    </tr>
                    <tr>
                        <td style="width: 158px;">Amount Funded ($)</td>
                        <td style="width: 158px;">$real_dollar_equivalent</td>
                    </tr>
                    <tr>
                        <td style="width: 158px;">Loyalty Point Funded ($)</td>
                        <td style="width: 158px;">$point_dollar_amount</td>
                    </tr>
                    <tr>
                        <td style="width: 158px;">Date</td>
                        <td style="width: 158px;">$date_ordered</td>
                    </tr>
                </tbody>
            </table>

            <p>Do you know you can win up to $4,200 and 1 million Naira from our point based
            loyalty program and rewards this year? <a href="https://instafxng.com/loyalty.php">Click here</a> for details</p>

            <p>Thank you for choosing Instafxng.</p>

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
                <p><strong>Lekki Office Address:</strong> Block A3, Suite 508/509 Eastline Shopping Complex, Opposite Abraham Adesanya Roundabout, along Lekki - Epe expressway, Lagos State</p>
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

        $system_object->send_email($subject, $message, $email, $first_name);
    }

    public function get_withdrawal_by_id_full($trans_id) {
        global $db_handle;

        $query = "SELECT uw.trans_id, uw.exchange_rate, uw.dollar_withdraw, uw.naira_equivalent_dollar_withdraw, uw.created AS withdrawal_created,
                uw.naira_total_withdrawable, uw.client_phone_password, uw.client_comment, uw.transfer_reference,
                uw.transfer_reference, uw.status AS withdrawal_status, ui.ifx_acct_no, ui.is_bonus_account, ui.type AS account_type, ui.user_code,
                ui.ifxaccount_id, ui.status AS account_status, u.email, CONCAT(u.last_name, SPACE(1), u.first_name) AS full_name, u.phone,
                uc.passport, uaf.ifxaccount_id AS account_flagged
                FROM user_withdrawal AS uw
                INNER JOIN user_ifxaccount AS ui ON uw.ifxaccount_id = ui.ifxaccount_id
                INNER JOIN user AS u ON ui.user_code = u.user_code
                LEFT JOIN user_credential AS uc ON ui.user_code = uc.user_code
                LEFT JOIN user_account_flag AS uaf ON ui.ifxaccount_id = uaf.ifxaccount_id
                WHERE uw.trans_id = '$trans_id' LIMIT 1";

        $result = $db_handle->runQuery($query);
        $fetched_data = $db_handle->fetchAssoc($result);
        $trans_detail = $fetched_data[0];

        return $trans_detail ? $trans_detail : false;
    }

    public function get_withdrawal_remark($trans_id) {
        global $db_handle;

        $query = "SELECT CONCAT(a.last_name, SPACE(1), a.first_name) AS admin_full_name, wc.comment, wc.created
                FROM withdrawal_comment AS wc
                INNER JOIN admin AS a ON wc.admin_code = a.admin_code
                WHERE wc.trans_id = '$trans_id' ORDER BY wc.created DESC";
        $result = $db_handle->runQuery($query);
        $fetched_data = $db_handle->fetchAssoc($result);

        return $fetched_data ? $fetched_data : false;
    }

    public function withdrawal_comment($transaction_id, $admin_code, $comment) {
        global $db_handle;

        $query = "INSERT INTO withdrawal_comment (trans_id, admin_code, comment) VALUES ('$transaction_id', '$admin_code', '$comment')";
        $db_handle->runQuery($query);

        return true;
    }

    public function withdrawal_transaction_account_check($transaction_id, $status, $remarks, $admin_code) {
        global $db_handle;

        // Update transaction
        $query = "UPDATE user_withdrawal SET status = '$status' WHERE trans_id = '$transaction_id'";
        $db_handle->runQuery($query);

        // Log comment on transaction
        $this->withdrawal_comment($transaction_id, $admin_code, $remarks);

        // Log deposit monitoring
        $this->withdrawal_monitoring($admin_code, $transaction_id, $status);

        return true;
    }

    public function withdrawal_transaction_ifx_debited($transaction_id, $transaction_reference, $status, $remarks, $admin_code) {
        global $db_handle;

        // Update transaction
        $query = "UPDATE user_withdrawal SET status = '$status', transfer_reference = '$transaction_reference' WHERE trans_id = '$transaction_id'";
        $db_handle->runQuery($query);

        // Log comment on transaction
        $this->withdrawal_comment($transaction_id, $admin_code, $remarks);

        // Log deposit monitoring
        $this->withdrawal_monitoring($admin_code, $transaction_id, $status);

        return true;
    }

    public function withdrawal_transaction_completed($transaction_id, $status, $remarks, $admin_code) {
        global $db_handle;

        // Update transaction
        $query = "UPDATE user_withdrawal SET status = '$status' WHERE trans_id = '$transaction_id'";
        $db_handle->runQuery($query);

        /// Log comment on transaction
        $this->withdrawal_comment($transaction_id, $admin_code, $remarks);

        // Log deposit monitoring
        $this->withdrawal_monitoring($admin_code, $transaction_id, $status);

        return true;
    }

    public function withdrawal_monitoring($admin_code, $transaction_id, $status) {
        global $db_handle;

        $status = status_user_deposit($status);
        $query = "INSERT INTO withdrawal_monitoring (admin_code, trans_id, description) VALUES ('$admin_code', '$transaction_id', '$status')";
        $db_handle->runQuery($query);

        return true;
    }

    public function authenticate_smscode($user_code, $sms_code) {
        global $db_handle;

        $query = "SELECT * FROM user_verification WHERE user_code = '$user_code' AND phone_code = '$sms_code' LIMIT 1";
        if($db_handle->numRows($query) > 0) {
            $query = "UPDATE user_verification SET phone_status = '2' WHERE user_code = '$user_code' LIMIT 1";
            $db_handle->runQuery($query);
            return true;
        } else {
            return false;
        }
    }

    public function update_user_password($usercode, $passcode) {
        global $db_handle;
        $pass_salt = hash("SHA256", "$usercode");
        $hashed_password = hash("SHA512", "$pass_salt.$passcode");

        $query = "UPDATE user SET password = '$hashed_password', pass_salt = '$pass_salt', reset_code = '', reset_expiry = '' WHERE user_code = '$usercode' LIMIT 1";
        $db_handle->runQuery($query);

        if($db_handle->affectedRows() > 0) {
            return true;
        } else {
            return false;
        }
    }

    public function start_password_reset($user_code, $first_name, $email) {
        global $db_handle;
        global $system_object;

        $reset_code = rand_string(20);
        $user_code_encrypted = encrypt($user_code);

        $query = "UPDATE user SET reset_code = '$reset_code', reset_expiry = NOW() + INTERVAL 1 HOUR WHERE user_code = '$user_code' LIMIT 1";
        $db_handle->runQuery($query);

        if($db_handle->affectedRows() > 0) {
            $subject = "Instafxng: Passcode Recovery Mail";
            $message =
<<<MAIL
<div style="background-color: #F3F1F2">
    <div style="max-width: 80%; margin: 0 auto; padding: 10px; font-size: 14px; font-family: Verdana;">
        <img src="https://instafxng.com/images/ifxlogo.png" />
        <hr />
        <div style="background-color: #FFFFFF; padding: 15px; margin: 5px 0 5px 0;">
            <p>Dear $first_name,</p>

            <p>You requested to reset your passcode on our website. Click the link below to continue the process.<p>
            <p><strong>Note:</strong> If you did not request for this recovery, simply ignore this email, the link will
            expire in 1 hour time.</p>

            <p>https://instafxng.com/reset_password.php?x=$user_code_encrypted&c=$reset_code</p>

            <p>Thank you for choosing Instafxng.</p>

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

            $system_object->send_email($subject, $message, $email, $first_name);
            return true;
        } else {
            return false;
        }
    }

    public function log_deposit_meta($id, $gtpay_tranx_status_code, $gtpay_tranx_status_msg, $gtpay_tranx_amt, $gtpay_tranx_curr, $gtpay_gway_name, $gtpay_full_verification_hash) {
        global $db_handle;

        $query = "INSERT INTO user_deposit_meta (user_deposit_id, trans_status_code, trans_status_message, trans_amount, trans_currency, gateway_name, full_verify_hash) "
            . "VALUES ($id, '$gtpay_tranx_status_code', '$gtpay_tranx_status_msg', '$gtpay_tranx_amt', '$gtpay_tranx_curr', '$gtpay_gway_name', '$gtpay_full_verification_hash')";
        $db_handle->runQuery($query);

        if($db_handle->affectedRows() > 0) {
            return true;
        } else {
            return false;
        }
    }

    /**
     * @param $user_code
     * @param string $interest_training
     * @param string $interest_funding
     * @param string $interest_bonus
     * @param string $interest_investment
     * @param string $interest_services
     * @param string $interest_other
     * @return bool
     */
    public function log_sales_contact_client_interest($user_code, $interest_training = '1', $interest_funding = '1', $interest_bonus = '1', $interest_investment = '1', $interest_services = '1', $interest_other = '1') {
        global $db_handle;

        if(empty($interest_training)) { $interest_training = '1'; }
        if(empty($interest_funding)) { $interest_funding = '1'; }
        if(empty($interest_bonus)) { $interest_bonus = '1'; }
        if(empty($interest_investment)) { $interest_investment = '1'; }
        if(empty($interest_services)) { $interest_services = '1'; }
        if(empty($interest_other)) { $interest_other = '1'; }

        if($db_handle->numRows("SELECT user_code FROM sales_contact_client_interest WHERE user_code = '$user_code' LIMIT 1") > 0) {

            $query = "UPDATE sales_contact_client_interest SET
                  interest_training = '$interest_training',
                  interest_funding = '$interest_funding',
                  interest_bonus = '$interest_bonus',
                  interest_investment = '$interest_investment',
                  interest_services = '$interest_services',
                  interest_other = '$interest_other' WHERE user_code = '$user_code' LIMIT 1";
        } else {
            $query = "INSERT INTO sales_contact_client_interest (user_code, interest_training, interest_funding, interest_bonus, interest_investment, interest_services, interest_other) VALUES ('$user_code', '$interest_training', '$interest_funding', '$interest_bonus', '$interest_investment', '$interest_services', '$interest_other')";
        }

        $db_handle->runQuery($query);

        return $db_handle->affectedRows() > 0 ? true : false;
    }

    public function log_academy_first_login($first_name, $email_address, $user_code) {
        global $db_handle;
        global $system_object;

        $query = "UPDATE user SET academy_signup = NOW() WHERE user_code = '$user_code' LIMIT 1";
        $db_handle->runQuery($query);

        $subject = "$first_name, your Journey to Consistent Income Starts Here";
        $message =
<<<MAIL
<div style="background-color: #F3F1F2">
    <div style="max-width: 80%; margin: 0 auto; padding: 10px; font-size: 14px; font-family: Verdana;">
        <img src="https://instafxng.com/images/ifxlogo.png" />
        <hr />
        <div style="background-color: #FFFFFF; padding: 15px; margin: 5px 0 5px 0;">
            <p>Hello $first_name,</p>

            <p>A very warm welcome to you. The first step on the journey to making consistent
            income from Forex trading is getting adequate knowledge and I’m glad yours has begun.</p>

            <p>Tighten your seat belt as this is going to be an amazing journey and I can only giggle
            right now as I know that when you are done, you will be armed with enough knowledge to
            conquer the world, in this case, take all that you deserve from life instead of just
            settling with what life has to offer.</p>

            <ul>
                <li>Be sure to give this training your 100% attention and thoroughly go through
                each lesson without forgetting to attempt all the test exercises.</li>
                <li>Don’t hesitate to use any of the message box to ask a question when you need
                more clarity on something or you have a hard time understanding a particular lesson
                and you will be swiftly responded to. </li>
                <li>Rest assured that I am fully committed to holding your hands even as I guide you
                through all the lessons of this course.</li>
            </ul>

            <p>$first_name, brace up for you are about to start one heck of an amazing journey that
            leads you to getting all that you deserve from life. </p>

            <p><a href="http://bit.ly/2ffEeKl" title="Start the training">You can click here to start
            the training.</a></p>

            <p>It's a big Welcome once again from me to you and I look forward to seeing you on the
             other side.</p>


            <br /><br />
            <p>Best Regards,<br />
            Mercy,<br />
            Client relations manager<br /></p>
            <p>www.instafxng.com</p>
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

        $system_object->send_email($subject, $message, $email_address, $first_name);

        return $db_handle->affectedRows() > 0 ? true : false;
    }

    public function send_startup_bonus_training_mail($client_full_name, $client_email) {
        global $system_object;

        $subject = "[FREE TRAINING] You Need this to Make More Profit, [NAME]";
        $body = <<<MAIL
<div style="background-color: #F3F1F2">
    <div style="max-width: 80%; margin: 0 auto; padding: 10px; font-size: 14px; font-family: Verdana;">
        <img src="https://instafxng.com/images/ifxlogo.png" />
        <hr />
        <div style="background-color: #FFFFFF; padding: 15px; margin: 5px 0 5px 0;">
            <p>Hello $client_full_name,</p>

            <p>Do you know that the Forex Market is the most liquid market in the world with
            over 5 trillion dollars traded on a daily basis?</p>

            <p>That's right! Forex trading is highly profitable; in fact, you can make more
            money trading the Forex market and even gain financial freedom from it.</p>

            <p>Some months back, you got the $500 Startup bonus from InstaForex. This way you
            became a part of the money making team.</p>

            <p>But is that the only thing you need to make consistent income from Forex trading?
            Not at all!</p>

            <p>To keep making sustainable profit from the Forex market, you need KNOWLEDGE!</p>
            <p>You need to acquire adequate knowledge of the Forex market and understand how to
            trade profitably in the Forex market.</p>

            <p>Our Free Online Forex training is currently on at the moment and guess what? The
            training is FREE (at least for now).</p>

            <p>As soon as you're done with the online training, you will be able to place informed
            trades and increase your chances of taking your slice of the 5.3 Billion Dollars from
            the Forex market.</p>

            <p style="text-align: center"><a href="http://bit.ly/2ffEeKl">Click Here to Start the Training Now.</a></p>

            <p>[NAME], we are taking in just 50 people at this time, for this brand new Forex
            Money Maker Course and I really want you to be one of them. The slots are filling
            up very fast.</p>

            <p>Please don't miss this. Go ahead and login to the training immediately to secure
            your spot. Don’t worry you can take a break and continue later, as long as you have
            started and your spot is secured.</p>

            <p style="text-align: center"><a href="http://bit.ly/2ffEeKl">Click Here to Start the Training Now.</a></p>

            <p>This will be your best shot at generating a healthy side income from forex trading.
            Go ahead and make the move now.</p>


            <br /><br />
            <p>Best Regards,</p>
            <p>Bunmi,</p>
            <p>Clients Relations Manager,<br />
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

        // Replace [NAME] with clients full name
        $body = str_replace('[NAME]', $client_full_name, $body);
        $subject = str_replace('[NAME]', $client_full_name, $subject);

        return $system_object->send_email($subject, $body, $client_email, $client_full_name) ? true : false;
    }

    public function get_last_trade_detail($user_code) {
        global $db_handle;

        $query = "SELECT td.date_earned, td.volume
            FROM trading_commission AS td
            INNER JOIN user_ifxaccount AS ui ON td.ifx_acct_no = ui.ifx_acct_no
            INNER JOIN user AS u ON ui.user_code = u.user_code
            WHERE u.user_code = '$user_code' ORDER BY td.date_earned DESC LIMIT 1";

        $result =  $db_handle->runQuery($query);
        $fetched_data = $db_handle->fetchAssoc($result);
        $last_trade_detail = $fetched_data[0];

        return $last_trade_detail ? $last_trade_detail : false;
    }

}