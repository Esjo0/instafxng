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
        <base target="_self">
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>Create Multiple Streams of Income | Instaforex Nigeria</title>
        <meta name="title" content="" />
        <meta name="keywords" content="" />
        <meta name="description" content="" />
        <base href="https://instafxng.com/" />
        <meta http-equiv="Content-Language" content="en" />
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
        <meta http-equiv="Pragma" content="no-cache" />
        <meta name="robots" content="index, follow" />
        <meta name="author" content="Instant Web-Net Technologies Limited" />
        <meta name="publisher" content="Instant Web-Net Technologies Limited" />
        <meta name="copyright" content="Instant Web-Net Technologies Limited" />
        <meta name="rating" content="General" />
        <meta name="doc-rights" content="Private" />
        <meta name="doc-class" content="Living Document" />
        <link rel="stylesheet" href="forex-income/css/instafx_fi.css">
        <link rel="shortcut icon" type="image/x-icon" href="images/favicon.png">
        <link rel="stylesheet" href="//maxcdn.bootstrapcdn.com/bootstrap/3.3.5/css/bootstrap.min.css">
        <link rel="stylesheet" href="//maxcdn.bootstrapcdn.com/font-awesome/4.3.0/css/font-awesome.min.css">
        <script src="//ajax.googleapis.com/ajax/libs/jquery/2.1.1/jquery.min.js"></script>
        <script src="//maxcdn.bootstrapcdn.com/bootstrap/3.3.5/js/bootstrap.min.js"></script>
        <script src="js/ie10-viewport-bug-workaround.js"></script>
        <script src="js/validator.min.js"></script>
        <script src="js/npm.js"></script>
    </head>
    <body>
        <!-- Header Section: Logo and Live Chat  -->
        <header id="header">
            <div class="container-fluid no-gutter masthead">
                <div class="row">
                    <div id="main-logo" class="col-sm-12 col-md-5">
                        <img src="images/ifxlogo.png" alt="Instaforex Nigeria Logo" />
                    </div>
                    <div id="top-nav" class="col-sm-12 col-md-7 text-right">
                    </div>
                </div>
            </div>
            <hr />
        </header>
                
        <!-- Main Body: The is the main content area of the web site, contains a side bar  -->
        <div id="main-body" class="container-fluid">
            <div class="row no-gutter">
                
                <!-- Main Body - Content Area: This is the main content area, unique for each page  -->
                <div id="main-body-content-area" class="col-md-12">
                    
                    <!-- Unique Page Content Starts Here
                    ================================================== -->
                                       
                    <div class="section-tint super-shadow">
                        <h2 class="text-center color-red">
                            A Proven Pathway to Multiple <br />
                            Streams of Income
                        </h2><br />
                        <h5 class="text-center color-black">True Story - You can be next!</h5>
                        <hr />
                        <p class="text-center"><img src="images/free-training-banner-1.jpg" alt="" class="center-block img-responsive"></p>

                        <p>I can never forget 2017 as it brought an abrupt change for me financially.</p>
                        <p>I entered into the year full of hopes, hope that I would get my money that was stuck in the famous Ponzi Scheme back, hope that cost of living will go back to being normal, hope upon hope...</p>
                        <p>I didn’t have the least inkling that my hope will be dashed and I will have to help myself as it appears that nothing was going back to normal, the happenings in the country have come to stay and there was no way out of the situation.</p>
                        <p>The truth is, I was stuck. Stuck with my 9-5 job that could no longer provide me with the basic amenities I need to keep my family going.</p>

                        <p class="text-center"><img src="images/free-training-banner-2.jpg" alt="" class="center-block img-responsive"></p>

                        <p>Three years ago, I wouldn’t have been as worried and anxious as I was and the reason for the way I felt wasn’t farfetched.</p>
                        <p>Three years ago, my job was the only thing I needed to live the life of my dreams. It brought me fulfillment and my attractive salary was more than enough to live on and to even extend a hand of help to the needy.</p>
                        <p>But the situation in the country has changed, my company hit rock bottom and salaries were cut, the cost of living had skyrocketed, (I could barely feed my family on my salary in fact, sour three square meal had turned to two unsure meals.</p>
                        <p>My only choice was to find how I could make money online, as I went on with my day to day activities.  I needed something that I could do without having to quit my job (I needed all the money I could make).</p>

                        <p class="text-center"><img src="images/free-training-banner-3.jpg" alt="" class="center-block img-responsive"></p>

                        <p>I first heard of buying and selling of currencies (Forex) in 2016, how it was profitable and how anyone could make oil money by trading, at the other side I heard it was too volatile a business and the risk involved was great. “Which business has no risk?”</p>
                        <p>While I made inquiries on trading Forex, I learnt why people fail in Forex</p>

                        <ul>
                            <li>People run into forex thinking it is a get rich quick Scheme</li>
                            <li>People start trading without adequate Training (Information)</li>
                            <li>People gamble</li>
                        </ul>

                        <h5 class="text-center">I Found the Gold Mine ...</h5>
                        <p class="text-center"><img src="images/free-training-banner-4.jpg" alt="" class="center-block img-responsive" /></p>

                        <p>Forex is as every other Business, you need to gain adequate information and follow these information so as to make profit.</p>
                        <p>This is what I did...</p>

                        <p>I started to trade FOREX in the month of October and it has been the best channel for an extra income because while sleeping or working, I get to make money.</p>
                        <p>By December, I had made a total of &dollar;5,000 (&#8358;1,825,000) in profit from my trades and I even won a whooping sum of One million Naira in the InstaFxNg Loyalty Rewards Program.</p>
                        <p>Today the story has changed, I am not the man I used to be since I started trading Forex, yes, things are still expensive, the Naira value still fluctuates but I am able to win over the situation since I now make more money trading Forex.</p>

                        <hr />
                        <p class="color-red text-center">
                            <strong>Would you also like to increase your cash flow and achieve a greater 
                                quality of life for yourself and your loved ones? You are on the right page!</strong>
                        </p>
                        
                        
                        <div class="section-tint-blue super-shadow">
                            <h5 class="text-center">Online Forex Trading for Financial Freedom</h5>
                            <p class="text-center"><img src="images/free-training-banner-5.jpg" alt="" class="center-block img-responsive"></p>
                            <p>
                                Forex trading is not what many people explain it to be. 
                                It is quite easy until you complicate matters by yourself;
                            </p>
                            <ol>
                                <li>Trading Forex is easy and very profitable once you have gained adequate training.</li>
                                <li>Trading Forex gets better because you get mentored daily and get trading signals and analysis that increase your profit.</li>
                                <li>And the best part is, you get to practice with a Demo account before you go live so your risks are mitigated.</li>
                            </ol>
                        </div>
                        <br />
                        <h5 class="text-center">But don't be intimidated by the risks...</h5>
                        <p>
                            Here is something that is true: I believe you can make a (really good) 
                            living if you know how to trade FOREX. Thousands of
                            Nigerians are making steady income and consistent living trading the 
                            Forex market and you should be among them too.
                        </p>
                        <p class="color-blue text-center">
                            <strong>It is time to make one of the most important decisions this year,
                                let me show you how. Kindly fill the form below to get started...</strong>
                        </p>
                        
                        
                        <div class="row">
                            <div class="col-md-4">
                                <p class="text-center"><img src="images/free-training-banner-6.jpg" alt="" class="center-block img-responsive"></p>
                            </div>
                            <div class="col-md-8">

                                <div style="max-width: 850px; margin: 0 auto; ">
                                    <form data-toggle="validator" class="form-horizontal" role="form" method="post" action="<?php echo $_SERVER['REQUEST_URI']; ?>">
                                        <div class="form-group">
                                            <label class="control-label col-sm-3" for="full_name">Full Name:</label>
                                            <div class="col-sm-9 col-lg-7"><input name="full_name" type="text" class="form-control" id="full_name" maxlength="120" required></div>
                                        </div>
                                        <div class="form-group">
                                            <label class="control-label col-sm-3" for="email">Email Address:</label>
                                            <div class="col-sm-9 col-lg-7"><input name="email_address" type="email" class="form-control" id="email" maxlength="50" required></div>
                                        </div>
                                        <div class="form-group">
                                            <label class="control-label col-sm-3" for="phone">Phone Number:</label>
                                            <div class="col-sm-9 col-lg-7"><input name="phone_number" type="text" class="form-control" id="phone" maxlength="11" required></div>
                                        </div>

                                        <div class="form-group">
                                            <div class="col-sm-offset-3 col-sm-9">
                                                <input name="submit" type="submit" class="btn btn-success btn-lg" value="Get Me Started Now!" />
                                            </div>
                                            <div class="col-sm-offset-3 col-sm-9">
                                                <br />
                                                <span>*All fields are required</span>
                                            </div>
                                        </div>
                                    </form>
                                    
                                </div>
                            </div>
                        </div>
                                
                    </div>

                    <!-- Unique Page Content Ends Here
                    ================================================== -->
                </div>
            </div>
            
            
        </div>
        <footer id="footer" class="super-shadow">
            <div class="container-fluid no-gutter">
                <div class="col-sm-12">
                    <div class="row">
                        <p class="text-center" style="font-size: 16px !important;">&copy; <?php echo date('Y'); ?>, All rights reserved. Instant Web-Net Technologies Limited (www.instafxng.com)</p>
                    </div>
                </div>
            </div>
        </footer>
    </body>
</html>