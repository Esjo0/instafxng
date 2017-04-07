<?php
require_once 'init/initialize_general.php';
$thisPage = "About";

$news_id = $_GET['id'];

if(strlen($news_id) > 4) {
    header("Location: view_news.php");
} else {
    $query = "SELECT * FROM article WHERE article_id = $news_id LIMIT 1";
    $result = $db_handle->runQuery($query);
    $selected_news = $db_handle->fetchAssoc($result);

    if($db_handle->affectedRows() > 0) {
        extract($selected_news[0]);
        $db_handle->runQuery("UPDATE article SET view_count = view_count + 1 WHERE article_id = {$article_id} LIMIT 1");
        
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

                                <div class="fb-comments" data-href="https://instafxng.com/news1/id/<?php echo $article_id . '/u/' . $url . '/'; ?>" data-numposts="10" data-mobile="1" data-width="100%"></div>
                                
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