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
            'extra' => '',
            'args' => array('bonus_account_id'),
            'returns' => array('status', 'docs')
        ),
        4 => array(
            'title' => 'Bonus Expiry (Case 1)',
            'desc' => 'Bonuses would be withdrawn from any Instaforex account that benefits from this bonus package and fails to trade within the specified window period.',
            'type' => 0,
            'api' => 'bonus_withdrawal_case_1_cond',
            'extra' => array('Window'),
            'args' => array('bonus_account_id'),
            'returns' => array('status', 'docs')
        ),
        5 => array(
            'title' => 'Bonus Withdrawal (Case 1)',
            'desc' => 'Bonuses would be withdrawn from an Instaforex account benefiting from this bonus package trades less than the specified volume within the period of the bonus allocation.',
            'type' => 0,
            'api' => 'bonus_withdrawal_case_1_cond',
            'extra' => array('Volume'),
            'args' => array('bonus_account_id'),
            'returns' => array('status', 'docs')
        ),
        6 => array(
            'title' => 'Bonus Withdrawal (Case 2)',
            'desc' => 'Each withdrawal order from any benefiting Instaforex must not be more than the recommended amount or the stated percentage of the allocated bonus.',
            'type' => 0,
            'api' => 'bonus_withdrawal_case_1_cond',
            'extra' => array('Percentage'),
            'args' => array('bonus_account_id'),
            'returns' => array('status', 'docs')
        ),
        7 => array(
            'title' => 'Profit Withdrawal (Case 1)',
            'desc' => 'Allow or disallow profit withdrawal from benefiting Instaforex account numbers on this bonus package.',
            'type' => 0,
            'api' => 'bonus_withdrawal_case_1_cond',
            'extra' => array('Status'),
            'args' => array('bonus_account_id'),
            'returns' => array('status', 'docs')
        ),
        8 => array(
            'title' => 'Profit Withdrawal (Case 1)',
            'desc' => 'Allow or disallow profit withdrawal from benefiting Instaforex account numbers on this bonus package.',
            'type' => 0,
            'api' => 'bonus_withdrawal_case_1_cond',
            'extra' => array('Status'),
            'args' => array('bonus_account_id'),
            'returns' => array('status', 'docs')
        ),
        9 => array(
            'title' => 'Profit Withdrawal (Case 1)',
            'desc' => 'Allow or disallow profit withdrawal from benefiting Instaforex account numbers on this bonus package.',
            'type' => 0,
            'api' => 'bonus_withdrawal_case_1_cond',
            'extra' => array('Status'),
            'args' => array('bonus_account_id'),
            'returns' => array('status', 'docs')
        ),
        10 => array(
            'title' => 'Profit Withdrawal (Case 1)',
            'desc' => 'Allow or disallow profit withdrawal from benefiting Instaforex account numbers on this bonus package.',
            'type' => 0,
            'api' => 'bonus_withdrawal_case_1_cond',
            'extra' => array('Status'),
            'args' => array('bonus_account_id'),
            'returns' => array('status', 'docs')
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