<?php
require_once 'init/initialize_general.php';
$session_careers->logout();
redirect_to("careers_login.php?logout=1");