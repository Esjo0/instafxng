<?php
//Run this script every 3 hours
set_include_path('/home/tboy9/public_html/init/');
//set_include_path('../public_html/init/');
require_once 'initialize_admin.php';
$bonus_obj = new Bonus_Operations();
$bonus_cond_obj = new Bonus_Condition();
$query = "SELECT * FROM bonus_accounts AS BA ";
$bonus_account_list = $db_handle->fetchAssoc($db_handle->runQuery($query));
foreach ($bonus_account_list as $bonus_account){
    $conditions = explode(',', $bonus_account['condition_id']);
    foreach ($conditions as $cond_key){
        $condition = $bonus_cond_obj->BONUS_CONDITIONS[$cond_key];
        $cond_review_result = $bonus_cond_obj->{$condition['api']}($condition['args']);
        switch ((bool) $cond_review_result['status']){
            case true : $status = '1'; break;
            case false : $status = '0'; break;
        }
        $query_1 = "DELETE FROM bonus_acc_condition_meta WHERE bonus_account_id = {$bonus_account['bonus_account_id']} AND condition_id = $cond_key ";
        $db_handle->runQuery($query_1);
        $query_2 = "INSERT INTO bonus_acc_condition_meta (bonus_account_id, condition_id, status_id) VALUES ({$bonus_account['bonus_account_id']}, $cond_key, '$status') ";
        $db_handle->runQuery($query_2);
        $query_3 = "UPDATE bonus_accounts SET updated = now() WHERE bonus_account_id = {$bonus_account['bonus_account_id']}  ";
        $db_handle->runQuery($query_3);
    }
    $max_threshold = count($conditions);
    $min_threshold = $max_threshold / 2;
    $query = "SELECT BACM.status_id FROM bonus_acc_condition_meta AS BACM WHERE BACM.status_id = '0' 
AND BACM.bonus_account_id = {$bonus_account['bonus_account_id']} ";
    $failed_conds = $db_handle->numRows($query);
    if($failed_conds <= $min_threshold){
        $query = "UPDATE bonus_accounts SET updated = now(), recommendation = '1' WHERE bonus_account_id = {$bonus_account['bonus_account_id']} ";
        $db_handle->runQuery($query);
    }

    $query = "SELECT BACM.status_id FROM bonus_acc_condition_meta AS BACM WHERE BACM.status_id = '1' 
AND BACM.bonus_account_id = {$bonus_account['bonus_account_id']} ";
    $successful_conds = $db_handle->numRows($query);
    if($failed_conds <= $min_threshold){
        $query = "UPDATE bonus_accounts SET updated = now(), recommendation = '2' WHERE bonus_account_id = {$bonus_account['bonus_account_id']} ";
        $db_handle->runQuery($query);
    }
}