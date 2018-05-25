<?php

class Loyalty_Training
{
    public function is_duplicate_training($email_address, $phone_number)
    {
        global $db_handle;
        $query = "SELECT * FROM campaign_leads WHERE phone = '$phone_number' OR email = '$email_address' ";
        $result = $db_handle->runQuery($query);
        if ($db_handle->numOfRows($result) <= 0) {return true;}
        else{return false;}
    }

    public function add_training($first_name, $last_name, $email_address, $phone_number, $source, $state_id = '')
    {
        global $db_handle;
        global $system_object;
        $query = "INSERT INTO campaign_leads (f_name, l_name, email, phone, source) VALUE ('$first_name', '$last_name', '$email_address', '$phone_number', '$source')";
        $result = $db_handle->runQuery($query);
        $inserted_id = $db_handle->insertedId();
        if ($result)
        {
            $text_message = "Welcome on board! The key to trading Forex profitably is Knowledge, We are so excited you have chosen us to guide you through the path of making money from the Forex market, Click bit.ly/2iExTpN to begin your free training.";
            $system_object->send_sms($phone_number, $text_message);

            $subject = "Welcome to InstaFxNg $first_name";
            $message = <<<MAIL
    <div style="background-color: #F3F1F2">
    <div style="max-width: 80%; margin: 0 auto; padding: 10px; font-size: 14px; font-family: Verdana;">
        <img src="https://instafxng.com/images/ifxlogo.png" />
        <hr />
        <div style="background-color: #FFFFFF; padding: 15px; margin: 5px 0 5px 0;">
            <p>Hello $first_name,</p>
            <p>Welcome on board!</p>
            <p>I would like to take this opportunity to let you know how pleased and excited I am that you have chosen to trade with InstaForex Nigeria (www.InstaFxNg.com).</p>
            <p>You have joined over 14,000 Nigerians who make consistent income from the Forex market using the InstaForex platform and also earn more money just for trading.</p>
            <p>To start your journey to earning more money from Forex trading, you need to gain adequate knowledge of the Forex market.</p>
           
            <p>We have a Free online training that you can take advantage of to learn more about how to trade Forex profitably.</p>
            <p>The training is simple and easy to follow and you can take it from your house, office or even on your bed.</p>
            
            <p>Within the next hour, you will be placing informed trades and increase your chances of taking your slice of the 5.3 Billion Dollars from the Forex market.</p>
            <p>Guess what! It is free (at least for now).</p>
            <p><a href="https://instafxng.com/fxacademy/">Click Here to Start the Training Now.</a></p>
            <p>$first_name, we are taking in just 50 people at this time, for the brand new Forex Money Maker course.</p>
            <p>35 spots have been taken already and we have 15 left. 
            The slots are filling up very fast. 
            <a href="https://instafxng.com/fxacademy/">Go here now to start the free training.</a></p>
            <p>Please don’t miss this. Go ahead and login to the training immediately to secure your spot.</p>
            <p>This will be your best shot at generating a healthy side income from forex trading. Go ahead and make the move now.</p>
            <p>Start the Forex Money Maker Course now so you can launch your Forex trading entry with a big bang.</p>
            <p>See you on the other side. Secure your spot now. Only 15 spots are up for grabs. Don’t wait till it will be too late.</p>
            <p><a href="https://instafxng.com/fxacademy/">Here is the link to the online training again.</a></p>
            <br/><br/>
            <p>Best Regards,</p>
            <p>Mercy,</p>
            <p>Client Relations Manager,</p>
            <p>InstaForex Nigeria</p>
            <p>www.instafxng.com</p>
            <br /><br />
        </div>
        <hr />
        <div style="background-color: #EBDEE9;">
            <div style="font-size: 11px !important; padding: 15px;">
                <p style="text-align: center"><span style="font-size: 12px"><strong>We"re Social</strong></span><br /><br />
                    <a href="https://facebook.com/InstaForexNigeria"><img src="https://instafxng.com/images/Facebook.png"></a>
                    <a href="https://twitter.com/instafxng"><img src="https://instafxng.com/images/Twitter.png"></a>
                    <a href="https://www.instagram.com/instafxng/"><img src="https://instafxng.com/images/instagram.png"></a>
                    <a href="https://www.youtube.com/channel/UC0Z9AISy_aMMa3OJjgX6SXw"><img src="https://instafxng.com/images/Youtube.png"></a>
                    <a href="https://linkedin.com/company/instaforex-ng"><img src="https://instafxng.com/images/LinkedIn.png"></a>
                </p>
                <p><strong>Head Office Address:</strong> TBS Place, Block 1A, Plot 8, Diamond Estate, Estate Bus-Stop, LASU/Isheri road, Isheri Olofin, Lagos.</p>
                <p><strong>Lekki Office Address:</strong> Block A3, Suite 508/509 Eastline Shopping Complex, Opposite Abraham Adesanya Roundabout, along Lekki - Epe expressway, Lagos. </p>
                <p><strong>Office Number:</strong> 08028281192</p>
                <br />
            </div>
            <div style="font-size: 10px !important; padding: 15px; text-align: center;">
                <p>This email was sent to you by Instant Web-Net Technologies Limited, the
                    official Nigerian Representative of Instaforex, operator and administrator
                    of the website www.instafxng.com</p>
                <p>To ensure you continue to receive special offers and updates from us,
                    please add support@instafxng.com to your address book.</p>
            </div>
        </div>
    </div>
</div>
MAIL;
            $system_object->send_email($subject, $message, $email_address, $first_name);

            $assigned_account_officer = $system_object->next_account_officer();
            //$query = "UPDATE free_training_campaign SET attendant = $assigned_account_officer WHERE free_training_campaign_id = $inserted_id LIMIT 1";
            //$db_handle->runQuery($query);
            // create profile for this client
            $client_operation = new clientOperation();
            $client_operation->new_user_ordinary($first_name." ".$last_name, $email_address, $phone_number, $assigned_account_officer);
            return true;
        } else {return false;}
    }

    public function is_duplicate_loyalty($email_address, $phone_number)
    {
        global $db_handle;
        $query = "SELECT * FROM camapign_leads WHERE phone = '$phone_number' OR email = '$email_address' ";
        $result = $db_handle->runQuery($query);
        if ($db_handle->numOfRows($result) <= 0 ) {return true;}
        else{return false;}
    }

    public function add_lead($first_name, $last_name, $email_address, $phone_number, $source, $interest, $created, $state_id = '')
    {
        global $db_handle;
        global $system_object;
        $query = "INSERT INTO campaign_leads (f_name, l_name, email, phone, source, interest, created) VALUE ('$first_name', '$last_name', '$email_address', '$phone_number', '$source', '$interest', '$created')";
        $result = $db_handle->runQuery($query);
        switch ($interest)
        {
            case '1':
                $text_message = "Welcome on board! The key to trading Forex profitably is Knowledge, We are so excited you have chosen us to guide you through the path of making money from the Forex market, Click bit.ly/2iExTpN to begin your free training.";
                $subject = "Welcome to InstaFxNg $first_name";
                $message = <<<MAIL
    <div style="background-color: #F3F1F2">
    <div style="max-width: 80%; margin: 0 auto; padding: 10px; font-size: 14px; font-family: Verdana;">
        <img src="https://instafxng.com/images/ifxlogo.png" />
        <hr />
        <div style="background-color: #FFFFFF; padding: 15px; margin: 5px 0 5px 0;">
            <p>Hello $first_name,</p>
            <p>Welcome on board!</p>
            <p>I would like to take this opportunity to let you know how pleased and excited I am that you have chosen to trade with InstaForex Nigeria (www.InstaFxNg.com).</p>
            <p>You have joined over 14,000 Nigerians who make consistent income from the Forex market using the InstaForex platform and also earn more money just for trading.</p>
            <p>To start your journey to earning more money from Forex trading, you need to gain adequate knowledge of the Forex market.</p>
           
            <p>We have a Free online training that you can take advantage of to learn more about how to trade Forex profitably.</p>
            <p>The training is simple and easy to follow and you can take it from your house, office or even on your bed.</p>
            
            <p>Within the next hour, you will be placing informed trades and increase your chances of taking your slice of the 5.3 Billion Dollars from the Forex market.</p>
            <p>Guess what! It is free (at least for now).</p>
            <p><a href="https://instafxng.com/fxacademy/">Click Here to Start the Training Now.</a></p>
            <p>$first_name, we are taking in just 50 people at this time, for the brand new Forex Money Maker course.</p>
            <p>35 spots have been taken already and we have 15 left. 
            The slots are filling up very fast. 
            <a href="https://instafxng.com/fxacademy/">Go here now to start the free training.</a></p>
            <p>Please don’t miss this. Go ahead and login to the training immediately to secure your spot.</p>
            <p>This will be your best shot at generating a healthy side income from forex trading. Go ahead and make the move now.</p>
            <p>Start the Forex Money Maker Course now so you can launch your Forex trading entry with a big bang.</p>
            <p>See you on the other side. Secure your spot now. Only 15 spots are up for grabs. Don’t wait till it will be too late.</p>
            <p><a href="https://instafxng.com/fxacademy/">Here is the link to the online training again.</a></p>
            <br/><br/>
            <p>Best Regards,</p>
            <p>Mercy,</p>
            <p>Client Relations Manager,</p>
            <p>InstaForex Nigeria</p>
            <p>www.instafxng.com</p>
            <br /><br />
        </div>
        <hr />
        <div style="background-color: #EBDEE9;">
            <div style="font-size: 11px !important; padding: 15px;">
                <p style="text-align: center"><span style="font-size: 12px"><strong>We"re Social</strong></span><br /><br />
                    <a href="https://facebook.com/InstaForexNigeria"><img src="https://instafxng.com/images/Facebook.png"></a>
                    <a href="https://twitter.com/instafxng"><img src="https://instafxng.com/images/Twitter.png"></a>
                    <a href="https://www.instagram.com/instafxng/"><img src="https://instafxng.com/images/instagram.png"></a>
                    <a href="https://www.youtube.com/channel/UC0Z9AISy_aMMa3OJjgX6SXw"><img src="https://instafxng.com/images/Youtube.png"></a>
                    <a href="https://linkedin.com/company/instaforex-ng"><img src="https://instafxng.com/images/LinkedIn.png"></a>
                </p>
                <p><strong>Head Office Address:</strong> TBS Place, Block 1A, Plot 8, Diamond Estate, Estate Bus-Stop, LASU/Isheri road, Isheri Olofin, Lagos.</p>
                <p><strong>Lekki Office Address:</strong> Block A3, Suite 508/509 Eastline Shopping Complex, Opposite Abraham Adesanya Roundabout, along Lekki - Epe expressway, Lagos. </p>
                <p><strong>Office Number:</strong> 08028281192</p>
                <br />
            </div>
            <div style="font-size: 10px !important; padding: 15px; text-align: center;">
                <p>This email was sent to you by Instant Web-Net Technologies Limited, the
                    official Nigerian Representative of Instaforex, operator and administrator
                    of the website www.instafxng.com</p>
                <p>To ensure you continue to receive special offers and updates from us,
                    please add support@instafxng.com to your address book.</p>
            </div>
        </div>
    </div>
</div>
MAIL;
                break;
            case '2':
                $text_message = "Welcome to the Money Makers Club, Thank you for choosing us to ensure your journey to wealth is smooth and rewarding. Please click bit.ly/2mpqehQ to open an account or click bit.ly/2BjGSYF to fund your account right away.";
                $subject = "Welcome to InstaFxNg $first_name";
                $message = <<<MAIL
    <div style="background-color: #F3F1F2">
    <div style="max-width: 80%; margin: 0 auto; padding: 10px; font-size: 14px; font-family: Verdana;">
        <img src="https://instafxng.com/images/ifxlogo.png" />
        <hr />
        <div style="background-color: #FFFFFF; padding: 15px; margin: 5px 0 5px 0;">
            <p>Dear $first_name,</p>
            <p>Welcome on board!</p>
            <p>I would like to take this opportunity to let you know how pleased and excited I am that you have chosen to trade with InstaForex Nigeria (www.InstaFxNg.com).</p>
            <p>You have joined over 14,000 Nigerians who make consistent income from the Forex market using the InstaForex platform and also earn more money just for trading.</p>
            <p>At InstaFxNg, we consider it a privilege to serve and provide you with excellent and unparalleled service at all times.</p>
            <p>We have been around for over 7 years providing excellent forex services to thousands of Nigerian traders, we are reliable and your funds are safe in our hands.</p>
            <p><strong>Hey! What’s Special? Why InstaFxNg? Here’s why….</strong></p>
            <p>At InstaFxNg here are some benefits you won’t find elsewhere;</p>
            <ul>
                <li><b>Instant funding of account: </b>No holding back! No Long Procedures! You can make instant withdrawals directly into your local bank account. We will process all transactions you initiate on our platform immediately. </li>
                <li><b>130% Welcome Bonus: </b>Do you know that for your first Deposit of either $50, $100 or $150 you will get a whopping 130% bonus? Oh yes you can! So what are you waiting for? </li>
                <li><b>InstaFxNg Loyalty Program: </b>Yes! You will get rewarded for trading! We constantly plan rewards for our consistent traders to ensure that you have an exciting experience! So while you are trading you will earn points which can be redeemed, 5 leading traders will be rewarded monthly and N2,250,000 is up for grabs yearly! You can be the next lucky winner if you start now! </li>
                <li><b>Excellent Customer Service: </b>In addition, there is an extra topping on the cake! Our customer service executives will handle all other matters relating to your account while you trade. We will also follow through to ensure that all inquiries or queries are resolved promptly. </li>
            </ul>
            <p><b>Are you ready to begin your journey with us?</b></p>
            <p>To start your journey to earning more money in our Loyalty Rewards Program, 
            kindly <a href="https://instafxng.com/loyalty/live_account.php">click here to open an InstaForex 
            account</a> immediately if you haven’t done that already.</p>
            <p><center><b>Open My InstaForex Account</b></center></p>
            <p>I’m so glad you are here and I can’t wait for you to start earning in the <a href="https://instafxng.com/loyalty.php">InstaFxNg Loyalty Points and Reward Program</a></p>
            <br/><br/>
            <p>Best Regards,</p>
            <p>Mercy,</p>
            <p>Client Relations Manager,</p>
            <p>InstaForex Nigeria</p>
            <p>www.instafxng.com</p>
            <br /><br />
        </div>
        <hr />
        <div style="background-color: #EBDEE9;">
            <div style="font-size: 11px !important; padding: 15px;">
                <p style="text-align: center"><span style="font-size: 12px"><strong>We"re Social</strong></span><br /><br />
                    <a href="https://facebook.com/InstaForexNigeria"><img src="https://instafxng.com/images/Facebook.png"></a>
                    <a href="https://twitter.com/instafxng"><img src="https://instafxng.com/images/Twitter.png"></a>
                    <a href="https://www.instagram.com/instafxng/"><img src="https://instafxng.com/images/instagram.png"></a>
                    <a href="https://www.youtube.com/channel/UC0Z9AISy_aMMa3OJjgX6SXw"><img src="https://instafxng.com/images/Youtube.png"></a>
                    <a href="https://linkedin.com/company/instaforex-ng"><img src="https://instafxng.com/images/LinkedIn.png"></a>
                </p>
                <p><strong>Head Office Address:</strong> TBS Place, Block 1A, Plot 8, Diamond Estate, Estate Bus-Stop, LASU/Isheri road, Isheri Olofin, Lagos.</p>
                <p><strong>Lekki Office Address:</strong> Block A3, Suite 508/509 Eastline Shopping Complex, Opposite Abraham Adesanya Roundabout, along Lekki - Epe expressway, Lagos. </p>
                <p><strong>Office Number:</strong> 08028281192</p>
                <br />
            </div>
            <div style="font-size: 10px !important; padding: 15px; text-align: center;">
                <p>This email was sent to you by Instant Web-Net Technologies Limited, the
                    official Nigerian Representative of Instaforex, operator and administrator
                    of the website www.instafxng.com</p>
                <p>To ensure you continue to receive special offers and updates from us,
                    please add support@instafxng.com to your address book.</p>
            </div>
        </div>
    </div>
</div>
MAIL;
                break;
            default:
                $text_message = "Welcome on board! The key to trading Forex profitably is Knowledge, We are so excited you have chosen us to guide you through the path of making money from the Forex market, Click bit.ly/2iExTpN to begin your free training.";
                $subject = "Welcome to InstaFxNg $first_name";
                $message = <<<MAIL
    <div style="background-color: #F3F1F2">
    <div style="max-width: 80%; margin: 0 auto; padding: 10px; font-size: 14px; font-family: Verdana;">
        <img src="https://instafxng.com/images/ifxlogo.png" />
        <hr />
        <div style="background-color: #FFFFFF; padding: 15px; margin: 5px 0 5px 0;">
            <p>Hello $first_name,</p>
            <p>Welcome on board!</p>
            <p>I would like to take this opportunity to let you know how pleased and excited I am that you have chosen to trade with InstaForex Nigeria (www.InstaFxNg.com).</p>
            <p>You have joined over 14,000 Nigerians who make consistent income from the Forex market using the InstaForex platform and also earn more money just for trading.</p>
            <p>To start your journey to earning more money from Forex trading, you need to gain adequate knowledge of the Forex market.</p>
           
            <p>We have a Free online training that you can take advantage of to learn more about how to trade Forex profitably.</p>
            <p>The training is simple and easy to follow and you can take it from your house, office or even on your bed.</p>
            
            <p>Within the next hour, you will be placing informed trades and increase your chances of taking your slice of the 5.3 Billion Dollars from the Forex market.</p>
            <p>Guess what! It is free (at least for now).</p>
            <p><a href="https://instafxng.com/fxacademy/">Click Here to Start the Training Now.</a></p>
            <p>$first_name, we are taking in just 50 people at this time, for the brand new Forex Money Maker course.</p>
            <p>35 spots have been taken already and we have 15 left. 
            The slots are filling up very fast. 
            <a href="https://instafxng.com/fxacademy/">Go here now to start the free training.</a></p>
            <p>Please don’t miss this. Go ahead and login to the training immediately to secure your spot.</p>
            <p>This will be your best shot at generating a healthy side income from forex trading. Go ahead and make the move now.</p>
            <p>Start the Forex Money Maker Course now so you can launch your Forex trading entry with a big bang.</p>
            <p>See you on the other side. Secure your spot now. Only 15 spots are up for grabs. Don’t wait till it will be too late.</p>
            <p><a href="https://instafxng.com/fxacademy/">Here is the link to the online training again.</a></p>
            <br/><br/>
            <p>Best Regards,</p>
            <p>Mercy,</p>
            <p>Client Relations Manager,</p>
            <p>InstaForex Nigeria</p>
            <p>www.instafxng.com</p>
            <br /><br />
        </div>
        <hr />
        <div style="background-color: #EBDEE9;">
            <div style="font-size: 11px !important; padding: 15px;">
                <p style="text-align: center"><span style="font-size: 12px"><strong>We"re Social</strong></span><br /><br />
                    <a href="https://facebook.com/InstaForexNigeria"><img src="https://instafxng.com/images/Facebook.png"></a>
                    <a href="https://twitter.com/instafxng"><img src="https://instafxng.com/images/Twitter.png"></a>
                    <a href="https://www.instagram.com/instafxng/"><img src="https://instafxng.com/images/instagram.png"></a>
                    <a href="https://www.youtube.com/channel/UC0Z9AISy_aMMa3OJjgX6SXw"><img src="https://instafxng.com/images/Youtube.png"></a>
                    <a href="https://linkedin.com/company/instaforex-ng"><img src="https://instafxng.com/images/LinkedIn.png"></a>
                </p>
                <p><strong>Head Office Address:</strong> TBS Place, Block 1A, Plot 8, Diamond Estate, Estate Bus-Stop, LASU/Isheri road, Isheri Olofin, Lagos.</p>
                <p><strong>Lekki Office Address:</strong> Block A3, Suite 508/509 Eastline Shopping Complex, Opposite Abraham Adesanya Roundabout, along Lekki - Epe expressway, Lagos. </p>
                <p><strong>Office Number:</strong> 08028281192</p>
                <br />
            </div>
            <div style="font-size: 10px !important; padding: 15px; text-align: center;">
                <p>This email was sent to you by Instant Web-Net Technologies Limited, the
                    official Nigerian Representative of Instaforex, operator and administrator
                    of the website www.instafxng.com</p>
                <p>To ensure you continue to receive special offers and updates from us,
                    please add support@instafxng.com to your address book.</p>
            </div>
        </div>
    </div>
</div>
MAIL;
                break;
        }
        if($result) {
            $system_object->send_sms($phone_number, $text_message);
            $system_object->send_email($subject, $message, $email_address, $first_name);
            $assigned_account_officer = $system_object->next_account_officer();
            $client_operation = new clientOperation();
            $client_operation->new_user_ordinary($first_name." ".$last_name, $email_address, $phone_number, $assigned_account_officer);
            return true;}
        else{return false;}
    }

    public function add_loyalty($first_name, $last_name, $email_address, $phone_number, $source, $state_id = '')
    {
        global $db_handle;
        global $system_object;
        $query = "INSERT INTO campaign_leads (f_name, l_name, email, phone, source) VALUE ('$first_name', '$last_name', '$email_address', '$phone_number', '$source')";
        $result = $db_handle->runQuery($query);
        if($result)
        {
            $text_message = "Welcome to the Money Makers Club, Thank you for choosing us to ensure your journey to wealth is smooth and rewarding. Please click bit.ly/2jCUdko to proceed to the next step and begin to make more money right away.";
            $system_object->send_sms($phone_number, $text_message);

            $subject = "Welcome to InstaFxNg $first_name";
            $message = <<<MAIL
    <div style="background-color: #F3F1F2">
    <div style="max-width: 80%; margin: 0 auto; padding: 10px; font-size: 14px; font-family: Verdana;">
        <img src="https://instafxng.com/images/ifxlogo.png" />
        <hr />
        <div style="background-color: #FFFFFF; padding: 15px; margin: 5px 0 5px 0;">
            <p>Dear $first_name,</p>
            <p>Welcome on board!</p>
            <p>I would like to take this opportunity to let you know how pleased and excited I am that you have chosen to trade with InstaForex Nigeria (www.InstaFxNg.com).</p>
            <p>You have joined over 14,000 Nigerians who make consistent income from the Forex market using the InstaForex platform and also earn more money just for trading.</p>
            <p>At InstaFxNg, we consider it a privilege to serve and provide you with excellent and unparalleled service at all times.</p>
            <p>We have been around for over 7 years providing excellent forex services to thousands of Nigerian traders, we are reliable and your funds are safe in our hands.</p>
            <p><strong>Hey! What’s Special? Why InstaFxNg? Here’s why….</strong></p>
            <p>At InstaFxNg here are some benefits you won’t find elsewhere;</p>
            <ul>
                <li><b>Instant funding of account: </b>No holding back! No Long Procedures! You can make instant withdrawals directly into your local bank account. We will process all transactions you initiate on our platform immediately. </li>
                <li><b>130% Welcome Bonus: </b>Do you know that for your first Deposit of either $50, $100 or $150 you will get a whopping 130% bonus? Oh yes you can! So what are you waiting for? </li>
                <li><b>InstaFxNg Loyalty Program: </b>Yes! You will get rewarded for trading! We constantly plan rewards for our consistent traders to ensure that you have an exciting experience! So while you are trading you will earn points which can be redeemed, 5 leading traders will be rewarded monthly and N2,250,000 is up for grabs yearly! You can be the next lucky winner if you start now! </li>
                <li><b>Excellent Customer Service: </b>In addition, there is an extra topping on the cake! Our customer service executives will handle all other matters relating to your account while you trade. We will also follow through to ensure that all inquiries or queries are resolved promptly. </li>
            </ul>
            <p><b>Are you ready to begin your journey with us?</b></p>
            <p>To start your journey to earning more money in our Loyalty Rewards Program, 
            kindly <a href="https://instafxng.com/loyalty/live_account.php">click here to open an InstaForex 
            account</a> immediately if you haven’t done that already.</p>
            <p><center><b>Open My InstaForex Account</b></center></p>
            <p>I’m so glad you are here and I can’t wait for you to start earning in the <a href="https://instafxng.com/loyalty.php">InstaFxNg Loyalty Points and Reward Program</a></p>
            <br/><br/>
            <p>Best Regards,</p>
            <p>Mercy,</p>
            <p>Client Relations Manager,</p>
            <p>InstaForex Nigeria</p>
            <p>www.instafxng.com</p>
            <br /><br />
        </div>
        <hr />
        <div style="background-color: #EBDEE9;">
            <div style="font-size: 11px !important; padding: 15px;">
                <p style="text-align: center"><span style="font-size: 12px"><strong>We"re Social</strong></span><br /><br />
                    <a href="https://facebook.com/InstaForexNigeria"><img src="https://instafxng.com/images/Facebook.png"></a>
                    <a href="https://twitter.com/instafxng"><img src="https://instafxng.com/images/Twitter.png"></a>
                    <a href="https://www.instagram.com/instafxng/"><img src="https://instafxng.com/images/instagram.png"></a>
                    <a href="https://www.youtube.com/channel/UC0Z9AISy_aMMa3OJjgX6SXw"><img src="https://instafxng.com/images/Youtube.png"></a>
                    <a href="https://linkedin.com/company/instaforex-ng"><img src="https://instafxng.com/images/LinkedIn.png"></a>
                </p>
                <p><strong>Head Office Address:</strong> TBS Place, Block 1A, Plot 8, Diamond Estate, Estate Bus-Stop, LASU/Isheri road, Isheri Olofin, Lagos.</p>
                <p><strong>Lekki Office Address:</strong> Block A3, Suite 508/509 Eastline Shopping Complex, Opposite Abraham Adesanya Roundabout, along Lekki - Epe expressway, Lagos. </p>
                <p><strong>Office Number:</strong> 08028281192</p>
                <br />
            </div>
            <div style="font-size: 10px !important; padding: 15px; text-align: center;">
                <p>This email was sent to you by Instant Web-Net Technologies Limited, the
                    official Nigerian Representative of Instaforex, operator and administrator
                    of the website www.instafxng.com</p>
                <p>To ensure you continue to receive special offers and updates from us,
                    please add support@instafxng.com to your address book.</p>
            </div>
        </div>
    </div>
</div>
MAIL;
            $system_object->send_email($subject, $message, $email_address, $first_name);
            return true;
        }
        else
        {return false;}
    }

    public function get_lead_type($interest)
    {
        $interest == 1 ? $x = "ILPR" : $x = "FxAcademy";
        return $x;
    }

    public function lead_interest($interest)
    {
        switch ($interest)
        {
            case "1" : $x = "FxAcademy"; break;
            case "2" : $x = "ILPR"; break;
            default: $x = "Unknown"; break;
        }
        return $x;
    }

    public function get_lead_reg_by_id($selected_id) {
        global $db_handle;

        $query = "SELECT cl.lead_id, CONCAT(cl.l_name, SPACE(1), cl.f_name) AS full_name, cl.email, cl.phone, cl.created, u.user_code, cl.l_name as last_name, cl.f_name as first_name 
                FROM campaign_leads AS cl
                LEFT JOIN user AS u ON u.email = cl.email
                WHERE lead_id = $selected_id LIMIT 1";
        $result = $db_handle->runQuery($query);
        $fetched_data = $db_handle->fetchAssoc($result);

        return $fetched_data ? $fetched_data : false;
    }

    public function update_lead_registration($selected_id, $training_email, $training_phone, $training_first_name, $training_last_name, $comment, $admin_unique_code, $state = '', $add_ifx_account = '', $client_user_code = '') {
        global $db_handle;

        if(!empty($state)) {
            $query = "UPDATE campaign_leads SET state_id = '$state' WHERE lead_id = $selected_id";
            $db_handle->runQuery($query);
        }

        if(!empty($comment)) {
            $query = "INSERT INTO campaign_lead_comments (lead_id, admin_code, comment) VALUES ($selected_id, '$admin_unique_code', '$comment')";
            $db_handle->runQuery($query);
        }

        if(!empty($client_user_code)) {
            if(!empty($add_ifx_account)) {
                $query = "INSERT INTO user_ifxaccount (user_code, ifx_acct_no) VALUES ('$client_user_code', '$add_ifx_account')";
                $db_handle->runQuery($query);
                $ifxaccount_id = $db_handle->insertedId();

                $query = "INSERT INTO user_ilpr_enrolment (ifxaccount_id) VALUES ($ifxaccount_id)";
                $db_handle->runQuery($query);
            }
        } else {
            if(!empty($add_ifx_account)) {
                usercode:
                $user_code = rand_string(11);
                if($db_handle->numRows("SELECT user_code FROM user WHERE user_code = '$user_code'") > 0) { goto usercode; };

                $pass_salt = hash("SHA256", "$user_code");

                $query = "INSERT INTO user (user_code, email, pass_salt, first_name, last_name, phone) VALUES ('$user_code', '$training_email', '$pass_salt', '$training_first_name', '$training_last_name', '$training_phone')";
                $db_handle->runQuery($query);

                $client_operation = new clientOperation();
                $client_operation->send_welcome_email($training_last_name, $training_email);

                $query = "INSERT INTO user_ifxaccount (user_code, ifx_acct_no) VALUES ('$user_code', '$add_ifx_account')";
                $db_handle->runQuery($query);
                $ifxaccount_id = $db_handle->insertedId();

                $query = "INSERT INTO user_ilpr_enrolment (ifxaccount_id) VALUES ($ifxaccount_id)";
                $db_handle->runQuery($query);

            }
        }

        return true;
    }

    public function sum_leads_generated($from, $to, $x)
    {
        global $db_handle;
        $query = "SELECT * FROM campaign_leads WHERE (STR_TO_DATE(created, '%Y-%m-%d') BETWEEN '$from' AND '$to') ";
        if($x == 1) {$result = $db_handle->numRows($query);}
        else if($x == 2){$result = $db_handle->fetchAssoc($db_handle->runQuery($query));}
        return $result;
    }

    //Done
    public function sum_leads_with_accounts($from, $to, $x)
    {
        global $db_handle;
        $query = "SELECT CL.f_name, CL.email, CL.phone, CL.l_name, U.user_code, UI.ifx_acct_no, UI.type, CL.created
                  FROM campaign_leads AS CL 
                  LEFT JOIN user AS U ON U.email = CL.email
                  LEFT JOIN user_ifxaccount AS UI ON UI.user_code = U.user_code
                  WHERE (STR_TO_DATE(CL.created, '%Y-%m-%d') BETWEEN '$from' AND '$to')
                  AND ((U.user_code IS NOT NULL) OR (UI.ifx_acct_no IS NOT NULL))
                  GROUP BY CL.email ";
        if($x == 1) {$result = $db_handle->numRows($query);}
        else if($x == 2){$result = $db_handle->fetchAssoc($db_handle->runQuery($query));}
        return $result;
    }

    //Done
    public function sum_leads_funded($from, $to, $x)
    {
        global $db_handle;
        $query = "SELECT CL.f_name, CL.phone, CL.email, CL.l_name, UI.ifx_acct_no, SUM(UD.real_dollar_equivalent) AS dollar_amount, CL.created 
FROM campaign_leads AS CL 
LEFT JOIN user AS U ON U.email = CL.email 
LEFT JOIN user_ifxaccount AS UI ON UI.user_code = U.user_code 
LEFT JOIN user_deposit AS UD ON UD.ifxaccount_id = UI.ifxaccount_id 
WHERE (STR_TO_DATE(CL.created, '%Y-%m-%d') BETWEEN '$from' AND '$to') 
AND ((U.user_code IS NOT NULL) OR (UI.ifx_acct_no IS NOT NULL)) 
AND UD.real_dollar_equivalent IS NOT NULL 
AND (STR_TO_DATE(UD.created, '%Y-%m-%d') BETWEEN '$from' AND '$to') 
AND UD.status = '8' 
GROUP BY CL.email ";
        if($x == 1) {$result = $db_handle->numRows($query);}
        else if($x == 2){$result = $db_handle->fetchAssoc($db_handle->runQuery($query));}
        return $result;
    }

    //Done
    public function sum_training_leads($from, $to, $course, $x)
    {
        global $db_handle;
        switch ($course)
        {
            //FxAcademy - Forex Optimizer Course
            case "1" :
                $query = "SELECT CL.f_name, CL.l_name, CL.phone, CL.email, UED.trans_id, CL.created 
FROM campaign_leads AS CL
LEFT JOIN user AS U ON CL.email = U.email
RIGHT JOIN user_edu_deposits AS UED ON UED.user_code = U.user_code
WHERE (STR_TO_DATE(CL.created, '%Y-%m-%d') BETWEEN '$from' AND '$to')
AND (STR_TO_DATE(UED.created, '%Y-%m-%d') BETWEEN '$from' AND '$to')
AND U.user_code IN (SELECT user_edu_deposits.user_code FROM user_edu_deposits)
AND UED.status = '3'
 GROUP BY CL.email";
                break;
            //FxAcademy - Forex Money Maker Course
            case "2" :
                $query = "SELECT CL.f_name, CL.l_name, CL.phone, CL.email, CL.created
FROM campaign_leads AS CL, user AS U
WHERE (STR_TO_DATE(CL.created, '%Y-%m-%d') BETWEEN '$from' AND '$to')
AND (STR_TO_DATE(CL.academy_signup, '%Y-%m-%d') BETWEEN '$from' AND '$to')
AND CL.email = U.email
AND U.academy_signup IS NOT NULL
AND U.user_code NOT IN (SELECT user_edu_deposits.user_code FROM user_edu_deposits)";
                break;
            default: $query = ""; break;
        }
        if($x == 1) {$result = $db_handle->numRows($query);}
        elseif($x == 2){$result = $db_handle->fetchAssoc($db_handle->runQuery($query));}
        return $result;
    }

    //Done
    public function sum_active_leads($from, $to, $x)
    {
        $from_month = explode('-', $from)[1];
        $to_month = explode('-', $to)[1];
        global $db_handle;
        $query = "SELECT CL.f_name, CL.l_name, CL.phone, CL.email, TC.date_earned AS last_trade_date, CL.created
FROM campaign_leads AS CL
LEFT JOIN user AS U ON CL.email = U.email
LEFT JOIN user_ifxaccount AS UI ON U.user_code = UI.user_code
LEFT JOIN trading_commission AS TC ON UI.ifx_acct_no = TC.ifx_acct_no
WHERE (STR_TO_DATE(TC.date_earned) BETWEEN $from AND $to)
AND (STR_TO_DATE(CL.created) BETWEEN $from AND $to)
GROUP BY CL.email";
        if($x == 1) {$result = $db_handle->numRows($query);}
        elseif($x == 2){$result = $db_handle->fetchAssoc($db_handle->runQuery($query));}
        return $result;
    }

    public function get_lead_registration_comment($selected_id) {
        global $db_handle;
        $query = "SELECT fctcc.comment, fctcc.created, CONCAT(a.last_name, SPACE(1), a.first_name) AS admin_name
                  FROM campaign_lead_comments AS fctcc
                  INNER JOIN admin AS a ON fctcc.admin_code = a.admin_code
                  WHERE fctcc.lead_id = $selected_id ORDER BY fctcc.created DESC";
        $result = $db_handle->runQuery($query);
        $fetched_data = $db_handle->fetchAssoc($result);

        return $fetched_data ? $fetched_data : false;
    }

    //Done
    public function sum_none_active($from, $to, $type, $x)
    {
        global $db_handle;
        $client_operation = new clientOperation();
        switch ($type)
        {
            //Training Leads - Start The Training
            case "1" :
                $query = "SELECT CL.f_name, CL.l_name, CL.phone, CL.email, CL.created 
FROM campaign_leads AS CL, user AS U 
WHERE U.academy_signup IS NULL
AND CL.email = U.email
AND CL.interest = '1'
 GROUP BY CL.email";
                $result = $db_handle->fetchAssoc($db_handle->runQuery($query));
                break;
            //ILPR Leads - Open ILPR Account
            case "2" :
                $query = "SELECT CL.f_name, CL.l_name, CL.phone, CL.email, CL.created, U.user_code FROM campaign_leads AS CL, user AS u WHERE CL.email = U.email AND CL.interest = '2'";
                $result = $db_handle->fetchAssoc($db_handle->runQuery($query));
                foreach($result as $key => $value) {
                    $client_ilpr_account = $client_operation->get_client_ilpr_accounts_by_code($value['user_code']);
                    if(!empty($client_ilpr_account)) {unset($result[$key]);}}
                break;
            default: $query = ""; break;
        }
        if($x == 1){ $x = count($result); }
        else if($x == 2){ $x = $result; }
        return $x;
    }

}
$obj_loyalty_training = new Loyalty_Training();