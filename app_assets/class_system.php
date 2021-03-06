<?php
use PHPMailer\PHPMailer\PHPMailer;
class InstafxngSystem {
    
    // function to send SMTP emails
    public function send_email($subject, $message, $sendto_email, $sendto_name, $from_name = '', $attachment = '') {
        
        //PHPMailer Object
        $mail = new PHPMailer;
        $mail->IsSMTP();
        $mail->Host = "instafxng.com";  // specify main and backup server
        $mail->SMTPAuth = true;     // turn on SMTP authentication
        $mail->Username = "mailbox@instafxng.com";  // SMTP username
        $mail->Password = "s@he&LL6S9Xl"; // SMTP password

        //From email address and name
        $mail->From = "noreply@instafxng.com";
        
        if(isset($from_name) && !empty($from_name)) {
            $mail->FromName = $from_name;
            
            //Address to which recipient will reply
            $mail->addReplyTo("support@instafxng.com", $from_name);
        } else {
            $mail->FromName = "InstaFxNg";
            
            //Address to which recipient will reply
            $mail->addReplyTo("support@instafxng.com", "InstaFxNg");
        }

        //Send HTML or Plain Text email
        $mail->isHTML(true);
        
        //To address and name
        $mail->clearAddresses();
        $mail->addAddress("$sendto_email", "$sendto_name");

        //Set BCC address
//        $mail->addBCC("mailbox@instafxng.com", "InstaFxNg");

        $mail->Subject = $subject;
        $mail->Body = $message;

        if(isset($attachment) && !empty($attachment)) {
            $mail->addAttachment($attachment);
        }

        return $mail->send() ? true : false;
    }
    
    // function to send sms - using smslive247.com
    public function send_sms($phone, $my_message) {
        global $db_handle;
        $phone_number = trim(preg_replace('/[\s\t\n\r\s]+/', '', $phone));
        $message = str_replace(" ","+",$my_message);
        $status = file_get_contents("http://sms.smsworks360.com/api/?username=support@instafxng.com&password=ijoba_09A&message=$message&sender=INSTAFXNG&mobiles=$phone_number");
//        file_get_contents("http://www.smslive247.com/http/index.aspx?cmd=sendmsg&sessionid=5b422f10-7b78-4631-9b98-a1c2e1872099&message=$message&sender=INSTAFXNG&sendto=$phone_number&msgtype=0");
        ///Record the sms
        if(isset($_SESSION['admin_unique_code']) && !empty($_SESSION['admin_unique_code'])){$sender = $_SESSION['admin_unique_code'];}else{$sender = "INSTAFXNG";}
        $status = str_replace('	', '', $status);
        $message = str_replace('+', ' ', $message);
        $query = "INSERT INTO sms_records(phone_no, message, status, sender) VALUES('$phone', '$message', '$status', '$sender')";
        $db_handle->runQuery($query);
        return true;
    }

    // function to send app push notifications - using fcm
    public function send_push_mobile($token, $message, $title){
        $path_to_firebase_cm = 'https://fcm.googleapis.com/fcm/send';
        $API_SERVER_KEY = 'AAAAC0QYmqE:APA91bGSDtRp6HucthhIimDbmH3rzVakSLUIQRIIFqgBV-jXmYCfzE7sWvEdGVghRTSXL-fdLnnjdiXwTKibzrn4KrTaOTSrbyPGKkQylOt5mkRkvmup6MmUN9zZh-8QzYutQPazAvZu';
        $fields = array(
            'registration_ids' => $token,
            'priority' => 'high',
            'notification' => array('title' => $title, 'body' =>  $message ,'sound'=>'Enabled','image'=>'Notification Image' ),
            'delay_while_idle' => false,
            'content_available' => true,
			'time_to_live' => 2419200
            );
        $headers = array('Authorization:key='.$API_SERVER_KEY, 'Content-Type:application/json');

        // Open connection
        $ch = curl_init();
        // Set the url, number of POST vars, POST data
        curl_setopt($ch, CURLOPT_URL, $path_to_firebase_cm);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_IPRESOLVE, CURL_IPRESOLVE_V4 );
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($fields));
        // Execute post
        $result = curl_exec($ch);
        // Close connection
        curl_close($ch);
        return $result;
    }

    public function send_push_web($token, $message, $title, $click_action){
        $path_to_firebase_cm = 'https://fcm.googleapis.com/fcm/send';
        $API_SERVER_KEY = '';
        $fields = array(
            'notification' => array(
                'title' => $title,
                'body' =>  $message ,
                'icon' => 'https://instafxng.com/images/Insta_logo.jpg',
                'click_action'=> $click_action ),
            'to' => $token
        );
        $headers = array('Authorization:key='.$API_SERVER_KEY, 'Content-Type:application/json');

        // Open connection
        $ch = curl_init();
        // Set the url, number of POST vars, POST data
        curl_setopt($ch, CURLOPT_URL, $path_to_firebase_cm);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_IPRESOLVE, CURL_IPRESOLVE_V4 );
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($fields));
        // Execute post
        $result = curl_exec($ch);
        // Close connection
        curl_close($ch);
        return $result;
    }

    // Get all the states
    public function get_all_states() {
        global $db_handle;

        $query = "SELECT state_id, state FROM state ORDER BY state_id ASC";
        $result = $db_handle->runQuery($query);
        $fetched_data = $db_handle->fetchAssoc($result);

        return $fetched_data ? $fetched_data : false;
    }

    // Get all the banks
    public function get_all_banks() {
        global $db_handle;

        $query = "SELECT bank_id, bank_name FROM bank ORDER BY bank_id ASC";
        $result = $db_handle->runQuery($query);
        $fetched_data = $db_handle->fetchAssoc($result);

        return $fetched_data ? $fetched_data : false;
    }



    // Calculate saldo report
    public function get_saldo_report($from_date, $to_date, $category) {
        global $db_handle;

        if($category == 'all') {
            $query = "SELECT (SUM(ud.exchange_rate) / COUNT(ud.trans_id)) AS avg_deposit_rate,
                  SUM(ud.real_naira_confirmed) AS sum_total, SUM(ud.real_dollar_equivalent) AS sum_dol_total
                  FROM user_deposit AS ud WHERE ud.status = '8' AND (STR_TO_DATE(ud.order_complete_time, '%Y-%m-%d') BETWEEN '$from_date' AND '$to_date') ";
        } else {
            $query = "SELECT (SUM(ud.exchange_rate) / COUNT(ud.trans_id)) AS avg_deposit_rate,
                  SUM(ud.real_naira_confirmed) AS sum_total, SUM(ud.real_dollar_equivalent) AS sum_dol_total
                  FROM user_deposit AS ud
                  INNER JOIN user_ifxaccount AS ui ON ud.ifxaccount_id = ui.ifxaccount_id
                  WHERE ud.status = '8' AND ui.type = '$category' AND (STR_TO_DATE(ud.order_complete_time, '%Y-%m-%d') BETWEEN '$from_date' AND '$to_date') ";
        }

        $result = $db_handle->runQuery($query);
        $selected_data = $db_handle->fetchAssoc($result);

        $total_deposit = $selected_data[0]['sum_total'];
        $total_deposit_dollar = $selected_data[0]['sum_dol_total'];
        $deposit_avg = $selected_data[0]['avg_deposit_rate'];

        if($category == 'all') {
            $query = "SELECT (SUM(uw.exchange_rate) / COUNT(uw.trans_id)) AS avg_withdrawal_rate,
                  SUM(uw.naira_total_withdrawable) AS sum_total, SUM(uw.dollar_withdraw) AS sum_dol_total
                  FROM user_withdrawal AS uw WHERE uw.status = '10' AND (STR_TO_DATE(uw.updated, '%Y-%m-%d') BETWEEN '$from_date' AND '$to_date') ";
        } else {
            $query = "SELECT (SUM(uw.exchange_rate) / COUNT(uw.trans_id)) AS avg_withdrawal_rate,
                  SUM(uw.naira_total_withdrawable) AS sum_total, SUM(uw.dollar_withdraw) AS sum_dol_total
                  FROM user_withdrawal AS uw
                  INNER JOIN user_ifxaccount AS ui ON uw.ifxaccount_id = ui.ifxaccount_id
                  WHERE uw.status = '10' AND ui.type = '$category' AND (STR_TO_DATE(uw.updated, '%Y-%m-%d') BETWEEN '$from_date' AND '$to_date') ";
        }

        $result = $db_handle->runQuery($query);
        $selected_data = $db_handle->fetchAssoc($result);

        $total_withdrawal = $selected_data[0]['sum_total'];
        $total_withdrawal_dollar = $selected_data[0]['sum_dol_total'];
        $withdrawal_avg = $selected_data[0]['avg_withdrawal_rate'];

        $saldo_calculated = $total_deposit - $total_withdrawal;
        $saldo_calculated_dollar = $total_deposit_dollar - $total_withdrawal_dollar;

        $saldo = array("category" => $category, "deposit_avg" => $deposit_avg, "withdrawal_avg" => $withdrawal_avg,  "saldo" => $saldo_calculated, "saldo_dollar" => $saldo_calculated_dollar, "deposit" => $total_deposit, "deposit_dollar" => $total_deposit_dollar, "withdrawal" => $total_withdrawal, "withdrawal_dollar" => $total_withdrawal_dollar);
        return $saldo;
    }

    // Calculate commission report
    public function get_comission_report($from_date, $to_date) {
        global $db_handle;

        $query = "SELECT COUNT(DISTINCT ifx_acct_no) AS accounts, SUM(volume) AS volume, SUM(commission) AS commission
                FROM trading_commission WHERE date_earned BETWEEN '$from_date' AND '$to_date' ";
        $result = $db_handle->runQuery($query);
        $selected_data = $db_handle->fetchAssoc($result);

        return $selected_data[0];
    }

    // Calculate commission report details
    public function get_comission_report_details($from_date, $to_date) {
        global $db_handle;

        $query = "SELECT COUNT(DISTINCT ifx_acct_no) AS accounts, SUM(volume) AS volume, SUM(commission) AS commission, date_earned
                FROM trading_commission WHERE date_earned BETWEEN '$from_date' AND '$to_date' GROUP BY date_earned";
        $result = $db_handle->runQuery($query);
        $selected_data = $db_handle->fetchAssoc($result);

        return $selected_data;
    }

    // Calculate VAT charge report
    public function get_vat_charge_report($from_date, $to_date, $vat_type) {
        global $db_handle;

        if($vat_type == 'Deposit') {
            $query = "SELECT SUM(naira_vat_charge) AS vat FROM user_deposit WHERE status = '8' AND (STR_TO_DATE(created, '%Y-%m-%d') BETWEEN '$from_date' AND '$to_date') ";
        } else {
            $query = "SELECT SUM(naira_vat_charge) AS vat FROM user_withdrawal WHERE status = '10' AND (STR_TO_DATE(created, '%Y-%m-%d') BETWEEN '$from_date' AND '$to_date') ";
        }

        $result = $db_handle->runQuery($query);
        $selected_vat = $db_handle->fetchAssoc($result);

        return $selected_vat[0]['vat'];
    }

    // Calculate Service charge report
    public function get_service_charge_report($from_date, $to_date, $vat_type) {
        global $db_handle;

        if($vat_type == 'Deposit') {
            $query = "SELECT SUM(naira_service_charge) AS service_charge FROM user_deposit WHERE status = '8' AND (STR_TO_DATE(created, '%Y-%m-%d') BETWEEN '$from_date' AND '$to_date') ";
        } else {
            $query = "SELECT SUM(naira_service_charge) AS service_charge FROM user_withdrawal WHERE status = '10' AND (STR_TO_DATE(created, '%Y-%m-%d') BETWEEN '$from_date' AND '$to_date') ";
        }

        $result = $db_handle->runQuery($query);
        $selected_service_charge = $db_handle->fetchAssoc($result);

        return $selected_service_charge[0]['service_charge'];
    }

    // Get bulletin by id
    public function get_bulletin_by_id($bulletin_id) {
        global $db_handle;

        $query = "SELECT * FROM admin_bulletin WHERE admin_bulletin_id = $bulletin_id LIMIT 1";
        $result = $db_handle->runQuery($query);
        $fetched_data = $db_handle->fetchAssoc($result);

        return $fetched_data ? $fetched_data : false;
    }

    // Get article by id
    public function get_article_by_id($article_id) {
        global $db_handle;

        $query = "SELECT * FROM article WHERE article_id = $article_id LIMIT 1";
        $result = $db_handle->runQuery($query);
        $fetched_data = $db_handle->fetchAssoc($result);

        return $fetched_data ? $fetched_data : false;
    }

    // Get
    public function get_free_training_reg_by_id($selected_id) {
        global $db_handle;

        $query = "SELECT ftc.free_training_campaign_id, CONCAT(ftc.last_name, SPACE(1), ftc.first_name) AS full_name, ftc.email, ftc.phone,
                ftc.training_interest, ftc.training_centre, ftc.created, s.alias AS main_state, u.user_code, ftc.last_name, ftc.first_name
                FROM free_training_campaign AS ftc
                LEFT JOIN state AS s ON ftc.state_id = s.state_id
                LEFT JOIN user AS u ON u.email = ftc.email
                WHERE free_training_campaign_id = $selected_id LIMIT 1";
        $result = $db_handle->runQuery($query);
        $fetched_data = $db_handle->fetchAssoc($result);

        return $fetched_data ? $fetched_data : false;
    }

    public function update_free_training_registration($selected_id, $training_email, $training_phone, $training_first_name, $training_last_name, $comment, $admin_unique_code, $state = '', $add_ifx_account = '', $client_user_code = '') {
        global $db_handle;

        if(!empty($state)) {
            $query = "UPDATE free_training_campaign SET state_id = '$state' WHERE free_training_campaign_id = $selected_id";
            $db_handle->runQuery($query);
        }

        if(!empty($comment)) {
            $query = "INSERT INTO free_training_campaign_comment (training_campaign_id, admin_code, comment) VALUES ($selected_id, '$admin_unique_code', '$comment')";
            $db_handle->runQuery($query);
        }

        if(!empty($client_user_code)) {
            if(!empty($add_ifx_account)) {
                $query = "INSERT INTO user_ifxaccount (user_code, ifx_acct_no) VALUES ('$client_user_code', '$add_ifx_account')";
                $db_handle->runQuery($query);
                $ifxaccount_id = $db_handle->insertedId();

                $query = "INSERT INTO user_ilpr_enrolment (ifxaccount_id) VALUES ($ifxaccount_id)";
                $db_handle->runQuery($query);
            }
        } else {
            if(!empty($add_ifx_account)) {
                usercode:
                $user_code = rand_string(11);
                if($db_handle->numRows("SELECT user_code FROM user WHERE user_code = '$user_code'") > 0) { goto usercode; };

                $pass_salt = hash("SHA256", "$user_code");

                $query = "INSERT INTO user (user_code, email, pass_salt, first_name, last_name, phone) VALUES ('$user_code', '$training_email', '$pass_salt', '$training_first_name', '$training_last_name', '$training_phone')";
                $db_handle->runQuery($query);

                $client_operation = new clientOperation();
                $client_operation->send_welcome_email($training_last_name, $training_email);

                $query = "INSERT INTO user_ifxaccount (user_code, ifx_acct_no) VALUES ('$user_code', '$add_ifx_account')";
                $db_handle->runQuery($query);
                $ifxaccount_id = $db_handle->insertedId();

                $query = "INSERT INTO user_ilpr_enrolment (ifxaccount_id) VALUES ($ifxaccount_id)";
                $db_handle->runQuery($query);

            }
        }

        return true;
    }

    public function get_free_training_registration_comment($selected_id) {
        global $db_handle;

        $query = "SELECT fctcc.comment, fctcc.created, CONCAT(a.last_name, SPACE(1), a.first_name) AS admin_name
                  FROM free_training_campaign_comment AS fctcc
                  INNER JOIN admin AS a ON fctcc.admin_code = a.admin_code
                  WHERE fctcc.training_campaign_id = $selected_id ORDER BY fctcc.created DESC";
        $result = $db_handle->runQuery($query);
        $fetched_data = $db_handle->fetchAssoc($result);

        return $fetched_data ? $fetched_data : false;
    }

    public function get_sales_comment($user_code) {
        global $db_handle;

        $query = "SELECT scc.comment, scc.created, CONCAT(a.last_name, SPACE(1), a.first_name) AS admin_name
                  FROM sales_contact_comment AS scc
                  INNER JOIN admin AS a ON scc.admin_code = a.admin_code
                  WHERE scc.user_code = '$user_code' ORDER BY scc.created DESC";
        $result = $db_handle->runQuery($query);
        $fetched_data = $db_handle->fetchAssoc($result);

        return $fetched_data ? $fetched_data : false;
    }

    public function set_sales_comment($selected_id, $comment, $admin_unique_code) {
        global $db_handle;

        $query = "INSERT INTO sales_contact_comment (user_code, admin_code, comment) VALUES ('$selected_id', '$admin_unique_code', '$comment')";
        $db_handle->runQuery($query);

        return true;
    }

    // Add a new bulletin
    public function add_new_snappy_help($snappy_no, $content, $snappy_status = '3', $admin_code) {
        global $db_handle;

        if(!empty($snappy_no)) {
            $query = "UPDATE snappy_help SET content = '$content', status = '$snappy_status' WHERE snappy_help_id = $snappy_no";
        } else {
            $query = "INSERT INTO snappy_help (admin_code, content, status) VALUES ('$admin_code', '$content', '$snappy_status')";
        }

        $db_handle->runQuery($query);

        return $db_handle->affectedRows() > 0 ? true : false;
    }

    // Add a new campaign
    public function add_new_campaign_email($campaign_email_no, $sender, $subject, $campaign_category, $content, $admin_code, $campaign_email_status = '1') {
        global $db_handle;

        if(!empty($campaign_email_no))
        {
            $query = "UPDATE campaign_email SET campaign_category_id = '$campaign_category', sender = '$sender', subject = '$subject', content = '$content', status = '$campaign_email_status' WHERE campaign_email_id = $campaign_email_no LIMIT 1";
        }
        else
        {
            $query = "INSERT INTO campaign_email (admin_code, campaign_category_id, subject, sender, content, status) VALUES ('$admin_code', $campaign_category, '$subject', '$sender', '$content', '$campaign_email_status')";
        }
        $db_handle->runQuery($query);
        return $db_handle->affectedRows() > 0 ? true : false;
    }

    // Delete campaign email by id
    public function delete_campaign_by_id($campaign_email_id) {
        global $db_handle;

        //Delete campaign
        $db_handle->runQuery("DELETE FROM campaign_email WHERE campaign_email_id = $campaign_email_id LIMIT 1");

        return true;
    }

    // Get campaign email by id
    public function get_campaign_email_by_id($campaign_email_id) {
        global $db_handle;

        $query = "SELECT * FROM campaign_email WHERE campaign_email_id = $campaign_email_id LIMIT 1";
        $result = $db_handle->runQuery($query);
        $fetched_data = $db_handle->fetchAssoc($result);
        $fetched_data = $fetched_data[0];

        return $fetched_data ? $fetched_data : false;
    }

    // Add a new bulletin
    public function add_new_campaign_sms($campaign_sms_no, $campaign_category, $content, $admin_code, $campaign_sms_status = '1') {
        global $db_handle;

        if(!empty($campaign_sms_no)) {
            $query = "UPDATE campaign_sms SET campaign_category_id = '$campaign_category', content = '$content', status = '$campaign_sms_status' WHERE campaign_sms_id = $campaign_sms_no LIMIT 1";
        } else {
            $query = "INSERT INTO campaign_sms (admin_code, campaign_category_id, content, status) VALUES ('$admin_code', $campaign_category, '$content', '$campaign_sms_status')";
        }

        $db_handle->runQuery($query);

        return $db_handle->affectedRows() > 0 ? true : false;
    }

    // Get campaign email by id
    public function get_campaign_sms_by_id($campaign_sms_id) {
        global $db_handle;

        $query = "SELECT * FROM campaign_sms WHERE campaign_sms_id = $campaign_sms_id LIMIT 1";
        $result = $db_handle->runQuery($query);
        $fetched_data = $db_handle->fetchAssoc($result);
        $fetched_data = $fetched_data['0'];

        return $fetched_data ? $fetched_data : false;
    }

    // Get snappy help by id
    public function get_snappy_help_by_id($snappy_id) {
        global $db_handle;

        $query = "SELECT * FROM snappy_help WHERE snappy_help_id = $snappy_id LIMIT 1";
        $result = $db_handle->runQuery($query);
        $fetched_data = $db_handle->fetchAssoc($result);

        return $fetched_data ? $fetched_data : false;
    }

    // Get campaign category by id
    public function get_campaign_category_by_id($campaign_id) {
        global $db_handle;

        $query = "SELECT * FROM campaign_category WHERE campaign_category_id = $campaign_id LIMIT 1";
        $result = $db_handle->runQuery($query);
        $fetched_data = $db_handle->fetchAssoc($result);

        return $fetched_data ? $fetched_data : false;
    }

    // Get campaign category by id
    public function get_account_flag_by_id($account_flag_id) {
        global $db_handle;

        $query = "SELECT uaf.user_account_flag_id, uaf.comment, uaf.status, ui.ifx_acct_no
                FROM user_account_flag AS uaf
                INNER JOIN user_ifxaccount AS ui ON uaf.ifxaccount_id = ui.ifxaccount_id
                WHERE uaf.user_account_flag_id = $account_flag_id LIMIT 1";

        $result = $db_handle->runQuery($query);
        $fetched_data = $db_handle->fetchAssoc($result);

        return $fetched_data ? $fetched_data : false;
    }

    // Confirm that the commission is not for a previously uploaded date
    public function commission_upload_duplicate($commission_date) {
        global $db_handle;

        $query = "SELECT date_earned FROM trading_commission WHERE date_earned = '$commission_date'";
        $result = $db_handle->numRows($query);

        return $result ? true : false;
    }

    // Get system message by id
    public function get_system_message_by_id($message_id) {
        global $db_handle;

        $query = "SELECT * FROM system_message WHERE system_message_id = $message_id LIMIT 1";
        $result = $db_handle->runQuery($query);
        $fetched_data = $db_handle->fetchAssoc($result);

        if($fetched_data) {
            return $fetched_data;
        } else {
            return false;
        }
    }

    // Schedule a campaign to be sent - cron jobs will take over from here
    public function schedule_campaign($campaign_id, $campaign_type) {
        global $db_handle;

        if($campaign_type == 'email') {
            $main_table = "campaign_email";
            $track_table = "campaign_email_track";

            // get recipient query
            $query = "SELECT campaign_category_id FROM campaign_email WHERE campaign_email_id = $campaign_id LIMIT 1";
            $result = $db_handle->runQuery($query);
            $fetched_data = $db_handle->fetchAssoc($result);
            $category_id = $fetched_data[0]['campaign_category_id'];

            $query = "SELECT client_group FROM campaign_category WHERE campaign_category_id = $category_id LIMIT 1";
            $result = $db_handle->runQuery($query);
            $fetched_data = $db_handle->fetchAssoc($result);
            $client_group = $fetched_data[0]['client_group'];

            $recipient_query = $db_handle->sanitizePost(client_group_query($client_group, 1));
            $total_recipient = $db_handle->numRows(stripslashes($recipient_query));

            // schedule campaign
            $query = "INSERT INTO $track_table (campaign_id, recipient_query, total_recipient) VALUES ('$campaign_id', '$recipient_query', $total_recipient)";
            $db_handle->runQuery($query);

            // log send date
            $query = "UPDATE $main_table SET send_status = '1', send_date = NOW(), status = '5' WHERE campaign_email_id = $campaign_id LIMIT 1";
            $db_handle->runQuery($query);
        }

        if($campaign_type == 'sms') {
            $main_table = "campaign_sms";
            $track_table = "campaign_sms_track";

            // get recipient query
            $query = "SELECT campaign_category_id FROM campaign_sms WHERE campaign_sms_id = $campaign_id LIMIT 1";
            $result = $db_handle->runQuery($query);
            $fetched_data = $db_handle->fetchAssoc($result);
            $category_id = $fetched_data[0]['campaign_category_id'];

            $query = "SELECT client_group FROM campaign_category WHERE campaign_category_id = $category_id LIMIT 1";
            $result = $db_handle->runQuery($query);
            $fetched_data = $db_handle->fetchAssoc($result);
            $client_group = $fetched_data[0]['client_group'];

            $recipient_query = $db_handle->sanitizePost(client_group_query($client_group, 2));
            $total_recipient = $db_handle->numRows(stripslashes($recipient_query));

            // schedule campaign
            $query = "INSERT INTO $track_table (campaign_id, recipient_query, total_recipient) VALUES ('$campaign_id', '$recipient_query', $total_recipient)";
            $db_handle->runQuery($query);

            // log send date
            $query = "UPDATE $main_table SET send_status = '1', send_date = NOW(), status = '5' WHERE campaign_sms_id = $campaign_id LIMIT 1";
            $db_handle->runQuery($query);
        }

        return true;
    }

    // generate a unique user code
    public function generate_user_code() {
        global $db_handle;

        unique_user_code:
        $user_code = rand_string(11);
        if($db_handle->numRows("SELECT user_code FROM user WHERE user_code = '$user_code'") > 0) { goto unique_user_code; };

        return $user_code;
    }

    // generate a unique partner code
    public function generate_partner_code() {
        global $db_handle;

        unique_partner_code:
        $partner_code = strtoupper(rand_string(5));
        if($db_handle->numRows("SELECT partner_code FROM partner WHERE partner_code = '$partner_code'") > 0) { goto unique_partner_code; };

        return $partner_code;
    }

    public function get_client_distribution() {
        global $db_handle;

        $total_client = $db_handle->numRows("SELECT user_code FROM user");
        $unverified = $db_handle->numRows("SELECT user_code FROM user WHERE password IS NULL");
//        $level_one = $db_handle->numRows("SELECT user_code FROM user");
//        $level_two = $db_handle->numRows("SELECT user_code FROM user");
//        $level_three = $db_handle->numRows("SELECT user_code FROM user");


        $fetched_data = array('total_client' => $total_client, 'unverified' => $unverified);
        return $fetched_data;
    }

    function valid_ifxacct($ifx_acct) {
        global $db_handle;
        $query = "SELECT * FROM user_ifxaccount WHERE ifx_acct_no = '$ifx_acct' LIMIT 1";
        if($db_handle->numRows($query) > 0)
        {
            return true;
        }
        else
        {
            return false;
        }
    }

    // Create new campaign category
    public function add_new_campaign_category($title, $description, $campaign_category_status, $campaign_category_no, $client_group) {
        global $db_handle;

        if(!empty($campaign_category_no)) {
            $query = "UPDATE campaign_category SET title = '$title', description = '$description', status = '$campaign_category_status', client_group = '$client_group' WHERE campaign_category_id = $campaign_category_no";
        } else {
            $query = "INSERT INTO campaign_category (title, description, status, client_group) VALUES ('$title', '$description', '$campaign_category_status', '$client_group')";
        }

        $db_handle->runQuery($query);

        return $db_handle->affectedRows() > 0 ? true : false;
    }

    // Get all the campaign categories
    public function get_all_campaign_category() {
        global $db_handle;

        $query = "SELECT * FROM campaign_category WHERE status = '1' ORDER BY created DESC";
        $result = $db_handle->runQuery($query);
        $fetched_data = $db_handle->fetchAssoc($result);

        return $fetched_data ? $fetched_data : false;
    }

    // Create new campaign solo group
    public function add_new_campaign_solo_group($group_name, $group_no) {
        global $db_handle;

        if(!empty($group_no)) {
            $query = "UPDATE campaign_email_solo_group SET group_name = '$group_name' WHERE campaign_email_solo_group_id = $group_no";
        } else {
            $query = "INSERT INTO campaign_email_solo_group (group_name) VALUES ('$group_name')";
        }

        $db_handle->runQuery($query);

        return $db_handle->affectedRows() > 0 ? true : false;
    }

    // Get campaign category by id
    public function get_campaign_solo_group_by_id($solo_group_id) {
        global $db_handle;

        $query = "SELECT * FROM campaign_email_solo_group WHERE campaign_email_solo_group_id = $solo_group_id LIMIT 1";
        $result = $db_handle->runQuery($query);
        $fetched_data = $db_handle->fetchAssoc($result);

        return $fetched_data ? $fetched_data : false;
    }

    // Add a new bulletin
    public function add_new_solo_campaign_email($campaign_email_no, $subject, $solo_group, $send_day, $content, $admin_code, $solo_campaign_email_status = '1') {
        global $db_handle;

        if(!empty($campaign_email_no)) {
            $query = "UPDATE campaign_email_solo SET solo_group = '$solo_group', subject = '$subject', content = '$content', status = '$solo_campaign_email_status', day_to_send = $send_day WHERE campaign_email_solo_id = $campaign_email_no LIMIT 1";
        } else {
            $query = "INSERT INTO campaign_email_solo (admin_code, solo_group, subject, content, status, day_to_send) VALUES ('$admin_code', $solo_group, '$subject', '$content', '$solo_campaign_email_status', $send_day)";
        }

        $db_handle->runQuery($query);

        return $db_handle->affectedRows() > 0 ? true : false;
    }

    // Get all the campaign categories
    public function get_all_campaign_solo_group() {
        global $db_handle;

        $query = "SELECT * FROM campaign_email_solo_group ORDER BY campaign_email_solo_group_id ASC";
        $result = $db_handle->runQuery($query);
        $fetched_data = $db_handle->fetchAssoc($result);

        return $fetched_data ? $fetched_data : false;
    }

    // Get campaign email by id
    public function get_solo_campaign_email_by_id($campaign_email_id) {
        global $db_handle;

        $query = "SELECT * FROM campaign_email_solo WHERE campaign_email_solo_id = $campaign_email_id LIMIT 1";
        $result = $db_handle->runQuery($query);
        $fetched_data = $db_handle->fetchAssoc($result);

        return $fetched_data ? $fetched_data : false;
    }

    public function get_latest_funding( $user_code = '' ) {
        global $db_handle;

        if(!empty($user_code)) {
            $query = "SELECT ud.trans_id, ud.dollar_ordered, ud.status, ui.ifx_acct_no, ud.created, CONCAT(u.last_name, SPACE(1), u.first_name) AS full_name
              FROM user_deposit AS ud
              INNER JOIN user_ifxaccount AS ui ON ud.ifxaccount_id = ui.ifxaccount_id
              INNER JOIN user AS u ON ui.user_code = u.user_code
              WHERE ud.status <> '1' AND u.user_code = '$user_code' ORDER BY ud.created DESC LIMIT 10";
        } else {
            $query = "SELECT ud.trans_id, ud.dollar_ordered, ud.status, ui.ifx_acct_no, ud.created, CONCAT(u.last_name, SPACE(1), u.first_name) AS full_name
              FROM user_deposit AS ud
              INNER JOIN user_ifxaccount AS ui ON ud.ifxaccount_id = ui.ifxaccount_id
              INNER JOIN user AS u ON ui.user_code = u.user_code
              WHERE ud.status <> '1' ORDER BY ud.created DESC LIMIT 10";
        }

        $result = $db_handle->runQuery($query);
        $fetched_data = $db_handle->fetchAssoc($result);

        return $fetched_data ? $fetched_data : false;
    }

    public function get_all_funding($user_code)
    {
        global $db_handle;
        $query = "SELECT ud.trans_id, ud.dollar_ordered, ud.status, ui.ifx_acct_no, ud.created, CONCAT(u.last_name, SPACE(1), u.first_name) AS full_name
              FROM user_deposit AS ud
              INNER JOIN user_ifxaccount AS ui ON ud.ifxaccount_id = ui.ifxaccount_id
              INNER JOIN user AS u ON ui.user_code = u.user_code
              WHERE ud.status <> '1' AND u.user_code = '$user_code' ORDER BY ud.created ASC ";
        $result = $db_handle->runQuery($query);
        $fetched_data = $db_handle->fetchAssoc($result);
        return $fetched_data ? $fetched_data : false;
    }

    public function get_sum_client_completed_funding($user_code) {
        global $db_handle;
        $query = "SELECT SUM(ud.real_dollar_equivalent) AS total_dollar, SUM(ud.real_naira_confirmed) AS total_naira
              FROM user_deposit AS ud
              INNER JOIN user_ifxaccount AS ui ON ud.ifxaccount_id = ui.ifxaccount_id
              INNER JOIN user AS u ON ui.user_code = u.user_code
              WHERE ud.status = '8' AND u.user_code = '$user_code' ORDER BY ud.created ASC ";
        $result = $db_handle->runQuery($query);
        $fetched_data = $db_handle->fetchAssoc($result);
        $funding_sum = $fetched_data[0];
        return $funding_sum ? $funding_sum : false;
    }

    public function get_client_completed_funding($user_code) {
        global $db_handle;
        $query = "SELECT ud.trans_id, ud.dollar_ordered, ud.created, ud.naira_total_payable, ud.real_dollar_equivalent, ud.real_naira_confirmed,
                    ud.client_naira_notified, ud.client_pay_date, ud.client_reference, ud.client_pay_method,
                    ud.client_notified_date, ud.status AS deposit_status, ud.points_claimed_id, u.user_code,
                    ui.ifx_acct_no, CONCAT(u.last_name, SPACE(1), u.first_name) AS full_name, u.phone,
                    uc.passport, ui.ifxaccount_id, ud.updated, ud.order_complete_time, pbc.dollar_amount AS points_dollar_value,
                    STR_TO_DATE(ud.created, '%Y-%m-%d') AS strip_date
                  FROM user_deposit AS ud
                  INNER JOIN user_ifxaccount AS ui ON ud.ifxaccount_id = ui.ifxaccount_id
                  INNER JOIN user AS u ON ui.user_code = u.user_code
                  LEFT JOIN user_credential AS uc ON ui.user_code = uc.user_code
                  LEFT JOIN point_based_claimed AS pbc ON ud.points_claimed_id = pbc.point_based_claimed_id
                  WHERE ud.status = '8' AND u.user_code = '$user_code' ORDER BY ud.created ASC ";
        $result = $db_handle->runQuery($query);
        $fetched_data = $db_handle->fetchAssoc($result);

        return $fetched_data ? $fetched_data : false;
    }

    public function get_latest_withdrawal( $user_code = '' ) {
        global $db_handle;

        if(!empty($user_code)) {
            $query = "SELECT uw.trans_id, uw.dollar_withdraw, uw.status, ui.ifx_acct_no, uw.created, CONCAT(u.last_name, SPACE(1), u.first_name) AS full_name
              FROM user_withdrawal AS uw
              INNER JOIN user_ifxaccount AS ui ON uw.ifxaccount_id = ui.ifxaccount_id
              INNER JOIN user AS u ON ui.user_code = u.user_code
              WHERE u.user_code = '$user_code'
              ORDER BY uw.created DESC LIMIT 10";
        } else {
            $query = "SELECT uw.trans_id, uw.dollar_withdraw, uw.status, ui.ifx_acct_no, uw.created, CONCAT(u.last_name, SPACE(1), u.first_name) AS full_name
              FROM user_withdrawal AS uw
              INNER JOIN user_ifxaccount AS ui ON uw.ifxaccount_id = ui.ifxaccount_id
              INNER JOIN user AS u ON ui.user_code = u.user_code
              ORDER BY uw.created DESC LIMIT 10";
        }

        $result = $db_handle->runQuery($query);
        $fetched_data = $db_handle->fetchAssoc($result);

        return $fetched_data ? $fetched_data : false;
    }

    public function get_sum_client_completed_withdrawal($user_code) {
        global $db_handle;
        $query = "SELECT SUM(uw.dollar_withdraw) AS total_dollar, SUM(uw.naira_total_withdrawable) AS total_naira
              FROM user_withdrawal AS uw
              INNER JOIN user_ifxaccount AS ui ON uw.ifxaccount_id = ui.ifxaccount_id
              INNER JOIN user AS u ON ui.user_code = u.user_code
              WHERE uw.status = '10' AND u.user_code = '$user_code' ORDER BY uw.created ASC ";
        $result = $db_handle->runQuery($query);
        $fetched_data = $db_handle->fetchAssoc($result);
        $funding_sum = $fetched_data[0];
        return $funding_sum ? $funding_sum : false;
    }

    public function get_client_completed_withdrawal($user_code) {
        global $db_handle;
        $query = "SELECT uw.trans_id, uw.dollar_withdraw, uw.created, uw.naira_total_withdrawable,
                uw.client_phone_password, uw.status AS withdrawal_status,
                CONCAT(u.last_name, SPACE(1), u.first_name) AS full_name, u.phone,
                uc.passport, ui.ifxaccount_id, ui.ifx_acct_no, uw.updated, STR_TO_DATE(uw.created, '%Y-%m-%d') AS strip_date
                FROM user_withdrawal AS uw
                INNER JOIN user_ifxaccount AS ui ON uw.ifxaccount_id = ui.ifxaccount_id
                INNER JOIN user AS u ON ui.user_code = u.user_code
                LEFT JOIN user_credential AS uc ON ui.user_code = uc.user_code
                WHERE uw.status = '10' AND u.user_code = '$user_code' ORDER BY uw.created ASC ";
        $result = $db_handle->runQuery($query);
        $fetched_data = $db_handle->fetchAssoc($result);

        return $fetched_data ? $fetched_data : false;
    }

    public function get_failed_sms_code() {
        global $db_handle;

        $query = "SELECT u.last_name
                FROM user_verification AS uv
                INNER JOIN user AS u ON u.user_code = uv.user_code
                WHERE uv.user_code = u.user_code
                AND u.password IS NULL ORDER BY uv.created DESC";

        $result = $db_handle->runQuery($query);
        $fetched_data = $db_handle->fetchAssoc($result);

        return $fetched_data ? $fetched_data : false;
    }

    public function get_latest_bulletin() {
        global $db_handle;

        $query = "SELECT ab.allowed_admin, ab.admin_bulletin_id, ab.title, ab.created, CONCAT(a.last_name, SPACE(1), a.first_name) AS bulletin_author
              FROM admin_bulletin AS ab
              INNER JOIN admin AS a ON ab.admin_code = a.admin_code
              WHERE ab.status = '1' ORDER BY ab.created DESC LIMIT 5";

        $result = $db_handle->runQuery($query);
        $fetched_data = $db_handle->fetchAssoc($result);

        return $fetched_data ? $fetched_data : false;
    }

    // ALGORITHM FOR ACTIVE TRAINING CLIENTS
    public function get_total_active_training_clients() {
        global $db_handle;

        $from_date = date('Y-m-d', strtotime('today - 30 days'));
        $to_date = date('Y-m-d');
        $active_client_funding = ACTIVE_CLIENT_FUNDING;
        $active_client_volume = (ACTIVE_CLIENT_FUNDING * 100) / (ACTIVE_CLIENT_VOLUME * 100);

        $query = "SELECT final_sum_value
                      FROM (
                        SELECT SUM(sum_value) AS final_sum_value, email
                          FROM (
                              SELECT SUM(td.volume * $active_client_volume) AS sum_value, u.email
                              FROM trading_commission AS td
                              INNER JOIN user_ifxaccount AS ui ON td.ifx_acct_no = ui.ifx_acct_no
                              INNER JOIN user AS u ON ui.user_code = u.user_code
                              INNER JOIN free_training_campaign AS ftc ON u.email = ftc.email
                              WHERE td.date_earned BETWEEN '$from_date' AND '$to_date'
                              GROUP BY u.email

                              UNION ALL

                              SELECT SUM(ud.real_dollar_equivalent) AS sum_value, u.email
                              FROM user_deposit AS ud
                              INNER JOIN user_ifxaccount AS ui ON ud.ifxaccount_id = ui.ifxaccount_id
                              INNER JOIN user AS u ON ui.user_code = u.user_code
                              INNER JOIN free_training_campaign AS ftc ON u.email = ftc.email
                              WHERE (STR_TO_DATE(ud.created, '%Y-%m-%d') BETWEEN '$from_date' AND '$to_date')
                              AND ud.status = '8'
                              GROUP BY u.email
                          ) src GROUP BY email
                      ) src2 WHERE final_sum_value >= $active_client_funding";

        $active_client = $db_handle->numRows($query);
        return $active_client ? $active_client : '0';

    }

    // ALGORITHM FOR ACTIVE PROSPECT CLIENTS
    public function get_total_active_prospect_clients() {
        global $db_handle;

        $from_date = date('Y-m-d', strtotime('today - 30 days'));
        $to_date = date('Y-m-d');
        $active_client_funding = ACTIVE_CLIENT_FUNDING;
        $active_client_volume = (ACTIVE_CLIENT_FUNDING * 100) / (ACTIVE_CLIENT_VOLUME * 100);

        $query = "SELECT final_sum_value
                      FROM (
                        SELECT SUM(sum_value) AS final_sum_value, email
                          FROM (
                              SELECT SUM(td.volume * $active_client_volume) AS sum_value, u.email
                              FROM trading_commission AS td
                              INNER JOIN user_ifxaccount AS ui ON td.ifx_acct_no = ui.ifx_acct_no
                              INNER JOIN user AS u ON ui.user_code = u.user_code
                              INNER JOIN prospect_biodata AS pb ON u.email = pb.email_address
                              WHERE td.date_earned BETWEEN '$from_date' AND '$to_date'
                              GROUP BY u.email

                              UNION ALL

                              SELECT SUM(ud.real_dollar_equivalent) AS sum_value, u.email
                              FROM user_deposit AS ud
                              INNER JOIN user_ifxaccount AS ui ON ud.ifxaccount_id = ui.ifxaccount_id
                              INNER JOIN user AS u ON ui.user_code = u.user_code
                              INNER JOIN prospect_biodata AS pb ON u.email = pb.email_address
                              WHERE (STR_TO_DATE(ud.created, '%Y-%m-%d') BETWEEN '$from_date' AND '$to_date')
                              AND ud.status = '8'
                              GROUP BY u.email
                          ) src GROUP BY email
                      ) src2 WHERE final_sum_value >= $active_client_funding";

        $active_client = $db_handle->numRows($query);
        return $active_client ? $active_client : '0';

    }


    /**
     * ALGORITHM FOR ACTIVE CLIENTS
     *
     * An active client is someone who's funding and trading activity in the last 30 days is worth $50 and above.
     *
     * Client's traded volume can be equated to dollar using the formula 0.01 lots = 20 cents
     *
     */
    public function get_total_active_clients() {
        global $db_handle;

        $from_date = date('Y-m-d', strtotime('today - 30 days'));
        $to_date = date('Y-m-d');
        $active_client_funding = ACTIVE_CLIENT_FUNDING;
        $active_client_volume = (ACTIVE_CLIENT_FUNDING * 100) / (ACTIVE_CLIENT_VOLUME * 100);

        $query = "SELECT final_sum_value
                      FROM (
                        SELECT SUM(sum_value) AS final_sum_value, email
                          FROM (
                              SELECT SUM(td.volume * $active_client_volume) AS sum_value, u.email
                              FROM trading_commission AS td
                              INNER JOIN user_ifxaccount AS ui ON td.ifx_acct_no = ui.ifx_acct_no
                              INNER JOIN user AS u ON ui.user_code = u.user_code
                              WHERE td.date_earned BETWEEN '$from_date' AND '$to_date'
                              GROUP BY u.email

                              UNION ALL

                              SELECT SUM(ud.real_dollar_equivalent) AS sum_value, u.email
                              FROM user_deposit AS ud
                              INNER JOIN user_ifxaccount AS ui ON ud.ifxaccount_id = ui.ifxaccount_id
                              INNER JOIN user AS u ON ui.user_code = u.user_code
                              WHERE (STR_TO_DATE(ud.created, '%Y-%m-%d') BETWEEN '$from_date' AND '$to_date')
                              AND ud.status = '8'
                              GROUP BY u.email
                          ) src GROUP BY email
                      ) src2 WHERE final_sum_value >= $active_client_funding";

        $active_client = $db_handle->numRows($query);
        return $active_client ? $active_client : false;

    }

    /**
     * ALGORITHM FOR ACTIVE CLIENTS
     *
     * An active client is someone who's funding and trading activity in the last 30 days is worth $50 and above.
     *
     * Client's traded volume can be equated to dollar using the formula 0.01 lots = 20 cents
     *
     */
    public function get_total_active_accounts() {
        global $db_handle;

        $from_date = date('Y-m-d', strtotime('today - 30 days'));
        $to_date = date('Y-m-d');
        $active_client_funding = ACTIVE_CLIENT_FUNDING;
        $active_client_volume = (ACTIVE_CLIENT_FUNDING * 100) / (ACTIVE_CLIENT_VOLUME * 100);

        $query = "SELECT final_sum_value
                      FROM (
                        SELECT SUM(sum_value) AS final_sum_value, ifx_acct_no
                          FROM (
                              SELECT SUM(td.volume * $active_client_volume) AS sum_value, td.ifx_acct_no
                              FROM trading_commission AS td
                              WHERE td.date_earned BETWEEN '$from_date' AND '$to_date'
                              GROUP BY td.ifx_acct_no

                              UNION ALL

                              SELECT SUM(ud.real_dollar_equivalent) AS sum_value, ui.ifx_acct_no
                              FROM user_deposit AS ud
                              INNER JOIN user_ifxaccount AS ui ON ud.ifxaccount_id = ui.ifxaccount_id
                              WHERE (STR_TO_DATE(ud.created, '%Y-%m-%d') BETWEEN '$from_date' AND '$to_date')
                              AND ud.status = '8'
                              GROUP BY ui.ifx_acct_no
                          ) src GROUP BY ifx_acct_no
                      ) src2 WHERE final_sum_value >= $active_client_funding";

        $active_accounts = $db_handle->numRows($query);
        return $active_accounts ? $active_accounts : false;

    }

    public function get_total_accounts() {
        global $db_handle;
        $query = "SELECT ifx_acct_no FROM user_ifxaccount";
        return $db_handle->numRows($query);
    }

    public function get_total_clients() {
        global $db_handle;

        $query = "SELECT email FROM user";
        return $db_handle->numRows($query);
    }

    public function get_settings_by_id($settings_id) {
        global $db_handle;

        $query = "SELECT * FROM system_setting WHERE system_setting_id = $settings_id LIMIT 1";
        $result = $db_handle->runQuery($query);
        $fetched_data = $db_handle->fetchAssoc($result);
        $fetched_data = $fetched_data[0];

        return $fetched_data ? $fetched_data : false;
    }

    public function update_settings_by_id($set_id, $set_value) {
        global $db_handle;

        $query = "UPDATE system_setting SET value = '$set_value' WHERE system_setting_id = $set_id LIMIT 1";
        $db_handle->runQuery($query);

        if($db_handle->affectedRows() > 0) {
            return true;
        } else {
            return false;
        }
    }

    public function log_new_rates($effect_date, $deposit_ilpr, $deposit_nonilpr, $withdraw_rate) {
        global $db_handle;

        $query = "INSERT INTO exchange_rate_log (change_date, deposit_ilpr, deposit_nonilpr, withdraw) VALUES ('$effect_date', $deposit_ilpr, $deposit_nonilpr, $withdraw_rate)";
        $db_handle->runQuery($query);

        if($db_handle->affectedRows() > 0) {
            return true;
        } else {
            return false;
        }
    }

    public function next_account_officer()
    {
        global $db_handle;

        $query = "SELECT current_position, officer_count FROM account_officers_stopper";
        $result = $db_handle->runQuery($query);
        $fetched_data = $db_handle->fetchAssoc($result);
        $current_position = $fetched_data[0]['current_position'];
        $officer_count = $fetched_data[0]['officer_count'];

        if($current_position < $officer_count) {
            $query = "UPDATE account_officers_stopper SET current_position = current_position + 1";
            $db_handle->runQuery($query);
        } else {
            $query = "UPDATE account_officers_stopper SET current_position = 1";
            $db_handle->runQuery($query);
        }

        return $current_position ? $current_position : false;
    }

    public function unverified_email_campaign_log($email, $flag)
    {
        global $db_handle;
        $query = "INSERT INTO unverified_campaign_mail_log (email, email_flag) VALUES('$email', '$flag')";
        $db_handle->runQuery($query);

        if($db_handle->affectedRows() > 0) {
            return true;
        } else {
            return false;
        }
    }

    public function get_office_locations() {
        global $db_handle;

        $query = "SELECT * FROM office_location";
        $result = $db_handle->runQuery($query);
        $fetched_data = $db_handle->fetchAssoc($result);
        return $fetched_data;
    }

}

$system_object = new InstafxngSystem();