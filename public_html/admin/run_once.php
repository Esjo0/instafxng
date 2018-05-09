<?php
require_once("../init/initialize_admin.php");
if (!$session_admin->is_logged_in()) {redirect_to("login.php");}
echo 'Importing free_training_campaign records<br/>';
$q = "SELECT first_name as f_name, last_name as l_name, email, phone, created FROM free_training_campaign WHERE (STR_TO_DATE(created, '%Y-%m-%d') BETWEEN '2018-01-01' AND '2018-05-08') ";
$q_result = $db_handle->runQuery($q);
$q_result = $db_handle->fetchAssoc($q_result);
$query1 = '';
foreach ($q_result as $key)
{
    extract($key);
    $query1 .= "INSERT IGNORE INTO campaign_leads (f_name, l_name, email, phone, created, source, interest) VALUES ('$f_name', '$l_name', '$email', '$phone', '$created', '2', '1'); ";
}
$q_result = $db_handle->runQuery($query1);
if($q_result){echo 'Successfully Imported free_training_campaign records<br/>';}

echo 'Importing prospect_ilpr_biodata<br/>';
$q = "SELECT f_name, l_name, email, phone, created FROM prospect_ilpr_biodata WHERE (STR_TO_DATE(created, '%Y-%m-%d') BETWEEN '2018-01-01' AND '2018-05-08') ";
$q_result = $db_handle->runQuery($q);
$q_result = $db_handle->fetchAssoc($q_result);
$query2 = '';
foreach ($q_result as $key)
{
    extract($key);
    $query2 .= "INSERT IGNORE INTO campaign_leads (f_name, l_name, email, phone, created, source, interest) VALUES ('$f_name', '$l_name', '$email', '$phone', '$created', '2', '2'); ";
}
$q_result = $db_handle->runQuery($query2);
if($q_result){echo 'Successfully Imported prospect_ilpr_biodata<br/>';}