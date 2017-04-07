<?php
require_once '../init/initialize_general.php';
$thisPage = "";

$query = "SELECT * FROM miss_tourism_lagos ORDER BY contest_id ASC ";
$numrows = $db_handle->numRows($query);

$rowsperpage = 3;

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
$all_contestants = $db_handle->fetchAssoc($result);

?>
<!DOCTYPE html>
<html lang="en">
    <head>
        <base target="_self">
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>Instaforex Nigeria | Miss Toursism Lagos</title>
        <meta name="title" content="" />
        <meta name="keywords" content="instaforex nigeria, miss tourism nigeria, miss tourims lagos, miss tourism lagos 2016, tourism in nigeria, whispering palms, badagry tourism, tourist sites in nigeria" />
        <meta name="description" content="" />
        <link rel="stylesheet" href="../css/tourism.css">
        <link rel="stylesheet" href="../css/prettyPhoto.css">
        <style>
            .photo_g > ul, .photo_g > li {
                display: inline;
            }
        </style>
        <?php require_once '../layouts/head_meta.php'; ?>
    </head>
    <body>
        <?php require_once '../layouts/header.php'; ?>
        <!-- Main Body: The is the main content area of the web site, contains a side bar  -->
        <div id="main-body" class="container-fluid">
            <div class="row no-gutter">
                <?php require_once '../layouts/topnav.php'; ?>
                <!-- Main Body - Content Area: This is the main content area, unique for each page  -->
                <div id="main-body-content-area" class="col-md-8 col-md-push-4 col-lg-9 col-lg-push-3">
                    
                    <!-- Unique Page Content Starts Here
                    ================================================== -->
                    
                    <div class="super-shadow page-top-section">
                        <div class="row ">
                            <div class="col-sm-8">
                                <h4><strong>MEET THE CONTESTANTS OF MISS TOURISM LAGOS 2016</strong></h4>
                                <p>After weeks of auditioning at various locations in Lagos State and thorough screening by the judges, we present the contestants of Miss Tourism Lagos 2016</p>
                                <p class="text-danger">Miss Tourism Lagos 2016 is sponsored by Instaforex Nigeria</p>
                            </div>
                            <div class="col-sm-4">
                                <img src="images/miss-tourism-logo.png" alt="" class="img-responsive" />
                            </div>
                        </div>
                    </div>
                    
                    <div class="text-center" style="margin-bottom: 10px;">
                        <a href="tourism/" title="Meet Miss Tourism Queen" class="btn btn-primary btn-lg">Meet Miss Tourism Queen</a>
                    </div>
                    
                    <div class="section-tint super-shadow">
                        
                        <?php $display = 1; foreach ($all_contestants as $row) {
                            extract($row);
                        ?>
                        
                        <!-- Each contestant -->
                        <div>
                            <div class="row">
                                <div class="col-sm-12"><h5>Contestant <?php echo $contest_id; ?></h5></div>
                            </div>
                            <div class="row">
                                <div class="col-sm-3">
                                    <img src="images/miss-tourism-2016/contestant-<?php echo $contest_id; ?>/display.jpg" alt="" class="img-responsive" />
                                </div>
                                <div class="col-sm-9">
                                    <h5 class="t-name-tag"><?php echo $full_name; ?></h5>
                                    <p><strong>Age:</strong> <?php echo $age; ?> &nbsp;&nbsp; <strong>Height:</strong> <?php echo $height; ?></p>
                                    <p><strong>School:</strong> <?php echo $school; ?></p>
                                    <p><strong>Favorite Food:</strong> <?php echo $fav_food; ?></p>
                                    <p><strong>Hobby:</strong> <?php echo $hobby; ?></p>
                                    <p><strong>Ambition:</strong> <?php echo $ambition; ?></p>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-sm-12">
                                    <br/>
                                    <ul class="gallery clearfix photo_g" style="padding-left: 0 !important;">
                                        
                                        <?php $count = 1; while ($count <= $image_count) { ?>
                                        <li><a href="images/miss-tourism-2016/contestant-<?php echo $contest_id; ?>/<?php echo $image_name . $count; ?>.jpg" rel="prettyPhoto[gallery2]" title=""><img src="images/miss-tourism-2016/contestant-<?php echo $contest_id; ?>/thumbnails/<?php echo $image_name . $count; ?>.jpg" alt=" " /></a></li>
                                        <?php $count++; } ?>
                                        
                                    </ul>
                                </div>
                            </div>
                        </div>
                        <hr/>
                        <?php $display++; } ?>
                        
                        <?php require '../layouts/pagination_links.php'; ?>
                        <p>The Miss Tourism Lagos 2016 was made possible by MLK Events and Promotions 
                            with support from Instant Web-Net Technologies Limited who is the official 
                            sponsor for the pageant and Lagos State Ministry of Arts, Culture and Tourism. </p>
                    </div>

                    <!-- Unique Page Content Ends Here
                    ================================================== -->
                    
                </div>
                <!-- Main Body - Side Bar  -->
                <div id="main-body-side-bar" class="col-md-4 col-md-pull-8 col-lg-3 col-lg-pull-9 left-nav">
                <?php require_once '../layouts/sidebar.php'; ?>
                </div>
            </div>
        </div>
        <?php require_once '../layouts/footer.php'; ?>
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