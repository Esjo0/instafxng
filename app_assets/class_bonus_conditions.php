<?php
class Bonus_Condition{
    public $BONUS_CONDITIONS = array(
        1 => array(
            'title' => 'New Account',
            'desc' => 'All participating Instaforex accounts must be new accounts.',
            'type' => 0,
            'extra' => '',
            'api' => 'new_account_cond',
            'args' => array('bonus_account_id'),
            'returns' => array('status')
        ),
    );

    public $CONDITIONS_TYPES = array(
        0 => 'Conditions to be met before allocation of bonus',
        1 => 'Conditions to be met after allocation of bonus',
        2 => 'Conditions to be met before recycling out of bonus'
    );

    public function get_condition_types(){
        $result = array();
        foreach ($this->CONDITIONS_TYPES as $key => $value) {
            $result[count($result) + 1] = $key;
        }
        return $result;
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
        $result['status'] = true;
        return $result;
    }

    public function valid_ilpr_account_cond($bonus_account_id){
        $result['status'] = true;
        return $result;
    }

    public function bonus_withdrawal_case_1_cond($bonus_account_id){
        $result['status'] = true;
        return $result;
    }
}