<?php
require_once "../../init/initialize_client.php";
if(!empty($_POST['type']) && !empty($_POST['query']))
{
    if($_POST['type'] == '1') {
        $query = $_POST['query'];
        $result = $db_handle->runQuery($query);
    }
    if($_POST['type'] == '2'){
        $result = $db_handle->runQuery($_POST['query']);
        $result = $db_handle->fetchAssoc($result);
        if(!empty($result)) { echo json_encode($result);}
    }
    if($_POST['type'] == '3'){
        $result = $db_handle->runQuery($_POST['query']);
        $result = $db_handle->fetchAssoc($result);
        if(!empty($result)) { echo json_encode($result);}
    }
    if($_POST['type'] == '4'){
        $result = $db_handle->runQuery($_POST['query']);
        //$result = $db_handle->fetchAssoc($result);
        //if(!empty($result)) { echo json_encode($result);}
    }
}