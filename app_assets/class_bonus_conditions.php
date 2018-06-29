<?php
class Bonus_Condition
{
    public $BONUS_CONDITIONS = array(
        1 => array(
            'title' => 'New Account Validation',
            'desc' => 'This helps to validate that a given account is a new instaforex account.',
            'type' => 0,
            'extra' => '',
            'api' => 'new_account_cond',
            'set_params' => array('account_no'),
            'get_params' => array('valid_acc', 'return_type')
        ),
        2 => array(
            'title' => 'ILPR Account Validation',
            'desc' => 'This helps to validate that a given account is enrolled into the Instafxng Loyalty Promotion and Rewards.',
            'type' => 0,
            'api' => 'valid_ilpr_account_cond',
            'extra' => '',
            'set_params' => array('account_no'),
            'get_params' => array('valid_acc')
        ),
        3 => array(
            'title' => 'Bonus Withdrawal (Case 1)',
            'desc' => 'This helps to validate that the amount to be withdrawn does not exceed the stated amount.',
            'type' => 0,
            'api' => 'bonus_withdrawal_case_1_cond',
            'extra' => array('Amount'),
            'set_params' => array('account_no'),
            'get_params' => array('valid_acc')
        )
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

    /**
     * @param $condition_id
     * @return bool
     */
    public function get_condition_result($condition_id, $bonus_account_id){
        switch ($condition_id){
            case 1:
                $result = $this->new_account($bonus_account_id);
                break;
            case 2:
                $result = $this->valid_ilpr_account($bonus_account_id);
                break;
            default:
                break;
        }
        return $result;
    }

    public function new_account_cond($bonus_account_id){
        return json_encode(true);
    }

    public function valid_ilpr_account_cond($bonus_account_id){
        return json_encode(true);
    }

    public function bonus_withdrawal_case_1_cond($bonus_account_id){
        return json_encode(true);
    }

}