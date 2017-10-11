<?php
require_once("../init/initialize_admin.php");
if (!$session_admin->is_logged_in()) {
    redirect_to("login.php");
}

$get_params = allowed_get_params(['x', 'id']);
$bulletin_id_encrypted = $get_params['id'];
$bulletin_id = decrypt(str_replace(" ", "+", $bulletin_id_encrypted));
$bulletin_id = preg_replace("/[^A-Za-z0-9 ]/", '', $bulletin_id);

$admin_code = $_SESSION['admin_unique_code'];
$selected_bulletin = $system_object->get_bulletin_by_id($bulletin_id);
$selected_bulletin = $selected_bulletin[0];

$allowed_admin = explode(",", $selected_bulletin['allowed_admin']);
if (!in_array($_SESSION['admin_unique_code'], $allowed_admin)) { unset($selected_bulletin); }



if(!$selected_bulletin)
{
    redirect_to('bulletin_centre.php');
}

if (isset($_POST['post_comment']))
{
    $comment = $db_handle->sanitizePost($_POST['comment']);
    $bulletin_id = $db_handle->sanitizePost($_POST['bulletin_id']);

    $db_handle->runQuery("INSERT INTO admin_bulletin_comments (author_code, bulletin_id, comment) VALUES ('$admin_code', '$bulletin_id', '$comment')");
    if($db_handle->affectedRows() > 0)
    {
        $message_success = "You have successfully added a new comment.";

    }
    else
    {
        $message_error = "An error occurred, your comment could not be saved.";
    }
}

if (isset($_POST['reply_comment']))
{
    $comment = $db_handle->sanitizePost($_POST['comment']);
    $bulletin_id = $db_handle->sanitizePost($_POST['bulletin_id']);
    $comment_id = $db_handle->sanitizePost($_POST['comment_id']);
    $new_comment = $db_handle->runQuery("INSERT INTO admin_bulletin_comments (author_code, bulletin_id, comment, reply_to) VALUES('$admin_code', '$bulletin_id', '$comment', '$comment_id')");
    if($new_comment)
    {
        $message_success = "You have successfully added a reply.";
    }
}


// Select the latest comments
$result = $db_handle->runQuery("SELECT * FROM admin_bulletin_comments, admin  WHERE admin_bulletin_comments.author_code = admin.admin_code AND admin_bulletin_comments.bulletin_id = '$bulletin_id' ORDER BY comment_id DESC");
$latest_comments = $db_handle->fetchAssoc($result);
?>
<!DOCTYPE html>
<html lang="en">
    <head>
        <base target="_self">
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>Instaforex Nigeria | Admin - Bulletin Centre</title>
        <meta name="title" content="Instaforex Nigeria | Admin - Bulletin Centre" />
        <meta name="keywords" content="" />
        <meta name="description" content="" />
        <?php require_once 'layouts/head_meta.php'; ?>
    </head>
    <body>
        <?php require_once 'layouts/header.php'; ?>
        <!-- Main Body: The is the main content area of the web site, contains a side bar  -->
        <div id="main-body" class="container-fluid">
            <div class="row no-gutter">
                <!-- Main Body - Side Bar  -->
                <div id="main-body-side-bar" class="col-md-4 col-lg-3 left-nav">
                <?php require_once 'layouts/sidebar.php'; ?>
                </div>
                
                <!-- Main Body - Content Area: This is the main content area, unique for each page  -->
                <div id="main-body-content-area" class="col-md-8 col-lg-9">
                    
                    <!-- Unique Page Content Starts Here
                    ================================================== -->
                    <div class="section-tint super-shadow">
                        <div class="row">
                            <div class="col-sm-12">
                                <p><i class="fa fa-arrow-circle-left"></i> <a href="bulletin_centre.php" title="Bulletin Centre">Go Back To Bulletin Centre</a></p>
                                <p><em>Posted on <?php echo datetime_to_text($selected_bulletin['created']); ?></em></p>
                                <p><strong>Author: </strong><?php echo $admin_object->get_admin_name_by_code($selected_bulletin['admin_code']); ?></p>
                                <hr/>
                            </div>
                        </div>
                        
                        <div class="row">
                            <div class="col-sm-12 text-danger">
                                <h4><strong><?php echo $selected_bulletin['title'];?></strong></h4>
                            </div>
                        </div>
                        
                        <div class="row">
                            <div class="col-sm-12">
                                <?php echo $selected_bulletin['content'];?>
                            </div>
                        </div>
                        <h5>Leave a comment</h5>
                        <form data-toggle="validator" class="form-horizontal " role="form" method="post" action="">
                            <div class="form-group">
                                <label class="control-label col-sm-3" for="comment">Comment:</label>
                                <div class="col-sm-7">
                                    <div class="input-group">
                                        <span class="input-group-addon"><i class="fa fa-comment-o fa-fw"></i></span>
                                        <textarea  name="comment" type="text" id="comment" value="" class="form-control" required></textarea>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group">
                                <div class="col-sm-offset-3 col-sm-9">
                                    <input type="hidden" name="bulletin_id" value="<?php echo $bulletin_id;?>"/>
                                    <button type="submit" name="post_comment" class="btn btn-success">Post Comment</button>
                                </div>
                            </div>
                        </form>
                        <hr class=""/>

                        <?php if(isset($latest_comments)) { ?>
                            <h5>Previous Comments</h5>
                            <div id="latest_comment">
                            </div>

                            <ul class="fa-ul">
                                <?php foreach($latest_comments as $row) { ?>

                                    <?php echo $row['first_name']; ?> <?php echo $row['last_name']; ?> on <?php echo datetime_to_text($row['created']); ?>
                                    <?php

                                    if($row['reply_to'] > 0)
                                    {
                                        $query1 = $db_handle->runQuery("SELECT author_code, bulletin_id FROM admin_bulletin_comments WHERE comment_id = '" . $row['reply_to'] . "';");
                                        $details = $db_handle->fetchAssoc($query1);
                                        $details = $details[0];
                                        $details_x = $details['author_code'];
                                        $details_y = $details['bulletin_id'];
                                        $query2 = "SELECT CONCAT(admin.first_name, SPACE(1), admin.last_name) AS full_name, title FROM admin, admin_bulletin WHERE admin.admin_code = '$details_x' AND admin_bulletin.admin_bulletin_id = '$details_y';";
                                        $reply_details = $db_handle->runQuery($query2);
                                        $reply_details = $db_handle->fetchAssoc($reply_details);
                                        foreach ($reply_details as $row1) {
                                            echo "as a reply to ".$row1['full_name'] . "'s comment on ".$row1['title']." ";
                                        }
                                    }
                                    ?>

                                    said;<br/>"<?php echo $row['comment'];?>"<br/>
                                    <a title="Reply" data-target="#reply-comment<?php echo $row['comment_id'];?>" data-toggle="modal" href="#">Reply</a>

                                    <!--Modal - confirmation boxes-->
                                    <div id="reply-comment<?php echo $row['comment_id'];?>" tabindex="-1" role="dialog" aria-hidden="true" class="modal fade">
                                        <div class="modal-dialog">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <button type="button" data-dismiss="modal" aria-hidden="true"
                                                            class="close">&times;</button>
                                                    <h4 class="modal-title">Reply Comment</h4></div>
                                                <form data-toggle="validator" class="form-horizontal " role="form" method="post" action="">
                                                <div class="modal-body">
                                                    <p>Reply <?php echo $row['first_name']; ?> <?php echo $row['last_name']; ?>'s comment on <?php echo $title?>.</p>
                                                    <div class="form-group">
                                                        <label class="control-label col-sm-3" for="comment">Comment:</label>
                                                        <div class="col-sm-9">
                                                            <div class="input-group">
                                                                <span class="input-group-addon"><i class="fa fa-comment-o fa-fw"></i></span>
                                                                <textarea  name="comment" type="text" id="comment" value="" class="form-control" required></textarea>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="modal-footer">
                                                    <input type="hidden" name="bulletin_id" value="<?php echo $bulletin_id;?>"/>
                                                    <input type="hidden" name="comment_id" value="<?php echo $row['comment_id'];?>"/>
                                                    <input type="submit" name="reply_comment" class="btn btn-success" value="Submit Reply"/>
                                                    <button type="submit" name="close" onClick="window.close();" data-dismiss="modal" class="btn btn-danger">Close!</button>
                                                </div>
                                                </form>
                                            </div>
                                        </div>
                                    </div>

                                    <br/>

                                <?php } ?>
                            </ul>
                        <?php } ?>

                        <hr class=""/>
                    </div>
                    <!-- Unique Page Content Ends Here
                    ================================================== -->
                    
                </div>
                
            </div>
        </div>
        <?php require_once 'layouts/footer.php'; ?>
    </body>
</html>