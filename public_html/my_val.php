<?php
require_once 'init/initialize_general.php';
$thisPage = "Promo";

$news_id = $_GET['id'];

if(strlen($news_id) > 4) {
    header("Location: index.php");
} else {
    $query = "SELECT CONCAT(u.last_name, SPACE(1), u.first_name) AS full_name, uv2.val_pics, uv2.user_val_2017_id
          FROM user_val_2017 AS uv2
          INNER JOIN user AS u ON uv2.user_code = u.user_code
          WHERE user_val_2017_id = $news_id LIMIT 1";

    $result = $db_handle->runQuery($query);
    $selected_details = $db_handle->fetchAssoc($result);

    if($db_handle->affectedRows() > 0) {
        //
        extract($selected_details[0]);
    } else {
        header("Location: index.php");
    }
}

?>
<!DOCTYPE html>
<html lang="en">
    <head>
        <base target="_self">
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>Instaforex Nigeria | Who’s your Valentine Contest</title>
        <meta name="title" content="Instaforex Nigeria | Who’s your Valentine Contest" />
        <meta name="keywords" content="instaforex, how to trade forex, trade forex, instaforex nigeria, instafxng valentine contest, instaforex valentine contest.">
        <meta name="description" content="I entered the InstaFxNg.com Who's Your Valentine Contest. Kindly help me win $20 by clicking on this link to like my picture.">
        <?php require_once 'layouts/head_meta.php'; ?>

        <meta property="fb:app_id" content="652051961615498" />

        <meta property="og:url"           content="https://instafxng.com/my_val/id/<?php echo $user_val_2017_id; ?>/" />
        <meta property="og:type"          content="website" />
        <meta property="og:title"         content="Instaforex Nigeria | Who’s your Valentine Contest" />
        <meta property="og:description"   content="I entered the InstaFxNg.com Who's Your Valentine Contest. Kindly help me win $20 by clicking on this link to like my picture." />
        <meta property="og:image"         content="https://instafxng.com/images/val-pics/<?php echo $val_pics; ?>" />

    </head>
    <body>

        <div id="fb-root"></div>
        <script>(function(d, s, id) {
                var js, fjs = d.getElementsByTagName(s)[0];
                if (d.getElementById(id)) return;
                js = d.createElement(s); js.id = id;
                js.src = "//connect.facebook.net/en_GB/sdk.js#xfbml=1&version=v2.8&appId=652051961615498";
                fjs.parentNode.insertBefore(js, fjs);
            }(document, 'script', 'facebook-jssdk'));</script>
        
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
                                <div class="col-sm-8 text-center">
                                    <div class="fb-like" data-href="https://instafxng.com/my_val/id/<?php echo $user_val_2017_id; ?>/" data-layout="box_count" data-action="like" data-size="large" data-show-faces="false" data-share="true"></div>
                                    <h3>Instaforex Nigeria Who's Your Valentine Contest</h3>
                                    <p><em>Like my picture to help me win</em></p>
                                    <h4>Name: <strong><?php if(isset($full_name)) { echo stripslashes($full_name); } ?></strong></h4>
                                    <p><img class="img-responsive center-block" src="https://instafxng.com/images/val-pics/<?php echo $val_pics; ?>" alt="" /></p>
                                </div>
                                <div class="col-sm-4">
                                    <br />
                                    <div class="super-shadow">
                                        <header class="text-center" style="color: maroon; background-color: #E4E7EC; border-color: #CCCCCC; padding: 5px;">
                                            <h4><strong>A Proven Pathway to Multiple Streams of Income</strong></h4>
                                        </header>
                                        <article class="text-center" style="background-color: #FFF; border-color: #CCCCCC; padding: 10px;">
                                            <p><strong>Would you like to make more money from ANOTHER
                                                    SOURCE OF INCOME and get to where you want to be financially?</strong></p>
                                            <p><strong>What are you waiting for? Take a bold step!
                                                    <a href="https://instafxng.com/forex-income/">Click here now</a> to join the money making team...
                                                    Take action now!</strong></p>
                                        </article>
                                    </div>
                                </div>

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
    </body>
</html>