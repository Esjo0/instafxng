<?php
require_once '../init/initialize_client.php';
$session_client->logout();
unset($_SESSION['category3']);
redirect_to("login.php?logout=1");