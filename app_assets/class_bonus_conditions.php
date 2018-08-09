<?php
class Bonus_Condition{
    /*Params
    @title = The title of the bonus condition
    @desc = A breif and concise description of the functions of the condition
    @type = The type of condition
    @extra = A one dimensional array containing all the extra details the condition needs to function
    @api = The name of the method that delivers scripting functionality for this condition
    @args = A one dimensional array containing all the method arguments
    @returns = A one dimensional array containing all the expected return values
    */
    public $BONUS_CONDITIONS = array(
        1 => array(
            'title' => 'New Account',
            'desc' => 'All participating Instaforex accounts must be new accounts.',
            'type' => 0,
            'extra' => array(),
            'api' => 'new_account_cond',
            'args' => array('bonus_account_id'),
            'returns' => array('status')
        ),
        2 => array(
            'title' => 'ILPR Account',
            'desc' => 'All participating Instaforex accounts must have been enrolled into the InstaFxNg Loyalty Rewards Program (ILPR).',
            'type' => 0,
            'extra' => array(),
            'api' => 'valid_ilpr_account_cond',
            'args' => array('bonus_account_id'),
            'returns' => array('status')
        ),
        3 => array(
            'title' => 'Verified Clients Only',
            'desc' => 'All participating Instaforex account holders must have completed the verification process on Instafxng.com.',
            'type' => 0,
            'extra' => array(),
            'api' => 'verified_client_cond',
            'args' => array('bonus_account_id'),
            'returns' => array('status', 'docs')
        ),
        4 => array(
            'title' => 'Bonus Expiry Condition (Case 2)',
            'desc' => 'Allocated bonuses will be withdrawn from any participating Instaforex account that have not traded 
            for the number of days stated below after the accounts last trade date.',
            'type' => 0,
            'extra' => array('Days'),
            'api' => 'bonus_expiry_case_2_cond',
            'args' => array('bonus_account_id'),
            'returns' => array('status', 'last_trade_date', 'days_diff')
        ),
        5 => array(
            'title' => 'Bonus Expiry Condition (Case 1)',
            'desc' => 'Allocated bonuses will be withdrawn from any participating Instaforex account that has not traded for the number of days stated below at a stretch.',
            'type' => 0,
            'extra' => array('Days'),
            'api' => 'bonus_expiry_case_1_cond',
            'args' => array('bonus_account_id'),
            'returns' => array('status', 'last_trade_date', 'days_diff')
        ),
        6 => array(
            'title' => 'Bonus Withdrawal Condition (Case 1)',
            'desc' => 'Funds can be withdrawn from participating instaforex accounts if the stated volume or more has been traded on the account.',
            'type' => 0,
            'extra' => array('Volume'),
            'api' => 'bonus_withdrawal_case_1_cond',
            'args' => array('bonus_account_id', 'min_trade_volume'),
            'returns' => array('status', 'total_volume')
        ),
        7 => array(
            'title' => 'Bonus Withdrawal Condition (Case 2)',
            'desc' => 'Only the stated percentage of the accounts equity balance can be withdrawn from participating Instaforex accounts.',
            'type' => 1,
            'extra' => array('Percentage'),
            'api' => 'bonus_withdrawal_case_2_cond',
            'args' => array('min_percent'),
            'returns' => array('status', 'msg')
        ),
        8 => array(
            'title' => 'Profit Withdrawal Condition (Case 3)',
            'desc' => 'Profits can be only be withdrawn from only participating instaforex accounts which have no open trade.',
            'type' => 1,
            'extra' => array(),
            'api' => 'profit_withdrawal_case_3_cond',
            'args' => array(),
            'returns' => array('status')
        ),
        9 => array(
            'title' => 'Profit Withdrawal Condition (Case 2)',
            'desc' => 'Participating Instaforex accounts must have a minimum of the stated equity balance after a withdrawal is deducted before its withdrawal orders can be approved.',
            'type' => 1,
            'extra' => array('Minimum_Amount'),
            'api' => 'profit_withdrawal_case_2_cond',
            'args' => array('min_amount'),
            'returns' => array('status', 'msg')
        ),
        10 => array(
            'title' => 'Profit Withdrawal Condition (Case 1)',
            'desc' => 'Only the stated percentage of the accounts equity balance can be withdrawn from participating Instaforex accounts.',
            'type' => 1,
            'extra' => array('Percentage'),
            'api' => 'profit_withdrawal_case_1_cond',
            'args' => array('min_percent'),
            'returns' => array('status', 'msg')
        )
    );

    public $CONDITIONS_TYPES = array(
        0 => 'Compulsory Condition',
        1 => 'Passive Condition'
    );

    public function get_condition_types(){
        $result = array();
        foreach ($this->CONDITIONS_TYPES as $key => $value) {
            $result[count($result) + 1] = $key;
        }return $result;
    }

    public function new_account_cond($bonus_account_id){
        global $db_handle;
        $query = "SELECT 
BA.ifx_account_id 
FROM bonus_accounts AS BA
WHERE 
BA.ifx_account_id NOT IN (
    SELECT UD.trans_id FROM bonus_accounts AS BA INNER JOIN user_deposit AS UD ON BA.ifx_account_id = UD.ifxaccount_id 
)
AND BA.ifx_account_id NOT IN (
    SELECT UW.trans_id FROM bonus_accounts AS BA INNER JOIN user_withdrawal AS UW ON BA.ifx_account_id = UW.ifxaccount_id 
)
AND BA.ifx_account_id = $bonus_account_id ";
        $result = $db_handle->fetchAssoc($db_handle->runQuery($query));
        empty($result) ? $result['status'] = true : $result['status'] = false ;
        return $result;
    }

    public function valid_ilpr_account_cond($bonus_account_id){
        global $db_handle;
        $query = "SELECT UI.ifxaccount_id FROM bonus_accounts AS BA 
INNER JOIN user_ifxaccount AS UI ON BA.ifx_account_id = UI.ifxaccount_id
WHERE BA.bonus_account_id = $bonus_account_id
AND UI.type = '1'";
        $result = $db_handle->fetchAssoc($db_handle->runQuery($query));
        !empty($result) ? $result['status'] = true : $result['status'] = false;
        return $result;
    }

    public function verified_client_cond($bonus_account_id){
        $client_operation = new clientOperation;
        global $db_handle;
        $query = "SELECT U.user_code FROM bonus_accounts AS BA 
INNER JOIN user_ifxaccount AS UI ON BA.ifx_account_id = UI.ifxaccount_id
INNER JOIN user AS U ON UI.user_code = U.user_code
WHERE BA.bonus_account_id = $bonus_account_id";
        $user_code = $db_handle->fetchAssoc($db_handle->runQuery($query))[0]['user_code'];
        if(!empty($user_code)){
            $result['docs'] = $client_operation->get_user_docs_by_code($user_code);
            if($result['docs'] && !empty($result['docs'])){
                $result['status'] = true;
            }else{
                $result['status'] = false;
                $result['docs'] = null;
            }
        }else{
            $result['status'] = false;
            $result['docs'] = null;
        }
        return $result;
    }

    //TODO: Recheck this...
    public function bonus_expiry_case_1_cond($bonus_account_id){
        $cond_key = 5;
        $cond_extra = 'Days';
        $client_operation = new clientOperation;

        global $db_handle;
        $query = "SELECT BA.bonus_code, UI.user_code FROM bonus_accounts AS BA INNER JOIN user_ifxaccount AS UI ON BA.ifx_account_id = UI.ifxaccount_id WHERE bonus_account_id = $bonus_account_id ";
        $result = $db_handle->fetchAssoc($db_handle->runQuery($query))[0];
        $bonus_code = $result['bonus_code'];
        $user_code = $result['user_code'];
        $query = "SELECT meta_value FROM bonus_package_meta WHERE bonus_code = '$bonus_code' AND condition_id = $cond_key AND meta_name = '$cond_extra'";
        $meta_value = (int) $db_handle->fetchAssoc($db_handle->runQuery($query))[0]['meta_value'];
        $last_trade_detail = $client_operation->get_last_trade_detail($user_code);
        $days_diff = count(date_range($last_trade_detail['date_earned'], date('Y-m-d')));
        if($days_diff >= $meta_value){
            $result['status'] = false;
            $result['last_trade_date'] = $last_trade_detail['date_earned'];
            $result['days_diff'] = count(date_range($last_trade_detail['date_earned'], date('Y-m-d')));
        }else{
            $result['status'] = true;
            $result['last_trade_date'] = $last_trade_detail['date_earned'];
            $result['days_diff'] = count(date_range($last_trade_detail['date_earned'], date('Y-m-d')));
        }
        return $result;
    }

    //TODO: Recheck this...
    public function bonus_expiry_case_2_cond($bonus_account_id){
        //Allocated bonuses will be withdrawn from any participating Instaforex account that have not traded
        //for the number of days stated below after the accounts last trade date.
        $cond_key = 4;
        $cond_extra = 'Days';
        $client_operation = new clientOperation;
        global $db_handle;
        $query = "SELECT BA.bonus_code, UI.user_code FROM bonus_accounts AS BA INNER JOIN user_ifxaccount AS UI ON BA.ifx_account_id = UI.ifxaccount_id WHERE bonus_account_id = $bonus_account_id ";
        $result = $db_handle->fetchAssoc($db_handle->runQuery($query))[0];
        $bonus_code = $result['bonus_code'];
        $user_code = $result['user_code'];
        $query = "SELECT meta_value FROM bonus_package_meta WHERE bonus_code = '$bonus_code' AND condition_id = $cond_key AND meta_name = '$cond_extra'";
        $meta_value = (int) $db_handle->fetchAssoc($db_handle->runQuery($query))[0]['meta_value'];
        $last_trade_detail = $client_operation->get_last_trade_detail($user_code);
        $days_diff = count(date_range($last_trade_detail['date_earned'], date('Y-m-d')));
        if($days_diff >= $meta_value){
            $result['status'] = false;
            $result['last_trade_date'] = $last_trade_detail['date_earned'];
            $result['days_diff'] = count(date_range($last_trade_detail['date_earned'], date('Y-m-d')));
        }else{
            $result['status'] = true;
            $result['last_trade_date'] = $last_trade_detail['date_earned'];
            $result['days_diff'] = count(date_range($last_trade_detail['date_earned'], date('Y-m-d')));
        }
        return $result;
    }

    public function bonus_withdrawal_case_1_cond($bonus_account_id, $min_trade_volume){
        //This API validates that an account has traded the specified lot size since the bonus was assigned.
        global $db_handle;
        $query = "SELECT SUM(TC.volume) AS total_volume FROM bonus_accounts AS BA 
INNER JOIN user_ifxaccount AS UI ON BA.ifx_account_id = UI.ifxaccount_id 
INNER JOIN trading_commission AS TC ON UI.ifx_acct_no = TC.ifx_acct_no 
WHERE BA.bonus_account_id = $bonus_account_id ";
        $total_volume = (float) $db_handle->fetchAssoc($db_handle->runQuery($query))[0]['total_volume'];
        if($total_volume >= $min_trade_volume){
            $result['status'] = true;
            $result['total_volume'] = $total_volume;
        }else{
            $result['status'] = false;
            $result['total_volume'] = 0;
        }
        return $result;
    }

    public function bonus_withdrawal_case_2_cond($min_percent){
        $min_percent = (float) $min_percent;
        $result['msg'] = "Only $min_percent% of the equity balance can be withdrawn.";
        $result['status'] = true;
        return $result;
    }

    public function profit_withdrawal_case_3_cond(){
        $result['msg'] = 'All trades MUST be closed.';
        $result['status'] = true;
        return $result;
    }

    public function profit_withdrawal_case_2_cond($min_amount){
        $min_amount = (float) $min_amount;
        $result['msg'] = "Equity balance on this account MUST be a minimum of &dollar; $min_amount .";
        $result['status'] = true;
        return $result;
    }

    public function profit_withdrawal_case_1_cond($min_percent){
        $min_percent = (float) $min_percent;
        $result['msg'] = "Only $min_percent% of the equity balance can be withdrawn.";
        $result['status'] = true;
        return $result;
    }
}