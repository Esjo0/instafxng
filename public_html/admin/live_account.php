<?php
require_once("../init/initialize_admin.php");
if (!$session_admin->is_logged_in()) {
    redirect_to("login.php");
}

?>
<!DOCTYPE html>
<html lang="en">
    <head>
        <base target="_self">
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>Instaforex Nigeria | Admin - Open Live Account</title>
        <meta name="title" content="Instaforex Nigeria | Admin - Open Live Account" />
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
                            <h4><strong>OPEN LIVE ACCOUNT</strong></h4>
                        </div>
                    </div>
                    
                    <div class="section-tint super-shadow">
                        <div class="row">
                            <div class="col-sm-12">
                                <p>Fill the form below to open a live account. <a href="live_account.php" class="btn btn-primary">Click Here</a> to refresh the account opening form.</p>

                                <iframe class="embed-responsive-item" id="frame" style='width: 780px;border:0;height:auto;' src="https://secure.instaforex.com/en/partner_open_account.aspx?x=BBLR&width=580&showlogo=false&color=red&host="" style="padding:0; margin:0" scrolling="no" onload="var th=this; setTimeout(function() {var h=null;if (!h) if (location.hash.match(/^#h(\d+)/)) h=RegExp.$1;if (h) th.style .height=parseInt(h)+170+'px';}, 10);" ></iframe>
                                <script> document.getElementById('frame').src += window.location; </script>
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