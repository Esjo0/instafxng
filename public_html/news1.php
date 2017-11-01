<?php
require_once 'init/initialize_general.php';
$thisPage = "About";

$news_id = $_GET['id'];
//$news_id = 544;

if (isset($_POST['post_comment']))
{
    $name = $db_handle->sanitizePost($_POST['name']);
    $email = $db_handle->sanitizePost($_POST['email']);
    $comment = $db_handle->sanitizePost($_POST['comment']);
    $article_id = $db_handle->sanitizePost($_POST['article_id']);

    if(empty($name) || empty($email) || empty($comment)) {
        $message_error = "All fields are compulsory, please try again.";
    } elseif (!check_email($email)) {
        $message_error = "You have provided an invalid email address. Please try again.";
    }  else
    {
        $db_handle->runQuery("INSERT IGNORE INTO article_visitors (email, full_name) VALUES ('$email', '$name')");
        $block_status = $db_handle->runQuery("SELECT block_status FROM article_visitors WHERE email = '$email'");
        $block_status = $db_handle->fetchAssoc($block_status);
        $block_status = $block_status[0];
        $block_status = $block_status['block_status'];

        if ($block_status == 'OFF')
        {
            $fetched_data = $db_handle->fetchAssoc($db_handle->runQuery("SELECT visitor_id FROM article_visitors WHERE email = '$email'"));
            $visitor_id = $fetched_data[0]['visitor_id'];

            $db_handle->runQuery("INSERT INTO article_comments (visitor_id, article_id, comment) VALUES ($visitor_id, '$article_id', '$comment')");

            if($db_handle->affectedRows() > 0) {
                $message_success = "You have successfully added a new comment.";
            } else {
                $message_error = "An error occurred, your comment could not be saved.";
            }
        } else {
            $message_error = "An error occurred, you do not have permission to submit comments.";
        }

    }
}

if (isset($_POST['reply_comment']))
{
    $name = $db_handle->sanitizePost($_POST['name']);
    $email = $db_handle->sanitizePost($_POST['email']);
    $comment = $db_handle->sanitizePost($_POST['comment']);
    $article_id = $db_handle->sanitizePost($_POST['article_id']);
    $comment_id = $db_handle->sanitizePost($_POST['comment_id']);

    if(empty($name) || empty($email) || empty($comment)) {
        $message_error = "All fields are compulsory, please try again.";
    } elseif (!check_email($email)) {
        $message_error = "You have provided an invalid email address. Please try again.";
    }  else
    {
        $db_handle->runQuery("INSERT IGNORE INTO article_visitors (email, full_name) VALUES ('".$email."', '".$name."')");
        $block_status = $db_handle->runQuery("SELECT block_status FROM article_visitors WHERE email = '".$email."';");
        $block_status = $db_handle->fetchAssoc($block_status);
        $block_status = $block_status[0];
        $block_status = $block_status['block_status'];

        if ($block_status == 'OFF')
        {
            $db_handle->runQuery("SELECT @v_id:= visitor_id FROM article_visitors WHERE email = '".$email."';");
            $db_handle->runQuery("INSERT INTO article_comments (visitor_id, article_id, comment, reply_to) VALUES(@v_id, '".$article_id."', '".$comment."', '".$comment_id."')");
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
        $result = $db_handle->runQuery("SELECT * FROM article_comments, article_visitors  WHERE article_comments.visitor_id = article_visitors.visitor_id AND article_comments.article_id = $article_id AND article_comments.status = 'ON' ORDER BY comment_id DESC LIMIT 20");
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
        <style>

            body{
                background:#eee;
            }

            hr {
                margin-top: 20px;
                margin-bottom: 20px;
                border: 0;
                border-top: 1px solid #FFFFFF;
            }
            a {
                text-decoration: none;
            }
            .blog-comment::before,
            .blog-comment::after,
            .blog-comment-form::before,
            .blog-comment-form::after{
                content: "";
                display: table;
                clear: both;
            }

            .blog-comment{
                /*padding-left: 15%;
                padding-right: 15%;*/
            }

            .blog-comment ul{
                list-style-type: none;
                padding: 0;
            }

            .blog-comment img{
                opacity: 1;
                filter: Alpha(opacity=100);
                -webkit-border-radius: 4px;
                -moz-border-radius: 4px;
                -o-border-radius: 4px;
                border-radius: 4px;
            }

            .blog-comment img.avatar {
                position: relative;
                float: left;
                margin-left: 0;
                margin-top: 0;
                width: 65px;
                height: 65px;
            }

            .blog-comment .post-comments{
                border: 1px solid #eee;
                margin-bottom: 20px;
                margin-left: 85px;
                margin-right: 0px;
                padding: 10px 20px;
                position: relative;
                -webkit-border-radius: 4px;
                -moz-border-radius: 4px;
                -o-border-radius: 4px;
                border-radius: 4px;
                background: #fff;
                color: #6b6e80;
                position: relative;
            }

            .blog-comment .meta {
                font-size: 15px;
                color: #000;
                padding-bottom: 8px;
                margin-bottom: 10px !important;
                border-bottom: 1px solid #eee;
            }

            .blog-comment ul.comments ul{
                list-style-type: none;
                padding: 0;
                margin-left: 85px;
            }

            .blog-comment-form{
                padding-left: 15%;
                padding-right: 15%;
                padding-top: 40px;
            }

            .blog-comment h3,
            .blog-comment-form h3{
                margin-bottom: 40px;
                font-size: 26px;
                line-height: 30px;
                font-weight: 800;
            }
        </style>
        <link href='//fonts.googleapis.com/css?family=Average' rel='stylesheet'>
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

                                <h5>Leave a comment</h5>
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
                                                <span class="input-group-addon"><i class="fa fa-comment-o fa-fw"></i></span>
                                                <textarea  name="comment" type="text" id="comment" value="" class="form-control" required></textarea>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <div class="col-sm-offset-3 col-sm-9">
                                            <input type="hidden" name="article_id" value="<?php echo $news_id;?>"/>
                                            <button type="submit" name="post_comment" class="btn btn-success">Post Comment</button>
                                        </div>
                                    </div>
                                </form>
                                <hr class=""/>

                                <?php if(isset($latest_comments)) { ?>
                                <h5>Previous Comments</h5>
                                <div class="container bootstrap snippet">
                                    <div class="row">
                                        <div class="col-md-9">
                                            <div class="blog-comment">

                                    <?php foreach($latest_comments as $row)
                                    {?>
                                        <ul style="font-family: 'Average';font-size: 22px;" class="comments">
                                            <li class="clearfix">
                                                <div class="post-comments">
                                                    <p class="meta"><span style="color: #23527c;" id="trans_remark_date"><i class="glyphicon glyphicon-time"></i><?php echo datetime_to_text($row['created'])?></span>
                                                        <br/>
                                                        <span id="trans_remark_author"><b><?php echo strtoupper($row['full_name']);?></b></span>

                                                        <?php if($row['reply_to'] > 0):
                                                            $query1 = $db_handle->runQuery("SELECT @v_id:= visitor_id FROM article_comments WHERE article_comments.comment_id = '" . $row['reply_to'] . "';");
                                                            $query2 = $db_handle->runQuery("SELECT @a_id:= article_id FROM article_comments WHERE article_comments.comment_id = '" . $row['reply_to'] . "';");
                                                            $reply_details = $db_handle->runQuery("SELECT full_name, title FROM article_visitors, article WHERE article_visitors.visitor_id = @v_id AND article.article_id = @a_id;");
                                                            $reply_details = $db_handle->fetchAssoc($reply_details);
                                                            foreach ($reply_details as $row1)
                                                            {?>
                                                                replying <span id="trans_remark_author"><b><?php echo strtoupper($row1['full_name']);?></b></span>
                                                            <?php }
                                                        endif; ?>
                                                        <i class="pull-right">
                                                            <a  data-target="#reply-comment<?php echo $row['comment_id'];?>" data-toggle="modal" href="#">
                                                                <small>REPLY</small>
                                                            </a>
                                                        </i>
                                                    </p>
                                                    <span id="trans_remark"><?php echo $row['comment'];?></span>
                                                </div>
                                            </li>
                                        </ul>
                                        <!--Modal - confirmation boxes-->
                                        <div id="reply-comment<?php echo $row['comment_id'];?>" tabindex="-1" role="dialog" aria-hidden="true" class="modal fade">
                                            <div class="modal-dialog">
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <button type="button" data-dismiss="modal" aria-hidden="true"
                                                                class="close">&times;</button>
                                                        <h4 class="modal-title">Reply Comment</h4></div>
                                                    <div class="modal-body">
                                                        <p>Reply <?php echo $row['full_name'] ?>'s comment on <?php echo $title?>.</p>

                                                        <form data-toggle="validator" class="form-horizontal " role="form" method="post" action="">
                                                            <div class="form-group">
                                                                <label class="control-label col-sm-3" for="name">Full Name:</label>
                                                                <div class="col-sm-9">
                                                                    <div class="input-group">
                                                                        <span class="input-group-addon"><i class="fa fa-user fa-fw"></i></span>
                                                                        <input name="name" type="text" id="name" value="" class="form-control" required/>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <div class="form-group">
                                                                <label class="control-label col-sm-3" for="email">Email Address:</label>
                                                                <div class="col-sm-9">
                                                                    <div class="input-group">
                                                                        <span class="input-group-addon"><i class="fa fa-envelope fa-fw"></i></span>
                                                                        <input name="email" type="text" id="email" value="" class="form-control" required/>
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
                                                                    <input type="hidden" name="article_id" value="<?php echo $news_id;?>"/>
                                                                    <input type="hidden" name="comment_id" value="<?php echo $row['comment_id'];?>"/>
                                                                    <input type="submit" name="reply_comment" class="btn btn-success" value="Submit Reply"/>
                                                                </div>
                                                            </div>
                                                        </form>
                                                    </div>
                                                    <div class="modal-footer">
                                                        <button type="submit" name="close" onClick="window.close();" data-dismiss="modal" class="btn btn-danger">Close!</button>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    <?php } ?>

                                                </div>
                                            </div>
                                        </div>
                                    </div>
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