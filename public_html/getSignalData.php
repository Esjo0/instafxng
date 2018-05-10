<?php
/*header("Access-Control-Allow-Origin: *");*/
require_once("init/initialize_admin.php");
require_once("init/initialize_general.php");
/*foreach ($_POST as $key => $value)
{
        $_POST[$key] =  $db_handle->sanitizePost(trim($value));
}*/
extract($_POST);
$result = $db_handle->runQuery($query);
$result = $db_handle->fetchAssoc($result);
if(!empty($result)) {    echo json_encode($result); }
?>
