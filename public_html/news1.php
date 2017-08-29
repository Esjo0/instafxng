<?php
require_once 'init/initialize_general.php';
$thisPage = "About";

$news_id = $_GET['id'];

if (isset($_POST['post_comment']))
{
    $name = $_POST['name'];
    $email = $_POST['email'];
    $comment = $_POST['comment'];
    $article_id = $_POST['article_id'];

    if(empty($name) || empty($email) || empty($comment)) {
        $message_error = "All fields are compulsory, please try again.";
    } elseif (!check_email($email)) {
        $message_error = "You have provided an invalid email address. Please try again.";
    }  else
    {
        $db_handle->runQuery("INSERT IGNORE INTO visitors (visitor_email, visitor_name) VALUES ('".$email."', '".$name."')");
        $block_status = $db_handle->runQuery("SELECT block_status FROM visitors WHERE visitor_email = '".$email."';");
        $block_status = $db_handle->fetchAssoc($block_status);
        $block_status = $block_status[0];
        $block_status = $block_status['block_status'];

        if ($block_status == 'OFF')
        {
            $db_handle->runQuery("SELECT @v_id:= visitor_id FROM visitors WHERE visitor_email = '".$email."';");
            $db_handle->runQuery("INSERT INTO comments (visitor_id, article_id, comment) VALUES(@v_id, '".$article_id."', '".$comment."')");
        }
        else
        {
            $db_handle->runQuery("SELECT @v_id:= visitor_id FROM visitors WHERE visitor_email = '".$email."';");
            $db_handle->runQuery("INSERT INTO comments (visitor_id, article_id, comment) VALUES(@v_id, '".$article_id."', '".$comment."')");
        }
        $message_success = "You have successfully added a new comment.";
    }
}


if (isset($_POST['reply_comment']))
{
    $name = $_POST['name'];
    $email = $_POST['email'];
    $comment = $_POST['comment'];
    $article_id = $_POST['article_id'];
    $comment_id = $_POST['comment_id'];

    if(empty($name) || empty($email) || empty($comment)) {
        $message_error = "All fields are compulsory, please try again.";
    } elseif (!check_email($email)) {
        $message_error = "You have provided an invalid email address. Please try again.";
    }  else
    {
        $db_handle->runQuery("INSERT IGNORE INTO visitors (visitor_email, visitor_name) VALUES ('".$email."', '".$name."')");
        $block_status = $db_handle->runQuery("SELECT block_status FROM visitors WHERE visitor_email = '".$email."';");
        $block_status = $db_handle->fetchAssoc($block_status);
        $block_status = $block_status[0];
        $block_status = $block_status['block_status'];

        if ($block_status == 'OFF')
        {
            $db_handle->runQuery("SELECT @v_id:= visitor_id FROM visitors WHERE visitor_email = '".$email."';");
            $db_handle->runQuery("INSERT INTO comments (visitor_id, article_id, comment, reply_to) VALUES(@v_id, '".$article_id."', '".$comment."', '".$comment_id."')");
        }
        $message_success = "You have successfully added a reply.";
    }
}


if(strlen($news_id) > 4) {
    header("Location: view_news.php");
} else {
    $query = "SELECT * FROM article WHERE article_id = $news_id LIMIT 1";
    $result = $db_handle->runQuery($query);
    $selected_news = $db_handle->fetchAssoc($result);

    if($db_handle->affectedRows() > 0) {
        extract($selected_news[0]);
        $db_handle->runQuery("UPDATE article SET view_count = view_count + 1 WHERE article_id = {$article_id} LIMIT 1");

        // Select the latest comments
        $result = $db_handle->runQuery("SELECT * FROM comments, visitors  WHERE comments.visitor_id = visitors.visitor_id AND comments.article_id = $article_id AND comments.status = 'ON' ORDER BY comment_id DESC LIMIT 3");
        $latest_comments = $db_handle->fetchAssoc($result);
        
        // Select the latest news
        $result = $db_handle->runQuery("SELECT * FROM article ORDER BY article_id DESC LIMIT  1, 6");
        $latest_news = $db_handle->fetchAssoc($result);
    } else {
        header("Location: view_news.php");
    }
}

?>
<!DOCTYPE html>
<html lang="en">
    <head>
        <base target="_self">
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>Instaforex Nigeria | <?php if(isset($title)) { echo stripslashes($title); } ?></title>
        <meta name="title" content="Instaforex Nigeria | <?php if(isset($title)) { echo stripslashes($title); } ?>" />
        <meta name="keywords" content="instaforex, how to trade forex, trade forex, instaforex nigeria.">
        <meta name="description" content="<?php if(isset($description)) { echo stripslashes($description); } ?>">
        <?php require_once 'layouts/head_meta.php'; ?>
        <link rel="stylesheet" href="css/prettyPhoto.css">
        <style>
            .photo_g > ul, .photo_g > li {
                display: inline;
            }
        </style>
        <meta property="fb:app_id" content="652051961615498" />
    </head>
    <body>
        
        <!-- FB SDK for comment plugin -->
        <div id="fb-root"></div>
        <script>(function(d, s, id) {
          var js, fjs = d.getElementsByTagName(s)[0];
          if (d.getElementById(id)) return;
          js = d.createElement(s); js.id = id;
          js.src = "//connect.facebook.net/en_GB/sdk.js#xfbml=1&version=v2.7";
          fjs.parentNode.insertBefore(js, fjs);
        }(document, 'script', 'facebook-jssdk'));</script>
        <!-- FB SDK ends here -->
        
        <?php require_once 'layouts/header.php'; ?>
        <!-- Main Body: The is the main content area of the web site, contains a side bar  -->
        <div id="main-body" class="container-fluid">
            <div class="row no-gutter">
                <?php require_once 'layouts/topnav.php'; ?>
                <!-- Main Body - Content Area: This is the main content area, unique for each page  -->
                <div id="main-body-content-area" class="col-md-8 col-md-push-4 col-lg-9 col-lg-push-3">
                    
                    <!-- Unique Page Content Starts Here
                    ================================================== -->
                    
                    <div class="section-tint super-shadow">
                        <div class="row">
                            <div class="col-sm-12">
                                <p><a href="blog.php" class="btn btn-default" title="All Blog Post"><i class="fa fa-arrow-circle-left"></i> All Blog Post</a></p>
                                <p>
                                    <em>Posted on <?php if(isset($created)) { echo datetime_to_text($created); } ?></em><br />
                                    <em>Views: <?php if(isset($view_count)) { echo $view_count; } ?></em>
                                </p>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-sm-12"><div class="addthis_sharing_toolbox">Share on social media: </div></div>
                            <div class="col-sm-12 text-danger">
                                <h4><strong><?php if(isset($title)) { echo stripslashes($title); } ?></strong></h4>
                            </div>
                        </div>
                        
                        <div class="row">
                            <div class="col-sm-12">
                                <p><strong><?php if(isset($description)) { echo stripslashes($description); } ?></strong></p>
                                <?php if(isset($content)) { echo htmlspecialchars_decode(stripslashes(trim($content))); } ?>

                                <!--<div class="fb-comments" data-href="https://instafxng.com/news1/id/<?php /*echo $article_id . '/u/' . $url . '/'; */?>" data-numposts="10" data-mobile="1" data-width="100%"></div>-->

                                <form data-toggle="validator" class="form-horizontal " role="form" method="post" action="">
                                    <div class="form-group">
                                        <label class="control-label col-sm-3" for="name">Full Name:</label>
                                        <div class="col-sm-7">
                                            <div class="input-group">
                                                <span class="input-group-addon"><i class="fa fa-user fa-fw"></i></span>
                                                <input name="name" type="text" id="name" value="" class="form-control" required/>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="control-label col-sm-3" for="email">Email Address:</label>
                                        <div class="col-sm-7">
                                            <div class="input-group">
                                                <span class="input-group-addon"><i class="fa fa-envelope fa-fw"></i></span>
                                                <input name="email" type="text" id="email" value="" class="form-control" required/>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="control-label col-sm-3" for="comment">Comment:</label>
                                        <div class="col-sm-7">
                                            <div class="input-group">
                                                <span class="input-group-addon"><i class="fa fa-envelope fa-fw"></i></span>
                                                <textarea  name="comment" type="text" id="comment" value="" class="form-control" required></textarea>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <div class="col-sm-offset-3 col-sm-9">

                                            <input type="hidden" name="article_id" value="<?php echo $news_id;?>"/>
                                            <button type="button" data-target="#confirm-add-comment" data-toggle="modal" class="btn btn-success">Post</button>
                                        </div>
                                    </div>
                                    <!--Modal - confirmation boxes-->
                                    <div id="confirm-add-comment" tabindex="-1" role="dialog" aria-hidden="true" class="modal fade">
                                        <div class="modal-dialog">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <button type="button" data-dismiss="modal" aria-hidden="true"
                                                            class="close">&times;</button>
                                                    <h4 class="modal-title">Post Comment</h4></div>
                                                <div class="modal-body">Are you sure you want to post this comment? This action cannot be reversed.</div>
                                                <div class="modal-footer">
                                                    <input type="submit" name="post_comment" class="btn btn-success" value="Proceed"/>
                                                    <button name="close" onClick="window.close();" data-dismiss="modal" class="btn btn-danger">Close!</button>
                                                </div>
                                            </div>
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
                                            <li>
                                                <?php echo $row['visitor_name']; ?> on <?php echo datetime_to_text($row['created']);?>

                                                <?PHP

                                                if($row['reply_to'] > 0)
                                                {
                                                    $db_handle->runQuery("SELECT @v_id:= visitor_id FROM comments WHERE comments.comment_id = '" . $row['reply_to'] . "';");
                                                    $db_handle->runQuery("SELECT @a_id:= article_id FROM comments WHERE comments.comment_id = '" . $row['reply_to'] . "';");
                                                    $reply_details = $db_handle->runQuery("SELECT visitor_name, title FROM visitors, article WHERE visitors.visitor_id = @v_id AND article.article_id = @a_id;");
                                                    //var_dump($db_handle->runQuery($reply_details));
                                                    $reply_details = $db_handle->fetchAssoc($reply_details);
                                                    foreach ($reply_details as $row1) {
                                                        echo "as a reply to ".$row1['visitor_name'] . "'s comment on ".$row1['title']." ";
                                                    }
                                                }
                                                ?>

                                                said;<br/>
                                                <em>"<?php echo $row['comment'];?>"</em><br/>
                                                <a title="Reply" data-target="#reply-comment<?php echo $row['comment_id'];?>" data-toggle="modal" href="#"  >Reply</a>
                                                <!--Modal - confirmation boxes-->
                                                <div id="reply-comment<?php echo $row['comment_id'];?>" tabindex="-1" role="dialog" aria-hidden="true" class="modal fade">
                                                    <form data-toggle="validator" class="form-horizontal " role="form" method="post" action="">
                                                        <div class="modal-dialog">
                                                            <div class="modal-content">
                                                                <div class="modal-header">
                                                                    <button type="button" data-dismiss="modal" aria-hidden="true"
                                                                            class="close">&times;</button>
                                                                    <h4 class="modal-title">Reply Comment</h4></div>
                                                                <div class="modal-body">
                                                                    <p>Reply <?php echo $row['visitor_name'] ?>'s comment on <?php echo $title?>.</p>
                                                                    <div class="form-group">
                                                                        <div class="col-sm-12">
                                                                            <div class="input-group">
                                                                                <span class="input-group-addon"><i class="fa fa-user fa-fw"></i></span>
                                                                                <input placeholder="Full Name" name="name" type="text" id="name" value="" class="form-control" required/>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                    <div class="form-group">
                                                                        <div class="col-sm-12">
                                                                            <div class="input-group">
                                                                                <span class="input-group-addon"><i class="fa fa-envelope fa-fw"></i></span>
                                                                                <input placeholder="Email Address" name="email" type="text" id="email" value="" class="form-control" required/>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                    <div class="form-group">
                                                                        <div class="col-sm-12">
                                                                            <div class="input-group">
                                                                                <span class="input-group-addon"><i class="fa fa-envelope fa-fw"></i></span>
                                                                                <textarea placeholder="Comment"  name="comment" type="text" id="comment" value="" class="form-control" required></textarea>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                                <div class="modal-footer">
                                                                    <input type="hidden" name="article_id" value="<?php echo $news_id;?>"/>
                                                                    <input type="hidden" name="comment_id" value="<?php echo $row['comment_id'];?>"/>
                                                                    <input type="submit" name="reply_comment" class="btn btn-success" value="Proceed"/>
                                                                    <button name="close" onClick="window.close();" data-dismiss="modal" class="btn btn-danger">Close!</button>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </form>
                                                </div>

                                                <br/>
                                            </li>
                                        <?php } ?>
                                    </ul>
                                <?php } ?>

                                <hr class=""/>


                                <?php if(isset($latest_news)) { ?>
                                <h5>Latest News</h5>
                                <ul class="fa-ul">
                                <?php foreach($latest_news as $row) { ?>
                                    <li><i class="fa-li fa fa-check-square-o icon-tune"></i> <a href="news1/id/<?php echo $row['article_id'] . '/u/' . $row['url'] . '/'; ?>"><?php echo $row['title']; ?></a></li>
                                <?php } ?>
                                </ul>
                                <?php } ?>
                            </div>
                        </div>
                    </div>

                    <!-- Unique Page Content Ends Here
                    ================================================== -->
                    
                </div>
                <!-- Main Body - Side Bar  -->
                <div id="main-body-side-bar" class="col-md-4 col-md-pull-8 col-lg-3 col-lg-pull-9 left-nav">
                <?php require_once 'layouts/sidebar.php'; ?>
                </div>
            </div>
        </div>
        <?php require_once 'layouts/footer.php'; ?>
        <script type="text/javascript" src="//s7.addthis.com/js/300/addthis_widget.js#pubid=ra-524a5c1c01999da0" async="async"></script>

        <script type="text/javascript" charset="utf-8">
            $(document).ready(function(){
                $("area[rel^='prettyPhoto']").prettyPhoto();

                $(".gallery:first a[rel^='prettyPhoto']").prettyPhoto({animation_speed:'normal',theme:'light_square',slideshow:3000, autoplay_slideshow: false});
                $(".gallery:gt(0) a[rel^='prettyPhoto']").prettyPhoto({animation_speed:'fast',slideshow:10000, hideflash: true});

                $("#custom_content a[rel^='prettyPhoto']:first").prettyPhoto({
                    custom_markup: '<div id="map_canvas" style="width:260px; height:265px"></div>',
                    changepicturecallback: function(){ initialize(); }
                });

                $("#custom_content a[rel^='prettyPhoto']:last").prettyPhoto({
                    custom_markup: '<div id="bsap_1259344" class="bsarocks bsap_d49a0984d0f377271ccbf01a33f2b6d6"></div><div id="bsap_1237859" class="bsarocks bsap_d49a0984d0f377271ccbf01a33f2b6d6" style="height:260px"></div><div id="bsap_1251710" class="bsarocks bsap_d49a0984d0f377271ccbf01a33f2b6d6"></div>',
                    changepicturecallback: function(){ _bsap.exec(); }
                });
            });
        </script>
        <script src="js/jquery.prettyPhoto.js"></script>
    </body>
</html>