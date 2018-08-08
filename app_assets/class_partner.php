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
        global $db_handle;
        global $system_object;

        $partner_code = $this->generate_partner_code();
        $phone_code = generate_sms_code();

        $query  = "INSERT INTO partner (partner_code, first_name, middle_name, last_name, email_address, phone_number, full_address, city, state_id, phone_code) VALUES
                  ('$partner_code', '$first_name', '$middle_name', '$last_name', '$email', '$phone', '$address', '$city', $state_id, '$phone_code')";

        $result = $db_handle->runQuery($query);

        if($result) {
            $subject = 'Instafxng Partner Registration';
            $message_final = <<<MAIL
                    <div style="background-color: #F3F1F2">
                        <div style="max-width: 80%; margin: 0 auto; padding: 10px; font-size: 14px; font-family: Verdana;">
                            <img src="https://instafxng.com/images/ifxlogo.png" />
                            <hr />
                            <div style="background-color: #FFFFFF; padding: 15px; margin: 5px 0 5px 0;">

                                <p>Dear $first_name;</p>

                                <p>You have shown interest in the Instafxng Partnership System, an administrator is
                                moderating your application and you should get a response shortly.</p>

                                <p></p>


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

    public function get_selected_pending_application($partner_code) {
        global $db_handle;

        $query = "SELECT * FROM partner WHERE partner_code = '$partner_code' LIMIT 1";
        $result = $db_handle->runQuery($query);
        $found_application = $db_handle->fetchAssoc($result);
        $found_application = $found_application[0];

        return $found_application ? $found_application : false;
    }

    public function modify_partner_application($partner_code, $partner_status) {
        global $db_handle;

        if($partner_status == '2') { // Partner is approved, send password and welcome email
            // Generate password
            $new_password = random_password();
            $password_hash = password_hash($new_password, PASSWORD_DEFAULT);
            $query = "UPDATE partner SET status = '$partner_status', password = '$password_hash' WHERE partner_code = '$partner_code' LIMIT 1";

            // Send welcome email

        } elseif($partner_status == '3') {
            // Partner is not approved, send decline email
            $query = "UPDATE partner SET status = '$partner_status' WHERE partner_code = '$partner_code' LIMIT 1";

            // Send disapproval email

        }

        $db_handle->runQuery($query);

        if($db_handle->affectedRows() == 1) {
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
    
    // function to get referal details
    public function get_referalls($partner_code)
    {
        global $db_handle;
        
        $query = "SELECT * FROM user_ifxaccount INNER JOIN user USING(user_code) WHERE user_ifxaccount.partner_code = '$partner_code'";

        //echo $query;
        $result = $db_handle->runQuery($query);

        if($db_handle->numOfRows($result) > 0)
        {
            $all = array();
            while($referalls = $db_handle->fetchAssoc($result))
            {
                array_push($all, $referalls);
            }
            return $all;
        }
        else
            return NULL;
    }

    // calculate category A commision
    public function cat_a_commission()
    {
        global $db_handle;

        $query = "SELECT user_code, ifxaccount_id, user_ifxaccount.partner_code FROM partner INNER JOIN user_ifxaccount USING(user_code)";
        
        $result = $db_handle->runQuery($query);
        //echo $query;

        //print_r($result);
        
       // echo $result->num_rows;
        //break;

        if($result->num_rows > 0)
        {
            $yesterday = date("Y-m-d", strtotime( '-1 days' ) );
           
           $partners = $db_handle->fetchAssoc($result);

            //print_r($partners);
            // loop through partner row
            for ($i = 0; $i < count($partners); $i++)
            {
                $ifxaccount_id = $partners[$i]['ifxaccount_id'];;
                $user_code = $partners[$i]['user_code'];
                $partner_code = $partners[$i]['partner_code'];


                $query = "SELECT trans_id, SUM(naira_service_charge) AS charge, ifxaccount_id FROM user_deposit WHERE ifxaccount_id = '$ifxaccount_id' AND status = 8 AND DATE(client_notified_date) = '$yesterday'";

                $result_inner = $db_handle->runQuery($query);

                //echo $query;

                $get_result = $db_handle->fetchAssoc($result_inner);
                $service_charge = $get_result[0]['charge'];

                // due to the SUM() function used in our query, it will return a null value if no row is found
                if(!empty($service_charge))
                {

                    $service_charge_percentage = PARTNER_FIN_PERCENTAGE;

                    $service_charge = (PARTNER_FIN_PERCENTAGE / 100) * $service_charge;

                    $trans_id = $get_result[0]['trans_id'];

                    $balance = 0;

                    $query = "SELECT balance FROM partner_financial_activity_commission WHERE partner_code = '$partner_code' ORDER BY partner_financial_activity_commission_id DESC LIMIT 1";

                   // echo $query;
                    //break;

                    $result_balance = $db_handle->runQuery($query);

                    if($result_balance->num_rows != 0)
                    {
                        $get_result_balance = $db_handle->fetchAssoc($result_balance);

                        $balance = $get_result_balance[0]['balance'];
                    }

                    $balance = $balance + $service_charge;

                    // insert service charge 
                    $query = "INSERT INTO partner_financial_activity_commission (partner_code, reference_trans_id, amount, trans_type, balance, date) VALUES ('$partner_code','$trans_id', '$service_charge', 1, $balance, NOW())";

                    $result_insert = $db_handle->runQuery($query);

                    // update commission balance
                    $query = "SELECT balance FROM partner_balance WHERE partner_code = '$partner_code' AND type = 2";

                    //echo $query;

                    $result_bal = $db_handle->runQuery($query);

                    if($result_bal->num_rows > 0)
                    {
                        $query = "UPDATE partner_balance SET balance = '$balance', updated = NOW() WHERE partner_code = '$partner_code' AND type = 2";
                    }

                    else
                    {
                        $query = "INSERT INTO partner_balance (partner_code, type, balance) VALUES ('$partner_code', 2, '$balance')";
                    }
                    echo $query;
                    $result_update = $db_handle->runQuery($query);
                }
            }
        }
        else
            return NULL;
    }

    // category B cat_a_commission
    public function cat_b_commission()
    {
        global $db_handle;

        $query = "SELECT user_code, ifxaccount_id, user_ifxaccount.partner_code FROM partner INNER JOIN user_ifxaccount USING(user_code)";
        
        $result = $db_handle->runQuery($query);

        if($result->num_rows > 0)
        {
            $yesterday = date("Y-m-d", strtotime( '-1 days' ) );
            
            $partners = $db_handle->fetchAssoc($result);

            //print_r($partners);
            // loop through partner row
            for ($i = 0; $i < count($partners); $i++)
            {
                $ifxaccount_id = $partners[$i]['ifxaccount_id'];;
                $user_code = $partners[$i]['user_code'];
                $partner_code = $partners[$i]['partner_code'];


                $query = "SELECT trans_id, SUM(naira_service_charge) AS charge, ifxaccount_id FROM user_withdrawal WHERE ifxaccount_id = '$ifxaccount_id' AND status = 8 AND DATE(created) = '$yesterday'";

                //echo $query;

                $result_inner = $db_handle->runQuery($query);
                $get_result = $db_handle->fetchAssoc($result_inner);
                $service_charge = $get_result[0]['charge'];

                // due to the SUM() function used in our query, it will return a null value if no row is found
                if(!empty($service_charge))
                {
                    //echo $service_charge;

                    $service_charge_percentage = PARTNER_FIN_PERCENTAGE;

                    $service_charge = (PARTNER_FIN_PERCENTAGE / 100) * $service_charge;

                    $trans_id = $get_result[0]['trans_id'];

                    $balance = 0;

                    $query = "SELECT balance FROM partner_financial_activity_commission WHERE partner_code = '$partner_code' ORDER BY partner_financial_activity_commission_id DESC LIMIT 1";

                    $result_balance = $db_handle->runQuery($query);

                    if($result_balance->num_rows != 0)
                    {
                        $get_result_balance = $db_handle->fetchAssoc($result_balance);

                        $balance = $get_result_balance[0]['balance'];
                    }

                    $balance = $balance + $service_charge;

                    // insert service charge 
                    $query = "INSERT INTO partner_financial_activity_commission (partner_code, reference_trans_id, amount, trans_type, balance, date) VALUES ('$partner_code','$trans_id', '$service_charge', 2, $balance, NOW())";

                    $result_insert = $db_handle->runQuery($query);

                    // update commission balance
                    $query = "SELECT balance FROM partner_balance WHERE partner_code = '$partner_code' AND type = 2";

                    //echo $query;

                    $result_bal = $db_handle->runQuery($query);

                    if($result_bal->num_rows > 0)
                    {
                        $query = "UPDATE partner_balance SET balance = '$balance', updated = NOW() WHERE partner_code = '$partner_code' AND type = 2";
                    }

                    else
                    {
                        $query = "INSERT INTO partner_balance (partner_code, type, balance) VALUES ('$partner_code', 2, '$balance')";
                    }
                    $result_update = $db_handle->runQuery($query);
                }
            }
        }
        else
            return NULL;
    }

    // this will calculate trading commission for partners
    public function trading_commission() {
        global $db_handle;

        $query = "SELECT user_code, ifx_acct_no, user_ifxaccount.partner_code FROM partner INNER JOIN user_ifxaccount USING(user_code)";
        $result = $db_handle->runQuery($query);

        if($result->num_rows > 0) {
            $yesterday = date("Y-m-d", strtotime( '-1 days' ) );

            $partners = $db_handle->fetchAssoc($result);

            // loop through partner row
            for ($i = 0; $i < count($partners); $i++) {
                $ifxaccount_no = $partners[$i]['ifx_acct_no'];
                $user_code = $partners[$i]['user_code'];
                $partner_code = $partners[$i]['partner_code'];

                $query = "SELECT ifx_acct_no, trading_commission_id, SUM(commission) AS t_commission FROM trading_commission WHERE ifx_acct_no = '$ifxaccount_no' AND DATE(date_earned) = '$yesterday'";

                $result_inner = $db_handle->runQuery($query);
                $get_result = $db_handle->fetchAssoc($result_inner);
                $commission = $get_result[0]['t_commission'];

                // due to the SUM() function used in our query, it will return a null value if no row is found
                if(!empty($commission)) {
                    //print_r($result_inner);

                    $ifx_acct_no = $get_result[0]['ifx_acct_no'];
                    $tc_id = $get_result[0]['trading_commission_id'];
                    

                    //echo COMMISSION_PERCENTAGE;

                    $commission_percentage = COMMISSION_PERCENTAGE;

                    $commission = (COMMISSION_PERCENTAGE / 100) * $commission;

                    $balance = 0;

                    $query  = "SELECT balance FROM partner_trading_commission WHERE partner_code = '$partner_code' ORDER BY partner_trading_ommision_id DESC LIMIT 1";

                    $result_balance = $db_handle->fetchAssoc($result_balance);

                    if($result_balance->num_rows != 0) {
                        $get_result_balance = $db_handle->fetchAssoc($result_balance);
                        $balance = $get_result_balance[0]['balance'];
                    }

                    $balance = $balance + $commission;

                    // log commission
                    $query = "INSERT INTO partner_trading_commission (partner_code, amount, balance, date, reference_trans_id, status) VALUES ('$partner_code', '$commission', '$balance', NOW(), '$tc_id', 1)";
                    $result_insert = $db_handle->runQuery($query);

                    // update commission balance
                    $query = "SELECT balance FROM partner_balance WHERE partner_code = '$partner_code' AND type = 1";
                    $result_bal = $db_handle->runQuery($query);

                    if($result_bal->num_rows > 0)
                    {
                        $query = "UPDATE partner_balance SET balance = '$balance', updated = NOW() WHERE partner_code = '$partner_code' AND type = 1";
                    } else {
                        $query = "INSERT INTO partner_balance (partner_code, type, balance) VALUES ('$partner_code', 1, '$balance')";
                    }
                    $result_update = $db_handle->runQuery($query);
                }
            }
        } else {
            return NULL;
        }
    }

    public function add_bank_details($user_code, $acct_name, $acct_no, $bank_id) {
        global $db_handle;

        $query = "SELECT user_bank_id FROM user_bank WHERE user_code = '$user_code'";
        $result = $db_handle->runQUery($query);

        if ($result->num_rows == 0) {
            $query = "INSERT INTO user_bank (user_code, bank_acct_name, bank_acct_no, bank_id) VALUES ('$user_code', '$acct_name', '$acct_no', '$bank_id')";
        } else {
            $query = "UPDATE user_bank SET bank_acct_name = '$acct_name', bank_acct_no = '$acct_no', bank_id = '$bank_id' WHERE user_code = '$user_code'";
        }
        $result = $db_handle->runQuery($query);
        return $result;
    }

    public function view_financial_commission($partner_code) {
        global $db_handle;

        $query = "SELECT * FROM partner_financial_activity_commission WHERE partner_code = '$partner_code' ORDER BY partner_financial_activity_commission_id DESC";

        $result = $db_handle->runQuery($query);
        if($db_handle->numOfRows($result) > 0) {
            $all = array();
            while($referalls = $db_handle->fetchAssoc($result))
            {
                array_push($all, $referalls);
            }
            return $all;
        } else {
            return NULL;
        }

    }

    public function view_trading_commission($partner_code)
    {
        global $db_handle;

        $query = "SELECT * FROM partner_trading_commission WHERE partner_code = '$partner_code' ORDER BY partner_trading_commission_id DESC";
        $result = $db_handle->runQuery($query);

        if($db_handle->numOfRows($result) > 0) {
            $all = array();
            while($referalls = $db_handle->fetchAssoc($result)) {
                array_push($all, $referalls);
            }
            return $all;
        } else {
            return NULL;
        }

    }

    public function request_whitdrawal($partner_code, $account_id, $amount, $type,  $comment) {
        global $db_handle;

        $query = "INSERT INTO partner_payment (partner_code, account_id, amount, trans_type, comment) VALUES ('$partner_code', '$account_id', '$amount', 1, '$comment')";
        $result = $db_handle->runQuery($query);

        return $result ? true : false;
    }
}

$partner_object = new Partner();