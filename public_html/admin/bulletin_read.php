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
        //$content = 'Author: '.$admin_object->get_admin_name_by_code($admin_code) ."";
        $content .= 'Message: '.$comment;
        $title = "New Bulletin Comment";
        $message = "Bulletin Title: ".$selected_bulletin['title']." <br/> ".$content;
        $recipients = implode(",", $allowed_admin);
        $author = $admin_object->get_admin_name_by_code($_SESSION['admin_unique_code']);
        $source_url = "https://instafxng.com/admin/bulletin_read.php?id=".encrypt($bulletin_id);
        $notify_support = $obj_push_notification->add_new_notification($title, $message, $recipients, $author, $source_url);
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
$query = "SELECT admin_code, admin_bulletin_comments.created, admin_bulletin_comments.comment FROM admin_bulletin_comments, admin  WHERE admin_bulletin_comments.author_code = admin.admin_code AND admin_bulletin_comments.bulletin_id = '$bulletin_id' ORDER BY comment_id DESC";
$result = $db_handle->runQuery($query);
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
                                <p><em>Posted on <?php echo datetime_to_text2($selected_bulletin['created']); ?></em></p>
                                <p><strong>Author: </strong><?php echo $admin_object->get_admin_name_by_code($selected_bulletin['admin_code']); ?></p>
                                <hr/>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-sm-12">
                                <div class="col-sm-6">
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
                                    <form  data-toggle="validator" role="form" method="post" action="">
                                        <input type="hidden" class="form-control" id="client_id" name="transaction_id" value="<?php echo $trans_id; ?>">
                                        <div class="form-group">
                                            <label class="control-label" for="remarks">Your Remark:</label>
                                            <div>
                                                <textarea  name="comment" type="text" id="comment" value="" class="form-control" required></textarea>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <input type="hidden" name="bulletin_id" value="<?php echo $bulletin_id;?>"/>
                                            <button type="submit" name="post_comment" class="btn btn-success">Post Remark</button>
                                        </div>
                                    </form>
                                </div>


                                <div class="col-sm-6">
                                    <h5>Admin Remarks</h5>
                                    <div style="word-break:break-all; max-height: 550px; overflow-y: scroll; overflow-x: hidden">
                                        <?php
                                        if(isset($latest_comments) && !empty($latest_comments)) {
                                            foreach ($latest_comments as $row) {
                                                ?>
                                                <div class="row">
                                                    <div class="col-sm-12">
                                                        <div class="transaction-remarks">
                                                            <span id="trans_remark_author"><?php echo $admin_object->get_admin_name_by_code($row['admin_code']); ?></span>
                                                            <span id="trans_remark"><?php echo $row['comment'];?></span>
                                                            <span id="trans_remark_date"><?php echo datetime_to_text($row['created']); ?></span>
                                                        </div>
                                                    </div>
                                                </div>
                                            <?php } } else { ?>
                                            <div class="row">
                                                <div class="col-sm-12">
                                                    <div class="transaction-remarks">
                                                        <span class="text-danger"><em>There is no remark to display.</em></span>
                                                    </div>
                                                </div>
                                            </div>
                                        <?php } ?>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- Unique Page Content Ends Here
                    ================================================== -->
                    
                </div>
                
            </div>
        </div>
        <?php require_once 'layouts/footer.php'; ?>
    </body>
</html>