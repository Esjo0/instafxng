<?php
class Bonus_Condition{
    public $BONUS_CONDITIONS = array(
        1 => array(
            'title' => 'New Account Validation',
            'desc' => 'All participating Instaforex accounts must be new accounts.',
            'type' => 0,
            'extra' => '',
            'api' => 'new_account_cond',
            'args' => array('bonus_account_id'),
            'returns' => array('status')
        ),
        2 => array(
            'title' => 'ILPR Account Validation',
            'desc' => 'All participating Instaforex accounts must be enrolled into the Instafxng Loyalty Promotion and Rewards (ILPR).',
            'type' => 0,
            'api' => 'valid_ilpr_account_cond',
            'extra' => '',
            'args' => array('bonus_account_id'),
            'returns' => array('status')
        ),
        3 => array(
            'title' => 'Client Verification',
            'desc' => 'All clients benefiting from this bonus package must have verified their identities.',
            'type' => 0,
            'api' => 'bonus_withdrawal_case_1_cond',
            'extra' => array('Amount'),
            'args' => array('bonus_account_id'),
            'returns' => array('status', 'docs')
        ),
        4 => array(
            'title' => 'Bonus Expiry (Case 1)',
            'desc' => 'This API validates that an account has not traded for the specified number of days since the bonus was assigned.',
            'type' => 0,
            'api' => 'bonus_withdrawal_case_1_cond',
            'extra' => array('Amount'),
            'args' => array('bonus_account_id'),
            'returns' => array('status', 'docs')
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