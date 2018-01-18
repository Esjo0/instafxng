<?php
require_once '../init/initialize_general.php';
$thisPage = "";

// This section processes - views/live_account_ilpr_reg.php
if(isset($_POST['live_account_ilpr_reg'])) {
    $page_requested = "live_account_ilpr_reg_php";

    $secret = '6LcKDhATAAAAALn9hfB0-Mut5qacyOxxMNOH6tov';
    $verifyResponse = file_get_contents('https://www.google.com/recaptcha/api/siteverify?secret='.$secret.'&response='.$_POST['g-recaptcha-response']);
    $responseData = json_decode($verifyResponse);

    $account_no = $db_handle->sanitizePost($_POST['ifx_acct_no']);
    $full_name = $db_handle->sanitizePost($_POST['full_name']);
    $email_address = $db_handle->sanitizePost($_POST['email']);
    $phone_number = $db_handle->sanitizePost($_POST['phone']);

    if(!$responseData->success) {
        $message_error = "You did not pass the robot verification, please try again.";
    } elseif(empty($full_name) || empty($email_address) || empty($phone_number) || empty($account_no)) {
        $message_error = "All fields must be filled.";
    } elseif (!check_email($email_address)) {
        $message_error = "You have provided an invalid email addresss. Please try again.";
    } else {

        $client_operation = new clientOperation();
        $log_new_ifxaccount = $client_operation->new_user($account_no, $full_name, $email_address, $phone_number, $type = 2, $my_refferer);

        if($log_new_ifxaccount) {
            $message_success = "You have successfully opened an account." ;
        } else {
            $message_error = "Something went wrong, the operation could not be completed. Please try again.";
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
                            Make Up To $4,200 and &#8358;1,000,000 Extra While You Take Your Normal Forex Trades
                        </h2><br />
                        <h5 class="text-center color-black">If you don't get this much from your broker,
                            it's time to switch</h5>
                        <hr />

                        <?php require_once '../layouts/feedback_message.php'; ?>

                        <p><img src="images/point-based-rewards.jpg" alt="" class="img-responsive center-block" /></p>
                        <p>How do you feel anytime you make money from the Forex market? Very excited right?
                            Sometimes you go dancing or jubilating around as you write out a plan on what you intend
                            to do with the profit. Sometimes, you are overexcited when the profit is so massive,
                            you never expected it?</p>

                        <p>Did you know that every time you make profit, you can get even more money?</p>

                        <p class="text-center"><strong>Now, let’s take a look at the not-so-profitable days...</strong></p>

                        <p>What happens on days that the market goes against you and your funds get lost amidst
                            the volatility of the market? Nothing! No hope of getting some of your funds back, it’s
                            all gone! You are probably depressed and you don't ever want to trade Forex again...</p>

                        <p>What if I told that you can win always? That when you win, you win more and on days that
                            losses happen, you still win? Yes, it's true! With the new <em>Instafxng Loyalty Points
                            Programme</em>, YOU ALWAYS WIN!</p>

                        <p>The Instafxng Loyalty Points Promo allows you to make more money whether you win or
                            lose in the Forex market making you a winner at all times!</p>

                        <p>The most thrilling thing is how much money you can make without doing anything extra
                            other than just trading. Yea, you heard me right, 'Just Trade!' This unified reward
                            program is set to reward you daily, monthly and annually, are you getting excited, yet?</p>

                        <p>Several tens of thousands of dollars will be paid out to you in rewards and redemptions
                            during the one year duration of this promo.</p>

                        <p class="text-center"><strong>Now, how does this work?</strong></p>

                        <p>Just open an account with InstaForex and start trading; each time you make a deposit to your
                            account or trade as usual you earn some point’s equivalent to your deposit or trading actions.</p>

                        <p>Our proprietary reward technology automatically calculates the points you have earned and
                            your reward account is updated accordingly.</p>

                        <p class="text-center"><strong><a class="btn btn-lg btn-success" role="button" href="forex-income/rewards.php#acct_guide">Open an InstaForex account now</a></strong></p>

                        <h4 class="text-center">When can I redeem points?</h4>
                        <ol>
                            <li>You can only redeem your points to credit your instaforex account from 100 points and above.</li>
                            <li>If you have a minimum of 100 points you will be presented with the option of redeeming some of
                                your points for InstaForex credit to your account. For example if you are initiating a deposit
                                transaction of $100 to your InstaForex account and you have 1000 reward points, you will be able
                                to redeem up to 1000 points for another $100 hence your InstaForex account will be credited with
                                a total of $200. Every client is eligible for this as long as you have earned the minimum 100
                                reward points.</li>
                            <li>Points can only be redeemed when depositing funds.</li>
                        </ol>

                        <h4 class="text-center">But that's not all...</h4>
                        <p>We will be rewarding $500 to five (5) clients with the highest number of loyalty points as seen from the
                            monthly ranking for the month in view. The prizes are as follows</p>
                        <ul>
                            <li>1st Prize = $150</li>
                            <li>2nd Prize = $120</li>
                            <li>3rd Prize = $100</li>
                            <li>4th Prize = $80</li>
                            <li>5th prize = $50</li>
                        </ul>
                        <p>In addition to the prizes, each winner gets a guaranteed invite to our annual lavish dinner which holds at the
                            end of every year in December.</p>

                        <h4 class="text-center">We are not done yet...</h4>
                        <p>You still have &#8358;2,250,000 up for grabs. At the end of every annual cycle, the ten (10)
                            clients with the highest number of reward points as seen from the cumulative annual
                            ranking will be awarded prizes. An annual cycle runs from December 1 to November 30 of
                            the following year. The prizes are as follows</p>
                        <ul>
                            <li>1st Prize = &#8358;1,000,000</li>
                            <li>2nd Prize = &#8358;500,000</li>
                            <li>3rd Prize = &#8358;250,000</li>
                            <li>4th Prize = &#8358;150,000</li>
                            <li>5th Prize = &#8358;100,000</li>
                            <li>6th – 10th Prize = &#8358;50,000 each</li>
                        </ul>
                        <p>Prizes will be presented to winners during our end of the year dinner in December</p>

                        <h4 class="text-center">HOW much can you earn from this rewards Program...?</h4>
                        <p>You can earn up to $4,200 and &#8358;1,000,000 every year. Below is a breakdown of how easy it is.</p>
                        <p>If your deposit and trading activities earn you an average of 2, 000 points every month making you
                            the highest point earner every single month of the year, then you will earn the following.</p>
                        <ol>
                            <li>As first prize monthly winner you will get $150 every month making a total of $1, 800 for one year.</li>
                            <li>With 2,000 points monthly for 12 months you will have 24, 000 points and you can redeem that to get $2, 400.
                                <br />$2,400 + $1,800 = $4,200
                            </li>
                            <li>As the first prize annual cumulative winner, you will get one million naira (&#8358;1,000,000).</li>
                        </ol>
                        <p>This is an awesome opportunity to make more money as you go through the year! Get set and
                            take your positions immediately and make the points start counting.</p>

                        <hr />
                        <p id="acct_guide"></p>
                        <h4 class="text-center text-danger">2 EASY STEPS TO OPEN A LIVE TRADING ACCOUNT WITH INSTAFOREX</h4>

                        <ol>
                            <li><strong>Open Live Account</strong></li>
                            <p>On the Account Opening form, fill your personal data, select your account
                                type, accept the Public Offer Agreement, then click 'Open Account' to
                                open a new live trading account with Instaforex.</p>
                            <li><strong>Enrol Your New Account into INSTAFXNG LOYALTY PROGRAM AND REWARDS (ILPR) for FREE Make Up To $4, 200 and N1, 000, 000 Extra While You Take Your
                                Normal Trades</strong></li>
                            <p>On the Application form, fill your details and click the submit button.</p>
                        </ol>
                        <p>Follow the two steps below</p>
                        <hr />

                        <h4 class="text-center">1. Open Account to Begin</h4>
                        <div class="text-center"><iframe class="embed-responsive-item" id="frame" style='width: 780px;border:0;height:auto;' src="https://secure.instaforex.com/en/partner_open_account.aspx?x=BBLR&width=580&showlogo=false&color=red&host="//instafxng.com/forex-income/rewards.php" style="padding:0; margin:0" scrolling="no" onload="var th=this; setTimeout(function() {var h=null;if (!h) if (location.hash.match(/^#h(\d+)/)) h=RegExp.$1;if (h) th.style .height=parseInt(h)+170+'px';}, 10);" ></iframe></div>
                        <script> document.getElementById('frame').src += window.location; </script>

                        <h4 class="text-center">2. ILPR Enrolment</h4>
                        <form data-toggle="validator" class="form-horizontal" role="form" method="post" action="<?php echo $_SERVER['REQUEST_URI']; ?>">
                            <div class="form-group">
                                <label class="control-label col-sm-3" for="full_name">Full Name:</label>
                                <div class="col-sm-9 col-lg-5">
                                    <input name="full_name" type="text" class="form-control" id="full_name" required>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="control-label col-sm-3" for="email">Email Address:</label>
                                <div class="col-sm-9 col-lg-5">
                                    <input name="email" type="text" class="form-control" id="email" required>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="control-label col-sm-3" for="phone">Phone Number:</label>
                                <div class="col-sm-9 col-lg-5">
                                    <input name="phone" type="text" class="form-control" id="phone" required>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="control-label col-sm-3" for="ifx_acct_no">Instaforex Account Number:</label>
                                <div class="col-sm-9 col-lg-5">
                                    <input name="ifx_acct_no" type="text" class="form-control" id="ifx_acct_no" required>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="control-label col-sm-3" for="recaptcha">&nbsp;</label>
                                <div class="col-sm-9 col-lg-5 g-recaptcha" data-sitekey="6LcKDhATAAAAAF3bt-hC_fWA2F0YKKpNCPFoz2Jm"></div>
                            </div>
                            <div class="form-group">
                                <div class="col-sm-offset-3 col-sm-9">
                                    <input id="ILPRenrolment" name="live_account_ilpr_reg" type="submit" class="btn btn-success" value="Submit" />

                                    <script type="text/javascript">
                                        $( '#ILPRenrolment' ).click(function() {
                                            fbq('track', 'CompleteRegistration');
                                        });
                                    </script>

                                </div>
                            </div>
                        </form>

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
        <script src='https://www.google.com/recaptcha/api.js'></script>
    </body>
</html>