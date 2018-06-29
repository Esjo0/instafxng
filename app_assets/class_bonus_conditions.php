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
            'args' => array('bonus_account_id'),
            'returns' => array('status')
        ),
        2 => array(
            'title' => 'ILPR Account Validation',
            'desc' => 'This helps to validate that a given account is enrolled into the Instafxng Loyalty Promotion and Rewards.',
            'type' => 0,
            'api' => 'valid_ilpr_account_cond',
            'extra' => '',
            'args' => array('bonus_account_id'),
            'returns' => array('status')
        ),
        3 => array(
            'title' => 'Bonus Withdrawal (Case 1)',
            'desc' => 'This helps to validate that the amount to be withdrawn does not exceed the stated amount.',
            'type' => 0,
            'api' => 'bonus_withdrawal_case_1_cond',
            'extra' => array('Amount'),
            'args' => array('bonus_account_id'),
            'returns' => array('valid_acc')
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

    public function new_account_cond($bonus_account_id){
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