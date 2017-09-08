<?php
    require_once("../init/initialize_admin.php");
$comment_id = $_GET['data'];
        $query = "DELETE FROM comments 
                  WHERE comments.comment_id = '".$comment_id."'";
        $result = $db_handle->runQuery($query);
redirect_to("recent_comments.php");
?>