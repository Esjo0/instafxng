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
            $text_message = "This is the sms";
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
           
            <p>We have been around for over 7 years providing Forex services to thousands of Nigerian traders, 
            ensuring that their deposit and withdrawal transactions are promptly responded to and that every 
            challenge is totally resolved.</p>
            <p>InstaForex Nigeria is built upon seven strong foundational values; Integrity, Commitment, Speed, 
            Focus, Empathy, Reliability, and Innovation.</p>
            <p>From making deposit into your account (locally and easily) to instant stress free withdrawals (to your bank account) to unmatched customer support, we have you covered. </p>
            <p>We are dedicated to working effectively to ensure swift service delivery to you consistently.</p>
            <p>To start your journey to earning more money in our Loyalty Rewards Program, kindly click <a href="https://instafxng.com/live_account.php?id=lp">here</a> to open an InstaForex account immediately.</p>
            <p>Very shortly you're going to receive a call welcoming you on board from our ever 
            cheerful customer service representatives who will assist you in opening an InstaForex 
            account so you can get started immediately.</p>
            <p>Don’t forget to click <a href="https://instafxng.com/live_account.php?id=lp">here</a> to open your InstaForex account. You can also click <a href="https://instafxng.com/deposit_funds.php">here</a> to fund your account so you can get started immediately.</p>
            <p>I’m so glad you are here and I can’t wait for you to start earning from your trades and in the InstaFxNg Loyalty Points and Reward Program.</p>
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
            $log_new_client = $client_operation->new_user_ordinary($client_full_name, $email_address, $phone_number, $assigned_account_officer);
            //...//
        }
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
        $query = "INSERT INTO prospect_ilpr_biodata (f_name, l_name, m_name, email, phone) VALUE ('$first_name', '$last_name', '$middle_name', '$email_address', '$phone_number')";
        $result = $db_handle->runQuery($query);
        if($result)
        {
            $text_message = "This is the sms";
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
           
            <p>We have been around for over 7 years providing Forex services to thousands of Nigerian traders, 
            ensuring that their deposit and withdrawal transactions are promptly responded to and that every 
            challenge is totally resolved.</p>
            <p>InstaForex Nigeria is built upon seven strong foundational values; Integrity, Commitment, Speed, 
            Focus, Empathy, Reliability, and Innovation.</p>
            <p>From making deposit into your account (locally and easily) to instant stress free withdrawals (to your bank account) to unmatched customer support, we have you covered. </p>
            <p>We are dedicated to working effectively to ensure swift service delivery to you consistently.</p>
            <p>To start your journey to earning more money in our Loyalty Rewards Program, kindly click <a href="https://instafxng.com/live_account.php?id=lp">here</a> to open an InstaForex account immediately.</p>
            <p>Very shortly you're going to receive a call welcoming you on board from our ever 
            cheerful customer service representatives who will assist you in opening an InstaForex 
            account so you can get started immediately.</p>
            <p>Don’t forget to click <a href="https://instafxng.com/live_account.php?id=lp">here</a> to open your InstaForex account. You can also click <a href="https://instafxng.com/deposit_funds.php">here</a> to fund your account so you can get started immediately.</p>
            <p>I’m so glad you are here and I can’t wait for you to start earning from your trades and in the InstaFxNg Loyalty Points and Reward Program.</p>
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
        }
    }

}
$obj_loyalty_training = new Loyalty_Training();