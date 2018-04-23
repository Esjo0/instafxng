<?php
session_start();
require_once '../init/initialize_client.php';
require_once '../init/initialize_general.php';
$client_operation = new clientOperation();
$user_code = $client_operation->get_user_by_email($_SESSION['email']);
if(empty($user_code) || !isset($user_code)) { redirect_to("../fxacademy/register.php?id=".$_SESSION['email']);}

$user_ifx_details = $client_operation->get_user_by_code($user_code['user_code']);

if($user_ifx_details) {
    $found_user = array(
        'user_code' => $user_ifx_details['client_user_code'],
        'status' => $user_ifx_details['client_status'],
        'first_name' => $user_ifx_details['client_first_name'],
        'last_name' => $user_ifx_details['client_last_name'],
        'email' => $user_ifx_details['client_email']
    );
    $session_client->login($found_user);

    // Check if this is a first time login, then log the date
    if(empty($user_ifx_details['client_academy_first_login']) || is_null($user_ifx_details['client_academy_first_login'])) {
        $client_operation->log_academy_first_login($user_ifx_details['client_first_name'], $user_ifx_details['client_email'], $user_ifx_details['client_user_code']);
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <title>Create Multiple Streams of Income | Instaforex Nigeria</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <meta name="keywords" content=""/>
    <script type="application/x-javascript">
        addEventListener("load", function () { setTimeout(hideURLbar, 0); }, false);
        function hideURLbar() { window.scrollTo(0, 1); }
    </script>
    <link rel="stylesheet" href="css/bootstrap.css">
    <link rel="stylesheet" href="css/style.css" type="text/css" media="all"/>
    <link rel="stylesheet" href="css/font-awesome.css">
    <link href="//fonts.googleapis.com/css?family=Roboto+Condensed:300,300i,400,400i,700,700i" rel="stylesheet">
    <link href="//fonts.googleapis.com/css?family=Open+Sans:300,300i,400,400i,600,600i,700,700i,800,800i" rel="stylesheet">
    <!-- Facebook Pixel Code -->
    <script>
        !function (f, b, e, v, n, t, s) {
            if (f.fbq)return;
            n = f.fbq = function () {
                n.callMethod ?
                    n.callMethod.apply(n, arguments) : n.queue.push(arguments)
            };
            if (!f._fbq)f._fbq = n;
            n.push = n;
            n.loaded = !0;
            n.version = '2.0';
            n.queue = [];
            t = b.createElement(e);
            t.async = !0;
            t.src = v;
            s = b.getElementsByTagName(e)[0];
            s.parentNode.insertBefore(t, s)
        }(window, document, 'script',
            'https://connect.facebook.net/en_US/fbevents.js');
        fbq('init', '177357696206919');
        fbq('track', 'PageView');
    </script>
    <noscript>
        <img height="1" width="1" src="https://www.facebook.com/tr?id=177357696206919&ev=PageView&noscript=1"/>
    </noscript>
    <!-- End Facebook Pixel Code -->
</head>
<body>
<div class="header fixed-top">
    <nav class="navbar navbar-default link-effect-8" id="link-effect-8">
        <div class="navbar-header">
            <h1><a href="index.php"><img class="img-responsive" src="../images/ifxlogo.png"></a></h1>
        </div>
        <div class="top-nav-text">
            <div class="nav-contact-w3ls">
                <p style="color: #000000"><i class="glyphicon glyphicon-phone-alt" aria-hidden="true"></i><b> +(234) 08028281192</b></p></div>
        </div>
        <!-- navbar-header -->
        <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
        </div>
        <div class="clearfix"></div>
    </nav>

</div>
<!-- Slider -->
<div class="slider" id="home">
    <div class="callbacks_container">
        <ul class="rslides" id="slider">
            <li>
                <div class="w3layouts-banner-top w3layouts-banner-top1">
                    <div class="banner-dott">
                        <div class="container">
                            <div class="slider-info">
                                <div class="col-md-7  slider-info-txt">
                                    <h2>Sit at the comfort of your home or office and earn in dollars!</h2>
                                    <div class="w3ls-button">
                                        <a href="https://instafxng.com/fxacademy/">Get Started</a>
                                    </div>
                                </div><div class="col-md-5"></div>
                            </div>
                        </div>
                    </div>
                </div>
            </li>
        </ul>
    </div>
    <div class="clearfix"></div>
</div>
<!-- //Slider -->
<!-- welcome -->
<!--<div class="welcome" id="about">
    <div class="container">

        <div class="welcome-grids">
            <div class="col-md-6 welcome-w3right">
                <img src="images/ab1.jpg" class="img-responsive" alt=""/>
            </div>
            <div class="col-md-6 welcome-w3left">
                <h3>Need Some Extra Cash?</h3>
                <!--<h4>Sit at the comfort of your home or office and make over $20 day!</h4>-->
                <!--<p class="text-justify">Could you use an extra $20 every day? $100 every week? Or even $400 every month?</p>
                <p class="text-justify">Are you used to spending hours, surfing the Internet, looking for a particular
                    business
                    which you can do alongside your business or 9-5 job?</p>
                <p class="text-justify">If so, you are one of the many people who would benefit from Forex Trading.</p>
                <p class="text-justify">A hassle-free, business that you can profit from every single day without
                    working
                    your fingers off for it! You read that right buddy!</p>
                <div class="readmore-w3-about">
                    <a class="readmore" href="https://instafxng.com/fxacademy/" data-toggle="modal" data-target="#myModal">Get Started Now</a>
                </div>
            </div>
            <div class="clearfix"></div>
        </div>
    </div>
</div>-->
<!-- //welcome -->
<!--/services-->
<div class="services" id="services">
    <div class="container">
        <div class="w3-heading-all"><h3>Let's Get You Started</h3></div>
        <div class="w3-agile-services-grids1">
            <div class="col-md-6 services-left-grid">
                <h3>WHAT IS FOREX TRADING?</h3>
                <h5><span><i class="fa fa-check" aria-hidden="true"></i></span>Trading of currencies.</h5>
                <p class="text-justify">Online Forex trading is the exchange of one currency with another online in
                    order to make profit.</p>
                <h5><span><i class="fa fa-check" aria-hidden="true"></i></span>World largest financial market.</h5>
                <p class="text-justify">The forex market is the biggest financial market in the world (bigger than the
                    Stock market)
                    and the profit you can make from trading forex is limitless.</p>
                <div class="readmore-w3-about">
                    <a class="readmore" href="https://instafxng.com/fxacademy/" data-toggle="modal" data-target="#myModal">Get Started Now</a>
                </div>
            </div>
            <div class="col-md-6 services-right-grid">
                <img src="images/t1.jpg" class="img-responsive" alt=""/>
            </div>
            <div class="clearfix"></div>
        </div>
        <div class="w3-agile-services-grids2">
            <div class="col-md-6 services-right-grid">
                <img src="images/t21.jpg" class="img-responsive" alt=""/>
            </div>
            <div class="col-md-6 services-left-grid">
                <h3>BECOMING A SUCCESSFUL TRADER</h3>
                <h5><span><i class="fa fa-check" aria-hidden="true"></i></span>Get trained.</h5>
                <p class="text-justify">Forex trading is a highly profitable business, no doubt!
                    It is easy and profitable once you have learnt how to trade and gained adequate knowledge.</p>
                <h5><span><i class="fa fa-check" aria-hidden="true"></i></span>Daily mentoring.</h5>
                <p class="text-justify">Trading Forex gets better because you get mentored daily and you also get
                    trading
                    signals and analysis that increase your profits.</p>
                <div class="readmore-w3-about">
                    <a class="readmore" href="https://instafxng.com/fxacademy/" data-toggle="modal" data-target="#myModal">Get Started Now</a>
                </div>
            </div>
            <div class="clearfix"></div>
        </div>
        <div class="w3-agile-services-grids3">
            <div class="col-md-6 services-left-grid">
                <h3>FREE ONLINE FOREX TRADING TRAINING</h3>
                <p class="text-justify">The Free online Forex trading training completes your search for another stream
                    of
                    income through one of the best means of earning online as it is designed to take
                    you from the basics up to the point of profitable live trading.</p>
                <p>In this FREE training, you will learn:</p>
                <h5><span><i class="fa fa-check" aria-hidden="true"></i></span>What Forex Trading is.</h5>
                <h5><span><i class="fa fa-check" aria-hidden="true"></i></span>How to make money trading Forex.</h5>
                <h5><span><i class="fa fa-check" aria-hidden="true"></i></span>What you need to trade Forex.</h5>
                <h5><span><i class="fa fa-check" aria-hidden="true"></i></span>How to trade Forex.</h5>
                <p class="text-justify">A practical trading session that teaches you how you can start trading to make
                    money from Forex trading and lots more will be held regularly.</p>
                <div class="readmore-w3-about">
                    <a class="readmore" href="https://instafxng.com/fxacademy/" data-toggle="modal" data-target="#myModal">Get Started Now</a>
                </div>
            </div>
            <div class="col-md-6 services-right-grid">
                <img src="images/t3.jpg" class="img-responsive" alt=""/>
            </div>
            <div class="clearfix"></div>
        </div>
    </div>
</div>

<!-- testimonials -->
<div class="testimonials" id="testimonials">
    <div class="container">
        <div class="w3-heading-all">
            <h3>Testimonials</h3>
            <div class="w3ls_testimonials_grids">
                <section class="center slider">
                    <div class="agileits_testimonial_grid">
                        <div class="w3l_testimonial_grid">
                            <p class="text-justify">Before now, I just started trading Forex without the necessary
                                training and I made some losses since I didn’t
                                know how to trade, I was practically gambling. After I took the online training, a lot
                                of things became
                                clear and now, my bank alert na kpakam!</p>
                            <h4>Abegunde Emmanuel</h4>
                            <h5>Hr/Business Strategist</h5>
                            <div class="readmore-w3-about col-lg-12">
                                <a class="readmore" href="https://instafxng.com/fxacademy/" data-toggle="modal" data-target="#myModal">Get Started
                                    Now</a>
                            </div>
                            <div class="w3l_testimonial_grid_pos">
                                <!--<img src="images/c1.jpg" alt=" " class="img-responsive" />-->
                            </div>
                        </div>
                    </div>
                    <div class="agileits_testimonial_grid">
                        <div class="w3l_testimonial_grid">
                            <p>I completed the Free Online Training course last week and made $150 from my
                                trade yesterday after finishing the online training. I’m awed!</p>
                            <h4>Damilare Akanmu</h4>
                            <h5>Self Employed</h5>
                            <div class="readmore-w3-about col-lg-12">
                                <a class="readmore" href="https://instafxng.com/fxacademy/" data-toggle="modal" data-target="#myModal">Get Started
                                    Now</a>
                            </div>
                            <div class="w3l_testimonial_grid_pos">
                                <!--<img src="images/c2.jpg" alt=" " class="img-responsive" />-->
                            </div>
                        </div>
                    </div>
                    <div class="agileits_testimonial_grid">
                        <div class="w3l_testimonial_grid">
                            <p class="text-justify">Thank you for introducing me to Forex trading! Since I took your
                                online training,
                                I have been making daily profits from my trade. I have made a total of $50
                                within three days of trading forex. I am so happy I found Forex.</p>
                            <h4>Joshua Adegoke</h4>
                            <h5>Economist</h5>
                            <div class="readmore-w3-about col-lg-12">
                                <a class="readmore" href="https://instafxng.com/fxacademy/" data-toggle="modal" data-target="#myModal">Get Started
                                    Now</a>
                            </div>
                            <div class="w3l_testimonial_grid_pos">
                                <!--<img src="images/c3.jpg" alt=" " class="img-responsive" />-->
                            </div>
                        </div>
                    </div>
                    <div class="agileits_testimonial_grid">
                        <div class="w3l_testimonial_grid">
                            <p class="text-justify">The online training is so informative, the tone is funny, simple and
                                easy to follow.
                                I had a great time taking the course.</p>
                            <h4>Emmanuel Audu</h4>
                            <h5>Software Engineer</h5>
                            <div class="readmore-w3-about col-lg-12">
                                <a class="readmore" href="https://instafxng.com/fxacademy/" data-toggle="modal" data-target="#myModal">Get Started
                                    Now</a>
                            </div>
                            <div class="w3l_testimonial_grid_pos">
                                <!--<img src="images/c3.jpg" alt=" " class="img-responsive" />-->
                            </div>
                        </div>
                    </div>
                </section>
            </div>
        </div>
    </div>
</div>
<div class="wthree_copy_right">
    <div class="container">
        <p>© 2018 Instant Web Net Technologies. All rights reserved <a href="http://Instafxng.com/">Instafxng.com</a>
        </p>
    </div>
</div>
<!-- //footer -->
<!-- js-scripts -->
<!-- js -->
<script type="text/javascript" src="js/jquery-2.1.4.min.js"></script>
<script type="text/javascript" src="js/bootstrap.js"></script> <!-- Necessary-JavaScript-File-For-Bootstrap -->
<!-- //js -->

<!-- start-smoth-scrolling -->
<script src="js/SmoothScroll.min.js"></script>
<script type="text/javascript" src="js/move-top.js"></script>
<script type="text/javascript" src="js/easing.js"></script>
<script type="text/javascript">
    jQuery(document).ready(function ($) {
        $(".scroll").click(function (event) {
            event.preventDefault();
            $('html,body').animate({scrollTop: $(this.hash).offset().top}, 1000);
        });
    });
</script>
<!-- here stars scrolling icon -->
<script type="text/javascript">
    $(document).ready(function () {
        /*
         var defaults = {
         containerID: 'toTop', // fading element id
         containerHoverID: 'toTopHover', // fading element hover id
         scrollSpeed: 1200,
         easingType: 'linear'
         };
         */

        $().UItoTop({easingType: 'easeOutQuart'});

    });
</script>
<!-- //here ends scrolling icon -->
<!-- start-smoth-scrolling -->

<!-- Baneer-js -->
<script src="js/responsiveslides.min.js"></script>
<script>
    $(function () {
        $("#slider").responsiveSlides({
            auto: true,
            pager: false,
            nav: true,
            speed: 1000,
            namespace: "callbacks",
            before: function () {
                $('.events').append("<li>before event fired.</li>");
            },
            after: function () {
                $('.events').append("<li>after event fired.</li>");
            }
        });
    });
</script>
<!-- //Baneer-js -->
<!-- js for Counter -->
<script type="text/javascript" src="js/numscroller-1.0.js"></script>
<!-- /js for Counter -->
<!-- carousal -->
<script src="js/slick.js" type="text/javascript" charset="utf-8"></script>
<script type="text/javascript">
    $(document).on('ready', function () {
        $(".center").slick({
            dots: true,
            infinite: true,
            centerMode: true,
            slidesToShow: 2,
            slidesToScroll: 2,
            responsive: [
                {
                    breakpoint: 768,
                    settings: {
                        arrows: true,
                        centerMode: false,
                        slidesToShow: 2
                    }
                },
                {
                    breakpoint: 480,
                    settings: {
                        arrows: true,
                        centerMode: false,
                        centerPadding: '40px',
                        slidesToShow: 1
                    }
                }
            ]
        });
    });
</script>
<!-- //carousal -->

<!-- //js-scripts -->
</body>
</html>
