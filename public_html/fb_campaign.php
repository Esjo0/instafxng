<?php
require_once 'init/initialize_general.php';
$thisPage = "Home";
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
    <script>
        window.onload=function(){
            document.getElementById("open").click();
        };
        //modal closes in 5sec
        setTimeout(modal_hide, 8000);
        function modal_hide() { document.getElementById('open').click(); }

        $(document).ready(function () {

            if (screen.width < 1024) {
                document.getElementById('btn_div').style.display = 'block';
            }
            else {
                document.getElementById('btn_div').style.display = 'none';
            }

        });

    $(window).scroll(function() {
    var hT = $('#old_div').offset().top,
    hH = $('#old_div').outerHeight(),
    wH = $(window).height(),
    wS = $(this).scrollTop();
    if (wS < (hT+hH-wH)){
        document.getElementById('new').style.display = 'block';
        document.getElementById('old').style.display = 'none';
    }
    });
        $(window).scroll(function() {
            var xT = $('#new_div').offset().top,
                xH = $('#new_div').outerHeight(),
                yH = $(window).height(),
                yS = $(this).scrollTop();
            if (yS < (xT+xH-yH)){
                document.getElementById('old').style.display = 'block';
                document.getElementById('new').style.display = 'none';
            }
        });
    </script>
    </script>
    <!-- Facebook Pixel Code -->
    <script>
        !function(f,b,e,v,n,t,s)
        {if(f.fbq)return;n=f.fbq=function(){n.callMethod?
            n.callMethod.apply(n,arguments):n.queue.push(arguments)};
            if(!f._fbq)f._fbq=n;n.push=n;n.loaded=!0;n.version='2.0';
            n.queue=[];t=b.createElement(e);t.async=!0;
            t.src=v;s=b.getElementsByTagName(e)[0];
            s.parentNode.insertBefore(t,s)}(window,document,'script',
            'https://connect.facebook.net/en_US/fbevents.js');
        fbq('init', '177357696206919');
        fbq('track', 'CompleteRegistration');
    </script>
    <noscript>
        <img height="1" width="1"
             src="https://www.facebook.com/tr?id=177357696206919&ev=PageView
&noscript=1"/>
    </noscript>
    <!-- End Facebook Pixel Code -->
</head>
<body>
<?php require_once 'layouts/header.php'; ?>
<!-- Main Body: The is the main content area of the web site, contains a side bar  -->
<div id="main-body" class="container-fluid">
    <div class="row ">
<div >
        <div id="main-body-side-bar" class="col-md-6">
            <div id="new_div" class="section-tint super-shadow" style="margin-top: 20px; margin-bottom: 20px;">
                <h2 class="text-center">Your Next Step as a Newbie</h2>
                <p class="text-center"><img src="images/train.png" alt="" class="img-responsive img-thumbnail" style="height:250px; width:250px; box-shadow: 0 4px 8px 0 rgba(0, 0, 0, 0.2), 0 6px 20px 0 rgba(0, 0, 0, 0.19)"/></p>
                <p>Your first step as a beginner is to learn how the market works, and how you can make money from it.</p>
                <p>This is a highly recommended move because overtime it is evident that traders who get trained catch up with the market quickly and start making profit faster than those who skipped training.</p>
                <p class="text-center"><strong><a target="_blank" class="btn btn-info" href="http://bit.ly/2iExTpN">Click here to start training for free!</a></strong></p>
                <p>The FXacademy is waiting to receive you right away and equip you with a structured online course that you can take right there on your device from any where in Nigeria and a no deposit required Demo account, that allows you to practice.</p>
                <p>After this you can get access to more personalized training service with our expert team, welcome bonuses and much more!</p>
                <p class="text-center"><strong><a target="_blank" class="btn btn-info" href="mailto:support@instafxng.com?subject=130%20Percent%20Bonus%20&body=Hello%20Mercy,%0A%0AI%20am%20interested%20in%20getting%20the%20130%20percent%20bonus.%0A%0AThanks!">Click here to begin learning for free.</a></strong></p>
            </div>
        </div>
        <div id="main-body-side-bar" class="col-lg-6">
            <div id="old_div" class="section-tint super-shadow" style=" margin-top: 20px; margin-bottom: 20px">
                <h2 class="text-center">Your Next Step As a Forex Trader</h2>
                <p class="text-center"><img src="https://instafxng.com/imgsource/ilpr_mail.jpg" alt="" class="img-responsive img-thumbnail" style="height:250px; width:250px; box-shadow: 0 4px 8px 0 rgba(0, 0, 0, 0.2), 0 6px 20px 0 rgba(0, 0, 0, 0.19)"/></p>
                <p>If you are not new to Trading Forex and you do not own an InstaForex Account or you haven’t enrolled into our loyalty program, here are reasons to create an account and enroll into our loyalty program right away!</p>
                <p>Start now! <a target="_blank" class="btn btn-info" href="http://bit.ly/2mpqehQ">click here to fill in the step 1 and 2 form to create and enroll your InstaForex account!</a></p>
                <ul>
                    <li>Unbeatable Discounted Funding Rate.</li>
                    <li>Fastest Deposit and Withdrawal Processing.</li>
                    <li>Diversified Funding Means From Your Local Bank Account (Online Card Payment, Direct deposit, USSD Transfer, ATM Transfer)</li>
                    <li>85% Super Signal Services</li>
                    <li>Up to 100% Welcome Bonus </li>
                    <li>Up to $500 Monthly Loyalty Rewards</li>
                    <li>Up to N1,000,000 Yearly Loyalty Rewards (See past winners below )</li>
                    <p class="text-center"><img src="https://instafxng.com/images/Loyalty_Points_Images/ILPR_Collage.jpg" alt="" class="img-responsive img-thumbnail" style="height:350px; box-shadow: 0 4px 8px 0 rgba(0, 0, 0, 0.2), 0 6px 20px 0 rgba(0, 0, 0, 0.19)"/></p>
                    <li>Account Audit and Strategy Review With Experts</li>
                </ul>
                <p>And lots more… Your next step is to join the winning team and start enjoying premium Forex services coupled with smooth client experience.</p>
                <p>Start now! <a target="_blank" class="btn btn-info" href="http://bit.ly/2mpqehQ">click here to fill in the step 1 and 2 form to create and enroll your InstaForex account!</a></p>
                <p><strong>NOTICE! NOTICE! NOTICE!</strong>  Only enrolled accounts can enjoy the full benefits listed above, ensure to proceed to step 2 after creating your account. <a target="_blank" class="btn btn-info" href="http://bit.ly/2mpqehQ">Click here to create an account now.</a></p>
                <p>Would you need help creating an account or getting registered for the training? Call our support on 08084603182, 08028281192 or 08083956750 for help.</p>
            </div>
        </div>
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
<div id="btn_div">
<a id="old" href="#old_div" class="btn btn-default" style="display:none; width:100%; position: fixed; bottom: 10px;">Click Here if You have Traded forex before</a>
<a id="new" href="#new_div" class="btn btn-default" style="display:none; width:100%; position: fixed; bottom: 10px;">Click Here if You are new to forex</a>
</div>
<div class="wthree_copy_right">
    <div class="container">
        <hr />
        <p class="text-center">&copy; 2018 Instant Web Net Technologies. All rights reserved <a href="http://Instafxng.com/">Instafxng.com</a>
            Contact us on 08028281192.
        </p>
    </div>
</div>
</body>
</html>