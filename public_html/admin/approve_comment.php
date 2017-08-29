<?php
    require_once("../init/initialize_admin.php");
    $comment_id = $_GET['data'];  //This recieves the data passed from JavaScript
    $query = "UPDATE comments 
                   SET comments.status = 'ON'
                      WHERE comments.comment_id = '".$comment_id."';";
    //var_dump($query);
    $result = $db_handle->runQuery($query);
    redirect_to("recent_comments.php");
?>