<?php
require_once("../init/initialize_admin.php");
if (!$session_admin->is_logged_in()) {
    redirect_to("login.php");
}
$admin_code = $db_handle->sanitizePost(trim($_POST['admin']));
$transaction_id = $db_handle->sanitizePost(trim($_POST['trans_id']));
$comment = $db_handle->sanitizePost(trim($_POST['comment']));
if(empty($comment)){
    $message_error = "The comment section was empty. Kindly type a comment.";
}else{
    $add_comment = $admin_object->add_operations_comment($transaction_id, $comment ,$admin_code);
    if($add_comment = true) {
        $message_success = "You have successfully added a new comment";
    } else {
        $message_error = "Something went wrong. Please try again.";
    }

}
$comments_details = $admin_object->get_comment_details( $transaction_id );
if(!empty($comments_details)){
    foreach($comments_details as $row3) {
        $admin_code = $row3['admin_code'];
        $destination_details = $obj_facility->get_admin_detail_by_code($admin_code);
        $admin_name = $destination_details['first_name'];
        $admin_lname = $destination_details['last_name'];
        $name = $admin_name." ".$admin_lname;
        $comment = $row3['comment'];
        $time = datetime_to_text($row3['created']);

        echo "<div style=\"background-color: lightgrey; margin: 15px; border: 1px solid #5f5f5f;\">

            <div  style=\"color: #0e90d2\"><i>
                    
                     $name  </i></div><br/>
            <div class=\"row\">
                <div class=\"col-sm-2\"></div>
                <div class=\"col-sm-8\"> $comment </div>
                <div class=\"col-sm-2\"></div>
            </div>
            <span class=\"time-right\" style=\"color: #ff0f1d\"><strong>TIME : </strong> $time </span>
        </div>";
    }} else{ echo "<img class=\"img-responsive\" src=\"../images/No-Comments.png\" /> "; }

                    ?>