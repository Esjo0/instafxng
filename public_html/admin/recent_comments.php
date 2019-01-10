<?php
require_once("../init/initialize_admin.php");
if (!$session_admin->is_logged_in()) {
    redirect_to("login.php");
}

if (isset($_POST['reply_comment']))
{
    $name = $db_handle->sanitizePost($_POST['name']);
    $email = $db_handle->sanitizePost($_POST['email']);
    $comment = $db_handle->sanitizePost($_POST['comment']);
    $article_id = $db_handle->sanitizePost($_POST['article_id']);
    $article_title = $db_handle->sanitizePost($_POST['article_title']);
    $article_url = $db_handle->sanitizePost($_POST['article_url']);
    $comment_id = $db_handle->sanitizePost($_POST['comment_id']);

    if(empty($name) || empty($email) || empty($comment)) {
        $message_error = "All fields are compulsory, please try again.";
    } elseif (!check_email($email)) {
        $message_error = "You have provided an invalid email address. Please try again.";
    }  else
    {
        $db_handle->runQuery("INSERT IGNORE INTO article_visitors (email, full_name) VALUES ('".$email."', '".$name."')");
        $db_handle->runQuery("SELECT @v_id:= visitor_id FROM article_visitors WHERE email = '".$email."';");
        $db_handle->runQuery("INSERT INTO article_comments (visitor_id, article_id, comment, reply_to, status) VALUES(@v_id, '".$article_id."', '".$comment."', '".$comment_id."', 'ON')");

        $admin_name = $name;
        $poster_details = $db_handle->fetchAssoc($db_handle->runQuery("SELECT full_name, email FROM article_visitors, article_comments WHERE comment_id = '$comment_id' AND article_visitors.visitor_id = article_comments.visitor_id "));
        $poster_details = $poster_details[0];
        $name = explode(' ', $poster_details['full_name']);
        $name = $name[0];
        $subject = strtoupper($name).", Your Comment Has Been Replied";
        $message_final = <<<MAIL
                    <div style="background-color: #F3F1F2">
                        <div style="max-width: 80%; margin: 0 auto; padding: 10px; font-size: 14px; font-family: Verdana;">
                            <img src="https://instafxng.com/images/ifxlogo.png" />
                            <hr />
                            <div style="background-color: #FFFFFF; padding: 15px; margin: 5px 0 5px 0;">
                                <p>Dear $name,</p>
                                <p>Your comment on '$article_title' has been replied.</p>                                                              
                                <p><a href="$article_url">Click here to view all the replies to your comments on this article.</a></p>
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
                                    <p><strong>Lekki Office Address:</strong> Road 5, Suite K137, Ikota Shopping Complex, Lekki/Ajah Express Road, Lagos State</p>
                                    <p><strong>Office Number:</strong> 08028281192</p>
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
        $system_object->send_email($subject, $message_final, $poster_details['email'], $name,$admin_name);
        $message_success = "You have successfully added a reply.";
    }
}

$query = "SELECT * FROM article, article_visitors, article_comments
            WHERE article.article_id = article_comments.article_id
            AND article_visitors.visitor_id = article_comments.visitor_id
            AND article_visitors.block_status = 'OFF'
            AND article_comments.status = 'OFF'
            ORDER BY comment_id DESC ";
$numrows = $db_handle->numRows($query);

$rowsperpage = 20;

$totalpages = ceil($numrows / $rowsperpage);
// get the current page or set a default
if (isset($_GET['pg']) && is_numeric($_GET['pg'])) {
    $currentpage = (int) $_GET['pg'];
} else {
    $currentpage = 1;
}
if ($currentpage > $totalpages) { $currentpage = $totalpages; }
if ($currentpage < 1) { $currentpage = 1; }

$prespagelow = $currentpage * $rowsperpage - $rowsperpage + 1;
$prespagehigh = $currentpage * $rowsperpage;
if($prespagehigh > $numrows) { $prespagehigh = $numrows; }

$offset = ($currentpage - 1) * $rowsperpage;
$query .= 'LIMIT ' . $offset . ',' . $rowsperpage;
$result = $db_handle->runQuery($query);
$all_comments_items = $db_handle->fetchAssoc($result);


if (isset($_POST['delete_comment']))
{
    $query = "DELETE FROM article_comments
                  WHERE comment_id = '".$_POST['comment_id']."'
                  AND visitor_id = '".$_POST['visitor_id']."';";
    $result = $db_handle->runQuery($query);
}

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <base target="_self">
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Instaforex Nigeria | Admin - View Recent Comments</title>
    <meta name="title" content="Instaforex Nigeria | Admin - View Recent Comments" />
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

            <div class="row">
                <div class="col-sm-12 text-danger">
                    <h4><strong>VIEW RECENT COMMENTS</strong></h4>
                </div>
            </div>

            <div class="section-tint super-shadow">
                <div class="row">
                    <div class="col-sm-12">
                        <?php require_once 'layouts/feedback_message.php'; ?>

                        <p>Below is the list of all the recent comments and articles.</p>
                        <table class="table table-striped table-bordered table-hover">
                            <thead>
                            <tr>
                                <th>Article Title</th>
                                <th>Name / Email</th>
                                <th>Comment</th>
                                <th>Created</th>
                                <th>Action</th>
                            </tr>
                            </thead>
                            <tbody>
                            <?php if(isset($all_comments_items) && !empty($all_comments_items)) {
                                foreach ($all_comments_items as $row) { ?>
                                    <tr>
                                        <td><a href="../news1/id/<?php echo $row['article_id'] . '/u/' . $row['url'] . '/'; ?>" target="_blank" title="Visit Article Page"><?php echo $row['title']; ?></a></td>
                                        <td><strong><?php echo $row['full_name']; ?></strong><br /><br />
                                            <?php echo $row['email']; ?></td>
                                        <td><?php echo $row['comment']; ?></td>
                                        <td><?php echo datetime_to_text($row['created']); ?></td>
                                        <td nowrap>
                                            <button type="button" data-target="#reply-comment<?php echo $row['comment_id']; ?>" data-toggle="modal" class="btn btn-info"><i class="glyphicon glyphicon-comment icon-white"></i> </button>
                                            <!--Modal - confirmation boxes-->
                                            <div id="reply-comment<?php echo $row['comment_id'];?>" tabindex="-1" role="dialog" aria-hidden="true" class="modal fade">
                                                <div class="modal-dialog">
                                                    <div class="modal-content">
                                                        <div class="modal-header">
                                                            <button title="Reply Comment" type="button" data-dismiss="modal" aria-hidden="true" class="close">&times;</button>
                                                            <h4 class="modal-title">Reply Comment</h4>
                                                        </div>
                                                        <form data-toggle="validator" class="form-horizontal " role="form" method="post" action="">
                                                            <div class="modal-body">
                                                                <p>Reply <?php echo $row['full_name'] ?>'s comment on <?php echo $title?>.</p>


                                                                <div class="form-group">
                                                                    <label class="control-label col-sm-3" for="name">Display Name:</label>
                                                                    <div class="col-sm-9">
                                                                        <div class="input-group">
                                                                            <span class="input-group-addon"><i class="fa fa-user fa-fw"></i></span>
                                                                            <input name="name" type="text" id="name" value="Instaforex NG" class="form-control" disabled required/>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                                <div class="form-group">
                                                                    <label class="control-label col-sm-3" for="email">Display Email Address:</label>
                                                                    <div class="col-sm-9">
                                                                        <div class="input-group">
                                                                            <span class="input-group-addon"><i class="fa fa-envelope fa-fw"></i></span>
                                                                            <input name="email" type="text" id="email" value="Support@instafxng.com" disabled class="form-control" required/>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                                <div class="form-group">
                                                                    <label class="control-label col-sm-3" for="comment">Comment:</label>
                                                                    <div class="col-sm-9">
                                                                        <div class="input-group">
                                                                            <span class="input-group-addon"><i class="fa fa-comment-o fa-fw"></i></span>
                                                                            <textarea  name="comment" type="text" id="comment" value="" class="form-control" required></textarea>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                                <div class="form-group">
                                                                    <div class="col-sm-offset-3 col-sm-9">
                                                                        <input name="email" type="hidden" id="email" value="Support@instafxng.com" class="form-control" required/>
                                                                        <input name="name" type="hidden" id="name" value="Instaforex NG" class="form-control" required/>
                                                                        <input type="hidden" name="article_id" value="<?php echo $row['article_id'];?>"/>
                                                                        <input type="hidden" name="comment_id" value="<?php echo $row['comment_id'];?>"/>
                                                                        <input type="hidden" name="article_title" value="<?php echo $row['title']; ?>"/>
                                                                        <input type="hidden" name="article_url" value="https://instafxng.com/news1/id/<?php echo $row['article_id'] . '/u/' . $row['url'] . '/'; ?>"/>
                                                                    </div>
                                                                </div>

                                                            </div>
                                                            <div class="modal-footer">
                                                                <input type="submit" name="reply_comment" class="btn btn-success" value="Submit Reply"/>
                                                                <button type="submit" name="close" onClick="window.close();" data-dismiss="modal" class="btn btn-danger">Close!</button>
                                                            </div>
                                                        </form>
                                                    </div>
                                                </div>
                                            </div>


                                            <!-- Approve Comment Section -------------->
                                            <button title="Approve Comment" type="button" data-target="#confirm-approve-comment<?php echo $row['comment_id']; ?>" data-toggle="modal" class="btn btn-success"><i class="glyphicon glyphicon-ok icon-white"></i></button>
                                            <!--Modal - confirmation boxes-->
                                            <div id="confirm-approve-comment<?php echo $row['comment_id']; ?>" tabindex="-1" role="dialog" aria-hidden="true" class="modal fade">
                                                <div class="modal-dialog">
                                                    <div class="modal-content">
                                                        <div class="modal-header">
                                                            <button type="button" data-dismiss="modal" aria-hidden="true"
                                                                    class="close">&times;</button>
                                                            <h4 class="modal-title">Approve Comment</h4></div>
                                                        <div class="modal-body">
                                                            Are you sure you want to approve this comment?
                                                            This action cannot be reversed.
                                                        </div>
                                                        <div class="modal-footer">
                                                            <a title="Approve Comment" class="btn btn-success" href="approve_comment.php?data=<?php echo $row['comment_id']; ?>">Proceed</a>
                                                            <button type="submit" name="close" onClick="window.close();" data-dismiss="modal" class="btn btn-danger">Close!</button>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>


                                            <!-- Delete Comment Section ------------->
                                            <button  title="Delete Comment" type="button" data-target="#confirm-delete-comment<?php echo $row['comment_id']; ?>" data-toggle="modal" class="btn btn-danger"><i class="glyphicon glyphicon-remove icon-white"></i> </button>
                                            <!--Modal - confirmation boxes-->
                                            <div id="confirm-delete-comment<?php echo $row['comment_id']; ?>" tabindex="-1" role="dialog" aria-hidden="true" class="modal fade">
                                                <div class="modal-dialog">
                                                    <div class="modal-content">
                                                        <div class="modal-header">
                                                            <button type="button" data-dismiss="modal" aria-hidden="true"
                                                                    class="close">&times;</button>
                                                            <h4 class="modal-title">Delete Comment</h4></div>
                                                        <div class="modal-body">
                                                            Are you sure you want to delete this comment?
                                                            This action cannot be reversed.
                                                        </div>
                                                        <div class="modal-footer">
                                                            <a title="Delete Comment" class="btn btn-primary" href="delete_comment.php?data=<?php echo $row['comment_id']; ?>">Proceed</a>
                                                            <button type="submit" name="close" onClick="window.close();" data-dismiss="modal" class="btn btn-danger">Close!</button>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>

                                        </td>
                                    </tr>
                                <?php } } else { echo "<tr><td colspan='5' class='text-danger'><em>No new comments on this platform.</em></td></tr>"; } ?>
                            </tbody>
                        </table>


                        <?php if(isset($all_comments_items) && !empty($all_comments_items)) { ?>
                            <div class="tool-footer text-right">
                                <p class="pull-left">Showing <?php echo $prespagelow . " to " . $prespagehigh . " of " . $numrows; ?> entries</p>
                            </div>
                        <?php } ?>

                    </div>
                </div>

                <?php if(isset($all_comments_items) && !empty($all_comments_items)) { require_once 'layouts/pagination_links.php'; } ?>
            </div>

            <!-- Unique Page Content Ends Here
            ================================================== -->

        </div>

    </div>
</div>
<?php require_once 'layouts/footer.php'; ?>
</body>
</html>