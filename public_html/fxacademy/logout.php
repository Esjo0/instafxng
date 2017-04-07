<?php
require_once '../init/initialize_client.php';
$session_client->logout();
redirect_to("login.php?logout=1");