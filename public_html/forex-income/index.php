<?php
require_once '../init/initialize_general.php';
$thisPage = "";

if (isset($_POST['submit'])) {
    foreach($_POST as $key => $value) {
        $_POST[$key] = $db_handle->sanitizePost(trim($value));
    }

    extract($_POST);

    if(empty($full_name) || empty($email_address) || empty($phone_number)) {
        $message_error = "All fields must be filled, please try again";
    } elseif (!check_email($email_address)) {
        $message_error = "You have supplied an invalid email address, please try again.";
    } else {

        $client_full_name = $full_name;
        $full_name = str_replace(".", "", $full_name);
        $full_name = ucwords(strtolower(trim($full_name)));
        $full_name = explode(" ", $full_name);

        if(count($full_name) == 3) {
            $last_name = trim($full_name[0]);

            if(strlen($full_name[2]) < 3) {
                $middle_name = trim($full_name[2]);
                $first_name = trim($full_name[1]);
            } else {
                $middle_name = trim($full_name[1]);
                $first_name = trim($full_name[2]);
            }

        } else {
            $last_name = trim($full_name[0]);
            $middle_name = "";
            $first_name = trim($full_name[1]);
        }

        if(empty($first_name)) {
            $first_name = $last_name;
            $last_name = "";
        }

        $query = "INSERT INTO free_training_campaign (first_name, last_name, email, phone) VALUE ('$first_name', '$last_name', '$email_address', '$phone_number')";
        $result = $db_handle->runQuery($query);
        $inserted_id = $db_handle->insertedId();

        if($result) {

            $assigned_account_officer = $system_object->next_account_officer();

            $query = "UPDATE free_training_campaign SET attendant = $assigned_account_officer WHERE free_training_campaign_id = $inserted_id LIMIT 1";
            $db_handle->runQuery($query);

            // create profile for this client
            $client_operation = new clientOperation();
            $log_new_client = $client_operation->new_user_ordinary($client_full_name, $email_address, $phone_number, $assigned_account_officer);
            //...//

            $user_code = $client_operation->get_user_by_email($email_address);
            $user_ifx_details = $client_operation->get_user_by_code($user_code['user_code']);
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

            redirect_to('thank_you.php');
        } else {
            $message_error = "An error occurred, please try again.";
        }
    }
}

$all_states = $system_object->get_all_states();

?>
<!DOCTYPE html>
<html lang="en">

  <head>

    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">

    <title>Create Multiple Streams of Income | Instaforex Nigeria</title>

    <!-- Bootstrap core CSS -->
    <link href="vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet">

    <!-- Custom fonts for this template -->
    <link rel="stylesheet" href="vendor/font-awesome/css/font-awesome.min.css">
    <link rel="stylesheet" href="vendor/simple-line-icons/css/simple-line-icons.css">
    <link href="https://fonts.googleapis.com/css?family=Lato" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css?family=Catamaran:100,200,300,400,500,600,700,800,900" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css?family=Muli" rel="stylesheet">

    <!-- Plugin CSS -->
    <link rel="stylesheet" href="device-mockups/device-mockups.min.css">

    <!-- Custom styles for this template -->
    <link href="css/new-age.min.css" rel="stylesheet">

  </head>

  <body id="page-top">

    <!-- Navigation -->
    <nav class="navbar navbar-expand-lg navbar-light fixed-top" id="mainNav">
      <div class="container" >
        <a class="navbar-brand js-scroll-trigger" href="#page-top">
            <img class="img-responsive" src="img/ifxlogo.png"></a>
        <button class="navbar-toggler navbar-toggler-right" type="button" data-toggle="collapse" data-target="#navbarResponsive" aria-controls="navbarResponsive" aria-expanded="false" aria-label="Toggle navigation">
          Menu
          <i class="fa fa-bars"></i>
        </button>
        <div class="collapse navbar-collapse" id="navbarResponsive">
          <ul class="navbar-nav ml-auto">
            <!--<li class="nav-item">
              <a class="nav-link js-scroll-trigger" href="#download">Download</a>
            </li>
            <li class="nav-item">
              <a class="nav-link js-scroll-trigger" href="#features">Features</a>
            </li>-->
            <li class="nav-item">
              <a class="nav-link js-scroll-trigger" href="javascript:void(0);" onclick="document.getElementById('full_name').focus();" style="font-size: large">Get Started Now</a>
            </li>
          </ul>
        </div>
      </div>
    </nav>

    <header class="masthead" style="background:url(img/FXDINNER-223.jpg) center no-repeat !important; ">
      <div class="container h-100" >
        <div class="row h-100" >
          <div class="col-lg-9 my-auto"  style="background: rgba(0, 0, 0, 0.42); padding: 5%">
            <div class="header-content mx-auto">
              <h1 class="mb-5"><b>ITS POSSIBLE FOR YOU...</b></h1>
                <p class="text-justify">
                    <i class="icon-check"></i>
                        <b>To have a consistent inflow of cash.</b>
                    </p>
                <p class="text-justify">
                    <i class="icon-check"></i>
                    <b>Be your own boss and spend more time with the people you love.</b>
                    </p>
                <p class="text-justify">
                    <i class="icon-check"></i>
                    <b>Live life on your own terms.</b>
                    </p>

              <!--<p class="text-justify">Imagine what it feels like to have a consistent inflow of cash, travel around the world and live life on your own terms... </p>-->
                <a href="javascript:void(0);" onclick="document.getElementById('full_name').focus();" class="btn btn-outline btn-xl js-scroll-trigger">Get Started Now!</a>
            </div>
          </div>
          <div class="col-lg-3 my-auto">
            <div class="device-container">
              <div class="device-mockup iphone6_plus portrait white">
                <div class="device">
                  <div class="screen">
                    <!-- Demo image for screen mockup, you can put an image here, some HTML, an animation, video, or anything else! -->
                    <!--<img src="img/FXDINNER-223.jpg"  class="img-fluid" alt="">-->
                  </div>
                  <div class="button">
                    <!-- You can hook the "home button" to some JavaScript events or just remove it -->
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </header>

    <section>
        <div class="container">
            <div class="row align-items-center">
                <div class="col-lg-6 order-lg-2">
                    <div class="p-5">
                        <img class="img-fluid img-thumbnail" src="img/new-stock/sad-man-2.jpeg" alt="">
                    </div>
                </div>
                <div class="col-lg-6 order-lg-1">
                    <div class="p-5">
                        <h2 class="display-4">The beginning...</h2>
                        <p class="text-justify">I can never forget 2017 as it brought an abrupt change for me financially.</p>
                        <p class="text-justify">I entered into the year full of hopes, hope that I would get my money that was
                            stuck in the famous Ponzi Scheme back, hope that cost of living will go back to
                            being normal, hope upon hope...</p>
                        <p class="text-justify">I didn’t have the least inkling that my hope will be dashed and I will have
                            to help myself as it appears that nothing was going back to normal,
                            the happenings in the country have come to stay and there was no way
                            out of the situation.</p>
                        <p class="text-justify">The truth is, I was stuck. Stuck with my 9-5 job that could no longer
                            provide me with the basic amenities I need to keep my family going.</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section>
        <div class="container">
            <div class="row align-items-center">
                <div class="col-lg-6">
                    <div class="p-5">
                        <img class="img-fluid img-thumbnail" src="img/new-stock/sad-man-3.jpeg" alt="">
                    </div>
                </div>
                <div class="col-lg-6">
                    <div class="p-5">
                        <h2 class="display-4">The search...</h2>
                        <p class="text-justify">Three years ago, I wouldn’t have been as worried and anxious as I was and the reason for the
                            way I felt wasn’t farfetched.</p>
                        <p class="text-justify">Three years ago, my job was the only thing I needed to live the life of my dreams. It brought
                            me fulfillment and my attractive salary was more than enough to live on and to even extend
                            a hand of help to the needy.</p>
                        <p class="text-justify">But the situation in the country has changed, my company hit rock bottom and salaries were cut,
                            the cost of living had skyrocketed, (I could barely feed my family on my salary in fact,
                            our three square meal had turned to two unsure meals.</p>
                        <p class="text-justify">My only choice was to find how I could make money online, as I went on with my day to day activities.
                            I needed something that I could do without having to quit my job (I needed all the money I could make).</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section>
        <div class="container">
            <div class="row align-items-center">
                <div class="col-lg-6 order-lg-1">
                    <div class="p-5">
                        <h2 class="display-4">The break...</h2>
                        <p>I first heard of buying and selling of currencies (Forex) in 2016, how it was profitable and
                            how anyone could make oil money by trading, at the other side I heard it was too volatile a
                            business and the risk involved was great. “Which business has no risk?”</p>
                        <p>While I made inquiries on trading Forex, I learnt why people fail in Forex</p>
                        <ul>
                            <li>People run into forex thinking it is a get rich quick Scheme</li>
                            <li>People start trading without adequate Training (Information)</li>
                            <li>People gamble</li>
                        </ul>
                    </div>
                </div>
                <div class="col-lg-6 order-lg-2">
                    <div class="p-5">
                        <img class="img-fluid" src="img/new-stock/10-broken-chain-png-image.png" alt="">
                    </div>
                </div>

            </div>
        </div>
    </section>

    <!--<section class="cta" style="background-image:url(img/free-training-banner-4.jpg) !important; padding:0px 0!important;">
        <div class="cta-content">
            <div class="container">
                <div class="row">
                    <div class="col-lg-6 " style="background-color: rgb(6, 6, 3); opacity: 0.9">
                        <h2 class="text-center">The Gold Mine ...</h2>
                        <div style="font-size: large; color: #ffffff" class="text-justify">
                            <p>Forex is as every other Business, you need to gain adequate information and follow these information so as to make profit.</p>
                            <p>This is what I did...</p>
                            <p>I started to trade FOREX in the month of October and it has been the best channel for an extra income because while sleeping or working, I get to make money.</p>
                            <p>By December, I had made a total of &dollar;5,000 (&#8358;1,825,000) in profit from my trades and I even won a whooping sum of One million Naira in the InstaFxNg Loyalty Rewards Program.</p>
                            <p>Today the story has changed, I am not the man I used to be since I started trading Forex, yes, things are still expensive, the Naira value still fluctuates but I am able to win over the situation since I now make more money trading Forex.</p>
                        </div>
                    </div>
                    <div class="col-lg-6">
                    </div>
                </div>
            </div>
        </div>
        <div class=""></div>
    </section>-->
    <section class="cta" style="background-image:url(img/new-stock/banner.jpg);">
        <div class="cta-content">
            <div class="container">
                <div class="row">
                    <div class="col-lg-6">
                        <h2>The Gold Mine ...</h2>
                        <p style="font-size: large; color: #ffffff" class="text-justify">Forex is as every other Business,
                            you need to gain adequate information and follow these information so as to make profit.</p>
                        <p style="font-size: large; color: #ffffff" class="text-justify">This is what I did...</p>
                        <p style="font-size: large; color: #ffffff" class="text-justify">I started to trade FOREX in the
                            month of October and it has been the best channel for an extra income because while sleeping
                            or working, I get to make money.</p>
                        <p style="font-size: large; color: #ffffff" class="text-justify">By December, I had made a total
                            of &dollar;5,000 (&#8358;1,825,000) in profit from my trades and I even won a whooping sum of One
                            million Naira in the InstaFxNg Loyalty Rewards Program.</p>
                        <p style="font-size: large; color: #ffffff" class="text-justify">Today the story has changed, I
                            am not the man I used to be since I started trading Forex, yes, things are still expensive,
                            the Naira value still fluctuates but I am able to win over the situation since I now make
                            more money trading Forex.</p>
                    </div>
                    <div class="col-lg-6">
                    </div>
                </div>

                <!-- -->

                <a href="javascript:void(0);" onclick="document.getElementById('full_name').focus();" class="btn btn-outline btn-xl js-scroll-trigger">Let's Get You Started <i class="icon-arrow-right"></i></a>
            </div>
        </div>
        <div class="overlay"></div>
    </section>


    <section class="features" id="features">
      <div class="container">
        <div class="section-heading text-center">
          <h2>Online Forex Trading for Financial Freedom</h2>
          <p class="text-muted">Forex trading is not what many people explain it to be.
              It is quite easy until you complicate matters by yourself;</p>
            <hr>
        </div>
        <div class="row">
          <div class="col-lg-4 my-auto">
            <div class="device-container">
              <div class="device-mockup iphone6_plus portrait white">
                <div class="device">
                  <div class="screen">
                    <img src="img/essentials_of_successful_forex_trading.jpg" class="img-fluid" alt="">
                  </div>
                </div>
              </div>
            </div>
          </div>
          <div class="col-lg-8 my-auto">
            <div class="container-fluid">
              <div class="row">
                <div class="col-lg-4">
                  <div class="feature-item">
                    <!--<i class="icon-screen-smartphone text-primary"></i>-->
                      <i class="icon-check text-primary"></i>
                    <!--<h3>Device Mockups</h3>-->
                    <p class="text-muted">Trading Forex is easy and very profitable once you have gained adequate training.</p>
                  </div>
                </div>
                <div class="col-lg-4">
                  <div class="feature-item">
                    <!--<i class="icon-camera text-primary"></i>-->
                    <!--<h3>Flexible Use</h3>-->
                      <i class="icon-check text-primary"></i>
                    <p class="text-muted">Trading Forex gets better because you get mentored daily and get trading signals and
                        analysis that increase your profit.</p>
                  </div>
                </div>
                  <div class="col-lg-4">
                      <div class="feature-item">
                          <!--<i class="icon-present text-primary"></i>-->
                          <!--<h3>Free to Use</h3>-->
                          <i class="icon-check text-primary"></i>
                          <p class="text-muted">And the best part is, you get to practice with a Demo account before you go live so
                              your risks are mitigated.</p>
                      </div>
                  </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </section>

    <section class="cta" id="form">
      <div class="cta-content">
        <div class="container">
            <div class="row">
                <div class="col-lg-6">
                    <h2>But don't be intimidated by the risks...</h2>
                    <p style="font-size: large; color: #ffffff" class="text-justify">Here is something that is true: I believe you can make a (really good) living if you know how to trade FOREX.
                        Thousands of Nigerians are making steady income and consistent living trading the Forex market and you should
                        be among them too.</p>
                </div>
                <div class="col-lg-6">
                    <form data-toggle="validator" class="form-horizontal" role="form" method="post" action="/forex-income/">
                        <div class="form-group">
                            <div class="col-sm-12"><input name="full_name" placeholder="Full Name" type="text" class="form-control" id="full_name" maxlength="120" required></div>
                        </div>
                        <div class="form-group">
                            <div class="col-sm-12"><input name="email_address" placeholder="Email Address" type="email" class="form-control" id="email" maxlength="50" required></div>
                        </div>
                        <div class="form-group">
                            <div class="col-sm-12"><input name="phone_number" placeholder="Phone Number" type="text" class="form-control" id="phone" maxlength="11" required></div>
                        </div>
                        <div class="col-sm-12">
                            <span style="color: #ffffff">*All fields are required</span>
                        </div>
                        <div class="form-group">
                            <div class="col-sm-12">
                                <input name="submit" type="submit" class="btn btn-success btn-lg form-control" value="Get Me Started Now!" />
                            </div>

                        </div>
                    </form>
                </div>
            </div>

           <!-- -->

          <a href="javascript:void(0);" onclick="document.getElementById('full_name').focus();" class="btn btn-outline btn-xl js-scroll-trigger">Let's Get You Started <i class="icon-arrow-right"></i></a>
        </div>
      </div>
      <div class="overlay"></div>
    </section>

    <!--<section class="contact bg-primary" id="contact">
      <div class="container">
        <h2>We
          <i class="fa fa-heart"></i>
          new friends!</h2>
        <ul class="list-inline list-social">
          <li class="list-inline-item social-twitter">
            <a href="#">
              <i class="fa fa-twitter"></i>
            </a>
          </li>
          <li class="list-inline-item social-facebook">
            <a href="#">
              <i class="fa fa-facebook"></i>
            </a>
          </li>
          <li class="list-inline-item social-google-plus">
            <a href="#">
              <i class="fa fa-google-plus"></i>
            </a>
          </li>
        </ul>
      </div>
    </section>-->

    <footer>
      <div class="container">
        <p>&copy; <?php echo date('Y'); ?>, All rights reserved. Instant Web-Net Technologies Limited (www.instafxng.com)</p>
        <!--<ul class="list-inline">
          <li class="list-inline-item">
            <a href="#">Privacy</a>
          </li>
          <li class="list-inline-item">
            <a href="#">Terms</a>
          </li>
          <li class="list-inline-item">
            <a href="#">FAQ</a>
          </li>
        </ul>-->
      </div>
    </footer>

    <!-- Bootstrap core JavaScript -->
    <script src="vendor/jquery/jquery.min.js"></script>
    <script src="vendor/bootstrap/js/bootstrap.bundle.min.js"></script>

    <!-- Plugin JavaScript -->
    <script src="vendor/jquery-easing/jquery.easing.min.js"></script>

    <!-- Custom scripts for this template -->
    <script src="js/new-age.min.js"></script>

  </body>

</html>
