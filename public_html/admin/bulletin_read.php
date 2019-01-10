<?php
require_once("../init/initialize_admin.php");
if (!$session_admin->is_logged_in()) {
    redirect_to("login.php");
}

$get_params = allowed_get_params(['x', 'id']);
$bulletin_id_encrypted = $get_params['id'];
$bulletin_id = dec_enc('decrypt',  $bulletin_id_encrypted);

$admin_code = $_SESSION['admin_unique_code'];
$selected_bulletin = $system_object->get_bulletin_by_id($bulletin_id);
$selected_bulletin = $selected_bulletin[0];

$allowed_admin = explode(",", $selected_bulletin['allowed_admin']);
if (!in_array($_SESSION['admin_unique_code'], $allowed_admin)) { unset($selected_bulletin); }



if(!$selected_bulletin)
{
    redirect_to('bulletin_centre.php');
}

if (isset($_POST['post_comment'])) {

    foreach($_POST as $key => $value) {
        $_POST[$key] = $db_handle->sanitizePost(trim($value));
    }

    $comment = $_POST['comment'];
    $bulletin_id = $_POST['bulletin_id'];
    $query = "INSERT INTO admin_bulletin_comments (author_code, bulletin_id, comment) VALUES ('$admin_code', '$bulletin_id', '$comment')";
    $db_handle->runQuery($query);
    if($db_handle->affectedRows() > 0)
    {
        //$content = 'Author: '.$admin_object->get_admin_name_by_code($admin_code) ."";
        $content = 'Message: '.$comment;
        $title = "New Bulletin Comment";
        $message = "Bulletin Title: ".$selected_bulletin['title']." <br/> ".$content;
        $recipients = implode(",", $allowed_admin);
        $author = $admin_object->get_admin_name_by_code($_SESSION['admin_unique_code']);
        $source_url = "https://instafxng.com/admin/bulletin_read.php?id=".dec_enc('encrypt', $bulletin_id);
        $notify_support = $obj_push_notification->add_new_notification($title, $message, $recipients, $author, $source_url);
        foreach ($allowed_admin as $row)
        {
            $author = $_SESSION['admin_first_name']." ".$_SESSION['admin_last_name'];
            $destination_details = $admin_object->get_admin_detail_by_code($row);
            $admin_name = $destination_details['first_name'];
            $admin_email = $destination_details['email'];
            $subject = 'New Bulletin Comment - '.$selected_bulletin['title'];
            $title = $selected_bulletin['title'];
            $created = date('d-m-y h:i:s a');

            $comment_mail = str_replace('\r\n', '', $comment);
            $comment_mail = str_replace("\'", "'", $comment_mail);

            $message_final = <<<MAIL
                    <div style="background-color: #F3F1F2">
                        <div style="max-width: 80%; margin: 0 auto; padding: 10px; font-size: 14px; font-family: Verdana;">
                            <img src="https://instafxng.com/images/ifxlogo.png" />
                            <hr />
                            <div style="background-color: #FFFFFF; padding: 15px; margin: 5px 0 5px 0;">
                                <p>Dear $admin_name,</p>
                                <p>$author left a new comment.</p>
                                <p><b>BULLETIN TITLE: </b>$title</p>
                                <p><b>COMMENT: </b><br/>$comment_mail</p>
                                <p><b>DATE AND TIME: </b>$created</p>
                                <p><a href="https://instafxng.com/admin/">Login to your Admin Cabinet for for more information.</a></p>
                                <br /><br />
                                <p>Best Regards,</p>
                                <p>Instafxng Support,<br />
                                   www.instafxng.com</p>
                                <br /><br />
                            </div>
                            <hr />
                            <div style="background-color: #EBDEE9;">
                                <div style="font-size: 11px !important; padding: 15px;">
                                    <p style="text-align: center"><span style="font-size: 12px"><strong>We"re Social</strong></span><br /><br />
                                        <a href="https://facebook.com/InstaFxNg"><img src="https://instafxng.com/images/Facebook.png"></a>
                                        <a href="https://twitter.com/instafxng"><img src="https://instafxng.com/images/Twitter.png"></a>
                                        <a href="https://www.instagram.com/instafxng/"><img src="https://instafxng.com/images/instagram.png"></a>
                                        <a href="https://www.youtube.com/channel/UC0Z9AISy_aMMa3OJjgX6SXw"><img src="https://instafxng.com/images/Youtube.png"></a>
                                        <a href="https://linkedin.com/company/instaforex-ng"><img src="https://instafxng.com/images/LinkedIn.png"></a>
                                    </p>
                                    <p><strong>Head Office Address:</strong> TBS Place, Block 1A, Plot 8, Diamond Estate, Estate Bus-Stop, LASU/Isheri road, Isheri Olofin, Lagos.</p>
                                    <p><strong>Lekki Office Address:</strong> Block A3, Suite 508/509 Eastline Shopping Complex, Opposite Abraham Adesanya Roundabout, along Lekki - Epe expressway, Lagos.</p>
                                    <p><strong>Office Number:</strong> 08139250268, 08083956750</p>
                                    <br />
                                </div>
                                <div style="font-size: 10px !important; padding: 15px; text-align: center;">
                                    <p>This email was sent to you by Instant Web-Net Technologies Limited, the
                                        official Nigerian Representative of Instaforex, operator and administrator
                                        of the website www.instafxng.com</p>
                                    <p>To ensure you continue to receive special offers and updates from us,
                                        please add support@instafxng.com to your address book.</p>
                                </div>
                            </div>
                        </div>
                    </div>
MAIL;
            $system_object->send_email($subject, $message_final, $admin_email, $admin_name);
        }
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
        <script src="tinymce/tinymce.min.js"></script>
        <script type="text/javascript">
            tinyMCE.init({
                selector: "textarea#content",
                height: 350,
                theme: "modern",
                relative_urls: false,
                remove_script_host: false,
                convert_urls: true,
                plugins: [
                    "autolink lists print preview hr anchor",
                    "wordcount code fullscreen",
                    "insertdatetime nonbreaking save",
                    ""
                ],
                toolbar1: "undo redo | bold italic ",
                browser_spellcheck: true
//                external_plugins: { "filemanager" : "../filemanager/plugin.min.js"}

            });
        </script>
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
                                <p>
                                <li class="list-group-item d-flex justify-content-between lh-condensed" style="display:block" >
                                <strong>Copied Admin :</strong>
                                    <?php $allowed_admin = explode(",", $selected_bulletin['allowed_admin']);
                                    foreach($allowed_admin AS $row){
                                        echo $admin_object->get_admin_name_by_code($row)."  :|:  ";
                                    }
                                    ?>
                                </li>
                                </p>
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
                                            <label class="control-label" for="content">Your Remark:</label>
                                            <div>
                                                <textarea  name="comment" type="text" id="content" value="" rows="10" class="form-control" required></textarea>
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
                                                            <span id="trans_remark"><?php echo nl2br($row['comment']);?></span>
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