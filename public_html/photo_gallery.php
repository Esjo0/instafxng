<?php
require_once 'init/initialize_general.php';
$thisPage = "About";
?>
<!DOCTYPE html>
<html lang="en">
    <head>
        <base target="_self">
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>Instaforex Nigeria | Photo Gallery</title>
        <meta name="title" content="Instaforex Nigeria | Photo Gallery" />
        <meta name="keywords" content="instaforex, how to trade forex, trade forex, instaforex nigeria, forex pictures, instaforex photo gallery">
        <meta name="description" content="">
        <?php require_once 'layouts/head_meta.php'; ?>
        <link rel="stylesheet" href="css/prettyPhoto.css">
        <style>
            .photo_g > ul, .photo_g > li {
                display: inline;
            }
        </style>
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
                            <div class="col-sm-12 text-danger">
                                <h4><strong>Instaforex Nigeria - Photo Gallery</strong></h4>
                            </div>
                        </div>
                        
                        <div class="row">
                            <div class="col-sm-12">
                                <p>See photos of our company events below</p>

                                <h5>Moments from Pencil Unbroken 3(The Evolution)</h5>
                                <p></p>
                                <ul class="gallery clearfix photo_g">
                                    <?php
                                    for($i = 1; $i <= 53; $i++){
                                        ?>
                                        <li><a href="https://instafxng.com/imgsource/pencil_unbroken_images/pencil_unbroken_3_<?php echo $i;?>.jpg" rel="prettyPhoto[gallery4]" title=""><img class="img img-thumbnail" src="https://instafxng.com/imgsource/pencil_unbroken_images/thumbnail/pencil_unbroken_3_<?php echo $i;?>.jpg" alt=" " /></a></li>
                                    <?php }?>
                                </ul>

                                <h5>InstaFxNg 2018 Mid-Year Retreat</h5>
                                <p></p>
                                <ul class="gallery clearfix photo_g">
                                    <?php
                                    for($i = 1; $i <= 59; $i++){
                                    ?>
                                    <li><a href="https://instafxng.com/imgsource/2018_Retreat/instafxng_2018_retreat_<?php echo $i;?>.jpg" rel="prettyPhoto[gallery4]" title=""><img class="img img-thumbnail" src="https://instafxng.com/imgsource/2018_Retreat/thumbnail/instafxng_2018_retreat_<?php echo $i;?>.jpg" alt=" " /></a></li>
                                    <?php }?>
                                </ul>

                                <h5>2017 End of the Year Dinner at Four Points by Sheraton, VI, Lagos.(The Ethinc Impression Edition) - December 17, 2017</h5>
                                <p></p>
                                <ul class="gallery clearfix photo_g">
                                    <li><a href="https://instafxng.com/images/dinner-2017/dinner-2017-1.jpg" rel="prettyPhoto[gallery4]" title=""><img src="https://instafxng.com/images/dinner-2017/thumbnails/dinner-2017-1.jpg" alt=" " /></a></li>
                                    <li><a href="https://instafxng.com/images/dinner-2017/dinner-2017-2.jpg" rel="prettyPhoto[gallery4]" title=""><img src="https://instafxng.com/images/dinner-2017/thumbnails/dinner-2017-2.jpg" alt=" " /></a></li>
                                    <li><a href="https://instafxng.com/images/dinner-2017/dinner-2017-3.jpg" rel="prettyPhoto[gallery4]" title=""><img src="https://instafxng.com/images/dinner-2017/thumbnails/dinner-2017-3.jpg" alt=" " /></a></li>
                                    <li><a href="https://instafxng.com/images/dinner-2017/dinner-2017-4.jpg" rel="prettyPhoto[gallery4]" title=""><img src="https://instafxng.com/images/dinner-2017/thumbnails/dinner-2017-4.jpg" alt=" " /></a></li>
                                    <li><a href="https://instafxng.com/images/dinner-2017/dinner-2017-5.jpg" rel="prettyPhoto[gallery4]" title=""><img src="https://instafxng.com/images/dinner-2017/thumbnails/dinner-2017-5.jpg" alt=" " /></a></li>
                                    <li><a href="https://instafxng.com/images/dinner-2017/dinner-2017-6.jpg" rel="prettyPhoto[gallery4]" title=""><img src="https://instafxng.com/images/dinner-2017/thumbnails/dinner-2017-6.jpg" alt=" " /></a></li>
                                    <li><a href="https://instafxng.com/images/dinner-2017/dinner-2017-7.jpg" rel="prettyPhoto[gallery4]" title=""><img src="https://instafxng.com/images/dinner-2017/thumbnails/dinner-2017-7.jpg" alt=" " /></a></li>
                                    <li><a href="https://instafxng.com/images/dinner-2017/dinner-2017-8.jpg" rel="prettyPhoto[gallery4]" title=""><img src="https://instafxng.com/images/dinner-2017/thumbnails/dinner-2017-8.jpg" alt=" " /></a></li>
                                    <li><a href="https://instafxng.com/images/dinner-2017/dinner-2017-9.jpg" rel="prettyPhoto[gallery4]" title=""><img src="https://instafxng.com/images/dinner-2017/thumbnails/dinner-2017-9.jpg" alt=" " /></a></li>
                                    <li><a href="https://instafxng.com/images/dinner-2017/dinner-2017-10.jpg" rel="prettyPhoto[gallery4]" title=""><img src="https://instafxng.com/images/dinner-2017/thumbnails/dinner-2017-10.jpg" alt=" " /></a></li>

                                    <li><a href="https://instafxng.com/images/dinner-2017/dinner-2017-11.jpg" rel="prettyPhoto[gallery4]" title=""><img src="https://instafxng.com/images/dinner-2017/thumbnails/dinner-2017-11.jpg" alt=" " /></a></li>
                                    <li><a href="https://instafxng.com/images/dinner-2017/dinner-2017-12.jpg" rel="prettyPhoto[gallery4]" title=""><img src="https://instafxng.com/images/dinner-2017/thumbnails/dinner-2017-12.jpg" alt=" " /></a></li>
                                    <li><a href="https://instafxng.com/images/dinner-2017/dinner-2017-13.jpg" rel="prettyPhoto[gallery4]" title=""><img src="https://instafxng.com/images/dinner-2017/thumbnails/dinner-2017-13.jpg" alt=" " /></a></li>
                                    <li><a href="https://instafxng.com/images/dinner-2017/dinner-2017-14.jpg" rel="prettyPhoto[gallery4]" title=""><img src="https://instafxng.com/images/dinner-2017/thumbnails/dinner-2017-14.jpg" alt=" " /></a></li>
                                    <li><a href="https://instafxng.com/images/dinner-2017/dinner-2017-15.jpg" rel="prettyPhoto[gallery4]" title=""><img src="https://instafxng.com/images/dinner-2017/thumbnails/dinner-2017-15.jpg" alt=" " /></a></li>
                                    <li><a href="https://instafxng.com/images/dinner-2017/dinner-2017-16.jpg" rel="prettyPhoto[gallery4]" title=""><img src="https://instafxng.com/images/dinner-2017/thumbnails/dinner-2017-16.jpg" alt=" " /></a></li>
                                    <li><a href="https://instafxng.com/images/dinner-2017/dinner-2017-17.jpg" rel="prettyPhoto[gallery4]" title=""><img src="https://instafxng.com/images/dinner-2017/thumbnails/dinner-2017-17.jpg" alt=" " /></a></li>
                                    <li><a href="https://instafxng.com/images/dinner-2017/dinner-2017-18.jpg" rel="prettyPhoto[gallery4]" title=""><img src="https://instafxng.com/images/dinner-2017/thumbnails/dinner-2017-18.jpg" alt=" " /></a></li>
                                    <li><a href="https://instafxng.com/images/dinner-2017/dinner-2017-19.jpg" rel="prettyPhoto[gallery4]" title=""><img src="https://instafxng.com/images/dinner-2017/thumbnails/dinner-2017-19.jpg" alt=" " /></a></li>
                                    <li><a href="https://instafxng.com/images/dinner-2017/dinner-2017-20.jpg" rel="prettyPhoto[gallery4]" title=""><img src="https://instafxng.com/images/dinner-2017/thumbnails/dinner-2017-20.jpg" alt=" " /></a></li>

                                    <li><a href="https://instafxng.com/images/dinner-2017/dinner-2017-21.jpg" rel="prettyPhoto[gallery4]" title=""><img src="https://instafxng.com/images/dinner-2017/thumbnails/dinner-2017-21.jpg" alt=" " /></a></li>
                                    <li><a href="https://instafxng.com/images/dinner-2017/dinner-2017-22.jpg" rel="prettyPhoto[gallery4]" title=""><img src="https://instafxng.com/images/dinner-2017/thumbnails/dinner-2017-22.jpg" alt=" " /></a></li>
                                    <li><a href="https://instafxng.com/images/dinner-2017/dinner-2017-23.jpg" rel="prettyPhoto[gallery4]" title=""><img src="https://instafxng.com/images/dinner-2017/thumbnails/dinner-2017-23.jpg" alt=" " /></a></li>
                                    <li><a href="https://instafxng.com/images/dinner-2017/dinner-2017-24.jpg" rel="prettyPhoto[gallery4]" title=""><img src="https://instafxng.com/images/dinner-2017/thumbnails/dinner-2017-24.jpg" alt=" " /></a></li>
                                    <li><a href="https://instafxng.com/images/dinner-2017/dinner-2017-25.jpg" rel="prettyPhoto[gallery4]" title=""><img src="https://instafxng.com/images/dinner-2017/thumbnails/dinner-2017-25.jpg" alt=" " /></a></li>
                                    <li><a href="https://instafxng.com/images/dinner-2017/dinner-2017-26.jpg" rel="prettyPhoto[gallery4]" title=""><img src="https://instafxng.com/images/dinner-2017/thumbnails/dinner-2017-26.jpg" alt=" " /></a></li>
                                    <li><a href="https://instafxng.com/images/dinner-2017/dinner-2017-27.jpg" rel="prettyPhoto[gallery4]" title=""><img src="https://instafxng.com/images/dinner-2017/thumbnails/dinner-2017-27.jpg" alt=" " /></a></li>
                                    <li><a href="https://instafxng.com/images/dinner-2017/dinner-2017-28.jpg" rel="prettyPhoto[gallery4]" title=""><img src="https://instafxng.com/images/dinner-2017/thumbnails/dinner-2017-28.jpg" alt=" " /></a></li>
                                    <li><a href="https://instafxng.com/images/dinner-2017/dinner-2017-29.jpg" rel="prettyPhoto[gallery4]" title=""><img src="https://instafxng.com/images/dinner-2017/thumbnails/dinner-2017-29.jpg" alt=" " /></a></li>
                                    <li><a href="https://instafxng.com/images/dinner-2017/dinner-2017-30.jpg" rel="prettyPhoto[gallery4]" title=""><img src="https://instafxng.com/images/dinner-2017/thumbnails/dinner-2017-30.jpg" alt=" " /></a></li>

                                    <li><a href="https://instafxng.com/images/dinner-2017/dinner-2017-31.jpg" rel="prettyPhoto[gallery4]" title=""><img src="https://instafxng.com/images/dinner-2017/thumbnails/dinner-2017-31.jpg" alt=" " /></a></li>
                                    <li><a href="https://instafxng.com/images/dinner-2017/dinner-2017-32.jpg" rel="prettyPhoto[gallery4]" title=""><img src="https://instafxng.com/images/dinner-2017/thumbnails/dinner-2017-32.jpg" alt=" " /></a></li>
                                    <li><a href="https://instafxng.com/images/dinner-2017/dinner-2017-33.jpg" rel="prettyPhoto[gallery4]" title=""><img src="https://instafxng.com/images/dinner-2017/thumbnails/dinner-2017-33.jpg" alt=" " /></a></li>
                                    <li><a href="https://instafxng.com/images/dinner-2017/dinner-2017-34.jpg" rel="prettyPhoto[gallery4]" title=""><img src="https://instafxng.com/images/dinner-2017/thumbnails/dinner-2017-34.jpg" alt=" " /></a></li>
                                    <li><a href="https://instafxng.com/images/dinner-2017/dinner-2017-35.jpg" rel="prettyPhoto[gallery4]" title=""><img src="https://instafxng.com/images/dinner-2017/thumbnails/dinner-2017-35.jpg" alt=" " /></a></li>
                                    <li><a href="https://instafxng.com/images/dinner-2017/dinner-2017-36.jpg" rel="prettyPhoto[gallery4]" title=""><img src="https://instafxng.com/images/dinner-2017/thumbnails/dinner-2017-36.jpg" alt=" " /></a></li>
                                    <li><a href="https://instafxng.com/images/dinner-2017/dinner-2017-37.jpg" rel="prettyPhoto[gallery4]" title=""><img src="https://instafxng.com/images/dinner-2017/thumbnails/dinner-2017-37.jpg" alt=" " /></a></li>
                                    <li><a href="https://instafxng.com/images/dinner-2017/dinner-2017-38.jpg" rel="prettyPhoto[gallery4]" title=""><img src="https://instafxng.com/images/dinner-2017/thumbnails/dinner-2017-38.jpg" alt=" " /></a></li>
                                    <li><a href="https://instafxng.com/images/dinner-2017/dinner-2017-39.jpg" rel="prettyPhoto[gallery4]" title=""><img src="https://instafxng.com/images/dinner-2017/thumbnails/dinner-2017-39.jpg" alt=" " /></a></li>
                                    <li><a href="https://instafxng.com/images/dinner-2017/dinner-2017-40.jpg" rel="prettyPhoto[gallery4]" title=""><img src="https://instafxng.com/images/dinner-2017/thumbnails/dinner-2017-40.jpg" alt=" " /></a></li>

                                    <li><a href="https://instafxng.com/images/dinner-2017/dinner-2017-41.jpg" rel="prettyPhoto[gallery4]" title=""><img src="https://instafxng.com/images/dinner-2017/thumbnails/dinner-2017-41.jpg" alt=" " /></a></li>
                                    <li><a href="https://instafxng.com/images/dinner-2017/dinner-2017-42.jpg" rel="prettyPhoto[gallery4]" title=""><img src="https://instafxng.com/images/dinner-2017/thumbnails/dinner-2017-42.jpg" alt=" " /></a></li>
                                    <li><a href="https://instafxng.com/images/dinner-2017/dinner-2017-43.jpg" rel="prettyPhoto[gallery4]" title=""><img src="https://instafxng.com/images/dinner-2017/thumbnails/dinner-2017-43.jpg" alt=" " /></a></li>
                                    <li><a href="https://instafxng.com/images/dinner-2017/dinner-2017-44.jpg" rel="prettyPhoto[gallery4]" title=""><img src="https://instafxng.com/images/dinner-2017/thumbnails/dinner-2017-44.jpg" alt=" " /></a></li>
                                    <li><a href="https://instafxng.com/images/dinner-2017/dinner-2017-45.jpg" rel="prettyPhoto[gallery4]" title=""><img src="https://instafxng.com/images/dinner-2017/thumbnails/dinner-2017-45.jpg" alt=" " /></a></li>
                                    <li><a href="https://instafxng.com/images/dinner-2017/dinner-2017-46.jpg" rel="prettyPhoto[gallery4]" title=""><img src="https://instafxng.com/images/dinner-2017/thumbnails/dinner-2017-46.jpg" alt=" " /></a></li>
                                    <li><a href="https://instafxng.com/images/dinner-2017/dinner-2017-47.jpg" rel="prettyPhoto[gallery4]" title=""><img src="https://instafxng.com/images/dinner-2017/thumbnails/dinner-2017-47.jpg" alt=" " /></a></li>
                                    <li><a href="https://instafxng.com/images/dinner-2017/dinner-2017-48.jpg" rel="prettyPhoto[gallery4]" title=""><img src="https://instafxng.com/images/dinner-2017/thumbnails/dinner-2017-48.jpg" alt=" " /></a></li>
                                    <li><a href="https://instafxng.com/images/dinner-2017/dinner-2017-49.jpg" rel="prettyPhoto[gallery4]" title=""><img src="https://instafxng.com/images/dinner-2017/thumbnails/dinner-2017-49.jpg" alt=" " /></a></li>
                                    <li><a href="https://instafxng.com/images/dinner-2017/dinner-2017-50.jpg" rel="prettyPhoto[gallery4]" title=""><img src="https://instafxng.com/images/dinner-2017/thumbnails/dinner-2017-50.jpg" alt=" " /></a></li>

                                    <li><a href="https://instafxng.com/images/dinner-2017/dinner-2017-51.jpg" rel="prettyPhoto[gallery4]" title=""><img src="https://instafxng.com/images/dinner-2017/thumbnails/dinner-2017-51.jpg" alt=" " /></a></li>
                                    <li><a href="https://instafxng.com/images/dinner-2017/dinner-2017-52.jpg" rel="prettyPhoto[gallery4]" title=""><img src="https://instafxng.com/images/dinner-2017/thumbnails/dinner-2017-52.jpg" alt=" " /></a></li>
                                    <li><a href="https://instafxng.com/images/dinner-2017/dinner-2017-53.jpg" rel="prettyPhoto[gallery4]" title=""><img src="https://instafxng.com/images/dinner-2017/thumbnails/dinner-2017-53.jpg" alt=" " /></a></li>
                                    <li><a href="https://instafxng.com/images/dinner-2017/dinner-2017-54.jpg" rel="prettyPhoto[gallery4]" title=""><img src="https://instafxng.com/images/dinner-2017/thumbnails/dinner-2017-54.jpg" alt=" " /></a></li>
                                    <li><a href="https://instafxng.com/images/dinner-2017/dinner-2017-55.jpg" rel="prettyPhoto[gallery4]" title=""><img src="https://instafxng.com/images/dinner-2017/thumbnails/dinner-2017-55.jpg" alt=" " /></a></li>
                                    <li><a href="https://instafxng.com/images/dinner-2017/dinner-2017-56.jpg" rel="prettyPhoto[gallery4]" title=""><img src="https://instafxng.com/images/dinner-2017/thumbnails/dinner-2017-56.jpg" alt=" " /></a></li>
                                    <li><a href="https://instafxng.com/images/dinner-2017/dinner-2017-57.jpg" rel="prettyPhoto[gallery4]" title=""><img src="https://instafxng.com/images/dinner-2017/thumbnails/dinner-2017-57.jpg" alt=" " /></a></li>
                                    <li><a href="https://instafxng.com/images/dinner-2017/dinner-2017-58.jpg" rel="prettyPhoto[gallery4]" title=""><img src="https://instafxng.com/images/dinner-2017/thumbnails/dinner-2017-58.jpg" alt=" " /></a></li>
                                    <li><a href="https://instafxng.com/images/dinner-2017/dinner-2017-59.jpg" rel="prettyPhoto[gallery4]" title=""><img src="https://instafxng.com/images/dinner-2017/thumbnails/dinner-2017-59.jpg" alt=" " /></a></li>
                                    <li><a href="https://instafxng.com/images/dinner-2017/dinner-2017-60.jpg" rel="prettyPhoto[gallery4]" title=""><img src="https://instafxng.com/images/dinner-2017/thumbnails/dinner-2017-60.jpg" alt=" " /></a></li>

                                    <li><a href="https://instafxng.com/images/dinner-2017/dinner-2017-61.jpg" rel="prettyPhoto[gallery4]" title=""><img src="https://instafxng.com/images/dinner-2017/thumbnails/dinner-2017-61.jpg" alt=" " /></a></li>
                                    <li><a href="https://instafxng.com/images/dinner-2017/dinner-2017-62.jpg" rel="prettyPhoto[gallery4]" title=""><img src="https://instafxng.com/images/dinner-2017/thumbnails/dinner-2017-62.jpg" alt=" " /></a></li>
                                    <li><a href="https://instafxng.com/images/dinner-2017/dinner-2017-63.jpg" rel="prettyPhoto[gallery4]" title=""><img src="https://instafxng.com/images/dinner-2017/thumbnails/dinner-2017-63.jpg" alt=" " /></a></li>
                                    <li><a href="https://instafxng.com/images/dinner-2017/dinner-2017-64.jpg" rel="prettyPhoto[gallery4]" title=""><img src="https://instafxng.com/images/dinner-2017/thumbnails/dinner-2017-64.jpg" alt=" " /></a></li>
                                    <li><a href="https://instafxng.com/images/dinner-2017/dinner-2017-65.jpg" rel="prettyPhoto[gallery4]" title=""><img src="https://instafxng.com/images/dinner-2017/thumbnails/dinner-2017-65.jpg" alt=" " /></a></li>
                                    <li><a href="https://instafxng.com/images/dinner-2017/dinner-2017-66.jpg" rel="prettyPhoto[gallery4]" title=""><img src="https://instafxng.com/images/dinner-2017/thumbnails/dinner-2017-66.jpg" alt=" " /></a></li>
                                    <li><a href="https://instafxng.com/images/dinner-2017/dinner-2017-67.jpg" rel="prettyPhoto[gallery4]" title=""><img src="https://instafxng.com/images/dinner-2017/thumbnails/dinner-2017-67.jpg" alt=" " /></a></li>
                                    <li><a href="https://instafxng.com/images/dinner-2017/dinner-2017-68.jpg" rel="prettyPhoto[gallery4]" title=""><img src="https://instafxng.com/images/dinner-2017/thumbnails/dinner-2017-68.jpg" alt=" " /></a></li>
                                    <li><a href="https://instafxng.com/images/dinner-2017/dinner-2017-69.jpg" rel="prettyPhoto[gallery4]" title=""><img src="https://instafxng.com/images/dinner-2017/thumbnails/dinner-2017-69.jpg" alt=" " /></a></li>
                                    <li><a href="https://instafxng.com/images/dinner-2017/dinner-2017-70.jpg" rel="prettyPhoto[gallery4]" title=""><img src="https://instafxng.com/images/dinner-2017/thumbnails/dinner-2017-70.jpg" alt=" " /></a></li>

                                    <li><a href="https://instafxng.com/images/dinner-2017/dinner-2017-71.jpg" rel="prettyPhoto[gallery4]" title=""><img src="https://instafxng.com/images/dinner-2017/thumbnails/dinner-2017-71.jpg" alt=" " /></a></li>
                                    <li><a href="https://instafxng.com/images/dinner-2017/dinner-2017-72.jpg" rel="prettyPhoto[gallery4]" title=""><img src="https://instafxng.com/images/dinner-2017/thumbnails/dinner-2017-72.jpg" alt=" " /></a></li>
                                    <li><a href="https://instafxng.com/images/dinner-2017/dinner-2017-73.jpg" rel="prettyPhoto[gallery4]" title=""><img src="https://instafxng.com/images/dinner-2017/thumbnails/dinner-2017-73.jpg" alt=" " /></a></li>
                                    <li><a href="https://instafxng.com/images/dinner-2017/dinner-2017-74.jpg" rel="prettyPhoto[gallery4]" title=""><img src="https://instafxng.com/images/dinner-2017/thumbnails/dinner-2017-74.jpg" alt=" " /></a></li>
                                    <li><a href="https://instafxng.com/images/dinner-2017/dinner-2017-75.jpg" rel="prettyPhoto[gallery4]" title=""><img src="https://instafxng.com/images/dinner-2017/thumbnails/dinner-2017-75.jpg" alt=" " /></a></li>
                                    <li><a href="https://instafxng.com/images/dinner-2017/dinner-2017-76.jpg" rel="prettyPhoto[gallery4]" title=""><img src="https://instafxng.com/images/dinner-2017/thumbnails/dinner-2017-76.jpg" alt=" " /></a></li>
                                    <li><a href="https://instafxng.com/images/dinner-2017/dinner-2017-77.jpg" rel="prettyPhoto[gallery4]" title=""><img src="https://instafxng.com/images/dinner-2017/thumbnails/dinner-2017-77.jpg" alt=" " /></a></li>
                                    <li><a href="https://instafxng.com/images/dinner-2017/dinner-2017-78.jpg" rel="prettyPhoto[gallery4]" title=""><img src="https://instafxng.com/images/dinner-2017/thumbnails/dinner-2017-78.jpg" alt=" " /></a></li>
                                    <li><a href="https://instafxng.com/images/dinner-2017/dinner-2017-79.jpg" rel="prettyPhoto[gallery4]" title=""><img src="https://instafxng.com/images/dinner-2017/thumbnails/dinner-2017-79.jpg" alt=" " /></a></li>
                                    <li><a href="https://instafxng.com/images/dinner-2017/dinner-2017-80.jpg" rel="prettyPhoto[gallery4]" title=""><img src="https://instafxng.com/images/dinner-2017/thumbnails/dinner-2017-80.jpg" alt=" " /></a></li>

                                    <li><a href="https://instafxng.com/images/dinner-2017/dinner-2017-81.jpg" rel="prettyPhoto[gallery4]" title=""><img src="https://instafxng.com/images/dinner-2017/thumbnails/dinner-2017-81.jpg" alt=" " /></a></li>
                                    <li><a href="https://instafxng.com/images/dinner-2017/dinner-2017-82.jpg" rel="prettyPhoto[gallery4]" title=""><img src="https://instafxng.com/images/dinner-2017/thumbnails/dinner-2017-82.jpg" alt=" " /></a></li>
                                    <li><a href="https://instafxng.com/images/dinner-2017/dinner-2017-83.jpg" rel="prettyPhoto[gallery4]" title=""><img src="https://instafxng.com/images/dinner-2017/thumbnails/dinner-2017-83.jpg" alt=" " /></a></li>
                                    <li><a href="https://instafxng.com/images/dinner-2017/dinner-2017-84.jpg" rel="prettyPhoto[gallery4]" title=""><img src="https://instafxng.com/images/dinner-2017/thumbnails/dinner-2017-84.jpg" alt=" " /></a></li>
                                    <li><a href="https://instafxng.com/images/dinner-2017/dinner-2017-85.jpg" rel="prettyPhoto[gallery4]" title=""><img src="https://instafxng.com/images/dinner-2017/thumbnails/dinner-2017-85.jpg" alt=" " /></a></li>
                                    <li><a href="https://instafxng.com/images/dinner-2017/dinner-2017-86.jpg" rel="prettyPhoto[gallery4]" title=""><img src="https://instafxng.com/images/dinner-2017/thumbnails/dinner-2017-86.jpg" alt=" " /></a></li>
                                    <li><a href="https://instafxng.com/images/dinner-2017/dinner-2017-87.jpg" rel="prettyPhoto[gallery4]" title=""><img src="https://instafxng.com/images/dinner-2017/thumbnails/dinner-2017-87.jpg" alt=" " /></a></li>
                                    <li><a href="https://instafxng.com/images/dinner-2017/dinner-2017-88.jpg" rel="prettyPhoto[gallery4]" title=""><img src="https://instafxng.com/images/dinner-2017/thumbnails/dinner-2017-88.jpg" alt=" " /></a></li>
                                    <li><a href="https://instafxng.com/images/dinner-2017/dinner-2017-89.jpg" rel="prettyPhoto[gallery4]" title=""><img src="https://instafxng.com/images/dinner-2017/thumbnails/dinner-2017-89.jpg" alt=" " /></a></li>
                                    <li><a href="https://instafxng.com/images/dinner-2017/dinner-2017-90.jpg" rel="prettyPhoto[gallery4]" title=""><img src="https://instafxng.com/images/dinner-2017/thumbnails/dinner-2017-90.jpg" alt=" " /></a></li>

                                    <li><a href="https://instafxng.com/images/dinner-2017/dinner-2017-91.jpg" rel="prettyPhoto[gallery4]" title=""><img src="https://instafxng.com/images/dinner-2017/thumbnails/dinner-2017-91.jpg" alt=" " /></a></li>
                                    <li><a href="https://instafxng.com/images/dinner-2017/dinner-2017-92.jpg" rel="prettyPhoto[gallery4]" title=""><img src="https://instafxng.com/images/dinner-2017/thumbnails/dinner-2017-92.jpg" alt=" " /></a></li>
                                    <li><a href="https://instafxng.com/images/dinner-2017/dinner-2017-93.jpg" rel="prettyPhoto[gallery4]" title=""><img src="https://instafxng.com/images/dinner-2017/thumbnails/dinner-2017-93.jpg" alt=" " /></a></li>
                                    <li><a href="https://instafxng.com/images/dinner-2017/dinner-2017-94.jpg" rel="prettyPhoto[gallery4]" title=""><img src="https://instafxng.com/images/dinner-2017/thumbnails/dinner-2017-94.jpg" alt=" " /></a></li>
                                    <li><a href="https://instafxng.com/images/dinner-2017/dinner-2017-95.jpg" rel="prettyPhoto[gallery4]" title=""><img src="https://instafxng.com/images/dinner-2017/thumbnails/dinner-2017-95.jpg" alt=" " /></a></li>
                                    <li><a href="https://instafxng.com/images/dinner-2017/dinner-2017-96.jpg" rel="prettyPhoto[gallery4]" title=""><img src="https://instafxng.com/images/dinner-2017/thumbnails/dinner-2017-96.jpg" alt=" " /></a></li>
                                    <li><a href="https://instafxng.com/images/dinner-2017/dinner-2017-97.jpg" rel="prettyPhoto[gallery4]" title=""><img src="https://instafxng.com/images/dinner-2017/thumbnails/dinner-2017-97.jpg" alt=" " /></a></li>
                                    <li><a href="https://instafxng.com/images/dinner-2017/dinner-2017-98.jpg" rel="prettyPhoto[gallery4]" title=""><img src="https://instafxng.com/images/dinner-2017/thumbnails/dinner-2017-98.jpg" alt=" " /></a></li>
                                    <li><a href="https://instafxng.com/images/dinner-2017/dinner-2017-99.jpg" rel="prettyPhoto[gallery4]" title=""><img src="https://instafxng.com/images/dinner-2017/thumbnails/dinner-2017-99.jpg" alt=" " /></a></li>
                                    <li><a href="https://instafxng.com/images/dinner-2017/dinner-2017-100.jpg" rel="prettyPhoto[gallery4]" title=""><img src="https://instafxng.com/images/dinner-2017/thumbnails/dinner-2017-100.jpg" alt=" " /></a></li>

                                    <li><a href="https://instafxng.com/images/dinner-2017/dinner-2017-101.jpg" rel="prettyPhoto[gallery4]" title=""><img src="https://instafxng.com/images/dinner-2017/thumbnails/dinner-2017-101.jpg" alt=" " /></a></li>
                                    <li><a href="https://instafxng.com/images/dinner-2017/dinner-2017-102.jpg" rel="prettyPhoto[gallery4]" title=""><img src="https://instafxng.com/images/dinner-2017/thumbnails/dinner-2017-102.jpg" alt=" " /></a></li>
                                    <li><a href="https://instafxng.com/images/dinner-2017/dinner-2017-103.jpg" rel="prettyPhoto[gallery4]" title=""><img src="https://instafxng.com/images/dinner-2017/thumbnails/dinner-2017-103.jpg" alt=" " /></a></li>

                                </ul>

                                <h5>Annual Staff Retreat 2017</h5>
                                <p></p>
                                <ul class="gallery clearfix photo_g">
                                    <li><a href="https://instafxng.com/images/staff-retreat-2017/staff-retreat-2017-1.jpg" rel="prettyPhoto[gallery4]" title=""><img src="https://instafxng.com/images/staff-retreat-2017/thumbnails/staff-retreat-2017-1.jpg" alt=" " /></a></li>
                                    <li><a href="https://instafxng.com/images/staff-retreat-2017/staff-retreat-2017-2.jpg" rel="prettyPhoto[gallery4]" title=""><img src="https://instafxng.com/images/staff-retreat-2017/thumbnails/staff-retreat-2017-2.jpg" alt=" " /></a></li>
                                    <li><a href="https://instafxng.com/images/staff-retreat-2017/staff-retreat-2017-3.jpg" rel="prettyPhoto[gallery4]" title=""><img src="https://instafxng.com/images/staff-retreat-2017/thumbnails/staff-retreat-2017-3.jpg" alt=" " /></a></li>
                                    <li><a href="https://instafxng.com/images/staff-retreat-2017/staff-retreat-2017-4.jpg" rel="prettyPhoto[gallery4]" title=""><img src="https://instafxng.com/images/staff-retreat-2017/thumbnails/staff-retreat-2017-4.jpg" alt=" " /></a></li>
                                    <li><a href="https://instafxng.com/images/staff-retreat-2017/staff-retreat-2017-5.jpg" rel="prettyPhoto[gallery4]" title=""><img src="https://instafxng.com/images/staff-retreat-2017/thumbnails/staff-retreat-2017-5.jpg" alt=" " /></a></li>
                                    <li><a href="https://instafxng.com/images/staff-retreat-2017/staff-retreat-2017-6.jpg" rel="prettyPhoto[gallery4]" title=""><img src="https://instafxng.com/images/staff-retreat-2017/thumbnails/staff-retreat-2017-6.jpg" alt=" " /></a></li>
                                    <li><a href="https://instafxng.com/images/staff-retreat-2017/staff-retreat-2017-7.jpg" rel="prettyPhoto[gallery4]" title=""><img src="https://instafxng.com/images/staff-retreat-2017/thumbnails/staff-retreat-2017-7.jpg" alt=" " /></a></li>
                                    <li><a href="https://instafxng.com/images/staff-retreat-2017/staff-retreat-2017-8.jpg" rel="prettyPhoto[gallery4]" title=""><img src="https://instafxng.com/images/staff-retreat-2017/thumbnails/staff-retreat-2017-8.jpg" alt=" " /></a></li>
                                    <li><a href="https://instafxng.com/images/staff-retreat-2017/staff-retreat-2017-9.jpg" rel="prettyPhoto[gallery4]" title=""><img src="https://instafxng.com/images/staff-retreat-2017/thumbnails/staff-retreat-2017-9.jpg" alt=" " /></a></li>
                                    <li><a href="https://instafxng.com/images/staff-retreat-2017/staff-retreat-2017-10.jpg" rel="prettyPhoto[gallery4]" title=""><img src="https://instafxng.com/images/staff-retreat-2017/thumbnails/staff-retreat-2017-10.jpg" alt=" " /></a></li>
                                    <li><a href="https://instafxng.com/images/staff-retreat-2017/staff-retreat-2017-36.jpg" rel="prettyPhoto[gallery4]" title=""><img src="https://instafxng.com/images/staff-retreat-2017/thumbnails/staff-retreat-2017-36.jpg" alt=" " /></a></li>
                                    <li><a href="https://instafxng.com/images/staff-retreat-2017/staff-retreat-2017-11.jpg" rel="prettyPhoto[gallery4]" title=""><img src="https://instafxng.com/images/staff-retreat-2017/thumbnails/staff-retreat-2017-11.jpg" alt=" " /></a></li>
                                    <li><a href="https://instafxng.com/images/staff-retreat-2017/staff-retreat-2017-13.jpg" rel="prettyPhoto[gallery4]" title=""><img src="https://instafxng.com/images/staff-retreat-2017/thumbnails/staff-retreat-2017-13.jpg" alt=" " /></a></li>
                                    <li><a href="https://instafxng.com/images/staff-retreat-2017/staff-retreat-2017-14.jpg" rel="prettyPhoto[gallery4]" title=""><img src="https://instafxng.com/images/staff-retreat-2017/thumbnails/staff-retreat-2017-14.jpg" alt=" " /></a></li>
                                    <li><a href="https://instafxng.com/images/staff-retreat-2017/staff-retreat-2017-34.jpg" rel="prettyPhoto[gallery4]" title=""><img src="https://instafxng.com/images/staff-retreat-2017/thumbnails/staff-retreat-2017-34.jpg" alt=" " /></a></li>
                                    <li><a href="https://instafxng.com/images/staff-retreat-2017/staff-retreat-2017-15.jpg" rel="prettyPhoto[gallery4]" title=""><img src="https://instafxng.com/images/staff-retreat-2017/thumbnails/staff-retreat-2017-15.jpg" alt=" " /></a></li>
                                    <li><a href="https://instafxng.com/images/staff-retreat-2017/staff-retreat-2017-16.jpg" rel="prettyPhoto[gallery4]" title=""><img src="https://instafxng.com/images/staff-retreat-2017/thumbnails/staff-retreat-2017-16.jpg" alt=" " /></a></li>
                                    <li><a href="https://instafxng.com/images/staff-retreat-2017/staff-retreat-2017-17.jpg" rel="prettyPhoto[gallery4]" title=""><img src="https://instafxng.com/images/staff-retreat-2017/thumbnails/staff-retreat-2017-17.jpg" alt=" " /></a></li>
                                    <li><a href="https://instafxng.com/images/staff-retreat-2017/staff-retreat-2017-18.jpg" rel="prettyPhoto[gallery4]" title=""><img src="https://instafxng.com/images/staff-retreat-2017/thumbnails/staff-retreat-2017-18.jpg" alt=" " /></a></li>
                                    <li><a href="https://instafxng.com/images/staff-retreat-2017/staff-retreat-2017-19.jpg" rel="prettyPhoto[gallery4]" title=""><img src="https://instafxng.com/images/staff-retreat-2017/thumbnails/staff-retreat-2017-19.jpg" alt=" " /></a></li>
                                    <li><a href="https://instafxng.com/images/staff-retreat-2017/staff-retreat-2017-20.jpg" rel="prettyPhoto[gallery4]" title=""><img src="https://instafxng.com/images/staff-retreat-2017/thumbnails/staff-retreat-2017-20.jpg" alt=" " /></a></li>
                                    <li><a href="https://instafxng.com/images/staff-retreat-2017/staff-retreat-2017-21.jpg" rel="prettyPhoto[gallery4]" title=""><img src="https://instafxng.com/images/staff-retreat-2017/thumbnails/staff-retreat-2017-21.jpg" alt=" " /></a></li>
                                    <li><a href="https://instafxng.com/images/staff-retreat-2017/staff-retreat-2017-22.jpg" rel="prettyPhoto[gallery4]" title=""><img src="https://instafxng.com/images/staff-retreat-2017/thumbnails/staff-retreat-2017-22.jpg" alt=" " /></a></li>
                                    <li><a href="https://instafxng.com/images/staff-retreat-2017/staff-retreat-2017-23.jpg" rel="prettyPhoto[gallery4]" title=""><img src="https://instafxng.com/images/staff-retreat-2017/thumbnails/staff-retreat-2017-23.jpg" alt=" " /></a></li>
                                    <li><a href="https://instafxng.com/images/staff-retreat-2017/staff-retreat-2017-24.jpg" rel="prettyPhoto[gallery4]" title=""><img src="https://instafxng.com/images/staff-retreat-2017/thumbnails/staff-retreat-2017-24.jpg" alt=" " /></a></li>
                                    <li><a href="https://instafxng.com/images/staff-retreat-2017/staff-retreat-2017-25.jpg" rel="prettyPhoto[gallery4]" title=""><img src="https://instafxng.com/images/staff-retreat-2017/thumbnails/staff-retreat-2017-25.jpg" alt=" " /></a></li>
                                    <li><a href="https://instafxng.com/images/staff-retreat-2017/staff-retreat-2017-35.jpg" rel="prettyPhoto[gallery4]" title=""><img src="https://instafxng.com/images/staff-retreat-2017/thumbnails/staff-retreat-2017-35.jpg" alt=" " /></a></li>
                                    <li><a href="https://instafxng.com/images/staff-retreat-2017/staff-retreat-2017-26.jpg" rel="prettyPhoto[gallery4]" title=""><img src="https://instafxng.com/images/staff-retreat-2017/thumbnails/staff-retreat-2017-26.jpg" alt=" " /></a></li>
                                    <li><a href="https://instafxng.com/images/staff-retreat-2017/staff-retreat-2017-27.jpg" rel="prettyPhoto[gallery4]" title=""><img src="https://instafxng.com/images/staff-retreat-2017/thumbnails/staff-retreat-2017-27.jpg" alt=" " /></a></li>
                                    <li><a href="https://instafxng.com/images/staff-retreat-2017/staff-retreat-2017-28.jpg" rel="prettyPhoto[gallery4]" title=""><img src="https://instafxng.com/images/staff-retreat-2017/thumbnails/staff-retreat-2017-28.jpg" alt=" " /></a></li>
                                    <li><a href="https://instafxng.com/images/staff-retreat-2017/staff-retreat-2017-29.jpg" rel="prettyPhoto[gallery4]" title=""><img src="https://instafxng.com/images/staff-retreat-2017/thumbnails/staff-retreat-2017-29.jpg" alt=" " /></a></li>
                                    <li><a href="https://instafxng.com/images/staff-retreat-2017/staff-retreat-2017-30.jpg" rel="prettyPhoto[gallery4]" title=""><img src="https://instafxng.com/images/staff-retreat-2017/thumbnails/staff-retreat-2017-30.jpg" alt=" " /></a></li>
                                    <li><a href="https://instafxng.com/images/staff-retreat-2017/staff-retreat-2017-31.jpg" rel="prettyPhoto[gallery4]" title=""><img src="https://instafxng.com/images/staff-retreat-2017/thumbnails/staff-retreat-2017-31.jpg" alt=" " /></a></li>
                                    <li><a href="https://instafxng.com/images/staff-retreat-2017/staff-retreat-2017-32.jpg" rel="prettyPhoto[gallery4]" title=""><img src="https://instafxng.com/images/staff-retreat-2017/thumbnails/staff-retreat-2017-32.jpg" alt=" " /></a></li>
                                    <li><a href="https://instafxng.com/images/staff-retreat-2017/staff-retreat-2017-33.jpg" rel="prettyPhoto[gallery4]" title=""><img src="https://instafxng.com/images/staff-retreat-2017/thumbnails/staff-retreat-2017-33.jpg" alt=" " /></a></li>
                                    <li><a href="https://instafxng.com/images/staff-retreat-2017/staff-retreat-2017-39.jpg" rel="prettyPhoto[gallery4]" title=""><img src="https://instafxng.com/images/staff-retreat-2017/thumbnails/staff-retreat-2017-39.jpg" alt=" " /></a></li>
                                    <li><a href="https://instafxng.com/images/staff-retreat-2017/staff-retreat-2017-40.jpg" rel="prettyPhoto[gallery4]" title=""><img src="https://instafxng.com/images/staff-retreat-2017/thumbnails/staff-retreat-2017-40.jpg" alt=" " /></a></li>
                                    <li><a href="https://instafxng.com/images/staff-retreat-2017/staff-retreat-2017-41.jpg" rel="prettyPhoto[gallery4]" title=""><img src="https://instafxng.com/images/staff-retreat-2017/thumbnails/staff-retreat-2017-41.jpg" alt=" " /></a></li>
                                    <li><a href="https://instafxng.com/images/staff-retreat-2017/staff-retreat-2017-42.jpg" rel="prettyPhoto[gallery4]" title=""><img src="https://instafxng.com/images/staff-retreat-2017/thumbnails/staff-retreat-2017-42.jpg" alt=" " /></a></li>
                                    <li><a href="https://instafxng.com/images/staff-retreat-2017/staff-retreat-2017-43.jpg" rel="prettyPhoto[gallery4]" title=""><img src="https://instafxng.com/images/staff-retreat-2017/thumbnails/staff-retreat-2017-43.jpg" alt=" " /></a></li>
                                    <li><a href="https://instafxng.com/images/staff-retreat-2017/staff-retreat-2017-44.jpg" rel="prettyPhoto[gallery4]" title=""><img src="https://instafxng.com/images/staff-retreat-2017/thumbnails/staff-retreat-2017-44.jpg" alt=" " /></a></li>
                                    <li><a href="https://instafxng.com/images/staff-retreat-2017/staff-retreat-2017-38.jpg" rel="prettyPhoto[gallery4]" title=""><img src="https://instafxng.com/images/staff-retreat-2017/thumbnails/staff-retreat-2017-38.jpg" alt=" " /></a></li>
                                    <li><a href="https://instafxng.com/images/staff-retreat-2017/staff-retreat-2017-45.jpg" rel="prettyPhoto[gallery4]" title=""><img src="https://instafxng.com/images/staff-retreat-2017/thumbnails/staff-retreat-2017-45.jpg" alt=" " /></a></li>
                                    <li><a href="https://instafxng.com/images/staff-retreat-2017/staff-retreat-2017-46.jpg" rel="prettyPhoto[gallery4]" title=""><img src="https://instafxng.com/images/staff-retreat-2017/thumbnails/staff-retreat-2017-46.jpg" alt=" " /></a></li>
                                    <li><a href="https://instafxng.com/images/staff-retreat-2017/staff-retreat-2017-47.jpg" rel="prettyPhoto[gallery4]" title=""><img src="https://instafxng.com/images/staff-retreat-2017/thumbnails/staff-retreat-2017-47.jpg" alt=" " /></a></li>
                                    <li><a href="https://instafxng.com/images/staff-retreat-2017/staff-retreat-2017-48.jpg" rel="prettyPhoto[gallery4]" title=""><img src="https://instafxng.com/images/staff-retreat-2017/thumbnails/staff-retreat-2017-48.jpg" alt=" " /></a></li>
                                    <li><a href="https://instafxng.com/images/staff-retreat-2017/staff-retreat-2017-37.jpg" rel="prettyPhoto[gallery4]" title=""><img src="https://instafxng.com/images/staff-retreat-2017/thumbnails/staff-retreat-2017-37.jpg" alt=" " /></a></li>
                                    <li><a href="https://instafxng.com/images/staff-retreat-2017/staff-retreat-2017-49.jpg" rel="prettyPhoto[gallery4]" title=""><img src="https://instafxng.com/images/staff-retreat-2017/thumbnails/staff-retreat-2017-49.jpg" alt=" " /></a></li>
                                    <li><a href="https://instafxng.com/images/staff-retreat-2017/staff-retreat-2017-50.jpg" rel="prettyPhoto[gallery4]" title=""><img src="https://instafxng.com/images/staff-retreat-2017/thumbnails/staff-retreat-2017-50.jpg" alt=" " /></a></li>
                                </ul>

                                <h5>Pencil Unbroken Comedy Event</h5>
                                <p></p>
                                <ul class="gallery clearfix photo_g">
                                    <li><a href="https://instafxng.com/images/pencil-unbroken-2017/pencil-unbroken-comedy-event-1.jpg" rel="prettyPhoto[gallery4]" title=""><img src="https://instafxng.com/images/pencil-unbroken-2017/thumbnails/pencil-unbroken-comedy-event-1.jpg" alt=" " /></a></li>
                                    <li><a href="https://instafxng.com/images/pencil-unbroken-2017/pencil-unbroken-comedy-event-2.jpg" rel="prettyPhoto[gallery4]" title=""><img src="https://instafxng.com/images/pencil-unbroken-2017/thumbnails/pencil-unbroken-comedy-event-2.jpg" alt=" " /></a></li>
                                    <li><a href="https://instafxng.com/images/pencil-unbroken-2017/pencil-unbroken-comedy-event-3.jpg" rel="prettyPhoto[gallery4]" title=""><img src="https://instafxng.com/images/pencil-unbroken-2017/thumbnails/pencil-unbroken-comedy-event-3.jpg" alt=" " /></a></li>
                                    <li><a href="https://instafxng.com/images/pencil-unbroken-2017/pencil-unbroken-comedy-event-4.jpg" rel="prettyPhoto[gallery4]" title=""><img src="https://instafxng.com/images/pencil-unbroken-2017/thumbnails/pencil-unbroken-comedy-event-4.jpg" alt=" " /></a></li>
                                    <li><a href="https://instafxng.com/images/pencil-unbroken-2017/pencil-unbroken-comedy-event-5.jpg" rel="prettyPhoto[gallery4]" title=""><img src="https://instafxng.com/images/pencil-unbroken-2017/thumbnails/pencil-unbroken-comedy-event-5.jpg" alt=" " /></a></li>
                                    <li><a href="https://instafxng.com/images/pencil-unbroken-2017/pencil-unbroken-comedy-event-6.jpg" rel="prettyPhoto[gallery4]" title=""><img src="https://instafxng.com/images/pencil-unbroken-2017/thumbnails/pencil-unbroken-comedy-event-6.jpg" alt=" " /></a></li>
                                    <li><a href="https://instafxng.com/images/pencil-unbroken-2017/pencil-unbroken-comedy-event-7.jpg" rel="prettyPhoto[gallery4]" title=""><img src="https://instafxng.com/images/pencil-unbroken-2017/thumbnails/pencil-unbroken-comedy-event-7.jpg" alt=" " /></a></li>
                                    <li><a href="https://instafxng.com/images/pencil-unbroken-2017/pencil-unbroken-comedy-event-8.jpg" rel="prettyPhoto[gallery4]" title=""><img src="https://instafxng.com/images/pencil-unbroken-2017/thumbnails/pencil-unbroken-comedy-event-8.jpg" alt=" " /></a></li>
                                    <li><a href="https://instafxng.com/images/pencil-unbroken-2017/pencil-unbroken-comedy-event-9.jpg" rel="prettyPhoto[gallery4]" title=""><img src="https://instafxng.com/images/pencil-unbroken-2017/thumbnails/pencil-unbroken-comedy-event-9.jpg" alt=" " /></a></li>
                                    <li><a href="https://instafxng.com/images/pencil-unbroken-2017/pencil-unbroken-comedy-event-10.jpg" rel="prettyPhoto[gallery4]" title=""><img src="https://instafxng.com/images/pencil-unbroken-2017/thumbnails/pencil-unbroken-comedy-event-10.jpg" alt=" " /></a></li>
                                    <li><a href="https://instafxng.com/images/pencil-unbroken-2017/pencil-unbroken-comedy-event-11.jpg" rel="prettyPhoto[gallery4]" title=""><img src="https://instafxng.com/images/pencil-unbroken-2017/thumbnails/pencil-unbroken-comedy-event-11.jpg" alt=" " /></a></li>
                                    <li><a href="https://instafxng.com/images/pencil-unbroken-2017/pencil-unbroken-comedy-event-12.jpg" rel="prettyPhoto[gallery4]" title=""><img src="https://instafxng.com/images/pencil-unbroken-2017/thumbnails/pencil-unbroken-comedy-event-12.jpg" alt=" " /></a></li>
                                    <li><a href="https://instafxng.com/images/pencil-unbroken-2017/pencil-unbroken-comedy-event-13.jpg" rel="prettyPhoto[gallery4]" title=""><img src="https://instafxng.com/images/pencil-unbroken-2017/thumbnails/pencil-unbroken-comedy-event-13.jpg" alt=" " /></a></li>
                                    <li><a href="https://instafxng.com/images/pencil-unbroken-2017/pencil-unbroken-comedy-event-14.jpg" rel="prettyPhoto[gallery4]" title=""><img src="https://instafxng.com/images/pencil-unbroken-2017/thumbnails/pencil-unbroken-comedy-event-14.jpg" alt=" " /></a></li>
                                    <li><a href="https://instafxng.com/images/pencil-unbroken-2017/pencil-unbroken-comedy-event-15.jpg" rel="prettyPhoto[gallery4]" title=""><img src="https://instafxng.com/images/pencil-unbroken-2017/thumbnails/pencil-unbroken-comedy-event-15.jpg" alt=" " /></a></li>
                                    <li><a href="https://instafxng.com/images/pencil-unbroken-2017/pencil-unbroken-comedy-event-16.jpg" rel="prettyPhoto[gallery4]" title=""><img src="https://instafxng.com/images/pencil-unbroken-2017/thumbnails/pencil-unbroken-comedy-event-16.jpg" alt=" " /></a></li>
                                    <li><a href="https://instafxng.com/images/pencil-unbroken-2017/pencil-unbroken-comedy-event-17.jpg" rel="prettyPhoto[gallery4]" title=""><img src="https://instafxng.com/images/pencil-unbroken-2017/thumbnails/pencil-unbroken-comedy-event-17.jpg" alt=" " /></a></li>
                                    <li><a href="https://instafxng.com/images/pencil-unbroken-2017/pencil-unbroken-comedy-event-18.jpg" rel="prettyPhoto[gallery4]" title=""><img src="https://instafxng.com/images/pencil-unbroken-2017/thumbnails/pencil-unbroken-comedy-event-18.jpg" alt=" " /></a></li>
                                    <li><a href="https://instafxng.com/images/pencil-unbroken-2017/pencil-unbroken-comedy-event-19.jpg" rel="prettyPhoto[gallery4]" title=""><img src="https://instafxng.com/images/pencil-unbroken-2017/thumbnails/pencil-unbroken-comedy-event-19.jpg" alt=" " /></a></li>
                                    <li><a href="https://instafxng.com/images/pencil-unbroken-2017/pencil-unbroken-comedy-event-20.jpg" rel="prettyPhoto[gallery4]" title=""><img src="https://instafxng.com/images/pencil-unbroken-2017/thumbnails/pencil-unbroken-comedy-event-20.jpg" alt=" " /></a></li>
                                    <li><a href="https://instafxng.com/images/pencil-unbroken-2017/pencil-unbroken-comedy-event-21.jpg" rel="prettyPhoto[gallery4]" title=""><img src="https://instafxng.com/images/pencil-unbroken-2017/thumbnails/pencil-unbroken-comedy-event-21.jpg" alt=" " /></a></li>
                                    <li><a href="https://instafxng.com/images/pencil-unbroken-2017/pencil-unbroken-comedy-event-22.jpg" rel="prettyPhoto[gallery4]" title=""><img src="https://instafxng.com/images/pencil-unbroken-2017/thumbnails/pencil-unbroken-comedy-event-22.jpg" alt=" " /></a></li>
                                    <li><a href="https://instafxng.com/images/pencil-unbroken-2017/pencil-unbroken-comedy-event-23.jpg" rel="prettyPhoto[gallery4]" title=""><img src="https://instafxng.com/images/pencil-unbroken-2017/thumbnails/pencil-unbroken-comedy-event-23.jpg" alt=" " /></a></li>
                                    <li><a href="https://instafxng.com/images/pencil-unbroken-2017/pencil-unbroken-comedy-event-24.jpg" rel="prettyPhoto[gallery4]" title=""><img src="https://instafxng.com/images/pencil-unbroken-2017/thumbnails/pencil-unbroken-comedy-event-24.jpg" alt=" " /></a></li>
                                </ul>

                                <h5>New Business Location commissioned for business operations with a cocktail event on the 28th of January, 2017</h5>
                                <p>Please be informed that we have moved from the Ikota Shopping complex to our own facility at Block A3, Suite 508/509 East Line Shopping Complex, opposite Abraham Adesanya Roundabout along Lekki-Epe expressway, Lagos.</p>
                                <ul class="gallery clearfix photo_g">
                                    <li><a href="https://instafxng.com/images/lekki-office-opening/instafxng-lekki-office-opening-1.jpg" rel="prettyPhoto[gallery4]" title=""><img src="https://instafxng.com/images/lekki-office-opening/thumbnails/instafxng-lekki-office-opening-1.jpg" alt=" " /></a></li>
                                    <li><a href="https://instafxng.com/images/lekki-office-opening/instafxng-lekki-office-opening-2.jpg" rel="prettyPhoto[gallery4]" title=""><img src="https://instafxng.com/images/lekki-office-opening/thumbnails/instafxng-lekki-office-opening-2.jpg" alt=" " /></a></li>
                                    <li><a href="https://instafxng.com/images/lekki-office-opening/instafxng-lekki-office-opening-3.jpg" rel="prettyPhoto[gallery4]" title=""><img src="https://instafxng.com/images/lekki-office-opening/thumbnails/instafxng-lekki-office-opening-3.jpg" alt=" " /></a></li>
                                    <li><a href="https://instafxng.com/images/lekki-office-opening/instafxng-lekki-office-opening-4.jpg" rel="prettyPhoto[gallery4]" title=""><img src="https://instafxng.com/images/lekki-office-opening/thumbnails/instafxng-lekki-office-opening-4.jpg" alt=" " /></a></li>
                                    <li><a href="https://instafxng.com/images/lekki-office-opening/instafxng-lekki-office-opening-5.jpg" rel="prettyPhoto[gallery4]" title=""><img src="https://instafxng.com/images/lekki-office-opening/thumbnails/instafxng-lekki-office-opening-5.jpg" alt=" " /></a></li>
                                    <li><a href="https://instafxng.com/images/lekki-office-opening/instafxng-lekki-office-opening-6.jpg" rel="prettyPhoto[gallery4]" title=""><img src="https://instafxng.com/images/lekki-office-opening/thumbnails/instafxng-lekki-office-opening-6.jpg" alt=" " /></a></li>
                                    <li><a href="https://instafxng.com/images/lekki-office-opening/instafxng-lekki-office-opening-7.jpg" rel="prettyPhoto[gallery4]" title=""><img src="https://instafxng.com/images/lekki-office-opening/thumbnails/instafxng-lekki-office-opening-7.jpg" alt=" " /></a></li>
                                    <li><a href="https://instafxng.com/images/lekki-office-opening/instafxng-lekki-office-opening-8.jpg" rel="prettyPhoto[gallery4]" title=""><img src="https://instafxng.com/images/lekki-office-opening/thumbnails/instafxng-lekki-office-opening-8.jpg" alt=" " /></a></li>
                                    <li><a href="https://instafxng.com/images/lekki-office-opening/instafxng-lekki-office-opening-9.jpg" rel="prettyPhoto[gallery4]" title=""><img src="https://instafxng.com/images/lekki-office-opening/thumbnails/instafxng-lekki-office-opening-9.jpg" alt=" " /></a></li>
                                    <li><a href="https://instafxng.com/images/lekki-office-opening/instafxng-lekki-office-opening-10.jpg" rel="prettyPhoto[gallery4]" title=""><img src="https://instafxng.com/images/lekki-office-opening/thumbnails/instafxng-lekki-office-opening-10.jpg" alt=" " /></a></li>
                                    <li><a href="https://instafxng.com/images/lekki-office-opening/instafxng-lekki-office-opening-11.jpg" rel="prettyPhoto[gallery4]" title=""><img src="https://instafxng.com/images/lekki-office-opening/thumbnails/instafxng-lekki-office-opening-11.jpg" alt=" " /></a></li>
                                    <li><a href="https://instafxng.com/images/lekki-office-opening/instafxng-lekki-office-opening-12.jpg" rel="prettyPhoto[gallery4]" title=""><img src="https://instafxng.com/images/lekki-office-opening/thumbnails/instafxng-lekki-office-opening-12.jpg" alt=" " /></a></li>
                                    <li><a href="https://instafxng.com/images/lekki-office-opening/instafxng-lekki-office-opening-13.jpg" rel="prettyPhoto[gallery4]" title=""><img src="https://instafxng.com/images/lekki-office-opening/thumbnails/instafxng-lekki-office-opening-13.jpg" alt=" " /></a></li>
                                    <li><a href="https://instafxng.com/images/lekki-office-opening/instafxng-lekki-office-opening-14.jpg" rel="prettyPhoto[gallery4]" title=""><img src="https://instafxng.com/images/lekki-office-opening/thumbnails/instafxng-lekki-office-opening-14.jpg" alt=" " /></a></li>
                                    <li><a href="https://instafxng.com/images/lekki-office-opening/instafxng-lekki-office-opening-15.jpg" rel="prettyPhoto[gallery4]" title=""><img src="https://instafxng.com/images/lekki-office-opening/thumbnails/instafxng-lekki-office-opening-15.jpg" alt=" " /></a></li>
                                    <li><a href="https://instafxng.com/images/lekki-office-opening/instafxng-lekki-office-opening-16.jpg" rel="prettyPhoto[gallery4]" title=""><img src="https://instafxng.com/images/lekki-office-opening/thumbnails/instafxng-lekki-office-opening-16.jpg" alt=" " /></a></li>
                                    <li><a href="https://instafxng.com/images/lekki-office-opening/instafxng-lekki-office-opening-17.jpg" rel="prettyPhoto[gallery4]" title=""><img src="https://instafxng.com/images/lekki-office-opening/thumbnails/instafxng-lekki-office-opening-17.jpg" alt=" " /></a></li>
                                    <li><a href="https://instafxng.com/images/lekki-office-opening/instafxng-lekki-office-opening-18.jpg" rel="prettyPhoto[gallery4]" title=""><img src="https://instafxng.com/images/lekki-office-opening/thumbnails/instafxng-lekki-office-opening-18.jpg" alt=" " /></a></li>
                                    <li><a href="https://instafxng.com/images/lekki-office-opening/instafxng-lekki-office-opening-19.jpg" rel="prettyPhoto[gallery4]" title=""><img src="https://instafxng.com/images/lekki-office-opening/thumbnails/instafxng-lekki-office-opening-19.jpg" alt=" " /></a></li>
                                </ul>

                                <h5>2016 End of the Year Dinner at Four Points by Sheraton, VI, Lagos.(Classic 80's Night) - December 18, 2016</h5>
                                <ul class="gallery clearfix photo_g">
                                    <li><a href="images/dinner-2016/instaforex_dinner_1.jpg" rel="prettyPhoto[gallery4]" title=""><img src="images/dinner-2016/thumbnails/instaforex_dinner_1.jpg" alt=" " /></a></li>
                                    <li><a href="images/dinner-2016/instaforex_dinner_2.jpg" rel="prettyPhoto[gallery4]" title=""><img src="images/dinner-2016/thumbnails/instaforex_dinner_2.jpg" alt=" " /></a></li>
                                    <li><a href="images/dinner-2016/instaforex_dinner_3.jpg" rel="prettyPhoto[gallery4]" title=""><img src="images/dinner-2016/thumbnails/instaforex_dinner_3.jpg" alt=" " /></a></li>
                                    <li><a href="images/dinner-2016/instaforex_dinner_4.jpg" rel="prettyPhoto[gallery4]" title=""><img src="images/dinner-2016/thumbnails/instaforex_dinner_4.jpg" alt=" " /></a></li>
                                    <li><a href="images/dinner-2016/instaforex_dinner_5.jpg" rel="prettyPhoto[gallery4]" title=""><img src="images/dinner-2016/thumbnails/instaforex_dinner_5.jpg" alt=" " /></a></li>
                                    <li><a href="images/dinner-2016/instaforex_dinner_6.jpg" rel="prettyPhoto[gallery4]" title=""><img src="images/dinner-2016/thumbnails/instaforex_dinner_6.jpg" alt=" " /></a></li>
                                    <li><a href="images/dinner-2016/instaforex_dinner_7.jpg" rel="prettyPhoto[gallery4]" title=""><img src="images/dinner-2016/thumbnails/instaforex_dinner_7.jpg" alt=" " /></a></li>
                                    <li><a href="images/dinner-2016/instaforex_dinner_8.jpg" rel="prettyPhoto[gallery4]" title=""><img src="images/dinner-2016/thumbnails/instaforex_dinner_8.jpg" alt=" " /></a></li>
                                    <li><a href="images/dinner-2016/instaforex_dinner_9.jpg" rel="prettyPhoto[gallery4]" title=""><img src="images/dinner-2016/thumbnails/instaforex_dinner_9.jpg" alt=" " /></a></li>
                                    <li><a href="images/dinner-2016/instaforex_dinner_10.jpg" rel="prettyPhoto[gallery4]" title=""><img src="images/dinner-2016/thumbnails/instaforex_dinner_10.jpg" alt=" " /></a></li>
                                    <li><a href="images/dinner-2016/instaforex_dinner_11.jpg" rel="prettyPhoto[gallery4]" title=""><img src="images/dinner-2016/thumbnails/instaforex_dinner_11.jpg" alt=" " /></a></li>
                                    <li><a href="images/dinner-2016/instaforex_dinner_12.jpg" rel="prettyPhoto[gallery4]" title=""><img src="images/dinner-2016/thumbnails/instaforex_dinner_12.jpg" alt=" " /></a></li>
                                    <li><a href="images/dinner-2016/instaforex_dinner_13.jpg" rel="prettyPhoto[gallery4]" title=""><img src="images/dinner-2016/thumbnails/instaforex_dinner_13.jpg" alt=" " /></a></li>
                                    <li><a href="images/dinner-2016/instaforex_dinner_14.jpg" rel="prettyPhoto[gallery4]" title=""><img src="images/dinner-2016/thumbnails/instaforex_dinner_14.jpg" alt=" " /></a></li>
                                    <li><a href="images/dinner-2016/instaforex_dinner_15.jpg" rel="prettyPhoto[gallery4]" title=""><img src="images/dinner-2016/thumbnails/instaforex_dinner_15.jpg" alt=" " /></a></li>
                                    <li><a href="images/dinner-2016/instaforex_dinner_16.jpg" rel="prettyPhoto[gallery4]" title=""><img src="images/dinner-2016/thumbnails/instaforex_dinner_16.jpg" alt=" " /></a></li>
                                    <li><a href="images/dinner-2016/instaforex_dinner_17.jpg" rel="prettyPhoto[gallery4]" title=""><img src="images/dinner-2016/thumbnails/instaforex_dinner_17.jpg" alt=" " /></a></li>
                                    <li><a href="images/dinner-2016/instaforex_dinner_18.jpg" rel="prettyPhoto[gallery4]" title=""><img src="images/dinner-2016/thumbnails/instaforex_dinner_18.jpg" alt=" " /></a></li>
                                    <li><a href="images/dinner-2016/instaforex_dinner_19.jpg" rel="prettyPhoto[gallery4]" title=""><img src="images/dinner-2016/thumbnails/instaforex_dinner_19.jpg" alt=" " /></a></li>
                                    <li><a href="images/dinner-2016/instaforex_dinner_20.jpg" rel="prettyPhoto[gallery4]" title=""><img src="images/dinner-2016/thumbnails/instaforex_dinner_20.jpg" alt=" " /></a></li>
                                    <li><a href="images/dinner-2016/instaforex_dinner_21.jpg" rel="prettyPhoto[gallery4]" title=""><img src="images/dinner-2016/thumbnails/instaforex_dinner_21.jpg" alt=" " /></a></li>
                                    <li><a href="images/dinner-2016/instaforex_dinner_22.jpg" rel="prettyPhoto[gallery4]" title=""><img src="images/dinner-2016/thumbnails/instaforex_dinner_22.jpg" alt=" " /></a></li>
                                    <li><a href="images/dinner-2016/instaforex_dinner_24.jpg" rel="prettyPhoto[gallery4]" title=""><img src="images/dinner-2016/thumbnails/instaforex_dinner_24.jpg" alt=" " /></a></li>
                                    <li><a href="images/dinner-2016/instaforex_dinner_26.jpg" rel="prettyPhoto[gallery4]" title=""><img src="images/dinner-2016/thumbnails/instaforex_dinner_26.jpg" alt=" " /></a></li>
                                    <li><a href="images/dinner-2016/instaforex_dinner_27.jpg" rel="prettyPhoto[gallery4]" title=""><img src="images/dinner-2016/thumbnails/instaforex_dinner_27.jpg" alt=" " /></a></li>
                                    <li><a href="images/dinner-2016/instaforex_dinner_28.jpg" rel="prettyPhoto[gallery4]" title=""><img src="images/dinner-2016/thumbnails/instaforex_dinner_28.jpg" alt=" " /></a></li>
                                    <li><a href="images/dinner-2016/instaforex_dinner_29.jpg" rel="prettyPhoto[gallery4]" title=""><img src="images/dinner-2016/thumbnails/instaforex_dinner_29.jpg" alt=" " /></a></li>
                                    <li><a href="images/dinner-2016/instaforex_dinner_30.jpg" rel="prettyPhoto[gallery4]" title=""><img src="images/dinner-2016/thumbnails/instaforex_dinner_30.jpg" alt=" " /></a></li>
                                    <li><a href="images/dinner-2016/instaforex_dinner_31.jpg" rel="prettyPhoto[gallery4]" title=""><img src="images/dinner-2016/thumbnails/instaforex_dinner_31.jpg" alt=" " /></a></li>
                                    <li><a href="images/dinner-2016/instaforex_dinner_32.jpg" rel="prettyPhoto[gallery4]" title=""><img src="images/dinner-2016/thumbnails/instaforex_dinner_32.jpg" alt=" " /></a></li>
                                    <li><a href="images/dinner-2016/instaforex_dinner_34.jpg" rel="prettyPhoto[gallery4]" title=""><img src="images/dinner-2016/thumbnails/instaforex_dinner_34.jpg" alt=" " /></a></li>
                                    <li><a href="images/dinner-2016/instaforex_dinner_35.jpg" rel="prettyPhoto[gallery4]" title=""><img src="images/dinner-2016/thumbnails/instaforex_dinner_35.jpg" alt=" " /></a></li>
                                    <li><a href="images/dinner-2016/instaforex_dinner_36.jpg" rel="prettyPhoto[gallery4]" title=""><img src="images/dinner-2016/thumbnails/instaforex_dinner_36.jpg" alt=" " /></a></li>
                                    <li><a href="images/dinner-2016/instaforex_dinner_37.jpg" rel="prettyPhoto[gallery4]" title=""><img src="images/dinner-2016/thumbnails/instaforex_dinner_37.jpg" alt=" " /></a></li>
                                    <li><a href="images/dinner-2016/instaforex_dinner_39.jpg" rel="prettyPhoto[gallery4]" title=""><img src="images/dinner-2016/thumbnails/instaforex_dinner_39.jpg" alt=" " /></a></li>
                                    <li><a href="images/dinner-2016/instaforex_dinner_40.jpg" rel="prettyPhoto[gallery4]" title=""><img src="images/dinner-2016/thumbnails/instaforex_dinner_40.jpg" alt=" " /></a></li>
                                    <li><a href="images/dinner-2016/instaforex_dinner_42.jpg" rel="prettyPhoto[gallery4]" title=""><img src="images/dinner-2016/thumbnails/instaforex_dinner_42.jpg" alt=" " /></a></li>
                                    <li><a href="images/dinner-2016/instaforex_dinner_43.jpg" rel="prettyPhoto[gallery4]" title=""><img src="images/dinner-2016/thumbnails/instaforex_dinner_43.jpg" alt=" " /></a></li>
                                    <li><a href="images/dinner-2016/instaforex_dinner_44.jpg" rel="prettyPhoto[gallery4]" title=""><img src="images/dinner-2016/thumbnails/instaforex_dinner_44.jpg" alt=" " /></a></li>
                                    <li><a href="images/dinner-2016/instaforex_dinner_45.jpg" rel="prettyPhoto[gallery4]" title=""><img src="images/dinner-2016/thumbnails/instaforex_dinner_45.jpg" alt=" " /></a></li>
                                    <li><a href="images/dinner-2016/instaforex_dinner_46.jpg" rel="prettyPhoto[gallery4]" title=""><img src="images/dinner-2016/thumbnails/instaforex_dinner_46.jpg" alt=" " /></a></li>
                                    <li><a href="images/dinner-2016/instaforex_dinner_47.jpg" rel="prettyPhoto[gallery4]" title=""><img src="images/dinner-2016/thumbnails/instaforex_dinner_47.jpg" alt=" " /></a></li>
                                    <li><a href="images/dinner-2016/instaforex_dinner_48.jpg" rel="prettyPhoto[gallery4]" title=""><img src="images/dinner-2016/thumbnails/instaforex_dinner_48.jpg" alt=" " /></a></li>
                                    <li><a href="images/dinner-2016/instaforex_dinner_49.jpg" rel="prettyPhoto[gallery4]" title=""><img src="images/dinner-2016/thumbnails/instaforex_dinner_49.jpg" alt=" " /></a></li>
                                    <li><a href="images/dinner-2016/instaforex_dinner_50.jpg" rel="prettyPhoto[gallery4]" title=""><img src="images/dinner-2016/thumbnails/instaforex_dinner_50.jpg" alt=" " /></a></li>
                                    <li><a href="images/dinner-2016/instaforex_dinner_51.jpg" rel="prettyPhoto[gallery4]" title=""><img src="images/dinner-2016/thumbnails/instaforex_dinner_51.jpg" alt=" " /></a></li>
                                    <li><a href="images/dinner-2016/instaforex_dinner_23.jpg" rel="prettyPhoto[gallery4]" title=""><img src="images/dinner-2016/thumbnails/instaforex_dinner_23.jpg" alt=" " /></a></li>
                                    <li><a href="images/dinner-2016/instaforex_dinner_25.jpg" rel="prettyPhoto[gallery4]" title=""><img src="images/dinner-2016/thumbnails/instaforex_dinner_25.jpg" alt=" " /></a></li>
                                    <li><a href="images/dinner-2016/instaforex_dinner_33.jpg" rel="prettyPhoto[gallery4]" title=""><img src="images/dinner-2016/thumbnails/instaforex_dinner_33.jpg" alt=" " /></a></li>
                                    <li><a href="images/dinner-2016/instaforex_dinner_38.jpg" rel="prettyPhoto[gallery4]" title=""><img src="images/dinner-2016/thumbnails/instaforex_dinner_38.jpg" alt=" " /></a></li>
                                    <li><a href="images/dinner-2016/instaforex_dinner_41.jpg" rel="prettyPhoto[gallery4]" title=""><img src="images/dinner-2016/thumbnails/instaforex_dinner_41.jpg" alt=" " /></a></li>
                                </ul>

                                <h5>Ayoola Adewale Dubai Tour Sponsored by Instafxng.com</h5>
                                <ul class="gallery clearfix photo_g">
                                    <li><a href="images/ayoola/ayoola-dubai-trip-1.jpg" rel="prettyPhoto[gallery5]" title=""><img src="images/ayoola/thumbnails/ayoola-dubai-trip-1.jpg" alt=" " /></a></li>
                                    <li><a href="images/ayoola/ayoola-dubai-trip-2.jpg" rel="prettyPhoto[gallery5]" title=""><img src="images/ayoola/thumbnails/ayoola-dubai-trip-2.jpg" alt=" " /></a></li>
                                    <li><a href="images/ayoola/ayoola-dubai-trip-3.jpg" rel="prettyPhoto[gallery5]" title=""><img src="images/ayoola/thumbnails/ayoola-dubai-trip-3.jpg" alt=" " /></a></li>
                                    <li><a href="images/ayoola/ayoola-dubai-trip-4.jpg" rel="prettyPhoto[gallery5]" title=""><img src="images/ayoola/thumbnails/ayoola-dubai-trip-4.jpg" alt=" " /></a></li>
                                    <li><a href="images/ayoola/ayoola-dubai-trip-5.jpg" rel="prettyPhoto[gallery5]" title=""><img src="images/ayoola/thumbnails/ayoola-dubai-trip-5.jpg" alt=" " /></a></li>
                                    <li><a href="images/ayoola/ayoola-dubai-trip-6.jpg" rel="prettyPhoto[gallery5]" title=""><img src="images/ayoola/thumbnails/ayoola-dubai-trip-6.jpg" alt=" " /></a></li>
                                    <li><a href="images/ayoola/ayoola-dubai-trip-7.jpg" rel="prettyPhoto[gallery5]" title=""><img src="images/ayoola/thumbnails/ayoola-dubai-trip-7.jpg" alt=" " /></a></li>
                                    <li><a href="images/ayoola/ayoola-dubai-trip-8.jpg" rel="prettyPhoto[gallery5]" title=""><img src="images/ayoola/thumbnails/ayoola-dubai-trip-8.jpg" alt=" " /></a></li>
                                    <li><a href="images/ayoola/ayoola-dubai-trip-9.jpg" rel="prettyPhoto[gallery5]" title=""><img src="images/ayoola/thumbnails/ayoola-dubai-trip-9.jpg" alt=" " /></a></li>
                                    <li><a href="images/ayoola/ayoola-dubai-trip-10.jpg" rel="prettyPhoto[gallery5]" title=""><img src="images/ayoola/thumbnails/ayoola-dubai-trip-10.jpg" alt=" " /></a></li>
                                    <li><a href="images/ayoola/ayoola-dubai-trip-11.jpg" rel="prettyPhoto[gallery5]" title=""><img src="images/ayoola/thumbnails/ayoola-dubai-trip-11.jpg" alt=" " /></a></li>
                                    <li><a href="images/ayoola/ayoola-dubai-trip-12.jpg" rel="prettyPhoto[gallery5]" title=""><img src="images/ayoola/thumbnails/ayoola-dubai-trip-12.jpg" alt=" " /></a></li>
                                    <li><a href="images/ayoola/ayoola-dubai-trip-13.jpg" rel="prettyPhoto[gallery5]" title=""><img src="images/ayoola/thumbnails/ayoola-dubai-trip-13.jpg" alt=" " /></a></li>
                                    <li><a href="images/ayoola/ayoola-dubai-trip-14.jpg" rel="prettyPhoto[gallery5]" title=""><img src="images/ayoola/thumbnails/ayoola-dubai-trip-14.jpg" alt=" " /></a></li>
                                    <li><a href="images/ayoola/ayoola-dubai-trip-15.jpg" rel="prettyPhoto[gallery5]" title=""><img src="images/ayoola/thumbnails/ayoola-dubai-trip-15.jpg" alt=" " /></a></li>
                                    <li><a href="images/ayoola/ayoola-dubai-trip-16.jpg" rel="prettyPhoto[gallery5]" title=""><img src="images/ayoola/thumbnails/ayoola-dubai-trip-16.jpg" alt=" " /></a></li>
                                    <li><a href="images/ayoola/ayoola-dubai-trip-17.jpg" rel="prettyPhoto[gallery5]" title=""><img src="images/ayoola/thumbnails/ayoola-dubai-trip-17.jpg" alt=" " /></a></li>
                                    <li><a href="images/ayoola/ayoola-dubai-trip-18.jpg" rel="prettyPhoto[gallery5]" title=""><img src="images/ayoola/thumbnails/ayoola-dubai-trip-18.jpg" alt=" " /></a></li>
                                    <li><a href="images/ayoola/ayoola-dubai-trip-19.jpg" rel="prettyPhoto[gallery5]" title=""><img src="images/ayoola/thumbnails/ayoola-dubai-trip-19.jpg" alt=" " /></a></li>
                                    <li><a href="images/ayoola/ayoola-dubai-trip-20.jpg" rel="prettyPhoto[gallery5]" title=""><img src="images/ayoola/thumbnails/ayoola-dubai-trip-20.jpg" alt=" " /></a></li>
                                    <li><a href="images/ayoola/ayoola-dubai-trip-21.jpg" rel="prettyPhoto[gallery5]" title=""><img src="images/ayoola/thumbnails/ayoola-dubai-trip-21.jpg" alt=" " /></a></li>
                                    <li><a href="images/ayoola/ayoola-dubai-trip-22.jpg" rel="prettyPhoto[gallery5]" title=""><img src="images/ayoola/thumbnails/ayoola-dubai-trip-22.jpg" alt=" " /></a></li>
                                    <li><a href="images/ayoola/ayoola-dubai-trip-23.jpg" rel="prettyPhoto[gallery5]" title=""><img src="images/ayoola/thumbnails/ayoola-dubai-trip-23.jpg" alt=" " /></a></li>
                                    <li><a href="images/ayoola/ayoola-dubai-trip-24.jpg" rel="prettyPhoto[gallery5]" title=""><img src="images/ayoola/thumbnails/ayoola-dubai-trip-24.jpg" alt=" " /></a></li>
                                    <li><a href="images/ayoola/ayoola-dubai-trip-25.jpg" rel="prettyPhoto[gallery5]" title=""><img src="images/ayoola/thumbnails/ayoola-dubai-trip-25.jpg" alt=" " /></a></li>
                                    <li><a href="images/ayoola/ayoola-dubai-trip-26.jpg" rel="prettyPhoto[gallery5]" title=""><img src="images/ayoola/thumbnails/ayoola-dubai-trip-26.jpg" alt=" " /></a></li>
                                    <li><a href="images/ayoola/ayoola-dubai-trip-27.jpg" rel="prettyPhoto[gallery5]" title=""><img src="images/ayoola/thumbnails/ayoola-dubai-trip-27.jpg" alt=" " /></a></li>
                                </ul>

                                <h5>End of the Year Dinner at Four Points Hotel, Lekki Lagos - December 12, 2015</h5>
                                <ul class="gallery clearfix photo_g">
                                    <li><a href="images/dinner-2015/instaforex-dinner-1.jpg" rel="prettyPhoto[gallery4]" title=""><img src="images/dinner-2015/thumbnails/instaforex-dinner-1.jpg" alt=" " /></a></li>
                                    <li><a href="images/dinner-2015/instaforex-dinner-2.jpg" rel="prettyPhoto[gallery4]" title=""><img src="images/dinner-2015/thumbnails/instaforex-dinner-2.jpg" alt=" " /></a></li>
                                    <li><a href="images/dinner-2015/instaforex-dinner-3.jpg" rel="prettyPhoto[gallery4]" title=""><img src="images/dinner-2015/thumbnails/instaforex-dinner-3.jpg" alt=" " /></a></li>
                                    <li><a href="images/dinner-2015/instaforex-dinner-4.jpg" rel="prettyPhoto[gallery4]" title=""><img src="images/dinner-2015/thumbnails/instaforex-dinner-4.jpg" alt=" " /></a></li>
                                    <li><a href="images/dinner-2015/instaforex-dinner-5.jpg" rel="prettyPhoto[gallery4]" title=""><img src="images/dinner-2015/thumbnails/instaforex-dinner-5.jpg" alt=" " /></a></li>
                                    <li><a href="images/dinner-2015/instaforex-dinner-6.jpg" rel="prettyPhoto[gallery4]" title=""><img src="images/dinner-2015/thumbnails/instaforex-dinner-6.jpg" alt=" " /></a></li>
                                    <li><a href="images/dinner-2015/instaforex-dinner-7.jpg" rel="prettyPhoto[gallery4]" title=""><img src="images/dinner-2015/thumbnails/instaforex-dinner-7.jpg" alt=" " /></a></li>
                                    <li><a href="images/dinner-2015/instaforex-dinner-8.jpg" rel="prettyPhoto[gallery4]" title=""><img src="images/dinner-2015/thumbnails/instaforex-dinner-8.jpg" alt=" " /></a></li>
                                    <li><a href="images/dinner-2015/instaforex-dinner-9.jpg" rel="prettyPhoto[gallery4]" title=""><img src="images/dinner-2015/thumbnails/instaforex-dinner-9.jpg" alt=" " /></a></li>
                                    <li><a href="images/dinner-2015/instaforex-dinner-10.jpg" rel="prettyPhoto[gallery4]" title=""><img src="images/dinner-2015/thumbnails/instaforex-dinner-10.jpg" alt=" " /></a></li>
                                    <li><a href="images/dinner-2015/instaforex-dinner-11.jpg" rel="prettyPhoto[gallery4]" title=""><img src="images/dinner-2015/thumbnails/instaforex-dinner-11.jpg" alt=" " /></a></li>
                                    <li><a href="images/dinner-2015/instaforex-dinner-12.jpg" rel="prettyPhoto[gallery4]" title=""><img src="images/dinner-2015/thumbnails/instaforex-dinner-12.jpg" alt=" " /></a></li>
                                    <li><a href="images/dinner-2015/instaforex-dinner-13.jpg" rel="prettyPhoto[gallery4]" title=""><img src="images/dinner-2015/thumbnails/instaforex-dinner-13.jpg" alt=" " /></a></li>
                                    <li><a href="images/dinner-2015/instaforex-dinner-14.jpg" rel="prettyPhoto[gallery4]" title=""><img src="images/dinner-2015/thumbnails/instaforex-dinner-14.jpg" alt=" " /></a></li>
                                    <li><a href="images/dinner-2015/instaforex-dinner-15.jpg" rel="prettyPhoto[gallery4]" title=""><img src="images/dinner-2015/thumbnails/instaforex-dinner-15.jpg" alt=" " /></a></li>
                                    <li><a href="images/dinner-2015/instaforex-dinner-16.jpg" rel="prettyPhoto[gallery4]" title=""><img src="images/dinner-2015/thumbnails/instaforex-dinner-16.jpg" alt=" " /></a></li>
                                    <li><a href="images/dinner-2015/instaforex-dinner-17.jpg" rel="prettyPhoto[gallery4]" title=""><img src="images/dinner-2015/thumbnails/instaforex-dinner-17.jpg" alt=" " /></a></li>
                                    <li><a href="images/dinner-2015/instaforex-dinner-18.jpg" rel="prettyPhoto[gallery4]" title=""><img src="images/dinner-2015/thumbnails/instaforex-dinner-18.jpg" alt=" " /></a></li>
                                    <li><a href="images/dinner-2015/instaforex-dinner-19.jpg" rel="prettyPhoto[gallery4]" title=""><img src="images/dinner-2015/thumbnails/instaforex-dinner-19.jpg" alt=" " /></a></li>
                                    <li><a href="images/dinner-2015/instaforex-dinner-20.jpg" rel="prettyPhoto[gallery4]" title=""><img src="images/dinner-2015/thumbnails/instaforex-dinner-20.jpg" alt=" " /></a></li>
                                    <li><a href="images/dinner-2015/instaforex-dinner-21.jpg" rel="prettyPhoto[gallery4]" title=""><img src="images/dinner-2015/thumbnails/instaforex-dinner-21.jpg" alt=" " /></a></li>
                                    <li><a href="images/dinner-2015/instaforex-dinner-22.jpg" rel="prettyPhoto[gallery4]" title=""><img src="images/dinner-2015/thumbnails/instaforex-dinner-22.jpg" alt=" " /></a></li>
                                    <li><a href="images/dinner-2015/instaforex-dinner-23.jpg" rel="prettyPhoto[gallery4]" title=""><img src="images/dinner-2015/thumbnails/instaforex-dinner-23.jpg" alt=" " /></a></li>
                                    <li><a href="images/dinner-2015/instaforex-dinner-24.jpg" rel="prettyPhoto[gallery4]" title=""><img src="images/dinner-2015/thumbnails/instaforex-dinner-24.jpg" alt=" " /></a></li>
                                    <li><a href="images/dinner-2015/instaforex-dinner-25.jpg" rel="prettyPhoto[gallery4]" title=""><img src="images/dinner-2015/thumbnails/instaforex-dinner-25.jpg" alt=" " /></a></li>
                                    <li><a href="images/dinner-2015/instaforex-dinner-26.jpg" rel="prettyPhoto[gallery4]" title=""><img src="images/dinner-2015/thumbnails/instaforex-dinner-26.jpg" alt=" " /></a></li>
                                    <li><a href="images/dinner-2015/instaforex-dinner-27.jpg" rel="prettyPhoto[gallery4]" title=""><img src="images/dinner-2015/thumbnails/instaforex-dinner-27.jpg" alt=" " /></a></li>
                                    <li><a href="images/dinner-2015/instaforex-dinner-28.jpg" rel="prettyPhoto[gallery4]" title=""><img src="images/dinner-2015/thumbnails/instaforex-dinner-28.jpg" alt=" " /></a></li>
                                    <li><a href="images/dinner-2015/instaforex-dinner-29.jpg" rel="prettyPhoto[gallery4]" title=""><img src="images/dinner-2015/thumbnails/instaforex-dinner-29.jpg" alt=" " /></a></li>
                                </ul>
                                
                                <h5>Lucky 150 Promotion - Presentation / Award of winnings - October 24, 2015</h5>
                                <ul class="gallery clearfix photo_g">
                                    <li><a href="images/lucky-150/lucky-150-promo-pics-1.jpg" rel="prettyPhoto[gallery1]" title=""><img src="images/lucky-150/thumbnails/lucky-150-promo-pics-1.jpg" alt=" " /></a></li>
                                    <li><a href="images/lucky-150/lucky-150-promo-pics-2.jpg" rel="prettyPhoto[gallery1]" title=""><img src="images/lucky-150/thumbnails/lucky-150-promo-pics-2.jpg" alt=" " /></a></li>
                                    <li><a href="images/lucky-150/lucky-150-promo-pics-3.jpg" rel="prettyPhoto[gallery1]" title=""><img src="images/lucky-150/thumbnails/lucky-150-promo-pics-3.jpg" alt=" " /></a></li>
                                    <li><a href="images/lucky-150/lucky-150-promo-pics-4.jpg" rel="prettyPhoto[gallery1]" title=""><img src="images/lucky-150/thumbnails/lucky-150-promo-pics-4.jpg" alt=" " /></a></li>
                                    <li><a href="images/lucky-150/lucky-150-promo-pics-5.jpg" rel="prettyPhoto[gallery1]" title=""><img src="images/lucky-150/thumbnails/lucky-150-promo-pics-5.jpg" alt=" " /></a></li>
                                    <li><a href="images/lucky-150/lucky-150-promo-pics-6.jpg" rel="prettyPhoto[gallery1]" title=""><img src="images/lucky-150/thumbnails/lucky-150-promo-pics-6.jpg" alt=" " /></a></li>
                                    <li><a href="images/lucky-150/lucky-150-promo-pics-7.jpg" rel="prettyPhoto[gallery1]" title=""><img src="images/lucky-150/thumbnails/lucky-150-promo-pics-7.jpg" alt=" " /></a></li>
                                    <li><a href="images/lucky-150/lucky-150-promo-pics-8.jpg" rel="prettyPhoto[gallery1]" title=""><img src="images/lucky-150/thumbnails/lucky-150-promo-pics-8.jpg" alt=" " /></a></li>
                                    <li><a href="images/lucky-150/lucky-150-promo-pics-9.jpg" rel="prettyPhoto[gallery1]" title=""><img src="images/lucky-150/thumbnails/lucky-150-promo-pics-9.jpg" alt=" " /></a></li>
                                    <li><a href="images/lucky-150/lucky-150-promo-pics-10.jpg" rel="prettyPhoto[gallery1]" title=""><img src="images/lucky-150/thumbnails/lucky-150-promo-pics-10.jpg" alt=" " /></a></li>
                                    <li><a href="images/lucky-150/lucky-150-promo-pics-11.jpg" rel="prettyPhoto[gallery1]" title=""><img src="images/lucky-150/thumbnails/lucky-150-promo-pics-11.jpg" alt=" " /></a></li>
                                    <li><a href="images/lucky-150/lucky-150-promo-pics-12.jpg" rel="prettyPhoto[gallery1]" title=""><img src="images/lucky-150/thumbnails/lucky-150-promo-pics-12.jpg" alt=" " /></a></li>
                                    <li><a href="images/lucky-150/lucky-150-promo-pics-13.jpg" rel="prettyPhoto[gallery1]" title=""><img src="images/lucky-150/thumbnails/lucky-150-promo-pics-13.jpg" alt=" " /></a></li>
                                    <li><a href="images/lucky-150/lucky-150-promo-pics-14.jpg" rel="prettyPhoto[gallery1]" title=""><img src="images/lucky-150/thumbnails/lucky-150-promo-pics-14.jpg" alt=" " /></a></li>
                                    <li><a href="images/lucky-150/lucky-150-promo-pics-15.jpg" rel="prettyPhoto[gallery1]" title=""><img src="images/lucky-150/thumbnails/lucky-150-promo-pics-15.jpg" alt=" " /></a></li>
                                    <li><a href="images/lucky-150/lucky-150-promo-pics-16.jpg" rel="prettyPhoto[gallery1]" title=""><img src="images/lucky-150/thumbnails/lucky-150-promo-pics-16.jpg" alt=" " /></a></li>
                                    <li><a href="images/lucky-150/lucky-150-promo-pics-17.jpg" rel="prettyPhoto[gallery1]" title=""><img src="images/lucky-150/thumbnails/lucky-150-promo-pics-17.jpg" alt=" " /></a></li>
                                    <li><a href="images/lucky-150/lucky-150-promo-pics-18.jpg" rel="prettyPhoto[gallery1]" title=""><img src="images/lucky-150/thumbnails/lucky-150-promo-pics-18.jpg" alt=" " /></a></li>
                                    <li><a href="images/lucky-150/lucky-150-promo-pics-19.jpg" rel="prettyPhoto[gallery1]" title=""><img src="images/lucky-150/thumbnails/lucky-150-promo-pics-19.jpg" alt=" " /></a></li>
                                    <li><a href="images/lucky-150/lucky-150-promo-pics-20.jpg" rel="prettyPhoto[gallery1]" title=""><img src="images/lucky-150/thumbnails/lucky-150-promo-pics-20.jpg" alt=" " /></a></li>
                                    <li><a href="images/lucky-150/lucky-150-promo-pics-21.jpg" rel="prettyPhoto[gallery1]" title=""><img src="images/lucky-150/thumbnails/lucky-150-promo-pics-21.jpg" alt=" " /></a></li>
                                    <li><a href="images/lucky-150/lucky-150-promo-pics-22.jpg" rel="prettyPhoto[gallery1]" title=""><img src="images/lucky-150/thumbnails/lucky-150-promo-pics-22.jpg" alt=" " /></a></li>
                                    <li><a href="images/lucky-150/lucky-150-promo-pics-23.jpg" rel="prettyPhoto[gallery1]" title=""><img src="images/lucky-150/thumbnails/lucky-150-promo-pics-23.jpg" alt=" " /></a></li>
                                    <li><a href="images/lucky-150/lucky-150-promo-pics-24.jpg" rel="prettyPhoto[gallery1]" title=""><img src="images/lucky-150/thumbnails/lucky-150-promo-pics-24.jpg" alt=" " /></a></li>
                                    <li><a href="images/lucky-150/lucky-150-promo-pics-25.jpg" rel="prettyPhoto[gallery1]" title=""><img src="images/lucky-150/thumbnails/lucky-150-promo-pics-25.jpg" alt=" " /></a></li>
                                    <li><a href="images/lucky-150/lucky-150-promo-pics-26.jpg" rel="prettyPhoto[gallery1]" title=""><img src="images/lucky-150/thumbnails/lucky-150-promo-pics-26.jpg" alt=" " /></a></li>
                                    <li><a href="images/lucky-150/lucky-150-promo-pics-27.jpg" rel="prettyPhoto[gallery1]" title=""><img src="images/lucky-150/thumbnails/lucky-150-promo-pics-27.jpg" alt=" " /></a></li>
                                    <li><a href="images/lucky-150/lucky-150-promo-pics-28.jpg" rel="prettyPhoto[gallery1]" title=""><img src="images/lucky-150/thumbnails/lucky-150-promo-pics-28.jpg" alt=" " /></a></li>
                                    <li><a href="images/lucky-150/lucky-150-promo-pics-29.jpg" rel="prettyPhoto[gallery1]" title=""><img src="images/lucky-150/thumbnails/lucky-150-promo-pics-29.jpg" alt=" " /></a></li>
                                    <li><a href="images/lucky-150/lucky-150-promo-pics-30.jpg" rel="prettyPhoto[gallery1]" title=""><img src="images/lucky-150/thumbnails/lucky-150-promo-pics-30.jpg" alt=" " /></a></li>
                                    <li><a href="images/lucky-150/lucky-150-promo-pics-31.jpg" rel="prettyPhoto[gallery1]" title=""><img src="images/lucky-150/thumbnails/lucky-150-promo-pics-31.jpg" alt=" " /></a></li>
                                </ul>
                                
                                <h5>End of the Year Lunch at Sheraton Hotel, Ikeja Lagos - December 21, 2014</h5>
                                <ul class="gallery clearfix photo_g">
                                    <li><a href="images/lunch-2014/instaforex-lunch-1.jpg" rel="prettyPhoto[gallery2]" title=""><img src="images/lunch-2014/thumbnails/instaforex-lunch-1.jpg" alt=" " /></a></li>
                                    <li><a href="images/lunch-2014/instaforex-lunch-2.jpg" rel="prettyPhoto[gallery2]" title=""><img src="images/lunch-2014/thumbnails/instaforex-lunch-2.jpg" alt=" " /></a></li>
                                    <li><a href="images/lunch-2014/instaforex-lunch-3.jpg" rel="prettyPhoto[gallery2]" title=""><img src="images/lunch-2014/thumbnails/instaforex-lunch-3.jpg" alt=" " /></a></li>
                                    <li><a href="images/lunch-2014/instaforex-lunch-4.jpg" rel="prettyPhoto[gallery2]" title=""><img src="images/lunch-2014/thumbnails/instaforex-lunch-4.jpg" alt=" " /></a></li>
                                    <li><a href="images/lunch-2014/instaforex-lunch-5.jpg" rel="prettyPhoto[gallery2]" title=""><img src="images/lunch-2014/thumbnails/instaforex-lunch-5.jpg" alt=" " /></a></li>
                                    <li><a href="images/lunch-2014/instaforex-lunch-6.jpg" rel="prettyPhoto[gallery2]" title=""><img src="images/lunch-2014/thumbnails/instaforex-lunch-6.jpg" alt=" " /></a></li>
                                    <li><a href="images/lunch-2014/instaforex-lunch-7.jpg" rel="prettyPhoto[gallery2]" title=""><img src="images/lunch-2014/thumbnails/instaforex-lunch-7.jpg" alt=" " /></a></li>
                                    <li><a href="images/lunch-2014/instaforex-lunch-8.jpg" rel="prettyPhoto[gallery2]" title=""><img src="images/lunch-2014/thumbnails/instaforex-lunch-8.jpg" alt=" " /></a></li>
                                    <li><a href="images/lunch-2014/instaforex-lunch-9.jpg" rel="prettyPhoto[gallery2]" title=""><img src="images/lunch-2014/thumbnails/instaforex-lunch-9.jpg" alt=" " /></a></li>
                                    <li><a href="images/lunch-2014/instaforex-lunch-10.jpg" rel="prettyPhoto[gallery2]" title=""><img src="images/lunch-2014/thumbnails/instaforex-lunch-10.jpg" alt=" " /></a></li>
                                    <li><a href="images/lunch-2014/instaforex-lunch-11.jpg" rel="prettyPhoto[gallery2]" title=""><img src="images/lunch-2014/thumbnails/instaforex-lunch-11.jpg" alt=" " /></a></li>
                                    <li><a href="images/lunch-2014/instaforex-lunch-12.jpg" rel="prettyPhoto[gallery2]" title=""><img src="images/lunch-2014/thumbnails/instaforex-lunch-12.jpg" alt=" " /></a></li>
                                    <li><a href="images/lunch-2014/instaforex-lunch-13.jpg" rel="prettyPhoto[gallery2]" title=""><img src="images/lunch-2014/thumbnails/instaforex-lunch-13.jpg" alt=" " /></a></li>
                                    <li><a href="images/lunch-2014/instaforex-lunch-14.jpg" rel="prettyPhoto[gallery2]" title=""><img src="images/lunch-2014/thumbnails/instaforex-lunch-14.jpg" alt=" " /></a></li>
                                    <li><a href="images/lunch-2014/instaforex-lunch-15.jpg" rel="prettyPhoto[gallery2]" title=""><img src="images/lunch-2014/thumbnails/instaforex-lunch-15.jpg" alt=" " /></a></li>
                                    <li><a href="images/lunch-2014/instaforex-lunch-16.jpg" rel="prettyPhoto[gallery2]" title=""><img src="images/lunch-2014/thumbnails/instaforex-lunch-16.jpg" alt=" " /></a></li>
                                    <li><a href="images/lunch-2014/instaforex-lunch-17.jpg" rel="prettyPhoto[gallery2]" title=""><img src="images/lunch-2014/thumbnails/instaforex-lunch-17.jpg" alt=" " /></a></li>
                                    <li><a href="images/lunch-2014/instaforex-lunch-18.jpg" rel="prettyPhoto[gallery2]" title=""><img src="images/lunch-2014/thumbnails/instaforex-lunch-18.jpg" alt=" " /></a></li>
                                    <li><a href="images/lunch-2014/instaforex-lunch-19.jpg" rel="prettyPhoto[gallery2]" title=""><img src="images/lunch-2014/thumbnails/instaforex-lunch-19.jpg" alt=" " /></a></li>
                                    <li><a href="images/lunch-2014/instaforex-lunch-20.jpg" rel="prettyPhoto[gallery2]" title=""><img src="images/lunch-2014/thumbnails/instaforex-lunch-20.jpg" alt=" " /></a></li>
                                    <li><a href="images/lunch-2014/instaforex-lunch-21.jpg" rel="prettyPhoto[gallery2]" title=""><img src="images/lunch-2014/thumbnails/instaforex-lunch-21.jpg" alt=" " /></a></li>
                                    <li><a href="images/lunch-2014/instaforex-lunch-22.jpg" rel="prettyPhoto[gallery2]" title=""><img src="images/lunch-2014/thumbnails/instaforex-lunch-22.jpg" alt=" " /></a></li>
                                </ul>
                                
                                <h5>Instaforex KIA Picanto Award to Lucky Nigerian Forex Trader - November 17, 2013</h5>
                                <ul class="gallery clearfix photo_g">
                                    <li><a href="images/picanto-award/picanto-award-1.jpg" rel="prettyPhoto[gallery3]" title=""><img src="images/picanto-award/thumbnails/picanto-award-1.jpg" alt=" " /></a></li>
                                    <li><a href="images/picanto-award/picanto-award-2.jpg" rel="prettyPhoto[gallery3]" title=""><img src="images/picanto-award/thumbnails/picanto-award-2.jpg" alt=" " /></a></li>
                                    <li><a href="images/picanto-award/picanto-award-3.jpg" rel="prettyPhoto[gallery3]" title=""><img src="images/picanto-award/thumbnails/picanto-award-3.jpg" alt=" " /></a></li>
                                    <li><a href="images/picanto-award/picanto-award-4.jpg" rel="prettyPhoto[gallery3]" title=""><img src="images/picanto-award/thumbnails/picanto-award-4.jpg" alt=" " /></a></li>
                                    <li><a href="images/picanto-award/picanto-award-5.jpg" rel="prettyPhoto[gallery3]" title=""><img src="images/picanto-award/thumbnails/picanto-award-5.jpg" alt=" " /></a></li>
                                    <li><a href="images/picanto-award/picanto-award-6.jpg" rel="prettyPhoto[gallery3]" title=""><img src="images/picanto-award/thumbnails/picanto-award-6.jpg" alt=" " /></a></li>
                                    <li><a href="images/picanto-award/picanto-award-7.jpg" rel="prettyPhoto[gallery3]" title=""><img src="images/picanto-award/thumbnails/picanto-award-7.jpg" alt=" " /></a></li>
                                    <li><a href="images/picanto-award/picanto-award-8.jpg" rel="prettyPhoto[gallery3]" title=""><img src="images/picanto-award/thumbnails/picanto-award-8.jpg" alt=" " /></a></li>
                                    <li><a href="images/picanto-award/picanto-award-9.jpg" rel="prettyPhoto[gallery3]" title=""><img src="images/picanto-award/thumbnails/picanto-award-9.jpg" alt=" " /></a></li>
                                    <li><a href="images/picanto-award/picanto-award-10.jpg" rel="prettyPhoto[gallery3]" title=""><img src="images/picanto-award/thumbnails/picanto-award-10.jpg" alt=" " /></a></li>
                                    <li><a href="images/picanto-award/picanto-award-11.jpg" rel="prettyPhoto[gallery3]" title=""><img src="images/picanto-award/thumbnails/picanto-award-11.jpg" alt=" " /></a></li>
                                    <li><a href="images/picanto-award/picanto-award-12.jpg" rel="prettyPhoto[gallery3]" title=""><img src="images/picanto-award/thumbnails/picanto-award-12.jpg" alt=" " /></a></li>
                                </ul>
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