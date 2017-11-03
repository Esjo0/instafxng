<?php
require_once '../init/initialize_general.php';
require_once("../init/initialize_admin.php");

$query = "SELECT user_code FROM user ORDER BY RAND() LIMI 1 ";
$result = $db_handle->runQuery($query);
$result = $db_handle->fetchAssoc($result);
$result = $result[0];
echo "https://instafxng.com/dinner_2017/dinner_2017_landing_page.php?id=".encrypt($result);