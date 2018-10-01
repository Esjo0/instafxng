<?php
/**
 * Make all settings constants - available all through the system
 */
$query = "SELECT * FROM system_setting";
$result = $db_handle->runQuery($query);
$settings = $db_handle->fetchAssoc($result);
foreach ($settings as $row => $value) {
    defined($value['constant']) ? null : define($value['constant'], $value['value']);
}

$nigeria_states = array('FCT - Abuja','');
unset($query);



$client_group_DEFAULT = array(
    "1" => "All Clients",
    "2" => "Last Month New Clients",
    "3" => "Free Training Campaign Clients",
    "4" => "Level 1 Clients",
    "5" => "Level 2 Clients",
    "6" => "Level 3 Clients",
    "7" => "Unverified Clients",
    "8" => "Dinner Clients",
    "9" => "Lagos Clients",
    "10" => "Online Training Students",
    "11" => "Lekki Training Students",
    "12" => "Diamond Training Students",
    "13" => "Past Forum Participants",
    "14" => "Clients Interested in Training",
    "15" => "Clients Interested in Funding",
    "16" => "Clients Interested in Bonuses",
    "17" => "Clients Interested in Investment",
    "18" => "Clients Interested in Services",
    "19" => "Clients Interested in Other Things",
    "20" => "Last Month Funding Clients",
    "21" => "Pencil Unbroken Reg",
    "22" => "In-house Test",
    "23" => "Top 20 Rank in Current Loyalty Year",
    "24" => "Career Application Submitted",
    "25" => "Top Traders",
    "26" => "Prospect - Pencil Comedy Event",
    "27" => "Prospect - 500 USD No-Deposit",
    "28" => "Online Trainee - Not Started",
    "29" => "Point Winners (Dec '16 - Oct '17)",
    "30" => "Commission Clients (Dec '16 - Oct '17)",
    "31" => "Online Training - Completed Course 1",
    "32" => "2017 Dinner Guests",
    "33" => "Office Funding Clients",
    "34" => "Failed SMS Clients",
    "35" => "Students Category 1",
    "36" => "Students Category 2",
    "37" => "Students Category 3",
    "38" => "Students Category 4",
    "39" => "3 Months Active Clients",
    "40" => "6 Months Active Clients",
    "41" => "12 Months Active Clients",
    "42" => "3 Months Inactive Clients",
    "43" => "6 Months Inactive Clients",
    "44" => "12 Months Inactive Clients",
    "45" => "Non-ILPR Clients*Clients",
    "46" => "Article Readers (Visitors)",
    "47" => "Last Months New Clients with Accounts",
    "48" => "Last Months New Clients without Accounts and have no Training",
    "49" => "Last Months New Clients without Accounts and have Training",
    "50" => "Last Months New Clients not yet funded above $50",
    "51" => "Clients that funded before March 22, 2018 and haven't funded till date.",
    "52" => "April 2018 ILPR campaign leads",
	"53" => "All clients who funded their accounts last month",
	"54" => "All clients who withdrew from the accounts last month",
    "55" => "All inactive clients before May 1 2018.",
	"56" => "All Clients Who have used WebPay for Deposit Transaction",
	"57" => "All signal user",
    "58" => "VIP Clients",
	"59" => "Independence Contest Participants"
	
);
$client_group_DESC = array(
    "1" => "*Consist of all clients that have at anytime come into our system.",
    "2" => "*All clients that were been added to our system in the last month.",
    "3" => "*All clients intrested in training and are currently undergoing training",
    "4" => "*Clients who have submitted only their emails and phone number.",
    "5" => "*Clients who have completed all document verification.",
    "6" => "*Clients who have registered under our loyality program",
    "7" => "*Clients who havn't taken any action on our system",
    "8" => "*Consist of all the clients who have attened any of our end of the year dinner",
    "9" => "*All clients with address in Lagos state",
    "10" => "*All clients undergoing Forex training on our system",
    "11" => "*Online training students Taking their offline training at the Lekki office ",
    "12" => "*Online training students Taking their offline training at the Diamond Estate Office",
    "13" => "*All participants of the past monthly traders forum",
    "14" => "*Customer Service week 2017",
    "15" => "*Customer Service week 2017",
    "16" => "*Customer Service week 2017",
    "17" => "*Customer Service week 2017",
    "18" => "*Customer Service week 2017",
    "19" => "*Customer Service week 2017",
    "20" => "*All clients who funded in the last month.",
    "21" => "*Clients who attended pencil unbroken",
    "22" => "*User details used for in house test run ",
    "23" => "*Consist of the first 20 clients in the loyality program for the running year",
    "24" => "*List of those who have submitted job application.",
    "25" => "Top Traders*Active clients withing the space of two weeks",
    "26" => "*Prospective clients from Pencil Unbroken event.",
    "27" => "*Prospective clients who have claimed InstaForex Russia 500 USDollar No deposit bonus.",
    "28" => "*Consist of those who have registered for online training but havnt started",
    "29" => "*Clients who had at one pointor the other won out of the monthly ILPR $500 Bonus within Dec'16 to Oct'17",
    "30" => "*Clients who have earned trading commissions from with Dec'16 to Oct'17",
    "31" => "*Clients who have started the training and are done with the Forex Money maker course",
    "32" => "*All guest at 2017 end of the year Dinner",
    "33" => "*Client that come to our office premises to fund their instaforex account",
    "34" => "*List of clients who dnt recieve SMS sent from our system",
    "35" => "*Forex Profit Academy clients who havn't started the training at all but have singned in with their emails",
    "36" => "*Forex Profit Academy clients who havn't progress past the Lesson 1-4 of the Forex money Maker course",
    "37" => "*Forex Profit Academy clients who are at Lesson 5 of the Forex money maker course",
    "38" => "*Forex Profit Academy clients who have PAID for the Forex Profit Optimizer course",
    "39" => "*Consist of clients who have funded and traded the required Volume in the last Three Months.",
    "40" => "*Consist of clients who have funded and traded the required Volume in the last Six Months.",
    "41" => "*Consist of clients who have funded and traded the required Volume in the last Twelve Months.",
    "42" => "*Consist of clients who have NOT funded and traded the required Volume in the last Three Months.",
    "43" => "*Consist of clients who have NOT funded and traded the required Volume in the last Six Months.",
    "44" => "*Consist of clients who have NOT funded and traded the required Volume in the last Twelve Months.",
    "45" => "*Clients Not registered under our Loyality promo",
    "46" => "*Any one who comments on the article section",
    "47" => "*All clients who came into the system last month and have opened Instaforex accounts",
    "48" => "*All clients who came into the system last month who have NOT opened Instaforex accounts and have not gone through our training ",
    "49" => "*All clients who came into the system last month, have opened Instaforex accounts and have undergone online training",
    "50" => "*All New clients who havn't funded up to $50",
    "51" => "Clients that funded before March 22, 2018 and haven't funded till date.",
    "52" => "*Campaign leads who came into our system in April 2018",
	"53" => "All clients who made deposit in the Just concluded month",
	"54" => "All clients who made withdrawals in the Just concluded month",
    "55" => "All inactive clients before May 1 2018.",
	"56" => "Clients who have funded their forex accounts with instant card transfer using their ATM cards",
	"57" => "Consists of all individuals who have ever used the signals before",
    "58" => "VIP Clients: clients with at least a single completed deposit transaction worth $1000 and above.",
	"59" => "List Of all clients who opt in to participate in the independence contest."
    );
