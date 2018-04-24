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
    "1" => "All Clients*Consist of all clients that have at anytime come into our system.",
    "2" => "Last Month New Clients*All clients that were been added to our system in the last month.",
    "3" => "Free Training Campaign Clients*All clients intrested in training and are currently undergoing training",
    "4" => "Level 1 Clients*Clients who have submitted only their emails and phone number.",
    "5" => "Level 2 Clients*Clients who have completed all document verification.",
    "6" => "Level 3 Clients*Clients who have regitered under our loyality program",
    "7" => "Unverified Clients*Clients who havn't taken any action on our system",
    "8" => "Dinner Clients*Consist of all the clients who have attened any of our end of the year dinner",
    "9" => "Lagos Clients*All clients with address in Lagos state",
    "10" => "Online Training Students*All clients undergoing Forex training on our system",
    "11" => "Lekki Training Students*Online training students Taking their offline training at the Lekki office ",
    "12" => "Diamond Training Students*Online training students Taking their offline training at the Diamond Estate Office",
    "13" => "Past Forum Participants*All participants of the past monthly traders forum",
    "14" => "Clients Interested in Training*Customer Service week 2017",
    "15" => "Clients Interested in Funding*Customer Service week 2017",
    "16" => "Clients Interested in Bonuses*Customer Service week 2017",
    "17" => "Clients Interested in Investment*Customer Service week 2017",
    "18" => "Clients Interested in Services*Customer Service week 2017",
    "19" => "Clients Interested in Other Things*Customer Service week 2017",
    "20" => "Last Month Funding Clients*All clients who funded in the last month.",
    "21" => "Pencil Unbroken Reg*Clients who attended pencil unbroken",
    "22" => "In-house Test*User details used for in house test run ",
    "23" => "Top 20 Rank in Current Loyalty Year",
    "24" => "Career Application Submitted*List of those who have submitted job application.",
    "25" => "Top Traders*Active clients withing the space of two weeks",
    "26" => "Prospect - Pencil Comedy Event*Prospective clients from Pencil Unbroken event.",
    "27" => "Prospect - 500 USD No-Deposit*Prospective clients who have claimed InstaForex Russia 500 USDollar No deposit bonus.",
    "28" => "Online Trainee - Not Started*Consist of those who have registered for online training but havnt started",
    "29" => "Point Winners (Dec '16 - Oct '17)*Clients who had at one pointor the other won out of the monthly ILPR $500 Bonus within Dec'16 to Oct'17",
    "30" => "Commission Clients (Dec '16 - Oct '17)*Clients who have earned trading commissions from with Dec'16 to Oct'17",
    "31" => "Online Training - Completed Course 1*Clients who have started the training and are done with the Forex Money maker course",
    "32" => "2017 Dinner Guests*All guest at 2017 end of the year Dinner",
    "33" => "Office Funding Clients*Client that come to our office premises to fund their instaforex account",
    "34" => "Failed SMS Clients*List of clients who dnt recieve SMS sent from our system",
    "35" => "Students Category 1*Forex Profit Academy clients who havn't started the training at all but have singned in with their emails",
    "36" => "Students Category 2*Forex Profit Academy clients who havn't progress past the Lesson 1-4 of the Forex money Maker course",
    "37" => "Students Category 3*Forex Profit Academy clients who are at Lesson 5 of the Forex money maker course",
    "38" => "Students Category 4*Forex Profit Academy clients who have PAID for the Forex Profit Optimizer course",
    "39" => "3 Months Active Clients*Consist of clients who have funded and traded the required Volume in the last Three Months.",
    "40" => "6 Months Active Clients*Consist of clients who have funded and traded the required Volume in the last Six Months.",
    "41" => "12 Months Active Clients*Consist of clients who have funded and traded the required Volume in the last Twelve Months.",
    "42" => "3 Months Inactive Clients*Consist of clients who have NOT funded and traded the required Volume in the last Three Months.",
    "43" => "6 Months Inactive Clients*Consist of clients who have NOT funded and traded the required Volume in the last Six Months.",
    "44" => "12 Months Inactive Clients*Consist of clients who have NOT funded and traded the required Volume in the last Twelve Months.",
    "45" => "Non-ILPR Clients*Clients Not registered under our Loyality promo",
    "46" => "Article Readers (Visitors)*Any one who comments on the article section",
    "47" => "Last Months New Clients with Accounts*All clients who came into the system last month and have opened Instaforex accounts",
    "48" => "Last Months New Clients without Accounts and have no Training*All clients who came into the system last month who have NOT opened Instaforex accounts and have not gone through our training ",
    "49" => "Last Months New Clients without Accounts and have Training*All clients who came into the system last month, have opened Instaforex accounts and have undergone online training",
    "50" => "Last Months New Trainee Still in course 2 in current month*All clients who came into the system last month undergone training and are in course two",
    "51" => "Last Months New Clients not yet funded above $50*All New clients who havn't funded up to $50"


);