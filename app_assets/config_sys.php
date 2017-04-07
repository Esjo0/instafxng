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
    "12" => "Diamond Training Students"
);