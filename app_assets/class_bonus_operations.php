<?php
class Bonus_Operations {

    public function bonus_acc_reviwed($bonus_account_id, $condition_id, $status){
        global $db_handle;
        $query = "DELETE FROM bonus_acc_condition_meta WHERE bonus_account_id = $bonus_account_id AND condition_id = $condition_id";
        $db_handle->runQuery($query);
        $query = "INSERT INTO bonus_acc_condition_meta (bonus_account_id, condition_id, status_id) VALUES ($bonus_account_id, $condition_id, '$status')";
        if($db_handle->runQuery($query)){
            return true;
        }

    }

    public function bonus_status($app_id){
        switch ($app_id){
            case '1': $x = "Bonus Live"; break;
            case '2': $x = "Bonus Withdrawn"; break;
            case '3': $x = "Bonus Expired"; break;
            default:$x = "UNKNOWN";break;
        }return $x;
    }

    public function get_app_meta_by_id($app_id){
        global $db_handle;
        return $db_handle->fetchAssoc($db_handle->runQuery("SELECT comments, admin_code, MAX(created) AS _created FROM bonus_app_meta WHERE app_id = $app_id ORDER BY _created DESC"))[0];
    }

    public function decline_app($app_id, $reasons, $admin_code){
        global $db_handle;
        global $system_object;
        $query = "UPDATE bonus_accounts SET enrolment_status = '1', allocation_status = '2', updated = now(), admin_code = '$admin_code' WHERE bonus_account_id = $app_id; ";
        $updates = $db_handle->runQuery($query);
        if($updates) {
            $db_handle->runQuery("INSERT INTO bonus_app_meta (app_id, comments, admin_code) VALUES ($app_id, '$reasons', '$admin_code');");
            $app_details = $this->get_app_by_id($app_id);
            $mail_subject = "Instaforex Bonus Application";
            //Todo: get a mail that would be sent to clients when their bonus applications are declined.
            $mail_content = <<<MAIL
                            <div style="background-color: #F3F1F2">
                                <div style="max-width: 80%; margin: 0 auto; padding: 10px; font-size: 14px; font-family: Verdana;">
                                    <img src="https://instafxng.com/images/ifxlogo.png" />
                                    <hr />
                                    <div style="background-color: #FFFFFF; padding: 15px; margin: 5px 0 5px 0;">
                                   
                                    <br /><br />
                                    <p>Best Regards,</p>
                                    <p>Instaforex Nigeria,<br />www.instafxng.com</p>
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
            $mail_sender = "InstaFxNg";
            $system_object->send_email($mail_subject, $mail_content, $app_details['email'], $app_details['last_name'], $mail_sender);
            $x = true;
        }else{ $x = false; }
        return $x;
    }

    public function approve_app($app_id, $amount, $admin_code){
        global $db_handle;
        global $system_object;
        if(!empty($amount) && ($amount > 0)) {
            $query = "UPDATE bonus_accounts 
                        SET enrolment_status = '2', 
                        allocation_status = '1', 
                        updated = now(), 
                        admin_code = '$admin_code', 
                        allocation_date = now(), 
                        allocated_amount = $amount, 
                        bonus_status = '1'  
                        WHERE bonus_account_id = $app_id; ";
        }else{
            $query = "UPDATE bonus_accounts 
SET enrolment_status = '2', 
allocation_status = '2', 
updated = now(), 
admin_code = '$admin_code' 
WHERE bonus_account_id = $app_id; ";
        }
        $updates = $db_handle->runQuery($query);
        if($updates) {
            $app_details = $this->get_app_by_id($app_id);
            $mail_subject = "Instaforex Bonus Application";
            //Todo: get a mail that would be sent to clients when their bonus applications are approved.
            $mail_content = <<<MAIL
                            <div style="background-color: #F3F1F2">
                                <div style="max-width: 80%; margin: 0 auto; padding: 10px; font-size: 14px; font-family: Verdana;">
                                    <img src="https://instafxng.com/images/ifxlogo.png" />
                                    <hr />
                                    <div style="background-color: #FFFFFF; padding: 15px; margin: 5px 0 5px 0;">
                                   
                                    <br /><br />
                                    <p>Best Regards,</p>
                                    <p>Instaforex Nigeria,<br />www.instafxng.com</p>
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
            $mail_sender = "InstaFxNg";
            $system_object->send_email($mail_subject, $mail_content, $app_details['email'], $app_details['last_name'], $mail_sender);
            $x = true;
        }else{ $x = false; }
        return $x;
    }
    
    public function get_app_by_id($app_id){
        global $db_handle;
        $query = "SELECT 
BA.bonus_code, BA.enrolment_status, BA.allocation_status, BA.allocation_date, BA.allocated_amount, BA.admin_code AS compliance_officer, BA.bonus_status, 
UI.user_code, UI.ifx_acct_no, UI.type AS account_type, 
U.first_name, UPPER(U.last_name) AS last_name, U.middle_name, U.email, U.phone, 
BP.bonus_title, BP.bonus_desc, BP.condition_id, 
BA.created AS created, 
BA.bonus_account_id, UI.ifxaccount_id,
BA.updated, UIE.user_ilpr_enrolment_id 
FROM bonus_accounts AS BA 
INNER JOIN user_ifxaccount AS UI ON BA.ifx_account_id = UI.ifxaccount_id 
INNER JOIN user AS U ON UI.user_code = U.user_code 
INNER JOIN bonus_packages AS BP ON BA.bonus_code = BP.bonus_code 
INNER JOIN user_ilpr_enrolment AS UIE ON UIE.ifxaccount_id = UI.ifxaccount_id
WHERE BA.bonus_account_id = $app_id
ORDER BY created DESC ";
        //var_dump($query);
        return $db_handle->fetchAssoc($db_handle->runQuery($query))[0];
    }

    public function get_active_packages(){
        global $db_handle;
        $query = "SELECT * FROM bonus_packages WHERE status = '2' ORDER BY created DESC ";
        return $db_handle->fetchAssoc($db_handle->runQuery($query));
    }

    public function create_new_package($bonus_title, $bonus_desc, $bonus_details, $bonus_image, $condition_id, $status, $type, $admin_code, $extra = '', $type_value){
        global $db_handle;
        bonus_code:
        $bonus_code = rand_string(5);
        if($db_handle->numRows("SELECT bonus_code FROM bonus_packages WHERE bonus_code = '$bonus_code'") > 0) { goto bonus_code; };

        $query = "INSERT INTO bonus_packages 
(bonus_code, bonus_title, bonus_desc, bonus_details, bonus_img, condition_id, status, type, admin_code, bonus_type_value) 
VALUES 
('$bonus_code', '$bonus_title', '$bonus_desc', '$bonus_details', '$bonus_image','$condition_id', '$status', $type, '$admin_code', $type_value);";
        $result = $db_handle->runQuery($query);

        if($extra && !empty($extra) && is_array($extra)) {
            foreach ($extra as $row => $row_value) {
                foreach ($row_value as $key => $value) {
                    $this->create_new_package_meta($bonus_code, $row, $key, $value);
                }
            }
        }
        return $result;
    }

    public function create_new_package_meta($bonus_code, $condition_id, $meta_name, $meta_value){
        global $db_handle;
        $query = "INSERT INTO bonus_package_meta (bonus_code, condition_id, meta_name, meta_value) VALUES ('$bonus_code', $condition_id, '$meta_name', '$meta_value');";
        $result = $db_handle->runQuery($query);
        return $result;
    }

    public function get_package_active_clients($bonus_code){
        global $db_handle;
        $query = "SELECT CONCAT(U.first_name, SPACE(1), UPPER(U.last_name)) AS fullname, BA.created AS created, U.phone, U.email, UI.ifx_acct_no  
FROM bonus_accounts AS BA 
INNER JOIN user_ifxaccount AS UI ON BA.ifx_account_id = UI.ifxaccount_id
INNER JOIN user AS U ON UI.user_code = U.user_code
WHERE BA.bonus_code = '$bonus_code'
AND BA.enrolment_status = '2'
AND BA.allocation_status = '1'
AND BA.bonus_status = '1'
ORDER BY created DESC";
        $return_value['details'] = $db_handle->fetchAssoc($db_handle->runQuery($query));
        $return_value['sum'] = $db_handle->numRows($query);
        return $return_value;
    }

    public function get_package_recycled_clients($bonus_code){
        global $db_handle;
        $query = "SELECT CONCAT(U.first_name, SPACE(1), UPPER(U.last_name)) AS fullname, BA.updated AS updated, U.phone, U.email, UI.ifx_acct_no  
FROM bonus_accounts AS BA 
INNER JOIN user_ifxaccount AS UI ON BA.ifx_account_id = UI.ifxaccount_id
INNER JOIN user AS U ON UI.user_code = U.user_code
WHERE BA.bonus_code = '$bonus_code'
AND BA.enrolment_status = '2'
AND BA.allocation_status = '1'
AND BA.bonus_status IN ('2', '3') 
ORDER BY updated DESC";
        $return_value['details'] = $db_handle->fetchAssoc($db_handle->runQuery($query));
        $return_value['sum'] = $db_handle->numRows($query);
        return $return_value;
    }

    public function get_total_bonus_package_payouts($bonus_code){
        global $db_handle;
        $query = "SELECT CONCAT(U.first_name, SPACE(1), UPPER(U.last_name)) AS fullname, BA.updated AS updated, U.phone, U.email, UI.ifx_acct_no  
FROM bonus_accounts AS BA 
INNER JOIN user_ifxaccount AS UI ON BA.ifx_account_id = UI.ifxaccount_id
INNER JOIN user AS U ON UI.user_code = U.user_code
WHERE BA.bonus_code = '$bonus_code'
AND BA.enrolment_status = '2'
AND BA.allocation_status = '1'
AND BA.bonus_status IN ('2', '3') 
ORDER BY updated DESC";
        $return_value['details'] = $db_handle->fetchAssoc($db_handle->runQuery($query));
        $return_value['sum'] = '0.00';
        return $return_value;
    }

    public function get_total_bonus_package_withdrawals($bonus_code){
        global $db_handle;
        $query = "SELECT CONCAT(U.first_name, SPACE(1), UPPER(U.last_name)) AS fullname, BA.updated AS updated, U.phone, U.email, UI.ifx_acct_no  
FROM bonus_accounts AS BA 
INNER JOIN user_ifxaccount AS UI ON BA.ifx_account_id = UI.ifxaccount_id
INNER JOIN user AS U ON UI.user_code = U.user_code
WHERE BA.bonus_code = '$bonus_code'
AND BA.enrolment_status = '2'
AND BA.allocation_status = '1'
AND BA.bonus_status IN ('2', '3') 
ORDER BY updated DESC";
        $return_value['details'] = $db_handle->fetchAssoc($db_handle->runQuery($query));
        $return_value['sum'] = '0.00';
        return $return_value;
    }

    public function get_package_by_code($bonus_code){
        global $db_handle;
        $query = "SELECT bonus_code, bonus_title, bonus_desc, bonus_details, bonus_img, bonus_type_value, created, updated, condition_id, status, type, admin_code FROM bonus_packages WHERE bonus_code = '$bonus_code' ";
        return $db_handle->fetchAssoc($db_handle->runQuery($query))[0];
    }

    public function get_package_meta_by_code($bonus_code){
        global $db_handle;
        $query = "SELECT * FROM bonus_package_meta WHERE bonus_code = '$bonus_code' ";
        return $db_handle->fetchAssoc($db_handle->runQuery($query));
    }

    public function get_single_condition_by_id($condition_id){
        $bonus_conditions = new Bonus_Condition();
        $conditions = $bonus_conditions->BONUS_CONDITIONS;
        return $conditions[$condition_id];
    }

    public function bonus_package_status($package_status){
        switch ((int) $package_status){
            case 1: $msg = "Draft"; break;
            case 2: $msg = "Active"; break;
            case 3: $msg = "Inactive"; break;
        }
        return $msg;
    }

    public function bonus_package_pending_applications($bonus_code){
        global $db_handle;
        $query = "SELECT BA.ifx_account_id, BA.created, U.email, CONCAT(U.first_name, SPACE(1), U.last_name) AS full_name, U.phone, UI.ifx_acct_no  
FROM bonus_accounts AS BA 
INNER JOIN user_ifxaccount AS UI ON BA.ifx_account_id = UI.ifxaccount_id 
INNER JOIN user AS U ON UI.user_code = U.user_code 
WHERE BA.bonus_code = '$bonus_code' 
AND BA.enrolment_status = '0' ";
        $feedback['sum'] = $db_handle->numRows($query);
        $feedback['details'] = $db_handle->fetchAssoc($db_handle->runQuery($query));
        return $feedback;
    }

    public function bonus_package_approved_applications($bonus_code){
        global $db_handle;
        $query = "SELECT BA.ifx_account_id, BA.created, U.email, CONCAT(U.first_name, SPACE(1), U.last_name) AS full_name, U.phone, UI.ifx_acct_no  
FROM bonus_accounts AS BA 
INNER JOIN user_ifxaccount AS UI ON BA.ifx_account_id = UI.ifxaccount_id 
INNER JOIN user AS U ON UI.user_code = U.user_code 
WHERE BA.bonus_code = '$bonus_code' 
AND BA.enrolment_status = '2' ";
        $feedback['sum'] = $db_handle->numRows($query);
        $feedback['details'] = $db_handle->fetchAssoc($db_handle->runQuery($query));
        return $feedback;
    }

    public function bonus_package_declined_applications($bonus_code){
        global $db_handle;
        $query = "SELECT BA.ifx_account_id, BA.created, U.email, CONCAT(U.first_name, SPACE(1), U.last_name) AS full_name, U.phone, UI.ifx_acct_no  
FROM bonus_accounts AS BA 
INNER JOIN user_ifxaccount AS UI ON BA.ifx_account_id = UI.ifxaccount_id 
INNER JOIN user AS U ON UI.user_code = U.user_code 
WHERE BA.bonus_code = '$bonus_code' 
AND BA.enrolment_status = '1' ";
        $feedback['sum'] = $db_handle->numRows($query);
        $feedback['details'] = $db_handle->fetchAssoc($db_handle->runQuery($query));
        return $feedback;
    }

    public function show_bonus_package_type($package_type, $bonus_type_value){
        switch ((int) $package_type){
            case 1: $msg = "$bonus_type_value% Bonus Package"; break;
            case 2: $msg = "&dollar; $bonus_type_value Bonus Package"; break;
        }
        echo $msg;
    }

    public function get_condition_extras($bonus_code,$condition_id){
        global $db_handle;
        $query = "SELECT meta_name, meta_value, condition_id FROM bonus_package_meta WHERE bonus_code = '$bonus_code' AND condition_id = $condition_id  ";
        return $db_handle->fetchAssoc($db_handle->runQuery($query));
    }

    public function show_conditions_by_code($bonus_code){
        $condition_ids = $this->get_package_by_code($bonus_code)['condition_id'];
        $conditions = array();
        $condition_ids = explode(',', $condition_ids);
        $count = 1;
        foreach ($condition_ids as $key) {
            $conditions[$count] = $this->get_single_condition_by_id($key);
            $count++;
        }
        foreach ($conditions as $key => $value) {
            if(!empty($value)){
                echo '<span class="text-justify">'.$key.'. '.$value['title'].'<br/>'.$value['desc'].'</span><br/><br/>';
            }
        }
    }

    public function get_conditions_by_code($bonus_code){
        $condition_ids = $this->get_package_by_code($bonus_code)['condition_id'];
        $conditions = array();
        $condition_ids = explode(',', $condition_ids);
        $count = 1;
        foreach ($condition_ids as $key) {
            $conditions[$count] = $this->get_single_condition_by_id($key);
            $count++;
        }
        return $conditions;
    }

    public function update_package($bonus_code, $bonus_title, $bonus_desc, $condition_id, $status, $type, $extra = '', $type_value){
        global $db_handle;
        $query = "UPDATE bonus_packages SET bonus_title = '$bonus_title', bonus_desc = '$bonus_desc', condition_id = '$condition_id', status = '$status', type = $type, updated = now(), bonus_type_value = $type_value WHERE bonus_code = '$bonus_code' ";
        $result = $db_handle->runQuery($query);
        $db_handle->runQuery("DELETE FROM bonus_package_meta WHERE bonus_code = '$bonus_code' ");
        if($extra && !empty($extra) && is_array($extra)) {
            foreach ($extra as $row => $row_value) {
                foreach ($row_value as $key => $value) {
                    $this->create_new_package_meta($bonus_code, $row, $key, $value);
                }
            }
        }return $result;
    }

    public function new_bonus_application($account_no, $full_name, $email_address, $phone_number, $bonus_code){
        global $db_handle;
        $client_operation = new clientOperation();
        $client_operation->new_user($account_no, $full_name, $email_address, $phone_number, $type = 2);
        $db_handle->runQuery("UPDATE user_ifxaccount SET is_bonus_account = '2' WHERE ifx_acct_no = '$account_no' ");
        $ifx_account_id = $db_handle->fetchAssoc($db_handle->runQuery("SELECT ifxaccount_id FROM user_ifxaccount WHERE ifx_acct_no = '$account_no' "))[0]['ifxaccount_id'];
        return $db_handle->runQuery("INSERT INTO bonus_accounts (ifx_account_id, bonus_code) VALUES ($ifx_account_id, '$bonus_code')");
        //$x && $y ? $result = true : $result = false;
        //return $result;
    }

    public function get_pending_applications(){
        global $db_handle;
        $query = "SELECT 
                BA.bonus_code, BA.enrolment_status, BA.allocation_status, BA.allocated_amount, BA.admin_code AS compliance_officer, BA.bonus_status, BA.bonus_account_id AS app_id,
                UI.user_code, UI.ifx_acct_no, UI.type AS account_type, 
                U.first_name, UPPER(U.last_name) AS last_name, U.middle_name, U.email, U.phone, 
                BP.bonus_title, BP.bonus_desc, BP.condition_id, 
                BA.created AS created 
                FROM bonus_accounts AS BA 
                INNER JOIN user_ifxaccount AS UI ON BA.ifx_account_id = UI.ifxaccount_id 
                INNER JOIN user AS U ON UI.user_code = U.user_code 
                INNER JOIN bonus_packages AS BP ON BA.bonus_code = BP.bonus_code 
                WHERE BA.enrolment_status = '0' 
                ORDER BY created DESC ";
        return $db_handle->fetchAssoc($db_handle->runQuery($query));
    }

    public function get_bonus_accounts(){
        global $db_handle;
        $query = "SELECT 
                  BA.bonus_code, BA.enrolment_status, BA.allocation_status, BA.allocated_amount, BA.admin_code AS compliance_officer, BA.bonus_status, BA.bonus_account_id AS app_id,
                  UI.user_code, UI.ifx_acct_no, UI.type AS account_type, 
                  U.first_name, UPPER(U.last_name) AS last_name, U.middle_name, U.email, U.phone, 
                  BP.bonus_title, BP.bonus_desc, BP.condition_id, 
                  BA.created AS created 
                  FROM bonus_accounts AS BA 
                  INNER JOIN user_ifxaccount AS UI ON BA.ifx_account_id = UI.ifxaccount_id 
                  INNER JOIN user AS U ON UI.user_code = U.user_code 
                  INNER JOIN bonus_packages AS BP ON BA.bonus_code = BP.bonus_code 
                  WHERE BA.enrolment_status = '2' 
                  ORDER BY created DESC ";
        return $db_handle->fetchAssoc($db_handle->runQuery($query));
    }

    public function get_single_pending_application($app_id){
        global $db_handle;
        $query = "SELECT 
BA.bonus_code, BA.enrolment_status, BA.allocation_status, BA.allocated_amount, BA.admin_code AS compliance_officer, BA.bonus_status, BA.bonus_account_id AS app_id
UI.user_code, UI.ifx_acct_no, UI.type AS account_type, 
U.first_name, UPPER(U.last_name) AS last_name, U.middle_name, U.email, U.phone, 
BP.bonus_title, BP.bonus_desc, BP.condition_id, 
BA.created AS created 
FROM bonus_accounts AS BA 
INNER JOIN user_ifxaccount AS UI ON BA.ifx_account_id = UI.ifxaccount_id 
INNER JOIN user AS U ON UI.user_code = U.user_code 
INNER JOIN bonus_packages AS BP ON BA.bonus_code = BP.bonus_code 
WHERE BA.enrolment_status = '0' 
AND BA.bonus_account_id = $app_id
ORDER BY created DESC ";
        return $db_handle->fetchAssoc($db_handle->runQuery($query))[0];
    }

    public function get_acc_ilpr_state($bonus_account){
        global $db_handle;
        $query = "SELECT type FROM user_ifxaccount WHERE ifx|_acct_no = '$bonus_account' ";
        $acc_type = $db_handle->fetchAssoc($db_handle->runQuery($query))[0]['type'];
        switch ((int) $acc_type){
            case 1: $msg = "ILPR Account"; break;
            case 2: $msg = "None-ILPR Account"; break;
        }
        return $msg;
    }

    public function get_funding_history($bonus_account){
        global $db_handle;
        $query = "SELECT real_dollar_equivalent AS amount, UD.created AS date FROM user_ifxaccount AS UI
INNER JOIN user_deposit AS UD ON UI.ifxaccount_id = UD.ifxaccount_id 
WHERE UI.ifx_acct_no = '$bonus_account' AND UD.status = '8'";
        $funding_transactions = $db_handle->fetchAssoc($db_handle->runQuery($query));
        $total_funded = $db_handle->fetchAssoc($db_handle->runQuery("SELECT SUM(real_dollar_equivalent) AS total_amount FROM user_ifxaccount AS UI
INNER JOIN user_deposit AS UD ON UI.ifxaccount_id = UD.ifxaccount_id 
WHERE UI.ifx_acct_no = '$bonus_account' AND UD.status = '8'"))[0]['total_amount'];
        $average_funding = $total_funded / $db_handle->numRows($query);
        $funding_history = array('total' => $total_funded, 'average' => $average_funding, 'transactions' => $funding_transactions);
        return $funding_history;
    }

    public function get_withdrawals_history($bonus_account){
        global $db_handle;
        $query = "SELECT dollar_withdraw AS amount, UW.created AS date FROM user_ifxaccount AS UI
INNER JOIN user_withdrawal AS UW ON UI.ifxaccount_id = UW.ifxaccount_id 
WHERE UI.ifx_acct_no = '$bonus_account' AND UW.status = '8'";
        $withdrawal_transactions = $db_handle->fetchAssoc($db_handle->runQuery($query));
        $total_withdrawal = $db_handle->fetchAssoc($db_handle->runQuery("SELECT SUM(dollar_withdraw) AS total_amount FROM user_ifxaccount AS UI
INNER JOIN user_withdrawal AS UW ON UI.ifxaccount_id = UW.ifxaccount_id 
WHERE UI.ifx_acct_no = '$bonus_account' AND UW.status = '10'"))[0]['total_amount'];
        $average_withdrawal =  66666 / 20;
        //$average_withdrawal = $total_withdrawal / $db_handle->numRows($query);
        $withdrawal_history = array('total' => $total_withdrawal, 'average' => $average_withdrawal, 'transactions' => $withdrawal_transactions);
        return $withdrawal_history;
    }

}





















