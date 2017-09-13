<?php
    require_once("../init/initialize_admin.php");
    $comment_id = $_GET['data'];  //This recieves the data passed from JavaScript
    $query = "UPDATE article_comments
                   SET article_comments.status = 'ON'
                      WHERE article_comments.comment_id = '".$comment_id."';";
    $result = $db_handle->runQuery($query);
    redirect_to("recent_comments.php");