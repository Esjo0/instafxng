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
    "29" => "Lagos Clients"
);