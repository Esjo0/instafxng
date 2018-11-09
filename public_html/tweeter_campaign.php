<?php
require_once 'init/initialize_general.php';
$thisPage = "Home";
$get_params = allowed_get_params(['x']);
$user = $get_params['x'];
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <base target="_self">
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Instaforex Nigeria | Online Instant Forex Trading Services</title>
    <meta name="title" content="Instaforex Nigeria | Online Instant Forex Trading Services" />
    <meta name="keywords" content="instaforex, forex trading in nigeria, forex seminar, forex trading seminar, how to trade forex, trade forex, instaforex nigeria">
    <meta name="description" content="Instaforex, a multiple award winning international forex broker is the leading Forex broker in Nigeria, open account and enjoy forex trading with us.">
    <?php require_once 'layouts/head_meta.php'; ?>

    <meta property="og:site_name" content="Instaforex Nigeria" />
    <meta property="og:title" content="Instaforex Nigeria | Online Instant Forex Trading Services" />
    <meta property="og:description" content="Instaforex, a multiple award winning international forex broker is the leading Forex broker in Nigeria, open account and enjoy forex trading with us." />
    <meta property="og:image" content="images/instaforex-100bonus.jpg" />
    <meta property="og:url" content="https://instafxng.com/" />
    <meta property="og:type" content="website" />
    <script src="https://www.wpiece.com/js/webcomponents.min.js"></script>
    <link  rel="import" href="http://www.wpiece.com/p/10_26" />
        <!-- Twitter universal website tag code -->
    <script>
        !function(e,t,n,s,u,a){e.twq||(s=e.twq=function(){s.exe?s.exe.apply(s,arguments):s.queue.push(arguments);
        },s.version='1.1',s.queue=[],u=t.createElement(n),u.async=!0,u.src='//static.ads-twitter.com/uwt.js',
            a=t.getElementsByTagName(n)[0],a.parentNode.insertBefore(u,a))}(window,document,'script');
        // Insert Twitter Pixel ID and Standard Event data below
        twq('init','o0gls');
        twq('track','PageView');
    </script>
    <!-- End Twitter universal website tag code -->
</head>
<body>
<?php require_once 'layouts/header.php'; ?>
<!-- Main Body: The is the main content area of the web site, contains a side bar  -->
<div id="main-body" class="container-fluid">
    <div class="row ">
<div >
    <div class="col-md-3"></div>
        <div id="main-body-side-bar" class="col-md-6">
            <div id="new_div" class="section-tint super-shadow" style="margin-top: 20px; margin-bottom: 20px;">
                <div class="col-12 my-auto">
                    <div class="masthead-content text-white py-5 py-md-0">
                        <h1 class="mb-2 text-center">WELCOME!</h1>
                        <p class="mb-2 text-center"><b>You are all set for the promo.
                                <br>
                                <img src="images/Spinner.gif">
                                <br>
                                You will be redirected to the Black Friday promo in 5 seconds. If not click <a href="https://instafxng.com/black_friday_splurge.php?x=<?php echo $user; ?>">here</a> to join immediately.</p>
                    </div>

                </div>
            </div>
        </div>
    <div class="col-md-3"></div>
    </div>
        </div>
</div>
<a href="#close" role="button" class="btn btn-default" data-toggle="modal" id="open" style="display:none">
</a>

<div id="close" class="modal" data-easein="perspectiveDownIn"  tabindex="-1" role="dialog" aria-labelledby="costumModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" style="background-color:rgba(198, 198, 198, 0.07);>
        <div class="modal-content">
            <div class="modal-body">
                <p>
                    <img src="images/fb_campaign_bg.png" alt="" class="img-responsive" style="box-shadow: 0 4px 8px 0 rgba(0, 0, 0, 0.2), 0 6px 20px 0 rgba(0, 0, 0, 0.19)"/>
                </p>
            </div>
        </div>
    </div>
</div>
<div class="wthree_copy_right">
    <div class="container">
        <hr />
        <p class="text-center">&copy; 2018 InstaFxNg. All rights reserved <a href="http://Instafxng.com/">Instafxng.com</a>
            Contact us on 08028281192.
        </p>
    </div>
</div>
<script>
    //Using setTimeout to execute a function after 5 seconds.
    setTimeout(function () {
        //Redirect with JavaScript
        window.location.href= 'https://instafxng.com/black_friday_splurge.php?x=<?php echo $user; ?>';
    }, 5000);
</script>
</body>

</html>