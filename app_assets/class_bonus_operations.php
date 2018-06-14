<?php
class Bonus_Operations
{
    public function get_conditions()
    {
        $result = array(
            1 => array(
                'title' => 'Bonus Expiry (Not traded for x number of days)',
                'desc' => 'This API validates that an account has not traded for the specified number of days since the bonus was assigned.',
                'extra' => array(
                    'date' => '',
                    'duration' => ''
                ),
            ),
            2 => array(
                'title' => 'Bonus Expiry (Not traded for x number of days since the last trade date)',
                'desc' => 'This API validates that an account has not traded for the specified number of days since the accounts last trade date.',
                'extra' => array(
                    'date' => '',
                    'duration' => ''
                ),
            ),
            3 => array(
                'title' => 'Bonus Withdrawal (Has traded x lot sizes)',
                'desc' => 'This API validates that an account has not traded for the specified number of days since the bonus was assigned.',
                'extra' => array(
                    'date' => '',
                    'duration' => ''
                ),
            ),
            4 => array(
                'title' => 'Bonus Withdrawal (Based on percentage)',
                'desc' => 'This API notifies the Compliance Officer of the set percentage for withdrawal for accounts under the enrolled bonus package.',
                'extra' => array(
                    'date' => '',
                    'duration' => ''
                ),
            )
        );
        return $result;
    }
    public function create_new_package($bonus_title, $bonus_desc, $condition_id, $status, $type, $admin_code)
    {
        global $db_handle;

        //check whether bonus_code generated by rand_string is already existing
        bonus_code:
        $bonus_code = rand_string(5);
        if($db_handle->numRows("SELECT bonus_code FROM bonus_packages WHERE bonus_code = '$bonus_code'") > 0) { goto bonus_code; };

        $query = "INSERT INTO bonus_packages (bonus_code, bonus_title, bonus_desc, condition_id, status, type, admin_code) VALUES ('$bonus_code', '$bonus_title', '$bonus_desc', '$condition_id', '$status', $type, '$admin_code');";
        $result = $db_handle->runQuery($query);
        return $result;
    }
}