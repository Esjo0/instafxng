<?php
require_once '../init/initialize_general.php';
$thisPage = "";

if(!isset($_GET['u'])) {
    redirect_to('index.php');
} else {
    $get_params = allowed_get_params(['u']);
    $client_email_encrypted = $get_params['u'];
    $client_email = decrypt(str_replace(" ", "+", $client_email_encrypted));
    $client_email = preg_replace("/[^A-Za-z0-9@. ]/", '', $client_email);
}

if (isset($_POST['submit'])) {
    foreach($_POST as $key => $value) {
        $_POST[$key] = $db_handle->sanitizePost(trim($value));
    }

    extract($_POST);

    if (!check_email($email_add)) {
        $message_error = "You have supplied an invalid email address, please try again.";
    } else {
        $query = "UPDATE free_training_campaign SET training_centre = '$venue', training_interest = '2' WHERE email = '$email_add' LIMIT 1";
        $result = $db_handle->runQuery($query);

        if($result) {
            $url = "https://instafxng.com/forex-income/thank_you.php";
            redirect_to($url);
            exit;
        } else {
            $message_error = "An error occurred, please try again.";
        }
    }
}

?>
<!DOCTYPE html>
<html lang="en">
    <head>
        <base target="_self">
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>Free Forex Training | Instaforex Nigeria</title>
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
                            Instafxng Free Forex Education Programme
                        </h2>
                        <hr />
                        <p class="text-center"><img src="images/free-training-banner-7.jpg" alt="" class="center-block img-responsive" /></p>
                        <p>
                            Adequate training/education on forex trading is 
                            the first step to making steady income trading Forex. It is the key to 
                            financial stability through Forex because it helps you gain the 
                            understanding of the basics and variables of the market. 
                        </p>
                        <p>
                            Who goes into a business without knowing the basics? He who fails to get 
                            adequate knowledge plans to become poor! Our Free Forex training programme 
                            will introduce you to practical ways of trading the Forex market. 
                        </p>
                        <p>You will learn the following;</p>
                        <ul>
                            <li>Introduction to online trading</li>
                            <li>The Forex Market</li>
                            <li>The Trading Platform</li>
                            <li>And lots more ...</li>
                        </ul>
                        <h5 class="color-blue text-center">And here is the amazing part ...</h5>
                        <p>
                            Last month, 25 traders registered to be trained on the Forex Freedom Course 
                            and they paid $300 equivalent to &#8358;147,000 naira each, right now we are
                            offering it to you on a platter of gold – <span>For Free!</span> You also get 
                            a double of whatever you fund your account with to encourage you start actual 
                            Forex trading. E.g. If you fund $50, your account will be funded with $100.
                        </p>
                        <p>
                            If you choose to fund your account with $150 and above you get a trend Detector 
                            that provides signals that are 70% accurate for trading and you are already on 
                            your way to trading profitably.
                        </p>
                        
                        <p class="color-red"><strong>Training Schedule</strong></p>
                        <p>
                            <strong>Venue 1: </strong>Block 1A, Plot 8, Diamond Estate, LASU/Isheri road, Isheri-Olofin, Lagos<br/>
                            <strong>Date: </strong>Monday 3rd - Wednesday 5th April, 2017.<br/>
                            <strong>Time: </strong>10am - 3pm Daily <br/>
                        </p>

                        <p>
                            <strong>Venue 2: </strong>Block A3, Suite 508/509 Eastline Shopping Complex, Opposite Abraham Adesanya Roundabout, along Lekki - Epe expressway, Lagos.<br/>
                            <strong>Date: </strong>Tuesday 4th - Thursday 6th April, 2017.<br/>
                            <strong>Time: </strong>10am - 3pm Daily <br/>
                        </p>

                        <br />
                        <div class="section-tint-blue super-shadow">
                            <h5 class="text-center">Remember, we would be with you every step of the way</h5>
                            <p>Isn't that super cool?</p>
                            <p>
                                P.S. We have only 50 available spaces and they will be 
                                filled first come first served. Our spots are filing fast, be sure to save yours now.
                            </p>
                            <div style="max-width: 450px; margin: 0 auto;">
                                <form data-toggle="validator" class="form-horizontal" role="form" method="post" action="<?php echo $_SERVER['REQUEST_URI']; ?>">
                                    <input type="hidden" name="email_add" value="<?php echo $client_email; ?>" />
                                    <div class="form-group">
                                        <label for="venue" class="control-label"><p><strong>Choose your venue</strong></p></label>
                                        <div class="radio">
                                            <label><input type="radio" name="venue" value="1" required><p>Block 1A, Plot 8, Diamond Estate, LASU/Isheri road, Isheri Olofin, Lagos.</p></label>
                                            <label><input type="radio" name="venue" value="2" required><p>Block A3, Suite 508/509 Eastline Shopping Complex, Opposite Abraham Adesanya Roundabout, along Lekki - Epe expressway, Lagos.</p></label>
                                        </div>
                                        <span class="glyphicon form-control-feedback" aria-hidden="true"></span>
                                    </div>
                                    <div class="form-group">
                                        <div class="col-sm-12 text-center"><input name="submit" type="submit" class="btn btn-primary btn-lg" value="Enrol Me For The Free Training!" /></div>
                                    </div>
                                </form>
                            </div>
                        </div>
                        <br />
                        
                        <p class="text-center color-red"><strong>Inquiries? Call 08182045184 or 07081036115</strong></p>
                        
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