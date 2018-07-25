<?php

//this class handles handles activities pertaining to a partner
class Partner {
    
    public function check_email_phone_duplicate($email, $phone) {
        global $db_handle;


    }

    public function authenticate($email = "", $password = "") {
        global $db_handle;
        $email = $db_handle->sanitizePost($email);

        $query = "SELECT pass_salt FROM user WHERE email = '$email' LIMIT 1";

        $result = $db_handle->runQuery($query);

        if($db_handle->numOfRows($result) == 1) {
            $user = $db_handle->fetchAssoc($result);
            $pass_salt = $user[0]['pass_salt'];
            $hashed_password = hash("SHA512", "$pass_salt.$password");

            $query = "SELECT * FROM user AS u LEFT JOIN partner AS p USING (user_code) "
                    . "WHERE (email = '$email' AND password = '$hashed_password') AND (p.user_code = u.user_code) "
                    . " LIMIT 1";
            $result = $db_handle->runQuery($query);

            if($db_handle->numOfRows($result) == 1) {
                $found_partner = $db_handle->fetchAssoc($result);
                return $found_partner;
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
    
    //this method is to register as a partner
    public function new_partner($first_name, $last_name, $middle_name, $email, $phone, $password) {
        global $db_handle;
        global $system_object;

        $client_operation = new clientOperation();
        
        // Check if supplied email is existing
        $query = "SELECT user_code, user_id, password FROM user WHERE email = '$email' LIMIT 1";
        $result = $db_handle->runQuery($query);
        if($db_handle->numOfRows($result) > 0) {
            // email existing, check if the user is already a partner
            $user_detail = $db_handle->fetchAssoc($result);
            $user_code = $user_detail[0]['user_code'];
            $user_id = $user_detail[0]['user_id'];
            $selected_password = $user_detail[0]['password'];
            
            $query = "SELECT user_code FROM partner WHERE user_code = '$user_code' LIMIT 1";
            $result = $db_handle->runQuery($query);
            
            if($db_handle->numOfRows($result) > 0) {
                // Partner exist
                return false;
            } else {
                $partner_code = $system_object->generate_partner_code();
                $query  = "INSERT INTO partner (partner_code, user_code, status, created) VALUES ('$partner_code', '$user_code', '1', NOW())";
                $result = $db_handle->runQuery($query);

                if($result) {
                    if(is_null($selected_password) || empty($selected_password)) {
                        // send verification email and sms
                        $this->partner_phone_email_verification($user_code, $email, $phone, $first_name);
                        return "Your registration has been completed, please check your email for activation instructions.";
                    } else {
                        return "You have been activated on the Partner system, you can now login with your email and your normal passcode.";
                    }
                } else {
                    return false;
                }

            }
        } else {
            // new client
            $user_code = $system_object->generate_user_code();
            $partner_code = $system_object->generate_partner_code();
            
            $pass_salt = hash("SHA256", "$user_code");
            $first_name = ucwords(strtolower(trim($first_name)));
            $last_name = ucwords(strtolower(trim($last_name)));
            $middle_name = ucwords(strtolower(trim($middle_name)));
            
            $query = "INSERT INTO user (user_code, email, pass_salt, first_name, last_name, middle_name, phone) VALUES ('$user_code', '$email', '$pass_salt',  '$first_name', '$last_name', '$middle_name', '$phone')";
            $db_handle->runQuery($query);
            
            $query  = "INSERT INTO partner (partner_code, user_code, created) VALUES ('$partner_code', '$user_code', NOW())";
            $db_handle->runQuery($query);

            $client_operation->send_verification_message($user_code, $email, $phone, $first_name, "");
            return "Your registration has been completed, please check your email for activation instructions.";
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
    public function trading_commission()
    {
        global $db_handle;

        $query = "SELECT user_code, ifx_acct_no, user_ifxaccount.partner_code FROM partner INNER JOIN user_ifxaccount USING(user_code)";

        //echo $query;
        
        $result = $db_handle->runQuery($query);

        //echo $result->num_rows;

        if($result->num_rows > 0)
        {
            $yesterday = date("Y-m-d", strtotime( '-1 days' ) );

            $partners = $db_handle->fetchAssoc($result);

            //print_r($partners);
            // loop through partner row
            for ($i = 0; $i < count($partners); $i++)
            {
                //print_r($partners);
                //echo $i;
                $ifxaccount_no = $partners[$i]['ifx_acct_no'];
                $user_code = $partners[$i]['user_code'];
                $partner_code = $partners[$i]['partner_code'];

               // echo $ifxaccount_no;


                $query = "SELECT ifx_acct_no, trading_commission_id, SUM(commission) AS t_commission FROM trading_commission WHERE ifx_acct_no = '$ifxaccount_no' AND DATE(date_earned) = '$yesterday'";

                //echo $query;

                $result_inner = $db_handle->runQuery($query);
                $get_result = $db_handle->fetchAssoc($result_inner);
                $commission = $get_result[0]['t_commission'];

                // due to the SUM() function used in our query, it will return a null value if no row is found
                if(!empty($commission))
                {           
                    //print_r($result_inner);

                    $ifx_acct_no = $get_result[0]['ifx_acct_no'];
                    $tc_id = $get_result[0]['trading_commission_id'];
                    

                    //echo COMMISSION_PERCENTAGE;

                    $commission_percentage = COMMISSION_PERCENTAGE;

                    $commission = (COMMISSION_PERCENTAGE / 100) * $commission;

                    $balance = 0;

                    $query  = "SELECT balance FROM partner_trading_commission WHERE partner_code = '$partner_code' ORDER BY partner_trading_ommision_id DESC LIMIT 1";

                    $result_balance = $db_handle->fetchAssoc($result_balance);

                    if($result_balance->num_rows != 0)
                    {
                        $get_result_balance = $db_handle->fetchAssoc($result_balance);

                        $balance = $get_result_balance[0]['balance'];
                    }

                    $balance = $balance + $commission;

                    // log commission
                    $query = "INSERT INTO partner_trading_commission (partner_code, amount, balance, date, reference_trans_id, status) VALUES ('$partner_code', '$commission', '$balance', NOW(), '$tc_id', 1)";

                    //echo $query;

                    

                    $result_insert = $db_handle->runQuery($query);

                    // update commission balance
                    $query = "SELECT balance FROM partner_balance WHERE partner_code = '$partner_code' AND type = 1";

                    //echo $query;

                    $result_bal = $db_handle->runQuery($query);

                    if($result_bal->num_rows > 0)
                    {
                        $query = "UPDATE partner_balance SET balance = '$balance', updated = NOW() WHERE partner_code = '$partner_code' AND type = 1";
                    }

                    else
                    {
                        $query = "INSERT INTO partner_balance (partner_code, type, balance) VALUES ('$partner_code', 1, '$balance')";
                    }
                    $result_update = $db_handle->runQuery($query);
                }
            }
        }
        else
            return NULL;
    }

    public function add_bank_details($user_code, $acct_name, $acct_no, $bank_id)
    {
        global $db_handle;

        $query = "SELECT user_bank_id FROM user_bank WHERE user_code = '$user_code'";
        $result = $db_handle->runQUery($query);

        if($result->num_rows == 0)

            $query = "INSERT INTO user_bank (user_code, bank_acct_name, bank_acct_no, bank_id) VALUES ('$user_code', '$acct_name', '$acct_no', '$bank_id')";
        
        else
            $query = "UPDATE user_bank SET bank_acct_name = '$acct_name', bank_acct_no = '$acct_no', bank_id = '$bank_id' WHERE user_code = '$user_code'";

        //echo 

        $result = $db_handle->runQuery($query);

        return $result;
    }

    public function view_financial_commission($partner_code)
    {
        global $db_handle;

        $query = "SELECT * FROM partner_financial_activity_commission WHERE partner_code = '$partner_code' ORDER BY partner_financial_activity_commission_id DESC";

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

    public function view_trading_commission($partner_code)
    {
        global $db_handle;

        $query = "SELECT * FROM partner_trading_commission WHERE partner_code = '$partner_code' ORDER BY partner_trading_commission_id DESC";
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

    public function request_whitdrawal($partner_code, $account_id, $amount, $type,  $comment)
    {
        global $db_handle;

        $query = "INSERT INTO partner_payment (partner_code, account_id, amount, trans_type, comment) VALUES ('$partner_code', '$account_id', '$amount', 1, '$comment')";

       // echo $query; 

        $result = $db_handle->runQuery($query);

        if($result)
            return true;
        else
            return false;
    }

    public function get_partner_by_partner_code($partner_code) {

        global $db_handle;



        $query = "SELECT * FROM partner WHERE partner_code = '$partner_code' LIMIT 1";

        $result = $db_handle->runQuery($query);

        $fetched_data = $db_handle->fetchAssoc($result);

        $fetched_data = $fetched_data[0];



        return $fetched_data;

    }
}

$partner_object = new Partner();