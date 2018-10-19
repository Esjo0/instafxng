<?php
require_once 'init/initialize_general.php';
$thisPage = "Articles";

$query = "SELECT * FROM article WHERE status = 1 ORDER BY type ASC ";
$numrows = $db_handle->numRows($query);

$rowsperpage = 10;

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
$all_news_items = $db_handle->fetchAssoc($result);
?>
<!DOCTYPE html>
<html lang="en">
    <head>
        <base target="_self">
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>Instaforex Nigeria | News Centre</title>
        <meta name="title" content="Instaforex Nigeria | News Centre" />
        <meta name="keywords" content="instaforex, instaforex news, how to trade forex, trade forex, instaforex nigeria.">
        <meta name="description" content="Instaforex Nigeria News Centre, be updated with the latest happenings in our company.">
        <?php require_once 'layouts/head_meta.php'; ?>
    </head>
    <body>
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
                            <div class="col-sm-12 text-center">
                                <h3><strong>Blog - Articles, News, How - to</strong></h3>
                                <br />
                                <div class="panel panel-default col-sm-3" style="background-color: floralwhite">
                                    <a href="blog.php"><div class="panel-body btn-default" title="Click to View All Blog post"><strong>All BLOG POSTS</strong></div></a>
                                </div>
                                <div class="panel panel-default col-sm-3" style="background-color: floralwhite">
                                    <a href="articles.php"><div class="panel-body btn-default" title="Click to View All Ariticles"><strong>ARTICLES</strong></div></a>
                                </div>
                                <div class="panel panel-default col-sm-3">
                                    <a href="fxcalendar.php"><div class="panel-body btn-default" title="Click to View All Forex News"><strong>NEWS CALENDAR</strong></div></a>
                                </div>
                                <div class="panel panel-default col-sm-3" >
                                    <a href="extras.php"><div class="panel-body btn-default" title="Click to View Extras"><strong>EXTRAS</strong></div></a>
                                </div>
                            </div>
                        </div>
                        
                        <div class="row">
                            <div class="col-sm-8">

                                <?php if(isset($all_news_items) && !empty($all_news_items)) { foreach ($all_news_items as $row) { extract($row); ?>
                                <div class="row news_list">
                                    <div class="col-sm-12"><h4><strong><a title="Click to read" class="title_link" href="news1/id/<?php echo $article_id . '/u/' . $url . '/'; ?>"><?php echo $title; ?></a></strong></h4></div>
                                    <div class="col-sm-5">
                                        <?php if(file_exists("images/blog/$display_image")) { ?>
                                            <img class="img-responsive center-block" alt="" src="https://instafxng.com/images/blog/<?php echo $display_image; ?>" />
                                        <?php } else { ?>
                                            <img class="img-responsive center-block" alt="" src="https://instafxng.com/images/placeholder2.jpg" />
                                        <?php } ?>
                                    </div>

                                    <div class="col-sm-7">
                                        <section>
                                            <p>
                                                <?php echo $description; ?>
                                                &nbsp;<span><a href="news1/id/<?php echo $article_id . '/u/' . $url . '/'; ?>">Read On!</a></span>
                                            </p>
                                            <small><em><strong>Posted:</strong> <?php echo datetime_to_text($created); ?> &nbsp;&nbsp; <strong>Views:</strong> <?php if( isset($view_count) ) { echo $view_count; } else { echo 0; } ?></em></small>
                                        </section>
                                    </div>
                                </div>
                                <?php } } else { echo "<p><em>No results to display</em></p>"; } ?>

                                <?php if(isset($all_news_items) && !empty($all_news_items)) { require_once 'layouts/pagination_links.php'; } ?>
                            </div>

                            <div class="col-sm-4"></div>
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