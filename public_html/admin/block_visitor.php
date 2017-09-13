<?php
    require_once("../init/initialize_admin.php");
    $visitor_id = $_GET['data'];
    $type = $_GET['type'];
    if($type == 'block')
    {
        $query = "UPDATE article_visitors
                          SET block_status = 'ON'
                          WHERE visitor_id = '".$visitor_id."'";
        $result = $db_handle->runQuery($query);
    }
    elseif($type == 'unblock')
    {
        $query = "UPDATE article_visitors
                              SET block_status = 'OFF'
                              WHERE visitor_id = '".$visitor_id."'";
        $result = $db_handle->runQuery($query);
    }
    redirect_to("list_of_visitors.php");