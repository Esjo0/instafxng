<?php
// If it's going to need the database, then it's 
// probably smart to require it before we start.
require_once(LIB_PATH.DS.'class_database.php');


//this class handles handles activities pertaining to a partner
class Partner {
    
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
    
	public function get_user_by_user_code($user_code) {
        global $db_handle;
        
        $query = "SELECT user_id, user_code, email, phone, password, first_name, middle_name, last_name, created FROM user WHERE user_code = '$user_code' LIMIT 1";
        $result = $db_handle->runQuery($query);
        $fetched_data = $db_handle->fetchAssoc($result);
        $fetched_data = $fetched_data[0];
        
        return $fetched_data;
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
                
                if(is_null($selected_password) || empty($selected_password)) {
                    // send verification email and sms
                    $this->partner_phone_email_verification($user_code, $email, $phone, $first_name);
                    return "Your registration has been completed, please check your email for activation instructions.";
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
            $result = $db_handle->runQuery($query);
            
            $query  = "INSERT INTO partner (partner_code, user_code, created) VALUES ('$partner_code', '$user_code', NOW())";
            $result = $db_handle->runQuery($query);
            
            $this->partner_phone_email_verification($user_code, $email, $phone, $first_name);
            return "Your registration has been completed, please check your email for activation instructions.";
        }
    }
    
    // Verify email and phone number of new partners
    public function partner_phone_email_verification($user_code, $email, $phone, $first_name) {
        global $db_handle;
        global $system_object;
        
        $user_code_encrypted = encrypt($user_code);
        $phone = trim(preg_replace('/[\s\t\n\r\s]+/', '', $phone));
        $sms_code = generate_sms_code();
        $sms_message = "Your activation code is: $sms_code. A message has been sent to your email, click the activation link in it and enter this code.";
        $sms_message = str_replace(" ", "+", $sms_message);
        file_get_contents("http://www.smslive247.com/http/index.aspx?cmd=sendmsg&sessionid=5b422f10-7b78-4631-9b98-a1c2e1872099&message=$sms_message&sender=INSTAFXNG&sendto=$phone&msgtype=0");
        
        if($db_handle->numRows("SELECT * FROM user_verification WHERE user_code = '$user_code' LIMIT 1") > 0) {
            $query = "UPDATE user_verification SET phone_code = '$sms_code' WHERE user_code = '$user_code' LIMIT 1";
            $db_handle->runQuery($query);
        } else {
            $query = "INSERT INTO user_verification (user_code, phone_code) VALUES ('$user_code', '$sms_code')";
            $db_handle->runQuery($query);
        }
        
        // Send activation link to email
        $subject = "Your Instafxng Partner Activation";
        $body = "
Dear " . $first_name . "

Please activate your partnership account, this activation will also give you
access to funding and withdrawal from your Instaforex account at www.instafxng.com

https://instafxng.com/partner/activate.php?uuc=$user_code_encrypted

You will be requested for the activation code sent to your phone.

Also, create a secured PASSCODE (4 - 8 characters) for yourself. The PASSCODE you
create will be requested any time you want to access the partner cabinet or anytime 
you want to make funding, withdrawal transactions

Thank you for using our services.    


Best Regards,
Instafxng Support
www.instafxng.com";
        
        $system_object->send_email($subject, $body, $email, $first_name);
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

    public function request_withdrawal($partner_code, $account_id, $amount, $type,  $comment)
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
	
	
	public function pay_partner($user_code,$partner_code, $account_id, $amount, $comment)
    {
        global $db_handle;
		$query = "INSERT INTO partner_payment (user_code,partner_code, account_id, amount, trans_type, comment) VALUES ('$user_code','$partner_code', '$account_id', '$amount', '$comment')";
        $result = $db_handle->runQuery($query);
		
		//to get new balance, take it back to dollar and subtract it
		$newacctbal = $acctbal - ($amount2pay/$withdrawrate);
		$query = "UPDATE partner_balance SET balance='$newacctbal' where partner_code='$partner_code'";
		$db_handle->runQuery($query);

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
	
    public function get_partner_by_user_code($user_code) {
        global $db_handle;
        
        $query = "SELECT * FROM partner WHERE user_code = '$user_code' LIMIT 1";
        $result = $db_handle->runQuery($query);
        $fetched_data = $db_handle->fetchAssoc($result);
        $fetched_data = $fetched_data[0];
        
        return $fetched_data;
    }	
	
	public function get_partner_payout_history_details($payout_id) {
        global $db_handle;
        
        $query = "SELECT * FROM partner WHERE partner_code = '$partner_code' LIMIT 1";
        $result = $db_handle->runQuery($query);
        $fetched_data = $db_handle->fetchAssoc($result);
        $fetched_data = $fetched_data[0];
        
        return $fetched_data;
    }
	
	public function get_partner_status($user_code) {
        global $db_handle;
        
        $query = "SELECT settlement_ifxaccount_id,status FROM partner WHERE user_code = '$user_code' LIMIT 1";
        $result = $db_handle->runQuery($query);
        $fetched_data = $db_handle->fetchAssoc($result);
        $fetched_data = $fetched_data[0];
        
        return $fetched_data;
    }
	
	public function update_partner_status($partner_code, $account_status) {
        global $db_handle;
        
        $query = "UPDATE partner SET status = '$account_status' WHERE partner_code = '$partner_code' LIMIT 1";
        $db_handle->runQuery($query);
        
        if($db_handle->affectedRows() > 0) {
            return true;
        } else {
            return false;
        }
    }
	
	public function setcommission_rate($com_rate) {
        global $db_handle;
        
        $query = "UPDATE system_setting SET value = '$com_rate' WHERE constant = 'COMMISSION_RATE' LIMIT 1";
        $db_handle->runQuery($query);
        
        if($db_handle->affectedRows() > 0) {
            return true;
        } else {
            return false;
        }
    }
	
	public function reward_details_by_period($partner_code,$month,$year) {
        global $db_handle;

		$query = "SELECT partner_trading_commission.partner_code as partner_code, SUM(IF(partfin.amount<0, 0.00, partfin.amount)) AS fin_comm, SUM(parttrad.amount) AS trad_comm, SUM(parttrad.amount+partfin.amount) AS total,MONTH(partfin.date) AS month,YEAR(partfin.date) AS year FROM partner_financial_activity_commission partfin,partner_trading_commission parttrad WHERE partfin.partner_code=parttrad.partner_code AND partfin.partner_code='$partner_code' AND MONTH(parttrad.date)='$month' AND YEAR(parttrad.date)='$year' AND MONTH(partfin.date)='$month' AND YEAR(partfin.date)='$year' GROUP by partner_code,MONTH(partfin.date),YEAR(parttrad.date) ";
        $result = $db_handle->runQuery($query);
        $fetched_data = $db_handle->fetchAssoc($result);

        return $fetched_data;
    }
	
	public function get_user_by_partner_code($partner_code) {
        global $db_handle;
        
        $query = "SELECT pt.partner_code as partner_code,us.user_id as user_id, us.user_code, us.email as email, us.phone as phone, us.password as password, us.first_name as first_name, us.middle_name as middle_name, us.last_name as last_name, us.created as created 
			FROM partner AS pt 
		    INNER JOIN user AS us ON pt.user_code=us.user_code
			WHERE pt.partner_code='$partner_code' LIMIT 1";
		
        $result = $db_handle->runQuery($query);
        $fetched_data = $db_handle->fetchAssoc($result);
        $fetched_data = $fetched_data[0];
        
        return $fetched_data;
    }
}

$partner_object = new Partner();