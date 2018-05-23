<?php
header("Access-Control-Allow-Origin: *");
require_once("../init/initialize_admin.php");
require_once("../init/initialize_general.php");
require_once('../init/initialize_client.php');

$get_params = allowed_get_params(['type', 'access_token', 'user_token', 'user_code']);
$request_type = $get_params['type'];
$_u_access_token = $get_params['access_token'];
$user_token = $get_params['user_token'];
$user_code = $get_params['user_code'];

if(validRequest($_u_access_token))
{
    if($request_type == 1){
        $result = array('user_code' => getUserCode());
        echo json_encode($result);
    }
    if($request_type == 2){
        $result = array('feedback' => setUserToken($user_code, $user_token));
        echo json_encode($result);
    }
}

function validRequest($provided_token){
    if($provided_token === ACCESS_TOKEN){
        $x = true;
    }
    else{
        $x = false;
    }
    return $x;
}

function getUserCode(){
    !empty($_SESSION['client_unique_code']) ? $x = $_SESSION['client_unique_code'] : $x = null;
    return $x;
}

function setUserToken($user_code, $user_token){
    global $db_handle;
    $query = "SELECT * FROM fxacademy_app_users WHERE user_code = '$user_code' ";
    $new_user = $db_handle->numRows($query);
    if($new_user > 0) {
        $query = "UPDATE fxacademy_app_users SET user_token = '$user_token' WHERE user_code = '$user_code' ";
        $x = $db_handle->runQuery($query);}
    else{
        $query = "INSERT INTO fxacademy_app_users (user_code, user_token) VALUES('$user_code', '$user_token') ";
        $x = $db_handle->runQuery($query);}
    return $x;
}

define("ACCESS_TOKEN", "mWSUe7/msniZD3f8EzN1m1dyzFXQdSnraUcfox7kgp8=");