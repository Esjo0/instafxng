<?php
class Bonus_Condition
{
    public $BONUS_CONDITIONS = array(
        1 => array(
            'title' => 'New Account Validation',
            'desc' => 'This helps to validate that a given account is a new instaforex account.',
            'type' => 0,
            'extra' => '',
            'api' => '',
            'set_params' => array('account_no'),
            'get_params' => array('valid_acc', 'return_type')
        ),
        2 => array(
            'title' => 'ILPR Account Validation',
            'desc' => 'This helps to validate that a given account is enrolled into the Instafxng Loyalty Promotion and Rewards.',
            'type' => 0,
            'extra' => '',
            'set_params' => array('account_no'),
            'get_params' => array('valid_acc')
        ),
        3 => array(
            'title' => 'Bonus Withdrawal (Case 1)',
            'desc' => 'This helps to validate that the amount to be withdrawn does not exceed the stated amount.',
            'type' => 0,
            'extra' => array('Amount'),
            'set_params' => array('account_no'),
            'get_params' => array('valid_acc')
        )
    );

    public $BONUS_TYPES = array(
        0 => 'Conditions to be met before allocation of bonus',
        1 => 'Conditions to be met after allocation of bonus',
        2 => 'Conditions to be met before recycling out of bonus'
    );

    public function get_condition_types(){
        $result = array();
        foreach ($this->BONUS_TYPES as $key => $value) {
            $result[count($result) + 1] = $key;
        }
        return $result;
    }

    public function get_condition_result($condition_id, $args){
        switch ($condition_id){
            case 1:
                $result = $this->new_account($args);
                break;
            case 2:
                $result = $this->valid_ilpr_account($args);
                break;
            default:
                break;
        }
        return $result;
    }

    public function new_account($account_no){
        return json_encode(true);
    }

    public function valid_ilpr_account($account_no){
        return json_encode(true);
    }

}