<?php

class Loyalty_Training
{
    public function is_duplicate_training($email_address, $phone_number)
    {
        global $db_handle;
        $query = "SELECT * FROM free_training_campaign WHERE phone = '$phone_number' OR email = '$email_address' ";
        return $db_handle->numRows($query) ? true : false;
    }

    public function add_training($first_name, $last_name, $email_address, $phone_number)
    {
        global $db_handle;
        global $system_object;
        $query = "INSERT INTO free_training_campaign (first_name, last_name, email, phone, campaign_period) VALUE ('$first_name', '$last_name', '$email_address', '$phone_number', '2')";
        $result = $db_handle->runQuery($query);
        $inserted_id = $db_handle->insertedId();
        if ($result)
        {
            $text_message = "Thank you for your interest in making more money from Forex trading. Kindly click here http://bit.ly/2DK2L0l to proceed to the next step. You are just one click away!";
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
            $query = "UPDATE free_training_campaign SET attendant = $assigned_account_officer WHERE free_training_campaign_id = $inserted_id LIMIT 1";
            $db_handle->runQuery($query);
            // create profile for this client
            $client_operation = new clientOperation();
            $client_operation->new_user_ordinary($first_name." ".$last_name, $email_address, $phone_number, $assigned_account_officer);

            return true;
        }
        else
        {return false;}
    }

    public function is_duplicate_loyalty($email_address, $phone_number)
    {
        global $db_handle;
        $query = "SELECT * FROM prospect_ilpr_biodata WHERE phone = '$phone_number' OR email = '$email_address' ";
        return $db_handle->numRows($query) ? true : false;
    }

    public function add_loyalty($first_name, $last_name, $email_address, $phone_number)
    {
        global $db_handle;
        global $system_object;
        $query = "INSERT INTO prospect_ilpr_biodata (f_name, l_name, email, phone) VALUE ('$first_name', '$last_name', '$email_address', '$phone_number')";
        $result = $db_handle->runQuery($query);
        if($result)
        {
            $text_message = "Thank you for your interest in making more money from Forex trading. Kindly click here http://bit.ly/2DK2L0l to proceed to the next step. You are just one click away!";
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

    public function log_lead($email)
    {
        $filepath = "admin".DIRECTORY_SEPARATOR."logs".DIRECTORY_SEPARATOR."facebook_generated_leads.txt";
        if(!file_exists($filepath)){mkdir("logs");}
        $leads = file_get_contents($filepath);
        $leads = explode(",",$leads);
        if(!in_array($email, $leads))
        {
            $new_log = fopen($filepath, 'a');
            $log = $email.",";
            fwrite($new_log, $log);
            fclose($new_log);
        }
    }

}
$obj_loyalty_training = new Loyalty_Training();